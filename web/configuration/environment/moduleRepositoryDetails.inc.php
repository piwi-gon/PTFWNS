<?php
/**
 *
 * moduleRepositoryDetails.inc.php
 *
 * author : piwi
 *
 * created: 18.05.2015
 * changed: 18.05.2015
 *
 * purpose:
 *
 */

/**
 * first: include the basic-start
 */
require_once(__DIR__."/../lib/baseStart.php");

$repo = $base->getUpdateChecker()->querySystemRepository();
?>
<script>
$(document).ready(function() {
    $('#cancelModuleRepoButtonId').hide();
});

var editable = false;
function toggleAuthFields() {
    if($('#repoAuthIsAuthValueId').is(':checked')) {
        $('#repoAuthAuthFuncValueId').removeClass('ui-state-disabled').prop('diabled', false);
        $('#repoUserValueId').removeClass('ui-state-disabled').prop('diabled', false);
        $('#repoPass1ValueId').removeClass('ui-state-disabled').prop('diabled', false);
        $('#repoPass2ValueId').removeClass('ui-state-disabled').prop('diabled', false);
    } else {
        $('#repoAuthAuthFuncValueId').addClass('ui-state-disabled').prop('diabled', true);
        $('#repoUserValueId').addClass('ui-state-disabled').prop('diabled', true);
        $('#repoPass1ValueId').addClass('ui-state-disabled').prop('diabled', true);
        $('#repoPass2ValueId').addClass('ui-state-disabled').prop('diabled', true);
    }
}

