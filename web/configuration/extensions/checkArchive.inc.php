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
    $('#horizontalButtonSetId').buttonset();
});

function enableInstallButtonAndFillValues(extId) {
    var selectedValue = $('#selectedFile' + extId + 'Id').val();
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
                <div style="display:table;">
                    <div style="display:table-row;">
                        <div style="display:table-cell;vertical-align:top;">
                            <div id="horizontalButtonSetId">
<?php
$files = @scandir(INSTALL_WORKDIR);
for($count = 0; $count < count($files); $count++) {
    // check for the first directory -
    // its the installed directory normally
    if($files[$count] != ".." && $files[$count] != ".") {
        if(is_dir(INSTALL_WORKDIR . $files[$count])) {
            $scanDir = INSTALL_WORKDIR . $files[$count];
            $installFiles = @scandir($scanDir);
            for($iCount = 0; $iCount < count($installFiles); $iCount++) {
                if($installFiles[$iCount] != ".." && $installFiles[$iCount] != ".") {
                    if(!is_dir($scanDir . DS . $installFiles[$iCount])) {
?>
                <div>
                    <input onClick="enableInstallButtonAndFillValues('<?php echo $iCount; ?>');"
                           type="radio" name="selectedFile"
                           id="selectedFile<?php echo $iCount; ?>Id"
                           value="<?php echo $installFiles[$iCount]; ?>">
                    <label for="selectedFile<?php echo $iCount; ?>Id"><?php echo $installFiles[$iCount]; ?></label>
                </div>
<?php
                    }
                }
            }
        } else {
            echo "found file '" . $files[$count] . "'<br>";
        }
    }
}
?>
                            </div>
                        </div>
                    </div>
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
                                    <div style="display:table-cell;">Ident:</div>
                                    <div style="display:table-cell;">
                                        <input type="text" name="ident" id="identId" value="<?php echo str_replace(array(".inc.php", ".php"), "", $installFiles[$iCount]); ?>">
                                    </div>
                                </div>
                                <div style="display:table-row;">
                                    <div style="display:table-cell;">Name:</div>
                                    <div style="display:table-cell;">
                                        <input type="text" name="name" id="nameId" value="<?php echo basename($installFiles[$iCount]); ?>">
                                    </div>
                                </div>
                                <div style="display:table-row;">
                                    <div style="display:table-cell;">Pfad:</div>
                                    <div style="display:table-cell;">
                                        <input type="text" name="path" id="pathId" value="<?php echo str_replace(INSTALL_WORKDIR, "", $scanDir); ?>">
                                    </div>
                                </div>
                                <div style="display:table-row;">
                                    <div style="display:table-cell;">Version:</div>
                                    <div style="display:table-cell;">
                                        <input type="text" name="version" id="versionId" value=""><br><span style="font-size:8pt;">please enter Version of extension here</span>
                                    </div>
                                </div>
                                <div style="display:table-row;">
                                    <div style="display:table-cell;">Aktion</div>
                                    <div style="display:table-cell;">
                                        <button class="ui-state-default ui-corner-all ui-state-disabled" id="installExtensionId" disabled="disabled" onClick="performExtensionInstall('<?php echo SID; ?>', encodeURI('<?php echo $installFileName; ?>'));">
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
