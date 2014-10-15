<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."models/base_model.php";

/*
 *  Model Class for Climbers
 *
 *  Change Log:
 *      
 *      2014-10-14  Luke Stuff   Creation
 */

class Climber extends Base_model {

    public function __construct() {
        $this->table_name = 'climber';
        parent::__construct();
    }

    public function search($term) {
    	$term = preg_replace('/[^A-Za-z0-9\- ]/', '', $term);
    	$sql = 'SELECT * FROM '.$this->table_name.' WHERE first_name LIKE "%' . $term . '%" OR last_name LIKE "%' . $term . '%" OR CONCAT(first_name, " ", last_name) LIKE "%' . $term . '%"';
        $result = $this->db->query($sql);
        return $result->result();
    }
}