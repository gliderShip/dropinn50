 <?php
	 
	 if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
?>
			
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Add Property type'); ?></h1>
	 </div>
	
	 
<form action="<?php echo admin_url('property_type/addproperties'); ?>" method="post">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
<td><?php echo translate_admin("Additional Property type"); ?><span style="color:#FF0000">*</span></td>
<td><input type="text" name="addproperty" value=""></td>
</tr>


<tr>
<td></td>
<td>

<input type="submit" name="update_price" value="<?php echo translate_admin("Add"); ?>" style="width:90px;" />


</td>
</tr>


</table> 
</form>

