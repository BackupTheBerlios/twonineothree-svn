<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 
  This file controls the handling of cache objects
*/

require_once($CONFIG['LibDir'] . 'db/' . $CONFIG['DatabaseType'] . '.php');

class cacheObject {

	private $db;
	private $cachedContent;
	private $latestChange;
	private $siteName;
	private $pageName;
	private $scheduleCaching;
	private $pFileName;

	function __construct(DatabaseConnector &$db, $siteName, $pageName) {
		$this->db =& $db;
		$this->cachedContent = 0;

		$this->siteName = $siteName;
		$this->pageName = $pageName;
		$this->scheduleCaching = false;

		$this->getLatestChange();
	}

	function __destruct() {

	}

	function getLatestChange() {

		if($this->scheduleCaching) {
			$this->db->executeQuery("SELECT MAX(mdate) FROM " . mktablename("boxes") . " WHERE name LIKE '" . $this->pageName ."_%'");

			$tmp = $this->db->fetchArray();

			$this->latestChange = $tmp[0];

			DEBUG("CACHE: Latest change on page occured on: " . strftime("%Y-%m-%d, %H:%M:%S %z", $this->latestChange));
		}
	}

	function getCached() {
		
		global $CONFIG;
		
		if($this->scheduleCaching) {
			$filename = $this->siteName . "_" . $this->pageName . "_" . sha1($this->siteName . "_" . $this->pageName . "_" . $this->latestChange);
		
			$this->pFileName = $filename;

			// cancel here for testing new layout thinx
		
			if(!file_exists($CONFIG["CacheDir"] . $filename)) {
				// cached file does not exists _OR_ is outdated, so delete any file with matching site and page name.
				$delete_pattern = $CONFIG["CacheDir"] . $this->siteName . "_" . $this->pageName . "_*";
				DEBUG("CACHE: Delete pattern is: $delete_pattern");
				$files = glob($delete_pattern);
				if($files !== false) {
					foreach($files as $oldfile) {
						unlink($oldfile);
						DEBUG("CACHE: I unlinked outdated/invalid cache file $oldfile");
					}
				} else {
					DEBUG("CACHE: No files to delete.");
				}

				$this->setScheduleCaching(true);
				return false;
			} else {
				DEBUG("CACHE: Found cached file at " . $CONFIG["CacheDir"] . $filename);
				return true;
			}
		} else {
			return false;
		}
	}

	function setScheduleCaching($to_be_or_not_to_be) {
		$this->scheduleCaching = $to_be_or_not_to_be;
	}

	function getScheduleCaching() {
		return $this->scheduleCaching;
	}

	function getCacheContent() {
		global $CONFIG;
		$tmp = file_get_contents($CONFIG["CacheDir"] . $this->pFileName);
		return $tmp;
	}

	function doCache($buffer) {
		global $CONFIG;
		if($this->scheduleCaching == true) {
			if(!$file = fopen($CONFIG["CacheDir"] . $this->pFileName, "w")) {
				DEBUG("CACHE: Failed opening " .  $CONFIG["CacheDir"] . $this->pFileName . " for caching.");
				return;
			} else {
				fwrite($file, $buffer);
				DEBUG("CACHE: Wrote " . strlen($buffer) . " bytes to cache file " . $CONFIG["CacheDir"] . $this->pFileName);
				return;
			}
		}
	}

	function getFileName() {
		global $CONFIG;
		return $CONFIG["CacheDir"] . $this->pFileName;
	}
}

?>
