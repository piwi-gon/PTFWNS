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

$repoConfigType="";

$repo = $base->getUpdateChecker()->querySystemRepository();
?>
<style>
label {
  display: block;
  cursor: pointer;
  line-height: 1;
  font-size: 1em;
}

[type="radio"] + span {
  display: block;
}

[type="radio"] {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: 0px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}

[type="radio"] + span:before {
  content: ' ';
  display: inline-block;
  width: 1em;
  height: 1em;
  vertical-align: 0;
  box-shadow: 0 0 1.5em 0 #000;
  margin-right: 1em;
  transition: 0.5s ease all;
}

[type="radio"]:checked + span:before {
  content: '\2713 ';
  box-shadow: 0 0 1.5em 0 #000;
}
</style>
<script src="lib/js/environment.js" type="text/javascript"></script>
<script>
var systemEditable = false;
$(document).ready(function() {
    $('#radio2').buttonset();
    $('#repositorytabsId').tabs();
    $( "#repositorytabsId li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    $('#cancelSystemRepoSOAPButtonId').hide();
    $('#cancelSystemRepoGITHUBButtonId').hide();
    $('.readOnly').each(function() { $(this).hide(); });
    $('.editable').each(function() { $(this).show(); });
    $('#systemRepoIsAuthEditId').html('').html(createOnOffDiv('systemRepoIsAuthValue', <?php echo $repo['system']['auth'] ? "true" : "false" ?>, 'toggleSystemAuthFields'));
    $('#systemRepoIsAuthGitHubEditId').html('').html(createOnOffDiv('systemRepoGitHubIsAuthValue', <?php echo $repo['system']['github_auth'] ? "true" : "false" ?>, 'toggleSystemAuthFields'));
    $('#systemSettingsContentId').load('environment/systemSettings.inc.php');
    $('#systemRepositoryConfigTypeContentId input').checkboxradio({icon:false});
    $('#systemRepositoryConfigTypeContentId').controlgroup();
    $('#githubRepositoryTpeButtonRowId input').checkboxradio({icon:false});
    $('#githubRepositoryTpeButtonRowId').controlgroup();
    $('#repoConfigTypeGITHUBContentId').hide();
    $('#repoConfigTypeSOAPContentId input,select').on('keypress', function() { $('#saveSystemRepoSOAPButtonId').prop('disabled', false); });
    $('#repoConfigTypeGITHUBContentId input,select').on('keypress', function() { $('#saveSystemRepoGITHUBButtonId').prop('disabled', false); });
});

function toggleRepoConfigType(theType) {
    if(theType == "GITHUB") {
        $('#repoConfigTypeSOAPContentId').hide();
        $('#repoConfigTypeGITHUBContentId').show();
    } else {
        $('#repoConfigTypeGITHUBContentId').hide();
        $('#repoConfigTypeSOAPContentId').show();
    }
}

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

function storeSystemRepositorySettings() {
    var activeRepoType = $('#activeRepositoryTypeSOAPId').is(':checked')?$('#activeRepositoryTypeSOAPId').val() : $('#activeRepositoryTypeGITHUBId').val();
    if(activeRepoType=="soap") {
        var formData = $('#repoConfigTypeSOAPContentId input,select,textarea').serialize();
        console.log('in SOAP');
        if($('#systemRepoIsAuthValueId').is(':checked')) {
            var pass1Value = $('#systemRepoPass1ValueId').val();
            var pass2Value = $('#systemRepoPass2ValueId').val();
            if(pass1Value == undefined || pass1Value == "") {
                alert('No Password is set');
            } else if(pass1Value != pass2Value) {
                alert('Password and Controlpassword are different');
            } else {
                alert(formData);
                _storeSystemRepoSettings(formData);

            }
        } else {
            alert(formData);
            _storeSystemRepoSettings(formData);
        }
    } else {
        console.log('in GITHUB');
        var formData = $('#repoConfigTypeGITHUBContentId input,select,textarea').serialize();
        if($('#systemRepoGitHubIsAuthValueId').is(':checked')) {
            var deployKey = $('#systemRepoDeployKeyValueId').val();
            if(deployKey == undefined || deployKey == "") {
                alert('No DeployKey is set');
            } else {
                alert(formData);
                _storeSystemRepoSettings(formData);
            }
        } else {
            alert(formData);
            _storeSystemRepoSettings(formData);
        }
    }
}

function _storeSystemRepoSettings(formData) {
    $.ajax({
        url: "environment/saveRepositories.php",
        data: formData,
        type: 'POST',
        success: function(data) {
            alert(data);
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
        <div class="tcell90 vtop">
            <div id="repositorytabsId">
                <ul>
                    <li><a href="#systemRepositoryId">System</a></li>
                    <li><a href="#moduleRepositoryId">Module</a></li>
                </ul>
                <div id="systemRepositoryId">
                    <div id="systemConfigRepositoryId">
                        <div id="systemRepositoryConfigTypeContentId">
                            <input type="radio" name="repoConfigType" onClick="toggleRepoConfigType('SOAP');" id="repoConfigTypeSOAPId" value="soap"<?php echo ($repoConfigType!="github") ? " checked=\"checked\"":""?>>
                            <label for="repoConfigTypeSOAPId">SOAP</label>
                            <input type="radio" name="repoConfigType" onClick="toggleRepoConfigType('GITHUB');" id="repoConfigTypeGITHUBId" value="github"<?php echo ($repoConfigType=="github") ? " checked=\"checked\"":""?>>
                            <label for="repoConfigTypeGITHUBId">GitHub</label>
                        </div>
                        <div id="repoConfigTypeSOAPContentId">
                            <div class="table99">
                                <div class="trow">
                                    <div class="tcell99 vtop">
                                        <div class="table99">
                                            <div class="trow">
                                                <div class="tcell50 h40 ui-state-default ui-corner-tl lalign">System-SOAP-Repository</div>
                                                <div class="tcell50 h40 ui-state-default ui-corner-tr lalign">
                                                    <div class="table99">
                                                        <div class="trow">
                                                            <div class="tcell70">
                                                                <button class="defaultButton" disabled="disabled" id="saveSystemRepoSOAPButtonId" onClick="storeSystemRepositorySettings();">
                                                                    <img src="images/16x16/disk.png"><br>Save
                                                                </button>
                                                            </div>
                                                            <div class="tcell30 ralign" style="vertical-align: middle;">
                                                                <label for="activeRepositoryTypeSOAPId">
                                                                    <input type="radio" name="activeRepositoryType" id="activeRepositoryTypeSOAPId" value="soap"<?php echo ($repo['system']['active_repo_type'] == "soap" ? " checked=\"checked\"" : ""); ?>>
                                                                    <span>Aktiv</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table99">
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign">URL:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign">
                                                    <div class="editable"><input type="text" style="width: 500px;" name="systemRepoURLValue" id="systemRepoURLValueId" value="<?php echo $repo['system']['repository']; ?>"></div>
                                                </div>
                                            </div>
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Auth:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                                    <div class="editable lalign">
                                                        <div id="systemRepoIsAuthEditId"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Authfunc:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                                    <div class="editable"><input type="text" name="systemRepoAuthFuncValue" id="systemRepoAuthFuncValueId" value="<?php echo $repo['system']['authfunc']; ?>"></div>
                                                </div>
                                            </div>
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">User:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                                    <div class="editable"><input type="text" name="systemRepoUserValue" id="systemRepoUserValueId" value="<?php echo $repo['system']['username']; ?>"></div>
                                                </div>
                                            </div>
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Delpoy-Key:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                                    <div class="editable">
                                                        <div class="table">
                                                            <div class="trow">
                                                                <div class="tcell"><input type="password" name="systemRepoPass1Value" id="systemRepoPass1ValueId" value="" placeholder="The Password"></div>&nbsp;&nbsp;-&nbsp;&nbsp;
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
                        <div id="repoConfigTypeGITHUBContentId">
                            <div class="table99">
                                <div class="trow">
                                    <div class="tcell99 vtop">
                                        <div class="table99">
                                            <div class="trow">
                                                <div class="tcell50 h40 ui-state-default ui-corner-tl lalign">
                                                    <div class="table99">
                                                        <div class="trow">
                                                            <div class="tcell80 h40">System-GitHub-Repository</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tcell50 h40 ui-state-default ui-corner-tr">
                                                    <div class="table99">
                                                        <div class="trow">
                                                            <div class="tcell80">
                                                                <button class="defaultButton" disabled="disabled" id="saveSystemRepoGITHUBButtonId" onClick="storeSystemRepositorySettings();">
                                                                    <img src="images/16x16/disk.png"><br>Save
                                                                </button>
                                                            </div>
                                                            <div class="tcell20 ralign" style="vertical-align: middle;">
                                                                <label for="activeRepositoryTypeGITHUBId">
                                                                    <input type="radio" name="activeRepositoryType" id="activeRepositoryTypeGITHUBId" value="github"<?php echo ($repo['system']['active_repo_type'] == "github" ? " checked=\"checked\"" : ""); ?>>
                                                                    <span>Aktiv</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table99">
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">User:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                                    <div class="editable"><input type="text" name="systemRepoUserGitHubValue" id="systemRepoUserGitHubValueId" value="<?php echo $repo['system']['github_username']; ?>"></div>
                                                </div>
                                            </div>
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Repositoryname:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                                    <div class="editable"><input type="text" style="width: 400px;" name="systemRepoURLGitHubValue" id="systemRepoURLGitHubValueId" value="<?php echo $repo['system']['github_repository']; ?>"></div>
                                                </div>
                                            </div>
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Which Type of Repo:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                                    <div class="editable">
                                                        <div id="githubRepositoryTpeButtonRowId">
                                                            <input type="radio" name="systemRepoGithubRepositoryTypeValue" id="githubRepositoryTpeReleaseValueId" value="release"<?php echo $repo['system']['github_repotype'] == "release" ? " checked=\"checked\"" : ""; ?>>
                                                            <label title="newest Release - if available" for="githubRepositoryTpeReleaseValueId">Release</label>
                                                            <input type="radio" name="systemRepoGithubRepositoryTypeValue" id="githubRepositoryTpeMasterValueId" value="master"<?php echo $repo['system']['github_repotype'] == "master" ? " checked=\"checked\"" : ""; ?>>
                                                            <label title="PreRelease-Version" for="githubRepositoryTpeMasterValueId">Master</label>
                                                            <input type="radio" name="systemRepoGithubRepositoryTypeValue" id="githubRepositoryTpeDevelValueId" value="devel"<?php echo $repo['system']['github_repotype'] == "devel" ? " checked=\"checked\"" : ""; ?>>
                                                            <label title="Developmentversion - do not use in Production!" for="githubRepositoryTpeDevelValueId">Development</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Auth:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                                    <div class="editable">
                                                        <div id="systemRepoIsAuthGitHubEditId"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="trow">
                                                <div class="tcell25 h40 ui-widget-content lalign" style="width:25%;">Pass:</div>
                                                <div class="tcell75 h40 ui-widget-content lalign" style="width:75%;">
                                                    <div class="editable">
                                                        <div class="table">
                                                            <div class="trow">
                                                                <div class="tcell"><input type="password" name="systemRepoDeployKeyValue" id="systemRepoDeployKeyValueId" value="" placeholder="The Deploy-Key"></div>
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
