<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."models/base_model.php";

/*
 *  Model Class for Ratings
 *
 *  Change Log:
 *      
 *      2014-10-07  Luke Stuff   Creation
 */

class Rating extends Base_model {

    public function __construct(){
        $this->table_name = 'rating';
        parent::__construct();
    }

}