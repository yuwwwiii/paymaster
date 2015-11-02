<?php

define('_USER_TYPE_EMPLOYER',1);
define('_USER_TYPE_JOBSEEKER',2);
define('_USER_TYPE_AGENCY',3);
define('_USER_TYPE_ADMIN',9);


class AppUser {
	
	/**
	 * Enter description here...
	 * 
	 * paramerters:
	 * $column - 
	 *   'wise_member' - user id
	 *   'wise_membertype' - user type 
	 *
	 * @param unknown_type $column
	 * @return unknown
	 */
	function getData($column) {
		return $_SESSION['admin_session_obj']['user_data'][$column];
	}
}

?>