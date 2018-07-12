<?php
/**
 *
 * moduleTests.inc.php
 *
 * author : piwi
 *
 * created: 10.05.2015
 * changed: 10.05.2015
 *
 * purpose:
 *
 */

require_once("../../lib/baseStart.php");
require_once("../../lib/uploadConstants.php");

$MODULES = $base->getConfiguration()->getListOfModules();

function cmp($a, $b) {
    return strcasecmp($a['moduleName'], $b['moduleName']);
}
$MODS = uasort($MODULES, "cmp");

?>
<script>
function loadModuleTests(moduleName) {
    $('#moduleTestId').load('tests/modules/listModuleTest.inc.php?<?php echo SID; ?>&moduleName=' + moduleName);
}
</script>

<div class="table" style="width:100%!important;height:500px;">
    <div class="trow">
        <div class="tcell" style="width:30%;">
            <div class="table" style="width:100%;">
                <div class="trow">
                    <div class="tcell">
                        <h3>Available Modules</h3>
                        <div id="moduleListId" style="height:200px;overflow:auto;border:1px solid lightgrey;">
                            <div class="table">
<?php
foreach($MODULES as $mod) {
?>
                                <div class="trow">
                                    <div class="tcell">
                                        <button onClick="loadModuleTests('<?php echo $mod['moduleName']; ?>', 'module');" style="width:150px;"
                                                class="ui-state-default ui-corner-all" id="<?php echo $mod['moduleName']; ?>Id">
                                        <label for="<?php echo $mod['moduleName']; ?>Id"><?php echo $mod['moduleName']; ?></label>
                                        </button>
                                    </div>
                                </div>
<?php
}
?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="trow">
                    <div class="tcell">
                        <h3>Available Testroutines</h3>
                        <div id="moduleTestId" style="height:150px;overflow:auto;">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="tcell" style="width:70%;">
            <h3 style="padding-bottom:2px;">Output</h3>
            <div id="moduleOutputContentId" style="width:100%;height:350px;border:1px solid black;"></div>
        </div>
    </div>
</div>