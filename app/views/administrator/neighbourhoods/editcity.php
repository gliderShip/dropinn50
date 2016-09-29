<div class="Edit_Page">
      <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}		
	  ?>
	  <?php
	  	//Content of a group
		if(isset($cities) and $cities->num_rows()>0)
		{
			$city = $cities->row();
	  ?>
	 	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 		<h1 class="page-header1"><?php echo translate_admin('Edit City'); ?></h1></div>
			<form method="post" action="<?php echo admin_url('neighbourhoods/editcity')?>/<?php echo $city->id;  ?>" enctype="multipart/form-data">
		<div class="col-xs-9 col-md-9 col-sm-9"> 	
   <table class="table" cellpadding="2" cellspacing="0">

		  <tr>
				<td><?php echo translate_admin('City'); ?><span style="color: red;">*</span></td>
				<td><input class="" type="text" size="30" name="city" id="city" value="<?php echo $city->city_name; ?>" ></td>
				<?php echo form_error('city'); ?>
		  </tr>
		  <tr>
  <td><?php echo translate_admin('City Description'); ?><span style="color: red;">*</span></label></td>
		<td>
				<textarea class="clsTextBox" name="city_desc" id="city_desc" style="height: 162px; width: 282px;" ><?php echo $city->city_desc; ?></textarea>
				<?php echo form_error('city_desc'); ?>
		</td>
</tr>

<tr>
  <td><?php echo translate_admin('Known For'); ?><span style="color: red;">*</span></label></td>
		<td>
				<textarea class="clsTextBox" name="known" id="known" value="" style="height: 162px; width: 282px;" ><?php echo $city->known; ?></textarea>
				<?php echo form_error('known'); ?>
				<p><span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate_admin("Ex: Hollywood,boardwalks"); ?>.</span></p>
		</td>
</tr>
<tr>
  <td><?php echo translate_admin('Get around with'); ?><span style="color: red;">*</span></label></td>
		<td>
				<input class="clsTextBox" size="30" type="text" name="around" id="around" value="<?php echo $city->around; ?>"/>
				<?php echo form_error('around'); ?>
				<p><span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate_admin("Ex: Car,Public Transit"); ?></span></p>
		</td>
</tr>
		  <tr>
			<td><?php echo translate_admin('City Image'); ?><span style="color: red;"></span></td>
<td>
<input id="city_image" name="city_image"  size="24" type="file" />
<p><span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate_admin("Resolution"); ?>: 1425x500</span></p>
</td>
<tr>
	<td></td>
<td>
	<img src="<?php echo cdn_url_images().'/images/neighbourhoods/'.$city->id.'/'.$city->image_name; ?>" height=183 width=300/>
</td>
</tr>
</tr>
<tr>
		     <td><?php echo translate_admin('Is Home').'?'; ?></td>
		     <td>
							<select name="is_home" id="is_home" >
							<option value="0"<?php if($city->is_home=="0"){echo "selected";} ?>>  <?php echo translate_admin('No'); ?> </option>
							<option value="1"<?php if($city->is_home=="1"){echo "selected";} ?>> <?php echo translate_admin('Yes'); ?></option>
							</select> 
							</td>
		  </tr>
		  <tr>
				<td><input type="hidden" name="id"  value="<?php echo $city->id; ?>"/></td>
				<td><input  name="submit" type="submit" value="Submit"></td>
	  	  </tr>  
        
	  </table>
	</form>
	  <?php
	  }
	  ?>
    </div>
