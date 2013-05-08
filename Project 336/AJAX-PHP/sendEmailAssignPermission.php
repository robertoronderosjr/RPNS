<?php
/**
 * @author Catalina Laverde Duarte
 */

 	$subject = "RPNS - Special Permission Number Assigned";
	$from = "do-not-reply@rpns.rutgers.edu";
	$headers = "From:" . $from;
	
 	//Sending Email to Student
	$to = $studentEmail;
	$message = "Hello ".$studentName.", \n\nA permission number has been successfully assigned to you for ".$courseName.":".$SectionNumber." by ".$professorName." ".$professorLastname."\n\n".
		"Login to your account to view and use your number.\n\n".
		"Sincerely,\nRPNS Team";
	mail($to,$subject,$message,$headers);
	
	//Sending Email to Professor
	$to = $professorEmail;
	$message = "Hello Professor ".$professorLastname.", \n\nYou have successfully assigned a permission number for - ".$courseName.":".$SectionNumber." to ".$studentName." ".$studentLastname."\n\n".
		"Login to your account for more details.\n\n".
		"Sincerely,\nRPNS Team";
	mail($to,$subject,$message,$headers);

?>