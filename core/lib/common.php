<?php
/* 29o3 content management system
 * (c) 2003-2004 by Ulrik Guenther <kpanic@mg2.org>
 * This software is licensed under the terms of the BSD license.
 */ 

$nachars_search = array ("'<script[^>]*?>.*?</script>'si",
                "'<[\/\!]*?[^<>]*?>'si",
                "'([\r\n])[\s]+'",
                "'&(quot|#34);'i",
                "'&(amp|#38);'i",
                "'&(lt|#60);'i",
                "'&(gt|#62);'i",
                "'&(nbsp|#160);'i",
                "'&(iexcl|#161);'i",
                "'&(cent|#162);'i",
                "'&(pound|#163);'i",
                "'&(copy|#169);'i",
                "'&#(\d+);'e",
		"'(\%20)'",
		"'(\!)'",
		"'(\?)'",
		"'(\")'",
		"'(\%22)'"
		);

$nachars_replace = array ("",
                 "",
                 "\\1",
                 "\"",
                 "&",
                 "<",
                 ">",
                 " ",
                 chr(161),
                 chr(162),
                 chr(163),
                 chr(169),
                 "chr(\\1)",
		 "_",
		 "",
		 "",
		 "",
		 ""
		 );

function err($title, $text, $fatality) {
	
	global $CONFIG;
	global $SYSTEM_INFO;
	global $output_started;
	
	$text = str_replace("\n", "<br/>", $text);
	
	if(!$output_started) {
		printf("<html>\n<head>\n<title>29o3</title>\n<link rel=\"stylesheet\" href=\"n_style.css\" />\n</head>\n<body>\n");
	}
	echo '<div class="error_box">' . $title . '<div class="error_text">' . $text . '<br/><br/><br/><a href="http://twonineothree.berlios.de/bugreport.php?id=' . $id . '" title="Click here to report a bug. Additional information is needed.">Report a bug</a> | <a href="calladmin.php?id=' . $id . '" title="Click here to contact the administrator of this website.">Contact administrator</a></div></div>' . "\n";
		
	// check if error notification mail should be sent.
	if($CONFIG['CriticalNotify']) {
		mail($CONFIG['CriticalNotifyAddr'], "29o3 (" . $VERSION['version'] . "/" . $VERSION['codename'] . ") Critical Notify", $text, "Cc: " . $_SETTINGS['CriticalNotifyCC'] . "\r\nFrom: 29o3 <29o3@localhost>\r\n");
	}

	if($fatality >= $CONFIG['DebugLevel']) {
		printf("\n</body>\n</html>");
		die;
	}
	
}

function checkConfigWritability($filename = "./config.php", $ifOrIfNot) {

	if(!ifOrIfNot) {
		if(is_writable($filename)) {
			err("Configuration is world-writable", "Your configurationfile (29o3_ROOT/config.php) is still world-writable.\nThis is only required for installation/upgrade purposes and is a strong security risk for 29o3.\nPlease remove writability on config.php if installation/upgrade was accomplished successfully!", 10);
		}
	}
}

function stripSpecialChars($string) {
	
	global $nachars_search, $nachars_replace;
		 
	$string = preg_replace ($nachars_search, $nachars_replace, $string);
	
	return $string;
}

function strfaut($db, $id) {
	
	global $_CONFIG;
	
	$db->query("SELECT name FROM " . $_CONFIG['DatabasePrefix'] . "users WHERE id=$id LIMIT 1");
	if(($author = $db->fetchArray($db->result))) {
		return $author['name'];
	}
	else {
		return "not found";
	}
}

function strfmail($db, $id) {
	
	global $_CONFIG;
	
	$db->query("SELECT email FROM " . $_CONFIG['DatabasePrefix'] . "users WHERE id=$id LIMIT 1");
	if(($email = $db->fetchArray($db->result))) {
		return $email['email'];
	}
	else {
		return "not found";
	}
}

function mktablename($name) {
	global $CONFIG;

	return $CONFIG['DatabasePrefix'] . $name;
}


?>
