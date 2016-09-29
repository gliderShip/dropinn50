<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script>
	$(document).ready(function()
{
	$("#form").validate({
     rules: {
                media: { 
                	required: true
                }
            },
     messages: {
                  media: {
                  	required: "This field is required"
                  	  }
                  }

});

});
</script>

<div id="How_It_Works">			
  <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	 ?>
			
		<div class="clsTitle">
	 <h3><?php echo translate_admin('Manage Banner Video'); ?></h3>
	 </div>

<form name="howit" id="form" action="<?php echo admin_url('settings/banner');?>" method="post" enctype="multipart/form-data">	

<table class="table" cellpadding="2" cellspacing="0">

<tr id="media">
			<td class="clsName"><?php echo translate_admin('Video'); ?><span class="clsRed">*</span></td>
			<td> 
			<p> <input type="file" id="media" name="media" value="<?php //echo $video_url;?>" size=50 /> &nbsp;
			</td>
</tr>	
<!--<tr id="picture">
			<td class="clsName"><?php echo translate_admin('Picture'); ?><span class="clsRed"></span></td>
			<td> 
			<p> <input type="file" name="picture"> &nbsp;
			</td>
</tr>
<tr>
	<td></td>
	<td>
		<img src="<?php echo base_url().'images/'.$video_image_url;?>" width="200" height="100">
		</td>
</tr>-->
<tr>
		<td></td>
		<td>
		<div class="clearfix">
		<span style="float:left; margin:0 10px 0 0;" id="upload_embed_btn">
		<input class="clsSubmitBt1" id="" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
		</span>
		</div>
		</td>
</tr>

</table>

</form>

</div>
