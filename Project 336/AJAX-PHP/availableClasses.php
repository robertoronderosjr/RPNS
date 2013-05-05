<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");


$query = "SELECT c.`C_ID`, c.`Name`, cr.`CO_ID`
		  FROM Course as c, Course_Offering as cr, Majors as m
		  WHERE c.`C_ID`=cr.`C_ID` 
		  AND  c.`M_ID`=m.`M_ID`
		  AND  m.`D_ID`='".$_GET['D_ID']."' 
		  AND c.`M_ID`='".$_GET['M_ID']."'
		  AND cr.`Semester` LIKE '%".$_GET['Semester']."%'";

if (!$mysql -> Query($query)) {
	echo "Failed retrieving courses: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$result = $mysql ->Records();
$num_rows = mysql_num_rows($result);
if($num_rows >0){
	echo '<h4>Results:</h4><div class="accordion" id="accordionPracticeCats">';
	while ($row = mysql_fetch_array($result)) {	
				
		/*go thorugh the categories the user wants and check if there's a practice for that category if so display it*/
		echo '<div class="accordion-group">';
			echo '<div class="accordion-heading" > <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPracticeCats" href="#collapse'.$row['C_ID'].'">
				  <span><span>///</span> '.$row['Name'].'</span>
				  </a></div>';
			echo '<div id="collapse'.$row['C_ID'].'" class="accordion-body collapse">';
				echo '<div class="accordion-inner"> ';			
					echo "
						  	<h5>Please fill out the following form</h5>						    	
						  	<label for='sections'>Choose a Section</label>
						  	<select id='sections'>";
							$query= "SELECT CS_ID,Section_Number FROM Course_Section WHERE CO_ID='".$row['CO_ID']."'";
							if (!$mysql -> Query($query)) {
									echo "Failed retrieving sections: ".$mysql->Error();
									$mysql -> Kill();
									exit(1);
							}
							$result2 = $mysql ->Records();
							while ($row2 = mysql_fetch_array($result2)) {
									echo "<option value=".$row2['CS_ID'].">".$row2['Section_Number']."</option>";
							}							
							echo "</select>";
							echo "<br/>";
							$query= "SELECT `Type`,`Values` FROM `Prof_Course_Requirements` WHERE C_ID='".$row['C_ID']."'";
							if (!$mysql -> Query($query)) {
									echo "Failed retrieving sections: ".$mysql->Error();
									$mysql -> Kill();
									exit(1);
							}
							$result3 = $mysql ->Records();
							$customQuestionCounter=0;
							while ($row3 = mysql_fetch_array($result3)) {
								
								
								switch($row3['Type']){
						   			case 'universityYear':
						   				echo "<label class='control-label' for='uYear'><b>Please choose your current University Year</b></label>
						   					 	<div class='controls'>
						   					 		<input type='radio' name='preferedYearModal' value='freshman'>Freshman<br>
						   					 		<input type='radio' name='preferedYearModal' value='sophomore'>Sophomore<br>
						   					 		<input type='radio' name='preferedYearModal' value='junior'>Junior<br>
						   					 		<input type='radio' name='preferedYearModal' value='senior'>Senior<br></div>
						   					 <br/>";
						   				break;
						   			case 'creditsCompleted':
						   				echo "<label class='control-label' for='creditsCompleted'><b>Please input the number of credits you have completed</b></label>
						   					 <input type='text' id='creditsCompleted' name='creditsCompleted' placeholder='Number of Credits'/><br/>";
						   				break;
						   			case 'gradesPreReq':
						   				echo "<label class='control-label' for='gradesPreReq'><b>Please select your grande in each of the following</b></label>";
						   				$query= "SELECT cr.Requirement_ID,c.Name FROM Course as c, Course_Requirements as cr WHERE c.C_ID=cr.Requirement_ID AND cr.Requirer_ID='".$row['C_ID']."'";
										if (!$mysql -> Query($query)) {
												echo "Failed retrieving sections: ".$mysql->Error();
												$mysql -> Kill();
												exit(1);
										}
										$result4 = $mysql ->Records();
										while ($row4 = mysql_fetch_array($result4)) {
												echo "<span>".$row4['Name']."</span><br/>";	
												echo "
														<div class='controls'>
															<input type='radio' name='".$row4['Requirement_ID']."' value='D'>D<br>
															<input type='radio' name='".$row4['Requirement_ID']."' value='C'>C<br>
															<input type='radio' name='".$row4['Requirement_ID']."' value='B'>B<br>
															<input type='radio' name='".$row4['Requirement_ID']."' value='A'>A<br>
															</div><br/>
													";
										}		
						   				break;
						   			case 'gpa':
						   				echo "<label class='control-label'  for='currentGPA'><b>Please input your current GPA</b></label>
						   					 <input type='text' id='currentGPA' name='currentGPA' placeholder='G.P.A.'/><br/>";
						   				break;
									case 'ck':
										$customQuestionCounter++;										
										$jsonForm = json_decode($row3['Values'],true);
										$question = $jsonForm[0];
										echo "<label class='control-label' for='ck".$customQuestionCounter."'><b>".$question."</b></label>";
										echo "<input id='ck".$customQuestionCounter."' name='ck".$customQuestionCounter."[]' placeholder='Type your answer here'/><br/><br/>";
										
										break;
									case 'cb':
										$customQuestionCounter++;								
										$jsonForm = json_decode($row3['Values'],true);
										$question = $jsonForm[0];
										echo "<label class='control-label' for='cb".$customQuestionCounter."'><b>".$question."</b></label>";							
										echo "<div class='controls'>";
										for($j=1;$j<sizeof($jsonForm);$j++){
											$checkBox = $jsonForm[$j];																						
											echo"<input type='checkbox' name='cb".$customQuestionCounter."[]' value='".$checkBox['importance']."'>".$checkBox['checkbox']."<br>";
										}
										echo "</div><br/>";	
										
										break;
									case 'rb';
										$customQuestionCounter++;
										$jsonForm = json_decode($row3['Values'],true);
										$question = $jsonForm[0];
										echo "<label class='control-label' for='rb".$customQuestionCounter."'><b>".$question."</b></label>";							
										echo "<div class='controls'>";
										for($j=1;$j<sizeof($jsonForm);$j++){
											$radio = $jsonForm[$j];																						
											echo"<input type='radio' name='rb".$customQuestionCounter."[]' value='".$radio['importance']."'>".$radio['radio']."<br>";
										}
										echo "</div><br/>";	
										
										break;
								}	
							}
				echo '</div>';//close accordion-inner
			echo '</div>';//close collapseGroup
		echo '</div>';//close accordion group
			
		
		
	}
	echo '</div>'; //close accordion
		
	
}
else{
	//no courses
	echo "<h4><i class='icon-exclamation-sign'></i> There are no classes that match your query. Try Again.</h4>";	
}

?>