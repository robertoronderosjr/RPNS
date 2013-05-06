<?php
session_start();
/**
 * @author Roberto Ronderos Botero
 */
require ("dbConnection.php");

$netid = $_SESSION['netid'];
$PR_ID = $_GET['PR_ID'];

$query = "DELETE FROM `Student_P#_Request`
		  WHERE PR_ID='".$PR_ID."' AND U_ID='".$netid."'";

if (!$mysql -> Query($query)) {
	echo "Failed Deteting request: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}

echo "Success";


?>
