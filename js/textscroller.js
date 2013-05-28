(function($){
  $(document).ready(function() {
      $("a.scroll").hover(
        function () {
          $(this).stop().animate({
            textIndent: "-" + ( $(this).width() - $(this).parent().width() ) + "px"  
          }, 1000);  
        }, 
        function () {
          $(this).stop().animate({
            textIndent: "0"           
          }, 1000);  
        }
      );
  });
})(jQuery);