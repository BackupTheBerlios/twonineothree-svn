<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  The Profiler class contains functions for measurement of 29o3's
  performance.
*/

class Profiler {

	private $breakpoints = array();

	function __construct() {

	}

	function __destruct() {

	}

	function addBreakpoint() {
		$rusage = getrusage();

		array_push($this->breakpoints, $rusage);
	}

	// system time functions 

	function getBreakpointSysDifference($first, $second) {
		return($this->breakpoints[$second]["ru_stime.tv_usec"] - $this->breakpoints[$first]["ru_stime.tv_usec"]);
	}

	function getBreakpointGrandSysDifference() {
		return($this->breakpoints[count($this->breakpoints)-1]["ru_stime.tv_usec"] - $this->breakpoints[count($this->breakpoints)-2]["ru_stime.tv_usec"]);
	}

	// user time functions

	function getBreakpointUserDifference($first, $second) {
		return($this->breakpoints[$second]["ru_utime.tv_usec"] - $this->breakpoints[$first]["ru_utime.tv_usec"]);
	}

	function getBreakpointGrandUserDifference() {
		return($this->breakpoints[count($this->breakpoints)-1]["ru_utime.tv_usec"] - $this->breakpoints[count($this->breakpoints)-2]["ru_utime.tv_usec"]);
	}


}
?>
