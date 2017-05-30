jQuery(document).ready(function($){
    
    $(".add-pending-btn").on("click",function(e){
        e.preventDefault();
        var inputfield = "<input type='hidden' name='action' value='add'/>";
        $("#updateprofileMedia").append(inputfield);
        $("#updateprofileMedia").submit();
    });
    
    $(".remove-missing-btn").on("click",function(e){
        e.preventDefault();
        var inputfield = "<input type='hidden' name='action' value='remove'/>";
        $("#updateprofileMedia").append(inputfield);
        $("#updateprofileMedia").submit();
    });
    
    $(".setprimary").on('click',function(){
        var setprivate = $(this).attr("checked");
            var isprivate = 0;
            if(setprivate=='checked'){
                isprivate = 1;
            }
            var mediaid = $(this).val();
            $.ajax({
              method: "POST",
              url: ajaxurl,
              data: { isprivate: isprivate,mediaid: mediaid,action: 'rb_agency_setprimary_image' }
              })
              .done(function( msg ) {
                console.log(msg);
            });
    });
    
});