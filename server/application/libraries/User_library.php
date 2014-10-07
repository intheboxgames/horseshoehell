<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *  User library class
 *
 *  Change Log:
 *      
 *      2014-10-06  Lucas Stufflebeam   Creation
 */


class User_library
{
    private $_current_user = null;

    public function __construct(){

    }

    /**
     * Enables the use of CI super-global without having to define an extra variable.
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function set_current_user($uid == NULL){
        global $user;

        if($uid == NULL || $uid = $user->id){
            $this->_current_user == $user;
        }
        else if($this->_current_user != NULL && $uid = $this->_current_user->id) {
            $this->_current_user = $this->_current_user;
        }
        else {
            //$this->load->model('userdata');
            //$this->load->model('users/users');
            //$this->_current_user = $this->users->get($uid)
            //$this->_current_user->data = $this->userdata->get_for_user($uid);
        }
        return $this->_current_user;
    }

    public function get_current_user() {
        if($this->_current_user == NULL) {
            return $this->set_current_user();
        }
        return $this->_current_user;
    }


}