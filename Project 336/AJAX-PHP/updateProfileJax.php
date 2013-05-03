<?php
/**
 * @author David Zafrani
 */

session_start();


require('dbConnection.php');
require('phpass/PasswordHash.php');
echo "hey dave";
exit(1);
$passwordOldNotHashed = mysql_real_escape_string($_GET["pass"]);
//hashing password : 
$pwdHasher = new PasswordHash(8, FALSE);

// $hashedPassword is what will be stored in the database
$hashedOldPassword = $pwdHasher->HashPassword( $passwordOldNotHashed );
$sql="SELECT * FROM User WHERE NetID='".$_SESSION["netid"]."' AND Password='".$hashedOldPassword."'";
if (!$mysql -> Query($sql)) {
	echo "failed to check password: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
if ($mysql->RowCount()!=1){
	
}
?>