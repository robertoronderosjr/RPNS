/**
 * Author: Roberto Ronderos Botero
 */
$(document).ready(function() {
	
	if(typeof(alertInvalidLogin) != "undefined" && alertInvalidLogin !== null && alertInvalidLogin!="") {
		 if(alertInvalidLogin==true){
			$("#invalidLoginAlert").show();					
		 }
	 }
	 if(typeof(alertRegistered) != "undefined" && alertRegistered !== null && alertRegistered!="") {
		 if(alertRegistered==true){
			$("#registeredAlert").show();					
		 }
	 }
	 
 	//Date Picker for D.O.B.
	$('#datetimepicker').datetimepicker({
      pickTime: false,
	  viewMode: "years"
    });
	
	//Validation methods implemented
	$.validator.addMethod('strongPassword', function(value, element) {
        return this.optional(element) || (value.match(/[a-zA-Z]/) && value.match(/[0-9]/));
    },
    'Password must contain at least one numeric<br>and one alphabetic character.');
	
	
	 $.validator.addMethod("textOnly", 
		 function(value, element) {
			   return /^[a-zA-Z' ]+$/.test(value);
			 }, 
			 "Alpha Characters Only."
	 );	
	
	//validation
	$("#signup").validate({
        rules: {
            fname: {
                required: true,
                maxlength: 45,
				textOnly:true
            },
			lname:{
				required:true,
				maxlength: 45,
				textOnly:true				
			},
			email:{
				required:true,
				maxlength: 45,
				email: true				
			},
			dob:{
				date: true	
			},
            netid: {
                required: true,
                remote: "../AJAX-PHP/checkNetID.php"
            } ,
			passwd:{
				required:true,
				minlength: 7,
				strongPassword:true					
			},
			conpasswd:{
				required:true,
				minlength: 7,
           		equalTo: "#passwd",
				strongPassword:true	
			}
        },
        messages: {
            fname: {
                required: "Name Required",
                maxlength: "Your name is too long. Must be at most {0} characters.",
				textOnly: "Can your name contain numbers?"
			},
			lname:{
				required: "Last Name Required",
                maxlength: "Your last name is too long. Must be at most {0} characters.",
				textOnly: "Can your last name contain numbers?"
			},
			email:{
				required: "Email Required",
                maxlength: "Your last name is too long. Must be at most {0} characters.",
				email: "Please input a valid e-mail address"
			},
			dob:{				
				date: "Please input a date"
			},
			netid:{				
				required: "Your NetID is required",
				remote: "That NetID is already registered in our system"
			},
			passwd:{
				required: "Password Required",
				minlength: "You need at least {0} characters in your password"
			},
			conpasswd:{
				required: "Password confirmation required",
				minlength: "You need at least {0} characters in your password",
				equalTo: "Your passwords do not match"				
			}
			
        },
	  highlight: function(element) {
		$(element).closest('.control-group').removeClass('success').addClass('error');
	  },
	  success: function(element) {
		element
		.addClass('valid')
		.closest('.control-group').removeClass('error').addClass('success');
	  }
    });
	
		
	//events
	$("#maleGender").click(function(){
		$("#genderHidden").val("");
		$("#genderHidden").val('m');
	});
	$("#femaleGender").click(function(){
		$("#genderHidden").val("");
		$("#genderHidden").val('f');
	});
	
	$("#dob").click(function(){
		$('#datetimepicker').datetimepicker('show');
	});

});
