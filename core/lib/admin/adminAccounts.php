<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  This is the admin overview page.
  This type of file also shows how plugins will be handled.
*/

importPackage("db", $CONFIG['DatabaseType']);
importPackage("user", "user");
importPackage("ui", "uiMgmtBigMenu");
importPackage("ui", "uiMgmtBigMenuItem");
importPackage("ui", "uiFrame");
importPackage("ui", "uiText");
importPackage("ui", "uiOptionGroup");
importPackage("ui", "uiMgmtUserGroup");

class AdminAccounts {
	
	private $db;
	private $pdo;
	
	function __construct($connector, $pdo) {
	
		$this->db = $connector;
		$this->pdo = $pdo;
		DEBUG("AdminAccounts: Constructed.");
	
	}

	function doPreBodyJobs() {
//		$this->pdo->scheduleInsertion_ExternalScript("JavaScript", "content/scripts/experimentmenu.js");
	}

	function doBodyJobs() {
		global $SYSTEM_INFO;
	
		$frame_01 = new uiFrame("accountsFrame", "", "mgmtFrame");
		$text_header = new uiText("text01", "<span class=\"cHeading\">:: accounts</span>");

		$text_welcome = new uiText("textAccounts", 
"With <strong>Accounts</strong> you can setup multiple users and groups and manage their particular permissions. If you want to change the permissions or details of a certain user or group, click the respective &quot;Modify&quot; link.<br/><br/>
");
		$text_usermenu = new uiText("", '<a href="' . mksyslink("?mgmt;CreateUser;") . '">Create new user</a>');
		
		/* users option group */
		$og_users = new uiOptionGroup("og_users", "Users", "background-color: rgb(166, 220, 113);");
		$text_users = new uiText("", "Modify or create users.");
		$og_users->attach($text_users);

		$this->db->executeQuery("SELECT * FROM " . mktablename("users") . " ORDER BY uid ASC");
		$i = 0;
		while($user = $this->db->fetchArray()) {
			$ugo = new uiMgmtUserGroup($i, "", "user", "uiMgmtUser", implode(":::", $user));
			$og_users->attach($ugo);
			$i++;
		}
		$text_usermenu = new uiText("", '<small><a href="' . mksyslink("?mgmt;CreateUser;") . '">Create new user</a></small>');
		$og_users->attach($text_usermenu);	

		/* group option group */
		$og_groups = new uiOptionGroup("og_groups", "Groups", "background-color: rgb(229, 136, 46);");

		$text_groups = new uiText("", "Create new groups or modify existing ones.");
		$og_groups->attach($text_groups);
		$this->db->executeQuery("SELECT * FROM " . mktablename("groups") . " ORDER BY gid ASC");

		$i = 0;
		while($group = $this->db->fetchArray()) {
			$ugo = new uiMgmtUserGroup($i, "", "group", "uiMgmtGroup", implode(":::", $group));
			$og_groups->attach($ugo);
			$i++;
		}

		$text_groupmenu = new uiText("", '<small><a href="' . mksyslink("?mgmt;CreateGroup;") . '">Create new group</a></small>');
		$og_groups->attach($text_groupmenu);

		$frame_01->attach($text_header);
		$frame_01->attach($text_welcome);
		$frame_01->attach($og_users);
		$frame_01->attach($og_groups);

		$this->pdo->insertIntoBodyBuffer($frame_01->__toString());
	}
}

?>
