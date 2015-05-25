<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Form extends CI_Controller{
	
	function get_template($type_id=NULL){
		if ($type_id != null)
		echo "template".$type_id;
	}
	
	function discard($form_id=NULL){
// 		if ($form_id != null){
// 			$this->load->model('form_model');
// 			$this->form_model->delete_from($form_id);
// 		}
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
		//process to send form
		
		
		//redirect to the SENT
		redirect('home/sent');
	}
	
	function detail($form_id=NULL){
		if ($form_id != NULL){
			echo $form_id;
		}
	}
}