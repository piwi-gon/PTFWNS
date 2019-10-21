<?php

///////////////////////////////////////////////////////////////////////////////

@define("WORKDIR",      "/tmp/workDir/");
@define("UPLOADDIR",    "/tmp/uploadDir/");
@define("ARCHIVEDIR",   "/tmp/archiveDir/");

///////////////////////////////////////////////////////////////////////////////
class cInstallExtension extends BASE {
///////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////
    //  public vars
    ///////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////
    //  public functions
    ///////////////////////////////////////////////////////////////////////

    public function __contructor() {
        ;
    }

    public function baseRun() { return $this; }

    public function cInstallExtension() {
        ;
    }

    public function getUploadForm() {
        $form  = "\n<form action=\"upload.php\" method=\"POST\" enctype=\"multipart/form-data\">\n";
        $form .= "<label for=\"uploadFile\">File:</label><input type=\"file\" name=\"uploadFile\"><br>\n";
        $form .= "<label for=\"uploadVersion\">Version:</label><input type=\"text\" name=\"uploadVersion\"><br>\n";
        $form .= "<input type=\"submit\" value=\"send\">";
        $form .= "</form>";
        return $form;
    }

    public function installUpdateFile($extName) {
        $this->_getVersionFromWebsite($extName);
        if(file_exists($this->_installDir)) {
            if(!file_exists($this->_installDir."/".$this->_version)) {
                mkdir($this->_installDir."/".$this->_version);
                // copy all Fie to new Location
                $this->_copyFiles(ARCHIVEDIR.$extName."/".$this->_version, $this->_installDir."/".$this->_version);
                // remove current active release
                unlink($this->_installDir."/current");
                // link new installed release
                $this->_createSymlink($this->_installDir."/".$this->_version, $this->_installDir."/current");
            }
        }
    }

    public function install($extName, $extArchive) {
        $this->_unpackArchive($extName, $extArchive);
        $this->_installDir = dirname(__FILE__).$extName;
        if(file_exists($this->_installDir)) {
            if(!file_exists($this->_installDir."/".$this->_version)) {
                mkdir($this->_installDir."/".$this->_version);
                // copy all Fie to new Location
                $this->_copyFiles(ARCHIVEDIR.$extName."/".$this->_version, $this->_installDir."/".$this->_version);
                // remove current active release
                unlink($this->_installDir."/current");
                // link new installed release
                $this->_createSymlink($this->_installDir."/".$this->_version, $this->_installDir."/current");
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////
    //  private vars
    ///////////////////////////////////////////////////////////////////////
        private $_installDir;
        private $_updateDir;
        private $_version;
        private $_installFileContent;
        private $_filename;
    ///////////////////////////////////////////////////////////////////////
    //  private functions
    ///////////////////////////////////////////////////////////////////////

    private function _createSymLink($source, $target) {
        if(!symlink($source, $target)) {
            return false;
        }
        return true;
    }

    private function _unpackArchive($extName, $extArchive) {
        // dont try to unzip a javascript-file (jquery.js)
        if(substr($extArchive,-3) == ".js") { return; }
        if(!file_exists(ARCHIVEDIR)) { mkdir(ARCHIVEDIR); }
        if(!file_exists(ARCHIVEDIR.$extName)) { mkdir(ARCHIVEDIR.$extName); }
        rename(UPLOADDIR.$extArchive, WORKDIR.$extArchive);
        $zip = new ZipArchive();
        $zip->open(WORKDIR.$extArchive);
        $zip->extractTo(ARCHIVEDIR.$extName);
        $zip->close();
    }

    private function _copyFiles($sourceDir, $targetDir) {
        $files = @scandir($sourceDir);
        for($count = 0; $count < count($files); $count++) {
            if($files[$count] != "." && $files[$count] != "..") {
                if(is_dir($sourceDir."/".$files[$count])) {
                    if(!file_exists($targetDir."/".$files[$count])) { mkdir($targetDir."/".$files[$count]); }
                    $this->_copyFiles($sourceDir."/".$files[$count], $targetDir."/".$files[$count]);
                } else if(is_file($sourceDir."/".$files[$count])) {
                    copy($sourceDir."/".$files[$count], $targetDir."/".$files[$count]);
                }
            }
        }
    }

    private function _getVersionFromWebSite($extName) {
        $this->_updateDir           = $this->getExtensionDir($extName);
        if(!file_exists($this->_updateDir."/updateVars.php")) { return "No check possible"; }
        include($this->_updateDir."/updateVars.php");
        $this->_installDir          = $this->getExtensionDir($extName)."/".$vars['filedir'];
        $this->_filename            = $vars['filename'];
        $siteToCheck                = $vars['available']['site'];
        $this->_installFileContent  = file_get_contents($siteToCheck);
        $searchStr   = $vars['available']['stringForPosition'];
        $pos         = strpos($this->_installFileContent, $searchStr);
        if($vars['available']['stringEndForPosition'] != "") {
            $offset = $pos+(strlen($vars['available']['stringForPosition']));
            $posEnd = strpos($this->_installFileContent, $vars['available']['stringEndForPosition'], $offset);
            $this->deb("Pos1: " . $pos . " <=> Pos2 " . $posEnd . ": Length: " . ($posEnd-($pos+strlen($searchStr))), "MSG", 2);
            echo("Pos1: " . $pos . " <=> Pos2 " . $posEnd . ": Length: " . ($posEnd-($pos+strlen($searchStr)))."<br>");
            $length = $posEnd - ($pos+strlen($searchStr));
        } else {
            $length = $vars['available']['lengthOfVersionString'];
        }
        $this->_version = substr($this->_installFileContent, $pos+strlen($searchStr), $length);
        if(strpos($this->_version, "v")===false) { $this->_version = "v".$this->_version; }
        if(!file_exists(ARCHIVEDIR)) { mkdir(ARCHIVEDIR); }
        if(!file_exists(ARCHIVEDIR.$extName)) { mkdir(ARCHIVEDIR.$extName); }
        if(!file_exists(ARCHIVEDIR.$extName."/".$this->_version)) { mkdir(ARCHIVEDIR.$extName."/".$this->_version); }
        $fp = fopen(ARCHIVEDIR.$extName."/".$this->_version."/".$this->_filename, "w+");
        if($fp !== null) {
            fwrite($fp, $this->_installFileContent);
            fclose($fp);
        }
        echo "<br><pre>Website:    " . $siteToCheck . "<br>";
        echo "length:     " . strlen($this->_installFileContent) . "<br>";
        echo "InstallDir: " . $this->_installDir . "<br>";
        echo "Version is: " . $this->_version . "<br>";
        echo "</pre>";
    }

///////////////////////////////////////////////////////////////////////////////
}
///////////////////////////////////////////////////////////////////////////////
?>