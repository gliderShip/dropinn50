<?php
	if($msg = $this->session->flashdata('flash_message'))
				{
					echo $msg;
				}
?>

<script type="text/javascript">
		function startCallback() {
		$("#message").html('<img src="<?php echo base_url().'images/loading.gif' ?>">');
		// make something useful before submit (onStart)
		return true;
	}

	/*function completeCallback(response) {
		$('#message').show();
		$("#message").html(response);
		$("#message").delay(1800).fadeOut('slow');
	}*/
</script>

    <div id="View_Contact_Info">
	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header"><?php echo translate_admin('Manage Contact Info'); ?></h1>
	 </div>
		
	<form action="<?php echo admin_url('contact'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
	
<tr>
			<td><?php echo translate_admin('Phone'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="45" name="phone" value="<?php if(isset($row->phone)) echo $row->phone; ?>">
			<?php echo form_error('phone'); ?></td>
</tr>		

<tr>
			<td><?php echo translate_admin('E-Mail'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="45" name="email" value="<?php if(isset($row->email)) echo $row->email; ?>">
			<?php echo form_error('email'); ?></td>
</tr>

<tr>
			<td><?php echo translate_admin('Name / Company Name'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="45" name="name" value="<?php if(isset($row->name)) echo $row->name; ?>">
			<?php echo form_error('name'); ?></td>
</tr>		

<tr>
			<td><?php echo translate_admin('Street Address'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="45" name="street" value="<?php if(isset($row->street)) echo $row->street; ?>">
			<?php echo form_error('street'); ?></td>
</tr>	

<tr>
			<td><?php echo translate_admin('City'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="45" name="city" value="<?php if(isset($row->city)) echo $row->city; ?>">
			<?php echo form_error('city'); ?></td>
</tr>		

<tr>
			<td><?php echo translate_admin('State'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="45" name="state" value="<?php if(isset($row->state)) echo $row->state; ?>">
			<?php echo form_error('state'); ?></td>
</tr>	

<tr>
			<td><?php echo translate_admin('Country'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="45" name="country" value="<?php if(isset($row->country)) echo $row->country; ?>">
				<?php echo form_error('country'); ?>
			</td>
			
</tr>

<tr>
			<td><?php echo translate_admin('Pincode'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="45" name="pincode" value="<?php if(($row->pincode!='0')) echo $row->pincode; ?>">
				<?php echo form_error('pincode'); ?>
			</td>
            
</tr>						

<tr>
		<td></td>
		<td>
		<div class="clearfix">
		<input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
		<div><div id="message"></div></div>
		</div>
		</td>
</tr>

</table>

<?php echo form_close(); ?>

</div>
