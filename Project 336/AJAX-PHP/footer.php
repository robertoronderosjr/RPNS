<!--	
 Authors: Roberto Ronderos Botero 
-->
	 <hr>

      <footer>
        <p>&copy; Rutgers University 2013</p>
      </footer>
      
    </div> <!-- /container -->
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="js/registration.js"></script>
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

?>