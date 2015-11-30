function Comment () {
    this.id = 0;
    this.news_id = 0;
    this.user_id = 0;
}

Comment.prototype.controller = "";
Comment.prototype.token = "";
Comment.prototype.id = 0;
Comment.prototype.news_id = 0;
Comment.prototype.user_id = 0;
Comment.prototype.content = "";
Comment.prototype.errors = [];

Comment.prototype.setToken = function(token) {
    this.token = token;
};

Comment.prototype.setController = function(controller) {
    this.controller = controller;
};

Comment.prototype.setId = function(id) {
    if (isNumber(id)) this.id = id;
};

Comment.prototype.setNewsId = function(news_id) {
    if (isNumber(news_id)) this.news_id = news_id;
};

Comment.prototype.setUserId = function(user_id) {
    if (isNumber(user_id)) this.user_id = user_id;
};

Comment.prototype.setContent = function(content) {
    if (isStringLenValid(content, 3, 250)) this.content = content;
};

Comment.prototype.getController = function() {
    return this.controller;
};

Comment.prototype.getToken = function() {
    return this.token;
};

Comment.prototype.getId = function() {
    return this.id;
};

Comment.prototype.getNewsId = function() {
    return this.news_id;
};

Comment.prototype.getUserId = function() {
    return this.user_id;
};

Comment.prototype.getContent = function() {
    return this.content;
};

Comment.prototype.prepare = function(){
    this.id = 0;
    this.content = '';
};

Comment.prototype.getErrors = function() {
    this.errors = [];
    if (this.controller == "") this.errors.push({name:"controller", msg:"Controller is not valid."});
    if (this.content == "") this.errors.push({name:"content", msg:"Content is not valid."});
    if (this.news_id == 0) this.errors.push({name:"news_id", msg:"News(id) is not valid."});
    return this.errors;
};

Comment.prototype.isValid = function() {
    return (this.getErrors().length == 0);
};

Comment.prototype.save = function(onSuccess, onError, onComplete){
    var url = '';
    var type = '';
    var data = {
        _news_id:this.getNewsId(),
        _content:this.getContent(),
        _csrf_token_comment:this.getToken()
    };
    if (this.getId() > 0) {
        data['id'] = this.getId();
        type = 'PUT';
    } else  {
        type = 'POST';
    }

    url = this.getController()+'/save';
    callService(url, JSON.stringify(data), type, 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
};

Comment.prototype.destroy = function(onSuccess, onError, onComplete){
    var data = { _id:this.getId(), _csrf_token_comment:this.getToken() };
    callService(this.getController()+'/delete', JSON.stringify(data), 'DELETE', 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
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