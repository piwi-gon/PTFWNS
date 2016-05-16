<?php
/**
 *
 * fileUpload.inc.php
 *
 * author : piwi
 *
 * created: 19.04.2015
 * changed: 19.04.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");
ini_set("display_errors", 1);
move_uploaded_file($_FILES['file']['tmp_name'], INSTALL_UPLOADDIR.$_FILES['file']['name']);
/*
$postKeys = array_keys($_POST);
for($count = 0; $count < count($postKeys); $count++) {
    $getAdd .= "&" . $postKeys[$count] . "=" . $_POST[$postKeys[$count]];
}
if($_GET['modify'] == true) {
?>
<button onClick="performExtensionModify('extensions/modifyExtension.php?PHPSESSID=<?php echo session_id().$getAdd; ?>&extension_id=<?php echo $_GET['extension_id']; ?>&fileName='+encodeURI('<?php echo INSTALL_UPLOADDIR.$_FILES['file']['name']; ?>'));">Installation<br>ausführen</button>
<div id="installMonitorExtensionId" style="width:650px;height:350px;border:1px solid black;overflow:auto"></div>
<?php
} else {
?>
<button onClick="performExtensionInstall('extensions/installExtension.php?PHPSESSID=<?php echo session_id().$getAdd; ?>&fileName='+encodeURI('<?php echo INSTALL_UPLOADDIR.$_FILES['file']['name']; ?>'));">Installation<br>ausführen</button>
<div id="installMonitorExtensionId" style="width:650px;height:350px;border:1px solid black;overflow:auto"></div>
<?php
}
*/
?>