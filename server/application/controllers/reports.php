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

        		$event = $this->event->get($event_id);
    			$event->name = $this->_event_name($event);
    			$event->is_current = $event->end_time > gmdate('Y-m-d H:i:s');

        		$this->data['climber'] = $climber;
        		$this->data['current_events'] = $current_events;
        		$this->data['past_official'] = $past_official;
        		$this->data['past_practice'] = $past_practice;
        		$this->data['event'] = $event;
		        $this->_show_view('reports/climber', 'Scores - ' . $climber->first_name . ' ' . $climber->last_name);
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
}