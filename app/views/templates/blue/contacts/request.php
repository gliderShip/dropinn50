<script src="<?php echo base_url(); ?>js/jquery.countdown.js" type="text/javascript"></script>
<link href="<?php echo css_url().'/reservation.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<?php
// Print The Confrmation

$confirmation = '';
$confirmation .= '<p style="padding:5px 5px 5px 725px"><a style="color:#38859B;cursor:pointer;" onClick="javascript:window.print();"><strong>'.translate('Print').'</strong></a></p>';
$confirmation .= '<table border="1" width="100%">';

$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Property").'</td>';
$confirmation .= '<td>'.get_list_by_id($result->list_id)->title.'</tr>';
$confirmation .= '</tr>';

$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Check in").'</td>';
$confirmation .= '<td>'.get_user_times($checkin, get_user_timezone()).'</tr>';
$confirmation .= '</tr>';

$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Check out").'</td>';
$confirmation .= '<td>'.get_user_times($checkout, get_user_timezone()).'</tr>';
$confirmation .= '</tr>';

$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Nights").'</td>';
$confirmation .= '<td>'.$nights.'</tr>';
$confirmation .= '</tr>';

$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Guests").'</td>';
$confirmation .= '<td>'.$no_quest.'</tr>';
$confirmation .= '</tr>';

$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Message").'</td>';
$confirmation .= '<td>'.$message.'</tr>';
$confirmation .= '</tr>';

if($price_original=='')
{
$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Rate").'( '. translate("per night") .' )'.'</td>';
$confirmation .= '<td>'.get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$per_night).'</tr>';
$confirmation .= '</tr>';
}
else {
	$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Rate").'( '. translate("per night") .' )'.'</td>';
$confirmation .= '<td>'.get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$price_original).'</tr>';
$confirmation .= '</tr>';
}
if($cleaning != 0)
{
$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Cleaning Fee").'</td>';
$confirmation .= '<td>'.get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$cleaning).'</tr>';
$confirmation .= '</tr>';
}
if($security != 0)
{
$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Security Fee").'</td>';
$confirmation .= '<td>'.get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$security).'</tr>';
$confirmation .= '</tr>';
 } 
if(isset($extra_guest_price))
{
if($extra_guest_price != 0)
{
$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Additional Guest Fee").'</td>';
$confirmation .= '<td>'.get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$extra_guest_price).'</tr>';
$confirmation .= '</tr>';
 }
} 
 if($result->offer ==1)
	{
	/*	$special_offer = '(Special offer used.)';
	}
	else
		{
		 	
			$special_offer = '';
		}*/

 $special_offer = '(Special offer used.)';

$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Subtotal").'</td>';
$confirmation .= '<td>'.get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$subtotal).$special_offer.'</tr>';
$confirmation .= '</tr>';

}
else 
{
 $confirmation .= '<tr>';
 $confirmation .= '<td>'.translate("Subtotal").'</td>';
 $confirmation .= '<td>'.get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$subtotal).$special_offer = ''.'</tr>';
 $confirmation .= '</tr>';	
	
	
}





$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Total Payout").'</td>';
$confirmation .= '<td>'.get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$subtotal).'</tr>';
$confirmation .= '</tr>';

$confirmation .= '<tr>';
$confirmation .= '<td>'.translate("Status").'</td>';
$confirmation .= '<td>'.translate($result->name).'</tr>';
$confirmation .= '</tr>';

$confirmation .= '</table>';

?>

	<script type="text/javascript">
	function print_confirmation() {
		var myWindow;
		myWindow=window.open('','_blank','width=400,height=500');
		myWindow.document.write("<p><?php echo addslashes($confirmation); ?></p>");
		myWindow.print();
	}
	
	
		$(document).ready(function()
	{
		
		if($('#expire').text() == '0:0:0:0')
		{
			alert("dfgdfg");
			
			$('#timeout').hide();
			$('#expired1').show();
			$('#expires_in').hide();
			var ida='<?php echo $this->uri->segment(3); ?>';
			 var status ="2";
			$.ajax({
   type: "POST", 
   url: "<?php echo base_url();?>contacts/expire",
    data: {
                cid: ida,
                status: status
            },
   success: function(msg){
if(msg=="hai")
    {
     window.reload();
    }
   
}
   
	})
	}
	})
	
