<?php

include_once 'SessionStart.php';
include_once 'ConnectDatabase.php';

final class ChannelValidator
{
	
	private function __construct() { }
	
	public static function descpValidator( $channel_descp )
	{
		
		if(empty($channel_descp)) return "Channel Description can not be empty";	
		
	}
	
	
}

?>