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
?>
<link rel="stylesheet" href="css/monthselector.css">
<script src="lib/js/jquery.monthselector.widget.js"></script>
<script>
$(document).ready(function() {
    $('#radio3').buttonset();
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
    .find(".ui-dialog-titlebar-close").css({
        display: "none"
//         top: 0,
//         right: 0,
//         margin: 0,
//         "z-index": 999
    });
    $('#dialog').dialog("widget").css({border: "2px solid #1c94c4" });
    $('#dialog').dialog("open");
    $('#dialog').load('extensions/addExtension.inc.php?' + sessId, function(){
        $.getScript('lib/js/uploadExtension.js').fail('uploadExtension.js could not be loaded').success(function() {
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
<div class="container" style="display:table;">
    <div class="table-row">
        <div class="table-header-cell ui-state-default ui-corner-top">
            <button class="ui-widget-header ui-corner-all" style="padding:5px;" onClick="createInstallExtDialog('<?php echo "PHPSESSID=".session_id(); ?>');">
                <img src="images/16x16/page_white.png" border="0">
            </button>
        </div>
        <div class="table-header-cell ui-state-default" style="text-align:center">
            Extensions installed in: <?php echo BASE_DIR . DS . "extensions" . DS; ?>
        </div>
        <div class="table-header-cell ui-state-default" style="text-align:center">
            Num of Extensions currently installed: <?php echo count($EXTENSIONS); ?>
        </div>
        <div class="table-header-cell ui-state-default ui-corner-tr" style="text-align:right">
            <div class="table100">
                <div class="trow">
                    <div class="tcell100">
                        <div class="table100"  style="text-align:right">
                            <div class="trow">
                                <div class="tcell" style="width:80%;text-align:right;">Use Extensions&nbsp;&nbsp;&nbsp;</div>
                                <div class="tcell" style="width:20%;text-align:center;vertical-align:middle;">
                                    <div class="onoffswitch" style="margin-left:auto;margin-right:auto;margin-top:-7px;">
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
<div class="container" style="display:table;">
    <div class="table-row">
        <div class="table-header-cell ui-state-default" style="width:10%;">No</div>
        <div class="table-header-cell ui-state-default" style="width:10%;">Ident</div>
        <div class="table-header-cell ui-state-default" style="width:20%;">Filename</div>
        <div class="table-header-cell ui-state-default" style="width:30%;">Path (relative to extension-install-path)</div>
        <div class="table-header-cell ui-state-default" style="width:10%;">Version</div>
        <div class="table-header-cell ui-state-default" style="width:15%;text-align:center;">Actions</div>
        <div class="table-header-cell ui-state-default" style="width:5%;">Active</div>
    </div>
<?php
$extCount=0;
for($count = 0; $count < count($EXTENSIONS); $count++) {
    $extCount++;
    $state  = ((($count+1)%2)==0) ? "ui-widget-content" : "ui-widget-content-alt";
?>
    <div class="table-row">
        <div class="table-cell <?php echo $state; ?>" style="width:10%;"><?php echo sprintf("%03d", ($extCount)); ?></div>
        <div class="table-cell <?php echo $state; ?>" style="width:10%;"><?php echo trim($EXTENSIONS[$count]['ident']); ?></div>
        <div class="table-cell <?php echo $state; ?>" style="width:20%;"><?php echo $EXTENSIONS[$count]['name']; ?></div>
        <div class="table-cell <?php echo $state; ?>" style="width:30%;"><?php echo $EXTENSIONS[$count]['path']; ?></div>
        <div class="table-cell <?php echo $state; ?>" style="width:10%;">
            <div class="table">
                <div class="trow">
                    <div class="tcell">
                        <?php echo trim($EXTENSIONS[$count]['version']); ?></div>
                    </div>
                </div>
            </div>
        <div class="actionList table-cell <?php echo $state; ?>" style="width:15%;">
            <div style="display:table;width:100%!important;">
                <div style="display:table-row">
                    <div style="display:table-cell">
                        <button class="ui-widget-header ui-corner-all" style="padding:5px;" onClick="createInstallExtDialog('<?php echo "PHPSESSID=".session_id(); ?>');">
                            <img src="images/16x16/update.png" border="0">
                        </button>
                    </div>
<?php
if($EXTENSIONS[$count]['additional'] != "") {
?>
                    <div style="display:table-cell">
                        <button class="ui-widget-header ui-corner-all" style="padding:5px;" onClick="<?php echo $editURL; ?>">
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
        <div class="actionList table-cell <?php echo $state; ?>" style="width:15%;vertical-align:middle;text-align:center;">
            <div class="onoffswitch_small" style="margin-left:auto;margin-right:auto;">
                <input onClick="toggleExtensionActivity('<?php echo SID; ?>',this.id);" type="checkbox" name="<?php echo trim($EXTENSIONS[$count]['ident']); ?>"
                       value="<?php echo trim($EXTENSIONS[$count]['ident']); ?>"
                       class="onoffswitch_small-checkbox"
                       id="module<?php echo trim($EXTENSIONS[$count]['ident']); ?>Id" <?php echo ($EXTENSIONS[$count]['active']=="true" ? "checked" : ""); ?>>
                <label class="onoffswitch_small-label" for="module<?php echo trim($EXTENSIONS[$count]['ident']); ?>Id">
                    <span class="onoffswitch_small-inner"></span>
                </label>
            </div>
        </div>
    </div>
<?php
}
?>
</div>
