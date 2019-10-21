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
session_start();

// get the location of frameweork
define("BASE_DIR", "/usr/share/pear/ptfwNS/");

// ste the include-path to it
set_include_path(get_include_path() . PATH_SEPARATOR . BASE_DIR);

// lets say, the session is loaded
$_SESSION['AGENT']['LOADED'] = "true";
error_reporting(E_ALL&~E_NOTICE);
ini_set("display_errors", 1);

// load base-class
require_once("cBase.inc.php");
$base = new PTFW\cBase();
if(!is_object($base)) { echo "Start failed - No base-class found - exiting..." . (PHP_SAPI !== "cli"?"<br>":"\n"); exit(); }
else { $base->getDebug()->deb("Base-class loaded"); }
$_psr4Object = $base->getPSRClassLoader();
$_psr4Object->buildPrefixesFromDir(_BASEDIR_.DS."base");
$_psr4Object->register();
?>