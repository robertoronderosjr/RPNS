<?php
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");


$query = "SELECT c.C_ID,c.Name,co.CO_ID FROM Course as c,Course_Offering as co WHERE c.C_ID=co.C_ID AND c.Managed_By='".$_SESSION['netid']."'";
if (!$mysql -> Query($query)) {
	echo "Failed retrieving courses: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
if($mysql->RowCount()>0){
	$coursesArray = array(); 
	$mysql -> MoveFirst();
	echo '<div class="accordion" id="accordionPracticeCats">';
	while (!$mysql -> EndOfSeek()) {
	
		$row = $mysql -> Row();		
		$coursesArray[$row -> C_ID] = $row -> Name;
		/*go thorugh the categories the user wants and check if there's a practice for that category if so display it*/
		echo '<div class="accordion-group">';
			echo '<div class="accordion-heading" > <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPracticeCats" href="#collapse'.$row -> C_ID.'">
				  <span><span>///</span> '.$row -> Name.'</span>
				  </a>
				  <a href="#addSPNSModal" role="button" class="btn btn-primary pull-right" data-toggle="modal" style="position: relative;top: -33px;" classID="'.$row -> C_ID.'" coid="'.$row->CO_ID.'">Add SP#\'s</a>
				  <button id="seeEditClass" type="button" class="btn btn-primary pull-right" style="position: relative;top: -33px;left:-10px" classID="'.$row -> C_ID.'" coid="'.$row->CO_ID.'">See/Edit</button>				  
				   </div>';
			echo '<div id="collapse'.$row -> C_ID.'" class="accordion-body collapse">';
				echo '<div class="accordion-inner"> ';			
					echo '<div class="alert alert-block" style="top:0px !important;">
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							  <h4>Notice:</h4>
							  <p>The following list of students who are requesting a permission number to this class was order with hiegher priority students on top</p>
							</div></br></br>';
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