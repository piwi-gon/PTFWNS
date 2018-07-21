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
    $('#dialog').dialog({ height: determineHeight(80), width:determineWidth(80) });
    $('#dialog').load('modules/addModule.inc.php?' + sessId, function(){
    $.getScript('lib/js/uploadModule.js').fail('uploadModule.js could not be loaded').done(function() {
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

function createInstallFromGitHubDialog(sessId) {
    $('#dialog').hide();
    $('#dialog').dialog({ height: determineHeight(80), width: determineWidth(80)});
    $('#dialog').load('modules/addModuleFromGithub.inc.php?' + sessId, function(){
    $.getScript('lib/js/uploadModule.js').fail('uploadModule.js could not be loaded').done(function() {
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
    $('#dialog').dialog({ height: determineHeight(40), width: determineWidth(20)});
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

function generateModuleIndex(sessionId) {
    $('#resultGenerateModuleIndexId').html('generating Index');
    $.ajax({
        url: 'environment/generateModuleIndex.php?' + sessionId,
        type: 'POST',
        success: function(data) {
            if(data == "success") {
                $('#resultGenerateModuleIndexId').html('Index successfully generated');
            }
        }
    });
}
</script>
<div class="table99">
    <div class="trow">
        <div class="tcell25 lalign">
            <button onclick="generateModuleIndex('<?php echo SID; ?>');" style="width:auto;">(Re-)Generate Module-Index</button>
        </div>
        <div class="tcell75 calign">
            <div id="resultGenerateModuleIndexId"></div>
        </div>

    </div>
</div>
<div class="container table99">
    <div class="trow">
        <div class="tcell ui-widget-header ui-corner-tl vmiddle">
            <div class="table">
                <div class="trow">
                    <div class="tcell" style="vertical-align:middle;">
                        <button style="width:50px;" id="selectRepositoryId"
                                onClick="createRepositoryChoiceDialog('<?php echo SID; ?>');">
                            <img src="images/32x32/plugin.png" border="0">
                        </button>
                    </div>
                    <div class="tcell" style="vertical-align:middle;">
                        <button style="width:50px;" id="installModuleId" disabled="disabled"
                                onClick="createInstallDialog('<?php echo SID; ?>');">
                            <img src="images/32x32/page_white.png" border="0">
                        </button>
                    </div>
                    <div class="tcell" style="vertical-align:middle;">
                        <button style="width:50px;" id="installModuleFromGitHubId" onClick="createInstallFromGitHubDialog('<?php echo SID; ?>');">
                            <img src="images/32x32/GitHub-Mark-32px.png" border="0">
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="tcell ui-widget-header vmiddle" style="text-align:center">
            Modules installed in:<br><?php echo BASE_DIR . DS . "modules" . DS; ?>
        </div>
        <div class="tcell ui-widget-header vmiddle" style="text-align:center">
            Num of Modules currently installed: <?php echo count($MODULES); ?>
        </div>
        <div class="tcell ui-widget-header ui-corner-tr vmiddle" style="text-align:right">
            <div class="table100">
                <div class="trow">
                    <div class="tcell100">
                        <div class="table100"  style="text-align:right">
                            <div class="trow">
                                <div class="tcell50 h40 vmiddle lalign">Use Modules&nbsp;&nbsp;&nbsp;</div>
                                <div class="tcell50 h40 vmiddle calign">
                                    <div class="onoffswitch" style="margin-left:auto;margin-right:auto;">
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
<div class="container" style="display:table;overflow:auto;">
    <div class="trow">
        <div class="tcell5 h40 ui-widget-header">No</div>
        <div class="tcell20 h40 ui-widget-header">Filename</div>
        <div class="tcell20 h40 ui-widget-header">Path (relative to module-install-path)</div>
        <div class="tcell55 h40 ui-widget-header vmiddle">
            <div class="table99">
                <div class="trow">
                    <div class="tcell15 ui-widget-header" style="text-align:center;">Info</div>
                    <div class="tcell15 ui-widget-header" style="text-align:center;">Version</div>
                    <div class="tcell45 ui-widget-header" style="text-align:center;">Repository</div>
                    <div class="tcell10 ui-widget-header" style="text-align:center;">Actual</div>
                    <div class="tcell15 ui-widget-header" style="text-align:center;">Active</div>
                </div>
            </div>
        </div>
    </div>
<?php
$modCounter=0;
for($count = 0; $count < count($MODULES); $count++) {
    $modCounter++;
    $state  = ((($count+1)%2)==0) ? "ui-widget-content" : "ui-widget-content";
?>
    <div class="trow">
        <div class="tcell5 ui-widget-content vmiddle"><?php echo sprintf("%03d", ($modCounter)); ?></div>
        <div class="tcell20 ui-widget-content vmiddle"><?php echo $MODULES[$count]['name']; ?></div>
        <div class="tcell20 ui-widget-content vmiddle"><?php echo $MODULES[$count]['path']; ?></div>
        <div class="tcell55 ui-widget-content vmiddle">
            <div class="table99">
                <div class="trow">
                    <div class="tcell15 ui-widget-content h40 vmiddle calign">
                        <button class="smallButton" onClick="showInfoDialog('<?php echo "PHPSESSID=".session_id(); ?>', '<?php echo $MODULES[$count]['moduleName']; ?>');">
                            <img src="images/16x16/info_rhombus.png" border="0">
                        </button>
                    </div>
                    <div class="tcell15 ui-widget-content h40 vmiddle calign">
                        <div id="moduleVersion<?php echo $MODULES[$count]['moduleName']; ?>Id"><?php echo trim($MODULES[$count]['version']); ?></div>
                    </div>
                    <div class="tcell45 ui-widget-content h40 vmiddle calign">
                        <div id="moduleVersion<?php echo $MODULES[$count]['repository']; ?>Id"><?php echo trim($MODULES[$count]['repository']); ?></div>
                    </div>
                    <div class="tcell10 ui-widget-content h40 vmiddle calign">
<?php
        if($MODULES[$count]['moduleState']) {
?>
                        <div id="checkModuleResult<?php echo $MODULES[$count]['moduleName']; ?>Id"><img src="images/16x16/accept.png"></div>
<?php
        } else {
?>
                        <div id="checkModuleResult<?php echo $MODULES[$count]['moduleName']; ?>Id">
                            <div class="table">
                                <div class="trow">
                                    <div class="tcell" style="vertical-align:middle;">
                                        <img src="images/16x16/new.png"
                                             onClick="updateSelectedModule('<?php echo SID; ?>', '<?php echo $MODULES[$count]['moduleName']; ?>');"
                                             title="Install new Version"
                                             style="cursor:pointer;">
                                    </div>
                                    <div class="tcell" style="vertical-align:middle;"><?php echo $moduleConnector->getNewVersion(); ?></div>
                                </div>
                            </div>
                        </div>
<?php
        }
?>
                    </div>
                    <div class="tcell15 ui-widget-content h40 vmiddle calign">
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
