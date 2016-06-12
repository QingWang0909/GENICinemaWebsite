<?php

include_once './controller/SessionStart.php';
include_once "./controller/User.php";
include_once './controller/Producer.php';
include_once './controller/UpdateSessionInfo.php';


/* Check Log-in State */
if( empty($_SESSION['usr_id']) || ($_SESSION['usr_type'] != "professor") ){
	header('Location: ' . $_SESSION['homepage_url']);
}

/* Turn Off a Channel */
if( isset($_POST['turnOffChannel']) ){
	$result_msg = Producer::turnOffVideoChannel($_POST['courseID'], $_POST['channelID'], $_SESSION['remove_channel_url'] );
	UpdateSessionInfo::updateSessionInfo($_POST['courseID'], $_POST['channelID']);
	
}

/* Modify a Channel Description */
if( isset($_POST['newChannelDscp']) ){
	Producer::modifyChannelDescp($_SESSION['course_id_modify'], $_SESSION['channel_id_modify'], $_SESSION['modify_channel_url'], $_POST['newChannelDscp']);
	UpdateSessionInfo::updateSessionInfo($_SESSION['course_id_modify'], $_SESSION['channel_id_modify']);
	echo "<meta http-equiv=\"refresh\" content=\"0;url=../ManageCourseChannelsPage.php\">";
	
}

/* Add a Channel */
if( isset($_POST['submitNewChannel']) ){
	Producer::addChannelToFL($_SESSION['usr_id'], $_SESSION['course_id_modify'], $_POST['ChannelDescp'], $_SESSION['date'], $_SESSION['date'], $_SESSION['add_channel_url']);	
	UpdateSessionInfo::updateSessionInfo($_SESSION['course_id_modify'], "");
	
}

/* Always List Course Info & Course Channels Info */
$result_msg = $_SESSION['course_chanInfo'];
$result_json = json_encode($result_msg);

//printDataInSession();
//printDataInPOST();
?>

<head>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>  
	<link rel="stylesheet" type="text/css" href="css/Text.css"/>	
	<link rel="stylesheet" type="text/css" href="css/Layout.css"/>
	<title>Channels Management</title>
</head>


<body>
        <!-- GENI Logo -->
        <div align="center">
        <img src="/Images/GENI-Cinema-Logo-weblogo.png">
        </div>

	<h1 style="text-align: center;">Course Channel Manager</h1>
	
	<div class = "courseInfo"></div>
	
	<?php 
		if( isset($_POST['addNewChannel']) ){
			echo "<form method=\"POST\" >";
			echo "<fieldset style=\"text-align: center;\">";
			echo "<legend >New Channel Profile</legend>";
			
			echo "<p>";
			echo "<label for=\"description\">Channel Description:</label>";	
			echo "<input id=\"Start\" type=\"text\" name=\"ChannelDescp\"/>";
			echo "</p>";
			
			
			echo "<p>";
			echo "<input type=\"submit\" name=\"submitNewChannel\"  value=\"Submit\">";
			echo "<input type=\"submit\" name=\"cancel\"  value=\"Cancel\">";
			echo "</p>";
			
			echo "</fieldset>";
			echo "</form>";
		}
	?>

	                
  	<div>
    <?php 
    if( isset($_POST['modifyChannel']) ){
        $_SESSION['channel_id_modify'] = $_POST['channelID'];
		$chanProfileNum = $_POST['chanProfileNum'];
        
    	echo "<form method=\"POST\">";		
		echo "<fieldset style=\"text-align: center;\">";
    	echo "<legend style=\"text-align: center;\">Please enter channel $chanProfileNum new description information below: </legend>";
						
    	echo "<p>";
    	echo "<label for=\"description\">New Channel Description:</label>";
		echo "<input id=\"ChannelDscp\" type=\"text\" name=\"newChannelDscp\" value=\"\"/>";
    	echo "</p>";
    				 	
    	echo "<p>";
    	echo "<input type=\"submit\" name=\"modifyChanDescp\" value=\"Submit\">";
    	echo "<input type=\"submit\" name=\"Reset\" value=\"Reset\">";
    	echo "</p>";
    				 	
    	echo "</fieldset>";
    	echo "</form>";
    }
	?>
    </div>	
	
	<div class="channelList"></div><br /><br /><br /><br />
	
	<div style="text-align: center;">
	<button class="btn" style="cursor: move;" onClick="location.href='UserPage.php'" type="submit">Back to Homepage</button>
	<button class="btn" style="cursor: move;" onClick="location.href='ManageCoursesPage.php'" type="submit">Back to manage current courses</button>
	</div><br /><br />
	
	<!-- Clemson & Wisconson Logo on one line -->
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
		
	<div align=center>
	<?php  
	if( $_SESSION['usr_id'] ){ 
		echo "<a class=\"btn\" style=\"cursor:move\" href=\"./controller/Logout.php\">Logout</a>";
	}
	?>
	</div>	
