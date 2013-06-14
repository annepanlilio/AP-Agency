(function($){
  $(window).load(function () {
    updateImages();
    $(window).resize(function() {
        updateImages();
    });
  });
  function updateImages(){
    $('.photo a img, #profile-list .rbprofile-list .image a img').each(function(){
      var div = $(this).parent("a").parent("div");
      $divW = $(div).width();
      $divH = $(div).height();
      $(div).children("a").height($divW-11);
        var height = $(this).height();
        var width = $(this).width();
        if(height<$divH){
          $(this).addClass('fillheight');
          $(this).removeClass('fillwidth');          
        }
        if(width<$divW){
          $(this).addClass('fillwidth');
          $(this).removeClass('fillheight');
        }
    });
  }
})(jQuery);