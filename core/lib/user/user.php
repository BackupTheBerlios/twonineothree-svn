<?
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  The user class enables user and rights management in 29o3.

*/

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'common.php');

class User {

	private $userName;
	private $realName;
	private $userId;
	private $groupId;
	private $groupName;
	private $passwordHash;
	private $emailAddress;

	function __construct(DatabaseConnector &$db, $username = "", $uid = "") {
		if($username != ""){
			$db->executeQuery("SELECT * FROM " . mktablename("users") . " WHERE username=" . $username);
		} else {
			$db->executeQuery("SELECT * FROM " . mktablename("users") . " WHERE uid=" . $uid);
		}
		if($db->getNumRows() == 1) {
			$usersArray = $db->fetchArray();
			
			$db->executeQuery("SELECT * FROM " . mktablename("groups") . " WHERE gid=" . $usersArray['gid']);

			$groupArray = $db->fetchArray();

			$this->userName = $usersArray["username"];
			$this->userId = $usersArray["uid"];
			$this->groupId = $groupArray["gid"];
			$this->groupName = $groupArray["name"];
			$this->passwordHash = $usersArray["password"];
			$this->emailAddress = $usersArray["email"];
			$this->realName = $usersArray["realname"];

			return 0; // ok
		}
		return 1; // not found
	}

	function getNickName() {
		return $this->userName;
	}

	function getRealName() {
		return $this->realName;
	}

	function getUid() {
		return $this->userId;
	}

	function getGid() {
		return $this->groupId;
	}

	function getGName() {
		return $this->groupName;
	}

	function getHashedPassword() {
		return $this->passwordHash;
	}

	function getEmailAddress() {

	}

}

?>
