<?php error_reporting(E_ERROR | E_PARSE); ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link href="<?php echo css_url().'/jquery_colorbox.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url().'/search_result.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!--<link href="<?php echo css_url().'/bootstrap-responsive.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
-->
 <style type="text/css">
 .gm-style-iw {
  width: 222px !important;
  height: 213px !important;
      overflow: visible !important;
}
.instant{
    border: 0 none;
     height: 40px;
    position: relative;
    vertical-align: middle;
    width: 20px;
    padding:2px;
    top: -6px;
    margin:0px 0px -12px 0px;
   
   
}
.room_type1 {
    margin: 0 0 0 220px;
    text-align: left;
}
.heading_1n
{
	font-size:14px;
	margin:10px 0px 0px 28px;
}
 .gm-style-iw {
  width: 222px !important;
  height: 213px !important;
}
/*

.gm-style-iw + div {
	display: none;
	}*/
.close_but{
	margin-right: -8px;
    font-size: 15px;
    margin-top: -10px;
}
 body { font: normal 10pt Helvetica, Arial; }
 #map { width: 350px; height: 300px; border: 0px; padding: 0px; 
  }
  .message{
display: none;
text-align: left;
color: #565a5c;
position: absolute;
top: 30px;
right: -15px;
background: #fff;
padding: 5px;
line-height: 22px;
width: 280px;
box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
 
}
.anchor:hover + .message{
    display:block !important;
    /*z-index:10;*/
   z-index:99;
    float:left;
    margin:5px 0px 0px 5px;
   
}
#results
{
	/*margin-left: -24px;*/
}
@media screen and (-webkit-min-device-pixel-ratio:0) {
#results
{
	/*margin-left: -39px;*/
}
}

}*/
#location:focus, #location:hover, #checkin:hover, #checkin:focus, #checkout:hover, #checkout:focus {
    border: 1px solid #bbb !important;
}
.navbar.navbar-static-top{
	position: fixed !important;
left: 0;
right: 0;
}

 </style>
<script>




$(document).ready(function() {
	        
       // $('#header').css({'position':'fixed'});
   $(window).scroll(function() {
  
       var headerH = $('#header').outerHeight(true);
   
       var scrollTopVal = $(this).scrollTop();
        if ( $(this).scrollTop()) {
            $('#search_map').css({'position':'fixed','top' :'49px' , 'bottom' : '0px'});
            $('#Search_Main').css({'position':''});
            $('#header').css({'z-index':'9999'});
        } else {
            $('#search_map').css({'position':'fixed','top':'49px'});
            $('#Search_Main').css({'position':''});
            $('#header').css({'z-index':'9999'});
        }
              
       var headerH = $('#header').outerHeight(true);
   

       //var scrollTopVal = $(window).scrollTop();
      // var filter_height = $("#search_main_left_top").height();
      // var filter_height_xs = $(".search-main-right").height();
      //  if ( scrollTopVal > filter_height + filter_height_xs + 40 ) {
        	
        	
     //$('#room_type_container').css({'display':'none'});
     //   $('#price_container').css({'display':'none'});

       var scrollTopVal = $(this).scrollTop();
        if ( scrollTopVal > 282 ) {

          $('.more_filters1').html("Filters");
          $('#filters_lightbox_nav').css({'position':'fixed', 'margin-top': '0px', 'top' :'48px','width':'100%','min-width':'57%','z-index' :'16','padding-right':'10px','margin-left':'15px !important'});
		//  $('#filters_lightbox_nav').css('border':'1px solid #000000');

  } 
        else {
        	$('.more_filters1').html("More Filters");

            $('#filters_lightbox_nav').css({'position':'static', 'margin-top': '0',  'top':'0px','width':'auto','width':'100%','margin-left':'0'});
          // $('#room_type_container').css({'display':'block'});
         //  $('#price_container').css({'display':'block'});

         //   $('#filters_lightbox_nav').css({'position':'static', 'margin-top': '0',  'top':'0px','width':'auto','min-width':'60%','margin-left':'0'});

       }
        
      //  window.resize(scroll);
     
    });

    
    
  
  
/* price  */

/*$("#slider_values").slider({
{
	alert('success');
	
});*/
 	
 	
/* price */
    
 

    /*room type */
  

 $("#roomType").hide();

$(".room_0").click(function() 
{
 
     if($(".rroom").is(":checked")  ) 
    {
       $("#roomType").show();
  
    } 
    else 
    {
    $("#roomType").hide();
 
    }

});



  
$("#roomType").hide();
if($("#roomType").click(function(){
	//alert('success');
	 $(".rroom").attr("checked", false)
	 $("#roomType").hide();
})) 
 
 /*   
 $("#roomType").hide();
if($("#roomType").click(function(){
	 $(".room_1").attr("checked", false)
	 $("#roomType").hide();
}))

$("#roomType").hide();
if($("#roomType").click(function(){
	 $(".room_2").attr("checked", false)
	 $("#roomType").hide();
}))*/ 
    
   
   
    
    /* room type */
    
    /* size */
   


$("#roomType4").hide();
 $( ".size1" ).click(function() {
 	
 	 //alert('success');
 	 if ( $('#min_bedrooms').value == '' && $('#min_bathrooms').value == '' && $('#min_beds').value == '' )	 
     {
     	 $("#roomType4").hide();
     }
 	 else
 	 {
 	 	 $("#roomType4").show();
 	 }
 
 });
  
 
 
 $("#roomType4").hide();
if($("#roomType4").click(function(){
	
	 
	$(".size1").prop('selectedIndex', 0);
	  	 
	 $("#roomType4").hide();
})) 

 
 
 
   /* size */
    
    
    
    
    
    
    
    /* property type  */
   
   
   $("#roomType2").hide();

$(".property_type1").click(function() 
{
	
	//alert('super');
 
 
     if($(".property_type1").is(":checked")  ) 
    {
      //  alert('success1');
       $("#roomType2").show();
  
    } 
    else 
    {
    $("#roomType2").hide();
 
    }

});

$("#roomType2").hide();
if($("#roomType2").click(function(){
	
	 $(".property_type1").attr("checked", false)
	 $("#roomType2").hide();
})) 




    
   /* property type */ 
    
   /* Amenities */ 
    
    $("#roomType3").hide();

$(".amenities1").click(function() 
{
	
	//alert('super');
 
 
     if($(".amenities1").is(":checked")  ) 
    {
      //  alert('success1');
       $("#roomType3").show();
  
    } 
    else 
    {
    $("#roomType3").hide();
 
    }

});

$("#roomType3").hide();
if($("#roomType3").click(function(){
	
	 $(".amenities1").attr("checked", false)
	 $("#roomType3").hide();
})) 

 
    /* Amenities */  
    
  
  
    

 

    $('#footer_search').hide();
$.ajax({
		url: '<?php echo base_url().'search/footer'; ?>',
		type: 'POST',
        success: function(data)
        {
        	$('#clientsCTA').html(data);
        }
     });
     
//moblie footer script start//

//mobile footer script end//

      $('#lang').click(function () {
      $('#clientsCTA').show();
    $('#clientsDropDown #clientsDashboard').slideToggle({
      direction: "up"
    }, 300);
    $(this).toggleClass('clientsClose');
  }); // end click
  
  var cancel = false;
  $('.selectContainer').click(function()
  {
  	$('.selectWidget').show();
  	if(i == 1 || i == 2)
  	{
  		$('#new_wishlist').show();
  		$('.doneContainer').hide();
  	}
  	else if(done == 1)
  	{
  		done = 0;
  		$('.selectWidget').hide();
  	}
  	else if(i == 3)
  	{
  		$('.doneContainer').show();
  		$('#new_wishlist').hide();
  	}
  	else
  	{
  	$('.doneContainer').show();
  	$('#new_wishlist').hide();
  	 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/get_wishlist_category";?>',
		   data: 'list_id='+$('#hidden_room_id').val(),
		   success: function(data){
		   		$('.selectList').replaceWith(data);
		   		i = 3;
				   }
		 }); 
  	}
  	
  	if($('#new_wishlist').css('display') == 'none' && i != 3)
  	{
  		i = 0;
  	}
  	else if(i == 3)
  	{
  		i = 3;
  	}
  	else {
  		i = 1;
  	}
  	cancel = true;
  	 	
  })
 var i = 0;
 $('#create_new').click(function()
 {
 	i++;
 	$('.doneContainer').hide();
 	$('#wishlist_category_name').val('');
 	$('#new_wishlist').show();
 })
 
 $('#wishlist_close').click(function()
			{
				$('body').css({'overflow':'scroll'});
				$('.modal_save_to_wishlist').hide();
			})
			
