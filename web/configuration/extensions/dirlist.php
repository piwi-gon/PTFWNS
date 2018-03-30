<?php
/**
 *
 * dirlist.php
 *
 * author : piwi
 *
 * created: 29.10.2016
 * changed: 29.10.2016
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");
$parentShow = false;
?>
                    <div style="display:table;width:100%;">
<?php
if($_GET['workdir'] == "") {
    $workDir = INSTALL_WORKDIR;
} else {
    $workDir = $_GET['workdir'];
}
if($_GET['parentDirectory'] != "") {
    $workDir .= $_GET['parentDirectory'] . DS;
}
if($_GET['changedDirectory'] != "") {
    $workDir .= $_GET['changedDirectory'] . DS;
    $tokens = explode(DS, $_GET['changedDirectory']);
    for($dCount = 0; $dCount < count($tokens)-1; $dCount++) {
        $parentDir .= $tokens[$dCount];
    }
}
if($parentDir == INSTALL_WORKDIR) {
    $parentDir = "";
} else {
    $parentShow = true;
}
if(!strstr($workDir, INSTALL_WORKDIR)) {
    $workDir = INSTALL_WORKDIR;
}
if($parentShow) {
?>
                        <div class="trow" onDblClick="changeDirectory('<?php echo SID; ?>', encodeURI('<?php echo $parentDir; ?>'), true);">
                            <div class="tcell ui-widget-content h40" style="width:20%;text-align:center;"><img src="images/16x16/bullet_arrow_left.png"></div>
                            <div class="tcell ui-widget-content h40 f12b">..</div>
                        </div>
<?php
}
$files = @scandir($workDir);
for($count = 0; $count < count($files); $count++) {
    // check for the first directory -
    // its the installed directory normally
    if($files[$count] != ".." && $files[$count] != ".") {
        if(!is_dir($workDir . $files[$count])) {
                    ?>
                        <div class="trow" onClick="enableInstallButtonAndFillValues('<?php echo $iCount; ?>', '<?php echo $files[$count]; ?>');">
                            <div class="tcell ui-widget-content h40" style="width:20%;text-align:center;"><img src="images/16x16/page_white.png"></div>
                            <div class="tcell ui-widget-content h40 f12b"><?php echo $files[$count]; ?></div>
                        </div>
<?php
        } else{
?>
                        <div class="trow" onDblClick="changeDirectory('<?php echo SID; ?>', encodeURI('<?php echo str_replace(INSTALL_WORKDIR, "", $workDir).(substr($workDir,-1)=="/" ?"":DS).$files[$count]; ?>'));">
                            <div class="tcell ui-widget-content h40" style="width:20%;text-align:center;"><img src="images/16x16/folder.png"></div>
                            <div class="tcell ui-widget-content h40 f12b" style="cursor:pointer;"><?php echo $files[$count]; ?></div>
                        </div>
<?php
        }
    }
}
?>
                    </div>