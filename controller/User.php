<?php
/* User.php
 * 1) This Class handles different video events based on different user behaviors on each page
 * 2) All neccessary user information will be fetched from webpage, and will be sent to GENI Cinema Controller
 * 3) Different event APIs is defined here for re-use purpose
 * 
 */

include_once 'SessionStart.php';
include_once "FunctionsForDatabase.php";
include_once "LinkUpController.php";
include_once "ConnectDatabase.php";
include_once "EventManager.php";
include_once "PwdValidator.php";
include_once 'PwdHasher.php';


class User 
{
	private function __construct() { }
	
	public static function logout()
	{	
		// a GENI Cinema client	
		if( isset($_SESSION['usr_id']) ){
			
			if( isset($_SESSION['watcher_id']) ){
				$json_message = LinkUpController::removeClient($_SESSION['remove_client_url'], $_SESSION['watcher_id']);
				$disconnect_message = LinkUpController::parseToArrayMsg($json_message);
			}
			
			session_unset();
			session_destroy();
			
		}
		// a visitor
		else{ 
			session_unset();
			session_destroy();
		}

		echo "<meta http-equiv=\"refresh\" content=\"0; url=../HomePage.php\">";
		
	}
	
	
	public static function login($usr_name)
	{
		
		if(!$usr_name) throw new Exception("Null Exception");
		
		$query = "SELECT * FROM " . $_SESSION['user_table'] . " WHERE user_name = " . "'$usr_name'";
		
		$data = mysql_query($query) or die( "no query" );
	
		if( !$data ){
			die ( "Fail to connect MySQL: <br/>" . mysql_error() );
		}else{
			if( mysql_num_rows($data) === 1 ){
				$row = mysql_fetch_row($data); 
	
				$_SESSION['usr_id'] = $row[0];
				$_SESSION['usr_name'] = $row[1];
				$_SESSION['usr_type'] = $row[7];
				
				echo "<meta http-equiv=\"refresh\" content=\"0; url=UserPage.php\">";
				
			}else{
				die ( "Duplicate User Name in database, something wrong before query: <br/>" . mysql_error() );
			}
		}
		
	}
	
	
	public static function signUp($user_name, $password, $email, $signup_time, $user_type, $fname, $lname)
	{
		
		
		if( !$user_name || !$password || !$email || !$signup_time || !$user_type ){
			throw new Exception("Null Exception");
		}
		
		$password_hash = PwdHasher::generateHashCode($password); 
		$password = "";
		
		$insert = "INSERT INTO "
				. $_SESSION['user_table'] .
				" (user_name, password, email, signup_time, fname, lname, type) VALUES(
				'$user_name', '$password_hash', '$email', '$signup_time', '$fname', '$lname', '$user_type'
				)";
		$data = mysql_query($insert) or die( "no insert" );
		
		if( !empty($_SERVER['sql_conn']) ){
			$last_id = mysql_insert_id($_SERVER['sql_conn']);
			$_SESSION['usr_id'] = $last_id;
			
		}
		else{
			die( "Fail to connect MySQL" );
		
		}
		
		$query1 = "SELECT * FROM " . $_SESSION['user_table'] . " WHERE user_name = " . "'$user_name'";
		$data1 = mysql_query($query1) or die( "no query" );
		
