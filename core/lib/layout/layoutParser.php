<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  The LayoutParser class is responsible for parsing layout definitions
  of pages.
 
*/


require_once($CONFIG['LibDir'] . 'page/pageDescriptionObject.php');
require_once($CONFIG['LibDir'] . 'layout/layoutHeaderFuncs.php');
require_once($CONFIG['LibDir'] . 'layout/layoutDesignFuncs.php');

class LayoutParser {

	private $pageBuffer;
	private $pdo; // page description object
	private $headerFuncsObject;
	private $designFuncsObject;
	
	function __construct(pageDescriptionObject &$pdo) {
		$this->pageBuffer = "";
		$this->pdo =& $pdo;
		$this->headerFuncsObject = new LayoutHeaderFuncs($this->pdo);
		$this->designFuncsObject = new LayoutDesignFuncs($this->pdo);
	}

	function __destruct() {
		$this->pageBuffer = "";
	}

	// LayoutParser::parseLayout
	// this function parses the layouts which are saved in the [PREFIX]_layout
	// table. It goes through them line after line, and ignores lines beginning 
	// with '//' and processes ::29o3.someFunction() commands.
	function parseLayout($layoutBuffer) {

		$buffer = explode("\n", $layoutBuffer);

		$multilineComment = false;
		
		for($i = 0; $i <= count($buffer); $i++) {
			$line = $buffer[$i];
			// check if we have a comment ('//') inside the current line
			// comments will be skipped from the '//' to the end of the line
			if(strlen($line) <= 1) {
				continue;
			}

			if($multilineComment == true && ($commentClosePosition = strpos($line, '*/')) !== false) {
				$multilineComment = false;
				if(($commentClosePosition + 2) < strlen($line)) {
					$line = substr($line, $commentClosePosition+2, strlen($line));
				} else {
					continue;
				}
			}
			
			// check for lines beginning with '//'
			// if the '//' is NOT at the beginning, 29o3 will not evaluate it as comment!
			if(strpos($line, '//') === 0) {
				continue;
			}
			if(($commentPosition = strpos($line, "/*")) !== false) {
				$multilineComment = true;
				continue;
			}
			if($multilineComment) {
				continue;
			}
			// at this point, we can be sure that the line is free of comments, so that it can
			// be processed; the next what we'll do is to check whether we are in the
			// header block or in the layout block since both of them (dis)allow particular
			// functions and options.
			
			// can anyone tell me if this regex is okay for this job?
			// i hate regex... cause i don't understand them
			preg_match("/(header|design)(\\r|\\s|\\n)+\{/", $line, $block_matches);
			if($block_matches[1] == "header") {
				$in_header = true;
//				$in_design = false;
				continue;
			}

			if($block_matches[1] == "design") {
				$in_design = true;
//				$in_header = false;
				continue;
			}

			if($in_header) {
				if(strpos($line, "}") === 0) {
					$in_header = false;
					continue;
				}
			}
			if($in_design) {
				if(strpos($line, "}") === 0) {
					$in_design = false;
					continue;
				}
			}

			// if operating on the header, $line is directly used because the
			// header data is of no importance for the content of the page
			if($in_header && !$in_design) {
				if(($commandPosition = strpos($line, "::29o3.")) !== false) {
					$line = substr($line, $commandPosition+7, strlen($line) - ($commandPosition+2));

					$function = $this->parseFunction($line);

					$this->headerFuncsObject->grabRightFunction($function["name"], $function["parameters"]);
				}
				continue;
			}

			// for the layout block, we use a copy of line to operate on since
			// the things in the layout block are of utmost importance for the
			// correct display of the page
			if(!$in_header && $in_design) {
				// do a copy of $line to operate on
				$line_copy = $line;
				if(($commandPosition = strpos($line_copy, "::29o3.")) !== false) {
					$line_copy = substr($line, $commandPosition+7, strlen($line_copy) - ($commandPosition+2));

					$function = $this->parseFunction($line_copy);

					$output = $this->designFuncsObject->grabRightFunction($function["name"], $function["parameters"]);
//					echo $output;
					$line = str_replace($function["fullname"], $output, $line);

//					$this->pdo->insertIntoBodyBuffer($line);
				}
//				continue;
			}

			if(!$in_header && $in_design) {
				$this->pdo->insertIntoBodyBuffer($line);
			}


			// this point is *never* reached
			
		}
	}

	/*	parseFunction produces the following array:
		[0] => name of the function
		[1] => parameter no. 1
		[2] => parameter no. 2
		...
		[n] => parameter no. n

		If no function is found, [0] will content "NotAFunction"

		TODO: Add check if function exists

	*/
	private function parseFunction($line) {
		
		$fullname = "::29o3.";
	
		// get the name of the function
		$functionName = substr($line, 0, ($openBracketPos=strpos($line, "(")));

		if($functionName == "") {
			$resultsArray[0] = "NotAFunction";
		} else {
			$resultsArray[0] = $functionName;
		}

		$fullname .= $resultsArray[0];
	
		// should be the last ')'
		$closeBracketPos = strrpos($line, ")");

		// get the string with the arguments
		$functionArguments = substr($line, $openBracketPos+1, $closeBracketPos-$openBracketPos-1);

		$fullname .= "(" . $functionArguments . ");";
		
		if(strlen($functionArguments) >= 2) {
			
			preg_match_all("/\"[A-Za-z0-9_\\s]+\"/", $functionArguments, $results);

			$i = 0;
			while($results[0][$i] != "") {	
				
				$results[0][$i] = str_replace("\"", "", $results[0][$i]);
				$resultsArray[$i+1] = $results[0][$i];
				$i++;				
			}
			
		}

		$params = array_slice($resultsArray, 1);
		
		$retArray = array(
			"name" 		=> $resultsArray[0],
			"parameters" 	=> $params,
			"fullname"	=> $fullname
		);

		return $retArray;
	}

}

?>
