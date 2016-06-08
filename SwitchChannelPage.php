<?php

include_once './controller/SessionStart.php';
include_once "./controller/FunctionsForDatabase.php";
include_once "./controller/User.php";
include_once './controller/Watcher.php';
include_once "./controller/ErrorMsg.php";


//header('Refresh: ' . $_SESSION['refreshTimeInterval']);

/* Forbid url type-in manually */
/*
if ($_SERVER['HTTP_REFERER'] == ""){
	echo "<h1>Forbidden</h1>";
	echo "You dont have permission to acesss / on this page";
	exit();
}
*/
/* Check Log-in State */
if( empty($_SESSION['usr_id']) ){
	header('Location: ' . $_SESSION['homepage_url']);
}

/* Check Log-out State */


/* Always List Course Info & Course Channels Info */
$_SESSION['course_chanInfo'] = Watcher::getCourseChanInfo($_SESSION['course_id']);

/* Refresh Video Channels */
if( isset($_POST['refreshChannels']) ){	
	Watcher::displayUpdate();

}

$result_msg = $_SESSION['course_chanInfo'];
$result_json = json_encode($result_msg);

/* Switch Channel */
if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['switchChannel']) ){
	
	if(  isset($_POST['channelID']) && !empty($_SESSION['watcher_id']) && ($_POST['channelID'] == $_SESSION['channel_id'])  ){	
		// select same channel
		$switch_result_msg = Watcher::switchVideoChannels($_SESSION['watch_channel_url'], $_SESSION['watcher_id'], $_POST['channelID'], $_POST['courseID']);
		
	}
	else if( isset($_POST['channelID']) && !empty($_SESSION['watcher_id']) && ($_SESSION['channelID'] != $_POST['channelID'])  ){
		// select different channel
		$switch_result_msg = Watcher::switchVideoChannels($_SESSION['watch_channel_url'], $_SESSION['watcher_id'], $_POST['channelID'], $_POST['courseID']);
		
	}
	
}

?>
<!-- HTML -->
<head>
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="css/Text.css" />
	<link rel="stylesheet" type="text/css" href="css/Layout.css" />	
</head>


<body>
	<h2 style="text-align: center;"> Video Channel Information</h2>
	
	<div style="text-align: center;">
		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		<input type="submit" name="refreshChannels"  value="Refresh Available Videos">
		</form>
	</div>
	
	<h6 style="text-align: center;">We will log you out automatically after 15 minutes of inactivity.</h6>
	<br>
	
	<div class="courseInfo"></div>
	<div class="channelList"></div>	 
	
	<br /><br />
</body>

<script>	
/* Dynamic List Course & its Channels */
$(document).ready(function()
{
	var result = <?php echo $result_json ?>;
	console.log(result);
	
    var obj = result;

    var courseID;
    var courseName;
    var descp;
    var school;
    var department;
    var channelInfo;
    var professor;
	var courseStatus;
	       
    for(var key in obj){
        courseID = obj.course_id;
        descp = obj.description;
        courseName = obj.name;
        school = obj.school;
        department = obj.department;
        professor = obj.professor;
        courseStatus = obj.course_status;         
        channelInfo = obj.channel_message;
    }

	if( courseID == 0){
		courseStatus = "OPEN";
		professor = "GENI";

	}	
	
    $(      "<form method='POST'>" + 
                '<fieldset style="text-align: center;">' + 
                '<legend >Course Profile</legend>' + 
                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
                    '<p><label>Course Name:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="courseName"' +  'value="' + courseName + '"/>'  +
                    '<p><label>Course Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="description"' +  'value="' + descp + '"/>'  +
                    '<p><label>School:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="school"' +  'value="' + school + '"/>'  +
                    '<p><label>Department:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + department + '"/>'  + 
                    '<p><label>Professor:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + professor + '"/>'  + 
                    '<p><label>Course Status:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + courseStatus + '"/>'  +       
                '</fieldset>' +     
            "</form>" 
    ).appendTo('.courseInfo');     


	var channelID;
	var channelDescp;
	var demand;
	
	if( channelInfo != null ){
		for(var i = 0; i < channelInfo.length; ++i){

			var channelObj = channelInfo[i];
			for(var key in channelObj){
				channelID = channelObj.channel_id;
				channelDescp = channelObj.channel_descp;
				demand = channelObj.demand;
			}

			j = i + 1;
			if(channelID == 0){
				$(		"<form method='POST' name=\"myform\" action=\"/SwitchChannelPage.php\">" + 
		                '<fieldset style="text-align: center;">' + 
		                '<legend >Channel ' + j + ' Profile</legend>' + 
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="chanProfileNum"' +  'value="' + j + '"/>'  +
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
		                	'<p><label>Channel ID</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelID"' +  'value="' + channelID + '"/>'  +
		                    '<p><label>Channel Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelDescp"' +  'value="' + channelDescp + '"/>'  +
		                    '<p><input type="hidden" readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + demand + '"/>'  +
		                    '<div>' +
		                    "<input class='channel' type='submit' name='switchChannel' value='Switch Video Channel'/>" +
		                    '</div>' +
		                '</fieldset>' +     
		                "</form>" 
				).appendTo('.channelList');				
				
			}
			else{
				$(		"<form method='POST' name=\"myform\" action=\"/SwitchChannelPage.php\">" + 
		                '<fieldset style="text-align: center;">' + 
		                '<legend >Channel ' + j + ' Profile</legend>' + 
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="chanProfileNum"' +  'value="' + j + '"/>'  +
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
		                	'<p><label>Channel ID</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelID"' +  'value="' + channelID + '"/>'  +
		                    '<p><label>Channel Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelDescp"' +  'value="' + channelDescp + '"/>'  +
		                    '<p><label>Current Channel Demand:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + demand + '"/>'  +
		                    '<div>' +
		                    "<input class='channel' type='submit' name='switchChannel' value='Switch Video Channel'/>" +
		                    '</div>' +
		                '</fieldset>' +     
		                "</form>" 
				).appendTo('.channelList');


			}


			
		}

	}

});

</script>		


