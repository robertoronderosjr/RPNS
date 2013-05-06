$(document).ready(function() {
	
	/*Button Handlers*/
	$(".requestSPN").click(function(){
		console.log("CLICKED!");
		var formID= $(this).attr('formid');
		console.log("form id: "+formID);
		var CS_ID = $("#"+formID).find('#sections').val();
		console.log("cs id: "+CS_ID);
		$("#"+formID).find('#csid').val(CS_ID);	
		$("#"+formID).submit();	
	});
	
	


});