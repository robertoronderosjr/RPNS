<?php
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");

/*get class information*/
echo "Course Name: ".$cName=$_POST['cName']."</br>";
echo "Course Major: ".$cMajor=$_POST['cMajor']."</br>";
echo "Course Max Student: ".$cMaxStudents=$_POST['cMaxStudents']."</br>";
$cPreReqs=$_POST['cPreReqs']; //array
echo "Course Pre Reqs: ";
print_r($cPreReqs);
echo "</br>";
$semester=$_POST['semester']; //array
echo "Course Semesters: ";
print_r($semester);
echo "</br>";
$sectionNumber=$_POST['sectionNumber'];//array
echo "Course Section Numbers: ";
print_r($sectionNumber);
echo "</br>";
$sectionProfessor=$_POST['sectionProfessor'];//array
echo "Course Section Professors: ";
print_r($sectionProfessor);
echo "</br>";
$basicCriteria=$_POST['basicCriteria'];//array, the order in this array tells the importance for each item
echo "Basic Criteria used: ";
print_r($basicCriteria);
echo "</br>";
if(isset($_POST['yearPreferred']) && !empty($_POST['yearPreferred'])){ echo "Year Preferred: ".$yearPreferred=$_POST['yearPreferred']."</br>"; }//optional, only used if exists in basicCriteria
if(isset($_POST['preferedCredits']) && !empty($_POST['preferedCredits'])){ echo "Preferred Credits: ".$preferedCredits=$_POST['preferedCredits']."</br>"; }//optional, only used if exists in basicCriteria
if(isset($_POST['preferedGradePreReqs']) && !empty($_POST['preferedGradePreReqs'])){ echo "Preferred PreReqs Grade: ".$preferedGradePreReqs=$_POST['preferedGradePreReqs']."</br>"; }//optional, only used if exists in basicCriteria
if(isset($_POST['preferedGPA']) && !empty($_POST['preferedGPA'])){ echo "Preferred GPA: ".$preferedGPA=$_POST['preferedGPA']."</br>"; }//optional, only used if exists in basicCriteria  
if(isset($_POST['preferedMajor']) && !empty($_POST['preferedMajor'])){ echo "Preferred Major: ".$preferedMajor=$_POST['preferedMajor']."</br>"; }//optional, only used if exists in basicCriteria  
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
		echo "Custom Question # ".($i+1)."</br>";		
		switch($typeCustomQuestions[$i]){
			case 'ck':
					//get keywords and importance
					$keywordsQuestion = $_POST['ck_'.($i+1)];//array of keywords for a question i
					$j=0;
					foreach($keywordsQuestion as $keyword){
						$importanceArray = $_POST['ckimportance_'.($i+1)];
						$importance = $importanceArray[$j];						
						echo "Keyword: ".$keyword." and importance: ".$importance."</br>";						
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
						$j++;	
					}
				break;
		}
		$i++;
	}
}










  
?>