<?php
/**
 *
 * cBase.inc.php
 *
 * author : piwi
 *
 * created: 06.12.2014
 * changed: 06.12.2014
 *
 * purpose: main base-class for handling this framework
 *          with this framework ist is possible to integrate
 *          your own classes if necessary.
 *          ist possible too, to integrate some other libraries
 *          such as tcpdf, barcode, nusoap and so on
 *
 *          to call the class to ues which is integrated in modules:
 *          i.e.: $base->getDate to get the class cDate (with their sub-classes!)
 *
 *          to call the class to ues which is integrated in extensions:
 *          i.e.: $base->getExtension('nusoap'); to get external library nusoap
 *
 */

namespace PTFW;

/**
 * the Debug-Class
 * is to be used to store and display some debug-messages
 */
use PTFW\Base\Debug\cDebug;
/**
 * the configiuration-class
 */
use PTFW\Base\Config\cConfiguration;
/**
 * the language-class
 */
use PTFW\Base\Lang\cLanguage;
/**
 * the module-loader-class
 */
use PTFW\Base\Module\ModuleClass\cModuleClass;
/**
 * the extension-loader-class
 */
use PTFW\Base\Module\Extension\cExtensionClass;
/**
 * the file-class
 */
use PTFW\Base\Module\File\cBaseFile;
/**
 * the tar-class
 */
use PTFW\Base\Module\Tar\cBaseTar;
/**
 * the update-checker-class
 */
use PTFW\Base\Update\Checker\cUpdateChecker;

/**
 * just a define to use DS instead of DIRECTORY_SEPARATOR
 */
@define("DS", DIRECTORY_SEPARATOR);

/**
 * just a define to use PS instead of PATH_SEPARATOR
 */
@define("PS", PATH_SEPARATOR);

/**
 * this define is to be used to set to use an ini-file or not
 */
@define("isIni", true);

/**
 * this defines the base-directory where the framewrok lives in
 */
@define("_BASEDIR_", dirname(__FILE__));

class cBase {

    const _VERSION = "v1.0.1";


    private $_includePath = "";

    private $_debugObject = null;
    private $_languageObject = null;
    private $_configurationObject = null;
    private $_moduleObject = null;
    private $_debugObject = null;
    private $_extenstionObject = null;
    private $_fileObject = null;
    private $_tarObject = null;
    private $_updateCheckObject = null;
    private $_psr4Object = null;

    private $_MODS = array();

    /**
     * the constructor loads base-classes and configuration
     *
     * it registeres the built-in shutdown-function too
     *
     */
    public function __construct() {
        register_shutdown_function(array($this, "shutDown"));
    }

    public function shutDown() {
        $error = error_get_last();
        if ($error['type'] === E_ERROR) {
            $this->_formatError($error["type"], $error["message"], $error["file"], $error["line"]);
        }
    }

    /**
     * this is the main debug-class
     *
     * to handle debug-messages
     */
    public function getDebug() {
        return $this->_debugObject;
    }

    /**
     * this is the main-configuration-class
     *
     * to handle the index-files for the installed modules and extensions
     *
     * @return cConfiguration
     */
    public function getConfiguration() {
        return $this->_configurationObject;
    }

    /**
     * the main language-class
     *
     * it handles the languages for some definitions
     *
     * @return cLanguage
     */
    public function getLanguage() {
        return $this->_languageObject;
    }

    /**
     * the main-module-loader
     *
     * this class ist the loader for all the installed modules
     *
     * @return cModuleClass
     */
    public function getModuleClass() {
        return $this->_moduleObject;
    }

    /**
     * the main-extension-loader
     *
     * this class ist the loader for all the installed extensions
     *
     * @return cExtensionClass
     */
    public function getExtensionClass() {
        return $this->_extenstionObject;
    }

    /**
     * helper-class for using some io-specific functions
     *
     * @return cBaseFile
     */
    public function getBaseFile() {
        return $this->_fileObject;
    }

    /**
     * helper-class to uncompress [.tar].gz-files
     *
     * @return cBaseTar
     */
    public function getBaseTar() {
        return $this->_tarObject;
    }

