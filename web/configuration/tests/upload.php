<?php
/**
 * upload.php
 *
 * author: klaus
 *
 * created: 19.07.2018
 * changed: 19.07.2018
 *
 */

include("sessionStart.php");
echo "</pre>"; print_r($_POST); echo "</pre>";
echo "</pre>"; print_r($_FILES); echo "</pre>";
$installExtension = $base->getInstallExtension();
$installExtension->installUpdateFile("cJQuery");