</body>

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
	var courseStatus;
    
    for(var key in obj){
        courseID = obj.course_id;
        descp = obj.description;
        courseName = obj.name;
        school = obj.school;
        department = obj.department;
        expireDate = obj.expire_date;
        professor = obj.professor;
        courseStatus = obj.course_status;
        channelInfo = obj.channel_message;
    }
		
    $(      "<form method='POST'>" + 
                '<fieldset style="text-align: center;">' + 
                '<legend >Your Course Profile</legend>' + 
                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
                    '<p><label>Course Name:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="courseName"' +  'value="' + courseName + '"/>'  +
                    '<p><label>Course Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="description"' +  'value="' + descp + '"/>'  +
                    '<p><label>School:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="school"' +  'value="' + school + '"/>'  +
                    '<p><label>Department:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + department + '"/>'  + 
                    '<p><label>Professor:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + professor + '"/>'  + 
                    '<p><label>Course Status:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + courseStatus + '"/>'  + 
                     '<p><label>Expire Date:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + expireDate + '"/>'  + 
                    '<div>' +
                    '<?php echo '<input type="submit" name="addNewChannel" value="Add A New Channel"/>'; 
                    ?>' +
                    '</div>' +

                '</fieldset>' +     
            "</form>" 
    ).appendTo('.courseInfo');     


	var channelID;
	var channelDescp;
	var gwIP;
	var gwPort;
	var startTime;
	var endTime;

	if( channelInfo != null ){
		for(var i = 0; i < channelInfo.length; ++i){

			var channelObj = channelInfo[i];
			for(var key in channelObj){
				channelID = channelObj.channel_id;
				channelDescp = channelObj.channel_descp;
				gwIP = channelObj.i_gateway_ip;
				gwPort = channelObj.i_gateway_port;
				startTime = channelObj.start_time;
				endTime = channelObj.end_time;
				
			}

			j = i + 1;
			$(		"<form method='POST'>" + 
	                '<fieldset style="text-align: center;">' + 
	                '<legend >Channel ' + j + ' Profile</legend>' + 
	                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="chanProfileNum"' +  'value="' + j + '"/>'  +
	                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
	                	'<p><label>Channel ID</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelID"' +  'value="' + channelID + '"/>'  +
	                    '<p><label>Channel Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelDescp"' +  'value="' + channelDescp + '"/>'  +
	                    '<p><label>Upload Gateway IP:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="gwIP"' +  'value="' + gwIP + '"/>'  +
	                    '<p><label>Upload Gateway Port:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="gwPort"' +  'value="' + gwPort + '"/>'  +
	                    '<div>' +
	                    '<?php echo '<input type="submit" name="modifyChannel" value="Modify This Channel"/>'; 
	                    	   echo '<input class="confirmCloseChannel" type="submit" name="turnOffChannel" value="Turn Off This Channel"/>'; 
						 ?>' + 
	                    '</div>' +
	                '</fieldset>' +     
	                "</form>" 
	                
			).appendTo('.channelList');
			
		}

	}

});

$(document).ready(function(){

	if( $(".confirmCloseChannel").click(function(e) {

	 	var c = confirm('This operation is NOT invertible and will release all the selected channel resource, are you sure to turn off the course?');

		if( c == false ){
			e.preventDefault();
		}		
		

	}));
	
});

	
</script>
