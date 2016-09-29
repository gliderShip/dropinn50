

				
  <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	 ?>
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Manage How It Works'); ?></h1>
	 </div>

<form name="howit" action="" method="post" enctype="multipart/form-data">	
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
	
<tr>
			<td><?php echo translate_admin('Display_Type'); ?><span style="color: red;">*</span></td>
			<td> 
			<select id="display_type" name="display_type" onChange="javascript:showhide(this.value);">
			   <option value="0"><?php echo translate_admin('Video Type'); ?></option>
      <option value="1"><?php echo translate_admin('Embed Type'); ?></option>
			</select>
			</td>
</tr>

<?php
if($display_type == 0)
{
$showM = 'table-row';
$showE = 'none';
}
else
{
$showM = 'none';
$showE = 'table-row';
}

?>	

<tr id="media" style="display:<?php echo $showM; ?>;">
			<td><?php echo translate_admin('Video'); ?><span style="color: red;">*</span></td>
			<td> 
			<p> <input type="file" name="media"> &nbsp;
			<span class="video"><a class="video" href="<?php echo site_url('info/how_it_works'); ?>" target="_blank"><?php echo translate_admin('Click_Here'); ?></a> <?php echo translate_admin('to see the current video'); ?>.</span>
			</td>
</tr>	

<tr id="embed" style="display:<?php echo $showE; ?>;">
			<td><?php echo translate_admin('Embed_Code'); ?><span class="clsRed">*</span></td>
			<td> 
			<textarea class="text_area" cols="60" rows="5" name="embed_code"><?php if(isset($embed_code)) echo $embed_code; ?></textarea>
			<div style="color:red;" >
			<?php echo form_error('embed_code'); ?>
			</div>
			</td>
			
</tr>			

<tr>
		<td></td>
		<td>
		<div class="clearfix">
		<!--<span style="float:left; margin:0 10px 0 0;display: none;" id="upload_embed_btn">-->
		<input class="can-but" id="" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
		<!--</span>-->
		<!--<span style="float:left; margin:0 10px 0 0;display: none;" id="upload_image_btn">-->
<!--		<input class="can-but" id="image_upload" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />-->
		<!--</span>
		<span style="float:left; margin:0 10px 0 0;display: none;" id="submit_dis">-->
	    <!--<input class="can-but" type="submit" name="update_photo" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" disabled/>-->
        <!--</span>-->	
		</div>
		</td>
</tr>

</table>

</form>

</div>


<script language="Javascript">
jQuery("#display_type").val('<?php echo $display_type; ?>');
$("#display_type").prop("enabled", true);

$('#image_upload').click(function()
{
	$('#submit_dis').show();
	$('#image_upload').hide();
	$("#display_type").prop("disabled", true);
})
var id = '<?php echo $display_type; ?>';
if(id == 0)
	{
	document.getElementById("media").style.display            = "table-row";
	document.getElementById("embed").style.display            = "none";
	$('#upload_image_btn').show();
	}
	else
	{ 
	document.getElementById("media").style.display             = "none";
	document.getElementById("embed").style.display             = "table-row";	
	$('#upload_embed_btn').show();
	}

function showhide(id)
{
	if(id == 0)
	{
	document.getElementById("media").style.display            = "table-row";
	document.getElementById("embed").style.display            = "none";
	$('#upload_embed_btn').hide();
	$('#upload_image_btn').show();
	}
	else
	{ 
	document.getElementById("media").style.display             = "none";
	document.getElementById("embed").style.display             = "table-row";	
	$('#upload_embed_btn').show();
	$('#upload_image_btn').hide();
	}

}
</script>