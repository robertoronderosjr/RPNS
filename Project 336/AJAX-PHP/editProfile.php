<?php
/**
* @author Roberto Ronderos Botero
*/
session_start();


require('dbConnection.php');
require('phpass/PasswordHash.php');

$name = mysql_real_escape_string($_POST['fname']);
$lastname = mysql_real_escape_string($_POST['lname']); 
$email = mysql_real_escape_string($_POST['email']);
$gender = mysql_real_escape_string($_POST['genderHidden']);
$dob = mysql_real_escape_string($_POST['dob']);
$passwordNotHashed = mysql_real_escape_string($_POST['passwd']);

//hashing password : 
$pwdHasher = new PasswordHash(8, FALSE);

// $hashedPassword is what will be stored in the database
$hashedPassword = $pwdHasher->HashPassword( $passwordNotHashed );

//insert query
$sql = "UPDATE User 
		SET Name='".$name."', Last_Name='".$lastname."', Gender='".$gender."', Email='".$email."', DOB='".$dob."', Password='".$hashedPassword."' 
        WHERE NetID='".$_SESSION["netid"]."'";
// Execute our query 
$success=true;
if (! $mysql->Query($sql)) {$mysql->Kill(); $success=false;} 

//Finally Send an email if all went good
if($success){
	$to = $email;
	$subject = "RPNS - Account information Changed!";
	$message = "Hello ".$name.", \n\n Your account has been succesfully edited in our system. Please keep the following information for your records:\n\n".
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
	
	header('Location: http://cs336-31.rutgers.edu/profile.php?alert=profileEdited');
	
}
    
?>