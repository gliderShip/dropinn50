<!--  Required external style sheets -->
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!--<link href="<?php echo css_url().'/popup.css'; ?>" media="screen" rel="stylesheet" type="text/css" />-->

<script> 
function is_read(id)
{
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/is_read'); ?>",
					async: true,
					data: "message_id="+id
		  });
}

</script>
<!-- End of style sheet inclusion -->
<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); 
$this->load->helper('text');
$refer=$this->db->query("select * from `referral_management` where `id`=1 ")->row();
		//$data['fixed_status']=$refer->fixed_status;
		$refamt=$refer->fixed_amt;
		$refcur=$refer->currency;
		$type=$refer->type;
		$trip_amt=$refer->trip_amt;
		$trip_per=$refer->trip_per;
		$rent_amt=$refer->rent_amt;
		$rent_per=$refer->rent_per;
		$ref_total=get_currency_value($refamt);
		if($type==1){
			$trip_amt0=$trip_amt;
			$rent_amt0=$rent_amt;
		$trip=get_currency_value($trip_amt);
		$rent=get_currency_value($rent_amt);
		}
		if($type==0){
			$trip=(($trip_per)/100)*$ref_total;
			$rent=(($rent_per)/100)*$ref_total;
		$current = $this->session->userdata("locale_currency");
			
		}
?>

<div id="dashboard_container" class="clearfix">
  <div id="dashboard_left" class="med_3 mal_3 pe_12 padding-zero">
    <div class="Box" id="das_user_box">
      <div class="Box_Content">
        <div id="user_pic" onClick="show_ajax_image_box();"> <img id="trigger_id" alt="" src="<?php 
       /*    if($this->session->userdata('image_url') != '')
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
		   else {*/
			  
		  	 echo $this->Gallery->profilepic($this->dx_auth->get_user_id(),2);
			   
		//   } ?>" title=""  /> </div>
        <h1>  <?php 
		 echo $name; ?>  </h1>
		<center><h3><span class="edit_label"><?php echo anchor('users/profile/'.$this->dx_auth->get_user_id(), translate("View Profile")); ?></span></h3>
        <h3><span class="edit_label"><?php echo anchor('users/edit', translate("Edit Profile")); ?></span></h3></center>
        <!-- middle -->
      </div>
    </div>
    <!-- /user -->
    <div class="Box" id="quick_links">
      <div class="Box_Head msgbg">
        <h2 class="msg_head_dash"><?php echo translate("Quick Links");?></h2>
      </div>
      <div class="Box_Content">
        <ul>
          <li><a href=<?php echo base_url().'listings'; ?>> <?php echo translate("View/Edit Listings"); ?></a></li>
          <li><a href="<?php echo site_url('listings/my_reservation'); ?>"><?php echo translate("Reservations"); ?></a></li>
        </ul>
      </div>
      <div style="clear:both"></div>
    </div>
    <style>
    .list1
    {
    	color: #999999;
    	 font-size: 12px;
    font-weight: bold;
    }
    </style>
    <?php
