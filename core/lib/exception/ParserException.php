<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  This file holds the GeneralException-derived class for parser exceptions
  in 29o3.
*/

require_once($CONFIG['LibDir'] . 'exception/GeneralException.php');

class DatabaseException extends GeneralException {

	public function __construct($message, $code = 0, $severity = 0) {
		$message = "29o3 Parser Exception (" . $message . ")";
		parent::__construct($message, $code, $severity);
	}

	public function __toString() {
		return $this->message . " occured in " . __CLASS__ . " on " .  __LINE__ . ".";
	}
}

?>
