    <div class="View_Addhelp">

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
    	<h1 class="page-header1"><?php echo translate_admin('Add Currency'); ?></h1></div>
			<form method="post" action="<?php echo admin_url('currency/addcurrency')?>">
	<div class="col-xs-9 col-md-9 col-sm-9">
	  <table class="table" cellpadding="2" cellspacing="0">
      
		 <!-- <tr>
		     <td class="clsName"><?php echo translate_admin('help Name'); ?><span class="clsRed">*</span></td>
		     <td class="clsMailID"><input type="text" name="help_name" value="<?php echo set_value('help_name'); ?>"><?php echo form_error('help_name'); ?></td>
		 </tr>-->
				
    <tr>
		     <td><?php echo translate_admin('Currency Name'); ?><span style="color: red;">*</span></td>
		     <td><input type="text" name="currency_name" value="<?php echo set_value('currency_name'); ?>"><p><?php echo form_error('currency_name'); ?></p></td>
		  </tr>
				
    <tr>
		     <td><?php echo translate_admin('Currency Code'); ?><span style="color: red;">*</span></td>
		   <td><input type="text" name="currency_code" value="<?php echo set_value('currency_code'); ?>"><p><?php echo form_error('currency_code'); ?></p></td>

		  </tr>
		  
		   <tr>
		     <td><?php echo translate_admin('Currency Symbol'); ?><span style="color: red;">*</span></td>
		   <td><input type="text" name="currency_symbol" value="<?php echo set_value('currency_symbol'); ?>"><p><?php echo form_error('currency_symbol'); ?></p></td>

		  </tr>
		  
		   <tr>
		     <td><?php echo translate_admin('Currency Value'); ?><span style="color: red;">*</span></td>
		   <td><input type="text" name="currency_value" value="<?php echo set_value('currency_value'); ?>"><p><?php echo form_error('currency_value'); ?></p></td>

		  </tr>
				
				<tr>

		     <td><?php echo translate_admin('Status'); ?></td>
		     <td>
							<select style="width:200px;" class="fixed-width" id="status" name="status" value="<?php echo set_value('status'); ?>">
							<option value="0">In Active</option>
							<option value="1">Active</option>
							</select> 
							</td>
		  </tr>
		  
		  <tr>

		     <td><?php echo translate_admin('Default'); ?></td>
		     <td>
							<select style="width:200px;" class="fixed-width" id="status" name="default" value="<?php echo set_value('default'); ?>">
							<option value="0">No</option>
							<option value="1">Yes</option>
							</select><p><?php echo form_error('default'); ?></p>
							</td>
		  </tr>
				
	     <tr>
						<td></td>    
						<td>
						<input type="hidden" name="help_operation" value="add"  />
						<input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addcurrency" type="submit">
						</td>
						</tr>
						
         </table>
								 </form>
    </div>
