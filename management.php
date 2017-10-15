<?php include("password_protect.php"); 

require_once 'management_data_layer.php';
$dataLayer = new DataLayer();
$dataLayer -> Open();
$winners = $dataLayer -> getAllGreetings();
?>
<!doctype html>
<html lang="he-IL">
	<head>
		<meta charset="utf-8">
		<title>Yes Planet New Year Greetings 2015 - Admin</title>
		<!-- DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.css">
		<!-- jQuery -->
		<script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>  
		<!-- DataTables -->
		<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.js"></script>
		<script>
			$(document).ready(function() {
				$('#winnersTable').DataTable();
			} );
		</script>
		<style>
			#content{
				width: 980px;
				margin: 0 auto;
				position: relative;
			}
		</style>
	</head>
	<body>
		<h2>Yes Planet New Year Greetings 2015 - Admin</h2>
		<div id="content">
		
		<table id="winnersTable" class="display">
			<thead>
				<tr>
					<td>Time</td>
					<td>Sender Name</td>
					<td>Sender Phone</td>
					<td>Greeting Style</td>
					<td>Greeting Text</td>
					<td>Recipient Name</td>
					<td>Recipient Phone</td>
					<td>Link</td>
				</tr>
			</thead>
			<tbody>
			<?php
				while($row = mysql_fetch_array($winners)) {
					$gid = "" . strlen($row['objid']) . $row['objid'] . mt_rand(3104, 9933);
					$link = "http://yp-newyear.co.il/g.htm?gid=$gid";
				?>
					<tr>
						<td><?php echo $row['entered']?></td>
						<td><?php echo $row['sendername']?></td>
						<td><?php echo $row['sendertel']?></td>
						<td><?php echo $row['style']?></td>
						<td><?php echo $row['greeting']?></td>
						<td><?php echo $row['recipientname']?></td>
						<td><?php echo $row['recipienttel']?></td>
						
						<td><a href="<?php echo $link?>">Link&#8599;</a></td>
					</tr>

			<?php
			}
			?>
			</tbody>
		</table>
		</div>
		<?php
		$dataLayer -> Close();
		?>
	</body>
</html>