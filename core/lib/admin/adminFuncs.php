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


class adminFuncs {

	private $pdo;
	private $pageRequest;

	private $adminStylesheet = ""; 

	function __construct(pageDescriptionObject &$pdo, PageRequest &$pageRequest) {

		global $CONFIG;

		$this->pdo =& $pdo;
		$this->pageRequest =& $pageRequest;

		$this->adminStylesheet = file_get_contents($CONFIG['LibDir'] . 'admin/adminStylesheet.css');

	}

	function getAdminStylesheet() {
		return $this->adminStylesheet;
	}

	function getAdminMenu() {
/*		$adminMenu =  "<a href=\"?2mc;Overview;\">overview</a> &middot; <a href=\"?2mc;GeneralSetup;\">general setup</a> &middot; <a href=\"?2mc;PageWizard;\">page wizard</a> &middot; ";
		$adminMenu .= "<a href=\"?2mc;EditPages;\">edit pages</a> &middot; <a href=\"?2mc;EditLayouts;\">edit layouts</a> &middot; <a href=\"?2mc;EditBoxes;\">edit boxes</a> &middot; ";
		$adminMenu .= "<a href=\"?2mc;help\">Help</a>";
*/
		$adminMenu = "";
		return $adminMenu;
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
