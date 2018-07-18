#!/usr/bin/php -q
<?php
//-----------------------------------------------------------------------------
ini_set("display_errors", 1);
//-----------------------------------------------------------------------------
if(PHP_SAPI == "cli") {
//-----------------------------------------------------------------------------
    include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "clistart.inc.php");
    include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "cliMenu.inc.php");
    $oCLI = $base->getCLI();
    echo $oCLI->clearScreen();
    showMainMenu();
    exit();
//-----------------------------------------------------------------------------
} else {
//-----------------------------------------------------------------------------
    header("Location: configuration/index.php");
//-----------------------------------------------------------------------------
}
//-----------------------------------------------------------------------------
?>