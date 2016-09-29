<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- mobile verification 1 start-->

<!-- mobile verification 1 end-->
<script>
$(document).ready(function() {
	
	
jQuery( "#dob" ).datepicker({
	            yearRange: "1914:-18",
	            defaultDate : '-18y',
	            changeMonth: true,
                changeYear: true,          
	    });
	    
	   
});


</script>

<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>
<?php $this->load->view(THEME_FOLDER.'/includes/profile_header'); 

$this->load->library('twconnect');
 $twitter_id =  $this->twconnect->tw_user_id;

?>

<div id="dashboard_container" class="med_12 padd-zero">
	<div id="View_Edit_Profile" class="Box">
    	<div class="Box_Head msgbg">
     <h2 class="prfl_head"><?php echo translate("About You"); ?> 
         <span class="Box_Head_Right" id="show_date_time"></span>
	 </h2>
     </div>
        	<div class="Box_Content clearfix" style="padding: 0px;">
             <div id="Edit_Pro_Left" class="clsFloatLeft med_3 mal_3 pe_12 ">

                           
						 <h2 class="profile_head"><?php echo translate("Upload photo"); ?></h2>
							<div id="user_pic" onclick="show_ajax_image_box();"> 
         <img  id="edit_profile_img" class="admin-img" src="<?php 
		 /*  if($this->session->userdata('image_url') != '')
		   {
		      echo $this->session->userdata('image_url');
		   }
		   else {*/
			   
		  	 echo $this->Gallery->profilepic($this->dx_auth->get_user_id(),2);
			   
		  // }
			    ?>"  /> 
      
                		
							<form action="<?php echo site_url('users/photo/'.$user_id); ?>" name="user_photo" id="user_photo" method="post" enctype="multipart/form-data">                 		
									<p>
									<input id="upload123" name="upload123" type="file" style="width:100%;" />
									<input id="upload" name="upload" value="Hello" type="hidden" />
									</p>
									<?php echo form_error('upload123'); ?>
									<p class="med_12 mal_12 pe_12"><button type="submit" id="upload_image_submit_button" class="btn_dash"  name="commit"><span><span><?php echo translate("Upload photo"); ?></span></span></button></p>
       </form>

        	</div> 
            </div>
												
             <div id="Edit_Pro_Right" class="med_9 mal_9 pe_12 padding-zero">
               <form action="<?php echo site_url('users/edit/'.$user_id); ?>" method="post" name="user_edit" style="overflow:hidden;">              	
                            <ul class="pro-list" style="overflow:hidden;">
                            	<li style="font-size: 14px;background:#efefef;padding-top:10px;">                                     	
                                     		<h2 style="text-align: left;">Required</h2>                                	
                                     </li>
                                    <li class="med_12 mal_12 pe_12 padding-zero">
                                        <p class=" label-edit med_3 mal_5 pe_12"><?php echo translate("First Name"); ?><span class="col_span">:</span></p>
                                        <span class="med_5 mal_6 pe_12">
                                        <input class="name_input" style="margin:0 10px 0 0;" id="user_first_name" name="Fname" size="30" type="text" value="<?php if(isset($profile->Fname)) echo $profile->Fname; else echo '""'; ?>" /></span>
										
                                    </li>
									<li class="med_12 mal_12 pe_12 padding-zero">
									<p class=" label-edit med_3 mal_5 pe_12"><?php echo translate("Last Name"); ?><span class="col_span">:</span></p>
                                        <span class="med_5 mal_6 pe_12"><input class="name_input" id="user_last_name" name="Lname" size="30" type="text" value="<?php if(isset($profile->Lname)) echo $profile->Lname; else echo '""'; ?>" /></span>
									</li>
									<li class="med_12 mal_12 pe_12 padding-zero">
									<p class=" label-edit med_3 mal_5 pe_12"><?php echo translate("I am"); ?><i class="fa fa-lock" style="margin:0px 3px;font-size:14px !important;color:#eb3c44;" title="Private"></i>	<span class="col_span">:</span></p>
                                        
                                       <span class="med_5 mal_6 pe_12">
                                        	<select name="gender" style="padding: 5px 14px 5px 2px">
                                        		<?php if($profile->gender == '')
												{
													?>
													<option selected>Gender</option>
												<?php } ?>
                                        		<option value="male" <?php if($profile->gender == 'male') echo 'selected'; ?>>Male</option>
                                        		<option value="female" <?php if($profile->gender == 'female') echo 'selected'; ?>>Female</option>
                                        		<option value="other" <?php if($profile->gender == 'other') echo 'selected'; ?>>Other</option>
                                        	</select>
                                        </span>
									</li>
									<li class="med_12 mal_12 pe_12 padding-zero">
									<p class=" label-edit med_3 mal_5 pe_12"><?php echo translate("Birth Date"); ?><i class="fa fa-lock" style="margin:0px 3px;font-size:14px !important;color:#eb3c44;" title="Private"></i>	<span class="col_span">:</span></p>
                                        <span class="med_5 mal_6 pe_12"><input type="text" id="dob" name="dob" size="9" value="<?php echo $profile->dob; ?>" placeholder="mm/dd/yyyy" readonly/></span>
									</li>
                                    <li class="med_12 mal_12 pe_12 padding-zero">
                                        <p class=" label-edit med_3 mal_5 pe_12">
                                        <?php echo translate("Email"); ?><sup class="sup-list">*</sup><i class="fa fa-lock" style="margin:0px 3px;font-size:14px !important;color:#eb3c44;" title="Private"></i>	<span class="col_span">:</span> </p>
                                        <span class="med_5 mal_6 pe_12">
                                        <input class="private_lock" id="user_email" name="email" size="30" type="text" value="<?php echo $email_id ; ?>" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" autocomplete=off  />
										<?php echo form_error('email'); ?>
                                        </span>
                                    </li>
                                        
                                     
                                      <!-- Mobile verification start--->  
                
                                        <!-- end mobile verification-->

                                    <li class="med_12 mal_12 pe_12 padding-zero">
                                        <p class=" label-edit med_3 mal_5 pe_12"><?php echo translate("Where You Live"); ?><span class="col_span">:</span></p>
                                        <span class="med_5 mal_6 pe_12"><input id="user_profile_info_current_city" name="live" value="<?php if(isset($profile->live)) echo $profile->live; else echo ''; ?>" size="30" type="text" /><br />
                                        	<span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate("Ex_live"); ?></span><br /></span>
                                    </li>
                                     <li class="med_12 mal_12 pe_12 padding-zero" style="margin-bottom: 25px;">
                                        <p class="label-edit med_3 mal_5 pe_12" style="vertical-align:top;"><?php echo translate("Describe Yourself"); ?><span class="col_span">:</span></p>
                                        <span class="med_5 mal_6 pe_12" ><textarea cols="40" id="user_profile_info_about" name="desc" rows="20" style="width:100%;height:200px;">
