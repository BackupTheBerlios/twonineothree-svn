<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  uiMgmtBigMenu.php
  This widget is the one with the big icons in the
  management console.
  
*/

require_once($CONFIG['LibDir'] . 'ui/uiElement.php');

class uiMgmtBigMenu extends uiElement {

	private $styleClass;
	private $type = UI_MENU;

	private $className = "uiMgmtBigMenu";

	function construct($name, $content, $style = "mgmtBigMenu") {
		$this->name = $name;
		$this->content = $content;
		$this->styleClass = $style;
	}

	function attach(uiElement &$element) {
		// only uiMgmtBigMenuItems can be attached to uiMgmtBigMenus
		if($element->getClassName() == "uiMgmtBigMenuItem") {
			parent::attach($element);
		}
	}

	function getHtmlContent() {
		
		$this->htmlContent .= '<div class="' . $this->styleClass . '" id="' . $this->name . '">' . $this->content . "\n";
		foreach($this->childElements as $key => $childElement) {
			$this->htmlContent .= $childElement->getHtmlContent() . "\n";
		}
		$this->htmlContent .= '</div>' . "\n";
		return $this->htmlContent;
	}

}

?>
