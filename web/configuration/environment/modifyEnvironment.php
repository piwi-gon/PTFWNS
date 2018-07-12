<?php
/**
 *
 * modifyEnvironment.php
 *
 * author : piwi
 *
 * created: 21.12.2014
 * changed: 21.12.2014
 *
 * purpose: modifier for the environment-config
 *          this could be in file or db
 *
 */

require_once(__DIR__."/../lib/baseStart.php");
$Config = $base->getConfiguration();
/*
 *  if the state is true the environment is successfully changed
 *  if not - no change was made
 */

if(!isset($_POST['DEBUG']))  { $_POST['DEBUG'] = "false";  }
if(!isset($_POST['UPLOAD'])) { $_POST['UPLOAD'] = "false"; }
?>
<div class="table">
    <div class="trow">
<?php
if($Config->modifyEnvrionment($_POST)) {
?>
    <div class="tcell">change was successfull</div>
<?php
} else {
?>
    <div class="tcell">change failed</div>
<?php
}
?>
    </div>
</div>
<?php
