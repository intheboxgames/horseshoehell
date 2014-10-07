<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."controllers/Base_Controller.php";

class Login extends Base_Controller {

    function __construct(){
        $this->controller_name = 'login';
        $this->requires_auth = false;
        parent::__construct();
    }

	public function index() {
        $this->_show_view('login', "Login");
	}

	public function auth() {

        if ($this->auth->login($this->input->post('identity'), $this->input->post('password'), true)) {
            //if the login is successful
            //redirect them back to the home page
            redirect('/', 'refresh');
        }
        else {
            //if the login was un-successful
            //redirect them back to the login page
            redirect('/login', 'refresh');
        }
	}

	public function logout() {
		$this->auth->logout();
        redirect('/', 'refresh');
	}
}