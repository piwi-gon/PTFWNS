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
        $index = $_POST['selectedModuleRepository'];
        $repo['modules']['auth'][$index]      = $_POST['repoAuthIsAuthValue'] == "true" ? "true" : "false";
        $repo['modules']['authfunc'][$index]  = $_POST['repoAuthAuthFuncValue'];
        $repo['modules']['modrepo'][$index]   = $_POST['repoURLValue'];
        $repo['modules']['username'][$index]  = $_POST['repoUserValue'];
        $repo['modules']['password'][$index]  = $_POST['repoPass1Value'];
        $repo['modules']['reponame'][$index]  = $_POST['selectedRepositoryName'];
    }
}
$base->getUpdateChecker()->writeSystemRepositories($repo, $_GET['deleteId']);
?>