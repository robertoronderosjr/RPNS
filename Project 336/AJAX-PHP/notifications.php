<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
 * @author Catalina Laverde Duarte  
 **/
require_once ("dbConnection.php");
 
 //Checking who is sending the request
 
 //It's a student
 if ($_SESSION['type']==1) 
 {
 	$sql = "SELECT * 
 		    FROM Notifications 
 		    WHERE To_NetID='".$_SESSION['netid']."' 
 		    ORDER BY Datestamp DESC";
	
	if (!$mysql -> Query($sql))
		$mysql -> Kill(); 
	
	if($mysql->RowCount()==0){
		echo "<h4><i class='icon-exclamation-sign'></i> You have no notifications.</h4>";
	}	
	else
	{		
		$mysql -> MoveFirst();
		while (!$mysql -> EndOfSeek()) {
			$row = $mysql -> Row();
			$action = $row->Action;
			
			switch ($action) {
				case '1': //New class is added
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>NEW CLASS AVAILABLE!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '2': //Permission number was requested
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>PERMISSION NUMBER REQUESTED!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '3': //Welcome message for the user
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>WELCOME TO RSPN!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '4': //Permission number was assigned
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>PERMISSION NUMBER ASSIGNED!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '5': //Profile modified 
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>PROFILE MODIFIED SUCCESSFULLY!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '6': //Cancelled request
					echo '<div class="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>YOU HAVE CANCELLED A REQUEST</strong>
						<p>'.$row->Message.'</p>				
					  </div>';
					break;
			}
			
		}
	}
 } 
 else  	 //It's a professor	
 {
	$sql = "SELECT * 
 		    FROM Notifications 
 		    WHERE To_NetID='".$_SESSION['netid']."' 
 		    ORDER BY Datestamp DESC";
	
	if (!$mysql -> Query($sql))
		$mysql -> Kill(); 
	
	if($mysql->RowCount()==0){
		echo "<h4><i class='icon-exclamation-sign'></i> You have no notifications.</h4>";
	}
	
	else
	{	
		$mysql -> MoveFirst();
		while (!$mysql -> EndOfSeek()) {
			$row = $mysql -> Row();
			$action = $row->Action;
			
			switch ($action) {
				case '1': //New class is added
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>NEW CLASS ADDED SUCCESSFULLY!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '2': //Permission number was requested
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>PERMISSION NUMBER REQUESTED!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '3': //Welcome message for the user
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>WELCOME TO RSPN!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '4': //Permission number was assigned
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>PERMISSION NUMBER ASSIGNED!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '5': //Profile modified 
					echo '<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>PROFILE MODIFIED SUCCESSFULLY!</strong>
						<p>'.$row->Message.'</p>				
						</div>';
					break;
					
				case '6': //Cancelled request
					echo '<div class="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>CANCELLED REQUEST</strong>
						<p>'.$row->Message.'</p>				
					  </div>';
					break;
				case '7': //Class edited successfully
					echo '<div class="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>CLASS EDITED SUCCESSFULLY</strong>
						<p>'.$row->Message.'</p>				
					  </div>';
					break;
			}		
		} 
	}
 }
 
?>