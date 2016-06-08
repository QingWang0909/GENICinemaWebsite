<?php 

include_once './controller/SessionStart.php';
include_once "./controller/UserValidator.php";	
include_once "./controller/User.php";
include_once './controller/Producer.php';
include_once './controller/CourseValidator.php';


/* Check Log-in State */
if( empty($_SESSION['usr_id']) || ($_SESSION['usr_type'] != "professor") ){
	header('Location: ' . $_SESSION['homepage_url']);
}

/* Sign Up Course */
$error_msg = "";
if( isset($_POST['SignUpCourse']) ){
	
	$start_date = isset($_REQUEST["start_date"]) ? $_REQUEST["start_date"] : "";
	$end_date   = isset($_REQUEST["end_date"]) ? $_REQUEST["end_date"] : "";
	
	$error_msg = CourseValidator::coursenameValidate($_POST['Name']);
	$course_info['name'] = $_POST['Name'];
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::schoolValidate($_POST['School']);
	$course_info['school'] = $_POST['School'];
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::deptValidate($_POST['Dept']);
	$course_info['department'] = $_POST['Dept'];
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::profnameValidate($_POST['ProfName']);
	$course_info['profName'] = $_POST['ProfName'];
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::pwdValidate($_POST['View'], $_POST['ViewConfirm']);
	if( !empty($error_msg) ) goto endA;
	
	$error_msg = CourseValidator::pwdValidate($_POST['Admin'], $_POST['AdminConfirm']);
	if( !empty($error_msg) ) goto endA;	
	
	Producer::signUpCourseInfo($_SESSION['usr_id'], $_POST['Name'], $_POST['Dscp'], $_POST['School'], $_POST['Dept'],
						   $_POST['View'], $_POST['Admin'], $_POST['ProfName'], $start_date, $end_date);
	
	echo "<meta http-equiv=\"refresh\" content=\"0;url=../AddVideoChannelPage.php\">";
	
endA:	
}


//printDataInSession();
//printDataInPost();
//printDataInServer();
?>


<head>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="/calendar/calendar.js"></script>  
	<link rel="stylesheet" type="text/css" href="css/Text.css"/>	
	<link rel="stylesheet" type="text/css" href="css/Layout.css"/>
	<title>Sign Up Course Information</title>
</head>

<body>
	<h1 style="text-align: center;">Please Enter Your New Course Informatoin</h1>
	
	
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		<fieldset style="text-align: left;">
			<legend style="text-align: center;"> Please fill out the information below</legend>
			<p>
				<label>All Fields With "*" Are Required:</label><br><br>
			</p>
				
			<p>
				<label for="name">(*)Course Name:</label><br><br>
				<input id="Name" type="text" name="Name" value="<?php echo $course_info['name']?>"/>
			</p>		
			<p>
				<label for="description">Course Description(Max 100 Words) :</label><br><br>
				<textarea name="Dscp" rows="5" cols="100" maxlength="100"></textarea>
			</p>
			<p>
				<label for="School">(*)School:</label><br><br>
				<input id="School" type="text" name="School" value="<?php echo $course_info['school']?>"/>
			</p>
			<p>
				<label for="Department">(*)Department:</label><br><br>
				<input id="Dept" type="text" name="Dept" value="<?php echo $course_info['department']?>"/>
			</p>		
			<p>
				<label for="Professor's Name">(*)Professor's Name:</label><br><br>
				<input id="prof_name" type="text" name="ProfName" value="<?php echo $course_info['profName']?>"/>
			</p>	
			<p>
				<label for="start_time">(*)Please Select Course Start Time:</label>
				<?php 
					require_once 'calendar/tc_calendar.php';
					$myCalendar = new tc_calendar("start_date", true);
					$myCalendar->setIcon("calendar/images/iconCalendar.gif");
					$myCalendar->setDate(date('d'), date('m'), date('Y'));
					$myCalendar->setPath("calendar/");
					$myCalendar->zindex = 150; 					//default 1
					$myCalendar->setYearInterval(date('Y'), 2020);
					$myCalendar->dateAllow(date('Y-m-d'), '2020-03-01');
					$myCalendar->setAlignment('right', 'bottom'); //optional
					$myCalendar->writeScript();		
				?>
				<br><br>
			</p>
			<p>
				<label for="end_time">(*)Please Select Course Expire Time:</label>
				<?php 
					require_once 'calendar/tc_calendar.php';
					$myCalendar = new tc_calendar("end_date", true);
					$myCalendar->setIcon("calendar/images/iconCalendar.gif");
					$myCalendar->setDate(date('d'), date('m'), date('Y'));
					$myCalendar->setPath("calendar/");
					$myCalendar->zindex = 150; 					//default 1
					$myCalendar->setYearInterval(date('Y'), 2020);
					$myCalendar->dateAllow(date('Y-m-d'), '2020-03-01');
					$myCalendar->setAlignment('right', 'bottom'); //optional
					$myCalendar->writeScript();		
				?>			
				<br><br>
			</p>														
			<p>
				<label for="view-password">Viewer Password:</label><br><br>
				<input id="View" type="password" name="View"/>
			</p>
			<p>
				<label for="view-password">Comfirm Viewer Password:</label><br><br>
				<input id="View" type="password" name="ViewConfirm"/>
			</p>				
			<p>
				<label for="admin-password">Admin Password:</label><br><br>
				<input id="Admin" type="password" name="Admin"/>
			</p>
			<p>
				<label for="admin-password">Comfirm Admin Password:</label><br><br>
				<input id="Admin" type="password" name="AdminConfirm"/>
			</p>				
			<p align="center">
				<input  class="btn" style="cursor:move" type="submit" name="SignUpCourse" value="Submit">
				<input  class="btn" style="cursor:move" type="submit" name="Reset" value="Reset">
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
	<button class="btn" style="cursor: move;" onClick="location.href='UserPage.php'" type="submit">Back to Main Page</button>
	<button class="btn" style="cursor: move;" onClick="location.href='ManageCoursesPage.php'" type="submit">Back to Manage Your Courses</button>
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

<script>

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
