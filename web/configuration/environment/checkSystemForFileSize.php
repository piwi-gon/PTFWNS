<?php
/**
 *
 * checkSystemForFileSize.php
 *
 * author : piwi
 *
 * created: 25.10.2015
 * changed: 25.10.2015
 *
 * purpose:
 *
 */


$postMaxSize   = ini_get("post_max_size");
$uploadMaxSize = ini_get("upload_max_filesize");

if(strpos($postMaxSize, "M")) {
    $postMaxSizeValue = intval($postMaxSize * 1024 * 1024);
}
if(strpos($uploadMaxSize, "M")) {
    $uploadMaxSizeValue = intval($uploadMaxSize * 1024 * 1024);
}

if($_GET['fileSize'] < $postMaxSizeValue) {
    if($_GET['fileSize'] < $uploadMaxSizeValue) {
        echo "true|Checked: PMV: '" . $postMaxSizeValue . "' => '" . $_GET['fileSize'] . "' and UMFS: '" . $uploadMaxSizeValue . "' => '" . $_GET['fileSize'] . "'";
    } else {
        echo "fail|UploadMaxFileSize is set to '" . $uploadMaxSize . "' - please change";
    }
} else {
    echo "fail|PostMaxSize is set to '" . $postMaxSize . "' - please change";
}