<?php
/**
 *
 * cModuleHandler.inc.php
 *
 * author : piwi
 *
 * created: 04.01.2015
 * changed: 04.01.2015
 *
 * purpose:
 *
 */

namespace PTFW\Base\Module\ModuleClass;

//@define("SOAPURL",  "http://localhost/ptfwmanager/webservice/server.php");
//@define("SOAPWSDL", SOAPURL . "?wsdl");

class cModuleClass {

    private $_sql;
    private $_msg;
    private $_date;
    private $_deb;

    private $_soapParams;
    private $_soapClient;

    private $_soapResult;

    private $_SOAPURL;
    private $_SOAPWSDL;

    private $_actualRepoModuleVersion;
    private $_manifestFileLocation;

    private $_installInfo;

    private $_modIndex;
    private $_extIndex;

    public function __construct() {
        global $oSQL, $oMSG, $oDate, $base;
        $this->_sql  = $oSQL;
        $this->_msg  = $oMSG;
        $this->_date = $oDate;
        $this->_deb  = $base;
    }

    public function getNewVersion() {
        return $this->_actualRepoModuleVersion;
    }

    public function toggleModule($VAR) {
        error_log("try to create new index (" . _BASEDIR_ . DS . "index" . DS . "modules.idx)\n", 3, "/tmp/install.log");
        $this->_createModuleIndex();
        /**
         * now find the selected identifier to
         * de-/activate the Extension
        */
        $fp = fopen(_BASEDIR_ . DS . "index" . DS . "modules.idx", "w+");
        if($fp != null) {
            $this->_buildModuleIndexHeader($fp, false);
            $numOfMod = count($this->_modIndex);
            for($count=0; $count < $numOfMod; $count++) {
                error_log("Checking '" . substr(trim($this->_installInfo['mainClass']), 1) . "'", 3, "/tmp/install.log");
                if($this->_modIndex[$count]['moduleName'] == $VAR['selectedModuleIdentifier']) {
                    echo "found\n";
                    $this->_modIndex[$count]['active'] = ($this->_modIndex[$count]['active']=="true"?"false":"true");
                }
                fwrite($fp, sprintf("%-20s", $this->_modIndex[$count]['moduleName']) . "= " . $this->_modIndex[$count]['path'].DS.$this->_modIndex[$count]['name'].".inc.php|".$this->_modIndex[$count]['version']."|".$this->_modIndex[$count]['active']."\n");
            }
            fclose($fp);
        } else {
            echo "cant create extension.idx-file\n";
        }
    }

    public function rebuildModuleIndexForExistantModules() {
        $startDir = _BASEDIR_ . DS . "modules";
        $mouleDirs = @scandir($startDir);
        foreach($moduleDirs as $modDir) {
            $moduleInfo[] = $this->_readManifestFile($startDir . DS . $modDir . DS . "current" . DS . "manifest");
        }
        $fp = fopen(_BASEDIR_ . DS . "index" . DS . "modules.idx", "w+");
        if($fp != null) {
            $this->_buildModuleIndexHeader($fp, false);
            fwrite($fp, sprintf("%-20s", $this->_modIndex[$count]['moduleName']) . "= " . $this->_modIndex[$count]['path'].DS.$this->_modIndex[$count]['name'].".inc.php|".$this->_modIndex[$count]['version']."|".$this->_modIndex[$count]['active']."\n");
        }
        fclose($fp);
    }

    public function checkIfActual($moduleName, $moduleVersion, $repoIdent) {
        error_log("Try to check: " . $moduleName . " (" . $moduleVersion . ")\n", 3, "/tmp/soapRequests.log");
        $this->_getRepoLocation($repoIdent);
        $this->_createSoapClient();
        $this->_soapAction = "getModuleVersion";
        if(isset($this->_soapParams)) { unset($this->_soapParams); }
        $this->_soapParams[] = new \SoapParam($moduleName, "moduleName");
        $this->_doCall();
        error_log("Version given: " . $moduleVersion . " <=> Module on Repo: " . $this->_soapResult . "\n", 3, "/tmp/soapRequests.log");
        $this->_actualRepoModuleVersion = $this->_soapResult;
        if(version_compare($this->_soapResult, $moduleVersion)>0) {
            error_log("Version is new\n", 3, "/tmp/soapRequests.log");
            return false;
        } else {
            return true;
        }
    }

