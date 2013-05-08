<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
/**
 * @author Roberto Ronderos Botero 
 * @contributor Catalina Laverde Duarte
 */
require ("dbConnection.php");

$CO_ID = intval($_GET['CO_ID']);
$C_ID = intval($_GET['C_ID']);
$Section_Number = mysql_real_escape_string($_GET['Section_Number']);

$sql = "INSERT INTO Course_Section (CO_ID, Section_Number,Teached_By) VALUES ('".$CO_ID."', '".$Section_Number."','".$_SESSION['netid']."')";

// Execute our query
if (!$mysql -> Query($sql)) {
	echo "failed";
	$mysql -> Kill();
	exit(1);
} 

include_once("addSectionNotifications.php");

?>