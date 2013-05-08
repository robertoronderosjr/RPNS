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

/*Change student request status*/
$query = "UPDATE `Student_P#_Request` SET Status='Denied',Active='n' WHERE PR_ID='".$prid."'";
	
if (!$mysql -> Query($query)) {
		echo "Failed denying request: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
}

/*Decrement room current size*/
/*Update room capacity*/
		$sql = "UPDATE Course_Offering SET CurrentSize=CurrentSize-1 WHERE CO_ID='".$CO_ID."'";
		// Execute our query
		if (!$mysql -> Query($sql)) {
			echo "Failed updating room size: ".$mysql->Error();
			$mysql -> Kill();
			exit(1);
		}

/*Send notification*/
include_once("notifyAssignPermissionD.php");
/*Send email*/
include_once("sendEmailAssignPermissionD.php");

	
/*Go back and return success*/
echo "success";	


?>