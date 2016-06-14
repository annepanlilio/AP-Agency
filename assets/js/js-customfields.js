jQuery(document).ready(function(){
	
//Select object type
	jQuery(".objtype").change(function(){
		
		if(jQuery("#obj_edit").attr("class") == jQuery(this).val()){
		  jQuery("#obj_edit").show();
		  jQuery("#objtype_customize").empty();	
		}else if(jQuery("#obj_edit").attr("class") != jQuery(this).val()){
				jQuery("#objtype_customize").hide().html(getObj(jQuery(this).val())).fadeIn("fast");
				
				if(jQuery(this).val()!=3){
				  jQuery(".add_more_object").hide();
							
				}else{
				 jQuery(".add_more_object").fadeIn("fast");	
				}
				if(jQuery("#obj_edit").attr("class") != jQuery(this).val()){
					jQuery("#obj_edit").hide();
				}
		}
     
	});
	//Add  dropdown group option
	jQuery(".add_more_object").click(function(){
	
		 if(jQuery("div[id=dropdown_custom]").size() <= 1){
			 var x = jQuery("div[id=dropdown_custom]").size();
			 x++;
			dropdown_template(x);
			 jQuery("#min_field").val("Min");
	        jQuery(this).fadeOut();
		}
	});
    var dropdown_values = [];
	jQuery("#obj_edit input[type=text]").each(function(i,d){
		if(jQuery(this).attr("name") != "ProfileCustomTitle" ){
			dropdown_values.push(jQuery(this).val());
		}
	});
	var OriginalProfileCustomTitle = (jQuery("input[name=ProfileCustomTitle]").val()?jQuery("input[name=ProfileCustomTitle]").val():"");
	
	//Get objects by selected type
	function getObj(type){
	        
	      switch(type){
			case "1": // Text
			     return '<div class="rbfield rbtext rbsingle">'
						    +'<label>Title*:</label> <div><input type="text" name="ProfileCustomTitle"/></div>'
						+'</div>'
						+ '<div class="rbfield rbtext rbsingle">'
						    +'<label>Value:</label> <div><input type="text" name="ProfileCustomOptions"/></div>'
						+'</div>';
			break;  
			case "2": 
			        jQuery("#objtype_customize").empty().html('<div class="rbfield rbtext rbsingle"><label>Title:</label><div><input type="text" name="ProfileCustomTitle"/></div></div>');
					jQuery("#objtype_customize").append('<div class="rbfield rbtext rbsingle"><label>Min*:</label><div><input type="text" name="textmin"/></div></div>');
					jQuery("#objtype_customize").append('<div class="rbfield rbtext rbsingle"><label>Max*:</label><div><input type="text" name="textmax"/></div></div>');
			break;  
			case "3":  // Dropdown
			    jQuery("#objtype_customize").empty();
			    jQuery("#obj_edit").remove();

				var appnd = ['<div id="obj_edit" class="3">',
							 '	<div class="rbfield rbtext rbsingle">',
							 '		<label>Title:</label><div><input name="ProfileCustomTitle" value="'+OriginalProfileCustomTitle+'" type="text"></div>',
							 '	</div>',
							 '	<ul id="editfield_add_more_options_12">',
							 '		<li class="rbfield rbtext rbsingle">',
							 '			<label>Option:</label><div class="option"><input value="" name="option[]" type="text">',
							 '			<a href="javascript:;" onclick="del_opt(jQuery(this));" class="del_opt" title="Delete Option" style="color:red; text-decoration:none">&nbsp;[ - ]</a></div>',
							 '		</li>',
							 '	</ul><br>',
							 '	<a href="javascript:;" onclick="addmoreoption(12);" id="addmoreoption_2" class="add-more-option">add more option[+]</a>',
							 '	<div class="ui-sortable" id="editfield_add_more_options_2"></div>',							 
							 '</div>'];
				jQuery(".inside form").find('.submit').before(appnd.join(''));
				jQuery("#obj_edit").css({display:'block'});
				jQuery.each(dropdown_values,function(i,v){
					jQuery( "#editfield_add_more_options_12" ).append('<li class="rbfield rbtext rbsingle"><label>Option:</label><div class="option"><input type="text" value="'+v+'" name="option[]"><a href="javascript:;" class="del_opt" onclick="del_opt(jQuery(this));" title="Delete Option" style="color:red; text-decoration:none">&nbsp;[ - ]</a></div></li>');
				});
				jQuery( "#editfield_add_more_options_12" ).sortable();
			break;  
			case "4": // Textbox
			     return '<div class="rbfield rbtext rbsingle">'
						    	+'<label>Title*:</label><div><input type="text" name="ProfileCustomTitle"/></div>'
							+'</div>'
						+'<div class="rbfield rbtextarea rbsingle">'
						    +'<label>TextArea:</label><div><textarea cols="60" rows="30" name="ProfileCustomOptions"></textarea></div>'
						+'</div>';
			break;  
			case "5": // Checkbox
			   
			/*    if(dropdown_values.length < 0){
				  	jQuery("#objtype_customize").append('Values:<input type="text" name="label[]"/><br/>');
				  	jQuery("#objtype_customize").empty().html('Title*:<input type="text" name="ProfileCustomTitle"/><br/>');
				 }else{
				 	jQuery("#objtype_customize").empty().html('Title*:<input type="text" value="'+OriginalProfileCustomTitle+'" name="ProfileCustomTitle"/><br/>');
				 }

				var append_vals = "";
				
				jQuery.each(dropdown_values,function(i,v){

					append_vals += "Values: <input type=\"text\" value=\""+v+"\" name=\"label[]\"/><br/>";
				});

				jQuery("#objtype_customize").append('<div id="addcheckbox_field_1">'+append_vals+'</div><a href=\"javascript:void(0);\" style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;text-align:right;\" onclick=\"add_more_checkbox_field(1);\" >add more[+]</a>');
			*/
				jQuery("#objtype_customize").empty();
			    jQuery("#obj_edit").remove();
			    jQuery("#obj_edit").css({display:'block'});
				
				var appnd = ['<div id="obj_edit" class="5">',
							 '<div class="rbfield rbcheckbox rbsingle">',
							 '<label>Title:</label><div><input name="ProfileCustomTitle" value="'+OriginalProfileCustomTitle+'" type="text"></div></div>',
							 '<ul id="editfield_add_more_options_12">',
							 '<li class="rbfield rbcheckbox rbsingle">',
							 '<label>Option:</label><div class="option"><input value="" name="option[]" type="text">',
							 '<a href="javascript:;" onclick="del_opt(jQuery(this));" class="del_opt" title="Delete Option" style="color:red; text-decoration:none">&nbsp;[ - ]</a></div>',
							 '</li>',
							 '</ul><br>',
							 '<a href="javascript:;" onclick="addmoreoption(12);" id="addmoreoption_2" class="add-more-option">add more option[+]</a>',
							 '<div class="ui-sortable" id="editfield_add_more_options_2"></div>',
							 '</div>'];
				jQuery(".inside form").find('.submit').before(appnd.join(''));
				jQuery.each(dropdown_values,function(i,v){
					jQuery( "#editfield_add_more_options_12" ).append('<li class="rbfield rbcheckbox rbsingle"><label>Option:</label><div class="option"><input type="text" value="'+v+'" name="option[]"><a href="javascript:;" class="del_opt" onclick="del_opt(jQuery(this));" title="Delete Option" style="color:red; text-decoration:none">&nbsp;[ - ]</a></div></li>');
				});
				jQuery( "#editfield_add_more_options_12" ).sortable();

			break;  
			case "6": // Radio button
			  /* 	    
			     if(dropdown_values.length < 0){
			     	jQuery("#objtype_customize").append('Values:<input type="text" name="label[]"/><br/>');
			     	jQuery("#objtype_customize").empty().html('Title*:<input type="text" name="ProfileCustomTitle"/><br/>');
				 }else{
				 	jQuery("#objtype_customize").empty().html('Title*:<input type="text" value="'+OriginalProfileCustomTitle+'" name="ProfileCustomTitle"/><br/>');
				 }
				var append_vals = "";
				
				jQuery.each(dropdown_values,function(i,v){

					append_vals += "Values: <input type=\"text\" value=\""+v+"\" name=\"label[]\"/><br/>";
				});
				jQuery("#objtype_customize").append('<div id="addcheckbox_field_1">'+append_vals+'</div><a href="javascript:void(0);" style="float:right;font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:250px;text-align:right;" onclick="add_more_checkbox_field(1);" >add more[+]</a>');
			  */
			  jQuery("#objtype_customize").empty();
			    jQuery("#obj_edit").remove();
			    jQuery("#obj_edit").css({display:'block'});
				
				var appnd = ['<div id="obj_edit" class="6">',
							 '	<div class=\"rbfield rbtext rbsingle\">',
							 '		<label>Title:</label><div><input name="ProfileCustomTitle" value="'+OriginalProfileCustomTitle+'" type="text"></div>',
							 '	</div>',
							 '	<ul id="editfield_add_more_options_12">',
							 '		<li class="rbfield rbtext rbsingle">',
							 '			<label>Option:</label><div class="option"><input value="" name="option[]" type="text">',
							 '			<a href="javascript:;" onclick="del_opt(jQuery(this));" class="del_opt" title="Delete Option" style="color:red; text-decoration:none">&nbsp;[ - ]</a></div>',
							 '		</li>',
							 '	</ul><br>',
							 '	<a href="javascript:;" onclick="addmoreoption(12);" id="addmoreoption_2" class="add-more-option">add more option[+]</a>',
							 '	<div class="ui-sortable" id="editfield_add_more_options_2"></div>',
							 '</div>'];
				jQuery(".inside form").find('.submit').before(appnd.join(''));
				jQuery.each(dropdown_values,function(i,v){
					jQuery( "#editfield_add_more_options_12" ).append('<li class="rbfield rbtext rbsingle"><label>Option:</label><div class="option"><input type="text" value="'+v+'" name="option[]"><a href="javascript:;" class="del_opt" onclick="del_opt(jQuery(this));" title="Delete Option" style="color:red; text-decoration:none">&nbsp;[ - ]</a></div></li>');
				});
				jQuery( "#editfield_add_more_options_12" ).sortable();
			  break;  
			case "7": // Imperials
			jQuery("#objtype_customize").empty();
			    jQuery("#obj_edit").remove();
			    jQuery("#obj_edit").css({display:'block'});
   		        jQuery("#objtype_customize").empty().html("<div class=\"rbfield rbtext rbsingle\"><label>Title*:</label><div><input type='text' name='ProfileCustomTitle' /></div></div>");
				// jQuery("#objtype_customize").append("<tr><td>&nbsp;</td></tr>");
				if(jQuery(".objtype").attr("id")==1){
				 	var appnd = ["<div id=\"obj_edit\" class=\"7\">",
				 					"<div class=\"rbfield rbradio rbmulti\"><label>&nbsp;</label>",
				 										"<div><div><div><label><input type='radio' name='ProfileUnitType' value='1' />Inches</label></div></div>",
														"<div><div><label><input type='radio' name='ProfileUnitType' value='2' />Pounds</label></div></div>",
														"<div><div><label><input type='radio' name='ProfileUnitType' value='3' />Feet/Inches</label></div></div></div></div></div>"];
														jQuery(".inside form").find('.submit').before(appnd.join(''));
				 } else if(jQuery(".objtype").attr("id")==0) {
					jQuery("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='1' />cm</td></tr>");
					jQuery("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='2' />kg</td></tr>");
					jQuery("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='3' />Feet/Inches</td></tr>");
				 }
			break;
			case "8":
				jQuery ("table tr[id=objtype_customize]").empty();
				jQuery ("table tr[id=objtype_customize]").html("<strong>Title*:</strong><input type='text' name='ProfileCustomTitle' /><br/>");
				jQuery ("table tr[id=objtype_customize]").append("<div id=\'dropdown_custom\' class=\"dropdown_1\">Option*:<input type=\"text\" name=\"multiple[]\"\/>");
				jQuery ("table tr[id=objtype_customize]").append("Option*:<input type=\"text\" name=\"multiple[]\"\/><a href=\"javascript:void(0);\" style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:150px;\" onclick=\"add_more_option_field(1);\" >add more option[+]<\/a> ");	
				jQuery ("table tr[id=objtype_customize]").append("<div id=\"addoptions_field_1\"></div></div>");
				break;
			case "9":  // Dropdown Multi-Select
			     jQuery("#objtype_customize").empty();
			    jQuery("#obj_edit").remove();
			    jQuery("#obj_edit").css({display:'block'});
				
				
				var appnd = ['<div id="obj_edit" class="9">',
							 '	<div class="rbfield rbtext rbsingle">',
							 '		<label>Title:</label><div><input name="ProfileCustomTitle" value="'+OriginalProfileCustomTitle+'" type="text"></div>',
							 '	</div>',
							 '	<ul id="editfield_add_more_options_12">',
							 '		<li class="rbfield rbtext rbsingle">',
							 '			<label>Option:</label><div class="option"><input value="" name="option[]" type="text">',
							 '			<a href="javascript:;" onclick="del_opt(jQuery(this));" class="del_opt" title="Delete Option" style="color:red; text-decoration:none">&nbsp;[ - ]</a></div>',
							 '		</li>',
							 '	</ul><br>',
							 '	<a href="javascript:;" onclick="addmoreoption(12);" id="addmoreoption_2" class="add-more-option">add more option[+]</a>',
							 '	<div class="ui-sortable" id="editfield_add_more_options_2"></div>',
							 '</div>'];
				jQuery(".inside form").find('.submit').before(appnd.join(''));
				jQuery.each(dropdown_values,function(i,v){
					jQuery( "#editfield_add_more_options_12" ).append('<li class="rbfield rbselect rbsingle"><label>Option:</label><div class="option"><input type="text" value="'+v+'" name="option[]"><a href="javascript:;" class="del_opt" onclick="del_opt(jQuery(this));" title="Delete Option" style="color:red; text-decoration:none">&nbsp;[ - ]</a></div></li>');
				});
				jQuery( "#editfield_add_more_options_12 " ).sortable();
				
			break;  	

			case "10": // Text
			     return '<div class="rbfield rbtext rbsingle">'
						    +'<label>Title*:</label><div><input type="text" name="ProfileCustomTitle"/></div>'
				 		+'</div>'
				 		+'<div class="rbfield rbtext rbsingle">'
						    +'<label>&nbsp;</label><div><input type="checkbox" name="ProfileCustomNotifyAdmin" value="1"/><span style="font-size:11px;">Notify the admin when a user reached expiry date.</span></div>'
				 		+'</div>';
			break; 
			case "11": // Text
			     return '<div class="rbfield rbtext rbsingle">'
						    +'<label>Title*:</label> <div><input type="text" name="ProfileCustomTitle"/></div>'
						+'</div>'
						+ '<div class="rbfield rbtext rbsingle">'
						    +'<label>Value:</label> <div><input type="text" name="ProfileCustomOptions"/></div>'
						+'</div>';
			break;  
			
			default:
			   return '';
			break;
			  
		  }
		
		
	}
	
	function dropdown_template(id){
		
		
	   if(jQuery("div[id=dropdown_custom]").size()<= 1){
		
		 
		 jQuery("table tr[id=objtype_customize]").append("<div id=\"dropdown_remove_"+id+"\"><br/><br/><tr><td  valign=\"top\" class=\"dropdown_title\" ><tr><td>&nbsp;&nbsp;<strong>Dropdown#:"+id+"</strong> </td><td><a href='javascript:void(0);' style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:150px;\" onclick=\"remove_more_option_field("+id+");\">remove group[x]</a></td></tr><tr><td><tr><td align=\"right\" valign=\"top\" class=\"dropdown_title\" ><br/>&nbsp;&nbsp;Label*:<input type='text' name='option_label2' value='Max' /></td></td></tr></td><div id=\'dropdown_custom\' class=\"dropdown_"+id+"\"></td></tr><td><tr><td><tr><td align=\"right\">&nbsp;Option*:<\/td><td><input type=\"text\" name=\"option2[]\"\/><input type=\"checkbox\" name=\"option_default_2\" class=\"set_as_default\" \/><span style=\"font-size:11px;\">(set as selected)<\/span><\/td><\/tr><tr><td align=\"right\"><br/>Option*:<input type=\"text\" name=\"option2[]\"\/><\/td><td><a href=\"javascript:void(0);\" style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:150px;\" onclick=\"add_more_option_field2("+id+");\" >add more option[+]<\/a> <\/td><\/tr> <div id=\"addoptions_field2_"+id+"\"> <\/div><\/td><\/div><\/div>");
		
	   }
	
		 
     }
	
		jQuery("#addmoreoption_1").on('click',function(){
			jQuery("#editfield_add_more_options_12").append("<li class=\"rbfield rbtext rbsingle\"><label>Option:</label><div class=\"option\"><input type=\"text\" name=\"option[]\"><a href='javascript:;' class='del_opt' onclick='del_opt(jQuery(this));' title='Delete Option' style='color:red; text-decoration:none'>&nbsp;[ - ]</a></div></li>");
		});
		jQuery("#addmoreoption_2").on('click',function(){
			jQuery("#editfield_add_more_options_2").append("<li class=\"rbfield rbtext rbsingle\"><label>Option:</label><div class=\"option\"><input type=\"text\" name=\"option2[]\"><a href='javascript:;' class='del_opt' onclick='del_opt(jQuery(this));' title='Delete Option' style='color:red; text-decoration:none'>&nbsp;[ - ]</a></div></li>");
		}); 
		jQuery("#addmoreoption_12").on('click',function(){
			jQuery("#editfield_add_more_options_12").append("<li class=\"rbfield rbtext rbsingle\"><label>Option:</label><div class=\"option\"><input type=\"text\" name=\"option[]\"><a href='javascript:;' class='del_opt' onclick='del_opt(jQuery(this));' title='Delete Option' style='color:red; text-decoration:none'>&nbsp;[ - ]</a></div></li>");
		});

		jQuery("a.del_opt").on('click',function(){
			jQuery(this).parents("li").remove();
		});	
		jQuery("a.del_cboxopt").on('click',function(){
			jQuery(this).parents("li").remove();
		});	
	 
	 	
});


