<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."models/base_model.php";

/*
 *  Model Class for sends
 *
 *  Change Log:
 *      
 *      2014-10-15  Luke Stuff   Creation
 */

class Send extends Base_model {

    public function __construct() {
        $this->table_name = 'send';
        parent::__construct();
    }

    public function get_for_event_climber($event_climber_id) {
    	$sql = 'SELECT send.*, route.name, route.rating, route.trad, route.height, route.wall, route.safety_rating FROM send JOIN route ON send.route_id = route.id WHERE send.deleted IS NULL AND send.event_climber_id = ' . $this->db->escape($event_climber_id) . ' ORDER BY created ASC';
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function get_count_for_event_climber_route($event_climber_id, $route_id) {
    	$sql = 'SELECT count(*) as count FROM send WHERE deleted IS NULL AND event_climber_id = ' . $this->db->escape($event_climber_id) . ' AND route_id = ' . $this->db->escape($route_id);
        $result = $this->db->query($sql);
        return $result->row()->count;
    }

    public function get_for_event_climber_route($event_climber_id, $route_id) {
    	$sql = 'SELECT * FROM send WHERE deleted IS NULL AND event_climber_id = ' . $this->db->escape($event_climber_id) . ' AND route_id = ' . $this->db->escape($route_id);
        $result = $this->db->query($sql);
        return $result->result();
    }
}