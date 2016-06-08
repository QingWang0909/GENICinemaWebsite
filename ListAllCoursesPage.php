<?php 

include_once './controller/SessionStart.php';
include_once "./controller/FunctionsForDatabase.php";
include_once './controller/User.php';
include_once "./controller/Watcher.php";
include_once "./controller/UserValidator.php";
include_once './controller/UpdateSessionInfo.php';


header('Refresh: ' . $_SESSION['refreshTimeInterval']);

/* Check Log-in State */
if( empty($_SESSION['usr_id']) ){
	header('Location: ' . $_SESSION['homepage_url']);
}


/* Clear Upload Process Sessions */
UpdateSessionInfo::initDownloadSessionInfo();


/* Always List Courses */
$result_msg = Watcher::listAllAvailableCourses($_SESSION['usr_id']);


/* Parsing & Store Course Message */
if( !isset($_SESSION['watcher_id']) ){
	$_SESSION['watcher_id'] = "";
}
$_SESSION['course_downloadInfo'] = $result_msg;
$course_json = json_encode($result_msg);


/* Check the Viewer Password */
if( isset($_POST['courseID']) ){
	
	// Check View Pwd except Default Channel
	if( $_POST['courseID'] === "0" ){
		if( isset($_POST['channelInfo']) ){
			Watcher::goListCourseChannelsClientPage($_POST['courseID']);
				
		}				
		
	}
	else{
		
		$error_msg = Watcher::verifyViewPwd($_POST['courseID'], $_POST['viewPassword']);
		if( $error_msg === 0 ){
			if( isset($_POST['channelInfo']) ){
				Watcher::goListCourseChannelsClientPage($_POST['courseID']);
					
			}
		
		}
		
	}

}

//printDataInSession();
//printDataInCookie();
//printArrayMsg($watch_result_msg);
?>
<!-- HTML -->
<head>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>  
	<link rel="stylesheet" type="text/css" href="css/Text.css"/>	
	<link rel="stylesheet" type="text/css" href="css/Layout.css"/>
	<title>List All Courses</title>
</head>

<body>
	<!-- GENI Logo -->
	<div align="center">
	<img src="/Images/GENI-Cinema-Logo-weblogo.png">
	</div>	
	
	<div>
	<?php
	if( !empty($error_msg) ){
		echo "<div align=\"center\" id='signUpResult'>". $error_msg . "</div>";
	} 
	?> 
	</div>
	<br /><br />		
	
	<div class="courseList"></div>
	
	<div style="text-align: center;">
	<?php 
		if( $_SESSION['usr_id']  && $_SESSION['usr_type'] == "professor" ){	
			echo "<button class=\"btn\" style=\"cursor:move\" onClick=\"location.href='ManageCoursesPage.php'\" type=\"submit\">Manage Your Courses</button>";
		}
	?>
	</div><br />
	
	<div style="text-align: center;">
		<button class="btn" style="cursor: move" onClick="location.href='UserPage.php'" type="submit">Back to Main Page</button>
	</div>
	<br><br>

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

	<!-- Loading Animation, not fully implemented yet -->
	<!-- <img id="loading" src="/GENI_Cinema/GIF/loading.gif" style="display: none"></img> -->
	
</body>

<script>
/* Dynamic Display All Uploaded Courses Info */
$(document).ready(function()
{
	var course_Info = <?php echo $course_json ?>;
	console.log(course_Info);

	if( course_Info != null ){
	    for(var i = 0; i < course_Info.length; i++){
	        var obj = course_Info[i];

	        var courseName;
	        var descp;
	        var school;
	        var department;
	        var totalClientNum;
	        var professor;
			var courseStatus;
	        
	        // inside each course object
	        for(var key in obj){
	            courseID = obj.course_id;
	            descp = obj.description;
	            courseName = obj.name;
	            school = obj.school;
	            department = obj.department;
	            professor = obj.professor;
	            courseStatus = obj.course_status;
	            totalClientNum = obj.total_demand;
	        }

	        
			j = i + 1;
			if( courseID == 0){
				courseStatus = "OPEN";
				professor = "GENI";
				
		        $(      "<form method='POST'>" + 
		                '<fieldset style="text-align: center;">' + 
		                '<legend >Course ' + j + '</legend>' + 
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
		                    '<p><label>Course Name:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="courseName"' +  'value="' + courseName + '"/>'  +
		                    '<p><label>Course Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="description"' +  'value="' + descp + '"/>'  +
		                    '<p><label>School:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="school"' +  'value="' + school + '"/>'  +
		                    '<p><label>Department:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + department + '"/>'  + 
		                    '<p><label>Professor:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + professor + '"/>'  + 
		                    '<p><label>Course Status:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + courseStatus + '"/>'  + 
		                    '<div>' +
		                    '<?php echo '<input type="submit" name="channelInfo" value="View Channels"/>'; 
							 ?>' + 
		                    '</div>' +

		                '</fieldset>' +     
		                "</form>" 
		        ).appendTo('.courseList');  
		        
			}
			else{
		        $(      "<form method='POST'>" + 
		                '<fieldset style="text-align: center;">' + 
		                '<legend >Course ' + j + '</legend>' + 
		                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
		                    '<p><label>Course Name:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="courseName"' +  'value="' + courseName + '"/>'  +
		                    '<p><label>Course Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="description"' +  'value="' + descp + '"/>'  +
		                    '<p><label>School:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="school"' +  'value="' + school + '"/>'  +
		                    '<p><label>Department:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + department + '"/>'  + 
		                    '<p><label>Professor:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + professor + '"/>'  + 
		                    '<p><label>Course Status:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + courseStatus + '"/>'  + 
		                    '<p><label>Current Demand:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + totalClientNum + '"/>'  + 
		                    '<p><label>View Password:</label> <input type="password" name="viewPassword" />'  +
		                    '<div>' +
		                    '<?php echo '<input type="submit" name="channelInfo" value="View Channels"/>'; 
							 ?>' + 
		                    '</div>' +

		                '</fieldset>' +     
		                "</form>" 
		        ).appendTo('.courseList');  

			}
			
   
	    }
	}
	
});

</script>

