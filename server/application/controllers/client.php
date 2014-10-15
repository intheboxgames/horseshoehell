<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."controllers/Base_Controller.php";

class Client extends Base_Controller {

    function __construct(){
        $this->controller_name = 'client';
        $this->requires_auth = false;

        parent::__construct();
    }

    public function find_climber() {
        $this->load->model("climber");

        $first_name = $this->input->get('first_name');
        $last_name = $this->input->get('last_name');

        $climbers = $this->climber->search($first_name . ' ' . $last_name);
        
        $result = new stdClass();
        $result->climbers = $climbers;
        $result->status = 'success';

        if(count($climbers) == 1) {
            $result->status = 'single';
        }
        else if(count($climbers) == 0) {
            $result->status = 'no_match';
        }

        $this->output->set_output(json_encode($result));
    }

    public function register_climber() {
        $this->load->model("climber");

        $first_name = $this->input->get('first_name');
        $last_name = $this->input->get('last_name');
        $category = $this->input->get('category');

        $climber = new stdClass();
        $climber->first_name = $this->input->get('first_name');
        $climber->last_name = $this->input->get('last_name');
        $climber->category = $this->input->get('category');

        $climber->id = $this->climber->create($climber);

        $result = new stdClass();
        $result->climber = $climber;
        $result->status = $climber->id ? 'success' : 'failed';

        $this->output->set_output(json_encode($result));
    }

    public function get_current_event() {

        $this->load->model("event");

        $climber_id = $this->input->get('climber');

        $events = $this->event->get_current_for_climber($climber_id);

        foreach($events as $event) {
            $event->name = $this->_event_name($event);
        }

        $result = new stdClass();
        $result->events = $events;
        $result->status = count($events) > 0 ? 'success' : 'none';

        $this->output->set_output(json_encode($result));
    }

    public function register_event() {
        $this->load->model("climber");
        $this->load->model("event");
        $this->load->model("event_climber");

        $climber_id = $this->input->get('climber');
        $event_length = $this->input->get('event_length');
        $public = $this->input->get('public');
        $start_time = $this->input->get('start_time');

        $climber = $this->climber->get($climber_id);

        $result = new stdClass();
        $result->climber = $climber;
        $result->status = 'success';

        $event = new stdClass();
        $event->start_time = $start_time == 'now' ? gmdate('Y-m-d H:i:s') : $start_time;
        $event->end_time = date('Y-m-d H:i:s', strtotime($event->start_time) + ($event_length * 60 * 60));
        $event->year = gmdate('Y');
        $event->event_length = $event_length;
        $event->editable = 1;
        $event->is_official = 0;
        $event->is_public = $public;

        $event->id = $this->event->create($event);
        if(!$event->id) {
            $result->status = 'failed';
        }
        else {
            $event_climber = new stdClass();
            $event_climber->climber_id = $climber->id;
            $event_climber->event_id = $event->id;
            $event_climber->category = $climber->category;

            $event_climber->id = $this->event_climber->create($event_climber);

            if(!$event->id) {
                $result->status = 'failed';
            }
            else {
                $event->name = $this->_event_name($event);
                $result->event = $event;
                $result->event_climber = $event_climber;
            }
        }

        $this->output->set_output(json_encode($result));
    }

    public function get_climbs() {
        $this->load->model("climber");
        $this->load->model("event");
        $this->load->model("event_climber");
        $this->load->model("send");
        $this->load->model("route");
        $this->load->model("rating");
        $this->load->model("wall");

        $climber_id = $this->input->get('climber');
        $event_id = $this->input->get('event');

        $climber = $this->climber->get($climber_id);
        $event = $this->event->get($event_id);
        $event_climber = $this->event_climber->get($climber_id, $event_id);
        $sends = $this->send->get_for_event_climber($event_climber->id);

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

        $send_map = array();
        foreach($sends as $send) {
            if(empty($send_map[$send->route_id])) {
                $send_map[$send->route_id] = 1;
            }
            else {
                $send_map[$send->route_id] = 2;
            }
        }

        $routes = array();
        foreach($route_list as $route) {
            if($event_climber->category == 'rec' && $route->rating > 12) {
                continue;
            }
            else if($event_climber->category == 'int' && $route->rating > 18) {
                continue;
            }
            else if($event_climber->category == 'adv' && $route->rating > 23) {
                continue;
            }
            if($event->event_length == '12' && !($route->wall >= 3 || $route->wall <= 13)) {
                continue;
            }
            $route->wall_name = $wall_map[$route->wall]->name;
            $route->rating_name = $rating_map[$route->rating]->name;
            $route->laps = empty($send_map[$route->id]) ? 0 : $send_map[$route->id];
            $routes[] = $route;
        }

        $result = new stdClass();
        $result->routes = $routes;
        $result->status = 'success';

        $this->output->set_output(json_encode($result));
    }

