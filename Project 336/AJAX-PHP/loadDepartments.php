<?php
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");
$departmentsArray = array();
$query = "SELECT D_ID,Name FROM Department";
if (!$mysql -> Query($query))
	$mysql -> Kill();
$mysql -> MoveFirst();
while (!$mysql -> EndOfSeek()) {

	$row = $mysql -> Row();
	$index = $row -> D_ID;
	$departmentsArray[$index] = $row -> Name;
}

echo json_encode($departmentsArray);
?>