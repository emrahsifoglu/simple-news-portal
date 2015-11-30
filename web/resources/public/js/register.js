$( document ).ready(function() {

    $(document).trigger('routeChanged', ['register']);
    $(this).on('login', function(event, param){
        $('#form-register').find(':input').prop('disabled', true);
    });

    var registerForm = $('#form-register');
    var user = new User();
    var dialog = null;

    registerForm.submit(function(event) {
        event.preventDefault();
        register();
    });

    function register(){
        dialog = null;

        user.prepare();
        user.setToken(registerForm.find('#_csrf_token_register').val());
        user.setController(registerForm.attr('action'));
        user.setUsername($.trim(registerForm.find('#_username').val()));

        var _password = $.trim(registerForm.find('#_password').val());
        var _password_confirm = $.trim(registerForm.find('#_password_confirm').val());
        if (_password == _password_confirm) user.setPassword(_password);

        registerForm.find(':input').prop('disabled', true);

        if (!user.isValid()){
            registerFailed('not_valid', user.getErrors());
        } else {
            dialog = BootstrapDialogShow(BootstrapDialog.TYPE_INFO, 'Information', '<b>Please wait...</b>', [{ id: 'btn-wait' }]);
            dialog.onShow(function(dialog){
                dialog.getButton('btn-wait').disable();
                dialog.getButton('btn-wait').spin();
            });
            dialog.onShown(function(){
                user.save(onSuccess, onError, onComplete);
            });
            dialog.setClosable(false);
            dialog.open();
        }
    }

    function onSuccess(data, textStatus, jqXHR){
        if (jqXHR.status === 201){
            registerSucceed(user.getUsername());
        } else {
            registerFailed('unknown', null);
        }
    }

    function onError(jqXHR, textStatus, errorThrown){
        var textError = textStatus;
        if (jqXHR.status === 409 && jqXHR.responseText != ''){
            textError =  $.parseJSON(jqXHR.responseText).error;
        }
        registerFailed(textError, errorThrown);
    }

    function onComplete(){

    }

    function registerSucceed(username){
        $(document).trigger('registerSucceed', username );

        if (dialog == null){
            dialog = new BootstrapDialog(); //BootstrapDialogShow(BootstrapDialog.TYPE_SUCCESS, 'Information', '', []);
            dialog.onShow(function(dialog){
                registerForm.find(':input').prop('disabled', true);
            });
        }

        dialog.setType(BootstrapDialog.TYPE_SUCCESS);
        dialog.setTitle('Information');
        dialog.setMessage('Welcome, ' +  user.getUsername() + '.');
        dialog.setClosable(true);
        dialog.setButtons([{
            id:'btn-ok',
            label:'OK',
            action: function(dialogItself){
                dialogItself.close();
            }}]);
        dialog.getButton('btn-ok').enable();
        if (!dialog.opened) dialog.open();
    }

    function registerFailed(textError, data){
        var title = 'Registration Failed';
        var message = '';
        var type = BootstrapDialog.TYPE_WARNING;
        switch (textError){
            case 'username_is_taken':
                title = data;
                message = '<b>User registration is failed because username might be taken.</b>';
                break;
            case 'not_valid':
                title = 'Form Validation';
                type = BootstrapDialog.TYPE_WARNING;
                message = '';
                $.each(data, function(i, error){
                    message += '<p><b>'+error.msg+'</b></p>';
                });
                break;
            case 'bad_request' :
                type = BootstrapDialog.TYPE_DANGER;
                message = '<b>Server did not get the request.</b>';
                break;
            case 'error':
                type = BootstrapDialog.TYPE_DANGER;
                message = '<b>'+data+'</b>';
                break;
            case 'unknown':
                type = BootstrapDialog.TYPE_WARNING;
                message = '<b>Server did not response.</b>';
                break;
        }
        var buttons = [{
            label: 'OK',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];

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
            registerForm.find(':input').prop('disabled', false);
        });
        if (!dialog.opened) dialog.open();
    }
});