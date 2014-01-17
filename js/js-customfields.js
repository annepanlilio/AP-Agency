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
				
				jQuery("#obj_edit").hide();
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
	
	//Get objects by selected type
	function getObj(type){
	
	      switch(type){
			case "1":
			     return '<tr>'
				          +'<td>'
						     +'<tr>'
						    	 +'<td align="right">Title*:</td> <td><input type="text" name="ProfileCustomTitle"/></td>'
							 +'</tr>'
						  +'</td>'
						   +'<td>'
						    + '<tr>'
						     +'<td align="right">Value:</td> <td><input type="text" name="ProfileCustomOptions"/></td>'
							+'</tr>' 
						  +'</td>'
						+'</tr>';
			break;  
			case "2":
			        jQuery("#objtype_customize").empty().html('<tr><td><td align="right" style="width:50px;">Title:</td><td style="width:10px;"><input type="text" name="ProfileCustomTitle"/></td></td></tr>');
					jQuery("#objtype_customize").append('<tr><td><td align="right" style="width:50px;">Min*:</td><td style="width:10px;"><input type="text" name="textmin"/></td></td></tr>');
					jQuery("#objtype_customize").append('<tr><td><td align="right" style="width:10px;">Max*:</td><td style="width:10px;"><input type="text" name="textmax"/></td></td></tr></tr>');
			break;  
			case "3":
				jQuery("table tr[id=objtype_customize]").empty();
				jQuery("table tr[id=objtype_customize]").html("<strong>Title*:</strong><input type='text' name='ProfileCustomTitle' /><br/>");
				// jQuery("table tr[id=objtype_customize]").append("Label*:<input type='text' name=\"option_label\" value=\"\" id=\"min_field\" />"); //<input type=\"checkbox\" class=\"set_as_default\" name=\"option_default_1\"\/><span style=\"font-size:11px;\">(set as selected)<\/span>
				 jQuery("table tr[id=objtype_customize]").append("<div id=\'dropdown_custom\' class=\"dropdown_1\">Option*:<input type=\"text\" name=\"option[]\"\/>");
				 jQuery("table tr[id=objtype_customize]").append("Option*:<input type=\"text\" name=\"option[]\"\/><a href=\"javascript:void(0);\" style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:150px;\" onclick=\"add_more_option_field(1);\" >add more option[+]<\/a> ");	
				 jQuery("table tr[id=objtype_customize]").append("<div id=\"addoptions_field_1\"></div></div>");
			break;  
			case "4":
			     return '<tr>'
				          +'<td>'
						     +'<tr>'
						    	 +'<td align="right">Title*:</td> <td><input type="text" name="ProfileCustomTitle"/></td>'
							 +'</tr>'
						  +'</td>'
						   +'<td>'
						    + '<tr>'
						     +'<td align="right" valign="top">TextArea:</td> <td><textarea cols="60" rows="30" name="ProfileCustomOptions"></textarea></td>'
							+'</tr>' 
						  +'</td>'
					+'</tr>';
			break;  
			case "5":
			    jQuery("#objtype_customize").empty().html('Title*:<input type="value" name="ProfileCustomTitle"/><br/>');
				jQuery("#objtype_customize").append('Values:<input type="text" name="label[]"/><br/>');
				jQuery("#objtype_customize").append('<div id="addcheckbox_field_1"></div><a href="javascript:void(0);" style="float:right;font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:250px;text-align:right;" onclick="add_more_checkbox_field(1);" >add more[+]</a>');
			break;  
			case "6":
		   	    jQuery("#objtype_customize").empty().html('Title*:<input type="value" name="ProfileCustomTitle"/><br/>');
				jQuery("#objtype_customize").append('Values:<input type="text" name="label[]"/><br/>');
				jQuery("#objtype_customize").append('<div id="addcheckbox_field_1"></div><a href="javascript:void(0);" style="float:right;font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:250px;text-align:right;" onclick="add_more_checkbox_field(1);" >add more[+]</a>');
			  break;  
			case "7":
   		        jQuery("#objtype_customize").empty().html("<tr><td>Title*:<input type='text' name='ProfileCustomTitle' /></td></tr>");
				jQuery("#objtype_customize").append("<tr><td>&nbsp;</td></tr>");
				if(jQuery(".objtype").attr("id")==1){
				 	jQuery("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='1' />Inches</td></tr>");
					jQuery("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='2' />Pounds</td></tr>");
					jQuery("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='3' />Feet/Inches</td></tr>");
				 }else if(jQuery(".objtype").attr("id")==0){
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
	
		jQuery("#addmoreoption_1").click(function(){
			jQuery("#editfield_add_more_options_1").append("Option:<input type=\"text\" name=\"option[]\"></br>");
		});
		jQuery("#addmoreoption_2").click(function(){
			jQuery("#editfield_add_more_options_2").append("Option:<input type=\"text\" name=\"option2[]\"></br>");
		}); 
	
	 
});


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
		
	 var a = document.getElementById("addcheckbox_field_"+objNum);
	 var b = a.innerHTML;
	 a.innerHTML = b +  '<tr><td>'
							 + '<tr>'
							 + '<td align="right">Value:</td><td><input type="text" name="label[]"/></td>'
							 + '</tr>'
						 + '</td></tr><br/>';
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
	if(jQuery("#"+countryId).val()!=""){
			jQuery("#"+stateId).show();
			jQuery("#"+stateId).find("option:gt(0)").remove();
			jQuery("#"+stateId).find("option:first").text("Loading...");
			jQuery.getJSON(url+"/get-state/"+ jQuery("#"+countryId).val(), function (data) {
			jQuery("<option/>").attr("value", "").text("Select State").appendTo(jQuery("#"+stateId));	
                        for (var i = 0; i < data.length; i++) {
				jQuery("<option/>").attr("value", data[i].StateID).text(data[i].StateTitle).appendTo(jQuery("#"+stateId));
			}
			jQuery("#"+stateId).find("option:eq(0)").remove();
		
		});
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

