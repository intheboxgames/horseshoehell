<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."controllers/Base_Controller.php";

class Users extends Base_Controller {

    function __construct(){
        $this->controller_name = 'users';
        $this->requires_auth = true;

        parent::__construct();
    }

    public function index() {
        $this->account('view');
    }

    public function account($action = 'view') {
        global $user;

        $this->load->model("admin_user");

        switch($action) {
            case 'add':
                $new_user = new stdClass();
                $new_user->email = $this->input->post('email');
                $new_user->first_name = $this->input->post('first_name');
                $new_user->last_name = $this->input->post('last_name');
                $new_user->role = $this->input->post('role');
                $new_user->password = $this->input->post('password');
                $new_user->password_confirm = $this->input->post('password_confirm');

                if(strlen($new_user->password) < 3) {
                    $this->message->add_error("Your password must be at least 3 characters long");
                    redirect(base_url("users"));
                    return;
                }
                if($new_user->password != $new_user->password_confirm) {
                    $this->message->add_error("Your password and password confirm do not match");
                    redirect(base_url("users"));
                    return;
                }

                $new_user->id = $this->admin_user->register($new_user->email, $new_user->password, $new_user->first_name, $new_user->last_name, $new_user->role);

                if(!$new_user->id) {
                    $this->message->add_error("There was a problem creating the user. Please try again.");
                }
                else {
                    $this->message->add_success("User successfully added");
                }

                redirect(base_url("users"));
                break;
            case 'edit':
                $edit_user = new stdClass();
                $edit_user->email = $this->input->post('email');
                $edit_user->first_name = $this->input->post('first_name');
                $edit_user->last_name = $this->input->post('last_name');

                if($user->role != "admin") {
                    if($user->id != $this->input->post('id')) {
                        $this->message->add_error("Oh No! There was a problem saving your account changes. Please try again.");
                        redirect(base_url("users"));
                        return;
                    }
                    $edit_user->role = $user->role;
                }
                else {
                    $user = $this->admin_user->get($this->input->post('id'));
                    $edit_user->role = $this->input->post('role');
                }

                // Save new email and name
                if($user->email != $edit_user->email || $user->first_name != $edit_user->first_name || $user->last_name != $edit_user->last_name || $edit_user->role != $user->role) {
                    if($user->email != $edit_user->email && $this->auth->email_check($edit_user->email)) {
                        $this->message->add_error("That email already exists. Please try a different one.");
                        redirect(base_url("users"));
                        return;
                    }
                    if($this->admin_user->update($user->id, $edit_user)){
                        $user = $this->admin_user->get($user->id);
                        $this->admin_user->set_session($user);
                        $this->admin_user->remember_user($user->id);
                    }
                    else {
                        $this->message->add_error("There was a problem saving your changes. Please try again");
                        redirect(base_url("users"));
                        return;
                    }
                }

                $password = $this->input->post('password');
                $password_confirm = $this->input->post('password_confirm');

                // Password changed
                if($password != '**************') {
                    if(strlen($password) < 3) {
                        $this->message->add_error("Your password must be at least 3 characters long");
                        redirect(base_url("users"));
                        return;
                    }
                    if($password != $password_confirm) {
                        $this->message->add_error("Your password and password confirm do not match");
                        redirect(base_url("users"));
                        return;
                    }
                    if($this->admin_user->change_password($user->email, $password)) {
                        $this->message->add_success("Account successfully updated");
                    }
                    else {
                        $this->message->add_error("There was a problem saving your changes. Please try again");
                    }
                }
                else {
                    $this->message->add_success("Account successfully updated");
                }

                redirect(base_url("users"));
                break;
            case 'view':
            default:

                if($user->role != "admin") {
                    $this->data['user'] = $this->admin_user->get($user->id);
                    $this->_show_view('manage/account', "Manage Account");
                }
                else {
                    $this->data['user_list'] = $this->admin_user->get_all();
                    $this->_show_view('manage/users', "Manage Users");
                }
                break;
        }
    }
}