<?php

include_once './controller/SessionStart.php';
include_once "./controller/User.php";
include_once './controller/Producer.php';
include_once './controller/UpdateSessionInfo.php';
include_once './controller/CourseValidator.php';

header('Refresh: ' . $_SESSION['refreshTimeInterval']);

/* Check Log-in State */
if( empty($_SESSION['usr_id']) || ($_SESSION['usr_type'] != "professor") ){
	header('Location: ' . $_SESSION['homepage_url']);
}

/* Extend Course Expire Date */
if( isset($_POST['submit']) ){
	$new_expire_date   = isset($_REQUEST["end_date"]) ? $_REQUEST["end_date"] : "";
	Producer::extendDate($_SESSION['course_id_modify'], $new_expire_date);
	UpdateSessionInfo::clearCourseChanInfo();
	
	echo "<meta http-equiv=\"refresh\" content=\"0;url=../ManageCoursesPage.php\">";
	
}

/* List Current Course Info */
$course_info_msg = Producer::listCourseInfo( $_SESSION['course_id_modify'] );
$course_info_json = json_encode($course_info_msg);

//printDataInSession();

?>

<head>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>  
	<link rel="stylesheet" type="text/css" href="css/Text.css"/>	
	<link rel="stylesheet" type="text/css" href="css/Layout.css"/>
	<title>Extend Course Expire Date</title>
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
	
	
	<h1 style="text-align: center;">Please Select the Expire Date you want to extend</h1>

	<div class="courseInfo"></div>
	
	
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	<fieldset style="text-align: left;">
		<legend>Please Select New Course Expire Time</legend>
		<p>	
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
		
		<p align="center">
		<input  class="btn" style="cursor:move" type="submit" name="submit" value="Submit">
		</p>
		
	</fieldset>
	</form>	
	
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
    var expireDate;
        
    for(var key in course_Info){
        descp = course_Info.description;
        courseName = course_Info.name;
        school = course_Info.school;
        department = course_Info.department;
        profName = course_Info.profName;
        expireDate = course_Info.expire_date;
    }
		
    $(      "<form method='POST'>" + 
                '<fieldset style="text-align: left;">' + 
                '<legend >Current Course ' + ' Information</legend>' + 
                    '<p><label>Course Name:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="courseName"' +  'value="' + courseName + '"/>'  +
                    '<p><label>Course Description:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="description"' +  'value="' + descp + '"/>'  +
                    '<p><label>School:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text" name="school"' +  'value="' + school + '"/>'  +
                    '<p><label>Department:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + department + '"/>'  + 
                    '<p><label>Professor Name:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + profName+ '"/>'  + 
                    '<p><label>Expire Date:</label> <input readonly="readonly" style="background-color:#DCDCDC" type="text"' +  'value="' + expireDate + '"/>'  + 
                    '<div>' +
                '</fieldset>' +     
            "</form>" 
    ).appendTo('.courseInfo');     
      
});


</script>