function addmoreoption(objNum){
		jQuery("#editfield_add_more_options_"+objNum).append("<li class=\"rbfield rbtext rbsingle\"><label>Option:</label><div class=\"option\"><input type=\"text\" name=\"option[]\"><a href='javascript:;' onclick='del_opt(jQuery(this));' class='del_opt' title='Delete Option' style='color:red; text-decoration:none'>&nbsp;[ - ]</a></div></li>");
		
}

function del_opt(d){
	console.log(d);
	d.parents("li").remove();
}

function add_more_option_field(objNum){
	 
	 //get all values from current class 1 input
	 var arr = [];
	 var x = 0;
	 jQuery(".1").each(function(){
	 	arr.push(jQuery(this).val());
	 });
	 
     var a = document.getElementById("addoptions_field_"+objNum);
	 var b = a.innerHTML;
	 a.innerHTML = b + ' <tr> '  
          +'<td align="right" >&nbsp;&nbsp;&nbsp;Option:<input type="text" class="'+objNum+'" name="option[]"/></td><br/>'
          +'<td>'
          +'</td>   '
          +'</tr> ';

	 //put all values to class 1	
	 jQuery(".1").each(function(){
	 	jQuery(this).val(arr[x]);
		x++;
	 });	 
   
}

function add_more_option_field2(objNum){
	
     var a = document.getElementById("addoptions_field2_"+objNum);
	 var b = a.innerHTML;
	 a.innerHTML = b + ' <tr> '  
          +'<td align="right" >&nbsp;&nbsp;&nbsp;Option:<input type="text" class="'+objNum+'" name="option2[]"/></td><br/>'
          +'<td>'
          +'</td>   '
          +'</tr> ';
		 
   
}
function add_more_checkbox_field(objNum){
		
	 var d = "";
	 jQuery("#addcheckbox_field_"+objNum+" input").each(function(i,d){
	 	if(i==0){
	 		 jQuery("#addcheckbox_field_"+objNum+"").empty();
	 	}
		d =  '<tr><td>'
				 + '<tr>'
				 + '<td align="right">Value:</td><td><input type="text" value="'+jQuery(this).val()+'" name="label[]"/></td>'
				+ '</tr>'
				+ '</td></tr><br/>';
            jQuery("#addcheckbox_field_"+objNum+"").append(d);
	  });
	   d =   '<tr><td>'
							 + '<tr>'
							 + '<td align="right">Value:</td><td><input type="text" name="label[]"/></td>'
							 + '</tr>'
						 + '</td></tr><br/>';
     jQuery("#addcheckbox_field_"+objNum+"").append(d);

}
function remove_more_option_field(objNum){
	var parent = document.getElementById("objtype_customize");
   a = document.getElementById("dropdown_remove_"+objNum);
    
    a.innerHTML="";
    parent.removeChild(a); 
   document.getElementById("min_field").value="";
   document.getElementById("add_more_object_show").style.display="inline";
}

