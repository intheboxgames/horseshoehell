<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."controllers/Base_Controller.php";

class Manage extends Base_Controller {

    function __construct(){
        $this->controller_name = 'manage';
        $this->requires_auth = true;

        $this->sidebar = array(
            'walls' => array('text' => "Walls", 'link' => 'manage/walls', 'active' => false),
            'routes' => array('text' => "Routes", 'link' => 'manage/routes', 'active' => false),
            'teams' => array('text' => "Teams", 'link' => 'manage/teams', 'active' => false),
        );
        parent::__construct();
    }

    public function index() {
        $this->_show_view('manage/manage', "Manage");
    }

    public function walls($action = 'view') {

        $this->load->model("wall");

        $this->sidebar['walls']['active'] = true;

        switch($action) {
            case 'add':
                $wall = new stdClass();
                $wall->name = $this->input->post('name');
                $wall->side = $this->input->post('side');

                $wall->id = $this->wall->create($wall);
                if(!$wall->id) {
                    $this->message->add_error("There was a problem saving the wall. Please try again.");
                }
                else {
                    $this->message->add_success("Wall successfully added");
                }
                redirect(base_url("manage/walls"));
                break;
            case 'edit':
                $wall = new stdClass();
                $wall->id = $this->input->post('id');
                $wall->name = $this->input->post('name');
                $wall->side = $this->input->post('side');

                if(!$this->wall->update($wall)) {
                    $this->message->add_error("There was a problem saving the wall. Please try again.");
                }
                else {
                    $this->message->add_success("Wall successfully updated");
                }
                redirect(base_url("manage/walls"));
                break;
            case 'view':
            default:
                $this->load->model("route");

                $wall_list = $this->wall->get_all();
                $route_count = $this->route->get_route_count_by_wall();

                $route_count_map = array();
                foreach($route_count as $route) {
                    $route_count_map[$route->wall] = $route->routes;
                }

                foreach($wall_list as $wall) {
                    $wall->route_count = !empty($route_count_map[$wall->id]) ? $route_count_map[$wall->id] : 0;
                }

                $this->data['wall_list'] = $wall_list;
                $this->_show_view('manage/walls', "Manage Walls");
                break;
        }
    }

    public function routes($action = 'view') {

        $this->load->model("route");
        $this->load->model("rating");
        $this->load->model("wall");

        $this->sidebar['routes']['active'] = true;

        switch($action) {
            case 'add':
                $route = new stdClass();
                $route->number = $this->input->post('number');
                $route->name = $this->input->post('name');
                $route->wall = $this->input->post('wall');
                $route->rating = $this->input->post('rating');
                $route->trad = $this->input->post('trad');
                $route->height = $this->input->post('height');
                $route->safety_rating = $this->input->post('safety_rating');
                $route->first_ascent = $this->input->post('first_ascent');
                $route->year = $this->input->post('year');
                $route->description = $this->input->post('description');
                $route->draws = $this->input->post('draws');
                $route->stars = $this->input->post('stars');

                $route->id = $this->route->create($route);
                if(!$route->id) {
                    $this->message->add_error("There was a problem saving the route. Please try again.");
                }
                else {
                    $this->message->add_success("Route successfully added");
                }
                redirect(base_url("manage/routes"));
                break;
            case 'edit':
                $route = new stdClass();
                $route->id = $this->input->post('id');
                $route->number = $this->input->post('number');
                $route->name = $this->input->post('name');
                $route->wall = $this->input->post('wall');
                $route->rating = $this->input->post('rating');
                $route->trad = $this->input->post('trad');
                $route->height = $this->input->post('height');
                $route->safety_rating = $this->input->post('safety_rating');
                $route->first_ascent = $this->input->post('first_ascent');
                $route->year = $this->input->post('year');
                $route->description = $this->input->post('description');
                $route->draws = $this->input->post('draws');
                $route->stars = $this->input->post('stars');

                if(!$this->route->update($route)) {
                    $this->message->add_error("There was a problem saving the route. Please try again.");
                }
                else {
                    $this->message->add_success("Route successfully updated");
                }
                redirect(base_url("manage/routes"));
                break;
            case 'view':
            default:
                $route_list = $this->route->get_all();
                $rating_list = $this->rating->get_all();
                $wall_list = $this->wall->get_all();

                $rating_map = array();
                foreach($rating_list as $rating) {
                    $rating_map[$rating->id] = $rating;
                }
                $wall_map = array();
                foreach($wall_list as $wall) {
                    $wall_map[$wall->id] = $wall;
                }

                foreach($route_list as $route) {
                    $route->wall_name = $wall_map[$route->wall]->name;
                    $route->rating_name = $rating_map[$route->rating]->name;
                }

                $this->data['route_list'] = $route_list;
                $this->data['rating_list'] = $rating_list;
                $this->data['wall_list'] = $wall_list;
                $this->_show_view('manage/routes', "Manage Routes");
                break;
        }
    }
}