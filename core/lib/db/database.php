<?php
/* 29o3 content management system
 * (c) 2003-2004 by Ulrik Guenther <kpanic@mg2.org>
 * This software is licensed under the terms of the BSD license.
 *
 * PostgreSQL Database Connector for 29o3
 *
 */ 


abstract class DatabaseDriver {

	function __construct();

	function __destruct();

	function setupConnection($srv, $usr, $pw, $database, $prt);

	function closeConnection();

	function executeQuery($sql_commands);

	function fetchObject();

	function fetchArray();

	function fetchRow();

	function getNumRows();

	function getNumFields();

	function getLastError();

	function checkPhpSupport();

	function getDriverInformation();

?>
