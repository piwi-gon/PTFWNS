<?php
/**
 *
 * systemRepoConfig.inc.php
 *
 * author : piwi
 *
 * created: 17.05.2015
 * changed: 17.05.2015
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
<div class="table100">
    <div class="trow">
        <div class="tcell ui-widget-content"><h3>Configuration System-Repository</h3></div>
    </div>
</div>
<div class="table100">
    <div class="trow">
        <div class="tcellH40 ui-state-default ui-corner-tl">Option</div>
        <div class="tcellH40 ui-state-default ui-corner-tr">
            <div class="table ui-widget-content" style="width:99%!important;border:0;">
                <div class="trow">
                    <div class="tcell ui-state-default" style="vertical-align:middle;text-align:center;width:99%!important;border:0;">Wert</div>
                    <div class="tcell" style="vertical-align:middle;text-align:right;width:99%!important;">
                        <div class="table">
                            <div class="trow">
                                <div class="tcell">
                                    <button class="ui-state-default ui-corner-all ui-state-disabled" disabled="disabled" id="cancelModuleRepoButtonId" onclick="toggleEditing(false);" style="width:120px;"><img id="modBUttonId" src="images/16x16/cancel.png"><br>Cancel</button>
                                </div>
                                <div class="tcell">
                                    <button class="ui-state-default ui-corner-all" id="modModuleRepoButtonId" onclick="toggleEditing(true);" style="width:120px;"><img id="modBUttonId" src="images/16x16/wrench.png"><br>Modify</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="trow">
        <div class="tcell ui-widget-content" style="height:40px;width:25%;vertical-align:middle;">URL:</div>
        <div class="tcell ui-widget-content" style="height:40px;width:75%;vertical-align:middle;">
            <input type="text" name="systemRepoURL" id="systemRepoURLId" value="<?php echo $repo['system']['repository']; ?>">
        </div>
    </div>
    <div class="trow">
        <div class="tcell ui-widget-content" style="height:40px;width:25%;vertical-align:middle;">Auth:</div>
        <div class="tcell ui-widget-content" style="height:40px;width:75%;vertical-align:middle;">
            <div class="table">
                <div class="trow">
                    <div class="tcell" style="width:10%;vertical-align:middle;">
                        <div class="onoffswitch_small" style="margin-left:auto;margin-right:auto;">
                            <input type="checkbox" name="systemAuthentication" id="systemAuthenticationId" value="true" <?php echo $repo['system']['auth'] == "true" ? "checked" : ""?>
                                   class="onoffswitch_small-checkbox">
                            <label class="onoffswitch_small-label" for="systemAuthenticationId">
                                <span class="onoffswitch_small-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="tcell" style="width:90%;">
                        Authfunction:&nbsp;<input type="text" name="systemAuthentication" id="systemAuthenticationId" value="<?php echo $repo['system']['authfunc']; ?>" <?php echo $repo['system']['auth'] == "true" ? "" : "disabled=\"disabled\""?>>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="trow">
        <div class="tcell ui-widget-content" style="height:40px;width:25%;vertical-align:middle;">User:</div>
        <div class="tcell ui-widget-content" style="height:40px;width:75%;vertical-align:middle;">
            <input type="text" name="systemrepoUserName" id="systemUserNameId" value="<?php echo $repo['system']['username']; ?>">
        </div>
    </div>
    <div class="trow">
        <div class="tcell ui-widget-content" style="height:40px;width:25%;vertical-align:middle;">Pass:</div>
        <div class="tcell ui-widget-content" style="height:80px;width:75%;vertical-align:middle;">
            <input type="text" name="systemrepoUserPass1" id="systemUserPass1Id" value="" placeholder="please insert password"><br>
            <input type="text" name="systemrepoUserPass2" id="systemUserPass2Id" value="" placeholder="please insert password">
        </div>
    </div>
</div>