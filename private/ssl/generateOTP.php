<?php

	class OTPHandler {
 		private $log;
 		
 		public function __construct()
		{
			// The __CLASS__ constant holds the class name, in our case "Foo".
			// Therefore this creates a logger named "Foo" (which we configured in the config file)
			$this->log = Logger::getLogger(__CLASS__);
		}
		
		function generateOTP(){
			$rand = uniqid("",true);
			$pkeyid = openssl_pkey_get_private("file://" . $_SERVER["DOCUMENT_ROOT"] . "/private/ssl/private.pem");
			openssl_private_encrypt($rand, $encrypted, $pkeyid,OPENSSL_PKCS1_PADDING);
			openssl_free_key($pkeyid);
			return array($rand, base64_encode($encrypted));
		}

		function validateOTP($rand, $encrypted){
			$this->log->debug('compairing OTP ' . $encrypted . 'with value ' . $rand);
			//echo  'checking ' . $rand . ' against ' . $encrypted . 'TEXT  ';
			$pubKey = openssl_pkey_get_public("file://" . $_SERVER["DOCUMENT_ROOT"] . "/private/ssl/public.pem");

			openssl_public_decrypt(base64_decode($encrypted), $decrypted, $pubKey, OPENSSL_PKCS1_PADDING);
			$this->log->debug('decrypted value id ' . $decrypted);
			return  strcmp($rand, $decrypted) == 0;
		}
	}
	

	
	

?>