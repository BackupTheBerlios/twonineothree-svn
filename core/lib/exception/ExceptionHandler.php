<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  This file contains the exception handler for all uncaught exceptions
  in 29o3.
*/

require_once($CONFIG['LibDir'] . 'common.php');
require_once($CONFIG['LibDir'] . 'exception/GeneralException.php');

function ExceptionHandler($exception) {

	global $CONFIG;
	global $output_started;
	global $header_started;
	global $body_started;
	
	if(!$output_started) {
		printf("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n<head>\n<title>29o3</title>\n<link rel=\"stylesheet\" href=\"n_style.css\" />\n</head>\n<body>");
	}
	if(!$header_started && !$body_started && $output_started) {
		printf('<head><link rel="stylesheet" href="n_style.css" /></head><body>');
	}
	if($header_started && !$output_started) {
		printf('<body>');
	}
	$header_started = true;
	$body_started = true;
	$output_started = true;
	echo '<div class="error_box">29o3: Exception occured<div class="error_text">' . /*stripSpecialChars(*/$exception->__toString()/*)*/ . '<br/><br/>';
	if($CONFIG['Developer_Debug']) {
		echo '<strong>Traceback:</strong><br/><textarea name="traceback" style="width: 90%; height: 100px; border: 1px solid #666666; font-family: courier; font-size: 11px;" readonly="readonly">';
		echo $exception->getTraceAsString();
		echo '</textarea>';
	}
	echo '<a href="https://developer.berlios.de/bugs/?func=addbug&amp;group_id=1891" title="Click here to report a bug. Additional information is needed.">Report a bug</a> | <a href="calladmin.php?packed=' . rawurlencode(getenv("QUERY_STRING")) . '" title="Click here to contact the administrator of this website.">Contact administrator</a></div></div>' . "\n";
		
	if($exception->getSeverity() >= $CONFIG['DebugLevel']) {
		printf("\n</body>\n</html>");
		exit();
	}

	printf('</body></html>');
	exit;

}

set_exception_handler("ExceptionHandler");

function ErrorToExceptionWrapper($errno, $errstr, $errfile, $errline) {
	global $CONFIG;
	if(($errno == (E_NOTICE || E_USER_NOTICE) && $CONFIG['Developer_Debug']) || $errno == (E_ERROR || E_WARNING || E_PARSE)) {
		// since the error handler cannot throw new exceptions properly, we need to
		// use the dirty, dirty hack of calling the exception handler directly.
		ExceptionHandler(new GeneralException($errstr . " in file " . $errfile . " on line " . $errline));
	}
}
?>
