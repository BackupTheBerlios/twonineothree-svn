<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  This file holds the Exception-derived class for generic exceptions
  in 29o3.
*/

class GeneralException extends Exception {

	protected $severity = 0;
	
	public function __construct($message, $code = 0, $severity = 0) {
		$this->severity = $severity;
		parent::__construct($message, $code);
	}

	public function __toString() {
		return $this->message;
	}

	public function getSeverity() {
		return $this->severity;
	}
}

?>
