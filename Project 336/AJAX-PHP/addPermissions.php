<?php
/**
 * @author David Zafrani
 */
 session_start();
require("dbConnection.php");

	
echo $CS_ID=$_POST["csid"]; 
$spns=$_POST['spnsp'];//array();
print_r($spns);

foreach($spns as $pn){
	$sql="INSERT INTO Permissions (CS_ID, P_Number, Available) VALUES ('".$CS_ID."', ".$pn.", 'y')";
	echo $sql."<br/>";
	if (!$mysql -> Query($sql)){
		echo "Failed adding permission number ".$mysql->Error();;
		$mysql -> Kill();
		exit(1);
	}	
}
	
header('Location: http://cs336-31.rutgers.edu/index.php?alert=spnsAdded');


?>