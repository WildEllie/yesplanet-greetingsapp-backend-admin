<?php
require_once 'management_data_layer.php';
$dataLayer = new DataLayer();
$dataLayer -> Open();
$frequency = $dataLayer -> getSenderHistogram();

$return_arr = array();
$a_row = array();
array_push($a_row, "Number");
array_push($a_row, "Count");
array_push($return_arr, $a_row);
while ($row = mysql_fetch_array($frequency, MYSQL_ASSOC)) {
	$a_row = array();
    array_push($a_row, $row['sendertel']);
    array_push($a_row, $row['counter']);
	
    array_push($return_arr,$a_row);
}

echo json_encode($return_arr);
?>