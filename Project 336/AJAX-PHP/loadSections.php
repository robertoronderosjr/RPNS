<?php
	/**
	 * @author David Zafrani
	 */
 	session_start();
	require("dbConnection.php");
	$CO_ID=$_GET["CO_ID"]; // ERase for function
	$content=file_get_contents($file);
	$sql="SELECT * FROM Course_Section WHERE CO_ID=".$CO_ID;	
	if (!$mysql -> Query($sql)){
		echo "Failed to get course section ";
		$mysql -> Kill();
	}
	$mysql -> MoveFirst();
	$ret=array();
	$i=0;
	while (!$mysql -> EndOfSeek()) {
		$row = $mysql -> Row();	
		$r['csid']=$row ->CS_ID;
		$ret[$i++]=$r;
	}
	echo json_encode($ret);
?>