<?php if(isset($profile->describe)) echo strip_tags(str_replace('[removed]', '', $profile->describe)); ?></textarea></span>
                                     </li>  
                                     <div class="clearfix"></div>
                                     <li style="font-size: 14px;background:#efefef;padding-top: 10px;">                                     	
                                     		<h2 style="text-align: left;">Optional</h2>                                	
                                     </li>
                                         <li class="med_12 mal_12 pe_12 padding-zero">
                                        <p class=" label-edit med_3 mal_5 pe_12"><?php echo translate("School"); ?><span class="col_span">:</span></p>
                                        <span class="med_5 mal_6 pe_12"><input id="user_profile_info_employer" name="school" size="30" type="text" value="<?php if(isset($profile->school)) echo $profile->school; else echo '""'; ?>" /></span>
                                    </li>                                                                      
                                    <li class="med_12 mal_12 pe_12 padding-zero">
                                        <p class=" label-edit med_3 mal_5 pe_12"><?php echo translate("Work"); ?><span class="col_span">:</span></p>
                                        <span class="med_5 mal_6 pe_12">
                                        <input id="user_profile_info_employer" name="work" size="30" type="text" value="<?php if(isset($profile->live)) echo $profile->work; else echo '""'; ?>" />
                                        </span>
                                    </li>
                                    <li class="med_12 mal_12 pe_12 padding-zero" id="veri">
                                    <div id="verified" class="med_4 mal_5 pe_12" style="display: none;font-size: 14px;padding:0px 0px 10px;">
                                        <span id="phone_no" style="background-color: #f1f1f1;border-right: 1px solid #d1d1c9;color: #393c3d;">  <?php  echo '+'.$profile->phnum; ?></span>
                                          <a id="verify_sms3" rel="sms" href="javascript:void(0);" class="" style="display: none;">Verify via SMS</a>
                                          <?php
                                          
                                           if($users->phone_verify == 'yes')
										  { ?>
										  	<a id="verify_sms2" rel="sms" href="javascript:void(0);" style="display: none;" class="">Verify via SMS</a>
                                           <span id="verified_success" style="color: #5bb013;"> Verified </span>
                                           <a id="close" title="Remove">
                                           	<i class="fa fa-close" style="color:red;"></i>
                                           	</a>
                                           
                                           <?php }
											else {
												
												?>
												<a id="verify_sms2" rel="sms" href="javascript:void(0);" class="">Verify via SMS</a>
												<span id="verified_success" style="color: #5bb013;display: none;"> Verified </span>
												<a id="close" title="Remove">
												<i class="fa fa-close" style="color:red;"></i>
												</a>
												<?php
											} ?>
                                            </div>
                                             <?php // } 
										//	else
											//	{
													?>

 <div class="pnaw-step2_new med_3 mal_5 pe_12" style="display: none;">
 </div>


 </li>
																																				
                                    <li class="med_12 mal_12 pe_12 padding-zero">
                                        <p class="label-edit med_3 mal_5 pe_12 " valign="top"><?php echo translate("Time Zone"); ?><span class="col_span">:</span></p>
                                        <span class="med_9 mal_6 pe_12 time_new"> <?php echo timezone_menu(get_user_timezone($this->dx_auth->get_user_id())); ?>  </span>
                                    </li>
																																																																								
                     <li class="med_12 mal_12 pe_12 padding-zero">
                                        <p class="label-edit med_3 mal_5 pe_12 " valign="top"><?php echo translate("Languages"); ?><span class="col_span">:</span></p>
                                         <span class="med_9 mal_6 pe_12 time_new"> 
                                        	<div class="none text-muted row-space-1">None</div>
                                        	<?php
                                        	if($user_language != '')
											{
												foreach($user_language as $lang)
												{
												?>
												<input type="hidden" value="<?php echo $lang; ?>" name="language[]">
												<?php	
												}
											}
                                        	?>
                                        	<a href="javascript:void(0);" class="multiselect-add-more" id="add_language">
     										 <i class="fa fa-plus" style="font-size:14px !important;"></i>
      											Add More
    										</a>
    										<div class="text-muted row-space-top-1">Add languages you speak.</div> 
                                         </span>
                                </li>
                                             <li class="med_12 mal_12 pe_12 padding-zero">
                                        <p class="label-edit med_3 mal_5 pe_12 " valign="top"><?php echo translate("Emergency contact"); ?>
                                        <i class="fa fa-lock" style="font-size:14px !important;color:#eb3c44;" title="Private"></i>
                                        <span class="col_span">:</span></p>
                                         <span class="med_5 mal_6 pe_12">
                                        	<?php
     										 if($profile->emergency_name != '')
											 {
     										 echo $profile->emergency_name.' ('.$profile->emergency_relation.')';
     										 }
     										 ?>
                                        	<a href="javascript:void(0);" id="add_contact" class="multiselect-add-more">
     										 <?php
     										 if($profile->emergency_name == '')
											 {
     										 ?>
     										 <i class="fa fa-plus" style="font-size:14px !important;"></i>
      											Add Contact
      										<?php
											 }
											 else {
												echo 'edit';
											 }
											 ?>
											 
    										</a>
    										<input type="hidden" name="emergency_status" id="emergency_status" value="<?php if(isset($profile->emergency_status)) echo $profile->emergency_status; else echo '0'; ?>">
    										<div class="text-muted row-space-top-1">Give our Customer Experience team a trusted contact we can alert in an urgent situation.</div>
                                         </span>
                                    </li>
                                      <li class="med_12 mal_12 pe_12 padding-zero">
						              	  <p class="label-edit med_3 mal_5 pe_12 emergency_contact" valign="top">
						              		Name<sup class="sup-list">*</sup><span class="col_span">:</span> </p>
						               <span class="med_5 mal_6 pe_12 emergency_contact">
						              		<input type="text" name="emergency_name" size="30" value="<?php if($profile->emergency_name != '') echo $profile->emergency_name; else echo set_value('emergency_name'); ?>"/>
						              		<?php echo form_error('emergency_name'); ?>
						              	</span>
						              </li> 
						         <li class="med_12 mal_12 pe_12 padding-zero emergency_contact">
						              	<p class="label-edit med_3 mal_5 pe_12 " valign="top">
						              		Phone<sup class="sup-list">*</sup><span class="col_span">:</span> </p>						              	
						               <span class="med_5 mal_6 pe_12">
						              		<input type="text" name="emergency_phone" value="<?php if($profile->emergency_phone != '') echo $profile->emergency_phone; else echo set_value('emergency_phone'); ?>" size="30"/>
						              		<?php echo form_error('emergency_phone'); ?>
						              	</span>
						              </li> 
						             <li class="med_12 mal_12 pe_12 padding-zero emergency_contact">
						              	<p class="label-edit med_3 mal_5 pe_12" valign="top">
						              		Email<sup class="sup-list">*</sup><span class="col_span">:</span> </p>
						              	<span class="med_5 mal_6 pe_12">
						              		<input type="text" name="emergency_email" value="<?php if($profile->emergency_email != '') echo $profile->emergency_email; else echo set_value('emergency_email'); ?>" size="30"/>
						              		<?php echo form_error('emergency_email'); ?>
						              	</span>
						              </li> 
						                 <li class="med_12 mal_12 pe_12 padding-zero">
						              	<p class="label-edit med_3 mal_5 pe_12 emergency_contact" valign="top">
						              		 Relationship<sup class="sup-list">*</sup><span class="col_span">:</span> </p>
						                 	<span class="med_5 mal_6 pe_12 emergency_contact">
						              		<input type="text" name="emergency_relation" value="<?php if($profile->emergency_relation != '') echo $profile->emergency_relation; else echo set_value('emergency_relation'); ?>" size="30"/>
						              		<?php echo form_error('emergency_relation'); ?>
						              	</span>
						              </li> 
