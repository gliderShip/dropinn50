<script src="<?php echo base_url(); ?>js/jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo css_url(); ?>/jquery-ui.css" />
<script src="<?php echo base_url(); ?>js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
 	  <?php
	  	//Content of a group
		if(isset($coupon) and $coupon->num_rows()>0)
		{
			$coupon = $coupon->row();
	  ?>
	<?php $xdate = date('d/m/Y',$coupon->expirein); ?>
<script>
$(function() {
$( "#expirein" ).datepicker(
	{
	 minDate: "<?php echo $xdate ; ?>",
	 dateFormat: "dd/mm/yy",
                maxDate: "+2Y",
                nextText: "",
                prevText: "",
                numberOfMonths: 1,
                showButtonPanel: true
               }
);
});
</script>

<div class="Edit_Coupon_Page">
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
	 <h1 class="page-header1"><?php echo translate_admin('Edit Coupon'); ?></h1>
	 </div>
<form action="<?php echo admin_url('coupon/edit_coupon'); ?>/<?php echo $coupon->id;  ?>" method="post">	
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
	<tr> 
	  <td><?php echo translate_admin("Expire In"); ?><span style="color:#FF0000">*</span></td>
      <td><input class="clsCoupon" id="expirein" name="expirein" type="text" size="10" value="<?php echo date('d/m/Y',$coupon->expirein); ?>" readonly="readonly" />
       <p><?php echo form_error('expirein');?></p>
      </td> 
	 </tr>
	 <tr> 
		<td><?php echo translate_admin("Enter Coupon Price"); ?><span style="color:#FF0000">*</span></td>
		<td><input class="clsCoupon" id="coupon_price" name="coupon_price" type="text" size="10" value="<?php echo $coupon->coupon_price; ?>"/>
		<p><?php echo form_error('coupon_price');?></p>
		</td> 
	</tr>
	<tr> 
		<td><?php echo translate_admin('Currecny'); ?></td>
			<td><select id="currency" name="currency">
			<?php 
			if($currencies->num_rows() != 0)
			{
			foreach($currencies->result() as $currency) { ?>
			<option value="<?php echo $currency->currency_code; ?>" <?php if($currency->currency_code == $coupon->currency) echo " selected='selected'"; ?> ><?php echo $currency->currency_symbol." ".$currency->currency_code; ?></option>
			<?php }
			}
			else
				{?>
				<option value="USD"><?php echo '$'." ".'USD'; ?></option>	
				<?php }
			 ?>
			</select> </td>
	</tr>
	 <tr>
	 	<td><?php echo translate_admin('Coupon Code:'); ?></td>
	 	<td><input class="clsCoupon" id="coupon_code" name="coupon_code" type="text" size="10" value="<?php echo $coupon->couponcode; ?>" readonly></td>
	 </tr>
	 <tr>
     <td></td>
     <td> <input class="clsCoupon updatecop" id="codegen" type="submit" style="width:150px;" value="<?php echo translate_admin('Update');?>" name="submit" ></td>
     </tr>
  </table>
</form>
 <?php } ?>
 </div>