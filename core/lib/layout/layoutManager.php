<?php
/* 29o3 content management system
 * (c) 2003-2004 by Ulrik Guenther <kpanic@mg2.org>
 * This software is licensed under the terms of the BSD license.
 *
 * Layout manager class
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
