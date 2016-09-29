<link href="<?php echo css_url().'/reservation.css'; ?>" media="screen" rel="stylesheet" type="text/css" />

<div class="container_bg container">
<div id="View_Traveler_Rreview" class="clearfix">
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
      <div class="Box_Head">
          <h2><?php echo translate("Review"); ?> </h2>
        </div>
      <div class="Box_Content">
<?php echo form_open('trips/review_by_traveller',array('id' => 'ReviewTraveler','name' => 'review_by_traveller')); ?>
        
        <div id="View_Review_Blk" class="clearfix med_12 mal_12 pe_12 padding-zero">
        	<div class="clsVReview_Left clsFloatLeft med_3 mal_3 pe_12">
            	<p class="VReview_Pbc"><?php echo translate("Public"); ?> </p>
            </div>
            <div class="clsVReview_Right clsFloatRight med_9 mal_9 pe_12">
            	<h3><?php echo translate("Share your experience"); ?></h3>
              <p><?php if(isset($result->review)) echo $result->review; ?></p>
            </div>
        </div>
        <div id="View_Review_Blk" class="clearfix med_12 mal_12 pe_12 padding-zero">
        	<div class="clsVReview_Left clsFloatLeft med_3 mal_3 pe_12">
            	<p class="VReview_Pvt"><?php echo translate("Private"); ?></p>
            </div>
            <div class="clsVReview_Right clsFloatRight med_9 mal_9 pe_12">
            	<h3><?php echo translate("Feedback to Guest"); ?></h3>
             <p><?php if(isset($result->review)) echo $result->feedback; ?></p>
            </div>
        </div>
        <div id="View_Review_Blk" class="clearfix med_12 mal_12 pe_12 padding-zero">
            <div class="clsVReview_Left clsFloatLeft med_3 mal_3 pe_12">
                <p class="VReview_Pvt"><?php echo translate("Private"); ?></p>
            </div>
            <div class="clsVReview_Right clsFloatRight med_9 mal_9 pe_12">
                <div class="clear">  <h3><?php echo translate("Cleanliness"); ?></h3>
                  <div>
                     <p><?php if(isset($result->review)) echo $result->cleanliness; ?></p>
                  </div>
                <br />
                <div class="clear"> <h3><?php echo translate("Communication"); ?></h3>
                  <div>
                     <p><?php if(isset($result->review)) echo $result->communication; ?></p>
                  </div>
                <br />
                <div class="clear"> <h3><?php echo translate("Accuracy"); ?></h3>
                  <div>
                     <p><?php if(isset($result->review)) echo $result->accuracy; ?></p>
                  </div>
                 <br />
																<div class="clear"> <h3><?php echo translate("Checkin"); ?></h3>
                  <div>
                     <p><?php if(isset($result->review)) echo $result->checkin; ?></p>
                  </div>
                 <br />
																	<div class="clear"> <h3><?php echo translate("Location"); ?></h3>
                  <div>
                    <p><?php if(isset($result->review)) echo $result->location; ?></p>
																		</div>
                 <br />
																<div class="clear"> <h3><?php echo translate("Value"); ?></h3>
                  <div>
                    <p><?php if(isset($result->review)) echo $result->value; ?></p>
                </div>
                 
            </div>
          </div>
      </div>
						</div>
    </div>
  </div>
  <div style="clear:both"></div>
</div>
</div>
<?php echo form_close(); ?>
</div>
</div>
</div>
</div></div>