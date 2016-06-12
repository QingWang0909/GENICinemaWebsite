<?php
/* ErrorMsg.php
 * 1) This class defines error-messages for each cases, including GENI Cinema Controller Communication events and other Error events
 * 
 * 2) GENI Cinema Controller Error-events are also defined on: 
 * https://github.com/rizard/geni-cinema/blob/master/src/main/java/net/floodlightcontroller/genicinema/web/JsonStrings.java
 * 
 * 3) Should make ErrorMsg.php and JsonString.java sync up each other
 * 
 */

include_once 'SessionStart.php';

final class ErrorMsg{
	private function __construct() { }
	
	public static function printMsg($error_msg){
		echo '<h3>' . "Error Message is: " . '</h3>';
		echo '<br/>' . var_dump($error_msg['result']) . '<br/>';
		echo '<br/>' . var_dump($error_msg['message']) . '<br/>';
	}
	/* Below is different error-events that received by GENI Cinema Controller */
	// 0
	public static function complete(){
		echo '<script language="javascript">';
		echo 'alert("Request completed successfully. Please see JSON response for relevant information.")';
		echo '</script>';		
	}
	// 1
	public static function noChannelsAvailable(){
		echo '<script language="javascript">';
		echo 'alert("There are no Channels available at this time. Please check back later.")';
		echo '</script>';
	}
	// 2
	public static function incorrectJsonFields(){
		echo '<script language="javascript">';
		echo 'alert("Did not receive all expected JSON fields in request.")';
		echo '</script>';
	}
	// 3	
	public static function clientIdParseError(){
		echo '<script language="javascript">';
		echo 'alert("A client ID was provided, but it could not be parsed. Please check your client ID and try again.")';
		echo '</script>';
	}		
	// 4
	public static function clientIdNotFound(){
		echo '<script language="javascript">';
		echo 'alert("The specified client ID cannot be found. Please check your client ID and try again.")';
		echo '</script>';
	}
	// 5
	public static function egressGatewayNotFound(){
		echo '<script language="javascript">';
		echo 'alert("Could not lookup egress Gateway from VLCStreamServer. Is GC in an inconsistent state?")';
		echo '</script>';
	}	
	// 6
	public static function clientIpAllZeros(){
		echo '<script language="javascript">';
		echo 'alert("Client IP was detected as 0.0.0.0 (should never happen). Is there an issue with the HTTP client?")';
		echo '</script>';
	}
	// 7	
	public static function clientIpParseError(){
		echo '<script language="javascript">';
		echo 'alert("A client IP could not be parsed from the HTTP header. That is odd.")';
		echo '</script>';
	}	
	// 8
	public static function ingressGatewayUnavailable(){
		echo '<script language="javascript">';
		echo 'alert("GENI Cinema is experiencing high load. Could not find an available GENI Cinema Ingress Gateway.")';
		echo '</script>';
	}	
	// 9
	public static function ingressVLCStreamServerUnavailable(){
		echo '<script language="javascript">';
		echo 'alert("GENI Cinema is experiencing high load. Could not allocate a new ingress stream to the GENI Cinema Service.")';
		echo '</script>';
	}
	// 10
	public static function channelAddError(){
		echo '<script language="javascript">';
		echo 'alert("Could not add the allocated channel to the GENI Cinema Service. This should never happen.")';
		echo '</script>';
	}	
	// 11
	public static function channelIdParseError(){
		echo '<script language="javascript">';
		echo 'alert("Could not parse specified Channel ID. Please provide a positive, integer Channel ID.")';
		echo '</script>';
	}	
	// 12
	public static function channelIdUnavailable(){
		echo '<script language="javascript">';
		echo 'alert("The Channel ID specified is not available as this time.")';
		echo '</script>';
	}
	// 13
	public static function adminPasswordIncorrect(){
		echo '<script language="javascript">';
		echo 'alert("The admin password entered does not match that of the Channel ID specified.")';
		echo '</script>';
	}	
	// 14
	public static function viewPasswordIncorrect(){
		echo '<script language="javascript">';
		echo 'alert("The view password entered does not match that of the Channel ID specified.")';
		echo '</script>';
	}	
	// 15
	public static function egressGatewayUnavailable(){
		echo '<script language="javascript">';
		echo 'alert("GENI Cinema is experiencing high load. Could not find an available GENI Cinema Egress Gateway.")';
		echo '</script>';
	}	
	// 16
	public static function egressVLCStreamServerUnavailable(){
		echo '<script language="javascript">';
		echo 'alert("GENI Cinema is experiencing high load. Could not allocate a new egress stream to the GENI Cinema Service.")';
		echo '</script>';
	}	
	// 99
	public static function switchesNotReady(){
		echo '<script language="javascript">';
		echo 'alert("All switches are not connected to the GENI Cinema Service. Please try again or contact kwang@clemson.edu for assistance (jk).")';
		echo '</script>';
	}	
	
	/* Below is other error events */
	public static function noConnectionController(){
		echo '<script language="javascript">';
		echo 'alert("No Network Connection Between Website and Floodlight Server Right Now.")';
		echo '</script>';		
		
	}
	
}

?>