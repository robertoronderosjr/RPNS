$(document).ready(function() {
	
	loadDepartments();
	/*Select handlers*/
	$("#departments").change(function() {
			var depID = $(this).val();
			loadMajors(depID);
	});
	
	
	/*Button Handlers*/
   $("#dashboardBtn").click(function() {
		if ($('#DashBoard').hasClass('inactiveWindow')) {
			$('.activeWindow').removeClass('activeWindow').addClass('inactiveWindow');
			$('#DashBoard').removeClass('inactiveWindow').addClass('activeWindow');
			$('#navigationBarLeft .active').removeClass('active');
			$(this).addClass('active');
		}
	});

	$("#addClassdBtn").click(function() {
		
		if ($('#requestPermission').hasClass('inactiveWindow')) {
				$('.activeWindow').removeClass('activeWindow').addClass('inactiveWindow');
				$('#requestPermission').removeClass('inactiveWindow').addClass('activeWindow');
				$('#navigationBarLeft .active').removeClass('active');
				$(this).addClass('active');
		}		
	});

	$("#seeClassesBtn").click(function() {
		if ($('#seeRequested').hasClass('inactiveWindow')) {
			$('.activeWindow').removeClass('activeWindow').addClass('inactiveWindow');
			$('#seeRequested').removeClass('inactiveWindow').addClass('activeWindow');
			$('#navigationBarLeft .active').removeClass('active');
			$(this).addClass('active');
		}
	});
	
	$("#lookup").click(function() {
		var D_ID= $('#departments option:selected').val();
		var M_ID= $('#majors option:selected').val();
		var semester = $("#semesters option:selected").val();
		console.log(D_ID,M_ID,semester);
		if(D_ID!=""&&M_ID!=""&&semester!=""){
			$('#courseResults').load('AJAX-PHP/availableClasses.php?D_ID='+D_ID+'&M_ID='+M_ID+'&Semester='+semester+'');
		}
		else{
			alertWindow("Error!", "Please select a value from all drop down boxes.", "alert-error");
		}
	});
	
})

function loadDepartments() {
	$('#departments').empty();
	$.ajax({
		url : "AJAX-PHP/loadDepartments.php",
		async : false
	}).done(function(data) {
		console.log(data);
		var obj = jQuery.parseJSON(data);
		$('#departments').append('<option value="">Select a Department</option>');
		$.each(obj, function(key, val) {
			$('#departments').append('<option value="' + key + '">' + val + '</option>');
		});

	});
}

function loadMajors(D_ID) {
	$("#majors").empty();
	$("#majors").append('<option value="">Select A Major</option>');
	$.ajax({
		url : "AJAX-PHP/loadMajors.php",
		data: { D_ID: D_ID },	
		async : false
	}).done(function(data) {		
		var obj = jQuery.parseJSON(data);
		$.each(obj, function(key, val) {
			$("#majors").append('<option value="' + key + '">' + val + '</option>');

		});
	});
}

function alertWindow(alertInfo, alertDesc, type) {
	$("#AlertWindow #alertInfo").html(alertInfo);
	$("#AlertWindow #alertDesc").html(alertDesc);
	if (type == 'alert-error') {
		if ($("#AlertWindow").hasClass('alert-success')) {
			$("#AlertWindow").removeClass('alert-success').addClass('alert-error');
		} else {
			$("#AlertWindow").addClass('alert-error');
		}
	} else {
		if ($("#AlertWindow").hasClass('alert-error')) {
			$("#AlertWindow").removeClass('alert-error').addClass('alert-success');
		} else {
			$("#AlertWindow").addClass('alert-success');
		}
	}
	$("#AlertWindow").fadeIn('slow');
	window.setTimeout(function() {
		$("#AlertWindow").slideUp();
	}, 5000);
}