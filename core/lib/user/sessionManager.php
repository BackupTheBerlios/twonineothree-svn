<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  The Session Manager is (currently) responsible for the
  management of sessions of the management console.
*/

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'common.php');

class sessionManager {

	private $cur_sessId;
	private $cur_uid;
	private $cur_pwHash;
	private $cur_loginTime;
	private $db;

	function __construct(DatabaseConnector $db) {
		$this->db =& $db;
	}

	function initiateSession($username, $password) {

		// at first, check for expired sessions
		$this->db->executeQuery("SELECT * FROM " . mktablename("sessions") . "");
		while(($arr = $this->db->fetchArray())) {
			
			if($arr["logintime"] <= time() - 3600 /* one hour grace time */) {
				
				// invalidate old sessions
				$this->db->executeQuery("DELETE FROM " . mktablename("sessions") . " WHERE id='" . $arr["id"] . "'");
				
			}
			
		}

		$cookieInvalid = 0;
		// check if we have a session cookie set
		if(isset($_COOKIE["29o3_session_cookie"])) {

			// we have a session cookie *woot*
			$sCookieArr = split(";", $_COOKIE["29o3_session_cookie"]);
			/* cookie has the following format:
			
			   29o3 [VERSION] scookie;[username];[hashed password];[logintime]

			   The values given by the cookie are checked against the values in the 
			   database.
			*/

			if($sCookieArr[0] != "29o3" . $SYSTEM_INFO["SystemVersion"]) {
				$cookieInvalid++;
				DEBUG("SESSM: Version signature in session cookie does not match installed version of 29o3.");
			}

			// TODO: Add validation for user input
			$this->db->executeQuery("SELECT * FROM " . mktablename("sessions") . " WHERE name='" . $username . "' AND logintime='" .  . "'");
			$dbSessionArray = $this->db->fetchArray();

			$this->db->executeQuery("SELECT * FROM " . mktablename("users") . " WHERE username='" . $username .  "'");

			$dbUserArray = $this->db->fetchArray();

			if($dbUserArray["password"] != $password) {
				DEBUG("SESSM: Password from cookie does not match password from database.");
				$cookieInvalid++;
			}

			// logintime in combination with user name not found in db
			if($dbSessionArray !== false) {
				DEBUG("SESSM: logintime in combination with user name not found in database.");
				$cookieInvalid++;
			}

			if($cookieInvalid > 0) {
				// invalidate cookie
				setcookie("29o3_session_cookie", "", time() - 86400);
				return 16;
			}

			// valid cookie, valid session found :-D

			$this->cur_sessId = $dbSessionArray["id"];
			$this->cur_userName = $dbSessionArray["uid"];
			$this->cur_pwHash = $password;
			$this->cur_loginTime = $dbSessionArray["logintime"];

		}

					

	}

	function __destruct() {

	}

}

?>
