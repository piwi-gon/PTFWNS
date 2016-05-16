<?php
require_once($argv[1] . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "clistart.inc.php");
// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "clistart.inc.php");
global $_theFiles;
echo basename(__FILE__) . "\n";

$extIndex   = BASE_DIR . "index" . DS . "ptfwExtensions.idx";
echo "Act Index-file: " . $extIndex."\n";
$className  = $argv[2];
$newVersion = $argv[3];

$_headerExtLines[] = ";---------------------------------------------";
$_headerExtLines[] = "; auto-generated Extension-Index";
$_headerExtLines[] = ";";
$_headerExtLines[] = "; includes all available extensions with their";
$_headerExtLines[] = "; pathes and versions";
$_headerExtLines[] = ";";
$_headerExtLines[] = "; created: " . date('Y-m-d H:i:s');
$_headerExtLines[] = ";";
$_headerExtLines[] = ";---------------------------------------------";

// try to rebuild the module-index
if(isset($_theFiles)) { unset($_theFiles); }
echo "building extension-index\n";
// first: get all lines in actual index
_getFiles();
// second: rename old as backupfile
rename($extIndex, $extIndex . ".bak");
for($count = 0; $count < count($_theFiles['ident']); $count++) {
    $lines[] = $_theFiles['ident'][$count] . " = " . $_theFiles['path'][$count] . "|" . $_theFiles['version'][$count];
}

$fp = fopen($extIndex, "w+");
fwrite($fp, join("\n", $_headerExtLines));
fwrite($fp, "\n\n"); // empty line
fwrite($fp, join("\n", $lines));
fwrite($fp, "\n; EOF\n");
fclose($fp);

exit;

function _getFiles() {
    global $base, $_theFiles, $className, $extIndex;
    if(!file_exists($extIndex)) { echo "File '" . $extIndex . "' doesnt exist!\n"; exit(); }
    $content = file_get_contents($extIndex);
    $lines = explode("\n", $content);
    for($count = 0; $count < count($lines); $count++) {
        if(substr($lines[$count], 0, 1) != ";" && strlen(trim($lines[$count])) > 0) {
            $ident = explode("=", $lines[$count]);
            $clsTokens = explode("|", trim($ident[1]));
            $base->deb("classPath: '" . dirname($clsTokens[0]) . " => file " . basename($clsTokens[0]));
            echo("classPath: '" . dirname($clsTokens[0]) . " => file " . basename($clsTokens[0])."\n");
            if(trim($ident[0]) == $className && $newVersion != "") { $version = $newVersion; }
            else { $version = $clsTokens[1]; }
            $_theFiles['ident'][]   = trim($ident[0]);
            $_theFiles['path'][]    = $clsTokens[0];
            $_theFiles['version'][] = $version;
        }
    }
/*
    $files = scandir($dir);
    for($count = 0; $count < count($files); $count++) {
        if($files[$count] != "." && $files[$count] != ".." && $files[$count] != "phpunit") {
            if(!is_dir($dir . DS . $files[$count])) {
                if(substr($files[$count], -4) == ".php" && basename($files[$count]) != basename(__FILE__) &&
                   strpos($dir, "current")>0) {
                    // try to instantiate the file as class
                    $className = str_replace(".inc.php", "", $files[$count]);
                    if(!class_exists($className)) {
                        include($dir . DS . $files[$count]);
                    }
                    $class= new \ReflectionClass($className);
                    if($className != "" && !$class->isAbstract()) {
                        echo "found: " . $className;
                        $obj = new $className;
                        if(is_object($obj)) {
                            $_theFiles[] = array("class"    => $className,
                                                 "location" => str_replace(_BASEDIR_ . DS, "", $dir . DS . $files[$count]),
                                                 "version"  => $obj->getVersion());
                            echo " -> " . $obj->getVersion() . "\n";
                            unset($className);
                            unset($obj); }
                    }
                } else {
                    echo "this is not a php-file/class\n";
                }
            } else {
                _getFiles($dir . DS . $files[$count], $_theFiles);
            }
        }
    }
*/
}
?>