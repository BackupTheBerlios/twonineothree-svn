<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  This file manages the creation of an evil empire and the
  therewith associated pwnage of the world by 29o3. HAHA!
 
*/

// check if the configuration file exists, if yes, exit
if(!file_exists('./config.php')) {
	echo "The file config.php, of essential meaning for the correct function of 29o3, does not exist<br/>";
	echo "If you just extracted your 29o3 archive, edit the file blankconfig.php then rename it to config.php<br/>";
	echo "If 29o3's coder was not too lazy, you can also use the included installer by simply calling<br/><a href=\"installer.php\">install.php</a> with a web browser.";
	exit;
}

require_once('./config.php');
require_once($CONFIG['LibDir'] . 'common.php');
require_once($CONFIG['LibDir'] . 'sys/param.php');

if(version_compare(phpversion(), $SYSTEM_INFO['MinPhpVersion']) < 0) {
		err("Newer version of PHP required", "A newer version of of PHP is required in order to run this version of 29o3.<br>You have: " . phpversion() . ". PHP " . $SYSTEM_INFO['MinPhpVersion'] . " is the minimal requirement.", 10);
}


$output_started = false;
$header_started = false;
$body_started = false;
$console = nUlL;


global $CONFIG;
global $SYSTEM_INFO;

// include the console library
require_once($CONFIG['LibDir'] . 'sys/console.php');
//include the database library (defined by the vars above, i know it looks cryptic...)
require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
// include performances measurement (profiler) class
require_once($CONFIG["LibDir"] . 'sys/profiler.php');
// include caching class
require_once($CONFIG["LibDir"] . 'cache/cacheObject.php');
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
// include the Exception handler
require_once($CONFIG['LibDir'] . 'exception/ExceptionHandler.php');
// include general exception class
require_once($CONFIG['LibDir'] . 'exception/GeneralException.php');
// include session management class
require_once($CONFIG['LibDir'] . 'user/sessionManager.php');

$profiler = new Profiler();
$profiler->addBreakpoint();

set_exception_handler("ExceptionHandler");
set_error_handler("ErrorToExceptionWrapper");

checkConfigWritability("./config.php", $CONFIG['Developer_Debug']);

// bootstrap HAS to be called *HERE* and nowhere else!!!
// otherwise it WILL NOT WORK!!
bootstrap();

