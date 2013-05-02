<?php
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");
$term = $_GET['term'];

$coursesArray = array();
$query = "SELECT C_ID,Name FROM Course WHERE Name LIKE '%".$term."%'";
if (!$mysql -> Query($query))
	$mysql -> Kill();
$mysql -> MoveFirst();
$i=0;
while (!$mysql -> EndOfSeek()) {

	$row = $mysql -> Row();	
	$entry['label']=$row -> Name;
	$entry['value']=$row -> Name;
	$entry['id']=$row -> C_ID;
	$coursesArray[$i] = $entry;
	$i++;
}

echo json_encode($coursesArray);
?>