<li class="med_12 mal_12 pe_12 padding-zero">      
<p class="label-edit med_3 mal_5 pe_12" style="vertical-align:top;"></p>
   <span class="med_5 mal_6 pe_12" style="padding:0px 0px 0px 15px;" >                  
	 <button  type="submit" class="btn_dash" style="width: auto;" id="save_changes" name="commit"><span><span><?php echo translate("Save Changes"); ?></span></span></button>
                           <span style="padding: 9px;"><span class="or_span">or </span> <span class="can_sp" style="font-size: 14px;"><?php echo anchor('home',translate("Cancel")); ?>&nbsp;&nbsp;&nbsp;</span> 	</span>
                           </span> 
                           	</li>
                                </ul>
                            
																												</form>
                            </div>
                           <div style="clear:both;"></div>

                             
</div>

</div>
</div>
</div>

<!-- End of the page scripts -->

<script type="text/javascript">
// Current Server Time script (SSI or PHP)- By JavaScriptKit.com (http://www.javascriptkit.com)
// For this and over 400+ free scripts, visit JavaScript Kit- http://www.javascriptkit.com/
// This notice must stay intact for use.

//Depending on whether your page supports SSI (.shtml) or PHP (.php), UNCOMMENT the line below your page supports and COMMENT the one it does not:
//Default is that SSI method is uncommented, and PHP is commented:

var currenttime = '<?php echo date("F d, Y H:i:s", get_user_time(local_to_gmt(),get_user_timezone())); ?>' //PHP method of getting server date

///////////Stop editting here/////////////////////////////////

var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime)

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
document.getElementById("show_date_time").innerHTML="<b>"+datestring+"</b>"+"&nbsp;<b>"+timestring+"</b>";
}

window.onload=function(){
setInterval("displaytime()", 1000)
}


/*  time out for link   */

$('#user_photo').submit(function(){
  setTimeout(function() {
    $('#upload123').val('');
  },1000);
});

/*  time out for link   */

$(document).ready(function()
{
	 setTimeout(function() {
	 	$.ajax({
		url: '<?php echo base_url()."users/profile_pic";?>',
		type: "POST",
		success: function(data)
		{
			$("#header_img").attr('src',data);
			$("#edit_profile_img").attr('src',data);
			
		}
	})
	},1000);
})

</script>
<!--<script>
	function InvalidMsg(textbox) {
    
     if(textbox.validity.patternMismatch){
        textbox.setCustomValidity('please enter 10 numeric value.');
    }    
    else {
        textbox.setCustomValidity('');
    }
    return true;
}
</script>-->
<script>


$('#user_phone').keydown(function (e) {
if (e.shiftKey || e.ctrlKey || e.altKey) {
e.preventDefault();
} else {
var key = e.keyCode;
if (!((key >=48 && key <= 57 )||(key >=96 && key <=105 )||(key ==8)||(key==110)||(key==190) )) {
e.preventDefault();
}

}
});
</script>

<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<style type="text/css">
.help{color:#FF0000;}
.phone-number-verify-widget {
    border: 1px solid #c3c3c3;
    border-radius: 2px;
    clear: both;
    float: left;
    line-height: 26px;
    margin: 10px 0;
    padding: 15px;
    text-align: left;
}
.phone-number-input-widget .pniw-number-container .pniw-number-prefix {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #bbb;
    border-image: none;
    border-radius: 2px 0 0 2px;
    border-style: solid;
    border-width: 1px 0 1px 1px;
    color: #393c3d;
    float: left;
    line-height: 32px;
    min-width: 30px;
    padding: 0 4px;
    text-align: center;
}

</style>
<script>
$(document).ready(function() {
	
	$('#phone_number').keypress(function(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
    }
 });
    $('#phone_number_verification2').keypress(function(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
    }
});

  $('#phone_number_verification').keypress(function(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
    }
});

