<?php
/**
 *
 * soapClientTest.php
 *
 * author : piwi
 *
 * created: 22.01.2015
 * changed: 22.01.2015
 *
 * purpose:
 *
 */

include("sessionStart.php");
$WSDL = "http://localhost/ptfw/pear/base/configuration/tests/soapTest.php?wsdl";
$optionArray = array(
       "location" => "http://localhost/ptfw/pear/base/configuration/tests/soapTest.php",
       "uri" => "urn:myWebService",
       "trace" => 1
      );
?>
<style>
.table { display:table; width:100%!important; }
.trow  { display:table-row; }
.tcell { display:table-cell; }
</style>
<div id="dialogWS"></div>
<div class="table">
    <div class="trow">
        <div class="tcell">
        <button class="ui-corner-all ui-state-default"
           onClick="$('#dialogWS').hide();$('#dialogWS').dialog({ height: 500, width: 900}).load('<?php echo dirname($_SERVER['PHP_SELF']); ?>/showWS.php?<?php echo session_name()."=".session_id();?>');$('#dialogWS').show();">
           <div style="padding:5px;">List Webservices</div>
        </button>
        </div>
    </div>
</div>
<?php
$client = new SoapClient($WSDL, $optionArray);
$list = $client->__getFunctions();
echo "FunctionList:<pre>\n";
print_r($list);
echo "</pre>";
echo "ServerInfo:<br>";
try {
    $result = $client->getVersionExtended();
} catch(Exception $ex) {
    echo $ex->getMessage();
    echo "<pre>";
    echo "Request:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastRequest()))."\n";
    echo "Response:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastResponse()))."\n";
    echo "</pre>";
}
echo "<pre>";
echo "Request:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastRequest()))."\n";
echo "RequestHeader:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastRequestHeaders()))."\n";
echo "Response:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastResponse()))."\n";
echo "</pre>";
echo $result."<br>";
if(is_array($result)) { echo "<pre>"; print_r($result); echo "</pre>"; }
try {
    $result = $client->calculate(new SoapParam("add", "type"), new SoapParam(3, 'num1'), new SoapParam(9, 'num2'));
} catch(Exception $ex) {
    echo $ex->getMessage();
    echo "<pre>";
    echo "Request:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastRequest()))."\n";
    echo "Response:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastResponse()))."\n";
    echo "</pre>";
}
echo "Add: " . $result."<br>";
try {
    $result = $client->multiply(new SoapParam(3, 'num1'), new SoapParam(9, 'num2'));
} catch(Exception $ex) {
    echo $ex->getMessage();
    echo "<pre>";
    echo "Request:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastRequest()))."\n";
    echo "Response:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastResponse()))."\n";
    echo "</pre>";
}
echo "Multiply: " . $result."<br>";

try {
    $result = $client->getCustomerName(new SoapParam(1, "customerId"));
} catch(Exception $ex) {
    echo $ex->getMessage();
    echo "<pre>";
    echo "Request:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastRequest()))."\n";
    echo "Response:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastResponse()))."\n";
    echo "</pre>";
}
echo "CustomerName: " . $result."<br>";

try {
    $result = $client->queryCustomer(new SoapParam(1, "customerId"));
} catch(Exception $ex) {
    echo $ex->getMessage();
    echo "<pre>";
    echo "Request:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastRequest()))."\n";
    echo "Response:\n".htmlentities(str_replace("><", ">\n<", $client->__getLastResponse()))."\n";
    echo "</pre>";
}
echo "CustomerObject: " . $result."<br>";
if(is_array($result)) { echo "<pre>"; print_r($result); echo "</pre>"; }
?>