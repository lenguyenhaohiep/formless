<?php
/**
 * @author: Hiep Le
 * 
 * Name: Base Controller
 * Description: This is the based controller for all controllers, 
 * all controllers will inherit this class except the class auth.php
 */


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
            redirect('auth/','refresh');
        }
        
        $this->lang->load('en','english');
        $this->update_inbox();

    }
    
    /**
     * Update the number of inbox
     */
    public function update_inbox(){
    	$this->load->model('form_model');
    	$inbox =  $this->form_model->count_unread();
    	$this->session->set_userdata('inbox',$inbox);
    }
    
    
    /**
     * Load the views
     */
    public function load_base(){
    	$this->data['css_javascript'] = $this->load->view("template/css_javascript", '', true);
    	$this->data['logo'] = $this->load->view('template/logo', '', true);
    	$this->data['top_menu'] = $this->load->view('template/top_menu', '', true);
    	$this->data['footer'] = $this->load->view('template/footer', '', true);
    }
    
    /**
     * Render a page based on the task
     * @param string $title title of the page
     * @param string $menu_select indication of the current menu in the left side bar in the view
     * @param string $view name of view
     * @param array $data data related to view
     */
    function render_page($title, $menu_select, $view, $data) {
    	$this->data['title'] = $title;
    	$this->session->set_userdata('select', $menu_select);
    	$this->data['left_menu'] = $this->load->view('template/left_menu', '', true);
    	$this->data['main_content'] = $this->load->view($view, $data, true);
    	$this->load->view("template/base", $this->data);
    	 
    }

    
}
