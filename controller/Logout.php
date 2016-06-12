<?php
/* 1) Logout & Redirect to index page
 * 2) Add some animation Later
 * 
 */

include_once 'SessionStart.php';
include "./User.php";

// Logout from current page
User::logout();
	


?>