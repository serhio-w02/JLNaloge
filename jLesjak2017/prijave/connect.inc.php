<?php
	
	$mysql_host = 'localhost';
	$mysql_user = 'root';
	$mysql_password = '';
	$mysql_database = 'prijave';
	
	$link = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database)
					or die('could not connect');
	
	mysqli_select_db($link, $mysql_database) 
					or die('could not select database');
	
	
	mysqli_set_charset($link, 'utf8');

?>