$(".panel-header").hover(function(){
   // $("div.description").show();
   $('#new_wishlist').hide();
   i = 0;
   $(".selectWidget").hide();
});

$(".panel-footer").hover(function(){
   // $("div.description").show();
   $('#new_wishlist').hide();
   i = 0;
   $(".selectWidget").hide();
});

$('.wishlist-note').focus(function()
{
	$('#new_wishlist').hide();
	i = 0;
	 $(".selectWidget").hide();
})

$('#add_note').click(function()
{
	$('#new_wishlist').hide();
	i = 0;
	$(".selectWidget").hide();
})

$('#wishlist_category').click(function()
{
	var dataString = 'name='+$('#wishlist_category_name').val()+'&privacy='+$('#privacy').val()+'&list_id='+$('#hidden_room_id').val();
	 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/wishlist_category";?>',
		   data: dataString,
		   success: function(data){
		   	
		   		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/get_wishlist_category";?>',
		   data: 'list_id='+$('#hidden_room_id').val(),
		   success: function(data){
		   	    $('.selectWidget').hide();
		   		$('.selectList').replaceWith(data);
				   }
		 });
		   		
				   }
		 });
})

var done = 0;

$('#wishlist_done').click(function()
{
	var wishlist_count = 0;
	var name = '';
	done = 1;
	$("input[type=checkbox]:checked").each ( function() 
	{
   wishlist_count++;
   name = $('#'+$(this).val()).text();
});

if(wishlist_count == 1)
{
$('#selected span').text(name);
}
else
{
$('#selected span').text(wishlist_count+' Wish Lists');
}

$('.selectWidget').css({'display':'none'});

})

$('#wishlist_save').click(function()
{
	var wishlist_count = 0;
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		wishlist_count++;
		
   		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/add_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val()+'&i='+wishlist_count,
		   success: function(data){
		   	   
		   	   $.ajax({
  				url: "<?php echo site_url('search/add_my_shortlist'); ?>",
  				async: true,
  				type: "POST",
  				data: "list_id="+$('#hidden_room_id').val(),
  				success: function(data) {
  				if(data == "error")
  				window.location.replace("<?php echo base_url(); ?>users/signin");
  				else
  				{
    			$('.'+$('#hidden_room_id').val()).attr('value', '<?php echo translate("Saved to Wish List"); ?>'); 
    		    $('.'+$('#hidden_room_id').val()).attr('src', '<?php echo base_url().'images/heart_rose.png'; ?>'); 
    		    $('.modal_save_to_wishlist').hide();
    		    $('body').css({'overflow':'scroll'});
    		    }
  				}
   				});
		   	   
				   }
		 });
});

if(wishlist_count == 0)
{	
	 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/remove_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val(),
		   success: function(data){
		   			   	 
    			$('.'+$('#hidden_room_id').val()).attr('value', '<?php echo translate("Save To Wish List"); ?>'); 
    			$('.'+$('#hidden_room_id').val()).attr('src', '<?php echo base_url().'images/search_heart_hover.png'; ?>'); 
    			$('.modal_save_to_wishlist').hide();
    			$('body').css({'overflow':'scroll'});
    		    
		   	   
				   }
		 });
	
   		 
}

})
 
 });
 
 
</script>

<script src="<?php echo base_url(); ?>js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
<!--<script>
	function translate_today(today) 
	{
		alert('success');
	}
</script>

<!-- //clientsDropDown -->
<script>

function myFunction() {
    jQuery('.clearfix_type').toggle('show');
}
function myFunctionamenities()
{

	//alert('welcome');

	jQuery('.lightbox_filter_container').toggle('show');
}

</script>
<script>

	function myfunctionmore() {
   	var $elem = $('#search_main_left_top'); 
		jQuery('html, body').animate({scrollTop: $elem.height()+50}, 800);
    jQuery('.more_filter_tab').hide();
    jQuery('#search_body').hide();
    
    jQuery('#results').hide();
    jQuery('#results_footer').hide();
    jQuery('.property_type_search').show();
    jQuery('.amenities_search').show();
    jQuery('.keywords_search').show();
    jQuery('#show_listing').show();
    jQuery('.size_search').show();
    jQuery('#results_footer').hide();
}	

</script>
<script>
	function myfunctionshowlist()
	{
		var $elem = $('#Search_Main'); 
			jQuery('html, body').animate({scrollTop: '0px'}, 800);
	jQuery('#results').toggle('show');
	jQuery('#search_body').toggle('show');
	jQuery('.lightbox_filters_class').hide();
	jQuery('.more_filter_tab').show();
	jQuery('#results_footer').show();
	
	jQuery('.property_type_search').hide();
	jQuery('.size_search').hide();
    jQuery('.amenities_search').hide();
    jQuery('.keywords_search').hide();
    jQuery('#show_listing').hide();
	
	}
</script>
<?php
$this->session->set_userdata('checkin','');
$this->session->set_userdata('checkout','');
$this->session->set_userdata('no_of_guest','');
?>

<?php $zz=0; ?>

<script type="text/javascript">


function show()
{

var location =  document.getElementById('location').value;
		var dataString = "&location=" +location;
			
	
	 b_url = "<?php echo base_url().'search/sample'?>";
		 $.ajax({
		   type: "GET",
		   url: b_url,
		   data: dataString,
		   success: function(data){
		   		$('#neighbor').html(data);
				   }
		 });
	
}


