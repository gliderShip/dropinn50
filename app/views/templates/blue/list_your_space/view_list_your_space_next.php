<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=10" />
<title>Calender</title>
<link href="<?php echo css_url().'/common.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url().'/demo.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url().'/listyourspace.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo css_url().'/jquery.ui.css'; ?>" media="screen" type="text/css"/>

<script language="Javascript" type="text/javascript" src="<?php echo base_url().'js/jquery.ui.js';?>"></script>
<script language="Javascript" type="text/javascript" src="<?php echo base_url().'js/rotate3Di.js'; ?>"></script>
<script language="Javascript" type="text/javascript" src="<?php echo base_url().'js/lys.js'; ?>"></script>
<!--Drag and Drop image upload 2 start-->


<!--Drag and Drop image upload 2 stop-->
<script>
	var calendar_type = <?php echo $calendar_type; ?>;
	var calendar_status = <?php echo $lys_status->calendar;?>;
	var price_status = <?php echo $lys_status->price;?>;
	var overview_status = <?php echo $lys_status->overview;?>;
	var address_status = <?php echo $lys_status->address;?>;
	var amenities_status = <?php echo $lys_status->amenities;?>;
	var listing_status = <?php echo $lys_status->listing;?>;
	var photo_status = <?php echo $lys_status->photo;?>;
	var title_status = <?php echo $lys_status->title;?>;
	var summary_status = <?php echo $lys_status->summary;?>;
	var beds_status = <?php echo $lys_status->beds; ?>;
	var bathrooms_status = <?php echo $lys_status->bathrooms; ?>;
	var bedtype_status = <?php echo $lys_status->bedtype; ?>;
	var bedscount_status = <?php echo $lys_status->bedscount; ?>;
	var photos_count = <?php echo $list_photo->num_rows(); ?>;
    var city = decodeURIComponent("<?php echo $city; ?>");  
    var state = decodeURIComponent("<?php echo rawurlencode($state); ?>");  
	var country = decodeURIComponent("<?php echo $country_name; ?>");
	var room_id = '<?php echo $room_id;?>';
	var cleaning_fee = '<?php echo $cleaning_fee;?>';
	var extra_guest_price = '<?php echo $extra_guest_price;?>';
	var security = '<?php echo $security;?>';
	var currency = '<?php echo $currency;?>';
    var room_type_org =  decodeURIComponent("<?php echo $room_type_org;?>");
	var house_rule =  decodeURIComponent("<?php echo rawurlencode($house_rule); ?>");
	var edit_photo = 0;
	var total_status_php = 0;
	var some_times_cal = 0;
	var some_times = 0;
	var places_API = '<?php echo $places_API;?>' ;
	<?php
if($this->uri->segment(3) == 'edit_photo')
{
	?>
	edit_photo = 1;
<?php }
?>
	<?php
		if($total_status == 6)
		{ ?>
			total_status_php = 1;
		<?php }?>
	<?php
    if($lys_status->calendar == 1 && $is_enable == 1 || $list_pay == 1)
	{ ?>
	 some_times_cal = 1;
	<?php }
	else {
	?>
	some_times = 1;
	<?php } ?>
</script>
<style>
.endcap > b {
   /* margin-left: -60%;*/
   }
html, body, #map-canvas1 {
        height: 100%;
        margin: 0px;
        padding: 0px;
      }
      #map-canvas1{
      	width:100%;
      	height: 300px;
      }
      .panel{margin-bottom:0px;}
    </style>

 <body>
<input type='hidden' id="currency_hidden" value=""/>
<input type='hidden' id="currency_symbol_hidden" value=""/>
<div id="mystick_head" class="header_bottom_nav">
	<div class="left_lys_ent">
        <a class="Entire_Left" href="<?php echo base_url().'listings'; ?>"></a>
        <span class="eelt"></span>
        <?php 
        $room_id = $this->uri->segment(4);
        $ori_room_type = $this->db->select('room_type')->where('id',$room_id)->get('list')->row()->room_type;?>
        <a class="tooltip" title="<?php if($lys_status->title == 1) echo $room_type; else echo translate("$ori_room_type"); ?>"><span id="title_header"><?php if($lys_status->title == 1) echo $room_type; else echo translate("$ori_room_type"); ?></span></a>
       
    </div>
    <a class="Entire_Right" target="_blank" href="<?php echo base_url().'rooms/'.$room_id.'/preview'; ?>">
        <div class="entright">
            <span class="entrt"></span>
            <span><?php echo translate('Preview');?></span>
        </div>
    </a>
</div>

<div id="sidebar_main_entire" class="main_entire med_2 mal_3 pe_12 padding-zero">
	<div class="main_entire_inner">
        <div class="entire_contain_bottom">
            <h4><?php echo translate('BASICS');?></h4>
            <p class="entire_title active_entire" id="cal"><img src="<?php echo base_url(); ?>images/calender_hv.png" /> <?php echo translate('Calendar');?>
            	 <img class="plus_hv" id="cal_plus" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
            	 <img class="plus_hv" id="cal_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
            	 </p>
                <p class="entire_title" id="cal_after" style="display: none"><img src="<?php echo base_url(); ?>images/calender.png" /> <?php echo translate('Calendar');?> 
            	<img class="plus_hv" src="<?php echo base_url(); ?>images/tick_grn.png" />
            	</p>
            	<p class="entire_title" id="cal1" style="display: none"><img src="<?php echo base_url(); ?>images/calender.png" /> <?php echo translate('Calendar');?> 
            	<img class="plus_hv" src="<?php echo base_url(); ?>images/plus_normal.png" />
            	</p>
            	<p class="entire_title" id="cal1_after" style="display: none"><img src="<?php echo base_url(); ?>images/calender.png" /> <?php echo translate('Calendar');?> 
            	<img class="plus_hv" src="<?php echo base_url(); ?>images/tick_grn.png" />
            	</p>
            <p class="entire_title" id="price"><img src="<?php echo base_url(); ?>images/star_pricing.png" /> <?php echo translate('Pricing');?> 
            	<img class="plus_hv" id="des_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
            	<img class="plus_hv" id="des_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
            </p>
            <p class="entire_title active_entire" id="price_after" style="display: none"><img src="<?php echo base_url(); ?>images/star_pricing_hv.png" /> <?php echo translate('Pricing');?> 
            	 <img class="plus_hv" id="price_plus" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
            	 <img class="plus_hv" id="price_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
            	
            	</p>
        </div>
        <div class="entire_contain_bottom">
            <h4><?php echo translate('DESCRIPTION');?></h4>
            <p class="entire_title" id="overview"><img class="listyoursp_inndesc" src="<?php echo base_url(); ?>images/overview.png" /> <?php echo translate('Overview');?> 
            	<img class="plus_hv" id="over_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
            	<img class="plus_hv" id="over_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
            </p>       
            <p class="entire_title active_entire" id="overview_after" style="display: none"><img src="<?php echo base_url(); ?>images/overview_hv.png" /> <?php echo translate('Overview');?>  
            	<img class="plus_hv" id="over_plus1" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
            	<img class="plus_hv" id="over_plus_after1" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
            	</p>
            	      <?php if($lys_status_count == 6)
			{
				?>
            <p class="entire_title" id="detail_side"><img class="listyoursp_detail" src="<?php echo base_url(); ?>images/detail.png" /> <?php echo translate('Detail');?> 
            	<img class="plus_hv" id="detail_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
		    </p>    
             <p class="entire_title active_entire" id="detail_side_after" style="display: none"><img src="<?php echo base_url(); ?>images/detail_hv.png" /> <?php echo translate('Detail');?>  
            	<img class="plus_hv" id="detail_plus1" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
            	</p>
            <?php } ?> 
           <!-- <p class="entire_title" id="detail_side_2" style="display: none"><img class="listyoursp_detail" src="<?php echo base_url(); ?>images/detail.png" /> <?php echo translate('Detail');?> 
            	<img class="plus_hv" id="detail_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
		    </p> --> 
           <p class="entire_title" id="photo"><img src="<?php echo base_url(); ?>images/photos.png" /> <?php echo translate('Photos');?>  
           	<img class="plus_hv" id="photo_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
            	<img class="plus_hv" id="photo_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
            	<img class="plus_hv" id="photo_grn" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
           </p>
            <p class="entire_title active_entire" id="photo_after" style="display: none"><img src="<?php echo base_url(); ?>images/photos_hv.png" /> <?php echo translate('Photos');?> 
            	<img class="plus_hv" id="photo_plus_white" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
            	<img class="plus_hv" id="photo_grn_white" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
            </p>
        </div>
        <div class="entire_contain_bottom">
            <h4><?php echo translate('SETTINGS');?></h4>
            <p class="entire_title" id="amenities"><img src="<?php echo base_url(); ?>images/entire_amenities.png" /> <?php echo translate('Amenities');?></p>
            <p class="entire_title active_entire" id="amenities_after" style="display: none"><img src="<?php echo base_url(); ?>images/entire_amenities_hv.png" /> <?php echo translate('Amenities');?>
            	</p>
            <p class="entire_title" id="address_side"><img src="<?php echo base_url(); ?>images/entire_address.png" /> <?php echo translate('Address');?> 
            	<img class="plus_hv" id="addr_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
            	<img class="plus_hv" id="addr_plus_after_grn" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
            </p>
            <p class="entire_title active_entire" id="address_after" style="display: none"><img src="<?php echo base_url(); ?>images/entire_address_hv.png" /> <?php echo translate('Address');?> 
            	<img class="plus_hv" id="address_before" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
            	<img class="plus_hv" id="addr_plus_after_white" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
            	</p>
              <?php if($lys_status_count == 6)
			{	?>
            <p class="entire_title" id="terms_side"><img class="listyoursp_terms" src="<?php echo base_url(); ?>images/terms.png" /> <?php echo translate('Terms');?> 
            	<img class="plus_hv" id="terms_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
            </p>   
            <p class="entire_title active_entire" id="terms_side_after" style="display: none"><img class="listyoursp_terms" src="<?php echo base_url(); ?>images/terms_hv.png" /> <?php echo translate('Terms');?> 
            	<img class="plus_hv" id="terms_plus_after" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
            </p>
            <?php } ?>	
          <!--  <p class="entire_title" id="terms_side_2" style="display: none"><img class="listyoursp_terms" src="<?php echo base_url(); ?>images/terms.png" /> <?php echo translate('Terms');?> 
            	<img class="plus_hv" id="terms_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
            </p>-->   
            <p class="entire_title" id="listing"><img class="listyoursp_terms" src="<?php echo base_url(); ?>images/entire_listing.png" /> <?php echo translate('Listing');?> 
            	<img class="plus_hv" id="list_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
            	<img class="plus_hv" id="list_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
            </p>         
            <p class="entire_title active_entire" id="listing_after" style="display: none"><img class="listyoursp_terms" src="<?php echo base_url(); ?>images/entire_listing_hv.png" /> <?php echo translate('Listing');?> 
            	<img class="plus_hv" id="listing_plus1" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
            	<img class="plus_hv" id="listing_plus_after1" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
            	</p>
        </div>
    </div>
     <div class="entire_count" id="steps_count">
		<p><?php echo translate('Complete');?> <span id="steps">6 <?php echo translate('steps');?></span> <?php echo translate('to');?> <br/> <?php echo translate('list your space');?></p>
    </div>
    <div style="display:none;" class="entire_count-button" id="list_space">
    <p>

  <!--  <button id="list-button" class="btn-special btn-yellow-list btn_list finish next_font">-->

    <button id="list-button" class="btn-special btn-yellow-list" style="">
    <input type="hidden" id="edit-list" value="<?php echo $this->uri->segment(3);?>" /> 

     <?php echo translate('List Space');?>
    </button>
    </p>
  </div>
</div>

