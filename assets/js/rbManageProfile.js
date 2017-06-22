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
			item: '<li class="jFiler-item"><div class="item gallery-item"><i class="dashicons dashicons-leftright"></i><div class="photo">{{fi-image}}</div><div class="make-primary"><input class="setprimary" name="ProfileMediaPrimary" value="{{fi-id}}" type="radio"> Set Primary</div><div class="setprivate-wrap"><input class="setprivate" name="setprivate[]" value="{{fi-id}}" type="checkbox"> Set Private</div><div class="selectProfileMedia-wrap"><input class="selectProfileMedia" name="selectProfileMedia" value="{{fi-id}}" type="checkbox"> Select</div><div class="jFiler-item-info pull-left"><div class="jFiler-item-others"><ul class="list-inline"><li><a class="icon-jfi-trash jFiler-item-trash-action"><i class="dashicons dashicons-trash"></i></a></li></ul><span class="jFiler-item-status"></span></div><div class="jFiler-item-assets"></div></div></div></li>',
			itemAppend: '<li class="jFiler-item"><div class="item gallery-item"><i class="dashicons dashicons-leftright"></i><div class="photo">{{fi-image}}</div><div class="make-primary"><input class="setprimary" name="ProfileMediaPrimary" value="{{fi-mediaid}}" type="radio"> Set Primary</div><div class="setprivate-wrap"><input class="setprivate" name="setprivate[]" value="{{fi-mediaid}}" type="checkbox"> Set Private</div><div class="selectProfileMedia-wrap"><input class="selectProfileMedia" name="selectProfileMedia" value="{{fi-mediaid}}" type="checkbox"> Select</div><div class="jFiler-item-info pull-left"><div class="jFiler-item-others"><ul class="list-inline"><li><a id="trash_{{fi-mediaid}}" class="icon-jfi-trash jFiler-item-trash-action"><i class="dashicons dashicons-trash"></i></a></li></ul><span class="jFiler-item-status"></span></div><div class="jFiler-item-assets"></div></div></div></li>',
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
            data: {action:'rb_agency_upload_image',profilemediatype:'Image',profilegallery: profilegallery,profileid:profileid},
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
     
     var selectedMediaFiles = [];
     
     $( ".media-files" ).on('change',"input[name=media_files]",function(){
       var item = $(this);       
       if($(this).is(':checked')){
        selectedMediaFiles.push(item);
       }else{
        var _searchedIndex = $.inArray(item,selectedMediaFiles);
        if(_searchedIndex >= 0){
             selectedMediaFiles.splice(_searchedIndex,1);
            }
       }
                
     }); 
     
     $("#dashboard_media").on('click',"#bulk_delete_media",function(e){   
        e.preventDefault();
         if(selectedMediaFiles.length>0){
                $.confirm({
                    title: 'Confirm!',
                    content: "Are you sure you want to delete selected media file/s?",
                    buttons: {
                        Yes: {
                            btnClass: 'btn-red',
                            action:function(){
                            $(selectedMediaFiles).each(function(i,v){
                                var mediaid = $(v).val();
                                $.post(ajaxurl,{action:'rb_agency_delete_image',mediaid:mediaid,profileid:ProfileID},function(result){
                                    var msg = $.parseJSON(result);
                                    if(msg.error){
                                        $.alert({
                                            title: 'ERROR!',
                                            content: 'Failed to delete file - '+msg.error,
                                            useBootstrap:false,
                                            boxWidth:'400px'
                                        });
                                    }else{
                                        $(v).closest('.media-file').remove();
                                    }
                                });
                            });
                            selectedMediaFiles = [];
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
                    content: 'No media file selected!',
                    useBootstrap:false,
                    boxWidth:'400px'
                });
            }         
            return false;
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
     
     $("ul.jFiler-items-list").sortable({
        item:'jFiler-item',
        cursor:'move',
        handle: 'i.dashicons-leftright',
        update:function(event,ui){
            var imgs = $(this).children();
            var obj = [];
            $.each(imgs,function(i,d){
                console.log(i);
		        var imgid = $(this).find("input[name=selectProfileMedia]").val();
                    obj.push(imgid);
		      });
              
              $.ajax({
              method: "POST",
              url: ajaxurl,
              data: { action: 'rb_agency_sort_image',profileMedium:obj }
              })
              .done(function( msg ) {
                console.log(msg);
            });
        }
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
        
        
function deleteAuditionDemo(auditiondemopath){
	var c = confirm('Are you sure that you want to delete this file?');
	if(c){
		jQuery.post(ajaxurl, {
			auditiondemo_path:auditiondemopath,
			action: 'deleteauditiondemo_func'
		}).done(function(data) {
			console.log(data);
			alert('File successfully deleted!');
			window.location.reload();
		});
	}
}

	jQuery('.audition-mp3').click(function(){
		var audition_demo_name_key = jQuery(this).attr('audition_demo_name_key');
		var audition_demo_name_val = jQuery(this).attr('audition_demo_name_val');
		jQuery('.auditiondemoname').val(audition_demo_name_val);
		jQuery('.old_auditiondemoname').val(audition_demo_name_val);
		jQuery('.auditiondemoname_key').val(audition_demo_name_key);
		jQuery('.auditiondemoname_val').val(audition_demo_name_val);
		tb_show('Edit Audition Demo','#TB_inline?width=500&height=100&inlineId=edit-audition-demo');
		return false;
	});
	jQuery('.auditiondemoname').keyup(function(){
		jQuery('.new_auditiondemoname').val(jQuery(this).val());
	});
	jQuery('.update_auditiondemoname').click(function(){
		var new_val = jQuery('.new_auditiondemoname').val();
		var old_val = jQuery('.old_auditiondemoname').val();
		var auditiondemoname_key = jQuery('.auditiondemoname_key').val();
		jQuery.post(ajaxurl, {
			demo_name_key:auditiondemoname_key,
			old_value: old_val,
			new_value:new_val,
			action: 'editauditiondemo'
		}).done(function(data) {
			jQuery(".auditiondemo-caption", '.media-file[audiodemo_place_id='+auditiondemoname_key+']').html('');
			jQuery(".auditiondemo-caption", '.media-file[audiodemo_place_id='+auditiondemoname_key+']').html(new_val);
			jQuery(".audvoicedemo-caption" , '.media-file[audaudiodemo_place_id='+auditiondemoname_key+']').html('');
			jQuery(".audvoicedemo-caption" , '.media-file[audaudiodemo_place_id='+auditiondemoname_key+']').html(new_val);
		});
		tb_remove();
		return false;
	});
	//voice demo
	jQuery('.voice-demo-mp3').click(function(){
		var voice_demo_name_key = jQuery(this).attr('voice_demo_name_key');
		var voice_demo_name_val = jQuery(this).attr('voice_demo_name_val');
		var voice_demo_caption_key = jQuery(this).attr('voice_demo_caption_key');
		var voice_demo_caption_val = jQuery(this).attr('voice_demo_caption_val');
		jQuery('.voicedemoname').val(voice_demo_name_val);
		jQuery('.old_voicedemoname').val(voice_demo_name_val);
		jQuery('.voicedemoname_key').val(voice_demo_name_key);
		jQuery('.voicedemoname_val').val(voice_demo_name_val);
		jQuery('.voicedemocaption').val(voice_demo_caption_val);
		jQuery('.old_voicedemocaption').val(voice_demo_caption_val)
		jQuery('.voicedemocaption_key').val(voice_demo_caption_key);
		jQuery('.voicedemocaption_val').val(voice_demo_caption_val);									
		tb_show('Edit Voice Demo','#TB_inline?width=500&height=110&inlineId=edit-voice-demo');
		return false;
	});
	jQuery('.voicedemoname').keyup(function(){
		jQuery('.new_voicedemoname').val(jQuery(this).val());
	});
	jQuery('.voicedemocaption').keyup(function(){
		jQuery('.new_voicedemocaption').val(jQuery(this).val());
	});
	jQuery('.update_voicedemoname').click(function(){
		var new_val = jQuery('.new_voicedemoname').val();
		var old_val = jQuery('.old_voicedemoname').val();
		var voicedemoname_key = jQuery('.voicedemoname_key').val();	
		var new_val_caption = jQuery('.new_voicedemocaption').val();
		var old_val_caption = jQuery('.old_voicedemocaption').val();
		var voicedemocaption_key = jQuery('.voicedemocaption_key').val();
		jQuery.post(ajaxurl, {
			demo_name_key:voicedemoname_key,
			old_value: old_val,
			new_value:new_val,
			action: 'editvoicedemo',
			new_value_caption : new_val_caption,
			old_value_caption : old_val_caption,
			demo_caption_key:voicedemocaption_key
		}).done(function(data) {	
		console.log(data);									
			jQuery(".voicedemo-caption", '.media-file[voicedemo_place_id='+voicedemoname_key+']').html('');
			if(new_val.length > 0){
				jQuery(".voicedemo-caption", '.media-file[voicedemo_place_id='+voicedemoname_key+']').html(new_val);
			}else{
				jQuery(".voicedemo-caption", '.media-file[voicedemo_place_id='+voicedemoname_key+']').html(old_val);
			}
			if(new_val_caption.length > 0){
				jQuery(".voicedemocaption_label",'.media-file[voicedemo_place_id='+voicedemoname_key+']').html(new_val_caption);
			}else{
				jQuery(".voicedemocaption_label",'.media-file[voicedemo_place_id='+voicedemoname_key+']').html(old_val_caption);
			}
		});
		tb_remove();
		return false;
	});
    
    $('.prepare-edit-video').on('click',function(){
	var vidID = $(this).attr('video_id');
	var vidType = $(this).attr('video_type');
	var vidtitle = $(this).attr('video_title');
	var vidURL = $(this).attr('video_url');
	var vidCap = $(this).attr('video_caption');
	$('.profileMediaVType option[value="'+vidType+'"]','.inline-edit-video').prop('selected', true);
	$('.profilemedia_title','.inline-edit-video').val(vidtitle);
	$('.profilemedia_url','.inline-edit-video').val(vidURL);
	$('.profilemedia_id','.inline-edit-video').val(vidID);
	$('.profilemedia_caption','.inline-edit-video').val(vidCap);
	console.log(vidType);
	//profileMediaV
	//profileMediaVType
	//alert('eoe');
	tb_show('Edit Video','#TB_inline?width=500&height=220&inlineId=inline-edit-video');
	return false;
});
$('.save_media_inline').on('click',function(){
	var v_id      = $( ".profilemedia_id" ).val();
	var v_url     = $( ".profilemedia_url" ).val();
	var v_title   = $( ".profilemedia_title" ).val();
	var v_caption = $( ".profilemedia_caption" ).val();
	var v_medtype = $( ".profileMediaVType" ).val();
	jQuery.post(ajaxurl, {
		id:      v_id,
		url:     v_url,
		title:   v_title,
		caption: v_caption,
		medtype: v_medtype,
		action: 'editvideo_inline_save'
	}).done(function(data) {
		/* if( data== 'error'){
			$('#photo-message-div').addClass('error');
			$('#photo-message-div').html('<p>Error uploading image</p>');
		}else{
			jQuery("#wrapper-sortable #gallery-sortable").append(data);
		} */
		$('span.video-title', '.media-file[video_place_id='+v_id+']').html(v_title);
		$('span.video-caption', '.media-file[video_place_id='+v_id+']').html(v_caption);
		$('span.video-type', '.media-file[video_place_id='+v_id+']').html(v_medtype);
		$('span.video-thumb', '.media-file[video_place_id='+v_id+']').html(data);
		console.log(data);
	});
	//process the ajax save and result
	tb_remove();
	console.log('saved');
	return false;
});
	
jQuery(".add-account-url-btn").click(function(event){
	event.preventDefault;
	jQuery("#other-account-url-wrapper").append("<input type='text' class='rb-url-input add-other-account-url-txt' name='otherAccountURLs[]' placeholder='Add URL Here' /><br/> ");
});		


jQuery('.imperial_metrics').keyup(function(){
		var vals = jQuery(this).val();
		var new_val = extractNumber(vals,2,false);
		if(new_val !== true){
				jQuery(this).nextAll('.error_msg').eq(0).html('*Non numeric value is not accepted');
				new_val.replace(/[^/\d*\.*]/g,'');
				jQuery(this).val(new_val);
		}
});
jQuery('.imperial_metrics').focusout(function(){
		var vals = jQuery(this).val();
		var new_val = extractNumber(vals,2,false);
		if(new_val !== true){
				jQuery(this).nextAll('.error_msg').eq(0).html('*Non numeric value is not accepted');
				new_val.replace(/[^/\d*\.*]/g,'');
				jQuery(this).val(new_val);
		} else {
				jQuery(this).nextAll('.error_msg').eq(0).html('');
		}
});

function extractNumber(obj, decimalPlaces, allowNegative)
{
		var temp = obj; var reg0Str = '[0-9]*';
		if (decimalPlaces > 0) {
				reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
		} else if (decimalPlaces < 0) {
				reg0Str += '\\.?[0-9]*';
		}
		reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
		reg0Str = reg0Str + '$';
		var reg0 = new RegExp(reg0Str);
		if (reg0.test(temp)) return true;
		var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
		var reg1 = new RegExp(reg1Str, 'g');
		temp = temp.replace(reg1, '');
		if (allowNegative) {
				var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
				var reg2 = /-/g;
				temp = temp.replace(reg2, '');
				if (hasNegative) temp = '-' + temp;
		}
		if (decimalPlaces != 0) {
				var reg3 = /\./g;
				var reg3Array = reg3.exec(temp);
				if (reg3Array != null) {
						var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
						reg3Right = reg3Right.replace(reg3, '');
						reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
						temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
				}
		}
		return temp;
}	
							
});//document ready end