<?php

require_once 'base_controller.php';

class Template extends CI_Controller {

	/**
	 * Get and return all groups of type in the application
	 */
    function get_group() {
        $this->load->model('type_model');
        $groups = $this->type_model->getAllTypes();
        echo json_encode($groups);
    }

    /**
     * Return the template corresponds to the type id given
     * Return null of type does not exist
     */
    function get_template($type_id = NULL) {
        if ($type_id != NULL) {
            $this->load->model('type_model');
            $type = $this->type_model->get_type($type_id);
            if ($type->getData() !== '' && $type->getData() !== NULL) {
                echo $type->getData();
            } else
                echo json_encode(array());
        }else {
            echo json_encode(array());
        }
    }

    
    /**
     * Return the relation (JSON) between to types
     * each JSON is a pair ('a':'b') which means type1[a]=type2[b]
     * @param int $type_id
     * @param int $type_id2
     */
    function get_relation($type_id = NULL, $type_id2 = NULL) {
        if ($type_id != NULL && $type_id2 != NULL) {
            $this->load->library('formmaker');
            $this->load->model('type_model');

            $relation = $this->type_model->get_relation($type_id, $type_id2);
            if ($relation == NULL)
                $relation = $this->type_model->get_relation($type_id2, $type_id);
            
            if ($relation != NULL) {
                $r = $this->formmaker->get_relation($relation);
            
            	echo json_encode($r[$type_id][$type_id2]);
            }
            else{
            	$this->load->model('type_model');
            	$this->load->library('graphmaker');
            	$relations = $this->type_model->get_all_relation();
            	$graph = $this->graphmaker->build_graph_from_relation($relations);
            	$lst_nodes_1 = $this->graphmaker->get_nodes($relations, $type_id);
            	$lst_nodes_2 = $this->graphmaker->get_nodes($relations, $type_id2);
            	
            	if (count($lst_nodes_1) > 0 && count($lst_nodes_2) > 0)
            		echo json_encode($this->graphmaker->find_relation($graph, $lst_nodes_1,$lst_nodes_2));
            	else 
            		echo json_encode(array());
            }
        }
    }

    /**
     * Reponse the list of attribute name (JSON) of the type given
     * @param int $type_id
     */
    function get_attr($type_id = NULL) {
        $this->load->library('formmaker');
        if ($type_id != null) {
            $this->load->model('type_model');
            $type = $this->type_model->get_type($type_id);
            if ($type != null) {
                echo json_encode($this->formmaker->get_attribute_from_json($type->getData()));
            }
        } else {
            echo json_encode(array());
        }
    }

    /**
     * Save a template to database
     */
    function save() {
    	//get the request data
        $type_id = $this->input->post('type_id');
        //Json template
        $json_template = $this->input->post('json_template');

        $this->load->model('type_model');
        $type = $this->type_model->update_template($type_id, $json_template);

        echo $type->getId();
    }

    /**
     * Discard a template (type) by the id given
     * @param int $type_id
     */
    function discard($type_id) {
        if ($type_id != null) {
            $this->load->model('type_model');
            $discard = $this->type_model->discard_template($type_id);
            $msg_success = "deleted successfully";
            $msg_err = "can't not delete, you don't have permission or this template has been used";
            $result = array();
            if ($discard == true) {
                $result ['msg'] = $msg_success;
                $result ['err'] = 0;
            } else {
                $result ['msg'] = $msg_err;
                $result ['err'] = 1;
            }
        }
        echo json_encode($result);
    }

    
    /**
     * Define the relation between two forms
     */
    function relation() {
    	//type 1 and 2
        $type_id1 = $this->input->post('type_id1');
        $type_id2 = $this->input->post('type_id2');
        
        //relation defined by a array of array (name=>value) 
        //where name is the attribute name of type 1, and value is for type 2
        $data = $this->input->post('data');

        $this->load->model('type_model');
        foreach ($data as $d) {
            $attr1 = $d['name'];
            $attr2 = $d['value'];
            $this->type_model->create_or_update_relation($type_id1, $type_id2, $attr1, $attr2);
        }
        echo "defined successfully";
    }
    
    /**
     * Check whether there is the relation between forms
     * @return pairs (type1, type2)
     */
    function get_simple_edge(){
    	$sql = 'SELECT DISTINCT type_id_1, type_id_2 FROM `form_relation` ';
    	$sent = $this->em->getConnection()->query($sql)->fetchAll();
    	return $sent;
    }
    
    /**
     * Building the graph relation between forms
     */
    function graph(){
    	$this->load->model('type_model');
    	$this->load->library('graphmaker');
    	$relations = $this->type_model->get_all_relation();
    	$graph = $this->graphmaker->build_graph_from_relation($relations);
    	print_r($graph);
    }

}
