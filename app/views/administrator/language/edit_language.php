    <div class="Edit_Page">
      <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}		
	  ?>
	  <?php
	  	//Content of a group
		if(isset($languages) and $languages->num_rows()>0)
		{
			$language = $languages->row();
	  ?>
	 	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 		<h1 class="page-header1"><?php echo translate_admin('Edit Language'); ?></h1></div>
			<form method="post" id="edit_language"  action="<?php echo admin_url('language/edit_language')?>/<?php echo $language->id;  ?>">
<div class="col-xs-9 col-md-9 col-sm-9">
   <table class="table" cellpadding="2" cellspacing="0">
			
		  		<tr>
					<td><?php echo translate_admin('Language Name'); ?><span style="color: red;">*</span></td>
					<td>
					<input class="" type="text" name="name" value="<?php echo $language->name; ?>">
					<?php echo form_error('name'); ?>
					</td>
				</tr>				
   <tr>
				<td><?php echo translate_admin('Language Code'); ?><span style="color: red;">*</span></td>
				<td>
					<input class="" type="text" name="language_code" value="<?php echo $language->code; ?>" readonly>
				 <?php echo form_error('language_code'); ?>
				</td>
			</tr>
			<tr>
				<td><?php echo translate_admin('Status'); ?><span style="color: red;">?</span></td>
				<td>
				<select name="status">
					<option value="1" <?php if($language->status == 1) echo 'selected';?>>Yes</option>
					<option value="0" <?php if($language->status == 0) echo 'selected';?>>No</option>
				</select>
				</td>
			</tr>	  
    <tr>
				<td></td>
				<td>
    <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" id="submit_edit"  name="edit_language"/>
     <input type="submit" class="can-but" value="<?php echo translate_admin('Cancel'); ?>"  name="cancel"/></td>
	  	</tr>  
        
	  </table>
	</form>
	  <?php
	  }
	  ?>
    </div>