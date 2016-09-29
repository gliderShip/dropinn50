<script type="text/javascript">
$(document).ready(function(){
	$('#is_fixed').change(function(){
	document.getElementById("show").style.display      = "";
	document.getElementById("hide").style.display      = "";
});
$('#is_fixeda').change(function(){
	document.getElementById("show").style.display      = "none";
	document.getElementById("hide").style.display      = "none";
});
});
$(document).ready(function(){
	$('#is_first').change(function(){
	document.getElementById("hide").style.display      = "";
});
$('#is_firsta').change(function(){
	document.getElementById("hide").style.display      = "none";
});
});
$(document).ready(function(){
	$('#typef').change(function(){
	document.getElementById("fixed").style.display      = "";
	document.getElementById("fixedt").style.display      = "";
	document.getElementById("percentage").style.display = "none";
	document.getElementById("percentaget").style.display = "none";
	 var trip=parseInt(document.getElementById("tripf").value);
	 var total=parseInt(document.getElementById("total").value);
	var c=document.getElementById("currency");
	var cur= c.options[c.selectedIndex].text;
	var rent=total-trip;
	//document.getElementById("rent").innerHTML=rent+cur;
	document.getElementById("rentf").value=rent;
	
});
$('#typep').change(function(){
	document.getElementById("tripf").value=0;
	document.getElementById("fixed").style.display      = "none";
	document.getElementById("fixedt").style.display      = "none";
	document.getElementById("percentage").style.display = "";	
	document.getElementById("percentaget").style.display = "";	
	 var trip=parseInt(document.getElementById("tripp").value);
	 var total=parseInt(document.getElementById("total").value);
	var rent=100-trip;
	document.getElementById("rentp").value=rent;
	
});
});
$(document).ready(function(){
					$("#fade").delay(1000).fadeOut('slow');
		});
$(document).ready(function(){
	$('#tripf').change(function(){	
	 var trip=parseInt(document.getElementById("tripf").value);
	 var total=parseInt(document.getElementById("total").value);
	if(total<trip){
		alert("Give amount less than the Total amount");
		document.getElementById("tripf").value=0;
	}
	else{
	var c=document.getElementById("currency");
	var cur= c.options[c.selectedIndex].text;
	var rent=total-trip;
	//document.getElementById("rent").innerHTML=rent+cur;
	document.getElementById("rentf").value=rent;
	}
	
});
	
});
$(document).ready(function(){
	$('#currency').change(function(){	
	var c=document.getElementById("currency");
	var cur= c.options[c.selectedIndex].text;
	document.getElementById("trip_cur").innerHTML=cur;
	document.getElementById("rent_cur").innerHTML=cur;
	
});
	
});
$(document).ready(function(){
	$('#tripp').change(function(){	
	 var trip=parseInt(document.getElementById("tripp").value);
	 var total=parseInt(document.getElementById("total").value);
	if(trip>100){
		alert("Total percentage should be less than 100!!!");
		document.getElementById("tripp").value=0;
		
	}
	else{
		var rent=100-trip;
	document.getElementById("rentp").value=rent;
	}
	
});
	
});
$(document).ready(function(){
	$('#total').change(function(){	
	 var trip=parseInt(document.getElementById("tripf").value);
	 var tot=document.getElementById("total").value
	 var total=parseInt(document.getElementById("total").value);
	 //alert(total);
	 if(tot == ''){
	document.getElementById("tripf").value=0;
	document.getElementById("total").value=1;
	document.getElementById("rentf").value=1;
	 }
	else if(total<trip ){
		alert("Total amount is less than the Trip amount!!!");
		document.getElementById("tripf").value=0;
		document.getElementById("rentf").value=total;
		
	}
	else if(total<1){
	alert("The minimum amount should be 1!!");
	document.getElementById("tripf").value=0;
	document.getElementById("total").value=1;
	document.getElementById("rentf").value=1;
	}
	
	else{
		var c=document.getElementById("currency");
		var cur= c.options[c.selectedIndex].text;
		var rent=total-trip;
	document.getElementById("rentf").value=rent;
	}
	
});
	
});
    //
</script>
<style>
	.box{
		width:70px !important
	}
</style>
<?php
if($fixed_status==1){
	$disp="";
	
}
else{
	$disp="none";
	
}
?>
    <div id="referrals">
    	<?php
    	if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
			?>
<div class="container-fluid top-sp body-color">
<div class="container">
 <div class="col-xs-9 col-md-9 col-sm-9">
 <h1 class="page-header1"><?php echo translate_admin('Referrals Management'); ?></h1>
 </div>
	 	  
<div class="col-xs-9 col-md-9 col-sm-9">
<form action="<?php echo admin_url('referrals/update'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	
<table class="table" cellpadding="2" cellspacing="0">  
<!--<tr> 
 <td><p class="control-list"><?php echo translate("Fixed Referral Amount");?></p>
 </td>
<div class="input-addon">
 <td> <input type="radio" <?php if($fixed_status== 1) echo 'checked="checked"'; ?> name="is_fixed" id="is_fixed" value="1"> Enable
 &#160;&#160;&#160;<input type="radio" <?php if($fixed_status== 0) echo 'checked="checked"'; ?> name="is_fixed" id="is_fixeda" value="0"> Disable</p></td>
</div>
</tr>-->
<tbody id="show" style="border-top-color: #E5EbF2 !important; >
<tr id="fixeda">
	<td><?php echo translate_admin('Total Amount'); ?><span style="color: red;">*</span></td>
	<td> <input class="box" type="text" id="total" name="total" value="<?php echo $famount; ?>" onkeypress="return event.charCode === 0 || /\d/.test(String.fromCharCode(event.charCode));" required></td>
