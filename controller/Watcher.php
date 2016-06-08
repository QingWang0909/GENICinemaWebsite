<?php

include_once 'SessionStart.php';
include_once "FunctionsForDatabase.php";
include_once "LinkUpController.php";
include_once "ConnectDatabase.php";
include_once "EventManager.php";
include_once "PwdValidator.php";


final class Watcher extends User
{
	private function __construct() { }
	
	private function listAllVideoChannelsFromFL($list_channel_url)
	{
		
		if(!$list_channel_url) throw new Exception("Null Exception");
		
		$json_message = LinkUpController::listChannels($list_channel_url);
		$channels_message = LinkUpController::parseToArrayMsg($json_message);
		
		// Parse Error Message if neccesary
		EventManager::listChannels($channels_message[0]['code']);
		
		return $channels_message;
	
	}	
	
	private function storeDemandInDB()
	{
		
		$channels_message = self::listAllVideoChannelsFromFL($_SESSION['list_channel_url']);
		
		
		for($i = 0; $i < count($channels_message); ++$i){
				
			$channel_id = $channels_message[$i]['channel_id'];
			$demand = $channels_message[$i]['demand'];	
			
			$update = "UPDATE " . $_SESSION['video_upload_table'] . " SET channel_demand = '$demand'
					   WHERE video_channel_id = '$channel_id'";
			$data = mysql_query($update) or die( "no update" );
				
		}		
		
	}
	
	
	// Output: course ID array if exists an array, otherwise return "false"
	private function getAllCourseID()
	{
		$course_id_array = array();
		
		$query = "SELECT * FROM " . $_SESSION['course_table'];
		$data = mysql_query($query) or die( "No Query" );	
		
		if( mysql_num_rows($data) === 0 ){
			return -1;
		}
		else{
			while( $course_id_row = mysql_fetch_array($data) ){
				$course_id_array[] = $course_id_row[0];
			}
			
		}
		
		return $course_id_array;
		
	}
	
	private function defaultChannelInfo()
	{
		
		$controller_url = $_SESSION['watch_channel_url'];
		$client_id = "";
		$view_password = "";
		$channel_id = "0";

		$coursesInfo_array = array();
		$coursesInfo_array[0]['course_id'] = "0";
		$coursesInfo_array[0]['name'] = "GENI Cinema Default Channel";
		$coursesInfo_array[0]['description'] = "GENI Cinema Default Channel";
		$coursesInfo_array[0]['school'] = "GENI";
		$coursesInfo_array[0]['department'] = "GENI";
		$coursesInfo_array[0]['total_demand'] = "";
		
		
		$channel_descp = "The GENI Cinema Splash Screen";
		$start_time = $_SESSION['date'];
		$end_time = $_SESSION['date'];
		
		$chan_msg[0] = array( 	'channel_id'		=>	$channel_id,
								'channel_descp'		=>	$channel_descp,
								'gateway_ip'		=>	"",
								'gateway_port'		=>	"",
								'start_time'		=>	$start_time,
								'end_time'			=>	$end_time
		
		);
		
		$coursesInfo_array[0]['channel_message'] = $chan_msg;
		
		return $coursesInfo_array;
		
	}
	
	public static function verifyViewPwd($course_id, $pwd)
	{
		
		if( !$course_id ) throw new Exception("Null Exception");	
		
		return PwdValidator::viewPwdCheck($course_id, $pwd);
		
	}
	
	public static function getCourseChanInfo($course_id)
	{
		
		return parent::getCourseChannelsArray($course_id, $_SESSION['course_downloadInfo']);
		
	}
	