//Populate state options for selected  country
function populateStates(countryId,stateId){
	var url=jQuery("#url").val();
	var ajax_url = (typeof(rb_ajaxurl)!=="undefined")? rb_ajaxurl: ajaxurl;
	
	var selCountry = jQuery("#"+countryId).val();
	
	if( selCountry !=""){
			jQuery("#"+stateId).show();
			jQuery("#"+stateId).find("option:gt(0)").remove();
			jQuery("#"+stateId).find("option:first").text(objectL10n.loading);
		jQuery.ajax({
			type:'POST',
			dataType : "json",
	        data:{
				action:"get_state_ajax",
				'country': selCountry
			},
			url: ajax_url,
			success:function(data) {
				jQuery("<option/>").attr("value", "").text(objectL10n.select_state).appendTo(jQuery("#"+stateId));	
	                        for (var i = 0; i < data.length; i++) {
								jQuery("<option/>").attr("value", data[i].StateID).text(data[i].StateTitle).appendTo(jQuery("#"+stateId));
							}
				jQuery("#"+stateId).find("option:eq(0)").remove();
				console.log(data);
			},
			error: function(e){
				console.log(e);
			}
		});
		
	}else{
		jQuery("#"+stateId).find("option:gt(0)").remove();
			
	}
 }

 function populateStatesPublic(countryId,stateId){
	var url=jQuery("#url").val();
	var ajax_url = (typeof(rb_ajaxurl)!=="undefined")? rb_ajaxurl: ajaxurl;
	console.log(ajax_url);
	console.log(countryId);
	console.log(stateId);
	console.log(jQuery("#CastingCountry").val());
	if(jQuery("#country").val()!=""){
			jQuery("#state").show();
			jQuery("#state").find("option:gt(0)").remove();
			jQuery("#state").find("option:first").text(objectL10n.loading);
		jQuery.ajax({
			type:'POST',
			dataType : "json",
	        data:{action:"get_state_ajax",country:jQuery("#country").val()},
			url: ajax_url,
			success:function(data) {		
				jQuery("<option/>").attr("value", "").text(objectL10n.select_state).appendTo(jQuery("#state"));	
	                        for (var i = 0; i < data.length; i++) {
								jQuery("<option/>").attr("value", data[i].StateID).text(data[i].StateTitle).appendTo(jQuery("#state"));
							}
				jQuery("#state").find("option:eq(0)").remove();
				console.log(data);
			},
			error: function(e){
				console.log(e);
			}
		});
		
	}else{
		jQuery("#"+stateId).find("option:gt(0)").remove();
			
	}
 }

