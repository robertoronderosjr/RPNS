<!--	
 Authors: Roberto Ronderos Botero 
-->
<?php include "AJAX-PHP/header.php" ?>
<!--Modals-->
<?php include "AJAX-PHP/modals.html" ?>

<div class="container"> <!--DO NOT CLOSE, CLOSED IN FOOTER-->
      
      <!-- Only if not logged in -->
      <?php global $loggedin; if(!$loggedin){ ?>
      <div class="hero-unit">
        <h1>Hello, Rutgers!</h1>
        <p>Please Sign in to start using this great Rutgers Service.</p><br />
        <div class="container">
	      	<style>
	      	.form-signin {
		        max-width: 300px;
		        padding: 19px 29px 29px;
		        margin: 0 auto 20px;
		        background-color: #fff;
		        border: 1px solid #e5e5e5;
		        -webkit-border-radius: 5px;
		           -moz-border-radius: 5px;
		                border-radius: 5px;
		        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
		           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
		                box-shadow: 0 1px 2px rgba(0,0,0,.05);
		      }
		      .form-signin .form-signin-heading,
		      .form-signin .checkbox {
		        margin-bottom: 10px;
		      }
		      .form-signin input[type="text"],
		      .form-signin input[type="password"] {
		        font-size: 16px;
		        height: auto;
		        margin-bottom: 15px;
		        padding: 7px 9px;
		      }
	      </style>
		      <form action="AJAX-PHP/login.php" method="post" class="form-signin">
		        <h2 class="form-signin-heading">Please sign in</h2>
		        <input type="text" class="input-block-level" id="netidlogin" name="netidlogin" placeholder="NetID">
		        <input type="password" id="passwordlogin" name="passwordlogin" class="input-block-level" placeholder="Password">
				<!-- TO BE DONE       
		        <label class="checkbox">
		          <input type="checkbox" value="remember-me"> Remember me
		        </label>
		       -->
		       <input type="hidden" id="actionlogin" name="actionlogin" value="login">
		        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
		      </form>
	    </div>
      </div>  
      <?php }
	  		else{ ?>    
      <div class="hero-unit">
        <h2>R.P.N.S. - Rutgers Permission Number System</h2>
        <h3>Welcome, <?php if($_SESSION['type']==2){ echo " Professor ".$_SESSION['lastname']; }else{echo $_SESSION['name'];} ?></h3>
        <p>Choose an option from the Actions menu to start.</p>
      </div> 
      
      <!-- Example row of columns -->
      <div class="row">
        <div class="span4">
        	<div class="well">
              <ul id="navigationBarLeft" class="nav nav-list">
              	<?php if($_SESSION['type']==2){ ?>              		
	                <li class="nav-header">Actions</li>
	                <li class="divider"></li>
	                <li id="dashboardBtn" class="active"><a href="#">Dashboard</a></li>
	                <li id="addClassdBtn"><a href="#">Add Class</a></li>
	                <li id="seeClassesBtn"><a href="#">See Classes</a></li>
	                <li ><a href="profile.php">Profile</a></li>
	                <li ><a href="settings.php">Settings</a></li>                
	                <li class="nav-header">Other</li>
	                <li class="divider"></li>
	                <li><a href="/AJAX-PHP/logout.php">Log Out</a></li>
	                <li ><a href="help.php">Help</a></li>
	            <?php }
	  				else{ ?>
	  				<li class="nav-header">Actions</li>
	  				<li class="divider"></li>
	                <li id="dashboardBtn" class="active"><a href="#">Dashboard</a></li>
	                <li id="addClassdBtn"><a href="#">Request Permission #</a></li>
	                <li id="seeClassesBtn"><a href="#">See Requested</a></li>
	                <li ><a href="profile.php">Profile</a></li>
	                <li ><a href="settings.php">Settings</a></li>                 
	                <li class="nav-header">Other</li>
	                <li class="divider"></li>
	                <li><a href="/AJAX-PHP/logout.php">Log Out</a></li>
	                <li ><a href="help.php">Help</a></li>
	            <?php }?>	
              </ul>
           </div>
        </div>
        <div class="span8">
        	<style>
	        	  	.alert{
	        	  		width:90% !important;
	        	  		margin-top:5px;
	        	  		margin-bottom:5px;
	        	  	}
	        	  	
	        	  	.activeWindow{
	        	  		display:block;
	        	  	}
	        	  	.inactiveWindow{
	        	  		display:none;
	        	  	}
	        </style>
        	<?php if($_SESSION['type']==2){ //Professor's view'?>
	        	<div id="DashBoard" class="activeWindow"> 	  
	              <h2>Important Notifications</h2>
	              <div class="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Warning!</strong><a class="btn btn-link pull-right" href="#">View details »</a>
					<p>Information needed for permission number.</p>				
				  </div>			  
				  <div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Congratulations!</strong><a class="btn btn-link pull-right" href="#">View details »</a>
					<p>Your have succesfully assigned a permission number.</p>				
				  </div> 
				  <div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong><a class="btn btn-link pull-right" href="#">View details »</a>
					<p>There's an error in one of your classes.</p>				
				  </div>                
	            </div>
	            <div id="addClass" class="inactiveWindow"> 	  
	              <h2>Add a class</h2>
	               <?php include "AJAX-PHP/addClassProfessor.html";?>              
	            </div>
	            <div id="seeClasses" class="inactiveWindow"> 	  
	              <h2>Classes you created</h2>
	               <?php include "AJAX-PHP/seeClassesProfessor.php";?>         
	            </div>
	        <?php }
	  				else{ //Student's view?>
	  			<div id="DashBoard" class="activeWindow"> 	  
	              <h2>Important Notifications</h2>
	              <div class="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Warning!</strong><a class="btn btn-link pull-right" href="#">View details »</a>
					<p>Information needed for one of your requested permission numbers.</p>				
				  </div>			  
				  <div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Congratulations!</strong><a class="btn btn-link pull-right" href="#">View details »</a>
					<p>Your have been succesfully assigned a permission number.</p>				
				  </div> 
				  <div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong><a class="btn btn-link pull-right" href="#">View details »</a>
					<p>There's an error in one requests.</p>				
				  </div>                
	            </div>
	            <div id="requestPermission" class="inactiveWindow"> 	  
	              <h2>Request a Special Permission Number</h2>
	               <?php include "AJAX-PHP/requestPermission.html";?>              
	            </div>
	            <div id="seeRequested" class="inactiveWindow"> 	  
	              <h2>See Requested Permission Numbers</h2>
	               <?php include "AJAX-PHP/seeRequested.php";?>         
	            </div>	  					
	  	    <?php }?>	
       </div>        
 	 </div>
 	  <?php }?>

<?php include "AJAX-PHP/footer.php" ?>
