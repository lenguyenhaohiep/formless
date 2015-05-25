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