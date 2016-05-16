<?php
/**
 *
 * installModule.php
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

echo "HIER";
echo "<pre>"; print_r($_GET); echo "</pre>";
$moduleHandler = $base->getModuleClass();
if(!is_object($moduleHandler)) { echo "No Module-Handler<br>"; }
else                           { $moduleHandler->installModule($_GET['selectedModuleId'], $_SESSION['AGENT']['REPO']['selectedModuleRepositoryIdent']); }
echo "HIER";
?>
