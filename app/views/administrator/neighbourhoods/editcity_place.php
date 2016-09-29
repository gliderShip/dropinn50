<div class="Edit_Page">
      <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}		
	  ?>
	 <script>
	 $(document).ready(function()
	 {
	 	$('#submit').click(function()
	 	{
   var len = $("input:checkbox:checked").length;
   
   	if(len == 0)
   	{
   		alert('Select atleast one category');
   		return false;
   	}
   		 	})
   		 	$.ajax(
   		 		{
   		 type: 'POST',
		data: 'edit_id='+<?php echo $places->row()->id; ?>,
		dataType: "json",
		url: '<?php echo base_url().'administrator/neighbourhoods/checked_category'; ?>',
		success : function(result){
			
			//alert(result);return false;
			  $.each(result, function(key, value)
			  {
			  // $('#category'+value).checked;
			   $('#category'+value).attr('checked','checked');
			  
              });
			  
         }
   		 		}
   		 	)
	 })
	 
	 </script>
	  <?php
	  	//Content of a group
		if(isset($places) and $places->num_rows()>0)
		{
			$place = $places->row();
	  ?>
	 	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 		<h1 class="page-header1"><?php echo translate_admin('Edit City Place'); ?></h1></div>
			<form method="post" id="form" action="<?php echo admin_url('neighbourhoods/editcity_place')?>/<?php echo $place->id;  ?>" enctype="multipart/form-data">
   <div class="col-xs-9 col-md-9 col-sm-9">
   <table class="table" cellpadding="2" cellspacing="0">

		  <tr>
				<td><?php echo translate_admin('City'); ?><span style="color: red;">*</span></td>
				<td>
				<select name='city' style="width:292px">
				<?php foreach($cities->result() as $row)
				{
if ($row->city_name==$place->city_name){
			$s="selected='selected'";
		}
		else{
			$s="";
		} 
					echo '<option value="'.$row->city_name.'"'.$s.'>'.$row->city_name.'</option>';
				}
				?>
				</select>
				</td>
		  </tr>
		  <tr>
  <td><?php echo translate_admin('Place'); ?><span style="color: red;">*</span></td>
		<td>
				<input class="clsTextBox" size="30" type="text" name="place" id="place" value="<?php echo $place->place_name; ?>"/>
				<?php echo form_error('place'); ?>
		</td>
</tr>
<tr>
  <td><?php echo translate_admin('Quote'); ?><span style="color: red;">*</span></td>
		<td>
				<input class="clsTextBox" size="30" type="text" name="quote" id="quote" value="<?php echo $place->quote; ?>"/>
				<?php echo form_error('quote'); ?>
		</td>
</tr>
		 <tr>
  <td><?php echo translate_admin('Short Description'); ?><span style="color: red;">*</span></td>
		<td>
				<textarea class="clsTextBox" name="short_desc" id="short_desc" value="" style="height: 162px; width: 282px;" ><?php echo $place->short_desc; ?></textarea>
				<?php echo form_error('short_desc'); ?>
		</td>
</tr>
<tr>
  <td><?php echo translate_admin('Long Description'); ?><span style="color: red;">*</span></td>
		<td>
				<textarea class="clsTextBox" name="long_desc" id="long_desc" value="" style="height: 162px; width: 282px;" ><?php echo $place->long_desc; ?></textarea>
				<?php echo form_error('long_desc'); ?>
		</td>
</tr>
<tr>
  <td><?php echo translate_admin('Category'); ?><span style="color: red;">*</span></td>
      <td class="admin_category">
      	<ul>
      <?php 
      foreach($categories->result() as $row)
	  {
			echo "<li><input type=\"checkbox\" id=\"category$row->id\" name=\"category[]\" value=\"$row->id\" $s>".$row->category.'</li>';
		 } 
		 
		 ?>
		 </ul>
		 </td>
</tr>
<tr>
			<td><?php echo translate_admin('Place Image'); ?><span style="color: red;"></span></td>
<td>
<input id="place_image" name="place_image"  size="24" type="file"/>
<span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate_admin("Resolution"); ?>: 1425x151</span>
</td>
</tr>
	<tr>
	<td></td>
<td>
	<img class="img-cls" src="<?php echo cdn_url_images().'images/neighbourhoods/'.$place->city_id.'/'.$place->id.'/'.$place->image_name; ?>" height=183 width=300 />
</td>
</tr>
<tr>
		     <td><?php echo translate_admin('Is Featured').'?'; ?></td>
		     <td>
							<select name="is_home" id="is_home" >
							<option value="0"<?php if($place->is_featured=="0"){echo "selected";} ?>> <?php echo translate_admin('No'); ?> </option>
							<option value="1"<?php if($place->is_featured=="1"){echo "selected";} ?>> <?php echo translate_admin('Yes'); ?> </option>
							</select> 
							</td>
		  </tr>
		  <tr>
				<td><input type="hidden" name="id"  value="<?php echo $place->id; ?>"/></td>
				<td><input  name="submit" id='submit' type="submit" value="Submit"></td>
	  	  </tr>  
        
	  </table>
	</form>
	  <?php
	  }
	  ?>
    </div>
