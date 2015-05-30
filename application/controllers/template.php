<?php
require_once 'base_controller.php';
class Template extends CI_Controller {
	function get_group() {
		$this->load->model ( 'type_model' );
		$groups = $this->type_model->getAllTypes ();
		echo json_encode ( $groups );
	}
	function get_template($type_id) {
		$myfile = fopen("testfile.txt", "w");
		
		$this->load->helper ( 'file' );
		$this->load->model ( 'type_model' );
		$type = $this->type_model->get_type ( $type_id );
		if ($type->getPathTemplate () == '') {
			$file = "/application/asset/tpt_templates/" . $type->getGroupType ()->getId () . $type->getId () . ".xml";
			fopen($file, "w");
			echo dirname($file);
			if(!file_exists(dirname($file))){
				mkdir(dirname($file), 0777, true);
			}
		}
	}
	
	function save(){
		$type_id = $this->input->post('type_id');
		$json_template = $this->input->post('json_template');
		echo $json_template;
	}
}