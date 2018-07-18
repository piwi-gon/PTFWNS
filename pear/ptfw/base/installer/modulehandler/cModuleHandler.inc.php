<?php
/**
 *
 * cModuleHandler.inc.php
 *
 * author : piwi
 *
 * created: 23.02.2015
 * changed: 23.02.2015
 *
 * purpose:
 *
 */


class cModuleHandler extends BASE {

    const version       = "v1.0.0";

    private $_installInfo = array();

    public function cModuleHandler() {
        ;
    }

    public function baseRun() { return $this; }

    public function getVersion() { return "v1.0.0"; }

    public function queryAvailableModules() {
        if(extension_loaded("sqlite")) {
            return $this->_getModuleData();
        } else if(extension_loaded("sqlite3")) {
            return $this->_getModuleData3();
        }
    }

    public function getInstallInfo() {
        return $this->_installInfo;
    }

    public function installModule($moduleArchive) {
        global $base;
        error_log("try to uncompress '" . $moduleArchive . "'\n", 3, "/tmp/install.log");
        $baseFile = $base->getBaseFile();
        if(!is_object($baseFile)) { die("No Base-File found\n"); exit(); }
        $installDir = $baseFile->uncompress(INSTALL_UPLOADDIR . $moduleArchive, INSTALL_WORKDIR);
        // now install archive
        $this->_installFrom(str_replace(".tar.gz", "", $moduleArchive));
        // does module exist?
        $installed = $this->_checkInstalledVersion();
        // if not - install it
        if(trim($installed) == "") {
            // now add this file to index
            $new = false;
            $new = !file_exists(_BASEDIR_ . DS . "index" . DS . "modules.idx");
            $fp = fopen(_BASEDIR_ . DS . "index" . DS . "modules.idx", "a+");
            if($fp != null && trim($this->_installInfo['mainClass']) != "") {
                if($new) { $this->_buildModuleIndexHeader($fp); }
                error_log("adding '".$this->_installInfo['mainClass']."'\n", 3, "/tmp/install.log");
                fwrite($fp, strtolower(substr($this->_installInfo['mainClass'], 1)) . DS . "current" . DS . $this->_installInfo['mainClass'] . ".inc.php|" . $this->_installInfo['version']."|true\n");
            }
            fclose($fp);
        } else {
            $fp = fopen(_BASEDIR_ . DS . "index" . DS . "modules.idx", "w");
            $this->_buildModuleIndexHeader($fp, false);
            for($count=0; $count < count($this->_modIndex); $count++) {
                error_log("Checking '" . substr(trim($this->_installInfo['mainClass']), 1) . "'", 3, "/tmp/install.log");
                if(strtolower(trim($this->_modIndex[$count]['name'])) == strtolower(trim($this->_installInfo['mainClass']))) {
                    error_log("updating '".$this->_installInfo['mainClass']."'\n", 3, "/tmp/install.log");
                    fwrite($fp, $this->_modIndex[$count]['path'].DS.$this->_modIndex[$count]['name'].".inc.php|".$this->_installInfo['version']."|".$this->_modIndex[$count]['active']."\n");
                } else if(trim($this->_installInfo['mainClass']) != "") {
                    error_log("adding '".$this->_modIndex[$count]['name']."'\n", 3, "/tmp/install.log");
                    fwrite($fp, $this->_modIndex[$count]['path'].DS.$this->_modIndex[$count]['name'].".inc.php|".$this->_modIndex[$count]['version']."|".$this->_modIndex[$count]['active']."\n");
                }
            }
            fclose($fp);
        }
        $this->_removeInstallDir($installDir, true);
    }

    private function _getModuleData($identifier = "") {
        try {
            $this->_sqliteDB = sqlite_open(dirname(__FILE__)."/../config.sqlite");
        } catch (Exception $ex) {
            die("DB-Conn could not be established");
        }
        $select = "select * from t_module ";
        if($identifier!="") {
            $select .= "where upper(identifier) = upper('" . $identifier . "') ";
        }
        $select .= "order by name";
        while($entry = sqlite_fetch_array($select, SQLITE_ASSOC)) {
            $data[] = $entry;
        }
        return $data;
    }

    private function _getModuleData3($identifier = "") {
        try {
            $this->_sqliteDB = new SQLite3(dirname(__FILE__)."/../config.sqlite3");
        } catch (Exception $ex) {
            die("DB-Conn could not be established");
        }
        $select = "select * from t_module ";
        if($identifier!="") {
            $select .= "where upper(identifier) = upper('" . $identifier . "') ";
        }
        $select .= "order by name";
        $result = $this->_sqliteDB->query($select);
        while($resX = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $resX;
        }
        return $data;
    }

