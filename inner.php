<?php 
	//$log = Logger::getLogger('myLogger');
	$GLOBALS['log']->debug('inner.php');
	echo $CONFIG['db.username'];

?>