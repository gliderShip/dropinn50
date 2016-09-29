<?php error_reporting(E_ERROR | E_PARSE); ?>
<script type="text/javascript">window.history.forward();function noBack(){window.history.forward();}</script>
<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
	<?php if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
			?>

<div id="View_Details">
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
         <h1 class="page-header1"><?php echo translate_admin('Reservation Details'); ?></h1>
        </div>
<form action="<?php echo admin_url('payment/toPay'); ?>" method="post" name="admin_paypal">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
 <tr>
  <td><?php echo translate_admin('List Name'); ?></td>
  <td><?php echo get_list_by_id($result->list_id)->title; ?></td>
 </tr>
	
 <tr>
  <td><?php echo translate_admin('Host Name'); ?></td>
  <td>
		<?php echo $hotelier_name; ?>
		<input type="hidden" name="userto" value="<?php echo $result->userto; ?>" />
		</td>
 </tr>
	
	 <tr>
  <td><?php echo translate_admin('Traveller Name'); ?></td>
  <td>
		<?php echo $booker_name; ?>
		<input type="hidden" name="userby" value="<?php echo $result->userby; ?>" />
		</td>
 </tr>
	
	 <tr>
  <td class="clsName"><?php echo translate_admin('Checkin'); ?></td>
  <td><?php echo date("F j, Y",$result->checkin); ?></td>
 </tr>
	
	 <tr>
  <td class="clsName"><?php echo translate_admin('Checkout'); ?></td>
  <td><?php echo date("F j, Y",$result->checkout); ?></td>
 </tr>
 
	<tr>
  <td class="clsName"><?php echo translate_admin('Using Travel Credits?'); ?></td>
  <td><?php if($result->credit_type ==  2) echo 'Yes'; else echo 'No'; ?></td>
 </tr>
 
 <tr>
  <td class="clsName"><?php echo translate_admin('Status'); ?></td>
  <td><?php echo $result->name; ?></td>
 </tr>
 
 <tr>
  <td class="clsName"><?php echo translate_admin('Cancellation Policy'); ?></td>
  <td><?php echo $this->Common_model->getTableData('cancellation_policy',array('id'=>$result->policy))->row()->name; ?></td>
 </tr>
	