</tr>	
				    
<tr id="currencya">
    <td><?php echo translate_admin('Currency'); ?><span style="color: red;">*</span></td>
	<td> 
	<select name="currency" id="currency" class="box">
				<?php
				foreach($currency->result() as $row)
				{
				if($result->currency == $row->currency_code)
				{
				$selected = "selected";
					}
				else {
				$selected = "";
					}
				?>
				<option value="<?php echo $row->currency_code;?>" <?php echo $selected; ?>><?php echo $row->currency_code;?></option>
				<?php	}	?>
	</select>
	</td>
</tr>	
</tbody>	
<tbody> 

 
 </tr>
 	</tbody>
 	<tbody id="hide" style="border-top-color: #E5EbF2;">
 	<tr>
 		
       <td><?php echo translate_admin('Promotion Type'); ?></td>
	   <td style="padding-left:30px"> <input type="radio" <?php if($result->type == 1) echo 'checked="checked"'; ?> name="type" id="typef"  value="1"> Fixed Pay 
	   <br><input type="radio" <?php if($result->type == 0) echo 'checked="checked"'; ?> name="type" id="typep"  value="0"> Percentage Pay</td>
	</tr>
		 <tr> <td><p class="control-list"><?php echo translate("The Amount When Take A Trip: ");?></p></td>	
				<?php
				if($result->type == 1)
				{ $showF = ''; $showP = 'none'; }
				else
				{ $showF = 'none'; $showP = ''; }
				?>	
				
	<tr id="fixed" style="display:<?php echo $showF; ?>;">
      <td><?php echo translate_admin('Fixed Amount'); ?>
      	<span style="color: red;">*</span><b>
      	</td>
   	  <td><input class="box" type="text" name="tripf" id="tripf" value="<?php echo $result->trip_amt; ?>" onkeypress="return event.charCode === 0 || /\d/.test(String.fromCharCode(event.charCode));" required>
<span id="trip_cur" class="input-prefix-curency">
		<b><?php echo $result->currency; ?></b>
			</span> 	
   	  </td>
   	</tr>	
	<tr id="percentage" style="display:<?php echo $showP; ?>;">
      <td><?php echo translate_admin('Percentage Amount'); ?><span style="color: red;">*</span></td>
	  <td> <input class="box" type="text" id="tripp" name="tripp" value="<?php echo $result->trip_per; ?>" onkeypress="return event.charCode === 0 || /\d/.test(String.fromCharCode(event.charCode));" required> %</td>
	</tr>
	<!---
		The Amount for renting
		
		
		 -->
	
	 <tr> <td><p class="control-list"><?php echo translate("The Amount When Rent A Place: ");?></p></td>	
				<?php
				if($result->type == 1)
				{ $showF = ''; $showP = 'none'; }
				else
				{ $showF = 'none'; $showP = ''; }
					if($result->type == 1)
				{
					
					 $rent = $famount-$result->trip_amt;
				}
				else
				{
					 $rent =100-$result->trip_per; 
				}
				
				?>	
				
	<tr id="fixedt" style="display:<?php echo $showF; ?>;">
      <td><?php echo translate_admin('Fixed Amount'); ?><span style="color: red;">*</span><b></td>
   	  <td><input class="box" type="text" name="rentf" id="rentf" value="<?php echo $rent ?>" onkeypress="return event.charCode === 0 || /\d/.test(String.fromCharCode(event.charCode));" readonly>
   	  	<span id="rent_cur" class="input-prefix-curency">
		<b><?php echo $result->currency; ?></b>
			</span> 
   	  </td>
   	</tr>	
	<tr id="percentaget" style="display:<?php echo $showP; ?>;">
      <td><?php echo translate_admin('Percentage Amount'); ?><span style="color: red;">*</span></td>
	  <td> <input class="box" type="text" id="rentp" name="rentp" value="<?php echo $rent ?>" onkeypress="return event.charCode === 0 || /\d/.test(String.fromCharCode(event.charCode));" readonly> %</td>
	</tr>
	
	
	<!--
	<tr id="currency" style="display:<?php echo $showF; ?>;">
       <td  style="padding-left:30px"><?php echo translate_admin('Currency'); ?><span style="color: red;">*</span></td>
			<td  style="padding-left:30px">
				<select name="currency1">
				<?php
				foreach($currency->result() as $row)
				{
				if($result->currency == $row->currency_code)
				{
				$selected = "selected";
				}
				else {
				$selected = "";
				}
				?>
				<option value="<?php echo $row->currency_code;?>" <?php echo $selected; ?>><?php echo $row->currency_code;?></option>
				<?php
				}
				?>
				</select>
			</td>
	</tr>	
	
<?php
				if($result->is_fixed == 1)
				{
					
					 $rent = $famount-$result->fixed_amount;
				}
				else
				{
					 $rent =100-$result->percentage_amount."%"; 
				}
				
?>	


				
	
	
	<tr> <td><p class="control-list"><?php echo translate("The Amount When Rent A Place: ");?></p></td>
	 <td id="rent"><?php echo $rent;?></td></tr>
	 <td  style="padding-left:30px"> <input type="text" id="rent1" name="rent1" value="<?php echo $rent;?>" onkeypress="return event.charCode === 0 || /\d/.test(String.fromCharCode(event.charCode));" readonly> %</td>
-->

	</tbody>				
	</table>
	<div class="col-xs-9 col-md-9 col-sm-9" id ="update">
	<tr> <td>
		<div class="clearfix">
		<input type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;"/>
		<p><div id="message"></div></p>
		</div>
		</td>
	</tr>
	

</form>
</div>
</div>



