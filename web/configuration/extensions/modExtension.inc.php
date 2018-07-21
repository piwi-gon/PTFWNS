<?php
require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");
$imgEdit   = "images/16x16/pencil.png";
?>
<div id="uploadExtensionContainerId">
<div class="table99">
    <div class="trow">
        <div class="tcell99 ui-widget-content h40 vtop">
            <div style="display:none">
                <input type="file" name="uploadExtensionFile" id="uploadExtensionFileId">
            </div>
            <img onClick="document.getElementById('uploadExtensionFileId').click();" src="<?php echo $imgEdit; ?>" border="0" style="float:left;">
            <div style="float:left;padding-left:20px;" id="extensionFileNameId"></div>
        </div>
    </div>
    <br><br>
    <div class="trow">
        <div class="tcell99 ui-widget-content h40 vtop">
            <input type="text" name="extensionName" id="extensionNameId"><br><label for="extensionNameId">(Basis-) Name der Klasse</label>
        </div>
    </div>
</div>
<div class="table99">
    <div class="trow">
        <div class="tcell50 ui-widget-content h40 vtop">
            <input type="text" name="extensionFile" id="extensionFileId"><br><label for="extensionFileId">Filename der Klasse</label>
        </div>
        <div class="tcell50 ui-widget-content h40 vtop">
            <input type="text" name="extensionVersion" id="extensionVersionId"><br><label for="extensionNameId">Version</label>
        </div>
    </div>
    <div class="trow">
        <div class="tcell50 ui-widget-content h40 vtop">
            <input type="text" name="extensionIdentifier" id="extensionIdentifierId"><br><label for="extensionNameId">Identifier</label>
        </div>
        <div class="tcell50 ui-widget-content h40 vtop">
            <input type="text" name="extensionPath" id="extensionPathId"><br><label for="extensionNameId">Installationspfad</label>
        </div>
    </div>
</div>
<div class="table99">
    <div class="trow">
        <div class="tcell99 ui-widget-content">
            <div class="ui-button ui-state-default ui-corner-all" style="padding:10px;" onClick="uploadFilesModify();">Upload</div>
        </div>
    </div>
</div>
<div id="resultUploadExtensionId"></div>
</div>