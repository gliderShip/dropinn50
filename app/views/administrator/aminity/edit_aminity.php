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
		if(isset($aminity) and $aminity->num_rows()>0)
		{
			$aminity = $aminity->row();
	  ?>
	 	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 	<h1 class="page-header1"><?php echo translate_admin('Edit Amenity'); ?></h1></div>
			<form method="post" action="<?php echo admin_url('lists/editamenity')?>/<?php echo $aminity->id;  ?>">
	<div class="col-xs-9 col-md-9 col-sm-9">
   <table class="table" cellpadding="2" cellspacing="0">
			
		 		<tr>
					<td><?php echo translate_admin('Amenity Name'); ?><span style="color: red;">*</span></td>
					<td>
					<input class="" type="text" name="name" id="name" value="<?php echo $aminity->name; ?>"><p><?php echo form_error('name');?></p>
					</td>
				</tr>
				<tr>
					<td><?php echo translate_admin('Amenity Description'); ?><span style="color: red;">*</span></td>
					<td>
					<input class="" type="text" name="desc" id="desc" value="<?php echo $aminity->description; ?>"><p><?php echo form_error('desc');?></p>
					</td>
				</tr>
		  
		<tr>
		<td>
		  <input type="hidden" name="id"  value="<?php echo $aminity->id; ?>"/></td><td>
   <input  name="submit" type="submit" value="Submit"></td>
	  	</tr>  
        
	  </table>
	</form>
	  <?php
	  }
	  ?>
    </div>
