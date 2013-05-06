<a class="button-secondary" id="bulk_generate" href="javascript:void(0)" style="margin-bottom: 5px" title="Generate" disabled="disabled">Generate</a>
<a class="button-primary"  id="bulk_send_email" href="javascript:void(0)" style="margin-left: 5px" title="Send Email" disabled="disabled">Send Email</a>
<a class="button-primary"  id="open_popup" href="javascript:void(0)" style="margin-left: 5px;float: right" title="Send Email">Edit Email Content</a>
<div id="ch_bulk" style="float: none !important;margin-left: 10px;width: 34px;display: inline-block;position: relative;top: 7px;margin-top: -15px;"></div>
<table cellspacing="0" class="widefat fixed">
  <thead>
    <tr class="thead">
      <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
      <th style="width:50px;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=ProfileID&dir=". $sortDirection) ?>">ID</a></th>
      <th style="width:250px;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=ProfileContactNameFirst&dir=". $sortDirection) ?>">First Name</a></th>
      <th style="width:250px;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=ProfileContactNameLast&dir=". $sortDirection) ?>">Last Name</a></th>
      <th style="width:250px;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=ProfileGender&dir=". $sortDirection) ?>">Email Addresses</a></th>
      <th style="width:100px;"></th>
      <th style="width:100px;"></th>
      <th></th>
    </tr>
  </thead>
 <tbody>

<?php
$query = "SELECT * FROM ". table_agency_profile ." profile LEFT JOIN ". table_agency_data_type ." profiletype ON profile.ProfileType = profiletype.DataTypeID ". $filter  ." ORDER BY $sort $dir $limit";
$results2 = mysql_query($query);
$count = mysql_num_rows($results2);
$i = 0;
while ($data = mysql_fetch_array($results2)) {

  $ProfileID = $data['ProfileID'];
  $ProfileContactNameFirst = stripslashes($data['ProfileContactNameFirst']);
  $ProfileContactNameLast = stripslashes($data['ProfileContactNameLast']);
  $ProfileContactEmail = rb_agency_strtoproper(stripslashes($data['ProfileContactEmail']));

  $i++;
  if ($i % 2 == 0) {
          $rowColor = " style='background: #fcfcfc'"; 
  } else {
          $rowColor = " "; 
  } ?>
  <tr <?php echo $rowColor ?>>
    <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $ProfileID ?>" id="<?php echo $ProfileID ?>" data-firstname="<?php echo $ProfileContactNameFirst ?>" data-lastname="<?php echo $ProfileContactNameLast ?>" data-email="<?php echo $ProfileContactEmail ?>" class="administrator"  name="<?php echo $ProfileID ?>"/></th>
    <td><?php echo $ProfileID ?></td>
    <td><?php echo $ProfileContactNameFirst ?></td>
    <td><?php echo $ProfileContactNameLast ?></td>
    <td><?php echo $ProfileContactEmail ?></td>
    <td><a href="javascript:void(0)" class="generate_lp button-secondary" data-id="<?php echo $ProfileID ?>" data-firstname="<?php echo $ProfileContactNameFirst ?>" data-lastname="<?php echo $ProfileContactNameLast ?>">Generate</a></td>
    <td><a href="javascript:void(0)" class="email_lp button-primary" disabled="disabled" data-id="<?php echo $ProfileID ?>" id="em_<?php echo $ProfileID ?>" data-email="<?php echo $ProfileContactEmail ?>">Send Email</a></td>
    <td>
      <div id="ch_<?php echo $ProfileID ?>"></div>
      <input id="l_<?php echo $ProfileID ?>" type="text" placeholder="Login" /><br />
      <input id="p_<?php echo $ProfileID ?>" type="text" placeholder="Password" />         
    </td>
  </tr>
  <?php
}

mysql_free_result($results2);
if ($count < 1) {
  if (isset($filter)) { 
?>
    <tr>
      <th class="check-column" scope="row"></th>
      <td class="name column-name" colspan="5">
        <p>No profiles found with this criteria.</p>
      </td>
    </tr>
<?php
  } else {
?>

    <tr>
      <th class="check-column" scope="row"></th>
      <td class="name column-name" colspan="5">
        <p>There aren't any profiles loaded yet!</p>
      </td>
    </tr>
<?php
  }
} 
?>
     
 </tbody>
  <tfoot>
    <tr class="thead">
      <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th>
      <th class="column" scope="col">ID</th>
      <th class="column" scope="col">First Name</th>
      <th class="column" scope="col">Last Name</th>
      <th class="column" scope="col">Email Address</th>
      <th class="column" scope="col"></th>
      <th class="column" scope="col"></th>
      <th class="column" scope="col"></th>
    </tr>
  </tfoot>
</table>
<div id="popup">
    <span class="popup_close" title="Close">x</span>
    <span class="popup_title">Use following shortcodes to put constructions in text</span><br />
    <code>[login]</code>, <code>[password]</code>, <code>[url]</code><br />
    <textarea id="emailContent" style="width:100%; height:200px"></textarea><br/>
    <input id="saveEmailContent" type="button" value="Save" class="button-primary" style="float:right;"/>
</div>
<div id="popup_bg"></div>
<script type="text/javascript">

