<?php
session_start();
/**
 * @author Roberto Ronderos Botero, Catalina Laverde
 */

require ("dbConnection.php");

$CS_ID = intval($_GET['CS_ID']);

$sql = "DELETE FROM Course_Section WHERE CS_ID='".$CS_ID."'";

// Execute our query
if (!$mysql -> Query($sql)) {
	echo "failed";
	$mysql -> Kill();
} else {
	echo "success";
}
?>