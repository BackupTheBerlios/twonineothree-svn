<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  This file holds the API class which contains functions for use
  in layouts :)
*/

require_once($CONFIG['LibDir'] . "page/pageDescriptionObject.php");

class API {

	private $pdo;
	private $hObj;
	private $bObj;
	private $layoutBuffer = "";

	function __construct(PageDescriptionObject $pdo) {
		$this->pdo =& $pdo;
	}

	function setOmitBranding() {
		$this->pdo->setOmitBranding(true);
	}

	function getProperty($name) {
		switch($name) {
		case "author_realname":
			$user = new User($this->pdo->databaseConnector, "", $this->pdo->getContent("owner"));
			echo $user->getRealName();
			break;
		case "author_nickname":
			$user = new User($this->pdo->databaseConnector, "", $this->pdo->getContent("owner"));
			echo $user->getNickName();
			break;
		}

	}

	function getBoxContent($name) {
		$boxArray = $this->pdo->boxes;
		$name = $this->pdo->getContent("name") . "_" . $name;

		if(isset($boxArray[$name]["content"])) {
			echo $boxArray[$name]["content"];
			return;
		} else {
			echo "Box not found";
			return;
		}
	}

	function getBoxes() {
		return $this->pdo->boxes;
	}

	function getSysSignature() {
		global $SYSTEM_INFO;

		echo "Powered by <a href=\"http://twonineothree.berlios.de\">29o3</a> " . $SYSTEM_INFO["SystemVersion"] . " Codename " . $SYSTEM_INFO["SystemCodename"];
	}

	function beginLayout() {
		ob_start();
	}

	function endLayout() {
		
		$this->layoutBuffer = ob_get_contents();
		
		ob_end_clean();
	}

	function setMainStylesheet($name, $type) {
		if($type == "internal") {
			$this->pdo->databaseConnector->executeQuery("SELECT * FROM " . mktablename("stylesheets") . " WHERE name='" . $name . "'");
			$test = $this->pdo->databaseConnector;
			if($this->pdo->databaseConnector->getNumRows() != 0) {
				$stylesheetArray = $this->pdo->databaseConnector->fetchArray();
				$this->pdo->scheduleInsertion_Stylesheet($stylesheetArray['content']);
				return 0; // no error
			} else {
				throw new GeneralException("Stylesheet for this page was not found: " . $params[0]);
				return 16; // not found
			}
		}
		if($type == "external") {
			$this->pdo->scheduleInsertion_ExternalStylesheet($params[0]);
		}
		else {
			throw new CodingException("Wrong parameter count, possible missing parameter 2.", __FUNCTION__, "header");
		}
	
	}

	function setLayoutInfo() {

	}

	function getBufferContent() {
		return $this->layoutBuffer;
	}

}

?>
