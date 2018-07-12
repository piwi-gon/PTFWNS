<?php
/**
 *
 * listExtensionTest.inc.php
 *
 * author : piwi
 *
 * created: 29.08.2015
 * changed: 29.08.2015
 *
 * purpose:
 *
 */

require_once("../../lib/baseStart.php");
require_once("../../lib/uploadConstants.php");

$TESTS = $base->getExtensionClass()->queryTestByExtension($_GET['ident']);
?>
<div class="table">
    <div class="trow">
        <div class="tcell">
<?php
if($TESTS != "") {
    include_once($TESTS);
} else {
?>
            No Tests available for this extension
<?php
}
?>
        </div>
    </div>
</div>
