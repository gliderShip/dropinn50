<?php
	if($msg = $this->session->flashdata('flash_message'))
				{
					echo $msg;
				}
?>
		
				
<div id="Add_Email_Template">
 <?php
	  	//Content of a group
		if(isset($categories) and $categories->num_rows()>0)
		{
			$category = $categories->row();
	  ?>
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
				<h1 class="page-header3"><?php echo translate_admin("Add Neighbourhood Category"); ?></h1>
				<div class="but-set">
			 <span3><input type="submit" onclick="window.location='<?php echo admin_url('neighbourhoods/viewcategory'); ?>'" value="<?php echo translate_admin('View Category'); ?>"></span3>
          <?php /*?><h3><?php echo translate_admin("Manage Neighborhood Places"); ?></h3><?php */?>
		</div>
      </div>
	  <div>
<form method="post" action="<?php echo admin_url('neighbourhoods/editcategory')?>/<?php echo $category->id;  ?>">					
<div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table" cellpadding="2" cellspacing="0">
<tr>
  <td style="padding: 20px 0px 0px 0px;"><?php echo translate_admin('Category'); ?><span style="color: red;">*</span></td>
		<td style="padding: 20px 0px 0px 8px;">
				<input class="clsTextBox" size="30" type="text" name="category" id="category" value="<?php echo $category->category; ?>"/>
				<?php echo form_error('category'); ?>
		</td>
</tr>
<tr>
	
		<td><input type="hidden" name="id"  value="<?php echo $category->id; ?>"/></td>
	<td><input  name="submit" type="submit" value="<?php echo translate_admin('Submit'); ?>">
	</td>
</tr>
		
</table>
</form>	
 <?php
	  }
	  ?>
</div>
