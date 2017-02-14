
<?php
	

	// define variables and set to empty values
	include 'private/ssl/generateOTP.php';
	$rand = $otp =  "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (empty($_POST["otp"])) {
	    list ($rand, $otp) = generateOTP();
	    echo $rand. '  ---  ' . $otp . 'Done';
	  } else {
		if (validateOTP($_POST["rand"], $_POST["otp"]))
			echo "They match";
		else
			echo "They Do not match";
		
	  }
	}


?>