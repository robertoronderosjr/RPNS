<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
* @author Roberto Ronderos Botero
*/
session_start();
require_once ("dbConnection.php");

$C_ID = $_POST['CID'];  

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

$sql = "UPDATE Course
		SET M_ID='".$cMajor."', Name='".$cName."',Managed_By='".$managedBy."',Creation_Date='".$date."'
		WHERE C_ID='".$C_ID."'"; 

// Execute our query
if (!$mysql -> Query($sql)) {
	echo "failed updating basic course info: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
} 

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

/*Get course offering CO_ID*/
$CO_ID=$_POST['COID'];
echo "C_ID:".$C_ID." AND CO_ID:".$CO_ID."<br/>";

$sql = "UPDATE Course_Offering
		SET Semester='".$semesters."', MAX_SIZE='".$cMaxStudents."'
		WHERE C_ID='".$C_ID."' AND CO_ID='".$CO_ID."'"; 
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "failed updating Course Offering info: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
} 


/*Add Course Sections information, using table Course_Section and tuples: CO_ID,Section_Number,Teached_By
$sectionNumber=$_POST['sectionNumber'];//array
GET CS_ID which is an array stored as CSID
$CSIDS=$_POST['CSID'];//array (parallel array to section Number)
echo "Course Section Numbers: ";
print_r($sectionNumber);
echo "</br>";
echo "Course Section CSIDS: ";
print_r($CSIDS);
echo "</br>";
$z=0;*/

/*Add Course Sections information, using table Course_Section and tuples: CO_ID,Section_Number,Teached_By*/
$sectionNumber=$_POST['sectionNumber'];//array
echo "Course Section Numbers: ";
print_r($sectionNumber);
echo "</br>";
echo "Deleting prev sections. <br/>";
$sql = "DELETE FROM Course_Section
		WHERE CO_ID='".$CO_ID."'"; 
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "failed deleting prev sections: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
} 
foreach($sectionNumber as $sectionN){
	$sql = "INSERT INTO Course_Section (CO_ID,Section_Number,Teached_By) VALUES ('".$CO_ID."', '".$sectionN."','".$managedBy."')";
	// Execute our query
	if (!$mysql -> Query($sql)) {
		echo "failed adding Course Section: ".$mysql->Error()." ".$sql;
		$mysql -> Kill();
		exit(1);
	} 
	
}
/*foreach($sectionNumber as $sectionN){
	$sql = "UPDATE Course_Section
			SET CO_ID='".$CO_ID."',Section_Number='".$sectionN."',Teached_By='".$managedBy."'
			WHERE CS_ID='".$CSIDS[$z]."'
			";
	// Execute our query
	if (!$mysql -> Query($sql)) {
		echo "failed updating Course Section: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	} 
	$z++;	
}*/

/*UPDATE course Pre-Reqs using table Course_Requirements and tuples: Requirer_ID,Requirement_ID (class id that requires and class id that is required)*/
/*Since there's not way to get unique row, need to delete all Requirer_ID=C_ID and then add them back again.*/

if(isset($_POST['cPreReqs']) && !empty($_POST['cPreReqs']) && !empty($_POST['cPreReqs'][0])){
$cPreReqs=$_POST['cPreReqs']; //array
echo "Course Pre Reqs: ";
print_r($cPreReqs);
echo "</br>";
echo "Deleting prev reqs. <br/>";
$sql = "DELETE FROM Course_Requirements
		WHERE Requirer_ID='".$C_ID."'"; 
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "failed deleting prev requirements: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
} 
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
					echo "(2)failed updating basic course info: ".$mysql->Error();
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
					echo "(2)failed updating basic course info: ".$mysql->Error();
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
echo "Deleting prev used criteria. <br/>";
$sql = "DELETE FROM Prof_Course_Requirements
		WHERE C_ID='".$C_ID."'"; 
// Execute our query
if (!$mysql -> Query($sql)) {
	echo "failed deleting prev used criteria: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
} 
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

