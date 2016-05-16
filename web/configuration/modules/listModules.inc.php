<?php
/**
 *
 * listModules.inc.php
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

$MODULES = $base->getConfiguration()->getListOfModules();

$modOK      = ($_SESSION['_ENV']['USE_MODULES'] ? " checked=\"checked\"" : "");
$modKO      = (!$_SESSION['_ENV']['USE_MODULES'] ? " checked=\"checked\"" : "");
?>
<script>
$(document).ready(function() {
    $('#radio4').buttonset();
});

function showInfoDialog(baseURL, moduleName) {
    alert('showing info for "' + moduleName + '"');
}

function createInstallDialog(sessId) {
    $('#dialog').hide();
    $('#dialog').dialog({ height: 600, width: 900});
    $('#dialog').load('modules/addModule.inc.php?' + sessId, function(){
    $.getScript('lib/js/uploadModule.js').fail('uploadModule.js could not be loaded').success(function() {
        addModuleUploadHandler('uploadModuleContainerId', ['.gz', '.tgz', '.zip']);});
    });
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
    });
    $('#dialog').dialog("widget").css({border: "2px solid #1c94c4" });
    $('#dialog').show();
}

function createRepositoryChoiceDialog(sessId) {
    $('#dialog').hide();
    $('#dialog').dialog({ height: 600, width: 900});
    $('#dialog').load('modules/chooseRepository.inc.php?' + sessId);
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
    });
    $('#dialog').dialog("widget").css({border: "2px solid #1c94c4" });
    $('#dialog').show();
}

function updateSelectedModule(sessid, moduleName) {
    $('#checkModuleResult' + moduleName + 'Id').html('trying to update class').css({fontSize: '8pt', color: 'lightgrey'});
    $.ajax({
        url: 'modules/updateModule.php?'+sessid+'&selectedModuleName=' + moduleName,
        type:'POST',
        success: function(data) {
            console.log(data);
            var tokens = data.split("||");
            console.log(tokens[0]);
            console.log(tokens[1]);
            $('#moduleVersion' + moduleName + 'Id').html(tokens[0]);
            $('#checkModuleResult' + moduleName + 'Id').html(tokens[1]);
        }
    });
}

function toggleModuleActivity(sessId, fieldId) {
    var identifier = $('#'+fieldId).val();
    $.ajax({
        url: "modules/toggleModule.inc.php?" + sessId + "&selectedModuleIdentifier="+identifier,
        method: "POST",
        success: function() {}
    });
}
</script>
<div class="container" style="display:table;">
    <div class="table-row">
        <div class="table-header-cell ui-state-default ui-corner-tl">
            <div class="table">
                <div class="trow">
                    <div class="tcell">
                        <button class="ui-widget-header ui-corner-all" style="padding:5px;" id="selectRepositoryId"
                                onClick="createRepositoryChoiceDialog('<?php echo SID; ?>');">
                            <img src="images/16x16/plugin.png" border="0">
                        </button>
                    </div>
                    <div class="tcell">
                        <button class="ui-widget-header ui-corner-all ui-state-disabled" style="padding:5px;" id="installModuleId" disabled="disabled"
                                onClick="createInstallDialog('<?php echo SID; ?>');">
                            <img src="images/16x16/page_white.png" border="0">
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-header-cell ui-state-default" style="text-align:center">
            Modules installed in: <?php echo BASE_DIR . DS . "modules" . DS; ?>
        </div>
        <div class="table-header-cell ui-state-default" style="text-align:center">
            Num of Modules currently installed: <?php echo count($MODULES); ?>
        </div>
        <div class="table-header-cell ui-state-default ui-corner-tr" style="text-align:right">
            <div class="table100">
                <div class="trow">
                    <div class="tcell100">
                        <div class="table100"  style="text-align:right">
                            <div class="trow">
                                <div class="tcell" style="width:80%;text-align:right;">Use Modules&nbsp;&nbsp;&nbsp;</div>
                                <div class="tcell" style="width:20%;text-align:center;vertical-align:middle;">
                                    <div class="onoffswitch" style="margin-left:auto;margin-right:auto;margin-top:-7px;">
                                        <input onClick="checkIfChecked('USE_MODULES', this.id);" type="checkbox" name="onoffswitchModules" class="onoffswitch-checkbox" id="switchOnOffUseModulesId" <?php echo $modOK ? "checked" : ""; ?>>
                                        <label class="onoffswitch-label" for="switchOnOffUseModulesId">
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
<br>
<div class="container" style="display:table;">
    <div class="table-row">
        <div class="thcell ui-state-default" style="width:5%;">No</div>
        <div class="thcell ui-state-default" style="width:10%;">Filename</div>
        <div class="thcell ui-state-default" style="width:20%;">Path (relative to module-install-path)</div>
        <div class="thcell ui-state-default" style="width:55%;">
            <div class="table100">
                <div class="trow">
                    <div class="tcell10" style="text-align:center;">Info</div>
                    <div class="tcell15" style="text-align:center;">Version</div>
                    <div class="tcell50" style="text-align:center;">Repository</div>
                    <div class="tcell10" style="text-align:center;">Actual</div>
                    <div class="tcell15" style="text-align:center;">Active</div>
                </div>
            </div>
        </div>
    </div>
<?php
$modCounter=0;
for($count = 0; $count < count($MODULES); $count++) {
    $modCounter++;
    $state  = ((($count+1)%2)==0) ? "ui-widget-content" : "ui-widget-content-alt";
?>
    <div class="table-row">
        <div class="table-cell <?php echo $state; ?>" style="width:5%;vertical-align:middle;"><?php echo sprintf("%03d", ($modCounter)); ?></div>
        <div class="table-cell <?php echo $state; ?>" style="width:20%;vertical-align:middle;"><?php echo $MODULES[$count]['name']; ?></div>
        <div class="table-cell <?php echo $state; ?>" style="width:20%;vertical-align:middle;"><?php echo $MODULES[$count]['path']; ?></div>
        <div class="table-cell <?php echo $state; ?>" style="width:55%;vertical-align:middle;">
            <div class="container">
                <div class="table-row">
                    <div class="tcell10" style="vertical-align:middle;text-align:center;">
                        <button class="ui-widget-header ui-corner-all" style="padding:5px;" onClick="showInfoDialog('<?php echo "PHPSESSID=".session_id(); ?>', '<?php echo $MODULES[$count]['moduleName']; ?>');">
                            <img src="images/16x16/info_rhombus.png" border="0">
                        </button>
                    </div>
                    <div class="tcell15" style="vertical-align:middle;text-align:center;">
                        <div id="moduleVersion<?php echo $MODULES[$count]['moduleName']; ?>Id"><?php echo trim($MODULES[$count]['version']); ?></div>
                    </div>
                    <div class="tcell50" style="vertical-align:middle;text-align:center;">
                        <div id="moduleVersion<?php echo $MODULES[$count]['repository']; ?>Id"><?php echo trim($MODULES[$count]['repository']); ?></div>
                    </div>
                    <div class="tcell10" style="vertical-align:middle;text-align:center;">
<?php
        if($MODULES[$count]['moduleState']) {
?>
                        <div id="checkModuleResult<?php echo $MODULES[$count]['moduleName']; ?>Id"><img src="images/16x16/accept.png"></div>
<?php
        } else {
?>
                        <div id="checkModuleResult<?php echo $MODULES[$count]['moduleName']; ?>Id">
                            <div class="table">
                                <div class="table-row">
                                    <div class="table-cell" style="vertical-align:middle;">
                                        <img src="images/16x16/new.png"
                                             onClick="updateSelectedModule('<?php echo SID; ?>', '<?php echo $MODULES[$count]['moduleName']; ?>');"
                                             title="Install new Version"
                                             style="cursor:pointer;">
                                    </div>
                                    <div class="table-cell" style="vertical-align:middle;"><?php echo $moduleConnector->getNewVersion(); ?></div>
                                </div>
                            </div>
                        </div>
<?php
        }
?>
                    </div>
                    <div class="tcell15" style="vertical-align:middle;text-align:center;">
                        <div class="onoffswitch_small" style="margin-left:auto;margin-right:auto;">
                            <input onClick="toggleModuleActivity('<?php echo SID; ?>',this.id);" type="checkbox" name="<?php echo $MODULES[$count]['moduleName']; ?>"
                                   class="onoffswitch_small-checkbox"
                                   value="<?php echo $MODULES[$count]['moduleName']; ?>"
                                   id="module<?php echo $MODULES[$count]['moduleName']; ?>Id" <?php echo (strtolower($MODULES[$count]['active'])=="true" ? "checked" : ""); ?>>
                            <label class="onoffswitch_small-label" for="module<?php echo $MODULES[$count]['moduleName']; ?>Id">
                                <span class="onoffswitch_small-inner"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
    <div id="installedModulesId">
    </div>
</div>
