<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."models/base_model.php";

/*
 *  Model Class for Event Climbers
 *
 *  Change Log:
 *      
 *      2014-10-145 Luke Stuff   Creation
 */

class Event_Climber extends Base_model {

    public function __construct() {
        $this->table_name = 'event_climber';
        parent::__construct();
    }

    public function get_for_climber($climber_id, $event_id) {
    	$sql = 'SELECT * FROM event_climber WHERE climber_id = ' . $this->db->escape($climber_id) . ' AND event_id = ' . $this->db->escape($event_id);
        $result = $this->db->query($sql);
        return $result->row();
    }
}