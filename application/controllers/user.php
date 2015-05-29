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
		$dn = array("countryName" => 'XX', "stateOrProvinceName" => 'State', "localityName" => 'SomewhereCity', "organizationName" => 'MySelf', "organizationalUnitName" => 'Whatever', "commonName" => 'mySelf', "emailAddress" => 'user@domain.com');
		$privkeypass = '1234';
		$numberofdays = 365;
		
		$privkey = openssl_pkey_new();
		$csr = openssl_csr_new($dn, $privkey);
		$sscert = openssl_csr_sign($csr, null, $privkey, $numberofdays);
		openssl_x509_export($sscert, $publickey);
		openssl_pkey_export($privkey, $privatekey, $privkeypass);
		openssl_csr_export($csr, $csrStr);
		
		echo $privatekey; // Will hold the exported PriKey
		echo '<br/>';
		echo $publickey;  // Will hold the exported PubKey
		echo '<br/>';
		echo $csrStr;     // Will hold the exported Certificate
	}
	
	function sign(){
		// init gnupg
		$res = gnupg_init();
		// not really needed. Clearsign is default
		gnupg_setsignmode($res,GNUPG_SIG_MODE_CLEAR);
		// add key with passphrase 'test' for signing
		gnupg_addsignkey($res,"8660281B6051D071D94B5B230549F9DC851566DC","test");
		// sign
		$signed = gnupg_sign($res,"just a test");
		echo $signed;
	}
}