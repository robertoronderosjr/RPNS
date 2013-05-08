<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
 * @author Catalina Laverde Duarte
 */
 	 //Getting C_ID
	 $sql = "SELECT * FROM Course_Offering WHERE CO_ID='".$CO_ID."'";
	 
	 if (!$mysql -> Query($sql)) {
		echo "failed";
		$mysql -> Kill();
		exit(1);
	} 
	
	$mysql -> MoveFirst();
	$row = $mysql -> Row();
	$C_ID = $row->C_ID;
	
	//Getting course name and professor's netid
	 $sql = "SELECT * FROM Course WHERE C_ID='".$C_ID."'";
	 
	 if (!$mysql -> Query($sql)) {
		echo "failed";
		$mysql -> Kill();
		exit(1);
	} 
	
	$mysql -> MoveFirst();
	$row = $mysql -> Row();
	$courseName = $row->Name;
	$professorNetid = $row->Managed_By;
	
	//Getting Section Number
	 $sql = "SELECT * FROM Course_Section WHERE CS_ID='".$CS_ID."'";
	 
	 if (!$mysql -> Query($sql)) {
		echo "failed";
		$mysql -> Kill();
		exit(1);
	} 
	
	$mysql -> MoveFirst();
	$row = $mysql -> Row();
	$SectionNumber = $row->Section_Number;
	
	//Getting professor's information
	 $sql = "SELECT * FROM User WHERE NetID='".$professorNetid."'";
	 
	 if (!$mysql -> Query($sql)) {
		echo "failed";
		$mysql -> Kill();
		exit(1);
	} 
	
	$mysql -> MoveFirst();
	$row = $mysql -> Row();
	$professorName = $row->Name;
	$professorLastname = $row->Last_Name;
	$professorEmail = $row->Email;
	
	//Getting student's information
	 $sql = "SELECT * FROM User WHERE NetID='".$assignee."'";
	 
	 if (!$mysql -> Query($sql)) {
		echo "failed";
		$mysql -> Kill();
		exit(1);
	} 
	
	$mysql -> MoveFirst();
	$row = $mysql -> Row();
	$studentName = $row->Name;
	$studentLastname = $row->Last_Name;
	$studentEmail = $row->Email;	
	
	//Notification  for the professor
	$message = "You have successfully denied a permission number for - ".$courseName.":".$SectionNumber." to ".$studentName." ".$studentLastname.".";
	
	$sql = "INSERT INTO `Notifications` (`C_ID`,`CO_ID`,`CS_ID`,`To_NetID`,`Message`,`Action`) 
		VALUES ('".$C_ID."',
		'".$CO_ID."',
		'".$CS_ID."',
		'".$professorNetid."', 
		'".$message."',
		'5')";
		
	// Execute our query
	if (!$mysql -> Query($sql)) {	
		$mysql -> Kill();
		echo 'failed';
		exit(1);
	} 

	//Notification  for the student
	$message = "Your request has been denied - ".$courseName.":".$SectionNumber." by ".$professorName." ".$professorLastname.".<br>".
				"Comments: ".$comments;
	
	$sql = "INSERT INTO `Notifications` (`C_ID`,`CO_ID`,`CS_ID`,`To_NetID`,`Message`,`Action`) 
			VALUES ('".$C_ID."',
			'".$CO_ID."',
			'".$CS_ID."',
			'".$assignee."', 
			'".mysql_real_escape_string($message)."',
			'5')";
	
	
	// Execute our query
	if (!$mysql -> Query($sql)) {
		$mysql -> Kill();
		echo 'failed';
		exit(1);
	}	
?>