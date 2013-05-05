<!--	
 Authors: David Zafrani
-->
<?php include "AJAX-PHP/header.php" ?>
<?php include "AJAX-PHP/userProfileFunctions.php" ?>
<!--Modals-->
<?php include "AJAX-PHP/modals.html" ?>
<!--Alert alert-success for green, alert-error for red-->

<div class="container"> <!--DO NOT CLOSE, CLOSED IN FOOTER-->
    <!-- Only if not logged in -->
    <?php global $loggedin; if(!$loggedin){ ?>
		<div class="hero-unit">
	        <h2>Hello, please sign in or register an account.</h2>
		</div>	
    <?php }else {$userprofileinfo=getUserInfoForProfile($_SESSION["netid"]); ?>	
    	
	    <div class="hero-unit">
	        <h2>Welcome, <?php if($_SESSION['type']==2){ echo " Professor ".$_SESSION['lastname']; }else{echo $_SESSION['name'];} ?></h2>
	        <div class="row"> 
	            <div class="span7">
	            	<div class="well">
	                	<form id="profile" class="form-horizontal" method="post" action="AJAX-PHP/editProfile.php">
                        <legend><?php echo $_SESSION["netid"]; ?></legend>
                        <div class="control-group">
                            <label class="control-label" for="fname">First Name</label>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input type="text" id="fname" name="fname" value="<?php echo $userprofileinfo["fname"]; ?>" placeholder="First Name">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="lname">Last Name</label>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input type="text"  id="lname" name="lname" value="<?php echo $userprofileinfo["lname"]; ?>" placeholder="Last Name">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="email">Email</label>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-envelope"></i></span>
                                    <input type="email"  id="email" name="email" value="<?php echo $userprofileinfo["email"]; ?>" placeholder="Email">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="gender">Gender</label>
                            <div class="controls">
                                    <p><div id="gender" name="gender" class="btn-group" data-toggle="buttons-radio">
                                    <button <?php echo $userprofileinfo["gender"] == "m" ? "class=\"btn btn-info active\"" : "class=\"btn btn-info\""; ?> type="button" id="maleGender" >Male</button>
                                    <button <?php echo $userprofileinfo["gender"] == "f" ? "class=\"btn btn-info active\"" : "class=\"btn btn-info\""; ?> type="button" id="femaleGender" >Female</button>
                                  </div></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="dob">Date of Birth</label>
                            <div class="controls">
                                 <div id="datetimepicker" class="input-prepend">
                                       <span class="add-on">
                                           <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                       </span>
                                       <input data-format="yyyy-MM-dd" type="text" id="dob" name="dob" placeholder="D.O.B." value="<?php echo $userprofileinfo["dob"] ?>"></input>                             
                                 </div> 
                            </div>
                        </div>   
                        <div class="control-group">
                            <label class="control-label" for="oldpass">Old Password</label>
                            <div class="controls">
                                <div class="input-prepend">
                                <span class="add-on"><i class="icon-lock"></i></span>
                                    <input  type="Password" id="oldpassSeen" placeholder="Old Password">
                                    <input  type="hidden" id="oldpass"  name="oldpass" placeholder="Old Password">
                                </div>
                            </div>
                        </div>  
                        <div class="control-group">
                            <label class="control-label" for="passwd">New Password</label>
                            <div class="controls">
                                <div class="input-prepend">
                                <span class="add-on"><i class="icon-lock"></i></span>
                                    <input type="Password" id="passwdSeen"  name="passwd" placeholder="Password">
                                    <input  type="hidden" id="passwd"  name="passwd" placeholder="Old Password">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="conpasswd">Confirm New Password</label>
                            <div class="controls">
                                <div class="input-prepend">
                                <span class="add-on"><i class="icon-lock"></i></span>
                                    <input type="Password" id="conpasswdSeen"   placeholder="Re-enter Password">
                                    <input  type="hidden" id="conpasswd"  name="conpasswd" placeholder="Old Password">
                                </div>
                            </div>
                        </div>         
                        <div class="control-group">
                            <label class="control-label"></label>
                            <div class="controls">
                            	<button type="submit" class="btn btn-success" >Save Settings</button>    
                            </div>                
                        </div>                        
                                               
                        <input type="hidden" id="genderHidden" name="genderHidden" value="<?php echo $userprofileinfo["gender"]; ?>" />
                        
                    </form>
	               </div>
	           </div>
           </div>
	    </div> 
    <?php } ?>	

<?php include "AJAX-PHP/footer.php" ?>

<script type="text/javascript" src="js/updateProfile.js"></script>