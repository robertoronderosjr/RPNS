<?php
/**
 * @author Roberto Ronderos Botero, Catalina Laverde
 */
 session_start();
require("dbConnection.php");

	
$CS_ID= $_GET["CS_ID"]; 
$filename = $_GET["filename"]; 

$spns = file('../uploads/'.$filename);

foreach($spns as $pn){
	$sql="INSERT INTO Permissions (CS_ID, P_Number, Available) VALUES ('".$CS_ID."', ".$pn.", 'y')";	
	if (!$mysql -> Query($sql)){
		echo "Failed adding permission number ".$mysql->Error();;
		$mysql -> Kill();
		exit(1);
	}	
}
	
echo "success";


?>