jQuery( "#dob" ).datepicker({
	            yearRange: "1914:-18",
	            defaultDate : '-18y',
	            changeMonth: true,
                changeYear: true,          
	    });
	    
	     $.ajax({
            url: "<?php echo base_url()?>users/phone_code",            
            type: "POST",                       
            data:"id="+$(this).val(),
            success: function (result) { 
                   
                   $('.pniw-number-prefix').text('+'+result);          
            }
            });
	    
$('#country').change(function()
{
	 $.ajax({
            url: "<?php echo base_url()?>users/phone_code",            
            type: "POST",                       
            data:"id="+$(this).val(),
            success: function (result) { 
                   
                   $('.pniw-number-prefix').text('+'+result);          
            }
            });
})	    
$('#verify_sms').click(function()
{	
	if($('#phone_number').val() == '' || isNaN($('#phone_number').val()))
	{
		$('.pnaw-verification-error').show();
		$('.pnaw-verification-error').text('Please enter a phone number.');return false;
	}
	
	if($('#phone_number').val().length < 4)
	{
		$('.pnaw-verification-error').show();
		$('.pnaw-verification-error').text('The phone number you entered was too short.');return false;
	}
	
	 $.ajax({
            url: "<?php echo base_url()?>users/mobile_verification",            
            type: "POST",                       
            data:"phone_number="+$('.pniw-number-prefix').text()+$('#phone_number').val(),
            success: function (result) { 
            	$('.pnaw-verification-error').hide();
            	$('.message').hide();
            	$('.pnaw-step1').hide();
                $('.pnaw-step2').show();  
                $('#phone_number_verification').val('');
	$('#phone_number_verification2').val('');
	$('#phone_number').val(''); 
            }
            });
})

$('#close').click(function()
{
	 $.ajax({
            url: "<?php echo base_url()?>users/close_phone",            
            type: "POST",                       
            success: function (result) { 
            //	$('.phone-number-verify-widget').show();
            	$('#pre_add_phone_no').show();
                $('#verified').hide();   
            }
            });
})

$('#verify_sms2').click(function()
{
	$.ajax({
            url: "<?php echo base_url()?>users/mobile_verification",            
            type: "POST",                       
            data:"phone_number="+$('#phone_no').text(),
            success: function (result) { 
            	$('.pnaw-verification-error').hide();
            	$('.message').hide();
            	$('.pnaw-step1').hide();
                $('.pnaw-step2').show(); 
                $('#phone_number_verification').val('');
	$('#phone_number_verification2').val('');
	$('#phone_number').val('');  
            }
            });
	$('#verified').hide();
	 $('.pnaw-step3').show();
})
$('#verify_code').click(function()
{
	 $.ajax({
            url: "<?php echo base_url()?>users/check_mobile_verification",            
            type: "POST",                       
            data:"phone_code="+$('#phone_number_verification').val(),
            success: function (result) { 
            	if(result == 'failed')
            	{
            		//alert('We were unable to validate your phone number. Please try again.');
            		$('.pnaw-verification-error').show();
					$('.pnaw-verification-error').text('We were unable to validate your phone number. Please try again.');
					$('.message').show();
            	    $('.message').text('We were unable to validate your phone number. Please try again.');return false;
            	} 
            	
            	if(result == 'success')
            	{
            		 $.ajax({
            url: "<?php echo base_url()?>users/get_phone_no",            
            type: "POST",                       
            success: function (result) { 
            	if($(result).length == 1)
            	{
            		window.location.reload();
            	}
            	else
            	{
            		$('#phone_no').text('+'+result);
            	}
            	
            }
            });
            		$('.phone-number-verify-widget').hide();
            		$('.pnaw-step2').hide();
            		$('.pnaw-step3').hide();
            		$('#verified').show();
            		$('#verified_success').show();
            		$('#close_img').css('margin-left','44px');
            		$('#verify_sms2').hide();
            		$('#phone_number_verification').val('');
	$('#phone_number_verification2').val('');
	$('#phone_number').val('');
            	}
           
            }
            });
})

$('#verify_code2').click(function()
{
	 $.ajax({
            url: "<?php echo base_url()?>users/check_mobile_verification",            
            type: "POST",                       
            data:"phone_code="+$('#phone_number_verification2').val(),
            success: function (result) { 
            	if(result == 'failed')
            	{
            		//alert('We were unable to validate your phone number. Please try again.');
            		$('.pnaw-verification-error').show();
					$('.pnaw-verification-error').text('We were unable to validate your phone number. Please try again.');
					//$('.message').show();
            		//$('.message').text('We were unable to validate your phone number. Please try again.');return false;
            	} 
            	
            	if(result == 'success')
            	{
            		 $.ajax({
            url: "<?php echo base_url()?>users/get_phone_no",            
            type: "POST",                       
            success: function (result) { 
            	$('#phone_no').text('+'+result);
            }
            });
            		$('.phone-number-verify-widget').hide();
            		$('.pnaw-step2').hide();
            		$('.pnaw-step3').hide();
            		$('#verified').show();
            		$('#verified_success').show();
            		$('#close_img').css('margin-left','44px');
            		$('#verify_sms2').hide();
            		$('#phone_number_verification').val('');
	$('#phone_number_verification2').val('');
	$('#phone_number').val('');
            	}
            }
            });
})

$('.cancel').click(function()
{
		$.ajax({
            url: "<?php echo base_url()?>users/get_phone_no",            
            type: "POST",                       
            success: function (result) { 
            	if($(result).length == 1)
            	{
            		window.location.reload();
            	}
            	else
            	{
            		$('#phone_no').text('+'+result);
            	}
            	
            }
            });
            
	$('.phone-number-verify-widget').hide();
	$('.pnaw-step3').hide();
	$('#verified').show();
	$('#verified_success').hide();
	$('#phone_number_verification').val('');
	$('#phone_number_verification2').val('');
	$('#phone_number').val('');
	$('#verify_sms2').show();
	$('#close_img').css('margin-left','1px');
})

<?php if($profile->phnum != '' || $users->phone_verify == 'yes')
{?>
//$('.phone-number-verify-widget').show();
$('#pre_add_phone_no').hide();
<?php } ?>

