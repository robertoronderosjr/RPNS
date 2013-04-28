<!--	
 Author: Roberto Ronderos Botero
-->
<?php include "AJAX-PHP/header.php" ?>
    
    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h1>Registration</h1>
        <p>Please register in our system to start using this great app!</p>        
      </div>

      <!-- Example row of columns -->
      
      <div class="row"> 
      	
            <div class="span7">
            	<div class="well">
                	<form id="signup" class="form-horizontal" method="post" action="AJAX-PHP/register.php">
                        <legend>Sign Up</legend>
                        <div class="control-group">
                            <label class="control-label" for="fname">First Name</label>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input type="text" id="fname" name="fname" placeholder="First Name">
                                </div>
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label" for="lname">Last Name</label>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input type="text"  id="lname" name="lname" placeholder="Last Name">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="email">Email</label>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-envelope"></i></span>
                                    <input type="email"  id="email" name="email" placeholder="Email">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="gender">Gender</label>
                            <div class="controls">
                
                                    <p><div id="gender" name="gender" class="btn-group" data-toggle="buttons-radio">
                                    <button type="button" class="btn btn-info" id="maleGender" >Male</button>
                                    <button type="button" class="btn btn-info" id="femaleGender" >Female</button>
                
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
                                       <input data-format="yyyy-MM-dd" type="text" id="dob" name="dob" placeholder="D.O.B."></input>                             
                                 </div> 
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="netid">NetID</label>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input type="text"  id="netid" name="netid" placeholder="NetID">
                                </div>
                            </div>
                        </div>            
                        <div class="control-group">
                            <label class="control-label" for="passwd">Password</label>
                            <div class="controls">
                                <div class="input-prepend">
                                <span class="add-on"><i class="icon-lock"></i></span>
                                    <input type="Password" id="passwd"  name="passwd" placeholder="Password">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="conpasswd">Confirm Password</label>
                            <div class="controls">
                                <div class="input-prepend">
                                <span class="add-on"><i class="icon-lock"></i></span>
                                    <input type="Password" id="conpasswd"  name="conpasswd" placeholder="Re-enter Password">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="aType">Account Type</label>
                            <div class="controls">
                                <div class="input-prepend">
                                <span class="add-on"><i class="icon-exclamation-sign"></i></span>
                                    <select id="aType"  name="aType" placeholder="Account Type">
                                        <option value="1">Student Account</option>
                                        <option value="2">Professor Account</option>
                                    </select>
                                </div>
                            </div>
                        </div>            
                        <div class="control-group">
                            <label class="control-label"></label>
                            <div class="controls">
                             <button type="submit" class="btn btn-success" >Create My Account</button>    
                            </div>
                
                        </div>
                        
                                               
                        <input type="hidden" id="genderHidden" name="genderHidden" value="">
                        
                    </form>
                </div>
            </div>
            <div class="span5" style="text-align:center">
           		<img src="img/RU_SIG_HZ_PMS186_PMS431_100K.png"  style="margin-left: -10px;">
                <br>
                <br>
                <br>
                <img src="img/Rutgers_athletics_logo.png" class="img-rounded" >
            </div> 
         
      </div>     
	
<?php include "AJAX-PHP/footer.php" ?>
    
