(function($){
    $("#photobook").booklet({
        width: 600,
        height: 450,
        pageNumbers: false,
        pagePadding: 0,
        overlays: true,
        manual: false,
        closed: true,        
    });

    $('#next-page').click(function(e){
        e.preventDefault();
        $('#photobook').booklet("next");
    });

    $('#prev-page').click(function(e){
        e.preventDefault();
        $('#photobook').booklet("prev");
    });
})(jQuery);