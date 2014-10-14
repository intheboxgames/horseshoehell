<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."models/base_model.php";

/*
 *  Model Class for Routes
 *
 *  Change Log:
 *      
 *      2014-10-06  Luke Stuff   Creation
 */

class Route extends Base_model {

    public function __construct(){
        $this->table_name = 'route';
        parent::__construct();
    }

    public function get_route_count_by_wall() {
    	$sql = 'SELECT wall, COUNT(*) as routes from '.$this->table_name.' GROUP BY wall';
        $result = $this->db->query($sql);
        return $result->result();
    }
}