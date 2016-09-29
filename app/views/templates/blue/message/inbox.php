<!--<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
-->
<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>
<!--
<script>
	$(document).ready(function()
	{
		$('#filter').change(function()
		{
			window.location.href='<?php echo base_url(); ?>message/inbox?type='+$(this).val();
		})
	})
</script> -->
<style>
.Box_Head.msgbg > select {
    /*margin-left: 11px;
    margin-top: 7px;*/
}
</style>
<!--<div id="dashboard_container">
<div class="Box" id="Msg_Inbox_Big">
    <div class="Box_Head msgbg all_msg">
<select name="filter" id="filter">
<option value="all" <?php if($type == 'all') echo 'selected';?>> <?php echo translate("All Messages"); ?> (<?php echo $all_count; ?>)</option>
<option value="starred" <?php if($type == 'starred') echo 'selected';?>><?php echo translate("Starred");?> (<?php echo $starred_count; ?>)</option>
<option value="unread" <?php if($type == 'unread') echo 'selected';?>><?php echo translate("Unread"); ?> (<?php echo $unread_count; ?>)</option>
<option value="never_responded" <?php if($type == 'never_responded') echo 'selected';?>><?php echo translate("Never Responded");?> (<?php echo $respond_count; ?>)</option>
<option value="reservations" <?php if($type == 'reservations') echo 'selected';?>><?php echo translate("Reservations");?> (<?php echo $reservations_count; ?>)</option>
<option value="hidden" <?php if($type == 'hidden') echo 'selected';?>><?php echo translate("Archived");?> (<?php echo $hidden_count;?>)</option>
	
</select>
     </div>
      <div class="Box_Content">
			<div id="message" style="padding:0 0 10px 0;"></div>
            <ul>
			 <?php
$this->load->helper('text');
																 if($messages->num_rows() > 0) 
																 {
																		foreach($messages->result() as $row) { //print_r($row);
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
<li class="clearfix med_12" <?php if($row->is_read == 0) echo 'style="background:#ececec;  color:#00B0FF"'; else echo 'style="background:#FFFFD0; background-color: white; color:#00B0FF"';  ?> >
                    	<div class="clsMsg_User clsFloatLeft med_2 mal_2 pe_12">
                        	<a class="med_6 mal_12 padding-zero" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($row->userby,2); ?>" /></a>
                            <p class="med_6 pe_6 mal_9"><a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a> <br />
                            <!--31 minutes-</p>
                        </div>
                        <div class="clsMeg_Detail clsFloatLeft med_5 mal_6 pe_12">
                            
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
													<?php }	else if($row->message_type == 4 || $row->message_type == 5) {      
																																																																																																																									
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$reservation_id, $row->message, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
															<p><?php echo substr(get_list_by_id($row->list_id)->title,0,10); ?></span> <span>(<?php echo get_user_times($checkin, get_user_timezone()).' - '.get_user_times($checkout, get_user_timezone()) ?>)</p>
											</div>
													<?php }		
													else if($row->message_type == 10) {      
																																																																																																																							
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$row->list_id, word_limiter($row->message,17), array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
											                <p><?php if(isset(get_list_by_id($row->list_id)->title)) echo substr(get_list_by_id($row->list_id)->title,0,10); ?></p>
											</div>
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
																
              <div class="clsMeg_Off clsFloatRight med_2 pe_6 mal_2">
                <p> <span><?php 				
				if($list_staus->num_rows() == 0)
				{
				
	           echo 'List Deletion';
				}
				else
               echo translate($row->name); 
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
																   } ?>
																		</p>
              </div>
              <?php } ?>
																								
							<div class="clsMsg_Del clsFloatLeft med_3 mal_2 pe_6 ">
																								<?php if($row->is_starred == 0) $class = "clsMsgDel_Unfil"; else $class = "clsMsgDel_fil"; ?>
                    	     <p class="clearfix">
																										<span><a class="<?php echo $class; ?>" id="starred_<?php echo $row->id; ?>" href="javascript:void(0);" onclick="javascript:starred('<?php echo $row->id; ?>');"></a></span>
																										<span><a onclick="javascript:deleted('<?php echo $row->id; ?>');" href="javascript:void(0);" id="delete_<?php echo $row->id; ?>"><?php echo translate("Delete"); ?></a></span>
																										<?php if($row->is_archived == 0)
																										{ ?>
																										<span><a onclick="javascript:archived('<?php echo $row->id; ?>');" href="javascript:void(0);" id="archive_<?php echo $row->id; ?>"><?php echo translate("Archive"); ?></a></span>
																										<?php }
																										else
																											{ ?>
																										<span><a onclick="javascript:unarchived('<?php echo $row->id; ?>');" href="javascript:void(0);" id="archive_<?php echo $row->id; ?>"><?php echo translate("Unarchive"); ?></a></span>		
																										<?php } ?>
																										</p>
                        </div>
                    </li>
																		
															<?php } } else { ?>
															
																		<li class="clearfix">
																					<?php echo translate("Nothing to show you."); ?>
																		</li> 
																					
															<?php } ?>
																
            </ul>
            
            <div style="clear:both"></div>
       </div>
    
  </div>
