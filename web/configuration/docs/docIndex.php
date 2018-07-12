<?php
/**
 *
 * docIndex.php
 *
 * author : piwi
 *
 * created: 18.04.2015
 * changed: 18.04.2015
 *
 * purpose:
 *
 */

?>
<div class="table" style="width:100%!important;">
    <div class="trow">
        <div class="tcell ui-state-default" style="width:100%!important;text-align:center;"><h1>PTFW - PiwiTechnologies Framework - Documentation</h1></div>
    </div>
</div>
<div class="table" style="width:100%!important;">
    <div class="trow">
        <div class="tcell" style="width:290px;vertical-align:top;border:1px solid lightgrey;">
            <div class="table">
                <div class="trow">
                    <div class="tcell">
                        <h2 style="cursor:pointer;padding-left:4px;margin-bottom:5px;" onClick="$('#mainInfoId').load('docs/introduction.html');" class="ui-state-default ui-corner-all">Introduction</h2>
                    </div>
                </div>
                <div class="trow">
                    <div class="tcell">
                        <h2 style="cursor:pointer;padding-left:4px;margin-bottom:5px;"  onCLick="$('#mainInfoId').load('docs/installation.html');" class="ui-state-default ui-corner-all">Installation</h2>
                        <ul>
                            <li style="cursor:pointer;padding-left:4px;" class="ui-state-default ui-corner-all" onCLick="$('#mainInfoId').load('docs/requirements.html');">Requirements</li>
                            <li style="cursor:pointer;padding-left:4px;" class="ui-state-default ui-corner-all" onCLick="$('#mainInfoId').load('docs/basics.php');">Basics</li>
                        </ul>
                    </div>
                </div>
                <div class="trow">
                    <div class="tcell">
                        <h2 style="cursor:pointer;padding-left:4px;margin-bottom:5px;" onCLick="$('#mainInfoId').load('docs/usage.html');" class="ui-state-default">Usage</h2>
                        <ul>
                            <li style="cursor:pointer;padding-left:4px;" class="ui-state-default ui-corner-all" onCLick="$('#mainInfoId').load('docs/usage_general.html');">General</li>
                            <li style="cursor:pointer;padding-left:4px;" class="ui-state-default ui-corner-all" onCLick="$('#mainInfoId').load('docs/usage_modules.html');">Modules</li>
                            <li style="cursor:pointer;padding-left:4px;" class="ui-state-default ui-corner-all" onCLick="$('#mainInfoId').load('docs/usage_extensions.html');">Extensions</li>
                        </ul>
                    </div>
                </div>
                <div class="trow">
                    <div class="tcell">
                        <h2 style="cursor:pointer;padding-left:4px;margin-bottom:5px;" onCLick="$('#mainInfoId').load('docs/devel.html');" class="ui-state-default">Developing</h2>
                        <ul>
                            <li style="cursor:pointer;padding-left:4px;" class="ui-state-default ui-corner-all" onCLick="$('#mainInfoId').load('docs/devel_modules.php');">Generating own Modules</li>
                            <li style="cursor:pointer;padding-left:4px;" class="ui-state-default ui-corner-all" onCLick="$('#mainInfoId').load('docs/devel_extensions.html');">Installing new Extensions&nbsp;</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="tcell" style="vertical-align:top;border:1px solid lightgrey;">
            <div id="mainInfoId" style="height: 500px; overflow: auto; width:100%;"></div>
        </div>
    </div>
</div>
