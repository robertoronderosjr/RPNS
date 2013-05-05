$(document).ready(function() {
	
	$.get("../AJAX-PHP/addPermissions.php", {
		CO_ID : CO_ID
	}).done(function(data) {
		console.log(data);
		if (data != "success") {
			
		} else {
			
		}

	});


});