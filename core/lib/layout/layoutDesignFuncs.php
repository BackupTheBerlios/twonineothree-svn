<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  LayoutDesignFuncs class: this class holds the functions which
  are callable in the design block of layout definitions for pages.

  **** ATTENTION ****
  AS OF 2004-12-19, THIS IS NOT USED ANYMORE. PLEASE REVIEW THE FILE
  "UPDATING" IN THE ROOT DIRECTORY OF THIS DISTRIBUTION OR CONTACT
  THE AUTHOR OF 29o3 DIRECTLY!


*/


require_once($CONFIG['LibDir'] . 'common.php');
require_once($CONFIG['LibDir'] . 'page/pageDescriptionObject.php');
require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'user/user.php');

class LayoutDesignFuncs {

	private $pdo;	// page description object

	// list of allowed functions. case-sensitive.
	// not that I'm paranoid. the problem is just
	// that each and every single man is after me...
	// hunting me... WAAAAAAAAAH!
	private $allowedFunctions = array(
		"getBoxContent",
		"getStaticBox",
		"getConsecutiveBoxes",
		"getProperty",
		"setOmitBranding",
		"getSysSignature"
	);

	function __construct(pageDescriptionObject &$pdo) {

		$this->pdo =& $pdo;

	}

	function grabRightFunction($name, $parameterArray) {
		if(array_search($name, $this->allowedFunctions) !== false) {
			$retval = $this->{$name}($parameterArray);

			return $retval;
		}
	}

	private function getBoxContent($params) {

		$boxArray = $this->pdo->boxes;
		$name = $this->pdo->getContent("name") . "_" . $params[0];
		if(isset($boxArray[$name]["content"])) {
			$retval =  $boxArray[$name]["content"];
		} else {
			$retval = "Box Not Found";
		}
		return $retval;
	}

	/*
	getConsecutiveBoxes()
	especially useful for creating newspages and/or weblogs
	params[0] is the sublayout to apply

	Consecutive Boxes need to be numbered from 0 to 65535 per page.
	Their name in the boxes table must be
	PAGENAME_Consecutive_[0-65535]
	*/
	private function getConsecutiveBoxes($params) {
		$boxArray = $this->pdo->boxes;
		for($i = 0; $i <= 65536; $i++) {
			$name = $this->pdo->getContent("name") . "_Consecutive_" . $i;
		}
	}

	private function getStaticBox($params) {
		
	}

	private function getProperty($params) {
		switch($params[0]) {

		case "author_realname":
			$user = new User($this->pdo->databaseConnector, "", $this->pdo->getContent("owner"));
			return $user->getRealName();
		case "author_nickname":
			$user = new User($this->pdo->databaseConnector, "", $this->pdo->getContent("owner"));
			return $user->getNickName();
		
		}
	}

	private function setOmitBranding($params) {
		$this->pdo->setOmitBranding(true);
		
	}

	private function getSysSignature($params) {
		
		global $SYSTEM_INFO;
		return "Powered by <a href=\"http://twonineothree.berlios.de\">29o3</a> " . $SYSTEM_INFO["SystemVersion"] . " Codename " . $SYSTEM_INFO["SystemCodename"];
		
	}
	

}

?>
