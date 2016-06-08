<?php

include_once './controller/SessionStart.php';
include_once "./controller/User.php";
include_once './controller/Producer.php';
include_once './controller/Watcher.php';

final class UpdateSessionInfo
{
	
	private function __construct() { }
	
	public static function updateSessionInfo($course_id, $channel_id)
	{
		// update $_SESSION
		if(empty($channel_id)) $_SESSION['channel_Info'] = "";
	
		$_SESSION['course_uploadInfo'] = Producer::listUploadedCourses($_SESSION['usr_id']);
		$_SESSION['course_chanInfo'] = User::getCourseChannelsArray($course_id, $_SESSION['course_uploadInfo']);
	
	}
	
	public static function clearCourseChanInfo()
	{
		
		$_SESSION['course_chanInfo'] = "";
		
	}
	
	public static function clearCacheInfo()
	{
	
		$_SESSION['cached_course_uploadInfo'] = "";
	
	}
	
	public static function updateCacheInfo($course_id)
	{
			
		$_SESSION['cached_course_uploadInfo'] = Producer::getCachedChanInfo($course_id);
	
	}
	
	public static function initDownloadSessionInfo()
	{
		
		$_SESSION['course_id'] = "";
		$_SESSION['course_uploadInfo'] = "";
		$_SESSION['course_id_modify'] = "";
		$_SESSION['cached_course_uploadInfo'] = "";
		$_SESSION['channel_id_modify'] = "";
		
	}
	
	public static function initUploadSessionInfo()
	{
		$_SESSION['channel_downloadInfo'] = "";
		$_SESSION['course_id_modify'] = "";
		$_SESSION['course_downloadInfo'] = "";
		$_SESSION['channel_id_modify'] = "";
		$_SESSION['course_chanInfo'] = "";
	}
	
	
}

?>