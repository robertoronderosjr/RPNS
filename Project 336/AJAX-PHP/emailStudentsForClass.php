<?php
/**
 * @author David Zafrani
 */
 session_start();
require("dbConnection.php");
function emailStudentsAbout($class, $professor,$message){
	global $mysql;
	$sql="SELECT * User WHERE User_Type=1";
	$subject = "New class available for ".$professor;
	$message = $message;
	$from ="do-not-reply@rpns.rutgers.edu" ;
	$headers = "From:" . $emailFrom;
	if (!$mysql -> Query($query))
		$mysql -> Kill();
	$mysql -> MoveFirst();
	while (!$mysql -> EndOfSeek()) {
		$row = $mysql -> Row();
		$to = $row->Email;
		mail($to,$subject,$message,$headers);	
	}
	
	
	header('Location: ' . $_SERVER['HTTP_REFERER']); 
	
	
	
}
