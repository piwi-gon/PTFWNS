<?php
?>
<script type="text/javascript">
<!--

$(document).ready(function() {
    $('#dragDebugId').draggable({handle: "p"});
    $('#dragDebugId').resizable({alsoResize: $(this).find("div [id=debugMsgId]")});
    $('.button').bind('click', function() {reloadDebugMessages();});
});

function reloadDebugMessages() {
    $('#debugMsgId').html('').load('showDebugMessages.php', function() {$('.button').unbind("click").bind('click', function() {reloadDebugMessages();});});
}

//-->
</script>
<div style="position:absolute;top:2px;right:2px;width:250px;height:400px;overflow:hidden;border:1px solid black;background-color:white;" id="dragDebugId">
    <p id="myDragLine" class="ui-widget-header" style="height:20px;margin-top:1px;padding-top:0;">
        <span style="float:left;cursor:pointer;">DEBUG-Information</span>
        <span style="float:right;cursor:pointer;" onClick="$('#dragDebugId').hide();$('#showDebugId').show();">X</span>
        <div style="clear:both;"></div>
    </p>
    <div id="debugMsgId" style="width:97%;height:330px;overflow:auto;"></div>
</div>
<button style="position:absolute;top:2px;right:2px;display:none;" id="showDebugId"
    onClick="$('#dragDebugId').show();$('#showDebugId').hide();">
    <img src="images/16x16/legend.png" border="0">
</button>
