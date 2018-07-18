<?php
/**
 *
 * cUpdateChecker.inc.php
 *
 * author : piwi
 *
 * created: 01.05.2015
 * changed: 01.05.2015
 *
 * purpose: this class checks if there is a new
 * base-version available
 * the repository for this could be everywhere
 * where you wanted it but at first at the place
 * this framework comes from
 *
 * the repository is editable from the frontend
 *
 * if you are deciding to develop your own base so make
 * sure the correct repository is connected
 * otherwise your changes will be lost and the base-version
 * from the place this framework comes from will be installed
 *
 * there can be only one system-repository
 *
 * on the other hand there can be more than one repository
 * for the modules - but be careful - same name of a module
 * could cause a problem in installing it if no namespace is used
 *
 */

namespace PTFW\Base\Update\Checker;

class cUpdateChecker {

    private $_soapURL;
    private $_soapAction;
    private $_repos;

    public function __construct() {
        ;
   }
    /**
     * this function tries to store a new repository for system and/or modules
     *
     * for the system it overwrites the current-system (can be set back
     * with activating default-settings)
     * for module-repositories it adds a new one or updates the current
     *
     * @param array $VAR
     */
   public function writeSystemRepositories($VAR, $deleteId) {
       if($deleteId == null) { $deleteId = -1; }
       print_r($VAR);
       exit;
       $fp = fopen(_BASEDIR_ . "/index/repositories.idx", "w+");
       if($fp != null) {
           fwrite($fp, "[system]\n");
           fwrite($fp, "active_repo_type = ".$VAR['system']['active_repo_type']."\n");
           fwrite($fp, "auth = ".$VAR['system']['auth']."\n");
           fwrite($fp, "authfunc = ".$VAR['system']['authfunc']."\n");
           fwrite($fp, "repository = ".$VAR['system']['repository']."\n");
           fwrite($fp, "username = ".$VAR['system']['username']."\n");
           fwrite($fp, "password = ".$VAR['system']['password']."\n");
           fwrite($fp, "github_auth = ".$VAR['system']['github_auth']."\n");
           fwrite($fp, "github_repository = ".$VAR['system']['github_repository']."\n");
           fwrite($fp, "github_username = ".$VAR['system']['github_username']."\n");
           fwrite($fp, "github_deploy_key = ".$VAR['system']['github_deploy_key']."\n");
           fwrite($fp, "github_repotype = ".$VAR['system']['github_repotype']."\n");
           fwrite($fp, "\n[modules]\n");
           $deleted = 0;
           for($count = 0; $count < count($VAR['modules']['modrepo']); $count++) {
               if($count != $deleteId) {
                   fwrite($fp, "auth[".($count-$deleted)."] = ".$VAR['modules']['auth'][($count)]."\n");
                   fwrite($fp, "authfunc[".($count-$deleted)."] = ".$VAR['modules']['authfunc'][($count)]."\n");
                   fwrite($fp, "modrepo[".($count-$deleted)."] = ".$VAR['modules']['modrepo'][($count)]."\n");
                   fwrite($fp, "username[".($count-$deleted)."] = ".$VAR['modules']['username'][($count)]."\n");
                   fwrite($fp, "password[".($count-$deleted)."] = ".$VAR['modules']['password'][($count)]."\n");
                   fwrite($fp, "reponame[".($count-$deleted)."] = ".$VAR['modules']['reponame'][($count)]."\n");
                   fwrite($fp, "github_auth = ".$VAR['modules']['github_auth']."\n");
                   fwrite($fp, "github_repository = ".$VAR['modules']['github_repository']."\n");
                   fwrite($fp, "github_username = ".$VAR['modules']['github_username']."\n");
                   fwrite($fp, "github_password = ".$VAR['modules']['github_password']."\n");
               } else {
                   echo "found!";
                   $deleted = 1;
               }
           }
           fclose($fp);
       }
   }

   public function querySystemRepository() {
       $this->_readSystemRepository();
       return $this->_repos;
   }

   public function checkIfUpdateIsavailable() {
       $this->_readSystemRepository();
   }

   private function _readSystemRepository() {
       error_log("reading '" . _BASEDIR_ . DS . "index" . DS . "repositories.idx" . "'\n", 3, "/tmp/reposicheck.log");
       $this->_repos = parse_ini_file(_BASEDIR_ . DS . "index" . DS . "repositories.idx", true, INI_SCANNER_RAW);
   }
}
?>