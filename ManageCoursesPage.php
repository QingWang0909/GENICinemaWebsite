<?php

include_once './controller/SessionStart.php';
include_once "./controller/UserValidator.php";
include_once "./controller/User.php";
include_once './controller/Producer.php';
include_once './controller/UpdateSessionInfo.php';

header('Refresh: ' . $_SESSION['refreshTimeInterval']);

/* Check Log-in State */
if( empty($_SESSION['usr_id']) || ($_SESSION['usr_type'] != "professor") ){
	header('Location: ' . $_SESSION['homepage_url']);
}


/* Always List Current Uploaded Courses */
$result_msg = Producer::listUploadedCourses($_SESSION['usr_id']);

/* Clear Download Process Sessions */
UpdateSessionInfo::initUploadSessionInfo();

/* Parsing & Store Course Message */
$_SESSION['course_uploadInfo'] = $result_msg;
$course_json = json_encode($result_msg);

if( isset( $_POST['adminPassword'] )  &&  isset($_POST['courseID']) ){
	$error_msg = Producer::verifyAdminPwd($_POST['courseID'], $_SESSION['usr_id'], $_POST['adminPassword']);
	
	if( $error_msg === 0 ){
		/* Turn Off a specific Course */
		if( isset($_POST['turnOffCourse']) ){
			Producer::turnOffCourse($_POST['courseID'], $_SESSION['remove_channel_url'], $_POST['adminPassword']);
			UpdateSessionInfo::updateSessionInfo($_POST['courseID'], "");
			echo "<meta http-equiv=\"refresh\" content=\"0;url=../ManageCoursesPage.php\">";
		
		}
		/* Modify a specific Course Registration Info */
		if( isset($_POST['modifyCourseInfo']) ){
			Producer::goModifyCoursePage($_POST['courseID']);
			
		}		
		/* View Detail Channels Info for a Course */
		if( isset($_POST['channelInfo']) ){
			Producer::goListCourseChannelsPage($_POST['courseID']);

		}
		/* Extend Course Expire Date */
		if( isset($_POST['extendCourse']) ){
			Producer::goExtendExpireDatePage($_POST['courseID']);
			
		}
		
	}
	
}
//printDataInSession();
//printDataInCookie();
//printDataInPOST();

?>
<!-- HTML -->
<head>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>        
    <link rel="stylesheet" type="text/css" href="css/Text.css">
    <link rel="stylesheet" type="text/css" href="css/Layout.css">
    <title>Course Management</title>
</head>

<body>
	
	<div style="text-align: center;">
	</div>
	
	<table align="center">
	<tr>
		<td style="text-align: center;">
		<!-- GENI Logo -->
		<img src="/Images/GENI-Cinema-Logo-weblogo.png">
		</td>
	</tr>
	</table>

	<h1 align="center">Course Manager</h1><br><br>

	<table align="center">
		<tr>
			<td>
				<form method="POST">
				<input class="btn"  style="cursor:move" onClick="location.href='RegisterCourseInfoPage.php'" value="Register A New Course">
				</form>			
			</td>
		</tr>	
	</table>	 
	
	<div>
	<?php
	if( !empty($error_msg) ){
		echo "<div align=\"center\" id='signUpResult'>". $error_msg . "</div>";
	} 
	?> 
	</div>
	<br /><br />	
	
	<div class="courseList"></div>

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
	
	<div>
	<table align="center">
		<tr>
			<td align="center">
				<button class="btn" style="cursor:move" onClick="location.href='ListAllCoursesPage.php'">List All Courses</button>
			</td>
		</tr>

		<tr>
			<td align="center">
				<button class="btn" style="cursor:move" onClick="location.href='UserPage.php'">Back to Main Page</button>
			</td>
		</tr>	
	</table>
	</div><br /><br />	
	
	<div align=center>
	<?php  
	if( $_SESSION['usr_id'] ){ 
		echo "<a class=\"btn\" style=\"cursor:move\" href=\"./controller/Logout.php\">Logout</a>";
	}
	?>
	</div>	

</body>

<script>
/* Disable Submitting from hitting Enter */
$(document).ready(function() {
	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });
});

/* Dynamic Display All Uploaded Courses Info */
$(document).ready(function()
{
	var course_uploadInfo = <?php echo $course_json ?>;
	console.log(course_uploadInfo);

	if( course_uploadInfo != null ){
	    for(var i = 0; i < course_uploadInfo.length; i++){
	        var obj = course_uploadInfo[i];

	        var courseName;
	        var descp;
	        var school;
	        var department;
	        var expireDate;
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
	            expireDate = obj.expire_date;
	        }

			j = i + 1;
	        $(      "<form method='POST'>" + 
	                '<fieldset style="text-align: center;">' + 
	                '<legend >Your Course ' + j + ' Profile</legend>' + 
	                	'<p><input readonly="readonly" style="background-color:#DCDCDC" type="hidden" name="courseID"' +  'value="' + courseID + '"/>'  +
	                    '<p><label>Course Name:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="courseName"' +  'value="' + courseName + '"/>'  +
	                    '<p><label>Course Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="description"' +  'value="' + descp + '"/>'  +
	                    '<p><label>School:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="school"' +  'value="' + school + '"/>'  +
	                    '<p><label>Department:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + department + '"/>'  + 
	                    '<p><label>Professor:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + professor + '"/>'  + 
	                    '<p><label>Course Status:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + courseStatus + '"/>'  + 
	                    '<p><label>Expire Date:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + expireDate + '"/>'  + 
	                    '<p><label>Administrative Password:</label> <input type="password" name="adminPassword" />'  +
	                    '<div>' +
	                    '<?php echo '<input type="submit" name="channelInfo" value="Manage Channels in Course"/>'; 
	                    	   echo '<input type="submit" name="modifyCourseInfo" value="Modify Course Information"/>'; 
	                    	   echo '<input type="submit" name="extendCourse" value="Extend Course Expire Date"/>'; 
	                    	   echo '<input class="confirmCloseCourses" type="submit" name="turnOffCourse" value="Turn Off This Course"/>'; 
						 ?>' + 
	                    '</div>' +

	                '</fieldset>' +     
	                "</form>" 
	        ).appendTo('.courseList');     
	      
	    }
	}
	
});

$(document).ready(function(){

	if($('.confirmCloseCourses').click(function(e){
	 	var c = confirm('This operation is NOT invertible and will release all the course channel resources, are you sure to turn off the course?');

		if( c == false ){
			e.preventDefault();
		}
		
	}));
	
});

</script>
