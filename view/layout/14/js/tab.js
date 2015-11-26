jQuery(".tabs-menu a").click(function(event) {
    event.preventDefault();
    jQuery(this).parent().addClass("current");
    jQuery(this).parent().siblings().removeClass("current");
    var tab = jQuery(this).attr("href");
    jQuery(".tab-content").not(tab).css("display", "none");
    jQuery(tab).fadeIn(1000);
});