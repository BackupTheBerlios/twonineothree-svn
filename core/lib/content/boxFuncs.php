<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  LayoutDesignFuncs class: this class holds the functions which
  are callable in the design block of layout definitions for pages.

*/


require_once($CONFIG['LibDir'] . 'common.php');
require_once($CONFIG['LibDir'] . 'page/pageDescriptionObject.php');
require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'user/user.php');

class BoxFuncs {

	private $pdo;	// page description object

	// list of allowed functions. case-sensitive.
	// not that I'm paranoid. the problem is just
	// that each and every single man is after me...
	// hunting me... WAAAAAAAAAH!
	private $allowedFunctions = array(
		"makeLink",
		"getStaticBox",
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

	/*
	syntax can be:
	makeLink("inSite", "Name of the Link", "Name of the page");
	- or -
	makeLink("otherSite", "Name of the Link", "Name of the site", "Name of the page");
	- or -
	makeLink("userDefined", "Name of the Link", "someotherlink");

	The last one is especially useful if you want to create a eMail-Link though
	this could also be accomplished with a normal <a href="">...
	*/
	private function makeLink($params) {
		if($params[0] == "inSite") {
			return "<a href=\"?" . $this->pdo->getSiteName() . "/" . $params[2] . "\">" . $params[1] . "</a>";	
		}
		if($params[0] == "otherSite") {
			return "<a href=\"?" . $params[2] . "/" . $params[3] . "\">" . $params[1] . "</a>";
		}
		if($params[0] == "userDefined") {
			return "<a href=\"" . $params[2] . "\">" . $params[1] . "</a>";
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