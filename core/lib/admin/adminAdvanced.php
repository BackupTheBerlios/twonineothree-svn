<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  This is the category "Advanced" of the management console.
  It is responsible for more advanced topics like database backup 
  and recovery etc.
*/

importPackage("db", $CONFIG['DatabaseType']);
importPackage("user", "user");
importPackage("ui", "uiMgmtBigMenu");
importPackage("ui", "uiMgmtBigMenuItem");
importPackage("ui", "uiFrame");
importPackage("ui", "uiText");
importPackage("ui", "uiOptionGroup");
importPackage("ui", "uiForm");
importPackage("ui", "uiFormElement");

class AdminAdvanced {
	
	private $db;
	private $pdo;
	
	function __construct($connector, $pdo) {
	
		$this->db = $connector;
		$this->pdo = $pdo;
		DEBUG("AdminAdvanced: Constructed.");
	
	}

	function doPreBodyJobs() {
//		$this->pdo->scheduleInsertion_ExternalScript("JavaScript", "content/scripts/experimentmenu.js");
	}

	function doBodyJobs() {
		global $SYSTEM_INFO;
	
		$frame_01 = new uiFrame("advancedFrame", "", "mgmtFrame");
		$text_header = new uiText("text01", "<span class=\"cHeading\">:: advanced</span>");

		$text_welcome = new uiText("textWelcome", 
'Inside of this category, you can for example backup and restore your database and do other advanced tasks of maintenance. Before using the Update feature of 29o3, please see the <a href="' . mksyslink("?mgmt;Help;chapter=Update29o3") . '">respective chapter of the documentation</a>.
<br/><br/>
<strong>Currently under development. Stay tuned.</strong>
');
		$og_backup = new uiOptionGroup("og_backup", "Database Backup", "background-color: rgb(166, 220, 113);");
		$text_backup = new uiText("", "
29o3 offers several options to back up the database.<br/>
Have in mind that in either way, the output is not encrypted.
Now, please choose the respective option below.");
		$form_backup = new uiForm("dbbackupform", "POST", mksyslink("?mgmt;DatabaseBackup;"));
		$rad_method = array();

		array_push($rad_method, new uiFormElement("method", "radio", "Upload to FTP server", "ftp"));
		array_push($rad_method, new uiFormElement("method", "radio", "Send via eMail", "mail"));
		array_push($rad_method, new uiFormElement("method", "radio", "Save to local file", "localfile"));
		array_push($rad_method, new uiFormElement("method", "radio", "Download", "downloadfile"));
		$og_backup->attach($text_backup);
		$og_backup->attach($form_backup);
		$form_backup->attach($rad_method);


		$frame_01->attach($text_header);
		$frame_01->attach($text_welcome);
		$frame_01->attach($og_backup);

		$this->pdo->insertIntoBodyBuffer($frame_01->__toString());
	}
}

?>
