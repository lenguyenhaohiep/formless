<?php

require_once 'base_controller.php';


class User extends Base_controller{
	
	function __construct(){
		parent::__construct();
		$this->load_base();
	}
	
	function get_all_emails(){
		$this->load->model('user_model');
		$emails = $this->user_model->get_all_emails();
		echo json_encode($emails);
	}
	

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

	function savekey(){
		$pri = $this->input->post('priv');
		$pub = $this->input->post('pub');
		$this->load->model('user_model');
		$cert = $this->user_model->create_or_update_key($pub, $pri);
		echo $cert->getId();
	}

	function loadkey(){
		$this->load->model('user_model');
		$cert = $this->user_model->load_user_pair_key();
		echo ($cert==null) ? "" : $cert->getPubicKey();
	}

	function loadkeyinfo(){
		$this->load->model('user_model');
		$cert = $this->user_model->load_user_pair_key();
		$this->load->library('securitygpg');
		$data = $this->securitygpg->get_information($cert->getSecretKey());
		echo json_encode($data);
		//echo $cert->getSecretKey();
	}
}