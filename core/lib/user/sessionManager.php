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

// maximum session length in seconds, currently hard-coded, will be added to database soon
// TODO: Add to database
$MAX_SESSION_LENGTH = 60*60;

class sessionManager {

	private $cur_sessId;
	private $cur_uid;
	private $cur_uniqueID;
	private $cur_loginTime;
	private $db;

	function __construct(DatabaseConnector &$db) {
		DEBUG("SESSM: Created session manager.");
		$this->db =& $db;
	}

	function checkSession() {

		global $SYSTEM_INFO, $MAX_SESSION_LENGTH;

		// at first, check for expired sessions
		$this->db->executeQuery("SELECT * FROM " . mktablename("sessions") . "");
		$n = 0;
		while(($arr = $this->db->fetchArray())) {
			
			if($arr["logintime"] <= time() - $MAX_SESSION_LENGTH /* one hour grace time */) {
				
				// invalidate old sessions
				$this->db->executeQuery("DELETE FROM " . mktablename("sessions") . " WHERE id='" . $arr["id"] . "'");
				$n++;
				
			}
			
		}

		DEBUG("SESSM: Deleted $n old sessions.");

		$cookieInvalid = 0;
		// check if we have a session cookie set
		if(isset($_COOKIE["29o3_session_cookie"])) {

			// we have a session cookie *woot*
			$sCookieArr = explode(";", $_COOKIE["29o3_session_cookie"]);
			/* cookie has the following format:
			
			   29o3 [VERSION];[username];[unique identification];[logintime]

			   The values given by the cookie are checked against the values in the 
			   database.
			*/

			if($sCookieArr[0] != "29o3 " . $SYSTEM_INFO["SystemVersion"]) {
				$cookieInvalid++;
				DEBUG("SESSM: Version signature in session cookie does not match installed version of 29o3: ". $sCookieArr[0]);
			}

			$username = $sCookieArr[1];
			$uniqueid = $sCookieArr[2];

			$this->db->executeQuery("SELECT * FROM " . mktablename("users") . " WHERE username='" . $username .  "'");

			$dbUserArray = $this->db->fetchArray();

			$this->db->executeQuery("SELECT DISTINCT * FROM " . mktablename("sessions") . " WHERE uid='" . $dbUserArray["uid"] . "' AND logintime='" . $sCookieArr[3] . "' AND id='" . $sCookieArr[2] . "'");
			$dbSessionArray = $this->db->fetchArray();

			if($dbSessionArray['logintime'] != $sCookieArr[3]) {
				DEBUG("SESSM: Login time from cookie does not match login time from database.");
				$cookieInvalid++;
			}

			if($dbSessionArray['ip'] != $_SERVER['REMOTE_ADDR']) {
				DEBUG("SESSM: IP of authenticating client does not match IP from database.");
				$cookieInvalid++;
			}


			/*if($dbUserArray["password"] != $password) {
				DEBUG("SESSM: Password from cookie does not match password from database.");
				$cookieInvalid++;
			}*/

			// logintime in combination with user name not found in db
			/*if($dbSessionArray !== false) {
				DEBUG("SESSM: logintime in combination with user name not found in database.");
				$cookieInvalid++;
			}*/

			if($cookieInvalid > 0) {
				// invalidate cookie
				setcookie("29o3_session_cookie", "", time() - 86400);
				return false;
			}

			// valid cookie, valid session found :-D

			$this->cur_sessId = $dbSessionArray["id"];
			$this->cur_userName = $dbSessionArray["uid"];
			$this->cur_uniqueID = $uniqueid;
			$this->cur_loginTime = $dbSessionArray["logintime"];

			return true;

		}

		return false;

					

	}

	function createSession($username, $password) {
		
		global $SYSTEM_INFO, $CONFIG, $MAX_SESSION_LENGTH;
		
		if($this->checkSession() == false) {
			
			// we have username and password, we need to check if they are correct
			// if they are correct, we create a new session :)

			if($CONFIG["PwHashAlgorithm"] == "MD5") {
				$password = md5($password);
			}
			
			$this->db->executeQuery("SELECT * FROM " . mktablename("users") ." WHERE username='" . $username . "' and password='" . $password . "'");
			$arr = $this->db->fetchArray();

			// double-check username and pw
			if($arr['password'] != $password || $arr['username'] != $username) {
				// authentication failed, no session created
				return false;
			}

			// create a session
			$loginTime = time();
			$uniqueid = md5(sha1(uniqid(rand(), true)));
			$this->db->executeQuery("INSERT INTO " . mktablename("sessions") . " VALUES ('" . $uniqueid . "', '" . $arr['uid'] . "', " . $loginTime . ", '" . $_SERVER['REMOTE_ADDR'] . "')");

			// create the session cookie
			/*$pwhash = "";
			if($CONFIG["PwHashAlgorithm"] == "MD5") {
				$pwhash = md5($password);
			} else {
				$pwhash = $password;
			}*/
			setcookie("29o3_session_cookie", "29o3 " . $SYSTEM_INFO['SystemVersion'] . ";" . $username . ";" . $uniqueid . ";" . $loginTime , $loginTime + $MAX_SESSION_LENGTH);

			// session created successfully :)
			return true;

		}
		
	}

	function __destruct() {

	}

}

?>
