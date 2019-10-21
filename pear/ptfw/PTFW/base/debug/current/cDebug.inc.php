<?php
/**
 *
 * cDebug.inc.php
 *
 * author : piwi
 *
 * created: 10.05.2015
 * changed: 10.05.2015
 *
 * purpose:
 *
 */

namespace PTFW\Base\Debug;

class cDebug {

    const VERSION = "v1.0.0";

    public function __construct() {
        //
    }

    public function deb($msg, $type="MSG", $debugLevel = "") {
        if($_SESSION['_ENV']['DEBUG']) {
            if($debugLevel=="") { $debugLevel = $_SESSION['_ENV']['DEBUGLEVEL']; }
            $_SESSION['_DEBUG'][strtoupper($type)][$debugLevel][] = $msg;
        }
    }

    public function showDebugMessage($clear = true, $which = "all") {
        if(!$_SESSION['_ENV']['DEBUG']) { return; }
        $msg = "";
        if(PHP_SAPI !== "cli") { $msg .= "<pre>\n"; }
        $msg .= "---------------------\n";
        $msg .= "START DEBUG-MESSAGES:\n";
        $msg .= "---------------------\n";
        $msg .= "Current DbgLevel: " . $_SESSION['_ENV']['DEBUGLEVEL'] . "\n";
        $msg .= "---------------------\n";
        switch(strtolower($which)) {
            default     :
            case "all"  :   $msg .= $this->_generateDebugMessageByLevel("MSG");
                            $msg .= $this->_generateDebugMessageByLevel("CLS");
                            $msg .= $this->_generateDebugMessageByLevel("SQL");     break;
            case "sql"  :   $msg .= $this->_generateDebugMessageByLevel("SQL");     break;
            case "info" :   $msg .= $this->_generateDebugMessageByLevel("MSG");     break;
            case "class":   $msg .= $this->_generateDebugMessageByLevel("CLS");     break;
        }
        $msg .= "---------------------\n";
        $msg .= "END DEBUG-MESSAGES:\n";
        $msg .= "---------------------\n";
        if(PHP_SAPI !== "cli") { $msg .= "</pre>\n"; }
        if($clear) { $_SESSION['_DEBUG'] = null; $_SESSION['_DEBUG'] = array(); }
        echo $msg;
    }

    public function checkDebug() {
        $tmpOut = "";
        // if debug is on - build debug-container
        if($_SESSION['_ENV']['DEBUG']) {
            $tmpOut = <<<EOT
<script type="text/javascript">
<!--
$(document).ready(function() {
    if($('#dragDebugId').length == 0) {
        $.ajax({
            url: 'debugDIV.php',
            success: function(data) { $('body').append(data); }
        });
    }
});

function sendFormData() {
        var data = $('#formIdentifier').serialize();
        alert(data);
}
//-->
</script>
EOT;
            $_SESSION['_OUTPUT'] .= $tmpOut;
        }
        $_SESSION['_DEBUG_LOADED'] = true;
        return $tmpOut;
    }

}