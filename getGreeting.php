<?php
	$gid = $_REQUEST['gid'];
	$greeting_id = substr($gid, 1, $gid[0]);
	require_once 'data_layer.php';
	$dataLayer = new DataLayer();
	$dataLayer -> Open();
	$type = $dataLayer -> getGreeting($greeting_id);
	$row = mysql_fetch_array($type);
	$greeting = json_encode($row);
	$dataLayer -> Close();
	
	print $greeting;
?>