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

class uiFormElement extends uiElement {

	protected $name;
	protected $content;
	protected $styleClass;
	protected $type = UI_CONTAINER;
	protected $elementType;
	protected $size;
	protected $style;

	protected $className = "uiFormElement";
	protected $htmlContent = "";

	function __construct($name, $type, $content, $size=1, $style="", $class = "uiFormElement") {
		$this->name = $name;
		$this->content = $content;
		$this->styleClass = $class;
		$this->style = $style;
		$this->type = UI_FORMELEMENT;
		$this->elementType = $type;
		$this->size = $size;
	}
	
	function __destruct() {

	}

	function attach(uiElement $element) {
		// can only attach, if we are a dropdown-box
		if($this->elementType == "dropdown") {
			parent::attach(&$element);
		} else {
			return 0;
		}
	}

	function __toString() {
/*		$this->htmlContent .= '<span class="' . $this->styleClass . '" id="' . $this->name . '">';
		foreach($this->childElements as $key => $childElement) {
			$this->htmlContent .= $childElement->__toString() . "<br/>\n";
		}

		$this->htmlContent .= '</span>' . "\n";
*/

		if($this->elementType == "dropdown") {
			$this->htmlContent .= '<select name="' . $this->name . '" size="' . $this->size . '" class="' . $this->styleClass . '"';
			if($this->style != "") {
				$this->htmlContent .= ' style="' . $this->style . '"';
			}
			$this->htmlContent .= ">\n";
			foreach($this->childElements as $childElement) {
				$this->htmlContent .= "\t" . $childElement->__toString() . "\n";
			}
			$this->htmlContent .= '</select>' . "\n";

			return $this->htmlContent();
		}
	
		if($this->elementType == "radio") {
			$this->htmlContent .= '<input type="radio" name="' . $this->name . '" value="' . $this->size . '" class="' . $this->styleClass . '"';
			if($this->style != "") {
				$this->htmlContent .= ' style="' . $this->style . '"';
			}
			$this->htmlContent .= "/>&nbsp;" . $this->content . "\n";

			return $this->htmlContent;
		}

		if($this->elementType == "checkbox") {
			$this->htmlContent .= '<input type="checkbox" name="' . $this->name . '" value="' . $this->size . '" class="' . $this->styleClass . '"';
			if($this->style != "") {
				$this->htmlContent .= ' style="' . $this->style . '"';
			}
			$this->htmlContent .= "/>&nbsp;" . $this->content . "\n";
		}


		return "Not a form element.";
	}

	function getClassName() {
		return $this->className;
	}
}
?>
