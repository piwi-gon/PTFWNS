<?php

class cConfigurationSQL extends cConfiguration {

    public function __construct() {
        /**
         * this is the main configuration-database
         * configuration is done by the administration-utility
         */
        $_SESSION['additionalDBVars']['ptfw_config']['dbHost'] = dirname(__FILE__)."/";
        $_SESSION['additionalDBVars']['ptfw_config']['dbName'] = "config.sqlite3";
        $_SESSION['additionalDBVars']['ptfw_config']['dbUser'] = "";
        $_SESSION['additionalDBVars']['ptfw_config']['dbPass'] = "";
        $_SESSION['additionalDBVars']['ptfw_config']['dbPort'] = "";
        $_SESSION['additionalDBVars']['ptfw_config']['dbType'] = "SQLITE";
    }

    public function loadConfiguration() {
        $this->_getInitDataSQL();
    }

    public function getVersion() { return "v1.0.0"; }

    private function _getInitDataSQL() {
        $this->addDBVars();
        cSQL::makeNewConn("ptfw_config");
        if(!$this->_checkTablesSQL()) {
            $this->_createTablesSQL();
            $this->_insertInitDataSQL();
            $this->_queryDBVarsSQL();
        } else {
            $this->_queryDBVarsSQL();
        }
    }

    private function _checkTablesSQL() {
        $query  = "select version from t_version";
        $ds_sel = $this->_sqlObj->makeQuery($query);
        $this->deb(basename(__FILE__) . ":" . $query, "SQL");
        if($ds_sel[0]['version'] != "") { return $this->_checkIfTableExistsSQL("t_version"); }
        return false;
    }

    private function _createTablesSQL() {
        echo "creating tables" . (PHP_SAPI == "cli" ? "\n":"<br>");
        $tbl['version'] = "create table t_version(version varchar(10) default '' not null);";
        $tbl['mod']     = "create table t_module (module_id integer primary key autoincrement default 0 not null,\n".
                          "name varchar(30) default '' not null,\n".
                          "identifier varchar(30) default '' not null,\n".
                          "path varchar(255) default '' not null,\n".
                          "installed_version varchar(10) default '' not null);";
        $tbl['ext']     = "create table t_extension (extension_id integer primary key autoincrement default 0 not null,\n".
                          "name varchar(30) default '' not null,\n".
                          "identifier varchar(30) default '' not null,\n".
                          "path varchar(255) default '' not null,\n".
                          "installed_version varchar(10) default '' not null);";
        $tbl['env']     = "create table t_environment (environment_id integer primary key autoincrement default 0 not null,\n".
                          "extensions boolean default true not null,\n".
                          "debug boolean default false not null,\n".
                          "debug_level integer default 1 not null,\n".
                          "upload boolean default true not null,\n".
                          "max_upload_size varchar(10) default '10MB' not null);\n";
        $keys = array_keys($tbl);
        for($count = 0; $count < count($keys); $count++) {
            $query = $tbl[$keys[$count]];
            $this->deb(basename(__FILE__) . ":" . $query, "SQL");
            $this->_sqlObj->makeQuery($query);
        }
    }

    private function _insertInitDataSQL() {
        $insert = "insert into t_version (version) VALUES('" . CURRENT_FRAMEWORK_VERSION . "');";
        $this->deb(basename(__FILE__) . ":" . $insert, "SQL");
        $this->_sqlObj->makeQuery($insert);
        // sets the environment-vars
        $envIns = "insert into t_environment (extensions, debug, debug_level, upload, max_upload_size) ".
                  "VALUES('t', 't', '2', 't', '10MB')";
        $this->deb(basename(__FILE__) . ":" . $envIns, "SQL");
        $_SESSION['_ENV']['DEBUG']          = true;
        $_SESSION['_ENV']['DEBUGLEVEL']     = 2;
        $this->_sqlObj->makeQuery($envIns);
        // cJQuery  = jquery/cJQuery.inc.php
        // cJQueryUI= jqueryui/cJQueryUI.inc.php
        $jqIns1 = "insert into t_extension (name, identifier, path, installed_version) ".
                  "VALUES('cJQuery', 'cJQuery', 'jquery/cJQuery.inc.php', '" . CURRENT_JQUERY_VERSION . "')";
        $this->deb(basename(__FILE__) . ":" . $jqIns1, "SQL");
        $this->_sqlObj->makeQuery($jqIns1);
        $jqIns2 = "insert into t_extension (name, identifier, path, installed_version) ".
                  "VALUES('cJQueryUI', 'cJQueryUI', 'jqueryui/cJQueryUI.inc.php', '" . CURRENT_JQUERYUI_VERSION . "')";
        $this->deb(basename(__FILE__) . ":" . $jqIns2, "SQL");
        $this->_sqlObj->makeQuery($jqIns2);
    }

    private function _queryDBVarsSQL() {
        $query  = "select * from t_environment";
        $this->deb(basename(__FILE__) . ":" . $query, "SQL");
        $entry = $this->_sqlObj->makeQuery($query);
        if($entry[0]['extensions']) { $this->_queryExtensionsSQL(); }
        $_SESSION['_ENV']['DEBUG']          = ($entry[0]['debug'] == "t" ? true : false);
        $_SESSION['_ENV']['DEBUGLEVEL']     = $entry[0]['debug_level'];
        $_SESSION['_ENV']['UPLOAD']         = ($entry[0]['upload'] == "t" ? true : false);
        $_SESSION['_ENV']['UPLOAD_SIZE']    = $entry[0]['max_upload_size'];
        $_SESSION['_ENV']['USE_EXTENSIONS'] = ($entry[0]['extensions'] == "t" ? true : false);
    }

    private function _queryExtensionsSQL() {
        if(isset($_SESSION['_EXT'])) { unset($_SESSION['_EXT']); }
        $query  = "select * from t_extension order by name";
        $this->deb(basename(__FILE__) . ":" . $query, "SQL");
        $ds_sel = $this->_sqlObj->makeQuery($query);
        for($count = 0; $count < count($ds_sel); $count++) {
            if(file_exists(dirname(__FILE__) . "/../../external/" . $ds_sel[$count]['path'])) {
                $_SESSION['_EXT'][$ds_sel[$count]['identifier']] = dirname(__FILE__) . "/../../external/" . $ds_sel[$count]['path'];
            }
        }
    }

}
?>