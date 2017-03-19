<?php

function sendVerificationMail($to, $otp, $userID){
	$subject = 'Verify enrollemnt to access';

	$headers = "From: link2tdss@yahoo.com"  . "\r\n";
	$headers .= "Reply-To: link2tdss@yahoo.com". "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$message = '<html><body> ';
	$message .= '<h1><a href="http://192.168.1.6/verifyusers.php?userId=' . $userID . '&verifyChain=' . $otp . '">Please verify your email by clicking this link</a></h1>';
	$message .= '</body></html>';

	if(mail($to,$subject,$message,$headers))
		return true;
	else
		return false;
}




?>