<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  This module enables the user to create new or modify existing
  designs for the use in pages respectively sites.
*/

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'user/user.php');

class AdminAppearance {
	
	private $db;
	private $pdo;
	
	function __construct($connector, $pdo) {
	
		$this->db = $connector;
		$this->pdo = $pdo;
		DEBUG("AdminAppearance: Constructed.");
	
	}

	function doPreBodyJobs() {

	}

	function doBodyJobs() {
		global $SYSTEM_INFO, $CONFIG;
	
		$this->pdo->scheduleInsertion_ExternalStylesheet("lib/admin/adminAppearance.css");
	
		if(strpos($this->pdo->getAdminFuncParam(), "EditLayout") === 0) {
			
			$CONFIG["Developer_Debug"] = false;
			$paramlist = split("_", $this->pdo->getAdminFuncParam());
			if(!isset($paramlist[1]) || $paramlist[1] == "") {
				$this->pdo->insertIntoBodyBuffer("<em><strong>Please select a layout from the list above or click &quot;Create&quot; to create a new one.</strong></em>");
			} else {

				ob_start();

				echo '<textarea style="width: 400px; height: 200px; border: 1px solid black;">';
				
				$file = file_get_contents($CONFIG["ContentDir"] . "layouts/" . $paramlist[1] . ".php");
				echo "<![CDATA[" . $file . "]]>";
				
				echo '</textarea>';

				$string = ob_get_contents();
				ob_end_clean();

				$this->pdo->insertIntoBodyBuffer($string);

			}
			return;
		}
	
		$this->pdo->insertIntoBodyBuffer(adminFuncs::getAdminDesignStart("appearance"));
		
		ob_start();

		echo "\n<br/>\n<div align=\"left\">";
		echo "<strong>Existing Layouts:</strong><br/>";

		echo '<div class="embedded">';
		// lets check which layouts we have
		$query = "SELECT " . mktablename("pages") . ".name, " . mktablename("pages") . 
			".layout, " . mktablename("layouts") . ".lname, " . mktablename("layouts") .
			".file FROM ". mktablename("layouts") .
			" LEFT JOIN " . mktablename("pages") . " ON " . mktablename("layouts") . ".lname=" .
			mktablename("pages") . ".layout";
		
		$this->db->executeQuery($query);
		echo "<div class=\"layout_table\">\n";
		echo "<div class=\"layout_tr\" style=\"font-size: 12px; font-weight: bold; background-image: url(lib/images/uiMgmtMenuBgHover.png);\">\n";
			echo '<div class="layout_td" style="background-image: url(lib/images/uiMgmtMenuBgHover.png);">Layout name</div><div class="layout_td" style="background-image: url(lib/images/uiMgmtMenuBgHover.png);">Used on page:</div><div class="layout_td" style="background-image: url(lib/images/uiMgmtMenuBgHover.png);">&nbsp;</div>'. "\n";
		echo "</div>";
		while($arr = $this->db->fetchArray()) {
			echo "<div class=\"layout_tr\" style=\"";
			if($arr["name"] == "") {
				echo "background-color: lightgreen; color: black;\">\n";
			} else {
				echo "background-color: white; color: black; background-image: url(lib/images/uiMgmtMenuBgHover.png);\">\n";
			}
			
			// layout name
			echo "<div class=\"layout_td\" id=\"td_lname\"><strong><a href=\"" . mksyslink("?mgmt;Appearance;EditLayout_" . $arr["file"]) . "\" target=\"editorframe\">" . $arr["lname"] .  "</a></strong></div>\n";
			// page name
			if($arr["name"] != "") {
				echo "<div class=\"layout_td\" id=\"td_pname\">" . $arr["name"] .  "</div>\n";		
			} else {
				echo "<div class=\"layout_td\" id=\"td_pname\"><em>none</em></div>\n";		
			}
			
			//echo "Layout " . $arr["lname"] . " used on page " . $arr["name"] . "<br/>\n";
			echo "<div class=\"layout_td\" style=\"width: 285px;\">&nbsp;</div>";

			// close row
			echo "</div>\n";
		}
		// close table
		echo "</div>\n";

		// close embedded
		echo "</div>\n";
		
		echo "<br/>";

		echo '<iframe src="' . mksyslink("?mgmt;Appearance;EditLayout_") . '" name="editorframe" class="editorframe"></iframe>';
		
		
		echo "</div>\n";

		$string = ob_get_contents();
		ob_end_clean();

		
		$this->pdo->insertIntoBodyBuffer($string . "\n" . adminFuncs::getAdminDesignEnd());


	}
}

?>
