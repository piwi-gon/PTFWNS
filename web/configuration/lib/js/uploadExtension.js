/**
 *
 * uploadExtension.js
 *
 * author : piwi
 *
 * created: 19.04.2015
 * changed: 19.04.2015
 *
 * purpose:
 *
 */

var data;
var currentFilter;
var currentContainerId;
var loadFile = false;
var theFile;

function handleExtensionFiles(sessId) {
    console.log("using container '" + currentContainerId + "'");
    $('#' + currentContainerId).on('change', '#uploadExtensionFileId', function() {
        /**
         * first check the file-extension
         */
        console.log('checking filter');
        var fileExtension = "";
        fileExtension = this.files[0]['name'].substr(this.files[0]['name'].lastIndexOf("."), (this.files[0]['name'].length-this.files[0]['name'].lastIndexOf(".")));
        for(var i=0; i < currentFilter.length;i++) {
            if(fileExtension == currentFilter[i]) {
                loadFile=true;
            }
        }
        if(!loadFile) {
            $('#resultUploadExtensionId').html('unsupported Extension');
        } else {
            /**
             * now check the file-size
             *
             * additional check the PHP-Ini-Settings
             */
            theFile = this.files[0];
            var fileSize = theFile.size;
            var checkResult = checkSystemForFileSize(fileSize);
            if(checkResult['check']) {
                data = new FormData();
                // $('#extensionFileNameId').html($('#uploadExtensionFileId')[0].files[0]['name']);
                // Post-Daten vorbereiten
                data.append('file', theFile);
                $('#extensionFileNameId').html(theFile['name']);
                $.ajax({
                    url: "extensions/fileUpload.inc.php",
                    data: data,
                    contentType: 'multipart/form-data',
                    type: "POST",
                    processData: false,
                    contentType: false,
                    success: function(evt, response) {
                        $.ajax({
                            url: "extensions/decompress.inc.php?fileName=" + encodeURI(theFile['name']),
                            type: "POST",
                            success: function(data) {
                                $('#extensionFileNameId').html($('#extensionFileNameId').html() + " => decompressed");
                            }
                        });
                    }
                });
            } else {
                $('#resultUploadExtensionId').html('file-size ' + fileSize + ' of file "' + theFile['name'] + '" is too big.<br>' + checkResult['reason']);
            }
        }
    });
}

function addExtensionUploadHandler(containerId, filter, sessId) {
    console.log("adding handler to '" + containerId + "'");
    console.log("adding filter : "  + filter);
    currentFilter = filter;
    currentContainerId = containerId;
    handleExtensionFiles(sessId);
}

function uploadFiles() {
    if(loadFile) {
        data = new FormData();
        $('#extensionFileNameId').html($('#uploadExtensionFileId')[0].files[0]['name']);
        // Post-Daten vorbereiten
        data.append('file', $('#uploadExtensionFileId')[0].files[0]);
        if($('#extensionNameId').val()!=undefined && $('#extensionNameId').val() != "") {
            data.append('nameToInstall', $('#extensionNameId').val());
//            console.log("appending data '" + $('#extensionNameId').val());
        }
        if($('#extensionFileId').val()!=undefined && $('#extensionFileId').val() != "") {
            data.append('fileToInstall', $('#extensionFileId').val());
//            console.log("appending data '" + $('#extensionFileId').val());
        }
        if($('#extensionPathId').val()!=undefined && $('#extensionPathId').val() != "") {
            data.append('pathToInstall', $('#extensionPathId').val());
//            console.log("appending data '" + $('#extensionPathId').val());
        }
        if($('#extensionIdentifierId').val()!=undefined && $('#extensionIdentifierId').val() != "") {
            data.append('identifierToInstall', $('#extensionIdentifierId').val());
//            console.log("appending data '" + $('#extensionIdentifierId').val());
        }
        if($('#extensionVersionId').val()!=undefined && $('#extensionVersionId').val() != "") {
            data.append('versionToInstall', $('#extensionVersionId').val());
//            console.log("appending data '" + $('#extensionVersionId').val());
        }
        $.ajax({
            url: "extensions/fileUpload.inc.php",
            data: data,
            contentType: 'multipart/form-data',
            type: "POST",
            processData: false,
            contentType: false,
            success: function(evt, response) {
                $('#resultUploadExtensionId').html("file uploaded<br>" + evt);
            }
        });
    }
}

function uploadFilesModify() {
    if(loadFile) {
        data = new FormData();
//        console.log("extension is valid - try uploading");
        $('#extensionFileNameId').html($('#uploadExtensionFileId')[0].files[0]['name']);
        // Post-Daten vorbereiten
        data.append('file', $('#uploadExtensionFileId')[0].files[0]);
//        console.log("Uploading data");
        if($('#extensionNameId').val()!=undefined && $('#extensionNameId').val() != "") {
            data.append('nameToInstall', $('#extensionNameId').val());
//            console.log("appending data '" + $('#extensionNameId').val());
        }
        if($('#extensionFileId').val()!=undefined && $('#extensionFileId').val() != "") {
            data.append('fileToInstall', $('#extensionFileId').val());
//            console.log("appending data '" + $('#extensionFileId').val());
        }
        if($('#extensionPathId').val()!=undefined && $('#extensionPathId').val() != "") {
            data.append('pathToInstall', $('#extensionPathId').val());
//            console.log("appending data '" + $('#extensionPathId').val());
        }
        if($('#extensionIdentifierId').val()!=undefined && $('#extensionIdentifierId').val() != "") {
            data.append('identifierToInstall', $('#extensionIdentifierId').val());
//            console.log("appending data '" + $('#extensionIdentifierId').val());
        }
        if($('#extensionVersionId').val()!=undefined && $('#extensionVersionId').val() != "") {
            data.append('versionToInstall', $('#extensionVersionId').val());
//            console.log("appending data '" + $('#extensionVersionId').val());
        }
        $.ajax({
            url: "extensions/fileUpload.inc.php",
            data: data,
            contentType: 'multipart/form-data',
            type: "POST",
            processData: false,
            contentType: false,
            success: function(evt, response) {
                $('#resultUploadExtensionId').html("file uploaded<br>" + evt);
            }
        });
    }
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
