<?php
/*
  29o3 content management system
  (c) 2003-2005 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  The XHTMLBody class allows output and formatting of body elements
  like <p>, <div>,...
 
*/


class XHTMLBody {

	private $buffer = "";

	function __construct($params = "") {
		$this->buffer = "<body" . $params . ">\n";
	}

	function printBuffer() {
		$this->buffer .= "\n</body>\n";
		echo $this->buffer;
	}

	function printPartialBuffer() {
		echo $this->buffer;
	}

	function eyecandyConsole($consoleObject) {
		if(method_exists($consoleObject, "getBuffer")) {
			$consoleObject->getBuffer();
		} else {
			err("System console object invalid","EyecandyConsole() got an invalid system console object.\nThis is unlikely to happen without *ANY* reason, please contact the administrator!", $CONFIG['DebugLevel']+1);
		}

		$this->buffer .= '<div class="eyecandyConsole">' . $consoleObject->getBuffer() . '</div>';
	}

	function insertDiv($styleClass, $content, $title = "", $additionals = "") {
		if($title == "") {
			printf('<div class="%s" title="%s"%s>%s</div>', $styleClass, $title, $additionals, $content);
		} else {
			printf('<div class="%s"%s>%s</div>', $styleClass, $additionals, $content);
		}
	}

	function insertParagraph() {

	}

	function insertSpan() {

	}

	function insertTag($tagName, $elemContent, $elemStyleClass, $elemId, $elemTitle = "", $elemAdditionals = "", $close = true) {

		$tagBuf = "";

		$tagBuf .= "<$tagName";

		if($elemStyleClass != "") {
			$tagBuf .= " class=\"$elemStyleClass\"";
		}

		if($elemId != "") {
			$tagBuf .= " id=\"$elemId\"";
		}

		if($elemTitle != "") {
			$tagBuf .= " title=\"$elemTitle\"";
		}

		if($elemAdditionals != "") {
			$tagBuf .= " $elemAdditionals";
		}

		if($close) {
			if($elemContent != "") {
				$tagBuf .= ">$elemContent</$tagName>\n";
			} else {
				$tagBuf .= "></$tagName>\n";
			}
		} else {
			$tagBuf .= ">\n";
		}

		$this->buffer .= $tagBuf;

	}

	function insertCloseTag($tag) {
		$this->buffer .= "</$tag>\n";
	}

	function rawInsert($string) {
		$this->buffer .= $string;
	}

	function getBuffer() {
		return $this->buffer;
	}
}

?>
