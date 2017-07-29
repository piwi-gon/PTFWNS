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

@define("DS", DIRECTORY_SEPARATOR);
@define("isIni", true);
@define("_BASEDIR_", dirname(__FILE__));

class cBase {

    const _VERSION = "v1.0.1";

    private $_includePath = "";
    private $_updateCheckerObject;
    private $_moduleObject;
    private $_extObject;
    private $_MODS = array();
    private $_DEBUG;

    public function __construct() {
        include_once(_BASEDIR_ . DS . "base" . DS . "debug" . DS . "current" . DS . "cDebug.inc.php");
        $debugObject = new Base\Debug\cDebug();
        if(!is_object($debugObject)) { die("no debug-class installed - please contact your administrator"); }
        $this->_DEBUG = $debugObject;
        $this->getConfiguration()->loadConfiguration();
    }

    /**
     * this is the main debug-class
     *
     * to handle debug-messages
     */
    public function getDebug() {
        include_once(dirname(__FILE__) . DS . "base" . DS . "debug" . DS . "current" . DS . "cDebug.inc.php");
        $debugObject = new Base\Debug\cDebug();
        if(!is_object($debugObject)) { die("no debug-class installed - please contact your administrator"); }
        return $debugObject;
    }

    /**
     * this is the main-configuration-class
     *
     * to handle the index-files for the installed modules and extensions
     *
     * @return cConfiguration
     */
    public function getConfiguration() {
        include_once(dirname(__FILE__) . DS . "base" . DS . "configuration" . DS . "current" . DS ."cConfiguration.inc.php");
        $_moduleObject = new Base\Config\cConfiguration();
        if(!is_object($_moduleObject)) { die("no config-class installed - please contact your administrator"); }
        return $_moduleObject;
    }

    /**
     * the main language-class
     *
     * it handles the languages for some definitions
     *
     * @return cLanguage
     */
    public function getLanguage() {
        include_once(dirname(__FILE__) . DS . "base" . DS . "language" . DS . "current" . DS ."cLanguage.inc.php");
        $_moduleObject = new Base\Lang\cLanguage();
        if(!is_object($_moduleObject)) { die("no language-class installed - please contact your administrator"); }
        return $_moduleObject;
    }

    /**
     * the main-module-loader
     *
     * this class ist the loader for all the installed modules
     *
     * @return cModule
     */
    public function getModuleClass() {
        include_once(dirname(__FILE__) . DS . "base" . DS . "module" . DS . "current" . DS ."cModuleClass.inc.php");
        $_moduleObject = new Base\Modules\ModuleClass\cModuleClass();
        if(!is_object($_moduleObject)) { die("no module-class installed - please contact your administrator - ".
                                             "maybe you are not allowed to install new modules"); }
        return $_moduleObject;
    }

    /**
     * the main-extension-loader
     *
     * this class ist the loader for all the installed extensions
     *
     * @return cExtension
     */
    public function getExtensionClass() {
        include_once(dirname(__FILE__) . DS . "base" . DS . "module" . DS . "current" . DS ."cExtensionClass.inc.php");
        $_extObject = new Base\Modules\Extension\cExtensionClass();
        if(!is_object($_extObject)) { die("no extension-class installed - please contact your administrator - ".
                                          "maybe you are not allowed to install new extensions"); }
        return $_extObject;
    }

    /**
     * helper-class for using some io-specific functions
     *
     * @return cBaseFile
     */
    public function getBaseFile() {
        include_once(dirname(__FILE__) . DS . "base" . DS . "module" . DS . "current" . DS ."cBaseFile.inc.php");
        $_moduleObject = new Base\Modules\File\cBaseFile();
        if(!is_object($_moduleObject)) { die("no base-file-class installed - please contact your administrator"); }
        return $_moduleObject;
    }

    /**
     * helper-class to uncompress [.tar].gz-files
     *
     * @return cBaseTar
     */
    public function getBaseTar() {
        include_once(dirname(__FILE__) . DS . "base" . DS . "module" . DS . "current" . DS ."cBaseTar.inc.php");
        $_moduleObject = new Base\Modules\Tar\cBaseTar();
        if(!is_object($_moduleObject)) { die("no base-tar-class installed - please contact your administrator"); }
        return $_moduleObject;
    }

