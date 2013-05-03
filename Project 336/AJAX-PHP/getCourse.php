<?php
/**
 * @author Roberto Ronderos Botero
 */

require ("dbConnection.php");

$C_ID = $_GET['C_ID'];
$courseArray=Array();

/*Get basic info*/
$sql = "SELECT * FROM Course WHERE C_ID='".$C_ID."'";
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "Failed retrieving Course: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$mysql->MoveFirst(); 
$row = $mysql->Row(); 
$courseArray['Major']=$row->M_ID;
$courseArray['Name']=$row->Name;
$courseArray['Managed_by']=$row->Managed_By;

/*Get course offering*/
$sql = "SELECT * FROM Course_Offering WHERE C_ID='".$C_ID."'";
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "Failed retrieving Course Offering: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$mysql->MoveFirst(); 
$row = $mysql->Row(); 
$courseArray['CO_ID']=$row->CO_ID;
//get semesters
$courseArray['Semesters']=array();
$semesters = $row->Semester;
$semester = explode(",", $semesters);
foreach($semester as $s){
	$s=str_replace('"', "", $s);
	$s=trim($s);
	array_push($courseArray['Semesters'],$s);
}
$courseArray['MAX_SIZE']=$row->MAX_SIZE;

/*Get course sections*/
$courseArray['Sections']=array();
$sql = "SELECT * FROM Course_Section WHERE CO_ID='".$courseArray['CO_ID']."'";
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "Failed retrieving Course Sections: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$mysql->GetHTML();
$mysql->MoveFirst(); 

while (! $mysql->EndOfSeek()) { 
    $row = $mysql->Row();
	$section = array();
	$section['Section_Number'] =  $row->Section_Number; 
	$section['Teached_By'] =  $row->Teached_By; 
	array_push($courseArray['Sections'],$section);
} 

/*Get course requirements*/
$courseArray['Requirements']=array();
$sql = "SELECT * FROM Course_Requirements WHERE Requirer_ID='".$C_ID."'";
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "Failed retrieving Course Requirements: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$mysql->MoveFirst(); 
while (! $mysql->EndOfSeek()) { 
    $row = $mysql->Row();
	$sql2 = "SELECT Name FROM Course WHERE C_ID='".$row->Requirement_ID."'";
	// Execute our query
	$mysql2 = new MySQL(true,DBNAME, DBHOST, DBUSERNAME, DBPASSWORD);
	if ($mysql2->Error()) $mysql2->Kill();  
	if (!$mysql2 -> Query($sql2)) {
		echo "Failed retrieving Course Name: ".$mysql2->Error();
		$mysql2 -> Kill();
		exit(1);
	} 
	$mysql2->MoveFirst(); 
	$row2= $mysql2->Row();
	$requirement=array();
	$requirement['C_ID'] =$row->Requirement_ID;
	$requirement['Name'] = $row2->Name;
	array_push($courseArray['Requirements'],$requirement);
} 

/*Get Professor's Course Requirements*/
$courseArray['Used_Criteria']=array();
$sql = "SELECT * FROM Prof_Course_Requirements WHERE C_ID='".$C_ID."' AND Prof_ID='".$courseArray['Managed_by']."'";
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "Failed retrieving Course Used Criteria: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$mysql->MoveFirst(); 
while (! $mysql->EndOfSeek()) { 
    $row = $mysql->Row();
	$criteria=array();
	$criteria['PCRID']=$row->PCR_ID;
	$criteria['Type']=$row->Type;
	$criteria['Rank']=$row->Rank;
	$criteria['Values']=$row->Values; 
    array_push($courseArray['Used_Criteria'],$criteria);
} 

echo json_encode($courseArray);
 

?>