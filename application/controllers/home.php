<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'basecontroller.php';

class Home extends Basecontroller {

    function __construct() {
        parent::__construct();

        $this->data['css_javascript'] = $this->load->view("template/css_javascript", '', true);
        $this->data['logo'] = $this->load->view('template/logo', '', true);
        $this->data['top_menu'] = $this->load->view('template/top_menu', '', true);
        $this->data['footer'] = $this->load->view('template/footer', '', true);
    }

    function index() {
        $this->render_page(lang('home_page_title'), "inbox", 'home/form', '');
    }

    function create() {
        $this->render_page(lang('create_page_title'), "create", 'home/create', '');
    }

    function sent() {
        $this->render_page(lang('sent_page_title'), "sent", 'home/form', '');
    }

    function draft() {
        $this->render_page(lang('draft_page_title'), "draft", 'home/form', '');
    }

    function mydocuments() {
        $this->render_page(lang('document_page_title'), "mydocuments", 'home/form', '');
    }

    function signature() {
        $this->render_page(lang('signature_page_title'), "", 'home/form', '');
    }

    function render_page($title, $menu_select, $view, $data) {
        $this->data['title'] = $title;
        $this->data['main_content'] = $this->load->view($view, $data, true);
        $this->session->set_userdata('select', $menu_select);
        $this->data['left_menu'] = $this->load->view('template/left_menu', '', true);
        $this->load->view("template/base", $this->data);
    }

}
