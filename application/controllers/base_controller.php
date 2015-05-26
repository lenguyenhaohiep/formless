<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Base_controller extends CI_Controller {
	
	public $data;

    public function __construct() {
        parent::__construct();
        //check login first
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login','refresh');
        }
        
        $this->lang->load('en','english');
    }
    
    public function load_base(){
    	$this->data['css_javascript'] = $this->load->view("template/css_javascript", '', true);
    	$this->data['logo'] = $this->load->view('template/logo', '', true);
    	$this->data['top_menu'] = $this->load->view('template/top_menu', '', true);
    	$this->data['footer'] = $this->load->view('template/footer', '', true);
    }
    
    function render_page($title, $menu_select, $view, $data) {
    	$this->data['title'] = $title;
    	$this->session->set_userdata('select', $menu_select);
    	$this->data['left_menu'] = $this->load->view('template/left_menu', '', true);
    	$this->data['main_content'] = $this->load->view($view, $data, true);
    	$this->load->view("template/base", $this->data);
    	 
    }

    
}
