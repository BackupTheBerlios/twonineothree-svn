<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  The file "common.php" holds functions which are frequently needed
  by different parts of 29o3. This includes for example the mktablename
  function which creates a table name (hah!) combined with the database
  prefix defined in the configuration for better readability of the code.

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
		"'(\%22)'",
		"'(\\r)'",
		"'(\\n)'"
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
		 "",
		 "<br/>",
		 "<br/>"
		 );

function checkConfigWritability($filename = "./config.php", $ifOrIfNot) {

	if(!$ifOrIfNot) {
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


// killScriptKiddies()
// simple countermeasures for prevention
// of sql injection attacks
function killScriptKiddies($string) {
	
	$string = rawurldecode($string);

	$string = str_replace("\\", "", $string);
	$string = str_replace("'", "", $string);
	$string = str_replace("\"", "", $string);
//	$string = ereg_replace("\\", "", $string);
	$string = str_replace("../", "", $string);

	if(strpos("/", $string) === 0) {
		$string = substr($string, 1, strlen($string)-1);
	}

	return $string;

}

?>
