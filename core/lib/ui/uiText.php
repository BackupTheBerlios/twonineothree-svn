<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  uiText.php
  For creating text labels...
*/

require_once($CONFIG['LibDir'] . 'ui/uiElement.php');

class uiText extends uiElement {

	protected $name;
	protected $content;
	protected $styleClass;
	protected $type = UI_TEXT;

	protected $className = "uiText";
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
		// end-level element, cannot attach elements to it.
		return 0;
	}

	function __toString() {
		$this->htmlContent .= $this->content;;
		return $this->htmlContent;
	}

	function getClassName() {
		return $this->className;
	}
}
?>
