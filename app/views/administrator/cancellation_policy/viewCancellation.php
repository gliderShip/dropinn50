<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Policy Name" ; }
	.res_table td:nth-of-type(3):before { content: "Cleaning Fee?"; }
	.res_table td:nth-of-type(4):before { content: "Security Fee?"; }
	.res_table td:nth-of-type(5):before { content: "Additional Fee?"; }
	.res_table td:nth-of-type(6):before { content: "List Fee Before Checkin?"; }
	.res_table td:nth-of-type(7):before { content: "List Fee After Checkin?"; }
	.res_table td:nth-of-type(8):before { content: "Action"; }
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
          <h1 class="page-header3"><?php echo translate_admin("Manage Cancellation Policy"); ?></h1>
			<div class="but-set">
				<span3><input type="submit" onclick="window.location='<?php echo admin_url('cancellation/addCancellation')?>'" value="<?php echo translate_admin('Add Cancellation Policy'); ?>"></span3>				
			</div>										
				      </div>

	
	<form action="<?php echo admin_url('cancellation/deleteCancellation') ?>" name="managepage" method="post">
  <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Policy Name'); ?></th>
											<th><?php echo translate_admin('Cleaning fee'); ?>?</th>
											<th><?php echo translate_admin('Security fee'); ?>?</th>
											<th><?php echo translate_admin('Additional fee'); ?>?</th>
											<th><?php echo translate_admin('List fee before checkin'); ?>?</th>
											<th><?php echo translate_admin('List fee after checkin'); ?>?</th>
											<th><?php echo translate_admin('Action'); ?></th>
										</thead>        
					<?php $i=1;
						if(isset($cancellation) and $cancellation->num_rows()>0)
						{  
							foreach($cancellation->result() as $cancellations)
							{
					?>
					
			 <tr>
			  <td><?php echo form_checkbox('checkbox[]', $cancellations->id);?>&#160;&#160;<?php echo $i++; ?></td>
			  <td><?php echo $cancellations->name; ?></td>
			  <td><?php 
			  if($cancellations->cleaning_status == 1) 
			  echo 'Yes';
			  else
			  echo 'No';		
			  	?></td>
			  	 <td><?php 
			  if($cancellations->security_status == 1) 
			  echo 'Yes';
			  else
			  echo 'No';		
			  	?></td>
			  	 <td><?php 
			  if($cancellations->additional_status == 1) 
			  echo 'Yes';
			  else
			  echo 'No';		
			  	?></td>
			  	 <td><?php 
			  if($cancellations->list_before_status == 1) 
			  echo 'Yes';
			  else
			  echo 'No';		
			  	?></td>
			  	<td><?php 
			  if($cancellations->list_after_status == 1) 
			  echo 'Yes';
			  else
			  echo 'No';		
			  	?></td>
              <td><a href="<?php echo admin_url('cancellation/editCancellation/'.$cancellations->id)?>">
			    <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
			  <a href="<?php echo admin_url('cancellation/deleteCancellation/'.$cancellations->id)?>">
			    <img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No Cancellation Policy Found').'</td></tr>'; 
			}
		?>
		</table>
		<br />
		<p style="text-align:left">
			<?php
				$data = array(
    'name' => 'delete',
    'class' => 'Blck_Butt',
    'value' => translate_admin('Delete Cancellation Policy'),
    );
		echo form_submit($data);?></p>
		</form>	
    </div>


