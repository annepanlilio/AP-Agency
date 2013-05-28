(function($){
  $(window).load(function () {
    updateImages();
    $(window).resize(function() {
        updateImages();
    });
  });
  function updateImages(){
    $('.photo a img, .image a img').each(function(){
      var div = $(this).parent("a").parent("div");
      $divW = $(div).width();
      $divH = $(div).height();
      $(div).children("a").height($divW-11);
        var height = $(this).height();
        var width = $(this).width();
        if(height<$divH){
          $(this).addClass('fillheight');
          $(this).removeClass('fillwidth');
          var imargin = parseInt(width/2);
          $('.fillheight').css({'margin-left':'-'+imargin+'px'});
        }
        if(width<$divW){
          $(this).addClass('fillwidth');
          $(this).removeClass('fillheight');
        }
        $(this).attr({'height': height, 'width': width});
    });
  }
})(jQuery);