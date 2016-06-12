<?php 
/**
 * This PHP file responses for the connection between Website and Web Database
 * 
 */


include_once 'SessionStart.php';

###################
#    Database     #
###################
$_SERVER['db_server'] = "localhost";
$_SERVER['db_server_usrname'] = "root";
$_SERVER['db_server_pwd'] = "wangOF10";
$_SERVER['db_name'] = "GENI_Cinema_New";

$_SERVER['sql_conn'] = mysql_connect($_SERVER['db_server'], $_SERVER['db_server_usrname'], $_SERVER['db_server_pwd']) or die( mysql_error() );

mysql_select_db($_SERVER['db_name'], $_SERVER['sql_conn']) or die ( mysql_error() );	

?>
