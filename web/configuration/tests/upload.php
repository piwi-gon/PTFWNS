<?php
include("sessionStart.php");
echo "</pre>"; print_r($_POST); echo "</pre>";
echo "</pre>"; print_r($_FILES); echo "</pre>";
$installExtension = $base->getInstallExtension();
$installExtension->installUpdateFile("cJQuery");
