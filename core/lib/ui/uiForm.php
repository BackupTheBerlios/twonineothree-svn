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

importPackage("ui", "uiElement");

class uiForm extends uiElement {

	protected $name;
	protected $content;
	protected $styleClass;
	protected $method;
	protected $action;
	protected $type = UI_FORM;

	protected $className = "uiForm";
	protected $htmlContent = "";

	function __construct($name, $method, $action) {
		$this->name = $name;
		$this->type = UI_CONTAINER;
		$this->method = $method;
		$this->action = $action;
	}
	
	function __destruct() {

	}

	function attach($element) {
		// we can attach everything as child element
		parent::attach(&$element);
	}

	function __toString() {
		$this->htmlContent .= '<form name="' . $this->name . '" action="' . $this->action . '" method="' . $this->method . '">' . "\n";
		foreach($this->childElements as $key => $childElement) {
			$this->htmlContent .= $childElement->__toString() . "<br/>\n";
		}

		$this->htmlContent .= '</form>' . "\n";
		return $this->htmlContent;
	}

	function getClassName() {
		return $this->className;
	}
}
?>
