<?php
/* 29o3 content management system
 * (c) 2003-2004 by Ulrik Guenther <kpanic@mg2.org>
 * This software is licensed under the terms of the BSD license.
 *
 * Class with functions for the layout header
 */ 

require_once($CONFIG['LibDir'] . 'common.php');
require_once($CONFIG['LibDir'] . 'page/pageDescriptionObject.php');
require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');

/*
	IMPORTANT ABOUT HEADER FUNCTIONS
	---

	A header function will *always* return zero.
	A non-zero value indicated an error, so never use
	these functions to integrate data into some page!
*/

class LayoutHeaderFuncs {

	private $pdo;	// page description object

	// list of allowed functions. case-sensitive.
	// not that I'm paranoid. the problem is just
	// that each and every single man is after me...
	// hunting me... WAAAAAAAAAH!
	private $allowedFunctions = array(
		"mainStylesheet",
		"auxiliaryStylesheet"
	);

	function __construct(pageDescriptionObject &$pdo) {

		$this->pdo =& $pdo;

	}

	function grabRightFunction($name, $parameterArray) {
		if(array_search($name, $this->allowedFunctions) !== false) {
			$retval = $this->{$name}($parameterArray);

			//return $retval;
		}
	}
	
	// parameter array:
	//   [0]  -  name of stylesheet
	private function mainStylesheet($params) {
		if($params[1] == "internal") {
			$this->pdo->databaseConnector->executeQuery("SELECT * FROM " . mktablename("stylesheets") . " WHERE name='" . $params[0] . "'");
			if($this->pdo->databaseConnector->getNumRows() != 0) {
				$stylesheetArray = $this->pdo->databaseConnector->fetchArray();
				$this->pdo->scheduleInsertion_Stylesheet($stylesheetArray['content']);
				return 0; // no error
			} else {
				return 16; // not found
				// TODO: Add exception, e.g. NotFoundException
			}
		}
	}

	private function auxiliaryStylesheet($params) {

	}

}

?>
