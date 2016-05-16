<?php
/**
 *
 * installExtension.php
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

echo "HIER";
echo "<pre>"; print_r($_GET); echo "</pre>";
echo "<pre>"; print_r($_POST); echo "</pre>";
$extHandler = $base->getExtensionClass();
if(!is_object($extHandler)) { echo "No Extension-Handler<br>"; }
else                        { $extHandler->installExtension($_GET); }
echo "HIER";
?>