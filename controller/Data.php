<?php
/* Data.php
 * 	1) This file defines global constants that will be used in GENI Cinema Website
 *  2) All global constants will be stored in $SESSION[ ]
 *  3) This file ONLY need to be include once in HomePage.php
 *  
 *  NOTE: This file ONLY include once in Home.php
 */

include_once 'SessionStart.php';


###########################################################
#  Variable Stores in Session, passing between PHP files  #
###########################################################
// user variables
$_SESSION['usr_id'] = "";
$_SESSION['usr_name'] = "";
$_SESSION['usr_type'] = "";
$_SESSION['watcher_id'] = "";
$_SESSION['producer_id'] = "";
// course 
$_SESSION['course_id'] = "";				// does NOT make sense if one people(student or professor) could access multiple video within one session
											// in different session, it would be possible that one account have access for multiple videos
$_SESSION['course_id_modify'] = "";
$_SESSION['course_name'] = "";
$_SESSION['course_descp'] = "";
$_SESSION['prof_name'] = "";
$_SESSION['course_status'] = "";
$_SESSION['course_uploadInfo'] = "";
$_SESSION['cached_course_uploadInfo'] = "";
$_SESSION['course_downloadInfo'] = "";
$_SESSION['course_chanInfo'] = "";
// video channel
$_SESSION['channel_id'] = "";				// currently for both uploading & downloading process, keep in mind if this causing a problem
$_SESSION['channel_id_modify'] = "";
// TODO: Video Status may indicate different cases
$_SESSION['video_status'] = "";				// -1: dead, 1: live, 0 
											
$_SESSION['curr_egress_gateway_ip'] = "";
$_SESSION['curr_egress_gateway_port'] = "";
$_SESSION['channel_Info'] = "";
// others
$_SESSION['date']  = date("Y-m-d H:i:s");		
$_SESSION['idleTimeValid'] = 300;				// TODO: observing timer bug
$_SESSION['refreshTimeInterval'] = 320;
##############################
#  REST API in Web Service   #
##############################
$_SESSION['add_channel_url'] = "http://128.163.232.18:8080/wm/geni-cinema/add-channel/json";
$_SESSION['watch_channel_url'] = "http://128.163.232.18:8080/wm/geni-cinema/watch-channel/json";
$_SESSION['list_channel_url'] = "http://128.163.232.18:8080/wm/geni-cinema/list-channels/json";
$_SESSION['remove_client_url'] = "http://128.163.232.18:8080/wm/geni-cinema/remove-client/json";	
$_SESSION['remove_channel_url'] = "http://128.163.232.18:8080/wm/geni-cinema/remove-channel/json";
$_SESSION['modify_channel_url'] = "http://128.163.232.18:8080/wm/geni-cinema/modify-channel/json";
##############################
#  Path to Redirect Pages    #
##############################
$_SESSION['server_ip'] = "128.163.232.19";
$_SESSION['logout_url'] = "http://128.163.232.19/controller/Logout.php";
$_SESSION['homepage_url'] = "http://128.163.232.19/HomePage.php";
$_SESSION['index_url'] = "http://128.163.232.19/index.php";
$_SESSION['mainpage_url'] = "http://128.163.232.19/UserPage.php";
######################
#    MySQL Tables    #
######################
$_SESSION['user_table'] = "user";
$_SESSION['course_table'] = "course";
$_SESSION['video_table'] = "video";
$_SESSION['video_download_table'] = "downloading_process";	// watch_channel process
$_SESSION['video_upload_table'] = "upload_channel_process";

?>
