<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  uiElement.php
  Base class for all UI elements used by 29o3
  This class is just an elementary one to enable
  the inclusion of UI elements into arrays and then
  iterating through them.
*/

define("UI_TEXT", 	0x00000001);
define("UI_IMAGE", 	0x00000002);
define("UI_PLACEHOLDER",0x00000004);
define("UI_CONTAINER",	0x00000008);
define("UI_MENUITEM",	0x00000016);
define("UI_MENU",	0x00000032);

class uiElement {

	private $name;
	private $content;
	private $type = UI_TEXT;

	private $htmlContent;

	private $className = "uiElement";

	private $childElements = array();

	function __construct($name, $content, $type = UI_TEXT) {
		$this->name = $name;
		$this->content = $content;
		$this->type = $type;
	}

	function __destruct() {

	}

	function attach(uiElement $element) {
		array_push(&$element, $this->childElements);
	}

	function detach($name) {
		$i = 0;
		foreach($this->childElements as $key => $childElement) {
			if($childElement->getName() == $name) {
			// detach the child element from the array
				// split the childElements array into two
				//    $slice1        $name         $slice2
				// [ SLICE ONE ][ITEM TO DETACH][ SLICE TWO ]
				$slice1	= array_slice($this->childElements, 0, $i);
				$slice2 = array_slice($this->childElements, $i+1);

				// put the array together again
				$this->childElements = array_merge($slice1, $slice2);
			}
			$i++;
		}
	}

	function getHtmlContent() {
		
		$this->htmlContent .= $this->content;
		// iterate through child elements
		foreach($this->childElements as $key => $childElement) {
			$this->htmlContent .= $childElement->getHtmlContent() . "\n";
		}
		return $this->htmlContent;
	}

	function getClassName() {
		return $this->className;
	}

}

?>
