$( document ).ready(function() {
    $(document).trigger('routeChanged', ['home']);
    eventPreventer('click', $('.navbar-brand'));
});