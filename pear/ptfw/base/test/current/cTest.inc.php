<?php
/**
 *
 * cTest.inc.php
 *
 * author : piwi
 *
 * created: 26.07.2015
 * changed: 26.07.2015
 *
 * purpose: this class is used to build a menu for the module-tests
 *          sometimes it is ncecessary to show the users/developers
 *          what ist possible with current module
 *          with this class you can use a module-based menu where
 *          the functions can be tested
 *
 */

class cTest {

    public function __construct() {
        //
    }

    public function scanDirectory($dirType = "modules") {
        $entries = @scandir(_BASEDIR_ . DS . $dirType);
        for($count = 0; $count < count($entries); $count++) {
            if($entries[$count] != ".." && $entries[$count] != ".") {
                if(FILE_EXISTS(_BASEDIR_ . DS . "modules" . DS . $entries[$count] . "testSuite.inc.php")) {
                    $testSuites[$entries[$count]] = _BASEDIR_ . DS . "modules" . DS . $entries[$count] . "testSuite.inc.php";
                }
            }
        }
        return $testSuites;
    }
}