    private function _installFrom($installFromDir, $installToDir = "", $installInfo=array()) {
        if(file_exists(INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR."manifest") && count($this->_installInfo)==0) {
            $this->_parseManifest(INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR."manifest");
        }
        if($installToDir == "") { $installToDir = MODULE_DIR; $installFromDir .= INSTALL_MODULEDIR; }
        error_log("reading dir: '" . INSTALL_WORKDIR.$installFromDir . "'\n", 3, "/tmp/install.log");
        // echo "reading dir: '" . INSTALL_WORKDIR.$installFromDir . "'" . (PHP_SAPI === "cli" ? "\n" : "<br>");
        $installFiles = scandir(INSTALL_WORKDIR.$installFromDir);
        if(count($this->_installInfo) > 0) {
            $installFiles = scandir(INSTALL_WORKDIR.$installFromDir);
            for($count = 0; $count < count($installFiles); $count++){
                if($installFiles[$count] != "." && $installFiles[$count] != "..") {
                    // echo "found file/dir '" . $installFiles[$count] . "'<br>";
                    if(is_dir(INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR.$installFiles[$count])) {
                        if(!file_exists($installToDir.DIRECTORY_SEPARATOR.$installFiles[$count])) {
                            // echo "try to create dir '" . $installToDir.DIRECTORY_SEPARATOR.$installFiles[$count] . "'".(PHP_SAPI === "cli" ? "\n" : "<br>");
                            mkdir($installToDir.DIRECTORY_SEPARATOR.$installFiles[$count]);
                        }
                        $this->_installFrom($installFromDir.DIRECTORY_SEPARATOR.$installFiles[$count], $installToDir.DIRECTORY_SEPARATOR.$installFiles[$count]);
                    } else {
                        // echo "try to copy '" .
                        //      INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR.$installFiles[$count] .
                        //      "' to '" .
                        //      $installToDir.DIRECTORY_SEPARATOR.$installFiles[$count] . "'".(PHP_SAPIE === "cli" ? "\n" : "<br>");
                        copy(INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR.$installFiles[$count], $installToDir.DIRECTORY_SEPARATOR.$installFiles[$count]);
                    }
                }
            }
            return $this->_installInfo;
        } else {
            die("No manifest found - aborting installation (" . INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR."manifest" . ")");
            exit;
        }
    }

    private function _checkInstalledVersion() {
        $this->_createModuleIndex();
        $version = "";
        for($count = 0; $count < count($this->_modIndex); $count++) {
            error_log("checking '" . $this->_modIndex[$count]['name'] . "' with '" . $this->_installInfo['mainClass'] . "'", 3, "/tmp/install.log");
            if(trim($this->_modIndex[$count]['name']) == trim($this->_installInfo['mainClass'])) {
                $version = $this->_modIndex[$count]['version'];
                error_log(" - version=" . $version, 3, "/tmp/install.log");
            }
            error_log("\n", 3, "/tmp/install.log");
        }
        return $version;
    }

    private function _createModuleIndex() {
        $content = file_get_contents(_BASEDIR_ . DS . "index" . DS . "modules.idx");
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            if(substr(trim($lines[$count]),0,1) != ";" && substr(trim($lines[$count]),0,1) != "") {
                $lineTokens = explode("|", trim($lines[$count]));
                $this->_modIndex[] = array("name"    =>  str_replace(".inc.php", "", basename($lineTokens[0])),
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

    private function _buildModuleIndexHeader($fp, $isNew = false) {
        if($isNew) {
            error_log("Hey - its a new installation - creating header\n", 3, "/tmp/install.log");
        } else {
            error_log("Updating - creating header\n", 3, "/tmp/install.log");
        }
        $header = ";------------------------------------------\n".
                  "; auto-generated Base-Index\n".
                  ";\n".
                  "; includes all available base-mdoules with\n".
                  "; their pathes and versions\n".
                  ";\n".
                  ";------------------------------------------\n".
                  "\n";
        fwrite($fp, $header);
    }

    private function _parseManifest($manifestFile) {
        error_log("parsing manifest-file '" . $manifestFile . "'\n", 3, "/tmp/install.log");
        $content = file_get_contents($manifestFile);
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            $actLine = trim($lines[$count]);
            $index = substr($actLine, 0, strpos($lines[$count], ":"));
            switch($index) {
                case "ModuleName"   : $ret['moduleName']    = trim(substr($actLine, strpos($lines[$count], ":")+1));                break;
                case "main-class"   : $ret['mainClass']     = trim(substr($actLine, strpos($lines[$count], ":")+1));                break;
                case "classes"      : $ret['classes']       = explode(",", str_replace(" ", "", trim(substr($actLine, strpos($lines[$count], ":")+1))));  break;
                case "version"      : $ret['version']       = trim(substr($actLine, strpos($lines[$count], ":")+1));                break;
                case "realeased"    : $ret['releaseDate']   = trim(substr($actLine, strpos($lines[$count], ":")+1));                break;
                case "author"       : $ret['author']        = trim(substr($actLine, strpos($lines[$count], ":")+1));                break;
                case "description"  : $ret['description']   = $this->_readAllDescLines($count, $lines);                             break;
            }
        }
        if(count($lines) < 6) { die("Manifest-File is not correct - please check"); exit(); }
        $this->_installInfo = $ret;
    }

    private function _readAllDescLines($start, $lines) {
        $desc="";
        $identifier = array("ModuleName", "main-class", "classes", "version", "realeased", "author");
        for($count = $start; $count < count($lines); $count++) {
            if($lines[$count] != "") {
                $isIdentifier = false;
                for($iCount = 0; $iCount < count($identifier); $iCount++) {
                    if(substr($lines[$count], 0, strlen($identifier[$iCount]))==$identifier[$iCount]) {
                        $isIdentifier = true;
                    }
                }
                if(!$isIdentifier) { $desc .= $lines[$count]; }
            }
        }
        return $desc;
    }
}
?>