<?php

include_once './controller/SessionStart.php';
include_once "./controller/FunctionsForDatabase.php";
include_once "./controller/User.php";


//header('Refresh: ' . $_SESSION['refreshTimeInterval']);

/* Make Sure All Pages on Same Session */
if(session_id() == ''){
	session_start();
}

/* Check Log-in State */
if( empty($_SESSION['usr_id']) ){
	header('Location: ' . $_SESSION['homepage_url']);
}

/* Check Log-out State */

?>
<!-- HTML -->
<head>
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="css/Text.css" />
	<link rel="stylesheet" type="text/css" href="css/Layout.css" />	
	<title>Watch Video Channel</title>
</head>

<!--  <frameset cols="80%,20%">
  <frame name="videoPlay" src="VideoPlayerPage.php" noresize="noresize" >
  <frame name="switch"    src="SwitchChannelPage.php" noresize="noresize">
</frameset> -->

<iframe name="videoPlay"   align="left"  src="VideoPlayerPage.php"    style="width: 80%; height: 100%; border: none"></iframe>
<iframe name="videoSwitch" align="right" src="SwitchChannelPage.php"  style="width: 20%; height: 100%; border: none"></iframe>


<script type="text/javascript">
/* Disable Submitting from hitting Enter  */
$(document).ready(function() {
	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });
});

/* Timer on Client side for inactivity, e.g mouse, keyboard, etc */

</script>
