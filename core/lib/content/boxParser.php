<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  The BoxParser class is responsible for parsing the content of
  boxes. Therefore it is not that complex like the one for layouts
  as the boxes' content structure isn't as complicated as the
  structure of the layout definitions.
 
*/


require_once($CONFIG['LibDir'] . 'page/pageDescriptionObject.php');
require_once($CONFIG['LibDir'] . 'content/boxFuncs.php');

class BoxParser {

	private $boxBuffer = "";
	private $pdo; // page description object
	private $boxFuncsObject;
	
	function __construct(pageDescriptionObject &$pdo) {
		$this->boxBuffer = "";
		$this->pdo =& $pdo;
		$this->boxFuncsObject = new BoxFuncs($this->pdo);
	}

	function __destruct() {
		$this->boxBuffer = "";
	}

	// BoxParser::parseLayout
	// this function parses the boxes which are saved in the [PREFIX]_boxes
	// table. It goes through them line after line, and ignores lines beginning 
	// with '//' and processes ::29o3.someFunction() commands.
	function parseBox($boxBuffer) {

		$buffer = "";
		$buffer = explode("\n", $boxBuffer);

		for($i = 0; $i < count($buffer); $i++) {
			$line = $buffer[$i];
			
			$line_copy = $line;
			if(($commandPosition = strpos($line_copy, "::29o3.")) !== false) {
				$line_copy = substr($line, $commandPosition+7, strlen($line_copy) - ($commandPosition+2));

				$function = $this->parseFunction($line_copy);

				$output = $this->boxFuncsObject->grabRightFunction($function["name"], $function["parameters"]);
				$line = str_replace($function["fullname"], $output, $line);

			}
			$this->boxBuffer .= "|" . $line;
			
		}
		return $boxBuffer; //this->boxBuffer;
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
