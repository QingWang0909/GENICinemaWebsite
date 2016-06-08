<?php

/**
 *  This class defines an Course Expire System to detect the expire course, and automatically turn them off
 */

include_once 'Data.php';
include_once "FunctionsForDatabase.php";
include_once "LinkUpController.php";
include_once "ConnectDatabase.php";
include_once "EventManager.php";
include_once 'User.php';
include_once "Producer.php";


// Check Expire Time for All Courses
$query = "SELECT * FROM " . $_SESSION['course_table'];
$data = mysql_query($query) or die( "no query" );

if( mysql_num_rows($data) > 0 ){
	while( $course_row = mysql_fetch_array($data) ){
		
		$course_id = $course_row[0];
		$course_name = $course_row[1];
		$admin_pwd = $course_row[3];
		$course_status = $course_row[8];
		$end_date = $course_row[10];
		
		if(  isExpire($end_date) && $course_status === "OPEN" ){
			Producer::closeCourse($course_id, $_SESSION['remove_channel_url'], $admin_pwd);
			echo "Course Name: $course_name, Course ID: $course_id already expired, automatically turned it off. \n";
			
		}
	
	}
	
}


function isExpire($expire_time)
{
	
	$today = date("Y-m-d H:i:s");
	echo "$today\n";
	
	if( strtotime($expire_time) < strtotime($today) ){
		return true;
		
	}
	else{
		return false;
		
	}
	
}



?>