		if( mysql_num_rows($data1) === 1 ){	
			$row = mysql_fetch_row($data1);
			
			$_SESSION['usr_name'] = $row[1];
			$_SESSION['usr_type'] = $row[7];
			
			echo "<meta http-equiv=\"refresh\" content=\"0;url=../UserPage.php\">";
			
		}else{
			die ( "Fail to connect MySQL: <br/>" . mysql_error() );
		}
		
	}
	
	
	public static function usrnameAvailable( $usr_name )
	{
		
		// one case: no data yet in user table, could not select here
		$query = "SELECT * FROM " . $_SESSION['user_table'] . " WHERE user_name = " . "'$usr_name'";
		$data = mysql_query($query) or die( "no query" );
		
		if(!$data){
			die ( "Fail to connect database: <br>" . mysql_error() );
		}
		else{
			$row = mysql_fetch_row($data);
			if($row[0] == 0){
				return  1;	// user name available
			}
			else if($row[0] == 1){
				return -1;	// user name NOT available
			}
		}		
		
	}

	public static function passwordCheck($usr_name, $pwd)
	{
		
		$query = "SELECT * FROM " . $_SESSION['user_table'] . " WHERE user_name = " . "'$usr_name'";
		$data = mysql_query($query);
		
		if( mysql_num_rows($data) === 0 ){
			return -1;
		}

		if( mysql_num_rows($data) === 1 ){
			$row = mysql_fetch_row($data);
			$hashcode = $row[2];
	
			if ( (PwdHasher::hashcodeCheck($hashcode, $pwd)) === -1 ){
				$pwd = "";
				return -1;
			}
			else{
				$pwd = "";
				return 1;
			}
		}
	}
	
	/**
	 *  Build Up Course - Channel Hirerachy Structure
	 *	  Input:  current "live" course ID array
	 *    Output: 3D Array to represent All Course & Channel info in Hirerachy Stucture
	 */
	public function buildCourseChannelInfo3DArray($course_id_array)
	{
	
		if( !$course_id_array ) throw new Exception("Null Exception");
	
		$channel_id_array = array();
		$channel_info_array = array();
		$result_arr = array();
		$total_demand = 0;
		
		for( $i = 0; $i < count($course_id_array); ++$i ){
	
			// fetch each course info
			$query = "SELECT * FROM " . $_SESSION['course_table'] . " WHERE course_id = " . "'$course_id_array[$i]'";
			$data = mysql_query($query) or die( "no query" );
	
			if( mysql_num_rows($data) === 1 ){
				$row = mysql_fetch_row($data);
				$course_name = $row[1];
				$course_descp = $row[6];
				$school = $row[4];
				$department= $row[5];
				$prof_name = $row[7];
				$course_status = $row[8];
				$expire_time = $row[10];	
			}
			else{
				die("Duplicate Course ID in database, which should never happen: <br/>" . mysql_error());
					
			}
	
			$query = "SELECT * FROM " . $_SESSION['video_table'] . " WHERE video_status = 1 " . "AND course_id = $course_id_array[$i]";
			$data  = mysql_query($query) or die( "no query" );
	
			$channel_num = mysql_num_rows($data);
			if( $channel_num === 0 ){
				$result_arr[] = array(
										'course_id'			=>  $course_id_array[$i],
										'name'				=>	$course_name,
										'description'		=>	$course_descp,
										'school'			=>  $school,
										'department'		=>	$department,
										'professor'			=>	$prof_name,
										'course_status'		=>	$course_status,
										'expire_date'		=> 	$expire_time,
										'total_demand'		=> 	$total_demand,
										'channel_message'	=>	$channel_info_array
									);
	
			}
			else{
				while( $process_id_row = mysql_fetch_array($data) ){
					$process_id_array[] = $process_id_row[4];
				}
				
				for( $j = 0; $j < count($process_id_array); ++$j ){
	
					// fetch each channel info
					$query = "SELECT * FROM " . $_SESSION['video_upload_table'] . " WHERE process_id = " . "'$process_id_array[$j]'";
					$data = mysql_query($query) or die( "no query" );
	
					if( mysql_num_rows($data) === 1 ){
						$row = mysql_fetch_row($data);
						$channel_id = $row[7];
						$channel_descp = $row[2];
						$ingress_ip = $row[3];
						$ingress_port = $row[4];
						$start_time = $row[5];
						$end_time = $row[6];
						$demand = $row[8];
					}
					else{
						die("Duplicate Process ID in database, which should never happen: <br/>" . mysql_error());
					}
	
	
					$channel_message = array(	'channel_id'	=>	$channel_id,
												'channel_descp'	=>	$channel_descp,
												'i_gateway_ip'	=> 	$ingress_ip,
												'i_gateway_port'=>	$ingress_port,
												'start_time'	=>  $start_time,
												'end_time'		=>	$end_time,
												'demand'		=>	$demand
											);
	
					$channel_info_array[] = $channel_message;
					$total_demand += $demand;
				}
	
				$result_arr[] = array(
										'course_id'			=>  $course_id_array[$i],
										'name'				=>	$course_name,
										'description'		=>	$course_descp,
										'school'			=>  $school,
										'department'		=>	$department,
										'professor'			=>	$prof_name,
										'course_status'		=>	$course_status,
										'expire_date'		=> 	$expire_time,
										'total_demand'		=> 	$total_demand,
										'channel_message'	=>	$channel_info_array
									 );
	
				// clean the current temprary array for next interation
				$process_id_array = NULL;
				unset($process_id_array);
				$channel_info_array = NULL;
				unset($channel_info_array);
	
			}
	
		}
		
		
		return  $result_arr;
	}
	
	
	/**
	 *  Get one Course - Channel Elements from Course - Channel Array
	 *	  Input:  Selected Course ID, Whole 3D Course - Channel Array
	 *    Output: Selected Course - Channels Information
	 */
	public function getCourseChannelsArray($course_id, $coursesInfo_array)
	{
	
		$course_chanInfo = array();
		for( $i = 0; $i < count($coursesInfo_array); ++$i ){
			if( $coursesInfo_array[$i]['course_id'] === $course_id ){
				$course_chanInfo = $coursesInfo_array[$i];
				break;
			}
	
		}
		
		return $course_chanInfo;
	
	}

	/**
	 *  Get one Channel Elements from a Course - Channel Elements
	 *	  Input:  Selected Channel ID, One Selected Course - Channel Structure
	 *    Output: Selected Channel Infomation
	 */
	public function getChannelArray($channel_id, $course_chanInfo)
	{
		if(!$channel_id) throw new Exception("Null Exception");
	
		$channel_Info = array();
		for( $i = 0; $i < count($course_chanInfo['channel_message']); ++$i ){
			if( $course_chanInfo['channel_message'][$i]['channel_id'] === $channel_id ){
				$channel_Info = $course_chanInfo['channel_message'][$i];
				break;
			}
	
		}
	
		return $channel_Info;
	
	}
			
	
}

?>
