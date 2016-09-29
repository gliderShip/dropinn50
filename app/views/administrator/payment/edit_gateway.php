<style>
	.table{
		margin:0px; 
	}
	tbody td:first-child { width: 150px; }
</style>
    <div id="Edit_Gateway">
    
    <div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header3"><?php echo translate_admin('Edit Payment GateWay'); ?></h1>
	 <div class="but-set">
	 <span3><input type="submit" onclick="window.location='<?php echo admin_url('payment/manage_gateway'); ?>'" value="<?php echo translate_admin('View All'); ?>"></span3>
	 </div>
	 </div>
   	 
  <?php 
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	 ?>
		
		<?php echo form_open('administrator/payment/manage_gateway'); ?>
	  <div class="col-xs-9 col-md-9 col-sm-9">
	 <table class="table">
	  	<tr>
       <td><?php echo trim(translate_admin('The Pay Gateway')); ?><span style="color: red;">*</span></td>
							<td>
						<!--	<select name="name_gate" class="usertype" id="name_gate" disabled="disabled">
							<option value=""> <?php echo translate_admin('Select'); ?> </option>
							<?php foreach($payments as $row) { ?>
							<option value="<?php echo $row->id; ?>" <?php if($row->id == $payId) echo 'selected="selected"'; ?>> <?php echo $row->payment_name; ?> </option>
       <?php } ?>
							</select> -->
							<?php foreach($payments as $row) { 
								if($row->id == $payId) {
								?>
							<input  size="60" type="text" value="<?php  echo $row->payment_name; ?>" readonly>
							<?php } 
							}?>
       <?php echo form_error('name_gate'); ?>
							</td>
				</tr>
				
				
				<tr>
       <td><?php echo translate_admin('Is Active?'); ?><span style="color: red;">*</span></td>
							<td>
							<select name="is_active" id="is_active" >
							<option value="0"> No </option>
							<option value="1"> Yes </option>
							</select> 
							</td>
				</tr>
				
				<?php
				if($result->id == 1)
				{ $showPE = 'block'; $showP = 'none'; $show2C = 'inline-table'; }
				else if($result->id == 2)
				{ $showPE = 'none'; $showP = 'inline-table'; $show2C = 'none'; }
				else if($result->id == 3)
				{ $showPE = 'none'; $showP = 'none'; $show2C = 'inline-table'; }
				?>	
				
				
				<table id="payment_express" class="table" style="display: <?php echo $showP;?>">
	  	<tr>
       <td><?php echo translate_admin('Paypal API Username'); ?><span style="color: red;">*</span></td>
							<td>
         <input type="text" size="70" name="pe_user" value="<?php echo $pe_user; ?>">
							</td>
				</tr>
				
					<tr>
       <td><?php echo translate_admin('Paypal API Password'); ?><span style="color: red;">*</span></td>
							<td>
         <input type="text" size="70" name="pe_password" value="<?php echo $pe_password; ?>">
							</td>
				</tr>
				
					<tr>
       <td><?php echo translate_admin('Paypal API Key'); ?><span style="color: red;">*</span></td>
							<td>
         <input type="text" size="70" name="pe_key" value="<?php echo $pe_key; ?>">
							</td>
				</tr>
				
				<!--<tr>
       <td class="clsName"><?php echo translate_admin('Is Live'); ?><span class="clsRed">*</span></td>
							<td>
								<select name="is_live" id="is_live" >
								<option value="1"> Yes (Paypal Live)</option>
								<option value="0"> No (Paypal Sandbox) </option>
								</select>
							</td>
				</tr>-->
				
				
				<!--<table id="paypal" class="table" style="display:<?php echo $showP; ?>;">-->
	  	<tr>
       <td><?php echo translate_admin('Paypal Email Id'); ?><span style="color: red;">*</span></td>
							<td>
         <input type="text" size="70" name="paypal_id" value="<?php echo $paypal_id; ?>">
							</td>
				</tr>
				
					<tr>
       <td><?php echo translate_admin('Payment URL'); ?><span style="color: red;">*</span></td>
							<td>
								<select name="paypal_url" id="paypal_url" >
								<option value="1"> Yes (Paypal Live)</option>
								<option value="0"> No (Paypal Sandbox) </option>
								</select>
							</td>
				</tr>
				</table>
				
				<table id="2checkout" class="table" style="display:<?php echo $show2C; ?>;">
	  	<tr>
       <td><?php echo translate_admin('Merchant ID'); ?><span style="color: red;">*</span></td>
							<td>
         <input type="text" size="70" name="merchantId" value="<?php echo $merchantId; ?>">
							</td>
				</tr>
				
					<tr>
       <td><?php echo translate_admin('Public Key'); ?><span style="color: red;">*</span></td>
							<td>
         <input type="text" size="70" name="publicKey" value="<?php echo $publicKey; ?>">
							</td>
				</tr>
				
				<tr>
       <td><?php echo translate_admin('Private Key'); ?><span style="color: red;">*</span></td>
							<td>
         <input type="text" size="70" name="privateKey" value="<?php echo $privateKey; ?>">
							</td>
				</tr>
				
				<tr>
       <td><?php echo translate_admin('Payment Type'); ?><span style="color: red;">*</span></td>
							<td>
								<select name="type" id="type" >
								<option value="1" <?php if($result->is_live == 1) echo 'selected'; ?>> Yes (Braintree Live)</option>
								<option value="0" <?php if($result->is_live == 0) echo 'selected'; ?>> No (Braintree sandbox) </option>
								</select>
							</td>
				</tr>
				
				</table>
				
				<tr>
						<td>&#160;</td>
						<td>
						<input type="hidden" name="payId" value="<?php echo $payId; ?>" />
						<input class="col-md-offset-4 clsSubmitBt1" value="<?php echo translate_admin('Update'); ?>" name="update" type="submit" >
						</td>
				</tr>
		
		</table>	
		<?php echo form_close(); ?>
		
    </div>
<script language="Javascript">
jQuery("#is_live").val('<?php echo $result->is_live; ?>');
jQuery("#is_active").val('<?php echo $result->is_enabled; ?>');
jQuery("#paypal_url").val('<?php echo $result->is_live; ?>');
</script>