if($verification->facebook_verify == 'yes' || $verification->google_verify == 'yes' || $verification->email_verify == 'yes' || $verification->phone_verify == 'yes')
	{
    ?>
     <div class="Box" id="quick_links">
      <div class="Box_Head msgbg">
        <h2 class="mybox-header"><?php echo translate("Verifications");?> </h2>
        <a class="add_more add_more_dash" href="<?php echo base_url().'users/verify'?>"><?php echo translate("Add more"); ?></a></div>
      
      <div class="Box_Content">
        <ul class="verification_list">
        	<?php 
        	if($verification->facebook_verify == 'yes')
			{
				$url = 'https://graph.facebook.com/fql?q=SELECT%20friend_count%20FROM%20user%20WHERE%20uid%20='.$verification->fb_id;
                            $json = @file_get_contents($url);
               $count = json_decode($json, TRUE);	
                        
        	?>
          <li class="verifications-list-item" ><b><?php echo translate("Facebook");
		   ?><span style="padding:4px 6px 0px 0px;">
		   	<!--<img src="<?php echo base_url();?>images/follow-us-facebook-plus.png" />-->
		   	<i class="icon-gray fa fa-facebook"></i></span></b>
		  <?php foreach($count['data'] as $row)
                        { 
                            echo '<p class="list1">'.$row["friend_count"].' '.'Friends</p>';
                        }?></li>
          
          <?php
			}
			?>
         <?php 
        	if($verification->google_verify == 'yes')
			{
        	?>
          <li class="verifications-list-item"><b><?php echo translate("Google"); ?><span>
          	<!--<img src="<?php echo base_url();?>images/follow-us-google-plus.png" />-->
          	<i class="icon-gray fa fa-google-plus"></i>
          	</span></b><p class="list1">Verified</p></li>
          
          <?php
			}
			?>
			<?php 
        	if($verification->email_verify == 'yes')
			{
        	?>
          <li class="verifications-list-item"><b><?php echo translate("Email"); ?><span>
          	<i class="icon-gray fa fa-envelope-o"></i>
          	</span></b><p class="list1">Verified</p></li>
          
          <?php
			}
			?>
			<?php 
        	if($verification->phone_verify == 'yes')
			{
        	?>
          <li class="verifications-list-item"><b><?php echo translate("Phone Number"); ?><span></span></b><p class="" style="color: #82888a;">▒▒▒▒▒▒▒▒▒▒ <?php echo $phone_number;?></p></li>
          
          <?php
			}
			?>
			
        </ul>
      </div>
      <div style="clear:both"></div>
    </div>
    <?php } ?>
  </div>
  <!-- /left -->
  <div id="dashboard_main" class="med_9 mal_9 pe_12">
    <div class="Box" id="welcome_msg_box">
      <div class="Box_Content">
        <h3><?php echo translate("Welcome to")." ".$this->dx_auth->get_site_title()."!"; ?></h3>
        <p><?php echo translate("This is your Dashboard, the place to manage your rental. Update all your personal information from here..");?></p>
      </div>
    </div>
    <?php
    if($verification->email_verify != 'yes' || $payout->num_rows() == 0)
	{
    ?>
    <div class="Box" id="alerts">
            <div class="middle_alert">
              <h3>Alerts</h3>
              <ul class="unstyled_alert">
     <?php
    if($payout->num_rows() == 0)
	{
    ?>
                  <li class="default_alert">
                  	
                    <a href="<?php echo base_url();?>account/payout" class="dashboard_alert_link">
                     <p><?php echo translate("Please tell us how to pay you.")  ?></p>
                      <!-- <img width="12" height="11" src="<?php echo base_url();?>images/alert_right_arrow.png" alt=""> -->
                    </a>

                  </li>
                  <?php
	}
if($verification->email_verify != 'yes')
{
     ?>
                  <li class="default_alert">
   <p><?php echo translate("Please confirm your email address by clicking on the link we just emailed you. If you cannot find the email, you can");?>
                   	<a href="<?php echo base_url().'users/email_verify?email=verify';?>">
                   		<?php echo translate("request a new confirmation email") ?></a> or 
                   		<a href="<?php echo base_url();?>users/edit"><?php echo translate("change your email address.")?></a>

                  </li>
<?php
}
?>
              </ul>
            </div>
          </div>
          <?php
	  }
          ?>
    <div class="Box" id="Dash_Msg_Small">
      <div class="Box_Head msgbg">
        <h2 class="msg_link_head"><?php echo translate("Messages")." "."(".$new_notify_rows ; echo " ".translate("new").")"; ?> </h2>
      </div>
      <div class="Box_Content">
        <div id="Msg_Inbox_Small">
          <ul>
            <?php
             if($new_notify->num_rows() > 0) 
             {
             foreach($new_notify->result() as $row) {
	     	 if($row->contact_id != 0)
			 {
			 $checkin=$this->Common_model->getTableData('contacts',array('id' => $row->contact_id))->row()->checkin;  
             $checkout=$this->Common_model->getTableData('contacts',array('id' => $row->contact_id))->row()->checkout; 
			 $checkin = strtotime($checkin);
			 $checkout = strtotime($checkout);
			 $topay = '';
             }
			 else if($row->reservation_id != 0)
			 {				
			 $checkin_res=$this->Common_model->getTableData('reservation',array('id' => $row->reservation_id));  
			 if($checkin_res->num_rows()!=0)
			 {
			 $checkin=$this->Common_model->getTableData('reservation',array('id' => $row->reservation_id))->row()->checkin;
             $checkin=$checkin;
             $checkout=$this->Common_model->getTableData('reservation',array('id' => $row->reservation_id))->row()->checkout;
			 $checkout=$checkout; 
			 $topay = $this->Common_model->getTableData('reservation',array('id' => $row->reservation_id))->row()->topay; 
			 $paid = $this->Common_model->getTableData('reservation',array('id' => $row->reservation_id))->row()->is_payed; 
			 $hostpaid = $this->Common_model->getTableData('reservation',array('id' => $row->reservation_id))->row()->is_payed_host;
			 $guestpaid = $this->Common_model->getTableData('reservation',array('id' => $row->reservation_id))->row()->is_payed_guest; 
			 $currency = $this->Common_model->getTableData('reservation',array('id' => $row->reservation_id))->row()->currency; 
			 }
			 }
else {
	$topay = '';
}
			 $list_staus = $this->Common_model->getTableData('list',array('id'=>$row->list_id));
			 $message_id = $this->db->where('userby',$row->userby)->where('userto',$row->userto)->order_by('conversation_id','desc')->get('messages')->row()->conversation_id;
			 	
             ?>
            <li class="clearfix" <?php if($row->is_read == 0) echo 'style="background:#ececec;border-bottom:1px solid #dbdbdb"'; ?>>
              <div class="clsMsg_User clsFloatLeft med_3 mal_2 pe_12"> <a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($row->userby,2); ?>" /></a>
                <p><a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a> <br />
                  <!--31 minutes-->
                </p>
              </div>
              <div class="clsMeg_Detail clsFloatLeft med_6 mal_5 pe_12 padding-zero">
                
														<?php	
															if($row->conversation_id != 0) 
															{
																$message_id = $row->conversation_id; 
																$reservation_id = $row->reservation_id;
															}
																else
																{
																	$message_id = $message_id;
																	$reservation_id = $row->reservation_id;
																}
															if($message_id == 0) 
															{
																$message_id = $row->contact_id;
															}
															if($row->message_type == 6)	{ 
															
															$subject = 'Inquiry about '.substr(get_list_by_id($row->list_id)->title,0,17);
																		if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$message_id, $subject, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; 
																																				
															} 
															else if($row->message_type == 2)	{ 
									
															$subject = 'Discuss about '.substr(get_list_by_id($row->list_id)->title,0,10);
														    if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$message_id, $subject, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; 
																														
																											}
															else if($row->message_type == 9)
															{
																if($row->is_read == 0) echo '<strong>'; echo anchor(''.'trips/conversation/'.$message_id, $row->message, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>';
															}
															else if($row->message_type == 1) {      
																																																																																																																									
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$reservation_id, $row->message, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
															<p><?php echo substr(get_list_by_id($row->list_id)->title,0,10); ?></span> <span>(<?php echo get_user_times($checkin, get_user_timezone()).' - '.get_user_times($checkout, get_user_timezone()) ?>)</p>
											</div>
													<?php }	
															else if($row->message_type == 4 || $row->message_type == 5) {      
																																																																																																																									
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$reservation_id, $row->message, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
															<p><?php echo substr(get_list_by_id($row->list_id)->title,0,10); ?></span> <span>(<?php echo get_user_times($checkin, get_user_timezone()).' - '.get_user_times($checkout, get_user_timezone()); ?>)</p>
											</div>
													<?php }		
															else if($row->message_type == 10) {      
																																																																																																																							
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$row->list_id, word_limiter($row->message,10), array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
															<p><?php if(isset(get_list_by_id($row->list_id)->title)) echo substr(get_list_by_id($row->list_id)->title,0,10); ?></p>
											</div>
											
											<!-- List edit  --->
											
													<?php }		
											
											else if($row->message_type == 11) {      
																																																																																																																							
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$row->list_id, word_limiter($row->message,11), array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
															<p><?php if(isset(get_list_by_id($row->list_id)->title)) echo substr(get_list_by_id($row->list_id)->title,0,10); ?></p>
											</div>
											
											<!-- List edit  --->
											
											
											
											
													<?php }		
															else {      
																if($row->contact_id != 0 && $row->url == 'contacts/response' || $row->url == 'contacts/request') 
																{
																$message_id = $row->contact_id;	
																}
																																																																																																											
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$message_id, $row->message, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
															<p><?php echo substr(get_list_by_id($row->list_id)->title,0,10); ?></span> <span>(<?php echo get_user_times($checkin, get_user_timezone()).' - '.get_user_times($checkout, get_user_timezone()) ?>)</p>
											</div>
													<?php }
															if($row->message_type != 9 || $row->message_type != 10)
															{
													 ?>
																
              <div class="clsMeg_Off clsFloatRight  med_3 mal_3 colxs-12 padding-zero">
                <p> <span><?php 
				if($list_staus->num_rows() == 0)
				echo 'List Deletion';
				else
                echo $row->name; 
                
                ?></span> 
																  <?php
																   if(isset($topay) && $topay != '' && $paid != 1) {
																   	if($hostpaid != 1 && $guestpaid != 1)
																	{
																   //	$topay = get_currency_value2($currency,$topay);
																   	?>
																		<br>
                  <span><?php echo get_currency_symbol($row->list_id).get_currency_value_lys($currency,get_currency_code(),$topay); ?></span> 
																		<?php } 
																		}?>
																		</p>
              </div>
              <?php } ?>
            </li>
            <?php } } else { ?>
            <li class="clearfix"> <?php echo translate("Nothing to show you."); ?> </li>
            <?php } ?>
          </ul>
          <p class="Txt_Right_Align"><a class="btn_dash" href="<?php echo site_url('message/inbox'); ?>"><?php echo translate("Go to all messages"); ?></a></p>
          <div style="clear:both"></div>
        </div>
      </div>
    </div>
    <?php 
    $refer_code = $this->db->select('referral_code')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_code;
	if($refer_code != '')
	{
    $check_refer_code = $this->db->where('trips_referral_code',$refer_code)->or_where('list_referral_code',$refer_code)->get('users');
	if($check_refer_code->num_rows() == 0)
	{
		$refer_amount = $this->db->select('referral_amount')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
		if($refer_amount == 0)
		{
    ?>
		<div class="Box_dash">
			<h2><?php echo translate("Invite your friends, earn");?> <?php echo get_currency_symbol1().get_currency_value($ref_total);?>  <?php echo translate("travel credit!"); ?>
	            <a href="<?php echo base_url().'referrals';?>" class="btn_dash_green"><?php echo translate('Invite now');?></a>
            </h2>
		</div>
<?php }
		else {
		$referral_amount = $this->db->select('referral_amount')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
		 ?>
        <div id="share" class="rounded_more silver_box">
          <div id="title_box">
            <h2><?php echo translate('Referrals');?></h2>
          </div>
          <div id="stats" class="clearfix">
            <div id="earned" class="stat_box">
              <span class="stat_title"><?php echo translate('Travel Credit Available');?></span>
              <div class="stat_number"><?php echo get_currency_symbol1().get_currency_value($referral_amount);?></div>
            </div>
            <div id="possible" class="stat_box">
              <span class="stat_title"><?php echo translate('Travel Credit Possible');?></span>
              <?php
               $trip_refer = $this->db->where('trips_referral_code',$refer_code)->get('users');
			   $trip_refering=$this->db->query("select `ref_trip` from `users` where `trips_referral_code`= '".$refer_code."'");
			 if($trip_refer->num_rows()!=0)
			  {
			  	$trip_amt=0;
				for($i=0;$i<$trip_refer->num_rows();$i++){
					$b= $trip_refering->row($i)->ref_trip;
					$trip_amt=$trip_amt+$b;
				
				}
			   	}
			  else{
			  	$trip_amt=0;	
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
			  $final_ref_amt = $total_amt;
              
              if(isset($trip_amt) && isset($list_amt))
              {
              	$final_amt = $trip_amt+$list_amt;
              }
			  else if(!isset($trip_amt) && !isset($list_amt))
			  {
			  	$final_amt = $final_ref_amt;
			  }
			  else if(isset($trip_amt) && !isset($list_amt))
              {
              	$final_amt = $trip_amt+0;
              }
			  else if(!isset($trip_amt) && isset($list_amt))
              {
              	$final_amt = 0+$list_amt;
              }
              ?>
              <div class="stat_number"><?php echo get_currency_symbol1().get_currency_value($final_amt);?></div>
            </div>
            <div class="stat_box" id="invite_box">
              <a href="<?php echo base_url().'referrals';?>" id="invite_more" class="btn green large">
                <?php echo translate('Invite More');?>
              </a>
            </div>
          </div>
        
    <div id="blast_box">
      <label class="share_link_text"><?php echo translate('Share Link');?>:</label>
      <input id="unique_link" class="share_link_box" value="<?php echo base_url().'users/signup?airef='.$referral_code; ?>" readonly="true">
        <i class="fbshare" onClick="fb_share();"></i>
        <a class="twshare" onClick="window.open (this.href, 'child', 'height=300,width=500'); return false" href="http://twitter.com/intent/tweet?text=I've been using <?php echo $this->dx_auth->get_site_title(); ?> and love it! Save $25 on your next trip if you sign up now: <?php echo base_url().'users/signup?airef='.$referral_code;; ?>&via=<?php echo $this->dx_auth->get_site_title(); ?>" target="_blank">
  </a>
    </div>
		</div>
    <?php } 
	}
	else {
		$referral_amount = $this->db->select('referral_amount')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
		 ?>
        <div id="share" class="rounded_more silver_box">
          <div id="title_box">
            <h2><?php echo translate('Referrals');?></h2>
          </div>
          <div id="stats" class="clearfix">
            <div id="earned" class="stat_box">
              <span class="stat_title"><?php echo translate('Travel Credit Available');?></span>
              <div class="stat_number"><?php echo get_currency_symbol1().get_currency_value($referral_amount);?></div>
            </div>
            <div id="possible" class="stat_box">
              <span class="stat_title"><?php echo translate('Travel Credit Possible');?></span>
              <?php
              $trip_refer = $this->db->where('trips_referral_code',$refer_code)->get('users');
			  if($trip_refer->num_rows()!=0)
			  {
			  	$trip_amt = $trip*$trip_refer->num_rows();
			  }
			   $list_refer = $this->db->where('list_referral_code',$refer_code)->get('users');
			  if($list_refer->num_rows()!=0)
			  {
			  	$list_amt = $rent*$list_refer->num_rows();
			  }
			  $final_ref_amt = $check_refer_code->num_rows()*$ref_total;
              
              if(isset($trip_amt) && isset($list_amt))
              {
              	$final_amt = $trip_amt+$list_amt;
              }
			  else if(!isset($trip_amt) && !isset($list_amt))
			  {
			  	$final_amt = $final_ref_amt;
			  }
			  else if(isset($trip_amt) && !isset($list_amt))
              {
              	$final_amt = $trip_amt+0;
              }
			  else if(!isset($trip_amt) && isset($list_amt))
              {
              	$final_amt = 0+$list_amt;
              }
              ?>
              <div class="stat_number"><?php echo get_currency_symbol1().get_currency_value($final_amt);?></div>
            </div>
            <div class="stat_box" id="invite_box">
              <a href="<?php echo base_url().'referrals';?>" id="invite_more" class="btn green large">
               <?php echo translate('Invite More');?>
              </a>
            </div>
          </div>
        
    <div id="blast_box">
      <label class="share_link_text"><?php echo translate('Share Link');?>:</label>
      <input id="unique_link" class="share_link_box" value="<?php echo base_url().'users/signup?airef='.$referral_code; ?>" readonly="true">
        <i class="fbshare" onClick="fb_share();"></i>
        <a class="twshare" onClick="window.open (this.href, 'child', 'height=300,width=500'); return false" href="http://twitter.com/intent/tweet?text=I've been using <?php echo $this->dx_auth->get_site_title(); ?> and love it! Save $25 on your next trip if you sign up now: <?php echo base_url().'users/signup?airef='.$referral_code;; ?> via <?php echo $this->dx_auth->get_site_title(); ?>" target="_blank">
  </a>
    </div>
		</div>
    <?php } 
    }
else {
	?>
	<div class="Box_dash">
			<h2><?php echo translate("Invite your friends, earn");?> <?php echo get_currency_symbol1().get_currency_value($ref_total);?>  <?php echo translate("travel credit!"); ?>
	            <a href="<?php echo base_url().'referrals';?>" class="invite_now_green">Invite now</a>
            </h2>
		</div>
	<?php
}
    ?>
		</div>
	</div> 
</body>
<script>
FB.init({ 
       appId:'<?php echo $fb_app_id; ?>', 
       frictionlessRequests: true
     });
function fb_share()
      {
      	FB.ui(
  {
    method: 'feed',
    name: 'Take a trip!',
    link: '<?php echo base_url()."users/signup?airef=".$referral_code;?>',
    picture: '<?php echo base_url()."logo/logo.png";?>',
    caption: "We'll help you pay for it",
    description: 'Discover and book unique spaces around the world with <?php echo $this->dx_auth->get_site_title();?>. Join now and save $25 off your first trip of $75 or more!'
  },
  function(response) {
    
  }
);
      }
      
	</script>