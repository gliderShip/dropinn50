
<div class="clsShow_Notification" id='facebook_verify_error_msg' style="display: none"><p class="error"><span><?php echo translate("Your Facebook Account Not Verified");?></span></p></div>
<div class="clsShow_Notification" id='facebook_verify_success_msg' style="display: none"><p class="success"><span><?php echo translate("Your Facebook Account Successfully Verified");?></span></p></div>
<div class="clsShow_Notification" id='facebook_verify_disconnect_msg' style="display: none"><p class="success"><span><?php echo translate("Your Facebook Account Successfully Disconnected");?></span></p></div>

<div class="clsShow_Notification" id='google_verify_error_msg' style="display: none"><p class="error"><span><?php echo translate("Your Google Account Not Verified");?></span></p></div>
<div class="clsShow_Notification" id='google_verify_success_msg' style="display: none"><p class="success"><span><?php echo translate("Your Google Account Successfully Verified");?></span></p></div>
<div class="clsShow_Notification" id='google_verify_disconnect_msg' style="display: none"><p class="success"><span><?php echo translate("Your Google Account Successfully Disconnected");?></span></p></div>

<div class="clsShow_Notification" id='email_verify_error_msg' style="display: none"><p class="error"><span><?php echo translate("Your Email Address Not Verified");?></span></p></div>
<div class="clsShow_Notification" id='email_verify_success_msg' style="display: none"><p class="success"><span><?php echo translate("Your Email Address Successfully Verified");?></span></p></div>
<div class="clsShow_Notification" id='email_verify_disconnect_msg' style="display: none"><p class="success"><span><?php echo translate("Your Email Address Successfully Disconnected");?></span></p></div>

<script>
	function verify()
	{
		$('#full_verify').hide();
		$('#profile').show(); 
		<?php
	if($users->facebook_verify != 'yes')
	{ 
	?>
		$('#facebook_verify').show();
	<?php }
		
	if($users->google_verify != 'yes')
	{ ?>
		$('#google_verify').show();
	<?php }
	
	if($users->email_verify != 'yes')
	{ ?>
		$("#email_verify").show();
	<?php }
	
        if($users->phone_verify != 'yes')
	{ ?>
		$("#phone_verify").show();
	<?php }
	 ?>
	}
	function back()
	{
		$('#full_verify').show();
		$('#profile').hide();
	}	
	<?php
	if($users->email_verify == 'yes' && $users->facebook_verify == 'yes' && $users->google_verify == 'yes')
	  {?>
	  	$('verify_via').hide();
	  	<?php
	  }
	 ?>
</script>
<?php 
if($this->input->get())
{ 
	if($this->input->get('google') == 'verified' || $this->input->get('google') == 'not_verified' || $this->input->get('email') == 'verify')
	{  ?>
		<script>
		window.onload=verify;
		</script>
		<?php
	}
}
?>
 </script>
 <style>
iframe[src^="https://apis.google.com"] {
  display: none;
}

@media screen and (max-width: 600px) 
{
.back
{
	 margin-left: 0px !important;
    text-align: left;
    padding:0px;
}
.box_verify br{
	display:none !important;
}
.ver_profile.new 
{
	margin-left: 0px !important;
}
.verify_me
{
	margin:0px;
	float:none;
}
#email_verify
{
	margin-bottom:15px;
}
.customGPlusSignIn.google_connect, .verify_me>a
{
	width:100%;
}
.verify_google{
	width:100%;
}	
}
@media screen and (max-width: 760px) 
{
	.full_verify_right
	{
		padding:0px;
	}

}
@media (min-width:601px){
	#phone_verify .verify_me , #email_verify .verify_me , #google_verify .verify_me
{
	margin-right:10px !important;
}
}
.verify_me{margin-left:0px;margin-top:0px;}
.pniw-number
{
	padding:4px !important;
}
.ver_profile{margin:0px 0px 20px !important}
#phone_no
{
	margin-right: 5px;
    padding: 9px;
}
#verify_code,#verify_code2
{
margin-right: 4px;
    padding: 3px 10px 2px !important;	
}
.back
{
	 margin:0px 0px 15px;
    text-align: left;
}
.box_verify
{
	margin-bottom:10px;
}
</style>
  <script type="text/javascript">
