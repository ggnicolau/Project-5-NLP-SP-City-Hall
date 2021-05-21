<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(-1);
 //mysqli_report(MYSQLI_REPORT_ALL);

function insertQuery($table, array $fields, array $values) {
		$mysqli = new mysqli("localhost", "root", "", "appetece");
		$query= "INSERT INTO  {$table} (".implode(",", $fields).") VALUES (".implode(',', array_fill(0, count($fields), '?')).")";
		$stmt = $mysqli -> prepare($query);
		$stmt->bind_param( ...$values);
			
		if ($stmt->execute()) {return true; $stmt->close();} else {return false; $stmt->close();}	
	}
	
function updateQuery($table, array $fields, array $values) {
		$mysqli = new mysqli("localhost", "root", "", "appetece");
		foreach($fields as $field) { $output []= $field."=?";}
		$query= "UPDATE {$table} SET ". implode(", ", $output) ." WHERE id = ?";
		$stmt = $mysqli -> prepare($query);
		$stmt->bind_param( ...$values);
			
		if ($stmt->execute()) {return true; $stmt->close();} else {return false; $stmt->close();}	
	}
	
function deleteQuery($table, $id) {
		$mysqli = new mysqli("localhost", "root", "", "appetece");		
		$query= "DELETE FROM {$table} WHERE id = ?";
		$stmt = $mysqli -> prepare($query);
		$stmt->bind_param('i',$id);
			
		if ($stmt->execute()) {return true; $stmt->close();} else {return false; $stmt->close();}	
	}
// upload photo	
function uploadImage($name) {
if (isset($_FILES['foto'])) {	
	if($_FILES['foto']['error'] > 0) { echo 'Error during uploading, try again'; }
	$extsAllowed = array( 'jpg', 'jpeg', 'png', 'gif' );	
	$extUpload = strtolower( substr( strrchr($_FILES['foto']['name'], '.') ,1) ) ;

	if (in_array($extUpload, $extsAllowed) ) { 
	$filename = "img/foto-usuario/".$name.'.'.$extUpload;
	
	$result = move_uploaded_file($_FILES['foto']['tmp_name'], $filename);	
	return $filename;
	} else { echo 'File is not valid. Please try again'; return false; }
}	
}
		


if((!empty($_POST["update"]))&&(isset($_GET['id']))) {
	/* Form Required Field Validation */
	foreach($_POST as $key=>$value) {
		if(empty($_POST[$key])) {
		$error_message = "All Fields are required";
		break;
		}
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

	/* register to the database */
	if(!isset($error_message)) {	
		$table="registered_users";
		$filename=uploadImage($_POST["lastName"].'-'.$_POST["firstName"].'-'.$_POST["birthday"]); 
		$fields=array('user_name', 'first_name', 'last_name', 'birthday','email', 'telefone', 'gender','foto');
		$values=array("ssssssssi", $_POST["userName"],$_POST["firstName"],$_POST["lastName"],$_POST["birthday"],$_POST["userEmail"],$_POST["userTel"],$_POST["gender"],$filename,$_GET['id']);
		if(updateQuery($table, $fields, $values)) {
			$error_message = "";
			$success_message = "You have updated successfully!";	
			unset($_POST);
		} else {
			$error_message = "Problem in updating. Try Again!";	
		}		
	}
}

if(!empty($_POST["delete"])) 
{ 
	deleteQuery('registered_users', $_GET['id']);
	$success_message = "You have deleted successfully!";	 
}else if(isset($_POST["delete"])) {
	$error_message = "Problem in deleting. Try Again!";	
}

$mysqli = new mysqli("localhost", "root", "", "appetece");
$stmt1 = $mysqli->query("SELECT * FROM registered_users ORDER by last_name");
while($rows = mysqli_fetch_assoc($stmt1)) {$userList[] = $rows;}
$stmt1->close();

if (isset($_GET['id'])) {
$stmt2 = $mysqli->prepare("SELECT * FROM registered_users where id=?");
$stmt2->bind_param("i", $_GET['id']);
$stmt2->execute();
$result = $stmt2->get_result();
$userInfo = $result->fetch_array();
$stmt2->close();
}		
		
?>
<html>
<head>
<title>User View Form</title>
<link rel="stylesheet" type="text/css" href="css/userRegistration.css" />
</head>
<body>
<div class="leftColumn">
		<legend>Lista de Usuarios</legend>
		<?php  if (isset($userList)){ ?>
				<ul>
		<?php  foreach($userList as $userList){ ?>
                <li>
                <a href="<?php echo 'user_view.php?id='.$userList['id']; ?>"> <?php echo $userList['first_name'].' '.$userList['last_name']; ?> </a>
                </li>
                <?php  } ?> 
                </ul>
        <?php  } ?> 	
</div>


<form enctype="multipart/form-data" name="frmRegistration" method="post" action="" >
<table border="0" width="500" align="center" class="table">
<?php if(!empty($success_message)) { ?>	
<div class="success-message"><?php if(isset($success_message)) echo $success_message; ?></div>
<?php } ?>
<?php if(!empty($error_message)) { ?>	
<div class="error-message"><?php if(isset($error_message)) echo $error_message; ?></div>
<?php } ?>
<tr>
<td>User Name</td>
<td><input type="text" class="InputBox" name="userName" value="<?php if(isset($userInfo['user_name'])) echo $userInfo['user_name']; ?>"></td>
<td rowspan=3><img src="<?php if (isset($userInfo['foto'])){echo $userInfo['foto'];}?>" alt="" width="160" height="160"/> </td>
</tr>

<tr>
<td>First Name</td>
<td><input type="text" class="InputBox" name="firstName" value="<?php if(isset($userInfo['first_name'])) echo $userInfo['first_name']; ?>"></td>
</tr>
<tr>
<td>Last Name</td>
<td><input type="text" class="InputBox" name="lastName" value="<?php if(isset($userInfo['last_name'])) echo $userInfo['last_name']; ?>"></td>
</tr>
<tr>
<td>Birth Day</td>
<td><input type="text" class="InputBox" name="birthday" value="<?php if(isset($userInfo['birthday'])) echo $userInfo['birthday']; ?>"></td>
</tr>
<tr>
<td>Email</td>
<td><input type="text" class="InputBox" name="userEmail" value="<?php if(isset($userInfo['email'])) echo $userInfo['email']; ?>"></td>
</tr>
<tr>
<td>Telefon</td>
<td><input type="text" class="InputBox" name="userTel" value="<?php if(isset($userInfo['telefone'])) echo $userInfo['telefone']; ?>"></td>
</tr>
<tr>
<tr>
<td>Foto</td>
<td><input type="file" name="foto" ></td>
</tr>
<tr>
<td>Gender</td>
<td><input type="radio" name="gender" value="Male" <?php if(isset($userInfo['gender']) && $userInfo['gender'] =="Male")  {echo "checked";} ?>> Male
<input type="radio" name="gender" value="Female" <?php if(isset($userInfo['gender']) && $userInfo['gender'] =="Female")  {echo "checked";}?>> Female
</td>
</tr>

<tr>
<td>
</td>
<td>
<input type="submit" name="update" value="Update" class="btnRegister">
<input type="submit" name="delete" value="Delete" class="btnRegister">
<td>
</tr>
</table>
</form>
</body></html>
