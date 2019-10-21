<?php
/**
 *
 * cSQLite.inc.php
 *
 * author : piwi
 *
 * created: 28.02.2015
 * changed: 28.02.2015
 *
 * purpose: SLQ-Data-Wrapper for sqlite
 *          namespaced version;
 *
 */

namespace PTFW\Base\Config\SQL;

class cSQLite {

    private $_sqlConn;
    private $_sqliteFile;
    private $_sqliteUser;
    private $_sqlitePass;
    private $_isSQLite;
    private $_isSQLite3;

    public function cSQLite() { /* constructor*/ }

    public function getVersion() { return "v1.0.0"; }
    public function run() { return $this; }

    public function makeNewConn($connName) {
        $actDB = $_SESSION['additionalDBVars'][$connName];
        // thats the file- and databasename
        $this->_sqliteFile = $actDB['dbHost'].$actDB['dbName'];
        $this->deb("SQLiteFile: " . $this->_sqliteFile, "SQL");
        $this->_sqliteUser = $actDB['dbUser'];
        $this->_sqlitePass = $actDB['dbPass'];
        if(extension_loaded("sqlite")) {
            $this->_initSQLite();
        } else if(extension_loaded("sqlite3")) {
            $this->_initSQLite3();
        }
    }

    public function makeQuery($theQuery, $additionalInfo = "") {
        if($this->_isSQLite) {
            return $this->_queryData($theQuery);
        } else if($this->_isSQLite3) {
            return $this->_queryData3($theQuery);
        }
    }

    public function getActualServerVersion() {
        return sqlite_libversion();
    }

    private function _initSQLite() {
        try {
            $this->_sqlConn = sqlite_open($this->_sqliteFile);
            $this->_isSQLite = true;
        } catch (\Exception $ex) {
            die("DB-Conn could not be established (sqlite; ".$this->_sqliteFile.")");
        }
    }

    private function _initSQLite3() {
        try {
            $this->_sqlConn = new \SQLite3($this->_sqliteFile);
            $this->_isSQLite3 = true;
        } catch (\Exception $ex) {
            die("DB-Conn could not be established (sqlite3; ".$this->_sqliteFile.")");
        }
    }

    private function _queryData($theQuery) {
        while($entry = sqlite_fetch_array($theQuery, SQLITE_ASSOC)) {
            $data[] = $entry;
        }
        return $data;
    }

    private function _queryData3($theQuery) {
        $result = $this->_sqlConn->query($theQuery);
        while($resX = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $resX;
        }
        return $data;
    }
}
?>