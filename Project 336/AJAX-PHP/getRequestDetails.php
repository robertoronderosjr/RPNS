<?php
/**
 * @author Roberto Ronderos Botero, Catalina Laverde
 */
require_once ("dbConnection.php");

$query = "SELECT Responses
		  FROM `Student_P#_Request` 
		  WHERE PR_ID='".$_GET['PR_ID']."'";

if (!$mysql -> Query($query)) {
	echo "Failed retrieving responses: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}

$row = $mysql->Row();

echo $row->Responses;


?>