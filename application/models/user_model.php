<?php
/**
 * Name : User model
 * @author Hiep Le
 * @abstract This class is to access user information
 */
class User_model extends CI_Model {
	
	/**
	 * Get id of a user from his email
	 * 
	 * @param {string} $email email of a user
	 * @return {integer} if of user by email
	 */
	function get_id_from_email($email) {
		$em = $this->doctrine->em;
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $email );
		return $user->getId ();
	}
	
	
	/**
	 * Get a user object from his email 
	 * @param {string} $email
	 * @return User object
	 */
	function get_user_from_email($email) {
		$em = $this->doctrine->em;
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $email );
		return $user;
	}
	
	
	/**
	 * Get all email of users in the systems except current user
	 * 
	 * @return {array} list of emails
	 */
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
	
	
	/**
	 * Get the public key of a user by his id
	 * @param {int} $user_id
	 * @return {string} public key value
	 */
	function get_public_key($user_id) {
		$em = $this->doctrine->em;
		
		$user = $em->find ( 'Entities\User', $user_id );
		$cert = $em->getRepository ( 'Entities\Certificate' )->findOneByUser ( $user );
		return $em->getPublicKey ();
	}
	
	
	/**
	 * Get the private key of a user by his id
	 * @param {int} $user_id
	 * @return {string} private key value
	 */
	function get_secret_key($user_id) {
		$em = $this->doctrine->em;
		
		$user = $em->find ( 'Entities\User', $user_id );
		$cert = $em->getRepository ( 'Entities\Certificate' )->findOneByUser ( $user );
		return $em->getSecretKey ();
	}
	
	
	/**
	 * Generate a key-pair for a user based on the passphrase
	 * Not implemented yet
	 * @param {string} $key_pass
	 */
	function generate_key_pair($key_pass) {
	}
	
	
	/**
	 * Check whether the current user has already had a pair key
	 * @return {bool} 
	 */
	function check_key_exists() {
		$em = $this->doctrine->em;
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$cert = $em->getRepository ( 'Entities\Certificate' )->findOneByUser ( $user );
		return ($cert == null) ? false : true;
	}
	
	/**
	 * Load the pair key information of the current user
	 * @param {int} $user_id
	 * @return {object} Certificate object 
	 */
	function load_user_pair_key($user_id = null) {
		$em = $this->doctrine->em;
		if (isset ( $user_id )) {
			$user = $em->find ( 'Entities\User', $user_id );
		} else
			$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$cert = $em->getRepository ( 'Entities\Certificate' )->findOneByUser ( $user );
		return $cert;
	}
	
	/**
	 * Create or update the key pair of current user
	 * @param {string} $pub public key
	 * @param {string} $priv private key
	 * @return \Entities\Certificate
	 */
	function create_or_update_key($pub, $priv) {
		$em = $this->doctrine->em;
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$cert = $em->getRepository ( 'Entities\Certificate' )->findOneByUser ( $user );
		if ($cert == null) {
			$cert = new Entities\Certificate ();
		}
		$cert->setSecretKey ( $priv );
		$cert->setPubicKey ( $pub );
		$cert->setUser ( $user );
		
		$em->persist ( $cert );
		$em->flush ();
		return $cert;
	}
}