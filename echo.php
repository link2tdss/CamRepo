<?php	
	include 'loadConfig.php';
	include 'phpinfo.php';
	include  $_SERVER["DOCUMENT_ROOT"] . '/log4php/Logger.php';
	Logger::configure( $_SERVER["DOCUMENT_ROOT"] . '/config.xml');
	$GLOBALS['log'] = Logger::getLogger('myLogger');
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