</script>


 <!---Include Validation for the Book it button----->
          <script type="text/javascript">
          
          
          
 $('#book_it_button').live('click',function()
  {
  	
  	var hid = $(this).attr("name");
var ratepernight=$(this).attr("alt");
var checkin = $("#checkin").val();

	var checkout = $("#checkout").val();
	var guest = $("#number_of_guests").val();
			
	//var dataString = "checkin=" +checkin +"&checkout="+checkout + "&guest="+guest+"&ratepernight=" +ratepernight; 
	
	var dataString = "checkin=" +checkin +"&checkout="+checkout + "&guest="+guest+"&ratepernight=" +ratepernight; 
	var c1= encodeURIComponent(checkin);
var c2=encodeURIComponent(checkout);
if($('#checkin').val()=='mm/dd/yy' && $('#checkout').val()=='mm/dd/yy')
{
	alert("Please choose the dates");

	return false;
}   
else
{  
  window.location.href="<?php echo base_url(); ?>payments/index/"+hid+"?"+dataString;
      }
     
    });
    
 
 
</script>



<style>

/*#clientsDropDown {
  position:fixed;
  bottom:0;
  width: 100%;
 /* padding-bottom:2%;
margin-bottom: 30px;
  z-index: 100;
}*/
.sch_fot_pop{
	height:150px !important;
}
#clientsOpen {
  color: #ececec;
  cursor: pointer;
  margin: -2px 0 0 10%;
  padding: 0 15px 2px;
  text-decoration: none;
}
/*#clientsCTA {
  width: 100%;
text-align: center;
padding: 0px 0;
text-decoration: none;
  /*background:#eb3c44;
  width:100%;
  color: #CCCCCC;
  text-align:center;
  margin-top: -80px;
  padding: 0px 0;
  text-decoration: none;
  padding:0 118px 0 40px;
  position: fixed;
}*/
/*#lang{
	border: 1px solid #dce0e0;
background: white;
color: #565a5c;
padding-bottom: 0%;
position: fixed;
background: none repeat scroll 0 0 #FFFFFF;
border-color: #DCE0E0;
color: #565A5C;
height: 45px;
padding: 12px;
left: 10px;
bottom: -1px;
font-weight: bold;
}
#lang:hover
{
	border:1px solid #b1b1b1; 
}
#clientsDropDown .clientsClose {
  background-image: url(images/close.png);
}
#clientsDropDown #clientsDashboard {
 display: none;
position: fixed;
width: 100%;
bottom: 0;
  /*float:bottom;
}*/
.pac-container {
   /* width: 450px !important;*/
  z-index: 9999;
}
.down_arrow
{
position: absolute;
right: 10px;
top: 15px;
}
.navbar-fixed-top, .navbar-fixed-bottom, .navbar-static-top{
margin-left:0px;
margin-right:0px;
}
body{
padding-left:0px;
padding-right:0px;
}

 /* #keywords
   {
   	margin: -10px 0px 0px 177px;
   	width:60%;
   }  */
 /* @media screen and (min-width:320px) and (max-width:767px){
	#clientsDropDown #clientsDashboard {
position: static;
}
#clientsDropDown {
  position:static;
  margin-bottom:0px;
}
#lang{
	/*display:none;
}
	}*/
	@media (min-width: 320px) and (max-width: 640px){
	.res_footer_container{
		margin:0px !important;
	}
	#clientsDropDown {
  position:relative !important;
  z-index: 100 !important;
}
#clientsCTA {
	 position:relative !important;
 }
}
@media (min-width: 320px) and (max-width: 640px)
{
	#lang , #close_search_footer
	{
		 display: none !important;
	}
	#clientsCTA
	{
		display: block !important;
	}
	#search_map
	{
	position: relative !important;
top: 0 !important;
width: 100% !important;
height: 300px !important;
}
#map_options
{
	position: absolute !important;
}
#filters_lightbox_nav
{
	width:100%;
}
#Search_Main
{
	margin-top:0px !important;
}
}
@media (min-width: 760px)
{
	#search_map
	{
	position: fixed !important;
}
#map_options
{
	position: relative;
}
}
#clientsOpen {
  color: #ececec;
  cursor: pointer;
  margin: -2px 0 0 10%;
  padding: 0 15px 2px;
  text-decoration: none;
}
/*@media (min-width: 759px)
{
/*#clientsCTA {
  margin-top: -231px;
  position: fixed;
	width:100%;
  /*background:#414142;
  color: #CCCCCC;
  text-align:center;
  padding: 0px 0;
  text-decoration: none;
  padding:0 118px 0 40px;
  
}*/
/*#clientsDropDown {
  position:absolute;
  bottom:0;
  width: 100%;
 /* padding-bottom:2%;
margin-bottom: 30px;
  z-index: 100;
}*/
/*#clientsDropDown #clientsDashboard {
  display: none;
  position:absolute;
  margin-top: -134px;
  float:bottom;
}*/
		


</style>
	<!-- this partial is wrapped in a div class='search_filters' -->
	<!-- Map Container Hidden-->         
      <div id="search_filters_wrapper" class="search-main-right med_5 mal_5 pe_12">
	<div id="search_filters">
		<div id="map_options" class="postifixed"><input type="checkbox" name="redo_search_in_map" id="redo_search_in_map" /><label for="redo_search_in_map"><?php echo translate("Redo Search Here"); ?></label>&nbsp;&nbsp;&nbsp;<img src="<?php echo base_url() ?>images/refresh_22.png" height="17" width="17" /></div>
		<div id="map_wrapper">       
    		<div id="search_map" class="searchmap1"></div>           
	</div>
	</div>
	</div>
	
	<div class="search_main_right_tool med_7 mal_7 pe_12">
<div id="Search_Main" class="list_view condensed_header_view searchmain1">
<!-- search_header -->
<div id="search_main_left_top">
<div id="Selsearch_params"> 
  <form onsubmit="clean_up_and_submit_search_request(); return false;" id="search_form" class="bordernone">
       
       <input id="location" class="location" type="hidden" value="" name="location" autocomplete="off" placeholder="<?php echo translate("Where_do_you_want_to_go");?>">
    <div id="SelSer_Par_Inps">
    	<input type="hidden" id="lat1" name="lat1" value="">
<input type="hidden" id="lng1" name="lng1" value="">
    	<div class="dates_section med_12 pe_12 mal_12 sch_dat">
    		<div class="heading_1 med_3 mal_12">Dates
    			</div>
    			
      <div class="dates_section med_3 mal_4">


        <input id="checkin" value="" class="checkin date active" name="checkin" autocomplete="off"  readonly placeholder="<?php echo translate('Check in'); ?>"/>
      </div>
      <div class="dates_section med_3 mal_4">
        <input id="checkout" value=""  class="checkout date active" name="checkout" autocomplete="off" placeholder="<?php echo translate('Check out'); ?>" readonly/>
      </div>
      <div class="guests_section med_3 mal_4">

      <!--  <input  id="checkin" value="" class="checkin date active" name="checkin" autocomplete="off"  readonly placeholder="Check In"/>
      </div>
     <!-- <div class="dates_section datesect2">
       <input id="checkout" value=""  class="checkout date active" name="checkout" autocomplete="off" placeholder="Check Out" readonly/>
     
     
     </div>-->
     
     
     
    <!-- <div class="dates_section datesect2">
       <input id="checkout" value=""  class="checkout date active" name="checkout" autocomplete="off"  placeholder="Check Out" readonly />
     
     
     </div>-->
     
     
     
     <!-- <div class="guests_section datesect3">-->

        <select id="number_of_guests" name="number_of_guests" value="Guests">
		 
          <option value="1">1  <?php echo translate('Guest'); ?></option>
          <option value="2">2 <?php echo translate('Guests'); ?></option>
          <option value="3">3 <?php echo translate('Guests'); ?></option>
          <option value="4">4 <?php echo translate('Guests'); ?></option>
          <option value="5">5 <?php echo translate('Guests'); ?></option>
          <option value="6">6 <?php echo translate('Guests'); ?></option>
          <option value="7">7 <?php echo translate('Guests'); ?></option>
          <option value="8">8 <?php echo translate('Guests'); ?></option>
          <option value="9">9 <?php echo translate('Guests'); ?></option>
          <option value="10">10 <?php echo translate('Guests'); ?></option>
          <option value="11">11 <?php echo translate('Guests'); ?></option>
          <option value="12">12 <?php echo translate('Guests'); ?></option>
          <option value="13">13 <?php echo translate('Guests'); ?></option>
          <option value="14">14 <?php echo translate('Guests'); ?></option>
          <option value="15">15 <?php echo translate('Guests'); ?></option>
          <option value="16">16+ <?php echo translate('Guests'); ?></option>
        </select>
      </div>
      </div>
    </div>
    
    
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
    <div class="clearfix"></div>
  </form>
