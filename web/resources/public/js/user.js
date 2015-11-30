function User () {
    this.id = 0;
}

User.prototype.controller = "";
User.prototype.token = "";
User.prototype.id = 0;
User.prototype.username = "";
User.prototype.password = "";
User.prototype.errors = [];

User.prototype.setToken = function(token) {
    this.token = token;
};

User.prototype.setController = function(controller) {
    this.controller = controller;
};

User.prototype.setId = function(id) {
    if (isNumber(id)) this.id = id;
};

User.prototype.setUsername = function(username) {
    if (isStringValid(username, 3, 15)) this.username = username;
};

User.prototype.setPassword = function(password) {
    if (isPasswordValid(password)) this.password = password;
};

User.prototype.getController = function() {
    return this.controller;
};

User.prototype.getToken = function() {
    return this.token;
};

User.prototype.getId = function() {
    return this.id;
};

User.prototype.getUsername = function() {
    return this.username;
};

User.prototype.getPassword = function() {
    return this.password;
};

User.prototype.prepare = function(){
    this.id = 0;
    this.username = '';
    this.password = '';
};

User.prototype.getErrors = function() {
    this.errors = [];
    if (this.controller == "") this.errors.push({name:"controller", msg:"Controller is not valid."});
    if (this.username == "") this.errors.push({name:"username", msg:"Username is not valid."});
    if (this.password == "") this.errors.push({name:"password", msg:"Password is not valid."});
    return this.errors;
};

User.prototype.isValid = function() {
    return (this.getErrors().length == 0);
};

User.prototype.save = function(onSuccess, onError, onComplete){
    var url = '';
    var type = '';
    var data = {
        _username:this.getUsername(),
        _password:this.getPassword(),
        _csrf_token_register:this.getToken()
    };
    if (this.getId() > 0) {
        data['id'] = this.getId();
        url = this.getController()+'update';
        type = 'PUT';
    } else  {
        url = this.getController()+'create';
        type = 'POST';
    }
    callService(url, JSON.stringify(data), type, 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
};

User.prototype.destroy = function(onSuccess, onError, onComplete){
    var data = { _id:this.getId(), _csrf_token_detail:this.getToken() };
    callService(this.getController(), JSON.stringify(data), 'DELETE', 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
};

User.prototype.login = function(form, onSuccess, onError){
    $.ajax({
        url: form.attr('action'),
        data: form.serialize(),
        type: form.attr('method'),
        dataType: 'html',
        beforeSend: function(){
            form.find(':input').prop('disabled', true);
        },
        success: function(data, textStatus, jqXHR) {
            if (jqXHR.status === 200){
                if (data != '')
                    try {
                        var u = $.parseJSON(data);
                        if (isNumber(u.id)) onSuccess(u);
                    }
                    catch(e) {
                        onError('User credentials is not found.');
                    }
            } else if (jqXHR.status === 204){
                onError('User credentials is not found.');
            }
        },
        error:function(jqXHR, textStatus, errorThrown){
            onError(errorThrown);
        }
    });
};

User.prototype.logout = function(onSuccess, onError){
    $.post($('#route-logout').val(), function(data, textStatus, jqXHR){
        if (jqXHR.status === 200){
            onSuccess();
        } else {
            onError();
        }
    })
};

function callService(url, data, type, dataType, contentType, onSuccess, onError, onComplete){
    $.ajax({
        type: type,
        dataType: dataType,
        url: url,
        contentType: contentType,
        data : data,
        success: function (data, textStatus, jqXHR) {
            onSuccess(data, textStatus, jqXHR);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            onError(jqXHR, textStatus, errorThrown);
        },
        complete: function(){
            onComplete();
        }
    });
}