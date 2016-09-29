<!-- Required css stylesheets -->
<!--<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />-->
<style>
.col-2 {
width: 16.66667%;
overflow:hidden;
}
.row-space-4 {
display: block !important;
}
.row-space-4 {
margin-bottom: 25px;
}
.media-round {
border-radius: 50%;
border: 2px solid #fff;
}
.media-photo {
-webkit-backface-visibility: hidden;
backface-visibility: hidden;
position: relative;
display: inline-block;
vertical-align: bottom;
overflow: hidden;
background-color: #cacccd;
}
.row>.col-1, .row>.col-2, .row>.col-3, .row>.col-4, .row>.col-5, .row>.col-6, .row>.col-7, .row>.col-8, .row>.col-9, .row>.col-10, .row>.col-11, .row>.col-12 {
padding-left: 12.5px;
padding-right: 12.5px;
}
.row {
margin-left: -12.5px;
margin-right: -12.5px;
}
.list-unstyled, .list-layout, .subnav-list, .sidenav-list {
padding-left: 0;
list-style: none;
}
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
float: left;
position: relative;
min-height: 1px;
}
.row-space-4 {
margin-bottom: 25px;
}
.name{
	text-align: center;
}
#form
{
	margin:0 !important;
}
.Box_reference{
	padding:10px 10px 10px 15px !important;
}
.msgbg h2{
	padding-left:15px;
}
textarea{
	width:100%;
}
.Box_reference p{
	line-height:25px;
}
</style>
<link rel="image_src" href="<?php echo base_url().'images/icon.png'; ?>" />

<meta property="og:image" content="<?php echo base_url().'logo/logo.png'; ?>" />

<!-- End of stylesheet inclusion -->
<script src="<?php echo base_url().'js/facebook_invite.js'; ?>"> </script>
<script>
	FB.init({ 
       appId:'<?php echo $fb_app_id; ?>', 
       frictionlessRequests: true
     });
     FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
  	$('#facebook_msg').show();
    FB.api('/me/friends', function(response) {
        if(response.data) {
        	
        	var count = 0;
        	$.each(response.data,function(index,friend) {
        	count++;
        	});
        	var i =0;
        	var id = new Array();
        	var name = new Array();
            $.each(response.data,function(index,friend) {
            	id[i] = friend.id;
            	name[i] = friend.name;
            	
            	i++;
            });
           
                $.ajax({
  type: "POST",
  url: '<?php echo base_url()."users/fun_friends_fb_id";?>',
  data: { fb_id: id, fb_name: name, friends_count: count, match_count: i },
   success: function(data)
        {
            $('#friends').html(data);
        }
});
        //  });
        } else {
            //alert("Error!");
        }
    });
  } else if (response.status === 'not_authorized') {
    // the user is logged in to Facebook, 
    // but has not authenticated your app
  } else {
    // the user isn't logged in to Facebook.
    	$('#facebook_not_login').show();
  }
 });
 
 function facebook_login()
 {
 	 FB.login(function(response) {
 	 	
 	 FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
  	$('#facebook_msg').show();
  	$('#facebook_not_login').hide();
    FB.api('/me/friends', function(response) {
        if(response.data) {
        	
        	var count = 0;
        	$.each(response.data,function(index,friend) {
        	count++;
        	});
        	var i =0;
        	var id = new Array();
        	var name = new Array();
            $.each(response.data,function(index,friend) {
            	id[i] = friend.id;
            	name[i] = friend.name;
            	
            	i++;
            });
           
                $.ajax({
  type: "POST",
  url: '<?php echo base_url()."users/fun_friends_fb_id";?>',
  data: { fb_id: id, fb_name: name, friends_count: count, match_count: i },
   success: function(data)
        {
            $('#friends').html(data);
        }
});
        //  });
        } else {
            //alert("Error!");
        }
    });
  } else if (response.status === 'not_authorized') {
    // the user is logged in to Facebook, 
    // but has not authenticated your app
  } else {
    // the user isn't logged in to Facebook.
    	$('#facebook_not_login').show();
  }
 });
 });
 }
 
 function send_invitation(){
     FB.ui(
     { 
      method: 'send', 
    //  to : fb_frnd_id,
      link: '<?php echo base_url();?>',
     }, requestCallback);
      function requestCallback(response) {
      
      }       
     } 

 	function create_request(id)
 	{
 	             $.ajax({
  type: "POST",
  url: '<?php echo base_url()."users/create_reference_request";?>',
  data: { user_id: id },
   success: function(data)
        {
            $('#'+data).text('Requested..');
            $('#'+data).prop('disabled', true);
        }
})
}

 </script>
 <?php
 $reference_count['by_you_count'] = $by_you_count;
 $reference_count['about_you_count'] = $about_you_count;
 ?>
  <?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>

			<?php $this->load->view(THEME_FOLDER.'/includes/profile_header'); ?>	
			<?php $this->load->view(THEME_FOLDER.'/includes/reference_header',$reference_count); ?>
