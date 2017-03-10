<?php	
	$rand = $otp =  "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (empty($_POST["name"])) {
	    $arr = array('name' => 'NULL');
	    echo json_encode($arr);
	  } else {
		$arr = array('name' => $_POST["name"]);
		echo json_encode($arr);
	  }
	}
?>