<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Base_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check login first
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login','refresh');
        }
        
        $this->lang->load('en','english');
    }

    
}
