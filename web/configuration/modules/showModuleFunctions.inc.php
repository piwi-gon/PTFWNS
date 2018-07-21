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
<div class="table99">
    <div class="trow">
        <div class="tcell ui-widget-header ui-corner-top">Anzahl Funktionen: <?php echo count($modules['function']); ?></div>
    </div>
</div>
<div class="table99">
    <div class="trow">
        <div class="tcell ui-widget-header h40 vtop">Funktion</div>
        <div class="tcell ui-widget-header h40 vtop">Aufrufparamter</div>
    </div>
<?php
foreach($modules['function'] as $func) {
?>
    <div class="trow">
        <div class="tcell ui-widget-content h40 vtop"><?php echo $func['name']; ?></div>
        <div class="tcell ui-widget-content h40 vtop"><?php echo $func['param'][0] != "" ? join("<br>", $func['param']) : "keine"; ?></div>
    </div>
<?php
}
?>
</div>
<div class="table99">
    <div class="trow">
        <div class="tcell ui-widget-header ui-corner-bottom">&nbsp;</div>
    </div>
</div>