    public function getModuleById($moduleId, $repoIdent) {
        $this->_getRepoLocation($repoIdent);
        $this->_createSoapClient();
        $this->_soapAction = "getModuleById";
        if(isset($this->_soapParams)) { unset($this->_soapParams); }
        $this->_soapParams[] = new \SoapParam($moduleId, "moduleId");
        $this->_doCall();
        return $this->_soapResult;
    }

    public function getModuleByName($moduleName, $repoIdent) {
        $this->_getRepoLocation($repoIdent);
        $this->_createSoapClient();
        $this->_soapAction = "getModule";
        if(isset($this->_soapParams)) { unset($this->_soapParams); }
        $this->_soapParams[] = new \SoapParam($moduleName, "moduleName");
        $this->_doCall();
        return $this->_soapResult;
    }

    public function getModuleDetails($moduleId, $repoIdent) {
        $this->_getRepoLocation($repoIdent);
        $this->_createSoapClient();
        $this->_soapAction = "getModuleDetails";
        if(isset($this->_soapParams)) { unset($this->_soapParams); }
        $this->_soapParams[] = new \SoapParam($moduleId, "moduleId");
        $this->_doCall();
        return $this->_soapResult;
    }

    public function getModuleList() {
        return $this->_createModuleIndex();
    }

    public function getAvailableModules($repoIdent) {
        $this->_getRepoLocation($repoIdent);
        echo $this->_SOAPWSDL."<br>";
        $this->_createSoapClient();
        $this->_soapAction = "getAvailableModules";
        if(isset($this->_soapParams)) { unset($this->_soapParams); }
        $this->_soapParams = [];
        $this->_doCall();
        return $this->_soapResult;
    }

    public function installModule($moduleId, $repoIdent) {
        global $base;
        $result = $this->getModuleById($moduleId, $repoIdent);
        $fp = fopen(INSTALL_UPLOADDIR . DS . $result['fileName'], "w+");
        if($fp != null) {
            fwrite($fp, base64_decode($result['fileContent']));
            fclose($fp);
            $this->installModuleArchive($result['fileName']);
            // $this->_installInfo = $moduleHandler->getInstallInfo();
            $this->_copyManifestFile(str_replace(".tar.gz", "", $result['fileName']));
            return true;
        } else {
            echo "install failed - please check your permissions";
        }
        return false;
    }

    public function installModuleByName($moduleName) {
        global $base;
        $result = $this->getModuleByName($moduleName);
        $fp = fopen(INSTALL_UPLOADDIR . DS . $result['fileName'], "w+");
        if($fp != null) {
            fwrite($fp, base64_decode($result['fileContent']));
            fclose($fp);
            $this->installModuleArchive($result['fileName']);
            // $this->_installInfo = $moduleHandler->getInstallInfo();
            return true;
        } else {
            echo "install failed - please check your permissions";
        }
        return false;
    }

