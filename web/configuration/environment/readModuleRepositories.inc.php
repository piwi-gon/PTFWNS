<?php
/**
 *
 * readModuleRepositories.inc.php
 *
 * author : piwi
 *
 * created: 11.10.2015
 * changed: 11.10.2015
 *
 * purpose:
 *
 */

/**
 * first: include the basic-start
 */
require_once(__DIR__."/../lib/baseStart.php");

$repo = $base->getUpdateChecker()->querySystemRepository();

for($count = 0; $count < count($repo['modules']['reponame']); $count++) {
    if($count > 0) { $str .= "|"; }
    $str .= $count.",".$repo['modules']['reponame'][$count];
}
echo $str;
?>