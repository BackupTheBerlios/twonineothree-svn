<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  uiOptionGroup.php
  Options group are groups which are... blabla bla...
*/

importPackage("ui", "uiElement");

class uiOptionGroup extends uiElement {

	protected $name;
	protected $content;
	protected $styleClass;
	protected $type = UI_CONTAINER;

	protected $className = "uiOptionGroup";
	protected $htmlContent = "";
	protected $style = "background-color: white;";

	function __construct($name, $content, $style, $class = "uiOptionGroup") {
		$this->name = $name;
		$this->content = $content;
		$this->styleClass = $class;
		$this->type = UI_CONTAINER;
		$this->style = " " . $style;
	}
	
	function __destruct() {

	}

	function attach(uiElement $element) {
		// we can attach everything as child element
		parent::attach(&$element);
	}

	function __toString() {
		$this->htmlContent .= '<div style="width: 450px; padding: 7px; color: white;' . $this->style . '" class="' . $this->styleClass . '" id="' . $this->name . '"><span style="font-size: 18px; line-height: 30px;">' . $this->content . "</span><br/><br/>";
		foreach($this->childElements as $key => $childElement) {
			$this->htmlContent .= $childElement->__toString() . "<br/>\n";
		}

		$this->htmlContent .= '</div>' . "\n";
		return $this->htmlContent;
	}

	function getClassName() {
		return $this->className;
	}
}
?>