    /**
     * helper-class to check for updates
     *
     * @return cUpdateChecker
     */
    public function getUpdateChecker() {
        return $this->_updateCheckObject;
    }

    /**
     * class for loading all libraries via autoloader (spl_autoload)
     */
    public function getPSRClassLoader() {
        return $this->_psr4Object;
    }

    /**
     * here is where the magic starts - the __call-method of php is widely used
     * to call a class
     *
     * the method is the class-name and the args for this class (if necessary)
     *
     * @param string $method
     * @param mixed $args
     * @return object|NULL
     */
    public function __call($method, $args) {
        $className = str_replace("get", "c", $method);
        /**
         * check if the class exists in the base-objects installed
         */
        if(array_key_exists($className, $_SESSION['_MOD'])) {
            $this->getDebug()->deb("searched class: " . $className);
            $this->getDebug()->deb("using preloaded class");
            $this->_includePath = $_SESSION['_MOD'][$className]['path'];
            require_once($this->_includePath . DS . $className . ".inc.php");
            $reflectObj = new \ReflectionClass($className);
            die("RName: " . $reflectObj->getNamespaceName()."\n");
            exit;
            return unserialize($_SESSION['_MOD'][$className]['class']);
        /**
         * if not - try to check if name is existant in the basic-classes
         * if aoutloading is working nothing else has to be done
         */
        } else {
            if(isset($this->_includePath)) { unset($this->_includePath); $this->_includePath = ""; }
            $className = $this->_queryBaseClass($method);
            if($className != null) {
                require_once($this->_includePath . DS . $className.".inc.php");
//                 $content = file_get_contents($this->_includePath . DS . $className.".inc.php");
//                 $nameSpace = $this->_byToken($content);
//                 $class = $nameSpace."\\".$className;
                $class = $className;
                $obj = new $class();
                if(is_object($obj)) {
                    return $obj;
                } else {
                    echo "object not instantiated (searched for: " . $this->_includePath . DS . $className . ".inc.php".")\n";
                }
            } else {
                if($className=="") {
                    $this->getDebug()->deb("no class found");
                }
            }
            return null;
        }
    }

    public function getVersion() {
        return self::_VERSION;
    }

    public function checkVersion() {
        return self::_VERSION;
    }

    public function setLanguage($language) {
        $lng = $this->getLanguage();
        if(!is_object($lng)) { $this->getDebug()->deb(basename(__FILE__) . ":" . "Language-class could not be loded"); }
        else                 { $lng->setLanguage($language); }
    }

    public function i18n($ident) {
        $lng = $this->getLanguage();
        if(!is_object($lng)) { $this->getDebug()->deb(basename(__FILE__) . ":" . "Language-class could not be loaded"); }
        else                 { return $lng->i18n($ident); }
    }

    public function getExtension($extName) {
        if($extName == "" || strlen($extName) == 0) { $this->getDebug()->deb("Nothing found"); return null; }
        $this->getDebug()->deb("Num of extensions found: " . count($_SESSION['_EXT']));
        $ext = $_SESSION['_EXT'][$extName]['filename'];
        if(!$_SESSION['_EXT'][$extName]['active']) {
            $this->getDebug()->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' deactivated ");
            echo(basename(__FILE__) . ":" . "required extension '" . $extName . "' deactivated\n");
        } else if(file_exists($ext)) {
            $this->getDebug()->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' found!");
            include($ext);
            if($_SESSION['_EXT'][$extName]['additional']=="false") {
                /**
                 * now lets check if there is any namespace in
                 * selected extension
                 */
                $content = file_get_contents($ext);
                $className = str_replace(".php", "", basename($ext));
                $nameSpace = $this->_byToken($content);
                if($nameSpace != "") {
                    $class = $nameSpace."\\".$className;
                }
                $obj = new $class();
                if($obj != null) { return $obj; }
            }
        } else {
            $this->getDebug()->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' not found (path: " . $ext .")");
            echo(basename(__FILE__) . ":" . "required extension '" . $extName . "' not found (path: " . $ext .")");
        }
        return null;
    }

