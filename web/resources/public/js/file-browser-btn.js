var is_fn_selected = false;
var browse;
var image_file;
var fn = '';
var ext = '';
var exts;
var valid_size;
var upload_max_file_size;

function makeItShort(caption, ext){
    return (caption.length > 10) ? caption.substr(0, 10)+'...'+ext : caption;
}

function isFileSelected(){
    return is_fn_selected;
}

function clearFileSelection(){
    image_file.val('');
    is_fn_selected = false;
    fn = ext = '';
    browse.html('Select');
}

function fileBrowser(){
    exts			     = ['gif','png','jpg','jpeg'];
    valid_size	         = 0;
    upload_max_file_size = 1;
    browse	             = $("#browse");
    image_file		     = $("#image_file");
    fn				     = '';
    ext				     = '';

    image_file.fileValidator({
        onValidation: function(files){
            valid_size = 1;
        },
        onInvalid:    function(type, file){
            valid_size = 0;
        },
        maxSize: upload_max_file_size+'m'
    });

    browse.click(function() {
        image_file.click();
    });

    image_file.change(function() {
        fn = $(this).val();
        ext = fn.split('.').pop().toLowerCase();
        if($.inArray(ext, exts) == -1) {
            $(this).val('');
            is_fn_selected = false;
            fn = ext = '';
            browse.html('Select');
            alert('Invalid file type!');
        }else{
            if (valid_size){
                var caption = fn;
                is_fn_selected = true;
                browse.html(makeItShort(caption, ext));
            }else{
                $(this).val('');
                is_fn_selected = false;
                fn = ext = '';
                browse.html('Select');
                alert('File must be less then '+upload_max_file_size+'mb.');
            }
        }
    });

    var shorter = makeItShort(browse.html(), ext);
    browse.html(shorter);
}