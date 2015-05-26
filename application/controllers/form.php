<?php

use Doctrine\DBAL\Logging\EchoSQLLogger;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'base_controller.php';


class Form extends Base_controller{
	
	function __construct(){
		parent::__construct();
		$this->load_base();
	}
	
	function get_template($type_id=NULL){
		if ($type_id != null)
		echo "template".$type_id;
	}
	
	function discard($form_id=NULL){
		if ($form_id != null){
			$this->load->model('form_model');
			$result = $this->form_model->delete_from($form_id);
			echo $result;
		}
	}
	
	function save(){
		$form_id = $this->input->post('form_id');
		$title= $this->input->post('title');
		$type_id= $this->input->post('type_id');
		
		$this->load->model('form_model');
		$form = $this->form_model->create_or_update_form($form_id, $title, $type_id, $status=0, $path_form='test');

		echo $form->getId();
	}
	
	function send(){
		//Load models
		$this->load->model('form_model');
		$this->load->model('user_model');
		
	
		//process to send form
		$form_id = $this->input->post('form_id');
		$title= $this->input->post('title');
		$type_id= $this->input->post('type_id');
		$to_email = $this->input->post('to_user');
		$message = $this->input->post('message');
		$from_user_id = $this->user_model->get_id_from_email($this->session->userdata('identity'));
		
		$list_emails= explode(",", $to_email);
		foreach ($list_emails as $email){
			
			$to_user_id = $this->user_model->get_id_from_email(trim($email));
		
			$send = $this->form_model->send_form($form_id, $title, $type_id, $status=0, $path_form="test", $from_user_id, $to_user_id, $message);

			echo $send->getId();
		}
		
	}
	
	function detail($form_id=NULL){
		if ($form_id != NULL){
			$this->load->model('type_model');
			$this->load->model('form_model');
			$this->data['group_types'] = $this->type_model->getAllTypes();
			$this->data['form_instance'] = $this->form_model->get_form_by_id($form_id);
			$this->data['modification_history'] = $this->form_model->get_history_modification($form_id);
			$this->render_page(lang('create_page_title'), "create", 'home/create', $this->data);
		}
	}
}