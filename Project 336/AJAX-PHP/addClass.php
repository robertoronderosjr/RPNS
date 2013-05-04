<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
 * @author Roberto Ronderos Botero
 */
session_start();
require ("dbConnection.php");

/*get class information*/
echo "Course Name: ".$cName=$_POST['cName'];
echo "</br>";
echo "Course Major: ".$cMajor=$_POST['cMajor'];
echo "</br>";

/*add To Database Basic Information, using table-> Course and tuples: M_ID:int,Name:varchar(45),Managed_By:int,Creation_Date:date*/
$cName = mysql_real_escape_string($cName);
$cMajor = intval($cMajor);
$managedBy = $_SESSION['netid'];
date_default_timezone_set('America/New_York');
$date = date('Y-m-d H:i:s');
$sql = "INSERT INTO Course (M_ID, Name,Managed_By,Creation_Date) VALUES ('".$cMajor."', '".$cName."','".$managedBy."', '".$date."')";
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "failed adding basic course info: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
} 
$C_ID = $mysql->GetLastInsertID();

/*Add Course offering information, using table->Course_Offering and tuples: C_ID, Semester, MAX_SIZE*/
$semesterArray=$_POST['semester']; //array
echo "Course Semesters: ";
print_r($semesterArray);
echo "</br>";
$semesters="";
$prefix = '';
foreach ($semesterArray as $semester)
{
    $semesters .= $prefix . '"' . $semester . '"';
    $prefix = ', ';
}
echo "Course Max Student: ".$cMaxStudents=$_POST['cMaxStudents'];
echo "</br>";
$cMaxStudents = intval($cMaxStudents);
$sql = "INSERT INTO Course_Offering (C_ID, Semester, MAX_SIZE) VALUES ('".$C_ID."', '".$semesters."','".$cMaxStudents."')";
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "failed adding Course Offering info: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
} 
$CO_ID = $mysql->GetLastInsertID();

/*Add Course Sections information, using table Course_Section and tuples: CO_ID,Section_Number,Teached_By*/
$sectionNumber=$_POST['sectionNumber'];//array
echo "Course Section Numbers: ";
print_r($sectionNumber);
echo "</br>";

