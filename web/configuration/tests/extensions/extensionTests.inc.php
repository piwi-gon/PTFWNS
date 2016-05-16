<?php
/**
 *
 * extensionTests.inc.php
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

$EXTENSIONS = $base->getConfiguration()->getListOfExtensions();

function cmp($a, $b) {
    return strcasecmp($a['ident'], $b['ident']);
}
uasort($EXTENSIONS, "cmp");

?>
<script>
function loadExtensionTests(ident) {
    $('#extensionTestId').load('tests/extensions/listExtensionTest.inc.php?<?php echo SID; ?>&ident='+ident);
}
</script>

<div class="table" style="width:100%!important;height:500px;">
    <div class="trow">
        <div class="tcell" style="width:30%;">
            <div class="table" style="width:100%;">
                <div class="trow">
                    <div class="tcell">
                        <h3>Available Extensions</h3>
                        <div id="extensionListId" style="height:200px;overflow:auto;border:1px solid lightgrey;">
                            <div class="table">
<?php
foreach($EXTENSIONS as $ext) {
?>
                                <div class="trow">
                                    <div class="tcell">
                                        <button onClick="loadExtensionTests('<?php echo $ext['ident']; ?>', 'extension');" style="width:150px;"
                                                class="ui-state-default ui-corner-all" id="<?php echo $ext['ident']; ?>Id">
                                        <label for="<?php echo $ext['ident']; ?>Id"><?php echo $ext['ident']; ?></label>
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
                        <div id="extensionTestId" style="height:150px;overflow:auto;">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="tcell" style="width:70%;">
            <h3 style="padding-bottom:2px;">Output</h3>
            <div id="extensionOutputContentId" style="width:100%;height:350px;border:1px solid black;"></div>
        </div>
    </div>
</div>
