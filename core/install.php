<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  
  Installation script for 29o3
 
*/


$_VERSION = "0.1pre";
if(!$_POST['step']) {
	$step = 0;
}
else {
	$step = $_POST['step'];
}

if($_GET['stylesheet'] == 1) {
	header("Content-type: text/css");
?>

body {
	font-family: helvetica, sans-serif;
	font-size: 12px;
	color: black;
	background-color: #EAEAEA;
}

input {
	border: 1px dotted silver;
	font-family: sans-serif, helvetica;
	font-size: 12px;
	padding: 2px;
}

td {
	padding: 3px;
	border-bottom: 1px dotted silver;
	border-left: 0;
}

<?php
	exit;
}

?>

<html>
<head>
	<title>29o3 Installation</title>
	<meta name="installer_script_version" content="<?php echo $_VERSION; ?>">
	<link rel="stylesheet" type="text/css" href="install.php?stylesheet=1">
</head>
<body>
<table width="100%" height="100%" style="border: 0;">
<tr>
	<td align="center">
	<form name="installform" method="post" action="install.php">
	<?php
	
	/* get all POST vars */
	foreach($_POST as $name=>$value) {
		if($name != "step") {
			printf("\t" . '<input type="hidden" name="' . $name . '" value="' . $value . '">' . "\n");
		}
	}
	
	?>
	<input type="hidden" name="step" value="<?php echo ($step+1); ?>">
	<table style="width: 570px; height: 450px; background-color: white; border: 1px dotted silver;" cellpadding="0" cellspacing="0">
	<tr>
		<td style="height: 40px; padding: 5px;font-family: sans-serif; color: white; font-size: 28px; border-bottom: 1px solid silver; text-align: right; background-color: #7392a6">
		<span style="font-size: 12px; font-weight: normal; position: inline; left: 0;">content management system</span>&nbsp;29o3
		</td>
	</tr>
	<tr>
		<td style="height: 5px; background-color: #a0c5dc; font-size: 0;">
		&nbsp;		
		</td>
	</tr>
	<tr>
		<td style="height: 375px; background-color: white; font-family: sans-serif, helvetica; font-size: 12px;">
		
		<div style="position: relative; left: 143px; top: 1px; width: 400px; height: 342px; padding: 5px; border: 1px solid silver; overflow:auto;">
		<?php
/*****     STEP NULL    *****/
		if($step == 0) {
		?>
		<b>Welcome to the installation wizard of 29o3</b><br><br>
		<span style="font-size: 11px;">This wizard will guide you through the setup process of the 29o3 content management system. If you want to be a really 1337 h4x0r, do
		the installation by hand. Information about the non-guided installation can be found in the file INSTALL you have received with this distribution
		or alternatively by clicking the button below.<br><br>
		If you don't know what a 1337 h4x0r is, proceed with this wizard.<br>
		In the upcoming steps, you'll need the following information about your server configuration:
		<ul>
			<li>Installation path of 29o3
			<li>User name, password, database and table prefix for the database server and the database running on the server
			<li>various other things I don't know yet ;)
		</ul>
		<span style="color: maroon; font-weight: bold;">WARNING<br>For security reasons, this script (install.php) should be deleted immediately after the installation of 29o3.
		Otherwise, anyone might gain access to your 29o3 installation and/or server.</span><br><br><br>
		Click "Next" to continue with the installation.
		</span>
		
		<?php
		}
/*****     STEP I     *****/
		if($step == 1) {
		?>
		<b>License Agreement</b><br><br>
		<span style="font-size: 11px;">
		To install and use 29o3 you have to agree with the license agreement. If you do not agree, you will have to cancel the setup
		at this point. By clicking "Next" you show that you agree to the terms and conditions of the license agreement.
		<hr>
		<pre style="font-family:courier; font-size: 10px;">
<?php include("LICENSE") ?>
		</pre>
		</span>
		<?php
		}
