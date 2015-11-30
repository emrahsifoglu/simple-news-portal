$(document).ready(function() {

    var category_name = $('#category-name');
    var add_category_btn = $('#add-category');
    var controller_route = $('#controller-category').val();
    var token = $('#csrf_token_category').val();
    var category_tr = $('#category-tr');
    var category_zero = $("#category-0");
    var temp_category_html = '';
    var current_action = '';
    var category = new Category();
    var currentRow = $('<tr></tr>');
    var dialog = null;

    add_category_btn.click(function(event) {
        event.preventDefault();
        if(!isTitleTaken($.trim(category_name.val()))){
            current_action = "add";
            category.preUpdate();
            category.setTitle($.trim(category_name.val()));
            if (category.isValid()){
                category.save(onSuccess, onError, onComplete);
            } else {
                current_action = '';
                errorDialog('not_valid', category.getErrors());
            }
        } else {
            errorDialog('is_taken', null);
        }
    });

    function isTitleTaken(title){
        return ($(".category").filter(function() {
            return $(this).find("td:eq(0)").html() == title;
        }).text().trim() != "");
    }

    function categoryRowClick(){
        currentRow.removeClass('glow');
        currentRow = $(this).parent('tr');
        currentRow.addClass('glow');
        category_name.val(currentRow.find("td:eq(0)").html());
    }

    function updateCategory(event) {
        event.preventDefault();
        if(!isTitleTaken($.trim(category_name.val()))){
            var title = $.trim(category_name.val());
            if ($(this).closest('tr').find("td:eq(0)").html() != title) {
                current_action = "update";
                category.preUpdate();
                category.setId($(this).attr('href'));
                category.setTitle($.trim(category_name.val()));
                if (category.isValid()){
                    category.save(onSuccess, onError, onComplete);
                } else {
                    current_action = '';
                    errorDialog('not_valid', category.getErrors());
                }
            }
        }else {
            errorDialog('is_taken', null);
        }
    }

    function deleteCategory(event) {
        event.preventDefault();
        var id = $(this).attr('href');
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
                current_action = "destroy";
                category.setId(id);
                category.destroy(onSuccess, onError, onComplete);
            }
        }, {
            id: 'btn-cancel',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];
        dialog = BootstrapDialogShow(BootstrapDialog.TYPE_DANGER, 'WARNING!', '<b>Do you want to delete this category?</b>', buttons);
        dialog.setClosable(true);
        dialog.open();
    }

    function onSuccess(returnData, textStatus, jqXHR){
        var status = jqXHR.status;
        var data;
        switch (status){
            case 200:
                if (current_action == 'update'){
                    data = $.parseJSON(returnData);
                    if (data.id > 0) {
                        var id = category.getId();
                        category_tr = $("#category-"+id);
                        category_tr.find("td:eq(0)").html(category.getTitle());
                    }
                } else if(current_action == 'load') {
                    updateRows(returnData);
                }
                break;
            case 201:
                if (current_action == 'add'){
                    data = $.parseJSON(returnData);
                    category.setId(data.id);
                    appendRow(
                        category.getId(),
                        category.getTitle()
                    );
                    bindCategoryControl(category.getId());
                }
                break;
            case 204:
                if (current_action == 'destroy'){
                    if (dialog != null) {
                        dialog.close();
                        dialog = null;
                    }
                    unbindCategoryControl(category.getId());
                    $("#category-"+category.getId()).remove();
                }
                break;
        }
    }

    function onError(jqXHR, textStatus, errorThrown){
        var error;
        if (jqXHR.responseText != ''){
            try {
                error = JSON.parse(jqXHR.responseText).error;
            }
            catch(e) {
                error = textStatus;
            }
        }
        errorDialog(error, errorThrown);
    }

    function errorDialog(error, data){
        var title = 'Category Failed';
        var message = '';
        var type = BootstrapDialog.TYPE_WARNING;
        switch (error){
            case 'not_valid':
                title = 'Form Validation';
                type = BootstrapDialog.TYPE_WARNING;
                message = '';
                $.each(data, function(i, e){
                    message += '<p><b>'+e.msg+'</b></p>';
                });
                break;
            case 'bad_request' :
                type = BootstrapDialog.TYPE_DANGER;
                message = '<b>Server did not get the request.</b>';
                break;
            case 'is_taken' :
                type = BootstrapDialog.TYPE_WARNING;
                message = '<b>Title might be being used.</b>';
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
        if (!dialog.opened) dialog.open();
        dialog = null;
    }

    function onComplete(){
        current_action = '';
    }

    function bindCategoryControls(){
        $('.category-td').bind("click", categoryRowClick);
        $(".delete-category").bind("click", deleteCategory);
        $(".update-category").bind("click", updateCategory);
    }

    function bindCategoryControl(id){
        $('.category-td', "#category-"+id).bind("click", categoryRowClick);
        $(".delete-category", "#category-"+id).bind("click", deleteCategory);
        $(".update-category", "#category-"+id).bind("click", updateCategory);
    }

    function unbindCategoryControls(){
        $('.category-td').unbind("click", categoryRowClick);
        $(".delete-category").unbind("click", deleteCategory);
        $(".update-category").unbind("click", updateCategory);
    }

    function unbindCategoryControl(id){
        $('.category-td', "#category-"+id).unbind("click", categoryRowClick);
        $(".delete-category", "#category-"+id).unbind("click", deleteCategory);
        $(".update-category", "#category-"+id).unbind("click", updateCategory);
    }

    function updateRows(data){
        if (data != ''){
            var categories = $.parseJSON(data);
            if (categories.length > 0) createRows(categories);
        }
    }

    function createRows(categories){
        $.each( categories, function( i, category ) {
            var id = category[0];
            var title = category[1];
            appendRow(id, title);
        });
        bindCategoryControls();
    }

    function appendRow(id, title){
        var category_html = temp_category_html.split('[[id]]').join(id).replace('[[title]]', title);
        category_zero.after('<tr class="category" id="category-'+id+'">'+category_html+'</tr>');
    }

    function setCategoryHTML(tr){
        temp_category_html = tr.html();
        tr.remove();
    }

    function loadCategories(){
        current_action = "load";
        category.fetch(onSuccess, onError, onComplete);
    }

    function initAdmin(){
        category.setController(controller_route+'/');
        category.setToken(token);
        setCategoryHTML(category_tr); // store html to use as a template
    }

    initAdmin();
    loadCategories();
});