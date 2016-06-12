<?php
/**
 *  This class checks the viewer password and admin password that client enter in:
 *  	1) viewer password check
 * 		2) admin  password check
 */

include_once 'SessionStart.php';

final class PwdValidator
{
	private function __construct() {}
	
	public static function adminPwdCheck($pwd, $uid, $course_id)
	{
		if( !$uid || !$course_id ){
			throw new Exception("Passing Parameter can NOT be NULL");
		}
		
		$query = " SELECT * FROM " . $_SESSION['course_table'] .
				 " WHERE uid = " . "'$uid'" . " AND " .
				 " course_id = " . "'$course_id'";
		$data = mysql_query( $query ) or die( "no query" );
		$row_num = mysql_num_rows($data);
		
		if( $row_num != 1 ){
			die("No matched record in Database, which should never happen: <br/>" . mysql_error());
		}

		$row = mysql_fetch_row($data);
		if( strcmp($row[3], $pwd) ){
			$error_msg = "Password is incorrect";
			
		}
		else{
			$error_msg = 0;	// success
			
		}
		
		return $error_msg;
	}
	
	public static function viewPwdCheck($course_id, $pwd)
	{
		if( !$course_id ){
			throw new Exception("Passing Parameter can NOT be NULL");
		}		
		
		
		$query = " SELECT * FROM " . $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query( $query ) or die( "no query" );
		
		
		$row_num = mysql_num_rows($data);
		if( $row_num != 1 ){
			die("No matched record in Database, which should never happen: <br/>" . mysql_error());
		}
		
		$row = mysql_fetch_row($data);
		if( strcmp($row[2], $pwd) !== 0 ){
			$error_msg = "Password is incorrect";
		
		}
		else{
			$error_msg = 0;	// success
		
		}

		return $error_msg;		
		
	}
	
	
	
}



?>