<?php
/* 29o3 content management system
 * (c) 2003-2004 by Ulrik Guenther <kpanic@mg2.org>
 * This software is licensed under the terms of the BSD license.
 */ 
 
require_once('./config.php');
require_once($CONFIG['LibDir'] . 'common.php');

$output_started = false;


global $CONFIG;
global $SYSTEM_INFO;

// include the console library
require_once($CONFIG['LibDir'] . 'sys/console.php');
//include the database library (defined by the vars above, i know it looks cryptic...)
require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
// include the xhtml header class
require_once($CONFIG['LibDir'] . 'xhtml/xhtmlheader.php');
// include the xhtml body class
require_once($CONFIG['LibDir'] . 'xhtml/xhtmlbody.php');
// include the Layout Manager (incl. parser) class
require_once($CONFIG['LibDir'] . 'layout/layoutManager.php');
// include the PageRequest class
require_once($CONFIG['LibDir'] . 'page/pagerequest.php');
// include PageDescriptionObject
require_once($CONFIG['LibDir'] . 'page/pageDescriptionObject.php');

checkConfigWritability("./config.php", $CONFIG['Developer_Debug']);

// bootstrap HAS to be called *HERE* and nowhere else!!!
// otherwise it WILL NOT WORK!!
bootstrap();

/*
	function:	DEBUG()
	purpose:	Does debug output if debugging is enabled
	---
	input:		$console - Handle to SystemConsole object
			$message - not to be explained I think...
	output:		(none)
*/
function DEBUG($console, $message) {

	global $CONFIG;

	if(method_exists($console, "write")) {
		if($CONFIG['Developer_Debug'])
			$console->write($message);
	} else {
		err("SystemConsole object not available", "SystemConsole object not available.<br>I think this means something went *REALLY* wrong!", 10);
	}
}
// END debugOutput()

/*
	function:	bootstrap()
	purpose:	Does the first things to do in setting up output
	---
	input:		(none)
	output:		(none)
*/
function bootstrap() {

	global $CONFIG, $SYSTEM_INFO, $output_started;

	header("Content-type: application/xhtml+xml\r");

	if(version_compare(phpversion(), $SYSTEM_INFO['MinPhpVersion']) < 0) {
		err("Newer version of PHP required", "A newer version of of PHP is required in order to run this version of 29o3.<br>You have: " . phpversion() . ". PHP " . $SYSTEM_INFO['MinPhpVersion'] . " is the minimal requirement.", 10);
	}

	$console = new SystemConsole();
	
	DEBUG($console, "Bootstrapping started...");
		
	$request = new PageRequest();
	$request->parseRequest();

	
	$connector = new DatabaseConnector();
	$connector->setupConnection($CONFIG['DatabaseHost'], $CONFIG['DatabaseUser'], $CONFIG['DatabasePassword'], $CONFIG['DatabaseName'], $CONFIG['DatabasePort']);

	DEBUG($console, "Connected to database.");
	
	printf('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">%s', "\n");
	printf('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">%s', "\n");

	$output_started = true;

	// construct header and body objects
	$header = new XHTMLHeader();
	$body = new XHTMLBody;

	$pdo = new pageDescriptionObject($header, $body, $connector);

	$connector->executeQuery("SELECT * FROM " . mktablename("pages") . " WHERE name='" . $request->getRequestedPage() . "'");

	$pageInfo = $connector->fetchArray();
	$pdo->setPageDescriptionA($pageInfo);

	$header->setTitle($pdo->getContent("title"));

	if($pdo->getContent("description") != "") {
		$header->addMetaDCDescription($pdo->getContent('description'));
	}
	if($pdo->getContent("subject") != "") {
		$header->addMetaDCSubject($pdo->getContent("subject"));
	}
	if($pdo->getContent("date") != 0) {
		$header->addMetaDCDate(strftime("%Y-%m-%d", $pdo->getContent('date')));
	}
	if($pdo->getContent("creator") != "") {
		$header->addMetaDCCreator($pdo->getContent("creator"));
	}
	if($pdo->getContent("contributors") != "") {
		$c_arr = explode(";", $pdo->getContent('contributors'));
		for($i = 0; $i <= count($c_arr)-1; $i++) {
			$header->addMetaDCContributor($c_arr[$i]);
		}
	}
	if($pdo->getContent("type") != "") {
		$header->addMetaDCType($pdo->getContent("type"));
	}
	if($pdo->getContent("sources") != "") {
		$sources_array = explode(";", $pdo->getContent('sources'));
		for($i = 0; $i <= count($sources_array)-1; $i++) {
			$header->addMetaDCSource($sources_array[$i]);
		}
	}


/*
!!!	FIXME: 	THE FOLLOWING CODE CAUSES A RACE CONDITION ON BOTH APACHE2/PHP
!!!		AND PHP-CLI. 
!!!	SEV:   	(5) - Causes server process to fill RAM and swap -> kill
!!!	RES:	Currently no resolution, commented out because of this.
!!!		I'd say it has got something to do with the database for
!!!		I cannot find an error elsewhere.
>!<	*** FIXED ***
>!<	FUCK YOU FUCK YOU DAMN CODER!!!! FUCK YOU!!!
*/
	if($pdo->getContent("language") != "") {
		$header->addMetaDCLanguage($pdo->getContent('language'));
	}

	if($pdo->getContent('copyright') != "") {
		$header->addMetaDCRights($pdo->getContent("copyright"));
	}


	// this is the r0x0r1ng stylesheet which controls how system messages (errors, etc.) appear
	$pdo->scheduleInsertion_ExternalStylesheet("n_style.css");

	// now, get the page's stylesheet; it might be empty, but we'll add it if not :)
	$connector->executeQuery("SELECT * FROM " . mktablename("stylesheets") . " WHERE " . mktablename("stylesheets") . ".name=" . mktablename("pages") . ".name AND " . mktablename("stylesheets") . ".name='" . $request->getRequestedPage() . "'" );
	if($connector->getNumRows() != 0) {
		$localStylesheets = $connector->fetchArray();
		$pdo->scheduleInsertion_Stylesheet($localStylesheets['content']);
	}

	$layoutManager = new LayoutManager($pdo);

	$connector->executeQuery("SELECT * FROM " . mktablename("layout") . " WHERE name='" . $pageInfo['layout'] . "'");
	if($connector->getNumRows() != 0) {
		$currentLayout = $connector->fetchArray();
		$layoutManager->setLayout($currentLayout['content']);
		$layoutManager->parseLayout();
		// TODO: Throw exception if no layout found
	}


	// print the buffer of the header since we're done with it :)
	$pdo->doInsertions();
	$pdo->printHeaderBuffer();
	// destruct the header object, it might not be used from here on.
	// as php doesn't offer something like C++'s delete [someobject]
	// we simply set $header to NULL.
	$pdo->destroyHeaderObject();

	DEBUG($console, $connector->getExecutedQueries() . " queries executed.");
	
	if($body) {
		$body->eyecandyConsole($console);
	} else {
		$console->printBuffer();
	}

	$body->printBuffer();

	printf('</html>');
}
// END bootstrap()

?>
