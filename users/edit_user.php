<?php
	include $_SERVER["DOCUMENT_ROOT"] . '/loadConfig.php';
	include $_SERVER["DOCUMENT_ROOT"] . '/private/conn_db.php';
	include $_SERVER["DOCUMENT_ROOT"] . '/mailtemplates/userverification.php';
	include $_SERVER["DOCUMENT_ROOT"] . '/log4php/Logger.php';
	include $_SERVER["DOCUMENT_ROOT"] . '/private/ssl/generateOTP.php';
	Logger::configure( $_SERVER["DOCUMENT_ROOT"] . '/config.xml');
	$GLOBALS['log'] = Logger::getLogger('myLogger');
	
	// define variables and set to empty values
	$error_arr = array();
	$firstName = $lastName = $email =  $camsSelected = $userId = "";
	$active = $sendVerification = 0;
	
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  $GLOBALS['log']->trace($_POST);	
	  if (empty($_POST["firstName"])) {
	    $error_arr["firstName"] = "First Name is required";
	  } else {
	    $firstName = test_input($_POST["firstName"]);
	    // check if name only contains letters and whitespace
	    if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
	      $error_arr["firstName"] = "Only letters and white space allowed";
	    }
	  }
	  
	  if (empty($_POST["lastName"])) {
	  	$error_arr["lastName"] = "Last Name is required";
	  } else {
	  	$lastName = test_input($_POST["lastName"]);
	  	// check if name only contains letters and whitespace
	  	if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
	  		$error_arr["lastName"] = "Only letters and white space allowed";
	  	}
	  }
	  
	  if (empty($_POST["userId"])) {
	  	$error_arr["userId"] = "User ID is required";
	  } else {
	  	$userId = test_input($_POST["userId"]);
	  	// check if name only contains letters and whitespace
	  	if (!preg_match("/^[a-zA-Z0-9]*$/",$userId)) {
	  		$error_arr["userId"] = "Only letters and Numbers allowed";
	  	}
	  }
	  
	 
	  
	  if (empty($_POST["email"])) {
	    $error_arr["email"] = "Email is required";
	  } else {
	    $email = test_input($_POST["email"]);
	    // check if e-mail address is well-formed
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	    	$error_arr["email"] = "Invalid email format";
	    }
	  }
	  
	  if (empty($_POST["camsSelected"])) {
	    $error_arr["camsSelected"] = "Please assign atleast one camera.";
	  } else {
	    $camsSelected = test_input($_POST["camsSelected"]);
	    // check if name only contains letters and whitespace
	    
	  }
	  $GLOBALS['log']->trace(' ACTIVE IS ' . $_POST["userActive"]);
	  if (empty($_POST["userActive"])) {
	  	$GLOBALS['log']->debug('Recieved InActive status for user');
	    $active = 0;
	  }else{	  	
	  	$GLOBALS['log']->debug('Recieved Active status for user');
	  	$active = 1;
	  }
	  
	  if (empty($_POST["sendVerification"])) {
	  	$GLOBALS['log']->debug('Received no request for user verification');
	   	$sendVerification = 0;
	  }else{
	  	$GLOBALS['log']->debug('Received send verfication email for user');
	    $sendVerification = 1;
	  }
	  
	  
	  
	  if (count($error_arr) > 0){
	  	header("HTTP/1.0 400 Bad Request");
	  	echo json_encode($error_arr);
	  	exit;
	  }else{
	  	$status = editUser($userId, $firstName, $lastName, $email, $camsSelected, $active, $sendVerification);
	  	if(empty($status)){
	  		header("HTTP/1.0 200 Success");
	  	}else{
	  		header("HTTP/1.0 400 Bad Request");
	  		echo $status;
	  	}
	  }
	  
	  
	} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
		if (empty($_GET["userId"])) {
	  		$error_arr["userId"] = "User ID is required";
	  	} else {
	  		$userId = test_input($_GET["userId"]);
			// check if name only contains letters and whitespace
			if (!preg_match("/^[a-zA-Z0-9]*$/",$userId)) {
				$error_arr["userId"] = "Only letters and Numbers allowed";
			}
		}
		if (count($error_arr) > 0){
	  		header("HTTP/1.0 400 Bad Request");
		  	echo json_encode($error_arr);
		  	exit;
	  	}else{
	  		echo  json_encode(getUser($userId));
	  	}
    }
	 

	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	
	function getUser($userId){
		$mysqli = getDbConn ();
		if(is_null($mysqli)){
			echo  "<br>". 'Cant do it boss';
		}
		
		if (! ($stmt = $mysqli->prepare ( "select usr.userID userId, firstName, lastName, email, group_concat(cameraID separator ',') as camsSelected,  if(usr.active = 1,'true','false') userActive from cam_user_assignment asgn, cam_users usr where asgn.userId = usr.userId and usr.userId = ? group by userID" ))) {
				return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			
			if (! $stmt->bind_param ( "s", $userId)) {
				return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (! $stmt->execute ()) {
				return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			$result = $stmt->get_result()->fetch_array();
			
			/* explicit close recommended */
			$stmt->close ();
			
			return $result;
		
	}

	function editUser($userId, $firstName, $lastName, $email, $camsSelected, $active, $sendVerification) {
		$GLOBALS['log']->debug('Editing user database');
		$mysqli = getDbConn ();
		if(is_null($mysqli)){
			echo  "<br>". 'Cant do it boss';
		}else {
			if($sendVerification == 1){
				$GLOBALS['log']->debug('Marking user for email to be verified');
				$otpHandler = new OTPHandler();
				list ($rand, $otp) = $otpHandler->generateOTP();
				
				if (! ($stmt = $mysqli->prepare ( "UPDATE cam_users set firstName = ?,lastName = ?, email = ?, active = ?, email_verify = ?, email_verify_timestamp = ?, emailVerified  = ? WHERE userId = ?" ))) {
					return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				}
				
				$currDate = date_create()->format('Y-m-d H:i:s');
				$emailVerified = 0;
			
				if (! $stmt->bind_param ( "sssissis", $firstName, $lastName, $email, $active, $rand, $currDate, $emailVerified, $userId)) {
					return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				}
			
				if (! $stmt->execute ()) {
					return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				}
			
				/* explicit close recommended */
				$stmt->close ();
				
				$GLOBALS['log']->debug('Sending verification email');
				sendVerificationMail($email,urlencode($otp),$uid);
			}else{
				$GLOBALS['log']->debug('Saving user information');
				if (! ($stmt = $mysqli->prepare ( "UPDATE cam_users set firstName = ?,lastName = ?, email = ?, active = ? WHERE userId = ?" ))) {
				return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				}
			
			
				if (! $stmt->bind_param ( "sssis", $firstName, $lastName, $email, $active, $userId)) {
					return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				}
			
				if (! $stmt->execute ()) {
					return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				}
			
				/* explicit close recommended */
				$stmt->close ();
			}
			
			$GLOBALS['log']->debug('Deleting user camera assignment');
			if (! ($stmt = $mysqli->prepare ( "DELETE from  cam_user_assignment WHERE userId = ?" ))) {
				return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			
			
			if (! $stmt->bind_param ( "s", $userId)) {
				return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (! $stmt->execute ()) {
				return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			/* explicit close recommended */
			$stmt->close ();
			
			$GLOBALS['log']->debug('Saving new user camera assignment');
			if (! ($stmt1 = $mysqli->prepare ( "INSERT INTO cam_user_assignment(cameraID,userID) VALUES (?,?)" ))) {
				return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			
			
			foreach (explode(",", $camsSelected) as $cameraID) {
				if (! $stmt1->bind_param ( "ss", $cameraID, $userId)) {
				return "Binding parameters failed: (" . $stmt1->errno . ") " . $stmt1->error;
				}
			
				if (! $stmt1->execute ()) {
					return "Execute failed: (" . $stmt1->errno . ") " . $stmt1->error;
				}
			}

			/* explicit close recommended */
			$stmt1->close ();
			
			return "";
		}
		
		
	}
	

	

?>