    /**
     * helper-class to check for updates
     *
     * @return cUpdateChecker
     */
    public function getUpdateChecker() {
        include_once(dirname(__FILE__) . DS . "base" . DS . "update" . DS . "current" . DS ."cUpdateChecker.inc.php");
        $_updateCheckerObject = new Base\Update\Checker\cUpdateChecker();
        if(!is_object($_updateCheckerObject)) { die("no base-tar-class installed - please contact your administrator"); }
        return $_updateCheckerObject;
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
            $relfectObj = new \ReflectionClass($className);
            echo "RName: " . $reflectObj->getNamespaceName()."\n";
            exit;
            return unserialize($_SESSION['_MOD'][$className]['class']);
        /**
         * if not - try to check if name is eistant in the basic-classes
         */
        } else {
            if(isset($this->_includePath)) { unset($this->_includePath); $this->_includePath = ""; }
            $className = $this->_queryBaseClass($method);
            if($className != null) {
                require_once($this->_includePath . DS . $className.".inc.php");
                $content = file_get_contents($this->_includePath . DS . $className.".inc.php");
                $nameSpace = $this->_byToken($content);
                $class = $nameSpace."\\".$className;
                $obj = new $class();
                if(is_object($obj)) {
                    //$this->_registerBASEObject($obj);
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
        if(!is_object($lng)) { $this->_DEBUG->deb(basename(__FILE__) . ":" . "Language-class could not be loded"); }
        else                 { $lng->setLanguage($language);                    }
    }

    public function i18n($ident) {
        $lng = $this->getLanguage();
        if(!is_object($lng)) { $this->_DEBUG->deb(basename(__FILE__) . ":" . "Language-class could not be loaded"); }
        else                 { $lng->i18n($ident);                    }
    }

    public function getExtension($extName) {
        if($extName == "" || strlen($extName) == 0) { $this->_DEBUG->deb("Nothing found"); return null; }
        $this->_DEBUG->deb("Num of extensions found: " . count($_SESSION['_EXT']));
        $ext = $_SESSION['_EXT'][$extName]['filename'];
        if(!$_SESSION['_EXT'][$extName]['active']) {
            $this->_DEBUG->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' not present or deactivated ");
            echo(basename(__FILE__) . ":" . "required extension '" . $extName . "' not present or deactivated\n");
        } else if(file_exists($ext)) {
            $this->_DEBUG->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' found!");
            include($ext);
            if($_SESSION['_EXT'][$extName]['additional']=="false") {
                /**
                 * now lets check if there is any namespace in
                 * selected extension
                 */
                /*
                if(class_exists($etxName)) {
                    $obj = new $extName();
                } else {
                */
                    $content = file_get_contents($ext);
                    $className = str_replace(".php", "", basename($ext));
                    $nameSpace = $this->_byToken($content);
                    if($nameSpace != "") {
                        $class = $nameSpace."\\".$className;
                    }
    //                echo "determined className: '" . $class . "'\n";
                    $obj = new $class();
                // }
                if($obj != null) { return $obj; }
            }
        } else {
            $this->_DEBUG->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' not found (path: " . $ext .")");
            echo(basename(__FILE__) . ":" . "required extension '" . $extName . "' not found (path: " . $ext .")");
        }
        return null;
    }

    public function getExtensionLibrary($extName) {
        if($extName == "" || strlen($extName) == 0) { $this->_DEBUG->deb("Nothing found"); return null; }
        $ext = $_SESSION['_EXT'][$extName]['filename'];
        if(!$_SESSION['_EXT'][$extName]['active']) {
            $this->_DEBUG->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' not present or deactivated ");
            echo(basename(__FILE__) . ":" . "required extension '" . $extName . "' not present or deactivated ");
        } else if(file_exists($ext)) {
            $this->_DEBUG->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' found!");
            require_once($ext);
            return true;
        } else {
            $this->_DEBUG->deb(basename(__FILE__) . ":" . "required extension '" . $extName . "' not found (path: " . $ext .")");
        }
        return false;
    }

    public function getExtensionDir($extName) {
        if($extName == "" || strlen($extName) == 0) { $this->_DEBUG->deb("Nothing found"); return ""; }
        $ext = $_SESSION['_EXT'][$extName];
        return dirname($ext);
    }

    private function _registerBASEObject(BASE $theObject) {
        if($theObject && $theObject instanceof BASE) {
            $_SESSION['_MOD'] += array(get_class($theObject)=>array("class"=>serialize($theObject), "path"=>$this->_includePath));
        }
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

    private function _byRegexp($src) {
        if (preg_match('#^namespace\s+(.+?);$#sm', $src, $m)) {
            return $m[1];
        }
        return null;
    }

    private function _byToken ($src) {
        $tokens = token_get_all($src);
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
}

abstract class BASE extends cBase {
    protected $name = null;
    protected $object = null;

    public function getName() {
        return $this->name;
    }

    abstract function baseRun();
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