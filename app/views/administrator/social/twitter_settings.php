<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script type="text/javascript">
		function startCallback() {
		//$("#message").html('<img src="<?php echo base_url().'images/loading.gif' ?>">');
		// make something useful before submit (onStart)
		return true;
	}

	function completeCallback(response) {
		if(response.length > 50 )
{
window.location.href = "<?php echo base_url().'administrator';?>";
}
else
{
	 $('#message').show();
		$("#message").html(response);
		$("#message").delay(1800).fadeOut('slow');
	}}
	$(document).ready(function()
{
	$("#form").validate({
     rules: {
                twitter_api_id: { 
                	required: true, 
                	alphanumeric: true 
                	},
                twitter_api_secret: { 
                	required: true, 
                	alphanumeric: true 
                	},
            },
     messages: {
                  twitter_api_id: {
                  	required: "Please enter the API ID.",
                  	alphanumeric: "Please give valid API ID."
                  	},
                  twitter_api_secret: {
                  	required: "Please enter the Secret Key.",
                  	alphanumeric: "Please give valid Secret Key."
                  	}
               }

});

jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
}, "Letters, numbers, and underscores only please");

});
</script>


<div id="Fb_Settings">
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
 <h1 class="page-header1"><?php echo translate_admin('Twitter Connect Settings'); ?></h1>
</div>
<form action="<?php echo admin_url('social/twitter_settings'); ?>" method="post" id="form" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
	<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
 <tr>
  <td><?php echo translate_admin('Twitter Application ID'); ?><span style="color: red;">*</span></td>
  <td><input type="text" size="30" name="twitter_api_id" value="<?php if(isset($twitter_api_id)) echo $twitter_api_id; ?>"></td>
 </tr>
 <tr>
  <td><?php echo translate_admin('Twitter Application Secret'); ?><span style="color: red;">*</span></td>
  <td><input type="text" size="55" name="twitter_api_secret" value="<?php if(isset($twitter_api_secret)) echo $twitter_api_secret; ?>"></td>
 </tr>
 <tr>
  <td></td>
  <td><div class="clearfix">
    <input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
    <div>
    <div id="message"></div>
    </div> </div></td>
 </tr>
</table>
</form>
</div>