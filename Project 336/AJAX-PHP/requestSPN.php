<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
/**
 * @author Roberto Ronderos Botero
 */
 
require ("dbConnection.php");

echo "Course id:". $C_ID = $_POST['C_ID'];
echo "<br/>";
echo "Course section id:".$CS_ID = $_POST['CS_ID'];
echo "<br/>";
echo "netid:".$netid = $_SESSION['netid'];
echo "<br/>";
date_default_timezone_set('America/New_York');
$date = date('Y-m-d H:i:s');
$dateStamp = new DateTime();
echo $dateStamp= 1/($dateStamp->getTimestamp()/1000000000);

/*The first thing to do is check if there are any available permission numbers for that section*/
$sql = "SELECT * FROM Permissions WHERE CS_ID='".$CS_ID."' AND Available='y'";
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "Failed retrieving permission numbers: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
if($mysql->RowCount()>0){
	/*Select Used Criteria for that class, so that we can see what to actually $_POST*/
	$sql = "SELECT * FROM Prof_Course_Requirements WHERE C_ID='".$C_ID."'";
	// Execute our query
	if (!$mysql -> Query($sql)) {
		echo "Failed retrieving course used criteria: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
	$studenScore=0;
	$result = $mysql ->Records();
	$customQuestionCounter=0;
	while ($row = mysql_fetch_array($result)) {
		echo "<br/>"; 
		echo "RANK USED:".$row['Rank']."<br/>";
		switch($row['Type'])
		{
			case 'requestedDate':
				echo "requested date, time stamp :".$dateStamp."<br/>";
				$studenScore+=$dateStamp/$row['Rank'];
				echo "<br/>Request Date score calulated:, "; echo $dateStamp/$row['Rank']; echo " points to student";
				break;				
			case 'universityYear':
				echo "Student current year:".$studentsCurrentYear = trim($_POST['preferedYearModal']);
				echo "<br/>preferred current year:".$preferred = trim($row['Values']);
				if(preg_match('/'.$studentsCurrentYear.'/',$preferred)){
					$studenScore+=10/$row['Rank'];
					echo "<br/>University Year Matched, "; echo 10/$row['Rank']; echo " points to student";
				}
				break;
			case 'creditsCompleted':
				echo "Student credits completed:".$studentsCreditsCompleted = intval(trim($_POST['creditsCompleted']));
				echo "<br/>preferred credits completed:".$preferred = intval(trim($row['Values']));
				if($studentsCreditsCompleted>=$preferred){
					$studenScore+=10/$row['Rank'];
					echo "<br/>Credits completed >= than preferred, "; echo 10/$row['Rank']; echo " points to student";
				}
				break;
			case 'gradesPreReq':
				echo "preferred Grade for requirements:".$preferred= getGradeValue(trim($row['Values']));
				
				/*Get course Pre-Reqs*/
				$sql = "SELECT c.Name,cr.Requirement_ID FROM Course as c,Course_Requirements as cr WHERE c.C_ID=cr.Requirement_ID AND cr.Requirer_ID='".$C_ID."'";
				// Execute our query
				if (!$mysql -> Query($sql)) {
					echo "Failed retrieving course requirements: ".$mysql->Error();
					$mysql -> Kill();
					exit(1);
				}
				$result2 = $mysql ->Records();
				while($row2 = mysql_fetch_array($result2)){
					echo "<br/>Student grade in requirement:".$gradeInrequirement= getGradeValue($_POST[$row2['Requirement_ID']]);
					if($gradeInrequirement>=$preferred){
						$studenScore+=10/$row['Rank'];
						echo "<br/>Grade in requirement was >=, "; echo 10/$row['Rank']; echo " points to student<br/>";
					}
				}
				break;
			case 'gpa':
				echo "Student gpa:".$studentsGPA = floatval(trim($_POST['currentGPA']));
				echo "<br/>preferred GPA: ".$preferred = floatval(trim($row['Values']));
				if($studentsGPA>=$preferred){
					$studenScore+=10/$row['Rank'];
					echo "<br/>GPA >= than preferred, "; echo 10/$row['Rank']; echo " points to student";
				}
				break;
			case 'major':
				echo "Student major: ".$studentsMajor = strtolower(trim($_POST['major']));
				$sql = "SELECT Name FROM Majors WHERE M_ID='".$row['Values']."'";
				// Execute our query
				if (!$mysql -> Query($sql)) {
					echo "Failed retrieving major name: ".$mysql->Error();
					$mysql -> Kill();
					exit(1);
				}
				$result3 = $mysql ->Records();
				$row3 = mysql_fetch_array($result3);
				echo "<br/>preferred Major: ".$preferred = strtolower(trim($row3['Name']));
				if(preg_match('/'.$studentsMajor.'/',$preferred)){
					$studenScore+=10/$row['Rank'];
					echo "<br/>Major is the one preferred, "; echo 10/$row['Rank']; echo " points to student";
				}
				break;
			case 'ck':
				$customQuestionCounter++;
				//get answer from student
				echo "Student answer to question ck: ".$answer = $_POST['ck'.$customQuestionCounter];
				//start looking for professors keywords
				$jsonObj = json_decode($row['Values'],true);
				for($j=1;$j<sizeof($jsonObj);$j++){
					echo "<br/>keyword: ".$keyword = $jsonObj[$j]['keyword'];
					$importance = intval($jsonObj[$j]['importance']);
					//if keyword is found in answer add the score depending on professors importance, score = 2*importance
					if(preg_match('/'.$keyword.'/',$answer)){
						$studenScore+=(2*$importance)/$row['Rank'];
						echo "<br/>Keyword found,". (2*$importance)/$row['Rank']." to student";
					}
				}
				break;
			case 'cb':
				$customQuestionCounter++;
				//get checked values from student
				$checkedValues = $_POST['cb'.$customQuestionCounter];
				print_r($checkedValues);
				$jsonObj = json_decode($row['Values'],true);
				for($j=0;$j<sizeof($checkedValues);$j++){
					echo "<br/>Checbox Student: ".$checkValue = $checkedValues[$j];
					for($z=1;$z<sizeof($jsonObj);$z++){
						echo "<br/>Checbox Professor: ".$checkbox = $jsonObj[$z]['checkbox'];
						$importance = intval($jsonObj[$z]['importance']);
						//if checkbox in professor is found in checkboxes from student add the score depending on professors importance, score = 2*importance
						if(preg_match('/'.$checkValue.'/',$checkbox)){
							$studenScore+=(2*$importance)/$row['Rank'];
							echo "<br/>CheckBox found,". (2*$importance)/$row['Rank']." to student";
						}
					}										
				}
				break;
			case 'rb';
				$customQuestionCounter++;
				//get selected radio from student
				echo "Student radio selected rb: ".$radioStudent = $_POST['rb'.$customQuestionCounter];
				//start looking for professors keywords
				$jsonObj = json_decode($row['Values'],true);
				for($j=1;$j<sizeof($jsonObj);$j++){
					echo "<br/>radio prof: ".$radioProf = $jsonObj[$j]['radio'];
					$importance = intval($jsonObj[$j]['importance']);
					//if radio is found in answer add the score depending on professors importance, score = 2*importance
					if(preg_match('/'.$radioStudent.'/',$radioProf)){
						$studenScore+=(2*$importance)/$row['Rank'];
						echo "<br/>Radio found,". (2*$importance)/$row['Rank']." to student";
					}
				}
				break;
		}
		echo "<br/>";
	}
	echo "<br/>";
	echo "Student's Final Score: ".$studenScore;
	
	/*check if student had previously requested a permission number for this class*/
	$sql = "SELECT * FROM `Student_P#_Request` WHERE U_ID='".$_SESSION['netid']."' AND C_ID='".$CS_ID."'";
	// Execute our query
	if (!$mysql -> Query($sql)) {
		echo "Failed selecting prev requested section: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
	if($mysql->RowCount()>0){//the student had indeed requested a SPN before
		//*Proceed to update student in Student_P#_Request*/
		$sql = "UPDATE `Student_P#_Request` 
			    SET Active='y' 
			    WHERE U_ID='".$_SESSION['netid']."' AND C_ID='".$CS_ID."'";
	}
	else{			 
		//*Proceed to add student to Student_P#_Request*/
		$sql = "INSERT INTO `Student_P#_Request` (U_ID,CourseID,C_ID,Score,Date,Status,Active) 
			    VALUES ('".$netid."','".$C_ID."','".$CS_ID."','".$studenScore."','".$date."','Pending','y')";
	}
	// Execute our query
	if (!$mysql -> Query($sql)) {
		echo "Failed requesting permission number: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
	echo "<br/>Student Request Succesful";
	header('Location: http://cs336-31.rutgers.edu/index.php?alert=requestDone');
	include("emailStudentProfessorRequest.php");	
	
}
else{
	echo "no permission numbers for this section";	
	header('Location: http://cs336-31.rutgers.edu/index.php?alert=noSPNS');
}

function getGradeValue($grade){
	switch($grade){
		case 'A':
			return 4;
		case 'B':
			return 3;
		case 'C':
			return 2;
		case 'D':
			return 1;
	}
}




?>