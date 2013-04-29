/**
 * @author Roberto Ronderos Botero
 */
var receivedItem; //variable used to get received item object in the criteria lists
$(document).ready(function() {
	
	/* drag-list */
	  $( ".sortable" ).sortable({
	      connectWith: '.sortable',
	      placeholder: "ui-state-highlight"
	    });
	  
	  $( ".sortable.ordered" ).on( "sortreceive", function( event, ui ) {		 
		  receivedItem = $(ui.item);
		  var modalTitle = $("#criteriaModalLabel");
		  var modalBodyContent = $("#criteriaModal .modal-body");
		  var bodyContent;
		  if(receivedItem.attr('value')=='universityYear'){
			  
					modalTitle = $("#criteriaModalLabel");
					modalBodyContent = $("#criteriaModal .modal-body");
					bodyContent = 
						'<div class="control-group">'+
							'<label class="control-label" for="year"><b>Please select which year is prefered for this class:</b></label>'+
							'<br/>'+
							'<div class="controls">'+
								'<input type="radio" name="preferedYearModal" value="freshman">'+
								'Freshman'+
								'<br>'+
								'<input type="radio" name="preferedYearModal" value="sophomore">'+
								'Sophomore'+
								'<br>'+
								'<input type="radio" name="preferedYearModal" value="junior">'+
								'Junior'+
								'<br>'+
								'<input type="radio" name="preferedYearModal" value="senior">'+
								'Senior'+
								'<br>'+
							'</div>'+
							'<br/>'+
						'</div>';
					
					modalTitle.html('University Year Prefered');
					
			  
		  }
		  else if(receivedItem.attr('value') == 'creditsCompleted'){
			  modalTitle = $("#criteriaModalLabel");
				modalBodyContent = $("#criteriaModal .modal-body");
				bodyContent = 
					'<div class="control-group">'+
						'<label class="control-label" for="numberCreditsModal"><b>Specify the minimum preferred number of credits (inclusive):</b></label>'+
						'<br/>'+
						'<div class="controls">'+
							'<input type="text" id="numberCreditsModal" name="numberCreditsModal" placeholder="Number of credits" value="">'+
						'</div>'+
						'<br/>'+
					'</div>';
				
				modalTitle.html('Prefered number of credits');
		  }
		  modalBodyContent.html(bodyContent);					
		  $("#criteriaModal").modal("show");
	  } );
	/* Function inits */

	loadMajors();

	/* Select2 initializations */

	/* Button handlers */

	$("#dashboardBtn").click(function() {
		if ($('#DashBoard').hasClass('inactiveWindow')) {
			$('.activeWindow').removeClass('activeWindow').addClass('inactiveWindow');
			$('#DashBoard').removeClass('inactiveWindow').addClass('activeWindow');
			$('#navigationBarLeft .active').removeClass('active');
			$(this).addClass('active');
		}
	});

	$("#addClassdBtn").click(function() {
		if ($('#addClass').hasClass('inactiveWindow')) {
			$('.activeWindow').removeClass('activeWindow').addClass('inactiveWindow');
			$('#addClass').removeClass('inactiveWindow').addClass('activeWindow');
			$('#navigationBarLeft .active').removeClass('active');
			$(this).addClass('active');
		}
	});

	$("#seeClassesBtn").click(function() {
		if ($('#seeClasses').hasClass('inactiveWindow')) {
			$('.activeWindow').removeClass('activeWindow').addClass('inactiveWindow');
			$('#seeClasses').removeClass('inactiveWindow').addClass('activeWindow');
			$('#navigationBarLeft .active').removeClass('active');
			$(this).addClass('active');
		}
	});

	$("#addPreReqs").click(function() {
		var row = "<input id='cPreReqs[]' name='cPreReqs[]' type='text' placeholder='Course Name'><BR>";
		$("#cPreReqs").append(row);
	});

	$("#addMajorBtn").click(function() {
		loadDepartments();
		$("#addMajorModal").modal("show");
	});

	$("#addMajorBtnModal").click(function() {
		var D_ID = $('#departmentM').find(":selected").val();
		var name = $('#majorName').val();
		addMajor(D_ID, name);
	});

	$("#addDepartmentbtn").click(function() {
		loadUniversities();
		$("#addMajorModal").modal("hide");
	});

	$("#addDepartmentBtnModal").click(function() {
		var U_ID = $('#universitiesM').find(":selected").val();
		var name = $('#departmentName').val();
		addDepartment(U_ID, name);
	});
	
	$("#addSection").click(function(){
		var rowSectionNumber = '<input id="sectionNumber" name="sectionNumber[]" class="input-medium  input" type="text" placeholder="Section Number" required><BR>';
		var rowSectionProfessor = '<input id="sectionProfessor" name="sectionProfessor[]" class="input-medium  input" type="text" placeholder="Professor" required><BR>';
		$("#sectionNumbersDiv").append(rowSectionNumber);
		$("#sectionprofessorsDiv").append(rowSectionProfessor);
	});
	
	$("#criteriaModalOKBtn").click(function() {
		var valueReceivedItem = receivedItem.attr('value');
		if(typeof(valueReceivedItem) != "undefined" && valueReceivedItem !== null && valueReceivedItem!="") {
			if(valueReceivedItem=='universityYear'){				
				$("#yearPreferred").attr('value',$('input[name="preferedYearModal"]:checked').val());				
				
			}
			else if(valueReceivedItem=='creditsCompleted'){
				$("#preferedCredits").attr('value',$('#numberCreditsModal').val());	
			}
			$("#criteriaModal").modal("hide");
		}
		
	});
	
	

});

