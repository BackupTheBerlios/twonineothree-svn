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

	protected $style;
	protected $name;
	protected $content;
	protected $type = UI_MENU;
	protected $maxCellCount;

	protected $className = "uiMgmtBigMenu";

	function __construct($name, $content, $maxCellCount = 2, $style = "newmenu") {
		$this->name = $name;
		$this->content = $content;
		$this->style = $style;
		$this->maxCellCount = $maxCellCount;
	}

	function attach(uiElement &$element) {
		// only uiMgmtBigMenuItems can be attached to uiMgmtBigMenus
		if($element->getClassName() == "uiMgmtBigMenuItem") {
			parent::attach(&$element);
		}
	}

	function __toString() {
		
		$this->htmlContent .= '<div class="' . $this->style . '" id="' . $this->name . '">' . "\n";
		$counter = 0;
		$openrow = false;
		$opencell = false;
		foreach($this->childElements as $key => $childElement) {
			$this->htmlContent .= $childElement->__toString() . "<br/>\n";
		}

		$this->htmlContent .= '</div>' . "\n";
		return $this->htmlContent;
	}

}

?>
