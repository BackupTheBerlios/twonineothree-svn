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
	private $timestamp;
	private $userAgent;
	private $requestType;

	private $connector;


	function __construct(DatabaseConnector &$connector) {
		$this->connector =& $connector;
	}

	function parseRequest() {

		$this->requestedSite = "default";
		$this->requestedPage = "home";

		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];

		$this->requestType = $_SERVER['REQUEST_TYPE'];

		$this->timestamp = 0;
		
		$requestString = explode(";", getenv("QUERY_STRING"));
		$tmpString = $requestString[0];
		if($tmpString != "") {
			$tmpString = killScriptKiddies($tmpString);
			$tmpString = explode("/", $tmpString);
			
			if(count($tmpString) == 1) {
//				echo "one";
				$this->requestedSite = "default";
				$this->requestedPage = $tmpString[0];
			}
			if(count($tmpString) >= 2) {
//				echo "two";
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
			return;
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

}

?>
