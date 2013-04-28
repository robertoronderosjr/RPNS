<!--	
 Author: Catalina Laverde Duarte
-->
<?php include "AJAX-PHP/header.php" ?>
    <style>
		.columnA, .columnB{
			width: 500px;
	  	}
	</style>

	<div class="container">
        <div class="hero-unit">
            <div class="row-fluid">
                <div class="columnA pull-left">
                <h1>Contact us</h1>
                <p>Want to get in touch? Send us an email!</p>  
                </div>
            
                <div class="columnB pull-right">
               	<img src="img/RU_SIG_HZ_PMS186_PMS431_100K.png" style="width:80%"/>
                </div>   
            </div>   
        </div>
    
    <div class="row">
    <div class="span3">
    </div>
    	<div class="span6">
        	<div class="well">
                <form id="contact" class="form-horizontal" method="post" action="AJAX-PHP/contacting.php">
                	<legend>Write to us</legend>
                  <div class="control-group">
                    <label class="control-label" for="fname">First name</label>
                    <div class="controls">
                      <div class="input-prepend">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input type="text" id="fname" name="fname" placeholder="First Name">
                            </div>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="lname">Last name</label>
                    <div class="controls">
							<div class="input-prepend">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input type="text" id="lname" name="lname" placeholder="Last Name">
                            </div>
                    </div>
                  </div>
                     <div class="control-group">
                        <label class="control-label" for="email">Email</label>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-envelope"></i></span>
                                <input type="text"  id="email" name="email" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                    	<label class="control-label" for="message">Message</label>
                        <div class="controls">
                            <textarea rows="10" name="message"></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                      <button type="submit" class="btn btn-large btn-primary">Send</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>       
        
<?php include "AJAX-PHP/footer.php" ?>
<script type="text/javascript" src="js/contact.js"></script>