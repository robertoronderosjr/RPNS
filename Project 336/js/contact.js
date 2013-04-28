$(document).ready(function() {
	
	 $.validator.addMethod("textOnly", 
	 function(value, element) {
		   return /^[a-zA-Z' ]+$/.test(value);
		 }, 
		 "Alpha Characters Only."
	 );	
	
	//validation
	$("#contact").validate({
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
})
