jQuery(document).ready(function($){

if(typeof boxcoverfiles=='undefined'){boxcoverfiles = [];}
$('#boxcover_upload').filer({
        limit: 30,
        maxSize: 20,
        extensions: ["jpg", "png", "gif"],
        showThumbs: true,
        addMore: true,
        appendTo: '#boxcoverfiles',
        files: boxcoverfiles,
        templates: {
			box: '<ul class="jFiler-items-list jFiler-items-default"></ul>',
			item: '<li class="jFiler-item"><span class="media-file-title">{{fi-mediatype}}</span><div class="item gallery-item"><div class="photo">{{fi-image}}</div><div class="setprivateBoxCover-wrap"><input class="setprivateBoxCover" name="setprivateBoxCover[]" value="{{fi-id}}" type="checkbox"> Set Private</div><div class="selectBoxCover-wrap"><input class="selectBoxCover" name="selectBoxCover" value="{{fi-id}}" type="checkbox"> Select</div><div class="jFiler-item-info pull-left"><div class="jFiler-item-others"><ul class="list-inline trashcan"><li><a class="icon-jfi-trash jFiler-item-trash-action"><i class="dashicons dashicons-trash"></i></a></li></ul><span class="jFiler-item-status"></span></div><div class="jFiler-item-assets"></div></div></div></li>',
			itemAppend: '<li class="jFiler-item"><span class="media-file-title">{{fi-mediatype}}</span><div class="item gallery-item"><div class="photo">{{fi-image}}</div><div class="setprivateBoxCover-wrap"><input class="setprivateBoxCover" name="setprivateBoxCover[]" value="{{fi-mediaid}}" type="checkbox"> Set Private</div><div class="selectBoxCover-wrap"><input class="selectBoxCover" name="selectBoxCover" value="{{fi-mediaid}}" type="checkbox"> Select</div><div class="jFiler-item-info pull-left"><div class="jFiler-item-others"><ul class="list-inline trashcan"><li><a id="trash_{{fi-mediaid}}" class="icon-jfi-trash jFiler-item-trash-action"><i class="dashicons dashicons-trash"></i></a></li></ul><span class="jFiler-item-status"></span></div><div class="jFiler-item-assets"></div></div></div></li>',
			progressBar: '<div class="jFiler-jProgressBar"></div>',
			itemAppendToEnd: true,
			removeConfirmation: false,
			_selectors: {
				list: '.jFiler-items-list',
				item: '.jFiler-item',
				progressBar: '.jFiler-jProgressBar',
				remove: '.jFiler-item-trash-action'
			}
		},
        afterRender:function(data,el){
            var boxcovertype = $('#boxcovertype').val();
           
           if(boxcoverfiles.length>0){
            $.each(el,function(i,elem){  
                $(elem).find(".media-file-title").text(boxcoverfiles[i].mediatype);
            });
            }
            $('#boxcovertype').on('change',function(){
              boxcovertype = $('#boxcovertype').val();
              
            });
        },
        onSelect:function(imgdata,el){
            boxcovertype = $('#boxcovertype').val();
            var form = $('form')[1];
            var formData = new FormData(form);
            formData.append('action', 'rb_agency_upload_image');
            formData.append('profilemediatype', boxcovertype);
            formData.append('profilegallery', profilegallery);
            formData.append('profileid', profileid);
            formData.append('security', security);
            formData.append('rba_boxcover_upload[]', imgdata);
            $.ajax({
            url: ajaxurl,
            data: formData,
            type: 'POST',
            processData: false,
            contentType:false,
            enctype: 'multipart/form-data',
            success: function(data){ 
                location.reload();
                
            },
            error: function(el){
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                    $("<div class=\"jFiler-item-others text-error\"><i class=\"fa fa-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");    
                });
            },
            error: function(el){
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                    $("<div class=\"jFiler-item-others text-error\"><i class=\"fa fa-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");    
                });
            },
            statusCode: null,
            onProgress: null,
            onComplete: null
            
            });
        },
        captions: {
			button: "<i class='dashicons dashicons-plus-alt'></i>",
			feedback: "Choose Images To Upload",
			feedback2: "files were chosen",
			drop: "Drop file here to Upload",
			removeConfirmation: "Are you sure you want to remove this file?",
			errors: {
				filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
				filesType: "Only Images are allowed to be uploaded.",
				filesSize: "{{fi-name}} is too large! Please upload file up to {{fi-fileMaxSize}} MB.",
				filesSizeAll: "Files you've choosed are too large! Please upload files up to {{fi-maxSize}} MB."
			}
		},
       onRemove:function(data,el){ 
        var image = data.find(".jFiler-item-title").data('jfiler-index');
        $('input[value="'+image+'"]').remove();
        var thumbid = data[0].jfiler_id;
        var thumb = $('#thumb_'+thumbid);
        var imgid = data.find("input[name=selectBoxCover]").attr('value');
        
        $.ajax({
          method: "POST",
          url: ajaxurl,
          data: { mediaid: imgid,profileid:profileid,action: 'rb_agency_delete_image',security:security }
        }).done(function( msg ) {
            console.log( "Image deleted: " + msg );
          });
      }
     });
     
     
     var selectedBoxCover = [];
      
     $( "#boxcoverfiles" ).on('change',"input[name=selectBoxCover]",function(){
       var item = $(this).val();       
       if($(this).is(':checked')){
        selectedBoxCover.push(item);
        
       }else{
        var _searchedIndex = $.inArray(item,selectedBoxCover);
        if(_searchedIndex >= 0){
             selectedBoxCover.splice(_searchedIndex,1);
            }
       }
       console.log(selectedBoxCover);
                
     });
     
     $.each(boxcoverfiles,function(i,value){ 
        
        if(value.private!=0){
            $("input[value="+value.id+"].setprivateBoxCover").attr('checked','checked');
        }
        
     });
     
     $(document).on('click',"#deleteBoxCover",function(e){   
        e.preventDefault();
         if(selectedBoxCover.length>0){
                $.confirm({
                    title: 'Confirm!',
                    content: "Are you sure you want to delete selected Box Cover?",
                    buttons: {
                        Yes: {
                            btnClass: 'btn-red',
                            action:function(){
                            $(selectedBoxCover).each(function(i,v){
                                console.log($("a#trash_"+v));
                                $("a#trash_"+v).trigger('click');
                            });
                            selectedBoxCover = [];
                            }
                        },
                        cancel: function () {}
                    },
                    useBootstrap:false,
                    boxWidth:'400px'
                });   
            }else{
                $.alert({
                    title: 'Alert!',
                    content: 'No image selected!',
                    useBootstrap:false,
                    boxWidth:'400px'
                });
            }         
            return false;
         });
});  