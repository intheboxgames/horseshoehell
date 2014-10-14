<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *  Auth library class
 *
 *  Change Log:
 *      
 *      2014-10-06  Luke Stuff   Creation
 */


class Auth_library
{
    protected $status;

    public function __construct()
    {
        $this->load->library('email');
        $this->load->helper('cookie');

        $this->load->library('session');

        $this->load->model('admin_user');

        //auto-login the user if they are remembered
        if (!$this->logged_in() && get_cookie('email'))
        {
            $this->admin_user->login_remembered_user();
        }

        //$this->email->initialize(array('mailtype' => 'html'));
    }

    public function __call($method, $arguments)
    {
        if (!method_exists($this->admin_user, $method))
        {
            throw new Exception('Undefined method Auth::' . $method . '() called');
        }

        return call_user_func_array(array($this->admin_user, $method), $arguments);
    }

    /**
     * Enables the use of CI super-global without having to define an extra variable.
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }

    /*
    public function register($username, $password, $email, $additional_data = array(), $role_ids = array())
    {
        $this->users->trigger_events('pre_account_creation');

        $id = $this->admin_user->register($username, $password, $email, $additional_data, $group_ids);
        if ($id !== FALSE)
        {
            $this->message->add_success('account_creation_successful');
            return $id;
        }
        else
        {
            $this->message->add_error('account_creation_unsuccessful');
            return FALSE;
        }
    }
    */

    public function logout() {

        $this->session->unset_userdata( array('email' => '', 'id' => '', 'user_id' => '') );

        //delete the remember me cookies if they exist
        if (get_cookie('email')) {
            delete_cookie('email');
        }
        if (get_cookie('remember_code')) {
            delete_cookie('remember_code');
        }

        //Destroy the session
        $this->session->sess_destroy();

        $this->session->sess_create();

        $this->message->add_success('Successfully logged out');
        return TRUE;
    }

   
    public function logged_in() {
        if(!$this->session->userdata('email')){
            $this->admin_user->login_remembered_user();
        }
        return (bool) $this->session->userdata('email');
    }


    public function get_user_id() {
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id))
        {
            return $user_id;
        }
        return null;
    }

    public function set_global_user(){
        global $user;
        if(!$this->logged_in()){
            $user = null;
            //$this->message->add_error('Attempting to load a user when none exists!');
            return false;
        }
        $user_id = $this->get_user_id();
        $user = $this->admin_user->get($user_id);
    }

    public function is_admin()
    {
        global $user;
        return $user && $user->role == 'admin';
    }

    /*public function in_group($check_group, $id=false, $check_all = false)
    {
            $this->users->trigger_events('in_group');

            $id || $id = $this->session->userdata('user_id');

            if (!is_array($check_group))
            {
                    $check_group = array($check_group);
            }

            if (isset($this->_cache_user_in_group[$id]))
            {
                    $groups_array = $this->_cache_user_in_group[$id];
            }
            else
            {
                    $users_groups = $this->users->get_users_groups($id)->result();
                    $groups_array = array();
                    foreach ($users_groups as $group)
                    {
                            $groups_array[$group->id] = $group->name;
                    }
                    $this->_cache_user_in_group[$id] = $groups_array;
            }
            foreach ($check_group as $key => $value)
            {
                    $groups = (is_string($value)) ? $groups_array : array_keys($groups_array);

                    if (in_array($value, $groups) xor $check_all)
                    {
                            return !$check_all;
                    }
            }

            return $check_all;
    }*/

}