window.onbeforeunload = function(e){
  gapi.auth.signOut();
};
   var profile, email;

  function loginFinishedCallback(authResult) {
    if (authResult) {
      if (authResult['status']['signed_in']){
    
      	if(authResult['status']['method'] == 'AUTO')
      	{
      	 return false;
      	}
     
        //toggleElement('signin-button'); // Hide the sign-in button after successfully signing in the user.
        gapi.client.load('plus','v1', loadProfile);  // Trigger request to get the email address.
      } else {
        console.log('An error occurred');
      }
    } else {
      console.log('Empty authResult');  // Something went wrong
    }
  }

  function loadProfile(){
    var request = gapi.client.plus.people.get( {'userId' : 'me'} );
    request.execute(loadProfileCallback);
  }

 
  function loadProfileCallback(obj) {
    profile = obj;
    email = obj['emails'].filter(function(v) {
        return v.type === 'account'; // Filter out the primary email
    })[0].value; // get the email from the filtered results, should always be defined.
    displayProfile(profile);
  }


  function displayProfile(profile){
  	var name;
  	if(profile['displayName'] == '')
  	{
  		name = email.split('@');
  		name = name[0];
  	}
  	else
  	{
  		name = profile['displayName'];
  	}
  	var last_name = profile['name']['familyName'];
  	var first_name = profile['name']['givenName'];
 /*   document.getElementById('name').innerHTML = profile['displayName'];
    document.getElementById('pic').innerHTML = '<img src="' + profile['image']['url'] + '" />';
    document.getElementById('email').innerHTML = email; */
     toggleElement('profile');
   var PostData = 'name='+name+'&first_name='+first_name+'&last_name='+last_name+'&id='+profile['id']+'&url='+profile['url']+'&imageurl='+profile['image']['url']+'&email='+email;
           $.ajax({
            url: "<?php echo base_url()?>users/google_verify_detail",            
            type: "POST",                       
            data:PostData,
            success: function (result) { 
            	//alert(result);return false;
              window.location.href = '<?php echo base_url();?>'+result;                  
            },
            error: function (thrownError)
            {
            	 //alert(thrownError);
            	//alert('error');
            },
           
            });
   
  }

 
  function toggleElement(id) {
   /* var el = document.getElementById(id);
    if (el.getAttribute('class') == 'hide') {
      el.setAttribute('class', 'show');
    } else {
      el.setAttribute('class', 'hide');
    }*/
  }
  </script>
  
  <script type="text/javascript">
  (function() {
    var po = document.createElement('script');
    po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client:plusone.js?onload=render';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
  })();

  function render() {
    gapi.signin.render('customBtn', {
      'callback': 'loginFinishedCallback',
      'clientid': '<?php echo $google_app_id;?>',
      'cookiepolicy': 'single_host_origin',
      'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
    });
  }
  </script>
	<div id="display" style="margin: 30px 0 0 0;">
            <div class="container" id="container" style="margin-bottom:25px;" >
               <div class="full_verify med_9 mal_9 pe_12" id="full_verify">
                    <h1><?php echo translate('Thanks for choosing to verify your ID!');?></h1>
                    <p class="full_content"><?php echo translate('Verifying your ID is an easy way to help build trust in the').' '.$this->dx_auth->get_site_title().' '.('community. We believe anonymity erodes trust, so we verify the IDs of our guests and hosts to help ensure the safety of our growing community.');?></a></p>
                    <p class="full_content"><?php echo translate('By verifying your identification, you give').' '.$this->dx_auth->get_site_title().' '.translate('and our service providers permission to use this information for verification and risk assessment purposes. If you do not consent to our use of your information, please do not verify your identification. The information you provide is governed by our');?>
        	<?php echo translate('Privacy Policy');?></a>.</p>
        <div class="inner_verify">
        	<?php 
        	$i = 0;
        	if($users->facebook_verify != 'yes')
			{
				$i = $i + 1;
			} 
			if($users->google_verify != 'yes')
			{
				$i = $i + 1;
			}
			if($users->email_verify != 'yes')
			{
				$i = $i + 1;
			}
			
                        // mobile verification 2 step start 
                       
                         // mobile verification 2 step end
                         ?>
          <h3 class="thin"><?php echo translate('You have');?> <?php echo $i; ?> <?php echo translate('things left to do:');?></h3>
          <div class="shadow med_5 mal_6 pe_12 padd-zero">
            <ul class="verification-summary">
                <?php 
                      if($users->facebook_verify != 'yes')
					  { ?> 
                <li class="default"><i class="icon-green fa fa-check-circle fa-1g"></i>
                	<!--<img src='<?php echo base_url()."images/nott_success.png"?>' alt='close' />-->
                	<?php echo translate('Verify your Facebook address');?></li>
                <?php } ?>
                <?php 
                      if($users->google_verify != 'yes')
					  { ?> 
                <li class="default"><i class="icon-green fa fa-check-circle fa-1g"></i>
                	<!--<img src='<?php echo base_url()."images/nott_success.png"?>' />-->
                		<?php echo translate('Verify your Google address');?></li>
               <?php } ?>
               <?php 
                      if($users->email_verify != 'yes')
					  { ?> 
				<li class="default"><i class="icon-green fa fa-check-circle fa-1g"></i>
				<?php echo translate('Verify your email address');?></li>
           <?php } 
                   
                   
					  // mobile verification 1 step start 
					  // mobile verification 1 step end
               
                    ?>
            </ul>
           </div>
             <a class="btn_dash" href='#verification' onClick="verify()"><?php echo translate('verify me');?></a>
        </div>

