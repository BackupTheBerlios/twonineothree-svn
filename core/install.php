<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  
  Installation script for 29o3
 
*/

$_VERSION = "0.1";

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
	background-color: #7392a6;
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
if($_POST['step'] == 2) {
// REAL HARD SETUP HERE NOW :D:D:D

$whatINeed = array(
	"admin_name",
	"admin_mail",
	"site_address",
	"site_name",
	"absolute_path",
	"activate_cen",
	"db_type",
	"db_host",
	"db_port",
	"db_dbname",
	"db_prefix",
	"db_user",
	"db_password",
	"license_agreed"
);

function endConsoleAndPage() {
	echo "</span></div></div></div></body></html>";
}
register_shutdown_function("endConsoleAndPage");

?><br/><br/>
<div style="width: 500px; border: 1px dotted #7392a6; background-color: #F1F1F1; font-family: courier; font-size: 12px;">
<span style="background-color: #7392a6; font-family: sans-serif, helvetica; font-size: 12px; font-weight: bold; position: absolute; left: 1; top: 1; right: 1; width: 500px; text-align: center; border-bottom: 1px dotted #7392a6; color: white;">Installation Progress</span><br/>
<span style="position: relative; left: 15px; top: 0px; right: 3px; font-family: courier; line-height: 150%; font-size: 12px;">
<?php
echo "Checking values...<br/>";
for($i = 0; $i < count($whatINeed); $i++) {
	if($_POST[$whatINeed[$i]] == "") {
		echo "<strong>ERROR: You missed something required.<br/>Please go <a href=\"javascript:history.go(-1);\">back</a>.</strong><br/>";
		exit;
	}
}
?>
</span>
</div>
<?php
} else {
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
<pre style="padding: 10px; border: 1px dotted #7392a6; background-color: #F1F1F1; font-family: courier; font-size: 12px; width: 500px;"><?php
if(file_exists("LICENSE")) {
	include("LICENSE");
} else {
	echo 'Error: File "LICENSE" not found down here. Please read the latest version of 29o3\'s license <a href="http://svn.berlios.de/viewcvs/twonineothree/core/LICENSE?rev=10000&view=markup" target="blank">here</a>.';
}
?>
</pre>
<form name="beisform" method="post" action="install.php">
<input type="hidden" name="step" value="2" />
<input type="checkbox" name="license_agreed" /> I have read and understood this license and agree to it. 
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
	<option selected="selected" value="mysql">PostgreSQL</option>
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
<?php
}
?>
	</div>
</div>
</body>
</html>
