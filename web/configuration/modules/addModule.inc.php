<?php
/**
 *
 * addModule.inc.php
 *
 * author : piwi
 *
 * created: 22.01.2015
 * changed: 22.01.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");

$imgEdit   = "images/file_extension_zip.png";
ini_set("display_errors", 1);
print_r($_SESSION['AGENT']);
if($_SESSION['AGENT']['REPO']['selectedModuleRepositoryIdent']=="") {
    echo "You haven't choosen a repository";
    exit();
}
$result = $moduleConnector->getAvailableModules($_SESSION['AGENT']['REPO']['selectedModuleRepositoryIdent']);
?>
<style>
.table { display:table; }
.trow  { display:table-row; }
.tcell { display:table-cell; }
</style>
<script>
function getModuleDetails(sessid) {
    $.ajax({
        url: 'modules/getModuleDetails.php?'+sessid+'&selectedModuleId=' + $('#moduleToInstallId option:selected').val(),
        type:'POST',
        success: function(data) {
            $('#moduleDetailsId').html(data);
        }
    });
}
function installSelectedModule(sessid) {
    $.ajax({
        url: 'modules/installModule.php?'+sessid+'&selectedModuleId=' + $('#moduleToInstallId option:selected').val(),
        type:'POST',
        success: function(data) {
            $('#resultModuleInstallId').html(data);
        }
    });
}
</script>
<div class="table" style="width:100%!important;">
    <div class="trow">
        <div class="tcell" style="vertical-align:top;width:35%;">
            <select name="moduleToInstall" id="moduleToInstallId" size="10" style="height:300px;width:250px;" onClick="getModuleDetails('<?php echo SID; ?>');">
<?php
for ($count = 0; $count < count($result); $count++) {
?>
                <option value="<?php echo $result[$count]['module_id']; ?>"><?php echo $result[$count]['module_name']; ?></option>
<?php
}
?>
            </select><br>
            <div class="ui-button ui-state-default ui-corner-all" style="padding:10px;width:120px;" onClick="$('#dialog').dialog('destroy');">Close</div>
        </div>
        <div class="tcell" style="vertical-align:top;width:65%!important;">
            <div class="table" style="width:100%!important;">
                <div class="trow">
                    <div class="tcell" style="vertical-align:top;width:100%!important;">
                        <div class="ui-widget-content" style="height:150px;width:100%!important;border:1px solid lightgrey;">
                            Modul-Details
                            <div id="moduleDetailsId" style="height:150px;width:100%!important;background-color:white;overflow:auto;border:1px solid lightgrey;">
                            </div>
                        </div>
                        <button onClick="installSelectedModule('<?php echo SID; ?>');" class="ui-state-default ui-corner-all" style="display:none;width:120px;height:40px;">Installieren</button><br>
                    </div>
                </div>
                <div class="trow">
                    <div class="tcell" style="vertical-align:top;width:100%!important;">
                        <div class="ui-widget-content" style="height:150px;width:100%!important;border:1px solid lightgrey;">
                            Information
                            <div id="resultModuleInfoId" style="height:133px;width:100%!important;background-color:white;overflow:auto;border:1px solid lightgrey;">&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
                <div class="trow">
                    <div class="tcell" style="vertical-align:top;width:100%!important;">
                        <div class="ui-widget-content" style="height:200px;width:100%!important;border:1px solid lightgrey;">
                            Installation
                            <div id="resultModuleInstallId" style="height:173px;width:100%!important;background-color:white;overflow:auto;border:1px solid lightgrey;">&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
