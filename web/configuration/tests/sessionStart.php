<?php
/**
 *
 * sessionStart.php
 *
 * author : piwi
 *
 * created: 07.12.2014
 * changed: 07.12.2014
 *
 * purpose:
 *
 */

// start session
session_start();
// set timezone (necessary in PHP >= 5.4)
date_default_timezone_set("Europe/Berlin");
// set actual FW-Version
// get the location of frameweork
$shareDir = "/usr". DIRECTORY_SEPARATOR . "share". DIRECTORY_SEPARATOR . "php". DIRECTORY_SEPARATOR . "ptfw". DIRECTORY_SEPARATOR;
define("BASE_DIR", $shareDir);

// ste the include-path to it
set_include_path(get_include_path() . PATH_SEPARATOR . BASE_DIR);
$_SESSION['AGENT']['LOADED'] = "true";
error_reporting(E_ALL&~E_NOTICE);
ini_set("display_errors", 1);

// load base-class
require_once("cBase.inc.php");
$base = new cBase();
if(!is_object($base)) { echo "Start failed - No base-class found - exiting..." . (PHP_SAPI !== "cli"?"<br>":"\n"); exit(); }

// initial set of memory-limit
ini_set("memory_limit", "512M");

// set language to de_DE
$base->getDebug()->deb("ty to load language-file", "MSG", 2);
$base->setLanguage("de_DE");

// function determineShareDir() {
//     $sharePathes = array("php", "php/PEAR", "pear");
//     for($count = 0; $count < count($sharePathes); $count++) {
//         if(file_exists("/usr/share/" . $sharePathes[$count])) { return "/usr/share/" . $sharePathes[$count]; }
//     }
// }
?>