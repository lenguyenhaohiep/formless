<?php
require __DIR__ . '/../../vendor/autoload.php';
use Doctrine\DBAL\Logging\EchoSQLLogger;
use mikehaertl\wkhtmlto\Pdf;

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

			$own = $this->form_model->check_own_form ( $form_id);


			if ($form != null) {
				$signed = $this->form_model->check_signed($form_id);
					if ($signed == true){
						$ediableFields = array();
						echo $this->formmaker->generate_from_json ( $form->getData (), $ediableFields);
					}
					else {
						if ($own)
							echo $this->formmaker->generate_from_json ( $form->getData () );
						else{
							$share_form = $this->form_model->get_shared_attrs_by_id ($form_id);
							if ($share_form == NULL)
								$ediableFields = array();
							else
								$ediableFields = json_decode($share_form->getAttrs(),true);
								

							echo $this->formmaker->generate_from_json ( $form->getData (), $ediableFields);
							}
					}
			}
		}
	}
	function get_attr($form_id = NULL) {
		if ($form_id != null) {
			$this->load->model ( 'form_model' );
			$form = $this->form_model->get_form ( $form_id );
			if ($form != null) {
				$own = $this->form_model->check_own_form ( $form_id);
				if ($own)
					echo json_encode ( $this->formmaker->get_attribute_from_json ( $form->getData () ) );
				else{
					$share_form = $this->form_model->get_shared_attrs_by_id ($form_id);
					if ($share_form == NULL)
						$ediableFields = array();
					else
						$ediableFields = json_decode($share_form->getAttrs(),true);

					$fullFields = $this->formmaker->get_attribute_from_json ($form->getData());

					$restrictedFields = array();
					foreach ($fullFields  as $e) {
						$field = (array_keys($e));
						$field = $field[0];
						if (in_array($field, $ediableFields))
							$restrictedFields[] = $e;
					}
					echo json_encode($restrictedFields);
				}
			} else {
				echo json_encode(array());
			}
		}
		else
			echo json_encode(array());
	}
	function discard($form_id = NULL) {
		if ($form_id != null) {
			try{
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
			catch(Exception $e){
				echo 0;
			}
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
		$data ['attrs'] = $this->input->post ( 'list_attrs');
		$data ['version'] = $this->input->post ('version');
		

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
			$user = $this->user_model->get_user_from_email($this->session->userdata('identity'));
			$template ['info'] = array (
					'type' => array('id'=>$data['type']->getId(), 
									'title'=>$data ['type']->getTitle ()),
					'title' => $data ['title'] ,
					'owner' => array(
									'firstname'=> $user->getFirstName(),
									'lastname'=>$user->getLastName(),
									'email'=>$this->session->userdata('identity')),
					'creation' => new DateTime()
			);
			$data ['template_json'] = json_encode ( $template );
		}


		// check ownership
		if ($data ['form_id'] == ''){
			$data ['own'] = true;
			$lstAttrs = $this->formmaker->get_attribute_from_json ($data ['template_json']);
			$data ['ediableFields'] = array();
			foreach ($lstAttrs as $a) {
				$keys = array_keys($a);
				$data ['ediableFields'][] = $keys[0];
			}

		}
		else{
			$data ['own'] = $this->form_model->check_own_form ( $data ['form_id']);
			$share_form = $this->form_model->get_shared_attrs_by_id ($data ['form_id']);

			if ($data['own'] == true){
				$lstAttrs = $this->formmaker->get_attribute_from_json ($data ['template_json']);
				$data ['ediableFields'] = array();
				foreach ($lstAttrs as $a){
					$keys = array_keys($a);
					$data ['ediableFields'][] = $keys[0];
				}
			}else 
				if ($share_form == NULL)
					$data ['ediableFields'] = array();
				else
					$data ['ediableFields'] = json_decode($share_form->getAttrs(),true);
		}

		// process requested shared information

		if ($data ['attrs'] != "")
			$data ['attrs'] = $this->formmaker->get_attributes_from_requested_json($data['attrs']);

		// check if you've already signed, when signed, from cannot be edited, 
		// ediableFields = array() means that you have no attribute in form to update
		if ($data ['form_id'] != ''){
			$signed = $this->form_model->check_signed($data ['form_id']);
			if ($signed == true){
				$data ['own'] = false; 
				$data ['ediableFields'] = array();
			}
		}

		// no update for form
		if ($data ['data'] == "" || $data ['data'] == "-1") {
			if ($data ['form_id'] != '')
				$data ['form_filled'] = "-1";
			else
				$data ['form_filled'] = $data ['template_json'];
		} else
			$data ['form_filled'] = $this->formmaker->fill_data ( $data ['template_json'], $data ['data'], $data ['own'], $data ['ediableFields']);
		return $data;
	}
	function save() {
		$data = $this->get_request_data ();
		// update or create new on
		$form = $this->form_model->create_or_update_form ( $data ['form_id'], $data ['title'], $data ['type_id'], $status = 0, $data ['form_filled'], $data['version'] );
		
		$detect_modify = $this->form_model->check_own_form ( $data ['form_id'] );
		
		if ($detect_modify == false)
			$detect_modify = $this->form_model->check_modify ( $data ['form_id'] );
		
		if ($detect_modify && $data ['form_id'] != null) {
			$user_id = $this->user_model->get_id_from_email ( $this->session->userdata ( 'identity' ) );
			$this->form_model->modify_form ( $data ['form_id'], $user_id );
		}
		if (is_object($form))
			echo json_encode(array('id'=>$form->getId(),'version'=>$form->getVersion()));
		else
			echo json_encode(array('id'=>$form));
	}
	function send() {
		$data = $this->get_request_data ();
		$form = $this->form_model->create_or_update_form ( $data ['form_id'], $data ['title'], $data ['type_id'], $status = 0, $data ['form_filled'], $data['version'] );
		if ($form == null){
			return;
			echo json_encode(array('id'=>'Cannot send'));

		}
		$id = $form->getId();
		$version = $form->getVersion();

		$list_emails = explode ( ",", $data ['to_email'] );
		foreach ( $list_emails as $email ) {

			$email = $this->process_email ( trim ( $email ) );

			$to_user_id = $this->user_model->get_id_from_email ( trim ( $email ) );
			if ($form != null){
				$send = $this->form_model->send_form ( $id, $data ['title'], $data ['type_id'], $status = 1, $data ['form_filled'], $version, $data ['from_user_id'], $to_user_id, $data ['message'] );
			}
		}

		if ($id != ''){
			echo json_encode(array('id'=>$id,'version'=>$version));
		}
		else{
			echo json_encode(array('id'=>$form));
		}
	}
	function share() {
		$data = $this->get_request_data ();
		$form = $this->form_model->create_or_update_form ( $data ['form_id'], $data ['title'], $data ['type_id'], $status = 0, $data ['form_filled'], $data['version'] );
		if ($form == null){
			return;
			echo json_encode(array('id'=>'Cannot share'));

		}
		$id = $form->getId();
		$version = $form->getVersion();

		if ($data ['to_email'] == '') {
			$share = $this->form_model->share_form ( $data ['form_id'], $data ['title'], $data ['type_id'], $status = 1, $data ['form_filled'], $data['version'], null, $data['attrs']);
			echo $share->getForm()->getId ();
		} else {
			$list_emails = explode ( ",", $data ['to_email'] );
			
			foreach ( $list_emails as $email ) {
				$email = $this->process_email ( trim ( $email ) );
				$to_user_id = $this->user_model->get_id_from_email ( trim ( $email ) );
				$share = $this->form_model->share_form ( $id, $data ['title'], $data ['type_id'], $status = 1, $data ['form_filled'], $data['version'], $to_user_id, $data['attrs']);
			}
		}

		if ($id != ''){
			echo json_encode(array('id'=>$id,'version'=>$version));
		}
		else{
			echo json_encode(array('id'=>$form));
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
		$email = trim ( $terms [count($terms)-1] );
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
				if ($s->getAttrs() != ''){
					$user = $s->getUser ();
					$firstname = $user->getFirstName ();
					$lastname = $user->getLastName ();
					$e = $user->getEmail ();
					$email = "$firstname $lastname ($e)";
					$result [] = array('email'=>$email,'attrs'=>$s->getAttrs());
				}
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
			$f = $f->getForm();
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


	function getfile(){
		$this->load->helper('download');
		force_download($this->session->userdata('i'),$this->session->userdata('j'));
	}





	function view_upload(){
		$file = $this->input->post('file');
		try{
			$data = json_decode($file,true);
			$form_id = isset($data['info']['id']) ? $data['info']['id'] : '';
			$type_id = isset($data['info']['type']['id']) ? $data['info']['type']['id'] : '';
			$title = $data['info']['title'];
			$form = $this->formmaker->generate_from_json ( $file );
			$result = array(
				'form_id'	=> $form_id,
				'type_id'	=> $type_id,
				'title'		=> $title,
				'form'		=> $form 
				);
			echo json_encode($result);

		} catch(Exception $e){
			echo json_encode(array());
		}	
	}

	function fill_upload(){
		$file = $this->input->post('file');
		try{
			$data = json_decode($file,true);
			$form_id = isset($data['info']['id']) ? $data['info']['id'] : '';
			$type_id = isset($data['info']['type']['id']) ? $data['info']['type']['id'] : '';
			$title = $data['info']['title'];
			$attrs = $this->formmaker->get_attribute_from_json($file);
			$data_array = $this->formmaker->get_data_form_json($file);
			$result = array(
				'form_id'	=> $form_id,
				'type_id'	=> $type_id,
				'title'		=> $title,
				'attributes'=> $attrs,
				'data' 		=> $data_array
				);
			echo json_encode($result);

		} catch(Exception $e){
			echo json_encode(array());
		}
	}

	function sign($form_id = NULL){
		$this->load->model('form_model');			
		$this->load->model('user_model');
		$form_id = $this->input->post ( 'form_id' );
		
		if ($form_id != NULL){
			$signed = $this->form_model->check_signed($form_id);

			//In case you have signed the form, you cannot sign again
			if ($signed){
				echo "";
				return;
			}
			
			$form = $this->form_model->get_form ($form_id);
			$cert = $this->user_model->load_user_pair_key();
	
			$pri = $cert->getSecretKey();
			$pub = $cert->getPubicKey();
			$passphrase = $this->input->post('passphrase-sign');

			$message = $form->getData();
			$this->load->library('securitygpg');

			$signed_message = $this->securitygpg->sign($message, $pri, $passphrase);

			$this->load->model('form_model');
			$sign = $this->form_model->sign($form_id, $signed_message);

			echo $sign->getId();
		}
		else{
			echo "";
		}

	}


	function verify($form_id = NULL){
		$this->load->model('user_model');
		$this->load->library('securitygpg');
		$form_id = $this->input->post('form_id');
		$signatures = $this->form_model->get_all_signature($form_id);
		$history = array();
		foreach ($signatures as $s){
			$user_id = $s->getUser()->getId();
			$signed_message = $s->getData();
			$cert = $this->user_model->load_user_pair_key($user_id);
			$PublicData = $cert->getPubicKey();
			$keyinfo="";
			$verify = $this->securitygpg->verify($signed_message, $PublicData, $keyinfo);
			$history[] = array(
				"firstname" => $s->getUser()->getFirstName(),
				"lastname" 	=> $s->getUser()->getLastName(),
				"email"		=> $s->getUser()->getEmail(),
				"keyinfo"	=> $this->securitygpg->get_by_fingerprint($keyinfo),
				"result"	=> $verify ? "Good Signature" : "Bad Signature",
				"status"	=> $verify
			);
		}

		echo json_encode($history);
	}

	function check_signed(){
		$form_id = $this->input->post('form_id');
		if ($form_id != null){
			$this->load->model('form_model');
			$signed = $this->form_model->check_signed($form_id, true);
			echo $signed == true ? "1" : "0";
			return;
		}
		
		echo "0";
	}

	function test(){
		try{		
			$options = array(
				'use-xserver',
				'commandOptions' => array('enableXvfb' => true),
				'disable-smart-shrinking',
				'user-style-sheet' => __DIR__ .'/../../css/pdf.css',
				"binary" => "/usr/bin/wkhtmltopdf");
			// You can pass a filename, a HTML string or an URL to the constructor
			$pdf = new Pdf($options);
			//echo $url = base_url()."index.php/form/get_data/1";
			$url ='<html>
				<head>
				</head>
				<body>

				    <div id="print-area">
				        <div id="header">
				            This is an example header.
				        </div>
				        <div id="content"><p><span>Sign</span><span>Hiep  Le</span></p></div>
				        <div id="footer">
				            This is an example footer.
				        </div>
				    </div>

				</body>
				</html>';
			$pdf->addPage($url);

			// On some systems you may have to set the binary path.
			// $pdf->binary = 'C:\...';
			
			$pdf->send();
		}catch(Exception $e){
			echo $pdf->getError();

		}
	}

	function getdata(){
		$form_id = $this->input->post('form_id');
		$type_id = $this->input->post('type_id');
		$title = $this->input->post('title');

		$data = $this->input->post('data_form');
		$data = str_replace('{name:', '{"name":', $data);
		$data = str_replace(', value:', ', "value":', $data);
		$data =  json_decode($data,true);

		if ($form_id == ''){
			$type = $this->type_model->get_type ($type_id);
			$template_json = $type->getData ();	
		}
		else{
			$form = $this->form_model->get_form ($form_id);
			$template_json = $form->getData ();
		}
		$form_filled = $this->formmaker->fill_data ( $template_json, $data, false, array());

		$result = array($form_id, $type_id, $form_filled, $title);
		return $result;
	}

	function convertpdf(){
		$data = $this->getdata();

		try{		
			$options = array(
				'use-xserver',
				'commandOptions' => array('enableXvfb' => true),
				'disable-smart-shrinking',
				'user-style-sheet' => __DIR__ .'/../../css/pdf.css',
				"binary" => "/usr/bin/wkhtmltopdf");
			// You can pass a filename, a HTML string or an URL to the constructor
			$pdf = new Pdf($options);
			//echo $url = base_url()."index.php/form/get_data/1";
			$url = $this->formmaker->generate_html_pdf($data[2]);
			$pdf->addPage($url);

			// On some systems you may have to set the binary path.
			// $pdf->binary = 'C:\...';
			
			$pdf->send();
		}catch(Exception $e){
			echo $pdf->getError();

		}

	}




	function download() {
		$data = $this->getdata();
		$this->load->library('jsonformat');
		$path = ($data[3] != "" ? $this->jsonformat->sanitize_file_name($data[3]) : 'untitled').".txt";
		$content = $this->jsonformat->json_pretty($data[2]);
		$this->load->helper('download');
		force_download($path,$content);
	}
	
}
