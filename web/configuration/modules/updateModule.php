<?php
/**
 *
 * updateModule.php
 *
 * author : piwi
 *
 * created: 14.03.2015
 * changed: 14.03.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");

$imgCheck  = "images/16x16/accept.png";
$moduleHandler = $base->getModule();
if(!is_object($moduleHandler)) { echo "No Module-Handler<br>"; }
else {
    if($moduleHandler->installModuleByName($_GET['selectedModuleName'], $_SESSION['AGENT']['REPO']['selectedModuleRepositoryIdent'])) {
        $installInfo = $moduleHandler->getInstallInfo();
        echo $installInfo['version']."||<img src=\"" . $imgCheck . "\">";
    }
}
?>
