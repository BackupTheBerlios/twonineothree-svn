<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  TrackBack client implementation.
  Dunno if really needed, but TrackBack seems to belong to weblog systems.
  And as 29o3 is something inbetween CMS systems and weblog systems, I implement
  TrackBack here. Main source of information was: 
  
  	http://www.movabletype.org/docs/mttrackback.html

  Thanks for this useful piece of information :)

  MEMO: I don't really like TrackBack...
*/

error_reporting(E_ERROR | E_PARSE);

require_once('./config.php');
require_once($CONFIG['LibDir'] . 'common.php');
require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');
require_once($CONFIG['LibDir'] . 'page/pagerequest.php');
require_once($CONFIG['LibDir'] . 'sys/console.php');

$console = new SystemConsole();

$connector = new DatabaseConnector();
$connector->setupConnection($CONFIG['DatabaseHost'], $CONFIG['DatabaseUser'], $CONFIG['DatabasePassword'], $CONFIG['DatabaseName'], $CONFIG['DatabasePort']);

$request = new pageRequest($connector);
$request->parseRequest();


if(isset($_POST['url'])) {
	$ping_url = $_POST['url'];
} else {
	$ping_url = "";
}

if($ping_url == "") {
	header("Location: index.php?" . $request->getRequestedSite() . "/" . $request->getRequestedPage());
}

if(isset($_POST['title'])) {
	$ping_title = $_POST['title'];
} else {
	$ping_title = "";
}

if(isset($_POST['blog_name'])) {
	$ping_blogname = $_POST['blog_name'];
} else {
	$ping_blogname = "";
}

if(isset($_POST['excerpt'])) {
	$ping_excerpt = $_POST['excerpt'];
	$ping_excerpt = substr(0,253, $ping_excerpt) . "...";
} else {
	$ping_excerpt = "";
}

killScriptKiddiesGently($ping_url);
killScriptKiddiesGently($ping_title);
killScriptKiddiesGently($ping_blogname);
killScriptKiddiesGently($ping_excerpt);


header("Content-type: text/xml");

printf('<?xml version="1.0" encoding="UTF-8"?>%s', "\n");

echo "<!-- 29o3 generated TrackBack response to ping from -->\n";
echo "<response>\n"; 


if($request->getWantAdmin()) {
	echo "<error>401</error>\n";
	echo "<message>Forbidden</message>\n";
} else {
	if($request->getError() != 0) {
		echo "<error>" . $request->getError() . "</error>\n";
		echo "<message>" .  getMsgFromNo($request->getError()) . "</message>\n";
	} elseif($ping_url == "") {
		echo "<error>2</error>\n";
		echo "<message>No Ping URL given</message>\n";
	}
	else {
		if($ping_title == "") {
			$ping_title = $ping_url;
		}
		$connector->executeQuery("INSERT INTO " . mktablename("tbping") . " VALUES(0, '" . 
				$request->getRequestedSite() . "/" . $request->getRequestedPage() . "', '" .
				$ping_url . "', '" . $ping_title . "', '" . $ping_excerpt . "', '" . $ping_blogname . "');");
		echo "<error>0</error>\n";

	}
}

echo "</response>\n";

?>
