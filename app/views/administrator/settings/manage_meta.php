<script type="text/javascript">
		function startCallback() {
		$("#message").html('<img src="<?php echo base_url().'images/loading.gif' ?>">');
		// make something useful before submit (onStart)
		return true;
	}

	function completeCallback(response) {
		$('#message').show();
	 $("#message").html(response);
		$("#message").delay(1800).fadeOut('slow');
	}
</script>

<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Home page Meta'); ?></h1>
	 </div>
		
<form action="<?php echo admin_url('settings/home_meta_settings'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
	
<tr>
			<td><?php echo translate_admin('Meta Keyword'); ?><span style="color: red;">*</span></td>
			<td>
			<textarea name="meta_keyword" style="width:400px; height:40px" rows="2" cols="60" class="text_area"><?php if(isset($meta_keyword)) echo $meta_keyword; ?></textarea>
			</td>
</tr>		

<tr>
			<td><?php echo translate_admin('Meta Description'); ?><span style="color: red;">*</span></td>
			<td>
			<textarea name="meta_description" style="width:400px; height:40px" rows="2" cols="60" class="text_area"><?php if(isset($meta_description)) echo $meta_description; ?></textarea>
			</td>
</tr>			

<tr>
		<td></td>
		<td>
		<div class="clearfix">
		<input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
		<p><div id="message"></div></p>
		</div>
		</td>
</tr>

</table>

<?php echo form_close(); ?>

</div>
