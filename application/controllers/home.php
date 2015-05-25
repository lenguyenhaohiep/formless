<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'base_controller.php';

class Home extends Base_controller {

    function __construct() {
        parent::__construct();

        $this->data['css_javascript'] = $this->load->view("template/css_javascript", '', true);
        $this->data['logo'] = $this->load->view('template/logo', '', true);
        $this->data['top_menu'] = $this->load->view('template/top_menu', '', true);
        $this->data['footer'] = $this->load->view('template/footer', '', true);
    }

    function index() {
    	$this->load->model('form_model');
    	$this->data['emails'] = $this->form_model->get_inbox($this->ion_auth->get_user_id());
        $this->render_page(lang('home_page_title'), "inbox", 'home/form', $this->data);
    }

    function create() {
    	//Load model
    	$action = $this->input->post();
    	if ($action == null){
	    	$this->load->model('type_model');
	    	$this->data['group_types'] = $this->type_model->getAllTypes();
	        $this->render_page(lang('create_page_title'), "create", 'home/create', $this->data);
    	}
    	else{
    		echo "create";
    	}
    }

    function sent() {
    	$this->load->model('form_model');
    	$this->data['emails'] = $this->form_model->get_sent($this->ion_auth->get_user_id());
        $this->render_page(lang('sent_page_title'), "sent", 'home/form', $this->data);
    }

    function draft() {
    	$this->load->model('form_model');
    	$this->data['forms'] = $this->form_model->get_draft($this->ion_auth->get_user_id());
        $this->render_page(lang('draft_page_title'), "draft", 'home/form', $this->data);
    }

    function mydocuments() {    	
    	$this->load->model('form_model');
    	$this->data['forms'] = $this->form_model->get_all_forms($this->ion_auth->get_user_id());
        $this->render_page(lang('document_page_title'), "mydocuments", 'home/form', $this->data);
    }

    function signature() {
        $this->render_page(lang('signature_page_title'), "", 'home/form', '');
    }

    function render_page($title, $menu_select, $view, $data) {
        $this->data['title'] = $title;
        $this->session->set_userdata('select', $menu_select);
        $this->data['left_menu'] = $this->load->view('template/left_menu', '', true);
        $this->data['main_content'] = $this->load->view($view, $data, true);
        $this->load->view("template/base", $this->data);
    }

}
