<?php error_reporting(E_ERROR | E_PARSE); ?>
<!-- Newer date picker required stuff -->
<script src="<?php echo base_url(); ?>js/pops.js" type="text/javascript"></script>
<style>
	#instant > img {
    border: 0 none;
    float: ;
    height: 30px;
    margin: -7px 0px 10px -25px;
    position: absolute;
    vertical-align: middle;
    width: 17px;
   
   
   
}

</style>
<!-- Displayed only to the people who have logged in -->
<?php 
	$set = 0;
	
	if( $this->dx_auth->is_logged_in())
	{
		$userid = $this->dx_auth->get_user_id();
		if( $list->user_id == $userid )
		{
			$set = 1;
		}
	}
?>
<!--  end of the top yellow bit -->
<div class="container">
<div id="rooms" class="container_bg maincontain med_12">  
<?php if($set && $preview != 'preview'): ?>
<div id="new_hosting_actions">
<h2> <?php echo anchor ('rooms/lys_next/edit/'.$room_id,translate("Edit this Listing")); ?> <span class="smaller"> <?php echo translate("Upload photos, change pricing, availability of calendar, edit details"); ?> </span> </h2>
</div>
<?php endif; ?>

  <div id="room">
  <div class="social_links">

<!-- AddThis Button BEGIN -->
<?php
if($images->num_rows() > 0)
								{
foreach ($images->result() as $image)
									{			
									  $url_link = base_url().'images/'.$image->list_id.'/'.$image->name;
									}
								}
else {
	$url_link = base_url().'images/no_image.jpg';
}
	?>
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet" tw:count="none"></a>
<a class="addthis_button_pinterest_pinit" pi:pinit:count="none" pi:pinit:layout="horizontal" pi:pinit:media="<?php echo $url_link; ?>"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<!-- AddThis Button END -->
  </div>
  <div class="detail_drop">
    <div id="the_roof">
      <div id="the_roof_left" class="clsFloatLeft med_9">
          <h1><?php echo $list->title; ?></h1>
          <h3><?php $check = $this->db->where('id' ,$list->property_id)->get('property_type');
		  if($check->num_rows()!=0)
		  {
		  	echo $check->row()->type;
			$fb_share_property_type = $check->row()->type;
		  }
		  else {
			  echo translate('No Property Type');
		  }
		   ?> - <?php echo $list->room_type; ?> <span class="middot">&middot;</span> 
										<span id="display_address" class="no_float"><?php 
										if($list->user_id == $this->dx_auth->get_user_id())
										{
											  $pieces = explode(",",$list->address); $i = count($pieces);
											 if($city!='')
												  {
												  	echo $city.', '.$state.', '.$country;
												  }
												  else {
													  
													  {
													  	echo $state.', '.$country;
													  }
												  }
											
										     //echo $pieces[$i-3].','.$pieces[$i-2].','.$pieces[$i-1];
										     // echo $pieces[$i-4].','.$pieces[$i-3].','.$pieces[$i-2].','.$pieces[$i-1];
										 	 
										     //echo $pieces[$i-6].','.$pieces[$i-4].','.$pieces[$i-3].'.'.$pieces[$i-2].",".$pieces[$i-1];									 
										  
											$fb_share_address = $city.', '.$state.', '.$country;
										}
										else {
											echo $city.', '.$state.', '.$country;
											$fb_share_address = $city.', '.$state.', '.$country;
										}
										 ?></span> </h3>
        </div>

       <div id="the_roof_right" class="clsFloatRight med_3">
											<ul class="clearfix det_view">
											<li>
											<p><span><?php echo $page_viewed; ?></span></p>
											<p><?php echo translate("View"); ?></p>
											</li>
											<li>
											<p><span><?php echo $result->num_rows(); ?></span></p>
											<p><?php echo translate("Reviews"); ?></p>
											</li>
											</ul>
								</div>
						<!--Spam listing 1 start-->
                    

