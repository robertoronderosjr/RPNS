<?php
/**
 * @author Roberto Ronderos Botero, Catalina Laverde
 */
require_once ("dbConnection.php");


$query = "SELECT s.PR_ID, c.Name,cs.CO_ID, cs.CS_ID, cs.Section_Number, s.`Date`, s.Status
		  FROM Course as c, Course_Section as cs, `Student_P#_Request` as s, Course_Offering as co
		  WHERE s.CS_ID=cs.CS_ID AND cs.CO_ID=co.CO_ID AND c.C_ID=co.C_ID AND s.NetID='".$_SESSION['netid']."' AND s.Active='y'";

if (!$mysql -> Query($query)) {
	echo "Failed retrieving requested permissions: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$result = $mysql ->Records();
$num_rows = mysql_num_rows($result);
if($num_rows >0){
	
	
	echo '<div class="accordion" id="accordionPracticeCats">';
	while ($row = mysql_fetch_array($result)) {	
			
		/*go thorugh the categories the user wants and check if there's a practice for that category if so display it*/
		echo '<div class="accordion-group">';
			
				echo '<div class="accordion-heading" > <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPracticeCats" href="#collapse'.$row['CS_ID'].'">
					  <span><span>///</span> '.$row['Name'].':'.$row['Section_Number'].'</span>
					  </a>
					  <button type="button" class="btn btn-primary pull-right cancelRequestBtn" style="position: relative;top: -33px;left:-10px" prid="'.$row['PR_ID'].'">Cancel Request</button>
					  </div>';
			
			echo '<div id="collapse'.$row['CS_ID'].'" class="accordion-body collapse">';
				echo '<div class="accordion-inner"> ';			
					echo '<div class="row">
							<div class="span8">
								<span><b>Status:</b> '.$row['Status'].'</span><br/>
								<span><b>Date Requested:</b> '.$row['Date'].'</span><br/>';								
								if($row['Status']=='Assigned'){
									/*Get assigned tupple from professor*/
									$sql="SELECT PA_ID, Comments, Assignation_Date, Expiration_Date
											FROM `Prof_P#_Assigns`
											WHERE CO_ID='".$row['CO_ID']."' AND CS_ID='".$row['CS_ID']."'";												
									if (!$mysql -> Query($sql)) {
										echo "Failed retrieving sections: ".$mysql->Error();
										$mysql -> Kill();
										exit(1);
									}
									$result2 = $mysql ->Records();
									$row2 = mysql_fetch_array($result2);									
									
									echo '<br/><span><b>Professor\'s Comments:</b></span><br/>
										  <span style="margin-left:5px;" class="profComment">'.$row2['Comments'].'</span><br/>
										  <span><b>Date Assigned:</b></span><br/>
										  <span style="margin-left:5px;" class="dateAssigned">'.$row2['Assignation_Date'].'</span><br/>
										  <span><b>Expiration Date:</b></span><br/>
										  <span style="margin-left:5px;" class="expDate">'.$row2['Expiration_Date'].'</span><br/><br/>
										  <button paid="'.$row2['PA_ID'].'" class="btn btn-success useIt">See SPN and Use it</button><br/>';
								}

						echo '</div>
						  </div>
							';
				echo '</div>';//close accordion-inner
			echo '</div>';//close collapseGroup
		echo '</div>';//close accordion group
			
		
		
	}
	echo '</div>'; //close accordion
		
	
}
else{
	//no courses
	echo "<h4><i class='icon-exclamation-sign'></i> You Have not requested SPNS for any classes yet!</h4>";	
}

?>