    public function getExtensionLibrary($extName) {
        if($extName == "" || strlen($extName) == 0) { $this->getDebug()->deb("Nothing found"); return null; }
        $ext = $_SESSION['_EXT'][$extName]['filename'];
        if(!$_SESSION['_EXT'][$extName]['active']) {
            $this->getDebug()->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' not present or deactivated ");
            echo(basename(__FILE__) . ":" . "required extension '" . $extName . "' not present or deactivated ");
        } else if(file_exists($ext)) {
            $this->getDebug()->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' found!");
            require_once($ext);
            return true;
        } else {
            $this->getDebug()->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' not found (path: " . $ext .")");
        }
        return false;
    }

    public function getExtensionDir($extName) {
        if($extName == "" || strlen($extName) == 0) { $this->getDebug()->deb("Nothing found"); return ""; }
        $ext = $_SESSION['_EXT'][$extName];
        return dirname($ext);
    }

    /**
     * this function loads all necessary classes to work with this framework
     *
     * it includes all available classes and stops if anything went wrong
     */
    private function _loadBaseClasses() {
        $this->_debugObject = new cDebug();
        if(!is_object($this->_debugObject)) { die("no debug-class installed - please contact your administrator"); }

        $this->_configurationObject = new cConfiguration();
        if(!is_object($this->_configurationObject)) { die("no config-class installed - please contact your administrator"); }

        $this->_languageObject = new cLanguage();
        if(!is_object($this->_languageObject)) { die("no language-class installed - please contact your administrator"); }

        $this->_moduleObject = new cModuleClass();
        if(!is_object($this->_moduleObject)) { die("no module-class installed - please contact your administrator - ".
                                                   "maybe you are not allowed to install new modules"); }

        $this->_extenstionObject = new cExtensionClass();
        if(!is_object($this->_extenstionObject)) { die("no extension-class installed - please contact your administrator - ".
                                                       "maybe you are not allowed to install new extensions"); }

        $this->_fileObject= new cBaseFile();
        if(!is_object($this->_fileObject)) { die("no base-file-class installed - please contact your administrator"); }

        $this->_tarObject = new cBaseTar();
        if(!is_object($this->_tarObject)) { die("no base-tar-class installed - please contact your administrator"); }

        $this->_updateCheckObject = new cUpdateChecker();
        if(!is_object($this->_updateCheckObject)) { die("no updatechecker-class installed - please contact your administrator"); }

        $this->_psr4Object = new Base\PSR\cPSRClassLoader();
        if(!is_object($this->_psr4Object)) { die("no PSRClassLoader-class installed - please contact your administrator"); }
    }

    /**
     * function to determine the class to be included
     * it could be cClassExample or ClassExample
     * the filename must be similar to it (i.E.: cClassExample.inc.php or ClassExample.inc.php)
     *
     * it could NOT be a trait - doesnt work unless you use it in a another class
     *
     * @param string $method        the Method which is called and what the ClassName is
     * @param string $currentDir    the Directory where the class are locatet in
     * @return string               the ClassName
     */
    private function _queryBaseClass($method, $currentDir="") {
        $className  = str_replace("get", "c", $method);
        $className2 = str_replace("get", "", $method);
        if($currentDir == "") { $currentDir = dirname(__FILE__) . DS . "modules"; }
        $this->getDebug()->deb(basename(__FILE__) . ":" . $method.": ".$currentDir, "MSG");
        $content = file_get_contents(dirname(__FILE__) . DS . "index" . DS . "modules.idx");
        $lines = explode("\n", $content);
        for($count = 0; $count < count($lines); $count++) {
            if(substr($lines[$count], 0, 1) != ";" && strlen(trim($lines[$count])) > 0) {
                $tokens = explode("=", $lines[$count]);
                $clsTokens = explode("|", trim($tokens[1]));
                $this->getDebug()->deb("classPath: '" . dirname($clsTokens[0]) . " => file " . basename($clsTokens[0]));
                if(str_replace(".inc.php", "", basename($clsTokens[0])) == $className) {
                    $this->_includePath = $currentDir . DS . dirname($clsTokens[0]);
                    $retClass = $className;
                    $count = count($lines);
                } else if(str_replace(".inc.php", "", basename($clsTokens[0])) == $className2) {
                    $this->_includePath = $currentDir . DS . dirname($clsTokens[0]);
                    $retClass = $className2;
                    $count = count($lines);
                }
            }
        }
        return $retClass;
    }

