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
	</script>
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	<h1 class="page-header1">Backup</h1>
</div>
	<div class="col-xs-9 col-md-9 col-sm-9">
<h1 class="page-header1"><?php echo '<br><br>'.translate_admin('MySQL Backup');?></h1>
</div>
<form action="<?php echo admin_url('backup/mysql_backup'); ?>" method="post" id="form" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
	<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">

 <tr>
  <td class="clsName"><?php echo translate_admin('Cron URL'); ?><span class="clsRed"></span></td>
  <td><input type="text" size="55" name="fb_api_id" value="<?php echo base_url().'cron/mysql_backup'?>" readonly></td>
 </tr>
  <tr>
  <td class="clsName"><?php echo translate_admin('Backup file path'); ?><span class="clsRed"></span></td>
  <td><input type="text" size="55" name="fb_api_id" value="<?php echo dirname($_SERVER['SCRIPT_FILENAME']).'/backup'?>" readonly></td>
 </tr>
 <tr>
  <td class="clsName"><?php echo translate_admin('Manual Running'); ?><span class="clsRed"></span></td>
  <td><input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Run'); ?>" style="width:90px;" /></td>
 </tr>
</table>
</form>