<?php if($result->contacts_offer == 1)
{
 $special_offer = '(Special offer used.)';
}
else
{
	$special_offer='';
} ?>
		<tr>
  <td class="clsName"><?php echo translate_admin('Total Price'); ?></td>
  <td><?php 
 /* if(isset($coupon_price))
  {
  	 echo $currency_code.' '.$currency_symbol; 
	 echo $result->price;
  }
  else*/
 	 echo $currency_code.' '.$currency_symbol.$result->price.$special_offer; 
  ?></td>
 </tr>
 <?php 
 if(isset($coupon_price))
 {
 	?>
 		<tr>
  <td class="clsName"><?php echo translate_admin('Coupon Code'); ?></td>
  <td><?php echo $result->coupon; ?></td>
 </tr>
		<tr>
  <td class="clsName"><?php echo translate_admin('Coupon Price'); ?></td>
  <td><?php echo $currency_code.' '.$currency_symbol.get_currency_value_lys($coupon_currency,$currency_code,$coupon_price); ?></td>
 </tr>
 <?php 
   
 } 
 if(!isset($commission_status))
 {
 ?>
		<tr>
  <td class="clsName"><?php echo translate_admin('Admin Commision'); ?></td>
  <td><?php echo $currency_code.' '.$currency_symbol.$result->admin_commission; ?></td>
 </tr>
<?php
	} if(isset($host_penalty))
	{
		?>
		<tr>
  <td class="clsName"><?php echo translate_admin('Host Penalty'); ?></td>
  <td>
		<?php echo $currency_code.' '.$currency_symbol.$host_penalty; 
		$this->session->set_userdata('host_penalty',$host_penalty);
		?>
		<input type="hidden" name="host_penalty" value="<?php echo $host_penalty; ?>" />
		<input type="hidden" name="currency" value="<?php echo $result->currency; ?>" />
		</td>
 </tr>
	<?php 
	}
	
	if(isset($host_topay))
	{
		?>
		<tr>
  <td class="clsName"><?php echo translate_admin('Host To Pay'); ?></td>
  <td>
		<?php echo $currency_code.' '.$currency_symbol.$host_topay; 
		$this->session->set_userdata('host_topay',$host_topay);
		?>
		<input type="hidden" name="host_to_pay" value="<?php echo $host_topay; ?>" />
		<input type="hidden" name="currency" value="<?php echo $result->currency; ?>" />
		</td>
 </tr>
	<?php 
	}
	if(isset($guest_topay))
	{
		?>
	<tr>
  <td class="clsName"><?php echo translate_admin('Guest To Pay'); ?></td>
  <td>
		<?php echo $currency_code.' '.$currency_symbol.$guest_topay; 
		$this->session->set_userdata('guest_topay',$guest_topay);
		?>
		<input type="hidden" name="guest_to_pay" value="<?php echo $guest_topay; ?>" />
		<input type="hidden" name="currency" value="<?php echo $result->currency; ?>" />
		</td>
 </tr>
 <?php } 
 if(!isset($guest_topay) && !isset($host_topay))
	{
		?>
	<tr>
  <td class="clsName"><?php echo translate_admin('To Pay'); ?></td>
  <td>
		<?php echo $currency_code.' '.$currency_symbol.$topay; 
		$this->session->set_userdata('topay',$topay);
		?>
		<input type="hidden" name="to_pay" value="<?php echo $topay; ?>" />
		<input type="hidden" name="currency" value="<?php echo $result->currency; ?>" />
		</td>
 </tr>
 <?php } ?>
	<tr>
  <td></td>
  <td>
		  <div class="clearfix">
				<span style="float:left; margin:0 10px 0 0;">
				<input type="hidden" name="list_id" value="<?php echo $result->list_id; ?>" />
				<input type="hidden" name="reservation_id" value="<?php echo $result->id; ?>" />
				<?php
				$host_payout_id = get_userPayout($result->userto);
				
				$is_host_banned = $this->db->where('id',$result->userto)->where('banned',1)->get('users')->num_rows();
				
				$is_guest_banned = $this->db->where('id',$result->userby)->where('banned',1)->get('users')->num_rows();
				
				$guest_payout_id = get_userPayout($result->userby);
				
				$check_accept_pay = $this->Common_model->getTableData('accept_pay',array('reservation_id'=>$result->id));
				
				if($check_accept_pay->num_rows() == 0)
				{
					$accept_pay = 2;
				}
				else
					{
						if($check_accept_pay->row()->status == 0)
						$accept_pay = 1;
						else
						$accept_pay = 0;
					}

			if($result->is_payed != 1)
				{
					if($result->status == 2 || $result->status == 4 || $result->status == 5 || $result->status == 6 || $result->status == 11 || $result->status == 12 || $result->status == 13)
					{
						if(!isset($guest_topay) && !isset($host_topay))
						{
							if(isset($guest_payout_id->email) && $result->transaction_id == "0" && $result->is_payed_guest != 1)
							{
							?>
							<input type="hidden" name="biz_id" value="<?php echo $guest_payout_id->email; ?>" />
    					   <input style="margin:0px 0px 0px 0px !important;" class="clsSubmitBt1" type="submit" name="payviapaypal" value="<?php echo translate_admin('Pay Using PayPal to Traveller'); ?>" style="width:250px;" />
							<?php
							}
							else if($result->transaction_id != "0" && $result->is_payed_guest != 1)
							{
							?>
							<input type="hidden" name="braintree_id" value="<?php echo $result->transaction_id; ?>" />
    						<input style="margin:0px 0px 0px 0px !important;" class="clsSubmitBt1" type="submit" name="payviabraintree" value="<?php echo translate_admin('Pay Using Braintree to Traveller'); ?>" style="width:250px;" />
							<?php
							}
							else if($result->is_payed_guest == 1)
							{
							?>
							<p><b><?php echo translate_admin('Already Guest amount paid.');?></b></p>
							<?php
							}
							else 
							{  
							?>
							<p> The <b><?php echo $booker_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
							<input type="hidden" name="payout_userby1" value="<?php echo $result->userby; ?>" />
							<textarea class="text_area" id="text_area1" cols="60" rows="2" style="width:400px; height:80px" name="comment"></textarea>	
							<br />
							<!--<?php if(form_error('comment')) { ?>
							<?php echo form_error('comment'); ?>
							<?php } ?>-->
						
							<p>	<input class="clsSubmitBt1" type="submit" id="message1" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
							<?php	
							}
						}
						if(!isset($guest_topay))
						{
							
						}
						else if(isset($guest_payout_id->email) && $result->transaction_id == "0" && $result->is_payed_guest != 1)
						{
							if($guest_topay != 0)
							{
						?>
					<input type="hidden" name="guest_biz_id" value="<?php echo $guest_payout_id->email; ?>" />
    				<input style="margin:0px 0px 0px 0px !important;" class="clsSubmitBt1" type="submit" name="payviapaypal_guest" value="<?php echo translate_admin('Pay Using PayPal to Traveller'); ?>" style="width:250px;" />
					<?php
							}
						}
						else if($result->transaction_id != "0" && $result->is_payed_guest != 1)
						{
							if($guest_topay != 0)
							{
						?>
						<input type="hidden" name="braintree_id" value="<?php echo $result->transaction_id; ?>" />
    					<input style="margin:0px 0px 0px 0px !important;" class="clsSubmitBt1" type="submit" name="payviabraintree" value="<?php echo translate_admin('Pay Using Braintree to Traveller'); ?>" style="width:250px;" />
					<?php
						 	}
						}
						else if($result->is_payed_guest == 1)
						{
							?>
							<p><b><?php echo translate_admin('Already Guest amount paid.');?></b></p>
							<?php
						}
						else 
						{  
						?>
							<p> The <b><?php echo $booker_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
							<input type="hidden" name="payout_userby1" value="<?php echo $result->userby; ?>" />
							<textarea class="text_area" id="text_area1" cols="60" rows="2" style="width:400px; height:80px" name="comment"></textarea>	
							<br />
							<!--<?php if(form_error('comment')) { ?>
							<?php echo form_error('comment'); ?>
							<?php } ?>-->
						
							<p>	<input class="clsSubmitBt1" type="submit" id="message1" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
						<?php	
						}
						
						if(isset($host_payout_id->email))
						{
							if(isset($host_topay))
							{
							if($host_topay != 0 && $result->is_payed_host != 1)
							{
							?>
							<input type="hidden" name="host_biz_id" value="<?php echo $host_payout_id->email; ?>" />
   							<input style="margin:0px 0px 0px 0px !important;" class="clsSubmitBt1" type="submit" name="payviapaypal_host" value="<?php echo translate_admin('Pay Using PayPal to Host'); ?>" style="width:250px;" />
						<?php
						    }
							else if($result->is_payed_host == 1)
						    {
							?>
							<p><b><?php echo translate_admin('Already Host amount paid.');?></b></p>
							<?php
						    }
							}
						}
						else if(isset($host_topay))
						{
						  ?>
							<p> The <b><?php echo $hotelier_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
							<input type="hidden" name="payout_userto1" value="<?php echo $result->userto; ?>" />
							<textarea class="text_area" cols="60" id="text_area1"  rows="2" style="width:400px; height:80px" name="comment"></textarea>	
							<br />
							<!--<?php if(form_error('comment')) { ?>
							<?php echo form_error('comment'); ?>
							<?php } ?>-->
						
							<p>	<input style="margin:0px 0px 0px 0px !important;" class="clsSubmitBt1" type="submit" id="message1" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
						<?php 
						}
					}
					else if($result->status == 7 || $result->status == 10 || $result->status == 8 || $result->status == 9)
					{
						if($topay != 0)
						{
						if(!isset($host_payout_id->email)){
						  ?>
							<p> The <b><?php echo $hotelier_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
							<input type="hidden" name="payout_userto1" value="<?php echo $result->userto; ?>" />
							<textarea class="text_area" cols="60" id="text_area1"  rows="2" style="width:400px; height:80px" name="comment"></textarea>	
							<br />
							<!--<?php if(form_error('comment')) { ?>
							<?php echo form_error('comment'); ?>
							<?php } ?>-->
						
							<p>	<input class="clsSubmitBt1" type="submit" id="message1" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
						<?php 
						}	
						else{	
						?>
						<input type="hidden" name="biz_id" value="<?php echo $host_payout_id->email; ?>" />
    					<input style="margin:0px 0px 0px 0px !important;" class="clsSubmitBt1" type="submit" name="payviapaypal" value="<?php echo translate_admin('Pay Using PayPal to Host'); ?>" style="width:250px;" />
						<?php
						}
						}
						else
							{
								echo '<p><b>'.translate_admin('Penalty amount is taken from Host topay.').'</b></p>';
							}
					}
					else if($result->status == 3)
					{
					?>
						<p><b><?php echo translate_admin('Waiting for traveller checkin.');?></b></p>
					<?php
					}
					else
					{
					?>
						<p><b><?php echo translate_admin('Waiting for host approval or declined.');?></b></p>
					<?php	
					}
				}	
				else
				{
				?>
				<tr><td></td>
 				<td><b><?php echo translate_admin('Already amount paid to Host/Guest.');?></b></td>
				<?php
				}
				if($accept_pay != 2 && $result->status != 1 && $result->status != 3)
				{
					?>
			<tr>
  <td class="clsName"><?php echo translate_admin('Host accept pay commission'); ?></td>
  <td>
		<?php 
		$currency_symbol_accept = $this->Common_model->getTableData('currency',array('currency_code'=>$check_accept_pay->row()->currency))->row()->currency_symbol;
		echo $check_accept_pay->row()->currency.' '.$currency_symbol_accept.$check_accept_pay->row()->amount; 
		?>
		</td>
 </tr>	
 	<?php
 	if($accept_pay == 1 && $result->status !=10 )
					{ ?>
 <tr><td></td>
 	<td>			
	<input type="hidden" name="accept_pay" value="<?php echo $check_accept_pay->row()->id; ?>" />
	<?php
	if($check_accept_pay->row()->transaction_id != '')
	{
		?> 
	 <input type="hidden" name="accept_braintree_id" value="<?php echo $check_accept_pay->row()->transaction_id; ?>" />
	 <input style="margin:0px 0px 0px 0px !important;" class="clsSubmitBt1" type="submit" name="payviabraintree_accept_pay" value="<?php echo translate_admin('Pay Using Braintree to Host'); ?>" style="width:240px;" />	
	<?php
	}
	else if(isset($host_payout_id->email))
	{
		?>
	<input type="hidden" name="biz_host_id" value="<?php echo $host_payout_id->email; ?>" />
	 <input style="margin:0px 0px 0px 0px !important;" class="clsSubmitBt1" type="submit" name="payviapaypal_accept_pay" value="<?php echo translate_admin('Pay Using PayPal to Host'); ?>" style="width:240px;" />	
	<?php
	}
	else {
		?>
		<p> The <b><?php echo $hotelier_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
				<input type="hidden" name="payout_userto2" value="<?php echo $result->userto; ?>" />
				<textarea class="text_area" id="text_area2"  cols="60" rows="2" style="width:400px; height:80px" name="comment"></textarea>	
				<br />
				<!--<?php if(form_error('comment')) { ?>
				<?php echo form_error('comment'); ?>
				<?php } ?>-->
						
		<p>	<input class="clsSubmitBt1" type="submit" id="message2" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
				
		<?php
	}
	?>
	</td>
	<?php
					}
else
	{
		?>
		<tr><td></td>
 	<td><b><?php echo translate_admin('Already amount paid to Host.');?></b></td>
		<?php
	}
	}
			/*	if($result->is_payed != 1)
				{
				if($result->status != 1 && $result->status != 2 && $result->status != 4 && $result->status != 5 && $result->status != 6 )
				{
				if(isset($host_payout_id->email))
				{
					if($is_host_banned != 0 || $is_guest_banned != 0)
					{
						if(isset($guest_payout_id->email) && $result->transaction_id == 0)
				{
					?>
					<input type="hidden" name="biz_id" value="<?php echo $guest_payout_id->email; ?>" />
    <input class="clsSubmitBt1" type="submit" name="payviapaypal" value="<?php echo translate_admin('Pay Using PayPal to Traveller'); ?>" style="width:210px;" />
					<?php
				}
						else if($result->transaction_id != "0")
				{
					?>
					<input type="hidden" name="braintree_id" value="<?php echo $result->transaction_id; ?>" />
    <input class="clsSubmitBt1" type="submit" name="payviabraintree" value="<?php echo translate_admin('Pay Using Braintree to Traveller'); ?>" style="width:250px;" />
					<?php
				}
					else {  ?>
					<p> The <b><?php echo $booker_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
				<input type="hidden" name="payout_userby1" value="<?php echo $result->userby; ?>" />
				<textarea class="text_area" id="text_area1" cols="60" rows="2" style="width:400px; height:80px" name="comment"></textarea>	
				<br />
				<!--<?php if(form_error('comment')) { ?>
				<?php echo form_error('comment'); ?>
				<?php } ?>-->
						
		<p>	<input class="clsSubmitBt1" type="submit" id="message1" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
					
				<?php	}
					}
else {
	
				?>
				<input type="hidden" name="host_biz_id" value="<?php echo $host_payout_id->email; ?>" />
    <input class="clsSubmitBt1" type="submit" name="payviapaypal_host" value="<?php echo translate_admin('Pay Using PayPal to Host'); ?>" style="width:210px;" />
	
	<input type="hidden" name="guest_biz_id" value="<?php echo $host_payout_id->email; ?>" />
    <input class="clsSubmitBt1" type="submit" name="payviapaypal_guest" value="<?php echo translate_admin('Pay Using PayPal to Traveller'); ?>" style="width:210px;" />
			<?php
} } else { ?>
				<p> The <b><?php echo $hotelier_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
				<input type="hidden" name="payout_userto1" value="<?php echo $result->userto; ?>" />
				<textarea class="text_area" cols="60" id="text_area1"  rows="2" style="width:400px; height:80px" name="comment"></textarea>	
				<br />
				<!--<?php if(form_error('comment')) { ?>
				<?php echo form_error('comment'); ?>
				<?php } ?>-->
						
		<p>	<input class="clsSubmitBt1" type="submit" id="message1" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
				<?php } 
				}
               else if($result->status == 2 || $result->status == 4 || $result->status == 5 || $result->status == 6)
				{
					if(isset($guest_payout_id->email) && $result->transaction_id == 0)
				{
					?>
					<input type="hidden" name="biz_id" value="<?php echo $guest_payout_id->email; ?>" />
    <input class="clsSubmitBt1" type="submit" id="message1" name="payviapaypal" value="<?php echo translate_admin('Pay Using PayPal to Traveller'); ?>" style="width:210px;" />
					<?php
				}
				elseif($result->transaction_id != "0")
				{
					?>
					<input type="hidden" name="braintree_id" value="<?php echo $result->transaction_id; ?>" />
    <input class="clsSubmitBt1" type="submit" name="payviabraintree" value="<?php echo translate_admin('Pay Using Braintree to Traveller'); ?>" style="width:250px;" />
					<?php
				}
					else { ?>
					<p> The <b><?php echo $booker_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
				<input type="hidden" name="payout_userby1" value="<?php echo $result->userby; ?>" />
				<textarea class="text_area" cols="60" id="text_area1"  rows="2" style="width:400px; height:80px" name="comment"></textarea>	
				<br />
				<!--<?php if(form_error('comment')) { ?>
				<?php echo form_error('comment'); ?>
				<?php } ?>-->
						
		<p>	<input class="clsSubmitBt1" type="submit" id="message1" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
					
				<?php	}
					
					if($check_accept_pay->num_rows() != 0)
					{
						?>
			<tr>
  <td class="clsName"><?php echo translate_admin('Host accept pay commission'); ?></td>
  <td>
		<?php 
		$currency_symbol_accept = $this->Common_model->getTableData('currency',array('currency_code'=>$check_accept_pay->row()->currency))->row()->currency_symbol;
		echo $check_accept_pay->row()->currency.' '.$currency_symbol_accept.$check_accept_pay->row()->amount; 
		?>
		</td>
 </tr>	
 	<?php
 	if($accept_pay == 1)
					{ ?>
 <tr><td></td>
 	<td>			
	<input type="hidden" name="accept_pay" value="<?php echo $check_accept_pay->row()->id; ?>" />
	<?php
	if($check_accept_pay->row()->transaction_id != '')
	{
		?> 
	 <input type="hidden" name="accept_braintree_id" value="<?php echo $check_accept_pay->row()->transaction_id; ?>" />
	 <input class="clsSubmitBt1" type="submit" name="payviabraintree_accept_pay" value="<?php echo translate_admin('Pay Using Braintree to Host'); ?>" style="width:210px;" />	
	<?php
	}
	else if(isset($host_payout_id->email))
	{
		?>
	<input type="hidden" name="biz_host_id" value="<?php echo $host_payout_id->email; ?>" />
	 <input class="clsSubmitBt1" type="submit" name="payviapaypal_accept_pay" value="<?php echo translate_admin('Pay Using PayPal to Host'); ?>" style="width:210px;" />	
	<?php
	}
	else {
		?>
		<p> The <b><?php echo $hotelier_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
				<input type="hidden" name="payout_userto2" value="<?php echo $result->userto; ?>" />
				<textarea class="text_area" id="text_area2"  cols="60" rows="2" style="width:400px; height:80px" name="comment"></textarea>	
				<br />
				<!--<?php if(form_error('comment')) { ?>
				<?php echo form_error('comment'); ?>
				<?php } ?>-->
						
		<p>	<input class="clsSubmitBt1" type="submit" id="message2" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
				
		<?php
	}
	?>
	</td>
	<?php
					}
else
	{
		?>
		<tr><td></td>
 	<td><b><?php echo translate_admin('Already amount payed to Host.');?></b></td>
		<?php
	}
					}
				}
else if($is_guest_banned != 0)
{ 
	if(isset($guest_payout_id->email) && $result->transaction_id == 0)
				{
					?>
					<input type="hidden" name="biz_id" value="<?php echo $guest_payout_id->email; ?>" />
    <input class="clsSubmitBt1" type="submit" name="payviapaypal" value="<?php echo translate_admin('Pay Using PayPal to Traveller'); ?>" style="width:210px;" />
					<?php
				}
						else if($result->transaction_id != "0")
				{
					?>
					<input type="hidden" name="braintree_id" value="<?php echo $result->transaction_id; ?>" />
    <input class="clsSubmitBt1" type="submit" name="payviabraintree" value="<?php echo translate_admin('Pay Using Braintree to Traveller'); ?>" style="width:250px;" />
					<?php
				}
					else { ?>
						<p> The <b><?php echo $booker_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
				<input type="hidden" name="payout_userby1" value="<?php echo $result->userby; ?>" />
				<textarea class="text_area" id="text_area1" cols="60" rows="2" style="width:400px; height:80px" name="comment"></textarea>	
				<br />
				<!--<?php if(form_error('comment')) { ?>
				<?php echo form_error('comment'); ?>
				<?php } ?>-->
						
		<p>	<input class="clsSubmitBt1" type="submit" id="message1" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
					
				<?php
					}	
}
else if($is_host_banned != 0)
					{
						if(isset($guest_payout_id->email) && $result->transaction_id == 0)
				{
					?>
					<input type="hidden" name="biz_id" value="<?php echo $guest_payout_id->email; ?>" />
    <input class="clsSubmitBt1" type="submit"onclick="change_accept_status();"  name="payviapaypal" value="<?php echo translate_admin('Pay Using PayPal to Traveller'); ?>" style="width:210px;" />
					<?php
				}
						else if($result->transaction_id != 0)
				{
					?>
					<input type="hidden" name="braintree_id" value="<?php echo $result->transaction_id; ?>" />
    <input class="clsSubmitBt1" type="submit" name="payviabraintree" value="<?php echo translate_admin('Pay Using Braintree to Traveller'); ?>" style="width:250px;" />
					<?php
				}
					else { ?>
					<p> The <b><?php echo $booker_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title;  ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
				<input type="hidden" name="payout_userby2" value="<?php echo $result->userby; ?>" />
				<textarea class="text_area" id="text_area2" cols="60" rows="2" style="width:400px; height:80px" name="comment"></textarea>	
				<br />
				<!--<?php if(form_error('comment')) { ?>
				<?php echo form_error('comment'); ?>
				<?php } ?>-->
						
		<p>	<input class="clsSubmitBt1" type="submit" id="message2" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
					
				<?php	}
					}
				else
				{
					?>
					<p><b><?php echo translate_admin('Waiting for host approval or declined.');?></b></p>
					<?php
				}
				}
				else
				{
					?>
					<p><b><?php echo translate_admin('Already amount payed to Host/Traveller.');?></b></p>
					<?php
					if($check_accept_pay->num_rows() != 0)
					{
						if($result->status == 5 || $result->status == 6)
					{
						?>
			<tr>
  <td class="clsName"><?php echo translate_admin('Host accept pay commission'); ?></td>
  <td>
		<?php 
		$currency_symbol_accept = $this->Common_model->getTableData('currency',array('currency_code'=>$check_accept_pay->row()->currency))->row()->currency_symbol;
		echo $check_accept_pay->row()->currency.' '.$currency_symbol_accept.$check_accept_pay->row()->amount; 
		?>
		</td>
 </tr>	
 	<?php
 	if($accept_pay == 1)
					{
	if($check_accept_pay->row()->transaction_id != '')
	{
		?> 
		<tr><td></td>
 	<td>
	<input type="hidden" name="accept_pay" value="<?php echo $check_accept_pay->row()->id; ?>" />	
	<input type="hidden" name="accept_braintree_id" value="<?php echo $check_accept_pay->row()->transaction_id; ?>" />
	 <input class="clsSubmitBt1" type="submit" name="payviabraintree_accept_pay" value="<?php echo translate_admin('Pay Using Braintree to Host'); ?>" style="width:210px;" />	
	
	</td>
	<?php
	}
	else {			
						 ?>
 <tr><td></td>
 	<td>			
	<input type="hidden" name="accept_pay" value="<?php echo $check_accept_pay->row()->id; ?>" />
	<input type="hidden" name="biz_host_id" value="<?php echo $host_payout_id->email; ?>" />
    <input class="clsSubmitBt1" type="submit" name="payviapaypal_accept_pay" value="<?php echo translate_admin('Pay Using PayPal to Host'); ?>" style="width:210px;" />
		</td>				<?php
					}
					}
else
	{
		?>
		<tr><td></td>
 	<td><b><?php echo translate_admin('Already amount payed to Host.');?></b></td>
		<?php
	}
					}
	}
				}*/
			?>
    </span></div>
		</td>
 </tr>
</table>
</form>

</div></div></div></body>
<script>
$('#message1').click(function()
{
	if($('#text_area1').val() == "")
	{
		alert('Please give some text on message box.');return false;
	}
	
	$('input[name="payout_userby2"]').val(0);
	$('input[name="payout_userto2"]').val(0);
})
$('#message2').click(function()
{
	if($('#text_area2').val() == "")
	{
		alert('Please give some text on message box.');return false;
	}
	
	$('input[name="payout_userby1"]').val(0);
	$('input[name="payout_userto1"]').val(0);
})
function change_accept_status()
{
	$('input[name="accept_pay"]').val(0);
}
</script>