<?php
/**
 *
 * basics.php
 *
 * author : piwi
 *
 * created: 24.05.2015
 * changed: 24.05.2015
 *
 * purpose:
 *
 */

require_once(dirname(__FILE__). DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "baseStart.php");
$geshi = $base->getExtension("geshi");
?>
<!--

basics.html

 -->

<style>
.phpcode {
    text-align:left;
    font-weight:normal;
}
.phpcode code {
    display:block;
    white-space: pre;
}

</style>
<div class="centered">
    <div class="table" style="width:99%;margin: 0 auto;">
        <div class="trow">
            <div class="tcell ui-widget-header" style="text-align:center;">
                <h3>Basics</h3>
            </div>
        </div>
        <div class="trow">
            <div class="tcell ui-widget-content">
                <div style="text-align:left;height:400px;overflow:auto;">
                    Back to the roots - no - lets build a dynamic website:<br>
                    First of all you need the path to include the framework:<br>
                    lets say
<?php
$source = <<<EOT
<?php
// set the include_path to a path which includes the framework
// this can be everywhere you want and the webserver could reach
set_include_path(get_include_path().":/usr/share/pear/ptfw/current/");
/usr/share/pear/ptfw
EOT;
$geshi->set_source($source);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_language("php");
echo $geshi->parse_code();
?>
<br>
                    Then you need to build the base as follows:
<?php
$source = <<<EOT
<?php
// include the base-class
\$base = new cBase();
EOT;
$geshi->set_source($source);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_language("php");
echo $geshi->parse_code();
?>
                    <br><br>
                    Thats it. Nothing else is to be done to build an base-object.<br><br>
                    Ok, ok - now lets build a database-connection.<br><br>
                    Therefore you need a SQL-Object ... hmm ... we got the base.<br>
                    Now we can use something like this:
<?php
$source = <<<EOT
<?php
/**
 * make a SQL-Object for calling some databases
 */
\$oSQL = \$base->getSQL();
EOT;
$geshi->set_source($source);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_language("php");
echo $geshi->parse_code();
?>
<br><br>
                    Now lets say it is a MySQL-Database (Maria-DB as well i think)<br>
                    <br>
                    try to build a configuration for the database:<br>
                    <div style="border:1px solid lightgrey;">
<?php
$source1 = <<<EOT
/**
 * change this to your own needs
 */
\$DBHost['local'] = "localhost";
\$DBName['local'] = "test";
\$DBUser['local'] = "test";
\$DBPass['local'] = "test";
\$DBPort['local'] = "3006";
\$DBType['local'] = "MYSQL";
EOT;
$geshi->set_source($source1);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_language("php");
echo $geshi->parse_code();
?>
                    </div><br>
                    this has to be global and included or in the same file with this framework.<br>
                    And now you can use the previously generated SQL-Object as follows:<br>
                    <div style="border:1px solid lightgrey;">
<?php
$source2 = <<<EOT
/**
 * make the connection to database
 * every SQL-Object does it in the same way
 */
\$oSQL->makeNewConn("local");

// do the query
\$result = \$oSQL->makeQuery("select * from test");

// and display the result-array
print_r(\$result);
// or var_dump(\$result);
EOT;
$geshi->set_source($source2);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_language("php");
echo $geshi->parse_code();
?>
                    </div>
                    <br><br>
                    Now - lets try to build this in a complete file:<br>
                        <div style="border:1px solid lightgrey;">
<?php
$source3 = <<<EOT
<?php

// set the include_path to a path which includes the framework
// this can be everywhere you want and the webserver could reach
set_include_path(get_include_path().":/usr/share/pear/ptfw/current/");

// include the base-class
include_once("cBase.inc.php");

// and instantiate it
\$base = new cBase();

// now lets try to set the database
\$DBHost['local'] = "localhost";
\$DBName['local'] = "test";
\$DBUser['local'] = "test";
\$DBPass['local'] = "test";
\$DBPort['local'] = "3006";
\$DBType['local'] = "MYSQL";

/**
 * make a SQL-Object for calling some databases
 */
\$oSQL = \$base->getSQL();

/**
 * make the connection to database
 * every SQL-Object does this in the same way
 */
\$oSQL->makeNewConn("local");

// do the query
\$result = \$oSQL->makeQuery("select * from test");

// and display the result-array
print_r(\$result);
// or var_dump(\$result);

?>
EOT;
$geshi->set_source($source3);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_language("php");
echo $geshi->parse_code();
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>