<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  Login page for people wanting access to management area of 29o3.
*/

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'user/user.php');
require_once($CONFIG['LibDir'] . 'user/sessionManager.php');

class AdminLogin {
	
	private $db;
	private $pdo;
	private $stage;
	private $sm;
	private $loginOK;
	
	function __construct($connector, $pdo, $sm) {
	
		$this->db =& $connector;
		$this->pdo =& $pdo;
		$this->sm =& $sm; 
		DEBUG("AdminLogin: Constructed.");
	
	}

	function doPreBodyJobs() {
		$this->pdo->setOmitBranding(true);
		// check if somebody tried to log in
		if(isset($_POST['loginstage']) && isset($_POST['username']) && isset($_POST['password'])) {
			$this->stage = 2;
		} else {
			$this->stage = 1;
		}

		if($this->stage == 2) {
		
			if($this->sm->createSession($_POST['username'], $_POST['password']) == true) {
				$this->loginOK = true;
			} else {
				$this->loginOK = false;
			}
		
		}
	}

	function doBodyJobs() {
		if($this->stage == 1) {
			$this->pdo->setOmitBranding(true);
			$this->pdo->insertIntoBodyBuffer('<span id="bg" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; text-align: center; vertical-align: middle;">' . "\n");
			$this->pdo->insertIntoBodyBuffer("<form method=\"post\" name=\"loginform\" id=\"loginform\" action=\"" . $_SERVER['PHP_SELF'] . "?" . $_SERVER["QUERY_STRING"] .  "\">\n");
//			$this->pdo->insertintobodybuffer('<div style="width: 100%; height: 100%; text-align: center; vertical-align: middle;">');
			$this->pdo->insertBodyDiv("&nbsp;", "mgmtLoginBox", "", "", "", false);
			$this->pdo->insertIntoBodyBuffer('<div class="mgmtLoginTitle">29o3 management console login</div>' . "\n");
			$this->pdo->insertIntoBodyBuffer('<span class="mgmtLoginPrompt">Please log in below with the credentials<br/>you have been provided with.<br/><br/><strong>Login name: <br/>
			<input type="text" name="username" class="mgmtLoginInputBox" /><br/><br/>
			Password:<br/>
			<input type="password" name="password" class="mgmtLoginInputBox" />
			<input type="hidden" name="loginstage" value="1" />
			</strong></span>' . "\n");
			$this->pdo->insertIntoBodyBuffer('<br/><br/><br/><br/><br/><br/><br/><br/>
			<button type="submit" class="mgmtButton">Login</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button type="reset" class="mgmtButton">Clear</button>' . "\n");
			$this->pdo->insertBodyCloseDiv();
			$this->pdo->insertIntoBodyBuffer("</form>\n");
			$this->pdo->insertIntoBodyBuffer("</span>\n");
		} else {

			// check if user & pw were okay
			if($this->loginOK == true) {
				$this->pdo->setOmitBranding(true);
				$this->pdo->insertIntoBodyBuffer('<div class="mgmtLoginState" style="background-color: green;">
				You have been successfully logged in.<br/>
				<a href="index.php?mgmt;Overview;"><strong>Please click here to continue.</strong></a>
				</div>');
			} else {
				$this->pdo->setOmitBranding(true);
				$this->pdo->insertIntoBodyBuffer('<div class="mgmtLoginState" style="background-color: red;">
				YOU PROVIDED THE WRONG ACCESS CREDENTIALS.<br/>
				Please go back and check them.<br/>
				This access attempt has been logged.
				</div>');
			}

		}
	}
}

?>
