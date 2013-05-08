<?php
session_start();
/**
 * @author Roberto Ronderos Botero
 */
require_once ("dbConnection.php");


$query = "SELECT c.C_ID,c.Name,co.CO_ID FROM Course as c,Course_Offering as co WHERE c.C_ID=co.C_ID AND co.Semester='".$_GET['Semester']."' AND c.Managed_By='".$_SESSION['netid']."'";

if (!$mysql -> Query($query)) {
	echo "Failed retrieving courses: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$result = $mysql ->Records();
$num_rows = mysql_num_rows($result);
if($num_rows >0){
	echo '<div class="alert alert-block" style="top:0px !important;">
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							  <h4>Notice:</h4>
							  <p>The list of students requesting a permission number is ordered in decreasing priority based on your criteria</p>
							</div>';
	echo '<div class="accordion" id="accordionPracticeCats">';
	while ($row = mysql_fetch_array($result)) {	
		/*go thorugh the categories the user wants and check if there's a practice for that category if so display it*/
		echo '<div class="accordion-group">';
			echo '<div class="accordion-heading" > <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPracticeCats" href="#collapse'.$row['C_ID'].'">
				  <span><span>///</span> '.$row['Name'].'</span>
				  </a>
				  </div>';
			echo '<div id="collapse'.$row['C_ID'].'" class="accordion-body collapse">';
				echo '<div class="accordion-inner"> ';			
					echo '<div id="btnsRow" class="pull-right">
				   		<a href="#addSPNSModal" role="button" class="btn btn-primary spnsbtn" data-toggle="modal" classID="'.$row['C_ID'].'" coid="'.$row['CO_ID'].'" >Add SP#\'s</a>
				   		<a href="#seeSPNSModal" role="button" class="btn btn-primary seespnsbtn" data-toggle="modal" classID="'.$row['C_ID'].'" coid="'.$row['CO_ID'].'" >See SP#\'s</a>
				   		<button id="seeEditClass" type="button" class="btn btn-primary " classID="'.$row['C_ID'].'" coid="'.$row['CO_ID'].'">See/Edit</button>
				  	</div><br/><br/>';
				   
					/*List sections*/
					$sql="SELECT CS_ID, Section_Number
							FROM Course_Section
							WHERE CO_ID='".$row['CO_ID']."'
							ORDER BY Section_Number ASC";	
					if (!$mysql -> Query($sql)) {
						echo "Failed retrieving sections: ".$mysql->Error();
						$mysql -> Kill();
						exit(1);
					}
					$result2 = $mysql ->Records();
					$num_rows = mysql_num_rows($result2);
					if($num_rows >0){
						
						while ($row2 = mysql_fetch_array($result2)) {
																
											echo '<div class="accordion-group">';
												echo '<div class="accordion-heading" ><a style="padding: 0px 15px !important;" class="accordion-toggle practicesAccordion" data-toggle="collapse" data-parent="#accordionPractices'.$row2['CS_ID'].'" href="#'.$row2['CS_ID'].'">
													  <h5>Section Number: '.$row2['Section_Number'].'</h5>
													  </a></div>';
												echo '<div id="'.$row2['CS_ID'].'" class="accordion-body collapse">';
													echo '<div class="accordion-inner">';
														//*list students in professors criteria*/
														$sql="SELECT s.PR_ID, u.Name, u.Last_Name, u.Email, u.NetID, s.CO_ID, s.CS_ID, s.`Date`, s.Status
																FROM User as u, `Student_P#_Request` as s
																WHERE u.NetID=s.NetID AND s.CS_ID='".$row2['CS_ID']."' AND s.CO_ID='".$row['CO_ID']."' AND s.Active='y' AND s.Status!='Assigned'
																ORDER BY s.Score DESC";	
														if (!$mysql -> Query($sql)) {
															echo "Failed retrieving permission Numbers: ".$mysql->Error();
															$mysql -> Kill();
															exit(1);
														}
														$result3 = $mysql ->Records();
														$num_rows = mysql_num_rows($result3);
														if($num_rows >0){
															echo "<div class='row'><div class='span8'><ol>"	;
															while ($row3 = mysql_fetch_array($result3)) {
																echo "<li prid='".$row3['PR_ID']."' > Name: ".$row3['Name']."<br/>
																									  Last Name: ".$row3['Last_Name']."<br/>
																									  E-mail: ".$row3['Email']."<br/>
																									  NetID: ".$row3['NetID']."<br/>
																									  Requested Date: ".$row3['Date']."<br/>
																									  Status: ".$row3['Status']."<br/>
																									  <br/>
																									  <p><button prid='".$row3['PR_ID']."' class='btn btn-success pull-left assignPermission' style='margin-right: 10px;'>Assign SPN</button>
																									  <button prid='".$row3['PR_ID']."' class='btn btn-danger denyPermission' style='margin-right: 10px;'>Deny Request</button>
																									  <button prid='".$row3['PR_ID']."' class='btn btn-info seeDetails' >See Request Details</button>
																									  </p>
																									  <br/>
																	  </li>";
															}
															echo "</ol>";
															$sql="SELECT s.PR_ID, u.Name, u.Last_Name, u.Email, u.NetID,s.CO_ID, s.CS_ID, s.`Date`, s.Status
																	FROM User as u, `Student_P#_Request` as s
																	WHERE u.NetID=s.NetID AND s.CS_ID='".$row2['CS_ID']."' AND s.CO_ID='".$row['CO_ID']."' AND Active='n'
																	ORDER BY s.Score DESC";	
															if (!$mysql -> Query($sql)) {
																echo "Failed retrieving permission Numbers(1): ".$mysql->Error();
																$mysql -> Kill();
																exit(1);
															}
															$result4 = $mysql ->Records();
															$sql="SELECT s.PR_ID, u.Name, u.Last_Name, u.Email, u.NetID,s.CO_ID, s.CS_ID, s.`Date`, s.Status
																	FROM User as u, `Student_P#_Request` as s
																	WHERE u.NetID=s.NetID AND s.CS_ID='".$row2['CS_ID']."' AND s.CO_ID='".$row['CO_ID']."' AND Active='y' AND s.Status='Assigned'
																	ORDER BY s.Score DESC";	
															if (!$mysql -> Query($sql)) {
																echo "Failed retrieving asigned students(1): ".$mysql->Error();
																$mysql -> Kill();
																exit(1);
															}
															$result5 = $mysql ->Records();
															
															
															echo "<br/><button class='btn btn-info showCancelled' style='margin-right: 10px;'>Display Cancelled/Denied Requests</button>
																		<button class='btn btn-info showAssigned'>See Assigned</button><br/>";
															
															echo "<br/><div id='showCancelled' style='display:none;'>";
															$num_rows = mysql_num_rows($result4);
															if($num_rows >0){
																	echo "<ol>";
																	while ($row4 = mysql_fetch_array($result4)) {
																		echo "<li prid='".$row4['PR_ID']."' > Name: ".$row4['Name']."<br/>
																											  Last Name: ".$row4['Last_Name']."<br/>
																											  E-mail: ".$row4['Email']."<br/>
																											  NetID: ".$row4['NetID']."<br/>
																											  Requested Date: ".$row4['Date']."<br/>
																											  Status: ".$row4['Status']."<br/>																											  
																											  <br/>
																											  <p>
																											  <button prid='".$row4['PR_ID']."' class='btn btn-info seeDetails' >See Request Details</button>
																											  </p>
																			  </li>";
																	}
																	echo "</ol>";
															}
															else{
																echo "<h4><i class='icon-exclamation-sign'></i> No Students have cancelled requests for this class.</h4>";		
															}
															echo "</div>";
															
															/*display assigned*/
															echo "<br/><div id='showAssigned' style='display:none;'>";
															$num_rows = mysql_num_rows($result5);
															if($num_rows >0){
																	echo "<ol>";
																	while ($row5 = mysql_fetch_array($result5)) {
																		echo "<li prid='".$row5['PR_ID']."' > Name: ".$row5['Name']."<br/>
																											  Last Name: ".$row5['Last_Name']."<br/>
																											  E-mail: ".$row5['Email']."<br/>
																											  NetID: ".$row5['NetID']."<br/>
																											  Requested Date: ".$row5['Date']."<br/>
																											  Status: ".$row5['Status']."<br/>																											  
																											  <br/>
																											  <p>
																											  <button prid='".$row5['PR_ID']."' class='btn btn-info seeDetails' >See Request Details</button>
																											  </p>
																			  </li>";
																	}
																	echo "</ol>";
															}
															else{
																echo "<h4><i class='icon-exclamation-sign'></i> You have not assigned permissions for this section.</h4>";		
															}
															echo "</div>";
															echo"</div></div>"	;	
														}
														else{
															echo "<h4><i class='icon-exclamation-sign'></i> No Students have requested a SPN for this class.</h4>";
															$sql="SELECT s.PR_ID, u.Name, u.Last_Name, u.Email, u.NetID,s.CO_ID, s.CS_ID, s.`Date`, s.Status
																	FROM User as u, `Student_P#_Request` as s
																	WHERE u.NetID=s.NetID AND s.CS_ID='".$row2['CS_ID']."' AND s.CO_ID='".$row['CO_ID']."' AND Active='n'
																	ORDER BY s.Score DESC";	
																	if (!$mysql -> Query($sql)) {
																		echo "Failed retrieving permission NUmbers(2): ".$mysql->Error();
																		$mysql -> Kill();
																		exit(1);
																	}
																	$result4 = $mysql ->Records();
																	$sql="SELECT s.PR_ID, u.Name, u.Last_Name, u.Email, u.NetID,s.CO_ID, s.CS_ID, s.`Date`, s.Status
																	FROM User as u, `Student_P#_Request` as s
																	WHERE u.NetID=s.NetID AND s.CS_ID='".$row2['CS_ID']."' AND s.CO_ID='".$row['CO_ID']."' AND Active='y' AND s.Status='Assigned'
																	ORDER BY s.Score DESC";	
																	if (!$mysql -> Query($sql)) {
																		echo "Failed retrieving asigned students(1): ".$mysql->Error();
																		$mysql -> Kill();
																		exit(1);
																	}
																	$result5 = $mysql ->Records();
																	
																	echo "<br/><button class='btn btn-info showCancelled' style='margin-right: 10px;'>Display Cancelled/Denied Requests</button>
																		<button class='btn btn-info showAssigned'>Display Assigned</button><br/>";
																									
																	echo "<br/><div id='showCancelled' style='display:none;'>";
																	$num_rows = mysql_num_rows($result4);
																	if($num_rows >0){
																			echo "<ol>";
																			while ($row4 = mysql_fetch_array($result4)) {
																				echo "<li prid='".$row4['PR_ID']."' > Name: ".$row4['Name']."<br/>
																													  Last Name: ".$row4['Last_Name']."<br/>
																													  E-mail: ".$row4['Email']."<br/>
																													  NetID: ".$row4['NetID']."<br/>
																													  Requested Date: ".$row4['Date']."<br/>
																													  Status: ".$row4['Status']."<br/>																											  
																													  <br/>
																													  <br/>
																													  <p>
																													  <button prid='".$row4['PR_ID']."' class='btn btn-info seeDetails' >See Request Details</button>
																													  </p>
																					  </li>";
																			}
																			echo "</ol>";
																	}
																	else{
																		echo "<h4><i class='icon-exclamation-sign'></i> No Students have cancelled requests for this class.</h4>";		
																	}
																	echo "</div>";
																	
																	/*display assigned*/
																	echo "<br/><div id='showAssigned' style='display:none;'>";
																	$num_rows = mysql_num_rows($result5);
																	if($num_rows >0){
																			echo "<ol>";
																			while ($row5 = mysql_fetch_array($result5)) {
																				echo "<li prid='".$row5['PR_ID']."' > Name: ".$row5['Name']."<br/>
																													  Last Name: ".$row5['Last_Name']."<br/>
																													  E-mail: ".$row5['Email']."<br/>
																													  NetID: ".$row5['NetID']."<br/>
																													  Requested Date: ".$row5['Date']."<br/>
																													  Status: ".$row5['Status']."<br/>																											  
																													  <br/>
																													  <p>
																													  <button prid='".$row5['PR_ID']."' class='btn btn-info seeDetails' >See Request Details</button>
																													  </p>
																					  </li>";
																			}
																			echo "</ol>";
																	}
																	else{
																		echo "<h4><i class='icon-exclamation-sign'></i> You have not assigned permissions for this section.</h4>";		
																	}
																	echo "</div>";	
														}	
													echo '</div>'; //close inner content
												echo '</div>';//close accordion collapsegroup
											echo '</div>';//close accordion group inner accordion
									}
						}
					else {
						echo "<h4><i class='icon-exclamation-sign'></i> No sections for this class.</h4>";						
					}					 
				echo '</div>';//close accordion-inner
			echo '</div>';//close collapseGroup
		echo '</div>';//close accordion group
	}
	echo '</div>'; //close accordion	
}
else{
	//no courses
	echo "<h4><i class='icon-exclamation-sign'></i> You Have not created any classes yet!</h4>";	
}

?>