<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  The LayoutManager class holds information about the currently
  used layout of a certain page.
 
*/


// include the layout parser/lexer
require_once($CONFIG['LibDir'] . 'layout/layoutParser.php');
require_once($CONFIG['LibDir'] . 'page/pageDescriptionObject.php');
require_once($CONFIG['LibDir'] . 'api/api.php');

class LayoutManager {

	private $layoutParser;
	private $currentLayoutName;
	private $currentLayoutBuffer;

	private $layoutAlreadyParsed;
	private $layoutReadyForParsing;

	private $pdo;
	
	// not sure if really needed :-\
	private $layoutCache;

	function __construct(pageDescriptionObject &$pdo, $layoutBuffer = "") {
		$this->pdo =& $pdo;
		$this->currentLayoutName = "";
		$this->currentLayoutBuffer = $layoutBuffer;

		if($this->currentLayoutBuffer = "") {
			$this->layoutAlreadyParsed = false;
			$this->layoutReadyForParsing = false;
		} else {
			$this->layoutAlreadyParsed = false;
			$this->layoutReadyForParsing = true;
		}
	}

	function __destruct() {
	}

	function setLayoutFile($layoutFile) {
		$this->currentLayoutName = $layoutFile;
	}

	function getLayoutAlreadyParsed() {
		return true;
	}

	function parseLayout() {
		
		global $CONFIG;

		$api = new API(&$this->pdo);
		
		if(!$this->layoutAlreadyParsed && $this->layoutReadyForParsing) {
			
			require_once($CONFIG["ContentDir"] . "layouts/" . $this->currentLayoutName . ".php");

			$this->pdo->insertIntoBodyBuffer($api->getBufferContent());
			
		}
	}
}

?>
