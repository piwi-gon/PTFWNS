<?php
/**
 *
 * toggleExtension.inc.php
 *
 * author : piwi
 *
 * created: 16.07.2015
 * changed: 16.07.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");

$modHandler = $base->getModuleClass();
if(!is_object($modHandler)) { echo "No Extension-Handler<br>"; }
else                        { echo "try to toggle Module<br>"; $modHandler->toggleModule($_GET, $_SESSION['AGENT']['REPO']['selectedModuleRepositoryIdent']); }
?>