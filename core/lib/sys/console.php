<?php
/* 29o3 content management system
 * (c) 2003-2004 by Ulrik Guenther <kpanic@mg2.org>
 * This software is licensed under the terms of the BSD license.
 *
 * Class for doing some debugging output
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
