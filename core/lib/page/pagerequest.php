<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  PageRequest class, which processes GET/POST requests for
  further usage in 29o3.
 
*/


class PageRequest {

	private $requestedPage;
	private $timestamp;
	private $userAgent;
	private $requestType;

	function parseRequest() {

		$this->requestedPage = "home";

		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];

		$this->requestType = $_SERVER['REQUEST_TYPE'];

		$this->timestamp = 0; 
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
