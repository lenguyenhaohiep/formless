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
	

	function certificate() {
		$config['certificate_rules'] =  array(
				'name' => array(
						'field' => 'name',
						'label' => 'Name',
						'rules' => 'trim|required|xss_clean'
				),
				'email' => array(
						'field' => 'email',
						'label' => 'Email',
						'rules' => 'trim|required|valid_email'
				),
				'subject' => array(
						'field' => 'subject',
						'label' => 'Subject',
						'rules' => 'trim|required|xss_clean'
				),
				'message' => array(
						'field' => 'message',
						'label' => 'Message',
						'rules' => 'trim|required|xss_clean'
				)
		);
		
		$this->render_page(lang('signature_page_title'), "", 'user/certificate', '');
	}
	
	function key(){

	}
	function sign() {
	    $keyring = "/pubkeys/.gnupg";
	    putenv("GNUPGHOME=$keyring");
		$GnuPG = new gnupg();
		$GnuPG->seterrormode(GNUPG_ERROR_WARNING);
		$PublicData = "-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
OKxbKfTH3fQuw69ChQX7WzXV
=d08a
-----END PGP PUBLIC KEY BLOCK-----";

		$PublicKey = $GnuPG->import($PublicData);
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
			openssl_csr_export($csr, $csrStr);
			
			$cert = $this->user_model->create_or_update_key($publickey, $privatekey);

			$data = array ('pri'=> $privatekey, 'pub' => $publickey);  // Will hold the exported PubKey
			echo json_encode($data);

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
}