<?php 
if($profile->phnum == 0)
{
	?>
	$('#pre_add_phone_no').show();
	<?php
}
?>

<?php if($profile->phnum != '' && $profile->phnum != 0 && $users->phone_verify != 'yes')
{?>
//$('.phone-number-verify-widget').show();
$('#verified').show();
<?php } ?>

<?php if($profile->phnum != '' && $profile->phnum != 0 && $users->phone_verify == 'yes')
{?>
//$('.phone-number-verify-widget').show();
$('#verified').show();
<?php } ?>


$('#add_phone_no').click(function()
{
	$('#phone_number_verification').val('');
	$('#phone_number_verification2').val('');
	$('#phone_number').val('');
	$('#pre_add_phone_no').hide();
	$('.pnaw-step2').hide();
	$('.pnaw-verification-error').hide();
	$('.message').hide();
	$('.pnaw-step1').show();
	$('.pnaw-step3').hide();
	$('.phone-number-verify-widget').show();
})
$('#save_changes').click(function()
{

var message = $('#user_profile_info_about').val();

   if(/\b(\w)+\@(\w)+\.(\w)+\b/g.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed in Describe Yourself field.');return false;
            }
            else if(message.match('@') || message.match('hotmail') || message.match('gmail') || message.match('yahoo'))
				{
					alert('Sorry! Email or Phone number is not allowed in Describe Yourself field.');return false;
				}
         	if(/\+?[0-9() -]{7,18}/.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed in Describe Yourself field.');return false;
            }
});

$('#add_language').click(function()
{
	$('.modal_save_to_wishlist').show();
})

$('.panel-close').click(function()
{
	$('.modal_save_to_wishlist').hide();
})

$('#cancel_language').click(function()
{
	$('.modal_save_to_wishlist').hide();
})
var j = 0;
$('#submit_language').click(function()
{
	$('.multiselect-option').remove();
	$('input[type=hidden]').remove();
	i = 0;
	j++; 
	$("input[type=checkbox]:checked").each ( function() 
	{
		i++;
		var id = $(this).val();
		var language = $('#'+$(this).val()).text();
		
		$('.none').after('<span class="multiselect-option" id="'+id+'"> <span class="btn gray small btn-small row-space-1">'+language+'&nbsp;<small><a class="text-normal" href="javascript:void(0);" style="color:inherit;"><i title="Remove from selection" class="fa fa-remove" onclick="hide('+id+');" style="font-size:1.25em;"></i></a> </small></span>&nbsp;</span>');
		$('.none').after('<input type="hidden" value="'+id+'" name="language[]" />');
       
	});

	$('.modal_save_to_wishlist').hide();
	
	if(i == 0)
	{
		$('.none').show();
		$('.none').next('br').remove();
		$('.multiselect-option').next('br').remove();
	}
	else
	{
		$('.none').hide();
	}
	
	if(j == 1 && i > 1)
	{
	$('#add_language').prev('br').remove();
	$('#add_language').before('<br>');
	$('#add_language').prev('br').prev('br').remove();
	}
	else
	{
	$('#add_language').before('<br>');
	$('#add_language').prev('br').prev('br').remove();
	}
})

<?php 

if($profile->language != '')
{
	?>
	$('.none').hide();
	$('.none').after('<?php echo $user_language_jquery;?>');
	$('#add_language').before('<br>');
	<?php 
}
?>

$('#add_contact').click(function()
{
 $( ".emergency_contact" ).toggle(0,function()
 {
 	if ( $(this).is(':visible')) {
       $('#emergency_status').val(1);  
    }
    else
    {
    $('#emergency_status').val(0);
    }
 });

});
$(".emergency_contact").hide();
<?php
if($profile->emergency_status == 1)
{
?>
//$(".emergency_contact").show();
<?php	
}
else {
	?>
	$(".emergency_contact").hide();
	<?php
}

if(isset($validation_status))
{
if($validation_status == 1)
{
?>
$(".emergency_contact").show();
$('#emergency_status').val(1);
<?php
}
}
?>
});

function hide(item_id)
{
	$('#checkbox_'+item_id).prop('checked', false);
	$('#'+item_id).hide();
	$("input[value="+item_id+"][name!='language1']").remove();
	var i=0;
	$("input[type=checkbox]:checked").each ( function() 
	{
		i++;
	});
	if(i == 0)
	{
		$('.none').show();
		$('.multiselect-option').next('br').remove();
	}
}
</script>

<!--End of stylesheet inclusion --
<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>
<?php $this->load->view(THEME_FOLDER.'/includes/profile_header'); 

$this->load->library('twconnect');
 $twitter_id =  $this->twconnect->tw_user_id;

?>

