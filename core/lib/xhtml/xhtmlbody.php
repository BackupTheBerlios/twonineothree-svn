<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  The XHTMLBody class allows output and formatting of body elements
  like <p>, <div>,...
 
*/


class XHTMLBody {

	private $buffer;

	function __construct($params = "") {
		$this->buffer .= "<body" . $params . ">\n";
	}

	function printBuffer() {
		$this->buffer .= "\n</body>\n";
		printf($this->buffer);
	}

	function eyecandyConsole($consoleObject) {
		if(method_exists($consoleObject, "getBuffer")) {
			$consoleObject->getBuffer();
		} else {
			err("System console object invalid","EyecandyConsole() got an invalid system console object.\nThis is unlikely to happen without *ANY* reason, please contact the administrator!", $CONFIG['DebugLevel']+1);
		}

		$this->buffer .= '<center><div class="eyecandyConsole">' . $consoleObject->getBuffer() . '</div></center>';
	}

	function insertDiv($styleClass, $content, $title = "") {
		if($title == "") {
			printf('<div class="%s" title="%s">%s</div>', $styleClass, $title, $content);
		} else {
			printf('<div class="%s"></div>', $styleClass, $title, $content);
		}
	}

	function insertParagraph() {

	}

	function insertSpan() {

	}
}

?>
