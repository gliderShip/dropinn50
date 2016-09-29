<script src="<?php echo base_url(); ?>js/swfobject.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/jwplayer.js" type="text/javascript"></script>
<style>
	.site{
		text-align:center !important; 
		position:absolute; 
		top:150px;
		left:450px;
	}
	.site_title{
		display:block;
		font-family: Circular,"Helvetica Neue",Helvetica,Arial,sans-serif;
		font-size:60px;
		text-transform:uppercase;
		font-weight:700;
		color:#fff;
	}
	.site_slogan{
		display:block;
		font-family: Circular,"Helvetica Neue",Helvetica,Arial,sans-serif;
		color:#fff;
		font-size:20px;
	}
</style>
<?php $this->load->library('Twconnect'); ?>
<!--aircont-->
<div class="app_view">
	<!-- video start-->
	<div class="videoContainer">
<video autoplay loop="loop">
  <source src="<?php echo base_url(); ?>uploads/home/SanFrancisco-P1-1.mp4" type='video/mp4'>
        <source src="<?php echo base_url(); ?>uploads/home/SanFrancisco-P1-0.webm" type='video/webm'>
  Your browser does not support the video tag.
</video>
 <div  class="site"  >
        <div class="overlay site_title"><?php echo $site_title; ?> </div>
       	<div class="overlay site_slogan"><?php echo $site_slogan; ?> </div>
   </div>
</div>
<!-- video end -->


<div class="search-area">
<!--<div id="blob-bg" class="searcharea" style="display: block;">
<!--<img width="600" height="180" src="css/templates/blue/images/search_bg.png" alt="">--
<!--<div class="search-area">-->
<!--<div id="blob-bg" style="display: block; opacity: 0.5;">
<!--<img width="600" height="180" src="css/templates/blue/images/search_bg.png" alt="">

