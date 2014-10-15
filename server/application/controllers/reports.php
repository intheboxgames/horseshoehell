<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."controllers/Base_Controller.php";

class Reports extends Base_Controller {

    function __construct(){
        $this->controller_name = 'reports';
        $this->requires_auth = false;

        parent::__construct();
    }

	public function index(){
        $this->_show_view('reports/scores', "Scores");
	}

	public function climber($action) {

        $this->load->model("climber");
        $this->load->model("event");
        $this->load->model("event_climber");
        $this->load->model("send");
        $this->load->model("route");
        $this->load->model("rating");
        $this->load->model("wall");

        switch($action) {
        	case 'search':
				$term = $this->input->get('term');
				$climbers = $this->climber->search($term);

				$result = new stdClass();
				$result->climbers = $climbers;
				$result->status = 'success';
				$this->output->set_output(json_encode($result));
        		break;
        	case 'view':
        		$climber_id = $this->input->get('climber');
        		$climber = $this->climber->get($climber_id);
        		$event_id = $this->input->get('event');

        		$event_list = $this->event->get_for_climber($climber_id);
        		$current_events = array();
        		$past_official = array();
        		$past_practice = array();

        		foreach($event_list as $event) {
        			if(!$event->is_public) {
        				return;
        			}
        			$event->name = $this->_event_name($event);
        			if($event->end_time > gmdate('Y-m-d H:i:s')) {
        				$current_events[] = $event;
        			}
        			else if($event->is_official) {
        				$past_official[] = $event;
        			}
        			else {
        				$past_practice[] = $event;
        			}
        		}

        		if(!empty($event_id) && is_numeric($event_id) && $event_id > 0) {

        		}
        		else {
        			if(count($current_events) > 0) {
        				$event_id = $current_events[count($current_events) - 1]->id;
        			}
        			else if(count($past_official) > 0) {
        				$event_id = $past_official[count($past_official) - 1]->id;
        			}
        			else if(count($past_practice) > 0) {
        				$event_id = $past_practice[count($past_practice) - 1]->id;
        			}
        		}

        		$this->data['climber'] = $climber;
        		$this->data['current_events'] = $current_events;
        		$this->data['past_official'] = $past_official;
        		$this->data['past_practice'] = $past_practice;
        		$this->data['selected_event'] = $event_id;
		        $this->_show_view('reports/climber', 'Scores - ' . $climber->first_name . ' ' . $climber->last_name);
        		break;
        	case 'event_details':
        		$climber_id = $this->input->get('climber');
        		$event_id = $this->input->get('event');

        		$climber = $this->climber->get($climber_id);
        		$event = $this->event->get($event_id);
    			$event->name = $this->_event_name($event);
    			$event->is_current = $event->end_time > gmdate('Y-m-d H:i:s');
        		$event_climber = $this->event_climber->get_for_climber($climber_id, $event_id);
        		$sends = $this->send->get_for_event_climber($event_climber->id);

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

                foreach($sends as $send) {
                    $send->wall_name = $wall_map[$send->wall]->name;
                    $send->rating_name = $rating_map[$send->rating]->name;
        			$send->timestamp = strtotime($send->created);
                }

        		$event_climber->category_name = $this->_category_name($event_climber->category);

        		$hourly_data = array();
        		$start_time = strtotime($event->start_time);
        		$end_time = min($start_time + ($event->event_length * 60 * 60), time());
        		$total_score = 0;
        		$total_routes = 0;
        		$total_rating = 0;
        		$total_trad = 0;
        		$total_height = 0;
        		$last_hour = $start_time;
        		for($hour = $start_time + 60 * 60; $hour <= $end_time; $hour += 60 * 60) {
        			$hourly_score = 0;
        			$hourly_routes = 0;
        			$hourly_rating = 0;
        			$hourly_trad = 0;
        			$hourly_height = 0;
        			foreach($sends as $send) {
        				if($send->timestamp <= $hour && $send->timestamp > $last_hour) {
        					$hourly_score += $send->score;
        					$hourly_routes += 1;
        					$hourly_rating += $send->rating;
        					$hourly_trad += $send->trad ? 1 : 0;
        					$hourly_height += $send->height;
        					$send->hour = count($hourly_data) + 1;
        				}
        			}
        			$total_score += $hourly_score;
        			$total_routes += $hourly_routes;
        			$total_rating += $hourly_rating;
        			$total_trad += $hourly_trad;
        			$total_height += $hourly_height;

        			$data = new stdClass();
        			$data->hour = count($hourly_data) + 1;
        			$data->routes 		= $hourly_routes;
        			$data->total_routes = $total_routes;
        			$data->score 		= $hourly_score;
        			$data->total_score 	= $total_score;
        			$data->rating 		= $hourly_routes > 0 ? floor($hourly_rating / $hourly_routes) : 0;
        			$data->total_rating = $total_routes > 0 ? floor($total_rating / $total_routes) : 0;;
        			$data->points_per_route = $hourly_routes > 0 ? $hourly_score / $hourly_routes : 0;
        			$data->total_points_per_routes = $total_routes > 0 ? $total_score / $total_routes : 0;
        			$data->trad 		= $hourly_trad;
        			$data->total_trad 	= $total_trad;
        			$data->height 		= $hourly_height;
        			$data->total_height = $total_height;

        			$hourly_data[] = $data;
        			$last_hour = $hour;
        		}

        		$this->data['climber'] = $climber;
        		$this->data['event'] = $event;
        		$this->data['event_climber'] = $event_climber;
        		$this->data['sends'] = $sends;
        		$this->data['hourly_data'] = $hourly_data;
		        $this->_show_view('reports/climber_event_scores', 'Event Scores');
        		break;
        }
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