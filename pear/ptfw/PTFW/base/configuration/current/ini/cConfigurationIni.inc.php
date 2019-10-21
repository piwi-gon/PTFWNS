<?php
/**
 *
 * cConfigurationIni.inc.php
 *
 * author : piwi
 *
 * created: 09.05.2015
 * changed: 09.05.2015
 *
 * purpose: this class is used to load the configuration
 *          of this framework-based installed modules and
 *          extensions
 *
 *          it uses the files modules.idx and extension.idx
 *
 *          for further usage and installation it uses
 *          repositories.idx too
 *
 */

namespace PTFW\Base\Config\Ini;

class cConfigurationIni extends \PTFW\Base\Config\cConfiguration {

    const VERSION = "v1.0.0";

    public function __construct() {
    }

    public function getVersion() { return self::VERSION; }

    public function loadFurtherConfiguration() {
        if($_SESSION['_ENV']['USE_EXTENSIONS']) {
            $this->_readAvailableExtensions();
        }
        if($_SESSION['_ENV']['USE_MODULES']) {
            $this->_readAvailableModules();
        }
    }

    public function getListOfExtensions() {
        $content = file_get_contents(BASE_DIR . DS . "index" . DS . "extensions.idx");
        $lines   = explode("\n", $content);
        $numOfLines = count($lines);
        $numOfExtensions=0;
        for($count = 0; $count < $numOfLines; $count++) {
            if(substr($lines[$count], 0, 1) != ";" && strlen(trim($lines[$count])) > 0) {
                $ident  = explode("=", $lines[$count]);
                $tokens = explode("|", trim($ident[1]));
                $EXTARRAY[] = array("ident" => $ident[0], "name"=>basename($tokens[0]), "path"=>dirname($tokens[0]), "version"=>$tokens[1], "active"=>$tokens[2], "additional" => $tokens[3]);
                $numOfExtensions++;
            }
        }
        return $EXTARRAY;
    }

    public function getListOfModules() {
        global $moduleConnector;
        $content = file_get_contents(BASE_DIR . DS . "index" . DS . "modules.idx");
        $lines   = explode("\n", $content);
        $numOfLines = count($lines);
        $numOfExtensions=0;
        for($count = 0; $count < $numOfLines; $count++) {
            if(substr($lines[$count], 0, 1) != ";" && strlen(trim($lines[$count])) > 0) {
                $lTokens = explode("=", $lines[$count]);
                $identifier = trim($lTokens[0]);
                $lineTokens = explode("|", trim($lTokens[1]));
                $state  = ((($count+1)%2)==0) ? "ui-widget-content" : "ui-widget-content-alt";
                $MODARRAY[] = array("moduleName"    =>  $identifier,
                                    "name"          =>  str_replace(".inc.php", "", basename($lineTokens[0])),
                                    "path"          =>  dirname($lineTokens[0]),
                                    "version"       =>  $lineTokens[1],
                                    "active"        =>  $lineTokens[2],
                                    "additional"    => $lineTokens[3],
                                    "moduleState"   => $state,
                                    "repository"    => $this->_getRepositoryFromManifest(str_replace("/current", "", dirname($lineTokens[0])) . DS . "manifest")
                );
                $numOfExtensions++;
            }
        }
        return $MODARRAY;
    }

    private function _readAvailableModules() {
        if(isset($_SESSION['_MOD'])) { unset($_SESSION['_MOD']); }
        $this->getDebug()->deb("reading index: '" . _BASEDIR_ . DS . "index" . DS . "modules.idx", "MSG");
        $content = file_get_contents(_BASEDIR_ . DS . "index" . DS . "modules.idx");
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            if(substr($lines[$count], 0, 1) != ";" && strlen(trim($lines[$count])) > 0) {
                $modCounter++;
                $tokens = explode("|", $lines[$count]);
                $state  = ((($count+1)%2)==0) ? "ui-widget-content" : "ui-widget-content-alt";
                $moduleName = str_replace(".inc.php", "", substr(basename($tokens[0]),1));
                $_SESSION['_MOD'][$moduleName] = $tokens[0];
                $_SESSION['_MOD'][$moduleName] = array("flename"=>$tokens[0], "active"=>(strtolower(trim($tokens[2])) == "true" ? true : false));
            }
        }
        if(!isset($_SESSION['_MOD'])) { $_SESSION['_MOD'] = array(); }
    }

    private function _readAvailableExtensions() {
        if(isset($_SESSION['_EXT'])) { unset($_SESSION['_EXT']); }
        $this->getDebug()->deb("reading index: '" . _BASEDIR_ . DS . "index" . DS . "extensions.idx", "MSG");
        $content = file_get_contents(_BASEDIR_ . DS . "index" . DS . "extensions.idx");
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            if(substr($lines[$count],0,1) != ";" && strlen(trim($lines[$count]))>0) {
                $tokens = explode("|", trim($lines[$count]));
                $fTokens = explode("=", trim($tokens[0]));
                $identifier = trim($fTokens[0]);
                $fName = _BASEDIR_ . DS . "external" . DS . trim($fTokens[1]);
                $this->getDebug()->deb($fName . " (" . $identifier . ")");
                if(file_exists($fName)) {
                    $_SESSION['_EXT'][$identifier] = array("filename"=>$fName, "active"=>(strtolower(trim($tokens[2])) == "true" ? true : false));
                }
            }
        }
    }

    function _getRepositoryFromManifest($manifestFile) {
        $manifestFile = $startDir = _BASEDIR_ . DS . "modules" . DS . $manifestFile;
        if(file_exists($manifestFile)) {
            $content = file_get_contents($manifestFile);
            $lines = explode("\n", $content);
            for($count = 0; $count < count($lines); $count++) {
                $actLine = trim($lines[$count]);
                $index = substr($actLine, 0, strpos($lines[$count], ":"));
                switch($index) {
                    case "repository"   : $repo = trim(substr($actLine, strpos($lines[$count], ":")+1));    break;
                }
            }
            return $repo;
        }
        return "n.a.";
    }
}
?>