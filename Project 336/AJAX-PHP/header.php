<?php
/**
 * @author Roberto Ronderos Botero
 */

session_start();
include($_SERVER['DOCUMENT_ROOT'].'/AJAX-PHP/class.login.php');
$log = new logmein();

$userNav=false;
$loggedin=false;
if(isset($_SESSION['loggedin'])){
		
		if($log->logincheck($_SESSION['loggedin'], "User", $_SESSION['netid'])){
			$userNav=true;
			$loggedin=true;
		}
		else{
			$userNav=false;
			$loggedin=false;
		}
}

function echoActiveClassIfRequestMatches($requestUri)
{
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="active"';
}
?>
<!DOCTYPE html> 
<html lang="en">
  <head>    
    <title>Registration form - Permission Numbers Web App</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registration Form for Web App">
    <meta name="author" content="Roberto Ronderos">
    <meta name="author" content="Catalina Laverde">
    <meta name="author" content="David Zafrani">
    
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!--Css Style for date picker-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css">
    
    <!--General Css styles for this page-->
    <style>
		.alert{			
			position: relative;
			top: 42px;
			width: 59%;
			margin: 0 auto;
		}
		label.valid {
		  width: 24px;
		  height: 24px;
		  background: url(img/valid.png) center center no-repeat;
		  display: inline;
		  text-indent: -9999px;
		}
		label.error {
		  font-weight: bold;
			color: red;
			padding: 2px 12px;
			margin-left: 5px;
			top: 5px;
			position: relative;
		}
		#invalidLoginAlert, #registeredAlert{
			display:none;	
		}
		.socials {  
		padding: 10px;  
		} 
		
	</style>   
</head>

<body>
	<!--Alerts-->
	<div id="invalidLoginAlert" class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Invalid user!</strong> We couldn't find you in our system. try again.
    </div>
    
    <div id="registeredAlert" class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>You are succesfully registered!</strong> Welcome to RPNS, we hope you have a great experience using it!
    </div>
	
	<div class="navbar navbar-inverse navbar-fixed-top">
    
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.php">RPNS</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li <?php echoActiveClassIfRequestMatches("index")?>><a href="index.php">Home</a></li>
              <li <?php echoActiveClassIfRequestMatches("about")?>><a href="about.php">About</a></li>
              <li <?php echoActiveClassIfRequestMatches("contact")?>><a href="contact.php">Contact</a></li>              
            </ul>
			<?php global $userNav; if(!$userNav){ ?>
            <form action="AJAX-PHP/login.php" method="post" class="navbar-form pull-right">
              <input class="span2" type="text" id="netidlogin" name="netidlogin" placeholder="NetID">
              <input class="span2" type="password" id="passwordlogin" name="passwordlogin" placeholder="Password">
              <button type="submit" class="btn">Sign in</button>              
              <input type="hidden" id="actionlogin" name="actionlogin" value="login">
              <a href="registration.php" type="button" class="btn btn-success">Sign up!</a>
            </form>            
			<?php }
	  		else{ ?>
            	 
				
                <div class="btn-group pull-right">
                  <span style="color: white;font-size: 18px;position: relative;top: 6px;">Welcome,  <?php if($_SESSION['type']==2){ echo " Professor "; } ?></span>
                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <?php echo $_SESSION['name']." ". $_SESSION['lastname']; ?>
                    <span class="caret"></span>
                  </a>              
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                      <li><a tabindex="-1" href="profile.php">Profile</a></li>
                      <li><a tabindex="-1" href="settings.php">Settings</a></li>
                      <li><a tabindex="-1" href="help.php">Help</a></li>
                      <li class="divider"></li>                      
                      <li><a tabindex="-1" href="/AJAX-PHP/logout.php">Log Out</a></li>                      	
                    </ul>              
                </div>
               
            <?php }?>
            <ul class="nav">  
              <li class="dropdown">  
                <a href="#"  
                      class="dropdown-toggle"  
                      data-toggle="dropdown">  
                      Social  
                      <b class="caret"></b>  
                </a>  
                <ul class="dropdown-menu">  
                 <li class="socials"><!-- Place this tag where you want the +1 button to render -->  
                	<div class="g-plusone" data-size="tall" data-annotation="inline" data-width="300"></div>
                    <script type="text/javascript">
					  (function() {
						var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
						po.src = 'https://apis.google.com/js/plusone.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
					  })();
					</script>
                </li>  
              <li class="socials">
              	<div class="fb-like" data-href="http://cs336-31.rutgers.edu/" data-send="false" data-width="300" data-show-faces="false" data-font="lucida grande"></div>
				<script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=199984090036053";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
              </li>  
              <li class="socials"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>  
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>  
                </ul>  
              </li>  
            </ul>  
          </div><!--/.nav-collapse -->
        </div>
      </div>     
    </div>