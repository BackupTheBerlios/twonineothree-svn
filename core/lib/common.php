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

?>
