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

		$this->layoutParser = new LayoutParser($this->pdo);

		if($this->currentLayoutBuffer = "") {
			$this->layoutAlreadyParsed = false;
			$this->layoutReadyForParsing = false;
		} else {
			$this->layoutAlreadyParsed = false;
			$this->layoutReadyForParsing = true;
		}
	}

	function __destruct() {
		$this->layoutParser = NULL;
	}

	function setLayout($layoutBuffer) {
		$this->currentLayoutBuffer = $layoutBuffer;
		$this->layoutReadyForParsing = true;
	}

	function getLayoutAlreadyParsed() {
		return true;
	}

	function parseLayout() {
		if(!$this->layoutAlreadyParsed && $this->layoutReadyForParsing) {
			$this->layoutParser->parseLayout($this->currentLayoutBuffer);
		}
	}
}

?>