</script>
<div class="container_bg container">
<div id="Reserve_Continer">
<div id="View_Request" class="clearfix">
<div id="left" class="med_3 mal_3 pe_12 pad_rig">

    <!-- /user -->
    <div class="Box" id="quick_links">
      <div class="Box_Head">
        <h2><?php echo translate("Quick Links");?></h2>
      </div>
      <div class="Box_Content">
        <ul>
          <li><a href=<?php echo base_url().'listings'; ?>> <?php echo translate("View/Edit Listings"); ?></a></li>
          <li><a href="<?php echo site_url('listings/my_reservation'); ?>"><?php echo translate("Reservations"); ?></a></li>
        </ul>
      </div>
      <div class="clearfix"></div>
    </div>

</div>
<div id="main_reserve" class="med_9 mal_9 pe_12 padding-zero pad_list_req">
 <div class="Box">
        <div class="Box_Head">
		<h2 class="req_head padding-zero"><?php echo "Contact Request"; ?> </h2><span class="View_MyPrint padding-zero">
	 <a href="javascript:void(0);" onclick="javascript:print_confirmation();"><?php echo translate("Print");  ?></a>
		</span></div>
        <div class="Box_Content res_cent med_12 mal_12 pe_12">
								
		<?php if($result->status != 1) { ?>
		
		<?php } ?>
<ul id="details_breakdown_1" class="dashed_table_1 clearfix">
<li class="top clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Property"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo get_list_by_id($result->list_id)->title; ?></span></span>
</li>

<li class="clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Check in"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo get_user_times($checkin, get_user_timezone()); ?></span></span>
</li>

<li class="clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Check out"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo get_user_times($checkout, get_user_timezone()); ?></span></span>
</li>

<li class="clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Night"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo $nights; ?></span></span>
</li>

<li class="clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Guest"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo $no_quest; ?></span></span>
</li>

<li class="bottom">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Message"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo $message; ?></span></span>
</li>
</ul>


<ul id="details_breakdown_1" class="dashed_table_1 clearfix">

<li class="top clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Average Rate").'( '. translate("per night") .' )'; ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner">
<!-- <li class="top clearfix"> 
<span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Average Rate").'( '. translate("per night") .' )'; ?></span></span>
<span class="data"><span class="inner"> -->

	<?php 
	if($price_original != '')
	{
	echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$per_night); 
	}
	else {
		echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$price_original);
	}
	?></span></span>
</li>

<?php if($cleaning != 0)
{
	?>
<li class="clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Cleaning Fee"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$cleaning); ?></span></span>
</li>
<?php } ?>

<?php if($security != 0)
{
?>
<li class="clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Security Fee"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$security); ?></span></span>
</li>
<?php } ?>

<?php 
if(isset($extra_guest_price))
{
if($extra_guest_price != 0)
{
?>
<li class="clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Additional Guest Fee"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$extra_guest_price); ?></span></span>
</li>
<?php }
} ?>

<li class="clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Subtotal"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner">
	<?php 
	
	//echo $result->price;
	/*if($price_original == '' || $price_original == $subtotal)
	{
		$special_offer = '';
	}
	else
		{
		 	$special_offer = '(Special offer used.)';
		}
	
		echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$subtotal).$special_offer; 	*/
		
    //echo $result->status;   
    if($result->offer ==1)
	{
		$special_offer = '(Special offer used.)';
	}
	else
		{	
		 	
			$special_offer = '';
		}
	
		echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$subtotal).$special_offer; 
	
	?>
	
	</span></span>
</li>

<!--<li class="clearfix">
<span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo "Host fee"; ?></span></span>
<span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$commission); ?></span></span>
</li>-->

