<?php  
ob_start();
require 'functions.php';
$con = new connectDB();
session_start();

if(!isset($_SESSION['location_query'])) {
	$con->connect();
	if (isset($_SERVER['HTTP_CLIENT_IP']))
	    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
	    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
	    $ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
	    $ipaddress = $_SERVER['REMOTE_ADDR'];
	else
	    $ipaddress = 'UNKNOWN';
	$user_agent = $_SERVER['HTTP_USER_AGENT'];	//get user IP

	date_default_timezone_set('America/New_York');
	$datetime = date("Y-m-d H:i:s");

	$email = "public";
	$page_accessed = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
	$sql = "INSERT INTO `login_user_ip` (`email`, `ip`, `user_agent`, `date_accessed`, `page_accessed`) VALUES ('{$email}', '{$ipaddress}', '{$user_agent}', '{$datetime}', '{$page_accessed}')";

	$send_query = $con->escape($con->query($sql));
	$con->confirm($send_query);
	
	$_SESSION['location_query'] = "sent";
	$con->disconnect();
}
$admin = new admin();


?>