<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Base Model Class
 *
 *	Change Log:
 *		
 *      2014-10-06  Luke Stuff   Creation
 */

class Base_model extends CI_Model {
	
	public $table_name = null;

	public function __construct(){
		parent::__construct();
		if($this->table_name == NULL){
			log_message('error', "Table name not set for class ".get_class($this));
		}
        $this->load->database();
	}

	 public function get_all()
    {
        $result = $this->db->query('SELECT * FROM '.$this->table_name);
        return $result->result();
    }

    public function get($id)
    {
        $result = $this->db->query('SELECT * FROM '.$this->table_name.' WHERE id = ?', $id);
        return $result->row();
    }

    public function get_in($ids){
    	if(!is_array($ids)){
    		$ids = array($ids);
    	}
        $this->db->where_in('id', $ids);
    	$result = $this->db->get($this->table_name);
    	return $result->result();
    }

    // accepts an array of options in the form array( '%param% %comparison% ?' => '%value%' ), ie  array('id IN (?)' => '1,5,7', 'level >= ?', '5')
    public function get_with_criteria($criteria, $op = 'AND'){
    	if(!is_array($criteria) || count($criteria) == 0){
        	log_message('error','Criteria provided must be an array with at least one value. Is it currently - '.$criteria);
        	return false;
    	}

    	$params = array();
    	$values = array();
    	foreach($criteria as $param => $value){
    		if($param){
    			/*$operator_check = strpos($param, 'AND');
    			if($operator_check != 0 && $operator_check != 1){
    				$operator_check = strpos($param, 'OR');
    				if($operator_check != 0 && $operator_check != 1){
    					// No starting operator so add the default
    					$param = $default_op.' '.$param;
    				}
    			}*/
    			$params[] = $param;
    			if($value){
    				$values[] = $value;
    			}
    		}
    	}
    	$param_list = implode(' '.$op.' ', $params);
    	$sql = 'SELECT * FROM '.$this->table_name.' WHERE '.$param_list;
    	log_message('debug', 'Executing get_with_criteria - '.sql);

    	$result = $this->db->query($sql, $values);
    	return $result->result();
    }


    public function create($data)
    {
        global $user;
        if(isset($data->id)){
            unset($data->id);
        }
    	$data = $this->_filter_data($this->table_name, $data);


    	if(!isset($data['created'])){
    		$data['created'] = date('Y-m-d H:i:s');
    	}

        // insert the new entry
        $this->db->insert($this->table_name, $data);
        $id = $this->db->insert_id();

        if($id){
        	log_message('debug','Successfully created '.$this->table_name);
            $data['id'] = $id;

            if($user) {
                $action = [
                    'admin_user_id' => $user->id,
                    'status' => 'success',
                    'status_message' => '',
                    'table_effected' => $this->table_name,
                    'action_type' => 'create',
                    'entry_id' => $id,
                    'pre_data' => '',
                    'post_data' => json_encode($data),
                ];
                $this->db->insert('admin_action_log', $action);
            }
        }
        else{
        	log_message('warning','Failed to create in '.$this->table_name.' with Error: '.$this->db->_error_message());

            if($user) {
                $action = [
                    'admin_user_id' => $user->id,
                    'status' => 'failed',
                    'status_message' => $this->db->_error_message(),
                    'table_effected' => $this->table_name,
                    'action_type' => 'create',
                    'entry_id' => '-1',
                    'pre_data' => '',
                    'post_data' => json_encode($data),
                ];
                $this->db->insert('admin_action_log', $action);
            }
        }
        return $id;
    }


    public function update($data)
    {
        global $user;
        $data = $this->_filter_data($this->table_name, $data);

        if(!isset($data['id']) || empty($data['id']) || !is_numeric($data['id'])){
        	// No id so we can't update, throw an error
        	log_message('error','Failed to update entry for table '.$this->table_name.' because no ID value was present - '.var_export($data, TRUE));
        	return false;
        }

    	$id = $data['id'];
    	unset($data['id']);
        $pre_data = $this->get($id);
    	$this->db->update($this->table_name, $data, array('id' => $id));

        if($this->db->affected_rows() == 0){
        	log_message('error','Failed to update on '.$this->table_name.' with Error: '.$this->db->_error_message());

            if($user) {
                $action = [
                    'admin_user_id' => $user->id,
                    'status' => 'failed',
                    'status_message' => $this->db->_error_message(),
                    'table_effected' => $this->table_name,
                    'action_type' => 'update',
                    'entry_id' => $id,
                    'pre_data' => json_encode($pre_data),
                    'post_data' => json_encode($data),
                ];
                $this->db->insert('admin_action_log', $action);
            }
        	return false;
        }

        log_message('debug','Successfully updated on '.$this->table_name);

        if($user) {
            $action = [
                'admin_user_id' => $user->id,
                'status' => 'success',
                'status_message' => '',
                'table_effected' => $this->table_name,
                'action_type' => 'update',
                'entry_id' => $id,
                'pre_data' => json_encode($pre_data),
                'post_data' => json_encode($data),
            ];
            $this->db->insert('admin_action_log', $action);
        }
        return true;
    }


    public function delete($id)
    {
        global $user;
        if(!$id || empty($id) || !is_numeric($id)){
        	log_message('error','Failed to update entry for table '.$this->table_name.' because no ID value was present - '.var_export($id, TRUE));
            return false;
        }

        $this->db->trans_begin();

        $pre_data = $this->get($id);
        $this->db->delete($this->table_name, array('id' => $id));

        if ($this->db->trans_status() === false)
        {
        	log_message('error','Failed to create '.$this->table_name.' with Error: '.$this->db->_error_message());
            $this->db->trans_rollback();

            if($user) {
                $action = [
                    'admin_user_id' => $user->id,
                    'status' => 'failed',
                    'status_message' => $this->db->_error_message(),
                    'table_effected' => $this->table_name,
                    'action_type' => 'update',
                    'entry_id' => $id,
                    'pre_data' => json_encode($pre_data),
                    'post_data' => '',
                ];
                $this->db->insert('admin_action_log', $action);
            }
            return false;
        }

        $this->db->trans_commit();

        log_message('debug','Successfully deleted from '.$this->table_name);

        if($user) {
            $action = [
                'admin_user_id' => $user->id,
                'status' => 'delete',
                'status_message' => '',
                'table_effected' => $this->table_name,
                'action_type' => 'update',
                'entry_id' => $id,
                'pre_data' => json_encode($pre_data),
                'post_data' => '',
            ];
            $this->db->insert('admin_action_log', $action);
        }
        return true;
    }

    protected function _filter_data($table, $data){
    	
        $filtered_data = array();
        $columns = $this->db->list_fields($table);

        if(is_object($data)){
        	$data_array = array();
        	foreach($data as $key => $value){
        		$data_array[$key] = $value;
        	}
        	$data = $data_array;
        }
        if(!is_array($data)){
        	return false;
        }
        foreach ($columns as $column){
            if (array_key_exists($column, $data)){
                $filtered_data[$column] = $data[$column];
            }
        }

        return $filtered_data;
    }
}