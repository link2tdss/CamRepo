<?php
	include 'private/conn_db.php';
	$mysqli = getDbConn ();
	if(is_null($mysqli)){
		echo  "<br>". 'Cant do it boss';
	}else {
		/* Non-prepared statement */
		if (! $mysqli->query ( "DROP TABLE IF EXISTS test" ) || ! $mysqli->query ( "CREATE TABLE test(id INT)" )) {
			echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		
		/* Prepared statement, stage 1: prepare */
		if (! ($stmt = $mysqli->prepare ( "INSERT INTO test(id) VALUES (?)" ))) {
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
		
		/* Non-prepared statement */
		$res = $mysqli->query ( "SELECT * FROM cam_users" );
		$result = array("data" => $res->fetch_all());
		echo json_encode($result  );
	}
	

?>