<div class="center_entire med_6 mal_7 pe_12" id="cal_container">
    <div class="contain_calender" id="calendar_first">
        <div class="calendarHeader">
            <h4 class="calendar-h4">
                <a class="calen_left" href="#"><i class="icon icon-chevron-left"><</i></a>
                <div class="cc" id="calendar_top"><?php echo translate('Calendar');?></div>
                <a class="calen_right" href="#"><i class="icon icon-chevron-left">></i></a>
            </h4>
        </div>
    <div id="calendar-tick" class="calendarHeaderBorder"></div> 
        <div class="center_entire_left">
            <div class="calender_row">
            
            <h3><?php echo translate('When is your listing available?');?></h3>
            <div class="calender_div">
             <!--   <div id="home-1" class="calender_left_img">
                <a class="myButtonLink" href="#"></a>
                <a class="myButtonLink_after" style="display: none;background: url<?php //echo base_url().'images/tick-hover.png'; ?>) no-repeat;" href="#LinkURL"></a>
                   <p class="calender_list">Always</p>
                   <p class="calender_content">List all dates as available</p>
                </div>-->
                <div id="home-2" class="calender_left_img">
                <a class="myButtonLink"></a>
                <a class="myButtonLink_after" style="display: none;background: url(<?php echo cdn_url_images().'images/cal-hover.png'; ?>) no-repeat;"></a>
                    <p class="calender_list"><?php echo translate('Always');?></p>
                    <p class="calender_content"><?php echo translate('List all dates as available');?></p>
                </div>
              <!--  <div id="home-3" class="calender_left_img">
                    <a class="myButtonLink" href="#LinkURL"></a>
                    <a class="myButtonLink_after" style="display: none;background: url<?php //echo base_url().'images/tick-hover.png'; ?>) no-repeat;" href="#LinkURL"></a>
                    <p class="calender_list">One Time</p>
                    <p class="calender_content">List only one time period as available</p>
              </div>-->
            </div>
            </div>
        </div>
    </div>
        <div class="contain_calender"  id="always" style="display: none">
        <div class="calendarHeader">
            <h4>
                <a class="calen_left" href="#"><img src="<?php echo cdn_url_images(); ?>images/calender_left_arrow.png" /></a>
                <div class="cc" id="calendar_always">Calendar</div>
                <a class="calen_right" href="#"><img src="<?php echo cdn_url_images(); ?>images/calender_right_arrow.png" /></a>
            </h4>
        </div>
          <div id="calendar-tick" class="calendarHeaderBorder"></div> 
        <div class="center_entire_left">
            <div class="calender_row">
            
            <div class="calender_div">
                <div class="calender-always">
                    <img src="<?php echo base_url(); ?>images/calender_list.png" />
                    <h3><?php echo translate('Always Available');?></h3>
                    <p class="available"><?php echo translate('This is your calendar! After listing your space, return here to update your availability.');?></p>
                    <p class="choose_again" id="back_always"><img src="<?php echo base_url(); ?>images/left-arrow.png" /> <?php echo translate('CHOOSE AGAIN');?></p>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <div class="contain_calender" id="one_time" style="display: none">
        <div class="calendarHeader">
            <h4>
                <a class="calen_left" href="#"><img src="<?php echo base_url(); ?>images/calender_left_arrow.png" /></a>
                <div class="cc" id="calendar_one"><?php echo translate('Calendar');?></div>
                <a class="calen_right" href="#"><img src="<?php echo base_url(); ?>images/calender_right_arrow.png" /></a>
            </h4>
        </div>
        <div class="center_entire_left">
            <div class="calender_row">
            
            <div class="calender_div" >
                <div class="calender-always">
                    <img src="<?php echo base_url(); ?>images/calender_list.png" />
                    <h3><?php echo translate('One Time Available');?></h3>
                    <p class="listing-date"><?php echo translate('Select the dates your listing is available.');?></p>
                    <div class="date-pic">
                    	<input type="text" value="Start Date" class="start-date" /> <span>to</span> <input type="text" value="End Date" class="start-date" /> <input type="text" value="Save" class="save" />
                    </div>
                    <p class="available"><?php echo translate('After listing your space, return here to set custom prices and availability.');?></p>
                    <p class="choose_again" id="back_one"><img src="<?php echo base_url(); ?>images/left-arrow.png" /> <?php echo translate('CHOOSE AGAIN');?></p>
                </div>
            </div>
            </div>
        </div>
    </div>
   <div class="contain_calender" id="some_times" style="display: none">
        <div class="calendarHeader">
            <h4>
                <a class="calen_left" href="#"><i class="icon icon-chevron-left"><</i></a>
                <div class="cc" id="calendar_some"><?php echo translate('Calendar');?></div>
                <a class="calen_right" href="#"><i class="icon icon-chevron-left">></i></a>
            </h4>
        </div>
        <div class="calendarHeaderBorder" id="calendar-tick"></div>
        <div class="center_entire_left">
            <div class="calender_row">
            
            <div class="calender_div">
                <div class="calender-always">
                    <img src="<?php echo base_url(); ?>images/calender_list.png" />
                    <h3><?php echo translate('Always Available');?></h3>
                    <p class="available"><?php echo translate('This is your calendar! After listing your space, return here to update your availability.');?></p>
                    <p class="choose_again" id="back_some"><img src="<?php echo base_url(); ?>images/left-arrow.png" /><?php echo translate('CHOOSE AGAIN');?></p>
                </div>
            </div>
            </div>
        </div>
    </div>
    <?php
    if($this->uri->segment(3) == 'edit' && $lys_status->calendar == 1 && $is_enable == 1 && $list_pay == 1)
	{ ?>

    <div id="some_times_cal" class="edit_calender listyoursp_in med_12 mal_12 pe_12 padding-zero">

    <!-- <div id="some_times_cal" class="edit_calender">-->

   
    	<!-- Required Stylesheets -->

<script type="text/javascript" src="<?php echo base_url(); ?>js/prototype.js"></script>

<script type="text/javascript">
function showmydiv()
{
	var availability=document.getElementById('lwlb_availability').value;
	if(availability=="Available")
	{
	document.getElementById('lwlb_price').style.display="block";
	document.getElementById('lwlb_notes').style.display="none";
	}
	else
	{
	document.getElementById('lwlb_price').style.display="none";
	document.getElementById('lwlb_notes').style.display="block";
	}
}

function get_square(rowIndex,colIndex) {
	return schedules[rowIndex][colIndex];
}
function gridColToDataCol(gridCol) {
				return (gridCol);
}

function gridRowToDataRow(gridRow) {
				return (gridRow);
}

function update_calendar_data(visible_row,hosting_id,json) {

				schedules[0] = json[0];
				render_grid(0, 34);
}

function is_address_line(row) {
				return false;
}

function lwlb_hide_special() {
				lwlb_hide('lwlb_calendar2');
				range_remove_all();
}

function range_add(i){

if(select_range_click_count <2)
{

				if (select_range_stop == null) return;
				if (i < select_range_start) return;
				if (i < select_range_stop) range_remove(i+1);

				for (var tmp=select_range_stop; tmp <= i; tmp++) {
								if (!is_selectable(0,tmp)) return;
								
								rollover('tile_' + tmp, 'tile', 'tile_selected');
				}

				select_range_stop = i;
			}
}

function range_remove(i) {
				if (select_range_stop == null) return;
				if (i <= select_range_start) return;
				if (select_range_click_count>=2) return;
				if (i>select_range_stop) return;
				var cged = select_range_stop;

				for (; select_range_stop >= i; select_range_stop--) {
								rollover('tile_' + select_range_stop, 'tile_selected', 'tile');
				}

				select_range_stop = i;
				
							
				for( j=cged; j <= 50; j++)
				{
				
					rollover('tile_' + j, 'tile_selected', 'tile');
				}
				
}


function range_remove_all() {
				for (var i=select_range_start; i <= select_range_stop; i++) {
								rollover('tile_' + i, 'tile_selected', 'tile');
				}

				select_range_click_count = 0;
				select_range_start = null;
				select_range_stop = null;
}

function click_down(i) {
				select_range_click_count++;

				if (select_range_click_count > 2) {
								range_remove_all();
								return;
				}

				if (select_range_stop != null) return; // abort on second click

				if (!is_selectable(0, i)) {
								var matches = getAtomicBounds(0,i);
								select_range_start = matches[0];
								select_range_stop = matches[1];
								show_calendar();
								return;
				}

				select_range_start = i;
				select_range_stop = i;
				rollover('tile_'+i,'tile','tile_selected');
}

function click_up(i) {
				if (select_range_click_count == 2) {
								show_calendar();
				}
				else
				{
					lwlb_hide('lwlb_calendar2');
				}
}

function show_calendar() {

				prepareLightbox(<?php echo $list_id; ?>,"<?php echo $list_title; ?>",0,select_range_start,select_range_stop);

}

</script>
<!-- End of style sheets inclusion -->
<?php 
	$id = $this->uri->segment(4);
	$query = $this->db->get_where('list', array( 'id' => $id));
	$q = $query->result();
	$query2 = $this->db->get_where('amnities', array( 'id' => $id));
	$r = $query2->result();
?>
<input type="hidden" id="mouse_x">
<input type="hidden" id="mouse_y">
<div class="container_bg edit_calend" id="View_Edit_List">
  <div class="clearfix" id="View_Edit_Content">
    <div class="View_Edit_Main_Content">
      <div id="notification-area"></div>
      <div id="dashboard-content">
        <div id="dashboard_v2" class="edit_room">
            <div class="col three-fourths content">
              <div id="notification-area"></div>
              <div id="dashboard-content">
  <?php
		$first_day     = mktime(0,0,0,$month,1,$year);
		$days_in_month = cal_days_in_month(0,$month,$year);
		$day_of_week   = date('N',$first_day);
		
		$months        = array('January','February','March','April','May','June','July','August','September','October','November','December');
		$title         = $months[$month-1]; 
		
		if ($day_of_week == 7) { $blank = 0; } else { $blank = $day_of_week; }
		
		if (($month-1) == 0) 
		{
		$prevmonth = 1;
		$prevyear  = ($year-1);
		}
		else 
		{
		$prevmonth = ($month-1);
		$prevyear  = $year;
		}
		$day_prevmonth=cal_days_in_month(0,$prevmonth,$prevyear)-($blank-1);
		
		if($month == 01)
		{
		$prev_month = 12; $prev_year = $year - 1;
		}
		else
		{
		$prev_month = $month - 1; $prev_year = $year;
		}
		
		if($month == 12)
		{
		$next_month = 01; $next_year = $year + 1;
		}
		else
		{
		$next_month = $month+1; $next_year = $year;
		}

		$day_num    = 1;
		$day_count  = 1;
		$datenow    = time();
		$monthnow   = date('n',$datenow);
		$yearnow    = date('Y',$datenow);
		$daynow     = date('j',$datenow);
		?>
                <script type="text/javascript">
var global_grid = null;

<?php
$columnInfo = '';
$schedules  = '';
$firstDay =  $prev_year.'-'.$prev_month.'-'.$day_prevmonth;
//Previous Months Days
while ($blank > 0) { 
$dayJ        = date('D',strtotime($prev_year.'-'.$prev_month.'-'.$day_prevmonth));
$month_day   = date('M d',strtotime($prev_year.'-'.$prev_month.'-'.$day_prevmonth));
$full_date   = $prev_month.'/'.$day_prevmonth.'/'.$prev_year;

$pre_schedules  = '{cl: "av", sty: "both", isa: 1},';
foreach($result_cal as $row)
{
	if(get_gmt_time(strtotime($full_date)) == $row->booked_days)
	{
	  if($row->value != '') { $value = ', reservation_value: '.$row->value; } else { $value = ''; }
			if($row->availability == 'Not Available') {$c1 = 'bs'; $st = 2; } else {$c1 = 'tp'; $st = 4; }
			$pre_schedules  = '{cl: "'.$c1.'", sty: "'.$row->style.'", isa: 0, id: '.$row->id.', notes: "'.$row->notes.'", st: '.$st.', sst: "'.$row->booked_using.'", gid: '.$row->group_id.$value.'},';
	}
}
$schedules  .= $pre_schedules;

$columnInfo .= '"'.$dayJ.'\u003Cbr /\u003E'.$month_day.'",';

$blank = $blank-1; $day_count++; $day_prevmonth++;
}

//Current Months Days
while ($day_num <= $days_in_month) { 
$dayJ        = date('D',strtotime($year.'-'.$month.'-'.$day_num));
$month_day   = date('M d',strtotime($year.'-'.$month.'-'.$day_num));
$full_date   = $month.'/'.$day_num.'/'.$year;

$pre_schedules  = '{cl: "av", sty: "both", isa: 1},';
foreach($result_cal as $row)
{
	if(get_gmt_time(strtotime($full_date)) == $row->booked_days)
	{
	  if($row->value != '') { $value = ', reservation_value: '.$row->value; } else { $value = ''; }
			if($row->availability == 'Not Available') {$c1 = 'bs'; $st = 2; } else {$c1 = 'tp'; $st = 4; }
			$pre_schedules  = '{cl: "'.$c1.'", sty: "'.$row->style.'", isa: 0, id: '.$row->id.', notes: "'.$row->notes.'", st: '.$st.', sst: "'.$row->booked_using.'", gid: '.$row->group_id.$value.'},';
	}
}
$schedules  .= $pre_schedules;

$columnInfo .= '"'.$dayJ.'\u003Cbr /\u003E'.$month_day.'",';

$day_num++; $day_count++;

if ($day_count > 7) { $day_count = 1; }
}

//Next Months Days
$day_nextmonth = 1;

while ($day_count > 1 && $day_count <= 7 ) {
$dayJ           = date('D',strtotime($next_year.'-'.$next_month.'-'.$day_nextmonth));
$month_day      = date('M d',strtotime($next_year.'-'.$next_month.'-'.$day_nextmonth));
$full_date      = $next_month.'/'.$day_nextmonth.'/'.$next_year;

$pre_schedules  = '{cl: "av", sty: "both", isa: 1},';
foreach($result_cal as $row)
{
	if(get_gmt_time(strtotime($full_date)) == $row->booked_days)
	{
			if($row->value != '') { $value = ', reservation_value: '.$row->value; } else { $value = ''; }
			if($row->availability == 'Not Available') {$c1 = 'bs'; $st = 2; } else {$c1 = 'tp'; $st = 4; }
			$pre_schedules  = '{cl: "'.$c1.'", sty: "'.$row->style.'", isa: 0, id: '.$row->id.', notes: "'.$row->notes.'", st: '.$st.', sst: "'.$row->booked_using.'", gid: '.$row->group_id.$value.'},';
	}
}
$schedules  .= $pre_schedules;

$columnInfo .= '"'.$dayJ.'\u003Cbr /\u003E'.$month_day.'",';

$day_count++; $day_nextmonth++;
}

$lastDay = $next_year.'-'.$next_month.'-'.($day_nextmonth-1);
?>

var columnInfo = [<?php echo substr($columnInfo, 0, -1); ?>];

var hostings = [{"price":<?php echo get_currency_value1($list_id,$list_price); ?>,"name":"<?php echo $list_title; ?>","available":1,"row":0,"id":<?php echo $list_id; ?>,"currency_symbol":"<?php  echo get_currency_symbol($list_id); ?>","currency":"<?php  echo get_currency_code(); ?>","lc_name":"<?php echo strtolower($list_title); ?>"}];

var schedules = [[<?php echo substr($schedules, 0, -1); ?>]];


var reservationHash = new Hash({});

var g_start_date = date_parse_datestamp('<?php echo $firstDay; ?>');

var g_stop_date = date_parse_datestamp('<?php echo $lastDay; ?>');
var g_today_index = 19;

var g_enable_change_dates = true;

function centerLightbox() {
    var boxWidth = jQuery('#lwlb_calendar2').width();
    var boxHeight= jQuery('#lwlb_calendar2').height();
   var g_mouse_x = jQuery('#mouse_x').val();
   var g_mouse_y = jQuery('#mouse_y').val();
  
    $('lwlb_calendar2').setStyle({ position:"absolute", left: (g_mouse_x - (boxWidth / 0.588))+"px", top: (g_mouse_y - boxHeight - 616)+"px"});
}

/***** SELECTION *****/

/* A span is selectable if it contains no atomic groups */
function is_selectable(gridRow,gridCol) {
    if (is_address_line(gridRow)) return false;

    var schedule = get_square(gridRow,gridCol);
    if (schedule.gid || schedule.confirmation) {
        return false;
    } else {
        return true;
    }
}

/* If atomic span / grouping, what are the bounds of the span? */
function getAtomicBounds(gridRow,gridCol) {
    var minCol = gridCol;
    var maxCol = gridCol;

    var grouping_uid = get_square(gridRow,gridCol).gid;
    while (get_square(gridRow,maxCol+1) && (get_square(gridRow,maxCol+1).gid==grouping_uid)) {
        maxCol+=1;
    }
    while (get_square(gridRow,minCol-1) && (get_square(gridRow,minCol-1).gid==grouping_uid)) {
        minCol-=1;
    }
    return [minCol,maxCol];
}

function getSpanBounds(gridRow,gridCol) {
    var minCol = gridCol;
    var maxCol = gridCol;

    if (get_square(gridRow,gridCol).sty=='single') return [minCol,maxCol];
    while (get_square(gridRow,maxCol+1) && (get_square(gridRow,maxCol+1).sty!='right')) {
        maxCol+=1;
    }
    return [minCol,maxCol+1];
}


function getSquareText(square,gridRow,gridCol) {
    var dataRow = gridRowToDataRow(gridRow);
    var dataCol = gridColToDataCol(gridCol);

    var hosting = hostings[dataRow];
    
    if (("left"==square.sty) || ("single"==square.sty)) {
        switch(square.st) {
        case 0:
            if (square.daily_price) {
                return hosting.currency_symbol + square.daily_price;
            } else {
                return hosting.currency_symbol + hosting.price;
            }
        case 2:
            if (decodeURIComponent(square.notes)) {
                return decodeURIComponent(square.notes)
            } else {
                return 'Not available';
            }
        case 5:
            if (decodeURIComponent(square.notes)) {
                return decodeURIComponent(square.notes)
            } else {
                return 'Not available';
            }
        case 3:
            if (decodeURIComponent(square.notes)) {
                return square.sst + ": " + decodeURIComponent(square.notes)
            } else {
                return square.sst + ': Not available';
            }
        case 4:
            return "Booked" + (decodeURIComponent(square.notes) ? ": "+decodeURIComponent(square.notes) : '');
        case 1:
            var r = reservationHash.get(square.confirmation);
            if (!r) return '(error)';
            if (r.is_accepted) {
                return r.base_price + " Cogzidel, " + r.guest_name +  (decodeURIComponent(square.notes) ? ": "+decodeURIComponent(square.notes) : '');
            } else {
                return r.base_price + " Cogzidel, " + r.guest_name + " (PENDING)" + (decodeURIComponent(square.notes) ? ": "+decodeURIComponent(square.notes) : '');
            }
        default: return hosting.currency_symbol + hostings[dataRow].price;
        }
    } else if ((dataCol==0) && square.isa) {
        if (square.daily_price) {
            return hosting.currency_symbol + square.daily_price;
        } else {
            return hosting.currency_symbol + hosting.price;
        }
    } else {
        return "&nbsp;";
    }
}


function before_submit() {
		var lwprice = document.getElementById('seasonal_price').value;
			var pri=document.getElementById('currency_hidden').value;

var pr=document.getElementById('currency_symbol_hidden').value;
             
               if($('lwlb_availability').value == 'Available')
		{
		if(lwprice>(pri*1000)){
		//	alert('Enter the value below'+pr+(pri*1000)+' price'); 
		return false;
		}
	 if(pri>lwprice)
		{
	//	alert('Must give the above  '+pr+pri+' price');
		return false;
		}
		}
		
		if($('lwlb_availability').value != 'Available' &&( $('lwlb_notes').value == 'Notes...'|| $('lwlb_notes').value ==''))
		{
			alert('Give some notes.');
			return false;
		}
		//window.name = '';
	$('lightbox_submit').disabled = true;
	$('lightbox_spinner').show();
}

function after_submit() {
	$('lightbox_submit').disabled = false;
	$('lightbox_spinner').hide();
    lwlb_hide_special();
}


function prepareLightbox(hosting_id,hosting_name,gridRow,gridMinCol,gridMaxCol) {
    var firstSquare = get_square(gridRow,gridMinCol);
    var lastSquare = get_square(gridRow,gridMaxCol);
    var startDate = new Date(g_start_date.getTime()); //date_parse_datestamp(firstSquare.dt);
    var stopDate = new Date(g_start_date.getTime()); //date_parse_datestamp(lastSquare.dt);

    var dataRow = gridRowToDataRow(gridRow);
    var hosting = hostings[dataRow];

    $$("span.currency_symbol").each(function(x){ x.innerHTML=hosting.currency_symbol; });
    $('lwlb_currency').value = hosting.currency;

    startDate.setDate(startDate.getDate() + gridColToDataCol(gridMinCol));
    stopDate.setDate(stopDate.getDate() + gridColToDataCol(gridMaxCol));

    /* Applies to all lightbox styles */

    $('lightbox_submit').disabled = false;
	$('lightbox_spinner').hide();

    $('lwlb_hosting_id').value = hosting_id;
    $('lwlb_visible_row').value = gridRow;

    if (firstSquare.gid) {
        $('lwlb_grouping_uid').value = firstSquare.gid;
    } else {
        $('lwlb_grouping_uid').value = "";
    }

    $('lwlb_starting_date').value = $('lwlb_starting_date_original').value = date_print_usa_date(startDate);
    $('lwlb_stopping_date').value = $('lwlb_stopping_date_original').value = date_print_usa_date(stopDate);
    $('lwlb_data_start_date').value = date_print_usa_date(g_start_date);
    $('lwlb_confirmation').value = firstSquare.confirmation ? firstSquare.confirmation : '';

    if (firstSquare.notes) {
         if (firstSquare.notes) $('lwlb_notes').value =  decodeURIComponent(firstSquare.notes);
    } else {
        $('lwlb_notes').value = "Notes...";
    }

    $('lwlb_reservation_section').hide();
    $('lwlb_normal_section').hide();

    // Reservation Case
    if (firstSquare.st==1) {
        g_enable_change_dates = false;
        var r = reservationHash.get(firstSquare.confirmation);

        $('lwlb_availability').value = "Booked";
        $('lwlb_booking_service_other').value = "Cogzidel";

        // Setup the date span
							/*	alert(r.start_date);*/
        $('lwlb_date_span_start').innerHTML = date_print_simplified(date_parse_datestamp(r.start_date));
        $('lwlb_date_span_stop').innerHTML = date_print_simplified(date_parse_datestamp(r.end_date));
        $('lwlb_date_single').hide();
        $('lwlb_date_span').show();

        $('lwlb_reservation_section').show();
        $('lwlb_reservation_guest_pic').src = r.guest_pic;
        $('lwlb_reservation_hosting_name').innerHTML = hosting_name;

        if (r.is_accepted) {
            $('lwlb_reservation_code').innerHTML = r.confirmation;
        } else {
            $('lwlb_reservation_code').innerHTML = "pending";
        }
        $('lwlb_reservation_guest_name').innerHTML = r.guest_name;
        $('lwlb_reservation_dates').innerHTML = date_print_simplified(date_parse_datestamp(r.start_date)) + " - " + date_print_simplified(date_parse_datestamp(r.end_date));
        $('lwlb_reservation_base_price').innerHTML = r.base_price;
        $('lwlb_reservation_guest_email').innerHTML = r.guest_email;
        $('lwlb_reservation_guest_email').href = "mailto:"+r.guest_email;
        $('lwlb_reservation_guest_phone').innerHTML = (""==r.guest_phone) ? "(unknown)" : r.guest_phone;

        if (r.is_accepted) {
            $('lwlb_reservation_contact').show();
            $('lwlb_reservation_itinerary').show();
            $('lwlb_reservation_accept').hide();
        } else {
            $('lwlb_reservation_contact').hide();
            $('lwlb_reservation_itinerary').hide();
            $('lwlb_reservation_accept').show();
        }

    } else {
        g_enable_change_dates = true;
        //if (minColIndex<g_today_index) return false; // disable click behavior if non-reservation

        $('lwlb_hosting_name').innerHTML = hosting_name;

        // Setup the date span
        if (gridMinCol==gridMaxCol) {
            $('lwlb_date_single').innerHTML = date_print_simplified(startDate);
            $('lwlb_date_single').show();
            $('lwlb_date_span').hide();
        } else {
								/*alert(startDate);*/
            $('lwlb_date_span_start').innerHTML = date_print_simplified(startDate);
            $('lwlb_date_span_stop').innerHTML = date_print_simplified(stopDate);
            $('lwlb_date_single').hide();
            $('lwlb_date_span').show();
        }

        $('lwlb_normal_section').show();

        //$('price').value = ""; //schedules[i].daily_price;
        if ((0==firstSquare.st) || !firstSquare.st) {
            $('lwlb_availability').value = 'Available';
        } else if ((2==firstSquare.st) || (5==firstSquare.st)) {
            $('lwlb_availability').value = 'Not Available';
        } else {
            $('lwlb_availability').value = 'Booked';
        }
    }

    lwlb_show('lwlb_calendar2',{no_scroll: true});

    centerLightbox(); // specific to multi-calendar vs. singular calendar
}


/**** DATE HELPERS ****/

function get_month_abbrev(month) {
    return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][month];
}

