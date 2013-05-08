<?php
/**
 * @author Roberto Ronderos Botero
 */
require_once ("dbConnection.php");

$query = "DELETE FROM Course		  
		  WHERE C_ID='".$_GET['C_ID']."'";

if (!$mysql -> Query($query)) {
	echo "Failed deleting class: ".$mysql->Error();
	$mysql -> Kill();
	exit(1);
}

echo "success";

?>
