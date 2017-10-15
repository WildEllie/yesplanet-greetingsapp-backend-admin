<?php
// 2 lines for debug - remove in prod
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

function send_sms($msg, $recepients){
	$msg = str_replace('<', '%26lt;', $msg); // "Cleans" the message from unsafe notes
	$msg = str_replace('>', '%26gt;', $msg); // "Cleans" the message from unsafe notes
	$msg = str_replace('\"', '%26quot;', $msg); // "Cleans" the message from unsafe notes
	$msg = str_replace("\'", '%26apos;', $msg); // "Cleans" the message from unsafe notes
	$msg = str_replace("&", '%26amp;', $msg); // "Cleans" the message from unsafe notes
	$msg = str_replace("\r\n", '%0D%0A', $msg); // "Cleans" the message from enter
	$message_text = $msg;
	$sms_host = "api.inforu.co.il"; // Application server's URL;
	$sms_port = 80; // Application server's PORT;
	$sms_path = "/SendMessageXml.ashx"; // Application server's PATH;
	// EDIT THE FOLLOWING LINES
	$sms_user = "yesplanett"; // User Name (Provided by Inforu) ;
	$sms_password = "yes962"; // Password (Provided by Inforu) ;
	$sms_sender_name = "Yes Planet"; // The SMS sender's will appear only on Cellcom & Partner's phones 
	//(Optional, only English characters, 11 max. );
	$sms_sender_num = "099526234"; // The number that this SMS will be sent from;
	$customer_parameter = "";
	$message_text = str_replace (" ", "%20", $message_text); // Encodes spaces into "%20" in the URL;
	$query = 'InforuXML=' . urlencode('<Inforu><User><Username>'.
		$sms_user.'</Username><Password>'.
		$sms_password.'</Password></User><Content Type="sms"><Message>').
		$message_text.urlencode('</Message></Content><Recipients><PhoneNumber>'.
		$recepients.'</PhoneNumber></Recipients><Settings><SenderName>'.
		$sms_sender_name.'</SenderName><SenderNumber>'.
		$sms_sender_num.'</SenderNumber><CustomerParameter>'.
		$customer_parameter.'</CustomerParameter></Settings></Inforu>');
	$fp = fsockopen("$sms_host", $sms_port, $errno, $errstr, 30); // Opens a socket to the Application server
	if (!$fp) { // Verifies that the socket has been opened and sending the message;
		echo "$errstr ($errno)<br />\n";
	} else {
		$out = "GET $sms_path?$query HTTP/1.1\r\n";
		$out .= "Host: $sms_host\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);
		while (!feof($fp)) {
			echo fgets($fp, 128); // Echos the respond from application server (you may replace this line 
								  // with an "Message has been sent" message);
		}
		fclose($fp);
	}
}
