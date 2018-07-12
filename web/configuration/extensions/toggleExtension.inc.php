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

$extHandler = $base->getExtensionClass();
if(!is_object($extHandler)) { echo "No Extension-Handler<br>"; }
else                        { echo "try to toggle Extension<br>"; $extHandler->toggleExtension($_GET); }
?>