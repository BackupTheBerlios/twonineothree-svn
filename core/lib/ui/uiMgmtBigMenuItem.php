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

class uiBigMenuItem extends uiElement {

	private $name;
	private $content;
	private $link;
	private $styleClass;
	private $type = UI_MENUITEM;

	private $className = "uiBigMenuItem";

	private $htmlContent;

	function __construct($name, $content, $link, $style = "mgmtBigMenuItem") {
		$this->name = $name;
		$this->content = $content;
		$this->link = $link;
		$this->styleClass = $style;
		$this->type = $type;
	}

	function __destruct() {

	}

	function attach(uiElement $element) {
//		attach() will not work since we are a menu item
//		and a menu item cannot have child items.
//		array_push(&$element, $this->childElements);
	}

	function getHtmlContent() {
		
		$this->htmlContent .= '<span class="' . $this->styleClass . '">' . $this->content . '</span>';
		// we do not iterate through menu items here since a menuitem 
		// cannot have subitems.
		return $this->htmlContent;
	}

}

?>
