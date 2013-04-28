<?php
/**
 * @author Roberto Ronderos Botero
 */
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/AJAX-PHP/class.login.php');
$log = new logmein();
if($_POST['actionlogin'] == "login"){
    if($log->login("User", $_POST['netidlogin'], $_POST['passwordlogin']) == true){
		$URL =  $_SERVER['HTTP_REFERER'] ;
		$url = explode("?",$URL);
		$urlNoQuery = $url[0];
        header('Location: ' . $urlNoQuery);
    }else{
	   if(strstr($_SERVER['HTTP_REFERER'],"?alert=invalid")==""){ //url doesn't contain it
		   header('Location: ' . $_SERVER['HTTP_REFERER']."?alert=invalid");
	   }
	   else{
		   header('Location: ' . $_SERVER['HTTP_REFERER']); 
	   }
       
    }
}
?>