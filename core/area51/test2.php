<?php

//require_once("../lib/common.php");
require_once("mysql.drv.php");
require_once("../config.php");

class XSLDependencyTracker {

	// database object
	var $db;

	/*
		function:	__construct()
			*CONSTRUCTOR*
		purpose:	Class for querying and organizing XSL template dependencies
		---
		input:	$db - database object which has to be constructed before
			(conforming to the 29o3 Database Layer API)
		output:	(constructor)
	*/
	function __construct($db) {
		
		// set db object to given db object
		$this->db = $db;
		
	}
	// END __construct
	
	/*
		function:	 getStylesheetDependencies()
		purpose:	query on which other XSL templates this stylesheet depends
		---
		input:	$name - name of the stylesheet to query
		output:	ARRAY with the names of the dependencies
	*/
	function getStylesheetDependencies($name) {
		
		global $CONFIG;
		
		$this->db->queryDatabase("SELECT dependencies FROM " . $CONFIG['DatabasePrefix'] . "stylesheets WHERE name = '" . $name . "'");
		$array = $this->db->fetchRow();
		if(count($array) > 1) {
			printf('<b>CRITICAL:</b> Stylesheet for element "%s" has been declared multiple times!<br>', $name);
		}
		
		$array = explode(",", $array[0]);
		
		$this->checkRecursivity($name, $array);
		
		return $array;
		
	}
	// END getStylesheetDependencies
	
	
	/*
		function:	checkRecursivity()
		purpose:	checks if a stylesheet has recursive dependencies,
			e.g. if "test" depends on "test" which means itself
		---
		input:	$name - name of the stylesheet
			$array - array containing the dependencies
		output:	(none)
	*/
	function checkRecursivity($name, $array) {
		
		$isRecursive = 0;
		
		for($i = 0; $i <= count($array); $i++) {
			if($name == $array[$i]) {
				$isRecursive = 1;
			}
		}
		
		if($isRecursive != 0) {
			printf('<b>CRITICAL:</b> Stylesheet for element "%s" has recursive dependencies!<br>', $name);
		}
	}
	// END checkRecursivity
	
}

$db = new driver();
$db->setupConnection($CONFIG['DatabaseHost'], $CONFIG['DatabaseUser'], $CONFIG['DatabasePassword'], $CONFIG['DatabaseName'], $CONFIG['DatabasePort']);

$XSLDepTracker = new XSLDependencyTracker($db);

$deps = $XSLDepTracker->getStylesheetDependencies("test");
printf("<pre>");
printf("Stylesheet: %s\n", "test");
print_r($deps);
printf("</pre");

?>