function date_parse_datestamp(datestamp) {
    // yyyy-mm-dd
    var parts = datestamp.split("-");
    return new Date(parts[0],parts[1]-1,parts[2]);
    //return new Date(get_month_abbrev(parts[1]-1)+" "+parts[2]+", "+parts[0]+" 00:00:00 "); // parts[0],parts[1]-1,parts[2]
}
function date_parse_usa_date(datestamp) {
    // mm/dd/yyyy
    var parts = datestamp.split("/");
    return new Date(parts[2],parts[0]-1,parts[1]);
}

function date_print_usa_date(dt) {
    // 2010-03-11: KEEP IT IN THE LOCAL TIMEZONE; OTHERWISE DATE OFFSET PROBLEMS!
    return (dt.getMonth()+1) + "/" + dt.getDate() + "/" + dt.getFullYear();
}

function date_print_simplified(dt) {
    //return get_month_abbrev(dt.getUTCMonth()) + " " + dt.getUTCDate();
    // 2010-03-11: KEEP IT IN THE LOCAL TIMEZONE; OTHERWISE DATE OFFSET PROBLEMS!
    return get_month_abbrev(dt.getMonth()) + " " + dt.getDate();
}

</script>
                <div id="lwlb_overlay" class="listyoursp_in1"></div>
                <script>
	
    function lwlb_force_stop_date_after_start() {
        var startDate = date_parse_usa_date($('lwlb_starting_date').value);
        var stopDate = date_parse_usa_date($('lwlb_stopping_date').value);

        if (startDate >= stopDate) {
            var newStopDate = new Date(startDate.getTime()+Date.one_day);
            $('lwlb_stopping_date').value = date_print_usa_date(newStopDate);
            $('lwlb_date_span_stop').innerHTML = date_print_simplified(newStopDate);
        }
    }

    function lwlb_start_date_calendar_popup() {
        if (!g_enable_change_dates) return false;

        new CalendarDateSelect($('lwlb_starting_date'), {
            popup_by:$('lwlb_date_span_start'),
            buttons:false,
            default_date_offset:0,
            year_range:[new Date().getFullYear(),new Date().getFullYear()+1],
            valid_date_check:function (dt){ return(dt >= calendar_helper_simple_today()); }
        } );
    }

    function lwlb_stop_date_calendar_popup() {
        if (!g_enable_change_dates) return false;
        
        var startDate = date_parse_usa_date($('lwlb_starting_date').value);
        var default_offset = Math.ceil((startDate - new Date())/Date.one_day) + 1;

        new CalendarDateSelect($('lwlb_stopping_date'), {
            popup_by:$('lwlb_date_span_stop'),
            buttons:false,
            default_date_offset:default_offset,
            year_range:[new Date().getFullYear(),new Date().getFullYear()+1],
            valid_date_check:function (dt){ return(dt > startDate); }
        } );
    }

    function lwlb_booking_service_changed() {
        if ($('lwlb_booking_service').value=="Other") {
            $('lwlb_booking_service_other').show();
        } else {
            $('lwlb_booking_service_other').hide();
        }

    }

    function lwlb_availability_changed() {
        switch ($('lwlb_availability').value) {
        case 'Available':
            $('lwlb_row_per_night').show();
            $('lwlb_row_service').hide();
            $('lwlb_row_value').hide();
            $('lwlb_notes').hide();
            break;
        case 'Not Available':
            $('lwlb_row_per_night').hide();
            $('lwlb_row_service').hide();
            $('lwlb_row_value').hide();
            $('lwlb_notes').show();
            break;
        case 'Booked':
            $('lwlb_row_per_night').hide();
            $('lwlb_row_service').show();
            $('lwlb_row_value').show();
            $('lwlb_notes').show();
            break;
        }
    }
    function lwlbOpenProfile() {
        var confirmation = $('lwlb_confirmation').value;
        var record = reservationHash.get(confirmation);
        window.open('<?php echo base_url(); ?>users/show/'+record.guest_id);
    }

    function lwlbOpenMessage() {
        var confirmation = $('lwlb_confirmation').value;
        var record = reservationHash.get(confirmation);
        window.open('<?php echo base_url(); ?>messaging/qt_with/'+record.guest_id);
    }

    function lwlbOpenItinerary() {
        var confirmation = $('lwlb_confirmation').value;
        var record = reservationHash.get(confirmation);
        window.open('<?php echo base_url(); ?>reservation/print_confirmation/?code='+confirmation);
    }

    function lwlbOpenAcceptDecline() {
        var confirmation = $('lwlb_confirmation').value;
        var record = reservationHash.get(confirmation);
        window.open('<?php echo base_url(); ?>reservation/approve?code='+confirmation);
    }
