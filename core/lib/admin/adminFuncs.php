<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
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

		$this->adminStylesheet = implode("\n", file($CONFIG['LibDir'] . 'admin/adminStylesheet.css'));

	}

	function getAdminStylesheet() {
		return $this->adminStylesheet;
	}

	function getAdminMenu() {
		$adminMenu =  "<a href=\"?2mc;overview\">overview</a> | <a href=\"?2mc;generalSetup\">general setup</a> | <a href=\"?2mc;pageWizard\">page wizard</a> | ";
		$adminMenu .= "<a href=\"?2mc;editPages\">edit pages</a> | <a href=\"?2mc;editLayouts\">edit layouts</a> | <a href=\"?2mc;editBoxes\">edit boxes</a> | ";
		$adminMenu .= "<a href=\"?2mc;help\">help</a>";

		return $adminMenu;
	}
}

?>
