<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  This file holds the Exception-derived class for coding-related exceptions
  in 29o3.
*/

class CodingException extends Exception {

	
	public function __construct($message, $function = "main", $type = "main") {
		$message = "There is a problem with your code: "  . $message . "\n\nThis exception is unrelated to 29o3's code, it's the fault of the author of this page. Please consult the 29o3 manual for the function <a href=\"http://twonineothree.berlios.de/?docs/$type-$function\">" .  $function . "</a>";
		parent::__construct($message, 65536);
	}

	public function __toString() {
		return $this->message . " occured in " . __CLASS__ . " on line " .  __LINE__ . ".";
	}

	public function getSeverity() {
		return 128;
	}
}

?>
