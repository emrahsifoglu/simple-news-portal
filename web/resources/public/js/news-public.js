$( document ).ready(function() {
    $(document).trigger('routeChanged', ['news']);
    var pages_count = $('#pages-count').val();
    var category = $('#category').val();
    $(document).trigger('categoryChange', [category, pages_count]);
});