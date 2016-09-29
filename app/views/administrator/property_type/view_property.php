<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Property Type" ; }
	.res_table td:nth-of-type(3):before { content: "Action"; }
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
          <h1 class="page-header3"><?php echo translate_admin("Manage property"); ?></h1>
			<div class="but-set">
			<span3><input type="submit" onclick="window.location='<?php echo admin_url('property_type/view_property')?>'" value="<?php echo translate_admin('Add Property type'); ?>"></span3>
			</div>	
      </div>
	
	
	<form action="<?php echo admin_url('property_type/delete_property') ?>" name="managepage" method="post">
<div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Property Type'); ?></th>
											<th><?php echo translate_admin('Action'); ?></th>
        								</thead>
        
					<?php $i=1;
						if(isset($property) and $property->num_rows()>0)
						{  
							foreach($property->result() as $property)
							{
					?>
					
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="propertylist[]" id="propertylist[]" value="<?php echo $property->id; ?>"  />&#160;&#160;<?php echo $i++; ?></td>
			  <td><?php echo $property->type; ?></td>	
			
			  <td><a href="<?php echo admin_url('property_type/editproperty/'.$property->id)?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
				
			 <a href="<?php echo admin_url('property_type/delete_property/'.$property->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No property type Found').'</td></tr>'; 
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


