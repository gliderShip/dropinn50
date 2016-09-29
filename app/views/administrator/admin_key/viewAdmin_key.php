<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Page Key" ; }
	.res_table td:nth-of-type(3):before { content: "Created"; }
	.res_table td:nth-of-type(4):before { content: "Status"; }
	.res_table td:nth-of-type(5):before { content: "Edit"; }
}
</style>


    <div id="View_Admin_keys">
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
          <h1 class="page-header3"><?php echo translate_admin("Manage Static Admin_key"); ?></h1>
          <div class="but-set">
			<span3><input type="submit" onclick="window.location='<?php echo admin_url('admin_key/addAdmin_key')?>'" value="<?php echo translate_admin('Add Key'); ?>"></span3>
					      </div>
      	</div>
	<form action="<?php echo admin_url('admin_key/deleteAdmin_key') ?>" name="manageAdmin_key" method="post">
  <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Page Key'); ?></th>
											<!--<th><?php echo translate_admin('Page Ref.'); ?></th>-->
											<th><?php echo translate_admin('Created'); ?></th>
											<th><?php echo translate_admin('Status'); ?></th>
											<th><?php echo translate_admin('Edit'); ?></th>
								</thead>        
					<?php $i=1;
						if(isset($Admin_key) and $Admin_key->num_rows()>0)
						{  
							foreach($Admin_key->result() as $Admin_key)
							{
					?>
					
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="Admin_keylist[]" id="Admin_keylist[]" value="<?php echo $Admin_key->id; ?>"  />&#160;&#160;<?php echo $i++; ?></td>
			  <td><?php echo $Admin_key->page_key; ?></td>
			  <!--<td><?php echo $Admin_key->page_refer; ?></td>-->
			  <td><?php echo get_user_times($Admin_key->created, get_user_timezone()); ?></td>
			 <?php if($Admin_key->status == '0')
			        {
			        	$status = translate_admin('Active');
			        }
						else
							{
								$status = translate_admin('In Active');
							}
			 ?>
			  <td><?php echo $status; ?></td>
			  <td><a href="<?php echo admin_url('admin_key/editAdmin_key/'.$Admin_key->id)?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
<a href="<?php echo admin_url('admin_key/deleteAdmin_key/'.$Admin_key->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			    </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No Admin_keys Found').'</td></tr>'; 
			}
		?>
		</table>

		</form>	
    </div>


