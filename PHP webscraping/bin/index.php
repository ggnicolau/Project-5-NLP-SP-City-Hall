<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(-1);

if(!empty($_POST["register-user"])) {
	/* Form Required Field Validation */
	foreach($_POST as $key=>$value) {
		if(empty($_POST[$key])) {
		$error_message = "All Fields are required";
		break;
		}
	}
	/* Password Matching Validation */
	if($_POST['password'] != $_POST['confirm_password']){ 
	$error_message = 'Passwords should be same<br>'; 
	}

	/* Email Validation */
	if(!isset($error_message)) {
		if (!filter_var($_POST["userEmail"], FILTER_VALIDATE_EMAIL)) {
		$error_message = "Invalid Email Address";
		}
	}

	/* Validation to check if gender is selected */
	if(!isset($error_message)) {
	if(!isset($_POST["gender"])) {
	$error_message = " All Fields are required";
	}
	}
	
	/* Validation to check if Terms and Conditions are accepted */
	if(!isset($error_message)) {
		if(!isset($_POST["terms"])) {
		$error_message = "Accept Terms and Conditions to Register";
		}
	}

	/* Validation to check if Captcha is digited correctly */
	if(!isset($error_message)) {
		if(!isset($_POST["verify"])) {
		$error_message = "Please, don't forget code validation";
		}
	}
	if ($_SESSION['verify']!=md5($_POST["verify"])){
	$error_message = "Please, digit the code correctly";
	}

	/* register to the database */
	if(!isset($error_message)) {					
		$mysqli = new mysqli("localhost", "dnelub", "Pisagor1p", "appetece");
		$stmt = $mysqli -> prepare('INSERT INTO  registered_users (user_name, first_name, last_name, password, email, gender, verify) VALUES (?,?,?,?,?,?,?)');
		$stmt->bind_param("sssssss", $_POST['userName'], $_POST['firstName'], $_POST['lastName'], md5($_POST["password"]), $_POST['userEmail'], $_POST['gender'], md5($_POST['verify']));
		$stmt->execute();
		
		if($stmt->execute()) {
			$error_message = "";
			$success_message = "You have registered successfully! Please verify your e-mail to complete your registration.";	
			unset($_POST);
			$stmt->close();
		} else {
			$error_message = "Problem in registration. Try Again!";	
		}
	}
}


?>
<html>
<head>
<title>User Registration Form</title>
<link rel="stylesheet" type="text/css" href="css/userRegistration.css" />
</head>
<body>
<form name="frmRegistration" method="post" action="">
<table border="0" width="500" align="center" class="table">
<?php if(!empty($success_message)) { ?>	
<div class="success-message"><?php if(isset($success_message)) echo $success_message; ?></div>
<?php } ?>
<?php if(!empty($error_message)) { ?>	
<div class="error-message"><?php if(isset($error_message)) echo $error_message; ?></div>
<?php } ?>
<tr>
<td>User Name</td>
<td><input type="text" class="InputBox" name="userName" value="<?php if(isset($_POST['userName'])) echo $_POST['userName']; ?>"></td>
</tr>
<tr>
<td>First Name</td>
<td><input type="text" class="InputBox" name="firstName" value="<?php if(isset($_POST['firstName'])) echo $_POST['firstName']; ?>"></td>
</tr>
<tr>
<td>Last Name</td>
<td><input type="text" class="InputBox" name="lastName" value="<?php if(isset($_POST['lastName'])) echo $_POST['lastName']; ?>"></td>
</tr>
<tr>
<td>Password</td>
<td><input type="password" class="InputBox" name="password" value=""></td>
</tr>
<tr>
<td>Confirm Password</td>
<td><input type="password" class="InputBox" name="confirm_password" value=""></td>
</tr>
<tr>
<td>Email</td>
<td><input type="text" class="InputBox" name="userEmail" value="<?php if(isset($_POST['userEmail'])) echo $_POST['userEmail']; ?>"></td>
</tr>
<tr>
<td>Gender</td>
<td><input type="radio" name="gender" value="Male" <?php if(isset($_POST['gender']) && $_POST['gender']=="Male") { ?>checked<?php  } ?>> Male
<input type="radio" name="gender" value="Female" <?php if(isset($_POST['gender']) && $_POST['gender']=="Female") { ?>checked<?php  } ?>> Female
</td>
</tr>
<tr>
<td>Code</td>
<td><img class="InputBoximg" src="php/image.php" /></td>
</tr>
<tr>
<td>Code Verification</td>
<td><input name="verify" type="text" id="verify" size="6" value="" style="width: 80px;" /></td>
</tr>
<tr>
<td>
<input type="checkbox" name="terms"> I accept Terms and Conditions
</td>
<td>
<input type="submit" name="register-user" value="Register" class="btnRegister">
<td>
</tr>
</table>
</form>
</body></html>
