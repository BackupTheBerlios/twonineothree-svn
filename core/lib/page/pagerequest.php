<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
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
	private $timestamp;
	private $userAgent;
	private $requestType;

	private $connector;


	function __construct(DatabaseConnector &$connector) {
		$this->connector =& $connector;
	}

	function parseRequest() {

		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];


		$this->timestamp = 0;
		
		$requestString = explode(";", getenv("QUERY_STRING"));
		if($requestString[0] == "2mc") {
			$this->wantAdmin = true;
			// removes the key containing "2mc" from the array
			// so that it can me used furthermore.
			$tmpString = @$requestString[2];
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
				$this->requestedPage = $tmpString[1];
				$this->requestedSite = $tmpString[0];
			}
			
			$this->connector->executeQuery("SELECT * FROM " . mktablename("sites") . " WHERE name='" . $this->requestedSite . "';");
			$siteArr = $this->connector->fetchArray();
			if($siteArr !== false) {
				$existingPages = explode(";", $siteArr['members']);
				// TODO: add code for check if page is accessible

				if(array_search($this->requestedPage, $existingPages) === false) {
					header("HTTP/1.1 404 Not Found");
					$this->requestedPage = "404NotFound";
				}
				
				$this->connector->executeQuery("SELECT * FROM " . mktablename("pages") . " WHERE name='" . $this->requestedPage . "'");
				$pageArr = $this->connector->fetchArray();
				$rm = new rightsManager($pageArr);
				if($rm->hasUserViewingRights() == false) {
					header("HTTP/1.1 401 Forbidden");
					$this->requestedPage = "401Forbidden";
				}
			}
		} else {
			$this->connector->executeQuery("SELECT name FROM " . mktablename("pages"));
			while($arr = $this->connector->fetchArray()) {
				if($arr["name"] == "home") {
					$homeFound = true;
				}
			}
			if($homeFound) {
				$this->requestedPage = "home";
				$this->requestedSite = "default";
			} else {
				$this->requestedPage = "InstallationSuccessful";
				$this->requestedSite = "default";
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

}

?>
