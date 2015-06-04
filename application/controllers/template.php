<?php

require_once 'base_controller.php';

class Template extends CI_Controller {

    function get_group() {
        $this->load->model('type_model');
        $groups = $this->type_model->getAllTypes();
        echo json_encode($groups);
    }

    function get_template($type_id = NULL) {
        if ($type_id != NULL) {
            $this->load->model('type_model');
            $type = $this->type_model->get_type($type_id);
            if ($type->getData() !== '' && $type->getData() !== NULL) {
                echo $type->getData();
            } else
                echo "{}";
        }else {
            echo "{}";
        }
    }

    function get_relation($type_id, $type_id2) {
        if ($type_id != NULL && $type_id2 != NULL) {
            $this->load->library('formmaker');
            $this->load->model('type_model');

            $relation = $this->type_model->get_relation($type_id, $type_id2);
            if ($relation == NULL)
                $relation = $this->type_model->get_relation($type_id2, $type_id);
            if ($relation != NULL) {
                $r = $this->formmaker->get_relation($relation);
            }
            echo json_encode($r[$type_id][$type_id2]);
        }
    }

    function get_attr($type_id = NULL) {
        $this->load->library('formmaker');
        if ($type_id != null) {
            $this->load->model('type_model');
            $type = $this->type_model->get_type($type_id);
            if ($type != null) {
                echo json_encode($this->formmaker->get_attribute_from_json($type->getData()));
            }
        } else {
            echo '{}';
        }
    }

    function save() {
        $type_id = $this->input->post('type_id');
        $json_template = $this->input->post('json_template');

        $this->load->model('type_model');
        $type = $this->type_model->update_template($type_id, $json_template);

        echo $type->getId();
    }

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

    function relation() {
        $type_id1 = $this->input->post('type_id1');
        $type_id2 = $this->input->post('type_id2');
        $data = $this->input->post('data');

        $this->load->model('type_model');
        foreach ($data as $d) {
            $attr1 = $d['name'];
            $attr2 = $d['value'];
            $this->type_model->create_or_update_relation($type_id1, $type_id2, $attr1, $attr2);
        }
        echo "defined successfully";
    }

}
