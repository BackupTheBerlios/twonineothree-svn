<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
 
  MySQL Database Connector for 29o3
 
  *** THIS DATABASE CONNECTOR IS CURRENTLY NOT USABLE ***
*/



class DatabaseDriver {

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

	function query($sql_commands) {

		$this->res = mysql_query ($sql_commands, $this->link)
			or n_error("Database Panic", "A database panic occured:\n" . mysql_errno() . " (" . mysql_error() . ")");

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

	function numRows() {
		return mysql_num_rows($this->res);
	}

	function numFields() {
		return mysql_num_fields($this->res);
	}

	function getLastError() {
		return $this->lasterr;
	}
}

?>
