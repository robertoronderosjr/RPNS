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
						  	<p>Please fill out the following form</p>						    	
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