<?php
/**
 *
 * checkArchive.inc.php
 *
 * author : piwi
 *
 * created: 19.04.2015
 * changed: 19.04.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");

$instFile = @scandir(INSTALL_UPLOADDIR);
for($count = 0; $count < count($instFile); $count++) {
    if($instFile[$count] != ".." && $instFile[$count] != ".") {
        $installFileName = $instFile[$count];
    }
}
?>
<script>
$(document).ready(function() {
    $('#filelistId').html('').load('extensions/dirlist.php?<?php echo SID; ?>&changedDirectory=&workdir=');
});

function changeDirectory(session, selectedValue, isParent) {
    console.clear();
    console.log(isParent + ': ' + selectedValue);
    if(isParent) {
        $('#filelistId').html('').load('extensions/dirlist.php?'+session+'&changedDirectory=&parentDirectory='+selectedValue+'&workdir='+encodeURI($('#pathId').val()));
    } else {
        $('#filelistId').html('').load('extensions/dirlist.php?'+session+'&changedDirectory='+selectedValue+'&parentDirectory=&workdir='+encodeURI($('#pathId').val()));
    }
}

function enableInstallButtonAndFillValues(extId, selectedValue) {
    $('#identId').val(selectedValue.replace(".inc.php", "").replace(".php", ""));
    $('#nameId').val(selectedValue);
    $('#installExtensionId').prop("disabled", false).removeClass('ui-state-disabled');
}

function performExtensionInstall(session, filename) {
    $('#installMonitorExtensionId').load('extensions/installExtension.php?' + session +
                                         '&fileName='+encodeURI(decodeURI(filename)) +
                                         '&name=' + $('#nameId').val() +
                                         '&ident=' + $('#identId').val() +
                                         '&path=' + encodeURI($('#pathId').val()) +
                                         '&version=' + $('#versionId').val());
}
</script>
<hr>
<div style="display:table;">
    <div style="display:table-row;">
        <div style="display:table-cell;vertical-align:top;width:30%;">
            <span style="font-weight:bold;">List of Files</span>
            <div style="height:300px;width:100%;overflow:auto;border:1px solid lightgrey;">
                <div id="filelistId">
                </div>
            </div>
        </div>
        <div style="display:table-cell;vertical-align:top;">
            <span style="font-weight:bold;">Check Your Input</span>
            <div style="height:300px;width:100%;overflow:auto;border:1px solid lightgrey;">
                <div style="display:table;">
                    <div style="display:table-row;">
                        <div style="display:table-cell;width:30%;vertical-align:top;">
                            <div style="display:table">
                                <div style="display:table-row;">
                                    <div class="ui-widget-content h40" style="display:table-cell;width:30%;">Ident:</div>
                                    <div class="ui-widget-content h40" style="display:table-cell;">
                                        <input type="text" name="ident" id="identId" value="<?php echo str_replace(array(".inc.php", ".php"), "", $installFiles[$iCount]); ?>">
                                    </div>
                                </div>
                                <div style="display:table-row;">
                                    <div class="ui-widget-content h40" style="display:table-cell;width:30%;">Name:</div>
                                    <div class="ui-widget-content h40" style="display:table-cell;">
                                        <input type="text" name="name" id="nameId" value="<?php echo basename($installFiles[$iCount]); ?>">
                                    </div>
                                </div>
                                <div style="display:table-row;">
                                    <div class="ui-widget-content h40" style="display:table-cell;width:30%;">Pfad:</div>
                                    <div class="ui-widget-content h40" style="display:table-cell;">
                                        <input type="text" name="path" id="pathId" value="<?php echo str_replace(INSTALL_WORKDIR, "", $scanDir); ?>">
                                    </div>
                                </div>
                                <div style="display:table-row;">
                                    <div class="ui-widget-content h40" style="display:table-cell;width:30%;">Version:</div>
                                    <div class="ui-widget-content h40" style="display:table-cell;">
                                        <input type="text" name="version" id="versionId" value=""><br><span style="font-size:8pt;">please enter Version of extension here</span>
                                    </div>
                                </div>
                                <div style="display:table-row;">
                                    <div class="ui-widget-content h40" style="display:table-cell;width:30%;">Only Load:</div>
                                    <div class="ui-widget-content h40" style="display:table-cell;">
                                        <input type="checkbox" name="onlyLoad" id="onlyLoadId" value=""><br><span style="font-size:8pt;">check this if the file is to be loaded only</span>
                                    </div>
                                </div>
                                <div style="display:table-row;">
                                    <div class="ui-widget-content h40" style="display:table-cell;">Aktion</div>
                                    <div class="ui-widget-content h40" style="display:table-cell;">
                                        <button class="ui-state-default ui-corner-all ui-state-disabled" id="installExtensionId" disabled="disabled" onClick="performExtensionInstall('<?php echo SID; ?>', encodeURI('<?php echo $installFileName; ?>', '<?php echo $workDir; ?>'));">
                                            Installation<br>ausführen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display:table-cell;vertical-align:top;width:40%;">
            <span style="font-weight:bold;">Install-Log</span>
            <div style="height:300px;width:100%;overflow:auto;border:1px solid lightgrey;">
                <div style="display:table;">
                    <div style="display:table-row;">
                         <div style="display:table-cell;">
                            <div id="installMonitorExtensionId" style="width:500px;height:280px;border:1px solid black;overflow:auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