<li class="clearfix">
<span class="label med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Total Payout"); ?></span></span>
<span class="data med_8 mal_7 pe_12"><span class="inner"><?php echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$subtotal); ?></span></span>
<!--<span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Total Payout"); ?></span></span>
<span class="data"><span class="inner">-->
	<?php 
	 

	  	// echo get_currency_symbol($result->list_id).get_currency_value_lys($result->currency,get_currency_code(),$subtotal); 

	  
	  ?>
	</span></span>
</li>

<li class="clearfix bottom">
<span class="label status1 med_4 mal_5 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span>
<?php if($result->status == 1) { ?>
<?php echo ""; ?>
<?php } else {  ?>
<?php echo translate("Status"); ?>
<?php } ?>
<!---->
	<?php if($result->status == 1) { ?>
	

	<span id="expires_in">
<?php echo translate("Expires in"); ?></span>
<div id="expired1" style="display: none"><?php echo translate('Expired');?></div>
<?php
$hourdiff = round((time() - $result->send_date)/3600, 0);
$contacts_id   	= $this->uri->segment(3);

$timestamp = $result->send_date;
$send_date = date('m/d/Y', $timestamp);
$gmtTime   = get_gmt_time(strtotime('+24 hours',$timestamp));

$date      = gmdate('D, d M Y H:i:s +\G\M\T', $gmtTime);
?>
<div id="expire"></div>
<?php } else { ?>
<?php echo translate("Status"); ?>
<?php } ?>

	
<!---->
</span></span>