</script>

                <div id="lwlb_calendar2" class="lwlb_lightbox_calendar">
                  <div class="container med_12">
                    <div class="inner">
                      <form action="<?php echo site_url('calendar/modify_calendar').'?month='.$month.'&year='.$year; ?>" method="post" onSubmit="before_submit();; new Ajax.Request('<?php echo site_url('calendar/modify_calendar').'?month='.$month.'&year='.$year; ?>', {asynchronous:true, evalScripts:true, parameters:Form.serialize(this), onSuccess: function(transport){location.reload(true);}}); return false;">
                        <input type="hidden" name="data_start_date" id="lwlb_data_start_date" value="" />
                        <input type="hidden" name="confirmation" id="lwlb_confirmation" value="" />
                        <input type="hidden" name="starting_date_original" id="lwlb_starting_date_original" value="" />
                        <input type="hidden" name="stopping_date_original" id="lwlb_stopping_date_original" value="" />
                        <input type="hidden" name="grouping_uid" id="lwlb_grouping_uid" value="" />
                        <input type="hidden" name="currency" id="lwlb_currency" value="" />
                        <input type="hidden" name="starting_date" id="lwlb_starting_date" value="" onChange="$('lwlb_date_span_start').innerHTML = date_print_simplified(date_parse_usa_date(this.value)); lwlb_force_stop_date_after_start();" />
                        <input type="hidden" name="stopping_date" id="lwlb_stopping_date" value="" onChange="$('lwlb_date_span_stop').innerHTML = date_print_simplified(date_parse_usa_date(this.value)); lwlb_force_stop_date_after_start();" />
                        <input type="hidden" name="hosting_id" id="lwlb_hosting_id" value="">
                        <input type="hidden" name="visible_row" id="lwlb_visible_row" value="">
                        <div id="lwlb_reservation_section" class="listyoursp_inres">
                          <div class="header bottom_line">
                            <div class="header_text floatleft"> <span id="lwlb_reservation_guest_name">guest name</span> (<span id="lwlb_reservation_code">code</span>) </div>
                            <div class="close"><a href="#" onClick="lwlb_hide_special();return false;"><img src="<?php echo css_url(); ?>/images/sin_cal_close.gif" /></a></div>
                            <div class="clear"></div>
                          </div>
                          <div class="bottom_line">
                            <div class="listyoursp_in2"> <img id="lwlb_reservation_guest_pic" class="bottom_inner" src="" /> </div>
                            <div class="listyoursp_in3">
                              <div><span class="label">Dates:</span> <span id="lwlb_reservation_dates">dates</span></div>
                              <div><span class="label">Place:</span> <span id="lwlb_reservation_hosting_name">dates</span></div>
                              <div><span class="label">Price:</span> <span id="lwlb_reservation_base_price">price</span></div>
                            </div>
                            <div class="clear"></div>
                          </div>
                          <div style=""> <span id="lwlb_reservation_contact"><a id="lwlb_reservation_contact_details_show_link" href="#" onClick="Element.hide('lwlb_reservation_contact_details_show_link');Element.show('lwlb_reservation_contact_details');Element.show('lwlb_reservation_contact_details_hide_link');return(false);" >Contact</a><a id="lwlb_reservation_contact_details_hide_link" href="#" onClick="Element.hide('lwlb_reservation_contact_details_hide_link');Element.show('lwlb_reservation_contact_details_show_link');Element.hide('lwlb_reservation_contact_details');return(false);" style="display:none;">Hide Contact</a> |</span> <a href="#" onClick="lwlbOpenProfile();return false;">Profile</a> | <a href="#" onClick="lwlbOpenMessage();return false;">Message History</a> <span id="lwlb_reservation_itinerary">| <a href="#" onClick="lwlbOpenItinerary();return false;">Itinerary</a></span> <span id="lwlb_reservation_accept">| <a href="#" onClick="lwlbOpenAcceptDecline();return false;">Accept/Decline</a></span> </div>
                          <div id="lwlb_reservation_contact_details" class="listyoursp_email" style="display:none;"> <span class="label">Email:</span> <a id="lwlb_reservation_guest_email" href="#">guest email</a> <span class="label">Phone:</span> <span id="lwlb_reservation_guest_phone">guest phone</span> </div>
                        </div>
                        <div id="lwlb_normal_section">
                          <div class="header bottom_line">
                            <div id="lwlb_hosting_name" class="header_text listyoursp_in4">hosting name</div>
                            <div class="close"><a href="#" onClick="lwlb_hide_special();return false;"><img src="<?php echo css_url(); ?>/images/sin_cal_close.gif" /></a></div>
                            <div class="clear"></div>
                          </div>
                          <div id="lwlb_date_single">single date</div>
                          <div id="lwlb_date_span"> <span id="lwlb_date_span_start" class="calendar_link" onClick="lwlb_start_date_calendar_popup();">checkin</span> - <span id="lwlb_date_span_stop" class="calendar_link" onClick="lwlb_stop_date_calendar_popup();">checkout</span> </div>
                          
                          <div class="listyoursp_availb">
                            <table>
                              <tr>
                                <td class="listyoursp_availb1">Availability</td>
                                <td><select id="lwlb_availability" name="availability" value="" onChange="showmydiv()" >
                                    <option value="Available">Available</option>
                                    <option value="Booked">Booked</option>
                                    <option value="Not Available">Not Available</option>
                                  </select></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                         <div id="lwlb_price"><div class="input-addon">
                        	 <!--<span id="currency_symbol"  class="input-prefix-curency cal_sym"><b><?php echo get_currency_symbol($list_id);?></b></span>-->
                        <p><label class="listyoursp_price">Price</label><!--<span onkeypress="return isNumberKey(event)" id="currency_drop"  class="input-prefix-curency cal_sym"><b><?php echo get_currency_symbol($list_id);?></b></span>-->
 <input style="width:40%;" type="text" name="seasonal_price" id="seasonal_price"  value=""></p>
                        <input type="hidden" id="hidden_price"/>
                        </div></div>
                        <textarea id="lwlb_notes" class="listyoursp_price1" name="notes" style="display:none;" onClick="if (this.value=='Notes...') { this.value=''; };">Notes...</textarea>
                        <div class="listyoursp_price2">
                         <p data-error="price" class="ml-error" id="small_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too low. The minimum is 10.');?></p>
		<p data-error="price" class="ml-error" id="large_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too long. The maximum is 10000.');?></p>





                          <button id="lightbox_submit" type="submit" class="btn_list" name="commit"><span><span><?php echo translate("Submit"); ?></span></span></button>
                          
                          <img src="<?php echo base_url(); ?>images/spinner.gif" id="lightbox_spinner" style="display:none;"/> or <a href="#" onClick="lwlb_hide_special();return false;">Cancel</a> </div>
                      </form>
                    </div>
                  </div>
                </div>
                </div>
                <div>
                  <div class="Box editlist_Box">
                  <!--<div class="Box_Head1">
                    <h2 class="step"><span class="edit_room_icon calendar"></span>Calendar</h2>
                  </div>-->
                  <div class="calendarHeader listyoursp_cal"> 
                  	<h4> 
                  		<a href="<?php echo site_url('rooms/lys_next/edit/'.$list_id.'?month='.$prev_month.'&year='.$prev_year); ?>" class="calen_left">
                  			<i class="icon icon-chevron-left">&lt;</i>
                  		</a> 
                  		<div id="calendar_some" class="cc"><?php echo "$title $year"; ?></div> 
                  		<a href="<?php echo site_url('rooms/lys_next/edit/'.$list_id.'?month='.$next_month.'&year='.$next_year); ?>" class="calen_right">
                  			<i class="icon icon-chevron-left">&gt;</i>
                  		</a> 
                  	</h4> 
                  </div>
                    <div id="calendar2" class="Box_Content">
                      <div id="backdrop">
                        <div class="clear"></div>
                        <div>
                          <script>
    var select_range_start = null;
    var select_range_stop = null;
    var select_range_click_count = 0;
</script>
                          <div class="full_bubble">
                            <div class="inner">
                              <?php
		$first_day     = mktime(0,0,0,$month,1,$year);
		$days_in_month = cal_days_in_month(0,$month,$year);
		$day_of_week   = date('N',$first_day);
		
		$months        = array('January','February','March','April','May','June','July','August','September','October','November','December');
		$title         = $months[$month-1]; 
		
		if ($day_of_week == 7) { $blank = 0; } else { $blank = $day_of_week; }
		
		if (($month-1) == 0) 
		{
		$prevmonth = 1;
		$prevyear  = ($year-1);
		}
		else 
		{
		$prevmonth = ($month-1);
		$prevyear  = $year;
		}
		$day_prevmonth=cal_days_in_month(0,$prevmonth,$prevyear)-($blank-1);
		
		if($month == 01)
		{
		$prev_month = 12; $prev_year = $year - 1;
		}
		else
		{
		$prev_month = $month - 1; $prev_year = $year;
		}
		
		if($month == 12)
		{
		$next_month = 01; $next_year = $year + 1;
		}
		else
		{
		$next_month = $month+1; $next_year = $year;
		}

		$day_num    = 1;
		$day_count  = 1;
		$datenow    = time();
		$monthnow   = date('n',$datenow);
		$yearnow    = date('Y',$datenow);
		$daynow     = date('j',$datenow);
		?>
                              <div>
                                <!-- Table -->
                                <!--<div class="calendar-header">
                                  <div class="prev-month"> <a href="<?php echo site_url('calendar/single/'.$list_id.'?month='.$prev_month.'&year='.$prev_year); ?>"> <img alt="Previous" height="34" src="<?php echo base_url(); ?>images/bttn_month_prev.png" width="35" /> </a> </div>
                                  <div class="next-month"> <a href="<?php echo site_url('calendar/single/'.$list_id.'?month='.$next_month.'&year='.$next_year); ?>"> <img alt="Next" height="34" src="<?php echo base_url(); ?>images/bttn_month_next.png" width="35" /> </a> </div>
                                  <div class="display-month"><?php echo "$title $year"; ?></div>
								  
                                  <div class="clear"></div>
                                </div>-->
                                <div>
                                  <div>
                                    <div class="day_header">Sun</div>
                                    <div class="day_header">Mon</div>
                                    <div class="day_header">Tue</div>
                                    <div class="day_header">Wed</div>
                                    <div class="day_header">Thu</div>
                                    <div class="day_header">Fri</div>
                                    <div class="day_header">Sat</div>
                                    <div class="clear"></div>
                                  </div>
                                  <?php $k = 1; $i = 0; $j = 48;	while ($blank > 0) { if($k == 1) echo '<div>'; ?>
                                  <?php if(strtotime($prev_year.'-'.$prev_month.'-'.$day_prevmonth) < time()) { ?>
                                  <div class="tile disabled" id="tile_<?php echo $i; ?>">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_prevmonth; ?></div>
                                      <div class="line_reg" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"></span> </div>
                                    </div>
                                  </div>
                                  <?php }  else { 
                                  		//seasonal rate
                                  		$date=$prev_month.'/'.$day_prevmonth.'/'.$prev_year;
                                  		$price=getDailyPrice($list_id,get_gmt_time(strtotime($date)),$list_price);
                                  	?>
                                  <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_prevmonth; ?></div>
                                      <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id).get_currency_value1($list_id,$price); ?></b></span> </div>
                                    </div>
                                  </div>
                                  <?php } ?>
                                  <?php $blank = $blank-1; $day_count++; $day_prevmonth++; $i++; $j--; if($k == 7) { $k = 0; echo '<div class="clear"></div></div>'; } $k++; } ?>
                                  <?php while ($day_num <= $days_in_month) { if($k == 1) echo '<div>';  ?>
                                  <?php if(strtotime($year.'-'.$month.'-'.$day_num) < time()-(1 * 24 * 60 * 60)) {
                                  //	if($i < date('d',time()) || $day_num != date('d',time()))
                                 // { ?>
                                  <div class="tile disabled" id="tile_<?php echo $i; ?>">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_num; ?></div>
                                      <div class="line_reg" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"></span> </div>
                                    </div>
                                  </div>
                                  <!--<?php// } 
								 // else {
                                  		//seasonal rate
                                  	//	$date=$month.'/'.$day_num.'/'.$year;
                                  	//	$price=getDailyPrice($list_id,get_gmt_time(strtotime($date)),$list_price);
									 ?>-->
                                 <!-- <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_num; ?></div>
                                      <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id).get_currency_value1($list_id,$price); ?></b></span> </div>
                                    </div>
                                 </div>-->
                                  <?php //} 
                                  } else {
                                  		//seasonal rate
                                  		$date=$month.'/'.$day_num.'/'.$year;
                                  		$price=getDailyPrice($list_id,get_gmt_time(strtotime($date)),$list_price);
									 ?>
                                  <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_num; ?></div>
                                      <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id).get_currency_value1($list_id,$price); ?></b></span> </div>
                                    </div>
                                  </div>
                                  <?php } ?>
                                  <?php $day_num++; $day_count++; if ($day_count > 7) { echo "</tr><tr>"; $day_count = 1; } $i++; $j--; if($k == 7) { $k = 0; echo '<div class="clear"></div></div>'; } $k++; } ?>
                                  <?php $day_nextmonth = 1; while ($day_count > 1 && $day_count <= 7 ) { if($k == 1) echo '<div>'; ?>
                                  <?php if(strtotime($next_year.'-'.$next_month.'-'.$day_nextmonth) < time()) { ?>
                                  <div class="tile disabled" id="tile_<?php echo $i; ?>">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_nextmonth; ?></div>
                                      <div class="line_reg" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"></span> </div>
                                    </div>
                                  </div>
                                  <?php } else { 
                                  		//seasonal rate
                                  		$date=$next_month.'/'.$day_nextmonth.'/'.$next_year;
                                  		$price=getDailyPrice($list_id,get_gmt_time(strtotime($date)),$list_price);
                                  	?>
                                  <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_nextmonth; ?></div>
                                      <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id).get_currency_value1($list_id,$price); ?></b></span> </div>
                                    </div>
                                  </div>
                                  <?php } ?>
                                  <?php $day_count++; $day_nextmonth++; $i++; $j--; if($k == 7) { $k = 0; echo '<div class="clear"></div></div>'; } $k++; } ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <script>
						  
						  
						  
function render_grid(start_idx, stop_idx) {
    var prev_square;

    // ignore the first and last week of data (since it is padding)
    for(var i = start_idx; i <= stop_idx; i++){
      var square = schedules[0][i];
      var e = jQuery('#square_' + i);

      if(e.length == 0 || square === undefined)
        continue;

      var text = getSquareText(square, 0);

      // append to the square text if we detect that it's blocked out because the host denied a reservation
      if(prev_square != null && ((prev_square.st != square.st && square.st == 2 && square.gid != null) ||
        (prev_square.st == square.st && square.st == 2 && prev_square.gid == null && square.gid != null)))
        text += " (denied booking)";

      var span = getSpanBounds(0, i);

      var width = span[1] - span[0] + 1;

      if(text.length > (width * 8)){
        e.attr('title', text);
        text = text.substr(0, (width * 16) - 4) + "...";
      }

      e.find('span.content').html(text);
      var baseClass = e.attr('class').split(' ')[0];
      e.attr('class', baseClass + " " + square.cl + " " + square.sty);

      prev_square = square;

    }
}
render_grid(0, <?php if($i == 35) echo '34'; else echo '41'; ?>);
</script>
                        </div>
                        <div class="clear"></div>
                      </div>
                      <!-- backdrop -->
                    </div>
                    <!-- calendar2 -->
				
					
                  </div>
                </div>
                <div>
					<div class="Box editlist_Box">
							<div class="Box_Head1">
							<h2 class="step">
							<span class="edit_room_icon calendar"></span>
							Synchronize Calendars
							</h2>
							
							</div>
							<div id="synccal" class="Box_Content">

<h3>Import and synchronize all your calendar</h3>

<?php 
//date_default_timezone_set('Asia/Kolkata');
if (isset($_POST["icals"]))
{
$icals= $_POST["icals"];
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Calender</title>
	<link rel="stylesheet" type="text/css" href="codebase/style.css" />
</head>
<body>
	<center>
		<div id="install_main">
			<div id="install_content">
<?php
	$problems = Array();

if (isset($log)) {

if (isset($_POST["icals"]))
{
$icals= $_POST["icals"];
}
	/*! output export result */
	?>
	<div class="log_row">
	<?php
	if(isset($success_num))
	{
		?>
                
                     <br><div class="log_msg <?php echo $log["type"]; ?>"><?php if($success_num != 0){ echo $log["type"].': '.$log["text"]; ?>
					<div class="log_row">
                    <!--<div class="log_msg"><div class="num"></div>Import is completed. 
                    <?php echo anchor('rooms/lys_next/edit/'.$this->uri->segment(4) ,translate('Return to show the booked calender'));?>
                	</div>-->
					<?php } else
						{
							$nodata_msg = 1;
							//echo '<span style="color:red">There is no data in given URL</span>';
						} 
                    ?>
                    </div>														 
                </div>
		<?php
	}
else {
	echo '<br><span class="iconcolor2"">'.$log['type'].': '.$log['text'].'</span>&nbsp&nbsp';
	//echo anchor('rooms/lys_next/edit/'.$this->uri->segment(4) ,translate('Return to Import')).'<br>';
}
echo '</div>';
	$urls='';
	
}
 //else
 // {

$ical_import= $this->db->query("SELECT * FROM `ical_import` WHERE `list_id` = '".$this->uri->segment(4)."' order by id ASC");

$results=$ical_import->result_array();


	/*! outputing configuration form */
?>
				<div id="content_step_1">
					<form style="overflow: hidden;" action="<?php echo base_url().'rooms/lys_next/edit/'.$this->uri->segment(4);?>" method="post" enctype="multipart/form-data">
							<tr><td></td></tr>					

							<tr class="listyoursp_heig">
								<td class="listyoursp_heig1"></td>
							
								<td class="first_td listyoursp_heig2" valign="top"></td>
								<td class="listyoursp_heig3">
									<p style="float:left;width:80%;">URL:&nbsp;<input style="width:90%;" class="input listyoursp_heig4" name="ical_url" onBlur="if(this.value=='') this.value=this.defaultValue" onFocus="if(this.value==this.defaultValue) this.value=''" value="" type="text">
									<div class="btn_dash cal_import" >
									<input class="continue-button btn_dash listyoursp_heig5" type="submit" name="next" value="Import" id="bluebutt" style="padding: 4px 10px;"> 
									</div>
									<?php 
									if(isset($required_msg))
									{
										?>
										<span class="iconcolor2 cal_error" id="required_msg" >Please give valid URL.</span>
									<?php }
									if(isset($nodata_msg))
									{
										?>
										<span class="iconcolor2 cal_error">There is no data in given URL.</span>
										<?php
									}
									?>
									<p class="cal_note">Paste valid calendar link here, e.g. <?php echo site_url()?>calendar/ical/...</p>
									
								</td>
							</tr>

						</table>
					</form>
				
				</div>	
		</div>
	</center>
</body>
</html>
<?php 
	if(count($results) != 0)
	{?>
<p class="listyoursp1">
	<span class="listyoursp2">Imported URL's:</span>
	<br><br>	<?php 
foreach ($results as $ical_url)
{

 $urls = $ical_url['url'];
 $last_sync=$ical_url['last_sync'];
 ?>
<div id="list_url" class="listyoursp3">
<span class="listyoursp4">
<?php
 if($urls!='')
{
if(strlen($urls)>41)
{
  echo anchor($urls ,substr($urls, 0, 41)."...");
}
else
{
  echo anchor($urls,$urls);
}
?></span>
  <span class="listyoursp_las">
  <?php echo "Last sync : $last_sync";?></span>
  <br/>
  <span class="listyoursp_las1"><?php
 echo anchor('rooms/sync_cal/'.$ical_url['id'] ,translate('Sync Now')).'&nbsp&nbsp&nbsp';
 echo anchor('rooms/delete_cal/'.$ical_url['id'] ,translate('Remove'));
}
 ?></span>
</div>
<?php } echo '</p>'; } ?>
<br/>
<?php //} ?>
<p class="listyoursp_las2">
	<span class="listyoursp_las3">
	Export URL:
	</span> &nbsp;&nbsp;
	<div class="calender">
	<a class="listinner" href="<?php echo base_url().'calendar/ical/'.$this->uri->segment(4);?>">
	<?php echo base_url().'calendar/ical/'.$this->uri->segment(4);?>
	</a>
	</div>
</p>

							</div>
					
					</div>
				</div>	
                <!-- export instructions -->
              </div>
              </div>
            </div>
          </div>
          <div class="clear"></div>        
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<!-- edit_room -->
</div>
<?php 
	}
	 ?>
    	</div>