function saveCountry(){
	var countryTitle=jQuery("#countryTitle").val();
	var countryCode=jQuery("#countryCode").val();
	var countryId=jQuery("#countryId").val();
	var url=jQuery("#url").val();
	if(countryTitle==""){
		alert("Please enter country title");
		jQuery("#countryTitle").focus();
		return false;
		}
	if(countryCode==""){
		alert("Please enter country code");
		jQuery("#countryCode").focus();
		return false;
	}
		var dataString = 'id='+countryId+'&code='+countryCode+'&title='+countryTitle+'&editaction=1';
		jQuery.ajax({
		type:'POST',
		data:dataString,
		url:url,
		success:function(data) {
			window.location=url;
	}
  });
	
	

}
function saveState(){
	
	var stateTitle=jQuery("#StateTitle").val();
	var stateCode=jQuery("#StateCode").val();
	var stateId=jQuery("#stateId").val();
	var url=jQuery("#url").val();
	if(stateTitle==""){
		alert("Please enter state title");
		jQuery("#stateTitle").focus();
		return false;
		}
	if(stateCode==""){
		alert("Please enter state code");
		jQuery("#stateCode").focus();
		return false;
	}
		var dataString = 'id='+stateId+'&code='+stateCode+'&title='+stateTitle+'&editaction=2';
		jQuery.ajax({
		type:'POST',
		data:dataString,
		url:url,
		success:function(data) {
			window.location=url;
	}
  });
	
	

}

