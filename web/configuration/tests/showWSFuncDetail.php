<?php
include("sessionStart.php");
ini_set("display_errors", 1);
$base->setLanguage("de_DE");
$sql = $base->getSQL();
$sql->makeNewConn("local");
$query  = "select * from t_ws_function_param as a ".
          "left join r_ws_function_function_param as b on a.ws_function_param_id = b.fk_ws_function_param_id ".
          "left join t_ws_var_type as c on a.fk_ws_var_type_id = c.ws_var_type_id ".
          "where fk_ws_function_id = '" . $_GET['selectedFunction'] . "'";
$result = $sql->makeQuery($query);
// echo "<pre>";
// print_r($result);
// echo "</pre>";
?>
<style>
.table { display:table; width:100%!important; }
.trow  { display:table-row; }
.tcell { display:table-cell; }
</style>
<div id="dialogWSDetails"></div>
<h3 style="margin-bottom: 2px;">Input</h3>
<div class="table">
    <div class="trow">
        <div class="tcell ui-widget-header">Nr</div>
        <div class="tcell ui-widget-header">Argument</div>
        <div class="tcell ui-widget-header">Beschreibung</div>
        <div class="tcell ui-widget-header">Art</div>
        <div class="tcell ui-widget-header">Min</div>
        <div class="tcell ui-widget-header">Max</div>
    </div>
    <?php for($count = 0; $count < count($result); $count++) { ?>
    <div class="trow">
        <div class="tcell ui-widget-content"><?php echo sprintf("%02d", ($count+1)); ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['argument_name']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['argument_desc']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['var_type_name']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['min_occur']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['max_occur']; ?></div>
    </div>
    <?php } ?>
</div>
<?php
$query  = "select * from t_return_ws_function_param as a " .
          "left join r_return_ws_function_function_param as b on a.return_ws_function_param_id = b.fk_return_ws_function_param_id " .
          "left join t_ws_var_type as c on a.fk_ws_var_type_id = c.ws_var_type_id  " .
          "where fk_ws_function_id = '" . $_GET['selectedFunction'] . "'";
$result = $sql->makeQuery($query);
?>
<h3 style="margin-bottom: 2px;">Output</h3>
<div class="table">
    <div class="trow">
        <div class="tcell ui-widget-header">Nr</div>
        <div class="tcell ui-widget-header">Argument</div>
        <div class="tcell ui-widget-header">Beschreibung</div>
        <div class="tcell ui-widget-header">Art</div>
        <div class="tcell ui-widget-header">Min</div>
        <div class="tcell ui-widget-header">Max</div>
    </div>
    <?php for($count = 0; $count < count($result); $count++) { ?>
    <div class="trow">
        <div class="tcell ui-widget-content"><?php echo sprintf("%02d", ($count+1)); ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['argument_name']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['argument_desc']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['var_type_name']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['min_occur']; ?></div>
        <div class="tcell ui-widget-content"><?php echo $result[$count]['max_occur']; ?></div>
    </div>
    <?php } ?>
</div>
