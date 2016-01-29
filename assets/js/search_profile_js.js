/*!
 * jQuery search profile
 */
jQuery(document).ready(function(){
    
    if (jQuery( "#profile-search-form-condensed" ).hasClass( "hide_custom_fields" )) {
        var fieldsObj = {
        "rb_gender": jQuery('#rb_gender'),
        "rb_datebirth": jQuery('#rb_datebirth'),
        "rb_datebirth2": jQuery('#rb_datebirth2'),
        "rb_city": jQuery('#rb_city'),
        "rb_country": jQuery('#rb_country'),
        "rb_state": jQuery('#rb_state'),
        "rb_zip": jQuery('#rb_zip'),
        "profilecustomid_1": jQuery('#profilecustomid_1'),
        "profilecustomid_2": jQuery('#profilecustomid_2'),
        "profilecustomid_3": jQuery('#profilecustomid_3'),
        "profilecustomid_4": jQuery('#profilecustomid_4'),
        "profilecustomid_5": jQuery('#profilecustomid_5'),
        
        "profilecustomid_6": jQuery('#profilecustomid_6'),
        "profilecustomid_7": jQuery('#profilecustomid_7'),
        "profilecustomid_8": jQuery('#profilecustomid_8'),
        "profilecustomid_9": jQuery('#profilecustomid_9'),
        
        "profilecustomid_10": jQuery('#profilecustomid_10'),
        "profilecustomid_11": jQuery('#profilecustomid_11'),
        "profilecustomid_12": jQuery('#profilecustomid_12'),
        "profilecustomid_13": jQuery('#profilecustomid_13'),
        
        "profilecustomid_14": jQuery('#profilecustomid_14'),
        "profilecustomid_15": jQuery('#profilecustomid_15'),
        "profilecustomid_16": jQuery('#profilecustomid_16'),
        "profilecustomid_17": jQuery('#profilecustomid_17'),
        
        "profilecustomid_18": jQuery('#profilecustomid_18'),
        "profilecustomid_19": jQuery('#profilecustomid_19'),
        "profilecustomid_20": jQuery('#profilecustomid_20'),
        "profilecustomid_21": jQuery('#profilecustomid_21'),
        "profilecustomid_22": jQuery('#profilecustomid_22'),        
      };
      
      //jQuery.each( fieldsObj , function( key, value ) {
      //      value.hide();
      //});
      
      jQuery( ".show_fields_dynamiclly" ).hide();
      jQuery( "#rb_gender" ).hide();
      
      
      jQuery( '#rb_profiletype #type' ).on('change', function() {        
        if(  this.value  != '')
        {
            fieldsObj.rb_gender.show();   
        }
        else
        {
            fieldsObj.rb_gender.hide();    
        }        
      });
      
      jQuery( '#rb_gender #gender' ).on('change', function() {
        if(  this.value  != '')
        {
            jQuery( '.show_fields_dynamiclly' ).show();
        }
        else
        {
            jQuery( '.show_fields_dynamiclly' ).hide();
        }        
      });
      
      
    //  jQuery( "#datebirth_min" ).blur(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.rb_datebirth2.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.rb_datebirth2.hide();    
    //    } 
    //  });
    //  
    //  jQuery( "#datebirth_max" ).blur(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.rb_datebirth2.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.rb_datebirth2.hide();    
    //    } 
    //  });
    //  
    // jQuery( "#rb_datepicker_from_bd" ).blur(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.rb_city.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.rb_city.hide();    
    //    } 
    //  });
    // 
    //   jQuery( "#rb_datepicker_to_bd" ).blur(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.rb_city.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.rb_city.hide();    
    //    } 
    //  });
    //   
    //   jQuery( "#rb_city #city" ).blur(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.rb_country.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.rb_country.hide();    
    //    } 
    //  });
    //   
    //  jQuery( "#rb_country #country" ).change(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.rb_state.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.rb_state.hide();    
    //    } 
    //  });
    //  
    //   jQuery( "#rb_state #state" ).change(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.rb_zip.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.rb_zip.hide();    
    //    } 
    //  });
    //   
    //jQuery( "#rb_zip #zip" ).blur(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.profilecustomid_1.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.profilecustomid_1.hide();    
    //    } 
    //});
    //
    //jQuery( "#profilecustomid_1 select" ).change(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.profilecustomid_2.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.profilecustomid_2.hide();    
    //    } 
    //  });
    //
    //   jQuery( "#profilecustomid_2 select" ).change(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.profilecustomid_3.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.profilecustomid_3.hide();    
    //    } 
    //  });
    //   
    //   jQuery( "#profilecustomid_3 select" ).change(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.profilecustomid_4.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.profilecustomid_4.hide();    
    //    } 
    //  });
    //   
    //   jQuery( "#profilecustomid_4 select" ).change(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.profilecustomid_5.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.profilecustomid_5.hide();    
    //    } 
    //  });
    //   
    //   jQuery( "#profilecustomid_5 select" ).change(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.profilecustomid_6.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.profilecustomid_6.hide();    
    //    } 
    //  });
    //   
    //   jQuery( "#profilecustomid_6 input" ).blur(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.profilecustomid_7.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.profilecustomid_7.hide();    
    //    } 
    //  });
    //   
    //     jQuery( "#profilecustomid_7 input" ).blur(function() {
    //    if(  this.value  != '')
    //    {
    //        fieldsObj.profilecustomid_8.show();   
    //    }
    //    else
    //    {
    //        fieldsObj.profilecustomid_8.hide();    
    //    } 
    //  });
       
      
      
        
        //jQuery( '#rb_profiletype #type' ).on('change', function() {        
        //    if(  this.value  != '')
        //    {                
        //        jQuery( ".show_fields_dynamiclly" ).show();
        //    }
        //    else
        //    {
        //        jQuery( ".show_fields_dynamiclly" ).hide();
        //    }        
        //  });
      
    }

});

