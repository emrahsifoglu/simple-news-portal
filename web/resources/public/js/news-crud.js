jQuery.each( [ "put", "delete" ], function( i, method ) {
    jQuery[ method ] = function( url, data, callback, type ) {
        if ( jQuery.isFunction( data ) ) {
            type = type || callback;
            callback = data;
            data = undefined;
        }

        return jQuery.ajax({
            url: url,
            type: method,
            dataType: type,
            data: data,
            success: callback
        });
    };
});

$( document ).ready(function() {

    $(document).trigger('routeChanged', ['news']);
    var news_controller = $('#controller-news').val();
    var news_form = $('#form-news');
    var category_cb = $('.category-cb');
    var add_news_btn = $('#add-news');
    var errors = [];
    var dialog = null;
    var news_id = 0;
    var current_category = 'all';
    var legend = $('legend');
    var callBack = null;
    var is_category_clicked = false;
    var is_news_clicked = false;

    function categoryClick(event){
        event.preventDefault();
        if (!is_category_clicked){
            is_category_clicked = true;
            news_form.find(':input').prop('disabled', is_category_clicked);
            clearAll();
            unbindNewsControls();
            changeCategory($(this).attr('id'), null);
        }
    }

    function deleteNews(event){
        event.preventDefault();
        var id = $(this).attr('id').replace('news-','');
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
                clearAll();
                news_form.find(':input').prop('disabled', true);
                $.delete(news_controller+'/delete/'+id, JSON.stringify({ _csrf_token_news: $('#_csrf_token_news').val() }), function(data, textStatus, jqXHR){
                    if (jqXHR.status == 204){
                        onSuccess('delete', null);
                    } else {
                        onError('unknown', null);
                    }
                }).fail(function(jqXHR, textStatus, errorThrown){
                    onError(textStatus, errorThrown);
                });
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

    function readNews(event){
        event.preventDefault();
        if (!is_news_clicked){
            is_news_clicked = true;
            news_form.find(':input').prop('disabled', is_news_clicked);
            category_cb.attr('disabled', 'disabled');
            var id = $(this).attr('id').replace('news-','');
            $("#news-"+news_id).find('.news-td').removeClass('glow');
            $("#news-"+id).find('.news-td').addClass('glow');
            $.getJSON(news_controller+'/read/'+id+'/'+new Date().getTime(), function(data){
                onSuccess('read', data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                is_news_clicked = false;
                news_form.find(':input').prop('disabled', is_news_clicked);
                category_cb.removeAttr('disabled');
                onError(textStatus, errorThrown);
            });
        }
    }

    function unbindNewsControls(){
         $(".delete-news").unbind("click", deleteNews);
         $(".read-news").unbind("click", readNews);
    }

    function bindControls(){
        var delete_news_btn = $(".delete-news");
        var update_news_btn = $(".read-news");
        delete_news_btn.bind("click", deleteNews);
        update_news_btn.bind("click", readNews);
    }

    function changeCategory(id, _callBack){
        $(document).trigger('categoryChangeStart');
        $("#category-"+current_category).first('td').removeClass('glow');
        $("#category-"+id).first('td').addClass('glow');
        $.getJSON(news_controller+'/getNewsPageCount/'+id+'/10/'+new Date().getTime(), function(data){
            current_category = id;
            callBack = _callBack;
            $(document).trigger('categoryChange', [id, data]);
        });
    }

    function clearAll(){
        legend.text('News');
        clearCategorySelection();
        clearFileSelection();
        clearFormInputs();
    }

    function clearFormInputs(){
        news_id = 0;
        news_form.find('#_id').val(news_id);
        news_form.find('#_title').val('');
        news_form.find('#_description').val('');
        news_form.find('#_content').val('');
    }

    function clearCategorySelection(){
        category_cb.prop('checked', false);
        category_cb.attr('disabled', 'disabled');
    }

    function addError(name , msg){
        errors.push({name:name, msg:msg});
    }
    function clearErrors(){
        errors = [];
    }

    function getErrors(){
        return errors;
    }

    function isValid(){
        return (errors.length == 0);
    }

    function onSuccess(status, data){
        switch (status){
            case 'create':
                clearFileSelection();
                news_id = data;
                news_form.find('#_id').val(news_id);
                changeCategory('all', function(){
                    news_form.find(':input').prop('disabled', false);
                    category_cb.prop('checked', false);
                    category_cb.removeAttr('disabled');
                    legend.text('News is added.');
                });
                break;
            case 'update':
                clearFileSelection();
                $("#news-"+news_id).find('.news-td').text(news_form.find('#_title').val());
                news_form.find(':input').prop('disabled', false);
                category_cb.removeAttr('disabled');
                legend.text('News is updated.');
                break;
            case 'delete':
                changeCategory(current_category, function(){
                    if (dialog != null){
                        dialog.close();
                        dialog = null;
                    }
                    news_form.find(':input').prop('disabled', false);
                    legend.text('News is deleted.');
                });
                break;
            case 'read':
                if (data != ''){
                    try {
                        var news = data.news[0];
                        var categories = data.categories;

						clearFileSelection();
                        news_id = news[0];
                        news_form.find('#_id').val(news_id);
                        news_form.find('#_title').val(news[1]);
                        news_form.find('#_description').val(news[2]);
                        news_form.find('#_content').val(news[3]);
                        news_form.find('#_title').focus();

                        clearCategorySelection();
                        $.each(categories, function(i, c){
                            $('#category-cb-'+c[0]).prop('checked', true);
                        });

                        legend.text("News's title is " + news[1]);
                        category_cb.removeAttr('disabled');
                        is_news_clicked = false;
                        news_form.find(':input').prop('disabled', is_news_clicked);
                        category_cb.removeAttr('disabled');
                    }
                    catch(e) {

                    }
                }
                break;
        }
    }

    function onError(textError, data){
        var title = 'News Failed';
        var message = '';
        var type = BootstrapDialog.TYPE_WARNING;
        switch (textError){
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
            news_form.find(':input').prop('disabled', false);
            if(news_id > 0) category_cb.removeAttr('disabled');
            dialog = null;
        });
        if (!dialog.opened) dialog.open();
    }

    news_form.ajaxForm({
         url: news_form.attr('action'),
         data: news_form.serialize(),
         type: (news_form.attr('method')),
         beforeSend : function (xhr){
             clearErrors();

             news_form.find(':input').prop('disabled', true);

             var title = $.trim(news_form.find('#_title').val());
             var description = $.trim(news_form.find('#_description').val());
             var content = $.trim(news_form.find('#_content').val());

             if (news_form.find('#_id').val() == 0){
                 if (!isFileSelected()) addError('image', 'image is not selected');
             } else {
                 category_cb.attr('disabled', 'disabled');
             }

             if(!isStringLenValid(title, 3, 50)){
                 addError('title', 'title is not valid');
             }

             if(!isStringLenValid(description, 3, 250)){
                 addError('description', 'description is not valid');
             }

             if(!isStringLenValid(content, 3, 1000)){
                 addError('content', 'content is not valid');
             }

             if(!isValid()){
                 xhr.abort();
                 legend.text('News');
                 onError('not_valid',getErrors());
             }

         },
         uploadProgress :function(event, position, total, percentComplete) {
            legend.text('News %' + percentComplete);
         },
         success : function (responseData, textStatus, jqXHR){
            if (jqXHR.status === 201){
                onSuccess('create', $.parseJSON(responseData).id);
            } else if (jqXHR.status === 200) {
                var data = $.parseJSON(responseData);
                onSuccess(data.status, data.id);
            }  else if (jqXHR.status == 204){

            }  else {
                legend.text('News');
                onError('unknown', null);
            }
         },
         error : function(jqXHR, textStatus, errorThrown) {
             var error;
             if (jqXHR.responseText != ''){
                 try {
                     error = JSON.parse(jqXHR.responseText).error;
                 }
                 catch(e) {
                     error = errorThrown;
                 }
             }
             legend.text('News');
             onError(textStatus ,error);
         }
    });

    $(this).on('newsLoad', function(event){
        bindControls();
        if (news_id > 0) $("#news-"+news_id).find('.news-td').addClass('glow');
        if (callBack !== null) callBack();
        callBack = null;
        is_category_clicked = false;
        news_form.find(':input').prop('disabled', is_category_clicked);
    });

    category_cb.click(function () {
        if (news_id != 0){
            category_cb.attr('disabled', 'disabled');
            var category_id = $(this).closest('td').attr('id');
            var checked = ($(this).is(':checked') == true) ? 1 : 0;
            $.post(news_controller+'/categoryUpdate/'+news_id+'/'+category_id+'/'+checked,function(data){
                category_cb.removeAttr('disabled');
            });
        }
    });

    add_news_btn.click(function(){
        $("#category-"+current_category).first('td').removeClass('glow');
        $("#news-"+news_id).find('.news-td').removeClass('glow');
        clearAll();
        news_form.find('#_title').focus();
    });

    fileBrowser();
    $('.category-td').bind('click', categoryClick);
    $('#category-all').find('td').click();
});