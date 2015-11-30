$( document ).ready(function() {

    var paginate_clicked = $('<a></a>');
    var news_controller = $('#controller-news').val();
    var item_per_page = $('#item-per-page').val();
    var news_count = $('#news-count').val();
    var pages_count = 0;
    var category = 'all';
    var paginate_tr = $('#paginate-tr');
    var temp_news_html = '';
    var news_zero = $('#news-0');
    var page = 0;

    $(this).on('categoryChangeStart', function(event){
        categoryChangeStart();
    });

    $(this).on('categoryChange', function(event, _category, _pages_count){
        pages_count = $('#pages-count').val(_pages_count).val();
        category = $('#category').val(_category).val();
        page = 0;
        init();
    });

    function categoryChangeStart(){
        clearPaginate();
        $(".news-tr").remove();
    }

    function paginateClick(event){
        event.preventDefault();
        paginate_clicked.removeClass('active');
        paginate_clicked = $(this);
        paginate_clicked.addClass('active');
        page = paginate_clicked.attr('id');
        $(".news-tr").remove();
        getNews();
    }

    function createRows(news){
        $.each( news, function( i, n ) {
            var id = n[0];
            var title = n[1];
            var description = n[2];
            appendRow(id, title, description);
        });
    }

    function clearPaginate(){
        paginate_tr.find('ul').empty();
        paginate_tr.hide();
    }

    function updatePaginate(){
        for (var i = 0; i < pages_count; i++){
            paginate_tr.find('#news').append('<li><a href="#" class="paginate_click" id='+i+'>'+(i+1)+'</a></li>');
        }
        paginate_tr.show();
    }

    function appendRow(id, title, description){
        var news_html = temp_news_html.
            split('[[id]]').join(id).
            replace('[[read]]', news_controller+'/read/'+id).
            replace('[[description]]', description).
            replace('[[title]]', title);
        news_zero.after('<tr class="news-tr" id="news-'+id+'">'+news_html+'</tr>');
    }

    function getNews(){
        $('#grid-news').find('#title').html('Loading...');
        $.getJSON(news_controller+'/category/'+category+'/'+(page*item_per_page)+'/'+item_per_page+'/'+new Date().getTime(), function( data ) {
            $('#grid-news').find('#title').html('NEWS');
                createRows(data);
                $(document).trigger('newsLoad');
        });
    }

    function init(){
        categoryChangeStart();
        if (pages_count > 1){
            updatePaginate();
            $(".paginate_click").click(paginateClick);
            $(".paginate_click").first().trigger('click');
        }  else {
            getNews();
        }
    }

    temp_news_html = $('#news-tr').html();
    $('#news-tr').remove();
});