<?php
//start session and include database
session_start();
include("dbconnect.php");//kuja kwa database inaitwa login LOGIN DATABASE
/*establish connection with database
$connect = mysqli_connect('localhost','root','','login');
if(!$connect){
	echo "Unable to establish connection";
}
*/
if(isset($_POST['registerBtn'])){
	//$id = mysqli_real_escape_string($connect, $_POST['id']);
	$fname = mysqli_real_escape_string($connect, $_POST['fname']);
	$lname = mysqli_real_escape_string($connect, $_POST['lname']);
	$email = mysqli_real_escape_string($connect, $_POST['email']);
	$password = mysqli_real_escape_string($connect, $_POST['password']);
	$cpassword = mysqli_real_escape_string($connect, $_POST['cpassword']);

	//check of passwords match
	if($password!=$cpassword){
		$_SESSION['status'] = 'Passwords do not match';
		header("Location:register.php?reason=error");
		//$message = "<div class='text-danger'>Passwords do not match</div>";
	}else{
		//check if email already exists
		//enda kwa table ya users alafu uangalie kama email inakuwa repeated
		$checkIfEmailExists = mysqli_query($connect, "SELECT *FROM users WHERE email='$email'");
		//count number of rows that umepat email inakuwa repeated
		if(mysqli_num_rows($checkIfEmailExists)>0){
			 $_SESSION['status'] = 'E-Mail already exists';
			 header("Location:register.php?reason=error");
		     //$message = "<div class='text-danger'>E-Mail already exists</div>";	
		}else{
			//encrypt the password
			$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
			//insert dat to database
			$insertDataToDatabase = mysqli_query($connect, "INSERT INTO users(`fname`, `lname`, `email`, `password`) VALUES ('$fname','$lname','$email','$hashedPassword')");
			if(!$insertDataToDatabase){
				$_SESSION['status'] = 'Unable to save data...Please try again';
				header("Location:register.php?reason=error");
				//$message = "<div class='text-danger'>Unable to save data...Please try again</div>";
			}else{
				$_SESSION['loggedInUser'] = $email;
				header("Location:home.php");
			}
		}
	}
}
?>
