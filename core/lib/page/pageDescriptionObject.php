<?php
/* 29o3 content management system
 * (c) 2003-2004 by Ulrik Guenther <kpanic@mg2.org>
 * This software is licensed under the terms of the BSD license.
 *
 * Layout parsing class
 */

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');

require_once($CONFIG['LibDir'] . 'xhtml/xhtmlheader.php');
require_once($CONFIG['LibDir'] . 'xhtml/xhtmlbody.php');

class pageDescriptionObject {

	private $id;
	private $name;
	private $cdate;
	private $mdate;
	private $owner;
	private $ogroup;
	private $rights;
	private $hidden;
	private $publishdate;
	private $title;
	private $description;
	private $contributors;
	private $publisher;
	private $language;
	private $coverage;
	private $stylesheet;
	private $layout;

	private $boxes = array();

	private $headerObject;
	private $bodyObject;

	// this array contains the database column names
	// which are the same as our private vars above
	// we use this array to speed up the process
	// of assignment
	private $nameDescriptor = array(
		"id",
		"name",
		"cdate",
		"mdate",
		"owner",
		"ogroup",
		"rights",
		"hidden",
		"publishdate",
		"title",
		"description",
		"contributors",
		"publisher",
		"language",
		"coverage",
		"stylesheet",
		"layout"
	);

	private $scheduledStylesheets = array();
	private $scheduledStylesheet_Count = 0;

	private $scheduledExternalStylesheets = array();
	private $scheduledExternalStylesheet_Count = 0;

	private $scheduledScriptTypes = array();
	private $scheduledScriptContent = array();
	private $scheduledScript_Count = 0;

	private $scheduledExternalScriptTypes = array();
	private $scheduledExternalScriptContent = array();
	private $scheduledExternalScript_Count = 0;

	public $databaseConnector;

	function __construct(XHTMLHeader &$xhtmlHeaderObject, XHTMLBody &$xhtmlBodyObject, DatabaseConnector &$connector) {

		// initialize references to XHTML* Objects
		$this->headerObject =& $xhtmlHeaderObject;
		$this->bodyObject =& $xhtmlBodyObject;
		$this->databaseConnector = $connector;

	}

	function setPageDescriptionA(&$infoArray) {

		// since we know the names of the database columns, we can use them as indexes
		// see the array definition of nameDescriptor above
		for($i = 0; $i != count($this->nameDescriptor); $i++) {
			$str = $this->nameDescriptor[$i];
			$this->{$str} = $infoArray[$str];
		}
	}

	function getNameDescriptor() {

	}

	function getContent($name) {
		if(array_search($name, $this->nameDescriptor) !== false) {
			return $this->{$name};
		}
	}

	function scheduleInsertion_Stylesheet($content) {
		array_push($this->scheduledStylesheets, $content);
		$this->scheduledStylesheet_Count++;
	}

	function scheduleInsertion_ExternalStylesheet($filename) {
		array_push($this->scheduledExternalStylesheets, $filename);
		$this->scheduledExternalStylesheet_Count++;
	}

	function scheduleInsertion_Script($script_type, $content) {
		array_push($this->scheduledScriptsTypes, $script_type);
		array_push($this->scheduledScriptsContent, $content);
		$this->scheduledScript_Count++;
	}

	function scheduleInsertion_ExternalScript($script_type, $file) {
		array_push($this->scheduledExternalScriptTypes, $script_type);
		array_push($this->scheduledExternalScriptContent, $file);
		$this->scheduledExternalScript_Count++;
	}

	function doInsertions() {

		for($i = 0; $i != $this->scheduledStylesheet_Count; $i++) {
			$this->headerObject->addStylesheet($this->scheduledStylesheets[$i]);
		}

		for($i = 0; $i != $this->scheduledExternalStylesheet_Count; $i++) {
			$this->headerObject->addStylesheetExternal($this->scheduledExternalStylesheets[$i]);
		}

		for($i = 0; $i != $this->scheduledScript_Count; $i++) {
			$this->headerObject->addScript($this->scheduledScriptContent[$i], $this->scheduledScriptTypes[$i]);
		}
		
		for($i = 0; $i != $this->scheduledExternalScript_Count; $i++) {
			$this->headerObject->addScriptExternal($this->scheduledExternalScriptContent[$i], $this->scheduledExternalScriptTypes[$i]);
		}

	}

	function printHeaderBuffer() {
		$this->headerObject->printBuffer();
	}

	function destroyHeaderObject() {
		$this->headerObject = NULL;
	}
}

?>