<?php
/**
 * @author Catalina Laverde Duarte  
 **/
 
//Getting section ID
$Section_ID = $mysql->GetLastInsertID();

//Getting course name
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

//Getting semester of the section
$sql = "SELECT Semester FROM Course_Offering WHERE CO_ID='".$CO_ID."'";

if (!$mysql -> Query($sql)) {
	echo "failed";
	$mysql -> Kill();
	exit(1);
} 

$mysql -> MoveFirst();
$row = $mysql -> Row();
$courseSemester = $row->Semester;

//Notification  for the professor
$message = "You have successfully added a new section for your course offering - ".$courseName.":".$Section_Number." for the ".$courseSemester." semester.";

$sql = "INSERT INTO `Notifications` (`C_ID`,`CO_ID`,`CS_ID`,`To_NetID`,`Message`,`Action`) 
	VALUES ('".$C_ID."',
	'".$CO_ID."',
	'".$Section_ID."',
	'".$_SESSION['netid']."', 
	'".$message."',
	'8')";


// Execute our query
if (!$mysql -> Query($sql)) {	
	$mysql -> Kill();
	echo 'failed';
	exit(1);
} 


//Notification  for the student
$message = "A new section has been added to the system - ".$courseName.":".$Section_Number." for the ".$courseSemester." semester.";

$sql = "INSERT INTO `Notifications` (`C_ID`,`CO_ID`,`CS_ID`,`To_NetID`,`Message`,`Action`) 
		VALUES ('".$C_ID."',
		'".$CO_ID."',
		'".$Section_ID."',
		'student', 
		'".$message."',
		'7')";


// Execute our query
if (!$mysql -> Query($sql)) {
	$mysql -> Kill();
	echo 'failed';
	exit(1);
}

//Send email to Students
//Getting professor's email, name and lastname
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

$query = "SELECT * FROM User WHERE User_Type=1";
$subject = "RPNS - NEW SECTION AVAILABLE";
$from = "do-not-reply@rpns.rutgers.edu";
$headers = "From:" . $from;

if (!$mysql -> Query($query))
	$mysql -> Kill();
$result = $mysql ->Records();

while ($row = mysql_fetch_array($result)){
	
	$to = $row['Email'];
	$netid = $row['NetID'];
	$message = "Hello ".$row['Name'].", \n\nA new section for ".$courseName." has been added to the system\n\n".
		"Section Number: ".$Section_Number."\n".
		"Professor: ".$professorName." ".$professorLastname."\n".
		"Semester: ".$courseSemester."\n\nLogin to your account for more information\n\n".
		"Sincerely,\nRPNS Team";
	mail($to,$subject,$message,$headers);			
}
echo "success";
?>