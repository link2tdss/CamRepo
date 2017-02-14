
<?php
	
	include $_SERVER["DOCUMENT_ROOT"] . '/private/conn_db.php';
	// define variables and set to empty values
	include $_SERVER["DOCUMENT_ROOT"] . '/private/ssl/generateOTP.php';
	$userId = $verifyChain =  "";
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
	  if (empty($_GET["verifyChain"]) || empty($_GET["userID"])) {
	    echo 'Invalid Post';
	  } else {
		$userId = $_GET["userID"];
		$verifyChain = $_GET["verifyChain"];
		//$verifyChain = urldecode($verifyChain);
		verifyChain($verifyChain, $userId);
		
	  }
	}

	function verifyChain($verifyChain, $userId){
		$mysqli = getDbConn ();
		if(is_null($mysqli)){
			echo  "<br>". 'Cant do it boss';
		}else {
			
			if (! ($stmt = $mysqli->prepare ( "SELECT email_verify, email_verify_timestamp FROM cam_users where userID = ?" ))) {
				return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			
			
			if (! $stmt->bind_param ( "s", $userId)) {
				return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (! $stmt->execute ()) {
				return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (!($res = $stmt->get_result())) {
    			echo "Getting result set failed: (" . $stmt->errno . ") " . $stmt->error;
    		}
    					
    		$row = $res->fetch_array();
			

			//echo 'llll ->>' . $userId . ' ---  RAND  --- ' . $row['email_verify'] . ' --- TIME  ---' . $row['email_verify_timestamp'];
			/*
				Not implementing the resend validation email for now. 
				May choose to enable function in the future.
				
				if(validateOTP($row['email_verify'], $verifyChain) && isValidDate( $row['email_verify_timestamp'])){
			*/
			if(validateOTP($row['email_verify'], $verifyChain))){
				if (! ($stmt = $mysqli->prepare ( "UPDATE cam_users set emailVerified = ? where userID = ?" ))) {
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
				echo 'INVALID OTP';
			}
		}
	}


	
	function isValidDate($verificationDate){
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

?>