    public function add_lap() {
        $this->load->model("climber");
        $this->load->model("event");
        $this->load->model("event_climber");
        $this->load->model("send");
        $this->load->model("route");
        $this->load->model("rating");

        $climber_id = $this->input->get('climber');
        $event_id = $this->input->get('event');
        $route_id = $this->input->get('route');

        $event = $this->event->get($event_id);
        $route = $this->route->get($route_id);

        if($event->editable == 0) {
            $result = new stdClass();
            $result->status = 'uneditable';
            $this->output->set_output(json_encode($result));
            return;
        }

        $event_climber = $this->event_climber->get($climber_id, $event_id);
        $laps = $this->send->get_count_for_event_climber_route($event_climber->id, $route_id);
        if($laps >= 2) {
            $result = new stdClass();
            $result->status = 'too_many';
            $this->output->set_output(json_encode($result));
            return;
        }

        if(($event_climber->category == 'rec' && $route->rating > 12) ||
           ($event_climber->category == 'int' && $route->rating > 18) ||
           ($event_climber->category == 'adv' && $route->rating > 23) ||
           ($event->event_length == '12' && !($route->wall >= 3 || $route->wall <= 13))) {
            $result = new stdClass();
            $result->status = 'wrong_category';
            $this->output->set_output(json_encode($result));
            return;
        }

        $send = new stdClass();
        $send->climber_id = $climber_id;
        $send->event_id = $event_id;
        $send->event_climber_id = $event_climber->id;
        $send->route_id = $route_id;
        $send->team_id = $event_climber->team_id;
        $send->pink_point = $route->trad == 1 ? $this->input->get('pink_point') : 0;

        $adjusted_rating = $route->rating;
        if($route->trad == 1 && $send->pink_point == 0) {
            $adjusted_rating += 1;
        }
        if($route->height >= 60) {
            $adjusted_rating += 1;
        }
        $rating = $this->rating->get($adjusted_rating);

        $send->score = $rating->points;

        $send->id = $this->send->create($send);
        if($send->id) {
            $result = new stdClass();
            $result->send = $send;
            $result->status = 'success';
            $this->output->set_output(json_encode($result));
        }
        else {
            $result = new stdClass();
            $result->status = 'failed';
            $this->output->set_output(json_encode($result));
        }
    }

    public function undo_send() {
        $this->load->model("climber");
        $this->load->model("event");
        $this->load->model("event_climber");
        $this->load->model("send");
        $this->load->model("route");
        $this->load->model("rating");

        $climber_id = $this->input->get('climber');
        $event_id = $this->input->get('event');
        $route_id = $this->input->get('route');
        $pink_point = $this->input->get('pink_point');

        $event = $this->event->get($event_id);
        $route = $this->route->get($route_id);

        if($event->editable == 0) {
            $result = new stdClass();
            $result->status = 'uneditable';
            $this->output->set_output(json_encode($result));
            return;
        }

        $event_climber = $this->event_climber->get($climber_id, $event_id);
        $sends = $this->send->get_for_event_climber_route($event_climber->id, $route_id);
        if(count($sends) == 0) {
            $result = new stdClass();
            $result->status = 'too_few';
            $this->output->set_output(json_encode($result));
            return;
        }

        $send = $sends[count($sends) - 1];
        if($route->trad == 1) {
            foreach ($sends as $s) {
                if($s->pink_point == $pink_point) {
                    $send = $s;
                }
            }
        }
        if($event->is_official) {
            $send->deleted = gmdate('Y-m-d H:i:s');

            $this->send->update($send);
        }
        else {
            $this->send->delete($send->id);
        }

        $result = new stdClass();
        $result->send = $send;
        $result->status = 'success';
        $this->output->set_output(json_encode($result));
    }

	protected function _event_name($event) {
		if($event->is_official) {
			return $event->year . ' ' . $event->event_length . ' Hour Event';
		}
		else {
			return date('m/d/Y', strtotime($event->start_time)) . ' Practice Event';
		}
	}

	protected function _category_name($category) {
		switch($category) {
			case 'rec':
				return "Recreational";
				break;
			case 'int':
				return "Intermediate";
				break;
			case 'adv':
				return "Advanced";
				break;
			case 'eli':
				return "Elite";
				break;
		}
	}
}