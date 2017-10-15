<?php
###############################################################
# Page Password Protect 2.13x
###############################################################
#
# Usage:
# Set usernames / passwords below between SETTINGS START and SETTINGS END.
# Open it in browser with "help" parameter to get the code
# to add to all files being protected. 
#    Example: password_protect.php?help
# Include protection string which it gave you into every file that needs to be protected
#
# Add following HTML code to your page where you want to have logout link
# <a href="http://www.example.com/path/to/protected/page.php?logout=1">Logout</a>
#
###############################################################

session_start();

/*
-------------------------------------------------------------------
SAMPLE if you only want to request login and password on login form.
Each row represents different user.

$LOGIN_INFORMATION = array(
  'noam' => 'portugali',
  'toysrus' => 'mustcast',
  'ellie' => 'portugali'
);

--------------------------------------------------------------------
SAMPLE if you only want to request only password on login form.
Note: only passwords are listed

$LOGIN_INFORMATION = array(
  'root',
  'testpass',
  'passwd'
);

--------------------------------------------------------------------
*/

##################################################################
#  SETTINGS START
##################################################################

// Add login/password pairs below, like described above
// NOTE: all rows except last must have comma "," at the end of line

/******** Lookup DB and create array here *****/
/*
require 'data_layer.php';

$conn = new DataLayer();
*/

$LOGIN_INFORMATION = array(
  'root'  => 'ellie123',
  'admin'  => 'upupa123',
  'yesplanet'  => 'upupa123'
);

// request login? true - show login and password boxes, false - password box only
define('USE_USERNAME', true);

// User will be redirected to this page after logout
define('LOGOUT_URL', './');

// time out after NN minutes of inactivity. Set to 0 to not timeout
define('TIMEOUT_MINUTES', 2);

// This parameter is only useful when TIMEOUT_MINUTES is not zero
// true - timeout time from last activity, false - timeout time from login
define('TIMEOUT_CHECK_ACTIVITY', false);

##################################################################
#  SETTINGS END
##################################################################


///////////////////////////////////////////////////////
// do not change code below
///////////////////////////////////////////////////////

// show usage example
if(isset($_GET['help'])) {
  die('Include following code into every page you would like to protect, at the very beginning (first line):<br>&lt;?php include("' . str_replace('\\','\\\\',__FILE__) . '"); ?&gt;');
}

// timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// logout?
if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . LOGOUT_URL);
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {

// show login form
function showLoginPasswordProtect($error_msg) {
?>
<html lang="heb">
	<head>
		<meta charset="utf-8">
		<title>Please enter password to access this page</title>
		<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	</head>
<body class="standalonebody">
  <style>
		BODY {width: 980px; height: 600px; margin: 0 auto;}
		input {
			border: 1px solid #A53F81; 
			border-radius: 5px; 
			height: 26px;
			/* float: left; */
			position: absolute;
			left: 0;
			width: 150px;
			margin-left: 130px;
		}
		input[type="submit"] {
			width: 120px;
			height: 50px;
			cursor: pointer;
		}
		input[type="submit"]:HOVER {
			background-color: #DAC8E2;
		}
		H2 { color: #A53F81;}
		.loginFont{ font-size: 1.5em; color: #ceb7cb; width: 100px; display: inline-block;}
		LABEL {color: white; font-family: arial;}
  </style>
  <div style="width:980px;">
  <div id="flashContent" style="margin-left: 100px;">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="800" height="180" id="master2" align="middle">
				<param name="movie" value="./images/upupalogo.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="./images/upupalogo.swf" width="800" height="180">
					<param name="movie" value="./images/upupalogo.swf" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#ffffff" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
		</div>
  <form method="post" dir="rtl" style="width: 400px; margin: 0 auto; position: relative;">
    <font color="red"><?php echo $error_msg; ?></font><br />
<?php
		if (USE_USERNAME)
			echo '<label class="loginFont">שם:</label><input type="input" name="access_login" />' . 
			'<br /><label class="loginFont">סיסמא:</label>';
 ?>
    <input type="password" name="access_password" />
	<p></p><br>
	<input type="submit" name="Submit" value="שלח" />
  </form>
  </div>
</body>
</html>

<?php
// stop at this point
die();
}
}

// user provided password
if (isset($_POST['access_password'])) {

$login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
$pass = $_POST['access_password'];

if (!array_key_exists($login, $LOGIN_INFORMATION)){
	
/*
	$conn->Open();
	
	//$result = $conn->GetBeauticianByUserName($login);
	
	if ($result){
		$row = mysql_fetch_array($result);
		$LOGIN_INFORMATION = array(
			$row['username']  => $row['password']
		);
		
		$_SESSION['b_id']=$row['id'];
		$_SESSION['b_name']=$row['first_name'];
		setcookie("b_id", $row['id'], $timeout, '/');
			
	}
	
	$conn->Close(); 
*/
}


if (USE_USERNAME && $login=='') {
		showLoginPasswordProtect("הכנס שם משתמש");	
		}
	



if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
|| (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) )
) {
showLoginPasswordProtect("Incorrect password.");
}
else {
// set cookie if password was validated
setcookie("verify", md5($login.'%'.$pass), $timeout, '/');

// Some programs (like Form1 Bilder) check $_POST array to see if parameters passed
// So need to clear password protector variables
unset($_POST['access_login']);
unset($_POST['access_password']);
unset($_POST['Submit']);
}

}

else {

// check if password cookie is set
if (!isset($_COOKIE['verify'])) {
showLoginPasswordProtect("");
}

// check if cookie is good
$found = false;
foreach($LOGIN_INFORMATION as $key=>$val) {
$lp = (USE_USERNAME ? $key : '') .'%'.$val;
if ($_COOKIE['verify'] == md5($lp)) {
$found = true;
// prolong timeout
if (TIMEOUT_CHECK_ACTIVITY) {
setcookie("verify", md5($lp), $timeout, '/');
}
break;
}
}
if (!$found) {
showLoginPasswordProtect("");
}

}
?>
