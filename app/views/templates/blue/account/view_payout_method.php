	<style>
@media screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "Method"; }
	.res_table td:nth-of-type(2):before { content: "Arrives On*" ; }
	.res_table td:nth-of-type(3):before { content: "Fees "; }
		.res_table td:nth-of-type(4):before { content: "Notes "; }
}
</style>
		<p class="padng_top_btm"><?php echo translate("We can send money to users in"); ?> <b><?php echo $country; ?></b> <?php echo translate("as follows:"); ?></p>
		<table id="payout_method_descriptions" class="clsTable_View res_table" cellpadding="5" cellspacing="0" width="100%">
			<thead>		<tr>
														<th><?php echo translate("Method"); ?></th>
														<th><?php echo translate("Arrives On*"); ?></th>
														<th><?php echo translate("Fees"); ?></th>
														<th ><?php echo translate("Notes"); ?></th>
											</tr></thead>
												
						<tbody>
								<?php foreach($result->result() as $row) { ?>
											<tr>
														<td class="type"><?php echo $row->payment_name; ?></td>
														<td><?php echo $row->arrives_on; ?></td>
														<td><?php echo $row->fees; ?></td>
														<td><?php echo $country; ?><br><?php echo $row->note; ?></td>
											</tr>
											<?php } ?>
											
						</tbody>
		</table>
		<div class="for_small_letters">* <?php echo translate("Money is always released the day after check in but may take longer to arrive to you."); ?></div>
		<form  method="post" action="<?php echo base_url().'account/paymentInfo'; ?>">
		<p><input type="hidden" value="<?php echo $country_symbol;?>" name="country">
		
		<?php echo translate("What method would you prefer?"); ?> 
		<select name="payout_type" id="payout_info_type">
		<?php foreach($result->result() as $row) {  ?>
				<option value="<?php echo $row->id; ?>"><?php echo $row->payment_name; ?></option>
		<?php } ?>
		</select>
   &nbsp;&nbsp;<button type="button" class="btn_dash_green" name="commit" id="next2"><span><span><?php echo translate("Next"); ?></span></span></button>
			 <?php echo translate("or"); ?>
			&nbsp;<a onclick="$('#payout_new_select').hide();$('#payout_new_initial').show();return false;" href="#"><?php echo translate("Cancel"); ?></a></p>
		</form>
		
<script type="text/javascript">

$(document).ready(function (){

$("#next2").click(function(){ 

var payout_type = $("#payout_info_type").val();
var country     = $("#country").val();

	$.ajax({
	 type: "POST",
		url: "<?php echo site_url('account/paymentInfo'); ?>",
		async: true,
		data: "payout_type="+payout_type+"&country="+country,
		success: function(data)
			{	
					$("#payout_new_select").html(data);
     $('#payout_country_select').hide();
					$('#payout_new_select').show();
			}
  });

})

});
</script>