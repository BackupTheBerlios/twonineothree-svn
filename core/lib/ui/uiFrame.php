<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  uiFrame.php
  This is for creating frames which contain other UI-related
  things.
*/

require_once($CONFIG['LibDir'] . 'ui/uiElement.php');

class uiFrame extends uiElement {

	protected $name;
	protected $content;
	protected $styleClass;
	protected $type = UI_CONTAINER;

	protected $className = "uiFrame";
	protected $htmlContent = "";

	function __construct($name, $content, $style = "uiFrame") {
		$this->name = $name;
		$this->content = $content;
		$this->styleClass = $style;
		$this->type = UI_CONTAINER;
	}
	
	function __destruct() {

	}

	function attach(uiElement $element) {
		// we can attach everything as child element
		parent::attach(&$element);
	}

	function __toString() {
		$this->htmlContent .= '<span class="' . $this->styleClass . '" id="' . $this->name . '">';
		foreach($this->childElements as $key => $childElement) {
			$this->htmlContent .= $childElement->__toString() . "<br/>\n";
		}

		$this->htmlContent .= '</span>' . "\n";
		return $this->htmlContent;
	}

	function getClassName() {
		return $this->className;
	}
}
?>
