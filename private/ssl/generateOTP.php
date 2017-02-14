<?php

function generateOTP(){
	$rand = uniqid("",true);
	$pkeyid = openssl_pkey_get_private("file://" . $_SERVER["DOCUMENT_ROOT"] . "/private/ssl/private.pem");
	openssl_private_encrypt($rand, $encrypted, $pkeyid,OPENSSL_PKCS1_PADDING);
	openssl_free_key($pkeyid);
	return array($rand, base64_encode($encrypted));
}

function validateOTP($rand, $encrypted){
	//echo  'checking ' . $rand . ' against ' . $encrypted . 'TEXT  ';
	$pubKey = openssl_pkey_get_public("file://" . $_SERVER["DOCUMENT_ROOT"] . "/private/ssl/public.pem");

	openssl_public_decrypt(base64_decode($encrypted), $decrypted, $pubKey, OPENSSL_PKCS1_PADDING);
	return  strcmp($rand, $decrypted) == 0;
}

?>