/*
	function:	bootstrap()
	purpose:	Does the first things to do in setting up output
	---
	input:		(none)
	output:		(none)
*/
function bootstrap() {

	global $CONFIG, $SYSTEM_INFO, $output_started, $body_started, $console, $profiler;

	header("Content-type: application/xhtml+xml\r");

	$console = new SystemConsole();
	
	DEBUG("<strong>This is 29o3 " . $SYSTEM_INFO['SystemVersion'] . " Codename " . $SYSTEM_INFO['SystemCodename'] . "</strong>");
	DEBUG("SYS: Bootstrapping started...");
	
	$connector = new DatabaseConnector();
	$connector->setupConnection($CONFIG['DatabaseHost'], $CONFIG['DatabaseUser'], $CONFIG['DatabasePassword'], $CONFIG['DatabaseName'], $CONFIG['DatabasePort']);

	DEBUG("DB: Connected to database.");

	
	$request = new PageRequest($connector);
	$request->parseRequest();

	// instanciate new cache object
	$co = new cacheObject($connector, $request->getRequestedSite(), $request->getRequestedPage());
	// check if we have content for current page cached
	$cacheContent = $co->getCached();
	if($cacheContent  === false) {

		// construct header and body objects
		$header = new XHTMLHeader();
		$body = new XHTMLBody();

		$pdo = new pageDescriptionObject($header, $body, $connector, $request->getWantAdmin());

		$connector->executeQuery("SELECT * FROM " . mktablename("pages") . " WHERE name='" . $request->getRequestedPage() . "'");

	/* lets see what the admin wants */
		if($request->getWantAdmin()) {
		
			if($request->getRequestedPage() == "overview") {
			
			}
		}

		$pageInfo = $connector->fetchArray();
		$pdo->setPageDescriptionA($pageInfo, $request->getRequestedSite());

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

		if($pdo->getContent('no_cache') == 1) {
			$co->setScheduleCaching(false);
			DEBUG("CACHE: Caching deactivated on request.");
		}
	
		// now, get the page's stylesheet; it might be empty, but we'll add it if not :)
		if($request->getWantAdmin() <= 1) {
			if($request->getWantAdmin() == 1) {
				$co->setScheduleCaching(false);
				DEBUG("CACHE: Admin wanted, caching deactivated.");
			}
		
			$layoutManager = new LayoutManager($pdo);
	
			$pdo->getAvailableBoxes();
	
			$connector->executeQuery("SELECT * FROM " . mktablename("layouts") . " WHERE name='" . $pageInfo['layout'] . "'");
			if($connector->getNumRows() != 0) {
				$currentLayout = $connector->fetchArray();
				$layoutManager->setLayoutFile($currentLayout['file']);
				$layoutManager->parseLayout();
			} else {
				throw new GeneralException("No layout found. 29o3 cannot continue.");
			}
	
			if($request->getWantAdmin()) {
				require_once($CONFIG['LibDir'] . 'admin/adminFuncs.php');

				$af = new adminFuncs($pdo, $request);
		
				$pdo->scheduleInsertion_Stylesheet($af->getAdminStylesheet());
//				$pdo->insertBodyDiv("<img src=\"lib/images/adminlogotop.png\" style=\"vertical-align: top; text-align: left; border: 0; padding: 0; margin: 0;\" /><span class=\"adminMenu\" style=\"width: 100%;\">" . $af->getAdminMenu() . "</span>", "adminStripe", "2mc_menu", "29o3 management console");	
			}
	
			
			DEBUG("DB: " . $connector->getExecutedQueries() . " queries executed.");

			$connector->closeConnection();
			DEBUG("DB: Connection closed.");

			$profiler->addBreakpoint();
			DEBUG("SYS: Resource usage,  sys:" . $profiler->getBreakpointGrandSysDifference() . "&micro;s usr:" .  $profiler->getBreakpointGrandUserDifference() . "&micro;s" );


			DEBUG("SYS: Exiting normally.");

			// print the buffer of the header since we're done with it :)
			$pdo->doInsertions();

			// we have everything at this point... start caching procedure
			$co->doCache($pdo->getBuffers());
		
			if($CONFIG['Developer_Debug'] == true ) {
				if($body) {
					$body->eyecandyConsole($console);
				} else {
					$console->printBuffer();
	
				}
			}

			if($pdo->getBrandingState() == true) {
				$pdo->insertBodyDiv("Powered by <a href=\"http://twonineothree.berlios.de\">29o3</a> " . $SYSTEM_INFO["SystemVersion"] . " Codename " . $SYSTEM_INFO["SystemCodename"], "poweredBy", "poweredBy_Banner", "Powered by 29o3");
			}

			printf('<?xml version="1.0" encoding="UTF-8"?>');
			printf('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">%s', "\n");
			printf('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">%s', "\n");
	
			$pdo->printHeaderBuffer();

			$header_started = true;
		
			// destruct the header object
			$pdo->destroyHeaderObject();

			$body_started = true;

			// print out the body buffer 
			$pdo->printBodyBuffer();

			printf('</html>');
	
			// exit normally.
			exit(0);	
		}
		else {
			$co->setScheduleCaching(false);
			$pdo->setOmitBranding(true);
			DEBUG("CACHE: Admin wanted, caching deactivated.");
			
			require_once($CONFIG['LibDir'] . 'admin/adminFuncs.php');
	
			$co->setScheduleCaching(false);
		
			$af = new adminFuncs($pdo, $request);
		
			$pdo->scheduleInsertion_Stylesheet($af->getAdminStylesheet());
//			$pdo->insertBodyDiv("<img src=\"lib/images/adminlogotop.png\" style=\"vertical-align: top; text-align: left; border: 0; padding: 0; margin: 0;\" /><span class=\"adminMenu\" style=\"width: 100%;\">" . $af->getAdminMenu() . "</span>", "adminStripe", "2mc_menu", "29o3 management console");	

		
			// this part is for the admin scripts which require
			// are not fetched from database	
			DEBUG("SYS: Skipping normal layout and box fetching procedures");
			$header->setTitle("29o3 management console");

			$ao = NULL;
			
			$func = $request->getWantedAdminFunc();
			if($func == "" || ($func != "Overview" || $func != "UserManagement")) {
				$func = "Overview";
			}
			// administration needs admin logged in
			$sm = new sessionManager($connector);

			if($sm->checkSession() == false) {
				DEBUG("MGMT: Admin not logged in.");

				$func = "Login";
			}
			require_once($CONFIG["LibDir"] . 'admin/admin' . $func . '.php');

			$name = "Admin" . $func;
			$ao = new $name($connector, $pdo, $sm);

			$ao->doPreBodyJobs();		
			$ao->doBodyJobs();

			DEBUG("DB: " . $connector->getExecutedQueries() . " queries executed.");
			$profiler->addBreakpoint();
			
			DEBUG("SYS: Resource usage,  sys:" . $profiler->getBreakpointGrandSysDifference() . "&micro;s usr:" .  $profiler->getBreakpointGrandUserDifference() . "&micro;s" );
	
			$connector->closeConnection();
			DEBUG("DB: Connection closed.");

			DEBUG("SYS: Exiting normally.");

			if($CONFIG['Developer_Debug'] == true ) {
				if($body) {
					$body->eyecandyConsole($console);
				} else {
					$console->printBuffer();
				}
			}
		
//			$pdo->insertBodyDiv("Powered by <a href=\"http://twonineothree.berlios.de\">29o3</a> " . $SYSTEM_INFO["SystemVersion"] . " Codename " . $SYSTEM_INFO["SystemCodename"], "poweredBy", "poweredBy_Banner", "Powered by 29o3");

			// print the buffer of the header since we're done with it :)
		
			printf('<?xml version="1.0" encoding="UTF-8"?>');
			printf('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">%s', "\n");
			printf('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">%s', "\n");

			
			$pdo->doInsertions();
			$pdo->printHeaderBuffer();

			$header_started = true;
	
			// destruct the header object
			$pdo->destroyHeaderObject();

			$body_started = true;
	
			// print out the body buffer 
			$pdo->printBodyBuffer();

			printf('</html>');
		
			// exit normally
			exit(0);
		}
	} else {
		echo $co->getCacheContent();

		DEBUG("DB: " . $connector->getExecutedQueries() . " queries executed.");
		
		$profiler->addBreakpoint();
		DEBUG("SYS: Resource usage,  sys:" . $profiler->getBreakpointGrandSysDifference() . "&micro;s usr:" .  $profiler->getBreakpointGrandUserDifference() . "&micro;s" );


		$connector->closeConnection();
		DEBUG("DB: Connection closed.");

		DEBUG("SYS: Exiting normally.");


		if($CONFIG['Developer_Debug'] == true ) {
				echo '<center><div class="eyecandyConsole">' . $console->getBuffer() . '</div></center>';
		}		
		echo "\n</body>\n</html>";

		// exit normally
		exit(0);
	}

	// never reached

}
// END bootstrap()

?>
