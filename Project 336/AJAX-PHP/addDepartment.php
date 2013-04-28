<?php
/**
 * @author Roberto Ronderos Botero
 */

require ("dbConnection.php");

$U_ID = intval($_GET['U_ID']);
$name = mysql_real_escape_string($_GET['name']);

$sql = "INSERT INTO Department (U_ID, Name) VALUES ('".$U_ID."', '".$name."')";

// Execute our query
if (!$mysql -> Query($sql)) {
	echo "fail , U_ID=".$U_ID."name=".$name." ";
	$mysql -> Kill();
} else {
	echo "success";
}
?>