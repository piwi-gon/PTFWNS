<?php
/**
 *
 * environment.inc.php
 *
 * author : piwi
 *
 * created: 21.12.2014
 * changed: 21.12.2014
 *
 * purpose:
 *
 */

/**
 * first: include the basic-start
 */
require_once(__DIR__."/../lib/baseStart.php");

/**
 * now check the ini-settings
 */
$debugOK    = ($_SESSION['_ENV']['DEBUG'] ? " checked=\"checked\"" : "");
$debugKO    = (!$_SESSION['_ENV']['DEBUG'] ? " checked=\"checked\"" : "");
$debugLevel = $_SESSION['_ENV']['DEBUGLEVEL'];
$uploadOK   = ($_SESSION['_ENV']['UPLOAD'] ? " checked=\"checked\"" : "");
$uploadKO   = (!$_SESSION['_ENV']['UPLOAD'] ? " checked=\"checked\"" : "");
$uploadSize = $_SESSION['_ENV']['UPLOAD_SIZE'];

$repo = $base->getUpdateChecker()->querySystemRepository();
?>
<style>
.ui-tabs-vertical { width: 99.5%; }
.ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 6em; }
.ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
.ui-tabs-vertical .ui-tabs-nav li a { display:block; }
.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; }
.ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width:90%;}
</style>
<script src="lib/js/environment.js" type="text/javascript"></script>
<script>
var systemEditable = false;
$(document).ready(function() {
    $('#radio2').buttonset();
    $('#repositorytabsId').tabs();
    $( "#repositorytabsId li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    $('#cancelSystemRepoButtonId').hide();
    $('.editable').each(function() { $(this).hide(); });
    $('.readOnly').each(function() { $(this).show(); });
    $('#systemSettingsContentId').load('environment/systemSettings.inc.php');
});

function showModRepoDetails(sessId) {
    var selectedEntry = $('#selectedModuleRepositoryId option:selected').val();
    if($('#selectedModuleRepositoryId option').length > 1) {
        $('#removeRepositoryButtonId').prop('disabled', false);
    }
    $('#repoContentId').load('environment/moduleRepositoryDetails.inc.php?' + sessId + '&selectedEntry=' + selectedEntry);
}

function checkIfChecked(idOfField, checkFieldId) {
    if($('#' + checkFieldId).is(':checked')) {
        $('#' + idOfField).prop('disabled', false);
    } else {
        $('#' + idOfField).prop('disabled', true);
    }
}

function toggleSystemAuthFields() {
    if($('#systemRepoIsAuthValueId').is(':checked')) {
        $('#systemRepoAuthFuncValueId').removeClass('ui-state-disabled').prop('diabled', false);
        $('#systemRepoUserValueId').removeClass('ui-state-disabled').prop('diabled', false);
        $('#systemRepoPass1ValueId').removeClass('ui-state-disabled').prop('diabled', false);
        $('#systemRepoPass2ValueId').removeClass('ui-state-disabled').prop('diabled', false);
    } else {
        $('#systemRepoAuthFuncValueId').addClass('ui-state-disabled').prop('diabled', true);
        $('#systemRepoUserValueId').addClass('ui-state-disabled').prop('diabled', true);
        $('#systemRepoPass1ValueId').addClass('ui-state-disabled').prop('diabled', true);
        $('#systemRepoPass2ValueId').addClass('ui-state-disabled').prop('diabled', true);
    }
}

function toggleSystemEditing(flag, sessId) {
    if(!systemEditable) {
        $('.readOnly').each(function() { $(this).hide(); });
        $('.editable').each(function() { $(this).show(); });
        var $onOffDiv = createOnOffDiv('systemRepoIsAuthValue', repoAuthIsAuthValue, 'toggleSystemAuthFields');
        $('#systemRepoIsAuthEditId').html('').html(createOnOffDiv('systemRepoIsAuthValue', repoAuthIsAuthValue, 'toggleSystemAuthFields'));
        var repoURLValue          = $('#systemRepoURLId').html();
        var repoAuthIsAuthValue   = $('#systemRepoIsAuthId').html().substring(0,3) == "Yes";
        var repoAuthAuthFuncValue = $('#systemRepoAuthFuncId').html();
        var repoUserValue         = $('#systemRepoUserId').html();
        $('#modSystemRepoButtonId').hide();
        $('#saveSystemRepoButtonId').show().prop("disabled", false).removeClass("ui-state-disabled");
        $('#cancelSystemRepoButtonId').show();
        systemEditable = true;
    } else {
        systemEditable = false;
        $('.readOnly').each(function() { $(this).show(); });
        $('.editable').each(function() { $(this).hide(); });
        var repoURLValue     = $('#systemRepoURLValueId').val();
        var repoAuthIsAuth   = $('#systemRepoIsAuthValueId').is(':checked');
        var repoAuthAuthFunc = $('#systemRepoAuthFuncValueId').val();
        var repoUserValue    = $('#systemRepoUserValueId').val();
        var pass1Value       = $('#systemRepoPass1ValueId').val();
        var pass2Value       = $('#systemRepoPass2ValueId').val();
        if(pass1Value == pass2Value && flag) {
            if(repoAuthIsAuth && pass1Value.length == 0) {
                alert('No Password given');
            } else if(repoAuthIsAuth && repoAuthAuthFunc.length == 0) {
                alert('No Name for the Authfunction given');
            } else {
                var formData = $('input,textarea,select').serialize();
                var baseURL = 'environment/saveRepositories.inc.php?<?php echo SID; ?>';
                $.ajax({
                    url: baseURL + "&isSystem=true",
                    method: "POST",
                    data: formData,
                    success: function(data) {
                        alert("saved");
                    }
                });
            }
        } else if(!flag) {
            console.log("Action cancelled");
        } else {
            if(pass1Value.length == 0) { alert('No Password given'); }
            else { alert('Passwords are different'); }
        }
        $('#systemRepoURLId').html(repoURLValue);
        $('#systemRepoIsAuthId').html((repoAuthIsAuth ? 'Yes' : 'No'));
        $('#systemRepoAuthFuncId').html(repoAuthAuthFunc);
        $('#systemRepoUserId').html(repoUserValue);
        if(pass1Value.length == 0 && flag) {
            $('#systemRepoPassId').html('not set');
        } else {
            $('#systemRepoPassId').html('is set');
        }
        $('#modSystemRepoButtonId').show();
        $('#saveSystemRepoButtonId').prop("disabled", true).addClass("ui-state-disabled");
        $('#cancelSystemRepoButtonId').hide();
    }
}
function addRepository(sessId) {
    $('#dialog').hide();
    $('#dialog').dialog({ height: 'auto', width: 900});
    $('#dialog').dialog("widget").find(".ui-dialog-titlebar").remove();
    $('#dialog').dialog("widget").css({border: "2px solid #1c94c4" });
    $('#dialog').load('environment/addModuleRepository.inc.php?' + sessId);
    $('#dialog').show();
}

function removeRepository(sessId) {
    var selectedEntry = $('#selectedModuleRepositoryId option:selected').val();
    $.ajax({
        url: "environment/saveRepositories.inc.php?" + sessId + '&deleteId=' + selectedEntry,
        method: "POST",
        success: function(data) {
            $('#selectedModuleRepositoryId').empty();
            $.ajax({
                url: 'environment/readModuleRepositories.inc.php?' + sessId,
                method: "POST",
                success: function(data) {
                    var tokens = data.split("|");
                    for(var i = 0; i < tokens.length; i++) {
                        var modTokens = tokens[i].split(",");
                        $('#selectedModuleRepositoryId').append($('<option></option>').val(modTokens[0]).text(modTokens[1]));
                    }
                    if($('#selectedModuleRepositoryId option').length == 1) {
                        $('#removeRepositoryButtonId').prop("disabled", true);
                    }
                }
            });
        }
    });
}

</script>
<div class="table" style="width:100%!important;margin-left:auto;margin-right:auto;">
    <div class="trow">
        <div class="tcell100 h40 ui-widget-header f12b calign">Available System-Settings</div>
    </div>
    <div class="trow">
        <div class="tcell100 h40 ui-widget-header f12b calign">
            <div id="systemSettingsContentId" style="width:100%;height:auto"></div>
        </div>
    </div>
</div>
<div class="table" style="width:100%!important;margin-left:auto;margin-right:auto;">
    <div class="trow">
        <div class="tcell100 h40 ui-widget-header f12b calign">Available Repositories</div>
    </div>
   <div class="trow">
        <div class="tcell100" style="vertical-align:top;width:90%;">
            <div id="repositorytabsId">
                <ul>
                    <li><a href="#systemRepositoryId">System</a></li>
                    <li><a href="#moduleRepositoryId">Module</a></li>
                </ul>
                <div id="systemRepositoryId">
                    <div class="table100">
                        <div class="trow">
                            <div class="tcell" style="vertical-align:top;width:80%;">
                                <div class="table" style="width:100%">
                                    <div class="trow">
                                        <div class="tcell50 h40 ui-state-default ui-corner-tl lalign">Main-System-Repository</div>
                                        <div class="tcell50 h40 ui-state-default ui-corner-tr">
                                            <div class="table">
                                                <div class="trow">
                                                    <div class="tcell">
                                                        <button class="defaultButton" disabled="disabled" id="saveSystemRepoButtonId" onclick="toggleSystemEditing(true, '<?php echo SID; ?>');">
                                                            <img src="images/16x16/disk.png"><br>Save
                                                        </button>
                                                        <button class="defaultButton" id="modSystemRepoButtonId" onclick="toggleSystemEditing(true, '<?php echo SID; ?>');">
                                                            <img src="images/16x16/wrench.png"><br>Modify
                                                        </button>
                                                        <button class="defaultButton" id="cancelSystemRepoButtonId" onclick="toggleSystemEditing(false, '<?php echo SID; ?>');">
                                                            <img src="images/16x16/cancel.png"><br>Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="trow">
                                        <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">URL:</div>
                                        <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                            <div class="readOnly" id="systemRepoURLId"><?php echo $repo['system']['repository']; ?></div>
                                            <div class="editable"><input type="text" style="width: 400px;" name="systemRepoURLValue" id="systemRepoURLValueId" value="<?php echo $repo['system']['repository']; ?>"></div>
                                        </div>
                                    </div>
                                    <div class="trow">
                                        <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Auth:</div>
                                        <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                            <div class="readOnly" id="systemRepoIsAuthId"><?php echo $repo['system']['auth'] == "true" ? "Yes" : "No"; ?></div>
                                            <div class="editable"><div id="systemRepoIsAuthEditId"></div></div>
                                        </div>
                                    </div>
                                    <div class="trow">
                                        <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Authfunc:</div>
                                        <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                            <div class="readOnly" id="systemRepoAuthFuncId"><?php echo $repo['system']['authfunc']; ?></div>
                                            <div class="editable"><input type="text" name="systemRepoAuthFuncValue" id="systemRepoAuthFuncValueId" value="<?php echo $repo['system']['authfunc']; ?>"></div>
                                        </div>
                                    </div>
                                    <div class="trow">
                                        <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">User:</div>
                                        <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                            <div class="readOnly" id="systemRepoUserId"><?php echo $repo['system']['username']; ?></div>
                                            <div class="editable"><input type="text" name="systemRepoUserValue" id="systemRepoUserValueId" value="<?php echo $repo['system']['username']; ?>"></div>
                                        </div>
                                    </div>
                                    <div class="trow">
                                        <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Pass:</div>
                                        <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                            <div class="readOnly" id="systemRepoPassId"><?php echo $repo['system']['password'] != "" ? "is set" : "is not set"; ?></div>
                                            <div class="editable">
                                                <div class="table">
                                                    <div class="trow">
                                                        <div class="tcell"><input type="password" name="systemRepoPass1Value" id="systemRepoPass1ValueId" value="" placeholder="The Password"></div>'
                                                        <div class="tcell"><input type="password" name="systemRepoPass2Value" id="systemRepoPass2ValueId" value="" placeholder="The Password for Control"></div>
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
                <div id="moduleRepositoryId">
                    <div class="table100">
                        <div class="trow">
                            <div class="tcell25 vtop">
                               <div class="table100 ui-widget-content ui-corner-top">
                                    <div class="trow">
                                        <div class="tcell">
                                            <div class="table">
                                                <div class="trow">
                                                    <div class="tcell">
                                                        <button onClick="addRepository('<?php echo SID; ?>');" class="smallButton">
                                                            &nbsp;<span style="font-weight:bold;">+</span>&nbsp;
                                                        </button>
                                                    </div>
                                                    <div class="tcell">
                                                        <button onClick="removeRepository('<?php echo SID; ?>');" id="removeRepositoryButtonId" disabled="disabled" class="smallButton">
                                                            &nbsp;<span style="font-weight:bold;">-</span>&nbsp;
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <select name="selectedModuleRepository" id="selectedModuleRepositoryId" onClick="showModRepoDetails('<?php echo SID; ?>');" style="width:99%;margin:0 auto;height:208px;" size="10">
<?php
for($count = 0; $count < count($repo['modules']['modrepo']); $count++) {
?>
                                    <option class="optionentry" value="<?php echo $count; ?>"><?php echo $repo['modules']['reponame'][$count]; ?></option>
<?php
}
?>
                                </select>
                            </div>
                            <div class="tcell" style="width: 75%;vertical-align:top;text-align:left;">
                                <div id="repoContentId" style="width:99%;border:1px solid lightgrey;height:280px;overflow:auto;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
