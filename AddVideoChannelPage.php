<?php

include_once './controller/SessionStart.php';
include_once "./controller/User.php";
include_once './controller/Producer.php';
include_once './controller/ChannelValidator.php';


header('Refresh: ' . $_SESSION['refreshTimeInterval']);

/* Check Log-in State */
if( empty($_SESSION['usr_id']) || ($_SESSION['usr_type'] != "professor") ){
	header('Location: ' . $_SESSION['homepage_url']);
	
}

$error_msg = "";
/* Add A Channel to Web Cache */
if( isset($_POST['AddChannelToCache']) ){
	
	$error_msg = ChannelValidator::descpValidator($_POST['ChannelDscp']);
	
	if( empty($error_msg) ){
		Producer::addVideoChannelToCache( $_SESSION['usr_id'], $_POST['ChannelDscp'], $_SESSION['date'], $_SESSION['date'], $_SESSION['course_id'] );
		UpdateSessionInfo::updateCacheInfo($_SESSION['course_id']);
		
	}

}

/* Remove A Channel from Web Cache */
if( isset($_POST['CancelOneChannel']) ){
	Producer::removeVideoChannelToCache($_SESSION['course_id'], $_POST['channel_descp'], $_SESSION['usr_id']);
	UpdateSessionInfo::updateCacheInfo($_SESSION['course_id']);

}

/* Submit Cached Channels to Floodlight */ 
if( isset($_POST['AddChannelsToFL']) ){
	Producer::addCacheChannelsToFL( $_SESSION['usr_id'], $_SESSION['course_id'], $_SESSION['add_channel_url'] );	
	UpdateSessionInfo::clearCacheInfo();
	echo "<meta http-equiv=\"refresh\" content=\"0;url=../ManageCoursesPage.php\">";

}

/* Remove All Channels Info in Web Cache*/
if( isset($_POST['RemoveChannelsInCache']) ){
	Producer::cancelChannelsInCache($_SESSION['course_id']);
	UpdateSessionInfo::clearCacheInfo();
	echo "<meta http-equiv=\"refresh\" content=\"0;url=../ManageCoursesPage.php\">";

}

/* Always List Cached Channel Infomation */
$channel_cache_json = "";
$channel_cache_json = json_encode($_SESSION['cached_course_uploadInfo'][$_SESSION['course_id']]);

//printDataInSession();
//printDataInServer();
//printDataInCookie();
?>

<head>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>  
	<link rel="stylesheet" type="text/css" href="css/Text.css"/>	
	<link rel="stylesheet" type="text/css" href="css/Layout.css"/>
	<title>Add Video Channel</title>
</head>

