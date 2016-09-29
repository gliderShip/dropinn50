
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
		if(isset($tags) and $tags->num_rows()>0)
		{
			$tag = $tags->row();
	  ?>
	 	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 		<h1 class="page-header1"><?php echo translate_admin('Edit Tag'); ?></h1></div>
			<form method="post" action="<?php echo admin_url('neighbourhoods/edittag')?>/<?php echo $tag->id;  ?>" enctype="multipart/form-data">
 <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table" cellpadding="2" cellspacing="0">
		  <tr>
  <td><?php echo translate_admin('Tag'); ?><span style="color: red;">*</span></td>
		<td>
				<input class="clsTextBox" size="30" type="text" name="tag" id="tag" value="<?php echo $tag->tag; ?>" />
				<?php echo form_error('tag'); ?>
		</td>
</tr>
<tr>
  <td><?php echo translate_admin('City'); ?><span style="color: red;">*</span></td>
		<td>
				<input class="clsTextBox" size="30" type="text" name="city" id="city" value="<?php echo $tag->city; ?>" readonly/>
		</td>
</tr>		
<tr>
  <td><?php echo translate_admin('Place'); ?><span style="color: red;">*</span></td>
		<td id="place">
				<input class="clsTextBox" size="30" type="text" name="place" id="place" value="<?php echo $tag->place; ?>" readonly/>
		</td>
</tr>
<tr>
		     <td><?php echo translate_admin('Is Shown').'?'; ?></td>
		     <td>
							<select name="is_shown" id="is_shown">
							<option value="0"<?php if($tag->shown=="0"){echo "selected";} ?>>  <?php echo translate_admin('No'); ?> </option>
							<option value="1"<?php if($tag->shown=="1"){echo "selected";} ?>> <?php echo translate_admin('Yes'); ?> </option>
							</select>  
							</td>
		  </tr>

		  <tr>
				<td><input type="hidden" name="id"  value="<?php echo $tag->id; ?>"/></td>
				<td><input  name="submit" type="submit" value="Submit"></td>
	  	  </tr>  
        
	  </table>
	</form>
	  <?php
	  }
	  ?>
    </div>
