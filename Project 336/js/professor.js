/**
 * @author Roberto Ronderos Botero
 * @contributor Catalina Laverde Duarte
 */
var receivedItem;//variable used to get received item object in the criteria lists
var counter;
var customQuestionCounter=0;
var selectedCustomCriteria="0";
var selectedPreReq;
var selectedClass;
var editing;
var courseSectionEditing=false;
var coid;
var assignBTN;
var denyBTN;
var aOrD;
$(document).ready(function() {
	
	courseSectionEditing=false;
	console.log(courseSectionEditing);
	/*read url parameter to see if we need to edit a class*/	
	if ( typeof (classEdit) != "undefined" && classEdit !== null && classEdit != "") {
			
			editing=true;
			var courseID=classEdit;
			
			editableClass(courseID);
			
	}
	
	/*alert class added*/
	if ( typeof (courseAdded) != "undefined" && courseAdded !== null && courseAdded != "") {
			if (courseAdded) {
				alertWindow("Success!", "You added a new course to the System.", "alert-success");
			}
		}
	/*alert class edited*/
	if ( typeof (courseEdited) != "undefined" && courseEdited !== null && courseEdited != "") {
			if (courseEdited) {
				alertWindow("Success!", "You correctly edited a class.", "alert-success");
			}
		}
		
	/*alert spns added*/
	if ( typeof (spnsAdded) != "undefined" && spnsAdded !== null && spnsAdded != "") {
			if (spnsAdded) {
				alertWindow("Success!", "You correctly edited special permission numbers to a section.", "alert-success");
			}
		}
		
		
		
		
	/*date picker for assign modal*/
	
	$('#datePicker').datetimepicker({
      pickTime: false
    });
    
    $("#expDate").click(function(){
		$('#datePicker').datetimepicker('show');
	});


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
    
    $("#lookupClasses").click(function() {		
		var semester = $("#semestersP option:selected").val();
		console.log(semester);
		if(semester!=""){
			$('#classesResults').load('AJAX-PHP/seeClassesProfessor.php?Semester='+semester+'');
		}
		else{
			alertWindow("Error!", "Please select a value from all drop down boxes.", "alert-error");
		}
	});	
    
    $("#cName").change(function(){
    	var val = $(this).val();
		$(this).next().next().attr('value',val);	
    })
    
    $("#cName").autocomplete({
      source: "AJAX-PHP/loadCourses.php",
      minLength: 2,     
      open: function (event,ui) {
        selectedClass=false;
        
      },
      select: function( event, ui ) {  
      	$(this).next().next().attr('value',ui.item.id);
      	selectedClass=true;
      },
      change: function(event,ui){
		   if(!selectedClass){
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
		courseSectionEditing=false
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
		
		if(!editing){
			if ($('#addClass').hasClass('inactiveWindow')) {
				$('.activeWindow').removeClass('activeWindow').addClass('inactiveWindow');
				$('#addClass').removeClass('inactiveWindow').addClass('activeWindow');
				$('#navigationBarLeft .active').removeClass('active');
				$(this).addClass('active');
			}
		}
		else{
			$('.activeWindow').removeClass('activeWindow').addClass('inactiveWindow');
			$('#addClass').removeClass('inactiveWindow').addClass('activeWindow');
			$('#addClass').children('h2').eq(0).html('Add a class');
			$('#navigationBarLeft .active').removeClass('active');
			$('#addClassdBtn').addClass('active');
			//resetForm
			resetForm();
			editing=false;
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
	
	$("#clear").click(function(){
		resetForm();		
	})

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
	    });//make all jquery ids have click function
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

	$("#accordionPracticeCats").delegate("#spnsbtn", "click", function() {

		console.log( $(this).attr('coid'));
		loadSections($(this).attr('coid'));
	});
	$("#addDepartmentBtnModal").click(function() {
		var U_ID = $('#universitiesM').find(":selected").val();
		var name = $('#departmentName').val();
		addDepartment(U_ID, name);
	});

	$("#addSection").click(function() {
		console.log("COURSE SECTION EDITING:"+courseSectionEditing);
		if(!courseSectionEditing){
		
		var rowSectionNumber = '<input id="sectionNumber" name="sectionNumber[]" class="input-medium  input" type="text" placeholder="Section Number" required><BR>';
		
		$("#sectionNumbersDiv").append(rowSectionNumber);
		}
		else{
			var modalTitle = $("#criteriaModalLabel");
			var modalBodyContent = $("#criteriaModal .modal-body");
			var bodyContent;
					
			bodyContent = '<div class="control-group">' + 
			'<label class="control-label" for="sectionNM"><b>Section Number:</b></label>' +
			 '<br/>' + 
			 '<div class="controls">' + 
			 '<input type="text" id="sectionNM" name="sectionNM" class="sectionNM" placeholder="Section #">' +
			 '</div>';
			
			
			modalTitle.html('Add a Section');
			modalBodyContent.html(bodyContent);
			$("#criteriaModalOKBtn").attr('sectionNM','none');
			
			$("#sectionNM").change(function(){				
				$("#criteriaModalOKBtn").attr('sectionNM',$(this).val());
			})
			$("#criteriaModal").modal("show");
		}
	});

	$("#criteriaModalOKBtn").click(function() {
		console.log(courseSectionEditing);
		if(!courseSectionEditing){
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
		}
		else{
			var Section_Number= $(this).attr('sectionNM');
			var CO_ID = $("#COID").attr('value');
			var C_ID = $("#CID").attr('value');
			console.log(Section_Number,CO_ID,C_ID);
			addSection(Section_Number,CO_ID,C_ID);
			courseSectionEditing=false;
			console.log(courseSectionEditing);
		}

	});
	
	$("#assignDenyModalBtn").click(function(){
		var profComments = $("#profComments").val();
		var prid = $("#assignDenyModal").data('prid');	
		if(aOrD=='assign'){					
			var expDate = $("#expDate").val();
			assignPermission(profComments,prid,expDate);
			$("#assignDenyModal").modal('hide');
		}
		else{
			denyPermission(profComments,prid);
			$("#assignDenyModal").modal('hide');
		}
	})
	
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
	
	$('#seeClasses').on('click', ".spnsbtn", function(){			
		var coid= $(this).attr('coid');
		loadSections(coid);
	});
	
	$('#seeClasses').on('click', ".seespnsbtn", function(){			
		var coid= $(this).attr('coid');
		loadSections(coid);
	});
	
	$('#seeClasses').on('click', "#seeEditClass", function(){	
		var cid = $(this).attr('classID');
		var coid= $(this).attr('coid');
		window.location.href = "http://cs336-31.rutgers.edu/index.php?classEdit="+cid+"&coid="+coid;
	});
	
	$('#seeClasses').on('click', ".showCancelled", function(){	
		console.log('show cancelled');
		$(this).next().next().next().next().toggle();
	});
	
	$('#seeClasses').on('click', ".showAssigned", function(){	
		console.log('show assigned');
		$(this).next().next().next().next().next().toggle();
	});
	
	
	$('#addClass').on('click', "#goBackBtn", function(){			
		$('#seeClassesBtn').click();		
	});
	
	$('#seeClasses').on('click', ".assignPermission", function(){			
		var prid= $(this).attr('prid');
		$("#assignDenyModal").data('prid',prid);
		$("#datePickerLabel").css("display",'block');
		$("#assignDenyModal #datePicker").css('display','block');
		aOrD='assign';
		$("#assignDenyModal").modal("show");
		assignBTN = $(this);
	});
	
	$('#seeClasses').on('click', ".denyPermission", function(){	
		var prid= $(this).attr('prid');
		$("#assignDenyModal").data('prid',prid);
		$("#datePickerLabel").toggle();
		$("#assignDenyModal #datePicker").toggle();
		aOrD='deny';
		$("#assignDenyModal").modal("show");
		denyBTN = $(this);	
	});
	
	$('#seeClasses').on('click', ".seeDetails", function(){	
		var prid= $(this).attr('prid');
		loadRequestDetails(prid);
		$("#seeDetailsRequestedModal").modal("show");		
	});
	
	$("#seedsection").change(function(){
		listSPNS($(this).val());		
	})
	
	
});

function listSPNS(csid){
	$.get("../AJAX-PHP/listsSNPNS.php", {
		CS_ID : csid,		
		async:false
	}).done(function(data) {
		console.log(data);		
		$("#listspns").empty();
		var spns = jQuery.parseJSON(data);
		$("#listspns").append('<ul>');
		for(i=0;i<spns.length;i++){
			$('#listspns').append('<li>' + spns[i].pnum + '</option>');
		}
		$("#listspns").append('</ul>');	
	});
}

function assignPermission(profComments,prid,expDate){
	$.get("AJAX-PHP/assignPermissionNumber.php", {
		PR_ID : prid,
		comments: profComments,
		expiration: expDate,
		async:false
	}).done(function(data) {
		console.log(data);
		if(data=="success"){
			alertWindow("Success!", "Permission Number has been assigned.", "alert-success");
			assignBTN.attr('disabled','disabled');			
			window.setTimeout(function() {
				location.reload();	
			}, 4000);						
		}
		else{
			alertWindow("Error!", data, "alert-error");
		}
	});
}

function denyPermission(profComments,prid){
	$.get("AJAX-PHP/denyPermissionNumber.php", {
		PR_ID : prid,
		comments: profComments,
		async:false
	}).done(function(data) {
		console.log(data);
		if(data=="success"){
			alertWindow("Success!", "Request has been denied.", "alert-success");
			denyBTN.attr('disabled','disabled');			
			window.setTimeout(function() {
				location.reload();	
			}, 4000);
					
		}
		else{
			alertWindow("Error!", data, "alert-error");
		}
	});
}

function loadRequestDetails(prid){
	$.get("AJAX-PHP/getRequestDetails.php", {
		PR_ID : prid,
		async:false
	}).done(function(data) {
		console.log("data:"+data);		
		var response = jQuery.parseJSON(data);
		var numberOfCustomQuestions=1;	
		$("#seeDetailsRequestedModal .modal-body").empty();
		$.each(response, function(key, value) { 
			console.log("response key: "+key+" response value: "+value);
			switch(key){
			   			case 'universityYear':
			   				$("#seeDetailsRequestedModal .modal-body").append('<label class="control-label" for="universityYear"><b>Current University Year</b></label>\
																				<div class="controls">\
																					<span id="universityYear" type="text" >'+value+'</span>\
																				</div><br/>');			   				
			   				break;
			   			case 'creditsCompleted':
			   				$("#seeDetailsRequestedModal .modal-body").append('<label class="control-label" for="creditsCompleted"><b>Credits Completed</b></label>\
																				<div class="controls">\
																					<span id="creditsCompleted" type="text" >'+value+'</span>\
																				</div><br/>');	
			   				break;
			   			case 'gradesPreReq':
			   				$("#seeDetailsRequestedModal .modal-body").append('<label class="control-label" for="gradesPreReq"><b>Credits Completed</b></label>\
																				<div class="controls">\
																					<span id="gradesPreReq" type="text" >'+value+'</span>\
																				</div><br/>');	
			   				break;
			   			case 'major':
			   				$("#seeDetailsRequestedModal .modal-body").append('<label class="control-label" for="major"><b>Major</b></label>\
																				<div class="controls">\
																					<span id="major" type="text" >'+value+'</span>\
																				</div><br/>');
			   				break;
			   			case 'gpa':
			   				$("#seeDetailsRequestedModal .modal-body").append('<label class="control-label" for="gpa"><b>G.P.A.</b></label>\
																				<div class="controls">\
																					<span id="gpa" type="text" >'+value+'</span>\
																				</div><br/>');
			   				
			   				break;
			   			case 'ck'+numberOfCustomQuestions:
			   				$("#seeDetailsRequestedModal .modal-body").append('<label class="control-label" for="ck'+numberOfCustomQuestions+'"><b>Custom Question # '+numberOfCustomQuestions+'</b></label>\
																				<div class="controls">\
																					<span id="ck'+numberOfCustomQuestions+'" type="text" >'+value+'</span>\
																				</div><br/>');
			   				numberOfCustomQuestions++;
			   				break;
			   			case 'cb'+numberOfCustomQuestions:
			   				
			   				$("#seeDetailsRequestedModal .modal-body").append('<label class="control-label" for="cb'+numberOfCustomQuestions+'"><b>Custom Question # '+numberOfCustomQuestions+'</b></label>\
																				<div class="controls">\
																					<span id="cb'+numberOfCustomQuestions+'" type="text">'+value[0]+'</span><br/>');
																					$("#seeDetailsRequestedModal .modal-body").append('<ul>');
																					for(i=1;i<value.length;i++){																						
																					$("#seeDetailsRequestedModal .modal-body").append('<li>'+value[i]+'</li>');
																					}
																					$("#seeDetailsRequestedModal .modal-body").append('</ul></div><br/>');
																				
			   				numberOfCustomQuestions++;
			   				break;
			   			case 'rb'+numberOfCustomQuestions:
			   				$("#seeDetailsRequestedModal .modal-body").append('<label class="control-label" for="rb'+numberOfCustomQuestions+'"><b>Custom Question # '+numberOfCustomQuestions+'</b></label>\
																				<div class="controls">\
																					<span id="rb'+numberOfCustomQuestions+'" type="text" >'+value+'</span>\
																				</div><br/>');	  
							numberOfCustomQuestions++;													 				
			   				break;
			   			
			   		}
		}); 
			   
			   		
			   	
			
	});
}
function editableClass(C_ID,CO_ID){
	$.get("AJAX-PHP/classEditable.php", {
					C_ID : C_ID,
					async:false
				}).done(function(data) {
					console.log("data:"+data);
					if (data != "editable") {			
						$("#addClass").data("editable", 'false');
						
					} else {
						$("#addClass").data("editable", 'true');			
						
					}
					fillClassForm(C_ID,coid);
					if ($('#addClass').hasClass('inactiveWindow')) {
						$('.activeWindow').removeClass('activeWindow').addClass('inactiveWindow');
						$('#addClass').removeClass('inactiveWindow').addClass('activeWindow');						
						$('#navigationBarLeft .active').removeClass('active');
						$('#seeClassesBtn').addClass('active');
						$("#addClass #submitBtn").html('Edit Class');
						$("#addClass #submitBtn").after('<button class="btn btn-danger pull-right" id="deleteClass" cid="'+C_ID+'" >Delete Class</button>');
						$("#deleteClass").click(function(){
							event.preventDefault();
							var cid = $(this).attr('cid');
							deleteClass(cid);
						})						
						$("#addClass").find('form').attr("action","AJAX-PHP/editClass.php");
						$("#addClass").find('form').append("<input name='CID'  id='CID' type='hidden' value='"+C_ID+"'/>");
						$("#addClass").find('form').append("<input name='COID' id='COID' type='hidden' value='"+coid+"'/>");
						$("#clear").remove();
					}			
			
				});
}

function deleteClass(cid){
	$.get("AJAX-PHP/deleteClass.php", {
		C_ID : cid,		
		async:false
	}).done(function(data) {
		console.log(data);
		if(data=="success"){
			alertWindow("Success!", "Class has been deleted.", "alert-success");				
			window.setTimeout(function() {
				window.location.href = "index.php";
			}, 2000);
					
		}
		else{
			alertWindow("Error!", data, "alert-error");
		}
	});
}

function resetForm(){
	$("#addClass").empty();
			$('#addClass').load('AJAX-PHP/addClassProfessor.html',function(){
				$(this).prepend('<h2>Add a class</h2>')
			loadMajors('#cMajor');
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
			    $("#addSection").click(function() {
					var rowSectionNumber = '<input id="sectionNumber" name="sectionNumber[]" class="input-medium  input" type="text" placeholder="Section Number" required><BR>';
					
					$("#sectionNumbersDiv").append(rowSectionNumber);
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
		});
}

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
function loadSections(COID) {	
	$('#dsection,#seedsection').empty();		
	$.get("../AJAX-PHP/loadSections.php", {
	CO_ID : COID
	}).done(function(data) {
		console.log(data);
		var sections=jQuery.parseJSON(data);
		$('#dsection,#seedsection').append('<option>Select Section</option>');
		for(i=0;i<sections.length;i++){
			$('#dsection,#seedsection').append('<option value="' + sections[i].CS_ID + '">' + sections[i].Section_number + '</option>');
		}
		

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
			loadMajors('#cMajor');
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

function addSection(Section_Number,CO_ID,C_ID){
	$.get("AJAX-PHP/addSection.php", {
		Section_Number : Section_Number,
		CO_ID : CO_ID,
		C_ID:C_ID
	}).done(function(data) {
		console.log(data);
		if (data != "success") {			
			alertWindow("Error", "There was an error trying to add the new section. Please contact the Web-Developer.", "alert-error");
		} else {
			
			alertWindow("Success!", "You added new section to the System.", "alert-success");
			 location.reload();
		}

	});
}

function removeSection(CS_ID){
	$.get("AJAX-PHP/removeSection.php", {
		CS_ID : CS_ID
	}).done(function(data) {
		console.log(data);
		if (data != "success") {			
			alertWindow("Error", "There was an error trying to remove this section. Please contact the Web-Developer.", "alert-error");
		} else {			
			alertWindow("Success!", "You added new section to the System.", "alert-success");
			location.reload();
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

function fillClassForm(C_ID,CO_ID){
	$.ajax({		  
		  url: "AJAX-PHP/getCourse.php",
		  data: { C_ID: C_ID , CO_ID:CO_ID }	  
		}).done(function( data ) {	
			   console.log(data);			   
			   	
			   console.log("EDITABLE:"+$("#addClass").data("editable"));
			   
			   if($("#addClass").data("editable")=='false'){
					$("#addClass").prepend('<div class="alert alert-info" style="position: relative;top: -10px;"> '+
											 ' <a class="close" data-dismiss="alert">×</a>  '+
											 ' Students on waiting list. You can only add or remove section. Everything else is locked.<br/> '+
											 ' WARNING: if you remove a section, all SPN from students requesting that section will be dropped.<br/> '+
											'</div> '+
											'<button id="goBackBtn" class="btn btn-success pull-right">Go Back</button>');
					$('#addClass').children('h2').eq(0).html('See/Edit Class (Not editable)');	
					$("#addClass #submitBtn").remove();
				}
				else{
					$("#addClass").prepend('<button id="goBackBtn" class="btn btn-success pull-right">Go Back</button>');
					$('#addClass').children('h2').eq(0).html('See/Edit Class');
				}	
			   var course = jQuery.parseJSON(data);
			   $("#cName").val(course.Name);	
			   $("#cMaxStudents").val(course.MAX_SIZE);			   
			   $('#cMajor option[value='+course.Major+']').prop('selected', true);
			   if($("#addClass").data("editable")=='false'){
								$("#cName").attr('disabled','disabled');
								$("#cMaxStudents").attr('disabled','disabled');
								$('#cMajor').attr('disabled','disabled');
								$("#addMajorBtn").remove();
								$("#addPreReqs").remove();
							}
			   for(i=0;i<course.Requirements.length;i++){				   		
						if(i==0){
							$("#cPreReqs").children('input').eq(0).val(course.Requirements[i].Name);
							$("#cPreReqs").children('input').eq(1).val(course.Requirements[i].C_ID);
						}
						else{
							var row = '<input type="text" class="input prereqsfields" placeholder="Course Name" value="'+course.Requirements[i].Name+'"/>\
											<a id="removeField" role="button" class="btn btn-mini removeField"><i class="icon-remove-sign"></i></a>\
											<input id="cPreReqs[]" name="cPreReqs[]" type="hidden" class="input" value="'+course.Requirements[i].C_ID+'"/><BR>';
							
							
							$("#cPreReqs").append(row);
							$(".removeField").click(function() {
								console.log("removing");
								$(this).prev('input').remove();
								$(this).remove();
							});
						}			   		
			   }
			    if($("#addClass").data("editable")=='false'){
			   		     	$(".prereqsfields").attr('disabled','disabled')
			   		     }
			   
			   if($("#addClass").data("editable")=='false'){
								$(".removeField").remove();
							}
			   
			   $("#semester option[value='"+course.Semesters[0]+"']").prop('selected', true);
			   
			   if($("#addClass").data("editable")=='false'){
			   		   $("#semester").attr('disabled','disabled');
			   	}
			   courseSectionEditing=true;
			   for(i=0;i<course.Sections.length;i++){
			   		if(i==0){
			   			$("#sectionNumbersDiv").children('input').eq(0).val(course.Sections[i].Section_Number);
			   			 if($("#addClass").data("editable")=='false'){
			   		     	$("#sectionNumbersDiv").children('input').eq(0).attr('disabled','disabled')
			   		     }
			   			$("#sectionNumbersDiv").children('input').eq(0).after(' <a id="removeField2" role="button" class="btn btn-mini removeField2"><i class="icon-remove-sign"></i></a>');
			   			$("#sectionNumbersDiv").append('<input name="CSID[]" type="hidden" value="'+course.Sections[i].CS_ID+'" />');
			   		}
			   		else{
			   			$("#sectionNumbersDiv").append('<input id="sectionNumber" name="sectionNumber[]" \
			   			class="input-medium  input sectionsInut" type="text" placeholder="Section Number" value="'+course.Sections[i].Section_Number+'">\
			   			<a id="removeField2" role="button" class="btn btn-mini removeField2"><i class="icon-remove-sign"></i></a>\
			   			<br>');
			   			$(".removeField2").click(function() {
								console.log("removing");
								var CSID= $(this).next().next().val();								
								removeSection(CSID);
							});
			   			$("#sectionNumbersDiv").append('<input name="CSID[]" type="hidden" value="'+course.Sections[i].CS_ID+'" />');
			   		}
			   }	
			   if($("#addClass").data("editable")=='false'){
			   		     	$(".sectionsInut").attr('disabled','disabled')
			   		     }		   
			   var numberOfCustomQuestions=0;			   
			   for(i=0;i<course.Used_Criteria.length;i++){
			   		switch(course.Used_Criteria[i].Type){
			   			case 'requestedDate':
			   				$("#sortable1 li[value=requestedDate]").clone().appendTo('#sortable2');
			   				$("#sortable1 li[value=requestedDate]").remove();
			   				$("#sortable2 li[value=requestedDate]").find('input').removeAttr('disabled');
			   				$("#sortable2 li[value=requestedDate]").append("<input type='hidden' name='PCRIDS[]' value='"+course.Used_Criteria[i].PCRID+"' />");
			   				break;
			   			case 'universityYear':
			   				$("#sortable1 li[value=universityYear]").clone().appendTo('#sortable2');
			   				$("#sortable1 li[value=universityYear]").remove();
			   				$("#sortable2 li[value=universityYear]").find('input').removeAttr('disabled');
			   				$("#sortable2 li[value=universityYear]").append("<input type='hidden' name='PCRIDS[]' value='"+course.Used_Criteria[i].PCRID+"' />");
			   				$("#yearPreferred").val(course.Used_Criteria[i].Values);
			   				break;
			   			case 'creditsCompleted':
			   				$("#sortable1 li[value=creditsCompleted]").clone().appendTo('#sortable2');
			   				$("#sortable1 li[value=creditsCompleted]").remove();
			   				$("#sortable2 li[value=creditsCompleted]").find('input').removeAttr('disabled');
			   				$("#sortable2 li[value=creditsCompleted]").append("<input type='hidden' name='PCRIDS[]' value='"+course.Used_Criteria[i].PCRID+"' />");
			   				$("#preferedCredits").val(course.Used_Criteria[i].Values);
			   				break;
			   			case 'gradesPreReq':
			   				$("#sortable1 li[value=gradesPreReq]").clone().appendTo('#sortable2');
			   				$("#sortable1 li[value=gradesPreReq]").remove();
			   				$("#sortable2 li[value=gradesPreReq]").find('input').removeAttr('disabled');
			   				$("#sortable2 li[value=gradesPreReq]").append("<input type='hidden' name='PCRIDS[]' value='"+course.Used_Criteria[i].PCRID+"' />");
			   				$("#preferedGradePreReqs").val(course.Used_Criteria[i].Values);
			   				break;
			   			case 'major':
			   				$("#sortable1 li[value=major]").clone().appendTo('#sortable2');
			   				$("#sortable1 li[value=major]").remove();
			   				$("#sortable2 li[value=major]").find('input').removeAttr('disabled');
			   				$("#sortable2 li[value=major]").append("<input type='hidden' name='PCRIDS[]' value='"+course.Used_Criteria[i].PCRID+"' />");
			   				$("#preferedMajor").val(course.Used_Criteria[i].Values);
			   				break;
			   			case 'gpa':
			   				$("#sortable1 li[value=gpa]").clone().appendTo('#sortable2');
			   				$("#sortable1 li[value=gpa]").remove();
			   				$("#sortable2 li[value=gpa]").find('input').removeAttr('disabled');
			   				$("#sortable2 li[value=gpa]").append("<input type='hidden' name='PCRIDS[]' value='"+course.Used_Criteria[i].PCRID+"' />");
			   				$("#preferedGPA").val(course.Used_Criteria[i].Values);
			   				break;
			   			case 'ck':
			   				numberOfCustomQuestions++;
			   				
			   				var ckQuestion = jQuery.parseJSON(course.Used_Criteria[i].Values);
			   				
			   				$('#sortable2').append('<li class="ui-state-default" value="'+ckQuestion[0]+'">'+ckQuestion[0]+'<input type="hidden" name="basicCriteria[]" value="'+ckQuestion[0]+'">'+
			   										'<input type="hidden" name="PCRIDS[]" value="'+course.Used_Criteria[i].PCRID+'" />'+	
			   										'</li>')
			   				$("#numberOfCustomQuestions").attr('value',numberOfCustomQuestions);
			   				$("#customCriteria").append('<input name="customQuestion_'+numberOfCustomQuestions+'" value="'+ckQuestion[0]+'">');
			   				$("#customCriteria").append('<input name="type[]" value="ck">');
			   				for(j=1;j<ckQuestion.length;j++){
			   					$("#customCriteria").append('<input name="ck_'+numberOfCustomQuestions+'[]" value="'+ckQuestion[j].keyword+'">');
			   					$("#customCriteria").append('<input name="ckimportance_'+numberOfCustomQuestions+'[]" value="'+ckQuestion[j].importance+'">');
			   				}
			   				
			   				break;
			   			case 'cb':
			   				numberOfCustomQuestions++;
			   				
			   				var cbQuestion = jQuery.parseJSON(course.Used_Criteria[i].Values);
			   				
			   				$('#sortable2').append('<li class="ui-state-default" value="'+cbQuestion[0]+'">'+cbQuestion[0]+'<input type="hidden" name="basicCriteria[]" value="'+cbQuestion[0]+'">'+
			   										'<input type="hidden" name="PCRIDS[]" value="'+course.Used_Criteria[i].PCRID+'" />'+	
			   										'</li>');
			   				$("#numberOfCustomQuestions").attr('value',numberOfCustomQuestions);
			   				$("#customCriteria").append('<input name="customQuestion_'+numberOfCustomQuestions+'" value="'+cbQuestion[0]+'">');
			   				$("#customCriteria").append('<input name="type[]" value="cb">');
			   				for(j=1;j<cbQuestion.length;j++){
			   					$("#customCriteria").append('<input name="cb_'+numberOfCustomQuestions+'[]" value="'+cbQuestion[j].checkbox+'">');
			   					$("#customCriteria").append('<input name="cbimportance_'+numberOfCustomQuestions+'[]" value="'+cbQuestion[j].importance+'">');
			   				}
			   				
			   				break;
			   			case 'rb':
			   				numberOfCustomQuestions++;
			   				
			   				var rbQuestion = jQuery.parseJSON(course.Used_Criteria[i].Values);
			   				
			   				$('#sortable2').append('<li class="ui-state-default" value="'+rbQuestion[0]+'">'+rbQuestion[0]+'<input type="hidden" name="basicCriteria[]" value="'+rbQuestion[0]+'">'+
			   										'<input type="hidden" name="PCRIDS[]" value="'+course.Used_Criteria[i].PCRID+'" />'+	
			   										'</li>')
			   				
			   				$("#numberOfCustomQuestions").attr('value',numberOfCustomQuestions);
			   				$("#customCriteria").append('<input name="customQuestion_'+numberOfCustomQuestions+'" value="'+rbQuestion[0]+'">');
			   				$("#customCriteria").append('<input name="type[]" value="rb">');
			   				for(j=1;j<rbQuestion.length;j++){
			   					$("#customCriteria").append('<input name="rb_'+numberOfCustomQuestions+'[]" value="'+rbQuestion[j].radio+'">');
			   					$("#customCriteria").append('<input name="rbimportance_'+numberOfCustomQuestions+'[]" value="'+rbQuestion[j].importance+'">');
			   				}
			   				
			   				break;
			   			
			   		}
			   }
			   
		});	
	
}