<body>

	<!-- GENI Logo -->
	<div align="center">
	<img src="/Images/GENI-Cinema-Logo-weblogo.png">
	</div>	
	
	<h1 style="text-align: left;">Below is Your Course Information</h1>
	
	<!-- <p id="raaagh">Ajax Away</p>  -->
	
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	<fieldset style="text-align: left;">
		<legend style="text-align: left;"> Your Registered Course Information:</legend>
		 
		<p>
		<label>Course name:</label><br> 
		<input readonly="readonly" style='background-color:#DCDCDC' value="<?php echo $_SESSION['course_name'];?>" />			
		</p>

		<p>
		<label>Course description:</label><br> 
		<textarea readonly="readonly" style='background-color:#DCDCDC' name="Dscp" rows="5" cols="100"><?php echo $_SESSION['course_descp'];?></textarea>
		</p>		

		<p>
		<label>School:</label><br>
		<input readonly="readonly" style='background-color:#DCDCDC' value="<?php echo $_SESSION['school'];?>" />			
		</p>			

		<p>
		<label>Department:</label><br>
		<input readonly="readonly" style='background-color:#DCDCDC' value="<?php echo $_SESSION['dept'];?>" />			
		</p>			
		
		<p>
		<label>Professor name:</label><br>
		<input readonly="readonly" style='background-color:#DCDCDC' value="<?php echo $_SESSION['prof_name'];?>" />			
		</p>			

		<p>
		<label>Expire Date:</label><br>
		<input readonly="readonly" style='background-color:#DCDCDC' value="<?php echo $_SESSION['date'];?>" />			
		</p>		
		
	</fieldset>	
	</form>
	
	<h1 style="text-align: left;">Register new channels for the course</h1>
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	<fieldset style="text-align: left;">
			<legend style="text-align: left;">Please fill out new channel information below, and click "Add Channel" Button:</legend>	
			<p>
				<label for="description">Channel Description (Required, Max 25 words):</label><br><br>
				<textarea name="ChannelDscp" rows="5" cols="100" maxlength="25"></textarea>
			</p>
					
			<p align="center">
				<input style="cursor:move" type="submit" name="AddChannelToCache"  value="Add This Channel">
			</p>	
		
	</fieldset>	
	</form>	
	
	<div style="text-align: center;">
	</div><br><br><br>
	
	<div>
	<?php
	if( !empty($error_msg) ){
		echo "<div align=\"center\" id='signUpResult'>". $error_msg . "</div>";
	} 
	?> 
	</div>
	<br /><br />
	
	<div class="channelList"></div>
	<br><br><br>
	
	<div style="text-align: center;">
	<form id="myform" method="POST">
		<input class="btn" style="cursor:move" type="submit" name="AddChannelsToFL" value="Submit">
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		<input class="btn" style="cursor:move" id="confirmCancel" type="submit" name="RemoveChannelsInCache" value="Cancel">
	</form>
	</div><br /><br />
		
		
	<!-- Put Clemson & Wisconson Logo on one line -->
	<table align="center">
		<tr>
			<td style="text-align: center;">
				<img src="/Images/ClemsonLogo.png" height=100px><br>
			</td>
			<td style="text-align: right;">
				<img src="/Images/WisconsinLogo.jpg" height=100px><br>	
			</td>			
			
		</tr>	
	</table><br /><br />
			
</body>

<script>
/* Confirm Remove All Cached Channels */
$(document).ready(function(){

	if($('#confirmCancel').click(function(e){
	 	var c = confirm('This operation is NOT invertible and will lose all the channels information you just entered, are you sure to cancel?');

		if( c == false ){
			e.preventDefault();
		}		
	}));

	
});


/* Dynamic Channel List, display added channels in Cache */
$(document).ready(function()
{
	var channel_cache_info = <?php echo $channel_cache_json ?>;
	console.log(channel_cache_info);

	if( channel_cache_info != null ){
		
		for(var i = 0; i <= channel_cache_info.length-1; i++){
	    	var obj = channel_cache_info[i];

	     	var startTime;
	     	var endTime;
	     	var channelDscp;
	     	//inside each channel object
	     	for(var key in obj){
	    		startTime = obj.start_time;
	    	 	endTime = obj.end_time;
	    	 	channelDscp = obj.channel_description;
	         	//console.log(courseName);
	     	}

	     	j = i + 1;
	     	$(      "<form method='POST'>" + 
	         	    '<fieldset style="text-align: center;">' + 
	            	 '<legend >Your New Channel ' + j + ' Information</legend>' + 
	            	 '<p><label>Channel Description:<br><br>' + 
	            	 '</label> <input readonly="readonly" size="30" style="background-color:#DCDCDC" type="text" name="channel_descp"' + 'value="' + channelDscp + '"/>'  +
	                 '<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="start_time"' +  'value="' + startTime + '"/>'  +
	                 '<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="end_time"' +  'value="' + endTime + '"/>'  +
	                 '<div>' +
	                 '<?php echo '<input class="confirmCancelChan" style="cursor:move" type="submit" name="CancelOneChannel" value="Cancel This Channel"/>' ?>' + 
	                 '</div>' +
	                 '</fieldset>' +     
	             "</form>" 
	     	).appendTo('.channelList');     
	   
	 	}
	}

});

/* Confirm to Remove One Cached Channels */
$(document).ready(function(){

	if( $(".confirmCancelChan").click(function(e){
	 	var c = confirm('This operation is NOT invertible and will lose the selected channel information you just entered, are you sure to cancel?');

		if( c == false ){
			e.preventDefault();
		}			

	}));
	
	
});

/* Disable Submitting from hitting Enter  */
$(document).ready(function() {
	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });
});
</script>
