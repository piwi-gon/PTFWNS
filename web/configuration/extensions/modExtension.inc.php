<?php
require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");
$imgEdit   = "images/16x16/pencil.png";
?>
<div id="uploadExtensionContainerId">
<form action="" enctype="multipart/form-data" method="POST">
<div style="display:table;width:100%;">
    <div style="display:table-row;width:100%">
        <div style="display:table-cell;width:100%">
            <div style="display:none">
                <input type="file" name="uploadExtensionFile" id="uploadExtensionFileId">
            </div>
            <img onClick="document.getElementById('uploadExtensionFileId').click();" src="<?php echo $imgEdit; ?>" border="0" style="float:left;">
            <div style="float:left;padding-left:20px;" id="extensionFileNameId"></div>
        </div>
    </div>
    <br><br>
    <div style="display:table-row;width:100%;">
        <div style="display:table-cell;width:100%;">
            <input type="text" name="extensionName" id="extensionNameId"><br><label for="extensionNameId">(Basis-) Name der Klasse</label>
        </div>
    </div>
    <div style="display:table-row;width:100%;">
        <div style="display:table-cell;width:50%;">
            <input type="text" name="extensionFile" id="extensionFileId"><br><label for="extensionFileId">Filename der Klasse</label>
        </div>
        <div style="display:table-cell;width:50%;">
            <input type="text" name="extensionVersion" id="extensionVersionId"><br><label for="extensionNameId">Version</label>
        </div>
    </div>
    <div style="display:table-row;width:100%;">
        <div style="display:table-cell;width:50%;">
            <input type="text" name="extensionIdentifier" id="extensionIdentifierId"><br><label for="extensionNameId">Identifier</label>
        </div>
        <div style="display:table-cell;width:50%;">
            <input type="text" name="extensionPath" id="extensionPathId"><br><label for="extensionNameId">Installationspfad</label>
        </div>
    </div>
    <div style="display:table-row;width:100%">
        <div style="display:table-cell;width:100%">
            <div class="ui-button ui-state-default ui-corner-all" style="padding:10px;" onClick="uploadFilesModify();">Upload</div>
        </div>
    </div>
</div>
</form>
<div id="resultUploadExtensionId"></div>
</div>