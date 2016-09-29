<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Amenity Name" ; }
	.res_table td:nth-of-type(3):before { content: "Description"; }
	.res_table td:nth-of-type(4):before { content: "Action"; }
}
</style>

    <div id="View_Pages">
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
          <h1 class="page-header3"><?php echo translate_admin("Manage Amenities"); ?></h1>
		<div class="but-set">
		<span3><input type="submit" onclick="window.location='<?php echo admin_url('lists/addamenities')?>'" value="<?php echo translate_admin('Add Amenity'); ?>"></span3>
		</div>
		</div>
      

	
	<form action="<?php echo admin_url('lists/delete_aminity') ?>" name="managepage" method="post">
  <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Amenity Name'); ?></th>
											<th><?php echo translate_admin('Description'); ?></th>
											<th><?php echo translate_admin('Action'); ?></th>
        								</thead>
					<?php $i=1;
						if(isset($aminity) and $aminity->num_rows()>0)
						{  
							foreach($aminity->result() as $aminity)
							{
					?>
					
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="aminitylist[]" id="aminitylist[]" value="<?php echo $aminity->id; ?>"  />&#160;&#160;<?php echo $i++; ?></td>
			  <td><?php echo $aminity->name; ?></td>			  
			  <td><?php echo $aminity->description; ?></td>
			
			  <td><a href="<?php echo admin_url('lists/editamenity/'.$aminity->id)?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
				
			  <a href="<?php echo admin_url('lists/delete_aminity/'.$aminity->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No aminities Found').'</td></tr>'; 
			}
		?>
		</table>
		<br />
		<p style="text-align:left">
			<?php
				$data = array(
    'name' => 'delete',
    'class' => 'Blck_Butt',
    'value' => translate_admin('Delete List'),
    );
		echo form_submit($data);?></p>
		</form>	
    </div>

</div></div>