<?php
/**
 *
 * index.php
 *
 * author : piwi
 *
 * created: 28.03.2015
 * changed: 28.03.2015
 *
 * purpose:
 *
 */

// includes the session and base-start
require_once("lib/baseStart.php");
if(isset($_SESSION['SCRIPT']['_ONDOCUMENTREADY'])) { unset($_SESSION['SCRIPT']['_ONDOCUMENTREADY']); }

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>PTFW - Piwi Gon Technologies Framework</title>
    <link rel="stylesheet" href="css/jqueryui/jquery-ui.css">
    <link rel="stylesheet" href="css/base.css">
    <script type="text/javascript" src="lib/js/jquery/jquery.js"></script>
    <script type="text/javascript" src="lib/js/jquery/jquery-ui.js"></script>
    <script type="text/javascript" src="lib/js/jquery.buttonsetv.js"></script>
</head>
<script>
$(document).ready(function() {
    $('#workContentId').css('width', ($(window).width()-610) + "px");
    $('#mainViewTableId').css('height', ($(window).height()-140) + "px");
    $('#mainId').css('height', ($(window).height()-180) + "px");
    $('#tabs-centre').tabs({
        cache: false,
    });
    $('#tabs-centre ul > li > a:first').click();
});
</script>
<body>
<div id="dialog"></div>
<div id="mainContentId" style="width:100%!important;overflow:auto;">
    <div class="table" style="width:99%!important;margin-left:auto;margin-right:auto;height:50px;">
        <div class="trow">
            <div class="tcell ui-corner-top" style="width:99%!important;text-align:center;border:1px solid lightgrey;">
                <h2>PTFW - Piwi-Technologies - FrameWork</h2>
            </div>
        </div>
    </div>
    <div class="table" id="mainViewTableId" style="width:99%!important;margin-left:auto;margin-right:auto;height:620px;overflow:auto;">
        <div class="trow">
            <div class="tcell" style="width:100%!important;margin-left:auto;margin-right:auto;">
                <div id="tabs-centre" style="width:100%!important;margin-left:auto;margin-right:auto;">
                    <ul>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('docs/info.html');">Home</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('docs/docIndex.php');">Documentation</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('environment/environment.inc.php?<?php echo SID; ?>');">Environment</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('system/system.inc.php?<?php echo SID; ?>');">System</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('modules/listModules.inc.php?<?php echo SID; ?>');">Modules</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('extensions/listExtensions.inc.php?<?php echo SID; ?>');">Extensions</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('phpinfo.php');">PHPInfo</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('tests/index.php?<?php echo SID; ?>');">Tests</a></li>
                    </ul>
                    <div id="mainId" style="height:600px;width:90%!important;margin-right:auto;margin-left:auto;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>