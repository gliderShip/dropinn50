    <div class="Edit_help">
      <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}		
	  ?>
	  <?php
	  	//Content of a group
		if(isset($currency) and $currency->num_rows()>0)
		{
			$currency_data = $currency->row();
	  ?>

	 	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 		<h1 class="page-header1"><?php echo translate_admin('Edit Currency'); ?></h1></div>
			<form method="post" action="<?php echo admin_url('currency/editcurrency')?>/<?php echo $this->uri->segment(4);  ?>">
<div class="col-xs-9 col-md-9 col-sm-9">
   <table class="table" cellpadding="2" cellspacing="0">
			
	<tr>
		     <td><?php echo translate_admin('Currency Name'); ?><span style="color: red;">*</span></td>
		     <td><input type="text" name="currency_name" value="<?php echo $currency_data->currency_name; ?>"><p><?php echo form_error('currency_name'); ?></p></td>
		  </tr>
				
    <tr>
		     <td><?php echo translate_admin('Currency Code'); ?><span style="color: red;">*</span></td>
		   <td><input type="text" name="currency_code" value="<?php echo $currency_data->currency_code; ?>"><p><?php echo form_error('currency_code'); ?></p></td>

		  </tr>
		  
		   <tr>
		     <td><?php echo translate_admin('Currency Symbol'); ?><span style="color: red;">*</span></td>
		   <td><input type="text" name="currency_symbol" value="<?php echo htmlentities($currency_data->currency_symbol);?>"><p><?php echo form_error('currency_symbol'); ?></p></td>

		  </tr>
		  
		   <tr>
		     <td><?php echo translate_admin('Currency Value'); ?><span style="color: red;">*</span></td>
		   <td><input type="text" name="currency_value" value="<?php echo $currency_data->currency_value; ?>"><p><?php echo form_error('currency_value'); ?></p></td>

		  </tr>
				
				<tr>

		     <td><?php echo translate_admin('Status'); ?></td>
		     <td>
							<select style="width:200px;" class="fixed-width" id="status" name="status" value="">
							<option value="0">In Active</option>
							<option value="1">Active</option>
							</select> 
							</td>
		  </tr>
		  
		  <tr>

		     <td><?php echo translate_admin('Default'); ?></td>
		     <td>
							<select style="width:200px;" class="fixed-width" id="default" name="default" value="">
							<option value="0">No</option>
							<option value="1">Yes</option>
							</select><p><?php echo form_error('default'); ?></p>
							</td>
		  </tr>
	  
    <tr>
				<td></td>
				<td>
		  <input type="hidden" name="help_operation" value="edit" />
		  <input type="hidden" name="id"  value="<?php echo $currency_data->id; ?>"/>
    <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>"  name="editcurrency"/></td>
	  	</tr>  
        
	  </table>
	</form>
	  <?php
	  }
	  ?>
    </div>
<script language="Javascript">
jQuery("#status").val('<?php echo $currency_data->status; ?>');
jQuery("#default").val('<?php echo $currency_data->default; ?>');
</script>