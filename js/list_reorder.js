
/* 
 * jquery attach to sorting dropdown 
 */
jQuery(document).ready(function(){
	
        /*
         * create element if not exist
         */
        if(jQuery('#hidden_div').length == 0){
		var h = '<div id="hidden_div" style="display:none !important"></div>';
		jQuery("body").append(h);
	}
        
        jQuery("#sort_by option[value='']").attr("selected", "selected");
        
	jQuery("#sort_by, #sort_option").change(function(){

                var manage = new manage_elem(jQuery("#sort_by").val(),
                                            jQuery("#profile-list"),
                                            jQuery("#hidden_div"));

                if(jQuery(this).attr('id') == 'sort_by'){
                        
                        manage.update_option_fields(jQuery(this).val(),
                                                    jQuery("#sort_option"),
                                                    manage.start_sorting);
                
                } else {
                        
                        manage.start_sorting(jQuery(this)); 
                
                }                            

	});
	
});

/* 
 * sorting object
 * accepts sort type and parent,hidden divs
 */
function manage_elem(typ1, main_elm, hidden_elm){
                
                // this object
		var prc = this;
                
                // sorting type
                var sort_typ;
		
                // array that holds sorting arrangement
                var srt_arr = new Array();
                
                // array that holds associative sorting 
                var srt_arr_assoc = new Array();

                /* 
                * start sorting
                */
                prc.start_sorting = function(typ2){
                    
                        sort_typ = prc.calculate_sortyp(typ1, typ2.val());
			
                        if(sort_typ != ''){
				prc.hide_elem(main_elm, prc.transfer_objects);
			}
			
	        }

                /*
                 * update sorting options    
                 */
                prc.update_option_fields = function(setting, opt_sort, cl_back){
                    
                    opt_sort.html("");

                    var options;

                    if(setting == 1) {
                          options = {
                                 1 : 'Youngest to Oldest',
                                 2 : 'Oldest to Youngest'
                          };
                    } else if(setting == 2 ){
                          options = {
                                 1 : 'A - Z',
                                 2 : 'Z - A'
                          };
                    } else if(setting == 3 ){
                          options = {
                                 1 : 'Ascending',
                                 2 : 'Descending'
                          };
                    } else {
                          options = {
                                 '' : 'Sort Options'
                          }
                    }            

                    jQuery.each(options, function(val, text) {
                            var sel = 0;
                            if (sel == 0){
                                opt_sort.append(
                                        jQuery('<option "selected"></option>').val(val).html(text)
                                );
                            } else {
                                opt_sort.append(
                                        jQuery('<option></option>').val(val).html(text)
                                );
                            }
                            sel = sel + 1;
                    });
                    
                    cl_back(arguments[1]);
                
                }    

                /* 
                * calculate sorting type
                */
                prc.calculate_sortyp = function (t1, t2){
                        
                        // 1 young to old
                        if(t1 == '1' && t2 == '1'){
                            return '1';
                        }
                        
                        // 2 name a-z
                        if(t1 == '2' && t2 == '1'){
                            return '2';
                        }
                        
                        // 3 date joined descending
                        if(t1 == '3' && t2 == '2'){
                            return '3';
                        }
                        
                        // 4 date joined ascending
                        if(t1 == '3' && t2 == '1'){
                            return '4';
                        }
                        
                        // 5 old to young 
                        if(t1 == '1' && t2 == '2'){
                            return '5';
                        }
                        
                        // 6 name z - a
                        if(t1 == '2' && t2 == '2'){
                            return '6';
                        }
                        
                        return "";
           		
	        }

                /* 
                * hide elements in main div
                */
		prc.hide_elem = function(main, call){

			main.animate({opacity:0},1000, function(){
				 call(main_elm, hidden_elm, prc.create_array_elm);
			});

		}
		
                /* 
                * transfer elements only when hidden div is empty
                */
		prc.transfer_objects = function(from_elm, to_elm, call){
                        
                            var htm = from_elm.html();		
                            from_elm.html('');
                            to_elm.html(htm);	
                            call(htm, prc.clone_object_elements);	

                }

                /* 
                * provide values to arrangement array
                */
				
                prc.create_array_elm = function(hd_elm, clone){
			
			srt_arr = [];
                        srt_arr_assoc = [];
                        main_elm.html('');
                        
                        // age sorting youngest to oldest   
			if(sort_typ == '1') {
				jQuery("#hidden_div").find(".p_birth").each(function(){
					srt_arr.push(jQuery(this).val());
                                        srt_arr_assoc[jQuery(this).attr('id')] = jQuery(this).val();
				});
				srt_arr.sort();
                                srt_arr.reverse();
			
                        // name sorting ascending 	
			} else if (sort_typ == '2') {
				jQuery("#hidden_div").find(".p_name").each(function(){
					srt_arr.push(jQuery(this).val());	
                                        srt_arr_assoc[jQuery(this).attr('id')] = jQuery(this).val();
				});
				srt_arr.sort();
                                
                        // member registered sorting descending         
			} else if (sort_typ == '3') {
				jQuery("#hidden_div").find(".p_created").each(function(){
					srt_arr.push(jQuery(this).val());	
                                        srt_arr_assoc[jQuery(this).attr('id')] = jQuery(this).val();
				});
				srt_arr.sort();
                                srt_arr.reverse();
                        
                        // member registered sorting ascending   
			} else if (sort_typ == '4') {
				jQuery("#hidden_div").find(".p_created").each(function(){
                			srt_arr.push(jQuery(this).val());	
                                        srt_arr_assoc[jQuery(this).attr('id')] = jQuery(this).val();
				});
				srt_arr.sort();
                                
                        // age sorting oldest to youngest 
			} else if (sort_typ == '5') {
				jQuery("#hidden_div").find(".p_birth").each(function(){
                			srt_arr.push(jQuery(this).val());	
                                        srt_arr_assoc[jQuery(this).attr('id')] = jQuery(this).val();
				});
				srt_arr.sort();
                                
                      
		 	// member registered sorting descending         
		        } else if (sort_typ == '6') {
				jQuery("#hidden_div").find(".p_name").each(function(){
					srt_arr.push(jQuery(this).val());	
                                        srt_arr_assoc[jQuery(this).attr('id')] = jQuery(this).val();
				});
				srt_arr.sort();
                                srt_arr.reverse();
                                
                        } 
                        
                        clone();
		
		}

                /* 
                * rearrange back the elements to main container
                */
		prc.clone_object_elements = function(){
                       
                       var counted = new Array();
                       counted = [];
                       
                       jQuery.each(srt_arr, function(index, value) {

                                if(sort_typ == '1'  || sort_typ == '5'){
                                        
                                        if(prc.check_instance_in_array(value)){
                                            var cloned = jQuery("#hidden_div").find(".p_birth[value='"+value+"']").parent();
                                            prc.clone_em(cloned);
                                        } else {
                                            if(prc.not_in_array(counted,value)){
                                                prc.clone_em_all(value,"p_birth");
                                                counted.push(value);
                                            }
                                        }
                                        
                                } else if(sort_typ == '2'  || sort_typ == '6') {
                                        if(prc.check_instance_in_array(value)){
                                            var cloned = jQuery("#hidden_div").find(".p_name[value='"+value+"']").parent();
                                            prc.clone_em(cloned);
                                       } else {
                                            if(prc.not_in_array(counted,value)){
                                                prc.clone_em_all(value,"p_name");
                                                counted.push(value);
                                            }
                                        }


                                } else if(sort_typ == '3'  || sort_typ == '4') {
                                        if(prc.check_instance_in_array(value)){
                                                var cloned = jQuery("#hidden_div").find(".p_created[value='"+value+"']").parent();
                                                prc.clone_em(cloned);
                                       } else {
                                            if(prc.not_in_array(counted,value)){
                                                prc.clone_em_all(value,"p_created");
                                                counted.push(value);
                                            }
                                        }


                                }					  					

                       });
                       
                       main_elm.animate({opacity:1},1000,function(){hidden_elm.html('');});
                       
          	 }

               /* 
                * check if there is more than one instance
                */
                 prc.check_instance_in_array = function(vl){
                        
                        var count = 0;
                        for (var key in srt_arr_assoc) {
                            if (srt_arr_assoc.hasOwnProperty(key)) {
                                if (srt_arr_assoc[key] == vl){
                                    count = count + 1;
                                } 
                            }
                        }
                        if(count == 1){
                            return true;
                        } else if (count > 1){
                            return false;
                        }
                        
                        return false;
                 }
                 
               /* 
                * check if there is more than one instance
                */
                 prc.not_in_array = function(counted,vl){
                        
                        var ctr = 0;
                        
                        jQuery.each(counted, function(index, value) {
                            if(value == vl){
                                ctr = ctr + 1;
                            }
                        });
                        
                        if(ctr > 0){
                            return false;
                        } else {
                            return true;
                        }
                        
                        
                 }                 

               /* 
                * clone object
                */
                 prc.clone_em = function(clon){
                        
                        var $cloned = clon.clone();
                        main_elm.append($cloned);
                     
                 }

               /* 
                * clone object
                */
                 prc.clone_em_all = function(vl,cls){
                         
                     jQuery("#hidden_div").find("."+cls+"[value='"+vl+"']").each(function(){
                           var $cloned = jQuery(this).parent().clone();
                           main_elm.append($cloned);
                     });
                            
                 }

}