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
                gmap_api_key: { 
                	required: true, 
                	 
                	},
                	 gmap_client_id: { 
                	required: true, 
                	 
                	},
            },
     messages: {
                  gmap_api_key: {
                  	required: "Please enter the API key.",
                  	
                  }, gmap_client_id: {
                  	required: "Please enter the Client ID.",
                  	
                  	}
               }

});

jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9-.]*$/.test(value);
}, "Letters, numbers, and underscores only please");

});
</script>


    <div id="google_settings">

<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Google API Settings'); ?></h1>
	 </div>
		
<form action="<?php echo admin_url('social/google_settings'); ?>" method="post" id="form" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
			<td><?php echo translate_admin('Google API Key'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" name="gmap_api_key" size="77" value="<?php if(isset($gmap_api_key)) echo $gmap_api_key; ?>"></td>
</tr>		
<tr>
			<td class="clsName"><?php echo translate_admin('Google Client ID'); ?><span class="clsRed">*</span></td>
			<td> <input type="text" name="gmap_client_id" size="77" value="<?php if(isset($gmap_client_id)) echo $gmap_client_id; ?>"></td>
</tr>		

<tr>
		<td></td>
		<td>
		<div class="clearfix">
		<input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
		<div><div id="message"></div></div>
		</div>
		</td>
</tr>

</table>

<?php echo form_close(); ?>

</div>
