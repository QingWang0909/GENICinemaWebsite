<?php
include_once 'SessionStart.php';
include_once 'ConnectDatabase.php';


final class CourseValidator
{
	private function __construct(){ }
	
	public static function coursenameValidate($course_name)
	{
		if( empty($course_name) ){
			return "Course name can not be empty";
		}
		
		if( strlen($course_name) > 50 ){
			return "Course name is too long";
		}
		
		if( !preg_match('/^[A-Za-z]+$/',substr($course_name, 0, 1)) ){
			return " Course name must begin with letter, ";
		}
		
	}
	public static function schoolValidate($school)
	{
		if( empty($school) ){
			return "School name can not be empty";
		}
		
		if( strlen($school) > 50 ){
			return "School name is too long";
		}
		
		if( !preg_match('/^[A-Za-z]+$/', $school) ){
			return "School name must be consist of letters";
		}
		
	}
	public static function deptValidate($dept)
	{
	
		if( empty($dept) ){
			return "Department name can not be empty";
		}
		
		if( strlen($dept) > 50 ){
			return "Deapartment name is too long";
		}
		
		if( !preg_match('/^[A-Za-z]+$/', $dept) ){
			return " Department name must be consist of letters";
		}
	
	}
	public static function pwdValidate( $pwd, $pwd_confirm )
	{

		if( strlen($pwd) > 16 ){
			return "Password can not be more than 16 letters";
		}
		
		if( !empty($pwd) ){
			if( !preg_match('/^[A-Za-z0-9!@#\\$%\\^&\\*_]+$/', $pwd) ){
				return " Password must be consist of letter, digit or !@#$%^&*_";
			}
		}
		
		if( $pwd !== $pwd_confirm ){
			return "The 'password' and 'confirmed password' not match";
			
		}
		
	}
	public static function profnameValidate($prof_name)
	{
		if( strlen($prof_name) > 50 ){
			return "Professor name is too long";
		}
		
	}

	
}

?>