<?php

include_once 'SessionStart.php';
include_once "FunctionsForDatabase.php";
include_once "LinkUpController.php";
include_once "ConnectDatabase.php";
include_once "EventManager.php";
include_once "PwdValidator.php";
include_once "UpdateSessionInfo.php";

final class Producer extends User 
{
	private function __construct() { }
	
		
	public static function verifyAdminPwd($course_id, $uid, $pwd)
	{

		if(!$course_id || !$uid ){
			throw new Exception("Null Exception");
		}
		
		return PwdValidator::adminPwdCheck($pwd, $uid, $course_id);
		
	}
	
	/* Course Activities */
	// TODO: special character handle
	public static function signUpCourseInfo($uid, $course_name, $course_dscp, $school, $department, 
											$view_password, $admin_password, $prof_name, $start_time, $end_time)
	{

		if( !$uid || !$course_name || !$school || !$department || !$prof_name || !$start_time || !$end_time ){
			throw new Exception("Null Exception");
		}
			
		$course_status = "CLOSE";		// by default
			
		$insert_course = "INSERT INTO "
						. $_SESSION['course_table'] .
						" (course_name, view_pwd, admin_pwd, school, dept,
						course_descp, prof_name, course_status, start_time, end_time, uid) VALUES(
						'$course_name', '$view_password', '$admin_password',
						'$school', '$department',
						'$course_dscp', '$prof_name', '$course_status', '$start_time', '$end_time', '$uid'
						)";
		
		//die( printSQLCmd($insert_course) );
		
		//$insert_course = mysql_real_escape_string($insert_course);
		
