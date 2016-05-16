<?php
/**
 *
 * saveModuleRepository.inc.php
 *
 * author : piwi
 *
 * created: 19.07.2015
 * changed: 19.07.2015
 *
 * purpose:
 *
 */

/**
 * first: include the basic-start
 */
require_once(__DIR__."/../lib/baseStart.php");

$repo = $base->getUpdateChecker()->querySystemRepository();

if($_GET['deleteId'] == "") {
    if($_GET['isSystem']) {
        $repo['system']['auth']         = $_POST['systemRepoIsAuthValue'] != "" ? "true" : "false";
        $repo['system']['authfunc']     = $_POST['systemRepoAuthFuncValue'];
        $repo['system']['repository']   = $_POST['systemRepoURLValue'];
        $repo['system']['username']     = $_POST['systemRepoUserValue'];
        $repo['system']['password']     = $_POST['systemRepoPass1Value'];
    } else {
        $repo['modules']['auth'][]      = $_POST['AUTHENTICATION'] != "" ? "true" : "false";
        $repo['modules']['authfunc'][]  = $_POST['moduleAuthFunc'];
        $repo['modules']['modrepo'][]   = $_POST['serviceURL'];
        $repo['modules']['username'][]  = $_POST['moduleUser'];
        $repo['modules']['password'][]  = $_POST['modulePass'];
        $repo['modules']['reponame'][]  = $_POST['repoName'];
    }
}
$base->getUpdateChecker()->writeSystemRepositories($repo, $_GET['deleteId']);
?>