<?php
/**
 *
 * listExtensions.inc.php
 *
 * author : piwi
 *
 * created: 07.03.2015
 * changed: 07.03.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");

$EXTENSIONS = $base->getConfiguration()->getListOfExtensions();
$extOK      = ($_SESSION['_ENV']['USE_EXTENSIONS'] ? " checked=\"checked\"" : "");
$extKO      = (!$_SESSION['_ENV']['USE_EXTENSIONS'] ? " checked=\"checked\"" : "");
if(!is_array($EXTENSIONS)) { $EXTENSIONS = array(); }
?>
<link rel="stylesheet" href="css/monthselector.css">
<script src="lib/js/jquery.monthselector.widget.js"></script>
<link rel="stylesheet" href="css/checkboxreplacement.css">
<script src="lib/js/jquery.checkboxreplacement.widget.js"></script>
<script>
$(document).ready(function() {
    $('#radio3').buttonset();
    $('input[type=checkbox]').each(function() { $(this).checkboxReplacementWidget(); });
//    $('#monthId').monthSelectorWidget({backward: 7, forward: 5});
});

function createInstallExtDialog(sessId) {
    $('#dialog').hide();
    $('#dialog').dialog({ autoOpen: false, height: 600, width: 1200});
    $('#dialog').dialog("widget").find(".ui-dialog-titlebar").css({
        "float": "right",
        padding: 0,
        border: 0
    })
    .find(".ui-dialog-title").css({
        display: "none"
    }).end()
    .find(".ui-dialog-titlebar-close").css({display: "none"});
    $('#dialog').dialog("widget").css({border: "2px solid #1c94c4" });
    $('#dialog').dialog("open");
    $('#dialog').load('extensions/addExtension.inc.php?' + sessId, function(){
        $.getScript('lib/js/uploadExtension.js').fail('uploadExtension.js could not be loaded').done(function() {
            addExtensionUploadHandler('uploadExtensionContainerId', ['.gz', '.tgz', '.zip'], sessId);});
        });
}

function toggleExtensionActivity(sessId, fieldId) {
    var identifier = $('#'+fieldId).val();
    $.ajax({
        url: "extensions/toggleExtension.inc.php?" + sessId + "&selectedExtensionIdentifier="+identifier,
        method: "POST",
        success: function() {}
    });
}
</script>
<div class="container table99">
    <div class="trow">
        <div class="tcell ui-widget-header ui-corner-top">
            <button class="smallButton" onClick="createInstallExtDialog('<?php echo "PHPSESSID=".session_id(); ?>');">
                <img src="images/16x16/page_white.png" border="0">
            </button>
        </div>
        <div class="tcell ui-widget-header" style="text-align:center">
            Extensions installed in: <?php echo BASE_DIR . DS . "extensions" . DS; ?>
        </div>
        <div class="tcell ui-widget-header" style="text-align:center">
            Num of Extensions currently installed: <?php echo count($EXTENSIONS); ?>
        </div>
        <div class="tcell ui-widget-header ui-corner-tr vmiddle">
            <div class="table100">
                <div class="trow">
                    <div class="tcell100">
                        <div class="table100"  style="text-align:right">
                            <div class="trow">
                                <div class="tcell60 lalign h40">Use Extensions&nbsp;&nbsp;&nbsp;</div>
                                <div class="tcell40 calign vmiddle">
                                    <div class="onoffswitch" style="margin-left:auto;margin-right:auto;">
                                        <input onClick="checkIfChecked('USE_EXTENSIONS', this.id);" type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switchOnOffUseExtensionsId" <?php echo $extOK ? "checked" : ""; ?>>
                                        <label class="onoffswitch-label" for="switchOnOffUseExtensionsId">
                                            <span class="onoffswitch-inner"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container table99">
    <div class="trow">
        <div class="tcell10 h40 ui-widget-header">No</div>
        <div class="tcell10 h40 ui-widget-header">Ident</div>
        <div class="tcell20 h40 ui-widget-header">Filename</div>
        <div class="tcell25 h40 ui-widget-header">Path (relative to extension-install-path)</div>
        <div class="tcell5 h40 ui-widget-header">Only<br>Load</div>
        <div class="tcell10 h40 ui-widget-header">Version</div>
        <div class="tcell15 h40 ui-widget-header calign">Actions</div>
        <div class="tcell5 h40 ui-widget-header">Active</div>
    </div>
<?php
$extCount=0;
for($count = 0; $count < count($EXTENSIONS); $count++) {
    $extCount++;
    $state  = ((($count+1)%2)==0) ? "ui-widget-content" : "ui-widget-content";
?>
    <div class="trow">
        <div class="tcell10 ui-widget-content"><?php echo sprintf("%03d", ($extCount)); ?></div>
        <div class="tcell10 ui-widget-content"><?php echo trim($EXTENSIONS[$count]['ident']); ?></div>
        <div class="tcell20 ui-widget-content"><?php echo $EXTENSIONS[$count]['name']; ?></div>
        <div class="tcell25 ui-widget-content"><?php echo $EXTENSIONS[$count]['path']; ?></div>
        <div class="tcell5 ui-widget-content">
            <input type="checkbox" name="onlyLoad<?php echo trim($EXTENSIONS[$count]['ident']); ?>" id="onlyLoad<?php echo trim($EXTENSIONS[$count]['ident']); ?>Id" value="true"<?php echo ($EXTENSIONS[$count]['additional'] == "true" ? " checked=\"checked\"":""); ?>>
        </div>
        <div class="tcell10 ui-widget-content">
            <div class="table">
                <div class="trow">
                    <div class="tcell">
                        <?php echo trim($EXTENSIONS[$count]['version']); ?></div>
                    </div>
                </div>
            </div>
        <div class="actionList tcell15 ui-widget-content">
            <div class="table99">
                <div class="trow">
                    <div class="tcell">
                        <button style="width:50px;padding:5px;" onClick="createInstallExtDialog('<?php echo "PHPSESSID=".session_id(); ?>');">
                            <img src="images/16x16/update.png" border="0">
                        </button>
                    </div>
<?php
if($EXTENSIONS[$count]['additional'] != "") {
?>
                    <div class="tcell">
                        <button style="width:50px;padding:5px;" onClick="<?php echo $editURL; ?>">
                            <img src="images/16x16/pencil.png" border="0">
                        </button>
                    </div>
<?php
} else {
?>
            &nbsp;
<?php
}
?>
                </div>
            </div>
        </div>
        <div class="actionList tcell5 ui-widget-content">
                <input onClick="toggleExtensionActivity('<?php echo SID; ?>',this.id);" type="checkbox" name="<?php echo trim($EXTENSIONS[$count]['ident']); ?>"
                       value="<?php echo trim($EXTENSIONS[$count]['ident']); ?>"
                       id="module<?php echo trim($EXTENSIONS[$count]['ident']); ?>Id" <?php echo ($EXTENSIONS[$count]['active']=="true" ? "checked" : ""); ?>>
        </div>
    </div>
<?php
}
?>
</div>