		$data = mysql_query($insert_course) or die( "no insert" );
		if( !empty($_SERVER['sql_conn']) ){
			$course_id = mysql_insert_id($_SERVER['sql_conn']);
				
			$_SESSION['course_id'] = $course_id;
			$_SESSION['course_status'] = 1;
			$_SESSION['course_name'] = $course_name;
			$_SESSION['course_descp'] = $course_dscp;
			$_SESSION['school'] = $school;
			$_SESSION['dept'] = $department;
			$_SESSION['prof_name'] = $prof_name;
							
		}
		else{
			die( "Fail to connect MySQL" );
		}
		
	}
	
	// Input:  User ID
	// Output: Course--Channel Hirerachy Array based on current DB record
	public static function listUploadedCourses($uid)
	{
		
		if(!$uid) throw new Exception("Null Exception");;
		
		$course_id_array = array();
		
		$query = "SELECT * FROM " . $_SESSION['course_table'] . " WHERE uid = $uid";
		$data = mysql_query($query) or die( "no query" );
		
		$course_num = mysql_num_rows($data);
		
		if( $course_num === 0 ){
			return $result_arr;
		}
		while( $course_id_row = mysql_fetch_array($data) ){
			$course_id_array[] = $course_id_row[0];
		}
		
		return parent::buildCourseChannelInfo3DArray($course_id_array);		
		
	}
	
	// Input:  Course ID
	// Output: One Course - Channel Element in Array, based on Course-Channel Array
	public static function getCourseChanInfo($course_id)
	{

		return parent::getCourseChannelsArray($course_id, $_SESSION['course_uploadInfo']);
		
	}
	
	
	public static function listCourseInfo($course_id)
	{
		
		if( !$course_id ) throw new Exception("Null Exception");
		
		$query = "SELECT * FROM " . $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($query) or die( "no query" );
		
		//die(printSQLCmd($query));
		
		if( mysql_num_rows($data) === 1 ){
			$row = mysql_fetch_row($data);
			$course_name = $row[1];
			$school = $row[4];
			$dept = $row[5];
			$course_descp = $row[6];
			$prof_name = $row[7];
			$expire_date = $row[10];
		}
		else{
			die("Duplicate Course ID in database, which should never happen: <br/>" . mysql_error());
		}
		
		$course_info = array( 	'name'			=>	$course_name,
								'school'		=>	$school,
								'department'	=>	$dept,
								'description'	=>	$course_descp,
								'profName'		=>	$prof_name,
								'expire_date'	=>	$expire_date
		);
		
		return $course_info;
		
	}

	public static function goModifyCoursePage($course_id)
	{
		
		if( !$course_id ) throw new Exception("Null Exception");
		
		$_SESSION['course_id_modify'] = $course_id;
		$_SESSION['course_chanInfo'] = parent::getCourseChannelsArray($course_id, $_SESSION['course_uploadInfo']);
		
		echo "<meta http-equiv=\"refresh\" content=\"0;url=../ModifyCourseInfoPage.php\">";
		
		
	}
	
	
	public static function naiveModifyCourseInfo($course_id, $new_course_name, $new_course_descp, $new_school, $new_dept,
			$new_prof_name, $new_view_pwd, $new_admin_pwd, $modify_channel_url)
	{
		
		if( !$course_id || !$new_course_name || !$new_course_descp || !$new_school || !$new_dept || !$new_prof_name || !$modify_channel_url  ){
			throw new Exception("Null Exception");
		}
		
		// get current admin_pwd before it updated
		$query = "SELECT * FROM " . $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($query) or die( "no query" );
		if( mysql_num_rows($data) === 1 ){
			$row = mysql_fetch_row($data);
			$curr_admin_pwd = $row[3];
		}
		else{
			die("Duplicate Course ID in database, which should never happen: <br/>" . mysql_error());
		}
		
		// update course info in Web DB
		$update_course = "UPDATE ". $_SESSION['course_table'] .
					 	 " SET course_name = '$new_course_name',
						 view_pwd = '$new_view_pwd',
						 admin_pwd = '$new_admin_pwd',
						 school = '$new_school',
						 dept = '$new_dept',
						 course_descp = '$new_course_descp',
						 prof_name = '$new_prof_name'
			 			 WHERE course_id = '$course_id'";
		$data = mysql_query($update_course) or die( "no update" );
		
		// query channel number
		$query = "SELECT * FROM " . $_SESSION['video_upload_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($query) or die( "no query" );
		$channel_num = mysql_num_rows($data);
		
		// Channel REST API for admin password and course name
		if( $channel_num > 0 ){
			for($i = 0; $i < $channel_num; $i++){
				$channel_id = $_SESSION['course_chanInfo']['channel_message'][$i]['channel_id'];
							
				$query = "SELECT * FROM " . $_SESSION['video_upload_table'] . " WHERE course_id = " . "'$course_id' AND video_channel_id = '$channel_id'";
				$data = mysql_query($query) or die( "no query" );
				if( mysql_num_rows($data) === 1 ){
				$row = mysql_fetch_row($data);
				$chan_descp = $row[2];
				}
				else{
				die("No query in Channel Table: <br/>" . mysql_error());
				}
						
					$json_message = LinkUpController::modifyChannel($modify_channel_url, $channel_id,
					$curr_admin_pwd, $new_course_name, $chan_descp, $new_view_pwd, $new_admin_pwd);
					$array_message = LinkUpController::parseToArrayMsg($json_message);
						
					EventManager::addChannel($array_message[0]['code']);
					
			}
		}
		
		
	}
	
	public static function extendDate($course_id, $new_expire_date)
	{
		if( !$course_id || !$new_expire_date ) throw new Exception("Null Exception");
		
		$update = "UPDATE " . $_SESSION['course_table'] . " SET end_time = '$new_expire_date' WHERE course_id = '$course_id' ";
		$data = mysql_query($update) or die( "no update" );

	}
	
	/**
	 * This method will release all course channel resourecs in GC system
	 * 
	 * @param $course_id
	 * @param $remove_channel_url
	 * @param $admin_password
	 * @throws Exception
	 */
	public static function closeCourse($course_id, $remove_channel_url, $admin_password)
	{
		
		if( !$course_id || !$remove_channel_url  ){
			throw new Exception("Null Exception");
		}
		
		$channel_id_array = array();
		$upload_id_array = array();
		
		$query = "SELECT * FROM " . $_SESSION['video_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($query) or die( "no query" );
		while( $row = mysql_fetch_row($data) ){
			$channel_id_array[] = $row[2];
			$upload_id_array[] = $row[4];
		}
		
		// send FL REST API
		for( $i = 0; $i < count($upload_id_array); ++$i ){
			$json_message = LinkUpController::removeChannel($remove_channel_url, $channel_id_array[$i], $admin_password);
			$remove_channelmessage = LinkUpController::parseToArrayMsg($json_message);
		
			EventManager::watchChannel($remove_channelmessage['code']);
		
			// remove record in Web DB
			$delete = "DELETE FROM ". $_SESSION['video_table'] . " WHERE upload_process_id = " . "'$upload_id_array[$i]'";
			$data = mysql_query($delete) or die( "no delete" );
		
			$delete = "DELETE FROM ". $_SESSION['video_upload_table'] . " WHERE process_id = " . "'$upload_id_array[$i]'";
			$data = mysql_query($delete) or die( "no delete" );
		
		}
		
		$course_status = "CLOSE";
		$update = "UPDATE " . $_SESSION['course_table'] . " SET course_status = '$course_status' WHERE course_id = '$course_id' ";
		$data = mysql_query($update) or die( "no update" );		
		
	}
	
	/**
	 * This method will both release all course channel resourecs and delet course information in GC system 
	 *
	 * @param $course_id
	 * @param $remove_channel_url
	 * @param $admin_password
	 * @throws Exception
	 */	
	public static function turnOffCourse($course_id, $remove_channel_url, $admin_password)
	{
		
		if( !$course_id || !$remove_channel_url  ){
			throw new Exception("Null Exception");
		}
		
		$channel_id_array = array();
		$upload_id_array = array();
		
		$query = "SELECT * FROM " . $_SESSION['video_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($query) or die( "no query" );
		while( $row = mysql_fetch_row($data) ){
			$channel_id_array[] = $row[2];
			$upload_id_array[] = $row[4];
		}
		
		// send FL REST API
		for( $i = 0; $i < count($upload_id_array); ++$i ){
			$json_message = LinkUpController::removeChannel($remove_channel_url, $channel_id_array[$i], $admin_password);
			$remove_channelmessage = LinkUpController::parseToArrayMsg($json_message);
				
			EventManager::watchChannel($remove_channelmessage['code']);
		
			// remove record in Web DB
			$delete = "DELETE FROM ". $_SESSION['video_table'] . " WHERE upload_process_id = " . "'$upload_id_array[$i]'";
			$data = mysql_query($delete) or die( "no delete" );
		
			$delete = "DELETE FROM ". $_SESSION['video_upload_table'] . " WHERE process_id = " . "'$upload_id_array[$i]'";
			$data = mysql_query($delete) or die( "no delete" );
				
		}
		$delete = "DELETE FROM ". $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($delete) or die( "no delete" );
		
	}
	
	/* Channel Activities */
	public function getCachedChanInfo($course_id)
	{
		if(!$course_id) throw new Exception("NULL Exception");
		
		$query = "SELECT * FROM " . $_SESSION['video_upload_table'] . " WHERE course_id = '$course_id' AND ingress_gw_port = -1 ";
		$data = mysql_query($query) or die( "no query" );
		
		$cached_course_chanInfo = array();
		
		while( $row = mysql_fetch_row($data) ){
			$chan_descp = $row[2];
			$start_time = $row[5];
			$end_time   = $row[6];
			
			// put new channel info into $_SESSION[]
			$channel_cache_msg = array(	'channel_description'	=>	$chan_descp,
										'start_time'			=>	$start_time,
										'end_time'				=> 	$end_time,
			);
			
			$cached_course_chanInfo[$course_id][] = $channel_cache_msg;
			
		}
		
		return $cached_course_chanInfo;
		
	}
	
	public static function addVideoChannelToCache($uid, $channel_descp, $start_time, $end_time, $course_id)
	{
	
		if( !$channel_descp  || !$start_time  || !$end_time  ){
			throw new Exception("Null Exception");
		}
		
		$ingress_gw_ip = NULL;
		$ingress_gw_port = -1;
		$video_channel_id = -1;
		
		$insert_upload = "INSERT INTO "
				. $_SESSION['video_upload_table'] .
				" (uid, channel_descp, ingress_gw_ip, ingress_gw_port, start_time, end_time, video_channel_id, course_id) VALUES(
				'$uid', '$channel_descp', '$ingress_gw_ip', '$ingress_gw_port',
				'$start_time', '$end_time',
				'$video_channel_id', '$course_id'
				)";
		
		$data = mysql_query($insert_upload) or die( "no insert" );
		
	}

	public static function removeVideoChannelToCache($course_id, $channel_descp, $uid)
	{
		
		if( !$course_id || !$channel_descp ){
			throw new Exception("Null Exception");
		}
		
		$delete = "DELETE FROM ". $_SESSION['video_upload_table'] . " WHERE uid = '$uid' AND course_id = '$course_id' AND channel_descp = '$channel_descp' LIMIT 1";
		$data = mysql_query($delete) or die( "no delete" );
		
	}
	
	public static function cancelChannelsInCache($course_id)
	{

		if(!$course_id) throw new Exception("Null Exception");
		
		$delete = "DELETE FROM " . $_SESSION['video_upload_table'] .
				  " WHERE course_id = '$course_id'";
		$data = mysql_query($delete) or die( "no delete" );
		
		
	}
	
	public static function turnOffVideoChannel($course_id, $channel_id, $remove_channel_url)
	{

		if( !$course_id || !$channel_id || !$remove_channel_url ){
			throw new Exception("Null Exception");
		}
		
		// get admin pwd
		$query = "SELECT * FROM " . $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($query) or die( "no query" );
		if( mysql_num_rows($data) === 1 ){
			$row = mysql_fetch_row($data);
			$admin_pwd = $row[3];
		}
		else{
			die("Duplicate Course ID in database, which should never happen: <br/>" . mysql_error());
		
		}
		
		// send REST API to FL
		$json_message = LinkUpController::removeChannel($remove_channel_url, $channel_id, $admin_pwd);
		$channels_message = LinkUpController::parseToArrayMsg($json_message);
		EventManager::addChannel($array_message[0]['code']);
		
		// update web DB & $_SESSION
		$delete = "DELETE FROM " . $_SESSION['video_upload_table'] . " WHERE video_channel_id = '$channel_id' AND course_id = '$course_id' LIMIT 1";
		$data = mysql_query($delete) or die( "no delete" );
		
		$delete = "DELETE FROM " . $_SESSION['video_table'] . " WHERE video_channel_id = '$channel_id' AND course_id = '$course_id' LIMIT 1";
		$data = mysql_query($delete) or die( "no delete" );
		
		// update $_SESSION
		$_SESSION['course_uploadInfo'] = self::listUploadedCourses($_SESSION['usr_id']);
		$_SESSION['course_chanInfo'] = self::getCourseChannelsArray($course_id, $_SESSION['course_uploadInfo']);
		
		return $_SESSION['cousre_chanInfo'];
		
	
	}	
	
	public static function addChannelToFL($uid, $course_id, $channel_descp, $start_time, $end_time, $add_channel_url)
	{

		if( !$uid || !$course_id ||  !$channel_descp || !$start_time || !$end_time || !$add_channel_url ){
			throw new Exception("Null Exception");
		}
		
		// fetch view & admin pwd
		$query = "SELECT * FROM " . $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($query) or die( "no query" );
		
		if( mysql_num_rows($data) === 1 ){
			$row = mysql_fetch_row($data);
			$admin_pwd = $row[3];
			$view_pwd = $row[2];
			$course_name = $row[1];
		}
		else{
			die("Duplicate Course ID in database, which should never happen: <br/>" . mysql_error());
		
		}
		
		// REST API to FL
		$video_status = 1;
		
		$json_message = LinkUpController::addChannel($add_channel_url,
				$course_name, $channel_descp,
				$view_pwd, $admin_pwd
		);
		$array_message = LinkUpController::parseToArrayMsg($json_message);
			
		EventManager::addChannel($array_message[0]['code']);
		
		$new_channel_message = array(	'channel_id'			=>	$array_message['channel_id'],
										'ingress_gateway_ip'	=> 	$array_message['gateway_ip'],
										'ingress_gateway_port'	=>	$array_message['gateway_port'],
										'result_message'		=>  $array_message['message'],
										'result_code'			=>	$array_message['code'],
										'video_status'			=>	$video_status
								);
		
		$channel_id = $new_channel_message['channel_id'];
		$ingress_gw_ip = $new_channel_message['ingress_gateway_ip'];
		$ingress_gw_port = $new_channel_message['ingress_gateway_port'];
		
		// Update Web DB
		$insert_upload = "INSERT INTO " . $_SESSION['video_upload_table'] .
						" (uid, channel_descp, ingress_gw_ip, ingress_gw_port, start_time, end_time, video_channel_id, course_id) VALUES(
						'$uid', '$channel_descp', '$ingress_gw_ip', '$ingress_gw_port',
						'$start_time', '$end_time',
						'$channel_id', '$course_id')";
		//die(printSQLCmd($insert_upload));
		$data = mysql_query($insert_upload) or die( "no insert" );
		
		if( !empty($_SERVER['sql_conn']) ){
				$process_id = mysql_insert_id($_SERVER['sql_conn']);
		}
		else{
			die( "Fail to connect MySQL" );
		}
		
		$insert_video = "INSERT INTO " . $_SESSION['video_table'] .
				" (course_id, video_channel_id, video_status, upload_process_id) VALUES(
						'$course_id', '$channel_id', '$video_status', '$process_id'
				)";
		$data = mysql_query($insert_video) or die( "no insert" );
		
		$course_status = "OPEN";
		$update = "UPDATE " . $_SESSION['course_table'] . " SET course_status = '$course_status' WHERE course_id = '$course_id' ";
		$data = mysql_query($update) or die( "no update" );
		
	}
	
	
	public static function addCacheChannelsToFL($uid, $course_id, $add_channel_url)
	{

		if( !$uid || !$course_id || !$add_channel_url ){
			throw new Exception("Null Exception");
		}
		
		// Query Channel Info & Course Info From Web Cache
		$query = "SELECT * FROM " . $_SESSION['video_upload_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($query) or die( "no query" );
		$channel_num = mysql_num_rows($data);
		
		if( $channel_num === 0 ){
			//$_SESSION['cached_course_uploadInfo'] = NULL;
			echo "<meta http-equiv=\"refresh\" content=\"0;url=../ManageCoursesPage.php\">";
		}
		
		$process_id_array = array();
		while( $process_id_row = mysql_fetch_array($data) ){
			$process_id_array[] = $process_id_row[0];
			$channel_descp_array[] = $process_id_row[2];
		}
		
		$query1 = "SELECT * FROM " . $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id'";
		$data1 = mysql_query($query1) or die( "no query" );
		
		$row = mysql_fetch_row($data1);
		if( mysql_num_rows($data1) === 1 ){
			$course_name = $row[1];
			$view_password = $row[2];
			$admin_password = $row[3];
		}
		else{
			die("Duplicate Course ID in database, which should never happen: <br/>" . mysql_error());
		}
		
		// TODO: Video Status Trigger Time
		$video_status = 1;
		
		// Send FL addChannel REST API for each Channel
		for( $i = 0; $i < count($process_id_array); ++$i ){
			$json_message = LinkUpController::addChannel($add_channel_url,
														$course_name, $channel_descp_array[$i],
														$view_password, $admin_password
														);
			$array_message = LinkUpController::parseToArrayMsg($json_message);
				
			//Parse Error Message if neccesary
			EventManager::addChannel($array_message[0]['code']);
				
			$ingress_gw_ip = $array_message['gateway_ip'];
			$ingress_gw_port = $array_message['gateway_port'];
			$video_channel_id = $array_message['channel_id'];
				
			// update tables in Web DB
			$update_course = "UPDATE " . $_SESSION['video_upload_table'] .
							 " SET ingress_gw_ip = '$ingress_gw_ip',
								   ingress_gw_port = '$ingress_gw_port',
								   video_channel_id = '$video_channel_id'
								   WHERE process_id = '$process_id_array[$i]'";
			$result = mysql_query($update_course) or die( "no update" );
				
			$insert_video = "INSERT INTO " . $_SESSION['video_table'] .
							" (course_id, video_channel_id, video_status, upload_process_id) VALUES(
							'$course_id', '$video_channel_id', '$video_status', '$process_id_array[$i]'
							)";
			$data = mysql_query($insert_video) or die( "no insert" );
								
			$channel_message = array(	'channel_id'			=>	$array_message['channel_id'],
										'ingress_gateway_ip'	=> 	$array_message['gateway_ip'],
										'ingress_gateway_port'	=>	$array_message['gateway_port'],
										'result_message'		=>  $array_message['message'],
										'result_code'			=>	$array_message['code'],
										'video_status'			=>	$video_status
									);
												
		}
		
		$course_status = "OPEN";
		$update = "UPDATE " . $_SESSION['course_table'] . " SET course_status = '$course_status' WHERE course_id = '$course_id' ";
		$data = mysql_query($update) or die( "no update" );
		
		
	}
	
	
	public static function goListCourseChannelsPage($course_id)
	{
		if(!$course_id) throw new Exception("Null Exception");
		
		$_SESSION['course_id_modify'] = $course_id;
		UpdateSessionInfo::updateSessionInfo($course_id, "");
		
		echo "<meta http-equiv=\"refresh\" content=\"0;url=../ManageCourseChannelsPage.php\">";
				
	}

	public static function goExtendExpireDatePage($course_id)
	{
		if(!$course_id) throw new Exception("Null Exception");
		
		$_SESSION['course_id_modify'] = $course_id;
		UpdateSessionInfo::updateSessionInfo($course_id, "");
		
		echo "<meta http-equiv=\"refresh\" content=\"0;url=../ExtendCourseExpireDatePage.php\">";
		
	}
	
	
	public static function modifyChannelDescp($course_id, $channel_id, $modify_channel_url, $new_channel_descp)
	{


		if(!$course_id || !$channel_id || !$modify_channel_url ){
			throw new Exception("Null Exception");
		}
		
		$_SESSION['channel_Info'] = parent::getChannelArray($channel_id, $_SESSION['course_chanInfo']);
		
		$query = "SELECT * FROM " . $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id'";
		$data = mysql_query($query) or die( "no query" );
		if( mysql_num_rows($data) === 1 ){
			$row = mysql_fetch_row($data);
			$course_name = $row[1];
			$view_password = $row[2];
			$admin_password = $row[3];
		}
		else{
			die("Duplicate Course ID in database, which should never happen: <br/>" . mysql_error());
		
		}
		
		$query = "SELECT * FROM " . $_SESSION['video_upload_table'] . " WHERE video_channel_id = " . "'$channel_id'";
		$data = mysql_query($query) or die( "no query" );
		if( mysql_num_rows($data) === 1 ){
			$row = mysql_fetch_row($data);
			$process_id = $row[0];
		}
		else{
			die("Duplicate Channel ID in database, which should never happen: <br/>" . mysql_error());
		
		}
		
		// send REST API to FL
		$json_message = LinkUpController::modifyChannel($modify_channel_url, $channel_id, $admin_password, $course_name, $new_channel_descp, $view_password, $admin_password);
		$array_message = LinkUpController::parseToArrayMsg($json_message);
		EventManager::addChannel($array_message[0]['code']);
		
		// update Web DB
		$new_channel_id = $array_message['channel_id'];
		
		$update = "UPDATE " . $_SESSION['video_upload_table'] . " SET channel_descp = '$new_channel_descp',
						      video_channel_id = '$new_channel_id'
							  WHERE process_id = '$process_id'";
		$data = mysql_query($update) or die( "no update" );
		
		$_SESSION['channel_id_modify'] = "";
		
		
	}	
	
	
}

?>
