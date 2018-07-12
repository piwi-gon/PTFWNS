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

