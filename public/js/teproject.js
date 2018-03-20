$('nav ul li a').each(function() {
    var currentLocation = window.location.pathname;
    var thisLinksLocation = $(this).attr('href');

    if(currentLocation == thisLinksLocation) {
        $(this).addClass('active');
    }
});


