<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."models/base_model.php";

/*
 *  Model Class for Device
 *
 *  Change Log:
 *      
 *      2014-10-06  Luke Stuff   Creation
 */

class Admin_User extends Base_model {

    public function __construct(){
        $this->table_name = 'admin_user';
        parent::__construct();

        $this->load->helper('cookie');
        $this->load->helper('date');

        $this->hash_method = $this->config->item('hash_method');
        $this->auth_rounds = $this->config->item('auth_rounds');
        $this->min_password_length = $this->config->item('min_password_length');
        $this->max_password_length = $this->config->item('max_password_length');
        $this->remember_users = $this->config->item('remember_users');
        $this->user_expire = $this->config->item('user_expire');
        $this->user_extend_on_login = $this->config->item('user_extend_on_login');
        $this->track_login_ip_address = $this->config->item('track_login_ip_address');
        $this->forgot_password_expiration = $this->config->item('forgot_password_expiration');
        $this->salt_length = $this->config->item('salt_length');
        $this->store_salt = $this->config->item('store_salt');
    }
    
    public function hash_password($password, $salt = false){
        
        if (empty($password)){
            $this->message->add_error('invalid_arg');
            return false;
        }

        if($this->store_salt && $salt){
            return  sha1($password . $salt);
        }
        else{
            $salt = $this->salt();
            return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }
        
    public function hash_password_db($id, $password){
        if(empty($id) || empty($password)){
            $this->message->add_error('invalid_arg');
            return false;
        }

        $query = $this->db->select('password, salt')
                          ->where('id', $id)
                          ->limit(1)
                          ->get('admin_user');

        $hash_password_db = $query->row();

        if($query->num_rows() !== 1){
            return false;
        }

        // sha1
        if($this->store_salt){
            $db_password = sha1($password . $hash_password_db->salt);
        }
        else{
            $salt = substr($hash_password_db->password, 0, $this->salt_length);
            $db_password =  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }

        if($db_password == $hash_password_db->password){
            return true;
        }
        else{
            return false;
        }
    }

    public function hash_code($password)
    {
        return $this->hash_password($password, false, true);
    }

    public function salt()
    {
        return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
    }

    public function change_password($email, $new)
    {
        $query = $this->db->select('id, password, salt')
                          ->where('email', $email)
                          ->limit(1)
                          ->get('admin_user');

        if ($query->num_rows() !== 1)
        {
            return false;
        }

        $user = $query->row();

        $salt = (empty($user->salt) || strlen($user->salt) == 0) ? ($this->store_salt ? $this->salt() : false) : $user->salt;
        $hashed_new_password  = $this->hash_password($new, $salt);
        $data = array(
            'password' => $hashed_new_password,
            'salt' => $salt,
        );

        return $this->db->update('admin_user', $data, array('email' => $email));
    }

    public function email_check($email = '')
    {
        if (empty($email))
        {
            $this->message->add_error('Please provide an email');
            return false;
        }

        return $this->db->where('email', $email)
                        ->count_all_results('admin_user') > 0;
    }

    public function register($email, $password, $first_name, $last_name, $role)
    {
        if ($this->email_check($email))
        {
            $this->message->add_error('That email already exists, please use a different one');
            return false;
        }

        $salt = $this->salt();
        if($password) {
            $password = $this->hash_password($password, $salt);
        }

        // Users table.
        $data = array(
            'email' => $email,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'role' => $role,
            'salt' => $salt,
            'created' => time(),
            'last_login' => time(),
        );

        //filter out any data passed that doesnt have a matching column in the users table
        $user_data = $this->_filter_data('admin_user', $data);

        $this->db->insert('admin_user', $user_data);

        $id = $this->db->insert_id();

        return (isset($id)) ? $id : false;
    }


    public function login($email, $password, $remember = false) {
        if (empty($email) || empty($password)) {
            $this->message->add_error('Login Failed, please provide both an email and password.');
            return false;
        }

        $query = $this->db->select('*')
                          ->where('email', $this->db->escape_str($email))
                          ->limit(1)
                          ->get('admin_user');

        if ($query->num_rows() === 1) {
            $user = $query->row();

            $password = $this->hash_password_db($user->id, $password);

            if ($password === true) {
                $this->set_session($user);

                $this->update_last_login($user->id);

                if ($remember && $this->config->item('remember_users')) {
                    $this->remember_user($user->id);
                }

                $this->message->add_success('Login Successful. Welcome '.$user->first_name." ".$user->last_name.".");

                return true;
            }
        }

        //Hash something anyway, just to take up time
        $this->hash_password($password);

        $this->message->add_error('Login Failed. That email/password was not found.');

        return false;
    }

    public function get_all($limit = 0, $offset = 0){
        if($limit > 0){
            $result = $this->db->query('SELECT * FROM admin_user LIMIT ?, ?', $offset, $limit);
        }
        else{
            $result = $this->db->get('admin_user');
        }
        return $result->result();
    }

    public function get_by_email($email){
        $result = $this->db->query('SELECT * FROM admin_user WHERE email = ?', $email);
        return $result->row();
    }

    public function get($id){
        $result = $this->db->query('SELECT * FROM admin_user WHERE id = ?', $id);
        return $result->row();
    }

    public function update($id, $data){

        if(empty($id) || empty($data)){
            $this->message->add_error('invalid_arg');
            return false;
        }

        $user = $this->get($id);

        $this->db->trans_begin();

        // Filter the data passed
        $data = $this->_filter_data('admin_user', $data);

        if (array_key_exists('password', $data)) {
            if(!empty($data['password'])) {
                $data['password'] = $this->hash_password($data['password'], $user->salt);
            }
            else {
                // unset password so it doesn't effect database entry if no password passed
                unset($data['password']);
            }
        }

        $this->db->update('admin_user', $data, array('id' => $user->id));

        if ($this->db->trans_status() === false){
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();

        return true;
    }


    public function delete($id)
    {
        $this->db->trans_begin();

        // delete roles
        $this->db->delete('user_role', array('user_id' => $id));

        // delete user
        $this->db->delete('admin_user', array('id' => $id));

        // if user does not exist in database then it returns false else removes the user from groups
        if ($this->db->affected_rows() == 0)
        {
            return false;
        }

        if ($this->db->trans_status() === false)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();

        return true;
    }


    public function update_last_login($id)
    {
        $this->db->update('admin_user', array('last_login' => date('Y-m-d H:i:s')), array('id' => $id));
        return $this->db->affected_rows() == 1;
    }


    public function set_lang($lang = 'en')
    {
        $this->trigger_events('set_lang');

        // if the user_expire is set to zero we'll set the expiration two years from now.
        $expire = ($this->user_expire === 0) ? (60*60*24*365*2) : $this->user_expire;

        set_cookie(array(
                'name'   => 'lang_code',
                'value'  => $lang,
                'expire' => $expire
        ));

        return true;
    }


    public function set_session($user) {
        $session_data = array(
            'email'                => $user->email,
            'user_id'              => $user->id, //everyone likes to overwrite id so we'll use user_id
            'old_last_login'       => $user->last_login
        );

        $this->session->set_userdata($session_data);
        return true;
    }


    public function remember_user($id) {
        if (!$id){
            return false;
        }

        $user = $this->get($id);

        $salt = sha1($user->password);

        $this->db->update('admin_user', array('remember_code' => $salt), array('id' => $id));

        if ($this->db->affected_rows() > -1)
        {
            // if the user_expire is set to zero we'll set the expiration two years from now.
            $expire = ($this->user_expire === 0) ? (60*60*24*365*2) : $this->user_expire;

            set_cookie(array(
                'name'   => 'email',
                'value'  => $user->email,
                'expire' => $expire
            ));

            set_cookie(array(
                'name'   => 'remember_code',
                'value'  => $salt,
                'expire' => $expire
            ));
            return true;
        }
        return false;
    }


    public function login_remembered_user() {
        //check for valid data
        if (!get_cookie('email') || !get_cookie('remember_code') || !$this->email_check(get_cookie('email'))) {
            return false;
        }

        //get the user
        $query = $this->db->select('*')
                          ->where('email', get_cookie('email'))
                          ->where('remember_code', get_cookie('remember_code'))
                          ->limit(1)
                          ->get('users');

        //if the user was found, sign them in
        if ($query->num_rows() == 1)
        {
            $user = $query->row();
            $this->update_last_login($user->id);
            $this->set_session($user);

            //extend the users cookies if the option is enabled
            if ($this->user_extend_on_login)
            {
                $this->remember_user($user->id);
            }
            return true;
        }
        return false;
    }

    protected function _prepare_ip($ip_address) {
        return inet_pton($ip_address);
    }
}