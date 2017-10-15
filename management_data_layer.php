<?php
class DataLayer {
	var $db = null;

	var $dbhost = 'localhost';
	var $dbuser = 'umdbadmin';
	var $dbpass = 'jmF4997SY3LTwRv9';
	var $dbname = 'yesplanet_greeting2015';

	function Open() {
		$this -> db = mysql_connect($this -> dbhost, $this -> dbuser, $this -> dbpass) or die('Error connecting to mysql');

		if (!mysql_select_db($this -> dbname)) {
			echo mysql_error($this -> db);
			$this -> Close();
		}

		// for displaying unicode (hebrew) characters
		mysql_query("SET character_set_client=utf8");
		mysql_query("SET character_set_connection=utf8");
		mysql_query("SET character_set_database=utf8");
		mysql_query("SET character_set_results=utf8");
		mysql_query("SET character_set_server=utf8");
	}

	// Close - close the database connection
	function Close() {
		if ($this -> db != null)
			mysql_close($this -> db);
		$this -> db = null;
	}

	// _execQuery - Public clients should not use this function
	// This is a helper class for derived classes
	function _execQuery($sql) {
		$rs = mysql_query($sql);
		if (!$rs) {
			echo mysql_error($this -> db);
			echo "sql: " . $sql;
			$this -> Close();
			exit ;
		}

		return $rs;
	}

	///////////////////////////////////////////////////
	
	function InsertGreeting($ip, $useragent, $sendername, $sendertel, $recipientname, $recipienttel, $style, $greeting, $couponcode) {
		$sql = "INSERT INTO `greeting2015` (ip, useragent, sendername, sendertel, recipientname, recipienttel, style, greeting, couponcode) " .
			 "value(\"" . $ip . "\", \"" . $useragent . "\", \"" . $sendername . "\", \"" . $sendertel . "\", \"" . $recipientname . "\", \"" 
			 . $recipienttel ."\", " .$style .", \"" .$greeting ."\", \"" . $couponcode . "\")";
		//return $this -> _execQuery($sql);
		$this -> _execQuery($sql);
		return mysql_insert_id();
	}

	function GetCouponCode(){
		$sql = "SELECT * from `couponcodes` WHERE usedby IS NULL LIMIT 1";
		return $this -> _execQuery($sql);
	}
	
	function UpdateUsedCoupon($greetingid, $couponecodeid) {
		$sql = "UPDATE `couponcodes` SET usedby=" . $greetingid . " WHERE objid=" . $couponecodeid;
		return $this -> _execQuery($sql);
	}
	
	function getGreeting($greet_id){
		$sql = "SELECT sendername, recipientname, style, greeting, couponcode FROM `greeting2015` WHERE objid=" . $greet_id;
		return $this -> _execQuery($sql);
	}
	function getAllGreetings(){
		$sql = "SELECT * FROM `greeting2015` WHERE test is null";
		return $this -> _execQuery($sql);
	}
	function getSenderHistogram(){
		$sql = "select sendertel, count(*) as counter from greeting2015 GROUP BY sendertel";
		return $this -> _execQuery($sql);
	}
	function getRecipientHistogram(){
		$sql = "select recipienttel, count(*) as counter from greeting2015 GROUP BY recipienttel";
		return $this -> _execQuery($sql);
	}
	function getCount(){
		$sql = "select count(*) as counter from greeting2015";
		return $this -> _execQuery($sql);
	}
	function getHourlyCount(){
		$sql = "SELECT day(entered) as Day, hour(entered) as Hour, entered, count(*) as Count "
			 . "from greeting2015 "
			 . "GROUP BY day(entered), hour(entered)";
		return $this -> _execQuery($sql);
	}
}
?>