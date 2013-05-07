<?php
/**
 * @author Catalina Laverde Duarte  
 * 
 **/
 	$query = "SELECT * FROM Course WHERE C_ID=".$C_ID;
	$subject = "RPNS - Special Permission Number Requested";
	$from = "do-not-reply@rpns.rutgers.edu";
	$headers = "From:" . $from;
	
	//Getting course name and professor's netID
	if (!$mysql -> Query($query))
		$mysql -> Kill();
	
	$mysql -> MoveFirst();
	$row = $mysql -> Row();
	$courseName = $row->Name;
	echo $courseName."\n";
	$professorID = $row->Managed_By;
	echo $professorID."\n";
	
	
	//Getting student's email 
	$sql="
			SELECT Email FROM User WHERE NetID='".$_SESSION['netid']."'
		 ";
	if (!$mysql -> Query($sql))
		$mysql -> Kill();
	
	$mysql -> MoveFirst();
	$row = $mysql -> Row();
	$studentEmail = $row->Email;
	echo $studentEmail."\n";
	
	//Getting professor's email and name
	if (!$mysql -> Query("SELECT * FROM User WHERE NetID='".$professorID."'"))
	$mysql -> Kill();
	
	$mysql -> MoveFirst();
	$row = $mysql -> Row();
	$professorEmail = $row->Email;		
	$professorLastname = $row->Last_Name;	
	echo $professorEmail."\n";
	echo $professorLastname."\n";
	
	//Sending Email to Student
	$to = $studentEmail;
	echo $to."\n";
	$message = "Hello ".$_SESSION['name'].", \n\nYou have successfully requested a special permission number for ".$courseName.".\n\n".
		"Login to your account to check the status of your request.\n\n".
		"Sincerely,\nRPNS Team";
	echo $message."\n";
	mail($to,$subject,$message,$headers);
	
	//Sending Email to Professor
	$to = $professorEmail;
	echo $to."\n";
	$message = "Hello Professor ".$professorLastname.", \n\nA student has successfully requested a special permission number for your class ".$courseName.".\n\n".
		"Name: ".$_SESSION['name']." ".$_SESSION['lastname']."\n".
		"Login to your account for more details.\n\n".
		"Sincerely,\nRPNS Team";
	echo $message."\n";
	mail($to,$subject,$message,$headers);
	
?>