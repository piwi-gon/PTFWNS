<?php
include("sessionStart.php");
ini_set("display_errors", 1);
$base->setLanguage("de_DE");
date_default_timezone_set("Europe/Berlin");
$soap = $base->getSimpleSoapServer();
$state = false;
if(isset($_GET['wsdl']) || isset($_GET['WSDL'])) { $state = true; }
$callURL = $_SERVER['PHP_SELF'];
if($state) {
    $soap->nusoapWSDLDB("myWebService", $callURL, $state);
} else {
    $soap->nusoapWSDLDB("myWebService", $callURL, $state);
}
?>
