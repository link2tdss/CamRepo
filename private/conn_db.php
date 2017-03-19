
<?php


function getDbConn() {
	
	$mysqli = new mysqli ( "localhost", "cam_schema_admin", "Pass$123", "cam_schema" );
	if ($mysqli->connect_errno) {
		$log = Logger::getLogger('myLogger');
		$log->error('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error . "\r");
		return NULL;
	}else {
		return $mysqli;
	}
	
	
}

?>
