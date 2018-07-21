<?php
/**
 *
 * addExtension.inc.php
 *
 * author : piwi
 *
 * created: 23.04.2015
 * changed: 23.04.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");
$imgEdit   = "images/16x16/pencil.png";
?>
<script>
function installExtensions(sessid) {
    $.ajax({
        url: 'modules/installExtension.php?'+sessid,
        type:'POST',
        success: function(data) {
            $('#resultExtensionInstallId').html(data);
        }
    });
}

function checkFileType() {
    var ext = $('#uploadExtensionFileId').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['tgz','gz','zip']) == -1) {
        $('#resultExtensionInstallId').html('Nicht unterst&uuml;tzter Dateityp').addClass('ui-state-highlight');
    } else {
        $('#checkButtonId').prop('disabled', false).removeClass('ui-state-disabled');
    }
}
</script>
<div id="uploadExtensionContainerId">
        <span style="font-weight:bold">Just select the file to install - it has to be a .zip or .tar.gz archive</span>
        <div class="table99">
            <div class="trow">
                <div class="tcell99">
                    <div style="display:none">
                        <input type="file" name="uploadExtensionFile" id="uploadExtensionFileId" onChange="checkFileType();">
                    </div>
                    <div onClick="document.getElementById('uploadExtensionFileId').click();" class="ui-button ui-state-default ui-corner-all" style="width:120px;padding:10px;float:left;">&nbsp;File&nbsp;</div>
                    <div style="float:left;padding-left:20px;" id="extensionFileNameId"></div>
                </div>
            </div>
            <div class="trow">
                <div class="tcell99">
                    <div id="checkButtonId" disabled="disabled" class="ui-button ui-state-default ui-corner-all ui-state-disabled" style="padding:10px;width:120px;" onClick="$('#fileArchiveResultId').load('extensions/checkArchive.inc.php');">Check</div>
                </div>
            </div>
            <div class="trow">
                <div class="tcell99">
                    <div id="fileArchiveResultId"></div>
                </div>
            </div>
            <div class="trow">
                <div class="tcell99">
                    <div class="ui-button ui-state-default ui-corner-all" style="padding:10px;width:120px;" onClick="$('#dialog').dialog('destroy');">Close</div>
                </div>
            </div>
        </div>
    <div id="resultUploadExtensionId"></div>
</div>