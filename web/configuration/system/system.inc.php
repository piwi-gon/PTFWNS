<?php
/**
 *
 * system.inc.php
 *
 * author : piwi
 *
 * created: 28.10.2015
 * changed: 28.10.2015
 *
 * purpose:
 *
 */


/**
 * first: include the basic-start
 */
require_once(__DIR__."/../lib/baseStart.php");

?>
<div class="table99">
    <div class="trow">
        <div class="tcell25 ui-widget-content" style="height:40px;vertical-align:middle;">DEBUG</div>
        <div style="height:40px;text-align:center;vertical-align:middle;" class="tcell25 ui-widget-content">
            <div class="onoffswitch" style="margin-left:auto;margin-right:auto;">
                <input onClick="checkIfChecked('DEBUGLEVELID', this.id);" type="checkbox" name="DEBUG" class="onoffswitch-checkbox" id="DEBUGId" <?php echo $debugOK ? "checked" : ""; ?>>
                <label class="onoffswitch-label" for="DEBUGId">
                    <span class="onoffswitch-inner"></span>
                </label>
            </div>
        </div>
        <div class="tcell25 ui-widget-content" style="height:40px;vertical-align:middle;">DEBUGLEVEL</div>
        <div style="height:40px;text-align:center;vertical-align:middle;" class="tcell25 ui-widget-content">
            <input type="text" name="DEBUGLEVEL" id="DEBUGLEVELID" size=2 maxlength=2 width="20px;" value="<?php echo intval($debugLevel); ?>"<?php echo ($debugKO ? " disabled=\"disabled\"" : "") ?>>
        </div>
    </div>
    <div class="trow">
        <div class="tcell25 ui-widget-content" style="height:40px;vertical-align:middle;">UPLOAD</div>
        <div style="height:40px;text-align:center;vertical-align:middle;" class="tcell25 ui-widget-content">
            <div class="onoffswitch" style="margin-left:auto;margin-right:auto;">
                <input onClick="checkIfChecked('UPLOAD_SIZEID', this.id);" type="checkbox" name="UPLOAD" class="onoffswitch-checkbox" id="UPLOADId" <?php echo $uploadOK ? "checked" : ""; ?>>
                <label class="onoffswitch-label" for="UPLOADId">
                    <span class="onoffswitch-inner"></span>
                </label>
            </div>
        </div>
        <div class="tcell25 ui-widget-content" style="height:40px;vertical-align:middle;">UPLOAD-SIZE</div>
        <div style="height:40px;text-align:center;vertical-align:middle;" class="tcell25 ui-widget-content">
            <input type="text" name="UPLOAD_SIZE" id="UPLOAD_SIZEID" size=2 maxlength=2 width="20px;" value="<?php echo intval($uploadSize); ?>"<?php echo ($uploadKO ? " disabled=\"disabled\"" : "") ?>>
        </div>
    </div>
</div>
<div class="table99">
    <div class="trow">
        <div class="tcell25 lalign">
            <button onclick="modifyEnvironment();">Aktualisieren</button>
            
        </div>
        <div class="tcell75 calign">
            <div id="resultUpdateEnvironmentId"></div>
        </div>
    </div>
</div>
<div class="table99" style="width:100%;margin:0 auto;">
    <div class="trow">
        <div class="tcell30">System-Version:</div>
        <div class="tcell70">
            <?php echo $system['version_installed']; ?>
        </div>
    </div>
    <div class="trow">
        <div class="tcell30">is aktuell:</div>
        <div class="tcell70">
            <?php echo $system['actual'] ? "yes" : "new version available (" . $system['version_available'] . ")"; ?>
        </div>
    </div>
<?php
if(!$system['actual']) {
?>
</div>
<div class="table">
    <div class="trow">
        <div class="tcell100" style="text-align:right;">
            <button onClick="updatePTFWSystem('<?php echo SID; ?>');" class="ui-state-default ui-corner-all" title="update to new Version">Update</button>
        </div>
    </div>
<?php
} else {
?>
</div>
<div class="table">
    <div class="trow">
        <div class="tcell100" style="text-align:right;">
            <button onClick="refreshPTFWSystem('<?php echo SID; ?>');" class="ui-state-default ui-corner-all" title="refresh installation">Refresh</button>
        </div>
    </div>
<?php
}
?>
</div>
