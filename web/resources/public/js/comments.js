$(document).ready(function() {

    $(document).trigger('routeChanged', ['news']);
    $(this).on('login', function(event, param){
        $('#comment-input').show();
    });

    var comment = new Comment();
    var add_comment = $('#add_comment');
    var temp_comment_html = $('#comment-0').html();
    var comments_ul = $('.comments-ul');

    add_comment.click(function(event){
        event.preventDefault();
        comment.prepare();
        comment.setContent($('#content').val());
        if (comment.isValid()){
            comment.save(onSuccess, onError, onComplete);
        } else {
            console.log(comment.getErrors());
        }

    });

    function onSuccess(responsedata, textStatus, jqXHR){
        if (jqXHR.status == 201){
            if (responsedata != ''){
                try {
                    var data = JSON.parse(responsedata);
                    var id = data[0];
                    var username = data[1];
                    var made_date = data[2];
                    var comment_html = temp_comment_html.
                        replace('[[date]]', made_date).
                        replace('[[username]]', username).
                        replace('[[content]]', comment.getContent());
                    comments_ul.prepend('<li id="comment-'+id+'">'+ comment_html +'</li>');
                }
                catch(e) {

                }
            }
        }
    }

    function onError(jqXHR, textStatus, errorThrown){
        console.log(errorThrown);
    }

    function onComplete(){

    }

    comment.setController($('#controller-comment').val());
    comment.setToken($('#csrf-token-comment').val());
    comment.setNewsId($('legend').attr('id'));
});