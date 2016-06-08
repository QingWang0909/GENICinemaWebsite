<?php

include_once './controller/SessionStart.php';
include_once "./controller/User.php";

//header('Refresh: ' . $_SESSION['refreshTimeInterval']);

/* Forbid url type-in manually*/
/*
if ($_SERVER['HTTP_REFERER'] == ""){
	echo "<h1>Forbidden</h1>";
	echo "You dont have permission to acesss on this page";
	exit();
}
*/

/* Check Log-in State */
if( empty($_SESSION['usr_id']) ){
	header('Location: ' . $_SESSION['homepage_url']);
}

/* Check Log-out State */


//printDataInServer();
//printDataInSession();
//printDataInCookie();
?>
<!-- HTML -->
<head>
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="css/Text.css" />
	<link rel="stylesheet" type="text/css" href="css/Layout.css" />   
</head>

<body>
	
	<!-- GENI Logo -->
        <div align="center">
        <img src="/Images/GENI-Cinema-Logo-weblogo.png">
        </div>
	<br>


	<!-- VLC Plugin Player -->
	<div class="videoPlayer" style="text-align: center;">
		<embed type="application/x-vlc-plugin"
		       name="video1"
		       autoplay="yes" loop="yes" width="80%" height="80%" network-caching=150
			   target="http://<?php echo $_SESSION['curr_egress_gateway_ip'] . ":" . $_SESSION['curr_egress_gateway_port']?>"
			   	       
		/>      
		
	</div><br /><br />
	
	
	<table align="center">
	<tr>
		<td>
		<a class="btn" style="cursor:move" href="UserPage.php" target="_top" type="submit" >Back to Main Page</a>
		</td>
		<td>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		<td>
		<a class="btn" style="cursor:move" href="ListCourseChannelsPage" target="_top" type="submit">Back to Video Channels of This Course</a>
		</td>
		<td>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		<td>
		<a class="btn" style="cursor:move" href="ListAllCoursesPage.php" target="_top" type="submit">Back to List All Courses</a>
		</td>
	</tr>
	</table>
	<br><br>
	
	<div style="text-align: center;">

	</div><br />
	
	<div style="text-align: center;">
		
	</div>
	
	<div align="center">
	<?php 
		if( $_SESSION['usr_id']  && $_SESSION['usr_type'] == "professor" ){	
			echo "<a  class=\"btn\" style=\"cursor:move\" href=\"ManageCoursesPage.php\" type=\"submit\" target=\"_top\">Manage Course & Channels</a>";
		}
	?>
	</div><br><br>
	
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
		echo "<a class=\"btn\" id=\"logout\" style=\"cursor:move\" href=\"./controller/Logout.php\" target=\"_top\" type=\"submit\">Logout</a>";
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

/* Timer on Client side for inactivity, e.g mouse, keyboard, etc */

/* check mobile users */
window.mobilecheck = function(){
	document.write("Hello user");
	var check = false;
	(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
	if( check == true ){
		document.write("Hello Mobile user");
	}
}
</script>
