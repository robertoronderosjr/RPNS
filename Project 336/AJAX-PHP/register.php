<?php
/**
 * @author Roberto Ronderos Botero
 */

session_start();


require('dbConnection.php');
require('phpass/PasswordHash.php');


//Add user to DB and send him and e-mail with its informatio

//get post data

$name = mysql_real_escape_string($_POST['fname']);
$lastname = mysql_real_escape_string($_POST['lname']); 
$email = mysql_real_escape_string($_POST['email']);
$gender = mysql_real_escape_string($_POST['genderHidden']);
$dob = mysql_real_escape_string($_POST['dob']);
$netid =mysql_real_escape_string(trim(strtolower($_POST['netid']))); 
$passwordNotHashed = mysql_real_escape_string($_POST['passwd']);
$accountType = mysql_real_escape_string($_POST['aType']); 

//hashing password : 
$pwdHasher = new PasswordHash(8, FALSE);

// $hashedPassword is what will be stored in the database
$hashedPassword = $pwdHasher->HashPassword( $passwordNotHashed );

//insert query
$sql = "INSERT INTO User (Name, Last_Name, Gender, Email, DOB, NetID, Password, User_Type) 
        VALUES ('".$name."', '".$lastname."','".$gender."','".$email."','".$dob."','".$netid."','".$hashedPassword."','".$accountType."')"; 


// Execute our query 
$success=true;
if (! $mysql->Query($sql)) {$mysql->Kill(); $success=false;} 

//Finally Send an email if all went succesfully
if($success){
	$to = $email;
	$subject = "RPNS - Registration Succesful!";
	$message = "Hello ".$name.", \n\nYour account has been succesfully registered in our system. Please keep the following information for your records:\n\n".
				"NetID: ".$netid."\n".
				"Password: ".$passwordNotHashed."\n\n".
				"Sincerely,\nRPNS Team";
	$from = "do-not-reply@rpns.rutgers.edu";
	$headers = "From:" . $from;
	mail($to,$subject,$message,$headers);
	
	$_SESSION['loggedin'] = $passwordNotHashed;
	$_SESSION['netid'] = $netid;
	$_SESSION['name'] = $name;
	$_SESSION['lastname'] = $lastname;
	$_SESSION['type'] = $accountType;
	
	header('Location: http://cs336-31.rutgers.edu/index.php?alert=registered');
	/*if(strpos($_SERVER['HTTP_REFERER'],"?alert")!=false){ //url doesn't contain it
			$urlb = explode("?",$_SERVER['HTTP_REFERER']);
			$url = $urlb[0];
		   header('Location: ' . $url."?alert=registered");
	}else{
		header('Location: ' . $_SERVER['HTTP_REFERER']."?alert=registered");
	}*/
}

?>

