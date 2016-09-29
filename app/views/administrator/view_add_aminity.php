 <?php
	 
	 if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
?>
<style>
td p{
	color: red;
}
</style>			
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Add Amenity'); ?></h1>
	 </div>
	
	 
<form action="<?php echo admin_url('lists/addamenities'); ?>" method="post">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
<td><?php echo translate_admin("Additional Amenity"); ?><span style="color: red;">*</span></td>
<td><input type="text" name="addaminitie" value="<?php echo set_value('addaminitie');?>"><?php echo form_error('addaminitie');?></td>
</tr>

<tr>
<td><?php echo translate_admin("Amenity Description"); ?><span style="color: red;">*</span></td>
<td><input type="text" name="desc_aminitie" value="<?php echo set_value('desc_aminitie');?>"><?php echo form_error('desc_aminitie');?></td>
</tr>
 

<tr>
<td></td>
<td>

<input class="clsSubmitBt1" type="submit" name="update_price" value="<?php echo translate_admin("Add"); ?>" style="width:90px;" />


</td>
</tr>


</table> 
</form>
