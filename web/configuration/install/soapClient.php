<?php
/**
 *
 * soapClient.php
 *
 * author : piwi
 *
 * created: 03.01.2015
 * changed: 03.01.2015
 *
 * purpose: this is a standalone soap-client which fetches the
 *          given archive and stores in /tmp-directory
 *
 */

$moduleName = $_GET['moduleName'];

$url = "http://localhost/ptfwmanager/webservice/server.php";

$soapClient = new SoapClient(null, array("location"=>$url, "uri"=>$url, "trace"=> 1, "exception"=>0));

$header = "";

$soapParams[] = new SoapParam($moduleName, "moduleName");
try {
    $result = $soapClient->__call("getModule", $soapParams);
    echo "success";
} catch(Exception $ex) {
    print_r($result);
    echo "failure";
}
?>