</div>
    
	<div id="profile" style="display:none;">
            <div class="box_verify med_9 mal_9 pe_12 padd-zero">
                <div class="box_me">
                    <p class="online_profile"><?php echo translate('Online Profile');?></p>
                </div>
                <div class="verify_content_me">
                    <h2 class="profile_heading"><?php echo translate('Verify Online Profile');?></h2>
                    <p class="verified_facebook"><?php echo translate("Verifying your online profile lets us match details to your official identification to help ensure that you're a 'real person' online, rather than a robot or a spammer.");?></p>
                    <p class="veirfied"><?php echo translate('Already verified via:');?></p>
                   <div  class="verified_facebook" id="facebook_verify_me" style='display: none'><?php echo translate('Facebook');?></div>
                   <div  class="verified_facebook" id="google_verify_me" style='display: none'><?php echo translate('Google');?></div>
                   <div  class="verified_facebook" id="email_verify_me" style='display: none'><?php echo translate('Email');?></div>
                    <?php
                    if($users->facebook_verify == 'yes')
                    {
                        echo '<p class="verified_facebook" id="facebook">'.translate("Facebook").'</p>';
                    }
                    if($users->google_verify == 'yes')
                    {
                         echo '<p class="verified_facebook" id="google">'.translate("Google").'</p>';
                    }
                    if($users->email_verify == 'yes')
                    {
                        echo '<p class="verified_facebook" id="email">'.translate("Email").'</p>';
                    }
                    if($users->phone_verify == 'yes')
                    {
                        echo '<p class="verified_facebook" id="phone">'.translate("Phone Number").'</p>';
                    }
					echo '<p class="verified_facebook" id="verified_success" style="display:none;">'.translate("Phone Number").'</p>';
                    if($users->email_verify != 'yes' && $users->facebook_verify != 'yes' && $users->google_verify != 'yes' && $users->phone_verify != 'yes')
              {
               echo "<p class='dont_verified_facebook' id='no_verification'>".translate("You Don't Have Any Verifications.")."</p>";
              }
               if($users->email_verify == 'yes' && $users->facebook_verify == 'yes' && $users->google_verify == 'yes' && $users->phone_verify != 'yes')
              {
               echo "<p class='choose' id='all_verification'>".translate("You Did All Verifications.")."</p>";
              } 
              else
              {
                echo '<p class="choose" id="verify_via">'.translate("Choose the verify via:").'</p>';
              }
              ?>
                </div>
                <div style="padding:0px 15px;">
                <div class="email" id="phone_verify" style="display: none;">
                   <!--mobile verification 3 start -->
                    
                    <!--mobile verification 3 end -->
                   
                    </div> 
                <div id="google_verify" style="display: none">
                    <div class="verify_me">
                   	<div id="gSignInWrapper">
                      <div id="customBtn" class="customGPlusSignIn btn_dash_green verify_google" class=""><?php echo translate('Google');?></div>
                      <span class="icon"></span>
      				  <!--<span class="buttonText"></span>-->
				</div>
                    </div>
                </div>
                <script src="<?php echo base_url().'js/facebook_invite.js'; ?>"> </script> 
                <script>
                 FB.init({ 
                       appId:'<?php echo $fb_app_id; ?>', 
                       frictionlessRequests: true
                     });
                     function facebook()
                     { 
                        FB.login(function(response) {
                    if (response.authResponse) {
                        FB.api("/me", function(me){
                                 $.ajax({
                  type: "POST",
                  url: '<?php echo base_url()."users/facebook_verify";?>',
                   data: { fb_id: me.id, email: me.email },
                   success: function(data)
                        {
                        if(data == 'verified')
                        {
                        $('#no_verify').hide();
                        $("#facebook_verify").hide();
                        $("#facebook_verify_disconnect").show();
                        $("#facebook_verify_me").show();
                        $('#no_verification').hide();
                        $('#fb_veri').show();
                        $('.title_no_one').hide();
                        $("#facebook_verify_success_msg").fadeIn(2000);
                        $("#facebook_verify_success_msg").fadeOut();  
                        }
                        else
                        {
                            $("#facebook_verify_error_msg").fadeIn(2000);
        	                $("#facebook_verify_error_msg").fadeOut();
                        }
                        }
                });
                   
                    });
                     }
                     });
                    }
      
                </script>
                <div class="email" id="email_verify" style="display: none">
                    <div class="verify_me">
                            <a class="btn_dash_green" href="<?php echo base_url().'users/email_verify?email=verify';?>"><?php echo translate('Email');?></a>
                    </div>
                </div>
                
                <script> $('#facebook_verify').show();</script>
                <div class="facebook" id="facebook_verify" style="display: none;">
                    <div class="verify_me">
                        <a style="margin-bottom:12px;" id="facebook" class="btn_dash_green" onClick="facebook()">
                        Facebook
                        </a>
                    </div>
                </div>
                </br></br></br>
                <div id="verified" class="ver_profile new dash_very">
                       <span id="phone_no" style="background-color: #f1f1f1;border-right: 1px solid #d1d1c9;color: #393c3d;display: none;">  <?php  echo '+'.$profile->phnum; ?></span>           
                                          <?php
                                          
                                           if($users->phone_verify == 'yes')
										  { ?>
                                           <!--<span id="verified_success" style="color: #5bb013;"> Verified </span>-->
                                           
                                           <?php }
											else if($profile->phnum == '') {
												
												?>
												<!-- <a id="add_phone_no" href="#"><?php echo translate('Phone Number');?></a>-->
												<!--<a id="verify_sms2" rel="sms" href="javascript:void(0);" class="btn blue gotomsg">Verify via SMS</a>
												<span id="verified_success" style="color: #5bb013;display: none;"> Verified </span>
												-->
												<span id="verified_success" style="color: #5bb013;display: none;"> Verified </span>
												<?php
											}
											else
											{
												?>
												<!-- <a id="add_phone_no" href="#"><?php echo translate('Phone Number');?></a>-->
												<span id="phone_no1" style="background-color: #f1f1f1;border-right: 1px solid #d1d1c9;color: #393c3d;padding:9px;line-height: 35px;">  <?php  echo '+'.$profile->phnum; ?></span>
												
												<a id="verify_sms2" rel="sms" href="javascript:void(0);">Verify via SMS</a>
												<span id="verified_success" style="color: #5bb013;display: none;"> Verified </span>
												<a id="close" title="Remove">
													<!--<img width="15" style="margin-top:-3px; margin-left: 0px;" src="<?php echo base_url().'images/close_red.png';?>" />-->
                                           	   <i class="fa fa-close" style="color:red;"></i>
													</a>
												
												<!--<span id="verified_success" style="color: #5bb013;display: none;"> Verified </span>-->
												
											<?php
												}
											 ?>
											 <a id="verify_sms2" rel="sms" href="javascript:void(0);" style="display:none">Verify via SMS</a>
											<a id="close" title="Remove" style="display: none;">
													<!--<img width="15" style="margin-top:-3px; margin-left: 0px;" src="<?php echo base_url().'images/close_red.png';?>" />-->
                                           	   <i class="fa fa-close" style="color:red;"></i>
													</a>
                                            </div>
                                             <?php // } 
										//	else
											//	{
													?>
												
													<?php
											//	}
                                             ?>
    
             
                	<div class="phone-number-verify-widget" style="display: none;">
  <p class="pnaw-verification-error" style="color: red;"></p>
  <div class="pnaw-step1">
    <div id="phone-number-input-widget-9d95625f" class="phone-number-input-widget">
  <label for="phone_country">Choose a country:</label>
  <div class="select">
     <select name="country" id="country">
                                           	<?php 
                                           	
                                           	foreach($country->result() as $row)
											{
												if($row->country_name == $this->session->userdata('locale_country'))
												{
													$s = 'selected';
												}
												else {
													$s = '';
												}
												echo '<option value="'.$row->id.'"'.$s.'>'.$row->country_name.'</option>';
											}
                                           	?>
     </select>
  </div>

  <label for="phone_number">Add a phone number:</label>
  <div class="pniw-number-container clearfix">
    <div class="pniw-number-prefix">+91</div>
    <input type="text" id="phone_number" class="pniw-number">
  </div>
  <input type="hidden" name="phone" data-role="phone_number" value="91">
  <input type="hidden" value="10346170" name="user_id">
