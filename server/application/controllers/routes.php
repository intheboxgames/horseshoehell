<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."controllers/Base_Controller.php";

class Routes extends Base_Controller {

    function __construct(){
        $this->controller_name = 'routes';
        $this->requires_auth = false;
        parent::__construct();
    }

	public function index(){
        $this->_show_view('routes/routes', "Routes");
	}
}