<?php

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