</div>
    <div class="pnaw-verify-container">
      <a id="verify_sms" rel="sms" href="javascript:void(0);" class="btn_dash">Verify via SMS</a>
      <a target="_blank" href="<?php echo base_url();?>home/help/17" class="why-verify">Why Verify?</a>
    </div>
  </div>
  <div class="pnaw-step2" style="display:none;">
    <p class="message" style="color: red;"></p>
    <label for="phone_number_verification">Please enter the 4-digit code:</label>
    <input type="text" id="phone_number_verification2">

    <div class="pnaw-verify-container">
      <a id="verify_code2" rel="verify" href="javascript:void(0);" class="btn_dash">
        Verify
      </a>
      <a href="javascript:void(0);" rel="cancel" class="cancel">
        Cancel
      </a>
    </div>
    <p class="cancel-message">If it doesn't arrive, click cancel and try again.</p>
  </div>
</div>
                 <div class="pnaw-step3" style="display:none;">
    <p class="message" style="color: red;"></p>
    <label for="phone_number_verification">Please enter the 4-digit code:</label>
    <input type="text" id="phone_number_verification">

    <div class="pnaw-verify-container">
      <a id="verify_code" rel="verify" href="javascript:void(0);" class="btn_dash">
        Verify
      </a>
      <a href="javascript:void(0);" rel="cancel" class="cancel">
        Cancel
      </a>
    </div>
    <p class="cancel-message">If it doesn't arrive, click cancel and try again.</p>
  </div>
                <div class="back  med_12 pe_12 mal_12 padding-zero" >
                    <a href="#" onClick="back()" class="btn_dash"><?php echo translate('Back');?></a>
                </div>
        </div>
    </div>
