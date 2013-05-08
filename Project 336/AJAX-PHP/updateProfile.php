<?php
/**
 * @author David Zafrani
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
$passwordOldNotHashed = mysql_real_escape_string($_POST['oldpass']);
$passwordNewNotHashed = mysql_real_escape_string($_POST['passwd']);
$accountType = mysql_real_escape_string($_POST['aType']); 

//hashing password : 
$pwdHasher = new PasswordHash(8, FALSE);

// $hashedPassword is what will be stored in the database
$hashedOldPassword = $pwdHasher->HashPassword( $passwordOldNotHashed );
$hashedNewPassword = $pwdHasher->HashPassword( $passwordNewNotHashed );
$sql="SELECT * FROM User WHERE NetID='".$_SESSION["netid"]."' AND Password='".$hashedOldPassword."'";
if (!$mysql -> Query($sql)) {
	echo "failed to check password: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
if ($mysql->RowCount()!=1){
	
}
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
	$message = "Hello ".$name.", \n\n Your account has been succesfully registered in our system. Please keep the following information for your records:\n\n".
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

}

?>

