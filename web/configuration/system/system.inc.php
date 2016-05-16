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
<div class="table" style="width:100%;margin:0 auto;">
    <div class="trow">
        <div class="tcell" style="width:30%;">System-Version:</div>
        <div class="tcell" style="width:70%;">
            <?php echo $system['version_installed']; ?>
        </div>
    </div>
    <div class="trow">
        <div class="tcell" style="width:30%;">is aktuell:</div>
        <div class="tcell" style="width:70%;">
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