</div>
<div class="panel_border"></div>
<!--Filters -->
<ul class="collapsable_filters">
		<li class="search_filter clearfix1" id="instance_book_con">
		<div class="dates_section col-md-12 col-xs-12 col-sm-12">
        <div class="heading_1n col-md-3">                                          
                         <?php echo translate("Booking type"); ?>
                        </div>
		 <div class="clearfix1 col-md-3 col-sm-4 col-xs-12 room_type1 room_type_mob room_ser ">
            <input style="margin:-15px 0px 0px 0px;" type="checkbox" id="instance_book" class="" name="instance_book" >
            			<img style="width:17px;height:30px; margin:-55px 0px 0px 60px;" src="<?php echo base_url() ?>images/svg_5.png">
			<label style="margin:0px 0px 0px -65px;" for="instance_book">&nbsp;&nbsp; <?php echo translate('instant booking'); ?>  </label>
            </div>
	</li>
            <li class="search_filter clearfix1" id="room_type_container">   
            	<div class="dates_section med_12 pe_12 mal_12">
        <div class="heading_1 med_3">                                          
                         <?php echo translate("Room type"); ?>
                        </div>
                        
                       
                  	
                    <!-- Search filter content is below this -->

                  <div class="clearfix1 med_3 mal_4 pe_12 room_type room_type_mob room_ser">
            <input type="checkbox" id="room_type_0" class="room_0" name="room_types" value="Entire home/apt">
			<img src="<?php echo base_url().'images/entire_home.png'; ?>">
			<label for="room_type_0"> <?php echo translate('Entire home/apt'); ?>  </label>
            </div>
					<div class="clearfix1 med_3 mal_4 pe_12 room_type room_type_mob room_type_mob1">
            <input type="checkbox" id="room_type_1" name="room_types" value="Private room">
			<img src="<?php echo base_url().'images/private_room.png'; ?>">
            <label for="room_type_1"><?php echo translate('Private room'); ?></label>
            </div>
                       <div class="clearfix1 med_3 mal_4 pe_12 room_type room_type_mob room_shar">
            <input type="checkbox" id="room_type_2" name="room_types" value="Shared room">
			<img class="sch_shar sch_shar_mob" src="<?php echo base_url().'images/shared_home.png'; ?>">
			
			
			<div class="room_type_anchr">
	        	<img height="15" width="15" src="<?php echo base_url().'images/question_mark_2.png'; ?>" class="anchor">
	        	<p class="message">
	        		<b>Entire Place</b><br>
	        		 Listing where you have the whole place to yourself.<br>
	        		 <b>Private Room</b><br>
	        		 Listings where you have your own room but share some common spaces.<br>
	        		 <b>Shared Room</b><br>
	        		 Listings where you'll share your room or your room may be a common space.
	        	</p>
	        </div>
			
			
			<label for="room_type_2">Shared room</label>
            </div>
              <!-- End of search filter content -->
             </div>
            </li>

       
            

                       

            
            <li id="price_container" class="search_filter clearfix1">
                <div class="dates_section med_12 pe_12 mal_12">
        <div class="heading_1 med_3">                                          
                         <?php echo translate("Price"); ?>
                        </div>
                  	                

                    <div class="search_filter_content med_9">
                        <div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div id="slide1" class="ui-slider-range ui-widget-header searchslider"></div><a href="#" class="ui-slider-handle ui-state-default ui-corner-all leftzero"></a><a href="#" class="ui-slider-handle ui-state-default ui-corner-all fullleft"></a></div>

                  <!--  <div class="search_filter_content">
                        <div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div id="slide1" class="ui-slider-range ui-widget-header searchslider"></div><a href="#" class="ui-slider-handle ui-state-default ui-corner-all leftzero"></a><a href="#" class="ui-slider-handle ui-state-default ui-corner-all fullleft"></a></div>-->

                        <ul id="slider_values">
                            <li id="slider_user_min">$10</li>
                            <li id="slider_user_max">$10000+ </li>
                        </ul>
                    </div>
               </div>
            </li>

            <!--<div class="size_end_form">-->
            	<li id="price_container" class="search_filter size_search clearfix1" style="display: none">
            <div class="dates_section med_12 pe_12 mal_12">
        <div class="heading_1 med_3 mal_12">                                          

            
            <!--	<li id="price_container" class="search_filter size_search" style="display: none">
            <div class="dates_section">
        <div class="heading_1">                                          -->

                         <?php echo translate("Size"); ?>
                        </div>
                  <div class="med_3 mal_4">
                          <select id="min_bedrooms" name="min_bedrooms"><option value=""><?php echo translate("Bedrooms"); ?></option>
						<?php for($i = 1; $i <= 16; $i++) { ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?> </option>
							<?php } ?>
						</select>
  					</div>
  					   <div class="med_3 mal_4">
                        <select id="min_bathrooms" name="min_bathrooms"><option value=""><?php echo translate("Bathrooms"); ?></option>
						<option>0</option>
                        <option>0.5</option>
                        <option>1</option>
                        <option>1.5</option>
                        <option>2</option>
                        <option>2.5</option>
                        <option>3</option>
                        <option>3.5</option>
                        <option>4</option>
                        <option>4.5</option>
                        <option>5</option>
                        <option>5.5</option>
                        <option>6</option>
                        <option>6.5</option>
                        <option>7</option>
                        <option>7.5</option>
                        <option>8+</option>
								</select>
                      </div>
                       <div class="med_3 mal_4">
                          <select  id="min_beds" name="min_beds">
							<option value=""><?php echo translate("Beds"); ?></option>
							<?php for($i = 1; $i <= 16; $i++) { ?>

													<option value="<?php echo $i; ?>"><?php echo $i; if($i == 16) echo '+'; ?> </option>

								<?php } ?>
							</select>
                      </div>
                      </div>
                      </li>
                      

                        <li id="room_type_container" class="search_filter property_type_search clearfix" style="display: none">
            <div class="dates_section med_12 pe_12 mal_12">
        <div class="heading_1 med_3">                                          

                         <?php echo translate("Property Type"); ?>
                        </div>
                        
                        <ul class="search_filter_content" id="lightbox_filter_content_property_type_id">
                    <?php
                   $property = $this->db->limit(3)->from('property_type')->get();
				$i = 1;
					 foreach($property->result() as $value) 
					 {

					   echo '<li class="clearfix1 med_3 mal_6" style="overflow:hidden; white-space:nowrap; text-overflow:ellipsis; ">';

					 //  echo '<li class="clearfix" style="overflow:hidden; white-space:nowrap; text-overflow:ellipsis; ">';

					   if($i == 1)
					   {
					   	 $label_style = 'margin:0px 0px 0px 0px;';
						 $check_style = 'margin-top:0;';
					   }
					    if($i == 2)
					   {
					   	 $label_style = 'margin:0;';
						 $check_style = 'margin:0 0px 0px 0px;';
					   }
					    if($i == 3)
					   {
					   	 $label_style = 'margin:0;';
						 $check_style = 'margin:0 0px 0px 0px;';
					   }
					   $i++;
					  ?>
					<input type="checkbox" style="<?php echo $check_style;?>" value="<?php echo $value->id;?>" name="property_type_id" id="lightbox_property_type_id_<?php echo $value->type;?>">

				<!--	<label for="lightbox_property_type_id_<?php echo $value->type;?>"><?php echo $value->type; ?></label>-->

					
					<label title="<?php echo $value->type; ?>" for="lightbox_property_type_id_<?php echo $value->type;?>"><?php echo $value->type.'<br>'; ?></label>

					<?php 
					  echo '</li>';
					 }
					 ?>
					 </ul> 
					 <img class="down_arrow" onclick="myFunction()" src="<?php echo base_url(); ?>images/dropdown.jpg" />
				<hr class="property_hr" style="display: none;">
				<ul class="search_filter_content med_offset-3" id="lightbox_filter_content_property_type_id" >
					 <?php 
					 $property_count = $this->db->from('property_type')->get()->num_rows();
					$property = $this->db->limit($property_count,3)->from('property_type')->get();
					
					foreach($property->result() as $value) 
					{

					 echo '<li class="clearfix_type med_4 mal_6" style="display:none; ">'; ?>
					<input type="checkbox" value="<?php echo $value->id;?>" name="property_type_id" id="lightbox_property_type_id_<?php echo $value->type;?>">
					<label title="<?php echo $value->type; ?>" for="lightbox_property_type_id_<?php echo $value->type;?>"><?php echo $value->type; ?></label>

					<!--echo '<li class="clearfix_type" style="display:none; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; ">'; ?>
				<!--	<input type="checkbox" value="<?php echo $value->id;?>" name="property_type_id" id="lightbox_property_type_id_<?php echo $value->type;?>">
					<label title="<?php echo $value->type; ?>" for="lightbox_property_type_id_<?php echo $value->type;?>"><?php echo $value->type.'<br>'; ?></label>-->

					<?php } echo '</li>';?>
					
				</ul> 
					 <div style="clear:both"></div>
					 </div>
					 
                      </li>
                      
                       <li id="room_type_container" class="search_filter amenities_search clearfix1" style="display: none">
            <div class="dates_section med_12 pe_12 mal_12">
        <div class="heading_1 med_3">                                          
                         <?php echo translate("Amenities"); ?>
                        </div>
                        
                        <ul class="search_filter_content" id="lightbox_container_amenities" style="padding:0px !important;">
                    <?php
                   $amenities = $this->db->limit(3)->from('amnities')->get();
				$i = 1;
					 foreach($amenities->result() as $value) 
					 {

					   echo '<li class="clearfix1 med_3 mal_6" style="overflow:hidden; white-space:nowrap; text-overflow:ellipsis; ">';

					  // echo '<li class="clearfix" style="overflow:hidden; white-space:nowrap; text-overflow:ellipsis; ">';

					   if($i == 1)
					   {
					   	 $label_style = 'margin:0px 0px 0px 0px;';
						 $check_style = 'margin-top:0;';
					   }
					    if($i == 2)
					   {

					   	 $label_style = 'margin: 5px 0px 0px 0px;';
						 $check_style = 'margin:0 0px 0px 0px;';
					   }
					    if($i == 3)
					   {
					   	 $label_style = 'margin:7px 0px 0px 0px;';
						 $check_style = 'margin:0 0px 0px 0px;';

					  /* 	 $label_style = 'margin: 5px 0px 0px 15px;';
						 $check_style = 'margin:0 0px 0px 17px;';
					   }
					    if($i == 3)
					   {
					   	 $label_style = 'margin:7px 0px 0px 15px;';
						 $check_style = 'margin:0 0px 0px 31px;';*/

					   }
					   $i++;
					  ?>
					<input type="checkbox" style="<?php echo $check_style;?>" value="<?php echo $value->id;?>" name="amenities" id="lightbox_amenity_<?php echo $value->name;?>">

				<!--	<label style="" for="lightbox_amenity_<?php echo $value->name;?>"><?php echo $value->name; ?></label>-->

					<label style="" title="<?php echo $value->name; ?>" for="lightbox_amenity_<?php echo $value->name;?>"><?php echo $value->name.'<br>'; ?></label>

					<?php 
					  echo '</li>';
					 }
					 ?>
					 </ul> 

				<img class="down_arrow" onclick="myFunctionamenities()" src="<?php echo base_url(); ?>images/dropdown.jpg" />
				<ul class="search_filter_content med_offset-3" id="lightbox_container_amenities" >

					<!-- <img class="down_arrow downarrowsearch" onclick="myFunctionamenities()" src="<?php echo base_url(); ?>images/dropdown.jpg" />
				<div class="searchfilter1_ami">
				<ul class="search_filter_content" id="lightbox_container_amenities" >-->

					 <?php 
					 $amnities_count = $this->db->from('amnities')->get()->num_rows();
					$amnities_total = $this->db->limit($amnities_count,3)->from('amnities')->get();
					$i = 0;
					foreach($amnities_total->result() as $value) 
					{
						echo '<li class="clearfix_amenities lightbox_filter_container med_4 mal_6" style="'.$style.'display:none; /*width: 23%; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;*/ ">'; ?>
			<input type="checkbox" value="<?php echo $value->id;?>" name="amenities" id="lightbox_amenity_<?php echo $value->name;?>">
					<label class="label-check" title="<?php echo $value->name; ?>" for="lightbox_amenity_<?php echo $value->name;?>"><?php echo $value->name.'<br>'; ?> </label>
					<?php } echo '</li>';?>

					<!--echo '<li class="clearfix_amenities lightbox_filter_container med_4 mal_6" style="display:none;">'; ?>-->
					<!--<input type="checkbox" value="<?php echo $value->id;?>" name="amenities" id="lightbox_amenity_<?php echo $value->name;?>">
					<label for="lightbox_amenity_<?php echo $value->name;?>"><?php echo $value->name; ?></label>

					<!--	if($i == 0)
						{
							$style = "clear: both;";
						}
						else {
							$style = '';
						}
                     $i++;-->
				</ul> 
				</div>
					 <div style="clear:both"></div>
                      </li>
                      
                      <li id="room_type_container" class="search_filter keywords_search clearfix" style="display: none">
            <div class="dates_section med_12 pe_12 mal_12">
        <div class="heading_1 med_3">                                          
                         <?php echo translate("Keywords"); ?>
                        </div>
                        <ul id="lightbox_container_amenities" class="search_filter_content">

                        	<li class="clearfix1 searchfilter3 ser_key med_6">

                        <!--	<li class="clearfix searchfilter3 ser_key">-->

                    <input type="text" placeholder="Ocean side, transit, relaxing..." id="keywords" />
                    </li>
                    </ul>
                    </div>
					 									
					 <div style="clear:both"></div>
                      </li>

                     <div class="show_listing_div">
                      <!--<a href="javascript:void(0);" id="show_listing" style="display: none">
                      <input type="button" value="Show listing" class="show_listing_show_1" onclick="myfunctionshowlist()" />
                      -->
                      <button class="show_listing_show_1 cursorpoint finish" id="show_listing" style="display: none;" onclick="myfunctionshowlist()">Show Listings</button>
                      </div>

                      <div class="">
                      
                  <!--    <button class="show_listing_show_1 cursorpoint" id="show_listing" style="display: none;" onclick="myfunctionshowlist()">Show Listings</button>-->
                      

                      </div>
                      </div>
