<?php
/**
 *
 * cExtension.inc.php
 *
 * author : piwi
 *
 * created: 20.04.2015
 * changed: 20.04.2015
 *
 * purpose:
 *
 */

namespace PTFW\Base\Module\Extension;

class cExtensionClass {

    const version       = "v1.0.0";

    private $_installInfo = array();

    private $_extIndex;

    private $_deb;

    public function __construct() {
        global $base;
        $this->_deb = $base;
    }

    public function toggleExtension($VAR) {
        error_log("try to create new index (" . _BASEDIR_ . DS . "index" . DS . "extensions.idx)\n", 3, "/tmp/install.log");
        $this->_createExtensionIndex();
        print_r($this->_extIndex);
        /**
         * now find the selected identifier to
         * de-/activate the Extension
         */
        $fp = fopen(_BASEDIR_ . DS . "index" . DS . "extensions.idx", "w+");
        if($fp != null) {
            $this->_buildExtensionIndexHeader($fp, false);
            $numOfExt = count($this->_extIndex);
            for($count = 0; $count < $numOfExt; $count++) {
                error_log("adding '".$this->_extIndex[$count]['name']."'\n", 3, "/tmp/install.log");
                echo sprintf("%-20s", $this->_extIndex[$count]['ident']) . "= " . $this->_extIndex[$count]['path'] . DS . $this->_extIndex[$count]['name'] . "|" . $this->_extIndex[$count]['version'] . "|" . $this->_extIndex[$count]['active'] . "\n";
                if($this->_extIndex[$count]['ident'] == $VAR['selectedExtensionIdentifier']) {
                    echo "found\n";
                    $this->_extIndex[$count]['active'] = ($this->_extIndex[$count]['active']=="true"?"false":"true");
                }
                echo sprintf("%-20s", $this->_extIndex[$count]['ident']) . "= " . $this->_extIndex[$count]['path'] . DS . $this->_extIndex[$count]['name'] . "|" . $this->_extIndex[$count]['version'] . "|" . $this->_extIndex[$count]['active'] . "\n";
                fwrite($fp, sprintf("%-20s", $this->_extIndex[$count]['ident']) . "= " . $this->_extIndex[$count]['path'] . DS . $this->_extIndex[$count]['name'] . "|" . $this->_extIndex[$count]['version'] . "|" . $this->_extIndex[$count]['active'] . "\n");
            }
            fclose($fp);
        } else {
            echo "cant create extension.idx-file\n";
        }
    }

    public function installExtension($VAR) {
        $this->_buildInfo($VAR);
        $this->_installFrom($VAR['pathToInstall']);
        $installed = $this->_checkInstalledVersion();
        if($installed == "") {
            // now add this file to index
            $new = false;
            $new = !file_exists(_BASEDIR_ . DS . "index" . DS . "extensions.idx");
            $fp = fopen(_BASEDIR_ . DS . "index" . DS . "extensions.idx", "a+");
            if($fp != null && trim($this->_installInfo['name']) != "") {
                /**
                 * install the new extension
                 *
                 * set usable to true at init so it usable at first
                 *
                 * you can configure it on the configuration-page or directly
                 * in the index-file (extension.idx in the index-directory at the
                 * framework-location)
                 */
                if($new) { $this->_buildExtensionIndexHeader($fp); }
                error_log("adding '".$this->_installInfo['name']."'\n", 3, "/tmp/install.log");
                fwrite($fp, sprintf("%-20s", $this->_installInfo['ident']) . "= " . $this->_installInfo['path'] . DS . $this->_installInfo['name'] . "|" . $this->_installInfo['version'] . "|true\n");
            }
            fclose($fp);
        } else {
            $fp = fopen(_BASEDIR_ . DS . "index" . DS . "extensions.idx", "w+");
            $this->_buildExtensionIndexHeader($fp, false);
            for($count=0; $count < count($this->_extIndex); $count++) {
                /**
                 * if the extension is to be updated:
                 * updating the extension - set the usable-flag to the stored one
                 *
                 * if not:
                 * add the current entry with current configuration
                 */
                error_log("Checking '" . trim($this->_installInfo['name']) . "'", 3, "/tmp/install.log");
                if(strtolower(trim($this->_extIndex[$count]['name'])) == strtolower(trim($this->_installInfo['name']))) {
                    error_log("updating '".$this->_installInfo['name']."'\n", 3, "/tmp/install.log");
                    fwrite($fp, sprintf("%-20s", $this->_extIndex[$count]['ident']) . "= " . $this->_extIndex[$count]['path'] . DS . $this->_extIndex[$count]['name'] . "|" . $this->_installInfo['version'] . "|" . $this->_extIndex[$count]['active'] . "\n");
                } else {
                    error_log("adding '".$this->_extIndex[$count]['name']."'\n", 3, "/tmp/install.log");
                    fwrite($fp, sprintf("%-20s", $this->_extIndex[$count]['ident']) . "= " . $this->_extIndex[$count]['path'] . DS . $this->_extIndex[$count]['name'] . "|" . $this->_extIndex[$count]['version'] . "|" . $this->_extIndex[$count]['active'] . "\n");
                }
            }
            fclose($fp);
        }
        $this->_removeInstallDir($installDir, true);
    }

