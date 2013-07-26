(function($){
  $(window).load(function () {
    updateImages();
    $(window).resize(function() {
        updateImages();
    });
  });
  function updateImages(){
    $('.photo a img').each(function(){
      var imgW = $(this).width();
      var imgH = $(this).height();
      var div = $(this).parent("a").parent("div");
      var diva = $(this).parent("a");
      $(div).height(div.width());
      $(diva).height(div.width()-11);
      if(imgW>imgH){
        $(this).addClass('fillheight');
        $(this).removeClass('fillwidth');          
      } else if(imgH>imgW){
        $(this).addClass('fillwidth');
        $(this).removeClass('fillheight');
      }
    });
  }
})(jQuery);