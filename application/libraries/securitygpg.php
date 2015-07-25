<?php
/**
 * Name: Securitygpg
 * @author Hiep Le
 * 
 * Description: This is to handle the signing and verification
 *
 */

class Securitygpg{
	
	/**
	 * Construction and configuration of the GnuPG
	 */
	function Securitygpg(){
		$CONFIG['gnupg_home'] = '/var/www/.gnupg';
		putenv("GNUPGHOME={$CONFIG['gnupg_home']}");

	}

	/**
	 * Verify a signature
	 * 
	 * @param string $signed_message
	 * @param string $public_key
	 * @param array $keyinfo the info of keys using to sign the form
	 * @return boolean
	 */
	function verify($signed_message, $public_key, &$keyinfo){
		$plaintext = "";
		$gnupg = new gnupg();
		
		//set mode error to display in the browser
		$gnupg->seterrormode(gnupg::ERROR_EXCEPTION);
		try{
			//import public key
			$public = $gnupg->import($public_key);
			
			//verify the signature
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

	
	/**
	 * Sign a text
	 * 
	 * @param string $message
	 * @param string $private_key
	 * @param string $pass_phrase
	 * @return string signed message
	 */
	function sign($message, $private_key, $pass_phrase){
		try{
			$gnupg = new gnupg();
			
			//set error mode and import key to sign
			$gnupg->seterrormode(gnupg::ERROR_EXCEPTION);
			$private = $gnupg->import($private_key);
			
			//add key to sign
			$gnupg->addsignkey($private['fingerprint'], $pass_phrase);
			
			//sign
			$signed_message = $gnupg->sign($message);
			
			//return signed message
			return $signed_message;
		} catch(Exception $e) {
  			echo 'Message: ' .$e->getMessage();
		}
		return null;
	}

	function authenticate(){

	}

	
	/**
	 * Get key info from fingerprint
	 * 
	 * 
	 * @param string $fingerprint
	 * @return array which contains key information respectively
	 */
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

	
	/**
	 * Get information of the key
	 * 
	 * @param string $key
	 * @return array which contains the information corresponding to the key
	 */
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

	
	/**
	 * Extraction of the plain text from signed text
	 * 
	 * @param string $signed_text
	 * @return string the plain text
	 */
	function extract_plaintext($signed_text){
		$plaintext = "";
		$gpg = new gnupg();
		$info = $gpg -> verify($signed_text,false,$plaintext);
		return $plaintext;
	}

	function extract_signature(){

	}
}
?>