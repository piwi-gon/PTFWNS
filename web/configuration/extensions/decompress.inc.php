<?php
/**
 *
 * decompress.inc.php
 *
 * author : piwi
 *
 * created: 19.04.2015
 * changed: 19.04.2015
 *
 * purpose:
 *
 */

require_once("../lib/baseStart.php");
require_once("../lib/uploadConstants.php");

move_uploaded_file($_FILES['file']['tmp_name'], INSTALL_UPLOADDIR.$_FILES['file']['name']);

$extArchive = urldecode($_GET['fileName']);
// try to uncompress the given file
if(file_exists(INSTALL_UPLOADDIR . $extArchive)) {
    $msg = "try to uncompress ...";
    $baseFile = $base->getBaseFile();
    if(!is_object($baseFile)) { die("No Base-File found\n"); exit(); }
    $installDir = $baseFile->uncompress(INSTALL_UPLOADDIR . $extArchive, INSTALL_WORKDIR);
    $msg .="done";
} else {
    $msg = "File not found '" . INSTALL_UPLOADDIR . $extArchive . "'";
}
echo $msg;