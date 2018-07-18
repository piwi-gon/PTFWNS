<?php
/**
 *
 * phpinfo.php
 *
 * author : piwi
 *
 * created: 06.04.2015
 * changed: 06.04.2015
 *
 * purpose:
 *
 */

if($_GET['show']!="true") {
?>
<iframe src="phpinfo.php?show=true" style="width:99%;overflow:auto;margin:0 auto;border:0;height:800px;"></iframe>
<?php
} else {
?>
<div style="width:1200px;overflow:auto;">
<?php
phpinfo();
?>
</div>
<?php
}
?>