function toggleEditing(flag) {
    if(!editable) {
        var repoURLValue          = $('#repoURLId').html();
        var repoAuthIsAuthValue   = $('#repoAuthIsAuth').html().substring(0,3) == "Yes";
        var repoAuthAuthFuncValue = $('#repoAuthAuthFunc').html();
        var repoUserValue         = $('#repoUserId').html();
        console.log("String: " + $('#repoAuthIsAuth').html() + " (" + repoAuthIsAuthValue + ")");
        $('#repoURLId').html('').html('<input type="text" name="repoURLValue" style="width:400px;" id="repoURLValueId" value="' + repoURLValue + '">');
        $('#systemRepoIsAuthId').html('').html(createOnOffDiv('repoAuthIsAuthValue', repoAuthIsAuthValue, 'toggleAuthFields'));
        $('#repoAuthAuthFunc').html('').html('<input type="text" name="repoAuthAuthFuncValue" id="repoAuthAuthFuncValueId" value="' + repoAuthAuthFuncValue + '">');
        $('#repoUserId').html('').html('<input type="text" name="repoUserValue" id="repoUserValueId" value="' + repoUserValue + '">');
        $('#repoPassId').html('').html('<div class="table" style="margin:0 auto;"><div class="trow">'+
                '<div class="tcell"><input type="password" name="repoPass1Value" id="repoPass1ValueId" value="" placeholder="The Password"></div>' +
                '<div class="tcell"><input type="password" name="repoPass2Value" id="repoPass2ValueId" value="" placeholder="The Password for Control"></div>'+
                '</div></div>');
        $('#modModuleRepoButtonId').hide();
        $('#cancelModuleRepoButtonId').show();
        $('#saveModuleRepoButtonId').removeClass("ui-state-disabled").prop("disabled", false);
        editable = true;
    } else {
        editable = false;
        var repoURLValue     = $('#repoURLValueId').val();
        var repoAuthIsAuth   = $('#repoAuthIsAuthValueId').is(':checked');
        var repoAuthAuthFunc = $('#repoAuthAuthFuncValueId').val();
        var repoUserValue    = $('#repoUserValueId').val();
        var pass1Value       = $('#repoPass1ValueId').val();
        var pass2Value       = $('#repoPass2ValueId').val();
        if(pass1Value == pass2Value && pass1Value.length > 0 && flag) {
            alert('saving');
        } else if(!flag) {
            console.log('action cancelled');
        } else {
            if(pass1Value.length == 0) { alert('No Password given'); }
            else { alert('Passwords are different'); }
        }
        $('#repoURLId').html('').append(repoURLValue);
        $('#repoAuthIsAuth').html('').append((repoAuthIsAuth ? 'Yes' : 'No'));
        $('#repoAuthAuthFunc').html('').append(repoAuthAuthFunc);
        $('#repoUserId').html('').append(repoUserValue);
        if(pass1Value.length == 0 && flag) {
            $('#repoPassId').html('not set');
        } else {
            $('#repoPassId').html('is set');
        }
        $('#modModuleRepoButtonId').show();
        $('#cancelModuleRepoButtonId').hide();
        $('#saveModuleRepoButtonId').addClass("ui-state-disabled").prop("disabled", true);
    }
}
</script>
<div class="table ui-widget-content" style="width:99%;">
    <div class="trow">
        <div class="tcellH40 ui-state-default ui-corner-top">
            <div class="table ui-widget-content" style="width:99%!important;border:0;">
                <div class="trow">
                    <div class="tcell ui-state-default" style="vertical-align:middle;text-align:left;width:99%!important;border:0;">
                        Selected Repository: <?php echo $repo['modules']['reponame'][$_GET ['selectedEntry']]; ?>
                    </div>
                    <div class="tcell" style="vertical-align:middle;text-align:right;width:99%!important;">
                        <div class="table">
                            <div class="trow">
                                <div class="tcell">
                                    <button class="ui-state-default ui-corner-all ui-state-disabled" disabled="disabled" id="saveModuleRepoButtonId" onclick="toggleEditing(true);" style="width:120px;"><img id="saveButtonId" src="images/16x16/disk.png"><br>Save</button>
                                </div>
                                <div class="tcell">
                                    <button class="ui-state-default ui-corner-all" id="modModuleRepoButtonId" onclick="toggleEditing(true);" style="width:120px;"><img id="modButtonId" src="images/16x16/wrench.png"><br>Modify</button>
                                    <button class="ui-state-default ui-corner-all" id="cancelModuleRepoButtonId" onclick="toggleEditing(false);" style="width:120px;"><img id="modButtonId" src="images/16x16/cancel.png"><br>Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table ui-widget-content" style="width:99%;">
    <div class="trow">
        <div class="tcellH40 ui-widget-content" style="width:25%;">URL:</div>
        <div class="tcellH40 ui-widget-content" style="width:75%;"><div id="repoURLId"><?php echo $repo['modules']['modrepo'][$_GET ['selectedEntry']]; ?></div></div>
    </div>
    <div class="trow">
        <div class="tcellH40 ui-widget-content" style="width:25%;">Auth:</div>
        <div class="tcellH40 ui-widget-content" style="width:75%;">
            <div id="repoAuthIsAuth"><?php echo $repo['modules']['auth'][$_GET ['selectedEntry']] == "true" ? "Yes"  : "No"; ?></div>
        </div>
    </div>
    <div class="trow">
        <div class="tcellH40 ui-widget-content" style="width:25%;">Authfunc:</div>
        <div class="tcellH40 ui-widget-content" style="width:75%;"><div id='repoAuthAuthFunc'><?php echo $repo['modules']['authfunc'][$_GET ['selectedEntry']]; ?></div></div>
    </div>

    <div class="trow">
        <div class="tcellH40 ui-widget-content" style="width:25%;">User:</div>
        <div class="tcellH40 ui-widget-content" style="width:75%;"><div id="repoUserId"><?php echo $repo['modules']['username'][$_GET ['selectedEntry']]; ?></div></div>
    </div>
    <div class="trow">
        <div class="tcellH40 ui-widget-content" style="width:25%;">Pass:</div>
        <div class="tcellH40 ui-widget-content" style="width:75%;"><div id="repoPassId"><?php echo $repo['modules']['password'][$_GET ['selectedEntry']] != "" ? "is set" : "is not set"; ?></div></div>
    </div>
</div>