</div>
            <div class="full_verify_right med_3 mal_3 pe_12">
                <div class="span_verify med_12 pe_12 mal_12 pad-edit" data-cid="view29" data-view="progress_widget">
                    <div class="verification-progress-container">
                        <p class="progress-header"><?php echo translate('Verification Progress:');?></p>
                            <div class="progress-bar-container progress-bar-well space1">
                                <?php 
                                  if($i == 4)
								  {
								  	$width = 0;
								  }
                                    if($i == 3)
                                   {
                                    $width = 20;
                                   }
                                    if($i == 2)
                                   {
                                    $width = 50;
                                   }
                                    if($i == 1)
                                   {
                                    $width = 75;
                                   }
                                    if($i == 0)
                                   {
                                    $width = 100;
                                   }
                                ?>
                            <div class="progress-bar progress-bar-green" style="width:<?php echo $width; ?>%"></div>
                            </div>
                    </div>
                </div>
                <div class="verification_picture med_12 pe_12 mal_12" style="padding: 5px;text-align: center;">
                    <img class="adm-img" src="<?php 
                    /* if($this->session->userdata('image_url') != '')
                   {
                      $image_url = $this->session->userdata('image_url');
                      
                      echo $image_url;
                      $split = explode('.', $image_url);
                  $url = $split[0].'.'.$split[1].'.'.$split[2];
                  $email = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->email;
                    $data_tw['src'] = $url;
                    $data_tw['ext'] = '.'.$split[3];
                    $data_tw['email'] = $email;
                  $this->db->insert('profile_picture',$data_tw);
                   }
                   else {
                      */
                     echo $this->Gallery->profilepic($this->dx_auth->get_user_id(),2);
                       
                  // }?>"  />
                    <div class="picture_description">
                        <p class="picture_heading"><?php echo $users->username; ?></p>
                        <p class="picture_city ver_adm"><?php if(isset($profiles->live))
                       {
                        echo $profiles->live; 
                       }
                       else
                        {
                       echo translate('Address').' '.translate("Doesn't Specified");
                       } ?></p>
                        <p class="member"><?php echo translate('Member Since').' '.date('F',$users->created).' '.date('y',$users->created); ?></p>
                    </div>
                </div>
                <div class="verification_details med_12 pe_12 mal_12" id="verification_details_bar">
                    <p class="verification"><?php echo translate('Verifications');?></p>
                    <p class="title" id='fb_veri' style="display: none"><br>Facebook</p>
                    <p class="title"><?php if($users->facebook_verify == 'yes')
                        {
                            echo '<br>Facebook</p>';
                            $url = 'https://graph.facebook.com/fql?q=SELECT%20friend_count%20FROM%20user%20WHERE%20uid%20='.$users->fb_id;
                            $json = file_get_contents($url);
               $count = json_decode($json, TRUE);	
                        foreach($count['data'] as $row)
                        { 
                            echo '<p class="list">'.$row["friend_count"].' '.'Friends</p>';
                        }
                        }
                        if($users->google_verify == 'yes')
                            {
                            echo ' <p class="title">'.translate('Google').'</p><p class="list">'.translate('Verified').'</p>';
                            }
                        if($users->email_verify == 'yes')
                        {
                            echo ' <p class="title">'.translate('Email').'</p><p class="list">'.translate('Verified').'</p>';
                        }
						
			 // mobile verification 2 step start			
                        
                        // mobile verification 2 step end
                        
						
                        if($users->email_verify != 'yes' && $users->google_verify != 'yes' && $users->facebook_verify != 'yes' && $users->phone_verify != 'yes')
                        {
                        echo '<p class="title_no_one">'.translate("No One Verified").'</p>';
                        }
                         ?> </p>
                    
                </div>

    </div>
