/**
 *
 * uploadModule.js
 *
 * author : piwi
 *
 * created: 19.04.2015
 * changed: 19.04.2015
 *
 * purpose:
 *
 */

function addModuleUploadHandler(containerId, filter) {
    var loadFile = false;
    var data;
    $('#' + containerId).on('change', '#uploadModuleFileId', function() {
        var fileExtension = "";
        fileExtension = this.files[0]['name'].substr(this.files[0]['name'].lastIndexOf("."), (this.files[0]['name'].length-this.files[0]['name'].lastIndexOf(".")));
        for(var i=0; i < filter.length;i++) {
            if(fileExtension == filter[i]) {
                loadFile=true;
                console.log(this.files[0]['name']);
                $('#moduleFileNameId').html(this.files[0]['name']);
            }
        }
        if(loadFile) {
            /**
             * now check the file-size
             *
             * additional check the PHP-Ini-Settings
             */
            theFile = this.files[0];
            /**
             * now check the file-size
             *
             * additional check the PHP-Ini-Settings
             */
            theFile = this.files[0];
            var fileSize = theFile.size;
            var checkResult = checkSystemForFileSize(fileSize);
            if(checkResult['check']) {
                console.log("extension is valid - try uploading");
                // Post-Daten vorbereiten
                data = new FormData();
                data.append('file', this.files[0]);
                $.ajax({
                    url: "modules/fileUpload.inc.php",
                    data: data,
                    type: "POST",
                    processData: false,
                    contentType: false,
                    success: function(evt, response) {
                        $('#resultUploadModuleId').html("file uploaded<br>" + evt);
                    }
                });
            } else {
                $('#resultUploadExtensionId').html('file-size ' + fileSize + ' of file "' + theFile['name'] + '" is too big.<br>' + checkResult['reason']);
            }
        } else {
            $('#resultUploadModuleId').html('unsupported File-Extension');
        }
    });
}

function performModuleInstall(URL) {
    $('#installMonitorModuleId').load(URL);
}

function checkSystemForFileSize(fileSize) {
    var result=[];
    $.ajax({
        url: 'environment/checkSystemForFileSize.php?fileName=' + encodeURI(theFile['name']) + '&fileSize=' + fileSize,
        type: "POST",
        async: false,
        success: function(data) {
            if(data.substring(0,4) != "true") {
                result['check'] = false;
                result['reason'] = data.substring(5);
            } else {
                result['check'] = true;
            }
        }
    });
    return result;
}
/*
$.ajax({
    url: 'environment/checkSystemForFileSize.php?fileName=' + encodeURI(theFile['name']) + '&fileSize=' + fileSize,
    type: "POST",
    success: function(data) {
        console.log(data);
        if(data.substring(0,4) != "true") {

        } else {
        }
    }
});
*/