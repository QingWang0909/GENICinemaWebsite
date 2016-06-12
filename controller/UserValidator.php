<?php

include_once 'SessionStart.php';
include_once "ConnectDatabase.php";


final class UserValidator{
	private function __construct() { }
	
	/* Not Working for now, SQL Code Injection */
	private function __input_check($input)
	{
		$value = trim($value);
		return $value;		
	}
	
	public static function logInValidate($usr_name, $pwd)
	{
		$errors = "";
		if( empty($usr_name) || empty($pwd) ){
			$errors = "user name or password can not be empty";
		}
		return $errors;		
	}
	public static function userNameValidate($usr_name)
	{
		if( empty($usr_name) ){
			$errors_name = "User name can not be empty.";
		}else{
			if( strlen($usr_name) < 3 ){
				$errors_name = $errors_name . "User name can not be less than 3 character, ";
			}else if( strlen($usr_name) > 16 ){
				$errors_name = $errors_name . " User name can not be more than 16 character, ";
			}
			if( !preg_match('/^[A-Za-z]+$/',substr($usr_name, 0, 1)) ){
				$errors_name = $errors_name . " User name must begin with letter, ";
			}
			if( !preg_match('/^[A-Za-z0-9_]+$/',$usr_name) ){
				$errors_name = $errors_name . " User name must be consist of letters, digits and _";
			}
		}
		return $errors_name;	
	} 	
	public static function pwdValidate($pwd, $confirm_pwd)
	{
		if( !$pwd || !$confirm_pwd){
			$errors_pwd = "Password fields can not be empty.";
		}else{
			if( strlen($pwd) < 6 ){
				$errors_pwd = $errors_pwd . "Password can not be less than 6 letters, ";
			}else if( strlen($pwd) > 16 ){
				$errors_pwd = $errors_pwd . " Password can not be more than 16 letters, ";
			}
			if( !preg_match('/^[A-Za-z0-9!@#\\$%\\^&\\*_]+$/', $pwd) ){
				$errors_pwd = $errors_pwd . " Password must be consist of letter, digit or !@#$%^&*_";
			}
			if( $pwd !== $confirm_pwd ){
				$errors_pwd = " Password you entered not match";
			}		
		}

		return $errors_pwd; 
	}
	public static function emailValidate($email)
	{	
		if( empty($email) ){
			$errors_email = "Email field can not be empty.";
		}
		else if( !filter_var($email, FILTER_VALIDATE_EMAIL) === true || (strlen($email) < 6) || !preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email) ){
			$errors_email = "Not a valid email format";
		}
		return $errors_email;		
	}
	public static function accountTypeValidate($account_type)
	{
		if( !$account_type ){
			$errors_account = "Needs to select account type";
			return $errors_account;
		}
	}
	public static function viewerPwdCheck($course_name, $school, $department, $view_password)
	{	
		
		$query = " SELECT * FROM " . $_SESSION['video_table'] . 
				 " WHERE course_name = " . "'$course_name'" . " AND " .
				 " dept = " . "'$department'" . " AND " .
				 " school = " . "'$school'"		
				;
		$data = mysql_query($query) or die( "no query" );
		

		if( mysql_num_rows($data) == 1 ){
			$row = mysql_fetch_row($data);
			if( strcmp($row[9], $view_password) ){
				return -1;		// password does NOT match
			}else{
				return 1;		// password match
			}
			
		}else{
			return -99;			// duplicate records
		}
			
		
	}

}
?>
