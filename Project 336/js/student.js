var cancelled;
$(document).ready(function() {
	
	/*alert request done*/
	if ( typeof (requestDone) != "undefined" && requestDone !== null && requestDone != "") {
			if (requestDone) {
				alertWindow("Success!", "You have succesfully requested a permission number.", "alert-success");
			}
		}
	
	/*alert request done*/
	
	if ( typeof (noSPNS) != "undefined" && noSPNS !== null && noSPNS != "") {
			
			if (noSPNS) {
				alertWindow("Error!", "There are no permission numbers available for this section.", "alert-error");
			}
		}
		
		
	/*room full*/	
	if ( typeof (roomFull) != "undefined" && roomFull !== null && roomFull != "") {
			
			if (roomFull) {
				alertWindow("Error!", "There are no spots in that room. The room is full.", "alert-error");
			}
		}
	
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
	
	$(".cancelRequestBtn").click(function(){
		var prid = $(this).attr("prid");
		console.log(prid);
		$.ajax({
			url : "AJAX-PHP/deleteRequest.php",
			data: { PR_ID: prid },
			async:false
		}).done(function(data) {
			console.log(data);		
			if(data=="Success"){				
				alertWindow("Success!", "Your Request was deleted from the system.", "alert-success");
				cancelled=true;
			}
			else{
				alertWindow("Error!", data, "alert-error");
				cancelled=false;
			}
		});
		if(cancelled){
			$(this).closest('.accordion-group').remove();
		}
		
	});
	
	$('#seeRequested').on('click', ".useIt", function(){			
		var paid= $(this).attr('paid');
		$("#seeSPNModal").data('paid',paid);
		$("#spnModalDiv").css('display','n');
		$("#seeSPNModal").modal("show");
	});
	
	$("#useItFinal").click(function(){
		var paid= $("#seeSPNModal").data('paid');
		getSPN(paid);
	});
	
})

function getSPN(paid){
	$.ajax({
		type: "POST",
		url : "AJAX-PHP/getSPN.php",
		data: { PA_ID: paid },
		async : false
	}).done(function(data) {
		console.log(data);
		if(data!='expired'){
			$("#spnModal").html(data);
		}
		else{
			$("#spnModal").html('Sorry but your Special Permission Number Expired!');
		}
		$("#spnModalDiv").css('display','block');

	});
}

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