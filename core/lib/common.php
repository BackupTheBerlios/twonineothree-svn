<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  The file "common.php" holds functions which are frequently needed
  by different parts of 29o3. This includes for example the mktablename
  function which creates a table name (hah!) combined with the database
  prefix defined in the configuration for better readability of the code.

*/

require_once($CONFIG['LibDir'] . 'sys/console.php');
require_once($CONFIG['LibDir'] . 'exception/GeneralException.php');

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

	if($ifOrIfNot) {
		if(is_writable($filename)) {
			throw new GeneralException("29o3's configuration file config.php is world-writable which is potentially a big huge security risk. Please change this, otherwise 29o3 will refuse to work.");
		}
	}
}

function stripSpecialChars($string) {
	
	global $nachars_search, $nachars_replace;
		 
	$string = preg_replace ($nachars_search, $nachars_replace, $string);
	
	return $string;
}

function mktablename($name) {
	global $CONFIG;

	return $CONFIG['DatabasePrefix'] . $name;
}

function mksyslink($path) {
	global $CONFIG;

	// get the position of the first / after http(s)://
	$pos = strpos($CONFIG["SiteAddress"], "/", 8); 

	$tmp = substr($CONFIG["SiteAddress"], $pos);

	$tmp .= $path;
	return $tmp;
	
}


// killScriptKiddies()
// simple countermeasures for prevention
// of sql injection attacks
function killScriptKiddies($string) {
	
	$string = rawurldecode($string);

	$string = str_replace("\\", "", $string);
	$string = str_replace("'", "", $string);
	$string = str_replace("\"", "", $string);
	$string = str_replace("../", "", $string);

	if(strpos("/", $string) === 0) {
		$string = substr($string, 1, strlen($string)-1);
	}

	return $string;

}

function killScriptKiddiesGently(&$string) {

	$string = rawurldecode($string);

	$string = str_replace("\\", "\\", $string);
	$string = str_replace("'", "&acute;", $string);
	$string = str_replace("\"", "&quot;", $string);
	$string = str_replace("&", "&amp;", $string);

	return $string;
}

function DEBUG($message, $level = 0) {

	global $CONFIG;
	global $console;

	if($CONFIG['Developer_Debug'] && $level <= $CONFIG['DebugLevel']) {
		if(method_exists($console, "write")) {
				$console->write($message);
		} else {
			throw new GeneralException("SystemConsole Object not available. This means something went *really* wrong!");
		}
	}
}

// mdBoxArraySort works on *REFERENCES* !!!
function mdBoxArraySort(&$array, $by, $order) {

	$tmpArr = array();

	foreach($array as $key => $value) {
		
		$tmp = array($key => $value["$by"]);

		$tmpArr = array_merge($tmp, $tmpArr);

	}


	if($order == "desc") {
		asort($tmpArr);
		reset($tmpArr);
	} else {
		arsort($tmpArr);
		reset($tmpArr);
	}


	$sortedBoxes = array();

	foreach($tmpArr as $key => $value) {
		
		$tempArray = array($array[$key]["name"] => $array[$key]);
		$sortedBoxes = array_merge($tempArray, $sortedBoxes);
		
	}

	$array = $sortedBoxes;

}

function getMsgFromNo($num) {

	$errortable = array(
	0	=>	"No error. Why did you ask me for a description?!",
	1	=>	"System level error",
	401	=>	"Forbidden",
	404	=>	"Not Found"
	);

	if(isset($errortable[$num])) {
		return $errortable[$num];
	} else {
		return "Unknown error.";
	}
	// never reached

}

?>
