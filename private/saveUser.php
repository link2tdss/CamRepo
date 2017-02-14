<?php
	include 'conn_db.php';

	// define variables and set to empty values
	$error_arr = array();
	$nameErr = $emailErr = $genderErr = $websiteErr = "";
	$name = $email = $gender = $comment = $website = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (empty($_POST["fname"])) {
	    //$nameErr = "fname:First Name is required";
	    $error_arr["fname"] = "First Name is required";
	  } else {
	    $name = test_input($_POST["fname"]);
	    // check if name only contains letters and whitespace
	    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
	      //$nameErr = "fname:Only letters and white space allowed";
	      $error_arr["fname"] = "Only letters and white space allowed";
	    }
	  }
	  
	  if (empty($_POST["lname"])) {
	  	//$nameErr = "lname:First Name is required";
	  	$error_arr["lname"] = "Last Name is required";
	  } else {
	  	$name = test_input($_POST["fname"]);
	  	// check if name only contains letters and whitespace
	  	if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
	  		//$nameErr = "fname:Only letters and white space allowed";
	  		$error_arr["lname"] = "Only letters and white space allowed";
	  	}
	  }
	  
	  if (empty($_POST["email"])) {
	    //$emailErr = "Email is required";
	    $error_arr["email"] = "Email is required";
	  } else {
	    $email = test_input($_POST["email"]);
	    // check if e-mail address is well-formed
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	      //$emailErr = "Invalid email format";
	    	$error_arr["email"] = "Invalid email format";
	    }
	  }
	  
	  if (count($error_arr) > 0){
	  	http_response_code(400);
	  	echo json_encode($error_arr);
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

	function saveUser() {
		$mysqli = getDbConn ();
		if(is_null($mysqli)){
			echo  "<br>". 'Cant do it boss';
		}else {
			if (! ($stmt = $mysqli->prepare ( "INSERT INTO cam_users() VALUES (?)" ))) {
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			
			$id = 1;
			if (! $stmt->bind_param ( "i", $id )) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (! $stmt->execute ()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			/* explicit close recommended */
			$stmt->close ();
		}
	}

?>