    private function _generateDebugMessageByLevel($which) {
        $splitter = "---------------------\n";
        if($which == "MSG" && is_array($_SESSION['_DEBUG']['MSG'][$_SESSION['_ENV']['DEBUGLEVEL']])) {
            $msg .= $splitter."INFO:\n".$splitter.join("\n", $_SESSION['_DEBUG']['MSG'][$_SESSION['_ENV']['DEBUGLEVEL']])."\n".$splitter;
        }
        if($which == "SQL" && is_array($_SESSION['_DEBUG']['SQL'][$_SESSION['_ENV']['DEBUGLEVEL']])) {
            $msg .= $splitter."SQL:\n".$splitter.join("\n", $_SESSION['_DEBUG']['SQL'][$_SESSION['_ENV']['DEBUGLEVEL']])."\n".$splitter;
        }
        if($which == "CLS" && is_array($_SESSION['_DEBUG']['CLS'][$_SESSION['_ENV']['DEBUGLEVEL']])) {
            $msg .= $splitter."CLASS:\n".$splitter.join("\n", $_SESSION['_DEBUG']['CLS'][$_SESSION['_ENV']['DEBUGLEVEL']])."\n".$splitter;
        }
        return $msg;
    }

    private function _readAvailableExtensions() {
        if(isset($_SESSION['_EXT'])) { unset($_SESSION['_EXT']); }
        $keys = array_keys($_SESSION['_INI']['extensions']);
        for($count = 0; $count < count($keys); $count++) {
            $ext = $_SESSION['_INI']['extensions'][$keys[$count]];
            if(file_exists(dirname(__FILE__) . "/external/" . $ext)) {
                $_SESSION['_EXT'][$keys[$count]] = dirname(__FILE__) . "/external/" . $ext;
            }
        }
    }

    /**
     * this function tries to determine the namespace
     *
     * works in every situation
     *
     * @param string $file
     * @return NULL|string
     */
    private function _byToken ($file) {
        $tokens = token_get_all($file);
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespace_ok = false;
        while ($i < $count) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < $count) {
                    if ($tokens[$i] === ';') {
                        $namespace_ok = true;
                        $namespace = trim($namespace);
                        break;
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }
                break;
            }
            $i++;
        }
        if (!$namespace_ok) {
            return null;
        } else {
            return $namespace;
        }
    }

    private function _formatError($errno, $errstr, $errfile, $errline) {
        $trace = print_r( debug_backtrace( false ), true );
        $content = '
        <link rel="stylesheet" href="css/jqueryui/jquery-ui.css">
        <link rel="stylesheet" href="css/jquery.messagebox.css">
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/grid.css">
        <div class="table99">
            <div class="trow">
                <div class="tcell20 ui-widget-header h40 f12b vtop">Error</div>
                <div class="tcell80 ui-widget-content f12 vtop" style="height:120px;overflow:auto;"><pre>'.$errstr.'</pre></div>
            </div>
            <div class="trow">
                <div class="tcell20 ui-widget-header h40 f12b vtop">ErrorNo.</div>
                <div class="tcell80 ui-widget-content f12 vtop"><pre>'.$errno.'</pre></div>
            </div>
            <div class="trow">
                <div class="tcell20 ui-widget-header h40 f12b vtop">File / Line</div>
                <div class="tcell80 ui-widget-content f12 vtop">'.$errfile.' on Line '.$errline.'</div>
            </div>
            <div class="trow">
                <div class="tcell20 ui-widget-header h40 f12b vtop" style="height:120px;overflow:auto;">Back-Trace</div>
                <div class="tcell80 ui-widget-content f12 vtop"><div style="width:100%;height:240px;overflow:auto;"><pre>'.$trace.'</pre></div></div>
            </div>
        </div>';
        echo $content;
    }

}

abstract class BASESOAP extends \SoapServer {
    protected $name = null;
    protected $object = null;

    public function getName() {
        return $this->name;
    }

    abstract function baseSoapRun();
}

?>