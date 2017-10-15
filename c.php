<?php
require_once 'management_data_layer.php';
$dataLayer = new DataLayer();
$dataLayer -> Open();
$res = $dataLayer -> getCount();
$count = mysql_fetch_array($res)['counter'];

$dataLayer -> Close();
?>
<!DOCTYPE html>
<html lang="en">
<head><title>c=<?php echo $count;?></title></head>
<body>
	<h1>C=<?php echo $count;?></h1>
</body>
</html>