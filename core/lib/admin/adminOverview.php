<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  This is the admin overview page.
  This type of file also shows how plugins will be handled.
*/

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'user/user.php');

require_once($CONFIG['LibDir'] . 'ui/uiMgmtBigMenu.php');
require_once($CONFIG['LibDir'] . 'ui/uiMgmtBigMenuItem.php');

class AdminOverview {
	
	private $db;
	private $pdo;
	
	function __construct($connector, $pdo) {
	
		$this->db = $connector;
		$this->pdo = $pdo;
		DEBUG("AdminOverview: Constructed.");
	
	}

	function doPreBodyJobs() {
		$this->pdo->scheduleInsertion_ExternalScript("JavaScript", "content/scripts/experimentmenu.js");
	}

	function doBodyJobs() {
		global $SYSTEM_INFO;
		
		$this->pdo->insertIntoBodyBuffer(adminFuncs::getAdminDesignStart("") . "\n<div align=\"center\">");
//		$this->pdo->insertIntoBodyBuffer($menu->__toString() . "</div>\n");

		$this->pdo->insertIntoBodyBuffer("<br/>29o3 " . $SYSTEM_INFO["SystemVersion"] . " '" . $SYSTEM_INFO["SystemCodename"] . "' is currently running on " .  $_SERVER["SERVER_SOFTWARE"] . " which\n<br/> works on top of " . php_uname("s") . "/" . php_uname("m") . " " . php_uname("r") . "<br/>\n" .
		"Currently it is " . strftime("%H:%M:%S on %Y-%m-%d") . ".</div>");

		$this->pdo->insertIntoBodyBuffer(adminFuncs::getAdminDesignEnd());

	}
}

?>
