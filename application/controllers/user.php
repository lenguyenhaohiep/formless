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

			$CONFIG['gnupg_home'] = '/Applications/XAMPP/htdocs/.gnupg/';
			$CONFIG['gnupg_fingerprint'] = 'FA451EE9877270EF1CFA99CE048A613921CCC3D6';
   
			$data = 'this is some confidential information';

			$gpg = new gnupg();
			putenv("GNUPGHOME={$CONFIG['gnupg_home']}");
			$gpg->seterrormode(GNUPG_ERROR_WARNING);
			$gpg->addencryptkey($CONFIG['gnupg_fingerprint']);
			$encrypted =  $gpg->encrypt($data);
			echo "Encrypted text: \n<pre>$encrypted</pre>\n";

			// Now you can store $encrypted somewhere.. perhaps in a MySQL text or blob field.

			// Then use something like this to decrypt the data.
			$passphrase = 'Your_secret_passphrase';
			$gpg->adddecryptkey($CONFIG['gnupg_fingerprint'], $passphrase);
			$decrypted = $gpg->decrypt($encrypted);

			echo "Decrypted text: $decrypted";



	}
	function sign() {
	    $keyring = "/pubkeys/.gnupg";
	    putenv("GNUPGHOME=$keyring");
		$GnuPG = new gnupg();
		$GnuPG->seterrormode(GNUPG_ERROR_WARNING);
		$PublicData = file_get_contents('/Users/hieple/public.key');
		$PrivateData = file_get_contents('/Users/hieple/public.key');

		$PublicKey = $GnuPG->import($PublicData);
		$PrivateKey = $GnuPG->import($PrivateData);

		echo 'Public Key : ',$PublicKey['fingerprint'],' & Private Key : ',$PrivateKey['fingerprint'];
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