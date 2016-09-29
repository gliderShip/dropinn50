<script type="text/javascript">
$(document).ready(function(){
	
  var cancellation_id = $('.name_tab').attr('data');
  
  $('.name_tab').each(function()
  {
  	 var hide_value = $(this).attr('data');
  	 $('#Box_Content'+hide_value).hide();
  	 $('#active_'+hide_value).removeClass('clsBg_None');
  	 $('#active_'+hide_value).removeClass('select');
  })

  var hashVal = window.location.hash.split("#")[1];
  $('.'+hashVal).show();
  
  if(hashVal == undefined)
  {
  	$('#Box_Content'+cancellation_id).show();
  	$('#active_'+cancellation_id).addClass('clsBg_None');
    $('#active_'+cancellation_id).addClass('select');
  }
  
  $('.tab_'+hashVal).addClass('clsBg_None');
  $('.tab_'+hashVal).addClass('select');
  
  if($(".tab_"+hashVal).hasClass("select"))
  {
  }
  else
  {
  	$('#Box_Content'+cancellation_id).show();
  	$('#active_'+cancellation_id).addClass('clsBg_None');
    $('#active_'+cancellation_id).addClass('select');
  }
  
$('.name_tab').click(function()
{
  var cancellation_id = $(this).attr('data');
  $('.name_tab').each(function()
  {
  	 var hide_value = $(this).attr('data');
  	 $('#Box_Content'+hide_value).hide();
  	 $('#active_'+hide_value).removeClass('clsBg_None');
  	 $('#active_'+hide_value).removeClass('select');
  })
  $('#Box_Content'+cancellation_id).show();
  $('#active_'+cancellation_id).addClass('clsBg_None');
  $('#active_'+cancellation_id).addClass('select');
})

  });
</script>

<style>
.timeline-container {
    margin-bottom: 70px;
    margin-top: 100px;
    position: relative;
}
#Box_Content1{
	overflow: visible !important;
}
.row-flush {
    margin-left: 0;
    margin-right: 0;
}
.clearfix:after {
    clear: both;
}
.clearfix:before, .clearfix:after {
    content: " ";
    display: table;
}
.row-flush > .col-4 {
    box-sizing: border-box;
    margin: 0;
    padding-left: 0;
    padding-right: 25px;
    position: relative;
}
.timeline-segment-refundable {
    background-color: #49bd59;
}
.timeline-segment {
    height: 25px;
    text-align: center;
}
.col-4 {
    width: 33.333%;
}
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
    float: left;
    min-height: 1px;
    position: relative;
}
.timeline-point {
    position: absolute;
    right: -30px;
    top: 0;
}

.tooltip-bottom-middle:before {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: rgba(0, 0, 0, 0.1) transparent -moz-use-text-color;
    border-image: none;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-style: solid solid none;
    border-width: 10px 10px 0;
    bottom: -10px;
    content: "";
    display: inline-block;
    left: 50%;
    margin-left: -10px;
    position: absolute;
    top: auto;
}
.tooltip-bottom-middle:after {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #fff transparent -moz-use-text-color;
    border-image: none;
    border-left: 9px solid transparent;
    border-right: 9px solid transparent;
    border-style: solid solid none;
    border-width: 9px 9px 0;
    bottom: -9px;
    content: "";
    display: inline-block;
    left: 50%;
    margin-left: -9px;
    position: absolute;
    top: auto;
}
.timeline-point-tooltip {
    display: block;
    left: -50% !important;
    margin-top: 0 !important;
    opacity: 1 !important;
    padding: 6px 10px;
    position: relative !important;
    top: -60px !important;
    white-space: nowrap;
    z-index: auto !important;
}
.tooltip {
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
    left: 0;
    max-width: 280px;
    opacity: 0;
    position: absolute;
    top: 0;
    transition: opacity 0.2s ease 0s;
    z-index: 3000;
}
.timeline-point-marker {
    background: none repeat scroll 0 0 #ffffff;
    border-radius: 50%;
    height: 12px;
    left: -6px;
    position: absolute;
    top: 6px;
    width: 12px;
}
.timeline-point-label {
    left: -25%;
    position: absolute;
    text-align: center;
    top: 34px;
    width:90%;
    
}
.timeline-segment-partly-refundable {
    background-color: #ffb400;
}
.timeline-segment-nonrefundable {
    background-color: #ff5a5f;
}
.container_bg{
	width:100%;
}
.cacel_pad{
	padding-left: 7px;
    padding-right: 7px;
}
@media screen and (-webkit-min-device-pixel-ratio:0) {
.tooltip-bottom-middle:before {
content: "";
display: inline-block;
position: absolute;
bottom: -10px;
left: 50%;
margin-left: -10px;
top: auto;
border: 10px solid transparent;
border-bottom: 0;
border-top-color: rgba(0,0,0,0.1);
}
.tooltip-bottom-middle:after {
content: "";
display: inline-block;
position: absolute;
bottom: -9px;
left: 50%;
margin-left: -9px;
top: auto;
border: 9px solid transparent;
border-bottom: 0;
border-top-color: #fff;
}
}
.row-fluid
{
	font-size: 14px;
}
</style>