</div>

 <div class="center_entire price_hig med_6 mal_6 pe_12" id="price_container" style="display: none">
 <div class="js-standard-price">
 <div class="listinner_pri">
 <div style="display:none;" class="js-saving-progress saving-progress" id="price_saving">
  <h5<?php echo translate('saving...');?></h5>
</div>
</div>
	<div style="" class="center_baseprice center_entire_left med_6 mal_6 pe_12">
        <h3 class="listinner_baspri"><?php echo translate('Base Price');?></h3>
        <p><?php echo translate('The base nightly price and default currency for your listing.');?></p>
    </div>
	<div style="" class="center_night center_entire_left med_6 mal_6 pe_12">
        <p><?php echo translate('Per night');?></p>
       <div class="input-addon">
      <span id="currency_symbol"  class="input-prefix-curency"><b>$</b></span>
       <input class="night" type="text" id="night_price" onkeypress="return isNumberkey(event)" value="<?php if($lys_status->price == 1) echo $price; ?>"/>
       <input type="hidden" id="hidden_price"/>
       </div>
		<p data-error="price" class="ml-error" id="small_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too low. The minimum is 10.');?></p>
		<p data-error="price" class="ml-error" id="large_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too long. The maximum is 10000.');?></p>
		<div class="suggest"><?php echo translate('suggested');?></div>
        <div class="currency_entire"><?php echo translate('Currency');?></div>

		<!--<select class="currency_price listinner_pri1" id="currency_drop">-->

		<select class="currency_price notranslate" name="currency_drop" id="currency_drop" style="height: 37px;">

			<?php foreach($currency_result->result() as $row)
			{
				?>
				<option value="<?php echo $row->currency_code;?>" <?php if($currency==$row->currency_code) echo 'selected'; ?>><?php echo $row->currency_code;?></option>
				<?php
			}
			?>
		</select>
	</div>
</div>
 <div class="js-standard-price">
 <div class="listinner_pri">
 <div style="display:none;" class="js-saving-progress saving-progress" id="price_saving">
  <h5<?php echo translate('saving...');?></h5>
</div>
</div>
	<div style="" class="center_baseprice center_entire_left med_6 mal_6 pe_12">
        <h3 class="listinner_baspri"><?php echo translate('Instant Booking');?></h3>
        <!--<p><?php echo translate('The base nightly price and default currency for your listing.');?></p>-->
    </div>
	 <div class="list-type_bed">
                    <p class="control-list"><?php echo translate("Instant Booking?");?></p>
                    
					<?php if($instance_book==1)
					{ ?>
                     <input type="radio" name="instance_book" id="instance_book" value="1" checked/> Yes &nbsp;&nbsp;
                     <input type="radio" name="instance_book" id="instance_booka" value="2"> No
					
				<?php	}
                   else
				   	{ ?>
                     <input type="radio" name="instance_book" id="instance_book" value="1" > Yes &nbsp;&nbsp;				   		
                     <input type="radio" name="instance_book" id="instance_booka" value="2" checked> No

				   <?php 	} ?>
                    </div>

</div>

       <hr class="hr_center">
       <?php
       if($this->uri->segment(3) != 'edit')
	   {
       ?>
       <div  id="advance_price1" class="longer-stays">
       <p id="advance_price1"><?php echo translate('Want to offer a discount for longer stays?');?> 
       <span class="link_color" id="advance_price"><?php echo translate('You can also set weekly and monthly prices.');?></span></p>
</div>
<?php } ?>
<?php
       if($this->uri->segment(3) != 'edit')
	   {
       ?>
	<div style="display: none;" class="center_baseprice center_entire_left listinner_adpri med_6 mal_6 pe_12" id="advance_price_after">
      <?php } 
	   else { ?>
		   <div class="center_baseprice center_entire_left listinner_adpri med_6 mal_6 pe_12" id="advance_price_after">
	  <?php } ?>
        <h3 class="listinnerfont"><?php echo translate('Long-Term Prices');?></h3>
        <p><?php echo translate('Offer discounted prices for stays one week or longer.');?></p>
    </div>
    <?php
       if($this->uri->segment(3) != 'edit')
	   {
       ?>
	<div style="display: none;" class="center_night center_entire_left med_6 mal_6 pe_12" id="advance_price_after1">
		<?php }
	   else { ?>
               <div id="loading" class="center-block">
                  <img src="<?php echo base_url(); ?>images/load.gif"  />
             </div>  
           

		<div class="center_night center_entire_left med_6 mal_6 pe_12" id="advance_price_after1">
<?php } ?>
<div class="center_night center_entire_left pad_zero_list med_12 mal_12 pe_12">
    <div class="listinner_pri">
    <div style="display:none;" class="js-saving-progress saving-progress" id="advance_price_saving">
     <h5><?php echo translate('saving...');?></h5>
     </div>
    </div>
            <p><?php echo translate('Per Week');?></p>
       <div class="input-addon">
      <span id="currency_symbol" class="input-prefix-curency"><b>$</b></span>
        <input class="night" type="text" id="week_price" onkeypress="return isNumberKey(event)" value="<?php if($lys_status->price == 1 && $week_price!=0) echo $week_price; ?>"/>
        </div>
        	<p data-error="price" class="ml-error listinnerpri" id="small_week_length" style="display: none;"><?php echo translate('Your price is too low. The minimum is 70.');?></p>
		<p data-error="price" class="ml-error listinnerpri" id="large_week_length" style="display: none;"><?php echo translate('Your price is too long. The maximum is 14000.');?></p>
		<div class="suggest"><?php echo translate('suggested');?></div>
	<p class="reservation clearfix"><?php echo translate('If set, this price applies to any reservation 7 nights or longer.');?> </p>
   <div class="per-month-margin">
     <div class="listinner_pri">
   <div style="display:none;" class="js-saving-progress saving-progress">
  <h5><?php echo translate('saving...');?></h5>
    </div></div>
      <p><?php echo translate('Per Month');?></p>
       <div class="input-addon">
      <span id="currency_symbol" class="input-prefix-curency"><b>$</b></span>
		        <input class="night" type="text" id="month_price" onkeypress="return isNumberKey(event)" value="<?php if($lys_status->price == 1 && $month_price!=0) echo $month_price; ?>" />
        </div>
        	<p data-error="price" class="ml-error listinnerpri" id="small_month_length" style="display: none;"><?php echo translate('Your price is too low. The minimum is 300.');?></p>
		<p data-error="price" class="ml-error listinnerpri" id="large_month_length" style="display: none;"><?php echo translate('Your price is too long. The maximum is 60000.');?></p>
            <p class="reservation clearfix"><?php echo translate('If set, this price applies to any reservation 28 nights or longer.');?> </p>
            </div>
	</div>
	</div>
	
<!-- price -->

<?php if($this->uri->segment(3) == 'edit')
{?>
	<hr class="hr_center" style="">
        <div id="additional_price_container">
        	<div class="listinner_pri">
    <div style="display:none;" class="js-saving-progress saving-progress" id="clean_price_saving">
     <h5><?php echo translate('saving...');?></h5>
     </div>
    </div>
        <div class="listing_container">
            <div class="control-left listinnepri2 med_6 mal_6 pe_12">
				<h2>Additional Charges</h2>
				<p class="common_text">These charges are added to the reservation total.</p>
            </div>
            <div class="control-right listcont med_6 mal_6 pe_12">

<div class="list_tab_list">
        

<div id="js-cleaning-fee" class=" js-tooltip-trigger">
  <label for="listing_cleaning_fee_native_checkbox" class="label-large">
    <input type="checkbox" data-extras="true" name="cleaning_fees" id="listing_cleaning_fee_native_checkbox">
    Cleaning Fee
  </label>
  <div id="clean_textbox"  style="display: none">
      <div class="col-middle">
        <div class="input-addon">
          <span class="input-prefix" id="clean_currency"><?php echo $currency_symbol; ?></span>
          <input type="text" id="cleaning_price" data-extras="true" value="<?php echo $cleaning_fee; ?>" onkeypress="return isNumberKey(event)" name="listing_cleaning_fee_native" class="autosubmit-text input-stem input-large">
        </div>
        <p data-error="price" class="ml-error listinnerpri" id="small_clean_length" style="display: none;"><?php echo translate('Your price is too low. The minimum is 5.');?></p>
		<p data-error="price" class="ml-error listinnerpri" id="large_clean_length" style="display: none;"><?php echo translate('Your price is too long. The maximum is 300.');?></p>    
      </div>
      <div class="col-8 col-middle">
        
      </div>


    <p data-error="extras_price" class="ml-error hide"></p>
  </div>
</div>


<!--<div id="js-weekend-pricing" class="row-space-3 js-tooltip-trigger">
  <label for="listing_weekend_price_native_checkbox" class="label-large">
    <input type="checkbox" data-extras="true" id="listing_weekend_price_native_checkbox">
    Weekend Pricing
  </label>

  <div data-checkbox-id="listing_weekend_price_native_checkbox" class="hide">
    <div class="row row-table row-space-1">
      <div class="col-4 col-middle">
        <div class="input-addon">
          <span class="input-prefix">₱</span>
          <input type="text" data-extras="true" value="" name="listing_weekend_price_native" class="autosubmit-text input-stem input-large">
        </div>
      </div>
      <div class="col-8 col-middle">
        
      </div>
    </div>

    <p class="text-muted">
      Price is <strong>per night</strong> and applied to every Friday and Saturday in your calendar.
    </p>
  </div>
</div>-->


<div id="js-additional-guests" class="js-tooltip-trigger">
  <label for="price_for_extra_person_checkbox" class="label-large">
    <input type="checkbox" data-extras="true" id="price_for_extra_person_checkbox">
    Additional Guests
  </label>

  <div id="additional_textbox"  style="display: none">
      <div class="price_add_1" style="float: left;">
        <div class="input-addon" style="float: left;">
          <span class="input-prefix" id="additional_currency"><?php echo $currency_symbol; ?></span>
          <input type="text" data-extras="true" value="<?php echo $extra_guest_price; ?>" id="extra_guest_price" onkeypress="return isNumberKey(event)" name="listing_price_for_extra_person_native" class="autosubmit-text input-stem input-large">
        </div>
        </div>
      <div class="text-right price_add_2 pad_zero_list pe_12 med_12 mal_12">
        <label class="label-large list_innerpr">For each guest after</label>
      </div>
        <div style="float: left;" id="guests-included-select"><div class="select
            select-large
            select-block">
  <select name="guests_included" id="extra_guest_count" class="addi_price_select">
    
      <option value="1" <?php if($guest_count == 1) echo 'selected';?>>1</option>
    
      <option value="2" <?php if($guest_count == 2) echo 'selected';?>>2</option>
    
      <option value="3" <?php if($guest_count == 3) echo 'selected';?>>3</option>
    
      <option value="4" <?php if($guest_count == 4) echo 'selected';?>>4</option>
    
      <option value="5" <?php if($guest_count == 5) echo 'selected';?>>5</option>
    
      <option value="6" <?php if($guest_count == 6) echo 'selected';?>>6</option>
    
      <option value="7" <?php if($guest_count == 7) echo 'selected';?>>7</option>
    
      <option value="8" <?php if($guest_count == 8) echo 'selected';?>>8</option>
    
      <option value="9" <?php if($guest_count == 9) echo 'selected';?>>9</option>
    
      <option value="10" <?php if($guest_count == 10) echo 'selected';?>>10</option>
    
      <option value="11" <?php if($guest_count == 11) echo 'selected';?>>11</option>
    
      <option value="12" <?php if($guest_count == 12) echo 'selected';?>>12</option>
    
      <option value="13" <?php if($guest_count == 13) echo 'selected';?>>13</option>
    
      <option value="14" <?php if($guest_count == 14) echo 'selected';?>>14</option>
    
      <option value="15" <?php if($guest_count == 15) echo 'selected';?>>15</option>
    
      <option value="16" <?php if($guest_count == 16) echo 'selected';?>>16+</option>
    
  </select>
</div>
<p data-error="price" class="ml-error listinnerpri" id="small_additional_length" style="display: none;"><?php echo translate('Your price for each extra person too low. The minimum is 5.');?></p>
		<p data-error="price" class="ml-error listinnerpri" id="large_additional_length" style="display: none;"><?php echo translate('Your price for each extra person is too high. The maximum is 300.');?></p>    
      
</div>
     
    <p data-error="price_for_extra_person" class="ml-error hide">
      
    </p>
    <p class="text-muted-text">
      per person per night
    </p>
  </div>
</div>


  <label for="listing_security_deposit_native_checkbox" class="label-large select_check">
    <input type="checkbox" data-extras="true" id="listing_security_deposit_native_checkbox">
    Security Deposit
  </label>

  <div id="security_textbox"  style="display: none" class="select_check">
        <div class="input-addon">
          <span class="input-prefix" id="security_currency"><?php echo $currency_symbol; ?></span>
          <input type="text" data-extras="true" id="security_price_textbox" value="<?php echo $security; ?>" onkeypress="return isNumberKey(event)" name="listing_security_deposit_native" class="autosubmit-text input-stem input-large">
        </div>
           <p data-error="price" class="ml-error listinnerpri" id="small_security_length" style="display: none;"><?php echo translate('Your price is too low. The minimum is 5.');?></p>
		<p data-error="price" class="ml-error listinnerpri" id="large_security_length" style="display: none;"><?php echo translate('Your price is too long. The maximum is 300.');?></p>    

    <p data-error="security_deposit" class="ml-error hide"></p>
    <p class="text-muted text_box_hight">
      This deposit is held by DropInn and refunded to the guest unless you make claim within 48 hours of guest checkout.
    </p>
  </div>



  </div>


            </div>
            
		</div>
    </div>
<?php } ?>

