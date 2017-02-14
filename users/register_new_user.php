<?php
	
	include '../private/ssl/generateOTP.php';
	include $_SERVER["DOCUMENT_ROOT"] . '/mailtemplates/userverification.php';
	// define variables and set to empty values
	$error_arr = array();
	$fname = $lname = $email = $uid = $password = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (empty($_POST["fname"])) {
	    $error_arr["fname"] = "First Name is required123";
	  } else {
	    $fname = test_input($_POST["fname"]);
	    // check if name only contains letters and whitespace
	    if (!preg_match("/^[a-zA-Z ]*$/",$fname)) {
	      $error_arr["fname"] = "Only letters and white space allowed";
	    }
	  }
	  
	  if (empty($_POST["lname"])) {
	  	$error_arr["lname"] = "Last Name is required";
	  } else {
	  	$lname = test_input($_POST["lname"]);
	  	// check if name only contains letters and whitespace
	  	if (!preg_match("/^[a-zA-Z ]*$/",$lname)) {
	  		$error_arr["lname"] = "Only letters and white space allowed";
	  	}
	  }
	  
	  if (empty($_POST["uid"])) {
	  	$error_arr["uid"] = "User ID is required";
	  } else {
	  	$uid = test_input($_POST["uid"]);
	  	// check if name only contains letters and whitespace
	  	if (!preg_match("/^[a-zA-Z0-9]*$/",$uid)) {
	  		$error_arr["uid"] = "Only letters and Numbers allowed";
	  	}
	  }
	  
	  if (empty($_POST["password"])) {
	  	$error_arr["password"] = "password is required";
	  } else {
	  	$password = password_hash(test_input($_POST["password"]), PASSWORD_BCRYPT);
	  	
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
	  
	  
	  if (count($error_arr) > 0){
	  	header("HTTP/1.0 400 Bad Request");
	  	echo json_encode($error_arr);
	  	exit;
	  }else{
	  	include '../private/conn_db.php';
	  	$status = saveUser($uid, $password, $fname, $lname, $email);
	  	if(empty($status)){
	  		header("HTTP/1.0 200 Success");
	  	}else{
	  		header("HTTP/1.0 400 Bad Request");
	  		echo $status;
	  	}
	  }
	  
	  
	} else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }
	  /*if (empty($_POST["website"])) {
	    $website = "";
	  } else {
	    $website = test_input($_POST["website"]);
	    // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
	    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
	      $websiteErr = "Invalid URL";
	    }
	  }*/
	
	  /*
	  if (empty($_POST["comment"])) {
	    $comment = "";
	  } else {
	    $comment = test_input($_POST["comment"]);
	  }*/

	  /*
	  if (empty($_POST["gender"])) {
	    $genderErr = "Gender is required";
	  } else {
	    $gender = test_input($_POST["gender"]);
	  }
	}
	*/

	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	function saveUser($uid, $password, $fname, $lname, $email) {
		$mysqli = getDbConn ();
		if(is_null($mysqli)){
			echo  "<br>". 'Cant do it boss';
		}else {
			list ($rand, $otp) = generateOTP();
			if (! ($stmt = $mysqli->prepare ( "INSERT INTO cam_users(userID,userName,password,firstName,lastName,email,email_verify,email_verify_timestamp,emailVerified) VALUES (?,?,?,?,?,?,?,?,?)" ))) {
				return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			$currDate = date_create()->format('Y-m-d H:i:s');
			$emailVerified = 0;
			
			if (! $stmt->bind_param ( "ssssssssi", $uid, $uid, $password, $fname, $lname, $email, $rand, $currDate, $emailVerified)) {
				return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (! $stmt->execute ()) {
				return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			/* explicit close recommended */
			$stmt->close ();
			sendVerificationMail($email,urlencode($otp),$uid);
			return "";
		}
	}

?>