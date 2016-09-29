<script src="<?php echo base_url(); ?>js/jquery.countdown.js" type="text/javascript"></script>
<style>
	#urle{
		display:none;
	}
</style>
<link href="<?php echo css_url().'/reservation.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
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
      <div style="clear:both"></div>
    </div>

</div>
<div id="main_reserve" class="med_9 mal_9 pe_12 padding-zero pad_list_req">
 <div class="Box">
        <div class="Box_Head padding-zero">
		<h2 class="req_head"><?php echo "Contact Response"; ?> </h2></div>
<div class="Box_Content res_cent med_12 mal_12 pe_12">
<ul id="details_breakdown_1" class="dashed_table_1 clearfix">
<li class="top clearfix">
<span class="label med_4 mal_4 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Property"); ?></span></span>
<span class="data med_8 mal_8 pe_12"><span class="inner"><?php echo get_list_by_id($result->list_id)->title; ?></span></span>
</li>

<li class="clearfix">
<span class="label med_4 mal_4 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Check in"); ?></span></span>
<span class="data med_8 mal_8 pe_12"><span class="inner"><?php echo date("F j, Y",strtotime($checkin)); ?></span></span>
</li>

<li class="clearfix">
<span class="label med_4 mal_4 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Check out"); ?></span></span>
<span class="data med_8 mal_8 pe_12"><span class="inner"><?php echo date("F j, Y",strtotime($checkout)); ?></span></span>
</li>

<li class="clearfix">
<span class="label med_4 mal_4 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Night"); ?></span></span>
<span class="data med_8 mal_8 pe_12"><span class="inner"><?php echo $nights; ?></span></span>
</li>

<li class="clearfix">
<span class="label med_4 mal_4 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Guest"); ?></span></span>
<span class="data med_8 mal_8 pe_12"><span class="inner"><?php echo $no_quest; ?></span></span>
</li>
<?php if($status==4) { ?>
<li class="bottom">
<span class="label med_4 mal_4 pe_12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Message"); ?></span></span>
<span class="data med_8 mal_8 pe_12"><span class="inner"><?php echo $message; ?></span></span>
</li>
<?php } else { ?>
<li class="clearfix">
		<span class="label status1 col-md-4 col-sm-5 col-xs-12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span>
<!---->
<?php echo translate("URL for Booking"); ?>
<?php if($status== 3) { ?>
	<span id="expires_in">
<?php echo translate("Expires in"); ?></span>
<?php
$hourdiff = round((time() - $result->send_date)/3600, 0);
$reservation_id   	= $this->uri->segment(3);

$timestamp = $result->send_date;
$send_date = date('m/d/Y', $timestamp);
$gmtTime   = get_gmt_time(strtotime('+24 hours',$timestamp));

$date      = gmdate('D, d M Y H:i:s +\G\M\T', $gmtTime);
?>
<div id="expire"></div>
<?php } ?>
</span></span>
<span class="data col-md-8 col-sm-8 col-xs-12"><span class="inner"><a id="url" href="<?php echo $url; ?>"><?php echo $url; ?></a>
<a href="#" id="urle"><?php echo Expired; ?></a>
</br>
</br>
</span></span>
</li>
<?php }?>
<li class="bottom">
<span class="label col-md-4 col-sm-4 col-xs-12"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Message"); ?></span></span>
<span class="data col-md-8 col-sm-8 col-xs-12"><span class="inner"><?php echo $message; ?></span></span>
</li>	
	
</ul>
</form>
<script type="text/javascript">

<?php if($status== 1) { ?>	

$('#expire').countdown({
		until: new Date("<?php echo $date; ?>"),
		format: 'dHMS',
		layout:'{hn}:'+'{mn}:'+'{sn}',
		onExpiry:liftOff,
		expiryText:"0:0:0"
		
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
  location.reload("true");		
			 	}
		  });	
}

$(document).ready(function()
	{
	
		if($('#expire').text() == '0:0:0')
		{ 
			//location.reload(true);
			$('#urle').show();
			$('#url').hide();
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
if(msg=='true')
    {
     location.reload(true);
    }
   
}
   
	})
	}
	})
	
<?php } ?>
</script>
</div>
</div>
</div>
</div>
</div>