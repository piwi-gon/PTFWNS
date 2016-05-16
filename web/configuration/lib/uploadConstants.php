<?php
/**
 *
 * uploadConstants.php
 *
 * author : piwi
 *
 * created: 15.03.2015
 * changed: 15.03.2015
 *
 * purpose:
 *
 */

ini_set("post_max_size", "30M");
ini_set("upload_max_filesize", "30M");
if(!defined("INSTALL_WORKDIR"))       { @define("INSTALL_WORKDIR", "/tmp/work/");       }
if(!file_exists(INSTALL_WORKDIR))     { mkdir(INSTALL_WORKDIR, 0777);                   }
if(!defined("INSTALL_UPLOADDIR"))     { @define("INSTALL_UPLOADDIR", "/tmp/upload/");   }
if(!file_exists(INSTALL_UPLOADDIR))   { mkdir(INSTALL_UPLOADDIR, 0777);                 }
if(!defined("MODULE_DIR"))            { @define("MODULE_DIR", BASE_DIR."modules/");     }
if(!file_exists(MODULE_DIR))          { mkdir(MODULE_DIR, 0777);                        }
if(!defined("EXTENSION_DIR"))         { @define("EXTENSION_DIR", BASE_DIR."external/"); }
if(!file_exists(EXTENSION_DIR))       { mkdir(EXTENSION_DIR, 0777);                     }
if(!defined("INSTALL_MODULEDIR"))     { define("INSTALL_MODULEDIR", "/pear/ptfw/modules"); }
?>