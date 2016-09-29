<!-- Required css stylesheets -->
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>
<?php $this->load->view(THEME_FOLDER.'/includes/profile_header'); ?>
<div id="dashboard_container" class="view_Reviews_Common">
  <div class="Box" id="View_Reviews" style="margin-bottom: 10px;">
    <div class="Box_Head msgbg">
      <h2><?php echo translate("Reviews"); ?></h2>
    </div>
    <div class="Box_Content">
      <p><?php echo translate("Reviews are allowed only at the end of a trip booked through"); ?> <?php echo $this->dx_auth->get_site_title(); ?>. </p>
      <!--<p><?php echo translate("Recommendations are earned by inviting your friends to vouch for you."); ?> <a  href="<?php echo base_url().'users/recommendation'?>"><?php echo translate("Get Recommendations"); ?></a>.</p>-->
      <p> <a href="javascript:void(0);" class="btn_dash_green" id="aboutyou"><?php echo translate("About You"); ?></a>&nbsp;&nbsp;&nbsp; <a class="btn_dash_green" href="javascript:void(0);" id="byyou"><?php echo translate("By You"); ?></a> </p>
      <div style="clear:both"></div>
    </div>
  </div>
  <div id="about_you" class="Box">
    <div class="Box_Head msgbg">
      <h2><?php echo translate("Review"); ?></h2>
    </div>
    <div class="Box_Content">
    	<?php 
					if($reviewfrom->num_rows() > 0)
					{
						?>
      <div style="width:100%;" class="quotes" id="user_result_review">
      	<ul>
        
          				<?php
						foreach($reviewfrom->result() as $row)
						{
					?>
          	<li class="reviews_list">
            <div class="med_2 mal_3 pe_4 fullwidth370" style="width:110px;">
            <div class="review_prof_img" style="text-align: center;"> <a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><img  title="Mahes W" src="<?php echo $this->Gallery->profilepic($row->userby);  ?>" alt="Mahes W">            	
            </a> 
            </div>
            </div>  
            <div class="med_10 mal_9 pe_8 fullwidth370" style="width:140px">
            	<p class="review_username"><a target="blank" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a></p>
            	
            	<h2 class="reviews_title"><i class="fa fa-thumbs-o-up fa-lg"></i> Review:</h2>	
                
                <div class="content trans"> <?php echo $row->review; ?> </div>
              
              	<h2 class="reviews_title"><i class="fa fa-thumbs-o-down fa-lg"></i> Feedback:</h2>
                
                  <div class="content trans"> <?php echo $row->feedback; ?> </div>
                  
              </div>        
             <div class="clearfix"></div> 
             </li>
          
          <?php } ?>
          </ul>
        </div>
        <?php
        } else {  echo '<p>'.translate("No one has reviewed you yet").'</p>'; } ?>
    </div>
    
   <!-- <div class="Box_Head msgbg">
      <h2> <?php echo translate("Recommendation"); ?></h2>
    </div>
    <div class="Box_Content">
      <table style="width:100%;" class="quotes" id="user_result_recommendation">
        <tbody>
          <?php 
					if($recommendsfrom->num_rows() > 0)
					{
						foreach($recommendsfrom->result() as $row)
						{
							if($this->db->where('id',$row->userby)->get('users')->num_rows()!=0)
							{
					?>
          <tr>
            <td width="82">
            <div class="review_prof_img"><a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><img width="76" height="76" title="Mahes W" src="<?php echo $this->Gallery->profilepic($row->userby);  ?>" alt="Mahes W"></a><a target="blank" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a></div>
               </td>
            <td valign="top">
            <div class="review_right_content">
                   <?php echo $row->message;?>
                   <span class="review_right_arrow"></span>
              </div></td>
          </tr>
          <?php }
          } } else {  echo '<p>'.translate("There is no Recommend").'</p>'; } ?>
        </tbody>
      </table>
    </div>-->
  </div>
  <div id="by_you" class="Box">
    <div class="Box_Head msgbg">
      <h2><?php echo translate("Review"); ?></h2>
    </div>
    <div class="Box_Content">
    	<?php 
													if($reviewby->num_rows() > 0)
													{ ?>
       <div style="width:100%;" class="quotes" id="user_result_review" >
        
          											<?php
														foreach($reviewby->result() as $row)
														{
													?>
													
			<li class="reviews_list">          
            <div class="med_2 mal_3 pe_4 fullwidth370" style="width:110px;">
            <div class="review_prof_img"> 
            <a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><img width="" height="" title="Mahes W" src="<?php echo $this->Gallery->profilepic($row->userby);  ?>" alt="Mahes W"></a>
            </div>
               </div>
            <div class="med_10 mal_9 pe_8 fullwidth370" style="width:140px">
            	<p class="review_username"><a target="blank" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a></p> 
            	<h2 class="reviews_title"><i class="fa fa-thumbs-o-up fa-lg"></i> Review:</h2>
                <div class="content trans"> <?php echo $row->review; ?> </div>
                <h2 class="reviews_title"><i class="fa fa-thumbs-o-down fa-lg"></i> Feedback:</h2>
                <div class="content trans"> <?php echo $row->feedback; ?> </div>
                
             </div>
              <div class="clearfix"></div>
          </li>
          <?php } ?>
       
    </div>
    <?php } else {  echo '<p>'.translate("No one has reviewed by you").'</p>'; } ?>
    </div>
   
   <!-- <div class="Box_Head">
      <h2> <?php echo translate("Recommendation"); ?></h2>
    </div>
    <div class="Box_Content">
      <table style="width:100%;" class="quotes" id="user_result_recommendation">
        <tbody>
          <?php 
					if($recommendsby->num_rows() > 0)
					{
						foreach($recommendsby->result() as $row)
						{
					?>
          <tr>
            <td width="82">
            <div class="review_prof_img"><a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><img width="76" height="76" title="Mahes W" src="<?php echo $this->Gallery->profilepic($row->userby);  ?>" alt="Mahes W"></a><a target="blank" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a></div>
               </td>
            <td valign="top">
            <div class="review_right_content">
                   <?php echo $row->message;?>
                   <span class="review_right_arrow"></span>
              </div>
            
            
              </td>
          </tr>
          <?php } } else {  echo '<p>'.translate("There is no Recommend").'</p>'; } ?>
        </tbody>
      </table>
    </div>-->
  </div>
</div>
</div>
<!-- Footer Scripts -->
<!--Hide and Show the By You -->
<script type="text/javascript">

$("#by_you").hide();
$("#aboutyou").click(function ( event ) {
event.preventDefault();
$("#about_you").show("slow");
$("#by_you").hide("slow");
});
$("#byyou").click(function ( event ) {
event.preventDefault();
$("#by_you").show("slow");
$("#about_you").hide("slow");
});
		
</script>