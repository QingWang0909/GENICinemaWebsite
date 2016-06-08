<?php

include_once 'SessionStart.php';
include_once "FunctionsForDatabase.php";
include_once "ErrorMsg.php";

final class LinkUpController{
	private function __construct() { }

	/* Not Working for now */
	private function __input_check($input){
		$value = trim($value);
		return $value;
	}
	
	public static function addChannel($controller_url, $video_name, $video_dscp, $video_pwd, $admin_pwd){
		
		$url = curl_init($controller_url);
		$data = array(
				'name'				=>	$video_name,
				'description'		=>	$video_dscp,
				'view_password'		=>	$video_pwd,
				'admin_password'	=>	$admin_pwd
				);
		$json_data = json_encode($data);
		
		curl_setopt($url, CURLOPT_POST, 1);
		curl_setopt($url, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($url);
		
		$httpcode = curl_getinfo($url, CURLINFO_HTTP_CODE);
		curl_close($url);
		
		if($httpcode == 200){
			return $result;
		}
		else{
			EventManager::networkConnectionError();
			//die("Something wrong b/w website and controller");
		}
		
	}
	public static function listChannels($controller_url){
		
		$url = curl_init($controller_url);
		curl_setopt($url, CURLOPT_URL, $controller_url);
		curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($url);
			
		$httpcode = curl_getinfo($url, CURLINFO_HTTP_CODE);
		curl_close($url);
		
		if($httpcode == 200){
			return $result;
		}
		else{
			EventManager::networkConnectionError();
			//die("Something wrong b/w website and controller");
		}
	}
	public static function watchChannels($controller_url, $client_id, $view_password, $channel_id){
		
		$url = curl_init($controller_url);
		$data = array(
				'client_id'			=>	$client_id,
				'view_password'		=>	$view_password,
				'channel_id'		=>	$channel_id,
				);
		$json_data = json_encode($data);
		
		curl_setopt($url, CURLOPT_POST, 1);
		curl_setopt($url, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($url);
		
		$httpcode = curl_getinfo($url, CURLINFO_HTTP_CODE);
		curl_close($url);
		
		if($httpcode == 200){
			return $result;
		}
		else{
			EventManager::networkConnectionError();
		}		
			
	}	
	public static function removeChannel($controller_url, $channel_id, $admin_password){
		
		$url = curl_init($controller_url);
		$data = array( 'channel_id'		=> 	$channel_id,
					   'admin_password' => 	$admin_password 
				
					  );
		$json_data = json_encode($data);
		
		curl_setopt($url, CURLOPT_POST, 1);
		curl_setopt($url, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($url);
		
		$httpcode = curl_getinfo($url, CURLINFO_HTTP_CODE);
		curl_close();
		
		if($httpcode == 200){
			return $result;
		}
		else{
			EventManager::networkConnectionError();
			//die("Something wrong b/w website and controller");
		}
				
	}
	public static function removeClient($controller_url, $client_id){
		
		$url = curl_init($controller_url);
		$data = array( 'client_id'	=>	$client_id );
		
		$json_data = json_encode($data);
		
		curl_setopt($url, CURLOPT_POST, 1);
		curl_setopt($url, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($url);
		
		$httpcode = curl_getinfo($url, CURLINFO_HTTP_CODE);
		curl_close($url);
		
		if($httpcode == 200){
			return $result;
		}
		else{
			EventManager::networkConnectionError();
		}
		
	}
	public static function modifyChannel($controller_url, $channel_id, $admin_password,
										 $new_course_name, $new_description, $new_view_password, $new_admin_password){
		
		$url = curl_init($controller_url);
		$data = array( 	'channel_id'		=>	$channel_id,
						'admin_password'	=>	$admin_password,
						'new_name'			=>	$new_course_name,
						'new_description'	=>	$new_description,
						'new_view_password' =>	$new_view_password,
						'new_admin_password'=>	$new_admin_password
				);
		$json_data = json_encode($data);
		
		curl_setopt($url, CURLOPT_POST, 1);
		curl_setopt($url, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($url);
		
		$httpcode = curl_getinfo($url, CURLINFO_HTTP_CODE);
		curl_close($url);
		
		if($httpcode == 200){
			return $result;
		}
		else{
			EventManager::networkConnectionError();
		}
		
	}	
 	public static function parseToArrayMsg($json_message){
 		$result = json_decode($json_message, true);	
 		
 		return $result;
 	}


}

?>