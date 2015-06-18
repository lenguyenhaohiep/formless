<?php
use Doctrine\DBAL\Logging\EchoSQLLogger;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'base_controller.php';
class Form extends Base_controller {
	function __construct() {
		parent::__construct ();
		$this->load_base ();
		$this->load->library ( 'formmaker' );
	}
	function get_template($type_id = NULL) {
		$this->load->library ( 'formmaker' );
		
		if ($type_id != null) {
			$this->load->model ( 'type_model' );
			$type = $this->type_model->get_type ( $type_id );
			if ($type != null) {
				echo $this->formmaker->generate_from_json ( $type->getData () );
			}
		}
	}
	
	// get only data with attributes
	function get_data_array($form_id = NULL) {
		if ($form_id != null) {
			$this->load->model ( 'form_model' );
			$form = $this->form_model->get_form_by_id ( $form_id );
			if ($form != null) {
				echo json_encode ( $this->formmaker->get_data_form_json ( $form->getData () ) );
			}
		}
	}
	
	// get full template with data
	function get_data($form_id = NULL) {
		if ($form_id != null) {
			$this->load->model ( 'form_model' );
			$form = $this->form_model->get_form_by_id ( $form_id );
			if ($form != null) {
				echo $this->formmaker->generate_from_json ( $form->getData () );
			}
		}
	}
	function get_attr($form_id = NULL) {
		if ($form_id != null) {
			$this->load->model ( 'form_model' );
			$form = $this->form_model->get_form ( $form_id );
			if ($form != null) {
				echo json_encode ( $this->formmaker->get_attribute_from_json ( $form->getData () ) );
			}
		} else {
			echo '{}';
		}
	}
	function discard($form_id = NULL) {
		if ($form_id != null) {
			$this->load->model ( 'form_model' );
			$own = $this->form_model->check_own_form ( $form_id );
			$msg_success = "deleted successfully";
			$msg_err = "can't not delete, you don't have permission or this form has been sent";
			$result = array ();
			if ($own == true) {
				if ($this->form_model->check_delete ( $form_id )) {
					$result = $this->form_model->delete_from ( $form_id );
					$result ['msg'] = $msg_success;
					$result ['err'] = 0;
				} else {
					$result ['msg'] = $msg_err;
					$result ['err'] = 1;
				}
			} else {
				$result ['msg'] = $msg_err;
				$result ['err'] = 1;
			}
			echo json_encode ( $result );
		}
	}
	function get_request_data() {
		// Load models
		$this->load->model ( 'form_model' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'type_model' );
		
		// process to send form
		$data ['form_id'] = $this->input->post ( 'form_id' );
		$data ['title'] = $this->input->post ( 'title' );
		$data ['type_id'] = $this->input->post ( 'type_id' );
		$data ['to_email'] = $this->input->post ( 'to_user' );
		$data ['message'] = $this->input->post ( 'message' );
		$data ['from_user_id'] = $this->user_model->get_id_from_email ( $this->session->userdata ( 'identity' ) );
		
		$data ['data'] = $this->input->post ( 'data_form' );
		// get template from type
		if ($data ['form_id'] != "") {
			// update from current form
			$form = $this->form_model->get_form ( $data ['form_id'] );
			$data ['template_json'] = $form->getData ();
		} else {
			// create new form template
			$data ['type'] = $this->type_model->get_type ( $data ['type_id'] );
			$data ['template_json'] = $data ['type']->getData ();
			$template = json_decode ( $data ['template_json'], true );
			$template ['info'] = array (
					'type' => $data ['type']->getTitle (),
					'title' => $data ['title'] 
			);
			$data ['template_json'] = json_encode ( $template );
		}
		// no update for form
		if ($data ['data'] == "" || $data ['data'] == "-1") {
			if ($data ['form_id'] != '')
				$data ['form_filled'] = "-1";
			else
				$data ['form_filled'] = $data ['template_json'];
		} else
			$data ['form_filled'] = $this->formmaker->fill_data ( $data ['template_json'], $data ['data'] );
		return $data;
	}
	function save() {
		$data = $this->get_request_data ();
		// update or create new on
		$form = $this->form_model->create_or_update_form ( $data ['form_id'], $data ['title'], $data ['type_id'], $status = 0, $data ['form_filled'] );
		
		$detect_modify = $this->form_model->check_own_form ( $data ['form_id'] );
		
		if ($detect_modify == false)
			$detect_modify = $this->form_model->check_modify ( $data ['form_id'] );
		
		if ($detect_modify && $data ['form_id'] != null) {
			$user_id = $this->user_model->get_id_from_email ( $this->session->userdata ( 'identity' ) );
			$this->form_model->modify_form ( $data ['form_id'], $user_id );
		}
		echo $form->getId ();
	}
	function send() {
		$data = $this->get_request_data ();
		
		$list_emails = explode ( ",", $data ['to_email'] );
		foreach ( $list_emails as $email ) {
			$email = $this->process_email ( trim ( $email ) );
			
			$to_user_id = $this->user_model->get_id_from_email ( trim ( $email ) );
			
			$send = $this->form_model->send_form ( $data ['form_id'], $data ['title'], $data ['type_id'], $status = 1, $data ['form_filled'], $data ['from_user_id'], $to_user_id, $data ['message'] );
			
			echo $send->getId ();
		}
	}
	function share() {
		$data = $this->get_request_data ();
		
		if ($data ['to_email'] == '') {
			$share = $this->form_model->share_form ( $data ['form_id'], $data ['title'], $data ['type_id'], $status = 1, $data ['form_filled'], null );
			echo $share->getId ();
		} else {
			$list_emails = explode ( ",", $data ['to_email'] );
			
			foreach ( $list_emails as $email ) {
				$email = $this->process_email ( trim ( $email ) );
				$to_user_id = $this->user_model->get_id_from_email ( trim ( $email ) );
				$share = $this->form_model->share_form ( $data ['form_id'], $data ['title'], $data ['type_id'], $status = 1, $data ['form_filled'], $to_user_id );
				
				echo $share->getId ();
			}
		}
	}
	function detail($form_id = NULL) {
		if ($form_id != NULL) {
			$this->load->model ( 'type_model' );
			$this->load->model ( 'form_model' );
			$check_access = $this->form_model->check_access ( $form_id );
			
			if (! $check_access) {
				$this->load->view ( '/index.html' );
				return;
			}
			
			$this->data ['group_types'] = $this->type_model->getAllTypes ();
			$this->data ['form_instance'] = $this->form_model->get_form_by_id ( $form_id );
			$this->data ['modification_history'] = $this->form_model->get_history_modification ( $form_id );
			$this->data ['list_email'] = $this->get_communicator ( $form_id );
			
			$this->form_model->mark_as_read ( $form_id, $this->ion_auth->get_user_id () );
			$this->update_inbox ();
			$this->data ['form_id'] = $form_id;
			
			$this->data ['own'] = $this->form_model->check_own_form ( $form_id );
			
			if ($this->data ['own'] == true)
				$this->data ['permission'] = true;
			else
				$this->data ['permission'] = $this->form_model->check_modify ( $form_id );
			$this->render_page ( lang ( 'detail_page_title' ), "detail", 'home/create', $this->data );
		}
	}
	function process_email($email = NULL) {
		$terms = explode ( " ", $email );
		$email = trim ( $terms [2] );
		$email = substr ( $email, 1, strlen ( $email ) - 2 );
		return $email;
	}
	function get_message() {
		$form_id = $this->input->post ( 'form_id' );
		$email_contact = $this->process_email ( $this->input->post ( 'email_contact' ) );
		
		$this->load->model ( 'form_model' );
		$histories = $this->form_model->get_message_by_email ( $form_id, $email_contact );
		
		$result = array ();
		foreach ( $histories as $h ) {
			$result [] = array (
					'from' => $h->getFromUser ()->getEmail (),
					'to' => $h->getToUser ()->getEmail (),
					'date' => $h->getSendDate ()->format ( "d/m/Y H:i:s" ),
					'message' => $h->getMessage () 
			);
		}
		
		echo json_encode ( $result );
	}
	function get_communicator($form_id) {
		$this->load->model ( 'form_model' );
		$histories = $this->form_model->get_message ( $form_id );
		$list_emails = array ();
		
		$current_email = $this->session->userdata ( 'identity' );
		
		$i = 0;
		foreach ( $histories as $history ) {
			
			if (strcmp ( $history->getFromUser ()->getEmail (), $current_email ) !== 0) {
				$user = $history->getFromUser ();
			} else {
				$user = $history->getToUser ();
			}
			$firstname = $user->getFirstName ();
			$lastname = $user->getLastName ();
			$e = $user->getEmail ();
			$email = "$firstname $lastname ($e)";
			
			$list_emails [$email] = 0;
			$i = $i + 1;
			if ($i == count ( $histories ))
				$list_emails [$email] = 1;
		}
		// get list of emails contacting with the current users
		return $list_emails;
	}
	function count_unread() {
		$this->load->model ( 'form_model' );
		echo $this->form_model->count_unread ();
	}
	function get_shared_info() {
		$form_id = $this->input->post ( 'form_id' );
		$this->load->model ( 'form_model' );
		$result = array ();
		
		if ($form_id != '') {
			$share = $this->form_model->get_share_by_form ( $form_id );
			foreach ( $share as $s ) {
				$user = $s->getUser ();
				$firstname = $user->getFirstName ();
				$lastname = $user->getLastName ();
				$e = $user->getEmail ();
				$email = "$firstname $lastname ($e)";
				$result [] = $email;
			}
		}
		echo json_encode ( $result );
	}
	function get_your_form() {
		$forms = $this->form_model->get_all_forms ( $this->ion_auth->get_user_id () );
		$shared = $this->form_model->get_shared_forms ( $this->ion_auth->get_user_id () );
		$result = array ();
		foreach ( $forms as $f ) {
			$type_id = $f->getType ()->getId ();
			$type_tile = $f->getType ()->getTitle ();
			$form_id = $f->getId ();
			$form_title = $f->getTitle ();
			$result [$type_id] [$type_tile] [$form_id] = $form_title;
		}
		
		foreach ( $shared as $f ) {
			$type_id = $f->getType ()->getId ();
			$type_tile = $f->getType ()->getTitle ();
			$form_id = $f->getId ();
			$form_title = $f->getTitle ();
			$result [$type_id] [$type_tile] [$form_id] = $form_title;
		}
		
		echo json_encode ( $result );
	}
	function fill($type_id, $form_id2) {
		$this->load->library ( 'formmaker' );
		$this->load->model ( 'type_model' );
		$this->load->library ( 'formmaker' );
		$this->load->model ( 'form_model' );
		$type_id2 = $this->form_model->get_form ( $form_id2 )->getType ()->getId ();
		if ($type_id != NULL && $type_id2 != NULL) {
			// if two forms are different
			if ($type_id != $type_id2) {
				
				$relation = $this->type_model->get_relation ( $type_id, $type_id2 );
				if ($relation == NULL)
					$relation = $this->type_model->get_relation ( $type_id2, $type_id );
				if ($relation != NULL) {
					$r = $this->formmaker->get_relation ( $relation );
				}
			} else {
				// if two forms are identical
				$this->load->model ( 'type_model' );
				$type = $this->type_model->get_type ( $type_id );
				$r = $this->formmaker->get_relation_identical ( $type_id, $type->getData () );
			}
			
			if (isset ( $r )) {
				echo json_encode ( $r [$type_id] [$type_id2] );
			} else
				json_encode ( '{}' );
		}
	}
	function download($form_id = NULL) {
		if ($form_id != NULL) {
			$this->load->model ( 'form_model' );
			$form = $this->form_model->get_form ( $form_id );
			$this->load->helper ( 'download' );
			$name = $this->sanitize_file_name ( $form->getTitle () ) . ".txt";
			$data = json_encode ( json_decode ( $form->getData (), true ), JSON_PRETTY_PRINT );
			force_download ( $name, $data );
		} else {
			echo "no form to download";
		}
	}
	function sanitize_file_name($str = '') {
		$sanitized = preg_replace ( '/[^a-zA-Z0-9-_\.]/', '', $str );
		return $sanitized;
	}
}
