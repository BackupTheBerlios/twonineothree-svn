<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
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
		$menu = new uiMgmtBigMenu("Admin Menu", "none", 2);
		$item_general = new uiMgmtBigMenuItem("gensetup", "General Setup", "Here you will be able to do some generic setup for 29o3.", "/29o3/?mgmt;GeneralSetup;");
		$item_sites = new uiMgmtBigMenuItem("sitessetup", "Sites", "To gain maximum publishing freedom, you are able to define multiple sites here.", "/29o3/?mgmt;Sites;");
		$item_pages = new uiMgmtBigMenuItem("pagessetup", "Pages", "Click here to define pages for your configured sites.", "/29o3/?mgmt;Pages;");
		$item_media = new uiMgmtBigMenuItem("mediamanager", "Manage Media", "With the media manager you can add media (video, images,...) to 29o3.", "/29o3/?mgmt;MediaManager;");
		$item_files = new uiMgmtBigMenuItem("filemanager", "Files", "Upload files to make them available to visitors of your site.", "/29o3/?mgmt;Files;");
		$item_appearance = new uiMgmtBigMenuItem("appearmanager", "Appearance Settings", "Change the layouts of your pages and sites.", "/29o3/?mgmt;AppearanceManager;");
		$menu->attach($item_general);
		$menu->attach($item_appearance);
		$menu->attach($item_sites);
		$menu->attach($item_pages);
		$menu->attach($item_media);
		$menu->attach($item_files);


		$this->pdo->insertIntoBodyBuffer('<br/><br/><br/>');
		$this->pdo->insertIntoBodyBuffer('<div align="center">');
		$this->pdo->insertIntoBodyBuffer('<div class="enclosure" align="center">');
		$this->pdo->insertIntoBodyBuffer('<div class="headline">29o3 management console</div>');
		$this->pdo->insertIntoBodyBuffer('<div style="text-align: left; width: 600px; font-size: 12px;">');
		$this->pdo->insertIntoBodyBuffer(':: Home :: Help :: About ::');
		$this->pdo->insertIntoBodyBuffer('</div><br/>');

		$this->pdo->insertIntoBodyBuffer($menu->__toString());

		// content here
		$this->pdo->insertIntoBodyBuffer('&nbsp;');
		$this->pdo->insertIntoBodyBuffer('</div>');
		$this->pdo->insertIntoBodyBuffer('</div>');


	}
}

?>
