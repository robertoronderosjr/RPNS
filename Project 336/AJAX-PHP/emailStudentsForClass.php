<?php
/**
 * @author Catalina Laverde Duarte  
 * 
 **/
 	$query = "SELECT * FROM User WHERE User_Type=1";
	$subject = "RPNS - NEW COURSE AVAILABLE";
	$from = "do-not-reply@rpns.rutgers.edu";
	$headers = "From:" . $from;
	
	if (!$mysql -> Query($query))
		$mysql -> Kill();
	
	$mysql -> MoveFirst();
	while (!$mysql -> EndOfSeek()) {
		$row = $mysql -> Row();
		$to = $row->Email;
		$message = "Hello ".$row->Name.", \n\nA new course has been added to the system\n\n".
			"Course: ".$cName."\n".
			"Professor: ".$_SESSION['name']." ".$_SESSION['lastname']."\n\nLogin to your account for more information\n\n".
			"Sincerely,\nRPNS Team";
		mail($to,$subject,$message,$headers);	
	}
?>		