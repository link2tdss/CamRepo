
<?php
function getDbConn(){
	$mysqli = new mysqli("localhost.etrade.com", "cam_schema_admin", "Pass$123", "cam_schema1");
	$dbh = new PDO('mysql:host=localhost.etrde.com;dbname=cam_schema_admin', "Pass$123", "cam_schema1");
if ($dbh->connect_errno) {
    echo "Failed to connect to MySQL: (" . $dbh->connect_errno . ") " . $dbh->connect_error;
}
return $dbh;
}

?>
