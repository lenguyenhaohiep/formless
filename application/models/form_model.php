<?php
class Form_model extends CI_Model{
	
	private $em;
	
	function __construct(){
		parent::__construct();
		$this->em = $this->doctrine->em;
	}
	
	/**
	 * Create new form 
	 * @param unknown $title
	 * @param unknown $type_id
	 * @param unknown $status
	 * @param unknown $path_form
	 */
	function create_or_update_form($form_id=NULL, $title, $type_id, $status, $path_form){		
		//get type of form
		$type = $this->em->find('Entities\Type', $type_id);
		
		//create new form
		if ($form_id==null)
			$form = new Entities\Form;
		else 
			$form = $this->em->find('Entities\Form', $form_id);
		if (isset($form)){
			$form->setType($type);
			$form->setTitle($title);
			$form->setPathForm($pathForm);
			if ($form_id == null)
				$form->setStatus($status);
			$form->setCreatedDate(new DateTime());
			
			$this->em->persist($form);
			$this->em->flush();
			return $form;
		}
	}
	
	function modify_form($form_id, $user_id){
		//get the current form
		$form = $this->em->find('Entities\Form', $form_id);
		
		//get the user who modifies the form
		$user = $this->em->find('Entities\User', $user_id);
		
		//Create new line in the modify history
		$modify = new Entities\Modify_history;
		$modify->setForm($form);
		$modify->setUser($user);
		$modify->setModifiedDate(new DateTime());
		
		$this->em->persist($form);
	}
	
	function delete_from($form_id){
		$form = $this->em->find('Entities\Form', $form_id);
		if (isset($form)){
			$this->em->remove($form);
			$this->em->flush();
		}
	}
	
	function send_form($form_id=NULL, $title, $type_id, $status, $path_form, $from_user_id, $to_user_id, $message){
		$form = $this->create_or_update_form($form_id, $title, $type_id, $status, $path_form);
		
		//get from user
		$from_user = $this->em->find('Entities\User',$from_user_id);
		//get to users
		$to_user = $this->em->find('Entities\User',$to_user_id);
		
		$sending = new Entities\Send_history;
		$sending->setForm($form);
		$sending->setFromUser($from_user);
		$sending->setToUser($to_user);
		$sending->setMessage($message);
		$sending->setSendDate(new DateTime());
		$sending->setStatus($status=0);
		
		$this->em->persist($sending);
		$this->em->flush();
		
	}
	
	function get_inbox($user_id){
		$user = $this->em->find('Entities\User',$user_id);		
		$inbox = $this->em->getRepository('Entities\Send_history')->findBy(array('status'=>0,'to_user'=>$user));
		return $inbox;
	}
	
	function get_sent($user_id){
		$user = $this->em->find('Entities\User',$user_id);
		$sent = $this->em->getRepository('Entities\Send_history')->findBy(array('status'=>0,'from_user'=>$user));
		return $sent;
	}
	
	function get_draft($user_id){
		$user = $this->em->find('Entities\User',$user_id);
		$draft = $this->em->getRepository('Entities\Form')->findBy(array('status'=>0,'user'=>$user));
		return $draft;
	}
	
	function get_all_forms($user_id){
		$user = $this->em->find('Entities\User',$user_id);
		$forms = $this->em->getRepository('Entities\Form')->findBy(array('user'=>$user));
		return $forms;
	}
	
	function get_history_modification($form_id){
		$form = $this->em->find('Entities\Form',$form_id);
		$history = $this->em->getRepository('Entities\Modify_history')->findBy(array('form'=>$form));
	}
	
	function get_all_receivers($form_id){
		$form = $this->em->find('Entities\Form',$form_id);
	}
	
	function get_message_by_receiver($form_id,$sender_id, $receiver_id){
		$to_user = $this->em->find('Entities\User',$receiver_id);
		$from_user = $this->em->find('Entities\User',$sender_id);	
	}
}