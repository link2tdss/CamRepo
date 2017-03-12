<?php	
	include 'loadConfig.php';
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
	$ini_array = parse_ini_file("../config.ini");
		$arr = array('name' => $_POST["name"]);
		echo var_dump($GLOBALS['HOSTNAME']);
?>