/*****     STEP II     *****/
		if($step == 2) {
		
		$site_address = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$site_address = ereg_replace("install.php", "", $site_address);
		
		?>
		
		<b>General information</b><br><br>
		<span style="font-size: 11px;">
		Here you will need to enter general information about your site like your name, eMail address, who to notify in
		case of an error etc.<br><br>
		</span>
		
		<table style="width: 380px;" cellspacing="0">
		<tr>
			<td style="font-size: 12px;">
			Your Name:
			</td>
			<td>
			<input type="text" name="ownername" size="20" maxlength="256" value="John Doe">
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			eMail:
			</td>
			<td style="font-size: 9px;">
			<input type="text" name="email" size="20" maxlength="256" value="localhost">&nbsp;
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			Address&nbsp;of<br>your site:
			</td>
			<td style="font-size: 9px;">
			<input type="text" name="address" size="25" maxlength="256" value="<?php echo $site_address;?>">
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			Name&nbsp;of<br>your site:
			</td>
			<td style="font-size: 9px;">
			<input type="text" name="sitename" size="25" maxlength="256">
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			CEN eMail:
			</td>
			<td style="font-size: 9px;">
			<input type="text" name="cn_to" size="25" maxlength="256" value="you@yoursite.com"><br>
			CEN (Critical Error Notification) is a feature which will inform you in case of an error/malfunction of 29o3
			so that you can correct the problem. Enter the eMail address you want to have notified here.
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			CEN CC:
			</td>
			<td style="font-size: 9px;">
			<input type="text" name="cn_cc" size="25" maxlength="256" value="root@localhost"><br>
			If you want to notify other addresses too, enter these ones here (comma seperated).
			</td>
		</tr>
		</table>
		
		<?php
		}
/*****     STEP III     *****/
		if($step == 3) {
		?>
		<b>Database Setup</b><br><br>
		<span style="font-size: 11px;">
		This step will gather information about your database configuration.<br>
		Please double-check your input for typos etc.!<br><br>
		</span>
		
		<table style="width: 380px;" cellspacing="0">
		<tr>
			<td style="font-size: 12px;">
			Database Type:
			</td>
			<td>
			<select name="servertype">
<?php

unset($DATABASE_TEST_FUNCTION);

// TODO: find directory automatically *DONE*
//$driverfile = dir();
$cwd = dirname(__FILE__) . "/lib/db";

$dir = dir($cwd);

while($file = $dir->read()) {
	if(strpos($file, ".php") !== false && strpos($file, ".") != 0) {
		include("lib/db/" . $file);
	}
}

?>

				<option value="sybase">MS SQL Server</option>
				<option selected value="mysql">MySQL</option>
				<option value="odbc">ODBC</option>
				<option value="pg">PostgresSQL</option>
				<option value="sybase">Sybase</option>
			</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			Address/Port:
			</td>
			<td style="font-size: 9px;">
			<input type="text" name="serveraddr" size="20" maxlength="256" value="localhost">&nbsp;
			<input type="text" name="serverport" size="4" maxlength="5" value="0"><br>
			Entering 0 (zero) as port will cause 29o3 to use the default port
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			Username:
			</td>
			<td style="font-size: 9px;">
			<input type="text" name="username" size="25" maxlength="256">
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			Password:
			</td>
			<td style="font-size: 9px;">
			<input type="password" name="password" size="25" maxlength="256">
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			Database:
			</td>
			<td style="font-size: 9px;">
			<input type="text" name="dbname" size="25" maxlength="256">
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px;">
			Prefix:
			</td>
			<td style="font-size: 9px;">
			<input type="text" name="prefix" size="25" maxlength="256" value="29o3_">
			</td>
		</tr>
		</table>
		<?php
		}
		if($step > 3) {
		?>
		<span style="font-size: 16px; color: maroon; font-weight: bold;">
		What have you done??<br>This step does not exist.
		</span>
		<?php
		}
		?>
		</div>
		
		
		</td>
	</tr>
	<tr>
		<td style="height: 30px; background-color: #DADADA; text-align: right;">
		<table width="100%" style="margin: 0px; padding: 0;" cellpadding="0" cellspacing="0">
			<tr>
				<td width="20%" style="font-family: sans; font-size: 10px; color: grey; padding: 4px;">
					29o3 Installer v.<?php echo $_VERSION; ?>
				</td>
				<td width="80%" align="right">
				<?php if($step == 0) { ?>
				<button type="button" style="font-family: sans; font-size: 12px; border: 1px dotted silver; height: 23px;" onClick="self.location.href='http://twentynineo3.sf.net/index.php?articles;manual_installation';">
				Do it by hand</button> &nbsp;&nbsp;&nbsp;
				<?php } ?>
				<button type="button" style="font-family: sans; font-size: 12px; border: 1px dotted silver; height: 23px; width: 70px;" onClick="javascript:history.go(-1);">
				&laquo;Back</button>
				<button type="submit" style="font-family: sans; font-size: 12px; border: 1px dotted silver; height: 23px; width: 70px;">
				Next &raquo;</button>
				&nbsp;&nbsp;&nbsp;
				</td>
			</tr>
		</table>
		</td>
	</tr>
	</table>
	</form>
	</td>
</tr>
</table>
</body>
</html>
