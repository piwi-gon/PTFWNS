/**
 * jquery.messagebox.widget.js
 * 
 * author: piwi (piwi.gon@osnanet.de)
 * 
 * created: 17.07.2017
 * changed: 17.07.2017
 *
 * useable options:
 *          boxType         : declares the Box-Type                 -   default: "OK",
 *          okFunction      : declares the okFunction               -   default: null,
 *          yesFunction     : declares the yesFunction              -   default: null,
 *          noFunction      : declares the noFunct6ion              -   default: null,
 *          cancelFunction  : declares the cancelFunction           -   default: null,
 *          dialogWidth     : declares the dialogWidth              -   default: 480,
 *          dialogHeight    : declares the dialogHeight             -   default: 'auto',
 *          isModal         : declares if dialog is modal           -   default: true,
 *          showIcon        : declares to show the msg-icon         -   default: true,
 *          message         : this is the message to be displayed   -   default: 'none',
 *          titleBar        : declares to show the titlebar or not  -   default: true,
 *          titleText       : declares the title-text               -   default: 'Information',
 *          buttonTextYes   : declares the text for button yes      -   default: 'Ja',
 *          buttonTextNo    : declares the text for button no       -   default: 'Nein',
 *          buttonTextCancel: declares the text for button cancel   -   default: 'Abbrechen',
 *          buttonTextOK    : declares the text for button ok       -   default: 'Ok',
 *
 */

