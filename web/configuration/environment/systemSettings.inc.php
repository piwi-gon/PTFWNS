<?php
/**
 * systemSettings.inc.php
 *
 * author: klaus
 *
 * created: 11.07.2018
 * changed: 11.07.2018
 *
 */

/**
 * first: include the basic-start
 */
require_once(__DIR__."/../lib/baseStart.php");

$system['version_installed'] = $_SESSION['_ENV']['VERSION'];
$system['version_available'] = $base->getAvailableVersion();
/**
 * now check the ini-settings
 */
$debugOK    = ($_SESSION['_ENV']['DEBUG'] ? " checked=\"checked\"" : "");
$debugKO    = (!$_SESSION['_ENV']['DEBUG'] ? " checked=\"checked\"" : "");
$debugLevel = $_SESSION['_ENV']['DEBUGLEVEL'];
$uploadOK   = ($_SESSION['_ENV']['UPLOAD'] ? " checked=\"checked\"" : "");
$uploadKO   = (!$_SESSION['_ENV']['UPLOAD'] ? " checked=\"checked\"" : "");
$uploadSize = $_SESSION['_ENV']['UPLOAD_SIZE'];
?>
<div class="table99">
    <div class="trow">
        <div class="tcell50 ui-widget-content" style="height:40px;vertical-align:middle;">
            <div class="table99">
                <div class="trow">
                    <div class="tcell25 ui-widget-content" style="height:40px;vertical-align:middle;">DEBUG</div>
                    <div style="height:40px;text-align:center;vertical-align:middle;" class="tcell25 ui-widget-content">
                        <div class="onoffswitch" style="margin-left:auto;margin-right:auto;">
                            <input onClick="checkIfChecked('DEBUGLEVELID', this.id);" type="checkbox" name="DEBUG" class="onoffswitch-checkbox" id="DEBUGId" <?php echo $debugOK ? "checked" : ""; ?>>
                            <label class="onoffswitch-label" for="DEBUGId">
                                <span class="onoffswitch-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="tcell25 ui-widget-content" style="height:40px;vertical-align:middle;">DEBUGLEVEL</div>
                    <div style="height:40px;text-align:center;vertical-align:middle;" class="tcell25 ui-widget-content">
                        <input type="text" name="DEBUGLEVEL" id="DEBUGLEVELID" size=2 maxlength=2 width="20px;" value="<?php echo intval($debugLevel); ?>"<?php echo ($debugKO ? " disabled=\"disabled\"" : "") ?>>
                    </div>
                </div>
                <div class="trow">
                    <div class="tcell25 ui-widget-content" style="height:40px;vertical-align:middle;">UPLOAD</div>
                    <div style="height:40px;text-align:center;vertical-align:middle;" class="tcell25 ui-widget-content">
                        <div class="onoffswitch" style="margin-left:auto;margin-right:auto;">
                            <input onClick="checkIfChecked('UPLOAD_SIZEID', this.id);" type="checkbox" name="UPLOAD" class="onoffswitch-checkbox" id="UPLOADId" <?php echo $uploadOK ? "checked" : ""; ?>>
                            <label class="onoffswitch-label" for="UPLOADId">
                                <span class="onoffswitch-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="tcell25 ui-widget-content" style="height:40px;vertical-align:middle;">UPLOAD-SIZE</div>
                    <div style="height:40px;text-align:center;vertical-align:middle;" class="tcell25 ui-widget-content">
                        <input type="text" name="UPLOAD_SIZE" id="UPLOAD_SIZEID" size=2 maxlength=2 width="20px;" value="<?php echo intval($uploadSize); ?>"<?php echo ($uploadKO ? " disabled=\"disabled\"" : "") ?>>
                    </div>
                </div>
            </div>
            <div class="table99">
                <div class="trow">
                    <div class="tcell75 calign">
                        <div id="resultUpdateEnvironmentId"></div>
                    </div>
                    <div class="tcell25 ralign">
                        <button onclick="modifyEnvironment();">Aktualisieren</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="tcell50 ui-widget-content h40">
            <div class="table99">
                <div class="trow">
                    <div class="tcell30 h40">System-Version:</div>
                    <div class="tcell70 h40 lalign">
                        <?php echo $system['version_installed']; ?>
                    </div>
                </div>
            </div>
            <div class="table99">
                <div class="trow">
                    <div class="tcell30 h40">Available Version:</div>
                    <div class="tcell50 h40 lalign">
                        <?php echo $system['version_available']; ?>
                    </div>
                    <div class="tcell20 h40 ralign">
<?php
if(!$system['actual']) {
?>
                        <button onClick="updatePTFWSystem('<?php echo SID; ?>');" title="update to new Version">Aktualisieren</button>
<?php
} else {
?>
                        <button onClick="refreshPTFWSystem('<?php echo SID; ?>');" title="refresh installation">Erneuern</button>
<?php
}
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