<!--Spam listing 1 end-->						
								
      </div>
      </div>
   <div class="detail_drop">
    <div id="left_column" class="med_8 mal_7 pe_12">
      <div id="Rooms_Slider" class="Box">
      	<div class="Box_Head">
        	<ul id="slider_sub_nav" class="rooms_sub_nav clearfix">
                <li onClick="select_tab('main', 'photos_div', jQuery(this)); initPhotoGallery();" class="main_link selected"><a href="#photos"><?php echo translate("Photos"); ?></a></li>
          
             <!-- Video grabber 1 end -->      
           
              <!-- Video grabber 2 end -->
                
                <li onClick="select_tab('main', 'maps_div', jQuery(this)); load_map_wrapper('load_google_map');" class="main_link"><a href="#maps"><?php echo translate("Maps"); ?></a><a href="#guidebook" style="display:none;"></a></li>
                <?php if($list->street_view != 0) { ?>
                <li onClick="select_tab('main', 'street_view_div', jQuery(this)); load_map_wrapper('load_pano');" class="main_link"><a href="#street-view"><?php echo translate("Street View"); ?></a></li>
                <?php } ?>
                <li onClick="select_tab('main', 'calendar_div', jQuery(this)); load_initial_month(<?php echo date('Y'); ?>);" class="main_link"><a href="#calendar"><?php echo translate("Calendar"); ?></a></li>
               
              </ul>
        </div>
        <div class="Box_Content">   
              <div id="photos_div" class="main_content">
              	<!-- Discount label 1 start -->  	
  
               <!--Discount label 1 end -->
              	
                <?php  
								echo '<div class="galleria_wrapper">';
								if($images->num_rows() > 0)
								{
								$i = 1;
									foreach ($images->result() as $image)
									{			
									 	$name=$image->name;
										$pieces    = explode(".", $name);			
									  $url_link = base_url().'images/'.$image->list_id.'/'.$name;
									  $url_banner =base_url().'images/'.$image->list_id.'/'.$name;
									  // $url_icon = base_url().'images/'.$image->list_id.'/'.$pieces[0].'.jpg';
                                      $url_icon = $url_banner;
											if($i == 1)
											{
												
											echo '<div class="image-placeholder"><img alt="Large" height="100%" src="'.$url_banner.'" width="100%" title="'.$image->highlights.'" /></div>';
											echo '<div id="galleria_container">';											
											}
											echo '<a href="'.$url_banner.'">
																<img height="40" src="'.$url_icon.'" title="'.$image->highlights.'" width="40" /></a>';
												$i++; 				
									}
									echo '</div>';
									
								}
								else
								{
									 $no_image = base_url().'images/no_image.jpg' ;
										echo '<div class="image-placeholder"><img alt="Room_default_no_photos" height="426" src="'.$no_image.' width="639" /></div>
													<div id="galleria_container">
														<img alt="" src="'.$no_image.'" />
											</div>';
								}
								echo '</div>';
								?>
              </div>
              <!-- Video grabber 1 start -->

                     <div id="video_div" class="main_content" style="display:none">
              	<?php //echo $list->video_code;
				if($list->video_code!= ''){
				 $video = explode("=",$list->video_code);
				// print_r($video[1]);
				 $code= $video[1];
				 
				 ?>
              	 <iframe width="640" id="video" height="507" src="//www.youtube.com/embed/<?php echo $code;?>" frameborder="0" allowfullscreen></iframe>
              	 <?php }else{
              	 	echo "No Video Found";
              	 }?>
         </div>
         
          <!-- Video grabber 1 end -->
              
              <div id="maps_div" class="main_content" style="display:none;">
                <div id="map" data-lat="<?php echo $list->lat; ?>" data-lng="<?php echo $list->long; ?>"> </div>
                <ul id="guidebook-recommendations" style="display: none;">
                </ul>
              </div>
														
													<div id="street_view_div" class="main_content" style="display:none;">
															<div id="pano_error" style="display:none;">
														<p>
															<?php echo translate("Unable to find street view of this location."); ?>
														</p>
														</div>
											
															<div id="pano_no_error">
																	<div data-lat="<?php if($list->street_view == 2) echo round($list->lat, 6); else echo $list->lat; ?>" data-lng="<?php if($list->street_view == 2) echo round($list->long, 6); else echo $list->long; ?>" id="pano"></div>
																	<div class="floatright">
																			<input checked="checked" id="auto_pan_pano" name="auto_pan_pano" type="checkbox" value="true" /> <?php echo translate("Rotate Street View"); ?>
																	</div>
															
															</div>
													</div>
														
              <div id="calendar_div" class="main_content" style="display:none;">
                <div id="calendar_tab_container" >
                  <div id="calendar_tab">
                      <div id="calendar2">
                        <div class="clearfix">
                          <div class="Edit_Cal_Top_left clsFloatLeft"> <?php echo translate("Select Month :");?>
                            <select id="cal_month" name="cal_month" onChange="change_month2(this.options[this.selectedIndex].title);">
                              <?php for ($x=0; $x < 12; $x++) {
																$time = strtotime('+' . $x . ' months', strtotime(date('Y-M' . '-01')));
																$key  = date('m', $time);
																$name = date('F', $time);
																$year = date('Y', $time);
																echo '<option title="'.$year.'" value="'.$key.'">'.$name.' '.$year.'</option>';
       														 }
															 ?>
                            </select>
                            <img id="calendar_loading_spinner" class="apt_calendar" style="display:none;" src="<?php echo base_url(); ?>images/spinner.gif" />
                          </div>
                          <div class="Edit_Cal_Top_Right clsFloatRight">
                          	<div id="legend">
                          		<div>
                          <div class="available key">&nbsp;</div>
                          <div class="key-text"> <?php echo translate("Available"); ?> </div>
                          </div>
                          <div>
                          <div class="unavailable key">&nbsp;</div>
                          <div class="key-text"> <?php echo translate("Unavailable"); ?> </div>
                          </div>
                          <div>
                          <div class="in_the_past key">&nbsp;</div>
                          <div class="key-text"> <?php echo translate("Past"); ?> </div>
                          </div>
                          <div class="clear"></div>
                        </div>
                          </div>
                          <div class="clear"></div>
                        </div>
                        <div id="calendar_tab_variable_content"></div>
                        
                      </div>
                    <p> <?php echo translate("The calendar is updated every five minutes and is only an approximation of availability. We suggest that you contact the host to confirm.");?> </p>
                    <div class="clear"></div>
                  </div>
                </div>
              </div>
              <div class="clear"></div>
         </div>
      </div>
      <div id="Rooms_Details" class="Box">
      	<div class="Box_Head">
              <ul id="details_sub_nav" class="rooms_sub_nav">
                <li onClick="select_tab('details', 'description', jQuery(this));" class="details_link selected" id="description_link"><a href="javascript:void(0);"> <?php echo translate("Description"); ?> </a></li>
                <li onClick="select_tab('details', 'amenities', jQuery(this));" class="details_link"><a href="javascript:void(0);" id="amenities_link"> <?php echo translate("Amenities"); ?> </a></li>
                <li onClick="select_tab('details', 'house_rules', jQuery(this));" class="details_link clsBg_None"><a href="javascript:void(0);" id="amenities_link"> <?php echo translate("House_Rules"); ?> </a></li>
              </ul>
          </div>
          <div class="Box_Content"> 
              <div id="description" class="details_content clearfix">
                <div id="description_text" class="med_6 pe_12">
                  <div id="new_translate_button_wrapper" style="display: none;">
                    <div id="new_translate_button"> <span class="label"> <?php echo translate("Translate this description to English");?> </span> </div>
                  </div>
                  <div id="description_text_wrapper" class="trans">
                    <p><?php //echo str_replace('^nl;^','<br />',$list->desc); 
                    	echo nl2br($list->desc);
                    	?></p>
                  </div>
                  
                </div>
                
                      <div id="description_details" class="med_6 pe_12 padding-zero">
      	
          <ul>
                      <li class="clearfix bg"><span class="property"> <?php echo translate("Room type:"); ?> </span><span class="value"><?php echo $list->room_type; ?></span></li>
                      <li class="clearfix "><span class="property">                     	
                      	 <?php echo translate("Bed type:"); ?> </span>
                      	 <span class="value">  <?php if($list->bed_type == '') echo translate("Not Available"); else echo $list->bed_type; ?> </span></li>
                      <li class="clearfix bg"><span class="property"> <?php echo translate("Accommodates:"); ?> </span><span class="value"><?php echo $list->capacity; ?></span></li>
                      <?php
                      if($list->bedrooms!=0)
					  { 
                      echo '<li class="clearfix"><span class="property">'.translate("Bedrooms:").'</span><span class="value">'.$list->bedrooms.'</span></li>';
                      }
                      if($list->beds!=0)
					  { 
                      echo '<li class="clearfix"><span class="property">'.translate("Beds:").'</span><span class="value">'.$list->beds.'</span></li>';
                      }
					  if($list->bathrooms!=0)
					  { 
                      echo '<li class="clearfix"><span class="property">'.translate("Bathrooms:").'</span><span class="value">'.$list->bathrooms.'</span></li>';
                      }
                      ?>
                    <!-- <li class="clearfix"><span class="property"> <?php echo translate("Beds:"); ?> </span><span class="value"><?php echo $list->beds; ?></span></li>-->
                     <?php
                     if($prices->guests < $list->capacity)
					 {
                     ?>
                      <li class="clearfix bg"><span class="property"> <?php echo translate("Extra people:"); ?> </span><span class="value" id="extra_people_price">
                      <?php if($prices->addguests == 0) echo "No Charge"; else echo get_currency_symbol($room_id).get_currency_value1($room_id,$prices->addguests).'/guest after '.$prices->guests; ?>
                        </span></li>
                        <?php } ?>
                      <li class="clearfix"><span class="property"> <?php echo translate("Cleaning Fee:"); ?> </span><span class="value"> <?php echo get_currency_symbol($room_id).get_currency_value1($room_id,$prices->cleaning); ?></span> </li>
                      <li class="clearfix"><span class="property"> <?php echo translate("Security Fee:"); ?> </span><span class="value"> <?php echo get_currency_symbol($room_id).get_currency_value1($room_id,$prices->security); ?></span> </li>
                      <li class="clearfix bg"><span class="property"> <?php echo translate("Weekly Price:"); ?> </span> <span class="value">
                        <?php if($prices->week == 0) echo translate("Not Available"); else echo get_currency_symbol($room_id).get_currency_value1($room_id,$prices->week); ?>
                        </span> </li>
                      <li class="clearfix"><span class="property"> <?php echo translate("Monthly Price:"); ?> </span> <span class="value">
                        <?php if($prices->week == 0) echo translate("Not Available"); else echo get_currency_symbol($room_id).get_currency_value1($room_id,$prices->month); ?>
                        </span> </li>
                      <?php 
                       $pieces = explode(",",$list->address); $i = count($pieces);
                      if(trim($pieces[$i-1]) != 'France' and $i != 1 and $i != 2) { ?>
                      <li class="clearfix bg"><span class="property"> <?php echo translate("City:"); ?> </span> <span class="value">
                        <?php $pieces = explode(",",$list->address); $i = count($pieces); echo $pieces[$i-3]; ?>
                        </span> </li>
                      <li class="clearfix"><span class="property"> <?php echo translate("State:"); ?> </span> <span class="value">
                        <?php $pieces = explode(",",$list->address); $i = count($pieces); echo $pieces[$i-2]; ?>
                        </span> </li>
                      <?php } ?>
																						<?php if($i != 1) { ?>
                      <li class="clearfix bg"><span class="property"> <?php echo translate("Country:"); ?> </span> <span class="value">
                        <?php $pieces = explode(",",$list->address); $i = count($pieces); echo $pieces[$i-1]; ?>
                        </span> </li>
																						<?php } else { ?>
                      <li class="clearfix"><span class="property"> <?php echo translate("Address:"); ?> </span> <span class="value">
                        <?php $pieces = explode(",",$list->address); $i = count($pieces); echo $pieces[$i-1]; ?>
                        </span> </li>
																					<?php } ?>
                      <li class="clearfix round_bottom"><span class="property"> <?php echo translate("Cancellation:"); ?> </span><span class="value"> 
                      	<?php if($policy !='') { ?>
                      	<a target="_blank" href="<?php echo site_url('pages/cancellation_policy/'.$policy.''); ?>">
                      	<?php echo $policy;?> </a>
                      	 <?php } else { echo translate("Not Available"); } ?>
                      	</span></li>
                    </ul>
      </div>
                
              </div>
              <div id="amenities" style="display:none" class="details_content">
                <?php 
                $in_arr = explode(',', $list->amenities);
                $tCount = $amnities->num_rows();
                $i = 1; $j = 1; 
                foreach($amnities->result() as $rows) { if($i == 1) echo '<ul>'; ?>
                <li>
                  <?php if(in_array($rows->id, $in_arr)) { ?>
                  <img class="amenity-icon" src="<?php echo base_url(); ?>images/has_amenity.png" height="17" width="17" alt="Has amenity / Allowed" title="Has amenity / Allowed" />
                  <?php } else { ?>
                  <img class="amenity-icon" src="<?php echo base_url(); ?>images/no_amenity.png" height="17" width="17" alt="Doesn't have amenity / Not allowed" title="Doesn't have amenity / Not allowed" />
                  <?php } ?>
                  <p><?php echo $rows->name; ?> <a class="tooltip" title="<?php echo $rows->description; ?>"><img alt="Questionmark_hover" src="<?php echo base_url(); ?>images/questionmark_hover.png" class="apt_aminities"/></a></p>
                </li>
                <?php if($i == 8) { $i = 0; echo '</ul>'; } else if($j == $tCount) { echo '</ul>'; } $i++; $j++; } ?>
                <div class="clear"></div>
              </div>
              <div id="house_rules" style="display:none" class="details_content">
                <?php if($list->house_rule == '') { ?>
                <div id="house_rules_text">
                  <p> <?php echo translate("This host has not specified any house rules."); ?> </p>
                </div>
                <?php } else { ?>
                <div id="house_rules_text">
                  <p><?php echo $list->house_rule; ?></p>
                </div>
                <?php } ?>
              </div>
        </div>
      </div>

      <div class="Box" id="reputation">
      <div class="Box_Head" id="reputations">
      	       <ul id="reputation_sub_nav" class="rooms_sub_nav">
        	<li onClick="select_tab('reputation', 'reviews', jQuery(this));" class="reputation_link selected" id="reviews_link"><a href="javascript:void(0);"> <?php echo translate("Reviews").'('.$result->num_rows().')'; ?> </a></li>
            <li onClick="select_tab('reputation', 'comments', jQuery(this));" class="reputation_link"> <a href="javascript:void(0);" id="comments_link">
            	<?php echo translate("Comments"); ?></a></li>
           <li onClick="select_tab('reputation', 'friends', jQuery(this));" class="reputation_link"> <a href="javascript:void(0);" id="friends_link"> 	
           <?php echo translate("Friends"); ?>
            </a></li>
           
               </ul></div>
 
             <div id='friends' class="reputation_content" style="display: none">
             	<div class="Box_Content" id='Box_Content'> 
             
            <!-- Status Bottom Blk -->
            <div class="Sta_Bttm_Blk"  >
              <ul>
              <?php
             $CI = &get_instance();
             $friends_id = $CI->fb_friends_id($room_id);
             if($friends_id)
			 { 
             foreach($friends_id as $fb_id)
			 {
			 	$this->load->helper('string');
			 	$frnds_id = reduce_multiples($fb_id, ",",TRUE);
			 //	echo $frnds_id;
              ?>
                <li class="clearfix">
                  <div class="Sta_Rat_Prof clsFloatLeft apt_profile"> 
                  	
					<a href="<?php echo site_url('users/profile').'/'.$frnds_id; ?>">
					<img height="82" width="76" src="<?php echo $this->Gallery->profilepic($frnds_id, 2); ?>" alt="Profile" /> 
					</a>
                    <center><span class="apt_username"><?php echo ucfirst(get_user_by_id($frnds_id)->username); ?></span>
                  </center>
                  <?php } ?>
                  </div>
                  <div class="clearfix"></div>
                </li>
                <?php }
				 else { echo translate("No friends found");
				?>
		  <div class="reputation_content">
		  	     <?php echo translate("No friends found"); ?> </li></div>
                <?php 
               
				 } ?>
              </ul></div>
              <div class="clearfix"></div>
            </div>
                    </div>
        <div id="comments" class="reputation_content" style="display: none">
       <div id="fb-root">  
