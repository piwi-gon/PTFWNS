<?php
/**
 *
 * index.php
 *
 * author : piwi
 *
 * created: 10.05.2015
 * changed: 10.05.2015
 *
 * purpose:
 *
 */

include("sessionStart.php");
?>
<script>
$(document).ready(function() {
    $('#testTabs').tabs();
});
</script>
<div id="testTabs">
    <ul>
        <li><a href="#testContentId" onClick="javascript:$('#testContentId').load('tests/modules/moduleTests.inc.php?<?php echo SID; ?>');">Modules</a></li>
        <li><a href="#testContentId" onClick="javascript:$('#testContentId').load('tests/extensions/extensionTests.inc.php?<?php echo SID; ?>');">Extensions</a></li>
    </ul>
    <div id="testContentId"></div>
</div>
