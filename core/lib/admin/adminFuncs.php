<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  The class adminFuncs contains all functions needed for managing 
  a website powered by 29o3.
 
*/

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'page/pageDescriptionObject.php');
require_once($CONFIG['LibDir'] . 'ui/uiMgmtBigMenu.php');
require_once($CONFIG['LibDir'] . 'ui/uiMgmtBigMenuItem.php');


class adminFuncs {

	private $pdo;
	private $pageRequest;

	private $adminStylesheet = ""; 

	function __construct(pageDescriptionObject &$pdo, PageRequest &$pageRequest) {

		global $CONFIG;

		$this->pdo =& $pdo;
		$this->pageRequest =& $pageRequest;


		$this->adminStylesheet = "content/stylesheets/adminStylesheet.css";
	}

	function getAdminStylesheet() {
		return $this->adminStylesheet;
	}

	function getAdminMenu() {
		
		$menu = new uiMgmtBigMenu("Admin Menu", "none", 1);
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

		$string = $menu->__toString() . "\n" . 
			'<div class="adminstripe">&nbsp;</div>';

		return $string;
	}

	static function getAdminDesignStart($name) {
		$string = '<br/><br/><br/>';
		$string .= '<div align="center">';
		$string .= '<div class="enclosure" align="center">';
		if ($name != "") {
			$name = " &middot; " . $name;
		}
		$string .= '<div class="headline">29o3 management console' . $name . '</div>';
		$string .= '<div style="text-align: left; width: 600px; font-size: 12px;">';
		$string .= ':: <a href="' . mksyslink("?mgmt;Overview;") .  '">Home</a> :: <a href="' . mksyslink("?mgmt;Help;") . '">Help</a> :: <a href="' . mksyslink("?mgmt;About;") . '">About</a> ::';
		$string .= '</div><br/>';

		return $string;

	}

	static function getAdminDesignEnd() {
		$string = '<br/><br/>';
		$string .= '</div>';
		$string .= '</div>';

		return $string;

	}
}

?>
