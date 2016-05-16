<?php
/**
 *
 * showModuleFunctions.inc.php
 *
 * author : piwi
 *
 * created: 27.10.2015
 * changed: 27.10.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
// maybe this could be in baseStart ... next time
require_once("../lib/uploadConstants.php");

$moduleName = "get".str_replace(array(".inc.php", ".php", ".inc"), "", substr($_GET['selectedModule'],1));
echo "Functions for selected Module '" . $_GET['selectedModule'] . "' as '" . $moduleName . "':<br>";
$module = $base->$moduleName();

$rc = new ReflectionClass(str_replace(".inc.php", "", $_GET['selectedModule']));
$methods = $rc->getMethods();
foreach($methods as $method) {
    $funcArray['name'] = $method->getName();
    unset($parameters);
    $params = $method->getParameters();
    for($pCount = 0; $pCount < count($params); $pCount++) {
        $parameters[] = $params[$pCount]->getName(); // . " (" . $params[$pCount]->getClass()->name. ")";
    }
    $funcArray['param'] = $parameters;
    $modules['function'][] = $funcArray;
}
?>
<style>
.table { display: table; width:90%!important; }
</style>
<div class="table">
    <div class="table-row">
        <div class="table-cell ui-widget-header ui-corner-top">Anzahl Funktionen: <?php echo count($modules['function']); ?></div>
    </div>
</div>
<div class="table">
    <div class="table-row">
        <div class="table-cell ui-widget-header">Funktion</div>
        <div class="table-cell ui-widget-header">Aufrufparamter</div>
    </div>
<?php
for($count = 0; $count < count($modules['function']); $count++) {
    $class="ui-widget-content";
    if((($count+1)%2)==0) { $class="ui-widget-content-alt"; }
?>
    <div class="table-row">
        <div class="table-cell <?php echo $class; ?>"><?php echo $modules['function'][$count]['name']; ?></div>
        <div class="table-cell <?php echo $class; ?>"><?php echo $modules['function'][$count]['param'][0] != "" ? join("<br>", $modules['function'][$count]['param']) : "keine"; ?></div>
    </div>
<?php
}
?>
</div>
<div class="table">
    <div class="table-row">
        <div class="table-cell ui-widget-header ui-corner-bottom">&nbsp;</div>
    </div>
</div>
