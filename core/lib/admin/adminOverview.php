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

	}

	function doBodyJobs() {
		global $SYSTEM_INFO;
		
		$menu = new uiMgmtBigMenu("Admin Menu", "none", 2);
		$item_general = new uiMgmtBigMenuItem("gensetup", "General Setup ::", "Here you will be able to do some generic setup for 29o3.", mksyslink("?mgmt;GeneralSetup;"));
		$item_sites = new uiMgmtBigMenuItem("structure", "Structure ::", "Create different sites to fit all your needs.", mksyslink("?mgmt;Structure;"));
		$item_pages = new uiMgmtBigMenuItem("contentsetup", "Content ::", "Click here to define pages which fill your sites with content.", mksyslink("?mgmt;Content;"));
		$item_media = new uiMgmtBigMenuItem("mediamanager", "Media ::", "With the media manager you can add media (video, images,...) to 29o3.", "/29o3/?mgmt;MediaManager;");
		$item_files = new uiMgmtBigMenuItem("filemanager", "Files ::", "Upload files to make them available to visitors of your site.", mksyslink("?mgmt;Files;"));
		$item_appearance = new uiMgmtBigMenuItem("appearance", "Appearance ::", "Create and modify layouts for usage in pages and sites.", mksyslink("?mgmt;Appearance;"));
		$menu->attach($item_general);
		$menu->attach($item_appearance);
		$menu->attach($item_sites);
		$menu->attach($item_pages);
		$menu->attach($item_media);
		$menu->attach($item_files);


		$this->pdo->insertIntoBodyBuffer(adminFuncs::getAdminDesignStart("") . "\n<div align=\"center\">");
		$this->pdo->insertIntoBodyBuffer($menu->__toString() . "</div>\n");

		$this->pdo->insertIntoBodyBuffer("<br/>29o3 " . $SYSTEM_INFO["SystemVersion"] . " '" . $SYSTEM_INFO["SystemCodename"] . "' is currently running on " .  $_SERVER["SERVER_SOFTWARE"] . " which\n<br/> works on top of " . php_uname("s") . "/" . php_uname("m") . " " . php_uname("r") . "<br/>\n" .
		"Currently it is " . strftime("%H:%M:%S on %Y-%m-%d") . ".");

		$this->pdo->insertIntoBodyBuffer(adminFuncs::getAdminDesignEnd());

	}
}

?>
