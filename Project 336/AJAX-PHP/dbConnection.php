<?php
/**
 * @author Roberto Ronderos Botero
 */
require("credentials.php");
require_once("mysql.class.php"); 
$mysql = new MySQL(true,DBNAME, DBHOST, DBUSERNAME, DBPASSWORD);
if ($mysql->Error()){ echo "Error connecting to the DB"; $mysql->Kill(); }  
?>