<div>
    <li id="more_filters" class="search_filter" style="padding:0px;">    
                     <div id="filters_lightbox Box_head" class="more_filter_tab" style="display: block;">
      <ul id="filters_lightbox_nav" class="sch_filt" style="position: static; margin-top: 0px; top: 0px; width: 100%; margin-left: 0px; z-index: 16;">

          <li class="filters_lightbox_nav1_element" id="lightbox_nav_room_type" >
          	<a href="javascript:void(0);" class="more_filters1" onclick="myfunctionmore()"><?php echo translate("More Filters"); ?></a> 
          	

          	
          	
         	<ul class="collapsable">
          		<li>
          	<input class="more_filters1" type="button" id="roomType"  value="RoomType X">
          		          		
           </li></ul> 
          	<!-- price option -->
          <!--<ul class="collapsable">
          		<li>
          	<input class="more_filters1" type="button" id="roomType1"  value="PriceType X">
          		          		
           </li></ul>
          	
          <!-- property type -->
         	<!--<ul class="collapsable">
          		<li>
          	<input class="more_filters1" type="button" id="roomType2"  value="PropertyType X">
          		          		
           </li></ul> 
          	
          	<ul class="collapsable">
          		<li>
          	<input class="more_filters1" type="button" id="roomType3"  value="Amenities X">
          		          		
           </li></ul> 
           <ul class="collapsable">
          		<li>
          	<input class="more_filters1" type="button" id="roomType4"  value="Size X">
          		          		
           </li></ul> -->
          	
          	
          	

          	<div class="results_count clsFloatLeft morefilter1"></div>
          </li>
      </ul>
      </div>
 </li>