<?php if($result->status == 1) { ?>
<span id="timeout" class="data status2 med_8 mal_7 pe_12"><span class="inner">
<input type="hidden" name="contact_id" id="contact_id" value="<?php echo $result->id; ?>" />
<!-- Pre-Approve -->
<br><br>
<div id="approve">
<a class="Reserve_approve" id="req_approve" href="javascript:show_hide(1);"><?php echo "Pre-Approve"; ?></a>
</div>
<div id="approve_form" style="display:none">
<form name="approve_req" action="<?php echo site_url('contacts/accept'); ?>" method="post">
<p>
<p>
<input type="hidden" id="title" name="title" value="<?php echo $list; ?>" />
<input type="hidden" id="checkin" name="list" value="<?php echo $result->checkin; ?>" />
<input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />
<input type="hidden" id="guests" name="guests" value="<?php echo $no_quest; ?>" />
<input type="hidden" id="price_approve" name="price_approve" value="<?php echo get_currency_value_lys($result->currency,get_currency_code(),$subtotal); ?>" />	
</p>
<p><b>If Mark books your space, the reservation will be automatically accepted</b></p>
<p><?php echo translate("Optional message")."..."; ?></p>
<p><textarea class="comment_contact" name="comment_approve" id="comment_approve"></textarea></p>
<p>
<input type="button" class="accept_button" name="approved" value="<?php echo "Send message"; ?>" onclick="javascript:req_action('approve');" />
</p>
</form>
</div>
<br><br>
<!-- Special-offer -->
<div id="special">
<a class="Reserve_special" id="req_special" href="javascript:show_hide(2);"><?php echo "Make a special offer"; ?></a>
</div>
<div id="special_form" style="display:none">
<form name="special_req" action="<?php echo site_url('contacts/accept'); ?>" method="post">
<p>
<p>
<input type="hidden" id="title" name="title" value="<?php echo $list; ?>" />
<input type="hidden" id="checkin" name="list" value="<?php echo $result->checkin; ?>" />
<input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />
<input type="hidden" id="guests" name="guests" value="<?php echo $no_quest; ?>" />
<input type="hidden" id="currency_code" name="guests" value="<?php echo get_currency_code(); ?>" />	
</p>
<?php $currency_symbol = $this->Common_model->getTableData('currency',array('currency_code'=>get_currency_code()))->row()->currency_symbol; ?>
<p align="center"><b>Price <?php echo $currency_symbol;?>:</b> 
<input type="text" id="price_special" name="price_special" value="<?php echo get_currency_value_lys($result->currency,get_currency_code(),$subtotal); ?>" /></p>
<input type="hidden" id="price_original" name="price_original" value="<?php echo get_currency_value_lys($result->currency,get_currency_code(),$price_original); ?>" /></p>

<p align="center">Enter the price for the reservation including all additional costs</p>
<p></p>
<p><?php echo translate("Optional message")."..."; ?></p>
<p><textarea class="comment_contact" name="comment_special" id="comment_special"></textarea></p>
<p>
<input type="button" class="accept_button" name="offered" value="<?php echo "Send message"; ?>" onclick="javascript:req_action('special');" />
</p>
</form>
</div>
<br><br>

<!-- Disscuss-More -->
<div id="discuss">
<a class="Reserve_discuss" id="req_accept" href="javascript:show_hide(3);"><?php echo "Let's Discuss More"; ?></a>
</div>
<div id="discuss_form" style="display:none">
<form name="discuss_req" action="<?php echo site_url('contacts/discuss'); ?>" method="post">
<p>
<p>
<input type="hidden" id="title" name="title" value="<?php echo $list; ?>" />
<input type="hidden" id="checkin" name="checkin" value="<?php echo $result->checkin; ?>" />
<input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />	
</p>
<p><b>I need More information from the guest, or they need more information from me.</b></p>
<p></p>
<p>Use the space below to request additional information or answer questions from the guest.</p>
<p><?php echo translate("Add a personal message here")."..."; ?></p>
<p><textarea class="comment_contact" name="comment_discuss" id="comment_discuss"></textarea></p>
<p>
<input type="button" class="accept_button" name="discussed" value="<?php echo "Send message"; ?>" onclick="javascript:req_action('discuss');" />
</p>
</form>
</div>
<br><br>

<!-- Decline -->
<div id="decline_option">
<a class="Reserve_decline_contact" id="req_decline" href="javascript:show_hide(4);"><?php echo translate("Decline"); ?></a>
</div>
<div id="decline" style="display:none">
<form name="decline_req" action="<?php echo site_url('contacts/decline'); ?>" method="post">

<p><?php echo translate("Optional message")."..."; ?></p>
<p><textarea class="comment_contact" name="comment_decline" id="comment_decline"></textarea></p>

<p>
<input type="hidden" id="checkin" name="checkin" value="<?php echo $result->checkin; ?>" />
<input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />
<input type="button" class="decline_button" name="decliend" value="<?php echo translate("Decline"); ?>" onclick="javascript:req_action('decline');" />
</p>
</form>
</div>
</span>


</span>
<?php } else { ?>

<span class="data status2 med_8 mal_7 pe_12"><span class="inner">
<?php 
echo translate($result->name);
?>
</span></span>

<?php } ?>
</li>
</ul>
<div class="clearfix"></div>
</div>
        
        </div>
								</div>
								<div class="clearfix"></div>
								</div>
							</div></div>
<script type="text/javascript">

<?php if($result->status == 1) { ?>	

 $('#expire').countdown({
		until: new Date("<?php echo $date; ?>"),
		format: 'dHMS',
		layout:'{dn}:'+'{hn}:'+'{mn}:'+'{sn}',
		onExpiry: liftOff,
		expiryText:"Expired"
	});
 	
	
function liftOff()
{ 
				  var contact_id = $("#contact_id").val();
	
   	 $.ajax({
				 type: "POST",
					url: "<?php echo site_url('contacts/expire'); ?>",
					async: true,
					data: "contact_id="+contact_id,
					success: function(data)
		  	{	
						location.reload(true);
			 	}
		  });			
			 
}

<?php } ?>

