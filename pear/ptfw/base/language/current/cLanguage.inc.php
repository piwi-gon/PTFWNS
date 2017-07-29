<?php
/**
 *
 * cLanguage.inc.php
 *
 * author : piwi
 *
 * created: 09.05.2015
 * changed: 09.05.2015
 *
 * purpose:
 *
 */

namespace PTFW\Base\Lang;

if(!class_exists(cLanguage)) {

class cLanguage {

    const VERSION = "v1.0.0";

    private $_deb;

    public function __construct() {
        global $base;
        $this->_deb = $base->getDebug();
    }

    public function getVersion() { return self::VERSION; }

    public function setLanguage($language) {
        if(file_exists(dirname(__FILE__) . "/" . $language . "/" . $language . ".lng")) {
            if($_SESSION['language'] == null) {
                $_SESSION['language'] = parse_ini_file(dirname(__FILE__) . "/" . $language . "/" . $language . ".lng");
                $this->_deb->deb("loading language");
            } else {
                $this->_deb->deb("language already loaded");
            }
        } else {
            $this->_deb->deb("loading language failed due to: no file found");
        }
    }

    public function i18n($ident) {
        if($_SESSION['language'][$ident]!="") { return $_SESSION['language'][$ident];}
        return $ident;
    }

}
}
?>