<?php
	include $_SERVER["DOCUMENT_ROOT"] . '/private/conn_db.php';
	$mysqli = getDbConn ();
	if(is_null($mysqli)){
		echo  "<br>". 'Cant do it boss';
	}else {
		/* Non-prepared statement */
		$res = $mysqli->query ( "SELECT CONCAT(firstName, ' ', lastName), email, userId FROM cam_users" );
		$result = array("data" => $res->fetch_all());
		echo json_encode($result);
	}
	

?>