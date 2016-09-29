<?php error_reporting(E_ERROR | E_PARSE); ?>
<!-- Included Style Sheets -->
<link href="<?php echo css_url().'/checkout.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- <link href="<?php echo css_url().'/common.css'; ?>" media="screen" rel="stylesheet" type="text/css" />-->
<script src="<?php echo base_url(); ?>js/prototype.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/common.js" type="text/javascript"></script>
<!-- End of style sheet inclusion -->
<script src="<?php echo base_url(); ?>js/tooltips_good.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/tabber-minimized.js" type="text/javascript"></script>
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<?php
//$amt=$amt*$days;
if(isset($flash_message))
{
echo '<div class="clsShow_Notification"><p class=""><span>'.$flash_message.'</span></p></div>';
}

if($meta_keyword == 'mobile')
{
	?>
<link href="<?php echo css_url().'/bootstrap.min.css'; ?>" media="screen" rel="stylesheet" type="text/css" />

<style type="text/css">
span.optional_usd {
	display:none !important;
}
.view_book
{
	    background: none repeat scroll 0 0 #F0F0F0;
    border-radius: 10px;
    margin: 0 auto;
    min-height: 375px;
    padding: 10px 10px 5px;
    text-align: left;
    max-width: 85%;
    margin-top:85px;
}
.dashed_table li
{
	border:none !important;
}
</style>

<div id="book_it" class="view_book"> 

<?php 

if($contact_key != '')
{
echo form_open('payments/form/'.$id.'?contact='.$contact_key);	
}
else
{
echo form_open('payments/index/'.$id); 
}

?>

  <input id="hosting_id" name="hosting_id" type="hidden" value="<?php echo $id; ?>" />
  <input id="checkin" name="checkin" type="hidden" value="<?php echo $checkin; ?>" /><br />
  <input id="checkout" name="checkout" type="hidden" value="<?php echo $checkout; ?>" /><br >
  <input id="number_of_guests" name="number_of_guests" type="hidden" value="<?php echo $guests; ?>" />
  <input id="contact_key" name="contact_key" type="hidden" value="<?php echo $contact_key ?>" />
  <input id="env" name="env" type="hidden" value="<?php echo $env; ?>">
  <?php
		$this->session->set_userdata('call_back','mobile');
  ?>
  <?php $this->session->set_userdata('booking_currency_symbol',get_currency_code());?>
  <div id="how_it_works" class="Box bookit_Box">
    <div class="Box_Head bookhead">
      <h2><?php echo translate("1. How it works"); ?></h2>
    </div>
    <div class="Box_Content">
      <p> <?php echo translate("Please provide your billing details now and the place shall be booked for your purpose"); ?> </p>
      <div class="clear"></div>
    </div>
  </div>
  
  <div id="property_details" class="Box bookit_Box">
    <div class="Box_Head bookhead">
      <h2><?php echo translate("2. Property details"); ?></h2>
    </div>
    <div class="Box_Content"> <img src="<?php echo getListImage($id); ?>" class="main_photo"/>
      <div id="hosting_details">
        <h2><a href=<?php echo base_url().'rooms/'.$id; ?> target="_blank"><?php echo $tit; ?></a></h2>
        <div id="hosting_address" class="rounded_more">
		
		 <?php //echo $address; ?>
		 <!-- Deepak Address Number Remove Ends -->
		  </div>
        <div class="clear"></div>
        <p><?php echo $room_type; ?></p>
        <p><?php echo translate("Accommodates "); ?><?php echo $guests; ?> <?php echo translate("guests"); ?></p>
      </div>
      <div class="clear"></div>
    </div>
  </div>
  <div id="trip_details" class="Box bookit_Box">
    <div class="Box_Head bookhead">
      <h2><?php echo translate("3. Trip Details"); ?></h2>
    </div>
    <div class="Box_Content">
    	
    <ul id="details_breakdown" class="dashed_table" >
    	
      <li class="top med_12 mal_12 pe_12 padding-zero"> <span class="label"><span class="inner"><span class="checkout_icon" id="icon_check_in"></span><?php echo translate("Check in"); ?></span></span> <span class="data"><span class="inner"> <?php echo get_user_times(get_gmt_time(strtotime($checkin)), get_user_timezone()); ?> <em class="check_in_out_time"><?php echo translate("(Flexible check in time)"); ?></em></span></span> </li>
						
      <li> <span class="label med_3 mal_3 pe_12"><span class="inner"><span class="checkout_icon" id="icon_check_out"></span><?php echo translate("Check out"); ?></span></span> <span class="data med_9 mal_9 pe_12"><span class="inner"> <?php echo get_user_times(get_gmt_time(strtotime($checkout)), get_user_timezone()); ?> <em class="check_in_out_time"><?php echo translate("(Flexible check out time)"); ?></em></span></span> </li>
						
      <li class="med_12 mal_12 pe_12 padding-zero"> <span class="label"><span class="inner"><span class="checkout_icon" id="icon_nights"></span><?php echo translate("Nights"); ?></span></span> <span class="data" ><span class="inner"><?php echo $days; ?></span></span> </li>
						      
      <li class="bottom"> <span class="label"><span class="inner"><span class="checkout_icon" id="icon_person"></span><?php echo translate("Guests"); ?></span></span> <span class="data"><span class="inner"><?php echo $guests;  ?></span></span> </li>
     
						
      <div class="clear"></div>
    </ul>
    <ul id="price_breakdown" class="dashed_table price1">
    
    	<?php
    	if(isset($avg_price))
		{
    	?>
    
    <!-- note -->
    	

      <!--<li class="top"> <span class="label"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Average Rate (per night)"); ?> </span> </span> <span class="data"> <span class="inner"> <?php echo get_currency_symbol($id).get_currency_value1($id,$avg_price); ?><sup></sup><span class="optional_usd">-->
      <li class="top"> <span class="label  med_4  pe_5"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Average Rate (per night)"); ?> </span> </span> <span class="data  med_8 pe_7"> <span class="inner"> <?php echo get_currency_symbol($id).$avg_price; ?><sup></sup><span class="optional_usd">
       
       

        </span> </span> </span> </li>
								<?php } else { ?>
								<li class="top"> <span class="label"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Rate (per night)"); ?> </span> </span> <span class="data"> <span class="inner"> 
									<?php echo get_currency_symbol($id).get_currency_value1($id,$price); 
									$this->session->set_userdata('per_night',get_currency_symbol($id).get_currency_value1($id,$price));
									?><sup></sup><span class="optional_usd">
        </span> </span> </span> </li>	
									<?php } ?>
									<?php 
									if($cleaning != 0)
									{
									?>
		 <li> <span class="label"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Cleaning Fee"); ?> </span> </span> <span class="data"> <span class="inner"> <?php echo get_currency_symbol($id).get_currency_value1($id,$cleaning); ?><sup></sup><span class="optional_usd">
        </span> </span> </span> </li>
        <?php } ?>
        <?php if($security != 0)
		{ ?>
         <li > <span class="label"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Security Fee"); ?> </span> </span> <span class="data "> <span class="inner"> <?php echo get_currency_symbol($id).get_currency_value1($id,$security); ?><sup></sup><span class="optional_usd">
        </span> </span> </span> </li>
        <?php } ?>	
         <?php 
         if(isset($extra_guest_price))
		 {
         if($extra_guest_price != 0)
		{ ?>
        <li> <span class="label" > <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Additional Guest Fee"); ?> </span> </span> <span class="data"> <span class="inner"> <?php echo get_currency_symbol($id).get_currency_value1($id,$extra_guest_price); ?><sup></sup><span class="optional_usd">
        </span> </span> </span> </li>
        <?php }
		 } ?>						
      <li class="top"> <span class="label"><span class="inner"><span class="checkout_icon" id="icon_sub_tot"></span> <?php echo translate("Subtotal"); ?> </span></span> <span class="data"> <span class="inner"> <?php echo get_currency_symbol($id).$subtotal; ?><sup></sup><span class="optional_usd">
		<?php $this->session->set_userdata('subtotal',$subtotal);?>						
        </span> </span> 
        <?php
        if($this->session->userdata('coupon_code_used') != 1)
		{
			?>
        <a id="coupon_show_link" class="coupon1" onclick="$('coupon_show_link').hide(); $('coupon').show();$('referral').hide();" href="javascript:void(0);"><?php echo translate("Coupon Code?"); ?></a> 
        <?php } ?>
        </span> </li>
								
      <li id="coupon" class="coupon2"> <span class="label"><span class="inner"><span id="icon_coupon" class="checkout_icon"></span><?php echo translate("Coupon"); ?></span></span> <span class="data"> <span class="inner coupon3"> <span id="coupon_fields">
        <p><input type="text" value="" name="coupon_code" id="coupon_code" class="active" autocomplete="off">
        
	<input type="submit" name="apply_coupon" value="<?php echo translate("Apply Coupon"); ?>" class="Butt_Normal" />
        
								</p>
        </span> </span> 
        <?php
        if($this->session->userdata('coupon_code_used') != 1)
		{
			?>
        <a id="coupon_hide_link" class="coupon4" onclick="$('coupon_show_link').show(); $('coupon').hide();$('referral').show();" href="javascript:void(0);"><?php echo translate("cancel"); ?></a> 
        <?php } ?>
        </span> </li>
								
       <li> <span class="label checkouticon"> <span class="inner"><span class="checkout_icon" id="icon_commi"></span><?php echo translate("Commission");?> </span> </span> <span class="data"> <span class="inner"> <?php echo get_currency_symbol($id).$commission; ?> </span> </span> </li>
      <span id="referral"></span>
      <?php 
      if(isset($referral_amount))
	  {
      ?>
	 <li class="top"> <span class="label" > <span class="inner"><span class="checkout_icon" id="icon_commi"></span><?php echo translate("Referral Amount");?> </span> </span> <span class="data"> <span class="inner"> 
	  	<?php  	
	  	if($referral_amount > 100)
		{
			echo get_currency_symbol($id).get_currency_value(100); 
		}
		else
			{
				echo get_currency_symbol($id).get_currency_value($referral_amount); 
			}
			?> 
	  	</span> </span> </li> 
	  <?php 
	  }
	  ?>
	<!--  <li class="bottom" id="total"> <span class="label"><span class="inner"><span class="checkout_icon icon_total"></span><?php echo translate("Total"); ?></span></span> <span class="data"> <span class="inner">-->
	  <li class="bottom" id="total"> 
	  	<span class="label med_4  pe_5">
		  	<span class="inner"><span class="checkout_icon icon_total"></span><?php echo translate("Total"); ?>
		  	</span>
	  	</span> 
	  	<span class="data  med_8 pe_7"> <span class="inner">
	  

	  	<?php 
       //  print_r($referral_amount) ;
	   // print_r($subtotal);
	 //	exit;
	   //echo "ssssssssssssssssssss";
	
	  	if((isset($referral_amount)) && ($subtotal > $referral_amount))
	  {
	  	if($referral_amount > 100)
		{
			$final_amt = $amt-get_currency_value(100);
	       
		}
		else
			{
				$final_amt = $amt-get_currency_value($referral_amount);
			    
			}
			
	  	echo get_currency_symbol($id).$final_amt; 
		$this->session->set_userdata('final_amount',$final_amt);
	  }
      else
       {
	  	echo get_currency_symbol($id).$amt; 
		$this->session->set_userdata('final_amount',$amt);
		//echo $amt;
       }
	  	?> 
	  	<sup></sup><span class="optional_usd"></sup></span> </span> </span> </li>
	 
	  </ul>
    
    <div class="clear"></div>
    
    </div> 	
  </div>
  <?php echo form_close(); ?>
  <?php if($full_cretids == 'off') { ?>
  <?php echo form_open('payments/payment/'.$id.'/'.$env, array("id" => "PaymentsForm")); ?>
  <?php
						  $value  = '';

								if($result[0]->is_enabled == 1)
								{
								$showP  = '';
									if($value  == '')
									{
											$value  = 'paypal';
									}
								}
								else
								{
								$showP  = ' payment_method_hidden';
								}
								
								if($result[1]->is_enabled == 1)
								{
								$show2C = '';
									if($value  == '')
									{
							   //	$value  = 'braintree';
									}
								}
								else
								{
								// $show2C = ' payment_method_hidden';
								}

								?>
 <div id="payment_options" class="Box bookit_Box">
    <div class="Box_Head bookhead">
      <h2><?php echo translate("4. Payment options"); ?> <span id="country_name" style="display:none;"></span></h2>
    </div>
    <div class="payment_tabs pe_12">
 				  <div id="payment_method_paypal" class="payment_method_content<?php echo $showP; ?> ">
        <input type="hidden" value="USD" name="payment_method_paypal_currency" id="payment_method_paypal_currency">
        <h2><?php echo translate("PayPal"); ?></h2>
	   <?php	   
	   if(isset($referral_amount))
	  {
	  	?>
	    <div class="currency_alert"><?php echo translate("This payment transacts in"); ?> <?php echo get_currency_symbol($id).get_currency_code(); ?>. <?php echo translate("Your total charge is"); ?> <?php echo get_currency_symbol($id).' '.$final_amt; ?>.</div>
		<?php }
	   else
	   	{ ?>
	   	<div class="currency_alert"><?php echo translate("This payment transacts in"); ?> <?php echo get_currency_symbol($id).get_currency_code(); ?>. <?php echo translate("Your total charge is"); ?> <?php echo get_currency_symbol($id).$amt; ?>.</div>
	   	<?php
	   	} ?>
		
        <p class="">
        	<img src="<?php echo css_url();?>/images/logo_paypal.png" />
        	<br/>
        	<span class="paypallogo"><?php echo translate("Instructions"); ?>:</span> <br>
          <?php echo translate("After clicking 'Book it' you will be redirected to PayPal to complete payment."); ?> <span class="paypallogo"> <?php echo translate("You must complete the process or the transaction will not occur."); ?> </span> </p>
        <div class="clear"></div>
      </div>
      <?php
      if($this->session->userdata('call_back') != 'mobile')
     {	

// brain tree 2 start 								?>
 
 <!--  brain tree 2 end-->
    <?php } ?>
 </div>
</div>
  <div id="policies" class="Box bookit_Box" >
    <div class="Box_Head bookhead">
      <h2><?php echo translate("5. Policies");?></h2>
    </div>
    <div class="Box_Content policies1"> 
    <ul class="dashed_table"  >
       <li ><span class="label"> <span class="inner"><span class="checkout_icon" id="icon_crossed_out"></span><?php echo translate("Cancellation"); ?></span> </span> <span class="data"> <span class="inner"> 
      	
      	<!--<a href="<?php echo site_url('pages/cancellation_policy'); ?>" target="_blank"><?php echo translate("Flexible");?></a>-->
      			   
			   <?php if($policy !='' && !isset($flash_message)) {
			   	 ?>
	  	<a target="_blank" href="<?php echo site_url('pages/cancellation_policy/#'.str_replace(' ','-',$policy).''); ?>">
	  	<?php echo $policy;?> 
	  	</a>
	  	 <?php } 
else if(isset($flash_message))
{
	$policy = $this->Common_model->getTableData('cancellation_policy',array('id'=>5))->row()->name;
	?>
	<a target="_blank" href="<?php echo site_url('pages/cancellation_policy/#'.str_replace(' ','-',$policy)); ?>">
	  	<?php echo $policy;?> 
	  	</a>
	<?php
}
	  	 else { echo translate("Not Available"); } ?>
			         	
      	</span> </span> </li>
      <li class="bottom"> <span class="label"> <span class="inner"><span class="checkout_icon" id="icon_house"></span><?php echo translate("House Rules"); ?></span> </span> <span class="data"> <span class="inner"> <a href="javascript:void(0);" oncontextmenu="return false" onclick="show_super_lightbox('house_rules_data'); return false;" href="javascript:void(0);"><?php echo translate("Read Policy"); ?></a></a> </span> </span> </li>
  
          <div class="clear"></div>
            </ul>
            

    </div>
  </div>
  
  <input type="hidden" value="<?php echo $value; ?>" name="payment_method" id="payment_method">
  
  <div class="book_it_section" >
    <p id="book_it_fine_print">
      <input type="checkbox" id="agrees_to_terms" name="agrees_to_terms" onclick="$('p4_book_it_button').disabled = '';" />
      <span><?php echo translate("I agree to the cancellation policy and house rules.");?></span></p>
   
    <input type="hidden" value=<?php echo $checkin; ?> name="checkin" />
    <input type="hidden" value=<?php echo $checkout; ?> name="checkout" />
    <input type="hidden" value=<?php echo $guests; ?> name="number_of_guests" />
    <input id="contact_key" name="contact_key" type="hidden" value="<?php echo $contact_key ?>" />
	
	<!-- Deepak Security Deposit Amount Starts -->
	<?php //if($security->security_deposit_status ==1){ ?>
	<!-- <input type="hidden" value=<?php //echo $final_security_amount; ?> name="final_security_amount" /> -->
	 <?php $roomtotal=$subtotal;?>
     <input type="hidden" value=<?php echo $roomtotal; ?> name="roomtotal" />
     <?php $commission= $commission; ?>
       <input type="hidden" value=<?php echo $commission; ?> name="commission" />
	<?php
	 $subtotal=$amt; ?> 
	<input type="hidden" value=<?php echo $subtotal; ?> name="subtotal" />
	<?php //} ?> 
	
	<!-- Deepak Security Deposit Amount Ends -->
	

    <p>
				<input type="button" name="book_it_button" id="p4_book_it_button" class="btn_dash" value="<?php echo translate("Book it"); ?>"  />
    </p>

   <!--<p>
				<input type="button" name="book_it_button" id="p4_book_it_button" class="btn large blue" value="<?php echo translate("Book it"); ?>"  />
   </p>-->


  
  <?php echo form_close(); ?>
  <?php } else { ?>
  <div id="policies" class="Box">
    <div class="Box_Head bookhead policies2">
      <h1><?php echo translate("5. Policies");?></h1>
    </div>
    <div class="Box_Content"> 
    <ul class="dashed_table">
      <li class="top"> <span class="label"> <span class="inner"><span class="checkout_icon" id="icon_crossed_out"></span><?php echo translate("Cancellation");?></span> </span> <span class="data"> <span class="inner"> <a href="<?php echo site_url('pages/cancellation_policy'); ?>" target="_blank"><?php echo translate("Flexible");?></a> </span> </span> </li>
      <li class="bottom"> <span class="label"> <span class="inner"><span class="checkout_icon" id="icon_house"></span><?php echo translate("House Rules");?></span> </span> <span class="data"> <span class="inner"> <a href="javascript:void(0);" oncontextmenu="return false" onclick="show_super_lightbox('house_rules_data'); return false;"  href="javascript:void(0);"><?php echo translate("Read Policy");?></a> <a href="#" onclick="return false;"></a> </span> </span> </li>
    
          <div class="clear"></div>
</ul>
    </div>
  </div>
  <p id="book_it_fine_print">
    <input type="checkbox" id="agrees_to_terms" name="agrees_to_terms" onclick="$('p4_book_it_button').disabled = '';" />
 
    <span><?php echo translate("I agree to the cancellation policy and house rules.");?></span></p>

  <?php echo form_open('referrals/booking/'.$this->uri->segment(3)); ?>
  <input type="hidden" value=<?php echo $checkin; ?> name="checkin" />
  <input type="hidden" value=<?php echo $checkout; ?> name="checkout" />
  <input type="hidden" value=<?php echo $guests; ?> name="number_of_guests" />
  <input id="contact_key" name="contact_key" type="hidden" value="<?php echo $contact_key ?>" />
 <p>
  <input type="button" name="book_it_button" id="p4_book_it_button" class="Butt_Normal" value="<?php echo translate("Book it"); ?>"  />
</p>
  <?php echo form_close(); ?>
  <?php } ?>
  <!-- /Form -->
</div>

<!-- #book_it -->
<!-- .narrow_page_bg -->

<div id="house_rules_data" class="super_lightbox"> <a class="hide_super_lightbox" href="javascript:void(0);" onclick="hide_super_lightbox('house_rules_data'); return false;">
  <?php echo "[X] Close"; ?>
  </a>
  <h3><?php echo translate("House Rules");?></h3>
  <h4><?php 
if($manual == '')
{
echo 'No House Rules for this list.	';
}
else {
echo $manual;
} ?></h4>
</div>
<div id="security_deposit_data" class="super_lightbox"> <a class="hide_super_lightbox" href="javascript:void(0);" onclick="hide_super_lightbox('security_deposit_data'); return false;">
  <?php echo "[X] Close"; ?>
  </a>
  <p class="policies3"> <?php echo translate("If the host reports a problem, we will capture the entire authorized amount while we gather additional information from both parties. If a compromise is reached, we will refund the agreed upon amount. Although it is primarily up to the host to determine the extent of the damage, We tracks every claim
that is made, and if a host develops a trend of claiming damages in order to keep the security deposit, the host may removed from ".$this->dx_auth->get_site_title()."."); ?> </p>
</div>
<div id="pricing_explanation" class="super_lightbox"> <a class="hide_super_lightbox" href="javascript:void(0);" onclick="hide_super_lightbox('pricing_explanation'); return false;">
  <?php echo "[X] Close"; ?>
  </a>
  <h3><?php echo translate("Subtotal Breakdown");?></h3>
  <p class="subtotal1"> 
		<?php echo translate("A variety of factors contribute to how the subtotal is calculated. Below is a detailed explanation.");?> </p>
  </div>
<div id="transparent_bg_overlay"></div>
<?php } 

else {
	
	$this->session->unset_userdata('call_back');
	?>
<style type="text/css">
span.optional_usd {
	display:none !important;
}

.dashed_table .label {
    /*width: 300px;*/
}
.dashed_table li.top {
    border-top-width: 1px;
}
</style>

<div id="book_it" class="container_bg container"> 

<?php 
if($contact_key != '')
{
echo form_open('payments/form/'.$id.'?contact='.$contact_key);	
}
else
{
echo form_open('payments/index/'.$id); 
}
?>

  <input id="hosting_id" name="hosting_id" type="hidden" value="<?php echo $id; ?>" />
  <input id="checkin" name="checkin" type="hidden" value="<?php echo $checkin; ?>" /><br />
  <input id="checkout" name="checkout" type="hidden" value="<?php echo $checkout; ?>" /><br >
  <input id="number_of_guests" name="number_of_guests" type="hidden" value="<?php echo $guests; ?>" />
  <input id="contact_key" name="contact_key" type="hidden" value="<?php echo $contact_key ?>" />
  <?php $this->session->set_userdata('booking_currency_symbol',get_currency_code());?>
  <div id="how_it_works" class="Box bookit_Box">
    <div class="Box_Head bookhead">
      <h2><?php echo translate("1. How it works"); ?></h2>
    </div>
    <div class="Box_Content">
      <p> <?php echo translate("Please provide your billing details now and the place shall be booked for your purpose"); ?> </p>
      <div class="clear"></div>
    </div>
  </div>
  
  <div id="property_details" class="Box bookit_Box">
    <div class="Box_Head bookhead">
      <h2><?php echo translate("2. Property details"); ?></h2>
    </div>
    <div class="Box_Content"> <img src="<?php echo getListImage($id); ?>" class="main_photo"/>
      <div id="hosting_details">
        <h2><a href=<?php echo base_url().'rooms/'.$id; ?> target="_blank"><?php echo $tit; ?></a></h2>
        <div id="hosting_address" class="rounded_more">
		
		 <?php //echo $address; ?>
		 <!-- Deepak Address Number Remove Ends -->
		  </div>
        <div class="clear"></div>
        <p><?php echo $room_type; ?></p>
        <p><?php echo translate("Accommodates "); ?><?php echo $guests; ?> <?php echo translate("guests"); ?></p>
      </div>
      <div class="clear"></div>
    </div>
  </div>
  <div id="trip_details" class="Box bookit_Box">
    <div class="Box_Head bookhead">
      <h2><?php echo translate("3. Trip Details"); ?></h2>
    </div>
    <div class="Box_Content">
    	
    <ul id="details_breakdown" class="dashed_table" >
    	
      <li class="top med_12 mal_12 pe_12 padding-zero"> <span class="label med_3 mal_3 pe_12"><span class="inner"><span class="checkout_icon" id="icon_check_in"></span><?php echo translate("Check in"); ?></span></span> <span class="data med_9 mal_9 pe_12"><span class="inner"> <?php echo get_user_times(get_gmt_time(strtotime($checkin)), get_user_timezone()); ?> <em class="check_in_out_time"><?php echo translate("(Flexible check in time)"); ?></em></span></span> </li>
						
      <li class="med_12 mal_12 pe_12 padding-zero"> <span class="label med_3 mal_3 pe_12"><span class="inner"><span class="checkout_icon" id="icon_check_out"></span><?php echo translate("Check out"); ?></span></span> <span class="data med_9 mal_9 pe_12"><span class="inner"> <?php echo get_user_times(get_gmt_time(strtotime($checkout)), get_user_timezone()); ?> <em class="check_in_out_time"><?php echo translate("(Flexible check out time)"); ?></em></span></span> </li>
						
      <li class=" med_12 mal_12 pe_12 padding-zero"> <span class="label med_3 mal_3 pe_12"><span class="inner"><span class="checkout_icon" id="icon_nights"></span><?php echo translate("Nights"); ?></span></span> <span class="data med_9 mal_9 pe_12"><span class="inner"><?php echo $days; ?></span></span> </li>
						      
      <li class="bottom med_12 mal_12 pe_12 padding-zero"> <span class="label med_3 mal_3 pe_12"><span class="inner"><span class="checkout_icon" id="icon_person"></span><?php echo translate("Guests"); ?></span></span> <span class="data med_9 mal_9 pe_12"><span class="inner"><?php echo $guests;  ?></span></span> </li>
     
						
      <div class="clear"></div>
    </ul>
    <ul id="price_breakdown" class="dashed_table">
    
    	<?php
    	if(isset($avg_price))
		{
    	?>
    	

      <li class="top med_12 mal_12 pe_12 padding-zero"> <span class="label med_3 mal_3 pe_12"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Average Rate (per night)"); ?> </span> </span> <span class="data med_9 mal_9 pe_12 padding-zero"> <span class="inner"> <?php echo get_currency_symbol($id).get_currency_value1($id,$avg_price); ?><sup></sup><span class="optional_usd">

     <!-- <li class="top"> <span class="label  med_4  pe_5"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Average Rate (per night)"); ?> </span> </span> <span class="data  med_8 pe_7"> <span class="inner"> <?php echo get_currency_symbol($id).$avg_price; ?><sup></sup><span class="optional_usd">-->

        </span> </span> </span> </li>
								<?php } else { ?>
								<li class="top med_12 mal_12 pe_12 padding-zero"> <span class="label med_3 mal_3 pe_12"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span>
									<?php echo translate("Rate (per night)");
									 ?> </span> </span> <span class="data med_9 mal_9 pe_12 padding-zero"> <span class="inner"> 
									 	<?php echo get_currency_symbol($id).get_currency_value1($id,$price); 
									 	$this->session->set_userdata('per_night',get_currency_symbol($id).get_currency_value1($id,$price));
									 	?><sup></sup><span class="optional_usd">
        </span> </span> </span> </li>	
									<?php } ?>
									<?php 
									if($cleaning != 0)
									{
									?>
		 <li> <span class="label med_3 mal_3 pe_12"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Cleaning Fee"); ?> </span> </span> <span class="data med_9 mal_9 pe_12 padding-zero"> <span class="inner"> <?php echo get_currency_symbol($id).get_currency_value1($id,$cleaning); ?><sup></sup><span class="optional_usd">
        </span> </span> </span> </li>
        <?php } ?>
        <?php if($security != 0)
		{ ?>
         <li > <span class="label med_3 mal_3 pe_12"> <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Security Fee"); ?> </span> </span> <span class="data med_9 mal_9 pe_12 padding-zero"> <span class="inner"> <?php echo get_currency_symbol($id).get_currency_value1($id,$security); ?><sup></sup><span class="optional_usd">
        </span> </span> </span> </li>
        <?php } ?>	
         <?php 
         if(isset($extra_guest_price))
		 {
         if($extra_guest_price != 0)
		{ ?>
        <li> <span class="label med_3 mal_3 pe_12" > <span class="inner"><span class="checkout_icon" id="icon_rate_pn"></span><?php echo translate("Additional Guest Fee"); ?> </span> </span> <span class="data med_9 mal_9 pe_12 padding-zero"> <span class="inner"> <?php echo get_currency_symbol($id).get_currency_value1($id,$extra_guest_price); ?><sup></sup><span class="optional_usd">
        </span> </span> </span> </li>
        <?php }
		 } ?>						

      <li class="med_12 mal_12 pe_12 padding-zero padding-zero padding-zero"> <span class="label med_3 mal_3 pe_12"><span class="inner"><span class="checkout_icon" id="icon_sub_tot"></span> <?php echo translate("Subtotal"); ?> </span></span> <span class="data med_9 mal_9 pe_12 padding-zero"> <span class="inner"> <?php echo get_currency_symbol($id).$subtotal; ?><sup></sup><span class="optional_usd">

    <!--  <li class="top"> <span class="label  med_4  pe_5"><span class="inner"><span class="checkout_icon" id="icon_sub_tot"></span> <?php echo translate("Subtotal"); ?> </span></span> <span class="data  med_8 pe_7"> <span class="inner"> <?php echo get_currency_symbol($id).$subtotal; if($offer == 1) echo '(Special offer used.)';?><sup></sup><span class="optional_usd">-->

		<?php $this->session->set_userdata('subtotal',$subtotal);?>						
        </span> </span> 
        <?php
        if($this->session->userdata('coupon_code_used') != 1)
		{
			?>
        <a id="coupon_show_link" class="coupon5" onclick="$('coupon_show_link').hide(); $('coupon').show();$('referral').hide();" href="javascript:void(0);"><?php echo translate("Coupon Code?"); ?></a> 
        <?php } ?>
        </span> </li>
								
      <li style="display:none;" id="coupon" class="coupon6"> <span class="label med_3 mal_3  pe_5"><span class="inner"><span id="icon_coupon" class="checkout_icon"></span><?php echo translate("Coupon"); ?></span></span> <span class="data med_9 mal_9 pe_12 padding-zero"> <span class="inner coupon3"> <span id="coupon_fields">
        <p><input type="text" value="" name="coupon_code" id="coupon_code" class="active" autocomplete="off">
        
	<input type="submit" name="apply_coupon" value="<?php echo translate("Apply Coupon"); ?>" class="Butt_Normal" />
        
								</p>
        </span> </span> 
        <?php
        if($this->session->userdata('coupon_code_used') != 1)
		{
			?>
        <a id="coupon_hide_link" class="coupon4" onclick="$('coupon_show_link').show(); $('coupon').hide();$('referral').show();" href="javascript:void(0);"><?php echo translate("cancel"); ?></a> 
        <?php } ?>
        </span> </li>
				
								
       <li class="med_12 mal_12 pe_12 padding-zero"> <span class="label med_3 mal_3  pe_12 checkouticon"> <span class="inner"><span class="checkout_icon" id="icon_commi"></span><?php echo translate("Commission");?> </span> </span> <span class="data med_9 mal_9 pe_7 padding-zero"> <span class="inner"> <?php echo get_currency_symbol($id).$commission; ?> </span> </span> </li>
      <span id="referral"></span>
      <?php 
      if(isset($referral_amount))
	  {
      ?>
	 <li class="top"> <span class="label" > <span class="inner"><span class="checkout_icon" id="icon_commi"></span><?php echo translate("Referral Amount");?> </span> </span> <span class="data"> <span class="inner"> 
	  	<?php  	
	  	if($referral_amount > 100)
		{
			echo get_currency_symbol($id).get_currency_value(100); 
		}
		else
			{
				echo get_currency_symbol($id).get_currency_value($referral_amount); 
			}
			?> 
	  	</span> </span> </li> 
	  <?php 
	  }
	  ?>
	  <li class="bottom med_12 mal_12 pe_12 padding-zero" id="total"> <span class="label med_3 mal_3  pe_5"><span class="inner"><span class="checkout_icon icon_total"></span><?php echo translate("Total"); ?></span></span> <span class="data med_9 mal_9 pe_12"> <span class="inner">
	  	<?php 

	  	if((isset($referral_amount)) && ($subtotal > $referral_amount))
	  {
	  	if($referral_amount > 100)
		{
			$final_amt = $amt-get_currency_value(100);
		}
		else
			{
				$final_amt = $amt-get_currency_value($referral_amount);
			}
			
	  	echo get_currency_symbol($id).$final_amt; 
		$this->session->set_userdata('final_amount',$final_amt);
	  }
      else
       {
        
	  	echo get_currency_symbol($id).$amt; 
		$this->session->set_userdata('final_amount',$amt);
		//echo $amt;
       }
	  	?> 
	  	<sup></sup><span class="optional_usd"></sup></span> </span> </span> </li>
	 
	  </ul>
    
    <div class="clear"></div>
    
    </div> 	
  </div>
  <?php echo form_close(); ?>
  <?php if($full_cretids == 'off') { ?>
  <?php echo form_open('payments/payment/'.$id.'/'.$env, array("id" => "PaymentsForm")); ?>
  <?php
						  $value  = '';
								
								
								if($result[0]->is_enabled == 1)
								{
								$showP  = '';
									if($value  == '')
									{
											$value  = 'paypal';
									}
								}
								else
								{
								$showP  = ' payment_method_hidden';
								}
								
								//brain tree 1 start
                                                             
                                                                //brain tree 1 end
								
								?>
 <div id="payment_options" class="Box bookit_Box">
    <div class="Box_Head bookhead">
      <h2><?php echo translate("4. Payment options"); ?> <span id="country_name" style="display:none;"></span></h2>
    </div>
    <div class="payment_tabs pe_12">
 				  <div id="payment_method_paypal" class="payment_method_content<?php echo $showP; ?> ">
        <input type="hidden" value="USD" name="payment_method_paypal_currency" id="payment_method_paypal_currency">
        <h2><?php echo translate("PayPal"); ?></h2>
	   <?php	   
	   if(isset($referral_amount))
	  {
	  	?>
	    <div class="currency_alert"><?php echo translate("This payment transacts in"); ?> <?php echo get_currency_symbol($id).get_currency_code(); ?>. <?php echo translate("Your total charge is"); ?> <?php echo get_currency_symbol($id).' '.$final_amt; ?>.</div>
		<?php }
	   else
	   	{ ?>
	   	<div class="currency_alert"><?php echo translate("This payment transacts in"); ?> <?php echo get_currency_symbol($id).get_currency_code(); ?>. <?php echo translate("Your total charge is"); ?> <?php echo get_currency_symbol($id).$amt; ?>.</div>
	   	<?php
	   	} ?>
		<div class="paypal_explanation med_2 mal_3 pe_6"></div>
        <p class="payment_method_explanation med_10  mal_9 pe_6">
        	<span class="paypallogo"><?php echo translate("Instructions"); ?>:</span> <br>
          <?php echo translate("After clicking 'Book it' you will be redirected to PayPal to complete payment."); ?> <span class="paypallogo"> <?php echo translate("You must complete the process or the transaction will not occur."); ?> </span> </p>
        <div class="clear"></div>
      </div>
  <!-- brain tree 2 start-->

<!-- brain tree 2 end-->
 </div>
</div>
  <div id="policies" class="Box bookit_Box" >
    <div class="Box_Head bookhead">
      <h2><?php echo translate("5. Policies");?></h2>
    </div>
    <div class="Box_Content policies1"> 
    <ul class="dashed_table"  >
       <li ><span class="label med_3 mal_3  pe_5"> <span class="inner"><span class="checkout_icon" id="icon_crossed_out"></span><?php echo translate("Cancellation"); ?></span> </span> <span class="data med_9 mal_9 pe_12"> <span class="inner"> 
      	
      	<!--<a href="<?php echo site_url('pages/cancellation_policy'); ?>" target="_blank"><?php echo translate("Flexible");?></a>-->
      			   
			   <?php if($policy !='' && !isset($flash_message)) {
			   	 ?>
	  	<a target="_blank" href="<?php echo site_url('pages/cancellation_policy/#'.str_replace(' ','-',$policy).''); ?>">
	  	<?php echo $policy;?> 
	  	</a>
	  	 <?php } 
else if(isset($flash_message))
{
	$policy = $this->Common_model->getTableData('cancellation_policy',array('id'=>5))->row()->name;
	?>
	<a target="_blank" href="<?php echo site_url('pages/cancellation_policy/#'.str_replace(' ','-',$policy)); ?>">
	  	<?php echo $policy;?> 
	  	</a>
	<?php
}
	  	 else { echo translate("Not Available"); } ?>
			         	
      	</span> </span> </li>
      <li class="bottom"> <span class="label med_3 mal_3  pe_12"> <span class="inner"><span class="checkout_icon" id="icon_house"></span><?php echo translate("House Rules"); ?></span> </span> <span class="data med_9 mal_9 pe_12"> <span class="inner"> <a href="javascript:void(0);" oncontextmenu="return false" onclick="show_super_lightbox('house_rules_data'); return false;" href="javascript:void(0);"><?php echo translate("Read Policy"); ?></a> </span> </span> </li>
  
          <div class="clear"></div>
            </ul>
            

    </div>
  </div>
  
  <input type="hidden" value="<?php echo $value; ?>" name="payment_method" id="payment_method">
  
  <div class="book_it_section" >
    <p id="book_it_fine_print">
      <input type="checkbox" id="agrees_to_terms" name="agrees_to_terms" onclick="$('p4_book_it_button').disabled = '';" />
      <span><?php echo translate("I agree to the cancellation policy and house rules.");?></span></p>
    
    <input type="hidden" value=<?php echo $checkin; ?> name="checkin" />
    <input type="hidden" value=<?php echo $checkout; ?> name="checkout" />
    <input type="hidden" value=<?php echo $guests; ?> name="number_of_guests" />
    <input id="contact_key" name="contact_key" type="hidden" value="<?php echo $contact_key ?>" />
	
	<!-- Deepak Security Deposit Amount Starts -->
	<?php //if($security->security_deposit_status ==1){ ?>
	<!-- <input type="hidden" value=<?php //echo $final_security_amount; ?> name="final_security_amount" /> -->
	 <?php $roomtotal=$subtotal;?>
     <input type="hidden" value=<?php echo $roomtotal; ?> name="roomtotal" />
     <?php $commission= $commission; ?>
       <input type="hidden" value=<?php echo $commission; ?> name="commission" />
	<?php
	 $subtotal=$amt; ?> 
	<input type="hidden" value=<?php echo $subtotal; ?> name="subtotal" />
	<?php //} ?> 
	
	<!-- Deepak Security Deposit Amount Ends -->
	
  
    <p>

				<input type="button" name="book_it_button" id="p4_book_it_button" class="btn_dis large blue" value="<?php echo translate("Book it"); ?>"  />
    </p>

			<!--	<input type="button" name="book_it_button" id="p4_book_it_button" class="btn large blue" value="<?php echo translate("Book it"); ?>"  />
 </p> -->


  
  <?php echo form_close(); ?>
  <?php } else { ?>
  <div id="policies" class="Box">
    <div class="Box_Head bookhead policies2">
      <h1><?php echo translate("5. Policies");?></h1>
    </div>
    <div class="Box_Content"> 
    <ul class="dashed_table">
      <li class="top"> <span> <span class="inner"><span class="checkout_icon" id="icon_crossed_out"></span><?php echo translate("Cancellation");?></span> </span> <span class="data"> <span class="inner"> <a href="<?php echo site_url('pages/cancellation_policy'); ?>" target="_blank"><?php echo translate("Flexible");?></a> </span> </span> </li>
      <li class="bottom"> <span> <span class="inner"><span class="checkout_icon" id="icon_house"></span><?php echo translate("House Rules");?></span> </span> <span class="data"> <span class="inner"> <a href="javascript:void(0);" oncontextmenu="return false" onclick="show_super_lightbox('house_rules_data'); return false;" target="_blank"><?php echo translate("Read Policy");?></a></span> </span> </li>
    
          <div class="clear"></div>
</ul>
    </div>
  </div>
  <p id="book_it_fine_print">
    <input type="checkbox" id="agrees_to_terms" name="agrees_to_terms" onclick="$('p4_book_it_button').disabled = '';" />
    <span><?php echo translate("I agree to the cancellation policy and house rules.");?></span></p>
   <?php echo form_open('referrals/booking/'.$this->uri->segment(3)); ?>
  <input type="hidden" value=<?php echo $checkin; ?> name="checkin" />
  <input type="hidden" value=<?php echo $checkout; ?> name="checkout" />
  <input type="hidden" value=<?php echo $guests; ?> name="number_of_guests" />
  <input id="contact_key" name="contact_key" type="hidden" value="<?php echo $contact_key ?>" />
 <p>
  <input type="button" name="book_it_button" id="p4_book_it_button" class="Butt_Normal" value="<?php echo translate("Book it"); ?>"  />
</p>
  <?php echo form_close(); ?>
  <?php } ?>
  <!-- /Form -->
</div>

<!-- #book_it -->
<!-- .narrow_page_bg -->

<div id="house_rules_data" class="super_lightbox"> <a class="hide_super_lightbox" href="javascript:void(0);" onclick="hide_super_lightbox('house_rules_data'); return false;">
  <?php echo "[X] Close"; ?>
  </a>
  <h3><?php echo translate("House Rules");?></h3>
  <h4><?php 
if($manual == '')
{
echo 'No House Rules for this list.	';
}
else {
echo $manual;
} ?></h4>
</div>
<div id="security_deposit_data" class="super_lightbox"> <a class="hide_super_lightbox" href="javascript:void(0);" onclick="hide_super_lightbox('security_deposit_data'); return false;">
  <?php echo "[X] Close"; ?>
  </a>
  <p class="policies3"> <?php echo translate("If the host reports a problem, we will capture the entire authorized amount while we gather additional information from both parties. If a compromise is reached, we will refund the agreed upon amount. Although it is primarily up to the host to determine the extent of the damage, We tracks every claim
that is made, and if a host develops a trend of claiming damages in order to keep the security deposit, the host may removed from ".$this->dx_auth->get_site_title()."."); ?> </p>
</div>
<div id="pricing_explanation" class="super_lightbox"> <a class="hide_super_lightbox" href="javascript:void(0);" onclick="hide_super_lightbox('pricing_explanation'); return false;">
  <?php echo "[X] Close"; ?>
  </a>
  <h3><?php echo translate("Subtotal Breakdown");?></h3>
  <p class="subtotal1"> 
		<?php echo translate("A variety of factors contribute to how the subtotal is calculated. Below is a detailed explanation.");?> </p>
  </div>
<div id="transparent_bg_overlay"></div>
	<?php
}
?>

<script type="text/javascript">
jQuery.noConflict();
var alreadySubmitted = false;
jQuery(document).ready(function()
{
	jQuery('#p4_book_it_button').click(function()
	{
		jQuery('#p4_book_it_button').disabled = 'disabled';
	})
})
    function clean_up_and_book_it(){

        if(alreadySubmitted === false){

            $('p4_book_it_button').disabled = 'disabled';
            $('p4_book_it_button').setStyle({cursor:'progress'});
            $('book_it_click_message').show();

            for(x = 0; x < fields_to_clear_on_submit.size(); x++){
                f = fields_to_clear_on_submit[x];
                if(f.defaultValue == f.value){
                    f.value = '';
                }
            }

            $('book_it_form').submit();

            alreadySubmitted = true;
        } else {
            //this is a double submit
            return false;
        }

    }
    function update_payment_options(country_code){
            $('ajax_method_spinner').show();

        //alert("update_payment_options");

        while(global_tabber_obj.tabs.size() > 1){
            removeLastTab();
        }

        $('payment_method').value = payment_method_value;

        $('credit_card_country').value = country_code;
    }

    function apply_error_class_to_ajax_fields_with_errors(){
        error_count = ajax_fields_with_errors.size();
        for(i=0;i<error_count;i++){
            if($(ajax_fields_with_errors[i])){
                $(ajax_fields_with_errors[i]).addClassName('error');
            }
        }

    }

    function update_labels_if_js(){
        move_all_labels_to_adjacent_inputs();
        remove_if_js();
    }

    function select_active_tab(){
        if($('payment_method_tab_link_' + payment_method_value)){
            $('payment_method_tab_link_' + payment_method_value).onclick();
        }
        else{
            //default to showing CC instead of nothing
            $('payment_method_tab_link_<?php echo $value; ?>').onclick();
        }
    }

    function update_p4_book_it_button(method){
        $('p4_book_it_button').value = "Book it using" + " " + method;
    }

    function move_all_labels_to_adjacent_inputs(){
        $$('label.inner_text').each(function(e){
            move_label_to_input(e);
        });
    }

    function move_label_to_input(e){
        input_field = e.next('input');

        user_value = input_field.value;
        input_field.defaultValue = e.innerHTML;
        input_field.value = e.innerHTML;

        //if(input_field.value.length == 0){
        if(user_value.length == 0){
            //input_field.defaultValue = e.innerHTML;
        } else {
            input_field.value = user_value;
            input_field.addClassName('active');
        }

        input_field.observe('focus', function(){
            if(this.value==this.defaultValue) { this.value=''; this.addClassName('active'); }
            this.removeClassName('error');
        });
        input_field.observe('blur', function(){
            if(this.value=='') { this.value=this.defaultValue; this.removeClassName('active');} else { this.removeClassName('error'); }
        });

        fields_to_clear_on_submit.push(input_field);
        e.remove(); 
    }

    function remove_if_js(){
        $$('.remove_if_js').each(function(e){
            e.remove(); 
        });
    }

    jQuery('document').ready(function(){
//        $('p4_book_it_button').disabled = 'disabled';

        fields_to_clear_on_submit = new Array();

        //these are fields that need to be given class="error" after a new payment method is loaded via ajax
        ajax_fields_with_errors = new Array();

        move_all_labels_to_adjacent_inputs();
        remove_if_js();

        payment_options_tabber_obj = tabberAutomatic();
								<?php  if($result[0]->is_enabled == 1) { ?>
						  addTab('PayPal', 'payment_method_paypal', 'paypal');
								<?php } 
                                                                 //brain tree 3 start
								
							//brain tree 3 end	                                                               

                                                                 ?>

        //This will show CC right away
        select_active_tab();

jQuery('#b_tree').click(function(){
jQuery('#payment_method').val('braintree');
//alert(jQuery('#payment_method').val())
});
        $('payment_method').value = payment_method_value;
        jQuery('#existing_cc').change();

            //update_payment_options('IN');
        

        jQuery('#coupon_code').blur(function(){
            jQuery('#coupon_code').removeClass("coupon_focus");
        }).focus(function() {                
            jQuery('#coupon_code').addClass("coupon_focus")
        });

        jQuery('input').keypress(function (e) {
           if ( e.keyCode == 13 ){
               if(jQuery('#coupon_code').hasClass('coupon_focus')){
                   $('submit_context').value = 'apply_coupon';
                   clean_up_and_book_it();
               }else{
                   $('submit_context').value = 'book_it_button';
                   clean_up_and_book_it();
               }
               return false;    
            }
        });
				var i = 0;
				//alert('hi'); return false;				
 jQuery('#p4_book_it_button').click(function(){
	 if($('agrees_to_terms').checked)
		{
								 
										 	//$('p4_book_it_button').disabled = ''; 
			}
			else
			{
			//alert('success');							// $('p4_book_it_button').disabled = 'disabled'; 
										//alert('checked');
			return false;
										
			}
 });
   jQuery('#agrees_to_terms').click(function(){
								
				 if($('agrees_to_terms').checked)
					{
					 	$('p4_book_it_button').disabled = ''; 
					}
					else
					{
					
					
					// $('p4_book_it_button').disabled = 'disabled'; 
					
					}
				}); 
									
						jQuery('#p4_book_it_button').click(function(){
						
								if(jQuery("#agrees_to_terms").prop('checked'))
									{
									 	//$('p4_book_it_button').disabled = ''; 

									 	//alert('success');
									 	
											 	if(i == 1)
											 	{
											 	   
											 		return false;
											 	    
											 	}
											   	i = 1;
									
									  
									}
							else
							{
							// $('p4_book_it_button').disabled = 'disabled'; 
							
							
							     alert('Please select the Cancellation policy and House rules agreement.');
							     return false;
							
							}
						
                                      
                                      
				var urldata = '<?php echo base_url();?>rooms/check_ban_user';
       jQuery.ajax({
   url: urldata,
   success:function(data)
   {
		    if(data == "Banned user")
		    {
		    	 if ($.browser.msie)
		    	 {
		    	 }
		    	 else
		    	 {
		    	 	window.location.assign("<?php echo base_url(); ?>users/signin");
		    	 }
	         }
   }
           
});

								var flag = 0;
								if(jQuery('#payment_method').val() == 'cc')
								{
								if(jQuery('#firstName').val() == "")
								{
								  flag = 0;
								  jQuery('#EfirstName').show();
								}
								else
								{
								  jQuery('#EfirstName').hide();
										flag = 1;
								}
								
								if(jQuery('#lastName').val() == "")
								{
								  flag = 0;
								  jQuery('#ElastName').show();
								}
								else
								{
								  jQuery('#ElastName').hide();
										flag = 1;
								}
								
								if(jQuery('#creditCardNumber').val() == "")
								{
								  flag = 0;
								  jQuery('#EcreditCardNumber').show();
								}
								else
								{
								  jQuery('#EcreditCardNumber').hide();
										flag = 1;
								}
								
								if(jQuery('#cvv2Number').val() == "")
								{
								  flag = 0;
								  jQuery('#Ecvv2Number').show();
								}
								else
								{
								  jQuery('#Ecvv2Number').hide();
										flag = 1;
								}
								
								if(jQuery('#address1').val() == "")
								{
								  flag = 0;
								  jQuery('#Eaddress1').show();
								}
								else
								{
								  jQuery('#Eaddress1').hide();
										flag = 1;
								}
								
								if(jQuery('#city').val() == "")
								{
								  flag = 0;
								  jQuery('#Ecity').show();
								}
								else
								{
								  jQuery('#Ecity').hide();
										flag = 1;
								}
								
							 if(jQuery('#state').val() == "")
								{
								  flag = 0;
								  jQuery('#Estate').show();
								}
								else
								{
								  jQuery('#Estate').hide();
										flag = 1;
								}
								
								if(jQuery('#zip').val() == "")
								{
								  flag = 0;
								  jQuery('#Ezip').show();
								}
								else
								{
								  jQuery('#Ezip').hide();
										flag = 1;
								}
								}
								else
								{
								flag =1;
								}
								
								if(flag == 1)
								{
								jQuery('#PaymentsForm').submit(); 
								}
								 
								});				
											
    });

   var payment_method_value = '<?php echo $value; ?>';
   
	<?php if($this->session->userdata('coupon_code_used') == 1)
	{
		?>
		
		$('#coupon_show_link').hide();
		<?php 
	}
	?>
	
	
</script>