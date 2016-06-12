<?php
/* EventManager.php
 * 1) This Class is really a top level receiver that handles different video events  
 * 	  based on GECNI Cinema Controller result message code  
 * 2) Different event APIs is defined for re-use purpose
 */

include_once 'SessionStart.php';
include_once "ErrorMsg.php";

final class EventManager {
	private function __construct(){ }
	
	/* List Channel Event Happens */
	public static function listChannels($result_str_code)
	{
		if($result_str_code != "0"){
			// All belows are error cases that needs to handle
			if($result_str_code === "1"){
				ErrorMsg::noChannelsAvailable();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=UserPage.php\">";
			}
			else if($result_str_code === "8"){
				ErrorMsg::ingressGatewayUnavailable();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=UserPage.php\">";
			}		
			else if($result_str_code === "9"){
				ErrorMsg::ingressVLCStreamServerUnavailable();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=UserPage.php\">";
			}	
			else if($result_str_code === "10"){
				ErrorMsg::channelAddError();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=UserPage.php\">";
			}		
			else if($result_str_code === "15"){
				ErrorMsg::egressGatewayUnavailable();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=UserPage.php\">";
			}		
			else if($result_str_code === "16"){
				ErrorMsg::egressVLCStreamServerUnavailable();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=UserPage.php\">";
			}		
			else if($result_str_code === "99"){
				ErrorMsg::switchesNotReady();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=UserPage.php\">";
			}		
		}
	}
	
	/* Watch Channel Event Happens */
	public static function watchChannel($result_code)
	{
		if($result_code != "0")
		{
	
			if($result_code === "1"){
				ErrorMsg::noChannelsAvailable();
			}
			else if($result_code === "5"){
				ErrorMsg::egressGatewayNotFound();
			}
			else if($result_code === "8"){
				ErrorMsg::ingressGatewayUnavailable();
			}
			else if($result_code === "9"){
				ErrorMsg::ingressVLCStreamServerUnavailable();
			}
			else if($result_code === "10"){
				ErrorMsg::channelAddError();
			}
			else if($result_code === "11"){
				ErrorMsg::channelIdParseError();
			}
			else if($result_code === "12"){
				ErrorMsg::channelIdUnavailable();
			}
			else if($result_code === "14"){
				ErrorMsg::viewPasswordIncorrect();
			}	
			else if($result_code === "15"){
				ErrorMsg::egressGatewayUnavailable();
			}		
			else if($result_code === "16"){
				ErrorMsg::egressVLCStreamServerUnavailable();
			}
			else if($result_code === "99"){
				ErrorMsg::switchesNotReady();
			}
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=ListAllCoursesPage.php\">";
			
		}
		
	}
	
	/* Switch Channel Event Happens */
	public static function switchChannel($result_code)
	{
		if($result_str_code === "0"){
			ErrorMsg::complete();
		}
		else{
			if($result_code === "1"){
				ErrorMsg::noChannelsAvailable();
			}
			else if($result_code === "5"){
				ErrorMsg::egressGatewayNotFound();
			}
			else if($result_code === "8"){
				ErrorMsg::ingressGatewayUnavailable();
			}
			else if($result_code === "9"){
				ErrorMsg::ingressVLCStreamServerUnavailable();
			}
			else if($result_code === "10"){
				ErrorMsg::channelAddError();
			}
			else if($result_code === "11"){
				ErrorMsg::channelIdParseError();
			}
			else if($result_code === "12"){
				ErrorMsg::channelIdUnavailable();
			}
			else if($result_code === "14"){
				ErrorMsg::viewPasswordIncorrect();
			}
			else if($result_code === "15"){
				ErrorMsg::egressGatewayUnavailable();
			}
			else if($result_code === "16"){
				ErrorMsg::egressVLCStreamServerUnavailable();
			}
			else if($result_code === "99"){
				ErrorMsg::switchesNotReady();
			}			
		}		
		
	}
	public static function addChannel($result_code)
	{
		if($result_str_code === "0"){
			ErrorMsg::complete();
		}
		else{
			if($result_code === "1"){
				ErrorMsg::noChannelsAvailable();
			}
			else if($result_code === "5"){
				ErrorMsg::egressGatewayNotFound();
			}
			else if($result_code === "8"){
				ErrorMsg::ingressGatewayUnavailable();
			}
			else if($result_code === "9"){
				ErrorMsg::ingressVLCStreamServerUnavailable();
			}
			else if($result_code === "10"){
				ErrorMsg::channelAddError();
			}
			else if($result_code === "11"){
				ErrorMsg::channelIdParseError();
			}
			else if($result_code === "12"){
				ErrorMsg::channelIdUnavailable();
			}
			else if($result_code === "14"){
				ErrorMsg::viewPasswordIncorrect();
			}
			else if($result_code === "15"){
				ErrorMsg::egressGatewayUnavailable();
			}
			else if($result_code === "16"){
				ErrorMsg::egressVLCStreamServerUnavailable();
			}
			else if($result_code === "99"){
				ErrorMsg::switchesNotReady();
			}
		}		
		
	}
	
	
	public static function networkConnectionError()
	{
		ErrorMsg::noConnectionController();
		echo "<meta http-equiv=\"refresh\" content=\"0; url=UserPage.php\">";
		
	}
	
	/* For Debugging Purpose, parsing error msg on screen */
	public static function parseErrMsg($result_str_code){
		if($result_str_code === "0"){
			ErrorMsg::complete();
		}
		else{
			if($result_str_code === "1"){
				ErrorMsg::noChannelsAvailable();
			}
			else if($result_str_code === "2"){
				ErrorMsg::incorrectJsonFields();
			}
			else if($result_str_code === "3"){
				ErrorMsg::clientIdParseError();
			}
			else if($result_str_code === "4"){
				ErrorMsg::clientIdNotFound();
			}
			else if($result_str_code === "5"){
				ErrorMsg::egressGatewayNotFound();
			}
			else if($result_str_code === "6"){
				ErrorMsg::clientIpAllZeros();
			}
			else if($result_str_code === "7"){
				ErrorMsg::clientIpParseError();
			}
			else if($result_str_code === "8"){
				ErrorMsg::ingressGatewayUnavailable();
			}
			else if($result_str_code === "9"){
				ErrorMsg::ingressVLCStreamServerUnavailable();
			}
			else if($result_str_code === "10"){
				ErrorMsg::channelAddError();
			}
			else if($result_str_code === "11"){
				ErrorMsg::channelIdParseError();
			}
			else if($result_str_code === "12"){
				ErrorMsg::channelIdUnavailable();
			}
			else if($result_str_code === "13"){
				ErrorMsg::adminPasswordIncorrect();
			}
			else if($result_str_code === "14"){
				ErrorMsg::viewPasswordIncorrect();
			}
			else if($result_str_code === "15"){
				ErrorMsg::egressGatewayUnavailable();
			}
			else if($result_str_code === "16"){
				ErrorMsg::egressVLCStreamServerUnavailable();
			}
			else if($result_str_code === "99"){
				ErrorMsg::switchesNotReady();
			}			
		}
	}
	
}
?>