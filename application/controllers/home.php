<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'base_controller.php';

/**
 * 
 * @author Hiep Le
 * 
 * Name: Home controller
 * 
 * Description: Controller to direct the corresponding view for each task
 *
 */
class Home extends Base_controller {

    function __construct() {
        parent::__construct();
        $this->load_base();
    }

    /**
     * Render home page
     */
    function index() {
    	$this->load->model('form_model');
    	$this->data['emails'] = $this->form_model->get_inbox($this->ion_auth->get_user_id());
        $this->render_page(lang('home_page_title'), "inbox", 'home/form', $this->data);
    }

    /**
     * Render filling page 
     */
    function create() {
    	//Load model
	    	$this->load->model('type_model');
	    	$this->data['group_types'] = $this->type_model->getAllTypes();
	    	$this->data['own'] = true;
	    	$this->data['permission'] = true;
	    	
	        $this->render_page(lang('create_page_title'), "create", 'home/create', $this->data);
    }

    
    /**
     * Render sent page
     */
    function sent() {
    	$this->load->model('form_model');
    	$this->data['emails'] = $this->form_model->get_sent($this->ion_auth->get_user_id());
        $this->render_page(lang('sent_page_title'), "sent", 'home/form', $this->data);
    }

    /**
     * Render draft page
     */
    function draft() {
    	$this->load->model('form_model');
    	$this->data['forms'] = $this->form_model->get_draft($this->ion_auth->get_user_id());
        $this->render_page(lang('draft_page_title'), "draft", 'home/form', $this->data);
    }

    /**
     * Render Forms page
     */
    function mydocuments() {    	
    	$this->load->model('form_model');
    	$this->data['forms'] = $this->form_model->get_all_forms($this->ion_auth->get_user_id());
    	$this->data['shared'] = $this->form_model->get_shared_forms($this->ion_auth->get_user_id());
    	 
        $this->render_page(lang('document_page_title'), "mydocuments", 'home/form', $this->data);
    }
    
    /**
     * Render the design page
     */
    function design(){
    	$this->load->model('type_model');
    	$this->data['group_types'] = $this->type_model->getAllTypes();
        $this->render_page(lang('design_page_title'), "design", 'home/design', $this->data);
    }

    


}
