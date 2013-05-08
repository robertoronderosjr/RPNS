<?php
/**
 * @author Roberto Ronderos Botero, Catalina Laverde
 */
require ("dbConnection.php");
$universityArray = array();
$query = "SELECT U_ID,Name FROM Universities";
if (!$mysql -> Query($query))
	$mysql -> Kill();
$mysql -> MoveFirst();
while (!$mysql -> EndOfSeek()) {

	$row = $mysql -> Row();
	$index = $row -> U_ID;
	$universityArray[$index] = $row -> Name;
}

echo json_encode($universityArray);
?>