<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."models/base_model.php";

/*
 *  Model Class for Events
 *
 *  Change Log:
 *      
 *      2014-10-14  Luke Stuff   Creation
 */

class Event extends Base_model {

    public function __construct() {
        $this->table_name = 'event';
        parent::__construct();
    }

    public function get_for_climber($climber) {
    	$sql = 'SELECT event.* FROM event_climber JOIN event ON event_climber.event_id = event.id WHERE event_climber.climber_id = ' . $this->db->escape($climber);
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function get_current_for_climber($climber) {
    	$sql = 'SELECT event.* FROM event_climber JOIN event ON event_climber.event_id = event.id WHERE event.end_time > CURRENT_TIMESTAMP AND event_climber.climber_id = ' . $this->db->escape($climber);
        $result = $this->db->query($sql);
        return $result->result();
    }
}