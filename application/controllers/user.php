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

		putenv("GNUPGHOME=~./.gnupg/");
		$pubkey = "-----BEGIN PGP PUBLIC KEY BLOCK-----
		Version: GnuPG v1.2.6 (GNU/Linux)

		mQGiBEe68W8RBACVuFuv4d+roDSCdRO1SuO8dQwds4VTjVOqgVKQtq6+8Fe95RY8
		BAf1IyLj4bxvWPhr0wZdVwTosD/sFoPtdCyhVcF932nP0GLHsTEeVwSz9mid22HI
		O4Kmwj2kE+I+C9QdzAg0zaWQnVaF9UC7pIdMR6tEnADI8nkVDdZ+zb2ziwCg6Yqu
		tk3KAzKRT1SNUzTE/n9y2PED/1tIWiXfGBGzseX0W/e1G+MjuolWOXv4BXeiFGmn
		8wnHsQ4Z4Tzk+ag0k+6pZZXjcL6Le486wpZ9MAe6LM31XDpQDVtyCL8t63nvQpB8
		TUimbseBZMb3TytCubNLGFe5FnNLGDciElcD09d2xC6Xv6zE2jj4GtBW1bXqYWtl
		jm0PA/4u6av6o6pIgLRfAawspr8kaeZ8+FU4NbIiS6xZmBUEQ/o7q95VKGgFVKBi
		ugDOlnbgSzBIwSlsRVT2ivu/XVWnhQaRCotSm3AzOc2XecqrJ6F1gqk0n+yP/1h1
		yeTvvfS5zgqNTG2UmovjVsKFzaDqmsYZ+sYfwc209z9PY+6FuLQnQXBhY2hlVGVz
		dCAoVGVzdGluZykgPGFwYWNoZUBsb2NhbGhvc3Q+iF4EExECAB4FAke68W8CGwMG
		CwkIBwMCAxUCAwMWAgECHgECF4AACgkQJE9COu2PFIEGDwCglArzAza13xjbdR04
		DQ1U9FWQhMYAnRrWQeGTRm+BYm6SghNpDOKcmMqruQENBEe68XAQBADPIO+JFe5t
		BQmI4l60bNMNSUqsL0TtIP8G6Bpd8q2xBOemHCLfGT9Y5DN6k0nneBQxajSfWBQ5
		ZdKFwV5ezICz9fnGisEf9LPSwctfUIcvumbcPPsrUOUZX7BuCHrcfy1nebS3myO/
		ScTKpW8Wz8AjpKTBG55DMkXSvnx+hS+PEwADBQP/dNnVlKYdNKA70B4QTEzfvF+E
		5lyiauyT41SQoheTMhrs/3RIqUy7WWn3B20aTutHWWYXdYV+E85/CarhUmLNZGA2
		tml1Mgl6F2myQ/+MiKi/aj9NVhcuz38OK/IAze7kNJJqK+UEWblB2Wfa31/9nNzv
		ewVHa1xHtUyVDaewAACISQQYEQIACQUCR7rxcAIbDAAKCRAkT0I67Y8UgRwEAKDT
		L6DwyEZGLTpAqy2OLUH7SFKm2ACgr3tnPuPFlBtHx0OqY4gGiNMJHXE=
		=jHPH
		-----END PGP PUBLIC KEY BLOCK-----";

		$enc = (null);
		$res = gnupg_init();
		gnupg_seterrormode($res, GNUPG_ERROR_WARNING);
		echo "gnupg_init RTV = <br/><pre>\n";
		var_dump($res);
		echo "</pre>\n";
		$rtv = gnupg_import($res, $pubkey);
		echo "gnupg_import RTV = <br/><pre>\n";
		var_dump($rtv);
		echo "</pre>\n";
		$rtv = gnupg_addencryptkey($res, "C25F29936D9046D73A77DCF8244F423AED8F1481");
		echo "gnupg_addencryptkey RTV = <br /><pre>\n";
		var_dump($rtv);
		echo "</pre>\n";
		$enc = gnupg_encrypt($res, "just a test to see if anything works");
		echo "Encrypted Data: " . $enc . "<br/>";
	}

	function genkey(){
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