function loadUniversities() {
	$('#universitiesM').empty();
	$.ajax({
		url : "AJAX-PHP/loadUniversities.php",
		async : false
	}).done(function(data) {
		console.log(data);
		var obj = jQuery.parseJSON(data);
		$('#universitiesM').append('<option>Select University</option>');
		$.each(obj, function(key, val) {
			$('#universitiesM').append('<option value="' + key + '">' + val + '</option>');
		});

	});
}

function loadDepartments() {
	$('#departmentM').empty();
	$.ajax({
		url : "AJAX-PHP/loadDepartments.php",
		async : false
	}).done(function(data) {
		console.log(data);
		var obj = jQuery.parseJSON(data);
		$('#departmentM').append('<option>Select Department</option>');
		$.each(obj, function(key, val) {
			$('#departmentM').append('<option value="' + key + '">' + val + '</option>');
		});

	});
}

function loadMajors() {
	$('#cMajor').empty();
	$.ajax({
		url : "AJAX-PHP/loadMajors.php",
		async : false
	}).done(function(data) {
		console.log(data);
		var obj = jQuery.parseJSON(data);
		$.each(obj, function(key, val) {
			$('#cMajor').append('<option value="' + key + '">' + val + '</option>');
		});

	});
}

function addMajor(D_ID, name) {

	$.get("AJAX-PHP/addMajor.php", {
		name : name,
		D_ID : D_ID
	}).done(function(data) {
		console.log(data);
		if (data != "success") {
			$("#addMajorModal").modal("hide");
			alertWindow("Error", "There was an error trying to add the new Major. Please contact the Web-Developer.", "alert-error");
		} else {
			loadMajors();
			$("#addMajorModal").modal("hide");
			alertWindow("Success!", "You added new Major to the System.", "alert-success");
		}

	});

}

function addDepartment(U_ID, name) {

	$.get("AJAX-PHP/addDepartment.php", {
		name : name,
		U_ID : U_ID
	}).done(function(data) {
		console.log(data);
		if (data != "success") {
			$("#addDepartmentModal").modal("hide");
			alertWindow("Error", "There was an error trying to add the new Department. Please contact the Web-Developer.", "alert-error");
		} else {
			loadDepartments();
			$("#addDepartmentModal").modal("hide");
			alertWindow("Success!", "You added new Department to the System.", "alert-success");
		}

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
