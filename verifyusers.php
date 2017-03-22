
<?php

	include $_SERVER["DOCUMENT_ROOT"] . '/loadConfig.php';
	include $_SERVER["DOCUMENT_ROOT"] . '/log4php/Logger.php';
	Logger::configure( $_SERVER["DOCUMENT_ROOT"] . '/config.xml');
	$GLOBALS['log'] = Logger::getLogger('myLogger');
	
	include $_SERVER["DOCUMENT_ROOT"] . '/private/conn_db.php';
	// define variables and set to empty values
	include $_SERVER["DOCUMENT_ROOT"] . '/private/ssl/generateOTP.php';
	
	$userId = $verifyChain =  "";
	$GLOBALS['log']->info("Verifing email OTP.");
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$userId = $_GET["userId"];
		$verifyChain = $_GET["verifyChain"];
		
		$GLOBALS['log']->trace("userId " . $userId);
		$GLOBALS['log']->trace("verifychain " . $_GET["verifyChain"]);
		
		if (empty($userId)) {
			$error_arr["userId"] = "User Id is required";
			$GLOBALS['log']->error("User Id is required.");
		} else {
			$userId = test_input($userId);
			// check if name only contains letters and whitespace
			if (!preg_match("/^[a-zA-Z0-9]*$/",$userId)) {
				$error_arr["userId"] = "Only letters and Numbers allowed";
				$GLOBALS['log']->error("Only letters and Numbers allowed for userId.");
			}
		}


		if (empty($verifyChain)) {
			$error_arr["verifyChain"] = "verifychain is required";
			$GLOBALS['log']->error("verifychain is required.");
		} else {
			$verifyChain = test_input($verifyChain);
			// check if name only contains letters and whitespace
			/*if (!preg_match("/^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?$/",$verifyChain)) {
				$error_arr["verifyChain"] = "Only letters and Numbers allowed";
				$GLOBALS['log']->error("Only letters and Numbers allowed for verifychain.");
			}*/
		}
		//$verifyChain = urldecode($verifyChain);
		$ret = verifyChain($verifyChain, $userId, $GLOBALS['log']);
		if(empty($ret)){
			echo "<br>". '<h1>You can view your assigned camera here</h1>';
		} else {
			$GLOBALS['log']->error($ret);
			echo "<br>". '<h1>Could not verify you email. PLease contact system administrator</h1>';
		}
	}
	

	function verifyChain($verifyChain, $userId){
		$GLOBALS['log']->debug("ValIdating email for user " . $userId);
		$mysqli = getDbConn ();
		if(is_null($mysqli)){
			$GLOBALS['log']->error("could not get database connection.");
			return "Could not get Database connection ";
		}else {
			
			if (! ($stmt = $mysqli->prepare ( "SELECT email_verify, email_verify_timestamp FROM cam_users where userId = ?" ))) {
				return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			
			
			if (! $stmt->bind_param ( "s", $userId)) {
				return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (! $stmt->execute ()) {
				return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (!($res = $stmt->get_result())) {
    			return "Getting result set failed: (" . $stmt->errno . ") " . $stmt->error;
    		}
    					
    		$row = $res->fetch_array();
			
			if(count($row) < 1){
				
				return "No data found for user";
			}
			$GLOBALS['log']->debug("ValIdating email for user " . $userId);
			//echo 'llll ->>' . $userId . ' ---  RAND  --- ' . $row['email_verify'] . ' --- TIME  ---' . $row['email_verify_timestamp'];
			/*
				Not implementing the resend valIdation email for now. 
				May choose to enable function in the future.
				
				if(valIdateOTP($row['email_verify'], $verifyChain) && isValIdDate( $row['email_verify_timestamp'])){
			*/
			$otpHandler = new OTPHandler();
			if($otpHandler->valIdateOTP($row['email_verify'], $verifyChain)){
				if (! ($stmt = $mysqli->prepare ( "UPDATE cam_users set emailVerified = ? where userId = ?" ))) {
					return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				}
			
				$emailVerified = 1;
				if (! $stmt->bind_param ( "is", $emailVerified,$userId)) {
					return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				}
			
				if (! $stmt->execute ()) {
					return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				}
			}else{
				return 'INVALId OTP';
			}
		}
		return "";
	}


	
	function isValIdDate($verificationDate){
		$date =  date_create();
		//echo date_create()->format('Y-m-d H:i:s');
		$date1 = DateTime::createFromFormat('Y-m-d H:i:s', $verificationDate);
		#echo $date->format('Y-m-d H:i:s') , $date1->format('Y-m-d H:i:s');
		$diff = $date1->diff($date,false);
		if ($date < $date1 || $diff->days > 0 || $diff->h > 0 || $diff->i > 5){
			echo false;
		}else{
		
		echo true;
		}
	}
	
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

?>