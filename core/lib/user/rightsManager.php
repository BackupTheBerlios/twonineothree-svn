<?php
/*
  29o3 content management system
  (c) 2003-2004 by Ulrik Guenther <kpanic@00t.org>
  This software subjects to the license described in the
  file LICENSE you should have received with this distribution.
 

  Class for checking access privileges for a particular user. 
*/

class rightsManager {

	private $currentUser = "";
	private $userVerified = false;
	private $currentGroup = "";
	private $userCurrentAccessRights = "000";
	
	function __construct(&$array) {
		$this->userCurrentAccessRights =& $array['rights'];
	}

	function hasUserViewingRights() {
		$splitRights = preg_split("//", $this->userCurrentAccessRights, -1, PREG_SPLIT_NO_EMPTY);
		if($splitRights[2] >= 1) {
			return true;
		}
		return false;
	}
}

?>
