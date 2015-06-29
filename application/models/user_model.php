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

	/*
	Check whether the current user has already had  a pair key
	*/
	function check_key_exists(){
		$em = $this->doctrine->em;
        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
		$cert = $em->getRepository('Entities\Certificate')->findOneByUser($user);
		return ($cert == null)? false : true;
	}

	/*
	Load the pair key information of the current user
	*/
	function load_user_pair_key($user_id = null){
		$em = $this->doctrine->em;
		if ($user_id != null)
			$user = $em->find('Entities\User',$user_id);
		else
        	$user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
		$cert = $em->getRepository('Entities\Certificate')->findOneByUser($user);
		return $cert;
	}

	function create_or_update_key($pub, $priv){
		$em = $this->doctrine->em;
		$user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
		$cert = $em->getRepository('Entities\Certificate')->findOneByUser($user);
		if ($cert == null){
			$cert = new Entities\Certificate();
		}
		$cert->setSecretKey($priv);
		$cert->setPubicKey($pub);
		$cert->setUser($user);

		$em->persist($cert);
		$em->flush();
		return $cert;
	}
}