<?php
/**
 *
 * listModuleTest.inc.php
 *
 * author : piwi
 *
 * created: 19.08.2015
 * changed: 19.08.2015
 *
 * purpose:
 *
 */

require_once("../../lib/baseStart.php");
require_once("../../lib/uploadConstants.php");

$TESTS = $base->getModuleClass()->queryTestByModuleName($_GET['moduleName']);
?>
<div class="table">
    <div class="trow">
        <div class="tcell">
<?php
if(count($TESTS)> 0) {
    for($count = 0; $count < count($TESTS); $count++) {
?>
            <button class="ui-state-default ui-corner-all" style="width:150px;"
                    onClick="loadSpecificModuleTest('<?php echo $_GET['moduleName']; ?>', '<?php echo $TESTS[$count]; ?>');">
                <?php echo $TESTS[$count]; ?>
            </button>
<?php
    }
} else {
?>
            No Tests available for this module
<?php
}
?>
        </div>
    </div>
</div>
