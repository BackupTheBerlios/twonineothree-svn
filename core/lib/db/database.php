<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  The abstract class DatabaseDriver contains all functions which
  29o3's database drivers need to implement. Use this as base class, please :)
 
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
