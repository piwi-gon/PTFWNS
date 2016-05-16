<?php
/**
 *
 * checkWSDL.inc.php
 *
 * author : piwi
 *
 * created: 19.07.2015
 * changed: 19.07.2015
 *
 * purpose:
 *
 */

try {
    $client = new SoapClient($_POST['serviceURL']."?wsdl", array('exceptions'=>false));
    if(is_object($client)) {
        $result = '<span style="background-color:darkgreen;color:white;font-weight:bold;">&nbsp;success&nbsp;</span>';
    }
} catch(SoapFault $e) {
    $result = '<span style="background-color:red;color:white;font-weight:bold;">&nbsp;failed&nbsp;</span>';
}
echo $result;
?>