<style type="text/css">

.phone-number-verify-widget {
    border-radius: 2px;
    clear: both;
    display: inline-block;
    float: left;
    line-height: 26px;
    margin-bottom: 20px;
    padding: 0px 0px 15px;
    text-align: left;
    width: 416px;  
}

#phone_number {
  height: 32px;
}
#phone_verify
{
	margin-bottom:15px;
}
.pnaw-step3 {
   border: 1px solid #c3c3c3;
    border-radius: 2px;
    clear: both;
    display: inline-block;
    float: left;
    line-height: 26px;
    margin-bottom: 20px;
    margin-left: 0px;
    padding: 15px 34px 15px 15px;
    text-align: left;
}
.phone-number-input-widget .pniw-number-container .pniw-number-prefix {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #bbb;
    border-image: none;
    border-radius: 2px 0 0 2px;
    border-style: solid;
    border-width: 1px 0 1px 1px;
    color: #393c3d;
    float: left;
    line-height: 30px;
    min-width: 30px;
    padding: 0 4px;
    text-align: center;
}
</style>
<script>
$(document).ready(function() {
	     $.ajax({
            url: "<?php echo base_url()?>users/phone_code",            
            type: "POST",                       
            data:"id="+$(this).val(),
            success: function (result) { 
                   
                   $('.pniw-number-prefix').text('+'+result);          
            }
            });
	    
$('#country').change(function()
{
	 $.ajax({
            url: "<?php echo base_url()?>users/phone_code",            
            type: "POST",                       
            data:"id="+$(this).val(),
            success: function (result) { 
                   
                   $('.pniw-number-prefix').text('+'+result);          
            }
            });
})	    
$('#verify_sms').click(function()
{	
	if($('#phone_number').val() == '' || isNaN($('#phone_number').val()))
	{
		$('.pnaw-verification-error').show();
		$('.pnaw-verification-error').text('Please enter a phone number.');return false;
	}
	
	if($('#phone_number').val().length < 4)
	{
		$('.pnaw-verification-error').show();
		$('.pnaw-verification-error').text('The phone number you entered was too short.');return false;
	}
	
	 $.ajax({
            url: "<?php echo base_url()?>users/mobile_verification",            
            type: "POST",                       
            data:"phone_number="+$('.pniw-number-prefix').text()+$('#phone_number').val(),
            success: function (result) { 
            	$('.pnaw-verification-error').hide();
            	$('.message').hide();
            	$('.pnaw-step1').hide();
                $('.pnaw-step2').show();   
            }
            });
})

$('#close').click(function()
{
	 $.ajax({
            url: "<?php echo base_url()?>users/close_phone",            
            type: "POST",                       
            success: function (result) { 
            //	$('.phone-number-verify-widget').show();
            	$('#add_phone_no').show();
                $('#verified').hide();   
            }
            });
})

$('#verify_sms2').click(function()
{
	$('#verified').hide();
	 $('.pnaw-step3').show();
	 $('.message').hide();
	//$('.phone-number-verify-widget').show();
})
$('#verify_code').click(function()
{
	 $.ajax({
            url: "<?php echo base_url()?>users/check_mobile_verification",            
            type: "POST",                       
            data:"phone_code="+$('#phone_number_verification').val(),
            success: function (result) { 
            	if(result == 'failed')
            	{
            		//alert('We were unable to validate your phone number. Please try again.');
            		$('.pnaw-verification-error').show();
					$('.pnaw-verification-error').text('We were unable to validate your phone number. Please try again.');
					$('.message').show();
            	    $('.message').text('We were unable to validate your phone number. Please try again.');return false;
            	} 
            	
            	if(result == 'success')
            	{
            		 $.ajax({
            url: "<?php echo base_url()?>users/get_phone_no",            
            type: "POST",                       
            success: function (result) { 
            	$('#phone_no').text('+'+result);
            }
            });
            		$('.phone-number-verify-widget').hide();
            		$('.pnaw-step2').hide();
            		$('.pnaw-step3').hide();
            		$('#verified_success').show();
            		$('#verified').hide();
            		$('#no_verification').hide();
            		$('#verify_sms2').hide();
            		$('#close').hide();
            	}
            }
            });
})

$('#verify_code2').click(function()
{
	 $.ajax({
            url: "<?php echo base_url()?>users/check_mobile_verification",            
            type: "POST",                       
            data:"phone_code="+$('#phone_number_verification2').val(),
            success: function (result) { 
            	if(result == 'failed')
            	{
            		//alert('We were unable to validate your phone number. Please try again.');
            		//$('.pnaw-verification-error').show();
					//$('.pnaw-verification-error').text('We were unable to validate your phone number. Please try again.');
					//$('.message').show();
            		$('.message').text('We were unable to validate your phone number. Please try again.');return false;
            	} 
            	
            	if(result == 'success')
            	{
            		 $.ajax({
            url: "<?php echo base_url()?>users/get_phone_no",            
            type: "POST",                       
            success: function (result) { 
            	$('#phone_no').text('+'+result);
            }
            });
            		$('.phone-number-verify-widget').hide();
            		$('.pnaw-step2').hide();
            		$('.pnaw-step3').hide();
            		$('#verified').hide();
            		$('#verified_success').show();
            		$('#no_verification').hide();
            		$('#verify_sms2').hide();
            		$('#close').hide();
            	}
            }
            });
})

$('.cancel').click(function()
{
		 $.ajax({
            url: "<?php echo base_url()?>users/get_phone_no",            
            type: "POST",                       
            success: function (result) { 
            	$('#phone_no').text('+'+result);
            }
            });
    $('#phone_number_verification').val('');
	$('.phone-number-verify-widget').hide();
	$('.pnaw-step3').hide();
	$('#verified').show();
	$('#verify_sms2').show();
	$('#close').show();
	$('#phone_no').show();
	$('#phone_no1').hide();
})

<?php if($profile->phnum != '' || $users->phone_verify == 'yes')
{?>
//$('.phone-number-verify-widget').show();
$('#add_phone_no').hide();
<?php } ?>

<?php if($profile->phnum != '' && $users->phone_verify != 'yes')
{?>
//$('.phone-number-verify-widget').show();
//$('#add_phone_no').show();
//$('#verified').show();
<?php } ?>

<?php if($profile->phnum != '' && $users->phone_verify == 'yes')
{?>
//$('.phone-number-verify-widget').show();
$('#verified').show();
<?php } ?>


$('#add_phone_no').click(function()
{
	$('#add_phone_no').hide();
	$('.message').hide();
	$('.pnaw-step2').hide();
	$('.pnaw-step1').show();
	$('.pnaw-step3').hide();
	$('.phone-number-verify-widget').show();
})

});
</script>
