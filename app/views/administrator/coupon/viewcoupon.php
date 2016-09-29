<script src="<?php echo base_url(); ?>js/jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo css_url(); ?>/jquery-ui.css" />
<script src="<?php echo base_url(); ?>js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
<?php 
  		$currency_code = $this->Common_model->getTableData('currency',array('status'=>1,'default'=>1))->row()->currency_code;
		$id = $this->Common_model->getTableData('list')->row()->id;
		$min_value = get_currency_value1($id,9);
		?>
<script>
$(function() {
$( "#expirein" ).datepicker(
	{
	 minDate: new Date(),
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
<script>
$(document).ready(function(){
$("#gencode").val("");	
	
	var currency_converter = function(from,to,amount)
{
	jQuery.ajax({
   	url: '<?php echo base_url().'rooms/currency_converter'?>',
   	type : 'POST',
   	data : { from: from, to: to, amount:amount },
   	success : function(price)
   	{
   		setTimeout(function()
   		{
   		jQuery('#currency_hidden').val(price);
   		jQuery('#gen_code_price_valid').text('Please give the above or equal to '+to+' '+price+'.');
   		},100);
   	}
  })
}

currency_converter('USD',$('#currency').val(),10);

$("#codegen").click(function() {
	
//currency_converter('USD',$('#currency').val(),10);
from = 'usd';
to = $('#currency').val();
amount = 10;

jQuery.ajax({
   	url: '<?php echo base_url().'rooms/currency_converter'?>',
   	type : 'POST',
   	data : { from: from, to: to, amount:amount },
   	success : function(price)
   	{
   		
   		jQuery('#currency_hidden').val(price);
   		jQuery('#gen_code_price_valid').text('Please give the above or equal to '+to+' '+price+'.');
   		
   		$('.help').hide();
		
		if($('#expirein').val() == '' || $('#coupon_price').val() == '')
		{
		$('#gen_code_price_valid').hide();
		$('#gen_code_valid').show();
		$('#gen_code_char_valid').hide();
		$('#gen_code_td span').hide();
		}
		else if(isNaN($('#coupon_price').val()))
		{
		$('#gen_code_valid').hide();
		$('#gen_code_price_valid').hide();
		$('#gen_code_char_valid').show();
		$('#gen_code_td span').hide();
		$("#gencode").val("");
		}
		else if($('#coupon_price').val() < parseInt($('#currency_hidden').val()))
		{
		$('#gen_code_valid').hide();
		$('#gen_code_char_valid').hide();
		$('#gen_code_price_valid').show();
		$('#gen_code_td span').hide();
		$("#gencode").val("");
		}
		else
		{
		$('#gen_code_valid').hide();
		$('#gen_code_price_valid').hide();
    	$("#gencode").val("<?php echo uniqid();?>");
		}
   	}
  });

});

});
</script>

    <div id="Viewcoupon">
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
	 <h1 class="page-header3"><?php echo translate_admin('Generate Coupon Code'); ?></h1>
		<div class="but-set">
			<span3><input type="submit" class="view_coupon_bg" onclick="window.location='<?php echo admin_url('coupon/view_all_coupon')?>'" value="<?php echo translate_admin('View Coupon'); ?>"></span3> 
	 </div>
<form action="<?php echo admin_url('coupon/add_coupon'); ?>" method="post">	
	<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table1" cellpadding="2" cellspacing="0">
	<tr> 
			<td><?php echo translate_admin("Expire In"); ?>:<span style="color:#FF0000">*</span></td>
			<td><input class="clsCoupon" id="expirein" name="expirein" type="text" size="10" value="<?php echo set_value('expirein'); ?>" readonly="readonly" />
			<?php echo form_error('expirein');?>
			</td> 
	</tr>
	<tr> 
		<td><?php echo translate_admin("Enter Coupon Price"); ?>:<span style="color:#FF0000">*</span></td>
		<td><input class="clsCoupon" id="coupon_price" name="coupon_price" type="text" size="10" value="<?php echo set_value('coupon_price'); ?>"/>
		<?php echo form_error('coupon_price');?>
		</td> 
	</tr>
	<tr> 
		<td><?php echo translate_admin('Currecny'); ?></td>
			<td><select id="currency" name="currency">
			<?php 
			if($currencies->num_rows() != 0)
			{
			foreach($currencies->result() as $currency) { ?>
			<option value="<?php echo $currency->currency_code; ?>" <?php if($currency->default==1) echo " selected='selected'"; ?> ><?php echo $currency->currency_symbol." ".$currency->currency_code; ?></option>
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
	 	<td><?php echo translate_admin('Coupon Code'); ?>:<span style="color:#FF0000">*</span></td>
	 	<td id="gen_code_td"><div><input type="text" name="gencode" id="gencode" value="<?php echo set_value('gencode'); ?>" />
 	   <input class="clsCoupon" id="codegen" type="button" style="width:150px;" value="<?php echo translate_admin('Generate Code'); ?>" name="codegen" >
  		<?php echo form_error('gencode');?>
  		<label id="gen_code_valid" class="help" style="display:none">Please fill all above fields.</label> 
  		<label id="gen_code_char_valid" class="help" style="display:none">Please give valid price amount.</label> 
  		<label id="gen_code_price_valid" class="help" style="display:none">Please give the above or equal to <?php echo $currency_code.' '.$min_value;?>.</label> 
	 	</td>
	 	<input type="hidden" id="currency_hidden" value="">
	 </tr>
     <tr>
     <td></td>
     <td><input class="clsCoupon"  type="submit" style="width:150px;" value="<?php echo translate_admin('submit'); ?>" name="submit" ></td>
     </tr>
  </table>
   
</form>
</div>