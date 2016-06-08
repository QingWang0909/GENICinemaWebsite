<?php

include_once './controller/SessionStart.php';
include_once "./controller/UserValidator.php";
include_once "./controller/User.php";
include_once './controller/Producer.php';
include_once './controller/CourseValidator.php';
include_once './controller/UpdateSessionInfo.php';

/* Check Log-in State */
if( empty($_SESSION['usr_id']) || ($_SESSION['usr_type'] != "professor") ){
	header('Location: ' . $_SESSION['homepage_url']);
}

/* Modify Course Profile */
$error_msg = "";
$course_update_msg = "";
if( isset($_POST['ModifyCourseInfo']) ){
	
	$error_msg = CourseValidator::coursenameValidate($_POST['Name']);
	$course_update_msg['name'] = $_POST['Name'];
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::schoolValidate($_POST['School']);
	$course_update_msg['school'] = $_POST['School'];
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::deptValidate($_POST['Dept']);
	$course_update_msg['department'] = $_POST['Dept'];
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::profnameValidate($_POST['ProfName']);
	$course_update_msg['profName'] = $_POST['ProfName'];
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::pwdValidate($_POST['View'], $_POST['ViewConfirm']);
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::pwdValidate($_POST['Admin'], $_POST['AdminConfirm']);
	if( !empty($error_msg) ) goto endA;
	
	$course_update_msg['description'] = $_POST['Dscp'];
	
	Producer::naiveModifyCourseInfo($_SESSION['course_id_modify'], $_POST['Name'], $_POST['Dscp'], $_POST['School'],
						   $_POST['Dept'], $_POST['ProfName'], $_POST['View'], $_POST['Admin'], $_SESSION['modify_channel_url']);
	UpdateSessionInfo::clearCourseChanInfo();
	
	echo "<meta http-equiv=\"refresh\" content=\"0;url=../ManageCoursesPage.php\">";
	
	
endA:
}


/* List Current Course Info */
$course_info_msg = Producer::listCourseInfo( $_SESSION['course_id_modify'] );
$course_info_json = json_encode($course_info_msg);

//printDataInSession();
//printDataInPOST();
?>


<head>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>  
	<link rel="stylesheet" type="text/css" href="css/Text.css"/>	
	<link rel="stylesheet" type="text/css" href="css/Layout.css"/>
	<title>Modify Course Information</title>
</head>

<body>

	<table align="center">
	<tr>
		<td style="text-align: center;">
		<!-- GENI Logo -->
		<img src="/Images/GENI-Cinema-Logo-weblogo.png">
		</td>
	</tr>
	</table>

	<h1 style="text-align: center;">Please Enter Course Informatoin to Update Course Profile</h1>

	<div class="courseInfo"></div>

	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		<fieldset style="text-align: center;">
			<legend style="text-align: center;"> New Course Profile Information</legend>	
			<p>
				<label for="name">Course Name:</label>
				<input id="Name" type="text" name="Name" value="<?php echo $course_update_msg['name']?>"/>
			</p>		
			<p>
				<label for="description">Course Description:</label>
				<input id="Dscp" type="text" name="Dscp" value="<?php echo $course_update_msg['description']?>"/>
			</p>
			<p>
				<label for="School">School:</label>
				<input id="School" type="text" name="School" value="<?php echo $course_update_msg['school']?>"/>
			</p>
			<p>
				<label for="Department">Department:</label>
				<input id="Dept" type="text" name="Dept" value="<?php echo $course_update_msg['department']?>"/>
			</p>		
			<p>
				<label for="Professor's Name">Professor's Name:</label>
				<input id="prof_name" type="text" name="ProfName" value="<?php echo $course_update_msg['profName']?>"/>
			</p>										
			<p>
				<label for="view-password">New Viewer Password:</label>
				<input id="View" type="password" name="View"/>
			</p>
			<p>
				<label for="view-password">Comfirm Viewer Password:</label>
				<input id="View" type="password" name="ViewConfirm"/>
			</p>			
			<p>
				<label for="admin-password">New Admin Password:</label>
				<input id="Admin" type="password" name="Admin"/>
			</p>
			<p>
				<label for="admin-password">Comfirm Admin Password:</label>
				<input id="Admin" type="password" name="AdminConfirm"/>
			</p>			
			
			<p>
				<input type="submit" name="ModifyCourseInfo" value="Submit">
				<input type="submit" name="Reset" value="Reset">
			</p>
			
		</fieldset>	
	</form>
	<div style="text-align: center;">
	</div><br>
	
	<div>
	<?php
	if( !empty($error_msg) ){
		echo "<div align=\"center\" id='signUpResult'>". $error_msg . "</div>";
	} 
	?> 
	</div>
	<br /><br />	
	
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
/* Disable Submitting from hitting Enter */
$(document).ready(function() {
	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });
});

/* Display Current Course Info */
$(document).ready(function()
{
	var course_Info = JSON.parse( '<?php echo $course_info_json ?>' );
	console.log(course_Info);


    var courseName;
    var descp;
    var school;
    var department;
    var profName;
        
    for(var key in course_Info){
        descp = course_Info.description;
        courseName = course_Info.name;
        school = course_Info.school;
        department = course_Info.department;
        profName = course_Info.profName;
    }
		
    $(      "<form method='POST'>" + 
                '<fieldset style="text-align: center;">' + 
                '<legend >Current Course ' + ' Information</legend>' + 
                    '<p><label>Course Name:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="courseName"' +  'value="' + courseName + '"/>'  +
                    '<p><label>Course Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="description"' +  'value="' + descp + '"/>'  +
                    '<p><label>School:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="school"' +  'value="' + school + '"/>'  +
                    '<p><label>Department:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + department + '"/>'  + 
                    '<p><label>Professor Name:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + profName+ '"/>'  + 
                    '<div>' +
                '</fieldset>' +     
            "</form>" 
    ).appendTo('.courseInfo');     
      
});


</script>
