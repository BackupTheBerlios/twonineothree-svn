<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@mg2.org>
  This software is licensed under the terms of the BSD license.
 
  PostgreSQL Database Connector for 29o3
 
  CHANGELOG:
  somewhen Aug 2004	Initial version
  2004/09/04		Changed pg_query to pg_send_query. Tricky, tricky!
 
*/ 


class DatabaseConnector {

	private $DRIVER_INFO = array(
		"DatabaseType"			=>	"postgresql",
		"DatabaseConnectorVersion"	=>	"0.0.1",
		"DatabaseConnectorAuthor"	=>	"Ulrik Guenther",
		"DatabaseConnectorAuthorMail"	=>	"kpanic@00t.org",
		"DatabaseConnectorStatus"	=>	"devel"
	);

	private $server;
	private $port;
	private $user;
	private $password;

	private $res;
	private $link;

	private $lasterr;
	private $executedQueries;


	function __construct() {
		$this->link = NULL;
		$this->res = NULL;

		// check if PHP supports this database type.
		// the installer script will build an instance of every
		// db driver, some will exit here due to php's (non-)support
		$this->checkPhpSupport();

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
			$prt = 5432;
		}
		
		$this->server  = $srv;
		$this->port =	$prt;
		$this->user = $usr;
		$this->password = $pw;
		


		$this->link = pg_connect("host=" . $srv . " port=" . $prt . " dbname=" . $database . " user=" . $usr . " password=" . $pw);
		if($this->link == NULL) {
			die("Database connection failed! [" . __FILE__ . " line " . __LINE__);
		}

	}

	function closeConnection() {
		pg_close($this->link);
	}

	function executeQuery($sql_commands) {

		pg_send_query ($this->link, $sql_commands)
			or n_error("Database Panic", "A database panic occured:\n" . pg_result_error($this->res));
		
		$this->res = pg_get_result($this->link);
		$this->executedQueries++;

	}

	function fetchObject() {

		if(($object = pg_fetch_object($this->res)) != false) {
			return $object;
		}

		else {
			return false;
		}
	}

	function fetchArray() {

		$array = pg_fetch_array($this->res);
		if($array) {
			return $array;
		}

		else {
			return false;
		}
	}

	function fetchRow() {

		if(($array = pg_fetch_row($this->res)) != false) {
			return $array;
		}

		else {
			return false;
		}
	}

	function getNumRows() {
		return pg_num_rows($this->res);
	}

	function getNumFields() {
		return pg_num_fields($this->res);
	}

	function getLastError() {
		return $this->lasterr;
	}

	function checkPhpSupport() {
		if(!function_exists("pg_version")) {
			die("PHP has to be compiled with PostgreSQL support in order to use PostgreSQL as database. Please recompile PHP with the option --with-pgsql or get adequate modules installed.");
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