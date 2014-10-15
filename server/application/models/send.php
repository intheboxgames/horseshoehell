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
    	$sql = 'SELECT send.*, route.name, route.rating, route.trad, route.height, route.wall, route.safety_rating FROM send JOIN route ON send.route_id = route.id WHERE send.event_climber_id = ' . $this->db->escape($event_climber_id) . ' ORDER BY created ASC';
        $result = $this->db->query($sql);
        return $result->result();
    }
}