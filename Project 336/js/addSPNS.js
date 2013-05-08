var filename;
var done=false;
var manual=true;
$(document).ready(function() {
	
	
	
	$("#addSPN").click(function(){
		var rowSectionNumber = '<input name="spnsp[]" class="input-large" type="text" placeholder="Special Permission Number"></br>';
		$(this).prev().before(rowSectionNumber);
	})
	
	$("#spnsmodalbtn").click(function(){
		if(manual){
			$("#manualForm").submit();
		}
		else{
			var CSID=$("#csidHiddenModal").val();
			if(done){
				addPermission(CSID,filename);
			}
			done=false;
		}
	})
	
	$("#dsection").change(function(){
		$("#csidHiddenModal").val($(this).val());
	})
	
	var errorHandler = function(event, id, fileName, reason, xhr) {
        qq.log("id: " + id + ", fileName: " + fileName + ", reason: " + reason);
    };
	
   $('#jquery-wrapped-fine-uploader').fineUploader({        
        request: {
            endpoint: "../AJAX-PHP/upload.php",
            paramsInBody: true
        },
        chunking: {
            enabled: true
        },
        resume: {
            enabled: true
        },
        retry: {
            enableAuto: true,
            showButton: true
        },        
        display: {
            fileSizeOnSubmit: true
        },
    })
        .on('error', errorHandler)
        .on('uploadChunk resume', function(event, id, fileName, chunkData) {
            filename = fileName;
            qq.log('on' + event.type + ' -  ID: ' + id + ", FILENAME: " + fileName + ", PARTINDEX: " + chunkData.partIndex + ", STARTBYTE: " + chunkData.startByte + ", ENDBYTE: " + chunkData.endByte + ", PARTCOUNT: " + chunkData.totalParts);
        })
        .on("upload", function(event, id, filename) {
            $(this).fineUploader('setParams', {"hey": "ho"}, id);
            done=true;
            console.log("UPLOAD SUCESS: "+filename);
        });
	
	
	$("#spnsradiobtns").delegate("#spnsradio", "click", function() {
		
		console.log($(this).val());
		if($(this).val()==0){
			$('#fileModal').css('display','none');
			$("#manualModal").css('display','block');
			manual=true;			
		}else if($(this).val()==1){
			$("#manualModal").css('display','none');
			$('#fileModal').css('display','block');
			manual=false;
		}
	});
	
	$('#file').change(function(){
        ('#selected').text(this.value || 'Nothing selected')
    });
		
});

function addPermission(CSID,filename){
	$.get("../AJAX-PHP/addPermissionsFile.php", {
		CS_ID : CSID,
		filename:filename
	}).done(function(data) {
		console.log(data);
		if (data == "success") {
			$("#addSPNSModal").modal('hide');
			alertWindow("Success!", "You correctly added special permission numbers to a section from a file.", "alert-success");
		} else {
			alertWindow("Error!", data, "alert-error");
		}

	});
}
	

