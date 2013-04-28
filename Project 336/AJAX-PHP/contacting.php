<?php
/**
 * @author Catalina Laverde Duarte
 */
 
 
//Add user to DB and send him and e-mail with its information
//get post data

$name = $_POST['fname'];
$lastname = $_POST['lname']; 
$emailFrom = $_POST['email'];
$message = $_POST['message'];

//Send the email with the message
$to = "catalavdu@gmail.com";
$subject = "RPNS - Message from ".$name."";
$message = $message;
$from = $emailFrom;
$headers = "From:" . $emailFrom;
mail($to,$subject,$message,$headers);	

header('Location: ' . $_SERVER['HTTP_REFERER']); 

?>