function show_hide(id)
{
		if(id == 1)
		{	
		 $('#approve_form').show();
		 $('#special_form').hide();
		 $('#discuss_form').hide();
		 $('#decline').hide();
		}
		else if(id == 2)
		{
		 $('#approve_form').hide();
		 $('#special_form').show();
		 $('#discuss_form').hide();
		 $('#decline').hide();	
		}
		else if(id == 3)
		{
		 $('#approve_form').hide();
		 $('#special_form').hide();
		 $('#discuss_form').show();
		 $('#decline').hide();
		}
		else
		{
		 $('#approve_form').hide();
		 $('#special_form').hide();
		 $('#discuss_form').hide();
		 $('#decline').show();
		}	
}


function req_action(id)
{
	 var message = $('#comment_approve').val();
		 if(/\b(\w)+\@(\w)+\.(\w)+\b/g.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
            else if(message.match('@') || message.match('hotmail') || message.match('gmail') || message.match('yahoo'))
				{
					alert('Sorry! Email or Phone number is not allowed');return false;
				}
         	if(/\+?[0-9() -]{7,18}/.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
            
            var message2 = $('#comment_special').val();
		 if(/\b(\w)+\@(\w)+\.(\w)+\b/g.test(message2))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
            else if(message2.match('@') || message2.match('hotmail') || message2.match('gmail') || message2.match('yahoo'))
				{
					alert('Sorry! Email or Phone number is not allowed');return false;
				}
         	if(/\+?[0-9() -]{7,18}/.test(message2))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
            
            var message3 = $('#comment_discuss').val();
		 if(/\b(\w)+\@(\w)+\.(\w)+\b/g.test(message3))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
            else if(message3.match('@') || message3.match('hotmail') || message3.match('gmail') || message3.match('yahoo'))
				{
					alert('Sorry! Email or Phone number is not allowed');return false;
				}
         	if(/\+?[0-9() -]{7,18}/.test(message3))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
             2
            var message4 = $('#comment_decline').val();
		 if(/\b(\w)+\@(\w)+\.(\w)+\b/g.test(message4))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
            else if(message4.match('@') || message4.match('hotmail') || message4.match('gmail') || message4.match('yahoo'))
				{
					alert('Sorry! Email or Phone number is not allowed');return false;
				}
         	if(/\+?[0-9() -]{7,18}/.test(message4))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
            
 var contact_id = $("#contact_id").val();
	 
 if(id == "approve")
	{
	var price    = $("#price_approve").val();
	var comment  = $("#comment_approve").val();
	var action = "accept";
	}
else if(id == "special")
	{
	var price    = $("#price_special").val();
	var original_price = $("#price_original").val();	
	var comment  = $("#comment_special").val();
	var action	 = "special";
	}
else if(id == "discuss")
	{
	var comment  = $("#comment_discuss").val();
	var action	 = id;
	}
else
	{
	var comment  = $("#comment_decline").val();
	var action	 = id;
	}
	
	var checkin   = $("#checkin").val();
	var checkout  = $("#checkout").val();
	
	if(id == "discuss")
	var ok=confirm("Are you sure to "+action+"?");
	else
	var ok=confirm("Are you sure to "+action+" request?");
		if(!ok)
		{
			return false;
		}
		else
		{
		 $("input.accept_button").attr("disabled", true);
		  $("input.decline_button").attr("disabled", true);
		}
		document.getElementById(id).innerHTML = '<img src="<?php echo base_url().'images/loading.gif' ?>">';
		
	   $.ajax({
				 type: "POST",
					url: "<?php echo site_url('contacts'); ?>/"+action,
					async: true,
					data: "comment="+comment+"&contact_id="+contact_id+"&checkin="+checkin+"&checkout="+checkout+"&price="+price+"&price_original="+original_price,
					success: function(data)
		  			{	
		  				if(data)
		  				{
		  				//alert(data);
		  				}
					 //document.getElementById(id).innerHTML = data;
					 location.reload(true);
			 	}
		  });
}

</script>
<style>
	@media (max-width: 767px) and (min-width: 300px){
#details_breakdown_1 .data {
border: none !important;
border-radius: 0 !important;
}
#details_breakdown_1 .bottom .data {
border-radius: 0 0 10px 10px !important;
}
	}
</style>
