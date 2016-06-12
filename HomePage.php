<?php 

include_once './controller/SessionStart.php';
include_once "./controller/Data.php";
include_once "./controller/FunctionsForDatabase.php";
include_once "./controller/UserValidator.php";
include_once "./controller/User.php";


// TODO: change folder controller permission level, so people can NOT visit by URL

/* Prevent Manually Type in URL to go to this Page */
if( !empty($_SESSION['usr_id']) ){
	header('Location: ' . $_SESSION['mainpage_url']);
}

/* Log-in Validation */
if( isset($_POST['submit']) ){
	
	$_SESSION['usr_name'] = $_POST['username'];

	$login_error = "";
	$login_error = UserValidator::logInValidate( $_POST['username'] , $_POST['password'] );

	if( !empty($login_error) ) goto ifend;
	
	$check_status = User::passwordCheck($_POST['username'], $_POST['password']);
	if( $check_status === 1 ){
		User::login($_POST['username']);
	}
	else{
		$login_error = "Incorrect user name or password";
	}
	
ifend:	
}

//printDataInSession();
//printDataInCookie();
?>
<!-- HTML -->
<head>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>        
    <!-- jStorage Plugin -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/json2/20110223/json2.js"></script>
	<script src="https://raw.github.com/andris9/jStorage/master/jstorage.js"></script>
	<script> /* $.jStorage is now available */ </script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/json2/20110223/json2.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	<script src="https://raw.github.com/andris9/jStorage/master/jstorage.js"></script>
	<script> /* $.jStorage is now available */ </script>
	<!-- jStorage Plugin Finished -->
	<link rel="stylesheet" type="text/css" href="css/Text.css"/>	
	<link rel="stylesheet" type="text/css" href="css/Layout.css"/>
</head>

<body>
	<!-- <h1 style="text-align: center;">GENI Cinema</h1> -->
	<title>GENI Cinema</title><br />
	
	<!-- GENI Logo -->
	<div align="center">
	<img src="/Images/GENI-Cinema-Logo-weblogo.png">
	</div>	

	<!-- Log In Up Form -->
	<h4 class="italic">Please log in using your account</h4>
	<form method="POST" style="text-align: center;" action="<?php echo $_SERVER['PHP_SELF']; ?>" >	
		<table class="logInTable">
		<tr>
			<td align="right" style="text-align: right;">
				<img src="/Images/geni-logo-1.jpg" height=100px><br>
			</td>
		</tr>	
				
		<tr>
			<td style="font-size: 20px;">Enter Your Username:</td>
			<td><input class="text" type="text" value="<?php echo $_SESSION['usr_name'] ?>" id="username" name="username" maxlength='16'><br/></td>
		</tr>
		
		<tr>
			<td style="font-size: 20px;">Enter Your Password:</td>
			<td><input class="text" type="password" name="password"><br/></td>	
		</tr>
		
		<tr>
			<td align="center">
				<input class="btn" style="cursor:move" name="submit" type="submit" value="submit">
				<input class="btn" style="cursor:move" name="reset" type="reset" value="reset">
			</td>
		</tr>
		</table>
	</form><br>		
	<?php 
	 	if( isset($login_error) ){
			echo "<div align=\"center\" id=\"loginResult\">". $login_error . "</div>";
		} 
	?> 		

	<!-- Sign Up Button -->
	<div align="center">
	<button  class="btn" style="cursor:pointer" OnClick="location.href='SignUpPage.php'" type="submit">Sign Up</button>
	<br /><br />
	<button  class="btn" style="cursor:pointer" OnClick="location.href='HelpPage.html'" type="submit">Help</button>
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
	
</body>

<script>
/* Disable submitting from hitting Enter */
$(document).ready(function(){
	
	$(window).keydown(function(event){
		if( event.keyCode == 13 ){
			event.preventDefault();
			return false;
		}
	});
	
});

</script>
