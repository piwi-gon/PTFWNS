<?php
/**
 *
 * cInstaller.inc.php
 *
 * author : piwi
 *
 * created: 01.03.2015
 * changed: 01.03.2015
 *
 * purpose:
 *
 */


class cInstaller extends BASE {

    /** baseDir
     *
     * the base directory
     *
     * @access private
     */
    private $_baseDir;

    /**
     *
     *
     */
    private $_theFiles;

    /**
     * _installDir
     *
     * the install-directory
     *
     */
    private $_installDir;

    /**
     * _installExtDir
     *
     * the install-directory for extension
     *
     */
    private $_installExtDir;

    /**
     * _sourceDir
     *
     * the source to be installed
     */
    private $_sourceDir;

    /**
     * the debug-object
     *
     * the object for debuggung-informations
     */
    private $_deb;

    public function __construct() {
        global $base;
        // the minstaller-contructor
        $this->_baseDir = _BASEDIR_;
        $this->_deb = $base;
    }

    public function baseRun() { return $this; }

    public function getVersion() { return "v1.0.0"; }

    public function renameCurrentExtFolderToVersionFolder($method, $version) {
        $this->_getExtInstallDir($method, $version);
        echo "installed on: '" . $this->_installExtDir . "' ";
        echo "renaming '" . $this->_baseDir . DS . "external" . DS . $this->_installExtDir . "' to '" .
             $this->_baseDir . DS . "external" . DS . str_replace("current", $version, $this->_installExtDir) ."' ";
        $dirs = explode(DS, str_replace("current", $version, $this->_installExtDir));
        rename($this->_baseDir . DS . "external" . DS . $this->_installExtDir,
               $this->_baseDir . DS . "external" . DS . str_replace("current", $version, $this->_installExtDir));
        $this->_extVersion = $version;
    }

    public function renameCurrentFolderToVersionFolder($method, $version) {
        $this->_getInstallDir($method, $version);
        echo "installed on: '" . $this->_installDir . "' ";
        echo "renaming '" . $this->_baseDir . DS . "modules" . DS . $this->_installDir . "' to '" . $this->_baseDir . DS . "modules" . DS . str_replace("current", $version, $this->_installDir) ."' ";
        $dirs = explode(DS, str_replace("current", $version, $this->_installDir));
        if(!file_exists($this->_baseDir . DS . "modules_bak" . DS . $dirs[0])) {
            $oldmask = umask(0);
            mkdir($this->_baseDir . DS . "modules_bak" . DS . $dirs[0]);
            umask($oldmask);
        }
        rename($this->_baseDir . DS . "modules" . DS . $this->_installDir,
               $this->_baseDir . DS . "modules_bak" . DS . str_replace("current", $version, $this->_installDir));
    }

    public function copyNewVersion($source) {
        $dirToInstall = "current";
        $oldmask = umask(0);
        $target = $this->_baseDir . DS . "modules" . DS . $this->_installDir;
        echo "copying from '" . $source . "' to '" . $target . "'\n";
        mkdir($target, 0777);
        umask($oldmask);
        $this->_copy_r($source, $target);
    }

    public function copyExtNewVersion($source) {
        $dirToInstall = "current";
        $oldmask = umask(0);
        $target = $this->_baseDir . DS . "external" . DS . $this->_installExtDir;
        echo "copying from '" . $source . "' to '" . $target . "'\n";
        mkdir($target, 0777);
        umask($oldmask);
        $this->_copy_r($source, $target);
    }

    public function rebuildModuleIndex() {
        $cmd = "/usr/bin/php -q " . dirname(__FILE__) . DS . "rebuildModuleIndex.cli.php " . _BASEDIR_;
        echo "executing '" . $cmd . "'\n";
        system($cmd);
    }

    public function rebuildExtensionIndex() {
        $cmd = "/usr/bin/php -q " . dirname(__FILE__) . DS . "rebuildExtensionIndex.cli.php " . _BASEDIR_ . " " . $this->_installExtDir;
        echo "executing '" . $cmd . "'\n";
        system($cmd);
    }

    private function _getFirstDir($source) {
        $dirs = @scandir($source);
        for($count = 0; $count < count($dirs); $count++) {
            echo "checking '" . $dirs[$count] . "' ...\n";
            if($dirs[$count] != "." && $dirs[$count] != ".." && $retDir == "") {
                if(is_dir($source.DS.$dirs[$count])) { echo "yep!\n"; $retDir = $dirs[$count]; }
            }
        }
        $this->_sourceDir = $retDir;
        return $retDir;
    }

    private function _getInstallDir($method, $currentDir="") {
        $retClass  = null;
        $className = str_replace("get", "c", $method);
        $this->_deb->deb(basename(__FILE__) . ":" . $method.": ".$currentDir, "MSG");
        $content = file_get_contents($this->_baseDir . DS . "index" . DS . "ptfwModules.idx");
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            if(substr($lines[$count], 0, 1) != ";" && strlen(trim($lines[$count])) > 0) {
                $clsTokens = explode("|", $lines[$count]);
                $this->_deb->deb("classPath: '" . dirname($clsTokens[0]) . " => file " . basename($clsTokens[0]));
                if(str_replace(".inc.php", "", basename($clsTokens[0])) == $className) {
                    $_includePath = dirname($clsTokens[0]);
                }
            }
        }
        $this->_installDir = $_includePath;
    }

    private function _getExtInstallDir($method, $version) {
        $retClass  = null;
        $className = str_replace("get", "c", $method);
        echo $className . "\n";
        $this->_deb->deb(basename(__FILE__) . ":" . $method.": ".$currentDir, "MSG");
        $content = file_get_contents($this->_baseDir . DS . "index" . DS . "ptfwExtensions.idx");
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            if(substr($lines[$count], 0, 1) != ";" && strlen(trim($lines[$count])) > 0) {
                $ident = explode("=", $lines[$count]);
                $clsTokens = explode("|", trim($ident[1]));
                $this->_deb->deb("classPath: '" . dirname($clsTokens[0]) . " => file " . basename($clsTokens[0]));
                echo("classPath: '" . dirname($clsTokens[0]) . " => file " . basename($clsTokens[0])."\n");
                if(trim($ident[0]) == $className) {
                    echo "found\n";
                    $_includePath = dirname($clsTokens[0]);
                }
            }
        }
        echo $_includePath."\n";
        $this->_installExtDir = $_includePath;
    }

    private function _copy_r( $path, $dest ) {
        if( is_dir($path)) {
            if(!file_exists($dest)) { echo "\ntry to create directory '".$dest."'"; mkdir( $dest ); }
            if(!@chmod($dest, 0777)) { echo "Directory could not be changed - no Permission\n"; }
            $objects = scandir($path);
            if( sizeof($objects) > 0 ) {
                foreach( $objects as $file ) {
                    if( $file == "." || $file == ".." || $file == ".svn") { continue; }
                    // go on
                    if( is_dir( $path.DS.$file ) ) { $this->_copy_r( $path.DS.$file, $dest.DS.$file ); }
                    else { echo "<br>copying file '".$file."'"; copy( $path.DS.$file, $dest.DS.$file ); chmod($dest.DS.$file, 0666); }
                }
            }
            return true;
        }
        else if( is_file($path) ) { return copy($path, $dest); }
        else { return false; }
    }
}