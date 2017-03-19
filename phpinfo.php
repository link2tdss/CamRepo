 <?php
//echo 'testing 123';
 	include 'inner.php';
 	//$log = Logger::getLogger('myLogger');
 	$GLOBALS['log']->debug('phpinfo.php');
	echo $CONFIG['db.hostname'];
//phpinfo();

?> 