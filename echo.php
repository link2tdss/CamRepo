<?php	
	include $_SERVER["DOCUMENT_ROOT"] . '/loadConfig.php';
	
	include  $_SERVER["DOCUMENT_ROOT"] . '/log4php/Logger.php';
	Logger::configure( $_SERVER["DOCUMENT_ROOT"] . '/config.xml');
	$log = Logger::getLogger('myLogger');
	
	//include 'phpinfo.php';
	/*$rand = $otp =  "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (empty($_POST["name"])) {
	    $arr = array('name' => 'NULL');
	    echo json_encode($arr);
	  } else {
		$arr = array('name' => $_POST["name"]);
		echo json_encode($arr);
	  }
	}
	
	$arr = array('name' => $_POST["name"]);*/
	$log->debug('echo.php');
	echo var_dump($CONFIG);
?>