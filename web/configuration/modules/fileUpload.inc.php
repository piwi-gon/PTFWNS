<?php
/**
 *
 * fileUpload.inc.php
 *
 * author : piwi
 *
 * created: 24.04.2015
 * changed: 24.04.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");
ini_set("display_errors", 1);
move_uploaded_file($_FILES['file']['tmp_name'], INSTALL_UPLOADDIR.$_FILES['file']['name']);
?>
<div class="ui-button ui-state-default" onClick="performModuleInstall('modules/installModule.php?PHPSESSID=<?php echo session_id(); ?>&fileName='+encodeURI('<?php echo INSTALL_UPLOADDIR.$_FILES['file']['name']; ?>'));">Installation<br>ausführen</div>
<div id="installMonitorModuleId" style="width:850px;height:350px;border:1px solid black;overflow:auto"></div>