<?php
/**
 * @author Roberto Ronderos Botero, Catalina Laverde
 */
include($_SERVER['DOCUMENT_ROOT'].'/AJAX-PHP/class.login.php');
$log = new logmein();
$log->logout();
$URL =  $_SERVER['HTTP_REFERER'] ;
$url = explode("?",$URL);
$urlNoQuery = $url[0];
header('Location: ' . $urlNoQuery);
?>