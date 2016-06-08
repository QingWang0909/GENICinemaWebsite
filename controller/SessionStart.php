<?php
/**
 * This PHP File makes sure all web system in the same session
 * 
 * Usage: include this file on the top of each PHP file
 * 
 */

header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

/* Make Sure All Pages on Same Session */
if(session_id() == ''){
	session_start();
}


?>