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
		$item_overview = new uiMgmtBigMenuItem("overview", "Overview ::", "Go to the overview of the management console", mksyslink("?mgmt;Overview;"));
		$item_general = new uiMgmtBigMenuItem("gensetup", "Preferences ::", "Here you are able to do some generic setup for 29o3.", mksyslink("?mgmt;Preferences;"));
		$item_structure = new uiMgmtBigMenuItem("structure", "Structure ::", "Create structures and content to fit all your needs.", mksyslink("?mgmt;Structure;"));
		$item_media = new uiMgmtBigMenuItem("mediamanager", "Media ::", "With the media manager you can add media (video, images,...) to 29o3.", "/29o3/?mgmt;Media;");
		$item_files = new uiMgmtBigMenuItem("filemanager", "Files ::", "Upload other files to make them available to visitors of your site.", mksyslink("?mgmt;Files;"));
		$item_appearance = new uiMgmtBigMenuItem("appearance", "Appearance ::", "Create and modify layouts for usage in pages and sites.", mksyslink("?mgmt;Appearance;"));
		$item_users = new uiMgmtBigMenuItem("users", "Accounts ::", "Create and manage users and groups and their particular permissions", mksyslink("?mgmt;Accounts;"));
		$item_advanced = new uiMgmtBigMenuItem("advanced", "Advanced ::", "Accomplish more advanced tasks like database recovery etc.", mksyslink("?mgmt;Advanced;"));
		$item_logout = new uiMgmtBigMenuItem("logout", "Log out ::", "Leave 29o3's management console", mksyslink("?mgmt;Logout;"));
		$menu->attach($item_overview);
		$menu->attach($item_general);
		$menu->attach($item_appearance);
		$menu->attach($item_structure);
		$menu->attach($item_media);
		$menu->attach($item_files);
		$menu->attach($item_users);
		$menu->attach($item_advanced);
		$menu->attach($item_logout);

		$string = $menu->__toString() . "\n" . 
			'<div class="adminstripe">&nbsp;</div>' . "\n";

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
