<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
/**
 * @author Roberto Ronderos Botero
 */
require_once ("dbConnection.php");

$sql="SELECT  Permission_N, CO_ID,CS_ID
	  FROM `Prof_P#_Assigns`
	  WHERE PA_ID='".$_POST['PA_ID']."' AND CURDATE()<=Expiration_Date";											
if (!$mysql -> Query($sql)) {
	echo "Failed retrieving permission N: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}

if($mysql->RowCount()>0){
		
	$mysql->MoveFirst();
	$row = $mysql->Row(); 
	$pn= $row->Permission_N;
	$CO_ID= $row->CO_ID;
	$CS_ID= $row->CS_ID;
	/*Change the status request*/
	$sql="UPDATE `Student_P#_Request`
		  SET Active='n' , Status='Used'
		  WHERE CO_ID='".$CO_ID."' AND	CS_ID='".$CS_ID."'	AND NetID='".$_SESSION['netid']."'";									
	if (!$mysql -> Query($sql)) {
		echo "Failed changing statis to request: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
		
	/*Delete permission number*/
	$sql="DELETE FROM `Permissions`		  
		  WHERE CS_ID='".$CS_ID."'	AND P_Number='".$pn."'";									
	if (!$mysql -> Query($sql)) {
		echo "Failed deleting permission number: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	}
	
	echo $pn;
}
else{
	echo "expired";
}
			
?>