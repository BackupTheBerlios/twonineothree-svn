<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  MySQL Database Connector for 29o3
  Written by Markus Hesse <digitaldevil@oc-hartware.de>
 
  CHANGELOG:
  2004/10/26		Initial version
*/



class DatabaseConnector {

	private $DRIVER_INFO = array(
		"DatabaseType"			=>	"mysqll",
		"DatabaseConnectorVersion"	=>	"0.0.1",
		"DatabaseConnectorAuthor"	=>	"Markus Hesse",
		"DatabaseConnectorAuthorMail"	=>	"digitaldevil@oc-hartware.de",
		"DatabaseConnectorStatus"	=>	"devel"
	);

	private $server;
	private $port;
	private $user;
	private $password;

	private $res;
	private $link;

	private $lasterr;


	function __construct() {
		$this->link = NULL;
	}

	function __destruct() {
		$this->link = NULL;
		$this->res = NULL;
		$this->user = NULL;
		$this->password = NULL;
	}

	function setupConnection($srv, $usr, $pw, $database, $prt) {

		$this->link = NULL;
		if($prt == "") {
			$prt = 3306;
		}
		
		$this->server  = $srv;
		$this->port =	$prt;
		$this->user = $usr;
		$this->password = $pw;
		


		$this->link = mysql_connect("$this->server:$this->port", $this->user, $this->password);
		if($this->link == NULL) {
			die("Database connection failed! [" . __FILE__ . " line" . __LINE__);
		}

		mysql_select_db($database);

	}

	function closeConnection() {
		mysql_close($this->link);
	}

	function executeQuery($sql_commands) {

		if(strpos(";", $sql_commands)+1 == strlen($sql_commands)) {
			$sql_commands = substr($sql_commands, 0, strlen($sql_commands)-1);
		}
		$this->res = mysql_query ($sql_commands, $this->link)
			or err("Database Panic", "A database panic occured:\n" . mysql_errno() . " (" . mysql_error() . ")");

	}

	function fetchObject() {

		if(($object = mysql_fetch_object($this->res)) != false) {
			return $object;
		}

		else {
			return false;
		}
	}

	function fetchArray() {

		if(($array = mysql_fetch_array($this->res)) != false) {
			return $array;
		}

		else {
			return false;
		}
	}

	function fetchRow() {

		if(($array = mysql_fetch_row($this->res)) != false) {
			return $array;
		}

		else {
			return false;
		}
	}

	function getNumRows() {
		return mysql_num_rows($this->res);
	}

	function getNumFields() {
		return mysql_num_fields($this->res);
	}

	function getLastError() {
		return $this->lasterr;
	}

	function checkPhpSupport() {
		if(!function_exists("mysql_connect")) {
			die("PHP has to be compiled with MySQL support in order to use MySQL as database. Please recompile PHP with the option --with-mysql or get adequate modules installed.");
			return false;
		}
		return true;
	}

	function getConnectorInformation() {
		return ($this->DRIVER_INFO);
	}

	function getExecutedQueries() {
		return $this->executedQueries;
	}
}

?>