jQuery(document).ready(function($){
  $('#open_popup').click(function(){
    $('#popup_bg').fadeIn('fast',function(){
      $('#popup').fadeIn('fast');
      
      // get custom email message, if defined
      $.ajax({
        url: ajaxurl,
        type: 'get',
        data: { 
          action: 'read_email_cnt'
        },
        success: function(data){
          if(data != 'empty'){
            $('#emailContent').val(data);
          }
          else {
            $('#emailContent').val('Hello, we generated new login and password for you at RB Agency\n\n[login]\n[password]\n\nYou can [url] here\n\nThanks.');
          }
        }
      });
    });
  });
    
  $('#popup_bg, .popup_close').click(function(){
    $('#popup').fadeOut('fast',function(){
      $('#popup_bg').fadeOut('fast');
    });
  });
    
  $('.generate_lp').click(function(){
    var pid = $(this).attr('data-id');
    var pfname = $(this).attr('data-firstname');
    var plname = $(this).attr('data-lastname');
    
    var lp_arr = generateLP(pid, pfname, plname);

    var login = lp_arr[0];
    var password = lp_arr[1];
    
    $('#l_' + pid).val(login);
    $('#p_' + pid).val(password);
    $('#em_' + pid).removeAttr('disabled');
    $('#em_' + pid).bind('click', sendEmail);
    $('#ch_' + pid).addClass('pending-profile');
  });
   
   
  $('#cb input[type=checkbox], .administrator').click(function(){ 
    if($(this).is(':checked')){
      $('#bulk_generate').removeAttr('disabled');
      $('#bulk_generate').unbind('click').bind('click', bulkGenerateLP);
    }
    else { 
      if($('.administrator:checked').length == 0 || $('.administrator:checked').length == 50)
      $('#bulk_generate').attr('disabled', 'disabled');
    }
  });

   
  // saving custom email message
  $('#saveEmailContent').click(function(){
    var emailContent = $('#emailContent').val();
    if(emailContent){
      $.ajax({
        url: ajaxurl,
        type: 'post',
        data: { 
          action: 'write_email_cnt',
          email_message: emailContent 
        },
        success: function(){
          $('#popup').fadeOut('fast',function(){
            $('#popup_bg').fadeOut('fast');
          });
        }
      });
    }
  });
      
   
  function generateLP(pid, pfname, plname){
    var login = pfname.toLowerCase().substr(0, 5).replace(' ', '-') + pid + plname.toLowerCase().substr(-3, 3);
    var password = generatepass(login);
     
    return [login, password];
  }

  function bulkGenerateLP(){
    if($('.administrator:checked').length > 0){ 
      $('#ch_bulk').removeClass();
      $('.administrator:checked').each(function(){
        var pid = $(this).attr('id');
        var pfname = $(this).attr('data-firstname');
        var plname = $(this).attr('data-lastname');

        var lp_arr = generateLP(pid, pfname, plname);

        var login = lp_arr[0];
        var password = lp_arr[1];

        $('#l_' + pid).val(login);
        $('#p_' + pid).val(password);
        $('#em_' + pid).removeAttr('disabled');
        $('#em_' + pid).bind('click', sendEmail);
        $('#ch_' + pid).addClass('pending-profile');
      });
         
      $('#bulk_send_email').removeAttr('disabled');
    }
     
    $('#bulk_send_email').unbind('click').bind('click', bulkSendEmail);
  }

  function sendEmail(){
    var pid = $(this).attr('data-id');

    var login = $('#l_' + pid).val();
    var password = $('#p_' + pid).val();
    var email = $(this).attr('data-email').toLowerCase();

    if(login && password && email){
      $('#ch_' + pid).removeClass('pending-profile').addClass('loading-profile');
      $.ajax({
        url: ajaxurl, // pointed to admin-ajax.php
        type: 'post',
        data: {
          action: 'send_mail',
          profileid : pid,
          login : login,
          password : password,
          email : email
        },
        success: function(data){
          if(data == 'SUCCESS'){
            $('#ch_' + pid).removeClass('loading-profile').addClass('checked-profile');
            $('#l_' + pid).attr('disabled', 'disabled');
            $('#p_' + pid).attr('disabled', 'disabled');
            $('#em_' + pid).attr('disabled', 'disabled');
            $('#em_' + pid).unbind('click');
          }
          else {
            $('#ch_' + pid).removeClass('loading-profile').addClass('error-profile');
            alert(data);
            return false;
          }
        }
      });
    }
    else {
       alert("Please Generate Login / Password, then send!");
       return false;
    }
  }
    
  function bulkSendEmail(){ 
    var usersLP = {};
    if($('.administrator:checked').length > 0){ 
        $('#ch_bulk').removeClass().addClass('loading-profile');
        $('.administrator:checked').each(function(){
          var pid = $(this).attr('id');
          var login = $('#l_' + pid).val();
          var password = $('#p_' + pid).val();
          var email = $(this).attr('data-email');

          usersLP[pid] = {
            pid : pid,
            login : login,
            password : password,
            email : email
          };                         
      });
       
      //console.log(usersLP);       
      $.ajax({
        url: ajaxurl, // pointed to admin-ajax.php
        type: 'post',
        data: {
          action: 'send_bulk_mail',
          users_pl : usersLP
        },
        success: function(data){
          if(data == 'SUCCESS'){
            $('#ch_bulk').removeClass().addClass('checked-profile');
            $('.administrator:checked').each(function(){
              var pid = $(this).attr('id');
              $('#ch_' + pid).removeClass();
            });
          }
          else {
            $('#ch_bulk').removeClass().addClass('error-profile');
          }
        }
      });
    }
  }   
});

var keylist = "abcdefghijklmnopqrstuvwxyz123456789";

function generatepass(str){
  var temp = '';
  for (i=0; i < str.length; i++)
    temp += keylist.charAt(Math.floor(Math.random() * keylist.length));
  return temp;
}
</script>