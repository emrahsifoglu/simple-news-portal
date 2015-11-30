$( document ).ready(function() {
    $(document).trigger('routeChanged', ['comments']);

    var comment_controller = $('#controller-comments').val();
    var comment = new Comment();
    var error = null;
    var dialog = null;

    $('.delete-comment').click(function(event){
        event.preventDefault();
        var id = $(this).closest('li').attr('id');
        deleteComment(id);
    });

    function deleteComment(id){
        var buttons = [{
            id: 'btn-yes',
            label: 'Yes',
            cssClass: 'btn-danger',
            action: function(dialog){
                var $button = this;
                dialog.setClosable(false);
                dialog.getButton('btn-cancel').disable();
                $button.disable();
                $button.spin();
                comment.setId(id);
                comment.destroy(onSuccess, onError, onComplete);
            }
        }, {
            id: 'btn-cancel',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];
        dialog = BootstrapDialogShow(BootstrapDialog.TYPE_DANGER, 'WARNING!', '<b>Do you want to delete this news?</b>', buttons);
        dialog.setClosable(true);
        dialog.open();
    }

    function onSuccess(responsedata, textStatus, jqXHR){
        if (jqXHR.status === 204){
            $('#comment-'+comment.getId()).closest('li').remove();
        } else {
            error = { status: 'unknown', data: null };
        }
    }

    function onError(jqXHR, textStatus, errorThrown){
        var status;
        if (jqXHR.responseText != ''){
            try {
                status = JSON.parse(jqXHR.responseText).error;
            }
            catch(e) {
                status = errorThrown;
            }
        }
        error = { status:status, data:errorThrown };
    }

    function onComplete(){
        if (error == null){
            if (dialog != null){
                dialog.close();
                dialog = null;
            }
        } else {
            var type = BootstrapDialog.TYPE_DANGER;
            var title = 'Comment Failed';
            var message = '';
            var buttons = [{
                label: 'OK',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }];

            switch (error.status){
                case 'unknown':
                    message = '<b>Server did not response.</b>';
                    break;
                case 'bad_request' :
                    message = '<b>Server did not get the request.</b>';
                    break;
                case 'error':
                    message = '<b>'+error.data+'</b>';
                    break;
            }

            if (dialog == null){
                dialog = BootstrapDialogShow(type, title, message, buttons);
            } else {
                dialog.setType(type);
                dialog.setTitle(title);
                dialog.setMessage(message);
                dialog.setButtons(buttons);
            }
            dialog.setClosable(true);
            dialog.onHidden(function(){
                dialog = null;
            });
            if (!dialog.opened) dialog.open();
        }
        error = null;
    }

    comment.setController(comment_controller);
    comment.setToken($('#csrf_token_comment').val());

});