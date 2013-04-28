<?php
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");

$D_ID = intval($_GET['D_ID']);
$name = mysql_real_escape_string($_GET['name']);

$sql = "INSERT INTO Majors (D_ID, Name) VALUES ('".$D_ID."', '".$name."')";

// Execute our query
if (!$mysql -> Query($sql)) {
	echo "fail , D_ID=".$D_ID."name=".$name." ";
	$mysql -> Kill();
} else {
	echo "success";
}
?>