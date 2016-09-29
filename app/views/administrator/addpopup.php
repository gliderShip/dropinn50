<script type="text/javascript" src="<?php echo base_url()?>css/tiny_mce/tiny_mce.js" ></script>
 <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
 <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
   <script type="text/javascript">
$(document).ready(function() {
			$("#popup_form").validate({
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
						
						minlength:1,
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
		theme : "simple"
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
	 <h1 class="page-header1"><?php echo translate_admin('Add Popup'); ?></h1>
	 </div>
		
<form method="post" action="<?php echo admin_url('popup/addpopup')?>" id="popup_form" name="popup_form">	
	<div class="col-xs-9 col-md-9 col-sm-9">
			<table class="table" cellpadding="2" cellspacing="0" width="100%">
		<tr>
					<td><?php echo translate_admin('URL'); ?>&nbsp;<span style="color: red;">*</span></td>
					<td class="clsMailID">
			<select name="page_name">
				    <option value="home">Home Page</option>
					<option value="rooms">List Detail Page</option>
					<option value="search">Search Page</option>
					<option value="step2">Step 2 Booking confirmation page</option>
					<option value="step4">Step 4 Booking confirmation page</option>
				</select>
		</td>
		</tr>
							
		<tr>
				<td class="clsName"> 
						<?php echo translate_admin('Content'); ?>&nbsp;<span style="color: red;">*</span>
				</td>
				<td>
				<textarea id="page_content" name="page_content" rows="15" cols="150" style="width: 100%"></textarea>
		         <span id="content_error" class="content_error"></span>
			</td>
	</tr>
			<tr>
				<td class="clsName"> 
						<?php echo translate_admin('Status'); ?>&nbsp;<span style="color: red;">*</span>
				</td>
				<td>
				<select name="page_status">
					<option value="1">Enable</option>
					<option value="0">Disable</option>
				</select>
		
			</td>
	</tr>
	
		<tr>
		<td></td>    
		<td>
		<input type="hidden" name="popup_operation" value="add"  />
		<input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addpopup" type="submit">
		</td>
		</tr>
		</table>
 </form>

</div>
