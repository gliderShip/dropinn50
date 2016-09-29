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
	</script>

<div id="footer_link">
<div class="clsTitle">
 <h3><?php echo translate_admin('Footer Social Link'); ?></h3>
</div>
<form action="<?php echo admin_url('social/footer_link'); ?>" method="post" id="form" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
<table class="table" cellpadding="2" cellspacing="0">
 <tr>
  <td class="clsName"><?php echo translate_admin('Twiter Link'); ?></td>
  <td><input type="text" size="55" name="twitter" value="<?php if(isset($twitter)) echo $twitter; ?>"></td>
 </tr>
 <tr>
  <td class="clsName"><?php echo translate_admin('Face Book Link'); ?></td>
  <td><input type="text" size="55" name="facebook" value="<?php if(isset($facebook)) echo $facebook; ?>"></td>
 </tr>
 <tr>
  <td class="clsName"><?php echo translate_admin('Google Link'); ?></td>
  <td><input type="text" size="55" name="google" value="<?php if(isset($google)) echo $google; ?>"></td>
 </tr>
 <tr>
  <td class="clsName"><?php echo translate_admin('YouTube Link'); ?></td>
  <td><input type="text" size="55" name="youtube" value="<?php if(isset($youtube)) echo $youtube; ?>"></td>
 </tr>
 <tr>
  <td></td>
  <td><div class="clearfix"> <span style="float:left; margin:0 10px 0 0;">
    <input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
    </span> <div>
    <div id="message"></div>
    </div> </div></td>
 </tr>
</table>
</form>
</div>

