<?php 

include_once 'SessionStart.php';
include "ConnectDatabase.php";
include_once './controller/PwdHasher.php';

###################
# MISC functions  #
###################
/* prevent Code Injection into SQL database */
// something wrong here
function input_check($input){
	$input = stripslashes($input);
	$input = mysql_real_escape_string($input);
}

#######################
# Tools for database  #
#######################
/* For Debugging Purpose */
function printSQLCmd($sql_cmd){
	echo '<h3>' . "The SQL Command you type-in is: " . '</h3>';
	echo '<br/>' . print_r($sql_cmd, true) . '<br/>';	
	
}
function printDataInSession(){
	echo '<h3>' . "Data In Session Now: " . '</h3>';
	echo '<pre>' . print_r($_SESSION, true) . '</pre>';
	
}
function printDataInArray($arr){
	echo '<h3>' . "Data In Array Now: " . '</h3>';
	echo '<pre>' . print_r($arr, true) . '</pre>';

}
function printDataInCookie(){
	echo '<h3>' . "Data In Cookie Now: " . '</h3>';
	echo '<pre>' . print_r($_COOKIE, true) . '</pre>';

}
function printDataInServer(){
	echo '<h3>' . "Data In Server Now: " . '</h3>';
	echo '<pre>' . print_r($_SERVER, true) . '</pre>';

}
function printDataInEnv(){
	echo '<h3>' . "Data In Env Now: " . '</h3>';
	echo '<pre>' . print_r($_ENV, true) . '</pre>';

}
function printDataInGet(){
	echo '<h3>' . "Data In Get Now: " . '</h3>';
	echo '<pre>' . print_r($_GET, true) . '</pre>';

}
function printDataInPOST(){
	echo '<h3>' . "Data In POST Now: " . '</h3>';
	echo '<pre>' . print_r($_POST, true) . '</pre>';
	
}
function printJsonMsg($json_message){
	//$json_string = json_encode($json_message, JSON_PRETTY_PRINT);
	echo '<h3>' . "JSON Message that Controller gave back to website: " . '</h3>';
	echo '<br/>' . print_r($json_message, true) . '<br/>';

}
function printArrayMsg($array_message){
	echo '<h3>' . "Array Message that Controller gave back to website: " . '</h3>';
	echo '<br/>' . print_r($array_message, true) . '<br/>';

}
?>
