/**
 *
 * environment.js
 *
 * author : piwi
 *
 * created: 29.03.2015
 * changed: 29.03.2015
 *
 * purpose: includes the javascript-functions for handling the environment-data
 *
 */

function modifyEnvironment() {
    var postData = $('input, radio').serialize();
    $.ajax( {
        url: "environment/modifyEnvironment.php",
        type: "POST",
        data: postData,
        success: function(data) {
            $('#resultUpdateEnvironmentId').html("Update saved");
            console.log(data);
        }
    } );
}

function loadInDialog(site, theWidth, theHeight) {
    $('#dialog').hide();
    $('#dialog').dialog({ height:(theHeight != undefined ? theHeight : 600), width: (theWidth != undefined ? theWidth : 900)});
    $('#dialog').load(site);
    $('#dialog').show();
}

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
