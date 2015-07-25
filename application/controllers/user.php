<?php
/**
 * @author Hiep Le
 * 
 * Name: User controller
 * 
 * Description: This class aims to handle the request for the tasks related to users
 */

require_once 'base_controller.php';


class User extends Base_controller{
	
	function __construct(){
		parent::__construct();
		$this->load_base();
	}
	
	/**
	 * Get all of email of this application except the current user
	 */
	function get_all_emails(){
		$this->load->model('user_model');
		$emails = $this->user_model->get_all_emails();
		echo json_encode($emails);
	}
	

	/**
	 * Generate SSL pair-key, this function is not currently used in the application
	 */
	function genkey(){
			error_reporting(E_ERROR | E_PARSE);
			$pass = $this->input->post('pass');
			$this->load->model('user_model');

			$dn = array("countryName" => 'XX', "stateOrProvinceName" => 'State', "localityName" => 'SomewhereCity', "organizationName" => 'MySelf', "organizationalUnitName" => 'Whatever', "commonName" => 'mySelf', "emailAddress" => 'user@domain.com');
			$privkeypass = $pass;
			$numberofdays = 365;
			
			$privkey = openssl_pkey_new();
			$csr = openssl_csr_new($dn, $privkey);
			$sscert = openssl_csr_sign($csr, null, $privkey, $numberofdays);
			openssl_x509_export($sscert, $publickey);
			openssl_pkey_export($privkey, $privatekey, $privkeypass);
			//openssl_csr_export($csr, $csrStr);

			$publickey=openssl_pkey_get_public($publickey);
			
			$cert = $this->user_model->create_or_update_key($publickey, $privatekey);

			$data = array ('pri'=> $privatekey, 'pub' => $publickey);  // Will hold the exported PubKey
			echo json_encode($data);


			$dn = array();  // use defaults
			$res_privkey = openssl_pkey_new();
			$res_csr = openssl_csr_new($dn, $res_privkey);
			$res_cert = openssl_csr_sign($res_csr, null, $res_privkey, $ndays);

			openssl_x509_export($res_cert, $str_cert);

			$res_pubkey = openssl_pkey_get_public($str_cert);

	}

	/**
	 * Save the key-pair uploaded by user to DB, the key pair standard used in OpenPGP
	 * Return the tuple id if successful
	 */
	function savekey(){
		$pri = $this->input->post('priv');
		$pub = $this->input->post('pub');
		$this->load->model('user_model');
		$cert = $this->user_model->create_or_update_key($pub, $pri);
		echo $cert->getId();
	}
	
	
	/**
	 * Load the key-pair of the current user
	 * Return '' if there is no keypair for the current user, else return public key
	 */
	function loadkey(){
		$this->load->model('user_model');
		$cert = $this->user_model->load_user_pair_key();
		echo ($cert==null) ? "" : $cert->getPubicKey();
	}

	/**
	 * Get the key information of the current user, which contains the registration date, expiration date and the status of the key pair
	 */
	function loadkeyinfo(){
		$this->load->model('user_model');
		$cert = $this->user_model->load_user_pair_key();
		if ($cert == null){
			echo json_encode(array());
			return;
		}
		$this->load->library('securitygpg');
		$data = $this->securitygpg->get_information($cert->getPubicKey());
		echo json_encode($data);
		//echo $cert->getSecretKey();
	}
}