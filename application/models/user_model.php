<?php
class User_model extends CI_Model{
	
	function get_id_from_email($email){
		$em = $this->doctrine->em;
		$user = $em->getRepository('Entities\User')->findOneByEmail($email);
		return $user->getId();
	}
	
	function get_user_from_email($email){
		$em = $this->doctrine->em;
		$user = $em->getRepository('Entities\User')->findOneByEmail($email);
		return $user;
	}
	function get_all_emails() {
		$em = $this->doctrine->em;
		$users = $em->getRepository ( 'Entities\User' )->findAll ();
		
		$emails = array ();
		
		foreach ( $users as $user ) {
			if (strcmp ( $user->getEmail (), $this->session->userdata ( 'identity' ) ) !== 0) {
				$firstname = $user->getFirstName ();
				$lastname = $user->getLastName ();
				$email = $user->getEmail ();
				$emails [] = "$firstname $lastname ($email)";
			}
		}
		return $emails;
	}
	
	function get_public_key($user_id){
		$em = $this->doctrine->em;
		
		$user = $em->find('Entities\User',$user_id);
		$cert = $em->getRepository('Entities\Certificate')->findOneByUser($user);
		return $em->getPublicKey(); 
	}
	
	function get_secret_key($user_id){
		$em = $this->doctrine->em;
	
		$user = $em->find('Entities\User',$user_id);
		$cert = $em->getRepository('Entities\Certificate')->findOneByUser($user);
		return $em->getSecretKey();
	}
	
	function generate_key_pair($key_pass){
		
	}
}