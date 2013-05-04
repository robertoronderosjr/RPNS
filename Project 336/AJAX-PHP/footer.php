<!--	
 Authors: Roberto Ronderos Botero 
-->
	 <hr>

      <footer>
        <p>&copy; Rutgers University 2013</p>
      </footer>
      
    </div> <!-- /container  -->
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
    <?php 
    	$query = $_SERVER['PHP_SELF'];
		$path = pathinfo( $query );
		$file = $path['basename'];
    	if(strcmp($file, "registration.php")==0){ ?>
		<script type="text/javascript" src="js/registration.js"></script>
	<?php }else if(strcmp($file,"registration.php")>0){ ?>
		<script type="text/javascript" src="js/updateProfile.js"></script>
   	<?php } ?>
    </script>
    <?php if($_SESSION['type']==2){ ?>
    <script type="text/javascript" src="js/professor.js"></script>
    <?php } else{ ?>
    <script type="text/javascript" src="js/student.js"></script>
    <?php } ?>
</body>
</html>
<?php
if(isset($_GET['alert'])){
	if($_GET['alert']=='invalid'){
		echo "<script>var alertInvalidLogin=true;</script>";
	}
	else if($_GET['alert']=='registered'){
		echo "<script>var alertRegistered=true;</script>";
	}
	else if($_GET['alert']=='courseAdded'){
		echo "<script>var courseAdded=true;</script>";
	}
}
else if(isset($_GET['classEdit'])){	
	echo "<script>var classEdit='".$_GET['classEdit']."';</script>";	
}


function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>