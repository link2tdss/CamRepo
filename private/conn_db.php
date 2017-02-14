
<?php
function getDbConn() {
	
	$mysqli = new mysqli ( "localhost", "cam_schema_admin", "Pass$123", "cam_schema" );
	if ($mysqli->connect_errno) {
		echo 'Failed to connect to MySQ123L: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error . "\r";
		return NULL;
	}else {
		return $mysqli;
	}
	// echo $mysqli->host_info . "\n";
	
	
}

?>