<!-- price -->	
	
</div>
<div id="price-right-hover" class="floatleft med_3  mal_3 pe_12">
<div class="main_entire_right" style="display:none;" id="price_right">
	<div class="main_entire_right_inner listinner_price">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <b class="head-hover"><?php echo translate('Setting a price');?></b></p>
        <div class="inner_entire">
    	<p><?php echo translate("For new listings with no reviews, it's important to set a competitive price. Once you get your first booking and review, you can raise your price!");?></p>
		<p><?php echo translate("The suggested nightly price tip is based on:");?></p>
		<p class="bot_inner"><?php echo translate("1.Seasonal travel demand in your area.");?></p>
		<p class="bot_inner"><?php echo translate("2.The median nightly price of recent Airbnb bookings in your city.");?></p>
		<p class="bot_inner"><?php echo translate("3.The details of your listing.");?></p>
        </div>
    </div>
</div>

<div id="summary-price-right" style="display:none;" class="main_entire_right">
	<div class="main_entire_right_inner listspace_it">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <b class="head-hover"><?php echo translate("Offer a discount");?></b></p>
        <div class="inner_entire">
	    	<p><?php echo translate("Most hosts offer a discount for longer stays of a week or more.");?></p>
		</div>
    </div>
</div>
</div>
<div style="display:none; float:right;" id="cleaning-price-right" class="main_entire_right med_4 mal_3 pe_12">
<div class="main_entire_right_inner main_clean">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"> <?php echo translate("A great summary");?></b></p>
        <div class="inner_entire">
    		<p><?php echo translate("The cleaning fee is added to the total cost of every reservation at your listing.");?></p>
		</div>
    </div>
</div>
<div style="display:none;" id="additional-price-right" class="main_entire_right med_4 mal_3 pe_12">
<div class="main_entire_right_inner mainprice">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"> <?php echo translate("A great summary");?></b></p>
        <div class="inner_entire">
    		<p><?php echo translate("The additional guest charge is added to the nightly price of your listing (for each guest over the number you specify).");?></p>
		</div>
    </div>
</div>
<div class="center_entire med_6 mal_6 pe_12" id="overview_entire" style="display: none">
	<div class="title-overview center_entire_left overview_list med_6 mal_6 pe_12" >
        <h2><?php echo translate("Overview");?></h2>
        <p class="text_overview"><?php echo translate("A title and summary displayed on your public listing page.");?></p>
    </div>
	<div class="summary-overview center_entire_left  med_6 mal_6 pe_12" style="">
    <div>
     <div class="listyoursp_heig">
    <div style="display:none;" class="js-saving-progress saving-progress" id="overview_saving">
     <h5 class="over_title"><?php echo translate("saving...");?></h5>
     </div>
     </div>

        <h3 class="overview_head" style="font-weight: normal;"><?php echo translate ("Title");?></h3>
       
        
       
       <input type="text" class="text_characters" oncontextmenu="return false"  placeholder="<?php echo translate('Write a title');?>" id="title" maxlength="35" value="<?php if($lys_status->title == 1) echo $room_type; ?>"/><br>
        

        <span id="chars_count" class="charcount"><?php if($lys_status->title == 1){$count = strlen($room_type);
echo 35-$count.' '.translate("CHARACTERS LEFT");
}else echo '35 '.' '.translate("CHARACTERS LEFT");?></span>
        </div>
        <div class="overview">
     <div class="listinner_pri">
    <div style="display:none;" class="js-saving-progress saving-progress">
     <h5><?php echo translate("saving...");?></h5>
     </div></div>
            <h3 class="overview_head" style="font-weight: normal;"><?php echo translate("Summary");?></h3>
            <textarea id="summary" class="text_words" maxlength="250" placeholder="<?php echo translate('Write a summary in 250 characters or less');?>"><?php if($lys_status->summary == 1) echo $desc; ?></textarea>
    <span class="overviewhead" id="display_count"><?php if($lys_status->summary == 1){ $count = strlen($desc);
echo 250-$count.' '.translate("CHARACTERS LEFT");
}else echo '250 '.' '.translate("CHARACTERS LEFT");?></span>
		</div>
    </div>
</div>
<div class="floatleft med_3 mal_3 pe_12" style="display: none;" id="overview-textbox-hover">
	<div style="display:none;" id="overview-text-right" style="" class="main_entire_right">
	<div class="main_entire_right_inner listinner_price">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"><?php echo translate("A great title");?></b></p>
        <div class="inner_entire">
    	<p> <?php echo translate("A great title is unique and descriptive!  It should highlight the main attractions of your space.");?></p>
		<p><b class="head-hover"><?php echo translate("Example:");?></b></p>
		<p class="bot_inner"><?php echo translate("Charming Victorian in the Mission.");?></p>
		<p class="bot_inner"><?php echo translate("Cozy 2BD with Parking Included.");?></p>
		<p class="bot_inner"><?php echo translate("Amazing View from a Modern Loft");?></p>
        </div>
    </div>
</div>
<div style="display:none;" id="summary-text-hover" class="main_entire_right med_12 mal_12 pe_12 padding-zero">
<div class="main_entire_right_inner listinner_price">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"> <?php echo translate("A great summary");?></b></p>
        <div class="inner_entire">
    	<p><?php echo translate("A great summary is rich and exciting! It should cover the major features of your space and neighborhood in 250 characters or less.");?></p>
<p><b class="head-hover"><?php echo translate("Example:");?></b><br><?php echo translate("Our cool and comfortable one bedroom apartment with exposed brick has a true city feeling! It comfortably fits two and is centrally located on a quiet street, just two blocks from Washington Park. Enjoy a gourmet kitchen, roof access, and easy access to all major subway lines!");?></p>
		
        </div>
    </div>
</div>
</div>

<!-- detail -->


        <div class="center_entire med_6 mal_6 pe_12" id="detail_container" style="display: none">
        	<div class="listinner_pri">
    <div style="display:none;" class="js-saving-progress saving-progress" id="detail_saving">
     <h5><?php echo translate("saving...");?></h5>
     </div>
     </div>
        <div class="listing_container">
            <div class="control-left listinnepri2 med_6 mal_6 pe_12">
				<h2>Extra details</h2>
				<p class="common_text">Other information you wish to share on your public listing page.</p>
            </div>
            <div class="control-right listcont med_6 mal_6 pe_12" >

                <div class="list-type_bed">
                    <p class="control-list">House Rules</p>
<textarea id="house_rules_textbox" placeholder="How do you expect your guests to behave?" rows="4" name="house_rules" class="house_rules">
<?php
echo $house_rule;
?>
</textarea>
                </div>


            </div>
            
		</div>
<div class="floatleft med_4 mal_3 pe_12" id="details-textbox-hover">
<div style="display:none;" id="details-text-right" style="" class="main_entire_right">
	<div class="main_entire_right_inner listinner_price">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"><?php echo translate("A great title");?></b></p>
        <div class="inner_entire">
    	<p> <?php echo translate("A great title is unique and descriptive!  It should highlight the main attractions of your space.");?></p>
		<p><b class="head-hover"><?php echo translate("Example:");?></b></p>
		<p class="bot_inner"><?php echo translate("Charming Victorian in the Mission.");?></p>
		<p class="bot_inner""><?php echo translate("Cozy 2BD with Parking Included.");?></p>
		<p class="bot_inner"><?php echo translate("Amazing View from a Modern Loft");?></p>
        </div>
    </div>
</div>
<div id="ded" style="display:none" id="details_text_hover" class="main_entire_right med_12 mal_12 pe_12">
<div class="main_entire_right_inner listinner_price">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover">House Rules</b></p>
        <div class="inner_entire">
    	<ul class="head-hover">
    		<li>How do you expect your guests to behave?</li>
    		<li>Do you allow pets?</li>
    		<li>Do you have rules against smoking?</li>
    	</ul>
		
        </div>
    </div>
</div>
</div>
</div>
<!-- detail -->

<!-- Terms -->


        <div class="center_entire med_6 mal_6 pe_12" id="terms_container" style="display: none">
        	 <div class="listinner_pri">
 <div style="display:none;" class="js-saving-progress saving-progress" id="policy_saving">
  <h5><?php echo translate('saving...');?></h5>
</div>
</div>
        <div class="listing_container">
            <div class="control-left listcomment med_6 mal_6 pe_12">
				<h2>Terms</h2>
				<p class="common_text">The requirements and conditions to book a reservation at your listing. </p>
            </div>
            <div class="control-right listcount med_6 mal_6 pe_12">

<!--<div id="min-max-nights" class="row row-space-2">
	<div class="terms_segment_1">
  <label class="label-large">Minimum Stay</label>
  <div class="input-addon">
    <input type="text" class="input-stem input-large" value="10" id="min-nights" name="min_nights_input_value">
    <span class="input-suffix">nights</span>
  </div>
</div>
<div class="terms_segment_1">
  <label class="label-large">Maximum Stay</label>
  <div class="input-addon">
    <input type="text" class="input-stem input-large" value="30" id="max-nights" name="max_nights_input_value">
    <span class="input-suffix">nights</span>
  </div>
</div>
<p style="display:none;" class="ml-error" id="min-max-error"></p>
</div>-->

<!--<div class="row row-space-2">
  <div class="terms_segment_2">
    <label class="label-large">Check in after</label>
    <div id="check-in-time-select"><div class="select
            select-large
            select-block">
  <select name="check_in_time">
    
      <option value="">Flexible</option>
    
      <option value="0" selected="selected">12:00 AM (midnight)</option>
    
      <option value="1">1:00 AM</option>
    
      <option value="2">2:00 AM</option>
    
      <option value="3">3:00 AM</option>
    
      <option value="4">4:00 AM</option>
    
      <option value="5">5:00 AM</option>
    
      <option value="6">6:00 AM</option>
    
      <option value="7">7:00 AM</option>
    
      <option value="8">8:00 AM</option>
    
      <option value="9">9:00 AM</option>
    
      <option value="10">10:00 AM</option>
    
      <option value="11">11:00 AM</option>
    
      <option value="12">12:00 PM (noon)</option>
    
      <option value="13">1:00 PM</option>
    
      <option value="14">2:00 PM</option>
    
      <option value="15">3:00 PM</option>
    
      <option value="16">4:00 PM</option>
    
      <option value="17">5:00 PM</option>
    
      <option value="18">6:00 PM</option>
    
      <option value="19">7:00 PM</option>
    
      <option value="20">8:00 PM</option>
    
      <option value="21">9:00 PM</option>
    
      <option value="22">10:00 PM</option>
    
      <option value="23">11:00 PM</option>
    
  </select>
</div>
</div>
  </div>
  <div class="terms_segment_2">
    <label class="label-large">Check out before</label>
    <div id="check-out-time-select"><div class="select
            select-large
            select-block">
  <select name="check_out_time">
    
      <option value="">Flexible</option>
    
      <option value="0" selected="selected">12:00 AM (midnight)</option>
    
      <option value="1">1:00 AM</option>
    
      <option value="2">2:00 AM</option>
    
      <option value="3">3:00 AM</option>
    
      <option value="4">4:00 AM</option>
    
      <option value="5">5:00 AM</option>
    
      <option value="6">6:00 AM</option>
    
      <option value="7">7:00 AM</option>
    
      <option value="8">8:00 AM</option>
    
      <option value="9">9:00 AM</option>
    
      <option value="10">10:00 AM</option>
    
      <option value="11">11:00 AM</option>
    
      <option value="12">12:00 PM (noon)</option>
    
      <option value="13">1:00 PM</option>
    
      <option value="14">2:00 PM</option>
    
      <option value="15">3:00 PM</option>
    
      <option value="16">4:00 PM</option>
    
      <option value="17">5:00 PM</option>
    
      <option value="18">6:00 PM</option>
    
      <option value="19">7:00 PM</option>
    
      <option value="20">8:00 PM</option>
    
      <option value="21">9:00 PM</option>
    
      <option value="22">10:00 PM</option>
    
      <option value="23">11:00 PM</option>
    
  </select>
</div>
</div>
  </div>
</div>-->

<div class="row-space-2 listmes">
  <label class="label-large">Cancellation Policy</label>
  <div id="cancellation-policy-select"><div class="select
            select-large
            select-block">
  <select name="cancel_policy" id="cancel_policy" class="cancel_policy terms">
  <?php
  foreach($cancellation_policy_data->result() as $policy)
  {
  	?>
  	<option value="<?php echo $policy->id;?>" <?php if($policy->id == $cancellation_policy) echo 'selected'; ?>><?php 
  		if($policy->list_before_days > 1)
			{
				$day = $policy->list_before_days.' days';
				$hours = $policy->list_before_days.' days';
			}
			else
			{
				$day = $policy->list_before_days.' day';
				$hours = '24 hours';
			}
			if($policy->list_before_percentage == 100)
			{
				$percentage = 'Full';
			}
			else
			{
				$percentage = $policy->list_before_percentage.'%';
			}
			echo str_replace(array('{day}','{percentage}'),array($day,$percentage),$policy->cancellation_title); 
  		?></option>
  	<?php
  }
  ?>
  </select>
</div>
</div>
  <a target="_blank" id="js-learn-more" href="<?php echo base_url();?>pages/cancellation_policy">
    Learn More »
  </a>
</div>

            </div>
            
		</div>
    </div>


<!-- Terms -->

<div class="center_entire med_6 mal_6 pe_12" id="amenities_entire" style="display: none">

    <div>
        <div class="non_container">
        <div class="listinner_pri">
    <div style="display:none;" class="js-saving-progress saving-progress" id="amenities_saving">
     <h5><?php echo translate("saving...");?></h5>
     </div>
      </div>
          <div class="control-group">
                <div class="control-label med_6 mal_6 pe_12">
                    <h3><?php echo translate("Most Common");?></h3>
                    <p class="common_text"><?php echo translate("Common amenities at most DropInn listing");?></p>    
                </div>
                <div class="amenities-control med_6 mal_6 pe_12">
                <?php
                if($result_amenites != '')
					{
					$amenities_expload = explode(',',$result_amenites);
					}
                foreach($amenities->result() as $row)
				{
					if($result_amenites != '')
					{
					if(in_array($row->id, $amenities_expload))
					{
						$checked = 'checked';
					}	
					else {
						$checked = '';
					}
					}
					else {
						$checked = '';
					}
                ?>
                <div class="controls">
                    <input type="checkbox" class="lst_amt" id="<?php echo $row->id; ?>" name="amenities_<?php echo $row->id; ?>" value="<?php echo $row->id; ?>" <?php echo $checked;?>/> <p class="lst_cont"><?php echo $row->name; ?></p>
                </div>
                <?php
				}
				?>
                </div>
          </div>        
        </div>
    </div>
</div>

