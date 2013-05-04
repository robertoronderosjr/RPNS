<?php
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");
$majorsArray = array();
if(!isset($_GET['D_ID'])){
$query = "SELECT M_ID,Name FROM Majors";
}
else{
$query = "SELECT M_ID,Name FROM Majors WHERE D_ID='".$_GET['D_ID']."'";	
}
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