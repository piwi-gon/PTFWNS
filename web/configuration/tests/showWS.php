<?php
include("sessionStart.php");
ini_set("display_errors", 1);
$base->setLanguage("de_DE");
$sql = $base->getSQL();
$sql->makeNewConn("local");
$query  = "select * from t_webservice order by upper(service_name)";
$result = $sql->makeQuery($query);
echo "<h3 style=\"margin-bottom:1px;margin-top:2px;\">ServiceName: ".
     "<input type=\"text\" name=\"service_urn\" id=\"service_urnId\" value=\"" . $result[0]['service_name'] . "\"></h3>";
echo "<h3 style=\"margin-bottom:1px;margin-top:2px;\">Address: " .
     "<input type=\"text\" name=\"service_url\" id=\"service_urlId\" value=\"" . $result[0]['service_url'] . "\" size=\"60\"></h3>";

$query2 = "select * from r_webservice_ws_function as a ".
          "left join t_ws_function as b on a.fk_ws_function_id = b.ws_function_id ".
          "where fk_webservice_id = '1'";
$result2= $sql->makeQuery($query2);
?>
<style>
.table { display:table; width:100%!important; }
.trow  { display:table-row; }
.tcell { display:table-cell; }
</style>
<div id="dialogWSDetails"></div>
<div class="table">
    <div class="trow">
        <div class="tcell ui-widget-header">Nr</div>
        <div class="tcell ui-widget-header">Funktionsname</div>
        <div class="tcell ui-widget-header">Beschreibung</div>
        <div class="tcell ui-widget-header">PHP-Klasse</div>
        <div class="tcell ui-widget-header">Aktion</div>
    </div>
    <?php for($count = 0; $count < count($result2); $count++) { ?>
    <div class="trow">
        <div class="tcell ui-widget-content"><?php echo sprintf("%02d", ($count+1)); ?></div>
        <div class="tcell ui-widget-content"><?php echo $result2[$count]['function_name']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result2[$count]['function_desc']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result2[$count]['class_name']; ?></div>
        <div class="tcell ui-widget-content">
            <button class="ui-state-default ui-corner-all" style="padding:5px;"
                    onClick="$('#dialogWSDetails').hide();$('#dialogWSDetails').dialog({ height: 400, width: 600}).load('<?php echo dirname($_SERVER['PHP_SELF']); ?>/showWSFuncDetail.php?<?php echo session_name()."=".session_id();?>&selectedFunction=<?php echo $result2[$count]['ws_function_id'];?>');$('#dialogWSDetails').show();">
                        show Parameter
            </button>
        </div>
    </div>
    <?php } ?>
</div>
