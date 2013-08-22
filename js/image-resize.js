(function($){
  $(window).load(function () {
    updateImages();
  });
  $(window).resize(function() {
      updateImages();
  });
  function updateImages(){
    $('#photos .photo a').each(function(){
      var thumbHeight = $(this).width();
      $(this).height(thumbHeight);
    });
  }
})(jQuery);