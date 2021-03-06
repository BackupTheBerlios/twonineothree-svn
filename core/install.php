<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  
  Installation script for 29o3
 
*/

function generateConfig() {
	$arr = file($_POST['absolute_path'] . "/bootstrap/blankconfig");
	$string = implode('', $arr);
	
	$string = str_replace("DATABASE_TYPE", $_POST['db_type'], $string);
	$string = str_replace("DATABASE_HOST", $_POST['db_host'], $string);
	$string = str_replace("DATABASE_USER", $_POST['db_user'], $string);
	$string = str_replace("DATABASE_PASSWORD", $_POST['db_pw'], $string);
	$string = str_replace("DATABASE_PORT", $_POST['db_port'], $string);
	$string = str_replace("DATABASE_NAME", $_POST['db_dbname'], $string);
	$string = str_replace("DATABASE_PREFIX", $_POST['db_prefix'], $string);
	$string = str_replace("DIR_LIBDIR", $_POST['absolute_path'] . "/lib/", $string);
	$string = str_replace("DIR_MEDIADIR", $_POST['absolute_path']. "/media/", $string);
	$string = str_replace("DIR_CACHEDIR", $_POST['absolute_path'] . "/cache/", $string);
	$string = str_replace("SITE_AUTHOR", $_POST['admin_name'], $string);
	$string = str_replace("SITE_ADDRESS", $_POST['site_address'], $string);
	if($_POST['activate_cen'] == "1") {
		$string = str_replace("CEN_ACTIVATED", "true", $string);
		$string = str_replace("CEN_ADDRESSES", $_POST['cen_addresses'], $string);
	} else {
		$string = str_replace("CEN_ACTIVATED", "false", $string);
	}
	return $string;
}

$_VERSION = "0.1";
$_CONFIGCHECK = true;

if($_GET['stylesheet'] == 1) {
	header("Content-type: text/css");
?>
body {
	background-color: white;
	font-family: sans-serif, arial, helvetica;
	font-size: 11px;
	color: black;
	margin: 0px;
	padding: 0px;
}

.stripediv {
	position: absolute;
	top: 10%;
	left: 0px;
	right: 0px;
	width: 100%;
	font-family: sans-serif, arial, helvetica;
	line-height: 130%;
	text-align: center;
}

.bluestripe {
	text-align: center;
	width: 100%;
	height: 97px;
	background-color: rgb(1,6,6);
}

.textunderstripe {
	text-align: left;
	background-color: white;
	color: #666666;
	position: relative;
	top: 25px;
	left: 20%;
	width: 60%;
	line-height: 170%;
	font-family: sans-serif, arial, helvetica;
	font-size: 12px;
}

.textunderstripe a:link, .textunderstripe a:active, .textunderstripe a:visited {
	color: #666666;
	text-decoration: none;
	font-weight: bold;
}

.textunderstripe a:hover {
	text-decoration: none;
	font-weight: bold;
	border-bottom: 1px dotted #666666;
}

input {
	font-family: sans-serif, helvetica;
	font-size: 12px;
	border: 1px dotted #7392a6;
	background-color: #F1F1F1;
	text-align: center;
	vertical-align: middle;
}

.inputfield {
	position: absolute;
	left: 300px;
	width: 200px;
}

.inputcb {
	position: absolute;
	left: 300px;
}

.annot {
	font-size: 10px;
	position: absolute;
	left: 12px;
	line-height: 120%;
}
<?php
	exit();
}

header("Content-type: application/xhtml+xml");

printf('<?xml version="1.0" encoding="UTF-8"?>');
printf('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">%s', "\n");
printf('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">%s', "\n");
?>
<head>
	<title>29o3 - installation</title>
	<link rel="stylesheet" type="text/css" href="install.php?stylesheet=1" />
</head>
<body>
<div class="stripediv">
	<div class="bluestripe">
	<img src="lib/images/logo.png" />
	</div>
	<div class="textunderstripe">
<?php
if(file_exists("./config.php") && $_CONFIGCHECK) {
	$config_exists = true;
?>

<span style="font-size: 12px; font-weight: bold;">
29o3 <acronym title="[B]esch&auml;mend [E]infaches [I]nstallations [S]kript">BEIS</acronym> <?=$_VERSION ?>
</span><br/><br/>
<span style="font-size: 14px; font-weight: bold;">Houston... we've had a problem</span><br/><br/>
The 29o3 configuration file <em>config.php</em> was found. The installer cannot continue as this is a sure
sign for a already-running installation of 29o3. For updating 29o3, please use the appropriate option in the
management console.
</div></div>
<?php
}

