<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  uiMgmtBigMenuItem.php
  This class is symbolized by the big menu items
  in the management console of 29o
*/

require_once($CONFIG['LibDir'] . 'ui/uiElement.php');

class uiMgmtBigMenuItem extends uiElement {

	protected $name;
	protected $content;
	protected $link;
	protected $styleClass;
	protected $title;
	protected $type = UI_MENUITEM;

	protected $className = "uiBigMenuItem";

	protected $htmlContent = "";

	function __construct($name, $title, $content, $link, $style = "mgmtBigMenuItem") {
		$this->name = $name;
		$this->title = $title;
		$this->content = $content;
		$this->link = $link;
		$this->styleClass = $style;
		$this->type = UI_MENUITEM;
	}

	function __destruct() {

	}

	function attach(uiElement $element) {
//		attach() will not work since we are a menu item
//		and a menu item cannot have child items.
//		array_push(&$element, $this->childElements);
	}

	function __toString() {
		
		$this->htmlContent .= '<div class="' . $this->styleClass . '"><a href="' . $this->link .'"><strong>' . $this->title . "</strong></a>\n" . $this->content . '</div>';
		// we do not iterate through menu items here since a menuitem 
		// cannot have subitems.
		return $this->htmlContent;
	}

	function getClassName() {
		return "uiMgmtBigMenuItem";
	}

}

?>
