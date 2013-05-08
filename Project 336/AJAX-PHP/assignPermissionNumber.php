<?php
session_start();
/**
 * @author Roberto Ronderos Botero
 */
require_once ("dbConnection.php");

$comments="";
if(isset($_GET['comments'])){
	$comments=$_GET['comments'];
}

$query = "SELECT *
		  FROM `Student_P#_Request` 
		  WHERE PR_ID='".$_GET['PR_ID']."'";

if (!$mysql -> Query($query)) {
	echo "Failed retrieving request from student: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$mysql->MoveFirst(); 
$row = $mysql->Row();
$prid = $row->PR_ID;
$assignee = $row->NetID;
$CO_ID=$row->CO_ID;
$CS_ID=$row->CS_ID;
/*Check that there's enough space in the course*/
$query = "SELECT MAX_SIZE,CurrentSize
		  FROM `Course_Offering` 
		  WHERE CO_ID='".$CO_ID."'";

if (!$mysql -> Query($query)) {
	echo "Failed max and current class sizes: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}
$mysql->MoveFirst(); 
$row = $mysql->Row();
$maxSize = $row->MAX_SIZE;
$currentSize = $row->CurrentSize;

if($currentSize>$maxSize){
	echo "Room Full.";	
	exit(1);
}

/*Check that there's a permission number available for that class*/
$query = "SELECT `PN_ID`, `P_Number`
		  FROM `Permissions` 
		  WHERE CS_ID='".$CS_ID."' AND Available='y'";

if (!$mysql -> Query($query)) {
	echo "Failed retrieving available permission #: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}

if($mysql->RowCount()>0){
	$mysql->MoveFirst(); 
	$row = $mysql->Row();
	$spnid = $row->PN_ID;
	$spn = $row->P_Number;//This is the special permission number
	/*Make permission unavailable*/
	$query = "
			  UPDATE `Permissions` SET Available='n' WHERE PN_ID='".$spnid."'			  
			 ";
	
	if (!$mysql -> Query($query)) {
		echo "Failed making permission unavailable: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
	
	/*assign permission number*/
	$query = "
			  INSERT INTO `Prof_P#_Assigns` (Assigner,Assignee,CO_ID,CS_ID,Permission_N,Comments,Expiration_Date)
			  VALUES ('".$_SESSION['netid']."','".$assignee."','".$CO_ID."','".$CS_ID."','".$spn."','".$comments."','".$_GET['expiration']."')
			 ";
	
	if (!$mysql -> Query($query)) {
		echo "Failed assigning sp #: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
	
	/*Change student request status*/
	$query = "
			  UPDATE `Student_P#_Request` SET Status='Assigned' WHERE PR_ID='".$prid."'			  
			 ";
	
	if (!$mysql -> Query($query)) {
		echo "Failed changing permission status: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
	
	/*Send notification*/
	include_once("notifyAssignPermission.php");
	/*Send email*/
	include_once("sendEmailAssignPermission.php");

	
	/*Go back and return success*/
	echo "success";
	
}
else{
	echo "No available SPNS for this section";
	exit(1);
}




?>