/**
 * @author Roberto Ronderos Botero
 * @contributor Catalina Laverde Duarte
 */
var receivedItem;//variable used to get received item object in the criteria lists
var counter;
var customQuestionCounter=0;
var selectedCustomCriteria="0";
var selectedPreReq;
$(document).ready(function() {

	/*course pre-reqs auto-complete*/
	$(".prereqsfields").autocomplete({
      source: "AJAX-PHP/loadCourses.php",
      minLength: 2,
      open: function (event,ui) {
        selectedPreReq=false;
        
      },
      select: function( event, ui ) {  
      	$(this).next().next().attr('value',ui.item.id);
      	selectedPreReq=true;
      	
      },
      change: function(event,ui){
      	if(!selectedPreReq){
	      	var val = $(this).val();
	      	$(this).next().next().attr('value',val);
	    }
      }
    });
    
	
	/* drag-list criteria */
	$(".sortable").sortable({
		connectWith : '.sortable',
		placeholder : "ui-state-highlight",
		items : "li:not(.ui-state-disabled)"
	});
	$(".sortable.unordered").on("sortreceive", function(event, ui) {
		receivedItem = $(ui.item);
		receivedItem.find('input').attr('disabled','disabled');
		if(receivedItem.hasClass('customQuestion')){
			customQuestionCounter--;
			$("#numberOfCustomQuestions").attr('value',customQuestionCounter);
			$("#customCriteria .q"+(customQuestionCounter+1)).remove();
			var q = '.q'+(customQuestionCounter+1);
			$(this).find(q).remove();
		}
		
	});

	$(".sortable.ordered").on("sortreceive", function(event, ui) {
		receivedItem = $(ui.item);
		var modalTitle = $("#criteriaModalLabel");
		var modalBodyContent = $("#criteriaModal .modal-body");
		var bodyContent;
		receivedItem.find('input').removeAttr('disabled');
		if (receivedItem.attr('value') == 'universityYear') {
			bodyContent = '<div class="control-group">' + '<label class="control-label" for="year"><b>Please select which year is prefered for this class:</b></label>' + '<br/>' + '<div class="controls">' + '<input type="radio" name="preferedYearModal" value="freshman">' + 'Freshman' + '<br>' + '<input type="radio" name="preferedYearModal" value="sophomore">' + 'Sophomore' + '<br>' + '<input type="radio" name="preferedYearModal" value="junior">' + 'Junior' + '<br>' + '<input type="radio" name="preferedYearModal" value="senior">' + 'Senior' + '<br>' + '</div>' + '<br/>' + '</div>';

			modalTitle.html('University Year Prefered');
			modalBodyContent.html(bodyContent);
			$("#criteriaModal").modal("show");

		} else if (receivedItem.attr('value') == 'creditsCompleted') {
			bodyContent = '<div class="control-group">' + '<label class="control-label" for="numberCreditsModal"><b>Specify the minimum preferred number of credits (inclusive):</b></label>' + '<br/>' + '<div class="controls">' + '<input type="text" id="numberCreditsModal" name="numberCreditsModal" placeholder="Number of credits" value="">' + '</div>' + '<br/>' + '</div>';

			modalTitle.html('Prefered number of credits');
			modalBodyContent.html(bodyContent);
			$("#criteriaModal").modal("show");
		} else if (receivedItem.attr('value') == 'gradesPreReq') {
			bodyContent = '<div class="control-group">' + '<label class="control-label" for="preferedGradeModal"><b>Specify the minimum preferred grade for pre-reqs:</b></label>' + '<br/>' + '<div class="controls">' + '<input type="radio" name="preferedGradeModal" value="D">' + 'D' + '<br>' + '<input type="radio" name="preferedGradeModal" value="C">' + 'C' + '<br>' + '<input type="radio" name="preferedGradeModal" value="B">' + 'B' + '<br>' + '<input type="radio" name="preferedGradeModal" value="A">' + 'A' + '<br>' + '</div>' + '<br/>' + '</div>';

			modalTitle.html('Prefered minimum grade');
			modalBodyContent.html(bodyContent);
			$("#criteriaModal").modal("show");
		} else if (receivedItem.attr('value') == 'gpa') {
			bodyContent = '<div class="control-group">' + '<label class="control-label" for="gpaModal"><b>Specify the minimum preferred G.P.A. (inclusive):</b></label>' + '<br/>' + '<div class="controls">' + '<input type="text" id="gpaModal" name="gpaModal" placeholder="Minimum GPA" value="">' + '</div>' + '<br/>' + '</div>';

			modalTitle.html('Prefered minimum G.P.A.');
			modalBodyContent.html(bodyContent);
			$("#criteriaModal").modal("show");
		} else if (receivedItem.attr('value') == 'major') {

			bodyContent = '<div class="control-group">' + '<label class="control-label" for="cMajorModal"><b>Major:</b></label>' + '<br>' + '<div class="controls">' + '<select id="cMajorModal"></select>' + '</div>' + '</div>';
			modalTitle.html('Prefered Major');
			modalBodyContent.html(bodyContent);
			loadMajors('#cMajorModal');
			$("#criteriaModal").modal("show");
		}

	});
	/* Function inits */

	loadMajors('#cMajor');

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
		var row = '<input type="text" class="input prereqsfields" placeholder="Course Name"/>\
						<input id="cPreReqs[]" name="cPreReqs[]" type="hidden" class="input" value=""/><BR>';
		$("#cPreReqs").append(row);
		$(".prereqsfields").autocomplete({
	      source: "AJAX-PHP/loadCourses.php",
	      minLength: 2,
	      open: function (event,ui) {
		        selectedPreReq=false;
		        
		      },
		      select: function( event, ui ) {  
		      	$(this).next().next().attr('value',ui.item.id);
		      	selectedPreReq=true;
		      	
		      },
		      change: function(event,ui){
		      	if(!selectedPreReq){
			      	var val = $(this).val();
			      	$(this).next().next().attr('value',val);
			    }
		      }
	    });
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

	$("#addSection").click(function() {
		var rowSectionNumber = '<input id="sectionNumber" name="sectionNumber[]" class="input-medium  input" type="text" placeholder="Section Number" required><BR>';
		
		$("#sectionNumbersDiv").append(rowSectionNumber);
	});

	$("#criteriaModalOKBtn").click(function() {
		
		var valueReceivedItem = receivedItem.attr('value');
		if ( typeof (valueReceivedItem) != "undefined" && valueReceivedItem !== null && valueReceivedItem != "") {
			if (valueReceivedItem == 'universityYear') {
				$("#yearPreferred").attr('value', $('input[name="preferedYearModal"]:checked').val());

			} else if (valueReceivedItem == 'creditsCompleted') {
				$("#preferedCredits").attr('value', $('#numberCreditsModal').val());
			} else if (valueReceivedItem == 'gradesPreReq') {
				$("#preferedGradePreReqs").attr('value', $('input[name="preferedGradeModal"]:checked').val());
			} else if (valueReceivedItem == 'gpa') {
				$("#preferedGPA").attr('value', $('#gpaModal').val());
			} else if (valueReceivedItem == 'major') {
				$("#preferedMajor").attr('value', $('#cMajorModal').find(":selected").val());
			}

			$("#criteriaModal").modal("hide");
		}

	});
	
	$("#customCriteriaModalOKBtn").click(function() {
		customQuestionCounter++;
		$("#numberOfCustomQuestions").attr('value',customQuestionCounter);
		/*append question*/
		var questionVal = $("#customHtmlModal .questionToSend input").val();
		$("#customCriteria").append("<input name='customQuestion_"+customQuestionCounter+"' class='q"+customQuestionCounter+"' value='"+questionVal+"'/>");	 
				
		
		/*add to form*/
		switch(selectedCustomCriteria){
			case '1': //input box			
				$("#customCriteria").append("<input name='type[]' class='q"+customQuestionCounter+"' value='ck'/>");
				/*append each of the keywords with its correspondent importance*/
				$("#customHtmlModal .keywordsToSend").each(function(index) {
				  	var keyWord = $(this).find('input').val();
				  	var importance = $(this).find('select option:selected').val();
				  	
				  	$("#customCriteria").append("<input name='ck_"+customQuestionCounter+"[]' class='q"+customQuestionCounter+"' value='"+keyWord+"'/>");
				  	$("#customCriteria").append("<input name='ckimportance_"+customQuestionCounter+"[]' class='q"+customQuestionCounter+"' value='"+importance+"'/>");
				});
				   
				break;
			case '2':
				$("#customCriteria").append("<input name='type[]' class='q"+customQuestionCounter+"' value='cb'/>");
				/*append each of the check boxes with its correspondent importance*/				
				$("#customHtmlModal .checkboxesToSend").each(function(index) {
				  	var checkbox = $(this).find('input').val();
				  	var importance = $(this).find('select option:selected').val();
				  	
				  	$("#customCriteria").append("<input name='cb_"+customQuestionCounter+"[]' class='q"+customQuestionCounter+"' value='"+checkbox+"'/>");
				  	$("#customCriteria").append("<input name='cbimportance_"+customQuestionCounter+"[]' class='q"+customQuestionCounter+"' value='"+importance+"'/>");
				});
			
				break;
			case '3':
				$("#customCriteria").append("<input name='type[]' class='q"+customQuestionCounter+"' value='rb'/>");
				/*append each of the radio buttons with its correspondent importance*/				
				$("#customHtmlModal .radiosToSend").each(function(index) {
				  	var radio = $(this).find('input').val();
				  	var importance = $(this).find('select option:selected').val();
				  	
				  	$("#customCriteria").append("<input name='rb_"+customQuestionCounter+"[]' class='q"+customQuestionCounter+"' value='"+radio+"'/>");
				  	$("#customCriteria").append("<input name='rbimportance_"+customQuestionCounter+"[]' class='q"+customQuestionCounter+"' value='"+importance+"'/>");
				});
				break;
		}
				
		
		
		var questionText = $("#customQuestion").val();
		
		$("#sortable2").append('<li class="ui-state-default customQuestion q'+customQuestionCounter+'" value="'+questionText+'">'+
									questionText+
								'<input type="hidden" name="basicCriteria[]" value="'+questionText+'"/></li>');
		
		/*reseting to default state*/
		$('#typeOfQuestion option[value=0]').attr('selected', 'selected');
		$("#customHtmlModal").empty();
		
		/*close modal*/
		$("#customCriteriaModal").modal("hide");
	});
	
	
	$("#typeOfQuestion").change(function() {
		$("#customHtmlModal").empty();
		counter=1;
		var selected = $("#typeOfQuestion option:selected").val();
		var innerhtml;
		switch(selected){
			case '1':
				selectedCustomCriteria="1";
				innerhtml = '<div class="control-group"> '+
                            	'<label class="control-label" for="customQuestion"><b>Question</b></label><br> '+
                            	'<div class="controls questionToSend"> '+
                            		'<input id="customQuestion" name="customQuestion" class="input-xlarge input" type="text" placeholder="Type your question to the student" required> '+
                            	'</div> '+
                          	'</div> '+
                          	'<div class="control-group"> '+
                            	'<label class="control-label" for="keywordsCustomQuestion"><b>Keywords to look for</b></label><br> '+
                            	'<div id="keywordsCustomQuestion"> '+
                            		'<div class="keywordGroup keywordsToSend">'+
	                            		'<input id="ck'+counter+'" name="customKeyword[]" class="input-xlarge input" type="text" placeholder="Type Keyword" required> '+							
											  '<select id="importanceKeyword" for="ck'+counter+'" >'+
													'<option value="0">Select a importance</option>'+
													'<option value="1">1</option>'+
													'<option value="2">2</option>'+
													'<option value="3">3</option>'+
													'<option value="4">4</option>'+
													'<option value="5">5</option>'+
												'</select>'+
									'</div>'+
                            	'</div>'+            
                          	'</div>'+
                          	'<a id="addKeywordField" role="button" class="btn btn-mini" ><i class="icon-plus"></i> Add Keyword field</a>';
                  $("#customHtmlModal").append(innerhtml);
                  $("#addKeywordField").click(function() { 
                  		counter++;                     
						var htmlToAppend =         		'<div class="keywordGroup keywordsToSend">'+
								                            		'<input id="ck'+counter+'" name="customKeyword[]" class="input-xlarge input" type="text" placeholder="Type Keyword" required> '+							
																		  '<select id="importanceKeyword" for="ck'+counter+'" >'+
																				'<option value="">Select a importance</option>'+
																				'<option value="1">1</option>'+
																				'<option value="2">2</option>'+
																				'<option value="3">3</option>'+
																				'<option value="4">4</option>'+
																				'<option value="5">5</option>'+
																			'</select>'+
																'</div>';
						$("#keywordsCustomQuestion").append(htmlToAppend);                         	
						
					});
				break;
			case '2':
				selectedCustomCriteria="2";
				innerhtml = '<div class="control-group"> '+
                            	'<label class="control-label" for="customQuestion"><b>Question</b></label><br> '+
                            	'<div class="controls questionToSend"> '+
                            		'<input id="customQuestion" name="customQuestion" class="input-xlarge input" type="text" placeholder="Type your question to the student" required> '+
                            	'</div> '+
                          	'</div> '+
                          	'<div class="control-group"> '+
                            	'<label class="control-label" for="checkboxCustomQuestion"><b>Checkboxes to display</b></label><br> '+
                            	'<div id="checkboxCustomQuestion"> '+
                            		'<div class="checkboxGroup checkboxesToSend">'+
	                            		'<input id="cb'+counter+'" name="customCheckboxes[]" class="input-xlarge input" type="text" placeholder="Type checkbox text" required> '+
	                            		'<select id="importanceCheckbox" for="cb'+counter+'" >'+
																				'<option value="">Select a importance</option>'+
																				'<option value="1">1</option>'+
																				'<option value="2">2</option>'+
																				'<option value="3">3</option>'+
																				'<option value="4">4</option>'+
																				'<option value="5">5</option>'+
																			'</select>'+							
									'</div>'+
                            	'</div>'+            
                          	'</div>'+
                          	'<a id="addKeywordField" role="button" class="btn btn-mini" ><i class="icon-plus"></i> Add checkbox field</a>';
                  $("#customHtmlModal").append(innerhtml);
                  $("#addKeywordField").click( function() {
						counter++;                     
						var htmlToAppend = '<div class="checkboxGroup checkboxesToSend">'+
								                            		'<input id="cb'+counter+'" name="customKeyword[]" class="input-xlarge input" type="text" placeholder="Type checkbox text" required> '+							
																		  '<select id="importanceCheckbox" for="cb'+counter+'" >'+
																				'<option value="">Select a importance</option>'+
																				'<option value="1">1</option>'+
																				'<option value="2">2</option>'+
																				'<option value="3">3</option>'+
																				'<option value="4">4</option>'+
																				'<option value="5">5</option>'+
																			'</select>'+
																'</div>';
						$("#checkboxCustomQuestion").append(htmlToAppend);
						});
				break;
			default:
				selectedCustomCriteria="3";
				innerhtml = '<div class="control-group"> '+
                            	'<label class="control-label" for="customQuestion"><b>Question</b></label><br> '+
                            	'<div class="controls questionToSend"> '+
                            		'<input id="customQuestion" name="customQuestion" class="input-xlarge input" type="text" placeholder="Type your question to the student" required> '+
                            	'</div> '+
                          	'</div> '+
                          	'<div class="control-group"> '+
                            	'<label class="control-label" for="checkboxCustomQuestion"><b>Radio Button text to display</b></label><br> '+
                            	'<div id="radioCustomQuestion"> '+
                            		'<div class="radioGroup radiosToSend">'+
	                            		'<input id="rb'+counter+'" name="customRadios[]" class="input-xlarge input" type="text" placeholder="Type radio button text" required> '+
	                            		'<select id="importanceRadio" for="rb'+counter+'" >'+
																				'<option value="">Select a importance</option>'+
																				'<option value="1">1</option>'+
																				'<option value="2">2</option>'+
																				'<option value="3">3</option>'+
																				'<option value="4">4</option>'+
																				'<option value="5">5</option>'+
																			'</select>'+							
									'</div>'+
                            	'</div>'+            
                          	'</div>'+
                          	'<a id="addRadioField" role="button" class="btn btn-mini" ><i class="icon-plus"></i> Add Radio field</a>';
                  $("#customHtmlModal").append(innerhtml);
                  $("#addRadioField").click( function() {
						counter++;                     
						var htmlToAppend = '<div class="radioGroup radiosToSend">'+
								                            		'<input id="rb'+counter+'" name="customRadios[]" class="input-xlarge input" type="text" placeholder="Type radio button text" required> '+							
																		  '<select id="importanceCheckbox" for="rb'+counter+'" >'+
																				'<option value="">Select a importance</option>'+
																				'<option value="1">1</option>'+
																				'<option value="2">2</option>'+
																				'<option value="3">3</option>'+
																				'<option value="4">4</option>'+
																				'<option value="5">5</option>'+
																			'</select>'+
																'</div>';
						$("#radioCustomQuestion").append(htmlToAppend);
						  
					});
				break;
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

function loadMajors(selector) {
	$(selector).empty();
	$(selector).append('<option >Select A Major</option>');
	$.ajax({
		url : "AJAX-PHP/loadMajors.php",
		async : false
	}).done(function(data) {
		
		var obj = jQuery.parseJSON(data);
		$.each(obj, function(key, val) {
			$(selector).append('<option value="' + key + '">' + val + '</option>');

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
