<?php
	if($msg = $this->session->flashdata('flash_message'))
				{
					echo $msg;
				}
?>
		
				
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
				<h1 class="page-header3"><?php echo translate_admin("Add Neighbourhood City"); ?></h1>
			 	<div class="but-set">
			 <span3><input type="submit" onclick="window.location='<?php echo admin_url('neighbourhoods/viewcity'); ?>'" value="<?php echo translate_admin('Manage Cities'); ?>"></span3>
         		</div>
         	</div>
         
         
          <?php /*?><h3><?php echo translate_admin("Manage Neighborhood Places"); ?></h3><?php */?>
		
<form method="post" action="<?php echo admin_url('neighbourhoods/addcity')?>" enctype="multipart/form-data">					
<div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table2" cellpadding="2" cellspacing="0">
<tr>
  <td><?php echo translate_admin('City'); ?><span style="color: red;">*</span></label></td>
		<td>
				<input size="30" type="text" name="city" id="city" value=""/>
				<?php echo form_error('city'); ?>
		</td>
</tr>
<tr>
  <td><?php echo translate_admin('City Description'); ?><span style="color: red;">*</span></label></td>
		<td>
				<textarea name="city_desc" id="city_desc" value="" style="height: 162px; width: 282px;" ></textarea>
				<?php echo form_error('city_desc'); ?>
		</td>
</tr>
<tr>
  <td><?php echo translate_admin('Known For'); ?><span style="color: red;">*</span></label></td>
		<td>
				<textarea name="known" id="known" value="" style="height: 162px; width: 282px;" ></textarea>
				<?php echo form_error('known'); ?>
				<span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate_admin("Ex: Hollywood,boardwalks."); ?></span>
		</td>
</tr>

<tr>
  <td><?php echo translate_admin('Get around with'); ?><span style="color: red;">*</span></label></td>
		<td>
				<input size="30" type="text" name="around" id="around" value=""/>
				<?php echo form_error('around'); ?>
				<span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate_admin("Ex: Car,Public Transit"); ?></span>
		</td>
</tr>

<tr>
			<td><?php echo translate_admin('City Image'); ?><span style="color: red;">*</span></td>
<td>
<input style="display: inline-block;" id="city_image" name="city_image"  size="24" type="file" />
<span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate_admin("Resolution"); ?>: 1425x500</span>
</td>
</tr>
<tr>
		     <td><?php echo translate_admin('Is Home').'?'; ?></td>
		     <td>
							<select name="is_home" id="is_home" >
							<option value="0"> <?php echo translate_admin('No'); ?> </option>
							<option value="1"> <?php echo translate_admin('Yes'); ?> </option>
							</select> 
							</td>
		  </tr>
<tr>
	<td></td>
	<td>
	<input  name="submit" type="submit" value="<?php echo translate_admin('Submit'); ?>">
	</td>
</tr>
		
</table>
</form>	
</div>





            