<input type="hidden" id="policy" value="<?php echo $this->uri->segment(3); ?>">
<div class="container">
<div class="container_bg container med_12 mal_12 pe_12" id="View_Canellation_Policy">
<div class="cancel_head_content med_12 mal_12 pe_12">
<h3><?php echo translate("Cancellation Policies"); ?></h3>
<p>
<?php 

echo $this->dx_auth->get_site_title(); ?> <?php echo translate("allows hosts to choose among")." ".$cancellation_standard->num_rows()." ".translate("standardized cancellation policies"); 

if($cancellation_standard->num_rows() != 0)
{
	echo ' (';
	$stand = '';
	foreach($cancellation_standard->result() as $row_stand)
	{
		$stand .= $row_stand->name.',';
	}
	echo rtrim($stand,',').') ';
}

echo translate('that we will enforce to protect both guest and host alike.')." ";
echo translate('The Super Strict cancellation policy applies to special circumstances and is by invitation only. The Long Term cancellation policy applies to all reservations of 28 nights or more.')." ";
echo translate("Each listing and reservation on our site will clearly state the cancellation policy. Guests may cancel and review any penalties by viewing their travel plans and then clicking 'Cancel' on the appropriate reservation.");

?>
	 </p>
</div>
<div class="Box med_12 mal_12 pe_12 can_pad">
	<div class="Box_Head">
		<?php
		foreach($cancellationDetails->result() as $row)
		{
		?>
		<ul class="med_2 mal_2 pe_12 can_pad can_list" id="<?php echo $row->id;?>">
        <li  id="active_<?php echo $row->id;?>" class="can_wid can_txt tab_<?php echo str_replace(' ','-',$row->name);?>"><a href="#<?php echo str_replace(' ','-',$row->name);?>" class="name_tab" data="<?php echo $row->id;?>"><?php echo $row->name; ?></a></li>
       	</ul>
		<?php
		}
		?>
    
    </div>
    
    <?php foreach($cancellationDetails->result() as $cancellation) { ?>
    
	<div id="Box_Content<?php echo $cancellation->id ?>" class="Box_Content <?php echo str_replace(' ','-',$cancellation->name);?>">
		<h3>
			<?php 
			//if($cancellation->list_before_status != 0 && $cancellation->list_before_days > 1)
			//{
				$day = $cancellation->list_before_days.' days';
				$hours = $cancellation->list_before_days.' days';
			//}
			//else if($cancellation->list_before_status != 0)
			//{
			//	$day = $cancellation->list_before_days.' day';
			//	$hours = '24 hours';
			//}
			//else
				//{
			//		$day = '0 day';
			//		$hours = '0 hour';
			//	}
			
			 if($cancellation->list_days_prior_days > 1)
			{
				$day_prior_days = $cancellation->list_days_prior_days.' days';
			}  
			else
			{
				$day_prior_days = $cancellation->list_days_prior_days.' day';	
			}
			
			if($cancellation->list_days_prior_percentage == 100)
			{
				$days_percentage = 'Full';
			}
			else
			{
				$days_percentage = $cancellation->list_days_prior_percentage.'%';
			}
			
			if($cancellation->list_before_percentage == 100)
			{
				$percentage = 'Full';
			}
			else
			{
				$percentage = $cancellation->list_before_percentage.'%';
			}
			echo str_replace(array('{day}','{percentage}'),array($day_prior_days,$days_percentage),$cancellation->cancellation_title); 
			?></h3>
         <?php 
         echo str_replace(array('{site_title}'),array($this->dx_auth->get_site_title()),$cancellation->cancellation_content); 
         ?>
        <div class="timeline-container hide-phone med_12 mal_12 can_pad" id="bar_<?php echo $cancellation->id;?>">
  <div class="row-fluid row-flush clearfix">
       <?php
         
   if($cancellation->list_days_prior_status == 1)
   {
   	$full_refund = 1;
   	?>
   	<div class="col-4 span4 timeline-segment-refundable timeline-segment">
        <div class="timeline-point" style="right:<?php echo $cancellation->list_days_prior_days-30;?>px !important;">
          <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
            <?php echo $day_prior_days;?> prior
          </div>

          <div class="timeline-point-marker"></div>
            <div class="timeline-point-label can_date can_sup">
              <?php 
              $date = date_create(date('Y-m-d'));
              $diff = $cancellation->list_days_prior_days.' days';
              date_sub($date, date_interval_create_from_date_string("$diff"));
			  echo date_format($date, 'D, M d');
              ?>
              <br>3:00 PM
            </div>
        </div>
      </div>
      <?php
   }
   else
   	{
   		$full_refund = 0;
   	}
   if($cancellation->list_before_status == 1)
   {
   	?>
   	<div class="col-4 span4 timeline-segment timeline-segment-partly-refundable">
   	<?php
   }
else {
	?>
	<div class="col-4 span4 timeline-segment timeline-segment-nonrefundable">
	<?php
     }
   ?>
      <div class="timeline-point" id="second-point" style="right:<?php echo $cancellation->list_after_days-30;?>px !important;">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check in
        </div>
        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label can_date"><?php echo date('D, M d');?>
            <br>3:00 PM</div>
      </div>
    </div>
   <?php
  /*if($cancellation->list_after_status == 1 && $cancellation->list_after_percentage != '100')
   {
   	?>
   	<div class="col-4 span4 timeline-segment timeline-segment-partly-refundable">
   	<?php
   }
else {*/
	?>
	<div class="col-4 span4 timeline-segment timeline-segment-nonrefundable">
	<?php
//}
   ?>
    
      <div class="timeline-point" id="third-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check out
        </div>

        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label can_date">
          	<?php 
          	$date_after = '+'.$cancellation->list_after_days.' day';
  			  $date = strtotime($date_after);
			  echo date('D, M d',$date);
              ?>
            <br>11:00 AM</div>
      </div>
    </div>
  </div>

  <div class="timeline-fineprint">
      Example
  </div>
</div>
<div class="row-fluid row-flush clearfix space2" id="bar_content_<?php echo $cancellation->id;?>">
      <?php
      if(isset($full_refund))
	  {
	  	if($full_refund == 1)
		{
	  	?>
	  	<div class="span4 med_4 mal_4 pe_12 cacel_pad">
          <p>For a <?php echo $days_percentage;?> refund, cancellation must be made a 
          	<?php       	
          	echo $day_prior_days;?> prior to listing's local check in time (or 3:00 PM if not specified) on the day of check in.  
          	For example, if check-in is on <?php echo date('l');?>, cancel by the previous
          	<?php 
          	$date = date_create(date('Y-m-d'));
            $diff = $cancellation->list_days_prior_days.' days';
            date_sub($date, date_interval_create_from_date_string("$diff"));
          	echo date_format($date, 'l');
          	?> before check in time.</p>
      </div>
	  	<?php
		}
	  }
	  if($cancellation->list_before_status != 0 && $cancellation->list_non_refundable_nights == 1 || $cancellation->list_non_refundable_nights == 0)
	  {
	  	$non_refundable_nights = 'first night is';
	  }
	  else if($cancellation->list_before_status != 0 ){
		  $non_refundable_nights = $cancellation->list_non_refundable_nights.' nights are';
	  }
	  else
	  	{
	  		$non_refundable_nights = 'all nights are';
	  	}
	  ?>
      <div class="span4 med_4 mal_4 pe_12 cacel_pad">
          <p>If the guest cancels less than <?php echo $hours;?> in advance, the <?php echo $non_refundable_nights;?> non-refundable<?php 
          if($cancellation->list_before_status == 0) 
          echo '.'; 
          else 
          echo ' but the remaining nights will be '.$cancellation->list_before_percentage.'% refunded.'; 
          ?></p>
      </div>
      <div class="span4 med_4 mal_4 pe_12 cacel_pad">
          <p>If the guest arrives and decides to leave early, the nights not spent <?php 
          if($cancellation->list_after_status == 0)
  		 {
  		 	echo '0';
   		 }
		 else
		 {
		  	echo $cancellation->list_after_days;	
		 }
          ?> days after the official cancellation are 
          <?php 
          if($cancellation->list_after_status == 0) 
          echo 'non-'; 
          else 
          echo $cancellation->list_after_percentage.'% '; 
          ?>refunded. <?php
        
          $refundable_fees = '';
          $non_refundable_fees = '';
		  
          if($cancellation->cleaning_status == 1)
		  {
		  	 $refundable_fees .=  'Cleaning fees, ';
		  }
		  else
		  	{
		  	  $non_refundable_fees .=  'Cleaning fees, ';
		  	}
			
			if($cancellation->security_status == 1)
		  {
		  	 $refundable_fees .=  'Security fees, ';
		  }
		  else
		  	{
		  	  $non_refundable_fees .=  'Security fees, ';
		  	}
			
			if($cancellation->additional_status == 1)
		  {
		  	 $refundable_fees .=  'Additional Guest fees ';
		  }
		  else
		  	{
		  	  $non_refundable_fees .=  'Additional Guest fees ';
		  	}
			
				if(!empty($refundable_fees))
				echo $refundable_fees.'are refunded. ';
			
				if(!empty($non_refundable_fees))
				echo $non_refundable_fees.'are non-refunded.';
	
           ?></p>
      </div>
</div>
    </div>
	 <?php } ?>
</div>
</div>

</div>
</div>