<div id="dashboard_container" class="med_12 padd-zero">   
    	
    <div class="Box" id="View_GetRecom_2">
         <div class="Box_Content Box_reference">
         <p>
         	<?php echo $this->dx_auth->get_site_title()." ".translate("is built on trust and reputation. You can request references from your personal network, and the references will appear publicly on your")." ".$this->dx_auth->get_site_title()." ".translate("profile to help other members get to know you. You should only request references from people who know you well.");?>
         </p>
          </div> 
    </div>  
    
	<div class="Box" id="View_GetRecom_2">
    	<div class="Box_Head msgbg"><h2><?php echo translate("Email your friends"); ?></h2></div>
         <div class="Box_Content Box_reference">
         <?php echo form_open("users/references",'id="form"')?>
         <p class="med_7"><?php echo translate("Friends you email can sign up on")." ".$this->dx_auth->get_site_title()." ".translate("to write you a personal reference. You can review new references and either accept or ignore them before they are displayed on your public profile."); ?> 
       
		 
		 <div class="padd-zero med_6 mal_6 pe_12" >
		 	<p id="message" class="err_msg" style="display:none"></p>
          <p>
          <textarea name="email_to_friend" id="email_valid" value="" cols="40" placeholder="Enter email addresses..."></textarea> <?php echo form_error('email_to_friend'); ?> </p>
          
          <p>
          <button type="submit" class="btn_dash" id="email_friends" name="commit"><span><span><?php echo translate("Send Request Emails"); ?></span></span></button>
          </p>
          </div>
          </form>
          </div> 
    </div>    
    <div class="Box" id="facebook_msg" style="display: none;">
    	<div class="Box_Head msgbg"><h2><?php echo translate("Facebook"); ?></h2></div>
         <div class="Box_Content Box_reference">
         	<p class="fb_cls_box">
          <a class="btn fb-blue facebook_me" href="javascript:void(0);" id="facebook_share" onclick="send_invitation()">
                <i class="icon icon-facebook"></i>
                Create Request
          </a>&nbsp&nbsp&nbsp</p>
        <p class="fb_desc"> <?php echo translate("Create and send a personal Reference request through Facebook messaging."); ?></p>
		 <div id="message" style="display:none"></div>
          
          </div> 
    </div>  
     <div class="Box" id="facebook_not_login" style="display: none;">
    	<div class="Box_Head msgbg"><h2><?php echo $this->dx_auth->get_site_title().' '.translate("Friends"); ?></h2></div>
         <div class="Box_Content Box_reference">
         	<p>
          <button class="btn fb-blue facebook_me" id="facebook_login" onclick="facebook_login()">
                <i class="icon icon-facebook"></i>
                Connect with Facebook
              </button>
         <?php echo translate("Connect your Facebook account to see which of your friends are on")." ".$this->dx_auth->get_site_title()." ".translate("You will be able to request references from them here. "); ?>
         </p>
		 <div id="message" style="display:none"></div>
          
          </div> 
    </div>  
     <div class="Box" id="friends">
    	
    </div>   
</div></div>
<script>
$('#email_friends').click(function(){
var emailAddress = $('#email_valid').val();
var mail = emailAddress.split(',');
for(i=0;i<mail.length;i++){
var emailRegex = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);
 var valid = emailRegex.test(mail[i]);
  if (!valid) {
  //  alert("Please Enter the valid e-mail address");
    $("#message").show();
	$("#message").html('<p style="color:red"><strong><em> Please Enter the valid e-mail address </em></strong></p>');
    $("#message").delay(2000).fadeOut('slow');
    return false;
  } else
  				$("#message").show();
				$("#message").html('<p style="color:#009933"><strong><em> Email Sent successfully </em></strong></p>');
				$("#message").delay(2000).fadeOut('slow');
    return true;
	}
	
});
</script>
