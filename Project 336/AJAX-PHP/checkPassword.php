<?php
/**
 * @author David Zafrani
 */
 session_start();
require("dbConnection.php");


if (! $mysql->Query("SELECT * FROM User WHERE NetID='".trim(strtolower($_SESSION['netid']))."' AND Password='".$_GET['oldpass']."'"))$mysql->Kill(); 

if( $mysql->RowCount() == 1){
	echo json_encode(TRUE);
}
else{
	echo json_encode(FALSE);
}

 

?>