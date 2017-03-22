<?php
	include $_SERVER["DOCUMENT_ROOT"] . '/private/conn_db.php';
	// define variables and set to empty values
	$error_arr = array();
	$userId = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
		if (empty($_POST["userId"])) {
			$error_arr["userId"] = "User ID is required";
		} else {
			$userId = test_input($_POST["userId"]);
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
			$status = disableUser($userId);
			if(empty($status)){
				header("HTTP/1.0 200 Success");
			}else{
				header("HTTP/1.0 400 Bad Request");
				echo $status;
			}
		}
	}
	
	 

	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	
	

	function disableUser($userId) {
		$mysqli = getDbConn ();
		if(is_null($mysqli)){
			echo  "<br>". 'Cant do it boss';
		}else {
			
			if (! ($stmt = $mysqli->prepare ( "UPDATE cam_users set active = ? WHERE userId = ?" ))) {
				return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			
			$disabled = 0;
			if (! $stmt->bind_param ( "is", $disabled, $userId)) {
				return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (! $stmt->execute ()) {
				return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			/* explicit close recommended */
			$stmt->close ();
			
			return "";
		}
	}

?>