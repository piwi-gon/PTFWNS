<?php
/**
 * cPSRClassLoader.inc.php
 *
 * author: klaus
 *
 * created: 20.07.2018
 * changed: 20.07.2018
 *
 */

namespace PTFW\Base\PSR;

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
                            /*
                             $found = false;
                             foreach($this->prefixes as $inPrefix) {
                             if($inPrefix[0] == $prefix) { $found = true; }
                             }
                             if(!$found) {
                             */
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