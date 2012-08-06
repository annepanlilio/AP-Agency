$(document).ready(function(){
	
//Select object type
	$(".objtype").change(function(){
		
		if($("#obj_edit").attr("class") == $(this).val()){
		  $("#obj_edit").show();
		  $("#objtype_customize").empty();	
		}else if($("#obj_edit").attr("class") != $(this).val()){
				$("#objtype_customize").hide().html(getObj($(this).val())).fadeIn("fast");
				
				if($(this).val()!=3){
				  $(".add_more_object").hide();
							
				}else{
				 $(".add_more_object").fadeIn("fast");	
				}
				
				$("#obj_edit").hide();
		}
     
	});
	//Add  dropdown group option
	$(".add_more_object").click(function(){
	
		 if($("div[id=dropdown_custom]").size() <= 1){
			 var x = $("div[id=dropdown_custom]").size();
			 x++;
			dropdown_template(x);
			 $("#min_field").val("Min");
	        $(this).fadeOut();
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
			        $("#objtype_customize").empty().html('<tr><td><td align="right" style="width:50px;">Title:</td><td style="width:10px;"><input type="text" name="ProfileCustomTitle"/></td></td></tr>');
					$("#objtype_customize").append('<tr><td><td align="right" style="width:50px;">Min*:</td><td style="width:10px;"><input type="text" name="textmin"/></td></td></tr>');
					$("#objtype_customize").append('<tr><td><td align="right" style="width:10px;">Max*:</td><td style="width:10px;"><input type="text" name="textmax"/></td></td></tr></tr>');
					
			   
			break;  
			
			case "3":
			   
				 $("table tr[id=objtype_customize]").empty();
				 $("table tr[id=objtype_customize]").html("<strong>Title*:</strong><input type='text' name='ProfileCustomTitle' /><br/>");
				// $("table tr[id=objtype_customize]").append("Label*:<input type='text' name=\"option_label\" value=\"\" id=\"min_field\" />"); //<input type=\"checkbox\" class=\"set_as_default\" name=\"option_default_1\"\/><span style=\"font-size:11px;\">(set as selected)<\/span>
				 $("table tr[id=objtype_customize]").append("<div id=\'dropdown_custom\' class=\"dropdown_1\">Option*:<input type=\"text\" name=\"option[]\"\/>");
				 $("table tr[id=objtype_customize]").append("Option*:<input type=\"text\" name=\"option[]\"\/><a href=\"javascript:void(0);\" style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:150px;\" onclick=\"add_more_option_field(1);\" >add more option[+]<\/a> ");	
				 $("table tr[id=objtype_customize]").append("<div id=\"addoptions_field_1\"></div></div>");
				 
					
					
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
			       $("#objtype_customize").empty().html('Title*:<input type="value" name="ProfileCustomTitle"/><br/>');
				 $("#objtype_customize").append('Values:<input type="text" name="label[]"/><br/>');
				 $("#objtype_customize").append('<div id="addcheckbox_field_1"></div><a href="javascript:void(0);" style="float:right;font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:250px;text-align:right;" onclick="add_more_checkbox_field(1);" >add more[+]</a>');
			break;  
			
			case "6":
		   	       $("#objtype_customize").empty().html('Title*:<input type="value" name="ProfileCustomTitle"/><br/>');
				 $("#objtype_customize").append('Values:<input type="text" name="label[]"/><br/>');
				 $("#objtype_customize").append('<div id="addcheckbox_field_1"></div><a href="javascript:void(0);" style="float:right;font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:250px;text-align:right;" onclick="add_more_checkbox_field(1);" >add more[+]</a>');
			  break;  
			
			case "7":
			       $("#objtype_customize").empty().html("<tr><td>Title*:<input type='text' name='ProfileCustomTitle' /></td></tr>");
				 $("#objtype_customize").append("<tr><td>&nbsp;</td></tr>");
				 
				  if($(".objtype").attr("id")==1){
				 	$("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='1' />in</td></tr>");
					$("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='2' />lb</td></tr>");
				 }else if($(".objtype").attr("id")==0){
					$("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='1' />cm</td></tr>");
					$("#objtype_customize").append("<tr><td><input type='radio' name='ProfileUnitType' value='2' />kg</td></tr>");
				 }
			break;
			
			
			default:
			   return '';
			break;
			  
		  }
		
		
	}
	
	function dropdown_template(id){
		
		
	   if($("div[id=dropdown_custom]").size()<= 1){
		
		 
		 $("table tr[id=objtype_customize]").append("<div id=\"dropdown_remove_"+id+"\"><br/><br/><tr><td  valign=\"top\" class=\"dropdown_title\" ><tr><td>&nbsp;&nbsp;<strong>Dropdown#:"+id+"</strong> </td><td><a href='javascript:void(0);' style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:150px;\" onclick=\"remove_more_option_field("+id+");\">remove group[x]</a></td></tr><tr><td><tr><td align=\"right\" valign=\"top\" class=\"dropdown_title\" ><br/>&nbsp;&nbsp;Label*:<input type='text' name='option_label2' value='Max' /></td></td></tr></td><div id=\'dropdown_custom\' class=\"dropdown_"+id+"\"></td></tr><td><tr><td><tr><td align=\"right\">&nbsp;Option*:<\/td><td><input type=\"text\" name=\"option2[]\"\/><input type=\"checkbox\" name=\"option_default_2\" class=\"set_as_default\" \/><span style=\"font-size:11px;\">(set as selected)<\/span><\/td><\/tr><tr><td align=\"right\"><br/>Option*:<input type=\"text\" name=\"option2[]\"\/><\/td><td><a href=\"javascript:void(0);\" style=\"font-size:12px;color:#069;text-decoration:underline;cursor:pointer;width:150px;\" onclick=\"add_more_option_field2("+id+");\" >add more option[+]<\/a> <\/td><\/tr> <div id=\"addoptions_field2_"+id+"\"> <\/div><\/td><\/div><\/div>");
		
	   }
	
		 
     }
	
		$("#addmoreoption_1").click(function(){
			$("#editfield_add_more_options_1").append("Option:<input type=\"text\" name=\"option[]\"></br>");
		});
		$("#addmoreoption_2").click(function(){
			$("#editfield_add_more_options_2").append("Option:<input type=\"text\" name=\"option2[]\"></br>");
		}); 
	
	 
});


function add_more_option_field(objNum){
	
     var a = document.getElementById("addoptions_field_"+objNum);
	 var b = a.innerHTML;
	 a.innerHTML = b + ' <tr> '  
          +'<td align="right" >&nbsp;&nbsp;&nbsp;Option:<input type="text" class="'+objNum+'" name="option[]"/></td><br/>'
          +'<td>'
          +'</td>   '
          +'</tr> ';
		 
   
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
