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
    public $requires_auth = false;

    public function __construct(){
        parent::__construct();

        $this->load->library('Auth_library', null, 'auth');
        $this->load->library('Message_library', null, 'message');

        $this->init();
    }

    public function init(){

        log_message('debug', 'Initializing '.$this->controller_name);

        if(!$this->auth->logged_in() && $this->requires_auth){
            log_message('info', 'Redirecting to Login from '.$this->controller_name);
            redirect('/login');
        }

        $this->auth->set_global_user();

        global $user;
        if(!$user && $this->requires_auth){
            $this->message->add_error('Invalid User!');
            redirect('login');
        }
        else if($user) {
            $this->data['is_authed'] = true;
            $this->data['user'] = $user;
        }
        else {
            $this->data['is_authed'] = false;
            $this->data['user'] = null;
        }
    }


    protected function _show_view($view, $title){
        if(empty($this->data)){
            $this->data = array();
        }
        $this->data['title'] = $title;

        $content = $this->load->view($view, $this->data, true);

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            $output = $content;
        }
        else{
            $this->data['content'] = $content;
            $output = $this->load->view('common/main', $this->data, true);
        }

        $this->message->clear_messages('all');

        $this->output->set_output($output);
    }
}