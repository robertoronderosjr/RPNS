<?php
/**
 * @author Roberto Ronderos Botero
 */
require("dbConnection.php");

if (! $mysql->Query("SELECT * FROM User WHERE NetID='".trim(strtolower($_GET['netid']))."'")) $mysql->Kill(); 

if( $mysql->RowCount() == 1){
	echo json_encode(FALSE);
}
else{
	echo json_encode(TRUE);
}

 

?>