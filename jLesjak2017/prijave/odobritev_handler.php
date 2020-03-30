<?php
session_start();

if(!isset($_SESSION['user']))
{
	header("location:". $_SERVER['HTTP_REFERER']);
}

require_once 'connect.inc.php';

$query = "UPDATE vloga
		  SET vloga.odobritev_{$_GET['kdo']} = '{$_GET['action']}'
		  WHERE vloga.EMSO = {$_GET['id']}";


if(!mysqli_query($link, $query))
{
	echo mysqli_error($link);
}

header("location:". $_SERVER['HTTP_REFERER']);
?>