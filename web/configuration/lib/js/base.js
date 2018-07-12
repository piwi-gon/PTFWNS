/**
 * base.js - basic javascript-functions to be used everywhere
 */

function createOnOffDiv(fieldName, checkedvalue, functionName) {
    var theDiv = '<div class="onoffswitch_small" style="margin:0 auto;">\n'+
                 '<input onClick="'+functionName+'();" type="checkbox" class="onoffswitch_small-checkbox" name="'+fieldName+'" '+
                 'id="'+fieldName+'Id" value="true"' + (checkedvalue ? ' checked="checked"' : '') + '>\n'+
                 '<label class="onoffswitch_small-label" for="'+fieldName+'Id">'+
                 '<span class="onoffswitch_small-inner"></span>'+
                 '</label>\n'+
                 '</div>\n';
    return theDiv;
}

function determineWidth(widthValue) {
    var windowWidth = $(document).width();
    var retWidth = 0;
    if(widthValue < 100) {
        retWidth = (parseInt(windowWidth/100)*parseInt(widthValue)).toFixed(2);
    }
    return retWidth;
}
function determineHeight(heightValue) {
    var windowHeight = $(document).height();
    var retHeight = 0;
    if(heightValue < 100) {
        retHeight = (parseInt(windowHeight/100)*parseInt(heightValue)).toFixed(2);
    }
    return retHeight;
}