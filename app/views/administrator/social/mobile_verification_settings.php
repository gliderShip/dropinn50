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
	}
	}
	$(document).ready(function()
{
	$("#form").validate({
     rules: {
                nexmo_api_key: { 
                	required: true, 
                	alphanumeric: true 
                	},
                nexmo_api_secret: {
                	required: true, 
                	alphanumeric: true 
                },
                	nexmo_api_phno : {
                	required: true
                	}
            },
     messages: {
                  nexmo_api_key: {
                  	required: "Please enter the API Key.",
                  	alphanumeric: "Please give valid API Key."
                  	},
                  nexmo_api_secret: {
                  	required: "Please enter the Secret Key.",
                  	alphanumeric: "Please give valid Secret Key."
                  },
                  nexmo_api_phno: {
                  	required: "Please enter the Phone Number."
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
 <h1 class="page-header1"><?php echo translate_admin('Nexmo (Phone number verification) Settings'); ?></h1>
</div>
<form action="<?php echo admin_url('social/mobile_verification_settings'); ?>" method="post" id="form" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
 <tr>
  <td><?php echo translate_admin('Nexmo Application Key'); ?><span style="color: red;">*</span></td>
  <td><input type="text" name="nexmo_api_key" value="<?php if(isset($nexmo_api_key)) echo $nexmo_api_key; ?>"></td>
 </tr>
 <tr>
  <td><?php echo translate_admin('Nexmo Application Secret'); ?><span style="color: red;">*</span></td>
  <td><input type="text" size="55" name="nexmo_api_secret" value="<?php if(isset($nexmo_api_secret)) echo $nexmo_api_secret; ?>"></td>
 </tr>
  <tr>
  <td><?php echo translate_admin('Nexmo Registered Phone Number'); ?><span style="color: red;">*</span></td>
  <td><input type="text" size="55" name="nexmo_api_phno" value="<?php if(isset($nexmo_api_phno)) echo $nexmo_api_phno; ?>"></td>
 </tr>
 <tr>
  <td></td>
  <td><div class="clearfix">
    <input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
    <div>
    <div id="message"></div>
    </span> </div></td>
 </tr>
</table>
</form>
</div>