<div class="center_entire med_6 mal_6 pe_12" id="listing_entire" style="display: none">
    <div class="listing">
    <div class="listinner_pri">
    <div style="display:none;" class="js-saving-progress saving-progress" id="listing_saving">
     <h5 class="list_sav"><?php echo translate("saving...");?></h5>
     </div>
     </div>
        <div class="listing_container">
            <div class="control-left  med_6 mal_6 pe_12">
				<h2><?php echo translate("Listing Info");?></h2>
				<p class="common_text"><?php echo translate("Basic information about your listing.");?></p>
            </div>
            <div class="control-right  med_6 mal_6 pe_12">
                <div class="list-type">
                    <p class="control-list"><?php echo translate("Home Type");?></p>
                    <select class="control_select-box" id="home_type_drop">
                    	<?php  
					$property = $this->db->get('property_type');
					foreach($property->result() as $value) {
						  ?> <option <?php if($home_type == $value->type) echo 'selected'; ?>><?php echo $value->type;?></option>
					<?php }?>  
                    </select>
                </div>

                <div class="list-type">
                    <p class="control-list"><?php echo translate("Room Type");?></p>
                    <select class="control_select-box" id="room_type_drop">
                        <option <?php if($room_type_only == 'Entire Home/Apt') echo 'selected'; ?>><?php echo translate("Entire Home/Apt");?></option>
                        <option <?php if($room_type_only == 'Private Room') echo 'selected'; ?>><?php echo translate("Private Room");?></option>
                        <option <?php if($room_type_only == 'Shared Room') echo 'selected'; ?>><?php echo translate("Shared Room");?></option>
                    </select>
                </div>

                <div class="list-type">
                    <p class="control-list"><?php echo translate("Accommodates");?></p>
                    <select class="control_select-box" id="accommodates_drop">
                        <option <?php if($accommodates == '1') echo 'selected'; ?>>1</option>
                        <option <?php if($accommodates == '2') echo 'selected'; ?>>2</option>
                        <option <?php if($accommodates == '3') echo 'selected'; ?>>3</option>
                        <option <?php if($accommodates == '4') echo 'selected'; ?>>4</option>
                        <option <?php if($accommodates == '5') echo 'selected'; ?>>5</option>
                        <option <?php if($accommodates == '6') echo 'selected'; ?>>6</option>
                        <option <?php if($accommodates == '7') echo 'selected'; ?>>7</option>
                        <option <?php if($accommodates == '8') echo 'selected'; ?>>8</option>
                        <option <?php if($accommodates == '9') echo 'selected'; ?>>9</option>
                        <option <?php if($accommodates == '10') echo 'selected'; ?>>10</option>
                        <option <?php if($accommodates == '11') echo 'selected'; ?>>11</option>
                        <option <?php if($accommodates == '12') echo 'selected'; ?>>12</option>
                        <option <?php if($accommodates == '13') echo 'selected'; ?>>13</option>
                        <option <?php if($accommodates == '14') echo 'selected'; ?>>14</option>
                        <option <?php if($accommodates == '15') echo 'selected'; ?>>15</option>
                        <option <?php if($accommodates == '16') echo 'selected'; ?>>16+</option>
                    </select>
                </div>

            </div>
            
		</div>
    </div>
<p class="hr_center"></p>

    <div class="listing">
    <div class="listinner_pri">
    <div style="display:none;" class="js-saving-progress saving-progress">
     <h5><?php echo translate("saving...");?></h5>
     </div></div>
        <div class="listing_container">
            <div class="control-left med_6 mal_6 pe_12">
				<h2><?php echo translate("Rooms and Beds");?></h2>
				<p class="common_text"><?php echo translate("The number of rooms and beds guests can access.");?></p>
            </div>
            <div class="control-right med_6 mal_6 pe_12">
				
				    <div class="list-type_bed">
                    <p class="control-list"><?php echo translate("Bedrooms");?></p>
                    <select class="control_select-box_bed" id="bedrooms">
                    	<option selected disabled value=""><?php echo translate("Select...");?>
                        <option <?php if($bedrooms == '1') echo 'selected'; ?>>1</option>
                        <option <?php if($bedrooms == '2') echo 'selected'; ?>>2</option>
                        <option <?php if($bedrooms == '3') echo 'selected'; ?>>3</option>
                        <option <?php if($bedrooms == '4') echo 'selected'; ?>>4</option>
                        <option <?php if($bedrooms == '5') echo 'selected'; ?>>5</option>
                        <option <?php if($bedrooms == '6') echo 'selected'; ?>>6</option>
                        <option <?php if($bedrooms == '7') echo 'selected'; ?>>7</option>
                        <option <?php if($bedrooms == '8') echo 'selected'; ?>>8</option>
                        <option <?php if($bedrooms == '9') echo 'selected'; ?>>9</option>
                        <option <?php if($bedrooms == '10') echo 'selected'; ?>>10</option>
                        <option <?php if($bedrooms == '11') echo 'selected'; ?>>11</option>
                        <option <?php if($bedrooms == '12') echo 'selected'; ?>>12</option>
                        <option <?php if($bedrooms == '13') echo 'selected'; ?>>13</option>
                        <option <?php if($bedrooms == '14') echo 'selected'; ?>>14</option>
                        <option <?php if($bedrooms == '15') echo 'selected'; ?>>15</option>
                        <option <?php if($bedrooms == '16') echo 'selected'; ?>>16+</option>
                    </select>
                </div>
               <div class="list-type_bed">
                    <p class="control-list"><?php echo translate("Beds");?></p>
                    <select class="control_select-box_bed" id="beds"><?php echo translate("Select...");?>
                    	<option selected disabled value=""><?php echo translate("Select...");?>
                        <option <?php if($beds == '1') echo 'selected'; ?>>1</option>
                        <option <?php if($beds == '2') echo 'selected'; ?>>2</option>
                        <option <?php if($beds == '3') echo 'selected'; ?>>3</option>
                        <option <?php if($beds == '4') echo 'selected'; ?>>4</option>
                        <option <?php if($beds == '5') echo 'selected'; ?>>5</option>
                        <option <?php if($beds == '6') echo 'selected'; ?>>6</option>
                        <option <?php if($beds == '7') echo 'selected'; ?>>7</option>
                        <option <?php if($beds == '8') echo 'selected'; ?>>8</option>
                        <option <?php if($beds == '9') echo 'selected'; ?>>9</option>
                        <option <?php if($beds == '10') echo 'selected'; ?>>10</option>
                        <option <?php if($beds == '11') echo 'selected'; ?>>11</option>
                        <option <?php if($beds == '12') echo 'selected'; ?>>12</option>
                        <option <?php if($beds == '13') echo 'selected'; ?>>13</option>
                        <option <?php if($beds == '14') echo 'selected'; ?>>14</option>
                        <option <?php if($beds == '15') echo 'selected'; ?>>15</option>
                        <option <?php if($beds == '16') echo 'selected'; ?>>16+</option>
                    </select>
                </div>
                
               <div class="list-type_bed">
                    <p class="control-list"><?php echo translate("Bed type");?></p>
    <select class="control_select-box_bed" id="hosting_bed_type" name="hosting_bed_type">                    	
	<option selected disabled value=""><?php echo translate("Select...");?>
	<option value="Airbed" <?php if($bed_type == 'Airbed') echo 'selected'; ?> ><?php echo translate_admin("Airbed");?></option>
	<option value="Futon" <?php if($bed_type == 'Futon') echo 'selected'; ?>><?php echo translate_admin("Futon");?></option>
	<option value="Pull-out Sofa" <?php if($bed_type == 'Pull-out Sofa') echo 'selected'; ?>><?php echo translate_admin("Pull-out Sofa");?></option>
	<option value="Couch" <?php if($bed_type == 'Couch') echo 'selected'; ?>><?php echo translate_admin("Couch");?></option>
	<option value="Real Bed" <?php if($bed_type == 'Real Bed') echo 'selected'; ?>><?php echo translate_admin("Real Bed");?></option>
	</select>
                </div>
                
                <div class="list-type_bed">
                    <p class="control-list"><?php echo translate("Bathrooms");?></p>
                    <select class="control_select-box_bed" id="bathrooms">
                        <option selected disabled><?php echo translate("Select...");?></option>
                        <option <?php if($bathrooms == '0') echo 'selected'; ?>>0</option>
                        <option <?php if($bathrooms == '0.5') echo 'selected'; ?>>0.5</option>
                        <option <?php if($bathrooms == '1') echo 'selected'; ?>>1</option>
                        <option <?php if($bathrooms == '1.5') echo 'selected'; ?>>1.5</option>
                        <option <?php if($bathrooms == '2') echo 'selected'; ?>>2</option>
                        <option <?php if($bathrooms == '2.5') echo 'selected'; ?>>2.5</option>
                        <option <?php if($bathrooms == '3') echo 'selected'; ?>>3</option>
                        <option <?php if($bathrooms == '3.5') echo 'selected'; ?>>3.5</option>
                        <option <?php if($bathrooms == '4') echo 'selected'; ?>>4</option>
                        <option <?php if($bathrooms == '4.5') echo 'selected'; ?>>4.5</option>
                        <option <?php if($bathrooms == '5') echo 'selected'; ?>>5</option>
                        <option <?php if($bathrooms == '5.5') echo 'selected'; ?>>5.5</option>
                        <option <?php if($bathrooms == '6') echo 'selected'; ?>>6</option>
                        <option <?php if($bathrooms == '6.5') echo 'selected'; ?>>6.5</option>
                        <option <?php if($bathrooms == '7') echo 'selected'; ?>>7</option>
                        <option <?php if($bathrooms == '7.5') echo 'selected'; ?>>7.5</option>
                        <option <?php if($bathrooms == '8+') echo 'selected'; ?>>8+</option>
                    </select>
                </div>

            </div>
            
		</div>
    </div>

<!--<p>If you wish, you can permanently <span class="link_color">delete this listing.</span></p>-->

</div>


<!--<div class="center_entire med_6 mal_6 pe_12" id="photos_container" style="display: none">-->

<!--Drag and Drop image upload 1 start-->

<div class="center_entire span12 med_6 mal_6 pe_12" id="photos_container" style="display: none">


<!--Drag and Drop image upload 1 end-->


	<div class="container_photo" id="container_photo">
	<div class="price_upload">
    	<img src="<?php echo base_url(); ?>images/inbox.png" />
    	<h3><?php echo translate("Add a photo or two!");?></h3>
        <p><?php echo translate("Or three, or more! Guests love photos that highlight the features of your space.");?></p>
       <!-- <img src="<?php echo base_url(); ?>images/add_photo.png" />-->
       <button class="btn_list finish"><?php echo translate("Add Photos");?></button>
       <p id="no_file" style="display: none"><?php echo translate("No File Choosen");?></p>
        <input type="file" id="upload_file" class="nofile" name="upload_file[]"  multiple="true">
        
        <br>
       <!-- <button id="upload_file_btn" class="upload_btn"><?php echo translate("Upload");?></button>
        <button id="upload_file_btn_dis" class="upload_btn" style="display: none;" disabled><?php echo translate("Upload");?></button>
   --> </div>
    </div>
    <div class="container_add_photo" style="display: none">
      <div class="panel_photos">
    
         <div class="add_photo">
           <!-- <img src="<?php echo base_url(); ?>images/add_photo.png" />-->
       <button class="btn_list finish" id="upload_file_btn1"><?php echo translate("Add Photos");?></button>
       <button class="pin-on-map" id="upload_file_btn1_dis" style="display: none;" disabled><?php echo translate("Add Photos");?></button>
       <p id="no_file1" style="display: none"><?php echo translate("No File Choosen");?></p>
           <input type="file" id="upload_file1" class="nofile" name="upload_file1[]"  multiple="true">
           
           <br>
            <!--<button id="upload_file_btn1" class="upload_btn"><?php echo translate("Upload");?></button>
            <button id="upload_file_btn1_dis" class="upload_btn" style="display: none;" disabled><?php echo translate("Upload");?></button>
         --></div>
        
         <div class="one_photos">
         <div id="content" class="fullwidth" style="display: none">
        <div class="expand"></div>
         </div>
         <p id="photos_count"><?php echo $list_photo->num_rows(); ?> <?php echo translate("Photos");?></p>
         </div>	
         
    </div>
    </div>
    <div class="rel_photo_appear">
    <div class="photo_appear">
    <?php echo translate("Your first photo appears in search results!");?>
    </div>
    </div>
    <div class="ul_overflow">
   <ul class="photo_img" id="photo_ul" style="display: none">
   	<?php
   	if($list_photo->num_rows() != 0)
	{
   	foreach($list_photo->result() as $row)
     {
     	?>
    
      <li class="photo_img_sub" id="<?php echo 'list_photo_'.$row->id; ?>">
      <div  id="pannel_photo_item_id" class="pannel_photo_item">
      <div class="first-photo-ribbon"></div>
      <div class="photo-drag-target"></div>
      <a class="media-link"><img width="100%" src="<?php echo base_url().'images/'.$room_id.'/'.$row->name; ?>"></a>
    <!--Drag and Drop image upload 4 start(replace)-->
   <button data-photo-id="29701026" class="delete-photo-btn js-delete-photo-btn" onClick="jQuery(this).delete_photo('<?php echo $row->id; ?>')">

<!--Drag and Drop image upload 4 stop-->
        <i ><img src="<?php echo css_url(); ?>/images/delete-32.png"></i>
      </button>
      <div class="panel-body panel-condensed">
      <textarea name="" id="highlight_<?php echo $row->id;?>" rows="3" placeholder="<?php echo translate('What are the highlights of this photo?');?>" class="input-large" onKeyUp="$(this).highlight('<?php echo $row->id;?>')"><?php echo trim($row->highlights); ?></textarea>
      </div>
      </div>
      </li>
   <?php
     }
	}
   	?>
   
   </div>
   
   </ul>
   <!-- Video grabber 1 start -->
       
          <!-- Video grabber 1 end -->
   
   
   </div>
 
 <!-- Video grabber 2 start -->

  <!-- Video grabber 2 end -->
 

</div>
<div class="center_entire med_6 mal_6 pe_12 lst_pho" id="address_entire" style="display: none">
	<div class="title-address center_entire_left nofiles med_6 mal_6 pe_12">
        <h2><?php echo translate("Address");?></h2>
        <p class="text_address"><?php echo translate("Your exact address is private and only shared with guests after a reservation is confirmed.");?></p>
    </div>
	<div class="lys_address med_6 mal_6 pe_12 padding-zero">
		<?php
		if($street_address != '')
		{
			?>
			<img id="static_map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $lat.','.$lng; ?>&key=<?php echo $places_API; ?>&size=571x275&zoom=15&format=png&markers=color:red|label:|<?php echo $lat.','.$lng; ?>&sensor=false&maptype=roadmap&style=feature:water|element:geometry.fill|weight:3.3|hue:0x00aaff|lightness:100|saturation:93|gamma:0.01|color:0x5cb8e4">
			<?php
		}
		else {
			?>
			<img id="static_map" class="lst_map" src="<?php echo base_url(); ?>images/map_lys.png" />
			<?php
		}
		?>
        <p class="add_content" id="add_content"><?php echo translate("This Listing has no address.");?></p>
        <!--<img id="add_address" src="<?php echo base_url(); ?>images/add_address.png" />-->
        <button id="add_address" class="btn_list finish"><?php echo translate("Add address");?></button>
        <div id="after_address" class="checklist" style="display: none;">
        <strong id="str_street_address1"><?php echo $address;?></strong><br>
       <!-- <strong id="str_city_state_address1"><?php echo $city.' '.$state;?></strong><br>
        <strong id="str_zipcode1"><?php echo $zipcode;?></strong><br>
        <strong id="str_country1"><?php echo $country_name;?></strong>-->
        <a id="edit_address1"><?php echo translate("Edit address");?></a>
        </div>
    </div>
