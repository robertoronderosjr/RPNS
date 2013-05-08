<?php
session_start();
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");

$netid = $_SESSION['netid'];
$PR_ID = $_GET['PR_ID'];


/*Check if permission has been assigned already*/
$query = "SELECT *
	      FROM `Student_P#_Request`		  
		  WHERE PR_ID='".$PR_ID."' AND NetID='".$netid."' AND Status='Assigned'";

if (!$mysql -> Query($query)) {
	echo "Failed checking if permission has been assigned: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}

if($mysql->RowCount()>0){ //IF this is more than 1 it means that it has indeed been assigned
		
	$mysql->MoveFirst();
	$row = $mysql->Row(); 
	$CO_ID= $row->CO_ID;
	$CS_ID= $row->CS_ID;
	/*make pn available*/
	$query = "SELECT Permission_N
		      FROM `Prof_P#_Assigns`		  
			  WHERE CO_ID='".$CO_ID."' AND	CS_ID='".$CS_ID."'	AND Assignee='".$_SESSION['netid']."'";	

	if (!$mysql -> Query($query)) {
		echo "Failed getting pn: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
	$mysql->MoveFirst();
	$row = $mysql->Row(); 
	$pn = $row->Permission_N;
	
	$query = "UPDATE `Permissions`
			  SET Available='y'		  
			  WHERE CS_ID='".$CS_ID."'	AND P_Number='".$pn."'";	

	if (!$mysql -> Query($query)) {
		echo "Failed making available the pn: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
	
	/*Delete from professor's assign table*/
	$sql="DELETE FROM `Prof_P#_Assigns`		  
		  WHERE CO_ID='".$CO_ID."' AND CS_ID='".$CS_ID."' AND Permission_N='".$pn."'";									
	if (!$mysql -> Query($sql)) {
		echo "Failed deleting permission number: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}	
		
	
}

/*update room capacity*/
$query = "UPDATE `Course_Offering`
		  SET CurrentSize=CurrentSize-1
		  WHERE CO_ID='".$CO_ID."'";

if (!$mysql -> Query($query)) {
	echo "Failed Updating room capacity: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}

$query = "UPDATE `Student_P#_Request`
		  SET Active='n', Status='Cancelled'
		  WHERE PR_ID='".$PR_ID."' AND NetID='".$netid."'";

if (!$mysql -> Query($query)) {
	echo "Failed Updating request to inactive and cancelled: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}

echo "Success";


?>