function setStatus($string) {
	echo "$string...&nbsp;&nbsp;&nbsp;";
}
function setState($failed) {
	if($failed == true) {
		echo "<span style=\"color: red; font-weight: bold;\">**FAILED**</span><br/>";
	} else {
		echo "<span style=\"color: green; font-weight: bold;\">SUCCESS</span><br/>";
	}
}

function exitConsole() {
	echo "</span></div></div></div></body></html>";	
}

function pleaseGoBack() {
	echo "<br/><strong>Please go <a href=\"javascript:history.go(-1);\">back</a>.</strong><br/>";
}

if($_POST['step'] == 2 && !$config_exists) {
// REAL HARD SETUP HERE NOW :D:D:D

$whatINeed = array(
	"admin_name" => "You forgot the name of the administrator",
	"admin_mail" => "You forgot the eMail address of the administrator",
	"site_address" => "You forgot the site address",
	"site_name" => "You forgot the site name",
	"absolute_path" => "You forgot the absolute path",
	"db_type" => "You forgot to specify a database type",
	"db_host" => "You forgot to set the database host",
	"db_dbname" => "You forgot to set the database name",
	"db_prefix" => "You forgot to set the database prefix",
	"db_user" => "You forgot to set the database username",
	"db_pw" => "You forgot to set the password for the database",
	"license_agreed" => "<em>YOU HAVE NOT AGREED TO THE LICENSE! Install cannot continue!</em>"
);

?><br/><br/>
<div style="width: 500px; border: 1px dotted #7392a6; background-color: #F1F1F1; font-family: courier; font-size: 12px;">
<span style="background-color: #7392a6; font-family: sans-serif, helvetica; font-size: 12px; font-weight: bold; position: absolute; left: 1; top: 1; right: 1; width: 500px; text-align: center; border-bottom: 1px dotted #7392a6; color: white;">Installation Progress</span><br/>
<span style="position: relative; left: 15px; top: 0px; right: 3px; font-family: courier; line-height: 150%; font-size: 12px;">
<?php
setStatus("Checking values");
$keys = array_keys($whatINeed);
for($i = 0; $i < count($whatINeed); $i++) {
	if($_POST[$keys[$i]] == "") {
		setState(true);
		echo "<strong>ERROR: You missed something required.</strong><br/>";
		pleaseGoBack();
		echo $whatINeed[$keys[$i]];
		exitConsole();
		exit;
	}
}
setState(false);

setStatus("Checking CEN");
if($_POST["activate_cen"] == 1) {
	if($_POST['cen_addresses'] == "") {
		setState(true);
		echo "<strong>ERROR: You activated CEN, but forgot specifying addresses to send the notifications to.</strong>";
		pleaseGoBack();
		exitConsole();
		exit();
	}
	setState(false);
}
setStatus("Checking path");
if(!file_exists($_POST['absolute_path'] . "/install.php")) {
	setState(true);
	echo "<strong>ERROR: Given absolute path does not exist.</strong>";
	pleaseGoBack();
	exitConsole();
	exit();
}

setStatus("Checking database");
echo "<br/>";
setStatus("Checking database connector");
if(!file_exists($_POST['absolute_path'] . "/lib/db/" . $_POST['db_type'] . ".php")) {
	setState(true);
	echo "<strong>ERROR: Given database type not available.</strong>";
	pleaseGoBack();
	exitConsole();
	exit();
}
setState(false);

setStatus("Trying to connect to database '" . $_POST['db_dbname'] . "'");
require_once($_POST['absolute_path'] . "/lib/db/" . $_POST['db_type'] . ".php");

$connector = new DatabaseConnector();

$successfulConnected = $connector->setupConnection($_POST['db_host'], $_POST['db_user'], $_POST['db_pw'], $_POST['db_dbname'], $_POST['db_port']);

if(!$successfulConnected) {
	setState(true);
	pleaseGoBack();
	echo "</span></div></div></div></body></html>";	
	exit();
}
setState(false);

setStatus("Bootstrapping database. Please wait");

$file = file($_POST['absolute_path'] . "/bootstrap/bootstrap." . $_POST['db_type']);
$query = implode('', $file);

$queried = $connector->executeQuery($query);
if($queried == false) {
	setState(true);
	pleaseGoBack();
	echo "</span></div></div></div></body></html>";	
	exit();
}
setState(false);

echo "<br/>*** DATABASE SETUP IS NOW COMPLETE ***<br/><br/>";

setStatus("Generating config.php");

$cfgFile = generateConfig();
echo $cfgFile;

$filename = "config.php";


   if (!$handle = fopen($filename, 'w')) {
	setState(true);
	echo "<strong>ERROR: Could not open config.php</strong>";
	 exitConsole();
         exit;
   }

   if (fwrite($handle, $cfgFile) === FALSE) {
	setState(true);
	echo "Cannot write to file ($filename)";
       exitConsole();
       exit;
   }

echo "</span></div>";

$connector->closeConnection();

?>
29o3 has been installed successfully. <a href="index.php?InstallationSuccessful">Please click here to go on.</a>
</div>
</div>
<?php
} else  { if(!$config_exists) {
?>

<span style="font-size: 12px; font-weight: bold;">
29o3 <acronym title="[B]esch&auml;mend [E]infaches [I]nstallations [S]kript">BEIS</acronym> <?=$_VERSION ?>
</span><br/><br/>
<span style="font-size: 14px; font-weight: bold;">Welcome to the installation...</span><br/><br/>
... and many thanks for choosing 29o3 :)<br/>
This installer will guide you through the installation of the 29o3 Content Management System.
Okay... this script is in an earlier stage of development as 29o3, so expect bugs. Additionally, this installer might
change during the development process of 29o3.<br/><br/>Note: In these forms, an asterisk (*) denotes <u>required</u> fields.<br/><br/>

<h3>Step 1: License Agreement</h3>
If you continue filling out these forms and end up with installing and using 29o3, you must agree to the following license:
<pre style="line-height: 130%; padding: 10px; border: 1px dotted #7392a6; background-color: #F1F1F1; font-family: courier; font-size: 12px; width: 500px;"><?php
if(file_exists("LICENSE")) {
	include("LICENSE");
} else {
	echo 'Error: File "LICENSE" not found down here. Please read the latest version of 29o3\'s license <a href="http://svn.berlios.de/viewcvs/twonineothree/core/LICENSE?rev=10000&view=markup" target="blank">here</a>.';
}
?>
</pre>
<form name="beisform" method="post" action="install.php">
<input type="hidden" name="step" value="2" />
<input type="checkbox" name="license_agreed" value="1" /> I have read and understood this license and agree to it. 
<br/>
<div style="text-align: center">&middot;&nbsp;&middot;&nbsp;&middot;</div>
<h3>Step 2: General Information</h3>
In the following fields, please type the requested information.
Some fields might be pre-filled by the installer and generally it's a good
idea to leave them as they are.<br/><br/>
Name of the site's administrator* <input type="text" name="admin_name" class="inputfield" /><br/>
eMail address of the site's administrator* <input type="text" name="admin_mail" class="inputfield" /><br/><br/>
Name of this site <input type="text" name="site_name" class="inputfield" /><br/>
Address of this site <input type="text" name="site_address" class="inputfield" value="http://" /><br/>
Absolute location of this script* <input type="text" name="absolute_path" class="inputfield" value="<?=dirname(__FILE__)?>" /><br/><br/>
Do you want to activate <acronym title="Critical Error Notification">CEN</acronym>? <span class="inputcb"><input type="checkbox" name="activate_cen" value="1" checked="checked"/>Yes</span><br/>
<span class="annot">CEN is a feature which notifies you via eMail<br/>
if something bad happened to 29o3.</span><br/>
Which eMail address(es) should CEN notify? <input type="text" name="cen_addresses" class="inputfield" /><br/>
<span class="annot">Please supply one address or a comma-separated list</span><br/><br/>
<div style="text-align: center">&middot;&nbsp;&middot;&nbsp;&middot;</div>
<h3>Step 3: Database Configuration</h3>
In this step, you will need to supply information about your database server.<br/><br/>
Database type: 
<select name="db_type" class="inputfield" size="1">
	<option selected="selected" value="postgresql">PostgreSQL</option>
	<option value="mysql">MySQL</option>
	<option disabled="disabled" value="odbc">ODBC</option>
	<option disabled="disabled" value="db2">DB2</option>
	<option disabled="disabled" value="sqlite">SQLite</option>
</select><br/>
Hostname/IP of the database server* <input type="text" name="db_host" class="inputfield" /><br/>
Port (leave blank for default) <input type="text" name="db_port" class="inputfield" /><br/>
Database name* <input type="text" name="db_dbname" class="inputfield" /><br/>
Database prefix* <input type="text" name="db_prefix" class="inputfield" value="broend_" /><br/>
Username* <input type="text" name="db_user" class="inputfield" /><br/>
Password* <input type="password" name="db_pw" class="inputfield" /><br/>
<br/>
<div style="text-align: center">&middot;&nbsp;&middot;&nbsp;&middot;</div>
<h3>Step 4: Finishing up</h3>
Before going on, please <strong><u>double-check</u></strong> that all required fields
have been filled out with the correct values. If the installer notices a problem during the installion,
it will notify you.
<div style="text-align: center"><br/>
<button type="submit" name="submit" style="padding: 4px;">Install now &gt;&gt;</button>
<br/><br/>&nbsp;
</div>
</form>
</div>
</div>
<?php
} }
?>
</body>
</html>
