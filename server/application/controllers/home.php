<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."controllers/Base_Controller.php";

class Home extends Base_Controller {

    function __construct(){
        $this->controller_name = 'home';
        $this->requires_auth = false;
        parent::__construct();
    }

	public function index(){
        $this->_show_view('home', "Home");
	}
}