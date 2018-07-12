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
<style>
.table { display:table; }
.trow  { display:table-row; }
.tcell { display:table-cell; }
</style>
<script>
$(document).ready(function() {
    $('#resultModuleInfoId').html('<?php echo $moduleDetails[0]['description']; ?>');
});
</script>
<div class="table" style="width:100%!important;">
    <div class="trow">
        <div class="tcell ui-widget-header" style="width:25%!important;height:40px;">ModuleName</div>
        <div class="tcell ui-widget-content" style="width:25%!important;height:40px;"><?php echo $moduleDetails[0]['module_name']; ?></div>
        <div class="tcell ui-widget-header" style="width:25%!important;height:40px;">Version</div>
        <div class="tcell ui-widget-content" style="width:25%!important;height:40px;"><?php echo $moduleDetails[0]['module_version']; ?></div>
    </div>
    <div class="trow">
        <div class="tcell ui-widget-header" style="width:25%!important;height:40px;">Author</div>
        <div class="tcell ui-widget-content" style="width:25%!important;height:40px;"><?php echo $moduleDetails[0]['author']; ?></div>
        <div class="tcell ui-widget-header" style="width:25%!important;height:40px;">Created</div>
        <div class="tcell ui-widget-content" style="width:25%!important;height:40px;"><?php echo substr($moduleDetails[0]['created'], 0, 10); ?></div>
    </div>
    <div class="trow">
        <div class="tcell ui-widget-header" style="width:25%!important;height:40px;">Released /<br>changed</div>
        <div class="tcell ui-widget-content" style="width:25%!important;height:40px;"><?php echo substr($moduleDetails[0]['changed'], 0, 10); ?></div>
        <div class="tcell ui-widget-header" style="width:25%!important;height:40px;">Action</div>
        <div class="tcell ui-widget-content" style="width:25%!important;height:40px;">
            <button onClick="installSelectedModule('<?php echo "PHPSESSID=" . session_id(); ?>');" class="ui-state-default ui-corner-all">&nbsp;Installieren&nbsp;</button>
        </div>
    </div>
</div>
