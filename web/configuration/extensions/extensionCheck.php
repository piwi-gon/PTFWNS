<?php
/**
 *
 * extensionCheck.php
 *
 * author : piwi
 *
 * created: 18.07.2015
 * changed: 18.07.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");

// this is the extension-checker which chekcs the extension-version

// first:
// get the package-name to check
$package = $base->getConfiguration()->getPackageName($_GET['extensionId']);
echo "<pre>"; print_r($package); echo "</pre>";

// sencond:
// try to catch the versions available and
// checks them against the current installed one
$updateChecker = $base->getUpdateChecker();
$result  = $updateChecker->checkFor($package);
echo "<pre>";
echo "Version Check:\n";
echo "for: " . $package . "\n";
if(is_array($result)) {
    echo "     installed: " . $updateChecker->fetchInstalledVersion() . "\n";
    echo "     in DB    : " . $updateChecker->fetchDBVersion() . "\n";
    echo "     available: " . $updateChecker->fetchAvailableVersion() . "\n";
    echo "     Result: " . $result['msg'] . "\n";
} else {
    echo "     Result: " . $result . "\n";
}
echo "</pre>";
if($result['state'] == 1) {
    // new version available - should we download?
    //        echo "<a href=\"http://".str_replace("{VERSION}", $result['version']['webSite'], $result['url'])."\">download</a>";
    echo "<a href=\"http://".str_replace("{VERSION}", $result['version']['webSite'], $result['url'])."\">download</a><br>or<br>";
            $installExtension = $base->getInstallExtension();
            echo $installExtension->getUploadForm();
}
?>
<div id="extensionCheckResultId">
    here comes the result:
</div>