function redirectToSetting(){
	var url=jQuery("#url").val();
	window.location=url
	}
//Edit country
function editCountry(aId){
jQuery("#"+aId).parent('td').siblings().each (function() {
	var text =jQuery(this).text();
	var className = jQuery(this).attr('class');
	jQuery(this).html('<input type="text" id='+className+' value='+text+'>');

});    
jQuery("#"+aId).parent('td').prepend('<input type="hidden" id="countryId" value='+jQuery("#"+aId).attr("id")+'><input type="button" name="submit" class="button-primary"  onclick="javascript:saveCountry();" value="Save"><input type="button" name="reset" value="Cancel" class="button-primary" onclick="javascript:redirectToSetting();">');
	jQuery("#"+aId).parent('td').find('a').hide();

}




//Edit state
function editState(aId){
jQuery("#"+aId).parent('td').siblings().each (function() {
	var text =jQuery(this).text();
	var className = jQuery(this).attr('class');
	jQuery(this).html('<input type="text" id='+className+' value='+text+'>');

});    
jQuery("#"+aId).parent('td').prepend('<input type="hidden" id="stateId" value='+jQuery("#"+aId).attr("id")+'><input type="button" class="button-primary" name="submit"  onclick="javascript:saveState();" value="Save"><input type="button" name="reset" onclick="javascript:redirectToSetting();" class="button-primary" value="Cancel">');
jQuery("#"+aId).parent('td').find('a').hide();
}

