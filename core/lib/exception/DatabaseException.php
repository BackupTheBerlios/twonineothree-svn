<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  This file holds the GeneralException-derived class for database exceptions
  in 29o3.
*/

class DatabaseException extends Exception {

	protected $severity;

	public function __construct($message, $code = 0, $severity = 0) {
		$message = "29o3 Database Exception (" . $message . ")";
		$this->severity = $severity;
		parent::__construct($message, $code);
	}

	public function __toString() {
		return $this->message . " occured in " . __CLASS__ . " on " .  __LINE__ . ".";
	}

	public function getSeverity() { 
		return $this->severity;
	}
}

?>
