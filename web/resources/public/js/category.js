function Category () {
    this.id = 0;
    this.title = '';
    this.token = '';
}

Category.prototype.controller = '';
Category.prototype.id = 0;
Category.prototype.token = '';
Category.prototype.title = '';
Category.prototype.errors = [];

Category.prototype.setToken = function(token) {
    this.token = token;
};

Category.prototype.setController = function(controller) {
    this.controller = controller;
};

Category.prototype.setId = function(id) {
    if (isNumber(id)) this.id = id;
};

Category.prototype.setTitle = function(title) {
    if (isStringValid(title, 3, 50)) this.title = title;
};

Category.prototype.getToken = function() {
    return this.token;
};

Category.prototype.getController = function() {
    return this.controller;
};

Category.prototype.getId = function() {
    return this.id;
};

Category.prototype.getTitle = function() {
    return this.title;
};

Category.prototype.preUpdate = function(){
    this.id = 0;
    this.title = '';
};

Category.prototype.getErrors = function() {
    this.errors = [];
    if (this.controller == '') this.errors.push({name:"controller", msg:"Controller is not valid."});
    if (this.title == '') this.errors.push({name:"name", msg:"Title is not valid."});
    return this.errors;
};

Category.prototype.isValid = function() {
    return (this.getErrors().length == 0);
};

Category.prototype.save = function(onSuccess, onError, onComplete){
    var url = '';
    var type = '';
    var data = {
        _title:this.getTitle(),
        _csrf_token_category:this.getToken()
    };
    if (this.getId() > 0) {
        data['_id'] = this.getId();
        url = this.getController()+'update';
        type = 'PUT';
    } else  {
        url = this.getController()+'create';
        type = 'POST';
    }
    callService(url, JSON.stringify(data), type, 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
};

Category.prototype.destroy = function(onSuccess, onError, onComplete){
    var data = { _id:this.getId(), _csrf_token_category:this.getToken() };
    callService(this.getController()+'delete', JSON.stringify(data), 'DELETE', 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
};

Category.prototype.fetch = function(onSuccess, onError, onComplete){
    callService(this.getController()+'fetch', '', 'GET', 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
};

/*
Category.prototype.find = function(data, onSuccess, onError, onComplete){
    callService(this.getController(), data, 'GET', 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
};*/

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