</div>

<div class="main_entire_right checklist1" style="display: none; id="address_right">
	<div class="main_entire_right_inner">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <?php echo translate("Your Address is Private");?></p>
        <div class="inner_entire">
    	<p class="text_inner"><?php echo translate("It will only be shared with guests after a reservation is confirmed.");?></p>
        </div>
    </div>
</div>
<div id="static_circle_map" class="main_entire_right mainenter med_4 mal_3 pe_12" style="display: none; float:right;">
	<div class="main_entire_right_inner">
    	<p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <?php echo translate("Your Address is Private");?></p>
        <div class="inner_entire">
    	<p class="text_inner"><?php echo translate("It will only be shared with guests after a reservation is confirmed.");?></p>
        </div>
    </div>
		<img id="static_circle_map_img" width="210" height="210" src="<?php echo $MapURL; ?>" />
		<p class="text_inner text_inner1">
		<?php echo translate("This is how your location appears on your listing page.");?> 
		<a target="_blank" href="<?php echo base_url().'rooms/'.$room_id;?>"> <?php echo translate("View on listing page");?> »</a>
		</p>
	</div>
<style>
.pac-container{
	position: relative;
	z-index: 9999;
}
.log_row{
	text-align: left;
margin-left: 48px;
}
.img_lang {
left: 11px;
top: 9px;
position: absolute;
}
#language_selector ul {
background: #fff;
border: 1px solid #c3c3c3;
text-align: left;
width: 109px;
cursor: pointer;
bottom: 21px;
position: absolute;
}
.container_bg {
background: #f0f0f0;
margin: 0 auto;
text-align: left;
padding: 10px 10px 30px;
-moz-border-radius: 10px;
-webkit-border-radius: 10px;
-khtml-border-radius: 10px;
border-radius: 10px;
/*width: 763px;*/
}

</style>
<div class="modal-list modal-with-background-image1" id="address_popup1" style="display: none">
 <div id="my_form">
  <div class="modal-content">
  <div class="panel">
  <div class="panel-header">
    <div class="media-body" style="display:block;">
    <a  class="pull-list-right" id="address_popup1_close">
        <img class="img-align" src="<?php echo base_url(); ?>images/list-close.png">
      </a>
        <h3 class="nav-heading">
          <p><span class="emailadd"><?php echo translate("Enter Address");?></span></p>
          <p><small class="addrs_small"><?php echo translate("What is your listing's address?");?></small></p>
        </h3>
     </div>
    </div>
  <div class="panel-body address_pop">
<!--<form id="js-address-form">-->
  <div class="row-space-2">
    <label for="country" class="label-large"><?php echo translate("Country");?></label>
    <div id="country-select"><div class="select select-block select-large">
  <select id="country" name="country_code">
  	<?php 
  	foreach($country->result() as $country_list)
  	{
  		if($country_name == $country_list->country_name)
		{
			$selected = 'selected';
		}
		else
	    {
		$selected = '';
        }
  		echo '<option value="'.$country_list->country_name.'"'.$selected.'>'.$country_list->country_name.'</option>';
  	}?>
       
  </select>
</div>
</div>
  </div>
  <div id="localized-fields">
  <div class="row-space-2">
    <label for="street" class="label-large"><?php echo translate("Street Address");?></label>
    <input type="text" placeholder="e.g. 123 Main St." class="input-large" value="<?php echo $street_address; ?>" id="lys_street_address">
  <div id="pac"></div>
  </div>
<input type="hidden" name="hidden_address" id="hidden_address"/>
<input type="hidden" name="hidden_lat" id="hidden_lat"/>
<input type="hidden" name="hidden_lng" id="hidden_lng"/>
  <div class="row-space-2">
    <label for="apt" class="label-large"><?php echo translate("Apt, Suite, Bldg. (optional)");?></label>
    <input type="text" placeholder="e.g. Apt #7" class="input-large" value="<?php echo $optional_address; ?>" id="apt" name="apt">
  </div>

  <div class="row-space-2">
    <label for="city" class="label-large"><?php echo translate("City");?></label>
    <input type="text" placeholder="e.g. San Francisco" class="input-large" value="<?php echo $city; ?>" id="city" name="city">
  </div>

  <div class="row-space-2">
    <label for="state" class="label-large"><?php echo translate("State");?></label>
    <input type="text" placeholder="e.g. CA" class="input-large" value="<?php echo $state; ?>" id="state" name="state">
  </div>
  
  <div class="row-space-2">
    <label for="zipcode" class="label-large"><?php echo translate("ZIP Code");?></label>
    <input type="text" placeholder="e.g. 94103" class="input-large" value="<?php echo $zipcode; ?>" id="zipcode" name="zipcode">
  </div>

</div>
<!--</form>-->
</div>
</div>
<div class="panel-footer">
    <button class="btn_dash_green finish next_font map_pin" id="address_popup1_cancel">
      <?php echo translate("Cancel");?>
    </button>
    <button id="next-btn" onclick="disable()" class="disable-btn btn-primarybtn next_active">
      <?php echo translate("Next");?>
    </button>
    <button id="next-btn" class="enable-btn btn-primarybtn next_active btn_list finish next_font" style="display: none">
      <?php echo translate("Next");?>
    </button>
</div>
</div>
</div>
</div>
<div class="main_entire_right" style="display:none;" id="calendar_right">
	<div class="main_entire_right_inner">
    	<p class="thimbthumb listthumb"><img class="postrela" src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <span class="nonmal"><?php echo translate("Choose the option that best fits your listing's availability. Don't worry, you can change this any time.");?></span></p>
    </div>
</div>
<div class="modal-list modal-with-background-image1" id="address_popup2" style="display: none">
<div id="my_form" class="addrespopup">
  <div class="modal-content">
  <div class="panel popup_bot">
  <div class="panel-header">
    <div class="media-body popup_body">
     <a  class="pull-list-right" id="address_popup2_close">
        <img class="img-align" src="<?php echo base_url(); ?>images/list-close.png">
      </a>
        <h3 class="nav-heading">
          <span class="localfind"><?php echo translate("Location Not Found");?></span><br>
          <small class="addrs_small"><?php echo translate("Manually pin this listing's location on a map.");?></small>
        </h3>
     </div>
    </div>
  <div class="panel-align panel-body">
 <p class="panel-para"><?php echo translate("We couldn't automatically find this listing's location, but if the address below is correct you can manually pin it's location on the map instead.");?></p>

<strong id="str_street_address"></strong><br>
<strong id="str_city_state_address"></strong><br>
<strong id="str_zipcode"></strong><br>
<strong id="str_country"></strong><br>
  </div>
<div class="panel-footer">
    <button class="btn_dash_green finish next_font map_pin" id="edit_address">
      <?php echo translate("Edit Address");?>
    </button>
    <button  class="btn_list finish next_font map_pin" id="pin-on-map">
     <?php echo translate("Pin on Map");?>
    </button>
</div>


</div>


</div>
</div>
</div>
            <input type='hidden' id="location_found"  value=""/>
            <input type='hidden' id="location_found_edit"  value=""/>
             <input type='hidden' id="location_found_popup_edit"  value=""/>
            
<!-- location found -->
<div class="modal-list modal-with-background-image1" id="address_popup3" style="display: none">
<div id="my_form">
 <div class="modal-content">
  <div class="panel-borderhead panel">
  <div class="panel-header">
    <div class="media-body" style="display:block;">
     <a  class="pull-list-right">
        <img class="img-align" id="close_popup3" src="<?php echo base_url(); ?>images/list-close.png">
      </a>
        <h3 class="nav-heading">
          <span class="emailadd"><?php echo translate("Pin Location");?></span><br>
          <small class="addrs_small"><?php echo translate("Move the map to pin your listing's exact location.");?></small>
        </h3>
     </div>
    </div>
  <div class="panel-body">
    

<div class="panel">
<div id="map-canvas1"></div>
  </div>
<div class="panel-border panel-align panel-body bortopnon">
<strong id="str_street_address2"></strong><br>
<strong id="str_city_state_address2"></strong><br>
<strong id="str_zipcode2"></strong><br>
<strong id="str_country2"></strong><br>
<a data-event-name="edit_address_click" class="edit-address-link" id="edit_popup3"><?php echo translate("Edit address");?></a>
</div>

</div>
<div class="panel-footer bornon">
   <!-- <button class="cancel-btn" id="cancel_popup3">
      Cancel
   </button>-->
    <button  class="pin-on-map enable_finish btn_list finish next_font" id="finish_popup3" style="display: none">
      <?php echo translate("Finish");?>
    </button>
    <button id="finish_popup3" class="pin-on-map disable_finish pinmap"  onClick="alert('Pin your listing exact location on map to continue.');">
      <?php echo translate("Finish");?>
    </button>
</div>


</div>
</div>
</div>
</div>
<div class="entire_footer clearfix">
	<div class="delete_box">
    	<p class="entire_title">&copy; <?php echo $this->dx_auth->get_site_title(); ?>, Inc.</p>
    </div>
    <div class="footer_container">
		<ul>
        	<li><a href="<?php echo site_url('pages/view/about'); ?>"><?php echo translate("About");?>&nbsp;&nbsp;|</a></li>
        	<li><a href="<?php echo base_url().'home/help'; ?>"><?php echo translate("Help");?>&nbsp;&nbsp;|</a></li>
        	<li><a href="<?php echo site_url('pages/view/press'); ?>"><?php echo translate("Press");?>&nbsp;&nbsp;|</a></li>
        	<li><a href="<?php echo site_url('pages/view/responsible_hosting'); ?>"><?php echo translate("Responsible Hosting");?>&nbsp;&nbsp;|</a></li>
        	<li><a href="<?php echo site_url('pages/view/policies'); ?>"><?php echo translate("Policies");?>&nbsp;&nbsp;|</a></li>
        	<li><a href="<?php echo site_url('pages/view/terms'); ?>"><?php echo translate("Terms & Privacy");?></a></li>
        </ul>
    </div>
    <div id="language" class="footer_right">
    	<?php
$default_language = $this->db->where('code','FRONTEND_LANGUAGE')->get('settings')->row()->int_value;
if($default_language == 2)
{
?>
<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'de,en,es,fr,it,pt', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php
}
else 
{
?>
<div id="language_display" class="rounded_top">
<div class="football_img"> 

	<!--<img class="img_lang" src="<?php echo css_url(); ?>/images/football.png" /> -->
	<i class="fa fa-soccer-ball-o img_lang footballicon"></i>

	<!--<img class="img_lang" src="<?php echo css_url(); ?>/images/football.png" />-->
	<!--<i class="fa fa-soccer-ball-o img_lang list_footer"></i>-->

	</div>
<div id="language_display_currency" class="language_set"> &nbsp; <?php if($this->session->userdata('language')=="") echo "English"; else echo $this->session->userdata('language'); ?></div>
</div>
<div class="arrow_sym">  </div>
<div id="language_selector_container" class="single_Lang" style="display:none;">
<div id="language_selector">
<ul id="locale2">
<?php 
$languages_core = $this->Common_model->getTableData('language',array('id <='=>6))->result();
foreach($languages_core as $language) { ?>
<li class="language option" id="language_selector_<?php echo $language->code; ?>" name="<?php echo $language->code; ?>"><?php echo $language->name; ?></li> <?php } ?>						
</ul>
</div>
</div>	
<?php
}?>																							
</div>
</div>

<?php
if($this->uri->segment(3) == 'edit_photo' || $this->uri->segment(3) == 'edit' || $total_status == 6 || $this->session->userdata('popup_status') == 1)
{
	?>
	<div class="modal-list modal-with-background-image" id="my_contain" style="display: none">
	<?php
}
else {
?>
<div class="modal-list modal-with-background-image" id="my_contain">
	<?php
}
?>
  <div class="modal-table-list">
    <div class="modal-cell-list">
      <div class="modal-content-list conteslist">
        <div  class="modal-body_img-list modal-body-list modal-body-picture-list">
        </div>
        <div class="modal-body-list modal-body-content-list text-center-list row-table-list">
          <div class="col-middle-list">
            <h1 class="row-space-7-list text-lite-list">
              
                <?php echo translate("We've created your listing.");?>
              
            </h1>
            <div class="p-relative steps-remaining-circle"><h1 class="steps-remaining-text">6</h1></div>
            <div class="new_row">
              <div class="p-relative col_listspace">
                <div class="h3_text"><?php echo translate("more steps to <br /> list your space");?></div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer-list text-center-list">
         <button  class="btn_list finish" id="finish_list">
            <?php echo translate("Finish my listing");?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>	

<div class="modal-list modal-with-background-image" id="seelist_container" style="display: none">
  <div class="modal-table-list">
    <div class="modal-cell-list">
      <div class="modal-content-list">
      	<?php
      	$list_is_featured_photo_result = $this->db->where('list_id',$room_id)->where('is_featured',1)->get('list_photo');
      	if($list_is_featured_photo_result->num_rows() != 0)
		{
			$list_is_featured_photo = $list_is_featured_photo_result->row()->name;
		}
else
{
	$list_is_featured_photo = '';
}
      	?>
        <div  class="modal-seelist-img modal-body-list modal-body-picture-list" id="final_photo" style="background: url(<?php echo base_url().'images/'.$room_id.'/'.$list_is_featured_photo; ?>); background-repeat:no-repeat; background-size:577px">
        </div>
        <div class="modal-body-list modal-body-content-list text-center-list row-table-list">
          <div class="col-middle-list">
            <i class="icon icon-ok-alt icon-size-3"><img src="<?php echo base_url(); ?>images/tick-tick.png"></i>
            <div class="h1 text-lite-list yourspace"><?php echo translate("Your space is listed!");?></div>
          </div>
        </div>
        <div class="panel-footer-list text-center-list">
        <button class="btn_dash_green finish" id="close_list">
          <?php echo translate("Close");?>
        </button>
         <button  class="btn_list finish" id="see_list">
           <?php echo translate("See your listing");?><span class="arow_img"><img src="<?php echo base_url(); ?>images/arrow-fw.png"></span>
        </button>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<style>
.goog-te-banner-frame.skiptranslate {
    display: none !important;
    } 
body {
    top: 0px !important; 
    }
    .pac-container
{
	left:0px !important;
   top:0px !important;
   position:relative !important;
}
.cal_sym{
	float: none;
padding: 5px 8px !important;
margin-right: -3px;
}

</style>
<script>
	 jQuery("#add_address").click(function() {
        jQuery("#address_popup1").delay(5e3).show()
         jQuery('body').css("overflow", "hidden !important;");
         
        
    });
     jQuery("#lys_street_address").keyup(function() {
       jQuery('#pac').append(jQuery('.pac-container'));
         
        
    });
</script>
