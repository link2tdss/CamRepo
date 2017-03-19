<?php
	$dir = getcwd();
	chdir($_SERVER["DOCUMENT_ROOT"]);
	$CONFIG = parse_ini_file("../config.ini");
	chdir($dir);
?>