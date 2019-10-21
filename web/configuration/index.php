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
    <link rel="stylesheet" href="css/jquery.messagebox.css">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/grid.css">
    <script type="text/javascript" src="lib/js/jquery/jquery.js"></script>
    <script type="text/javascript" src="lib/js/jquery/jquery-ui.js"></script>
    <script type="text/javascript" src="lib/js/jquery.messagebox.widget.js"></script>
    <script type="text/javascript" src="lib/js/base.js"></script>
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
<div class="grid3cols">
    <div class="item1" id="meunav">
        <div class="table99" style="height:600px;">
            <div class="trow">
                <div class="tcell h40 f12">Menuitem 1</div>
            </div>
            <div class="trow">
                <div class="tcell h40 f12">Menuitem 2</div>
            </div>
            <div class="trow">
                <div class="tcell h40 f12">Menuitem 3</div>
            </div>
            <div class="trow">
                <div class="tcell h40 f12">Menuitem 4</div>
            </div>
        </div>
    </div>
    <div class="item2" id="mainContent">
        <div class="table99" style="height:600px;">
            <div class="trow">
                <div class="tcell h40 f12">Content</div>
            </div>
        </div>
    </div>
    <div class="item3" id="rightinfo">
        <div class="table99" style="height:600px;">
            <div class="trow">
            <div class="tcell h40 f12">Infotainment</div>
            </div>
        </div>
    </div>
</div>
<div id="mainContentId" style="width:100%!important;overflow:auto;">
    <div class="table99" style="height:50px;">
        <div class="trow">
            <div class="tcell ui-corner-top" style="width:99%!important;text-align:center;border:1px solid lightgrey;">
                <h2>PTFW - Piwi-Technologies - FrameWork</h2>
            </div>
        </div>
    </div>
    <div class="table99" id="mainViewTableId" style="height:620px;overflow:auto;">
        <div class="trow">
            <div class="tcell" style="width:100%!important;margin-left:auto;margin-right:auto;">
                <div id="tabs-centre" style="width:100%!important;margin-left:auto;margin-right:auto;">
                    <ul>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('docs/info.html');">Home</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('docs/docIndex.php');">Documentation</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('environment/environment.inc.php?<?php echo SID; ?>');">Environment</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('modules/listModules.inc.php?<?php echo SID; ?>');">Modules</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('extensions/listExtensions.inc.php?<?php echo SID; ?>');">Extensions</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('phpinfo.php');">PHPInfo</a></li>
                        <li><a href="javascript:preventDefault();return false;" onClick="$('#mainId').html('').load('tests/index.php?<?php echo SID; ?>');">Tests</a></li>
                    </ul>
                    <div id="mainId" style="height:600px;width:90%!important;margin:0 auto;overflow:auto;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>