<script>
 window.fbAsyncInit = function() {
    FB.init({
      appId      : '<?php echo $fb_app_id; ?>', // App ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

    // Additional initialization code here
  };
  (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId="+<?php echo $fb_app_id; ?>;
  fjs.parentNode.insertBefore(js, fjs);
  document.getElementById(id).innerHTML='';
    parser=document.getElementById(id);
    //parser.innerHTML='<div style="padding-left:5px; min-height:500px" class="fb-comments" data-href="'+newUrl+'" data-num-posts="20" data-width="380"></div>';
    FB.XFBML.parse(parser);
    }(document, 'script', 'facebook-jssdk'));
</script></div>  
  <style>
 .fb-comments, .fb-comments span, .fb-comments iframe[style]
{
    width: 100% !important;
    min-height:200px !important;
}
 </style>                  
<div class="fb-comments" data-href=<?php echo base_url().'rooms/'.$room_id; ?> data-width="470" data-num-posts="10"></div>
</div>

										<!-- Top Rating Blk -->
										<div id='reviews' class="reputation_content">
            <?php
												 if($result->num_rows() > 0) 
													{     
															$accuracy      = (($stars->accuracy *2) * 10) / $result->num_rows();
															$cleanliness   = (($stars->cleanliness *2) * 10) / $result->num_rows();
															$communication = (($stars->communication *2) * 10) / $result->num_rows();
															$checkin       = (($stars->checkin *2) * 10) / $result->num_rows();
															$location      = (($stars->location *2) * 10) / $result->num_rows();
															$value         = (($stars->value *2) * 10) / $result->num_rows();
															$overall       = ($accuracy + $cleanliness + $communication + $checkin + $location + $value) / 6;
                                                    
             ?>
            <div id="Sati_Top_Blk" class="clearfix med_12 mal_12 sol-12">
              <div class="Sat_Top_Left clsFloatLeft med_3 mal_12 pe_12">
                <p><?php echo translate("Overall Guest Satisfaction"); ?></p>
                <div class="Sat_Star_Nor" title="<?php echo $overall; ?>%">
                  <div class="Sat_Star_Act" style="width:<?php echo $overall; ?>%;"> </div>
                </div>
              </div>
              <div class="Sat_Top_Right clsFloatRight med_9 med_offset-3 mal_12 pe_12 padding-zero">
                <!-- First ul start -->
                <ul class="Sat_List_1 clsFloatLeft">
                  <li class="clearfix">
                    <div class="Sat_Attribute"><?php echo translate("Accuracy"); ?></div>
                    <div class="Sat_Star_Nor_1" title="<?php echo $accuracy; ?>%">
                      <div class="Sat_Star_Act_1" style="width:<?php echo $accuracy; ?>%;"> </div>
                    </div>
                  </li>
                  <li class="clearfix">
                    <div class="Sat_Attribute"><?php echo translate("Cleanliness"); ?></div>
                    <div class="Sat_Star_Nor_1" title="<?php echo $cleanliness; ?>%">
                      <div class="Sat_Star_Act_1" style="width:<?php echo $cleanliness; ?>%;"> </div>
                    </div>
                  </li>
                  <li class="clearfix">
                    <div class="Sat_Attribute"><?php echo translate("Checkin"); ?></div>
                    <div class="Sat_Star_Nor_1" title="<?php echo $checkin; ?>%">
                      <div class="Sat_Star_Act_1" style="width:<?php echo $checkin; ?>%;"> </div>
                    </div>
                  </li>
                </ul>
                <!-- End of ul -->
                <!-- Second ul start -->
                <ul class="Sat_List_2 clsFloatLeft">
                  <li class="clearfix">
                    <div class="Sat_Attribute"><?php echo translate("Communication"); ?></div>
                    <div class="Sat_Star_Nor_2" title="<?php echo $communication; ?>%">
                      <div class="Sat_Star_Act_2" style="width:<?php echo $communication; ?>%;"> </div>
                    </div>
                  </li>
                  <li class="clearfix">
                    <div class="Sat_Attribute"><?php echo translate("Location"); ?></div>
                    <div class="Sat_Star_Nor_2" title="<?php echo $location; ?>%">
                      <div class="Sat_Star_Act_2" style="width:<?php echo $location; ?>%;"> </div>
                    </div>
                  </li>
                  <li class="clearfix">
                    <div class="Sat_Attribute"><?php echo translate("Value"); ?></div>
                    <div class="Sat_Star_Nor_2" title="<?php echo $value; ?>%">
                      <div class="Sat_Star_Act_2" style="width:<?php echo $value; ?>%;"> </div>
                    </div>
                  </li>
                </ul>
                <!-- End of ul -->
              </div>
              <div class="clearfix"></div>
            </div>
            <?php } ?>
            <!-- End of Top Rating Blk -->
          <div class="Box_Content" id="Box_Content"> 
           
            
            <!-- Status Bottom Blk -->
            <div class="Sta_Bttm_Blk med_12 padding-zero">
              <ul>
                <?php 
                if($result->num_rows() > 0) { 
                foreach($result->result() as $row) { 
              ?>
                <li class="clearfix">
                  <div class="Sta_Rat_Prof clsFloatLeft apt_profile med_3 mal_4 pe_12 padding-zero"> 
					<a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>">
					<img height="82" width="76" src="<?php echo $this->Gallery->profilepic($row->userby, 2); ?>" alt="Profile" /> 
					</a>
                    <left><span class="apt_username1"><?php echo ucfirst(get_user_by_id($row->userby)->username); ?></span>
                  </left></div>
                  <div class="Sta_Rat_Msg clsFloatRight med_9 mal_8 pe_12">
                    <p><?php echo $row->review; ?></p>

                    <p class="apt_review"><?php echo get_user_times($row->created, get_user_timezoneL($row->userby)); ?></p>

                    <span class="StaMsg_LeftArrow"></span> </div>
                  <div class="clearfix"></div>
                </li>
                <?php } }
				 else { echo translate("No reviews found.");
				?>
		  <div class="reputation_content">
		  	     <?php //echo translate("No reviews found."); ?> </li></div>
                <?php 
               
				 } ?>
              </ul></div>
              <div class="clearfix"></div>
            </div>
            </div>
            <!-- End of Status Bottom Blk -->
        		  
      </div>
      <!-- Reputation division was once here -->
      <!-- End of reputation division -->
      <script type="text/javascript">
  jQuery('#reputation .pagination a').live('click', function() {
    var $this = jQuery(this);
    $this.parent().append('<img src="<?php echo base_url(); ?>images/spinner.gif" class="spinner" height="16" width="16" alt="" />'); 

    jQuery.ajax({
      url: $this.attr('href'),
      success: function(data) {
        $this.closest(".rep_content").html(data);
        jQuery('html, body').animate({scrollTop: jQuery('#reputation').offset().top}, 'slow');
      }
    });

    return false;
  });
      select_tab('rep', 'this_hosting_reviews', jQuery('#this_hosting_reviews_link'));
</script>
    </div>
    </div>
      <?php
				$listid=$this->uri->segment(2);
			 $instance_book = $this->db->where('id',$listid)->get('list')->row()->instance_book;
				 
				 ?>
   <script type="text/javascript">
   
 var checkout;
   ;(function($) {
        $(function() {
			$('#my-button').bind('click', function(e) {
				var instance_book1='<?php echo $instance_book; ?>';
			if(instance_book1 != 1)
				{
					clearInterval(checkout);
<?php if($this->dx_auth->get_user_id() != ""){?>
$("#status").css("display","block");
$("#status_contact_login").css("display","none");
$("#status_availablity").css("display","none");
$("#status_already").css("display","none");
$("#status_your_list").css("display","none");
$("#status_contact").css("display","none");
$("#sendmessage").attr("disabled", false);
<?php } else {?>
$('#status').hide();
$('#status_contact_login').css("display","inline");				
<?php } ?>
				
	                	e.preventDefault();
		$('#element_to_pop_up').bPopup({
			closeClass:'close',
			fadeSpeed: 'slow', //can be a string ('slow'/'fast') or int
			followSpeed: 1500, //can be a string ('slow'/'fast') or int
			modalColor: 'black',
			contentContainer:'.content',
			
			 zIndex: 1,
			 modalClose: true
});  }
                
        else 
       { 
       	alert("Since it is an Instant booking list you can't contact host.");
       	
       }           

	 });
         });
     })(jQuery);		
   </script>
        
<style>
.per_night_drop
{
	margin-bottom: 4%;
	margin-left: 4%;
}
#main_content .container_bg{
	width:100%;

.container_bg
{
width: 980px !important;

}
</style>
    <div id="right_column" class="med_4 mal_5 pe_12">
      <div id="book_it">

            <div id="pricing" class="book_it_section med_12">
              <p class="med_5 pe_5"><label class="med_6"><?php echo translate("From"); ?></label>&nbsp;
              <label id="price_amount" class="price_left med_6"><?php echo get_currency_symbol($room_id).get_currency_value1($room_id,$list->price); ?></label>
                
              </p>
              <p class="med_6 pe_6"> 
            <select name="payment_period" id="payment_period">

                <option value="per_night"><?php echo translate("Per Night") ; ?> </option>
                <option value="per_week"><?php echo translate("Per Week") ; ?>   </option>
                <option value="per_month"><?php echo translate("Per Month") ; ?></option>
              </select>
              </p>
              <div id="includesFees" class="disblock"> 
                <p class="med_6 pe_6"><?php echo translate("Includes all fees"); ?> <a title="This is the final price, including any fees from the host and <?php echo $this->dx_auth->get_site_title(); ?>." class="tooltip"><img class="icon1" src="<?php echo base_url(); ?>images/questionmark_hover.png" alt="Questionmark_hover"></a></p></div>
            </div>
            <?php echo form_open('payments/index/'.$room_id, array('class' => "info room_form med_12", 'id' => "book_it_form" ,'name' => "book_it_form")); ?>
            <div id="dates" class="book_it_section">
              <input id="hosting_id" name="hosting_id" type="hidden" value="<?php echo $room_id; ?>" />
              
              <div class="book_head med_12" style="padding:0px;">
           <div class="check_label">   <label  for="checkin"><?php echo translate("Check_in"); ?></label>
              <input class="checkin" id="checkin" name="checkin" type="text" readonly="readonly"/></div>
              <div class="check_label"> <label for="checkout"><?php echo translate("Check_out"); ?></label>
              <input class="checkout" id="checkout" name="checkout" type="text" readonly="readonly"/></div>
             <div class="check_label">  <label for="number_of_guests"><?php echo translate("Guests"); ?></label>
               <select id="number_of_guests1" class="recomm-select" name="number_of_guests" onChange="refresh_subtotal();">
                  		<?php for($i = 1; $i <= 16; $i++) { ?>
													       	<option value="<?php echo $i; ?>"><?php echo $i; if($i == 16) echo '+'; ?> </option>
														       <?php } ?>
                </select></div>
              </div>          
    <!--      
              <div class="book_head">
              <label class="apt_checkin" for="checkin"><?php echo translate("Check_in"); ?></label>
              <label class="apt_checkin" for="checkout"><?php echo translate("Check_out"); ?></label>
              <label for="apt_checkin"><?php echo translate("Guests"); ?></label>
              </div>
              
              <div class="book_head1">
              <input class="checkin" id="checkin" name="checkin" type="text" readonly="readonly"/>
              <input class="checkout" id="checkout" name="checkout" type="text" readonly="readonly"/>
               <select id="number_of_guests1" class="no_guest" name="number_of_guests" onChange="refresh_subtotal();">
                  		<?php for($i = 1; $i <= 16; $i++) { ?>
													       	<option value="<?php echo $i; ?>"><?php echo $i; if($i == 16) echo '+'; ?> </option>
														       <?php } ?>
                </select>
              </div>
      -->  
            
            </div>
            <div class="book_it_section round_bottom" id="book_it_status">
              <div id="book_it_enabled" class="clearfix" style="display: none;">
                <div id="subtotal_area" class="clsFloatLeft">
                  <p class="det_par" style="display: none;"><?php echo translate("Subtotal"); ?></p>
                  <h2 class="det_pri" id="subtotal"><img width="16" height="16" alt="" src="<?php echo base_url(); ?>images/spinner.gif"></h2>
                </div>
               <!-- <div id="selBook_Now" class="clsFloatRight"><button id="book_it_button" type="button" class="button" name="commit" ><span><span><?php echo translate("Book Now"); ?></span></span></button></div>-->
               <button id="book_it_button" class="btn large btn_dash bokbtn" type="submit" oncontextmenu="return false">
<?php
 $instance_book = $this->db->where('id',$listid)->get('list')->row()->instance_book;
 if($instance_book==1){
?>
<span id="instant"><img src="<?php echo base_url() ?>images/svg_5.png"></span>
<span class="book-it"> <?php echo translate("Instant Booking"); ?>!</span>
<?php }else {?>
	<span class="book-it"> <?php echo translate("Book it"); ?>!</span>
	<?php } ?>
</button>
              </div>
              <div class="clearfix"></div>
              <div style="display: none;" id="book_it_disabled">
                <p class="bad gust_alt" id="book_it_disabled_message"><?php echo translate("Those dates are not available"); ?></p>
                <p class="apt_submit"><a href="<?php echo base_url(); ?>search" onClick="clean_up_and_submit_search_request(); return false;" id="view_other_listings_button" class="clsLink2_Bg"> <?php echo translate("View Other Listings"); ?> </a> </p>
              </div>
               <div style="display: none;" id="show_more_subtotal_info1">
              <?php if($guest_price != 0) { ?>
                <?php echo translate("Includes"); ?> <span class="value" id="extra_fee_string"><?php echo get_currency_symbol($room_id).get_currency_value1($room_id,$guest_price); ?></span> <?php echo translate("Additional guest fee"); ?> <br />
                <?php } ?>
                </div>
              <div style="display: none;" id="show_more_subtotal_info">
                <?php if($cleaning != 0) { ?>
                <?php echo translate("Includes"); ?> <span class="value" id="cleaning_fee_string"><?php echo get_currency_symbol($room_id).get_currency_value1($room_id,$cleaning); ?></span> <?php echo translate("Cleaning fee"); ?> <br />
                <?php } ?>
                <?php if($security != 0) { ?>
                <?php echo translate("Includes"); ?> <span class="value" id="security_fee_string"><?php echo get_currency_symbol($room_id).get_currency_value1($room_id,$security); ?></span> <?php echo translate("Security fee"); ?> <br />
                <?php } ?>
                <?php echo translate("Excludes").' '.$this->dx_auth->get_site_title().' '.translate("service fee"); ?> (<span id="service_fee">$<?php echo $commission ?></span>) </div>
            </div>
            <?php echo form_close(); ?> 
      </div>
      <!-- wishlist -->
      <div class="save_wishlist">
      <div class="savewish_but">
      <?php 
      	$short_listed=0;
		$count_wishlist=0;
		$cur_user_id=$this->dx_auth->get_user_id();
		if($cur_user_id)
		{
			$shortlist=$this->Common_model->getTableData('user_wishlist',array('user_id' => $cur_user_id,'list_id'=>$this->uri->segment(2)));
			if($shortlist->num_rows() != 0)
			{
				$short_listed = 1;
			}
			$count_wishlist = $this->Common_model->getTableData('user_wishlist',array('list_id'=>$this->uri->segment(2)))->num_rows();
		}
		  
	 // if(!$this->session->userdata('logged_in')) { ?>

	 <!--<a href="<?php echo base_url(); ?>users/signin" style="text-decoration:none;"><input class="save_wish" oncontextmenu="return false" type="button" value="<?php echo translate("Save To Wish List"); ?>" id="my_shortlist"></a>-->
	<?php // }
//else
if($short_listed == 0)
	   {  ?>	                 

	   <input class="save_wish detail med_10 pe_10" type="button" oncontextmenu="return false" value="<?php echo translate("Save To Wish List"); ?>" id="my_shortlist" onclick="add_shortlist(<?php echo $room_id; ?>,<?php echo $count_wishlist; ?>,this);"><!-- SAVE TO WISH LIST -->
	  <?php } 
	  else { ?>	 
<input class="accept_button_save_wish save_wish detail med_10 pe_10" type="button" oncontextmenu="return false" value="<?php echo translate("Saved To Wish List"); ?>" id="my_shortlist" onclick="add_shortlist(<?php echo $room_id; ?>,<?php echo $count_wishlist; ?>,this);"><!-- Remove from Wishlist-->

	 <!--  <input class="save_wish savelist" type="button" oncontextmenu="return false" value="<?php echo translate("Save To Wish List"); ?>" id="my_shortlist" onclick="add_shortlist(<?php echo $room_id; ?>,<?php echo $count_wishlist; ?>,this);"><!-- SAVE TO WISH LIST -->
	  <?php } 
	 // else { ?>	 
<!--<input class="accept_button_save_wish savedlist" type="button" oncontextmenu="return false" value="<?php echo translate("Saved To Wish List"); ?>" id="my_shortlist" onclick="add_shortlist(<?php echo $room_id; ?>,<?php echo $count_wishlist; ?>,this);"><!-- Remove from Wishlist-->

	  <?php //}  	?>
	  
	    <!-- wishlist count 1 start-->
	  
	   
	  
	 <!-- wishlist count 1 end -->
	 
	  </div>
	   <div class="saved_count">
	  	<div class="save_detail">
Saved
<span class="count" id="counter"><?php echo $count_wishlist; ?></span>
times
</div>
</div></div>
	  	 
	  
      <!-- wishlist -->      
      <div id="Room_User" class="Box1 pe_12">
          <div id="user_info_big" class="disblock">
            
            <?php $profiles = $this->Common_model->getTableData('profiles', array('id' => $list->user_id ))->row(); ?>
            <?php $user = $this->Common_model->getTableData('users',array( "id" => $list->user_id ))->row(); 
            $user_id = $this->db->where('id',$room_id)->from('list')->get()->row()->user_id;
            ?>
            <img id="trigger_id" width="230" height="236" alt="" src="<?php    
		  	 echo $this->Gallery->profilepic($user_id,2);
            ?>" title=""/>
            <h2>
              <a class="user_name" href="<?php echo site_url('users/profile').'/'.$user->id; ?>"><?php echo $user->username; ?></a>
            </h2>
              <div id="element_to_pop_up" style="display:none">
              	<div class="pop_element">
              <div id="status">
                <?php echo form_open('payments/index/'.$room_id, array('class' => "info", 'id' => "book_it_form" ,'name' => "book_it_form")); ?>
             <div id="dates" class="book_it_section" >
              <input id="hosting_id" name="hosting_id" type="hidden" value="<?php echo $room_id; ?>" />
                <h2><?php echo translate("Send_Message_to"); ?> <?php echo $user->username; ?>

                	           <a class="close" href="#"><img src="<?php echo base_url(); ?>images/fancy_close.png" alt="close" width="45" height="45" /> </a>

                </h2>
                 <div class="book_head label_text med_12" style="padding:0px;">
                <li class="check_list_label med_4 mal_4 pe_12">
                    <label class="med_12 pe_12 mal_12"   for="checkindatelabel"><?php echo translate("Check in"); ?></label>
                    <input class="checkin ui-datepicker-target apt_checkin1 check_pop med_12 pe_12 mal_12" id="checkindate" name="checkin" type="text" size="10" value="mm/dd/yy" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" autocomplete=off readonly="readonly" />
                </li>
              <li class="check_list_label med_4 mal_4 pe_12">
                <label class="med_12 pe_12 mal_12"   for="checkoutdatelabel"><?php echo translate("Check out"); ?></label>
                <input class="checkout ui-datepicker-target apt_checkin1 check_pop med_12 pe_12 mal_12" id="checkoutdate" class="chec_pop_select" name="checkout" type="text" size="10" value="mm/dd/yy" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" autocomplete=off readonly="readonly"  />
              </li>
              <li class="check_list_label med_4 mal_4 pe_12">
                <label class="med_12 pe_12 mal_12"   for="number_of_guests"><?php echo translate("Guests"); ?></label>
                <select class="apt_guest med_12 pe_12 mal_12" id="number_of_guest2" name="number_of_guest2" onChange="refresh_subtotal();">

                	       <!--     <a class="close" href="#"><img src="<?php echo base_url(); ?>images/fancy_close.png" alt="close" width="45" height="45" /> </a>

                </h2>
                <p>
                    <label for="checkindatelabel"><?php echo translate("Check in"); ?></label>
                    <input class="checkin ui-datepicker-target apt_checkin1" id="checkindate" name="checkin" type="text" size="10" value="mm/dd/yy" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" autocomplete=off readonly="readonly" />
                </p>
              <p>
                <label for="checkoutdatelabel"><?php echo translate("Check out"); ?></label>
                <input class="checkout ui-datepicker-target apt_checkin1" id="checkoutdate" name="checkout" type="text" size="10" value="mm/dd/yy" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" autocomplete=off readonly="readonly"  />
              </p>
              <p>
                <label for="number_of_guests"><?php echo translate("Guests"); ?></label>
                <select class="apt_guest" id="number_of_guest2" name="number_of_guest2" onChange="refresh_subtotal();">-->

                  		<?php for($i = 1; $i <= 16; $i++) { ?>
													       	<option value="<?php echo $i; ?>"><?php echo $i; if($i == 16) echo '+'; ?> </option>
														       <?php } ?>
                </select>
              </li>
              </ul>
              </div>
<div class="messagearea med_12 pe_12">
              
<?php /*?>                 <label for="checkout"><?php echo translate("Message"); ?></label><?php */?>
			<p class="apt_message"><?php echo translate("Tell"); ?> 
				
				<?php echo $user->username; ?>
				<?php echo translate("what you like about their place, what matters most about your accommodations, or ask them a question"); ?>.</p>	
              <p class="med_12 pe_12">  <textarea class="message_popup" id="message" name="message"   ></textarea>
              </p>
            <?php /*?>  <p class="reuse"><input type="checkbox" id="check" name="reuse" />Reuse this message next time I contact a host </p><?php */?>
              </div>
<p><div class="border"></div></p>
              <div class="send"> 
                 <button id="sendmessage" type="button" class="btn blue gotomsg btn_dash">
                <span>
                 <span><?php echo translate("Send Message"); ?></span>
                </span>
                </button>
                </div>
            </div>
           <?php echo form_close(); ?> 
            </div>
            <div id="status_contact_login" style="display:none">
           <h2>Sign up to send your message</h2>
		   <div>
		   	<br>                 
            <a href="<?php echo base_url(); ?>users/signin"><h3>Already an member?</h3></a>
            </div>

            <br>
            <p><center><b>OR</b></center><br></p>
            <div class="createaccount">
            <center><a href="<?php echo base_url(); ?>users/signup"><h3><?php echo translate("Create an account with your mail address"); ?></h3></a></center>
            </div>
            <!--<div class="terms">
            <p><center>
            	<?php echo translate("By clicking Connect with Facebook you confirm that you accept the"); ?> <a href="<?php echo site_url('pages/view/terms'); ?>">Terms of Service.</a></center> 
            </p>
            </div> -->
             </div>
             <div id="status_availablity" style="display:none">
             <h2><?php echo translate("Sorry Accomodataion Not available."); ?></h2>
             <div class="dont">
             
             </div>
             </div>
             <div id="status_already" style="display:none">
             <h2><?php echo translate("Sorry You're already contact this List for the same dates."); ?></h2>
             <div class="dont">
             
             </div>
             </div>
             <div id="status_your_list" style="display:none">
             <h2><?php echo translate("You can't contact your own List."); ?></h2>
             <div class="dont">
             
             </div>
             </div>
             <div id="status_contact" style="display:none">
             <h2><img src="<?php echo base_url(); ?>images/has_amenity.png" alt="close" width="22" height="22"/>&nbsp;&nbsp;<?php echo translate("Message Sent"); ?> </h2>
             <div class="dont">
             <h4><?php echo translate("Don't stop now-keep contacting other listings."); ?></h4>
             <p><?php echo translate("Contacting several places considerably improves your odds of a booking."); ?></p>
             <p><a href="<?php echo base_url(); ?>search"><?php echo translate("Return to your search"); ?></a></p>
             </div>
             </div>
             

            <!--<a class="close" href="#"><img src="<?php echo base_url(); ?>images/fancy_close.png" alt="close" width="45" height="45" /> </a>-->
             </div>

             

          </div>
              
                <button id="my-button" type="button" class="btn_dash btn-block large blue det_cont" >
                <span>
                <span><?php echo translate("Contact Me"); ?></span>
                </span>
                </button>
                <!--<button id="user_contact_link" class="btn btn-block large blue" type="submit">&#10; Contact Me&#10; </button>-->
                
												 <p class="apt_showmore"><a id="show_more_user_info1" href="javascript:void(0);"> <span id="more_info_text"><?php echo translate("Show More"); ?> </span> <span id="less_info_text" style="display:none;"><?php echo translate("Show Less"); ?></span> <span id="more_info_arrow" class="expand-arrow"></span> </a></p>
            <ul id="more_info1" style="display:none">
              <li><span class="property"> <?php echo translate("First Name"); ?></span><em>:</em>
                <span><p><?php if(isset($profiles->Fname)) echo $profiles->Fname; ?></p></span>
              </li>
              <li><span class="property"> <?php echo translate("Last Name"); ?></span><em>:</em>
                <span><p><?php if(isset($profiles->Lname)) echo $profiles->Lname; ?></p></span>
              </li>
              <!--<li><span class="property"> <?php echo translate("Living in"); ?> </span><em>:</em>
                <span><p><?php if(isset($profiles->live)) echo $profiles->live; ?></p></span>
              </li>-->
              <li><span class="property"> <?php echo translate("Working in"); ?> </span><em>:</em>
                <span><p><?php if(isset($profiles->work)) echo $profiles->work; ?></p></span>
              </li>
              <li><span class="property"> <?php echo translate("About Me"); ?> </span><em>:</em>
                <span><p><?php if(isset($profiles->describe)) echo $profiles->describe; ?></p></span>
              </li>
            </ul>
            <div class="clear"></div>
      </div>
          <div class="clear"></div>
      </div>

      <div class="related_listings Box pe_12" id="my_other_listings" style="padding:0px;">
        	<div class="Box_Head">
              <h2 class="similar"> <?php echo translate("Similar Listings"); ?> </h2>
            </div>
            <div class="related_listings_content">
            <!-- This section deals with the other listings by the same user -->
            
           <!-- <?php $ans = $this->db->get_where('list',array("user_id" => $list->user_id, "id !=" => $room_id, "status =" => 1,"is_enable" => 1)); ?> -->
          <?php $ans = $this->db->get_where('list',array("id !=" => $room_id, "status =" => 1, "is_enable ="=>1)); ?> 
           <?php 
           $count = 0;
          if($ans->num_rows > 0){
                  foreach($ans->result() as $a ){
					  $CI = &get_instance();
					  $distance  = $CI->getDistanceBetweenPointsNew($lat,$long,$a->lat,$a->long);
					 //echo $distance.'-'.$a->id;exit;
					  if($distance <= 15 )
					  {
					  	$count++;
					  }
				  }
				  }
					  	?>
            <h4><?php if($count != 0) {  echo $count.' '.translate("Listings"); } else { echo translate("N/A");  }?></h4>
            <ul>
            <?php 
            if($ans->num_rows > 0): 
                  foreach($ans->result() as $a ):
					  $CI = &get_instance();
					  $distance  = $CI->getDistanceBetweenPointsNew($lat,$long,$a->lat,$a->long);
					 //echo $distance.'-'.$a->id;exit;
					  if($distance <= 15 )
					  {
					 $url = getListImage($a->id); 
						echo '<li>
					<div class="related_listing_left pe_4 mal_4 med_4">
					<a href='.base_url().'rooms/'.$a->id.' id="related_listing_photo"><img alt="no image" height="56" src="'.$url.'" title="no image" width="71" />
					</a>
					</div>';				
		
					echo '<div class="related_listing_right pe_8 mal_8 med_8">';
					echo '<div class="distance">'.$distance." Miles".'</div>';
					echo anchor('rooms/'.$a->id , $a->title); 
					echo '<div class="subtitle">'.get_currency_symbol($a->id).get_currency_value1($room_id,$a->price).'/night <br />'.$a->room_type.'</div>
									</div>';
					echo '<div class="clear"></div>
					</li>';
					  }
           endforeach; 
           endif;
											?>
            </ul>
          </div>
          <!-- /related_listings_content -->
          <div class="clear"></div>
      </div>
    </div>
    <!-- /right_column -->
    <div id="lwlb_overlay"></div>
    <div id="lwlb_needs_to_message" class="lwlb_lightbox2" style="display:none;">
      <div class="header">
        <div class="h1">
          <h1> <?php echo translate("Please confirm availability"); ?> </h1>
        </div>
        <div class="close"><a href="#" onClick="lwlb_hide_and_reset('lwlb_needs_to_message');return false;"><img src="/images/lightboxes/close_button.gif" /></a></div>
        <div class="clear"></div>
      </div>
      <br/>
      <br/>
      <p> <?php echo translate("This host requires that you confirm availability before making a reservation.  Please send a message to the host and wait for a response before booking.");?> </p>
      <br/>
      <br/>
      <p><span class='v3_button v3_blue' onClick="jQuery('#lwlb_needs_to_message').hide();jQuery('#user_contact_link').click();"> <?php echo translate("Contact Host"); ?> </span></p>
    </div>
    <div id="lwlb_contact_container"></div>
    <!-- set up a dummy link that we click later with js -->
    <a style="display:none;" id="fb_share_dummy_link" name="fb_share" type="icon_link" href="http://www.facebook.com/sharer.php"> <?php echo translate("Share"); ?> </a>
    <div class="clear"></div>
  </div>
  <!-- /rooms -->
		 
</div>
</div>
<script type="text/javascript">

	$(document).ready(function(){
		
	$('select option:contains("Per Night")').prop('selected',true);
		
	$("#book_it_button").click(function(){
			if($("#checkin").val() && $("#checkin").val() == 'mm/dd/yy')
            {
			alert('Please choose the dates');
            return false;
            }
			else
            {
			$('#book_it_form').submit();
            }
	})
	
	})

	if (!window.Cogzidel) {Cogzidel = {};}
	Cogzidel.tweetHashTags = "#Travel";


		(function() {
  		var initOptions = {
  			userLoggedIn: true,
  			showRealNameFlow: false,
  			locale: "en"
  		};

  		if (jQuery.cookie("_name")) {
  			initOptions.userLoggedIn = true;
  		}

  		Cogzidel.init(initOptions);
		})();
  
  jQuery(document).ready(function() {
		Cogzidel.init({userLoggedIn: false});
		//My Wish List Button-Add to My Wish List & Remove from My Wish List
		add_shortlist = function(item_id,count_wishlist,that) {
			$.ajax({
      				url: "<?php echo site_url('search/login_check'); ?>",
      				async: true,
      				success: function(data) {
      				if(data == "error")
      				window.location.replace("<?php echo base_url(); ?>users/signin?rooms=1&id="+item_id);
      				}
      				});
		
		// $('#header').css({'z-index':'0'});
		
		 $('body').css({'overflow':'hidden'});
		// disable_scroll();
		//var value = $(that).val();
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
  				data: "list_id="+item_id+"&status=rooms&id="+item_id,
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
  		
	$(function() {
       var date = new Date();
var currentMonth = date.getMonth();
var currentDate = date.getDate();
var currentYear = date.getFullYear();
	   $( "#checkoutdate" ).datepicker({
                minDate: 0,
                maxDate: "+2Y",
                nextText: "",
                prevText: "",
                numberOfMonths: 1,
                // closeText: "Clear Dates",
                currentText: Translations.today,
                showButtonPanel: true
	    });
	    $( "#checkindate" ).datepicker({
			minDate: date,
                maxDate: "+2Y",
                nextText: "",
                prevText: "",
                numberOfMonths: 1,
                currentText: Translations.today,
                showButtonPanel: true,
	 onClose: function(dateText, inst) { 
          d = $('#checkindate').datepicker('getDate');
		  d.setDate(d.getDate()+1); // add int nights to int date
		$("#checkoutdate").datepicker("option", "minDate", d);
		setTimeout(function () {
                                    $("#checkoutdate").datepicker("show")
                                }, 0)
     }
	   });
       
    });
  $(document).ready(function() {
$('#sendmessage').live("click", function(){	
	 			$("#sendmessage").attr("disabled", true);
    				$.ajax({
      				url: "<?php echo site_url('search/login_check'); ?>",
      				async: true,
      				success: function(data) {
      				if(data == "error")
      				window.location.replace("<?php echo base_url(); ?>users/signin");
      				
      				}
      				});
      			

		var checkin = $("#checkindate").val();
		var checkout = $("#checkoutdate").val();
		var room_id = $("#hosting_id").val();
		var guests = $('#number_of_guest2 :selected').text();
		var message = $("#message").val();
		if($.trim(message) == $.trim("Add a Recommend") || $.trim(message) == "" || checkin == "mm/dd/yy" || checkout == "mm/dd/yy" || checkout == "mm/dd/yy") { 	
			alert('Please enter all valid informations. Like checkin or checkout or Message ');
			$("#sendmessage").attr("disabled", false);
		}
		else {
		var postdata = 'checkin='+checkin+'&checkout='+checkout+'&id='+room_id+'&message='+message+'&guests='+guests;
				
               if(/\b(\w)+\@(\w)+\.(\w)+\b/g.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed');
            	$("#sendmessage").attr("disabled", false);exit;
            }
            else if(message.match('@') || message.match('hotmail') || message.match('gmail') || message.match('yahoo'))
				{
					alert('Sorry! Email or Phone number is not allowed');
					$("#sendmessage").attr("disabled", false);exit;
				}
         	if(/\+?[0-9() -]{7,18}/.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed');
            	$("#sendmessage").attr("disabled", false);exit;
            }
           
		$.ajax({
            //this is the php file that processes the data and send mail
            url: "<?php echo base_url()?>payments/contact",             
            //GET method is used
            type: "POST",
            //pass the data        
            data: postdata,             
            //Do not cache the page
            cache: false,             
            //success
			dataType: "json",
            success: function (result) {  
            	//alert(result.status);return false;
            if(result.status == 'redirect')
            {
            	window.location.reload();
            }	
			else if(result.status == "error") {
						     $('#status').hide();
				$('#status_contact_login').css("display","inline");				
			}
			else if(result.status == "not_available")
			{
				$('#status').hide();
				$('#status_availablity').css("display","inline");
				location.reload();
			}
			else if(result.status == "your_list")
			{
				$('#status').hide();
				$('#status_your_list').css("display","inline");
				location.reload();
			}
			else if(result.status == "already")
			{
				$('#status').hide();
				$('#status_already').css("display","inline");
				location.reload();
			}
			else
			{ 
			
			$('#status').hide();
				$('#status_contact').css("display","inline");
				$("#message").val('');
				//alert("else");
			location.reload(); 
			}
			}	
		});
		}
	});
});
window.onload = initPhotoGallery; 
<?php if($images->num_rows() > 0) { ?>
function preloader() 
{
     // counter
     var i = 0;
     // create object
     imageObj = new Image();
     // set image list
     images = new Array();
					<?php $i = 0; foreach($images->result() as $image)	{  $url = base_url().'images/'.$image->list_id.'/'.$image->name; ?>
     images[<?php echo $i; ?>]="<?php echo $url; ?>"
					<?php $i++; } $num_rows = $images->num_rows(); $total_rows = $num_rows-1; ?>
     // start preloading
     for(i=0; i<=<?php echo $total_rows; ?>; i++) 
     {
          imageObj.src=images[i];
     }
} 
<?php } ?>
</script>
<!-- Scripts required for this page -->
<script type="text/javascript">
			 var needs_to_message = true;
    var ajax_already_messaged_url = "";
    var ajax_lwlb_contact_url = "<?php echo site_url('rooms/ajax_contact').'/'.$room_id; ?>";

    function action_email() {
            lwlb_show('lwlb_email');
    }

        function redo_search(opts) {
        opts = (opts === undefined ? {} : opts);

        opts.useAddressAsLocation = (opts.useAddressAsLocation === undefined ? true : opts.useAddressAsLocation);

        var urlParts = [base_url+"search?"];

        if(opts.useAddressAsLocation === true){
            //need to make this backwards compatible with cached versions
            var locationParam = '';

            if(jQuery('#display_address')){
                locationParam += jQuery('#display_address').data('location');
            } else if(jQuery('.current_crumb .locality')){ //we can remove this else if block after Oct 12, 2010 -Chris
                locationParam += jQuery('.current_crumb .locality').html();
                if(jQuery('.current_crumb .region')){
                    locationParam += ', ';
                    locationParam += jQuery('.current_crumb .region').html();
                }
            }

            if(locationParam && locationParam != 'null' && locationParam != ''){
                urlParts = urlParts.concat(["location=", locationParam, '&sort_by=2&']);
            }
        }

        var checkinValue = jQuery('#checkin').val();
        var checkoutValue = jQuery('#checkout').val();

        if(checkinValue !== 'mm/dd/yyyy' && checkoutValue !== 'mm/dd/yyyy'){
            urlParts = urlParts.concat(["checkin=", checkinValue, "&checkout=", checkoutValue, '&']);
        }

        urlParts = urlParts.concat(["number_of_guests=", jQuery('#number_of_guests').val()]);

        url = urlParts.join('');

        window.location = url;

        return true;
    }

	function change_month2(cal_year) {
var d = new Date();
var gmtHours = -d.getTimezoneOffset()/60;
var timezone_offset = String(gmtHours);
        var $spin = jQuery('#calendar_loading_spinner').show();

        // dim out the current calendar
        var $table = jQuery('#calendar_table')
          .css('opacity', .5)
          .css('filter', 'alpha(enabled=true)');
        
        // now load the calendar content
        jQuery('#calendar_tab_variable_content').load("<?php echo site_url('rooms/calendar_tab_inner').'/'.$room_id; ?>", 
          {cal_month: jQuery('#cal_month').val(), cal_year: cal_year,offset: timezone_offset},
          function(response) {
            $table.css('opacity', 1.0)
              .css('filter', 'alpha(enabled=false)');
            $spin.hide();
        });
	}

  var initial_month_loaded = false;
		
	function load_initial_month(cal_year) {

var d = new Date();
var gmtHours = -d.getTimezoneOffset()/60;
var timezone_offset = String(gmtHours);
	  var $spin;
    if (initial_month_loaded === false) {
      var $spin = jQuery('#calendar_loading_spinner').show();
      jQuery('#calendar_tab_variable_content').load("<?php echo site_url('rooms/calendar_tab_inner').'/'.$room_id; ?>",
        {cal_month: jQuery('#cal_month').val(), cal_year: cal_year,offset: timezone_offset},
        function() {
          $spin.hide();
          initial_month_loaded = true;
        }
      );
    }
  }

  var Translations = {
    translate_button: {
      
      show_original_description : 'Show original description',
      translate_this_description : 'Translate this description to English'
    },
    per_month: "per month",
    long_term: "Long Term Policy",
    clear_dates: "Clear Dates"
  }
		
		function preloaderUser() 
		{
		heavyImage = new Image(); 
		heavyImage.src = "<?php echo $this->Gallery->profilepic($list->user_id); ?>";
		}

    /* after pageload */
    jQuery(document).ready(function() {
        // initialize star state
        Cogzidel.Bookmarks.starredIds = [1,2];
        Cogzidel.Bookmarks.initializeStarIcons();

								<?php if($images->num_rows() > 0) { ?>
        preloader();
								<?php } ?>
								preloaderUser();
        //Code for back to page2
          var backToSearchHtml = ['<a rel="nofollow" onclick="if(redo_search({useAddressAsLocation : true})){return false;}" href="/search" id="back_to_search_link">', "View Nearby Properties", '</a>'].join('');

        jQuery('#back_to_search_container').append(backToSearchHtml);

        /* target specifically a#view_other_listings_button so no conflict with input#view_other_listings_button in cached pages */
        if(jQuery('a#view_other_listings_button')){
            jQuery('a#view_other_listings_button').attr('href', jQuery('#back_to_search_link').attr('href'));
        }
        /* end code for back to page2 */

        $('#new_hosting_actions a').click(function(e) {
          ajax_log('signup_funnel', 'click_new_hosting_action');
        });
        // init the flag widget handler too
        jQuery('.flag-container').flagWidget();

        CogzidelRooms.init({inIsrael: false, 
                          hostingId: <?php echo $room_id; ?>,
                          videoProfile: false,
                          isMonthly: false,
                          nameLocked: false,
						  staggeredPrice: "<?php echo get_currency_symbol($room_id).get_currency_value1($room_id,$Mprice); ?>",
                          nightlyPrice: "<?php echo get_currency_symbol($room_id).get_currency_value1($room_id,$price); ?>",
                          weeklyPrice: "<?php echo  get_currency_symbol($room_id).get_currency_value1($room_id,$Wprice); ?>",
                          monthlyPrice: "<?php echo get_currency_symbol($room_id).get_currency_value1($room_id,$Mprice); ?>"});

        page3Slideshow.enableKeypressListener();
								
							<?php if($this->session->userdata('Vcheckin') != '') { ?>
							jQuery("#checkin").val('<?php echo $this->session->userdata('Vcheckin'); ?>');
							<?php }  ?>
							<?php if($this->session->userdata('Vcheckout') != '') { ?>
							jQuery("#checkout").val('<?php echo $this->session->userdata('Vcheckout'); ?>');
							<?php } ?>

							<?php if($this->session->userdata('Vnumber_of_guests') != '') { ?>
							jQuery("#number_of_guests").val('<?php echo $this->session->userdata('Vnumber_of_guests'); ?>');
							<?php } else { ?>
							jQuery("#number_of_guests").val('1');
							<?php } ?>


        refresh_subtotal();

        jQuery('#extra_people_pricing').html("No Charge");

        add_data_to_cookie('viewed_page3_ids', <?php echo $room_id; ?>);
        				
        jQuery("#similar_listings").load("<?php echo base_url(); ?>rooms/similar_listings/<?php echo $room_id; ?>?checkin=<?php echo $this->session->userdata('Vcheckin'); ?>&checkout=<?php echo $this->session->userdata('Vcheckout'); ?>&guests=<?php echo $this->session->userdata('Vnumber_of_guests'); ?>");
								
								<?php if($this->dx_auth->is_logged_in()) { ?>
								
								jQuery('#message_submit').click(function(){
								
								 var message = jQuery('#message_body').val();
									if(message == "")
									{
											alert("Please type something to host");
											return false;
									}
									else
									{
									jQuery.ajax({ type: "POST",url: ajax_lwlb_contact_url,async: true,data: "room_id="+<?php echo $room_id; ?>+"&message="+message, success: function(data)
									{	
											if(data!=0)
											{
											 jQuery("#comment_success").show();
												jQuery("#comment_success").html(data);
												jQuery("#comment_success").delay(1700).fadeOut('slow');
											}
											else
											{
												alert("Error");
											}
									}
								});
							}
						});		
						
					<?php } ?>			
    });

			</script>
			<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-525c2c194313da57"></script>
<input type="hidden" value="" id="hidden_room_id">
<div class="modal_save_to_wishlist" style="display: none;">
</div>

<!-- End of this scripts section -->
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
    text-align:left;
}
.popup_title {
font-weight: bold;
font-size: 19px;
text-align: left !important;
color: #393C3D;
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
.addnote{
font-weight: bold;
font-size: 14px;
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
.wishlist-modal .selectContainer:hover{
	border: 1px solid #00b0ff;
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
    /*background-color: white;
    border: 1px solid #dce0e0;
    margin: -1px 0 0 -1px;*/
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
    /*border-top: 1px solid #dce0e0;*/
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
    left: 14px;
    position: absolute;
    top: auto;
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
    left: 0;
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
.selectList li
{
	padding: 10px;
}
#new_wishlist {
    overflow:hidden;
}
</style>
