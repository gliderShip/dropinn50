<html>
<head>
<link href="<?php echo css_url().'/common.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url().'/demo.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url().'/popup.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="image_src" href="<?php echo base_url().'images/icon.png'; ?>" />

<meta property="og:image" content="<?php echo base_url().'images/icon.png'; ?>" />

<script src="<?php echo base_url(); ?>js/facebook_invite.js"></script>
 
   	<script type="text/javascript">
FB.init({ 
       appId:'<?php echo $fb_app_id; ?>', 
       frictionlessRequests: true
     });
  function send_invitation(){
     FB.ui(
     { 
      method: 'send', 
    //  to : fb_frnd_id,
      link: '<?php echo base_url()."users/signup?airef=".$referral_code;?>',
     }, requestCallback);
      function requestCallback(response) {
      
      }       
     } 
    function send_invitation1(url, title, descr, image, winWidth, winHeight) {
        var winTop = (screen.height / 2) - (winHeight / 2);
        var winLeft = (screen.width / 2) - (winWidth / 2);
        window.open('http://www.facebook.com/sharer.php?s=100&p[title]=' + title + '&p[summary]=' + descr + '&p[url]=' + url + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
    }

      function fb_share()
      {
      	FB.ui(
  {
    method: 'feed',
    name: 'Take a trip!',
    link: '<?php echo base_url()."users/signup?airef=".$referral_code;?>',
    picture: '<?php echo base_url()."logo/logo.png";?>',
    caption: "We'll help you pay for it",
    description: 'Discover and book unique spaces around the world with <?php echo $this->dx_auth->get_site_title();?>. Join now and save <?php echo get_currency_symbol1().$trip;?> off your first trip of <?php echo get_currency_symbol1().$rent;?> or more!'
  },
  function(response) {
    
  }
);
      }
      
</script>

   <script type="text/javascript">
	$(document).ready(function(){
		$("#overlay_form").validate({
			debug: false,
			rules: {
				emails: {
          required: true,
          email: true
          }
			},
			messages: {
		        emails:
                    { 
                    	require: "You must enter the mail-id",
                    	email: "Please enter correct mail-id"
                    	
                  }
			},
		});
	});
	</script>
	<style>
	label.error { width: 250px; display: inline; color: red;}
	</style>
	
	<script>
	$(document).ready(function()
	{
		
		$('#pop1').click(function()
		{
			$('.action_bar_container').hide();
			$('.email_wrapper').show();
		})
		var counter = 3;
		$('.email_friend_add').click(function()
		{
			counter++;
			$('#textbox_groups').append('<input type="text" class="email_gray_text" id="email_id'+counter+'" name="email_id'+counter+'" placeholder="friend@example.com" />');
		$("input[id*=email_id"+counter+"]").each(function() {
    $(this).rules("add", {
        email: true,
        messages: {
            email: "Please enter a valid Email address"
        }
        });
        });
		})
		$('#back').click(function()
		{
			$('.email_wrapper').hide();
			$('.action_bar_container').show();
		})
	
		$('#email_form').submit(function()
{
	var k=0;
	  $('[name^="email_id"]').each(function () {
	  
	  	if($.trim($(this).val()).length == 0)
	  { 
	  	k++;
	  }
	  })
	  
	  if(counter == k)
	  	{
	  	$.validator.addClassRules("first_box", {
        required: true
        });
	  	}
	  	else
	  	{
	  		$.validator.addClassRules("first_box", {
        required: false
        });
	  	}
})
	})
$(document).ready(function(){
	                
    $("#email_form").validate();
    
    for(var i=1;i<=3;i++)
    {
    $("input[id*=email_id"+i+"]").each(function() {
    $(this).rules("add", {
        email: true,
        messages: {
            email: "Please enter a valid Email address"
        }
    });
});
}
});

	</script>
<body>
	<div class="container">
		
		<?php 
		$current = $this->session->userdata("locale_currency");
		if($current!=''){
		$total=get_currency_value2($currency,$current,$fixed_amt);
		}
		else{
			$default  = $this->Common_model->getTableData('currency', array('default'=>1))->row()->currency_code;
			$total=get_currency_value2($currency,$default,$fixed_amt);
		}
		if($type==1){
			$trip_amt0=$trip_amt;
			$rent_amt0=$rent_amt;
		$current = $this->session->userdata("locale_currency");
		if($current!=''){
		$trip=get_currency_value2($currency,$current,$trip_amt);
		$rent=get_currency_value2($currency,$current,$rent_amt);
		}
		else{
			$default  = $this->Common_model->getTableData('currency', array('default'=>1))->row()->currency_code;
		$trip=get_currency_value2($currency,$default,$trip_amt);
		$rent=get_currency_value2($currency,$default,$rent_amt);
		}
			
		}
		if($type==0){
			$trip=round((($trip_per)/100)*$total);
			$rent=round((($rent_per)/100)*$total);
		}
		$current = $this->session->userdata("locale_currency");	
	?>
<div class="container_referral referral">
    <h1 class="invite_friend"><?php echo translate('Invite Your Friends');?></h1>
    
    <div class="shelf_wrapper">
        <div class="offer_wrapper">
            <div class="user_left med_3 mal_5 med_offset-2 pe_12">
                <div class="offer_invite">
                	<img src="<?php echo base_url(); ?>images/invite_user.png "/>
                  
                    <div class="invite_user_label">
                        <span class="invite_label"><?php echo translate('Friend Uses');?> <?php echo $this->dx_auth->get_site_title();?></span>
                    </div>
                    <div class="invite_user_bottom"></div>
                </div>
            </div>
    
            <div class="equals med_1 mal_1 pe_12">
            	 <img src="<?php echo base_url(); ?>images/equal.png" />
            </div>
            <div class="coupon_wrapper med_4 mal_4 pe_12">
            	<div class="coupon">
                	
                	<div class="coupon_side_middle">
                    	<span class="coupon_amount"><?php echo get_currency_symbol1().$total;?></span>
                        <div class="travel_credit_tag"><?php echo translate('Travel Credit');?></div>
                    </div>
                	
                </div>
            </div>
           
		</div>
         <div class="big_self"></div>
    </div>
    <p class="referral_offer"><?php echo translate("You'll get");?> <?php echo get_currency_symbol1().$trip;?> <?php echo translate('when they take a trip &');?> <span><?php echo get_currency_symbol1().$rent;?> <?php echo translate('when they rent out their place.');?></span></p>
    <div class="action_bar_container">
    	<div class="action_bar">
        	<span class="fl_left"><?php echo translate('Get Started');?></span>
            <span class="sm_arrow fl_left"></span>
           <!-- <a class="invite_fb_blue" onclick="send_invitation('<?php echo base_url();?>users/signup?airef=<?php echo $referral_code;?>','Take a trip!','Discover and book unique spaces around the world with <?php echo $this->dx_auth->get_site_title();?>. Join now and save $25 off your first trip of $75 or more!', '<?php echo base_url(); ?>logo/logo.png', 520, 350)"></a>-->
           <a class="invite_fb_blue" onclick="send_invitation()"></a>
      <!-- <a class="invite_fb_blue" href="https://www.facebook.com/sharer.php?u=http://demo.cogzidel.com/dropinn&t=TEst"></a> -->    
            <span class="on_connect fl_left"><?php echo translate('or');?></span>

            <span class="invite_btn-green" id="pop1"></span>
        </div>
    </div>
	<div class="email_wrapper" style="display: none">
		<div class="share_email med_8 med_offset-2">
        	<div class="email_top">
        		   			<?php $logo         = $this->Common_model->getTableData('settings',array('code' => 'SITE_LOGO'))->row()->string_value; ?>

        		      <a href="<?php echo base_url(); ?>" class="navbar-brand invi_logo"> <img class="ref_logo-img" title="<?php echo $this->dx_auth->get_site_title(); ?>"  src="<?php echo base_url().'logo/'.$logo; ?>" width="137" height="45"> </a>
        	</div>
        	<div class="email_middle med_12 pe_12 mal_12">
            	<form class="referrals_email_form" id="email_form" action="<?php echo base_url().'home/fun_invite_mail'; ?>" method="post" autocomplete="on">
                	<div class="email_middle_form_inner med_7 mal_7 pe_12">
                    	<span class="email_explanation"><?php echo translate('To:');?></span><br/>
                    	<div id="textbox_groups">
                      <!--  <input type="text" class="email_gray_text first_box" id="email_id1" name="email_id1" placeholder="friend@example.com" />                       
                        <?php echo form_error('email_id1'); ?>
                        <input type="text" class="email_gray_text" id="email_id2" name="email_id2" placeholder="friend@example.com" />
                        <input type="text" class="email_gray_text" id="email_id3" name="email_id3" placeholder="friend@example.com" />-->
                        <input type="text" class="email_gray_text first_box" id="email_id1" name="email_id1" value="" placeholder="friend@example.com"/>                       
                        <?php echo form_error('email_id1'); ?>
                        <input type="text" class="email_gray_text" id="email_id2" name="email_id2" value="" placeholder="friend@example.com" />
                        <input type="text" class="email_gray_text" id="email_id3" name="email_id3" value="" placeholder="friend@example.com" />
                        </div>
                             <a class="email_friend_add"><?php echo translate('Add Another Friend');?></a>
                    </div>
                
                    <div class="email_middle_form_inner_right med_5 mal_5 pe_12">
                    	<span class="email_explanation"><?php echo translate('Message:');?></span>
                    	                    	
                        <textarea cols="49" rows="10" name="msg" class="email_textbox"><?php echo translate("I've been renting out my place on");?>' <?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate('to meet interesting people and earn extra cash. I highly recommend you join me by listing your space. Cheers!');?> <?php echo $username; ?>
                        </textarea><br/>
                       <div class="btn_dash submit"> <input type="submit" value="send invites" /></div>
                    </div>
                </form>
            </div>
            <div class="email_bottom med_12 mal_12 pe_12"></div>
            <div class="shadow_wrapper"></div>
        </div>
        <div class="back_button med_12 mal_12 pe_12">
    	<a id="back">&larr; <?php echo translate('Back');?></a>
    </div>
    </div>

</div>
    <div class="container_referral_ referral_shares">
    	<div class="rbs">
    	<label class="share_link_text"><?php echo translate('Share Link');?>:</label><input class="share_link_box" type="text" value="<?php echo base_url().'users/signup?airef='.$referral_code; ?>" readonly/>
        <i class="fbshare" onClick="fb_share();"></i>

       
  <a class="twshare" onClick="window.open (this.href, 'child', 'height=300,width=500'); return false" href="http://twitter.com/intent/tweet?text=I've been using <?php echo $this->dx_auth->get_site_title(); ?> and love it! Save <?php echo get_currency_symbol1().$trip;?> on your next trip if you sign up now: <?php echo base_url().'users/signup?airef='.$referral_code;; ?>&via=<?php echo $this->dx_auth->get_site_title(); ?>" target="_blank">
  </a>
        </div>
         
    </div>
  <!--  <div class="container_link">
    	<a href="#">Terms & Conditions</a>
  </div>-->
  
  <!----->
    <div>
 
	<?php		 
	  echo $refer_code;
	 $refer_code = $this->db->select('referral_code','refer_userid')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_code;

	 $refer_userid = $this->db->select('id')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->id;
	
	  $this->db->where('trips_referral_code',$refer_code)->or_where('list_referral_code',$refer_code)->or_where('refer_userid',$refer_userid);
      $query=$this->db->get('users');    
	  // echo $this->db->last_query();exit; 
	   $result=$query->result();
	 // print_r($query->result());
	 // exit;
	  
		
    
    if($refer_code != '')
	{
       $check_refer_code = $this->db->where('trips_referral_code',$refer_code)->or_where('list_referral_code',$refer_code)->get('users');
	   //print_r($check_refer_code);
	   if($check_refer_code->num_rows() == 0)
	   {
		   $refer_amount = $this->db->select('referral_amount')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
		   if($refer_amount == 0)
		   {		 
			
	     ?>
		<div class="Box_dash">
			<h2><?php echo translate("Invite your friends, earn");?> <?php echo get_currency_symbol1().$total;?>  <?php echo translate("travel credit!"); ?>
	            <a href="<?php echo base_url().'referrals';?>" class="btn_dash_green"><?php echo translate('Invite now');?></a>
            </h2>
		</div>
 

     <?php }

		else {
		$referral_amount = $this->db->select('referral_amount')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
		}
	   }
	   
	}
	   ?>	
		
         <!--  
             <div  id="email" class="email_name"><h2><?php echo $email  ?>  </div></h2>    
              <div class="stat_number"><h2><?php echo get_currency_symbol1().get_currency_value($referral_amount);?></div></h2>
          -->  
            <div id="possible" class="container_referral_" style="padding-bottom: 10px">
             
              <?php
              		           $referral_amount = $this->db->select('referral_amount')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
              
                        $trip_refer = $this->db->where('trips_referral_code',$refer_code)->get('users');
			   $trip_refering=$this->db->query("select `ref_trip` from `users` where `trips_referral_code`= '".$refer_code."'");
			 if($trip_refer->num_rows()!=0)
			  {
			  	$trip_amts=0;
				for($i=0;$i<$trip_refer->num_rows();$i++){
					$b= $trip_refering->row($i)->ref_trip;
					$trip_amts=$trip_amts+$b;
				
				}
			   	}
			  else{
			  	$trip_amts=0;	
				 }
			    $list_refer = $this->db->where('list_referral_code',$refer_code)->get('users');
			    $list_refering=$this->db->query("select `ref_rent` from `users` where `list_referral_code`= '".$refer_code."'");
			  if($list_refer->num_rows()!=0)
			  {
			 	$list_amt=0;
				for($i=0;$i<$list_refering->num_rows();$i++){
					$b= $list_refering->row($i)->ref_rent;
					$list_amt=$list_amt+$b;
				
				}
				 }
			  else{
			  	$list_amt=0;	
				 }
	// $total_refer = $this->db->where('list_referral_code',$refer_code)->get('users');
  $total_refering=$this->db->query("select `ref_total` from `users` where `refer_userid`= '".$this->dx_auth->get_user_id()."'");
			  if($total_refering->num_rows()!=0)
			  {
			 	$total_amt=0;
				for($i=0;$i<$total_refering->num_rows();$i++){
					$b= $total_refering->row($i)->ref_total;
					$total_amt=$total_amt+$b;
				
				}
				 }
			  else{
			  	$total_amt=0;	
				 }
              if(isset($trip_amts) && isset($list_amt))
              {
              	$final_amt = $trip_amts+$list_amt; 
              }
			  else if(!isset($trip_amts) && !isset($list_amt))
			  {
			  	 $final_amt = $final_ref_amt;
			  }
			  else if(isset($trip_amts) && !isset($list_amt))
              {
              	 $final_amt = $trip+0;
              }
			  else if(!isset($trip_amts) && isset($list_amt))
              {
              	$final_amt = 0+$list_amt;
              }
		
		 ?> 
		<h4 style="text-align:center"> You have <?php echo get_currency_symbol1().get_currency_value($referral_amount);?> travel credit to spend. </h4>
 
		 
	<h4 style="text-align: center">You have earned a total of <?php echo get_currency_symbol1().get_currency_value($referral_amount);?>,with<?php echo get_currency_symbol1().get_currency_value($final_amt);?> pending.Invite more friends to earn more credit</h4>
	 
	  <div  id="email" class="email_name"><h2><?php echo $email  ?>  </div></h2>    
	 
		<!--     check --->
             
          </div>
          
          <?php		 
	  
	 $refer_code = $this->db->select('referral_code','refer_userid')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_code;

	 $refer_userid = $this->db->select('id')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->id;
	
	 
	  $this->db->where('trips_referral_code',$refer_code)->or_where('list_referral_code',$refer_code)->or_where('refer_userid',$refer_userid);
      $query=$this->db->get('users');    
	  // echo $this->db->last_query();exit; 
	   $result=$query->result();
	 // print_r($query->result());
	 // exit;
	  
		foreach ($result as $res) {
				//print_r($res);
			$ID = $res->id;
			
			?>
			<br />
				<div class="container">	
					<label style="padding-top:-10px;">
			<?php
			if(!is_dir( realpath(APPPATH . '../images/users').'/'.$ID.'/'.$image))
      		{
				
				$value1 = base_url().'/images/no_avatar-xlarge.jpg';
				//echo $value1;
			}
			else {
				$value1 =  base_url('image/users/'. $ID)."images/users/".$ID."/userpic.jpg";
				//echo $value1;
			}
				?>
			
			  <img  width="50" height="50" style="border-radius: 50%;" src="<?php echo $value1;?>" /><?php
			
		     echo ucfirst($res->username);
		     
		     	?>
			  </label>
			  	 
		     
		     		<label position style="padding-top:10px;padding-left:500px;" class="pending_rate">
		     	<?php
			 
			  if(($res->trips_referral_code !== ''&& $res->list_referral_code == '') &&  $res->refer_userid)
			  {
			  
				 $trip_amount=get_currency_value($res->ref_trip);?> <?php 	
				  echo '<b style="padding-right:5px;color:green;">Pending</style></b>';
				 echo get_currency_symbol1().$trip_amount;
				 			  
			  } ?><label style="float:right;" class="pending_rate"><?php 
			   if(($res->trips_referral_code == ''&& $res->list_referral_code == '') &&  $res->refer_userid)
			  {
			  	// $completed_amount=0;?> <?php 
				 echo '<b style="padding-right:5px;color:green;">Completed</b>';
				 //echo get_currency_symbol1().get_currency_value($completed_amount);?><?php
							
			  }?><label style="float:right;" class="pending_rate"><?php
			  if(($res->trips_referral_code == ''&& $res->list_referral_code !== '') &&  $res->refer_userid)
			  {
			  	 
				 $list_Pending=get_currency_value($res->ref_rent);?><?php
				 echo '<b style="padding-right:5px;color:green">Pending</b>';
				 echo get_currency_symbol1().$list_Pending;
				
			
			  }?><label style="float:right;" class="pending_rate"><?php
			   if(($res->trips_referral_code !== ''&& $res->list_referral_code !== '') &&  $res->refer_userid)
			  {
			  	 $both_pending=get_currency_value($res->ref_total);?> <?php
			  	 echo '<b style="padding-right:5px;color:green">Pending</b>';
				 echo get_currency_symbol1().$both_pending;
				
			
			  }
		   
		  ?>
		 	 </label>
		 	 </label>
		 	 </label>
		 	 </label>
		 	 
		  </div>
	
		 
		  <br>
		      </div> 

		  <?php 
	       		
		} 
          
          ?>
          
          		<!-- check  --->
	
  <!--Reffer status end --->   
    
  <!--  <div class="container_link">
    	<a href="#">Terms & Conditions</a>
  </div>-->
<!---->
<div id="lightbox-shadow" style="display: block;"></div>
<body>
<br />
<form id="overlay_form" name="overlay_form" class="form" style="display:none" method="post" action='<?php echo base_url()."home/fun_invite_mail"; ?>'>
	<h4> <?php echo translate("Enter your friend's email id :"); ?></h4>
	<img src='<?php echo base_url()."images/close_pop.png"?>' alt='quit' class='close' id='close' />
	<div align="left">
		<table><tr><td valign="top">
	<label class='emails'><?php echo translate("Email"); ?><span class="star">*</span></label></td>
	<td><textarea name="emails" id="emails" rows="5" columns="20"></textarea></td></tr>
    <span id="email" style="color:red"></span><br /><br />
	<tr><td valign="top"><label class="message"><?php echo translate("Add your message"); ?> </label></td>
	<td><textarea name="message" rows="5" columns="20"></textarea></td></tr></table>
	<span id="message" style="color:red"></span><br /><br />
	<center><input type="submit" value="Send" class="btn blue gotomsg" name="submit" id="submit"/></center>
	</div>
</form>
<div id="overlay_form_fb" class="overlay_form_fb" style="display:none">
	</div> </div>
	 
</body>
</html>