foreach($sectionNumber as $sectionN){
	$sql = "INSERT INTO Course_Section (CO_ID,Section_Number,Teached_By) VALUES ('".$CO_ID."', '".$sectionN."','".$managedBy."')";
	// Execute our query
	if (!$mysql -> Query($sql)) {
		echo "failed adding Course Section: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	} 
	
}

/*Add course Pre-Reqs using table Course_Requirements and tuples: Requirer_ID,Requirement_ID (class id that requires and class id that is required)*/

if(isset($_POST['cPreReqs']) && !empty($_POST['cPreReqs']) && !empty($_POST['cPreReqs'][0])){
$cPreReqs=$_POST['cPreReqs']; //array
echo "Course Pre Reqs: ";
print_r($cPreReqs);
echo "</br>";
foreach($cPreReqs as $preReq){
	$courseCreated=false;
	echo $preReq;
	echo "</br>";
	if(!is_numeric($preReq)){ //there exists a class with that id
		echo "is numeric";
		echo "</br>";
		 
		//*Make sure it doesn't exist*/
		$query = "SELECT * FROM Course WHERE Name LIKE '%".$preReq."%'";
		if (!$mysql -> Query($query)) {
			echo "failed Retrieving Data from Courses : ".$mysql->Error();
			$mysql -> Kill();
			exit(1);
		}
		echo " row count ".$mysql->RowCount();
		if($mysql->RowCount()>0){
			$mysql->MoveFirst(); 
			$row = $mysql->Row();
			echo " row  ".$row->Name;
			if(strtolower($row->Name)==strtolower($preReq)){
				$preReq=  $row->C_ID;
			}
			else{ 
				/*since it doesn't exists in courses, then we have to add it first*/
				$sql = "INSERT INTO Course (Name,Creation_Date) VALUES ('".$preReq."', '".$date."')";
				// Execute our query
				if (!$mysql -> Query($sql)) {
					echo "(2)failed adding basic course info: ".$mysql->Error();
					$mysql -> Kill();
					exit(1);
				} 
				$newpreReqC_ID = $mysql->GetLastInsertID();
				$courseCreated=true;
			}
		}
		else{ 
				/*since it doesn't exists in courses, then we have to add it first*/
				$sql = "INSERT INTO Course (Name,Creation_Date) VALUES ('".$preReq."', '".$date."')";
				// Execute our query
				if (!$mysql -> Query($sql)) {
					echo "(2)failed adding basic course info: ".$mysql->Error();
					$mysql -> Kill();
					exit(1);
				} 
				$newpreReqC_ID = $mysql->GetLastInsertID();
				$courseCreated=true;
			}
	}

	echo "it is numeric";
	echo "</br>";
	
	if($courseCreated){
		$sql = "INSERT INTO Course_Requirements (Requirer_ID,Requirement_ID) VALUES ('".$C_ID."', '".$newpreReqC_ID."')";
	}
	else{
		$sql = "INSERT INTO Course_Requirements (Requirer_ID,Requirement_ID) VALUES ('".$C_ID."', '".$preReq."')";
	}
	
	echo $sql;
	echo "</br>";
	// Execute our query
	if (!$mysql -> Query($sql)) {
		echo "failed adding Course Pre-Reqs: ".$mysql->Error()." QUERY: ".$sql;
		$mysql -> Kill();
		exit(1);
	} 
	
}
}
/*Add Basic Criteria to professor's class using table Prof_Course_Requirements*/
if(isset($_POST['basicCriteria'])){//array, the order in this array tells the importance for each item
$basicCriteria=$_POST['basicCriteria'];
echo "Basic Criteria used: ";
print_r($basicCriteria);
echo "</br>";
$custiomQuestionsRank;
foreach($basicCriteria as $rank => $bc){
	$skip=false;	
	switch($bc){
		case 'universityYear':
			$yearPreferred=$_POST['yearPreferred'];
			$sql = "INSERT INTO `Prof_Course_Requirements` (`Type`,`Rank`,`Values`,`C_ID`,`Prof_ID`) VALUES ('".$bc."', '".($rank+1)."','".$yearPreferred."','".$C_ID."','".$managedBy."')";
			break;
		case 'major':
			$preferedMajor=$_POST['preferedMajor'];
			$sql = "INSERT INTO `Prof_Course_Requirements` (`Type`,`Rank`,`Values`,`C_ID`,`Prof_ID`) VALUES ('".$bc."', '".($rank+1)."','".$preferedMajor."','".$C_ID."','".$managedBy."')";
			break;
		case 'gpa':
			$preferedGPA=$_POST['preferedGPA'];
			$sql = "INSERT INTO `Prof_Course_Requirements` (`Type`,`Rank`,`Values`,`C_ID`,`Prof_ID`) VALUES ('".$bc."', '".($rank+1)."','".$preferedGPA."','".$C_ID."','".$managedBy."')";
			break;
		case 'gradesPreReq':
			$preferedGradePreReqs=$_POST['preferedGradePreReqs'];
			$sql = "INSERT INTO `Prof_Course_Requirements` (`Type`,`Rank`,`Values`,`C_ID`,`Prof_ID`) VALUES ('".$bc."', '".($rank+1)."','".$preferedGradePreReqs."','".$C_ID."','".$managedBy."')";
			break;
		case 'creditsCompleted':
			$preferedCredits=$_POST['preferedCredits'];
			$sql = "INSERT INTO `Prof_Course_Requirements` (`Type`,`Rank`,`Values`,`C_ID`,`Prof_ID`) VALUES ('".$bc."', '".($rank+1)."','".$preferedCredits."','".$C_ID."','".$managedBy."')";
			break;
		case 'requestedDate':
			$sql = "INSERT INTO `Prof_Course_Requirements` (`Type`,`Rank`,`C_ID`,`Prof_ID`) VALUES ('".$bc."', '".($rank+1)."','".$C_ID."','".$managedBy."')";
			break;
		default: //custom question.
			$custiomQuestionsRank[$bc]=($rank+1);
			$skip=true;
			break;
	}
	if(!$skip){
		// Execute our query
		if (!$mysql -> Query($sql)) {
			echo "Failed adding Basic Criteria Used in Course: ".$mysql->Error()." QUERY: ".$sql;
			$mysql -> Kill();
			exit(1);
		} 
	}
}


/*Add Custom Questions*/
echo "# of Custom Questions: ".$numberOfCustomQuestions=$_POST['numberOfCustomQuestions'];
$customQuestions=array(); //array containing all custom questions
for($i=0;$i<intval($numberOfCustomQuestions);$i++){
	$cq= $_POST['customQuestion_'.($i+1)];
	array_push($customQuestions,$cq); //add question to customQuestions array	
}
print_r($customQuestions);
echo "</br>";
if(sizeof($customQuestions)>0){
	
	$typeCustomQuestions=$_POST['type'];//array
	print_r($typeCustomQuestions);
	echo "</br>";
	//get it's type and retrieve its values
	$i=0;
	foreach($customQuestions as $question){
		$valuesArray = array();		
		$valuesArray[0]=$question;
		$jsonValues="";
		echo "Custom Question # ".($i+1)."</br>";		
		switch($typeCustomQuestions[$i]){ //parallel array with customQuestions
			case 'ck':
					//get keywords and importance					
					$keywordsQuestion = $_POST['ck_'.($i+1)];//array of keywords for a question i
					$j=0;
					foreach($keywordsQuestion as $keyword){
						$importanceArray = $_POST['ckimportance_'.($i+1)];
						$importance = $importanceArray[$j];						
						echo "Keyword: ".$keyword." and importance: ".$importance."</br>";
						$entry['keyword']=$keyword;
						$entry['importance']=$importance;
						$valuesArray[$j+1] = $entry;
						$jsonValues=json_encode($valuesArray);
						$questionRank = $custiomQuestionsRank[$question];												
						$j++;	
					}					
				break;
			case 'cb':
					//get keywords and importance
					$checkBoxesQuestion = $_POST['cb_'.($i+1)];//array of check boxes for a question i
					$j=0;
					foreach($checkBoxesQuestion as $checkbox){
						$importanceArray = $_POST['cbimportance_'.($i+1)];
						$importance = $importanceArray[$j];						
						echo "Check Box value: ".$checkbox." and importance: ".$importance."</br>";
						$entry['checkbox']=$checkbox;
						$entry['importance']=$importance;
						$valuesArray[$j+1] = $entry;
						$jsonValues=json_encode($valuesArray);
						$questionRank = $custiomQuestionsRank[$question];																	
						$j++;	
					}	
				break;
			case 'rb':
					//get keywords and importance
					$radioQuestion = $_POST['rb_'.($i+1)];//array of radio for a question i
					$j=0;
					foreach($radioQuestion as $radio){
						$importanceArray = $_POST['rbimportance_'.($i+1)];
						$importance = $importanceArray[$j];						
						echo "Radio Button value: ".$radio." and importance: ".$importance."</br>";
						$entry['radio']=$radio;
						$entry['importance']=$importance;
						$valuesArray[$j+1] = $entry;
						$jsonValues=json_encode($valuesArray);
						$questionRank = $custiomQuestionsRank[$question];							
						$j++;	
					}
				break;
		}
		$sql = "INSERT INTO `Prof_Course_Requirements` (`Type`,`Rank`,`Values`,`C_ID`,`Prof_ID`) VALUES ('".$typeCustomQuestions[$i]."', '".$questionRank."','".$jsonValues."','".$C_ID."','".$managedBy."')";						
		// Execute our query
		if (!$mysql -> Query($sql)) {
			echo "Failed adding Custom Question Used in Course: ".$mysql->Error()." QUERY: ".$sql;
			$mysql -> Kill();
			exit(1);
		} 
		unset($entry);
		$i++;
	}
}

}

header('Location: http://cs336-31.rutgers.edu/index.php?alert=courseAdded');










  
?>