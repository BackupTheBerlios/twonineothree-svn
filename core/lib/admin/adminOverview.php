<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  This is the admin overview page.
  This type of file also shows how plugins will be handled.
*/

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'user/user.php');

class AdminOverview {
	
	private $db;
	private $pdo;
	
	function __construct($connector, $pdo) {
	
		$this->db = $connector;
		$this->pdo = $pdo;
		DEBUG("AdminOverview: Constructed.");
	
	}

	function doPreBodyJobs() {

	}

	function doBodyJobs() {
		$this->pdo->insertBodyDiv("&nbsp;");
		$this->pdo->insertIntoBodyBuffer('<div class="adminFuncPage">');
		$this->pdo->insertBodyDiv("System Overview", "adminFuncTitle");

		$this->pdo->insertBodyDiv(
			"<strong>Server software:</strong> " . $_SERVER["SERVER_SOFTWARE"] . "<br/>\n" .
			"<strong>Server time:</strong> " . strftime("%Y-%m-%d, %T", time()) . "<br/>\n" .
			"<strong>Server uptime/load:</strong> " . `uptime` . "<br/>\n"
			, "adminPreformatted");

		$this->pdo->insertBodyDiv("Running Sites", "adminFuncTitle");

		$this->db->executeQuery("SELECT * FROM " . mktablename("sites") . " ORDER BY name ASC");
		$this->pdo->insertIntoBodyBuffer('<table>');
		$this->pdo->insertIntoBodyBuffer("<tr id=\"header\"><td>Name</td><td>Desc.</td><td>Owner</td><td>Status</td></tr>");
		$subdb = clone $this->db;
		while(($arr = $this->db->fetchArray())) {
			if($arr["active"] != "t") {
				$this->pdo->insertIntoBodyBuffer("<tr id=\"lightattention\">\n\t");
			} else {
				$this->pdo->insertIntoBodyBuffer("<tr>\n\t");
			}

			$usr = new User($subdb, "", $arr["owner"]);
		
			$tmp = split(";", $arr["members"]);
		
			$this->pdo->insertIntoBodyBuffer("<td><a href=\"?2mc;EditSite;" . $arr["name"] . "\">" . $arr["name"] . "</a> [<a href=\"?" . $arr["name"] . "/" .  $tmp[0] . "\" title=\"Visit site '" . $arr["name"] .  "'\">&raquo;</a>]</td>\n\t<td>" . $arr["title"] . 
				"</td>\n\t<td>" . $usr->getNickName() . "</td><td");
			if($arr["active"] == "t") {
				$this->pdo->insertIntoBodyBuffer(" id=\"okay\">active</td>\n</tr>\n");
			} else {
				$this->pdo->insertIntoBodyBuffer(" id=\"attention\">*INACTIVE*</td>\n</tr>\n");
			}
		}
		$this->pdo->insertIntoBodyBuffer("</table>\n");

		$this->pdo->insertIntoBodyBuffer('</div>');
	}
}

?>
