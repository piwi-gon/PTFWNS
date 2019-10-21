<?php

// start a session
session_start();

// get the location of frameweork
define("BASE_DIR", "/usr/share/pear/ptfwNS/");
define("_BASEDIR_", "/usr/share/pear/ptfwNS/");

// ste the include-path to it
set_include_path(get_include_path() . PATH_SEPARATOR . BASE_DIR);

// lets say, the session is loaded
$_SESSION['AGENT']['LOADED'] = "true";
error_reporting(E_ALL&~E_NOTICE);
ini_set("display_errors", 1);

@define("DS", DIRECTORY_SEPARATOR);

/**
 * this builds the psr-4 autoloaded index of classes to be included
 *
 * @var object $_psr4Object
 */
$_psr4Object = new cPSRClassLoader();
$_psr4Object->buildPrefixesFromDir(_BASEDIR_."PTFW");
$_psr4Object->register();

/**
 * now we can use the base-class in order to fetch all other
 * classes via $obj = $base->get{ClassName}();
 */
use PTFW\cBase;

$base = new cBase();
if(!is_object($base)) { echo "Start failed - No base-class found - exiting..." . (PHP_SAPI !== "cli"?"<br>":"\n"); exit(); }
else { $base->getDebug()->deb("Base-class loaded"); }

/**
 * this si a PSR-4 class loader which uses the __autoload and
 * spl_autoload_register functions from php
 *
 * it handles all internal-modules and/or other classes to be loaded
 * automatically by determining them from a given directory
 *
 * Attention: it uses namespaced classes only!
 *
 * @author klaus
 *
 */
class cPSRClassLoader {
    /**
     * @var array
     */
    private $prefixes = array();

    /**
     * just for having it ...
     */
    public function __construct() {}

    /**
     * building prefixes from a given directory
     * @param string $baseDir
     */
    public function buildPrefixesFromDir($baseDir) {
        if($baseDir == "") { return; }
        $files = @scandir($baseDir);
        foreach($files as $file) {
            if($file != "." && $file != "..") {
                // echo "reading '" . $file . "' ...\n";
                if(is_dir($baseDir.DIRECTORY_SEPARATOR.$file)) {
                    $this->buildPrefixesFromDir($baseDir.DIRECTORY_SEPARATOR.$file);
                } else {
                    if (preg_match( "/.php$/i" , $file ) ) {
                        $prefix = $this->_byToken($baseDir.DIRECTORY_SEPARATOR.$file);
                        if($prefix != null) {
                            if(!array_key_exists($prefix, $this->prefixes)) {
                                $this->prefixes[$prefix] = array($prefix=>$baseDir);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * return the used prefixes
     * @return array
     */
    public function getPrefixes() {
        return $this->prefixes;
    }

    /**
     * @param string $prefix
     * @param string $baseDir
     */
    public function addPrefix($prefix, $baseDir) {
        $prefix = trim($prefix, '\\').'\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $this->prefixes[] = array($prefix => $baseDir);
    }
    /**
     * @param string $class
     *
     * @return string|null
     */
    public function findFile($class) {
        $class = ltrim($class, '\\');
        $prefKeys = array_keys($this->prefixes);
        foreach ($prefKeys as $prefix) { // list($currentPrefix, $currentBaseDir)) {
            if (0 === strpos($class, $prefix)) {
                $classWithoutPrefix = substr($class, strlen($prefix));
                $file = $this->prefixes[$prefix].str_replace('\\', DIRECTORY_SEPARATOR, $classWithoutPrefix).'.php';
                if(!file_exists($file)) {
                    $file = $this->prefixes[$prefix].str_replace('\\', DIRECTORY_SEPARATOR, $classWithoutPrefix).'.inc.php';
                }
                // echo $file."\n";
                if (file_exists($file)) {
                    return $file;
                }
            }
        }
    }
    /**
     * @param string $class
     *
     * @return bool
     */
    public function loadClass($class) {
        $file = $this->findFile($class);
        if (null !== $file) {
            require $file;
            return true;
        }
        return false;
    }
    /**
     * Registers this instance as an autoloader.
     *
     * @param bool $prepend
     */
    public function register($prepend = false) {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }
    /**
     * Removes this instance from the registered autoloaders.
     */
    public function unregister() {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * this tries to determine the namespace-name
     * which is required to be used for psr-4
     */
    private function _byToken ($src) {
        $tokens = token_get_all(file_get_contents($src));
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