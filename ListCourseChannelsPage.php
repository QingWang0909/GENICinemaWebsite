<?php

include_once './controller/SessionStart.php';
include_once "./controller/User.php";
include_once './controller/Producer.php';
include_once './controller/Watcher.php';


/* Check Log-in State */
if( empty($_SESSION['usr_id']) ){
	header('Location: ' . $_SESSION['homepage_url']);
}

/* Watch A Video Channel */
if( isset($_POST['watchChannel']) ){

	if(  empty($_SESSION['watcher_id']) && empty($_SESSION['channel_id'])  ){							// client first time watch
		Watcher::watchVideoChannel($_SESSION['watch_channel_url'], $_SESSION['watcher_id'], $_POST['channelID'], $_POST['courseID'], FALSE);

	}
	else if(  !empty($_SESSION['watcher_id']) && ($_POST['channel_id'] == $_SESSION['channel_id'])  ){	// client is not frist time watch, but select same channel
		Watcher::watchVideoChannel($_SESSION['watch_channel_url'], $_SESSION['watcher_id'], $_POST['channelID'], $_POST['courseID'], FALSE);

	}
	else if(  !empty($_SESSION['watcher_id']) && ($_SESSION['channel_id'] != $_POST['channel_id'])  ){	// client is not first time watch, but select different channel
		Watcher::watchVideoChannel($_SESSION['watch_channel_url'], $_SESSION['watcher_id'], $_POST['channelID'], $_POST['courseID'], FALSE);

	}

}


/* Always List Course Info & Course Channels Info */
$_SESSION['course_chanInfo'] = Watcher::getCourseChanInfo($_SESSION['course_id']);
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
	<br>
	<!-- GENI Logo -->
        <div align="center">
        <img src="/Images/GENI-Cinema-Logo-weblogo.png">
        </div>
	<br>

	<div class = "courseInfo"></div>
	
	<div class="channelList"></div><br /><br />
	
	<div style="text-align: center;">
	<button class="btn" style="cursor: move;" onClick="location.href='UserPage.php'" type="submit">Back to Main Page</button>
	<button class="btn" style="cursor:move" onClick="location.href='ListAllCoursesPage.php'" type="submit">List All Courses</button>
	</div><br><br>
	
	<div align="center">
	<?php 
		if( $_SESSION['usr_id']  && $_SESSION['usr_type'] == "professor" ){	
			echo "<button class=\"btn\" style=\"cursor:move\" onClick=\"location.href='ManageCoursesPage.php'\" type=\"submit\">Manage Courses & Channels</button>";
		}
		
		echo str_repeat('&nbsp;', 10);
		
	?>
	</div><br><br><br>	
	
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

        echo "&nbsp&nbsp&nbsp&nbsp";

        if( $_SESSION['usr_id']  ){
                echo "<button class=\"btn\" style=\"cursor:move\" onClick=\"location.href='HelpPage.html'\" type=\"submit\">Help</button>";
        }
        ?>
        </div>

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
				$(		"<form method='POST'>" + 
		                '<fieldset style="text-align: center;">' + 
		                '<legend >Channel ' + j + ' Profile</legend>' + 
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="chanProfileNum"' +  'value="' + j + '"/>'  +
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
		                	'<p><label>Channel ID</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelID"' +  'value="' + channelID + '"/>'  +
		                    '<p><label>Channel Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelDescp"' +  'value="' + channelDescp + '"/>'  +
		                    '<div>' +
		                    '<?php echo '<input type="submit" name="watchChannel" value="Watch This Channel"/>'; 
							 ?>' + 
		                    '</div>' +
		                '</fieldset>' +     
		                "</form>" 
		                
				).appendTo('.channelList');

			}
			else{
				$(		"<form method='POST'>" + 
		                '<fieldset style="text-align: center;">' + 
		                '<legend >Channel ' + j + ' Profile</legend>' + 
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="chanProfileNum"' +  'value="' + j + '"/>'  +
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
		                	'<p><label>Channel ID</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelID"' +  'value="' + channelID + '"/>'  +
		                    '<p><label>Channel Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="channelDescp"' +  'value="' + channelDescp + '"/>'  +
		                    '<p><label>Current Channel Demand:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + demand + '"/>'  +
		                    '<div>' +
		                    '<?php echo '<input type="submit" name="watchChannel" value="Watch This Channel"/>'; 
							 ?>' + 
		                    '</div>' +
		                '</fieldset>' +     
		                "</form>" 
		                
				).appendTo('.channelList');
				
				
			}
			
		}

	}

});

	
</script>
