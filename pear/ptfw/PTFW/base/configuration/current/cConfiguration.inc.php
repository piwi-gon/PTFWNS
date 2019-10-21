<?php
/**
 *
 * cConfiguration.inc.php
 *
 * author : piwi
 *
 * created: 04.01.2015
 * changed: 04.01.2015
 *
 * purpose: the mian configuration.loader
 *          namespaced version
 *
 */

namespace PTFW\Base\Config;

class cConfiguration {

    const VERSION = "v1.0.0";

    private $_deb;

    public function __construct() {
    }

    public function getVersion() { return self::VERSION; }

    public function baseRun() { return $this; }

    public function run() { return $this; }

    public function getPackageName($extensionId) {
        $this->addDBVars();
        \PTFW\Base\Config\SQL\cSQLite::makeNewConn("ptfw_config");
        $select = "select name from t_extension ".
                  "where extension_id = '" . $extensionId . "'";
        $this->getDebug()->deb(basename(__FILE__) . ":" . "SQL: " . $select, "SQL");
        $result = $this->_sqlObj->makeQuery($select);
        return $result[0]['name'];
    }

    /**
     * loads the (installed) extensions via ini-file - the recommended way
     *
     * it loads the correct configuration-class with is relative
     * namespace path
     *
     * @return string[]|array[]|mixed[]
     */
    public function getListOfExtensions() {
        if(isIni) {
            $this->getDebug()->deb("locaConfiguration via ini-file");
            require_once(dirname(__FILE__).DS."ini".DS."cConfigurationIni.inc.php");
            $cfg = new Ini\cConfigurationIni();
            return $cfg->getListOfExtensions();
        } else {
            //
        }
    }

    /**
     * loads the (installed) modules via ini-file - the recommended way
     *
     * it loads the correct configuration-class with is relative
     * namespace path
     *
     * @return string[]|array[]|mixed[]
     */
    public function getListOfModules() {
        if(isIni) {
            $this->getDebug()->deb("locaConfiguration via ini-file");
            require_once(dirname(__FILE__).DS."ini".DS."cConfigurationIni.inc.php");
            $cfg = new Ini\cConfigurationIni();
            return $cfg->getListOfModules();
        } else {
            //
        }
    }

    /**
     * loads the configuration via ini-file - the recommended way
     *
     * it can be loaded via the sql-database but this doesnt work for now
     *
     * it loads the correct configuration-class with is relative
     * namespace path
     *
     * @return string[]|array[]|mixed[]
     */
    public function loadConfiguration() {
        $this->readEnvironment();
        if(isIni) {
            $this->getDebug()->deb("locaConfiguration via ini-file");
            require_once(dirname(__FILE__).DS."ini".DS."cConfigurationIni.inc.php");
            $cfg = new Ini\cConfigurationIni();
        } else {
            $this->getDebug()->deb("locaConfiguration via sql-db (sqlite)");
            require_once(dirname(__FILE__).DS."sql".DS."cConfigurationSQL.inc.php");
            $cfg = new SQL\cConfigurationSQL();
        }
        $cfg->loadFurtherConfiguration();
        $this->getDebug()->deb("Current Extensions:");
        if(count($_SESSION['_EXT'])>0) {
            $extKeys = array_keys($_SESSION['_EXT']);
            for($count = 0; $count < count($extKeys); $count++) {
                $this->getDebug()->deb($extKeys[$count]);
            }
        }
    }

    public function readEnvironment() {
        $content = file_get_contents(_BASEDIR_.DS."environment.ini");
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            $tokens = explode("=", trim($lines[$count]));
            if(strlen(trim($tokens[0]))>0) {
                if(intval($tokens[1])>0) {
                    $_SESSION['_ENV'][$tokens[0]] = $tokens[1];
                } else {
                    if(substr(strtolower($tokens[0]), 0, 1) == "v") {
                        $_SESSION['_ENV'][$tokens[0]] = $tokens[1];
                    } else {
                        if(strtoupper($tokens[1]) == "ON" || strtoupper($tokens[1]) == "TRUE") {
                            $_SESSION['_ENV'][$tokens[0]] = true;
                        } else {
                            $_SESSION['_ENV'][$tokens[0]] = false;
                        }
                    }
                }
            }
        }
    }

    public function modifyEnvrionment($VAR) {
        $this->_modEnvironment($VAR);
    }

    ///////////////////////////////////////////////////////////////////////
    //  private vars
    ///////////////////////////////////////////////////////////////////////
        private $_sqliteDB;
        private $_installInfo;
    ///////////////////////////////////////////////////////////////////////
    //  private functions
    ///////////////////////////////////////////////////////////////////////

    /*
     * 'public' private method _uncompress
     * @param $moduleArchive - the archive to uncompress
     * and install from
     */
    public function _uncompress($moduleArchive) {
        if(substr($moduleArchive,-3) == "zip") {
            $res = $this->_extractZIPArchive($moduleArchive);
        } else if(substr($moduleArchive, -6)=="tar.gz") {
            $res = $this->_extractTarGZArchive($moduleArchive);
        }
        if($res == true) {
            $files = @scandir(INSTALL_WORKDIR);
            for($count = 0; $count < count($files); $count++) {
                if($files[$count]!="." && $files[$count] != "..") {
                    if(is_dir(INSTALL_WORKDIR.$files[$count])) {
                        $installFromDir = $files[$count];
                    }
                }
            }
            return $installFromDir;
        } else {
            die ("couldnt extract Archive");
            exit;
        }
    }

    private function _extractZIPArchive($moduleArchive) {
        $zip = new \ZipArchive();
        $res = $zip->open($moduleArchive);
        if($res == true) {
            echo "try to extract to: '" . INSTALL_WORKDIR . "'<br>";
            $zip->extractTo(INSTALL_WORKDIR."/");
            $zip->close();
            return true;
        }
        return false;
    }

    private function _extractTarGZArchive($moduleArchive) {
        $zip = \PTFW\cBase::getTar();
        $res = $zip->open($moduleArchive);
        echo "try to extract to: '" . INSTALL_WORKDIR . "'<br>";
        $zip->extract(INSTALL_WORKDIR."/");
        return true;
    }

    /**
     * this function modifes the environment.ini
     *
     * @param array $VAR
     */
    private function _modEnvironment($VAR) {
        $keys = array_keys($VAR);
        $sKeys = array_keys($_SESSION['_ENV']);
        for($sCount = 0; $sCount < count($sKeys); $sCount++) {
            for($count = 0; $count < count($keys); $count++) {#
                if($sKeys[$sCount] == $keys[$count]) {
                    $_SESSION['_ENV'][$sKeys[$sCount]] = $VAR[$keys[$count]];
                }
            }
        }
        $target = _BASEDIR_.DS."environment.ini." . date('Ymd');
        if(file_exists(_BASEDIR_.DS."environment.ini." . date('Ymd'))) {
            $fileCount=1;
            while(file_exists(_BASEDIR_.DS."environment.ini." . date('Ymd') . "_" . $fileCount)) {
                $fileCount++;
            }
            $target .= "_".$fileCount;
        }
        rename(_BASEDIR_.DS."environment.ini", $target);
        $fp = fopen(_BASEDIR_.DS."environment.ini", "w+");
        if($fp != null) {
            $keys = array_keys($_SESSION['_ENV']);
            for($count = 0; $count < count($keys); $count++) {
                if(strlen(trim($keys[$count])) > 0) {
                    fwrite($fp, $keys[$count]."=".$_SESSION['_ENV'][$keys[$count]]."\n");
                }
            }
            fclose($fp);
        }
    }

}
?>