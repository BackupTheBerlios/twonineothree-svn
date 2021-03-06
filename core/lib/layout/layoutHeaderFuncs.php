<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  LayoutHeaderFuncs class: this class holds the functions which
  are callable in the header block of layout definitions for pages.

  **** ATTENTION ****
  AS OF 2004-12-19, THIS IS NOT USED ANYMORE. PLEASE REVIEW THE FILE
  "UPDATING" IN THE ROOT DIRECTORY OF THIS DISTRIBUTION OR CONTACT
  THE AUTHOR OF 29o3 DIRECTLY!


*/


require_once($CONFIG['LibDir'] . 'common.php');
require_once($CONFIG['LibDir'] . 'page/pageDescriptionObject.php');
require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'exception/GeneralException.php');
require_once($CONFIG['LibDir'] . 'exception/CodingException.php');

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

			// *NEVER* add a return $value here, since *all* return values (except 0)
			// are considered error messages when in header functions.
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
				throw new GeneralException("Stylesheet for this page was not found: " . $params[0]);
				return 16; // not found
			}
		}
		if($params[1] == "external") {
			$this->pdo->scheduleInsertion_ExternalStylesheet($params[0]);
		}
		else {
			throw new CodingException("Wrong parameter count, possible missing parameter 2.", __FUNCTION__, "header");
		}
	}

	private function auxiliaryStylesheet($params) {
		
		$this->pdo->databaseConnector->executeQuery("SELECT * FROM " . mktablename("stylesheets") . " WHERE name='" . $params[0] . "'");
		if($this->pdo->databaseConnector->getNumRows() != 0) {
			$stylesheetArray = $this->pdo->databaseConnector->fetchArray();
			$this->pdo->scheduleInsertion_Stylesheet($stylesheetArray['content']);
			return 0; // no error
		} else {
			throw new GeneralException("Stylesheet for this page was not found: " . $params[0]);
			return 16; // not found
		}

	}

}

?>