</div>
<!--<div>
    <li id="more_filters" class="search_filter" style="padding:0;">    
                     <div id="filters_lightbox Box_head" class="more_filter_tab">
      <ul id="filters_lightbox_nav" class="sch_filt" style="position: static; margin-top: 0px; top: 0px; width: 100%; margin-left: 0px; z-index: 16;" >

          <li class="filters_lightbox_nav1_element" id="lightbox_nav_room_type" >
          	<a href="javascript:void(0);" class="more_filters1" onclick="myfunctionmore()"><?php echo translate("More Filters"); ?></a>  
          	<div class="results_count clsFloatLeft" style="float: right; padding: 10px; margin-top: 15px;"></div>
          </li>
      </ul>
      </div>
 </li>
</div> -->
										 
            	
     
<!--Filters End-->	
<!-- search_params -->
<div id="standby_action_area" style="display:none;">
  <div> <b><a id="standby_link" href="/messaging/standby" target="_blank">
    <?php echo translate("Do you need a place <i>pronto</i>? Join our Standby list!"); ?>
    </a></b> </div>
</div>
    	<!-- End of Left -->
        <!-- Right -->
        <!-- End of Right 
        	
    <!-- Results header was here initially -->
    <!--  End of results header -->
       <div id="search_body" style=" min-height: 590px; " >
    <div id="results_filters">
        <div id="filters_text"><?php echo translate("Filters:"); ?></div>
        
        <ul id="applied_filters">
        </ul>
      </div>
    <ul id="results" class="med_12">
    	 
      </ul>
      <div id="footer_search" class="footsearch"></div>
 
    <!-- results -->
    <div id="results_footer" class="clearfix">
    	<div class="results_count" id="results_count_html"></div>
        <div id="results_pagination" class="clsFloatRight"></div>
        <span class="country_name">
        <?php
        if(isset($query))
		{
        $address_pieces = explode(',', $query);
		if(count($address_pieces) > 0)
		{
			$address_pieces = array_reverse($address_pieces);
			$i = 1;
			foreach($address_pieces as $row)
			{
				$row = trim($row);
				if(count($address_pieces) != $i)
				{
					echo '<a href="'.base_url().'search?location='.$row.'" > '.$row.'</a> > ';	
					//echo '<a href="'.base_url().'search?location='.$row.'" > '.$row.'</a>>';
				}
				else
				{
					echo $row;
				}
				$i++;
			}
		}
		else {
			echo $query;
		}
		}
        ?>	   
        </span>
      </div>
    <!-- results_footer -->
    <div id="list_view_loading" class="rounded_more" style="display: none;" > <img class="listview1"src="<?php echo base_url(); ?>images/page2_spinner.gif" height="42" width="42" alt="" /> </div>
  </div>
    
<!--End Of search_body -->
<!-- Contents below this is for the search filters -->

</div>
</div>
<!-- v3_search -->

<ul id="blank_state_content" style="display:none;">
  <li id="blank_state">
    <div id="blank_state_text">
          <p>
        <?php echo translate("We couldn’t find any results that matched your criteria, but tweaking your search may help. Here are some ideas:"); ?>
     </p>
      <p>
        <?php echo translate("but tweaking your search may help. Here are some ideas:"); ?>
      <ul>
      	<li class="content1">Remove some filters.</li>
      	<li class="content1">Expand the area of your search.</li>
      	<li class="content1">Search for a city, address, or landmark.</li>
      </ul>
        </p>
    </div>
  </li>
</ul>

							

