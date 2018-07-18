<?php
/**
 * saveRepositories.inc.php
 *
 * author: klaus
 *
 * created: 18.07.2018
 * changed: 18.07.2018
 *
 */


/**
 * first: include the basic-start
 */
require_once(__DIR__."/../lib/baseStart.php");

$repo = $base->getUpdateChecker()->querySystemRepository();
print_r($_POST);
print_r($_GET);
if($_GET['deleteId'] == "") {
    if($_GET['isSystem']) {
        $repo['system']['active_repo_type'] = $_POST['activeRepositoryType'];
        $repo['system']['auth']             = $_POST['systemRepoIsAuthValue'] != "" ? "true" : "false";
        $repo['system']['authfunc']         = $_POST['systemRepoAuthFuncValue'];
        $repo['system']['repository']       = $_POST['systemRepoURLValue'];
        $repo['system']['username']         = $_POST['systemRepoUserValue'];
        $repo['system']['password']         = $_POST['systemRepoPass1Value'];
        $repo['system']['github_auth']      = $_POST['systemRepoGitHubIsAuthValue'] !="" ? "true" : "false";
        $repo['system']['github_repository']= $_POST['systemRepoURLGitHubValue'];
        $repo['system']['github_username']  = $_POST['systemRepoUserGitHubValue'];
        $repo['system']['github_deploy_key']= $_POST['systemRepoDeployKeyValue'];
        $repo['system']['github_repotype']  = $_POST['systemRepoGithubRepositoryTypeValue'];
    } else {
        $index = $_POST['selectedModuleRepository'];
        $repo['modules']['auth'][$index]      = $_POST['repoAuthIsAuthValue'] == "true" ? "true" : "false";
        $repo['modules']['authfunc'][$index]  = $_POST['repoAuthAuthFuncValue'];
        $repo['modules']['modrepo'][$index]   = $_POST['repoURLValue'];
        $repo['modules']['username'][$index]  = $_POST['repoUserValue'];
        $repo['modules']['password'][$index]  = $_POST['repoPass1Value'];
        $repo['modules']['reponame'][$index]  = $_POST['selectedRepositoryName'];
        $repo['modules']['github_auth']       = $_POST['moduleRepoGitHubIsAuthValue'] !="" ? "true" : "false";
        $repo['modules']['github_repository'] = $_POST['moduleRepoURLGitHubValue'];
        $repo['modules']['github_username']   = $_POST['moduleRepoUserGitHubValue'];
        $repo['modules']['github_deploy_key'] = $_POST['moduleRepoDeployKeyValue'];
        $repo['modules']['github_repotype']   = $_POST['moduleRepoGithubRepositoryTypeValue'];
    }
}
$base->getUpdateChecker()->writeSystemRepositories($repo, $_GET['deleteId']);
?>