$.widget("ui.messageBoxWidget", {
    version: "1.0.0",
    currentDir: '',
    options: {
            boxType: "OK",
            okFunction: null,
            yesFunction: null,
            noFunction: null,
            cancelFunction: null,
            dialogWidth: 500,
            dialogHeight: 'auto',
            isModal: true,
            showIcon: true,
            message: '',
            titleBar: true,
            titleText: 'Information',
            buttonTextYes: 'Ja',
            buttonTextNo: 'Nein',
            buttonTextCancel: 'Abbrechen',
            buttonTextOK: 'Ok',
            },
    boxtypes: ["OK",
              "OK|CANCEL",
              "YES|NO",
              "YES|NO|CANCEL"],
    dialogId: '',
    fileName: '',
    message: 'No Message given',
    intDialogHeight: null,
    intDialogWidth: null,
    _isModal: true,
    _boxType:'',
    elemToAddTo:"",

    _create: function() {
        this.element.hide();
        this.elemToAddTo = this.element.parent();
        this.intDialogHeight = this.options.dialogHeight;
        this.intDialogWidth  = this.options.dialogWidth;
        this._isModal = this.options.isModal;
        this._boxType = this.options.boxType.replace("|ERROR", "");
        this.message = this.options.message;
    },

    _init: function() {
        console.log("TESTT (init)");
        var dir = document.querySelector('script[src$="jquery.messagebox.widget.js"]').getAttribute('src');
        var name = dir.split('/').pop(); 
        this.currentDir = dir.replace('/'+name,"/mbMasks/");
        if(this._boxType == "OK") {
            this.dialogId = "dialog-ok";
            this.fileName = "ok.html";
        }
        if(this._boxType == "OK|CANCEL") {
            this.dialogId = "dialog-okcancel";
            this.fileName = "okcancel.html";
        }
        if(this._boxType == "YES|NO") {
            this.dialogId = "dialog-yesno";
            this.fileName = "yesno.html";
        }
        if(this._boxType == "YES|NO|CANCEL") {
            this.dialogId = "dialog-yesnocancel";
            this.fileName = "yesnocancel.html";
        }
        console.log(this.options);
        this._createDialog();
    },

    _createDialog: function() {
        var self = this;
        var htmlData = null;
        if(this._isModal == undefined) { this._isModal = true; }
        $.ajax({
            url: this.currentDir + this.fileName,
            type: 'POST',
            success : function(data) {
                htmlData = data;
                $('body').append(htmlData);
                $('#'+self.dialogId).dialog({
                    autoOpen: false,
                    resizeable: false,
                    height: self.intDialogHeight,
                    width:  self.intDialogWidth,
                    modal: self._isModal,
                    open: function(event) {
                        //$(event.target).parent().css( { backgroundColor: 'lightgrey'})
                    },
                    close: function() { $(this).dialog('close').remove() }
                });
                $('#messageContentId').html('').html(self.message);
                $('#'+self.dialogId).siblings(".ui-dialog-titlebar").html(self.options.titleText);
                if(self.options.boxType.indexOf("ERROR")>-1) {
                    $('.ui-state-default').addClass('ui-state-error').removeClass('ui-state-default');
                    $('#messageIconId img').attr("src", 'images/fa-exclamation.png');
                }
                if(!self.options.titleBar) {
                    $('#'+self.dialogId).siblings(".ui-dialog-titlebar").remove();
                    $('#'+self.dialogId).siblings(".ui-dialog-content").css('padding-top', '10px');
                }
                if(!self.options.showIcon) {
                    console.log("removing icon-row");
                    $('#messageIconId').parent().remove();
                }
                self._checkButtons(self);
                self._openDialog(self);
            }
        });
    },

    _openDialog: function(self) {
        console.log('Dialog "' + self.dialogId + '"'); 
        if($('#'+self.dialogId).length > 0) {
            console.log('opening ...');
            $('#'+self.dialogId).dialog('open');
        }
    },

    _checkButtons: function(self) {
        if(self._boxType.indexOf("OK")>-1) {
            console.log("In OK");
            if($('#'+self.dialogId+'OKButtonId').length > 0) {
                $('#'+self.dialogId+'OKButtonId').html(self.options.buttonTextOK);
                if(self.options.okFunction != null && typeof self.options.okFunction == "function") {
                    $('#'+self.dialogId+'OKButtonId').click(function() { self.options.okFunction.call(); $('#'+self.dialogId).dialog('close').remove(); });
                } else {
                    $('#'+self.dialogId+'OKButtonId').click(function() { $('#'+self.dialogId).dialog('close').remove(); });
                }
            }
        }
        if(this._boxType.indexOf("CANCEL")>-1) {
            if($('#'+self.dialogId+'CancelButtonId').length > 0) {
                $('#'+self.dialogId+'CancelButtonId').html(self.options.buttonTextCancel);
                if(self.options.cancelFunction != null && typeof self.options.cancelFunction == "function") {
                    $('#'+self.dialogId+'CancelButtonId').click(function() { self.options.cancelFunction.call(); $('#'+self.dialogId).dialog('close').remove(); });
                } else {
                    $('#'+self.dialogId+'CancelButtonId').click(function() { $('#'+self.dialogId).dialog('close').remove(); });
                }
            }
        }
        if(this._boxType.indexOf("YES")>-1) {
            if($('#'+self.dialogId+'YesButtonId').length > 0) {
                $('#'+self.dialogId+'YesButtonId').html(self.options.buttonTextYes);
                if(self.options.yesFunction != null && typeof self.options.yesFunction == "function") {
                    $('#'+self.dialogId+'YesButtonId').click(function() { self.options.yesFunction.call(); $('#'+self.dialogId).dialog('close').remove(); });
                } else {
                    $('#'+self.dialogId+'YesButtonId').click(function() { $('#'+self.dialogId).dialog('close').remove(); });
                }
            }
        }
        if(this._boxType.indexOf("NO")>-1) {
            if($('#'+self.dialogId+'NoButtonId').length > 0) {
                $('#'+self.dialogId+'NoButtonId').html(self.options.buttonTextNo);
                if(self.options.noFunction != null && typeof self.options.noFunction == "function") {
                    $('#'+self.dialogId+'NoButtonId').click(function() { self.options.noFunction.call(); $('#'+self.dialogId).dialog('close').remove(); });
                } else {
                    $('#'+self.dialogId+'NoButtonId').click(function() { $('#'+self.dialogId).dialog('close').remove(); });
                }
            }
        }
    }
});

/**
 * to activate a replacement for alert try the following code
 *
 */

(function(alert) {
    window.alert= function() {
        openMessageBoxDialog.apply(this, arguments);
    }
})(window.alert);

function openMessageBoxDialog(text, type, okFunction, cancelFunction) {
    $.ui.messageBoxWidget({
        boxType: type,
        message: text,
        okFunction: okFunction || null,
        cancelFunction: cancelFunction || null
    });
}