</div>  
<script type="text/javascript">

function starred(id)
{

var className = $('#starred_'+id).attr('class');

	if(className == 'clsMsgDel_Unfil')
	{
	$("#starred_"+id).removeClass("clsMsgDel_fil").addClass("clsMsgDel_fil");
	var to_change = 1;
	}
	else
	{
	$("#starred_"+id).removeClass("clsMsgDel_fil").addClass("clsMsgDel_Unfil");
	var to_change = 0;
	}
	
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/starred'); ?>",
					async: true,
					data: "message_id="+id+"&to_change="+to_change,
					success: function(data)
		  	{	
		  		window.location.href="<?php echo current_url();?>";
					$("#message").html(data);
					$("#message").show();
					$("#message").delay(1000).fadeOut('slow');
			 	}
		  });
}

function deleted(id)
{
  var ok=confirm("Are you sure to delete the message?");
		if(!ok)
		{
			return false;
		}
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/delete'); ?>",
					async: true,
					data: "message_id="+id,
					success: function(data)
		  	{	
					$("#message").html("<?php echo translate('Message deleted successfully.');?>");
					$("#message").show();
					$("#message").delay(1000).fadeOut('slow');
					
					$("#messages_list").html(data);
			 	location.reload(); 
				}
		  });
}

function archived(id)
{
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/archive'); ?>",
					async: true,
					data: "message_id="+id,
					success: function(data)
		  	{	
					$("#message").html("<?php echo translate('Message archived successfully.');?>");
					$("#message").show();
					$("#message").delay(1000).fadeOut('slow');
					
					$("#messages_list").html(data);
			 	location.reload(); 
				}
		  });
}

function unarchived(id)
{
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/unarchive'); ?>",
					async: true,
					data: "message_id="+id,
					success: function(data)
		  	{	
					$("#message").html("<?php echo translate('Message unarchived successfully.');?>");
					$("#message").show();
					$("#message").delay(1000).fadeOut('slow');
					
					$("#messages_list").html(data);
			 	location.reload(); 
				}
		  });
}

function is_read(id)
{
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/is_read'); ?>",
					async: true,
					data: "message_id="+id
		  });
}

</script>-->
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!--<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>-->

<script>
	$(document).ready(function()
	{
		$('#filter').change(function()
		{
			window.location.href='<?php echo base_url(); ?>message/inbox?type='+$(this).val();
		})
	})
</script>
<style>
.Box_Head.msgbg > select {
    margin-left: 11px;
    margin-top: 7px;
}
</style>
<div id="dashboard_container">
<div class="Box" id="Msg_Inbox_Big">
    <div class="Box_Head msgbg">
