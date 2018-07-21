<?php
/**
 *
 * getModuleDetails.php
 *
 * author : piwi
 *
 * created: 25.01.2015
 * changed: 25.01.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");

$moduleDetails = $moduleConnector->getModuleDetails($_GET['selectedModuleId'], $_SESSION['AGENT']['REPO']['selectedModuleRepositoryIdent']);
?>
<script>
$(document).ready(function() {
    $('#resultModuleInfoId').html('<?php echo $moduleDetails[0]['description']; ?>');
});
</script>
<div class="table" style="width:100%!important;">
    <div class="trow">
        <div class="tcell25 ui-widget-header h40 vtop">ModuleName</div>
        <div class="tcell25 ui-widget-content h40 vtop"><?php echo $moduleDetails[0]['module_name']; ?></div>
        <div class="tcell25 ui-widget-header h40 vtop">Version</div>
        <div class="tcell25 ui-widget-content h40 vtop"><?php echo $moduleDetails[0]['module_version']; ?></div>
    </div>
    <div class="trow">
        <div class="tcell25 ui-widget-header h40 vtop">Author</div>
        <div class="tcell25 ui-widget-content h40 vtop"><?php echo $moduleDetails[0]['author']; ?></div>
        <div class="tcell25 ui-widget-header h40 vtop">Created</div>
        <div class="tcell25 ui-widget-content h40 vtop"><?php echo substr($moduleDetails[0]['created'], 0, 10); ?></div>
    </div>
    <div class="trow">
        <div class="tcell25 ui-widget-header h40 vtop">Released /<br>changed</div>
        <div class="tcell25 ui-widget-content h40 vtop"><?php echo substr($moduleDetails[0]['changed'], 0, 10); ?></div>
        <div class="tcell25 ui-widget-header h40 vtop">Action</div>
        <div class="tcell25 ui-widget-content h40 vtop">
            <button onClick="installSelectedModule('<?php echo "PHPSESSID=" . session_id(); ?>');" class="ui-state-default ui-corner-all">&nbsp;Installieren&nbsp;</button>
        </div>
    </div>
</div>
