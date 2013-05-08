<?php
/**
 * @author Catalina Laverde Duarte
 */
 
 	$subject = "RPNS - Special Permission Number Denied";
	$from = "do-not-reply@rpns.rutgers.edu";
	$headers = "From:" . $from;
	
 	//Sending Email to Student
	$to = $studentEmail;
	$message = "Hello ".$studentName.", \n\nYour request has been denied - ".$courseName.":".$SectionNumber." by ".$professorName." ".$professorLastname.".\n".
			   "Comments: ".$comments."\n\n".
		       "Login to your account for more details.\n\n".
		       "Sincerely,\nRPNS Team";
	mail($to,$subject,$message,$headers);
	
	//Sending Email to Professor
	$to = $professorEmail;
	$message = "Hello Professor ".$professorLastname.", \n\nYou have successfully denied a permission number for - ".$courseName.":".$SectionNumber." to ".$studentName." ".$studentLastname."\n\n".
		"Sincerely,\nRPNS Team";
	mail($to,$subject,$message,$headers);
 
?>