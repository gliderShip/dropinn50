<script type="text/javascript">
		function startCallback()
		{
		$("#message").html('<img src="<?php echo base_url().'images/loading.gif' ?>">');
		// make something useful before submit (onStart)
		return true;
	 }

	function completeCallback(response)
	{
	if(response.length > 500 )
{
window.location.href = "<?php echo base_url().'administrator/settings';?>";
}
/*
else{
				var res = response;
				var getSplit = res.split('#'); 
				$('#message').show();
				$("#img_logo").html(getSplit[0]);
				$("#message").html(getSplit[1]);
				$("#message").delay(1800).fadeOut('slow');
			}*/

	}
</script>

<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Global Settings'); ?></h1>
	 </div>
		
<form action="<?php echo admin_url('settings'); ?>" method="post" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
	
<tr>
			<td><?php echo translate_admin('Site Title'); ?></td>
			<td> <input type="text" size="55" name="site_title" value="<?php if(isset($site_title)) echo $site_title; ?>"></td>
</tr>		

<tr>
			<td><?php echo translate_admin('Site Slogan'); ?></td>
			<td> <input type="text" size="55" name="site_slogan" value="<?php if(isset($site_slogan)) echo $site_slogan; ?>"></td>
</tr>		

<tr>
			<td><?php echo translate_admin('Super Admin'); ?></td>
			<td> <input type="text" size="55" name="super_admin" value="<?php if(isset($super_admin)) echo $super_admin; ?>"></td>
</tr>		

<tr>
			<td><?php echo translate_admin('Site Offline'); ?></td>
<td>
	<input type="radio" <?php if($site_status == 0) echo 'checked="checked"'; ?> value="0" name="offline">
 No
	<input type="radio" <?php if($site_status == 1) echo 'checked="checked"'; ?> value="1" name="offline">
	Yes
</td>
</tr>

<tr>
			<td><?php echo translate_admin('Offline Message'); ?></td>
<td>
<textarea name="offline_message" style="width:400px; height:40px" rows="2" cols="60" class="text_area"><?php if(isset($offline_message)) echo $offline_message; ?></textarea>
</td>
</tr>

<tr>
			<td><?php echo translate_admin('Google Analytics Code'); ?></td>
<td>
<textarea name="google_analytics" style="width:400px; height:100px" rows="10" cols="60" class="text_area"><?php if(isset($google_analytics)) echo $google_analytics; ?></textarea>
</td>
</tr>

<tr>
			<td><?php echo translate_admin('Currecny Settings'); ?></td>
			<td><select id="currency" name="currency">
			<?php 
			if($currencies->num_rows() != 0)
			{
			foreach($currencies->result() as $currency) { ?>
			<option value="<?php echo $currency->currency_code; ?>" <?php if($currency->default==1) echo " selected='selected'"; ?> ><?php echo $currency->currency_symbol." ".$currency->currency_code; ?></option>
			<?php }
			}
			else
				{?>
				<option value="USD"><?php echo '$'." ".'USD'; ?></option>	
				<?php }
			 ?>
			</select> </td>
</tr>


<tr>
			<td><?php echo translate_admin('Change Logo'); ?></td>
<td>
<input id="new_photo_image" name="logo"  size="24" type="file" />
<p id="img_logo"> <img src="<?php echo $logo; ?>" alt="logo"></p>
</td>
</tr>

<!-- favicon image 1 start -->

<tr>
			<td class="clsName"><?php echo translate_admin('Favicon Image'); ?></td>
<td>
<input id="favicon_image" name="favicon"  size="24" type="file" />
<p id="img_logo"> <img src="<?php echo $favicon; ?>" alt="logo"></p>
</td>
</tr>

<!-- favicon image 1 end -->

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