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

	function beginSuccessiveBoxes() {
		ob_start();
	}

	function endSuccessiveBoxes($group = "") {
		$layout = ob_get_contents();
		ob_end_clean();

		$boxes = $this->getBoxes();

//		mdBoxArraySort(&$boxes, "title", "asc");
		
		foreach($boxes as $key => $box) {
			if($box['successive'] != true || $box['successive'] != "t") {
				continue;
			}

			if($group != "") { 
				if($box['group'] != $group) {
					continue;
				}
			}
				
			$layout_copy = $layout;

			$layout_copy = str_replace("{BOX:TITLE}", $box['title'], $layout_copy);
			$layout_copy = str_replace("{BOX:AUTHOR}", $box['owner'], $layout_copy);
			$layout_copy = str_replace("{BOX:CONTENT}", $box['content'], $layout_copy);
			$layout_copy = str_replace("{BOX:ID}", $box['id'], $layout_copy);

			$layout_copy = $layout_copy;
			echo $layout_copy;
		}
	}

	function makeTOC($link, $group = "", $exclude = "", $cutoff = 0) {

		$link_style = $link;

		$exclude_list = explode(",", $exclude);

		$boxes = $this->getBoxes();

//		mdBoxArraySort($boxes, "title", "asc");

		foreach($boxes as $box) {

			$sboxname = substr($box['name'], strlen($this->pdo->getPageName()) + 1, strlen($box['name']));
			if(in_array($sboxname, $exclude_list) || $box['group'] != $group) {
				continue;
			}	
				
			if($cutoff > 0 && strlen($box['title']) > $cutoff) {
				$substr = substr($box['title'], $cutoff, strlen($box['title']) - $cutoff);
				$nextwspos = strpos($substr, " ");
				$text = substr($box['title'], 0, $cutoff + $nextwspos);
				$text .= "...";
			} else {
			 	$text = $box['title'];
			}
				
				
			$link_style = str_replace("{LINK:ANCHOR}", $box['title'], $link_style);
			$link_style = str_replace("{LINK:TITLE}", $text, $link_style);
			$link_style = str_replace("{LINK:ID}", $box['id'], $link_style);

			echo $link_style . "\n";
			$link_style = $link;
		}

	}

	function setBoxOrder($sortby, $order) {
		mdBoxArraySort($this->pdo->boxes, $sortby, $order);
	}

}

?>
