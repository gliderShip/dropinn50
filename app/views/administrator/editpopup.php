<script type="text/javascript" src="<?php echo base_url()?>css/tiny_mce/tiny_mce.js" ></script>
 <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
 <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
   <script type="text/javascript">
$(document).ready(function() {

			$("#page_popup").validate({
				submitHandler:function(form) {
					var content = tinyMCE.activeEditor.getContent();
					if(content=='')
					{
						$('#content_error').html('Enter Content for popup');
						return false;
					}
					else
					{
						$('#content_error').html('');
						SubmittingForm();
					}
					
				},
				rules: {

						page_content: {
						maxlength:200
						
						
					},		// simple rule, converted to {required:true}
					page_name:
					{
						required: true,
						
					}

				},
				messages: {
				
					//email: "Enter Valid Email",

					page_content: {
												
						maxlength:"Maximum allowed character is 200"
						},

				}
			});
});
	</script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple",
		
	});
</script>
<style>
label.error
{
    color:#FF0000;
    display:inline;
}
.content_error
{
	color:#FF0000;
}
</style>



<div id="Add_Faq">

	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Edit Popup'); ?></h1>
	 </div>
<form method="post" name="page_popup" id="page_popup" action="<?php echo admin_url('popup/editpopup')?>/<?php echo $this->uri->segment(4,0);  ?>">	
	<div class="col-xs-9 col-md-9 col-sm-9">
			<table class="table" cellpadding="2" cellspacing="0" width="100%">
		<tr>
					<td class="clsName"><?php echo translate_admin('Page Name'); ?>&nbsp;<span style="color: red;">*</span></td>
					<td class="clsMailID">
		<select name="page_name">
			        <option value="home"<?php if($popups->name == 'home') echo 'selected'; ?>>Home Page</option>
					<option value="rooms"<?php if($popups->name == 'rooms') echo 'selected'; ?>>List Detail Page</option>
				<!--	<option value="search"<?php if($popups->name == 'search') echo 'selected'; ?>>Search Page</option>-->
					<option value="step2"<?php if($popups->name == 'step2') echo 'selected'; ?>>Step 2 Booking confirmation page</option>
					<option value="step4"<?php if($popups->name == 'step4') echo 'selected'; ?>>Step 4 Booking confirmation page</option>
				</select>
		</td>
		</tr>
							
		<tr>
				<td class="clsName"> 
						<?php echo translate_admin('Content'); ?>&nbsp;<span style="color: red;">*</span>
				</td>
				<td>
				<textarea id="page_content" name="page_content" rows="15" cols="150" style="width: 100%"><?php echo $popups->content; ?></textarea>
		        <span id="content_error" class="content_error"></span>
			</td>
	</tr>
			<tr>
				<td class="clsName"> 
						<?php echo translate_admin('Status'); ?>&nbsp;<span style="color: red;">*</span>
				</td>
				<td>
				<select name="page_status">
					<option value="1"<?php if($popups->status == 1) echo 'selected'; ?>>Enable</option>
					<option value="0"<?php if($popups->status == 0) echo 'selected'; ?>>Disable</option>
				</select>
		
			</td>
	</tr>
	
		<tr>
		<td></td>    
		<td>
		<input type="hidden" name="popup_operation" value="add"  />
		<input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addpopup" type="submit" id="addpopup">
		</td>
		</tr>
		</table>
 </form>

</div>