<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *  Base Controller Class
 *
 *  Change Log:
 *      
 *      2014-10-06  Luke Stuff   Creation
 */
class Base_Controller extends CI_Controller{

    public $controller_name = null;
    public $is_authed = false;
    public $uid = 0;
    public $device_id = 0;

    public function __construct(){
        parent::__construct();

        $this->load->model('device');
        $this->load->model('user');

        $this->init();
    }

    public function init(){

        $this->is_authed = false;
        $this->load->library('user_agent');
        if(ENVIRONMENT == 'production' && $this->agent->agent_string() != "UnityPlayer/4.5.2f1 (http://unity3d.com)") {
            // Oh No! These requests should always come from unity player.
            $response = new stdClass();
            $response->status = 'not_authed';
            $this->send_response($response);
            return;
        }

        log_message('debug', 'Initializing '.$this->controller_name);
        if($this->controller_name && $this->controller_name == 'login'){
            return;
        }

        $this->set_global_user();
        
        if(!$this->logged_in()){
            log_message('debug', 'Redirecting to Login from '.$this->controller_name);
            $response = new stdClass();
            $response->status = "not_authed";
            $this->send_response($response);
            return;
        }

        global $user;
        if(!$user){
            log_message('error','Invalid User!');
            $response = new stdClass();
            $response->status = "not_authed";
            $this->send_response($response);
            return;
        }
    }
  
    public function logged_in() {
        return $this->is_authed != 0;
    }

    public function set_global_user(){
        $this->is_authed = false;
        global $user;
        if(ENVIRONMENT == 'development' && $this->input->get('token') != null) {
            $steam_id = $this->input->get('steam');
            $device_id = $this->input->get('token');
        }
        else {
            $steam_id = $this->input->post('steam');
            $device_id = $this->input->post('token');
        }
        $device = $this->device->find_by_device_id($device_id);
        if(is_null($device) || empty($device)) {
            log_message('error', 'Failed to find valid device for device id - ' . $device_id);
            return;
        }
        $user = $this->user->get($device->uid);
        if(is_null($user) || empty($user)) {
            log_message('error', 'Failed to find valid user for  uid - ' . $device->uid);
            return;
        }
        $user->device = $device;
        $this->uid = $user->id;
        $this->device_id = $device->device_id;
        $this->is_authed = true;
    }

    public function send_response($data) {

        $this->output->set_content_type("application/json");
        $this->output->set_output(json_encode($data));
    }

    public function current_session($uid) {

        $this->load->model("usersession");
        $current_session = $this->usersession->get_current($uid);

        // no current session so create a new one
        if(is_null($current_session) || empty($current_session)) {
            $current_session = $this->start_session($uid);
        }

        $session_end_time = strtotime($current_session->end_time);

        // Make sure this session is still valid, it's valid for 15 minutes
        if($session_end_time + (15 * 60) < gmmktime() ) {
            $current_session = $this->start_session($uid);
        }

        $current_session->end_time = gmdate('Y-m-d H:i:s');
        $current_session->is_valid = 1; // sessions aren't valid until being updated at least once
        return $current_session;
    }

    public function start_session($uid) {
        $this->load->model("usersession");
        $current_session = new stdClass();
        $current_session->uid = $uid;
        $current_session->start_time = gmdate('Y-m-d H:i:s');
        $current_session->end_time = gmdate('Y-m-d H:i:s');
        $current_session->is_valid = 0;
        $current_session->levels_played = 0;
        $current_session->id = $this->usersession->create($current_session);
        return $current_session;
    }
}