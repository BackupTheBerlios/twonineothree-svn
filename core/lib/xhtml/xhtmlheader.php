<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  The XHTMLHeader class is the class which writes all the *HTML* header
  information to the page.
 
*/


class XHTMLHeader {

	private $buffer;
	private $titleSet = false;

	function __construct() {
		$this->buffer .= "<head>\n";
	}

	function addStylesheetExternal($external) {
		$this->buffer .= "\t<link rel=\"stylesheet\" href=\"$external\" />";
	}

	function addStylesheet($content) {
		// TODO: Replace ereg_replace with the faster preg_replace
		//       (before, learn syntax of pcre...)
		// TODO: Check if preg_* is really faster than ereg_*
		$content = "\t" . $content;
		$content = ereg_replace("\r\n", "\r\n\t", $content);
		$content = ereg_replace("\n", "\n\t", $content);
		$this->buffer .= "\n\t<style type=\"text/css\">\n\t<![CDATA[\n\t$content\n\t]]>\n\t</style>\n";	
	}

	private function addMetaTag($name, $content, $type) {
		if($type == 0) {
			$this->buffer .= "\t" . '<meta http-equiv="' . $name . '" content="' . $content . '" />' . "\n";
		}
		else {
			$this->buffer .= "\t" . '<meta name="' . $name . '" content="' . $content . '" />' . "\n";
		}
	}

	function addMetaGenerator($generator) {
		$this->addMetaTag("generator", $generator, 1);
	}

	function addMetaRefresh($url, $seconds) {
		$this->addMetaTag("refresh", "$seconds; $url", 0);
	}

	function addMetaPragmaNoCache() {
		$this->addMetaTag("pragma", "no-cache", 0);
	}

	function addMetaRevisitAfter($period) {
		$this->addMetaTag("revisit-after", "$period", 0);
	}

	function addMetaAuthor($author) {
		$this->addMetaTag("author", $author, 1);
	}

	function addMetaDescription($description) {
		$this->addMetaTag("description", $description, 1);
	}

	function addMetaKeywords($keywords) {
		$this->addMetaTag("keywords", $keywords, 1);
	}

	private function addMetaDC($type, $content) {
		$this->buffer .= "\t<meta name=\"DC.$type\" content=\"$content\" />\n";
	}

	function addMetaDCTitle($title) {
		$this->addMetaDC("Title", $title);
	}

	function addMetaDCCreator($author) {
		$this->addMetaDC("Creator", $author);
	}
	
	function addMetaDCSubject($subject) {
		$this->addMetaDC("Subject", $subject);
	}

	function addMetaDCDescription($desc) {
		$this->addMetaDC("Description", $desc);
	}

	function addMetaDCPublisher($pub) {
		$this->addMetaDC("Publisher", $pub);
	}

	function addMetaDCContributor($contrib) {
		$this->addMetaDC("Contributor", $contrib);
	}

	function addMetaDCDate($date) {
		$this->addMetaDC("Date", $date);
	}

	function addMetaDCType($type) {
		$this->addMetaDC("Type");
	}

	function addMetaDCFormat() {
		$this->addMetaDC("Format", "application/xhtml+xml");
	}

	function addMetaDCIdentifier($id) {
		$this->addMetaDC("Identifier", $id);
	}

	function addMetaDCSource($source) {
		$this->addMetaDC("Source", $source);
	}

	function addMetaDCLanguage($lang) {
		$this->addMetaDC("Language", $lang);
	}

	function addMetaDCCoverage($cov) {
		$this->addMetaDC("Coverage", $cov);
	}	

	function addMetaDCRights($cr) {
		$this->addMetaDC("Rights", $cr);
	}

	function setTitle($title) {
		if($title != "") {
			$this->buffer .= "\t<title>" . $title . "</title>\n";
			$this->titleSet = true;
		}
	}

	function addScriptExternal($external, $type = "JavaScript")  {
		$this->buffer .= "<script language=\"$type\" src=\"$external\" />\n";
	}

	function addScript($script, $type = "JavaScript") {
		$this->buffer .= "<script language=\"$type\">\n<![CDATA[\n<!--$script\n//-->\n]]>\n</script>\n";
	}

	function addComment($comment) {
		$this->buffer .= '<!-- ' . $comment . '-->';
	}

	function printBuffer() {
		$this->buffer .= "\n</head>\n";
		echo $this->buffer;
	}

	function getBuffer() {
		return $this->buffer . "</head>";
	}

}


?>
