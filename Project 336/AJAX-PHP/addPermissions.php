<?php
/**
 * @author David Zafrani
 */
 session_start();
require("dbConnection.php");

	$file=$_POST["file"]; // Erase for function
	$CO_ID=$_POST["CO_ID"]; // ERase for function
	$content=file_get_contents($file);
	$content=explode(",", $content);
	$sql="SELECT * FROM Course_Section WHERE CO_ID=".$CO_ID;	
	if (!$mysql -> Query($query)){
		echo "Failed to get course section";
		$mysql -> Kill();
	}
	$mysql -> MoveFirst();
	while (!$mysql -> EndOfSeek()) {
		$row = $mysql -> Row();
		for($i=0;$i<count($content);$i++){
		$sql="INSERT INTO Permissions (CS_ID, P#, Available) VALUES ('".$row->CS_ID."', ".$content[$i].", 'y')";
		if (!$mysql -> Query($query)){
			echo "Failed";
			$mysql -> Kill();
		}
	
	}
		
	}
		
	
	echo "Success";


?>