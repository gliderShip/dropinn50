<!-- Required css stylesheets -->
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
 <?php
 $reference_count['by_you_count'] = $by_you_count;
 $reference_count['about_you_count'] = $about_you_count;
 ?>
 <style>
 .review_right_content:before
 {
 	top:4px !important;
 }
 .review_right_content:after
 {
 	top:4px !important;
 }
 </style>
<!-- End of stylesheet inclusion -->
  <?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>

			<?php $this->load->view(THEME_FOLDER.'/includes/profile_header'); ?>	
			<?php $this->load->view(THEME_FOLDER.'/includes/reference_header',$reference_count); ?>

<div id="dashboard_container">   
    <div class="Box" id="View_GetRecom_1">
      <div class="Box_Head msgbg"><h2 class="pol_msgbg"><?php echo translate("Pending Approval"); ?></h2></div>  
      <div class="Box_Content Box_reference">           
            <p><?php echo translate("When you accept a reference it will appear on your public profile. If you ignore a reference it will not appear in your profile, and the other person will not be notified."); ?>
            </p>
            <table class="quotes fullwidth1" id="vouch_recom_tab">
					<tbody>
					<?php 
					if($recommends_pending->num_rows() > 0)
					{
						foreach($recommends_pending->result() as $row)
						{
							if($this->db->where('id',$row->userby)->get('users')->num_rows()!=0)
							{
					?>
						<tr>
								<td width="82" class="revprofile">
                                <div class="review_prof_img revprofile1">
                                <a class="media-photo media-round floleft" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>">
                                	<img width="76" height="76" title="<?php echo get_user_by_id($row->userby)->username; ?>" src="<?php echo $this->Gallery->profilepic($row->userby, 1);  ?>" alt="<?php echo get_user_by_id($row->userby)->username; ?>"></a>
                                	<a class="userprof" target="blank" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a>
                                </div>
										 </td>
								<td valign="top">
                                <div class="review_right_content">
														<?php echo $row->message;?>
                                                        <span class="review_right_arrow"></span>
										</div><br/>
								<form method="post" action="<?php echo base_url().'users/references/reference_about_you';?>" accept-charset="UTF-8">
								<input type="hidden" name="userto" value="<?php echo $row->userto; ?>">
     						    <input type="hidden" name="userby" value="<?php echo $row->userby; ?>">
								<input class="btn blue gotomsg" type="submit" value="Ignore" name="ignore">
								<input class="btn blue gotomsg" type="submit" value="Accept" name="accept">	
								</form>
                                        </td>
						</tr>
 <?php }
						} } else {  echo '<span class="norefcolor">'.translate("No pending references").'</span>'; } ?>
              </tbody>
            </table>
       </div>
    </div>
	<div class="Box" id="View_GetRecom_2">
    	<div class="Box_Head msgbg"><h2 class="pol_msgbg"><?php echo translate("Past References Written About You"); ?></h2></div>
         <div class="Box_Content Box_reference">
         <p>References require profile photos. A reference will only display in your public profile if the member who wrote it has a profile picture. </p>
		 <div id="message" style="display:none"></div>
        <table class="quotes fullwidth1" id="vouch_recom_tab">
					<tbody>
					<?php 
					if($recommends->num_rows() > 0)
					{
						foreach($recommends->result() as $row)
						{
							if($this->db->where('id',$row->userby)->get('users')->num_rows()!=0)
							{
					?>
						<tr>
								<td width="82" class="revprofile">
                                <div class="review_prof_img revprofile1">
                                <a class="media-photo media-round floleft" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>">
                                	<img width="76" height="76" title="<?php echo get_user_by_id($row->userby)->username; ?>" src="<?php echo $this->Gallery->profilepic($row->userby, 1);  ?>" alt="<?php echo get_user_by_id($row->userby)->username; ?>">
                                	</a><a class="userprof" target="blank" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a>
                                </div>
										 </td>
								<td valign="top">
                                <div class="review_right_content">  
														<?php echo $row->message;?>
                                                        <span class="review_right_arrow"></span>
										</div>
                                        </td>
						</tr>
 <?php }
						} } else {  echo '<span class="norefcolor">'.translate("There is no reference.").'</span>'; } ?>
              </tbody>
            </table>
          </div> 
    </div>     
</div>
<script>
$('.btn').click(function(){
var emailAddress = $('#email_valid').val();
var mail = emailAddress.split(',');
for(i=0;i<mail.length;i++){
var emailRegex = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);
 var valid = emailRegex.test(mail[i]);
  if (!valid) {
  //  alert("Please Enter the valid e-mail address");
    $("#message").show();
	$("#message").html('<p style="color:red"><strong><em> Please Enter the valid e-mail address </em></strong></p>');
    $("#message").delay(2000).fadeOut('slow');
    return false;
  } else
  				$("#message").show();
				$("#message").html('<p style="color:#009933"><strong><em> Email Sent successfully </em></strong></p>');
				$("#message").delay(2000).fadeOut('slow');
    return true;
	}
	
});
</script>
