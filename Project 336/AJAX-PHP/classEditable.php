<?php
session_start();
/**
 * @author Roberto Ronderos Botero, Catalina Laverde
 */

require ("dbConnection.php");

$C_ID = intval($_GET['C_ID']);

$sql = "SELECT * FROM `Student_P#_Request` WHERE CourseID='".$C_ID."' AND Active='y'";

// Execute our query
$mysql -> Query($sql);
if($mysql->RowCount()==0){
		echo "editable";
}
else{
		echo 'non editable';
}

?>