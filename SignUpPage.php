<?php 

include_once './controller/SessionStart.php';
include_once "./controller/UserValidator.php";	
include_once "./controller/FunctionsForDatabase.php";
include_once "./controller/User.php";


/* Check Log-in State */
if( !empty($_SESSION['usr_id']) ){
	header('Location: ' . $_SESSION['homepage_url']);
}

$user_info = array( "name"	=> $_POST['username'],
					"email"	=> $_POST['email']
);

/* Check User Name Available */
$signup_error = "";
if( isset($_POST['CheckAvailable']) ){
		
	$signup_error = UserValidator::userNameValidate($_POST['username']);
	if( !empty($signup_error) ) goto endA;
	
	$username_available = User::usrnameAvailable($_POST['username']);
	if($username_available == 1){
		$signup_error = "User name is available";
		
	}else{
		$signup_error = "User name already exist";
		
	}	
	
endA:	
}

/* Sign Up */
if( isset($_POST['UserSignUp']) ){
	
	$signup_error = UserValidator::userNameValidate($_POST['username']);
	if( !empty($signup_error) ) goto endB;
	
	$signup_error = UserValidator::pwdValidate($_POST['password'], $_POST['password_confirm']);
	if( !empty($signup_error) ) goto endB;
	
	$signup_error = UserValidator::emailValidate($_POST['email']);
	if( !empty($signup_error) ) goto endB;
	
	$signup_error = UserValidator::accountTypeValidate($_POST['select']);
	if( !empty($signup_error) ) goto endB;
	
	$username_available = User::usrnameAvailable($_POST['username']);
	if($username_available == 0){
		$signup_error = "User name already exists";
	}else{
		User::signUp($_POST['username'], $_POST['password'],
					 $_POST['email'], $_SESSION['date'],
					 $_POST['select'], $_POST['fname'], $_POST['lname']);
	}
	
endB:
}

//printDataInSession();
?>
<!-- HTML -->
<head>
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="css/Text.css" />
	<link rel="stylesheet" type="text/css" href="css/Layout.css" />
	<title>Sign Up A New Account</title>
</head>

<body>

	<!-- GENI Logo -->
	<div align="center">
	<img src="/Images/GENI-Cinema-Logo-weblogo.png">
	</div>	

	<div align="center" class='SignUpForm'>
	<form method="POST" action="<?php  echo $_SERVER['PHP_SELF']; ?>" >	
	<fieldset style="text-align: center;">
	<legend>Sign Up Form</legend>  	
		<h4 class="italic">Enter New User Name: ( at least 3 letters, max 16 letters )</h4> 
			<input type="text" name="username" id="username" maxlength='16' value="<?php echo $user_info['name']; ?>"/>
			<br/>
			<input type="submit" style="background:#FFFFFF;border-style:outset;border-width:1px;color:#0000FF" name="CheckAvailable" value="Check User Name Available"> 
		<br>
		
		<h4 class="italic">Enter Password: ( at least 6 letters, max 16 letters )</h4>
			<input type="password" name="password" maxlength='16' /> 
		<br>
		
		<h4 class="italic">Confirm Password:</h4>
			<input type="password" name="password_confirm" maxlength='16'/> 
		<br> 		
		
		<h4 class="italic">Email Address:</h4>
			<input type="email" name="email" value="<?php echo $user_info['email']; ?>"/>
		<br> 

		<h4 class="italic">First Name:</h4> 
			<input type="text" name="fname" id="fname" value="<?php echo $user_info['fname']; ?>"/>
		<br>

		<h4 class="italic">Last Name:</h4> 
			<input type="text" name="lname" id="lname" value="<?php echo $user_info['lname']; ?>"/>
		<br>		
		
		<h4 class="italic">Please select your account type</h4>  
			<select name="select" size="2">
				<option value="student">student</option>
				<option value="professor">professor</option> 
			</select>
		<br><br><br>		
				
		<table align="center">
		<tr>
			<td>
			<input class="btn" style="cursor:move" type="submit" name="UserSignUp" value="Submit"/>
			<input class="btn" style="cursor:move" type="reset" name="reset" value="Reset"/>
			</td>
		</tr>
		</table>
		
	</fieldset>	
	</form>
	</div>
	
	<?php
	if( !empty($signup_error) ){
		echo "<div  align=\"center\" id='signUpResult'>". $signup_error . "</div>";
	} 
	?> 
	<br/>	
		
	<div align="center">
	<button class="btn" style="cursor:move" onClick="location.href='./controller/Logout.php'" type="submit">Back to Main Page</button>
	</div><br/>

	<!-- GENI Logo -->
	<table align="center">
		<tr>
 			<td style="text-align: right;"> 	
				<img src="/Images/geni-logo-1.jpg" height=100px><br><br>
			</td>
		</tr>	
	</table><br /><br />	
	
	<!-- Put Clemson & Wisconson Logo on one line -->
	<table align="center">
		<tr>
			<td style="text-align: center;">
				<img src="/Images/ClemsonLogo.png" height=100px><br><br>
			</td>
			<td style="text-align: right;">
				<img src="/Images/WisconsinLogo.jpg" height=100px><br>	
			</td>			
			
		</tr>	
	</table><br /><br />
	
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


