<?php
	/**
	 * @author David Zafrani
	 */
 	
	require("dbConnection.php");
	
	$CO_ID=$_GET["CO_ID"]; 
	
	$sql="SELECT * FROM Course_Section WHERE CO_ID='".$CO_ID."'";	
	if (!$mysql -> Query($sql)){
		echo "Failed to get course section ";
		$mysql -> Kill();
	}	
	$ret=array();
	while (!$mysql -> EndOfSeek()) {
		$row = $mysql -> Row();
		$element = array();
		$csid= $row->CS_ID;
		$sectionNmber = $row->Section_Number;
		$element['CS_ID']=$csid;
		$element['Section_number']=$sectionNmber;
		array_push($ret,$element);
	}
	echo json_encode($ret);
?>