<?php
/**
 * @author Catalina Laverde Duarte  
 * 
 **/
 

$i=0;
foreach($sectionIDS as $section){
		
	//Notification  for the professor
	$message = "You have successfully added a new course offering - ".$classNameString.":".$sectionNumber[$i]." for the ".$semester." semester.";
	
	$sql = "INSERT INTO `Notifications` (`C_ID`,`CO_ID`,`CS_ID`,`To_NetID`,`Message`,`Action`) 
		VALUES ('".$C_ID."',
		'".$CO_ID."',
		'".$section."',
		'".$_SESSION['netid']."', 
		'".$message."',
		'1')";
	
		echo "<br/>".$sql."<br/>";
	
	// Execute our query
	if (!$mysql -> Query($sql)) {
		echo "Failed adding notification for the professor: ".$mysql->Error()." QUERY: ".$sql;
		$mysql -> Kill();
		exit(1);
	} 
	$i++;	
}

//Notification  for the student
$i=0;
$message = "A new course offering has been added to the system - ".$classNameString.":".$sectionNumber[$i]." for the ".$semester." semester.";

foreach($sectionIDS as $section){
	
	$sql = "INSERT INTO `Notifications` (`C_ID`,`CO_ID`,`CS_ID`,`To_NetID`,`Message`,`Action`) 
		VALUES ('".$C_ID."',
		'".$CO_ID."',
		'".$section."',
		'student', 
		'".$message."',
		'1')";

	echo $sql;
	
	// Execute our query
	if (!$mysql -> Query($sql)) {
		echo "Failed adding notification for the professor: ".$mysql->Error()." QUERY: ".$sql;
		$mysql -> Kill();
		exit(1);
	} 
	$i++;	
}
?>