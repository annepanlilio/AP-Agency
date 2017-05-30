jQuery(document).ready(function($){


$('#file_upload').filer({
        limit: 30,
        maxSize: 20,
        extensions: ["jpg", "png", "gif"],
        showThumbs: true,
        addMore: true,
        appendTo: '#files',
        files: files,
        templates: {
			box: '<ul class="jFiler-items-list jFiler-items-default"></ul>',
			item: '<li class="jFiler-item"><div class="item gallery-item"><div class="photo">{{fi-image}}</div><div class="make-primary"><input class="setprimary" name="ProfileMediaPrimary" value="{{fi-id}}" type="radio"> Set Primary</div><div class="setprivate-wrap"><input class="setprivate" name="setprivate[]" value="{{fi-id}}" type="checkbox"> Set Private</div><div class="selectProfileMedia-wrap"><input class="selectProfileMedia" name="selectProfileMedia" value="{{fi-id}}" type="checkbox"> Select</div><div class="jFiler-item-info pull-left"><div class="jFiler-item-others"><ul class="list-inline"><li><a class="icon-jfi-trash jFiler-item-trash-action"><i class="dashicons dashicons-trash"></i></a></li></ul><span class="jFiler-item-status"></span></div><div class="jFiler-item-assets"></div></div></div></li>',
			itemAppend: '<li class="jFiler-item"><div class="item gallery-item"><div class="photo">{{fi-image}}</div><div class="make-primary"><input class="setprimary" name="ProfileMediaPrimary" value="{{fi-mediaid}}" type="radio"> Set Primary</div><div class="setprivate-wrap"><input class="setprivate" name="setprivate[]" value="{{fi-mediaid}}" type="checkbox"> Set Private</div><div class="selectProfileMedia-wrap"><input class="selectProfileMedia" name="selectProfileMedia" value="{{fi-mediaid}}" type="checkbox"> Select</div><div class="jFiler-item-info pull-left"><div class="jFiler-item-others"><ul class="list-inline"><li><a id="trash_{{fi-mediaid}}" class="icon-jfi-trash jFiler-item-trash-action"><i class="dashicons dashicons-trash"></i></a></li></ul><span class="jFiler-item-status"></span></div><div class="jFiler-item-assets"></div></div></div></li>',
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
        uploadFile: {
            url: ajaxurl,
            data: {action:'rb_agency_upload_image',profilegallery: profilegallery,profileid:profileid},
            type: 'POST',
            enctype: 'multipart/form-data',
            beforeSend: function(){},
            success: function(data, el){ 
                var img = $.parseJSON(data);
                var imgurl = img.files;
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                    $('<div class="jFiler-item-others text-success"><i class="dashicons dashicons-plus-alt"></i> Success</div>').hide().appendTo(parent).fadeIn("slow");    
                });
                
                $.each(img,function(i,value){
                    el.find("input[name=ProfileMediaPrimary]").attr('value',value.mediaid);
                    el.find("input[name='setprivate[]']").attr('value',value.mediaid);
                    el.find("input[name='selectProfileMedia']").attr('value',value.mediaid);
                    el.find(".jFiler-item-trash-action").attr('id',"trash_"+value.mediaid);
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
        },
       onRemove:function(data,el){ 
        var image = data.find(".jFiler-item-title").data('jfiler-index');
        $('input[value="'+image+'"]').remove();
        var thumbid = data[0].jfiler_id;
        var thumb = $('#thumb_'+thumbid);
        var imgid = data.find("input[name=ProfileMediaPrimary]").attr('value');
        
        $.ajax({
          method: "POST",
          url: ajaxurl,
          data: { mediaid: imgid,profileid:profileid,action: 'rb_agency_delete_image' }
        }).done(function( msg ) {
            console.log( "Image deleted: " + msg );
          });
      }
     });
     
     var selectedProfileMedia = [];
      
     $( "#files" ).on('change',"input[name=selectProfileMedia]",function(){
       var item = $(this).val();       
       if($(this).is(':checked')){
        selectedProfileMedia.push(item);
        
       }else{
        var _searchedIndex = $.inArray(item,selectedProfileMedia);
        if(_searchedIndex >= 0){
             selectedProfileMedia.splice(_searchedIndex,1);
            }
       }
       console.log(selectedProfileMedia);
                
     }); 
     
     $(document).on('click',"#deleteProfileMedia",function(e){   
        e.preventDefault();
         if(selectedProfileMedia.length>0){
                $.confirm({
                    title: 'Confirm!',
                    content: "Are you sure you want to delete selected image/s?",
                    buttons: {
                        Yes: {
                            btnClass: 'btn-red',
                            action:function(){
                            $(selectedProfileMedia).each(function(i,v){
                                console.log($("a#trash_"+v));
                                $("a#trash_"+v).trigger('click');
                            });
                            selectedProfileMedia = [];
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
     
     $.each(files,function(i,value){ 
        if(value.primary!=0){
            $("input[value="+value.id+"].setprimary").attr('checked','checked');
        }
        if(value.private!=0){
            $("input[value="+value.id+"].setprivate").attr('checked','checked');
        }
        
     });
     
     $(document).on('change','.setprimary',function(){ 
            var setprivate = $(this).attr("checked");
            var isprivate = 0;
            if(setprivate=='checked'){
                isprivate = 1;
            }
            var mediaid = $(this).val();
            $.ajax({
              method: "POST",
              url: ajaxurl,
              data: { profileid: profileid,mediaid: mediaid,action: 'rb_agency_setprimary_image' }
              })
              .done(function( msg ) {
                console.log(msg);
            });
            
     });
     
     $(document).on('change','.setprivate',function(){ 
            var setprivate = $(this).attr("checked");
            var isprivate = 0;
            if(setprivate=='checked'){
                isprivate = 1;
            }
            var mediaid = $(this).val();
            $.ajax({
              method: "POST",
              url: ajaxurl,
              data: { isprivate: isprivate,mediaid: mediaid,action: 'rb_agency_setprivate_image' }
              })
              .done(function( msg ) {
                console.log(msg);
            });
            
     });
     
     $("#wrapper-sortable #gallery-sortable").sortable(
			{item:'.item',
			cursor:'move',
			update: function( event, ui ) {
					$("#wrapper-sortable #gallery-sortable .item").each(function(i,d){
						$(this).find("input[type=hidden]").val(i);
					});
					$("#notify-gallery").css('display','block').fadeOut().fadeIn().fadeOut().fadeIn();
			}
		});
	$("#wrapper-sortable #gallery-sortable").disableSelection();
    
								
		//onload
		var profileTypeTitles = $('.userProfileType:checkbox:checked').map(function() {
			return $(this).attr('profile-type-title');
		}).get();
	
		$(".userProfileType").click(function(){
			$(".tbody-table-customfields").empty();
			$(".tbody-table-customfields-private").empty();
			var profileTypeTitles = $('.userProfileType:checkbox:checked').map(function() {
				return $(this).attr('profile-type-title');
			}).get();
			if(profileTypeTitles.length>0){
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						action: "rb_get_customfields_edit_profile_onchanged_profiletype",
						'profile_types': profileTypeTitles,
						'profileID': ProfileID,
						'gender': jQuery("#ProfileGender").val()
					},
					success: function (results) {
						jQuery(".tbody-table-customfields").html(results);
						console.log(results);
					}
				});	
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						action: "rb_get_customfields_edit_profile_onchanged_profiletype_private",
						'profile_types': profileTypeTitles,
						'profileID': ProfileID,
						'gender': jQuery("#ProfileGender").val()
					},
					success: function (results) {
						jQuery(".tbody-table-customfields-private").html(results);
						console.log(results);
					}
				});
			}else{
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						action: "rb_get_customfields_edit_profile",
						'profileID': ProfileID,
						'gender': jQuery("#ProfileGender").val()
					},
					success: function (results) {
						jQuery(".tbody-table-customfields").html(results);
						console.log(results);
					}
				});	
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						action: "rb_get_customfields_edit_profile_private",
						'profileID': ProfileID,
						'gender': jQuery("#ProfileGender").val()
					},
					success: function (results) {
						jQuery(".tbody-table-customfields-private").html(results);
						console.log(results);
					}
				});
			}
		});
        
		$("#ProfileGender").on("change",function(){
			$(".tbody-table-customfields").empty();
			var profileTypeTitles = $('.userProfileType:checkbox:checked').map(function() {
				return $(this).attr('profile-type-title');
			}).get();
			if(profileTypeTitles.length>0){
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						action: "rb_get_customfields_edit_profile_onchanged_profiletype",
						profile_types: profileTypeTitles,
                        profileID: ProfileID,
						gender: $(this).val()
					},
					success: function (results) {
						jQuery(".tbody-table-customfields").html(results);
						console.log(results);
					}
				});	
				
                var privatetable = $(".tbody-table-customfields-private");
                if(privatetable)
                { 
                    jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							action: "rb_get_customfields_edit_profile_onchanged_profiletype_private",
							'profile_types': profileTypeTitles,
                            'profileID': ProfileID,
							'gender': $(this).val()
						},
						success: function (results) {
							jQuery(".tbody-table-customfields-private").html(results);
							console.log(results);
						}
					});	
                }
                
			}else{
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						action: "rb_get_customfields_edit_profile",
                        profileID: ProfileID,
						gender: $(this).val()
					},
					success: function (results) {
						jQuery(".tbody-table-customfields").html(results);
						console.log(results);
					}
				});	
                var privatetable = $(".tbody-table-customfields-private");
                if(privatetable)
                {
					jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							action: "rb_get_customfields_edit_profile_private",
                            profileID: ProfileID,
							gender: $(this).val()
						},
						success: function (results) {
							jQuery(".tbody-table-customfields-private").html(results);
							console.log(results);
						}
					});	
                }
			}
		});					
							
});