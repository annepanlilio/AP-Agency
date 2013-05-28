$(window).load(function () {
  updateImages();
  $(window).resize(function() {
      updateImages();
  });
});
function updateImages(){
  $('.photo a img, .image a img').each(function(){
    var div = $(this).parent("a").parent("div");
    var divW = $(div).width();
    $(div).height(divW);
    $('.image-broken').height(divW);
    $(div).children("a").height(divW-11);
      var height = $(this).height();
      var width = $(this).width();
      if(height<width){
        $(this).addClass('fillheight');
        $(this).removeClass('fillwidth');
        var imargin = parseInt(width/2);
        $('.fillheight').css({'margin-left':'-'+imargin+'px'});
      }
      if(width<height){
        $(this).addClass('fillwidth');
        $(this).removeClass('fillheight');
      }
      $(this).attr({'height': height, 'width': width});
  });
}