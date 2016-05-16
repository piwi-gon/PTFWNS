<?php
require_once($argv[1] . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "clistart.inc.php");
global $_theFiles;
//echo basename(__FILE__) . "\n";

$_headerModuleLines[] = ";------------------------------------------";
$_headerModuleLines[] = "; auto-generated Module-Index";
$_headerModuleLines[] = ";";
$_headerModuleLines[] = "; includes all available modules with their";
$_headerModuleLines[] = "; pathes and versions";
$_headerModuleLines[] = ";";
$_headerModuleLines[] = ";------------------------------------------";

// try to rebuild the module-index
if(isset($_theFiles)) { unset($_theFiles); }
// first: delete old index-file
rename(_BASEDIR_ . DS . "bin" . DS . "index" . DS . "ptfwModules.idx", _BASEDIR_ . DS . "bin" . DS .  "index" . DS . "ptfwModules.idx.bak");
echo "building module-index\n";
_getFiles(_BASEDIR_ . DS . "modules", $_theFiles);
print_r($_theFiles);
for($count = 0; $count < count($_theFiles); $count++) {
    $lines[] = str_replace("modules/", "", $_theFiles[$count]['location'])."|".$_theFiles[$count]['version'];
}
$fp = fopen(_BASEDIR_ . DS . "bin" . DS . "index" . DS . "ptfwModules.idx", "w+");
fwrite($fp, join("\n", $_headerModuleLines));
fwrite($fp, "\n\n"); // empty line
fwrite($fp, join("\n", $lines));
fwrite($fp, "\n; EOF\n");
fclose($fp);

exit;

function _getFiles($dir, $_theFiles) {
    global $_theFiles;
    $files = scandir($dir);
    if(!in_array("ignore.txt", $files)) {
        for($count = 0; $count < count($files); $count++) {
            if($files[$count] != "." && $files[$count] != ".." && $files[$count] != "phpunit" && substr(basename($files[$count]), 0, 7) != "rebuild") {
                if(!is_dir($dir . DS . $files[$count])) {
                    if(substr($files[$count], -4) == ".php" && basename($files[$count]) != basename(__FILE__) &&
                       strpos($dir, "current")>0) {
                        echo "found: " . $className;
                        // try to instantiate the file as class
                        $className = str_replace(".inc.php", "", $files[$count]);
                        if(!class_exists($className)) {
                            include($dir . DS . $files[$count]);
                        }
                        try {
                            $class= new \ReflectionClass($className);
                            if($class->isAbstract()) { echo "Hey! '" . $className . "' is abstract\n"; }
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
                        } catch(Exception $ex) {
                            echo $className . " => " . $ex->getMessage() . "\n";
                        }
                    } else {
                        echo "this is not a php-file/class\n";
                    }
                } else {
                    _getFiles($dir . DS . $files[$count], $_theFiles);
                }
            }
        }
    } else {
        echo "Directory '" . $dir . "' ignored\n";
    }
}
?>