<div id="dashboard_container">
	<div id="View_Edit_Profile" class="Box">
    	<div class="Box_Head msgbg">
     <h2><?php echo translate("About You"); ?> 
         <span class="Box_Head_Right" id="show_date_time"></span>
	 </h2>
     </div>
        	<div class="Box_Content clearfix">
             <div id="Edit_Pro_Left" class="clsFloatLeft">

                           
						 <h2><?php echo translate("Upload photo"); ?></h2>
							<div id="user_pic" onclick="show_ajax_image_box();"> 
         <img width="230" height="236" id="edit_profile_img" src="<?php 
		 /*  if($this->session->userdata('image_url') != '')
		   {
		      echo $this->session->userdata('image_url');
		   }
		   else {*/
			   
		  	 echo $this->Gallery->profilepic($this->dx_auth->get_user_id(),2);
			   
		  // }
			    ?>"  /> 
      
                		
							<form action="<?php echo site_url('users/photo/'.$user_id); ?>" name="user_photo" id="user_photo" method="post" enctype="multipart/form-data">                 		
									<p>
									<input id="upload123" name="upload123" type="file" class="edituser1"/>
									<input id="upload" name="upload" value="Hello" type="hidden" />
									</p>
									<?php echo form_error('upload123'); ?>
									<p><button type="submit" id="upload_image_submit_button" class="btn blue gotomsg"  name="commit"><span><span><?php echo translate("Upload photo"); ?></span></span></button></p>
       </form>

        	</div> 
            </div>
												
             <div id="Edit_Pro_Right" class="clsFloatRight">
               <form action="<?php echo site_url('users/edit/'.$user_id); ?>" method="post" name="user_edit">              	
                            <table>
                            	<tr bgcolor="#efefef" style="font-size: 14px;">
                                     	<td id="Edit_Pro_Left">
                                     		<h2 style="text-align: left;">Required</h2>
                                     	</td>
                                     	<td></td>
                                     </tr>
                                    <tr>
                                        <td class="label"><?php echo translate("First Name"); ?></td>
                                        <td>
                                        <input class="name_input editfirstname" id="user_first_name" name="Fname" size="30" type="text" value="<?php if(isset($profile->Fname)) echo $profile->Fname; else echo '""'; ?>" /></td>
										
                                    </tr>
									<tr>
									<td class="label"><?php echo translate("Last Name"); ?></td>
                                        <td><input class="name_input" id="user_last_name" name="Lname" size="30" type="text" value="<?php if(isset($profile->Lname)) echo $profile->Lname; else echo '""'; ?>" /></td>
									</tr>
									<tr>
									<td class="label"><?php echo translate("I Am"); ?>
										<i class="fa fa-lock" style="font-size:14px !important;color:#eb3c44;" title="Private"></i>	
									</td>
                                        <td>
                                        	<select>
                                        		<?php if($profile->gender == '')
												{
													?>
													<option selected>Gender</option>
												<?php } ?>
                                        		<option value="male" <?php if($profile->gender == 'male') echo 'selected'; ?>>Male</option>
                                        		<option value="female" <?php if($profile->gender == 'female') echo 'selected'; ?>>Female</option>
                                        		<option value="other" <?php if($profile->gender == 'other') echo 'selected'; ?>>Other</option>
                                        	</select>
                                        </td>
									</tr>
									<tr>
									<td class="label"><?php echo translate("Birth Date"); ?>
										<i class="fa fa-lock" style="font-size:14px !important;color:#eb3c44;" title="Private"></i>	
									</td>
                                        <td>
                                        	<input type="text" id="dob" name="dob" size="8" value="<?php echo $profile->dob; ?>" placeholder="mm/dd/yyyy" readonly/>
                                        </td>
									</tr>
                                    <tr>
                                        <td class="label">
                                        <?php echo translate("Email"); ?> <sup>*</sup>
                                        <i class="fa fa-lock" style="font-size:14px !important;color:#eb3c44;" title="Private"></i>	
                                        </td>
                                        <td>
                                        <input class="private_lock" id="user_email" name="email" size="30" type="text" value="<?php echo $email_id ; ?>" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" autocomplete=off  />
										<?php echo form_error('email'); ?>
                                        </td>
                                    </tr>
                                                                       																																				
                                    <tr>
                                        <td class="label" valign="top"><?php echo translate("Phone Number"); ?>
                                        <i class="fa fa-lock" style="font-size:14px !important;color:#eb3c44;" title="Private"></i>	
                                        </td>
                                        <td>
                                        	<div id="pre_add_phone_no" class="clearfix">
                    						<?php echo translate('No phone number entered'); ?><br>
                    					    <a id="add_phone_no"><?php echo translate('Add a Phone number');?></a></div>
                                            <?php
                                           //	if($profile->phnum != '')
										//	{
												?>
												<div id="verified" class="editverify">
                                        <span id="phone_no" class="editphono">  <?php  echo '+'.$profile->phnum; ?></span>
                                          <a id="verify_sms3" rel="sms" href="javascript:void(0);" class="" style="display: none;">Verify via SMS</a>
                                          <?php
                                          
                                           if($users->phone_verify == 'yes')
										  { ?>
										  	<a id="verify_sms2" rel="sms" href="javascript:void(0);" style="display: none;" class="">Verify via SMS</a>
                                           <span id="verified_success" class="verifycolor"> Verified </span>
                                           <a id="close" title="Remove">
                                           	<i class="fa fa-close iconcolor1"></i>
                                           	</a>
                                           
                                           <?php }
											else {
												
												?>
												<a id="verify_sms2" rel="sms" href="javascript:void(0);" class="">Verify via SMS</a>
												<span id="verified_success" class="verifycolor" style="display: none;"> Verified </span>
												<a id="close" title="Remove">
												<i class="fa fa-close iconcolor1"></i>
												</a>
												<?php
											} ?>
                                            </div>
                                             <?php // } 
										//	else
											//	{
													?>
													<div class="phone-number-verify-widget" style="display: none;">
  <p class="pnaw-verification-error iconcolor1"></p>
  <div class="pnaw-step1">
    <div id="phone-number-input-widget-9d95625f" class="phone-number-input-widget">
  <label for="phone_country">Choose a country:</label>
  <div class="select">
     <select name="country" id="country">
                                           	<?php 
                                           	
                                           	foreach($country->result() as $row)
											{
												if($row->country_name == $this->session->userdata('locale_country'))
												{
													$s = 'selected';
												}
												else {
													$s = '';
												}
												echo '<option value="'.$row->id.'"'.$s.'>'.$row->country_name.'</option>';
											}
                                           	?>
     </select>
  </div>
<style>
@media screen and (-webkit-min-device-pixel-ratio:0) {
.phone-number-input-widget .pniw-number-container .pniw-number-prefix
{
	line-height: 27px;
	margin-top: 2px;
}
}
.fa-remove:hover
{
	text-decoration: underline !important;
}
</style>
  <label for="phone_number">Add a phone number:</label>
  <div class="pniw-number-container clearfix editphone1">
    <div class="pniw-number-prefix">+91</div>
    <input type="text" id="phone_number" class="pniw-number editphone2">
  </div>
  <input type="hidden" name="phone" data-role="phone_number" value="91">
  <input type="hidden" value="10346170" name="user_id">
