<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."models/base_model.php";

/*
 *  Model Class for Routes
 *
 *  Change Log:
 *      
 *      2014-10-06  Luke Stuff   Creation
 */

class Route extends Base_model {

    public function __construct(){
        $this->table_name = 'route';
        parent::__construct();
    }

}