<select name="filter" id="filter">
<option value="all" <?php if($type == 'all') echo 'selected';?>> <?php echo translate("All Messages"); ?> (<?php echo $all_count; ?>)</option>
<option value="starred" <?php if($type == 'starred') echo 'selected';?>><?php echo translate("Starred");?> (<?php echo $starred_count; ?>)</option>
<option value="unread" <?php if($type == 'unread') echo 'selected';?>><?php echo translate("Unread"); ?> (<?php echo $unread_count; ?>)</option>
<option value="never_responded" <?php if($type == 'never_responded') echo 'selected';?>><?php echo translate("Never Responded");?> (<?php echo $respond_count; ?>)</option>
<option value="reservations" <?php if($type == 'reservations') echo 'selected';?>><?php echo translate("Reservations");?> (<?php echo $reservations_count; ?>)</option>
<option value="hidden" <?php if($type == 'hidden') echo 'selected';?>><?php echo translate("Archived");?> (<?php echo $hidden_count;?>)</option>
	
</select>
     </div>
      <div class="Box_Content">
			<div id="message" style="padding:10px"></div>
            <ul>
			 <?php
$this->load->helper('text');
																 if($messages->num_rows() > 0) 
																 {
																		foreach($messages->result() as $row) { //print_r($row);
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
																		 	<li class="clearfix" <?php if($row->is_read == 0) echo 'style="background:#ececec; color:#00B0FF"'; else echo 'style="background:#FFFFD0; background-color: white; color:#00B0FF"';  ?> >
                    	<div class="clsMsg_User clsFloatLeft med_2 mal_2 pe_12">
                        	<a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($row->userby,2); ?>" /></a>
                            <p><a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a> <br />
                            <!--31 minutes--></p>
                        </div>                   
                        <div class="clsMeg_Detail clsFloatLeft med_5 mal_6 pe_12">
                            
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
															else if($row->message_type == 2)
														{ 
									
															$subject = 'Discuss about '.substr(get_list_by_id($row->list_id)->title,0,10);
														    if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$message_id, $subject, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
																							
																											
																						</div>								
																							<?php				}
														
															else if($row->message_type == 9)
															{
																if($row->is_read == 0) echo '<strong>'; echo anchor(''.'trips/conversation/'.$message_id, $row->message, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>';
															}
															else if($row->message_type == 1) {      
																																																																																																																									
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$reservation_id, $row->message, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
															<p><?php echo substr(get_list_by_id($row->list_id)->title,0,10); ?></span> <span>(<?php echo get_user_times($checkin, get_user_timezone()).' - '.get_user_times($checkout, get_user_timezone()) ?>)</p>
											</div>
													<?php }	else if($row->message_type == 4 || $row->message_type == 5) {      
																																																																																																																									
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$reservation_id, $row->message, array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
															<p><?php echo substr(get_list_by_id($row->list_id)->title,0,10); ?></span> <span>(<?php echo get_user_times($checkin, get_user_timezone()).' - '.get_user_times($checkout, get_user_timezone()) ?>)</p>
											</div>
													<?php }		
													else if($row->message_type == 10) {      
																																																																																																																							
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$row->list_id, word_limiter($row->message,17), array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
											                <p><?php if(isset(get_list_by_id($row->list_id)->title)) echo substr(get_list_by_id($row->list_id)->title,0,10); ?></p>
											</div>
													<?php }
													else if($row->message_type == 11) {      
																																																																																																																							
															if($row->is_read == 0) echo '<strong>'; echo anchor(''.$row->url.'/'.$row->list_id, word_limiter($row->message,17), array("onmousedown" => "javascript:is_read(".$row->id.")")); if($row->is_read == 0) echo '</strong>'; ?>
											                <p><?php if(isset(get_list_by_id($row->list_id)->title)) echo substr(get_list_by_id($row->list_id)->title,0,10); ?></p>
											</div>
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
																
              <div class="clsMeg_Off clsFloatRight med_2 pe_6 mal_2">
                <p> <span><?php 				
				if($list_staus->num_rows() == 0)
				{
				
	           echo 'List Deletion';
				}
				else
               echo translate($row->name); 
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
																   } ?>
																		</p>
              </div>
              <?php } ?>
																								
																								<div class="clsMsg_Del clsFloatLeft med_3 mal_2 pe_6">
																								<?php if($row->is_starred == 0) $class = "clsMsgDel_Unfil"; else $class = "clsMsgDel_fil"; ?>
                    	     <p class="clearfix">
																										<span><a class="<?php echo $class; ?>" id="starred_<?php echo $row->id; ?>" href="javascript:void(0);" onclick="javascript:starred('<?php echo $row->id; ?>');"></a></span>
																										<span><a onclick="javascript:deleted('<?php echo $row->id; ?>');" href="javascript:void(0);" id="delete_<?php echo $row->id; ?>"><?php echo translate("Delete"); ?></a></span>
																										<?php if($row->is_archived == 0)
																										{ ?>
																										<span><a onclick="javascript:archived('<?php echo $row->id; ?>');" href="javascript:void(0);" id="archive_<?php echo $row->id; ?>"><?php echo translate("Archive"); ?></a></span>
																										<?php }
																										else
																											{ ?>
																										<span><a onclick="javascript:unarchived('<?php echo $row->id; ?>');" href="javascript:void(0);" id="archive_<?php echo $row->id; ?>"><?php echo translate("Unarchive"); ?></a></span>		
																										<?php } ?>
																										</p>
                        </div>
                    </li>
																		
															<?php } } else { ?>
															
																		<li class="clearfix">
																					<?php echo translate("Nothing to show you."); ?>
																		</li> 
																					
															<?php } ?>
																
            </ul>
            
            <div style="clear:both"></div>
       </div>
    
  </div>
