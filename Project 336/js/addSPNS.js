var filename;
$(document).ready(function() {
	
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
            
            console.log("UPLOAD SUCESS: "+filename);
        });
	
	
	$("#spnsradiobtns").delegate("#spnsradio", "click", function() {
		
		console.log($(this).val());
		if($(this).val()==0){
			$('#fileModal').css('display','none');
			$("#manualModal").css('display','block');			
		}else if($(this).val()==1){
			$("#manualModal").css('display','none');
			$('#fileModal').css('display','block');
		}
	});
	
	$('#file').change(function(){
        ('#selected').text(this.value || 'Nothing selected')
    });
	
		//console.log("Dave");
	/*$.get("../AJAX-PHP/addPermissions.php", {
		//CO_ID : CO_ID
	}).done(function(data) {
		console.log(data);
		if (data != "success") {
			
		} else {
			
		}

	});*/
});
	