    public function installModuleArchive($moduleArchive) {
        global $base;
        error_log("try to uncompress '" . $moduleArchive . "'\n", 3, "/tmp/install.log");
        $baseFile = $base->getBaseFile();
        if(!is_object($baseFile)) { die("No Base-File found\n"); exit(); }
        $installDir = $baseFile->uncompress(INSTALL_UPLOADDIR . $moduleArchive, INSTALL_WORKDIR);
        // now install archive
        // $this->_installFrom(str_replace(".tar.gz", "", $moduleArchive));
        $dirToCheck = str_replace(".tar.gz", "", $moduleArchive);
        if(file_exists(INSTALL_WORKDIR.$dirToCheck)) {
            $this->_installFrom($dirToCheck);
        } else {
            $tokens = explode("-", $moduleArchive);
            $dirToCheck = $tokens[0];
            if(file_exists(INSTALL_WORKDIR.$dirToCheck)) {
                $this->_installFrom($dirToCheck);
            } else {
                echo "No Files found to be installed - please check ...\n";
                echo "check in'" . str_replace(".tar.gz", "", $moduleArchive) . "' and in '" . $tokens[0] . "' (WorkDir: '" . INSTALL_WORKDIR . "')\n";
                exit(-1); 
            }
        }
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
                fwrite($fp, sprintf("%-20s", $this->_installInfo['moduleName']) . "= " . strtolower(substr($this->_installInfo['mainClass'], 1)) . DS . "current" . DS . $this->_installInfo['mainClass'] . ".inc.php|" . $this->_installInfo['version']."|true\n");
            }
            fclose($fp);
        } else {
            $fp = fopen(_BASEDIR_ . DS . "index" . DS . "modules.idx", "w");
            $this->_buildModuleIndexHeader($fp, false);
            for($count=0; $count < count($this->_modIndex); $count++) {
                error_log("Checking '" . substr(trim($this->_installInfo['mainClass']), 1) . "'", 3, "/tmp/install.log");
                if(strtolower(trim($this->_modIndex[$count]['name'])) == strtolower(trim($this->_installInfo['mainClass']))) {
                    error_log("updating '".$this->_installInfo['mainClass']."'\n", 3, "/tmp/install.log");
                    fwrite($fp, sprintf("%-20s", $this->_modIndex[$count]['moduleName']) . "= " . $this->_modIndex[$count]['path'].DS.$this->_modIndex[$count]['name'].".inc.php|".$this->_installInfo['version']."|".$this->_modIndex[$count]['active']."\n");
                } else if(trim($this->_installInfo['mainClass']) != "") {
                    error_log("adding '".$this->_modIndex[$count]['moduleName']."'\n", 3, "/tmp/install.log");
                    fwrite($fp, sprintf("%-20s", $this->_modIndex[$count]['moduleName']) . "= " . $this->_modIndex[$count]['path'].DS.$this->_modIndex[$count]['name'].".inc.php|".$this->_modIndex[$count]['version']."|".$this->_modIndex[$count]['active']."\n");
                }
            }
            fclose($fp);
        }
        $this->_removeInstallDir($installDir, true);
    }

    public function getInstallInfo() {
        return $this->_installInfo;
    }

    public function queryTestByModuleName($moduleName) {
        $modules = $this->_createModuleIndex();
        for($count = 0; $count < count($this->_modIndex); $count++) {
            if($this->_modIndex[$count]['moduleName'] == $moduleName) {
                global $base;
                $objToTest = $base->{"get".$moduleName};
                if(!is_object($objToTest)) {
                    return null;
                } else {
                    if(!method_exists($objToTest, "getTestMethods")) {
                        return null;
                    } else {
                        return $objToTest->getTestMethods();
                    }
                }
            }
        }
    }

    private function _getRepoLocation($repoIdent) {
        global $base;
        $repo = $base->getUpdateChecker()->querySystemRepository();
        for($count = 0; $count < count($repo['modules']['reponame']); $count++) {
            if(strtoupper($repo['modules']['reponame'][$count]) == strtoupper($repoIdent)) {
                $this->_SOAPURL  = $repo['modules']['modrepo'][$count];
                $this->_SOAPWSDL = $repo['modules']['modrepo'][$count] . "?wsdl";
            }
        }
    }

    private function _doCall() {
        try {
            $this->_soapResult = $this->_soapClient->__call($this->_soapAction, $this->_soapParams);
        } catch(Exception $ex) {
            echo "<pre>";
            echo "Functions are (URL: " . $this->_SOAPWSDL . "): <br>";
            print_r($this->_soapClient->__getFunctions());
            echo html_entity_decode($this->_soapClient->__getLastRequest());
            echo html_entity_decode($this->_soapClient->__getLastResponse());
            echo $ex->getMessage()."<br>";
            echo "</pre>";
            die("no connection in success");
            exit;
        }
    }

    private function _createSoapClient() {
        $WSDL = $this->_SOAPWSDL;
        $optionArray = array(
                        "location" => $this->_SOAPURL,
                        "uri" => "urn:testapi",
                        "trace" => 1); // ,
                        // "exceptions" => 0);
        $this->_soapClient = new \SoapClient($WSDL, $optionArray);
    }

    private function _createModuleIndex() {
        $content = file_get_contents(_BASEDIR_ . DS . "index" . DS . "modules.idx");
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            if(substr(trim($lines[$count]),0,1) != ";" && substr(trim($lines[$count]),0,1) != "") {
                $lTokens = explode("=", $lines[$count]);
                $identifier = trim($lTokens[0]);
                $lineTokens = explode("|", trim($lTokens[1]));
                $this->_modIndex[] = array("moduleName" =>  $identifier,
                                           "name"       =>  str_replace(".inc.php", "", basename($lineTokens[0])),
                                           "path"       =>  dirname($lineTokens[0]),
                                           "version"    =>  $lineTokens[1],
                                           "active"     =>  $lineTokens[2]);
            }
        }
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

    private function _installFrom($installFromDir, $installToDir = "", $installInfo=array()) {
        error_log("reading dir: '" . INSTALL_WORKDIR.$installFromDir . "'\n", 3, "/tmp/install.log");
        if(file_exists(INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR."manifest") && count($this->_installInfo)==0) {
            $this->_parseManifest(INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR."manifest");
            $this->_manifestFileLocation = INSTALL_WORKDIR.$installFromDir.DIRECTORY_SEPARATOR."manifest";
        }
        if($installToDir == "") { $installToDir = MODULE_DIR; $installFromDir .= INSTALL_MODULEDIR; }
        // error_log("reading dir: '" . INSTALL_WORKDIR.$installFromDir . "'\n", 3, "/tmp/install.log");
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
                case "repository"   : $ret['repository']    = trim(substr($actLine, strpos($lines[$count], ":")+1));                break;
                case "author"       : $ret['author']        = trim(substr($actLine, strpos($lines[$count], ":")+1));                break;
                case "description"  : $ret['description']   = $this->_readAllDescLines($count, $lines);                             break;
            }
        }
        if(count($lines) < 6) { die("Manifest-File is not correct - please check"); exit(); }
        $this->_installInfo = $ret;
    }

    private function _readManifestFile($manifestFile) {
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
                case "repository"   : $ret['repository']    = trim(substr($actLine, strpos($lines[$count], ":")+1));                break;
                case "author"       : $ret['author']        = trim(substr($actLine, strpos($lines[$count], ":")+1));                break;
                case "description"  : $ret['description']   = $this->_readAllDescLines($count, $lines);                             break;
            }
        }
        if(count($lines) < 6) { die("Manifest-File is not correct - please check"); exit(); }
        return $ret;
    }

    private function _readAllDescLines($start, $lines) {
        $desc="";
        $identifier = array("ModuleName", "main-class", "repository", "classes", "version", "realeased", "author");
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

    private function _copyManifestFile($installFromDir) {
        $installToDir = MODULE_DIR.$this->_getLastDir(dirname($this->_manifestFileLocation));
        $lastDir = $this->_getlastDir(dirname($this->_manifestFileLocation));
        echo "LAST: " . $lastDir."\n";
        echo "LASTDIR: " . MODULE_DIR.$lastDir."\n";
        $installFromDir .= INSTALL_MODULEDIR;
        copy($this->_manifestFileLocation, $installToDir.DIRECTORY_SEPARATOR."manifest");
    }

    private function _getLastDir($dir) {
        $scandir = $dir . DIRECTORY_SEPARATOR . "pear" . DIRECTORY_SEPARATOR . "ptfw" . DIRECTORY_SEPARATOR . "modules";
        $scanned = @scandir( $scandir );
        $str = "";
        for($count = 0; $count < count($scanned); $count++) {
            $curr = $scanned[$count];
            if($curr != ".." && $curr != ".") {
                if(is_dir($scandir . DIRECTORY_SEPARATOR . $curr)) { echo $curr . "\n"; $str = $curr; break; }
            }
        }
        echo $str;
        return $str;
    }
}