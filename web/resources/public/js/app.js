$( document ).ready(function() {

    var toggleLoginForm = $('#li-login');
    var loginFormContainer = $('#li-form');
    var loginForm = loginFormContainer.find('#form-login');
    var logoutLink = $('#li-logout');

    loginForm.submit(function( event ) {
        event.preventDefault();
        login();
    });

    toggleLoginForm.click(function(){
        loginFormContainer.toggle();
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            loginForm.find('.form-control').val('');
            $(this).addClass('active');
        }
    });

    logoutLink.click(function(event){
        event.preventDefault();
        $(this).addClass('active');
        logout();
    });

    $(this).on('registerSucceed', function( event, param ) {
        loginForm.find('.form-control').val('');
        loginForm.find('#_username').val(param);
        toggleLoginForm.addClass('active');
        loginFormContainer.show();
    });

    $(this).on('routeChanged', function(event, param){
        var link = $('#li-'+param);
        eventPreventer('click', link);
        link.addClass('active');
        link.addClass('li-route-clicked');
    });

    function login(){
        var user = new User();
        user.login(loginForm, loginSucceed, loginFailed);
    }

    function loginSucceed(user){
        if (user.role == 'ROLE_ADMIN') $('.admin').show();
        $('.authorized').show();
        $('.guest').hide();
        $(document).trigger('login');
    }

    function loginFailed(param){
        var type = BootstrapDialog.TYPE_WARNING;
        var title = 'Login Failed';
        var message = '<b>'+param+'</b>';
        var buttons = [{
            label: 'OK',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];
        var dialog = BootstrapDialogShow(type, title, message, buttons);
        dialog.onHide(function(){
            loginForm.find('#_password').val('');
            loginForm.find(':input').prop('disabled', false);
        });
        dialog.open();
    }

    function logoutSucceed(){
        logoutLink.removeClass('active');
        var type = BootstrapDialog.TYPE_INFO;
        var title = 'Logout Succeed';
        var message = '<b>You have been successfully logged out.</b>';
        var buttons = [{
            label: 'OK',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];
        var dialog = BootstrapDialogShow(type, title, message, buttons);
        dialog.onHidden(function(){
            window.location.replace($('#route-home').val());
        });
        dialog.open();
    }

    function logoutFailed(){
        var type = BootstrapDialog.TYPE_WARNING;
        var title = 'Logout Failed';
        var message = '<b>An error occurred, please try again.</b>';
        var buttons = [{
            label: 'OK',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];
        var dialog = BootstrapDialogShow(type, title, message, buttons);
        dialog.open();
    }

    function logout(){
        var user = new User();
        user.logout(logoutSucceed, logoutFailed);
    }
});