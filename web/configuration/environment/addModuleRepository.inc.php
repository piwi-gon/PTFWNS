<?php
/**
 *
 * addModuleRepository.inc.php
 *
 * author : piwi
 *
 * created: 18.07.2015
 * changed: 18.07.2015
 *
 * purpose:
 *
 */


?>
<script>
var repoURLState = "";
function checkIfChecked(sessId, fieldId) {
    if($('#'+fieldId).is(':checked')) {
        $('#moduleAuthFuncId').prop('disabled', false);
        $('#moduleUserId').prop('disabled', false);
        $('#modulePassId').prop('disabled', false);
        $('#modulePassCtrlId').prop('disabled', false);
    } else {
        $('#moduleAuthFuncId').prop('disabled', true);
        $('#moduleUserId').prop('disabled', true);
        $('#modulePassId').prop('disabled', true);
        $('#modulePassCtrlId').prop('disabled', true);
    }
}

function checkServiceURLWSDL(sessId) {
    var formData = $('input').serialize();
    $.ajax({
        url: "environment/checkWSDL.inc.php?" + sessId,
        data: formData,
        method: "POST",
        success: function(data) {
                    $('#resultWSDLCheckId').html(data);
                    repoURLState = $.trim($(data).text());
                    checkInput();
                 }
    });
}
function checkInput() {
    if(repoURLState=="") { $('#resultWSDLCheckId').html(''); }
    var value = $('#serviceURLId').val();
    if(value != undefined && value.length > 20) {
        $('#checkWSDLButtonId').prop('disabled', false).removeClass("ui-state-disabled");
    } else {
        $('#checkWSDLButtonId').prop('disabled', true).addClass("ui-state-disabled");
    }
    var repoName = $('#repoNameId').val();
    if(repoURLState == "success" && repoName != undefined && repoName.length > 5) {
        $('#saveRepositoryButtonId').prop('disabled', false).removeClass("ui-state-disabled");
    } else {
        $('#saveRepositoryButtonId').prop('disabled', true).addClass("ui-state-disabled");
    }
}

function saveRepository(sessId) {
    var formData = $('input').serialize();
    $.ajax({
        url: "environment/saveRepositories.inc.php?" + sessId,
        method: "POST",
        data: formData,
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
                }
            });
        }
    });
}
</script>
<div class="table100">
    <div class="trow">
        <div class="tcell100 h40 ui-state-default ui-corner-top centered">New Repository</div>
    </div>
</div>
<div class="table100">
    <div class="trow">
        <div class="tcell30 h40 ui-widget-content lalign">Name of Repository</div>
        <div class="tcell70 h40 ui-widget-content lalign">
            <input type="text" name="repoName" id="repoNameId" placeholder="The Reponame" onChange="checkInput();">&nbsp;(min. 5 chars)
        </div>
    </div>
    <div class="trow">
        <div class="tcell30 h40 ui-widget-content lalign">Service-URL</div>
        <div class="tcell70 h40 ui-widget-content lalign">
            <div class="table100">
                <div class="trow">
                    <div class="tcell60 h40" style="width:60%;">
                        <input type="text" name="serviceURL" id="serviceURLId" style="width:300px;" placeholder="Insert Service-URL here" onChange="checkInput();">
                    </div>
                    <div class="tcell20 h40" style="width:20%;">
                        <button disabled="disabled" id="checkWSDLButtonId" title="check Service for WSDL" onClick="checkServiceURLWSDL('<?php echo SID; ?>')" class="smallButton">
                            <img src="images/16x16/bullet_go.png">
                        </button>
                    </div>
                    <div class="tcell20 h40" style="width:20%;">
                        <div id="resultWSDLCheckId">(not checked)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="trow">
        <div class="tcell30 h40 ui-widget-content lalign">Use Authentication</div>
        <div class="tcell70 h40 ui-widget-content lalign">
            <div class="onoffswitch_small" style="margin-left:auto;margin-right:auto;">
                <input onClick="checkIfChecked('AUTHENTICATION', this.id);" type="checkbox" name="AUTHENTICATION" class="onoffswitch_small-checkbox" id="AUTHENTICATIONId">
                <label class="onoffswitch_small-label" for="AUTHENTICATIONId">
                    <span class="onoffswitch_small-inner"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="trow">
        <div class="tcell30 h40 ui-widget-content lalign">Function for Authentication</div>
        <div class="tcell70 h40 ui-widget-content lalign">
            <input type="text" name="moduleAuthFunc" id="moduleAuthFuncId" placeholder="Authfunction to be called" disabled="disabled">
        </div>
    </div>
    <div class="trow">
        <div class="tcell30 h40 ui-widget-content lalign">Username</div>
        <div class="tcell70 h40 ui-widget-content lalign">
            <input type="text" name="moduleUser" id="moduleUserId" placeholder="The Username" disabled="disabled">
        </div>
    </div>
    <div class="trow">
        <div class="tcell30 h40 ui-widget-content lalign">Password</div>
        <div class="tcell70 h40 ui-widget-content lalign">
            <input type="password" name="modulePass" id="modulePassId" placeholder="The Password" disabled="disabled"><input type="password" name="modulePassCtrl" id="modulePassCtrlId" placeholder="The Password for Control" disabled="disabled">
        </div>
    </div>
</div>
<div class="table" style="width:100%!important;">
    <div class="trow">
        <div class="tcell100 h40 ui-widget-content ui-corner-bottom ralign">
            <button style="line-height:12pt;width:100px;height:46px;" onClick="$('#dialog').dialog('destroy');">
                <img src="images/16x16/cancel.png"><br>Cancel
            </button>
            <button disabled="disabled" style="line-height:12pt;width:100px;height:46px;" id="saveRepositoryButtonId" onClick="saveRepository('<?php echo SID; ?>');">
                <img src="images/16x16/disk.png"><br>Save
            </button>
        </div>
    </div>
</div>
