<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  This is the admin overview page.
  This type of file also shows how plugins will be handled.
*/

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'user/user.php');

importPackage("ui", "uiMgmtBigMenu");
importPackage("ui", "uiMgmtBigMenuItem");
importPackage("ui", "uiFrame");
importPackage("ui", "uiText");

class AdminOverview {
	
	private $db;
	private $pdo;
	
	function __construct($connector, $pdo) {
	
		$this->db = $connector;
		$this->pdo = $pdo;
		DEBUG("AdminOverview: Constructed.");
	
	}

	function doPreBodyJobs() {
//		$this->pdo->scheduleInsertion_ExternalScript("JavaScript", "content/scripts/experimentmenu.js");
	}

	function doBodyJobs() {
		global $SYSTEM_INFO;
	
		$frame_01 = new uiFrame("overviewFrame", "", "mgmtFrame");
		$text_header = new uiText("text01", "<span class=\"cHeading\">:: management console overview</span>");

		$text_welcome = new uiText("textWelcome", 
"<strong>Welcome to the management console of the 29o3 content management system.</strong><br/><br/>
If this is your first visit, let me explain shortly how easy it is to publish content with 29o3:<br/><br/>
When you look to the left, you see the categories of the management console. If you click on <strong>Preferences</strong>, you are able to tune different system-wide settings of your system.<br/><br/>
The category <strong>Appearance</strong> lets you set up how your pages should look. Finally, through the category <strong>Structure</strong> you are able to give structure to your website and add content.<br/><br/>
If you want to give media files (videos, images, etc.) into the management of 29o3, choose <strong>Media</strong>. For adding other files, go to <strong>Files</strong>.<br/><br/>
For managing multiple users/groups editing your websites with different permissions, have a look at <strong>Accounts</strong><br/><br/>
More advanced topics, like database backup and recovery, can be found under <strong>Advanced</strong>.
<br/><br/>
To find out about the people behind 29o3, please <a href=\"" . mksyslink("?mgmt;Help;chapter=About") . "\">click here</a>.<br/><br/>
");

		$text_sysinfo = new uiText("sysInfo", "29o3 is currently running on ". php_uname("s") . "/" . php_uname("m") .  " " . php_uname("r") . " with " . $_SERVER["SERVER_SOFTWARE"] . " as webserver. You are running 29o3 ". $SYSTEM_INFO["SystemVersion"] . " Codename ". $SYSTEM_INFO["SystemCodename"] . ".");

		$frame_01->attach($text_header);
		$frame_01->attach($text_welcome);
		$frame_01->attach($text_sysinfo);

		$this->pdo->insertIntoBodyBuffer($frame_01->__toString());
	}
}

?>
