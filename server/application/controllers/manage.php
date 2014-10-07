<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."controllers/Base_Controller.php";

class Manage extends Base_Controller {

    function __construct(){
        $this->controller_name = 'manage';
        $this->requires_auth = true;
        parent::__construct();
    }

	public function index() {
        $this->routes();
	}

    public function routes() {

        $this->load->model("route");
        //$this->load->model("rating");
        //$this->load->model("wall");

        $route_list = $this->route->get_all();
        //$rating_list = $this->rating->get_all();
        //$wall_list = $this->wall->get_all();
        log_message('debug', var_export($route_list, true));

        $this->data['route_list'] = $route_list;
        $this->_show_view('manage/routes', "Manage Routes");
    }
}