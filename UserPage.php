<?php

include_once './controller/SessionStart.php';
include_once "./controller/User.php";


header('Refresh: ' . $_SESSION['refreshTimeInterval']);

/* Check Log-in State */
if( empty($_SESSION['usr_id']) ){
	header('Location: ' . $_SESSION['homepage_url']);
}

/* Check Log-out State */

//printDataInSession();
//printDataInCookie();
//printDataInServer();
?>
<!-- HTML -->
<head>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>        
	<link rel="stylesheet" type="text/css" href="css/Text.css"/>	
	<link rel="stylesheet" type="text/css" href="css/Layout.css"/>
</head>

<body>
	<title>GENI Cinema User Page</title>
	<br>
        <!-- GENI Logo -->
        <div align="center">
        <img src="/Images/GENI-Cinema-Logo-weblogo.png">
        </div><br><br><br>

	
	<div align="center">
	<?php 
		if( $_SESSION['usr_id']  && $_SESSION['usr_type'] == "professor" ){	
			echo "<button class=\"btn\" style=\"cursor:move\" onClick=\"location.href='ManageCoursesPage.php'\" type=\"submit\">Select Course To Manage</button>";
		}
	?>
	</div><br>		
	
	<div align="center">
	<?php	
		if( $_SESSION['usr_id'] ){
			echo "<button class=\"btn\" style=\"cursor:move\" onClick=\"location.href='ListAllCoursesPage.php'\" type=\"submit\">Select Course To Watch</button>";
		}
	
	?>
	</div><br><br><br>
	
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
