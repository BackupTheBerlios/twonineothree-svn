<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  uiUserGroup.php
  For displaying users and groups in the Accounts category
*/

require_once($CONFIG['LibDir'] . 'ui/uiElement.php');

class uiMgmtUserGroup extends uiElement {

	protected $name;
	protected $content;
	protected $styleClass;
	protected $type = UI_CONTAINER;
	protected $subject = "user";

	protected $className = "uiMgmtUserGroup";
	protected $htmlContent = "";

	protected $username;
	protected $groupname;
	
	protected $realname;
	protected $additionals;
	protected $uid;
	protected $gid;
	protected $email;
	protected $password;
	protected $state;
	protected $nextpwchange;

	function __construct($name, $content, $subject, $style = "uiMgmtUserGroup", $info) {
		$this->name = $name;
		$this->content = $content;
		$this->styleClass = $style;
		$this->type = UI_CONTAINER;
		$this->subject = $subject;
		// check if we should parse user or group
		$array = split(":::", $info);
		if($subject == "user") {
			$this->uid = $array[0];
			$this->username = $array[2];
			$this->email = $array[4];
			$this->additionals = $array[6];
			$this->gid = $array[8];
			$this->realname = $array[12];
			$this->state = $array[14];
			$this->nextpwchange = $array[16];
		} else {
			$this->groupname = $array[2];
			$this->gid = $array[0];
			$this->additionals = $array[4];
		}
	}
	
	function __destruct() {

	}

	function attach(uiElement $element) {
		// end-level element, nothing to attach to.
		return false;
	}

	function __toString() {
		$this->htmlContent .= '<div class="' . $this->styleClass . '" id="' . $this->name . '">';
		if($this->subject == "user") {
			$this->htmlContent .= "<strong>" . $this->realname . "</strong> (" . $this->username .  ")<br/>" .  $this->email;
			if($this->gid == 100) {
				$this->htmlContent .= ", Administrator";
			}
			$this->htmlContent .= "<br/>\n";
			$this->htmlContent .= '<small><a href="' . mksyslink("?mgmt;ModifyUser;uid=" . $this->uid) . '">Modify ' . $this->username . '\'s details and permissions</a> | <a href="' . mksyslink("?mgmt;DeleteUser;uid=" . $this->uid) . '">Delete user</a></small>';
		} else {
			$this->htmlContent .= "<strong>" . $this->groupname . "</strong><br/>" .
				$this->additionals . "<br/>\n";
			
			$this->htmlContent .= '<small><a href="' . mksyslink("?mgmt;ModifyGroup;gid=" . $this->gid) . '">Modify group ' . $this->groupname . '</a>';

			if($this->gid != 100) {
				$this->htmlContent .= ' | <a href="' . mksyslink("?mgmt;DeleteGroup;gid=" . $this->gid) . '">Delete group ' . $this->groupname . '</a>';
			}
			$this->htmlContent .= "</small>";
		}
		$this->htmlContent .= '</div>' . "\n";
		return $this->htmlContent;
	}

	function getClassName() {
		return $this->className;
	}
}
?>