	public static function displayUpdate()
	{
		$_SESSION['course_downloadInfo'] = Watcher::listAllAvailableCourses($_SESSION['usr_id']);
		$_SESSION['course_chanInfo'] = Watcher::getCourseChanInfo($_SESSION['course_id']);		
		
	}
	
	
	public static function listAllAvailableCourses($uid)
	{
		
		if( !$uid ) throw new Exception(" Null Exception ");

		// Always list the default course channel
		$defaultCourseInfo = self::defaultChannelInfo();
		
		// get All registed course ID
		$course_id_array = self::getAllCourseID();
		
		if( $course_id_array === -1 ) {
			$coursesInfo_array =  $defaultCourseInfo;

		}
		else{
			// update all demand
			self::storeDemandInDB();
			$coursesInfo_array = parent::buildCourseChannelInfo3DArray($course_id_array);
			$coursesInfo_array = array_merge($defaultCourseInfo, $coursesInfo_array);
			
		}
		
		//die(print_r(printDataInArray($coursesInfo_array)));
		
		return $coursesInfo_array;			
		
	}
	
	
	public static function goListCourseChannelsClientPage($course_id)
	{
		
		$_SESSION['course_id'] = $course_id;
		
		echo "<meta http-equiv=\"refresh\" content=\"0;url=../ListCourseChannelsPage.php\">";
		
	}
	
	
	public static function watchVideoChannel($controller_url, $client_id, $channel_id, $course_id, $switch_flag)
	{
		if( !$controller_url ){
			throw new Exception("Null Exception");
		}
		
		
		// fetch view-password except default channel
		$view_password = "";
		if($channel_id != 0){
			// fetch view password
			$query = "SELECT * FROM " . $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id'";
			$data = mysql_query($query) or die( "no query" );
			if( mysql_num_rows($data) === 1 ){
				$row = mysql_fetch_row($data);
				$view_password = $row[2];
			}
			else{
				die("Duplicate Course ID in database, which should never happen: <br/>" . mysql_error());
			}		
			
		}

		$uid = $_SESSION['usr_id'];
		// 1st time watching video
		if ( empty($client_id) ){
			$json_message = LinkUpController::watchChannels($controller_url, $client_id,
															$view_password,  $channel_id);
			$watch_message = LinkUpController::parseToArrayMsg($json_message);
				
			// Parse Error Message if neccesary
			EventManager::watchChannel($watch_message['code']);
				
			$_SESSION['watcher_id'] = $watch_message['client_id'];
			$_SESSION['channel_id'] = $watch_message['channel_id'];
			$_SESSION['curr_egress_gateway_ip'] = $watch_message['gateway_ip'];
			$_SESSION['curr_egress_gateway_port'] = $watch_message['gateway_port'];
			$_SESSION['view_password'] = $view_password;
		
			$egress_ip = $_SESSION['curr_egress_gateway_ip'];
			$egress_port = $_SESSION['curr_egress_gateway_port'];
			$watching_time = $_SESSION['date'];
			$video_channel = $_SESSION['channel_id'];
			
			$insert = "INSERT INTO " . $_SESSION['video_download_table'] .
					  " (egress_gw_ip, egress_gw_port, watching_time,
						 video_channel_id, uid) VALUES(
						 '$egress_ip', '$egress_port', '$watching_time',
						 '$video_channel', '$uid')";
			$data = mysql_query($insert);
		
			if( !empty($_SERVER['sql_conn']) ){
				$_SESSION['downloading_process_id'] = mysql_insert_id($_SERVER['sql_conn']);
			}
			else{
				die( "Fail to connect MySQL" );
			}
								
		}
		else{	// not 1st time watching video
			$json_message = LinkUpController::watchChannels($controller_url, $_SESSION['watcher_id'],
															$view_password,  $channel_id);
			$watch_message = LinkUpController::parseToArrayMsg($json_message);
				
			// Parse Error Message if neccesary
			EventManager::watchChannel($watch_message['code']);
				
			$_SESSION['view_password'] = $view_password;
			$_SESSION['channel_id'] = $watch_message['channel_id'];
		
			$video_channel = $_SESSION['channel_id'];
			$process_id = $_SESSION['downloading_process_id'];
			$update = "UPDATE " . $_SESSION['video_download_table'] .
					  " SET video_channel_id = $video_channel WHERE process_id = $process_id";
			$data = mysql_query($update);
						
		}
		
		// Update GENI Cinema Information
		self::displayUpdate();
		
		if($switch_flag === TRUE){
			echo "<meta http-equiv=\"refresh\" content=\"0; url=SwitchChannelPage.php\">";
		}
		else{
			echo "<meta http-equiv=\"refresh\" content=\"0; url=WatchVideoPage.php\">";
		}
		
		
	}
	
	public static function switchVideoChannels($controller_url, $client_id, $channel_id_selected, $course_id)
	{
		
		if( !$controller_url || !$client_id ){
			throw new Exception("Null Exception");
		}
		
		$switch_flag = TRUE;
		$switch_message = self::watchVideoChannel($controller_url, $client_id, $channel_id_selected, $course_id, $switch_flag);
		
		return $switch_message;		
		
	}	
	
	
}

?>