jQuery(function(){
					jQuery('.rbdate').each(function(){
						var id = jQuery(this).data("id");
						
						var from = jQuery(".profilecustomid_"+id+"_from");
						var to = jQuery(".profilecustomid_"+id+"_to");
						
						from.datepicker(
							{
								numberOfMonths: 1, 
								dateFormat: "yy-mm-dd",
								onSelect: function(selected){
									 to.datepicker("option","minDate", selected);
								} 
							}).val(from.val());
						to.datepicker(
							{
								numberOfMonths: 1, 
								dateFormat: "yy-mm-dd",
								onSelect: function(selected){
									 from.datepicker("option","maxDate", selected);
								}  
							}).val(to.val());
						
					});
						/*jQuery(".rb-datepicker").each(function(){
							jQuery(this).datepicker({numberOfMonths: 2, dateFormat: "yy-mm-dd" }).val(jQuery(this).val());
						})*/
						/*if(jQuery( "input[id=rb_datepicker_from],input[id=rb_datepicker_to]").length){
							jQuery( "input[id=rb_datepicker_from],input[id=rb_datepicker_to]").datepicker({
								dateFormat: "yy-mm-dd",
								defaultDate: "+1w",
								changeMonth: true,
								numberOfMonths: 3,
								onSelect: function( selectedDate ) {
									if(this.id == 'input[id=rb_datepicker_from]'){
									  var dateMin = jQuery('input[id=rb_datepicker_from]').datepicker("getDate");
									  var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1); // Min Date = Selected + 1d
									  var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
									  jQuery('input[id=rb_datepicker_from]').datepicker("option","minDate",rMin);
									  jQuery('input[id=rb_datepicker_to]').datepicker("option","maxDate",rMax);                    
									}

								}
							});
						}*/

});


jQuery(document).ready(function(){
	jQuery(".DataTypeIDClassCheckbox").click(function(){
		var mychild = jQuery(this).attr('id');
		if (jQuery(this).is(':checked')) {
			jQuery(".CDataTypeID"+mychild).show(500);
			jQuery(".CDataTypeID"+mychild).removeAttr('style');
		}else{
			jQuery(".CDataTypeID"+mychild).hide(500);
		}						
	});
});