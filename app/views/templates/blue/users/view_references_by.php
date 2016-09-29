<!-- Required css stylesheets -->
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
 <?php
 $reference_count['by_you_count'] = $by_you_count;
 $reference_count['about_you_count'] = $about_you_count;
 ?>
<!-- End of stylesheet inclusion -->
  <?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>

			<?php $this->load->view(THEME_FOLDER.'/includes/profile_header'); ?>	
			<?php $this->load->view(THEME_FOLDER.'/includes/reference_header',$reference_count); ?>
			<style>
			.edit_href:hover
			{
				color: #ffffff !important;
				text-decoration: none !important;
			}
			</style>
<div id="dashboard_container">   
    <div class="Box" id="View_GetRecom_1">
      <div class="Box_Head msgbg"><h2 class="pol_msgbg"><?php echo translate("Reference Requests"); ?></h2></div>  
      <div class="Box_Content Box_reference">
            <p><?php echo translate("Write references only for people you know well enough to recommend to the")." ".$this->dx_auth->get_site_title()." ".translate("Community. If you ignore a request the other person will not be notified."); ?>
           </p>
            <table class="quotes fullwidth1" id="vouch_recom_tab">
					<tbody>
					<?php 
					if($recommends_request->num_rows() > 0)
					{
						foreach($recommends_request->result() as $row)
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
								<p>
                          <?php echo get_user_by_id($row->userby)->username; ?> has requested a reference from you.
                        </p>
                        
                        <form method="post" action="<?php echo base_url().'users/reference_req_approval';?>" accept-charset="UTF-8">
						<input type="hidden" name="id" value="<?php echo $row->id;?>">
                        <a class="btn blue gotomsg edit_href" href="<?php echo base_url().'users/vouch/'.$row->userby; ?>"> Write a reference </a>
						<button class="btn blue gotomsg" type="submit"> Ignore </button>
						</form>
                         
                        <span class="review_right_arrow"></span>
										</div>
                                        </td>
						</tr>
 <?php }
						} } else {  echo '<span class="norefcolor">'.translate("No reference requests.").'</span>'; } ?>
              </tbody>
            </table>
  
       </div>
    </div>
	<div class="Box" id="View_GetRecom_2">
    	<div class="Box_Head msgbg"><h2 class="pol_msgbg"><?php echo translate("Past References You've Written"); ?></h2></div>
         <div class="Box_Content Box_reference">
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
                                <div class="review_prof_img">
                                <a class="media-photo media-round" href="<?php echo site_url('users/profile').'/'.$row->userto; ?>">
                                	<img width="76" height="76" title="<?php echo get_user_by_id($row->userto)->username; ?>" src="<?php echo $this->Gallery->profilepic($row->userto, 1);  ?>" alt="<?php echo get_user_by_id($row->userto)->username; ?>"></a>
                                	<!--<a target="blank" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a>-->
                                </div>
										 </td>
								<td valign="top">
                                <div class="review_right_content">
                                <?php
                                $date1 = new DateTime(date('Y-m-d H:i:s', $row->created));
$date2 = new DateTime(date('Y-m-d H:i:s'));
$interval = $date1->diff($date2);
$days = 15 - $interval->days;
if($days >= 0)
{
echo '<div class="leftedit"><a href="'.base_url().'users/vouch/'.$row->userto.'" class="btn blue gotomsg edit_href">Edit</a>&nbsp'.$days.' days left to edit</div>';
}
echo '<div class="leftedit"><h3>Reference for <a href="'.base_url().'users/vouch/'.$row->userto.'" class="">'.get_user_by_id($row->userto)->username.'</a></h3></div>';
?>	
														<?php echo $row->message;?>
                                                        <span class="review_right_arrow"></span>
										</div>
                                        </td>
						</tr>
 <?php }
						} } else {  echo '<span class="norefcolor">'.translate("You have not written a reference for anyone yet").'</span>'; } ?>
              </tbody>
            </table>
		 <div id="message" style="display:none"></div>
          
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
