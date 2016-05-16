<?php
/**
 *
 * cFile.inc.php
 *
 * author : piwi
 *
 * created: 23.02.2015
 * changed: 23.02.2015
 *
 * purpose:
 *
 */

namespace PTFW\Base\Module\File;

class cBaseFile {

    const VERSION  = "v1.0.0";

    private $_deb;

    /**
     * constrcutor of this class
     *
     * @access public
     *
     * @return cFile
     *
     */
    public function __construct() {
        global $base;
        $this->_deb = $base;
    }

    public function getVersion() { return self::VERSION; }

    public function baseRun() { return $this; }

    public function uncompress($archiveName, $target) {
        if(strtolower(substr($archiveName, -3)) == "zip") {
            $this->_unzipFile($archiveName, $target);
            $installDir = substr($archiveName, 0, (strlen($archiveName)-4));
        } else if(strtolower(substr($archiveName,-6)) == "tar.gz") {
            $this->_untarFile($archiveName, $target);
            $installDir = substr($archiveName, 0, (strlen($archiveName)-7));
        }
        return basename($installDir);
    }

    public function doCopy($source, $target, $recursive=false) {
        if(is_dir($source)) {
            if(!file_exists($target)) { $this->_deb->deb("try to create directory '".$target."'"); mkdir( $target ); }
            $objects = scandir($source);
            if(sizeof($objects) > 0 ) {
                foreach($objects as $file) {
                    if($file == "." || $file == ".." || $file == ".svn") { continue; }
                    if(is_dir($source.DIRECTORY_SEPARATOR.$file) && $recursive) { $this->doCopy($source.DIRECTORY_SEPARATOR.$file, $target.DIRECTORY_SEPARATOR.$file, true); }
                    else {
                        error_log("copying file '" . $source . DIRECTORY_SEPARATOR . $file . "' to ". $target . DIRECTORY_SEPARATOR . "\n", 3, "/tmp/soapaction.log");
                        $this->_deb->deb("copying file '".$file."'");
                        copy( $source.DIRECTORY_SEPARATOR.$file, $target.DIRECTORY_SEPARATOR.$file );
                        chmod($target.DIRECTORY_SEPARATOR.$file, 0666);
                    }
                }
            }
            return true;
        }
        else if(is_file($source)) { return copy($source, $target); }
        else { return false; }
    }

    function doRMDir($dir) {
        if(substr($dir,0,4)=="/tmp") {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir."/".$object) == "dir") { $this->doRMDir($dir."/".$object); }
                        else                                     { unlink($dir."/".$object);         }
                    }
                }
                reset($objects);
                rmdir($dir);
            }
        } else {
            echo "Keine Installation aus dem '/tmp'-Dir - keine L&oumlschung m&ouml;glich!<br>";
        }
    }

    public function displayDebug() {
        echo "<pre>"; print_r($_SESSION['MSG']); echo "</pre>";
        unset($_SESSION['MSG']);
    }

    private function _unzipFile($archive, $target) {
        $zip = new ZipArchive();
        $result = $zip->open($archive);
        if($result) {
            $zip->extractTo($target);
            $zip->close();
        } else {
            die("could not extract zip-archive");
            exit;
        }
    }

    private function _untarFile($archive, $target) {
        global $base;
        $unTar = $base->getBaseTar();
        if(!is_object($unTar)) {
            die("No untar-class installed - please install the untar-class to uncompress and untar a .tar.gz-archive");
        } else {
            $unTar->open($archive);
            error_log("untar '".$archive."' to following location '".$target."'\n", 3, "/tmp/soapaction.log");
            if(!is_object($unTar)) { error_log("no untar available\n", 3, "/tmp/soapaction.log"); die("no untar available"); }
            $result = $unTar->extract($target);
            if($result == false) {
                error_log("untar '".$archive."' failed\n", 3, "/tmp/soapaction.log");
            }
        }
    }

}
?>