/*UPDATE Basic Criteria to professor's class using table Prof_Course_Requirements

if(isset($_POST['basicCriteria'])){//array, the order in this array tells the importance for each item
$basicCriteria=$_POST['basicCriteria'];
echo "Basic Criteria used: ";
print_r($basicCriteria);
echo "</br>";
Get parallel array with all PCR_ID's parallel to basicCriteria
$PCRIDS=$_POST['PCRIDS'];
echo "Basic Criteria used PCRIDS: ";
print_r($PCRIDS);
echo "</br>";
$custiomQuestionsRank;
$customQuestionPCRIDS;
$p=0;
foreach($basicCriteria as $rank => $bc){
	$skip=false;	
	switch($bc){
		case 'universityYear':
			$yearPreferred=$_POST['yearPreferred'];
			$sql = "UPDATE `Prof_Course_Requirements`
					SET `Type`='".$bc."',`Rank`='".($rank+1)."',`Values`='".$yearPreferred."',`C_ID`='".$C_ID."',`Prof_ID`='".$managedBy."'
					WHERE PCR_ID='".$PCRIDS[$p]."'
					"; 
			break;
		case 'major':
			$preferedMajor=$_POST['preferedMajor'];
			$sql = "UPDATE `Prof_Course_Requirements`
					SET `Type`='".$bc."',`Rank`='".($rank+1)."',`Values`='".$preferedMajor."',`C_ID`='".$C_ID."',`Prof_ID`='".$managedBy."'
					WHERE PCR_ID='".$PCRIDS[$p]."'
					"; 
			break;
		case 'gpa':
			$preferedGPA=$_POST['preferedGPA'];
			$sql = "UPDATE `Prof_Course_Requirements`
					SET `Type`='".$bc."',`Rank`='".($rank+1)."',`Values`='".$preferedGPA."',`C_ID`='".$C_ID."',`Prof_ID`='".$managedBy."'
					WHERE PCR_ID='".$PCRIDS[$p]."'
					";
			break;
		case 'gradesPreReq':
			$preferedGradePreReqs=$_POST['preferedGradePreReqs'];
			$sql = "UPDATE `Prof_Course_Requirements`
					SET `Type`='".$bc."',`Rank`='".($rank+1)."',`Values`='".$preferedGradePreReqs."',`C_ID`='".$C_ID."',`Prof_ID`='".$managedBy."'
					WHERE PCR_ID='".$PCRIDS[$p]."'
					";
			break;
		case 'creditsCompleted':
			$preferedCredits=$_POST['preferedCredits'];
			$sql = "UPDATE `Prof_Course_Requirements`
					SET `Type`='".$bc."',`Rank`='".($rank+1)."',`Values`='".$preferedCredits."',`C_ID`='".$C_ID."',`Prof_ID`='".$managedBy."'
					WHERE PCR_ID='".$PCRIDS[$p]."'
					";
			break;
		case 'requestedDate':
			$sql = "INSERT INTO `Prof_Course_Requirements` (`Type`,`Rank`,`C_ID`,`Prof_ID`) VALUES ('".$bc."', '".($rank+1)."','".$C_ID."','".$managedBy."')";
			break;
		default: //custom question.
			$custiomQuestionsRank[$bc]=($rank+1);
			$customQuestionPCRIDS[$bc]=$PCRIDS[$p];
			$skip=true;
			break;
	}
	if(!$skip){
		// Execute our query
		if (!$mysql -> Query($sql)) {
			echo "Failed updating Basic Criteria Used in Course: ".$mysql->Error()." QUERY: ".$sql;
			$mysql -> Kill();
			exit(1);
		} 
	}
	$p++;
}


Add Custom Questions
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
		$sql = "UPDATE `Prof_Course_Requirements`
					SET `Type`='".$typeCustomQuestions[$i]."',`Rank`='".$questionRank."',`Values`='".$jsonValues."',`C_ID`='".$C_ID."',`Prof_ID`='".$managedBy."'
					WHERE PCR_ID='".$customQuestionPCRIDS[$question]."'
					";
								
		// Execute our query
		if (!$mysql -> Query($sql)) {
			echo "Failed updating Custom Question Used in Course: ".$mysql->Error()." QUERY: ".$sql;
			$mysql -> Kill();
			exit(1);
		} 
		unset($entry);
		$i++;
	}
}

}*/

header('Location: http://cs336-31.rutgers.edu/index.php?alert=courseEdited');
  
    
	
	
?>