</div>-->
<?php
$result = $this->db->where('status',0)->limit(1)->get('admin_key');
$key = '';
if($result->num_rows() != 0)
{
foreach($result->result() as $row)
{
	
   $key = $row->page_key;
	
}
}
?>
<script>
$(document).ready(function()
{
	$('#search_form').submit(function(){
            if(!$('#location').val() || ('Where do you want to go?' == $('#location').val()))
            {
               var errorEl = $('#enter_location_error_message');
            if(errorEl.css('display') == 'none'){
                $('#enter_location_error_message').show();
                return false;
            }
            else{
               // $("#enter_location_error_message").effect('pulsate', { times:1 }, 300);
                 $('#enter_location_error_message').show();
               //  $('#enter_location_error_message').fadeIn();
                return false;
            }

            return false;
            } 
            else{
                return true;
            }
        });
})
jQuery(document).ready(function() {
		Cogzidel.init({userLoggedIn: false});
		//My Wish List Button-Add to My Wish List & Remove from My Wish List
		add_shortlist = function(item_id,that) {
			$.ajax({
      				url: "<?php echo site_url('search/login_check'); ?>",
      				async: true,
      				success: function(data) {
      				if(data == "error")
      				window.location.replace("<?php echo base_url(); ?>users/signin?home=1");
      				}
      				});
		
		// $('#header').css({'z-index':'0'});
		 
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
  				type: "GET",
  				data: "list_id="+item_id+"&status=home",
  				success: function(data) {
  					$('.modal_save_to_wishlist').replaceWith(data);	
  					setTimeout(function() {
  						$('.modal_save_to_wishlist').show();
  						}, 200);
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
<div class="container search_bg search_pad" >
	<div class="search_col">
<!--<h1><?php echo translate($key); ?></h1>-->
<!--<h2>Rent from people in 38,368 cities and 192 countries.</h2>-->
<form id="search_form" class="custom show-search-options position-left med_12 mal_12 pe_12 " action="<?php echo site_url('search'); ?>" method ="post">
<div class="input-wrapper med_4 mal_4 pe_12 search-lang">
<input style="border-right: none !important;" id="location" class="location main_local" type="text" value="<?php echo translate("Where_do_you_want_to_go"); ?>" name="location" autocomplete="off" onblur="if (this.value == ''){this.value = '<?php echo translate("Where_do_you_want_to_go"); ?>'; }"
   onfocus="if (this.value == '<?php echo translate("Where_do_you_want_to_go"); ?>') {this.value = ''; }" placeholder="<?php echo translate("Where_do_you_want_to_go"); ?>">
<p id="enter_location_error_message" class="bad" style="display:none;">&#10; Please set location&#10; </p>
<input type="hidden" id="lat1" name="lat" value="">
<input type="hidden" id="lng1" name="lng" value="">
</div>

<div id="checkinWrapper" class="input-wrapper med_2 mal_2 pe_12 search-lang">
<input id="checkin" class="checkin search-option ui-datepicker-target" type="text" value="Check in" name="checkin" onblur="if (this.value == ''){this.value = 'Check in'; }"
   onfocus="if (this.value == 'Check in') {this.value = ''; }" readonly>
<span class="search-area-icon"></span>
</div>

<div id="checkoutWrapper" class="input-wrapper med_2 mal_2 pe_12 search-lang">
<input id="checkout" class="checkout search-option ui-datepicker-target" type="text" value="Check out" name="checkout" onblur="if (this.value == ''){this.value = 'Check out'; }"
   onfocus="if (this.value == 'Check out') {this.value = ''; }" readonly>
<span class="search-area-icon search-area-icon-checkout"></span>
</div>
<div class="input-wrapper med_2 mal_2 pe_12 search-lang">
<div class="custom-select-container">
<div id="guests_caption" class="custom dropdown small current dropdown_guest" aria-hidden="true">1 <?php echo translate("Guest"); ?></div>
<div class="custom selector"></div>
<select id="guests" class="search-option small" name="number_of_guests">
<option value="1">1 <?php echo translate("Guest"); ?></option>
<option value="2">2 <?php echo translate("Guests"); ?></option>
<option value="3">3 <?php echo translate("Guests"); ?></option>
<option value="4">4 <?php echo translate("Guests"); ?></option>
<option value="5">5 <?php echo translate("Guests"); ?></option>
<option value="6">6 <?php echo translate("Guests"); ?></option>
<option value="7">7 <?php echo translate("Guests"); ?></option>
<option value="8">8 <?php echo translate("Guests"); ?></option>
<option value="9">9 <?php echo translate("Guests"); ?></option>
<option value="10">10 <?php echo translate("Guests"); ?></option>
<option value="11">11 <?php echo translate("Guests"); ?></option>
<option value="12">12 <?php echo translate("Guests"); ?></option>
<option value="13">13 <?php echo translate("Guests"); ?></option>
<option value="14">14 <?php echo translate("Guests"); ?></option>
<option value="15">15 <?php echo translate("Guests"); ?></option>
<option value="16">16+ <?php echo translate("Guests"); ?></option>
</select>
</div>
</div>
<button id="submit_location" class="blue_home med_2 mal_2 pe_12 search-lang-btn" type="submit" value="Search" name="Submit">
<i class="icon icon-search"></i>
<!--<img src="css/templates/blue/images/search_icon1.png" />-->
<?php echo translate("Search"); ?>&#10;
</button>
</form>
</div>
</div>
 </div>
</div>
<div class="mid_banner_cont">
<div id="mid_cont" class="midpos container-fluid section new_centcont">
	<div class="title-section">
<h1 class="med_12 mal_12 pe_12"> <?php echo translate("Neighborhood Guides") ; ?> </h1>
<p class="med_12 mal_12 pe_12"> <?php echo translate("Not_sure") ; ?> </p>
</div>
<ul class="recent_view clearfix med_12 mal_12 pe_12 padding-zero" style="  text-align: center; color: #eb3c44; font-size: 29px;">
<?php

if(isset($cities))
{
if($cities->num_rows() != 0)
{
foreach($cities->result() as $city)
{
?>
<li class="rec_view1 med_4 mal_6 pe_12">
<div class="newbut">
<!--<img src="css/templates/blue/images/new_but.png" style="opacity:1;"/>-->
<?php 
$city_created = $this->db->where('city_name',$city->city_name)->get('neigh_city')->row()->created;
 $month = 60 * 60 * 24 * 30; // month in seconds
if (time() - $city_created < $month) {
  // within the last 30 days ...
  //echo translate("new");
} 
 ?> 
<!--<span> new </span>-->
</div>
<!--<a href="<?php echo site_url()."rooms/".$row->id; ?>"></a>-->
<a href='<?php echo site_url()."neighbourhoods/city/$city->id"; ?>' class="home">
	<?php $image_name = $this->db->where('city_name',$city->city_name)->from('neigh_city')->get()->row()->image_name; 
	$city_id = $this->db->where('city_name',$city->city_name)->from('neigh_city')->get()->row()->id; 
	?>
<img src="<?php echo base_url().'images/neighbourhoods/'.$city_id.'/home.jpg'; ?>" />
<!--<div class="room_n">
<label class="room_name"><?php echo $city->city_name; ?></label>
<?php
$this->db->distinct()->select('neigh_city_place.place_name')->where('neigh_city_place.is_featured',1)->where('neigh_city_place.city_name',$city->city_name);
$this->db->join('neigh_post', 'neigh_post.place = neigh_city_place.place_name')->where('neigh_post.is_featured',1); 
$this->db->from('neigh_city_place');
$place_ = $this->db->get();
?>
<label class="neigh_count"><?php echo $place_->num_rows().' '.translate('Neighbourhoods'); ?></label>
</div>-->
<label class="shop_hover">
	<p class="Topdestination_city"><?php echo $city->city_name; ?></p>
	<i class="view_dtails fa fa-arrow-circle-right"></i>
</label>
</a>
</li>
<?php } ?>
</ul>
<p class="text-center">
	<a href='<?php echo base_url().'home/neighborhoods'; ?>'><?php echo translate('All neighborhood guides');?></a>
 </p>
 <?php }
else
	{ ?>
		<?php echo translate("No Neighborhoods"); ?>
	<?php }
	} 
else
	{
		echo translate("No Neighborhood Places");
	}?>
    </div></div>
    <div class="med_12 mal_12 pe_12 no_padding">
   
<!-- video start -->
<!-- <video width="100%" height="100%" controls>
  <source src="<?php echo base_url(); ?>uploads/home/Belong_p1_v2.mp4" type='video/mp4'>
        <source src="<?php echo base_url(); ?>uploads/home/Belong_p1_v2.webm" type='video/webm'>
  Your browser does not support the video tag.
</video> -->

<div id="hero" class="search_intro" data-native-currency="USD" style="display: block;">
<div class="callbacks_container">
<ul class="rslides" id="slider5">
<?php foreach($lists->result() as $row) { $url = base_url().'images/'.$row->list_id.'/'.$row->name; 
$profile_pic = $this->Gallery->profilepic($row->user_id, 3); 
$city=explode(',', $row->address);
$shortlist=$this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()));
if($shortlist->num_rows() != 0)
{
	$shortlist = $shortlist->row()->shortlist;
		//Remove the selected list from the All short lists
		$result="";
		$my=explode(',',$shortlist);
		if(in_array($row->list_id, $my))
		{
			$src = 'images/heart_but_pink.png';
			$src = '<a class="heart_but" href="'.base_url()."rooms/remove_my_shortlist/".$row->list_id.'">
<img width="40" height="40" src="'.$src.'" alt="no heart image">
</a>';
		}
		else
			{
			$src = 'images/heart_but.png';	
		    $src = '<a class="heart_but" href="'.base_url()."rooms/add_my_shortlist/".$row->list_id.'">
<img width="40" height="40" src="'.$src.'" alt="no heart image">
</a>';
			}
}
else
{
	$src = 'images/heart_but.png';	
    $src = '<a class="heart_but" href="'.base_url()."rooms/add_my_shortlist/".$row->list_id.'">
<img width="40" height="40" src="'.$src.'" alt="no heart image">
</a>';
}		
?>
<li>
<img src="<?php echo $url; ?>" alt="">

<!--banner image name and wishlist -->
<!--<div class="caption">
	<?php echo $src; ?>
<div class="room_head">
<strong>
<span> <a href="<?php echo base_url().'rooms/'.$row->list_id; ?>"><?php echo $row->title; ?></a> </span>
</strong>
<br>
<!--<a href="<?php echo base_url().'rooms/'.$row->list_id; ?>"><?php echo $city[2]." - ".get_currency_symbol()."".$row->price; ?></a>
<a href="<?php echo base_url().'rooms/'.$row->list_id; ?>"><?php echo $city[2]." - ".get_currency_symbol($row->list_id).get_currency_value1($row->list_id,"".$row->price); ?></a>
</div>
<span class="thumb_img"> 
<img src="<?php echo $profile_pic; ?>" height="40" width="40" style="border-radius: 50%;" />
</span>
</div> -->
</li>
<?php } ?>
</ul>
<a class="callbacks_nav callbacks2_nav prev" href="#">Previous</a>
<a class="callbacks_nav callbacks2_nav next" href="#">Next</a>
</div> </div>



    </div> 
<div id="list_home" class="container">

<div class="travel med_4 mal_4 pe_12">
<h3> <?php echo translate("Travel"); ?> </h3>
<p> <?php echo translate("From_apartments"); ?></p>
<a href="<?php echo site_url('pages/view/travel'); ?>"> <?php echo translate("See most booked"); ?> <span> >> </span> </a>
</div>

<div class="host  med_4 mal_4 pe_12">
<h3> <?php echo translate("Host"); ?>  </h3>
<p>  <?php echo translate("Renting_out"); ?> </p> <br/>
<a href="<?php echo site_url('pages/view/why_host'); ?>"> <?php echo translate("Learn_more"); ?> >> </a>
</div>

<div class="work  med_4 mal_4 pe_12">
<h3> <?php echo translate("How It Works"); ?> </h3>
<p> <?php echo translate("From_our"); ?></p>
<a href="<?php echo site_url('info/how_it_works'); ?>"> <?php echo translate("Visit the trust & safety center"); ?> >> </a>
</div>
</div>
</div>
<!--end air-->
 <script>

    // You can also use "$(window).load(function() {"
    $(function () {
      // Slideshow 4
      $("#slider4").responsiveSlides({
        auto: true,
        pager: false,
        nav: true,
        speed: 500,
        namespace: "callbacks",
        before: function () {
          $('.events').append("<li>before event fired.</li>");
        },
        after: function () {
          $('.events').append("<li>after event fired.</li>");
        }
      });

    });
        $(function () {
      // Slideshow 4
      $("#slider5").responsiveSlides({
        auto: true,
        pager: false,
        nav: true,
        speed: 500,
        namespace: "callbacks",
        before: function () {
          $('.events').append("<li>before event fired.</li>");
        },
        after: function () {
          $('.events').append("<li>after event fired.</li>");
        }
      });

    });
$(document).ready(function(){
preloader();
$("#guests").change(function(){
	var guest=$("#guests").val();
	var temp_guest="";
	if(guest=="1")
	{
		temp_guest=guest+" "+"<?php echo translate("Guest");?>";
	}
	else if(guest=="16")
	{
		temp_guest=guest+"+ "+"<?php echo translate("Guests");?>";
	}
	else
	{
		temp_guest=guest+" "+"<?php echo translate("Guests");?>";
	}
	$("#guests_caption").html(temp_guest);
});
})
// Home Page Checkin Checkout date function below lines jQuery
jQuery(document).ready(function() {
Translations.review = "review";
Translations.reviews = "reviews";
Translations.night = "night";
var opts = {};
CogzidelHomePage.init(opts);
CogzidelHomePage.defaultSearchValue = "Where are you going?";
});
	jQuery(document).ready(function() {
		Cogzidel.init({userLoggedIn: false});
	});

	Cogzidel.FACEBOOK_PERMS = "email,user_birthday,user_likes,user_education_history,user_hometown,user_interests,user_activities,user_location";	

function preloader() 
{
     // counter
     var i = 0;
     // create object
     imageObj = new Image();
     // set image list
     images = new Array();
	 <?php $i = 0; foreach($lists->result() as $row)	{ $url = base_url().'images/'.$row->list_id.'/'.$row->name; ?>
     images[<?php echo $i; ?>]="<?php echo $url; ?>"
	 <?php $i++; } $num_rows = $lists->num_rows(); $total_rows = $num_rows-1; ?>
     // start preloading
     for(i=0; i<=<?php echo $total_rows; ?>; i++) 
     {
          imageObj.src=images[i];
     }
} 

$(document).ready(function(){
$("#view_most").html('<img src="<?php echo base_url()."images/loader.gif"; ?>">');
	$('#location').keypress(function()
	{
      var input = document.getElementById('location');
    var autocomplete = new google.maps.places.Autocomplete(input);    
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();
      $('#lat1').val(lat);
      $('#lng1').val(lng);
	 $('#enter_location_error_message').hide();
    });	
	})
});
</script>
<input type="hidden" value="" id="hidden_room_id">
<div class="modal_save_to_wishlist" style="display: none;">
</div>
<style>
*
{
    box-sizing: border-box;
}
.modal_save_to_wishlist {
    opacity: 1;
}
.modal_save_to_wishlist {
    background-color: rgba(0, 0, 0, 0.75);
   bottom: 0;
    left: 0;
    opacity: 1;
    overflow-y: auto;
    position: fixed;
    right: 0;
    top: 0;
    transition: opacity 0.2s ease 0s;
    z-index: 2000;
}
.modal-table {
    display: table;
    height: 100%;
    table-layout: fixed;
    width: 100%;
}
.modal-cell {
    display: table-cell;
    height: 100%;
    padding: 50px;
    vertical-align: middle;
    width: 100%;
}
.wishlist-modal {
    max-width: 700px;
    overflow: visible;
    width: 700px;
}

