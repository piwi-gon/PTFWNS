<?php
/**
 *
 * devel_modules.php
 *
 * author : piwi
 *
 * created: 23.05.2015
 * changed: 23.05.2015
 *
 * purpose:
 *
 */

require_once(dirname(__FILE__). DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "baseStart.php");
$geshi = $base->getExtension("geshi");
if(!is_object($geshi) || $geshi == null) {
    die("No GeSHi available - please check");
}
?>
<!--

devel_modules.html

 -->
<div class="centered">
    <div class="table" style="width:99%!important;margin: 0 auto;overflow-x:hidden;">
        <div class="trow">
            <div class="tcell ui-widget-header" style="text-align:center;">
                <h3>Development - Modules</h3>
            </div>
        </div>
        <div class="trow">
            <div class="tcell ui-widget-content">
                <div id="develContentId" style="overflow:auto;height:400px;">
                    <div class="table" style="width:99%!important;margin: 0 auto;overflow-x:hidden;">
                        <div class="trow">
                            <div class="tcell" style="text-align:left;">
                                To build a Module nothing really revolutionary has to be done.<br>
                                Yes - theree has to be a special-structure but this structure is very simple.<br>
                                And yes - we build a class which must have the name of the module you want to build.<br>
                                <br>
                                But - thats all.<br><br>
                                Now lets see how a module could be:<br>
                                The Module-Class:<br>
                                <div style="border:1px solid lightgrey;">
<?php
$source = <<<EOT
/**
 * beware of the c at the beginning of the classname
 * this is like an identifier of a class for the framework
 */
class cYour-New-Module  {

    /**
     * the constant for the version of this class/module
     */
    const VERSION = "v1.0.0";

    /**
     * the constructor of this class
     */
    public function __construct() {
        /* your construction goes here if necessary */
    }

    /**
     * this function could be to show the version of the class
     */
    public function getVersion() { return self::VERSION; }

    /**
     * this is a function which you want to have
     */
    public function someFunction(\$someVar) {
        /* your function-code goes here */
    }

}
EOT;
//ob_start();
$geshi->set_source($source);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_language("php");
echo $geshi->parse_code();
?>
                                </div>
                                    <br>
                                    The folder-layout for a module ist like this:<br>
                                    <div style="border: 1px solid lightgrey;overflow:auto;width:100%;">
<?php
$struct = <<<EOT
    pear
    +- ptfw
        +- modules
            +- your-new-module
                +- current
                    +- cYour-New-Module.inc.php (or .php - as you want)
    manifest
EOT;
$geshi->set_source($struct);
//$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_language("ini");
echo $geshi->parse_code();
?>
                                    </div>
                                    <br>
                                    You see there is only one directory and a manifest-file.<br>
                                    <br>
                                    Now lets see how the manifest-file should be:<br>
                                    <div style="border: 1px solid lightgrey;">
<?php
$manifest = <<<EOT
ModuleName: Your-New-Module
main-class: cYour-New-Module
main-file: cYour-New-Module.inc.php (or .php - as you want)
classes: cYour-New-Module, cMaybe-Another-One
version: v1.0.0
realeased: 2015-04-21
author: the author (the.author@wherever.net)
description:
a valid description of your module (class)
EOT;
$geshi->set_source($manifest);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_language("ini");
echo $geshi->parse_code();
?>
                                    <br>
                                    I want to try to explain the manifest-file.<br><br>
                                    The last line for the configuration has to be the description-line.<br><br>
                                    The other lines could be mixed up, but they must exist - that's <b>IMPORTANT</b>.
                                    <br><br>
                                    The lines <code>main-class</code> and <code>main-file</code> are controlling the module.<br><br>
                                    The line <code>ModuleName</code> is the identifier for loading the class with.<br>
                                    I.e.: <code>$yourNewModuleObject = $base->getYour-New-Module()</code>.<br><br>
                                    The line <code>version</code> is for reading the currently installed version and to check if there is a new one in the repository.<br><br>
                                    The lines <code>released</code> and <code>author</code> are for the information who build the module and when.<br><br>
                                    And at least the line <code>classes</code> shows which classes are included by the module.<br><br>
                                    Thats all.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>