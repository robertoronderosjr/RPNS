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
	$result = $mysql ->Records();
	
	while ($row = mysql_fetch_array($result)){
		
		$to = $row['Email'];
		$netid = $row['NetID'];
		$message = "Hello ".$row['Name'].", \n\nA new course offering has been added to the system\n\n".
			"Course: ".$classNameString."\n".
			"Professor: ".$_SESSION['name']." ".$_SESSION['lastname']."\n".
			"Semester: ".$semester."\n\nLogin to your account for more information\n\n".
			"Sincerely,\nRPNS Team";
		mail($to,$subject,$message,$headers);	
		
	}
?>		