.modal-content {
    background-color: #fff;
    border-radius: 2px;
    margin-left: auto;
    margin-right: auto;
    max-width: 700px;
    overflow: hidden;
    position: relative;
}
.panel-dark, .panel-header {
    background-color: #edefed;
}
.panel-header {
    border-bottom: 1px solid #dce0e0;
    color: #565a5c;
    font-size: 16px;
    padding-bottom: 14px;
    padding-top: 14px;
}
.panel-header, .panel-body, ul.panel-body > li, ol.panel-body > li, .panel-footer {
    border-top: 1px solid #dce0e0;
    margin: 0;
    padding: 20px;
    position: relative;
}
.panel-footer {
    text-align: right;
}
.panel-footer {
    border-top: 1px solid #dce0e0;
    margin: 0;
    padding: 20px;
    position: relative;
}
.wishlist-modal .dynamic-listing-photo-container {
    height: 64px;
}
.panel-close, .alert-close, .modal-close {
    color: #cacccd;
    cursor: pointer;
    float: right;
    font-size: 2em;
    font-style: normal;
    font-weight: normal;
    line-height: 0.7;
    vertical-align: middle;
}
.row > .col-1, .row > .col-2, .row > .col-3, .row > .col-4, .row > .col-5, .row > .col-6, .row > .col-7, .row > .col-8, .row > .col-9, .row > .col-10, .row > .col-11, .row > .col-12 {
    padding-left: 12.5px;
    padding-right: 12.5px;
}
.col-2 {
    width: 16.6667%;
}
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
    float: left;
    min-height: 1px;
    position: inherit;
}
.img-responsive-height {
    height: 100%;
    width: auto;
}
.media-cover, .media-cover-dark:after {
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}
.wishlist-modal .dynamic-listing-photo-container {
    height: 64px;
}
.media-photo-block {
    display: block;
}
.row > .col-1, .row > .col-2, .row > .col-3, .row > .col-4, .row > .col-5, .row > .col-6, .row > .col-7, .row > .col-8, .row > .col-9, .row > .col-10, .row > .col-11, .row > .col-12 {
    padding-left: 12.5px;
    padding-right: 12.5px;
}
.col-10 {
    width: 83.3333%;
    text-align: left;
}
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
    float: left;
    min-height: 1px;
    position: inherit;
}
.text-lead {
    font-size: 16px;
}
.row-space-2 {
    margin-bottom: 12.5px;
}
.row {
    margin-left: -12.5px !important;
    margin-right: -12.5px !important
}
#panel-body {
    margin-left: -12.5px !important;
    margin-right: -12.5px !important
}
#panel-body
{
    padding-left: 12.5px;
    padding-right: 12.5px;
}
#panel-body:before, #panel-body:after {
    content: "";
    display: table;
    line-height: 0;
}
#panel-body:after {
    clear: both;
}
.wishlist-modal .selectContainer {
    overflow: inherit;
}
.wishlist-modal .selectContainer {
    border: 1px solid #dce0e0;
}
.select-block {
    display: block;
    width: 100%;
}
.select {
    display: inline-block;
    position: relative;
    vertical-align: bottom;
}
.wishlist-modal #selected {
    display: block;
    height: 43px;
    line-height: 43px;
    margin-left: 20px;
    overflow: hidden;
    width: 252px;
}
.col-12 {
    width: 100%;
}
.noteContainer label {
    display: block;
    padding-bottom: 8px;
    padding-top: 9px;
}
.wishlist-note {
    line-height: inherit;
    padding-bottom: 10px;
    padding-top: 10px;
    resize: vertical;
      display: block;
    padding: 8px 10px;
    width: 100%;
}
.wishlist-modal .selectWidget {
    background-color: white;
    border: 1px solid #dce0e0;
    margin: -1px 0 0 -1px;
    position: absolute;
    width: 100%;
    z-index: 99999;
}
.wishlist-modal .selectList {
    margin: 0;
    max-height: 180px;
    overflow: auto;
    padding: 0;
}
.wishlist-modal .selectList li {
    border-bottom: 1px solid #dce0e0;
}
.wishlist-modal .selectContainer .checkbox.text-truncate {
    white-space: normal;
}
.wishlist-modal .selectList label {
    padding: 10px 15px;
}
.checkbox {
    cursor: pointer;
}
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.wishlist-modal .selectList input {
    display: inline-block;
}
input[type="radio"], input[type="checkbox"] {
    height: 1.25em;
    margin-bottom: -0.25em;
    margin-right: 5px;
    position: relative;
    vertical-align: top;
    width: 1.25em;
}
.wishlist-modal .selectList label span {
    margin-left: 5px;
    width: 245px;
}
.wishlist-modal .newWLContainer {
    border-top: 1px solid #dce0e0;
    padding: 8px;
}
.wishlist-modal .newWLContainer .doneContainer {
    overflow: hidden;
}
.tooltip-bottom-left:before {
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
    position: absolute;
    top: auto;
}
.tooltip-bottom-left:after {
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
    position: absolute;
    top: auto;
}
  .tooltip{
     background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
    left: -2px !important;
    top: -196px;
    max-width: 280px;
    display:none;
    position: absolute;
    transition: opacity 0.2s ease 0s;
    z-index: 3000;
}
#privacy-tooltip-trigger:hover + .tooltip{
    display:block !important;
    z-index:3000;
    float:left;
    display:block;
    margin:190px 0px 0px 250px;
}
.hosting_address {
    margin-bottom: 15px;
}
.wishlist-modal .hide {
    border: 0 none;
    clip: rect(0px, 0px, 0px, 0px);
    height: 1px;
    margin: -1px;
    opacity: 0;
    overflow: hidden;
    padding: 0;
    pointer-events: none;
    position: absolute;
    width: 1px;
}
.wishlist-modal .selectWidget {
    background-color: white;
    border: 1px solid #dce0e0;
    margin: -1px 0 0 -1px;
    position: absolute;
    width: 100%;
    z-index: 99999;
}
.selectList li
{
	padding: 10px;
}
#new_wishlist {
    overflow:hidden;
}
p.Topdestination_city {
    top: 0;
    position: absolute;
    left: 0;
    right: 0;
    color: #fff;
    padding: 15px 0px;
}
</style>