</div>

  

    <div class="pnaw-verify-container">
      <a id="verify_sms" rel="sms" href="javascript:void(0);" class="btn blue gotomsg">Verify via SMS</a>
      <!--<a rel="call" href="javascript:void(0);" class="btn btn-primary">Verify via Call</a>--
      <a href="javascript:void(0);" rel="cancel" class="cancel" style="display: none;">Cancel</a>
      <a target="_blank" href="<?php echo base_url();?>home/help/17" class="why-verify">Why Verify?</a>
    </div>
  </div>
  <div class="pnaw-step2 editphone3" style="display:none;">
    <p class="message iconcolor1"></p>
    <label for="phone_number_verification">Please enter the 4-digit code:</label>
    <input type="text" id="phone_number_verification2">

    <div class="pnaw-verify-container">
      <a id="verify_code2" rel="verify" href="javascript:void(0);" class="btn blue gotomsg">
        Verify
      </a>
      <a href="javascript:void(0);" rel="cancel" class="cancel">
        Cancel
      </a>
    </div>
    <p class="cancel-message">If it doesn't arrive, click cancel and try again.</p>
  </div>
</div>
													<?php
											//	}
                                             ?>
                                             <div class="pnaw-step3" style="display:none;">
    <p class="message iconcolor1"></p>
    <label for="phone_number_verification">Please enter the 4-digit code:</label>
    <input type="text" id="phone_number_verification">

    <div class="pnaw-verify-container">
      <a id="verify_code" rel="verify" href="javascript:void(0);" class="btn blue gotomsg">
        Verify
      </a>
      <a href="javascript:void(0);" rel="cancel" class="cancel">
        Cancel
      </a>
    </div>
    <p class="cancel-message">If it doesn't arrive, click cancel and try again.</p>
  </div>
            
                                        </td>
                                    </tr>
                                    
                                     <tr>
                                        <td class="label"><?php echo translate("Where You Live"); ?></td>
                                        <td><input id="user_profile_info_current_city" name="live" value="<?php if(isset($profile->live)) echo $profile->live; else echo ''; ?>" size="30" type="text" /><br />
                                        	<span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate("Ex_live"); ?></span><br /></td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="vertical-align:top;"><?php echo translate("Describe Yourself"); ?></td>
                                        <td><textarea cols="40" id="user_profile_info_about" name="desc" rows="20" style="width:250px;height:200px;"><?php echo $profile->describe; ?></textarea></td>
                                     </tr> 

                                     <tr bgcolor="#efefef" style="font-size: 14px;">
                                     	<td id="Edit_Pro_Left">
                                     		<h2 style="text-align: left;">Optional</h2>
                                     	</td>
                                     	<td></td>
                                     </tr>
									<tr>
                                        <td class="label"><?php echo translate("School"); ?></td>
                                        <td>
                                        <input id="user_profile_info_employer" name="school" size="30" type="text" value="<?php if(isset($profile->school)) echo $profile->school; else echo '""'; ?>" />
                                        </td>
                                    </tr>
                                                                                                            
                                    <tr>
                                        <td class="label"><?php echo translate("Work"); ?></td>
                                        <td>
                                        <input id="user_profile_info_employer" name="work" size="30" type="text" value="<?php if(isset($profile->live)) echo $profile->work; else echo '""'; ?>" />
                                        </td>
                                    </tr>	
																																				
                                    <tr>
                                        <td class="label" valign="top"><?php echo translate("Time Zone"); ?></td>
                                        <td> <?php echo timezone_menu(get_user_timezone($this->dx_auth->get_user_id())); ?>  </td>
                                    </tr>-->
                              <!--  <tr>
                                        <td class="label" valign="top"><?php echo translate("Languages"); ?></td>
                                        <td> 
                                        	<div class="none text-muted row-space-1">None</div>
                                        	<?php
                                        	if($user_language != '')
											{
												foreach($user_language as $lang)
												{
												?>
												<input type="hidden" value="<?php echo $lang; ?>" name="language[]">
												<?php	
												}
											}
                                        	?>
                                        
                                        	<a href="javascript:void(0);" class="multiselect-add-more" id="add_language">
     										 <i class="fa fa-plus" style="font-size:14px !important;"></i>
      											Add More
    										</a>
    										<div class="text-muted row-space-top-1">Add languages you speak.</div> 
                                         </td>
                                    </tr>
                                   
                                    <tr>
                                        <td class="label" style="width: 130px;display:inline-block;" valign="top"><?php echo translate("Emergency contact"); ?>
                                        <i class="fa fa-lock" style="font-size:14px !important;color:#eb3c44;" title="Private"></i>
                                        </td>
                                        <td> 
                                        	<?php
     										 if($profile->emergency_name != '')
											 {
     										 echo $profile->emergency_name.' ('.$profile->emergency_relation.')';
     										 }
     										 ?>
                                        	<a href="javascript:void(0);" id="add_contact" class="multiselect-add-more">
     										 <?php
     										 if($profile->emergency_name == '')
											 {
     										 ?>
     										 <i class="fa fa-plus" style="font-size:14px !important;"></i>
      											Add Contact
      										<?php
											 }
											 else {
												echo 'edit';
											 }
											 ?>
											 
    										</a>
    										<input type="hidden" name="emergency_status" id="emergency_status" value="<?php if(isset($profile->emergency_status)) echo $profile->emergency_status; else echo '0'; ?>">
    										<div class="text-muted row-space-top-1">Give our Customer Experience team a trusted contact we can alert in an urgent situation.</div>
                                         </td>
                                    </tr>
						              <tr class="emergency_contact">
						              	<td>
						              		Name<sup>*</sup>
						              	</td>
						              	<td>
						              		<input type="text" name="emergency_name" size="30" value="<?php if($profile->emergency_name != '') echo $profile->emergency_name; else echo set_value('emergency_name'); ?>"/>
						              		<?php echo form_error('emergency_name'); ?>
						              	</td>
						              </tr> 
						               <tr class="emergency_contact">
						              	<td>
						              		Phone<sup>*</sup>
						              	</td>
						              	<td>
						              		<input type="text" name="emergency_phone" value="<?php if($profile->emergency_phone != '') echo $profile->emergency_phone; else echo set_value('emergency_phone'); ?>" size="30"/>
						              		<?php echo form_error('emergency_phone'); ?>
						              	</td>
						              </tr> 
						               <tr class="emergency_contact">
						              	<td>
						              		Email<sup>*</sup>
						              	</td>
						              	<td>
						              		<input type="text" name="emergency_email" value="<?php if($profile->emergency_email != '') echo $profile->emergency_email; else echo set_value('emergency_email'); ?>" size="30"/>
						              		<?php echo form_error('emergency_email'); ?>
						              	</td>
						              </tr> 
						               <tr class="emergency_contact">
						              	<td>
						              		 Relationship<sup>*</sup>
						              	</td>
						              	<td>
						              		<input type="text" name="emergency_relation" value="<?php if($profile->emergency_relation != '') echo $profile->emergency_relation; else echo set_value('emergency_relation'); ?>" size="30"/>
						              		<?php echo form_error('emergency_relation'); ?>
						              	</td>
						            </tr> 
                                
                               
                                </table>
                           <!-- <p>
                            <button type="submit" class="btn green gotomsg" id="save_changes" name="commit"><span><span><?php echo translate("Save Changes"); ?></span></span></button>
                            or <?php echo anchor('home',translate("Cancel")); ?>&nbsp;&nbsp;&nbsp;</p>
																												</form>
                            </div>
                           <div class="clearfix"></div>
                          --> 

                             