<style type="text/css">
.ac_results { border-color:#a8a8a8; border-style:solid; border-width:1px 2px 2px; margin-left:1px; }
.clearfix:before, .clearfix:after {
    content: "";
}
</style>
<script type="text/x-jqote-template" id="badge_template">
    <![CDATA[
        <li class="badge badge_type_<*= this.badge_type *>">
            <span class="badge_image">
                <span class="badge_text"><*= this.badge_text *></span>
            </span>
            <span class="badge_name"><*= this.badge_name *></span>
        </li>
    ]]>
</script>
<script type="text/x-jqote-template" id="list_view_item_template">
    <![CDATA[
        <li id="room_<*= this.hosting_id *>" class="search_result med_6 ">
            <div class="pop_image_small">
                <div class="map_number"><*= this.result_number *></div>
                
               <!--Discount label 1 start -->
                           
               
 
  <!--Discount label 1 end -->
                
                
                
                
                <ul class="enlarge"> 
                <a href="<?php echo base_url(); ?>rooms/<*= this.hosting_id *>" class="image_link" title="<*= this.hosting_name *>">
                	<li class="img_wid">
                	<img alt="<*= this.hosting_name *>" class="search_thumbnail searchthumb" src="<*= this.hosting_thumbnail_url *>" title="<*= this.hosting_name *>"/><br />
			
</li>
				</a>
				</ul>
            <div class="price">
                <div class="price_data">
                    <sup class="currency_if_required"><*= CogzidelSearch.currencySymbolRight *></sup>
       
                    <div class='currency_with_sup'><*= this.symbol *><*= this.price *>
                    	<*if(this.instant_book==1) { *>
                  
            <img class="instant" src= "<?php echo base_url() ?>images/svg_5.png">
                <* } *>
                    	
                    </div>
                </div>
                <div class="price_modifier" style="display: none;">
                    Per night
                </div>
                </div>

                

              

                 <* if(this.short_listed == 1) { *>
				<img class="search_heart_hover searchheart_hover1"src="<?php echo base_url() ?>images/heart_rose.png" value="Saved to Wish List" id="my_shortlist"  onclick="add_shortlist(<*= this.hosting_id *>,this);">
				<* } else { *>
				<img class="search_heart_normal searchheart_hover1"src="<?php echo base_url() ?>images/search_heart_hover.png" value="Save To Wish List" id="my_shortlist" onclick="add_shortlist(<*= this.hosting_id *>,this);">
				<* } *>
				
            

            <ul class="reputation reputation_user"> <a class="" href="<?php echo base_url(); ?>users/profile/<*= this.user_id *>"><img alt="<*= this.user_name *>" height="45" src="<*= this.user_thumbnail_url *>" title="<*= this.user_name *>" width="45" class="img_user media-photo media-round" /></a> </ul>
               </div>
                </div>

          <!--  <ul class="reputation reputation_user"> <a class="" href="<?php echo base_url(); ?>users/profile/<*= this.user_id *>"><img alt="<*= this.user_name *>" height="45" src="<*= this.user_thumbnail_url *>" title="<*= this.user_name *>" width="45" class="img_user media-photo media-round" /></a> </ul>-->
               


            <div class="room_details">
                <div class="room_title">
                  <a class="name" title="<*= this.hosting_name *>" href="<?php echo base_url(); ?>rooms/<*= this.hosting_id *>"><*= this.hosting_name *> 
                 
                 <a href="#" id="star_<*= this.hosting_id *>" title="Add this listing as a 'favorite'" class="star_icon_container"><div class="star_icon"></div></a>

                </h6>

                
                 <* if(this.review_count != 0) { *> 
                 	<div class="Sat_Star_Nor" title="">
                  <div class="Sat_Star_Act" style="width:<*= this.review_rating *>%"> </div>
                </div>                                  	
                
                  <* } *>
                 </a>
                  </div> 
                <* if(this.review_count != 0) { *> 
                	<span class="review"><*= this.review_count *></span>  
                	<* } *>
                	
                   <!--wishlist count 1 start-->
                     <!--wishlist count 1 end-->
               
               
               
               

                <* if(this.distance) { *>
                    <p class="address_max_width"><*= this.address *></p>
                    <p class="distance"><*= this.distance *> <*= Translations.distance_away *></p>
                <* } else { *>
                    <p class="address"><*= this.address *></p>
                <* } *>
				
            </div>
            
			<div class="user_thumb">
          </div>
					<table width="76%" cellspacing="0" cellpadding="0" border="0" class="marginzero">
  <tbody><tr>
    <td width="25%" valign="middle" align="right" style="display: none;">
		<div class="count_badge countview">
			<*= this.views*></div>
			<div class="countview1"><?php echo translate('Views');?></div></td>
		
    	
   
    <td width="10%" valign="middle" align="center" style="display: none;">
		<a class="btn green bookit_button buttonsearch" href="#" alt="<*=this.price*>" name="<*= this.hosting_id *>" id="book_it_button" oncontextmenu="return false" style="display: inline-block;"><?php echo translate('Book it');?></a>
	</td>
  </tr>
</tbody>
</table>
        
			<* if (this.connections.length > 0) { *>

			<div class="room-connections-wrapper">
				<span class="room-connections-arrow"></span>
				<div class="room-connections">
					<ul>
						<* for (var k = 0; k < Math.min(this.connections.length, 3); k++) { *>
						<li>
							<img height="28" width="28" alt="" src="<*= this.connections[k].pic_url_small *>" />
							<div class="room-connections-title">
								<div class="room-connections-title-outer">
									<div class="room-connections-title-inner">
										<*= this.connections[k].caption *>
									</div>
								</div>
							</div>
						</li>
						<* } *>
					</ul>
				</div>
			</div>
			<* } *>

        </li>
    ]]>
</script>
<script type="text/x-jqote-template" id="applied_filters_template">
    <![CDATA[
        <li id="applied_filter_<*= this.filter_id *>"><span class="af_text"><*= this.filter_display_name *></span><a class="filter_x_container"><span class="filter_x"></span></a></li>
    ]]>
</script>
<script type="text/x-jqote-template" id="list_view_airtv_template">
    <![CDATA[
        <div id="airtv_promo">
            <img src="/images/page2/v3/airtv_promo_pic.jpg" />
            <h6><*= this.airtv_headline *></h6>
            <h6><*= this.airtv_description *> <b><?php echo translate("Watch Now!");?></b></h6>
        </div>
    ]]>
</script>
</div>


	
	


<script type="text/javascript">

    jQuery(document).ready(function(){
        Cogzidel.Bookmarks.starredIds = [];

        CogzidelSearch.$.bind('finishedrendering', function(){ 
          Cogzidel.Bookmarks.initializeStarIcons(function(e, isStarred){ 
            // hide the listing result from the set of search results when the result is unstarred
            if(!isStarred && CogzidelSearch.isViewingStarred){
              if(CogzidelSearch.currentViewType == 'list')
                $('#room_' + $(e).data('hosting_id')).slideUp(500);
              else if(CogzidelSearch.currentViewType == 'photo')
                $('#room_' + $(e).data('hosting_id')).fadeOut(500);
            }
          }) 
        });

            SearchFilters.amenities.a_11 = ["Smoking Allowed", false];
            SearchFilters.amenities.a_12 = ["Pets Allowed", false];
            SearchFilters.amenities.a_1 = ["TV", false];
            SearchFilters.amenities.a_2 = ["Cable TV", false];
            SearchFilters.amenities.a_3 = ["Internet", false];
            SearchFilters.amenities.a_4 = ["Wireless Internet", false];
            SearchFilters.amenities.a_5 = ["Air Conditioning", false];
            SearchFilters.amenities.a_30 = ["Heating", false];
            SearchFilters.amenities.a_21 = ["Elevator in Building", false];


            SearchFilters.amenities.a_6 = ["Handicap Accessible", false];
            SearchFilters.amenities.a_7 = ["Pool", false];
            SearchFilters.amenities.a_8 = ["Kitchen", false];
            SearchFilters.amenities.a_9 = ["Parking Included", false];
            SearchFilters.amenities.a_13 = ["Washer / Dryer", false];
            SearchFilters.amenities.a_14 = ["Doorman", false];
            SearchFilters.amenities.a_15 = ["Gym", false];
            SearchFilters.amenities.a_25 = ["Hot Tub", false];
            SearchFilters.amenities.a_27 = ["Indoor Fireplace", false];
            SearchFilters.amenities.a_28 = ["Buzzer/Wireless Intercom", false];
            SearchFilters.amenities.a_16 = ["Breakfast", false];
            SearchFilters.amenities.a_31 = ["Family/Kid Friendly", false];
            SearchFilters.amenities.a_32 = ["Suitable for Events", false];

        //CogzidelSearch.currencySymbolLeft = '<?php //echo get_currency_symbol(1); ?>';
        CogzidelSearch.currencySymbolRight = "";
        SearchFilters.minPrice = 10;
        SearchFilters.maxPrice = 10000;
        SearchFilters.minPriceMonthly = 150;
        SearchFilters.maxPriceMonthly = 5000;

        var options = {};

        //Some More Testing needs to be done with this logic - there are still edge cases
        //here, we add ability to hit the back button when the user goes from (page2 saved search)->page3->(browser back button)
        if(CogzidelSearch.searchHasBeenModified()){
            options = {"location":"<?php echo $query; ?>","number_of_guests":"<?php echo $number_of_guests; ?>","action":"ajax_get_results","checkin":"<?php echo $checkin; ?>","guests":"<?php echo $number_of_guests; ?>","checkout":"<?php echo $checkout; ?>","submit_location":"Search","controller":"search"};
        } else {
            options = {"location":"<?php echo $query; ?>","number_of_guests":"<?php echo $number_of_guests; ?>","action":"ajax_get_results","checkin":"<?php echo $checkin; ?>","guests":"<?php echo $number_of_guests; ?>","checkout":"<?php echo $checkout; ?>","submit_location":"Search","controller":"search"};
        }

          CogzidelSearch.isViewingStarred = false;
       

        if(options.search_view) {
            CogzidelSearch.forcedViewType = options.search_view;
        }

        //keep translations first
        Translations.clear_dates = "Clear Dates";
        Translations.entire_place = "Entire Place";
        Translations.friend = "friend";
        Translations.friends = "friends";
        Translations.loading = "Loading";
        Translations.neighborhoods = "Neighborhoods";
        Translations.private_room = "Private Room";
        Translations.review = "review";
        Translations.reviews = "reviews";
        Translations.superhost = "superhost";
        Translations.shared_room = "Shared Room";
        Translations.today = "Today";
        Translations.you_are_here = "You are Here";
        Translations.a_friend = "a friend";
        Translations.distance_away = "away";
        Translations.instant_book = "Instant Book";
        Translations.show_more = "Show More...";
        Translations.learn_more = "Learn More";
        Translations.social_connections = "Social Connections";

        //these are generally for applied filter labels
        Translations.amenities = "Amenities";
        Translations.room_type = "Room Type";
        Translations.price = "Price";
        Translations.keywords = "Keywords";
        Translations.property_type = "Property Type";
        Translations.bedrooms = "Bedrooms";
        Translations.bathrooms = "Bathrooms";
        Translations.beds = "Beds";
        Translations.languages = "Languages";
        Translations.collection = "Collection";

        //zoom in to see more properties message in map view
        Translations.redo_search_in_map_tip = "\"Redo search in map\" must be checked to see new results as you move the map";
        Translations.zoom_in_to_see_more_properties = "Zoom in to see more properties";

        //when map is zoomed in too far
        Translations.your_search_was_too_specific = "Your search was a little too specific.";
        Translations.we_suggest_unchecking_a_couple_filters = "We suggest unchecking a couple filters, zooming out, or searching for a different city.";

        //Tracking Pixel
        //run after localization
        TrackingPixel.params.uuid = "yq0m0k6hjg";
        TrackingPixel.params.user = "";
        TrackingPixel.params.af = "";
        TrackingPixel.params.c = "";
        TrackingPixel.params.pg = '2';

        CogzidelSearch.init(options);

    });
    
    function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
      e.preventDefault();
  e.returnValue = false;  
}

    function wheel(e) {
  preventDefault(e);
}

