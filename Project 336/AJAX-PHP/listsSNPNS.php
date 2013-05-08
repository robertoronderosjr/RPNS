<?php
	/**
	 * @author Roberto Ronderos Botero, Catalina Laverde
	 */
 	
	require("dbConnection.php");
	
	$CS_ID=$_GET["CS_ID"]; 
	
	$sql="SELECT P_Number FROM Permissions WHERE CS_ID='".$CS_ID."'";	
	if (!$mysql -> Query($sql)){
		echo "Failed to get permission numbers ";
		$mysql -> Kill();
	}	
	$ret=array();
	while (!$mysql -> EndOfSeek()) {
		$row = $mysql -> Row();
		$element = array();		
		$pn = $row->P_Number;
		$element['pnum']=$pn;
		array_push($ret,$element);
	}
	echo json_encode($ret);
?>