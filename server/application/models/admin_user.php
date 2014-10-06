<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."models/base_model.php";

/*
 *  Model Class for Device
 *
 *  Change Log:
 *      
 *      2014-10-06  Luke Stuff   Creation
 */

class Admin_User extends Base_model {

    public function __construct(){
        $this->table_name = 'admin_user';
        parent::__construct();
    }

}