function disable_scroll() {
  if (window.addEventListener) {
      window.addEventListener('DOMMouseScroll', wheel, false);
  }
  window.onmousewheel = document.onmousewheel = wheel;
}

	jQuery(document).ready(function() {
		Cogzidel.init({userLoggedIn: false});
		//My Wish List Button-Add to My Wish List & Remove from My Wish List
		add_shortlist = function(item_id,that) {
			
			$.ajax({
      				url: "<?php echo site_url('search/login_check'); ?>",
      				async: true,
      				success: function(data) {
      				if(data == "error")
      				window.location.replace("<?php echo base_url(); ?>users/signin?search=1");
      				}
      				});
		
		 $('#header').css({'z-index':'0'});
		 
		 $('body').css({'overflow':'hidden'});
		// disable_scroll();
		var value = $(that).val();
		$.ajax({
  				url: "<?php echo site_url('rooms/get_data'); ?>",
  				type: "POST",
  				dataType: 'json',
  				data: "list_id="+item_id,
  				success: function(data) {
  				//alert(data.images)
  				$('.dynamic-listing-photo').attr('src',data.images);
  				$('.hosting_description').text(data.title);
  				$('.hosting_address').text(data.address);  				
  				}
   				});
		$('#hidden_room_id').val(item_id);
		//$('.modal_save_to_wishlist').show();
		
		$.ajax({
  				url: "<?php echo site_url('rooms/wishlist_popup'); ?>",
  				type: "get",
  				data: "list_id="+item_id,
  				success: function(data) {
  					//alert(data);
  					$('.modal_save_to_wishlist').replaceWith(data);	
  					$('.modal_save_to_wishlist').show();
  				}
   				});
		
		if(value == "Save To Wish List" || value == '')	
		{	
			//$('.modal_save_to_wishlist').show();
   		}
   		else
   		{
   			//$('.modal_save_to_wishlist').show();  			
   		}			
    	};
    	//My Wish List Menu-Check whether the user is login or not 
    	view_shortlist =  function(that){
    			var value = $('#short').val();
    			if(value=="short")
    			{
    				$.ajax({
      				url: "<?php echo site_url('search/login_check'); ?>",
      				async: true,
      				success: function(data) {
      				if(data == "error")
      				window.location.replace("<?php echo base_url(); ?>users/signin");
      				else
      				{
      				$('#search_type_short').attr('id','search_type_photo');
      				$('#short').attr('value', 'photo');
      				$("#search_type_photo").trigger("click");
      				}
      				}
      				});
      			}
    	};	
    			
		});
	</script>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
  <div id="clientsDropDown" class="med_12 mal_12 pe_12 search-main-right">
  <div id="clientsDashboard">
    <div id="clientsCTA" style="bottom:0px;"></div>
  </div>
  
</div>
<input type="hidden" value="" id="hidden_room_id">
<button id="lang"><img src="<?php echo base_url() ?>images/globe-icon.png" />&nbsp;&nbsp;&nbsp;Language and Currency</button>

<div class="modal_save_to_wishlist" style="display: none;">
	
</div>
