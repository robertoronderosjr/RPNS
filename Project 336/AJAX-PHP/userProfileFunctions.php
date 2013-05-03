<?php
error_reporting(E_ALL);
ini_set("display_errors","1");
/**
 * @author David Zafrani
 */
require("dbConnection.php");

function getUserInfoForProfile($net){
	global $mysql;
	$sql="
		SELECT * 
		FROM User
		WHERE NetID= '".$net."'
	";
	if (!$mysql -> Query($sql)) {
		echo "failed to retrieve user info: ".$mysql->Error();
		$mysql -> Kill();
		exit(1);
	} 
	$mysql->MoveFirst(); 
	$row=$mysql->Row();
	$userarray= array("fname"=>$row->Name, "lname"=>$row->Last_Name, "email"=>$row->Email, "dob"=>$row->DOB, "gender"=>$row->Gender);
	return $userarray;
}
	

?>