    public function queryTestByExtension($ident) {
        $modules = $this->_createExtensionIndex();
        for($count = 0; $count < count($this->_extIndex); $count++) {
            if($this->_extIndex[$count]['ident'] == $ident) {
                $extension = $this->_extIndex[$count];
                if(file_exists($extension['path'] . DIRECTORY_SEPARATOR . "tests.php")) {
                    return $extension['path'] . DIRECTORY_SEPARATOR . "tests.php";
                }
            }
        }
        return "";
    }

    private function _installFrom($installFromDir, $installToDir = "", $installInfo = array()) {
        if($installToDir == "") {
            $installToDir    = EXTENSION_DIR;
        }
        error_log("reading dir: '" . INSTALL_WORKDIR . $installFromDir . "'\n", 3, "/tmp/install.log");
        $installFiles = @scandir(INSTALL_WORKDIR . $installFromDir);
        for($count = 0; $count < count($installFiles); $count++){
            if($installFiles[$count] != "." && $installFiles[$count] != "..") {
                if(is_dir(INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR.$installFiles[$count])) {
                    if(!file_exists($installToDir.DIRECTORY_SEPARATOR.$installFiles[$count])) {
                        mkdir($installToDir.DIRECTORY_SEPARATOR.$installFiles[$count]);
                    }
                    $this->_installFrom($installFromDir.DIRECTORY_SEPARATOR.$installFiles[$count], $installToDir.DIRECTORY_SEPARATOR.$installFiles[$count]);
                } else {
                    copy(INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR.$installFiles[$count], $installToDir.DIRECTORY_SEPARATOR.$installFiles[$count]);
                }
            }
        }
    }

    private function _buildInfo($VAR) {
        $this->_installInfo['ident']   = $VAR['ident'];
        $this->_installInfo['name']    = $VAR['name'];
        $this->_installInfo['path']    = $VAR['path'];
        $this->_installInfo['version'] = $VAR['version'];
        $this->_installInfo['active']  = "true";
    }

    private function _checkInstalledVersion() {
        $this->_createExtensionIndex();
        $version = "";
        for($count = 0; $count < count($this->_extIndex); $count++) {
            error_log("checking '" . $this->_extIndex[$count]['name'] . "' with '" . $this->_installInfo['name'] . "'", 3, "/tmp/install.log");
            if(trim($this->_extIndex[$count]['name']) == trim($this->_installInfo['name'])) {
                $version = $this->_extIndex[$count]['version'];
                error_log(" - version=" . $version, 3, "/tmp/install.log");
            }
            error_log("\n", 3, "/tmp/install.log");
        }
        return $version;
    }

    private function _createExtensionIndex() {
        $content = file_get_contents(_BASEDIR_ . DS . "index" . DS . "extensions.idx");
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            if(substr(trim($lines[$count]),0,1) != ";" && substr(trim($lines[$count]),0,1) != "") {
                $lTokens = explode("=", $lines[$count]);
                $identifier = trim($lTokens[0]);
                $lineTokens = explode("|", trim($lTokens[1]));
                $this->_extIndex[] = array("ident"   =>  $identifier,
                                           "name"    =>  basename($lineTokens[0]),
                                           "path"    =>  dirname($lineTokens[0]),
                                           "version" =>  $lineTokens[1],
                                           "active"  =>  $lineTokens[2]);
            }
        }
    }

    private function _removeInstallDir($directory, $delRoot) {
        if(!$dh = @opendir($directory)) { return; }
        while(false !==($obj = @readdir($dh))) {
            if($obj == "." || $obj == "..") { continue; }
            if(!@unlink($directory."/".$obj)) { $this->_removeInstallDir($directory."/".$obj, true); }
        }
        closedir($dh);
        if($delRoot) { rmdir($directory); }
        return;
    }

    private function _buildExtensionIndexHeader($fp, $isNew = false) {
        if($isNew) {
            error_log("Hey - its a new installation - creating header\n", 3, "/tmp/install.log");
        } else {
            error_log("Updating - creating header\n", 3, "/tmp/install.log");
        }
        $header = ";------------------------------------------\n".
                  "; auto-generated Extension-Index\n".
                  ";\n".
                  "; includes all available base-extensions with\n".
                  "; their pathes, identifiers and versions\n".
                  ";\n".
                  ";------------------------------------------\n".
                  "\n";
        fwrite($fp, $header);
    }
}
?>