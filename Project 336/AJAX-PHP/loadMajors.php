<?php
/**
 * @author Roberto Ronderos Botero, Catalina Laverde
 */
require ("dbConnection.php");
$majorsArray = array();
$query = "SELECT M_ID,Name FROM Majors";
if (!$mysql -> Query($query))
	$mysql -> Kill();
$mysql -> MoveFirst();
while (!$mysql -> EndOfSeek()) {

	$row = $mysql -> Row();
	$index = $row -> M_ID;
	$majorsArray[$index] = $row -> Name;
}

echo json_encode($majorsArray);
?>