<?php
// include the session-start
include("sessionStart.php");

$package = urldecode($_GET['package']);
if($package == "") { echo "No Package given...";; }
else if($package == "all") { echo "trying to load and verify all available extensions";
    $keys = array_keys($_SESSION['_INI']['extensions']);
    foreach ($keys as $package) {
        $updateChecker = $base->getUpdateChecker();
        $result  = $updateChecker->checkFor($package);
        echo "<pre>";
        echo "Version Check:\n";
        echo "for: " . $package . "\n";
        if(is_array($result)) {
            echo "     installed: " . $updateChecker->fetchInstalledVersion(). "\n";
            echo "     available: " . $updateChecker->fetchAvailableVersion(). "\n";
            echo "     Result: " . $result['msg'] . "\n";
            if($result['state'] == 1) {
                // new version available - should we download?
                echo "<a href=\"http://".str_replace("{VERSION}", $result['version']['webSite'], $result['url'])."\">download</a><br>or<br>";
                $installExtension = $base->getInstallExtension();
                echo $installExtension->getUploadForm();
            }
        } else {
            echo "     Result: " . $result . "\n";
        }
        echo "</pre>";
    }
} else {
    $updateChecker = $base->getUpdateChecker();
    $result  = $updateChecker->checkFor($package);
    echo "<pre>";
    echo "Version Check:\n";
    echo "for: " . $package . "\n";
    if(is_array($result)) {
        echo "     installed: " . $updateChecker->fetchInstalledVersion(). "\n";
        echo "     available: " . $updateChecker->fetchAvailableVersion(). "\n";
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
}
// show the debug-messages and clear
// $base->showDebugMessage();
?>