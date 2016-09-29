    <div class="add_Page">
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
<h1 class="page-header1"><?php echo translate_admin('Add Language'); ?></h1></div>
			<form method="post" action="<?php echo admin_url('language/add_language')?>">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
		  		<tr>
					<td><?php echo translate_admin('Language Name'); ?><span style="color: red;">*</span></td>
					<td>
					<input class="" type="text" name="name" value="<?php echo set_value('name');?>">
					<p><?php echo form_error('name'); ?></p>
					</td>
				</tr>				
   <tr>
				<td><?php echo translate_admin('Language Code'); ?><span style="color: red;">*</span></td>
				<td>
					<input class="" type="text" name="language_code" value="<?php echo set_value('language_code');?>">
				 <p><?php echo form_error('language_code'); ?></p>
				</td>
			</tr>
		
			<tr>
				<td><?php echo translate_admin('Status'); ?><span>?</span></td>
				<td>
				<select name="status">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
				</td>
			</tr>
    <tr>
				<td></td>
				<td>
    <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>"  name="add_language"/>
     <input type="submit" class="can-but" value="<?php echo translate_admin('Cancel'); ?>"  name="cancel"/></td>
	  	</tr>  
        
	  </table>
	</form>
    </div>