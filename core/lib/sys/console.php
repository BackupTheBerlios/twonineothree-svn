<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  The SystemConsole class is for the creation of some debug output.
  When debugging is disabled, this class will never be instanciated.
 
*/


class SystemConsole {

	private $buffer;
	private $file;
	private $mappedToFile;
	
	/*
		function:	__construct()
			*CONSTRUCTOR*
		purpose:	Class for doing some debugging output, p.e. page construction time, XML well-formedness errors etc.
		---
		input:	(none)
		output:	(constructor)
	*/
	function __construct() {
		$this->buffer = "";
	}	
	
	/*
		function:	__destruct()
			*DESTRUCTOR*
		purpose:	...
		---
		input:	(none)
		output:	(constructor)
	*/
	function __destruct() {
		// flush buffers
		$this->buffer = "";
	}
	
	/*
		function:	flushBuffer()
		purpose:	flushes the console buffer
		---
		input:	(none)
		output:	(constructor)
	*/
	function flushBuffer() {
		$this->buffer = "";
	}
	
	/*
		function:	print()
		purpose:	Print something to the console
		---
		input:	$text - what to print
		output:	(constructor)
	*/
	function write($text) {
		$this->buffer .= $text . "<br/>\n";
	}
	
	function printBuffer() {
		printf("%s", $this->buffer);
		$this->flushBuffer();
	}

	function getBuffer() {
		return $this->buffer;
	}
}

?>
