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

class LayoutDesignFuncs {

	private $pdo;	// page description object

	// list of allowed functions. case-sensitive.
	// not that I'm paranoid. the problem is just
	// that each and every single man is after me...
	// hunting me... WAAAAAAAAAH!
	private $allowedFunctions = array(
		"getBoxContent",
		"getStaticBox",
		"getProperty"
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
/*		echo "<pre>";
		print_r($boxArray);
		echo "</pre>";*/
		$name = $this->pdo->getContent("name") . "_" . $params[0];
//		echo $name;
//		echo $boxArray[$name][$params[1]];
		$retval = $boxArray[$name]["content"];
		return $retval;
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
	

}

?>
