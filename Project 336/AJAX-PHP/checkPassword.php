<?php
/**
 * @author David Zafrani
 */
 session_start();
require("dbConnection.php");
require('phpass/PasswordHash.php');

if (! $mysql->Query("SELECT Password FROM User WHERE NetID='".trim(strtolower($_SESSION['netid']))."'"))$mysql->Kill(); 
$mysql -> MoveFirst();
$row = $mysql -> Row();
//create password hasher object with the same parameters that were used to hash the initial pwd.
$pwdHasher = new PasswordHash(8, FALSE);
				
//validate password against hashed one
$validPassword = $pwdHasher->CheckPassword( $_GET['oldpass'] , $row->Password );

if($validPassword){
	echo json_encode(TRUE);
}
else{
	echo json_encode(FALSE);
}

 

?>