</div>

</div>
</div>

<div class="modal_save_to_wishlist" id="share-via-email" style="display: none;">
	<div class="modal-table">
	<div class="modal-cell">
		<div class="new-modal wishlist-modal modal-content show_share_fb_checkbox" style="max-width: 520px;">
  <div class="panel-header">
    <span class="no_fb">Spoken Languages</span>
    <a class="panel-close" data-behavior="modal-close" id="wishlist_close_share" href="javascript:void(0);">×</a>
  </div>
  <div class="panel-body">
      <p style="margin-bottom: 15px;">What languages can you speak fluently? We have many international travelers who appreciate hosts who can speak their language.</p>

        <div class="span6 col-6">
        	<?php
        	if($languages->num_rows() != 0)
			{
        	foreach($languages->result() as $row)
			{
        	?>
          <label>
            <input type="checkbox" id="checkbox_<?php echo $row->id;?>" value="<?php echo $row->id;?>" name="language1" <?php if(in_array($row->id,$user_language)) echo 'checked'; ?>>
            <span id="<?php echo $row->id;?>"><?php echo $row->name;?></span>
          </label>
          	<?php
          	}
			}
          	?>
        </div>

    </div>
  <div class="panel-footer">
  	<button class="btn_dash" id="cancel_language" type="cancel">Cancel</button>
    <button class="btn_dash" id="submit_language" type="submit">Save</button>
  </div>
</div>
</div>
</div>
</div>

<style>
.btn-small {
    font-size: 12px;
    padding: 4px 12px;
}
small {
    font-size: 0.85em !important;
}
*, *:before, *:after, hr, hr:before, hr:after, input[type="search"], input[type="search"]:before, input[type="search"]:after {
    box-sizing: border-box;
}
*, *:before, *:after, hr, hr:before, hr:after, input[type="search"], input[type="search"]:before, input[type="search"]:after {
    box-sizing: border-box;
}
.panel-close:hover, .alert-close:hover, .modal-close:hover, .panel-close:focus, .alert-close:focus, .modal-close:focus {
    color: #b0b3b5;
    text-decoration: none;
}
.row {
    margin-left: -12.5px;
    margin-right: -12.5px;
}
.col-6 {
    float: left;
    width: 50%;
}
label {
    display: block;
    padding-bottom: 8px;
    padding-top: 9px;
}
.row:before, .row:after {
    content: " ";
    display: table;
}
*, *:before, *:after, hr, hr:before, hr:after, input[type="search"], input[type="search"]:before, input[type="search"]:after {
    box-sizing: border-box;
}
.panel-header + .panel-body, .panel-body + .panel-body, ul.panel-body > li + .panel-body, ol.panel-body > li + .panel-body, .panel-footer + .panel-body {
    border-top: medium none;
}
.panel-body {
    position: relative;
}
.panel-header, .panel-body, ul.panel-body > li, ol.panel-body > li, .panel-footer {
    border-top: 1px solid #dce0e0;
    margin: 0;
    padding: 20px;
    position: relative;
}
.row:after {
    clear: both;
}
.panel-body > *:last-child {
    margin-bottom: 0;
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
.panel-footer {
    text-align: right;
}
.panel-footer {
    border-top: 1px solid #dce0e0;
    margin: 0;
    padding: 20px;
    position: relative;
}
.wishlist-modal {
    max-width: 700px;
    overflow: visible;
}.modal-content {
    background-color: #fff;
    border-radius: 2px;
    margin-left: auto;
    margin-right: auto;
    max-width: 700px;
    overflow: hidden;
    position: relative;
}


table{border-collapse:collapse;}
td {
    padding-top: .5em;
    padding-bottom: .5em;
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
</style>
<!-- End of the page scripts --

<script type="text/javascript">
// Current Server Time script (SSI or PHP)- By JavaScriptKit.com (http://www.javascriptkit.com)
// For this and over 400+ free scripts, visit JavaScript Kit- http://www.javascriptkit.com/
// This notice must stay intact for use.

//Depending on whether your page supports SSI (.shtml) or PHP (.php), UNCOMMENT the line below your page supports and COMMENT the one it does not:
//Default is that SSI method is uncommented, and PHP is commented:

var currenttime = '<?php echo date("F d, Y H:i:s", get_user_time(local_to_gmt(),get_user_timezone())); ?>' //PHP method of getting server date

///////////Stop editting here/////////////////////////////////

var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime)

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
document.getElementById("show_date_time").innerHTML="<b>"+datestring+"</b>"+"&nbsp;<b>"+timestring+"</b>";
}

window.onload=function(){
setInterval("displaytime()", 1000)
}


/*  time out for link   */

$('#user_photo').submit(function(){
  setTimeout(function() {
    $('#upload123').val('');
  },1000);
});

/*  time out for link   */

$(document).ready(function()
{
	 setTimeout(function() {
	 	$.ajax({
		url: '<?php echo base_url()."users/profile_pic";?>',
		type: "POST",
		success: function(data)
		{
			$("#header_img").attr('src',data);
			$("#edit_profile_img").attr('src',data);
			
		}
	})
	},1000);
})

</script> -->

