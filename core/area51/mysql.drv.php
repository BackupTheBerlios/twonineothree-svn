<?php
/* 29o3 website management system
 * (c) 2003-2004 by Ulrik Guenther <kpanic@mg2.org>
 * This software is licensed under the terms of the MIT license.
 *
 * MySQL (PHP4/5) Database Connection Layer for 29o3.
 *
 */ 

class driver {

	var $server;
	var $port;
	var $user;
	var $password;

	var $res;
	var $link;

	var $lasterr;

	function setupConnection($srv, $usr, $pw, $database, $prt) {

		if($prt == "") {
			$prt = 3306;
		}
		
		$this->server  = $srv;
		$this->port =	$prt;
		$this->user = $usr;
		$this->password = $pw;
		


		$this->link = mysql_connect("$this->server:$this->port", $this->user, $this->password);

		mysql_select_db($database);

	}

	function closeConnection() {
		mysql_close($this->link);
	}

	function queryDatabase($sql_commands) {

		$this->res = mysql_query ($sql_commands, $this->link)
			or die("A database panic occured:\n" . mysql_errno() . " (" . mysql_error() . ")");

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
}

?>