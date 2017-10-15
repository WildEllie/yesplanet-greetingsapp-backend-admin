<?php
require_once 'management_data_layer.php';
$dataLayer = new DataLayer();
$dataLayer -> Open();
$res = $dataLayer -> getHourlyCount();

$return_arr = array();
$a_row = array();
/*
array_push($a_row, "Day and Hour");
array_push($a_row, "Count");
array_push($return_arr, $a_row);
*/
while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$a_row = array();
	$b_row = array();
/*	array_push($b_row, "2015");
	array_push($b_row, "9");
	array_push($b_row, $row['Day']);
*/	array_push($b_row, $row['Hour']);
	array_push($b_row, "0");
	array_push($b_row, "0");
/*	array_push($b_row, "0");
*/	
    array_push($a_row, $b_row);
    array_push($a_row, $row['Count']);
	
    array_push($return_arr,$a_row);
}

echo json_encode($return_arr);
?>