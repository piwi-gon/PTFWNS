<?php
/**
 *
 * baseStart.php
 *
 * author : piwi
 *
 * created: 06.12.2014
 * changed: 06.12.2014
 *
 * purpose: the start-script to include base
 *
 */

// start a session
if(isset($_COOKIE['PHPSESSID'])) {
    session_id($_COOKIE['PHPSESSID']);
}
session_set_cookie_params(180); // 3 minutes
session_start();
@define("SID",  session_name()."=".session_id());

// get the location of framework
$shareDir = "/usr/share/pear/ptfwNS/";
define("BASE_DIR", $shareDir);

// ste the include-path to it
set_include_path(get_include_path() . PATH_SEPARATOR . BASE_DIR);

// lets say, the session is loaded
$_SESSION['AGENT']['LOADED'] = "true";
error_reporting(E_ALL&~E_NOTICE);
ini_set("display_errors", 1);

// load base-class
require_once("cBase.inc.php");
$base = new \PTFW\cBase();
if(!is_object($base)) { echo "Start failed - No base-class found - exiting..." . (PHP_SAPI !== "cli"?"<br>":"\n"); exit(); }
else { $base->getDebug()->deb("Base-class loaded"); }
$base->getDebug()->deb("try to load language-file", "MSG", 2);
$base->setLanguage("de_DE");
/**
 * this is the connector to the repository where the modules are present
 */
$moduleConnector = $base->getModuleClass();
?>