</div>  
<script type="text/javascript">

function starred(id)
{

var className = $('#starred_'+id).attr('class');

	if(className == 'clsMsgDel_Unfil')
	{
	$("#starred_"+id).removeClass("clsMsgDel_fil").addClass("clsMsgDel_fil");
	var to_change = 1;
	}
	else
	{
	$("#starred_"+id).removeClass("clsMsgDel_fil").addClass("clsMsgDel_Unfil");
	var to_change = 0;
	}
	
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/starred'); ?>",
					async: true,
					data: "message_id="+id+"&to_change="+to_change,
					success: function(data)
		  	{	
		  		window.location.href="<?php echo current_url();?>";
					$("#message").html(data);
					$("#message").show();
					$("#message").delay(1000).fadeOut('slow');
			 	}
		  });
}

function deleted(id)
{
  var ok=confirm("Are you sure to delete the message?");
		if(!ok)
		{
			return false;
		}
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/delete'); ?>",
					async: true,
					data: "message_id="+id,
					success: function(data)
		  	{	
					$("#message").html("<?php echo translate('Message deleted successfully.');?>");
					$("#message").show();
					$("#message").delay(1000).fadeOut('slow');
					
					$("#messages_list").html(data);
			 	location.reload(); 
				}
		  });
}

function archived(id)
{
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/archive'); ?>",
					async: true,
					data: "message_id="+id,
					success: function(data)
		  	{	
					$("#message").html("<?php echo translate('Message archived successfully.');?>");
					$("#message").show();
					$("#message").delay(1000).fadeOut('slow');
					
					$("#messages_list").html(data);
			 	location.reload(); 
				}
		  });
}

function unarchived(id)
{
	$.ajax({
				 type: "POST",
					url: "<?php echo site_url('message/unarchive'); ?>",
					async: true,
					data: "message_id="+id,
					success: function(data)
		  	{	
					$("#message").html("<?php echo translate('Message unarchived successfully.');?>");
					$("#message").show();
					$("#message").delay(1000).fadeOut('slow');
					
					$("#messages_list").html(data);
			 	location.reload(); 
				}
		  });
}

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
<?php echo $pagination; ?>
