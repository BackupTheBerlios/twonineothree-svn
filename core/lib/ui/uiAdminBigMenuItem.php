<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  uiMgmtBigMenuItem.php
  the Admin
*/

define("UI_TEXT", 	0x00000001);
define("UI_IMAGE", 	0x00000002);
define("UI_PLACEHOLDER",0x00000004);
define("UI_CONTAINER",	0x00000008);

class uiElement {

	private $name;
	private $content;
	private $type = UI_TEXT;

	private $htmlContent;

	function __construct($name, $content, $type = UI_TEXT) {
		$this->name = $name;
		$this->content = $content;
		$this->type = $type;
	}

	function __destruct() {

	}

	function getHtmlContent() {
		
		$this->htmlContent .= $this->content;
		
		return $this->htmlContent;
	}

}

?>
