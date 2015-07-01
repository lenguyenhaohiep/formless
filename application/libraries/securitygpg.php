<?php
class Securitygpg{
	function Securitygpg(){
		$CONFIG['gnupg_home'] = '/var/www/.gnupg';
		putenv("GNUPGHOME={$CONFIG['gnupg_home']}");

	}

	function verify($signed_message, $public_key, &$keyinfo){
		$plaintext = "";
		$gnupg = new gnupg();
		$gnupg->seterrormode(gnupg::ERROR_EXCEPTION);
		try{
			$public = $gnupg->import($public_key);
			$info = $gnupg->verify($signed_message, false, $plaintext);
			if($info !== false){
				  $fingerprint = $info[0]['fingerprint'];
				  $fingerprint_publickey = $public['fingerprint'];

				  //in case success, it will return FINGERPRINT, otherwise, KEYID 
				  $keyinfo = $fingerprint;
				  return $fingerprint == $fingerprint_publickey;
			}
			else{
				echo "verify failed";
			}
		}
		catch(Exception $e) {
  			echo 'Message: ' .$e->getMessage();
		}

		return false;
	}

	function sign($message, $private_key, $pass_phrase){
		try{
			$gnupg = new gnupg();
			$gnupg->seterrormode(gnupg::ERROR_EXCEPTION);
			$private = $gnupg->import($private_key);
			$gnupg->addsignkey($private['fingerprint'], $pass_phrase);
			$signed_message = $gnupg->sign($message);
			return $signed_message;
		} catch(Exception $e) {
  			echo 'Message: ' .$e->getMessage();
		}
		return null;
	}

	function authenticate(){

	}

	function get_by_fingerprint($fingerprint){
			$gnupg = new gnupg();
			$detail = $gnupg->keyinfo($fingerprint);
			$infouser = ($detail[0]['uids'][0]);
			$infokey = ($detail[0]['subkeys'][0]);
			$data = array(
				'name' => $infouser['name'],
				'email' => $infouser['email'],
				'reg' => date('d-M-Y H:i:s', $infokey['timestamp']),
				'exp' => date('d-M-Y H:i:s', $infokey['expires']),
				'valid' => $infokey['invalid'] == "" ? "valid" : "invalid",
				'expired' => $infokey['expired'] == "" ? "active" : "expired"
			);
			return $data;
	}

	function get_information($key){
		$gnupg = new gnupg();
		$gnupg->seterrormode(gnupg::ERROR_EXCEPTION);
		try{
			$info = $gnupg->import($key);
		}catch(Exception $e){
			echo $e;
		}
		if ($info !== false){
			$data = $this->get_by_fingerprint($info['fingerprint']);
			return $data;
		}
		return null;
	}

	function extract_plaintext($signed_text){
		$plaintext = "";
		$gpg = new gnupg();
		// signé en clair
		$info = $gpg -> verify($signed_text,false,$plaintext);
		echo "dadasdsa<br/>";
		print_r($info);
		return $plaintext;
	}

	function extract_signature(){

	}
}
?>