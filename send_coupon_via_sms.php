<?php
/*
	greeting_json.style = style;
	greeting_json.greeting = greeting;
	greeting_json.sendername = senderName;
	greeting_json.sendertel = senderTel;
	greeting_json.recipientname = recipientName;
	greeting_json.recipienttel = recipientTel;
*/
	$sendername = $_REQUEST['sendername'];
	$sendertel = $_REQUEST['sendertel'];
	$recipientname = $_REQUEST['recipientname'];
	$recipienttel = $_REQUEST['recipienttel'];
	$style = $_REQUEST['style'];
	$greeting = $_REQUEST['greeting'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	
	require_once 'data_layer.php';
	
	$dataLayer = new DataLayer();
	$dataLayer -> Open();
	$res = $dataLayer -> GetCouponCode();
	$couponrow = mysql_fetch_array($res);
	$couponcode = $couponrow["code"];
	$couponid = $couponrow["objid"];
	$greeting_id = $dataLayer -> InsertGreeting($ip, $useragent, $sendername, $sendertel, $recipientname, $recipienttel, $style, $greeting, $couponcode);
	$dataLayer -> UpdateUsedCoupon($greeting_id, $couponid);
	
	/*check number of coupons sent */
	$res1 = $dataLayer -> getCount();
	$count = mysql_fetch_array($res1)['counter'];

	$dataLayer -> Close();
	
	require_once 'sendsms.php';
	$to = $recipienttel;
	$gid = "" . strlen($greeting_id) . $greeting_id . mt_rand(310304, 998323);
	/*eep
	$body = "http://banners-us.s3.amazonaws.com/yesplanet/greetings2015/greeting.html?gid=$gid";
	*/
	$body = "http://yp-newyear.co.il/g.htm?gid=$gid";
		
	/*EEP
	$sms_message = "שלום " . $recipientname . " קיבלת ברכה ומתנה לכבוד ראש השנה " . $body;
	*/
	$sms_message = $recipientname . " קיבלת פופקורן במתנה וברכה לחג מ" . $sendername . " " . $body;
	send_sms($sms_message, $to);
	
	
	echo "$ip, $useragent, $sendername, $sendertel, $recipientname, $recipienttel, $style, $greeting";
	
	if (($count % 100) == 0) send_sms("C=".$count, "0545245926");
?>

