<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  PageRequest class, which processes GET/POST requests for
  further usage in 29o3.
 
*/

require_once($CONFIG['LibDir'] . 'common.php');
require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_Once($CONFIG['LibDir'] . 'user/rightsManager.php');

class PageRequest {

	private $requestedPage;
	private $requestedSite;
	private $wantAdmin;
	private $wantedAdminFunc;
	private $wantedAdminFuncParam;
	private $timestamp;
	private $userAgent;
	private $requestType;
	private $error = 0;

	private $connector;


	function __construct(DatabaseConnector &$connector) {
		$this->connector =& $connector;
	}

	function parseRequest() {
		
		global $CONFIG;

		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];


		$this->timestamp = 0;
		
		$requestString = explode(";", getenv("QUERY_STRING"));
		if($requestString[0] == "mgmt") {
			$this->wantAdmin = 1;
			// this is needed due to the stylesheets
			// and as we think the admin is "clean" to the users, the console p.e. is not needed
			// removes the key containing "2mc" from the array
			// so that it can be used furthermore.
			DEBUG("PR: Admin wanted.");
			if(isset($requestString[2])) {
				$tmpString = $requestString[2];
				$this->wantedAdminFuncParam = $requestString[2];
				$this->wantedAdminFunc = $requestString[1];
			} else {
				$tmpString = "";
			}
			$this->wantAdmin++;
			DEBUG("PR: Admin non-db page requested: " . $requestString[1]);
			return;
		} else {
			$tmpString = $requestString[0];
		}
		if($tmpString != "") {
			$tmpString = killScriptKiddies($tmpString);
			$tmpString = explode("/", $tmpString);
			
			if(count($tmpString) == 1) {
				$this->requestedSite = "default";
				$this->requestedPage = $tmpString[0];
			}
			if(count($tmpString) >= 2) {
				if(strlen($tmpString[1]) == 0) {
					$this->requestedPage = "home";
				} else {
					$this->requestedPage = $tmpString[1];
				}
				$this->requestedSite = $tmpString[0];
			}
			$this->connector->executeQuery("SELECT * FROM " . mktablename("sites") . " WHERE name='" . $this->requestedSite . "';");
			$siteArr = $this->connector->fetchArray();
			if($siteArr !== false) {
				$existingPages = explode(";", $siteArr['members']);

				if(array_search($this->requestedPage, $existingPages) === false) {
					header("HTTP/1.1 404 Not Found");
					$orig_page = $this->requestedPage;
					$this->requestedPage = "404NotFound";
					DEBUG("PR: Given page not found in database (" . $this->requestedSite . "/" . $orig_page . ")");
					$this->error = 404;
					return;
				}
				
				$this->connector->executeQuery("SELECT * FROM " . mktablename("pages") . " WHERE name='" . $this->requestedPage . "'");
				$pageArr = $this->connector->fetchArray();
				$rm = new rightsManager($pageArr);
				if($rm->hasUserViewingRights() == false) {
					header("HTTP/1.1 401 Forbidden");
					$this->requestedPage = "401Forbidden";
					DEBUG("PR: Current user has insufficient rights to view this page.");
					$this->error = 401;
					return;
				}
			} else {
				header("HTTP/1.1 404 Not Found");
				$or_site = $this->requestedSite;
				$or_page = $this->requestedPage;
				$this->requestedPage = "404NotFound";
				$this->requestedSite = "default";
				DEBUG("PR: Given site does not exist, falling back to " . $this->requestedSite . "/" . $this->requestedPage . ". Requested site/page was: " . $or_site . "/" . $or_page);
				$this->error = 404;
				return;
			}
		} else {
			$homeFound = false;
			$this->connector->executeQuery("SELECT name FROM " . mktablename("pages"));
			while($arr = $this->connector->fetchArray()) {
				if($arr["name"] == "home") {
					$homeFound = true;
				}
			}
			if($homeFound) {
				$this->requestedPage = "home";
				$this->requestedSite = "default";
				DEBUG("PR: Falling back to default target.");
			} else {
				$this->requestedPage = "InstallationSuccessful";
				$this->requestedSite = "default";
				DEBUG("*** fresh 29o3 installation, defaulting to default/InstallationSuccessful ***");
			}
		}
	}

	function getRequestUserAgent() {

	}

	function getRequestType () {

	}

	function getRequestTimestamp() {

	}

	function getRequestedPage() {
		return $this->requestedPage;
	}

	function getRequestedSite() {
		return $this->requestedSite;
	}

	function getWantAdmin() {
		return $this->wantAdmin;
	}

	function getWantedAdminFunc() {
		return $this->wantedAdminFunc;
	}

	function getAdminFuncParam() {
		return $this->wantedAdminFuncParam;
	}

	function getError() {
		return $this->error;
	}

}

?>
