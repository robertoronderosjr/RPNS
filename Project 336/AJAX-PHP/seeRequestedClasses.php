<?php
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");


$query = "SELECT s.PR_ID, c.C_ID, c.Name, cs.CS_ID, cs.Section_Number, s.Date, s.Status
          FROM Course as c, Course_Section as cs, `Student_P#_Request` as s
          WHERE s.CourseID=c.C_ID AND s.C_ID=cs.CS_ID AND s.U_ID='".$_SESSION['netid']."'";

if (!$mysql -> Query($query)) {
	echo "Failed retrieving requested permissions: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
if($mysql->RowCount()>0){
	
	$mysql -> MoveFirst();
	echo '<div class="accordion" id="accordionPracticeCats">';
	while (!$mysql -> EndOfSeek()) {	
		$row = $mysql -> Row();		
		/*go thorugh the categories the user wants and check if there's a practice for that category if so display it*/
		echo '<div class="accordion-group">';
			echo '<div class="accordion-heading" > <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPracticeCats" href="#collapse'.$row -> CS_ID.'">
				  <span><span>///</span> '.$row -> Name.':'.$row->Section_Number.'</span>
				  </a>
				  <button type="button" class="btn btn-primary pull-right cancelRequestBtn" style="position: relative;top: -33px;left:-10px" prid="'.$row->PR_ID.'">Cancel Request</button>
				  </div>';
			echo '<div id="collapse'.$row -> CS_ID.'" class="accordion-body collapse">';
				echo '<div class="accordion-inner"> ';			
					echo '<div class="row">
							<div class="span8">
								<span><b>Status:</b> '.$row->Status.'</span><br/>
								<span><b>Date Requested:</b> '.$row->Date.'</span><br/>
								<span><b>Professor\'s Comments:</b></span><br/>
								<span id="profComment"></span>
							</div>
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
	echo "<h4><i class='icon-exclamation-sign'></i> You Have not created any classes yet!</h4>";	
}

?>