<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Question" ; }
	.res_table td:nth-of-type(3):before { content: "Description"; }
	.res_table td:nth-of-type(4):before { content: "Page_Refer"; }
	.res_table td:nth-of-type(5):before { content: "Created"; }
	.res_table td:nth-of-type(6):before { content: "Modified date"; }
	.res_table td:nth-of-type(7):before { content: "Status"; }
	.res_table td:nth-of-type(8):before { content: "Action"; }
}
</style>

    <div id="View_helps">
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
  <h1 class="page-header3"><?php echo translate_admin("Help"); ?></h1>
  <div class="but-set">
  			<span3><input type="submit" onclick="window.location='<?php echo admin_url('help/addhelp')?>'" value="<?php echo translate_admin('Add Help'); ?>"></span3>
  </div>
	  </div>

	
	<form action="<?php echo admin_url('help/hidehelp') ?>" name="managehelp" method="post">
<div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Question'); ?></th>
											<th><?php echo translate_admin('Description'); ?></th>
											<th><?php echo translate_admin('page_refer'); ?></th>
											<th><?php echo translate_admin('Created'); ?></th>
											<th><?php echo translate_admin('Modified Date'); ?></th>
											<th><?php echo translate_admin('Status'); ?></th>
											<th><?php echo translate_admin('Action'); ?></th>
        							</thead>
					<?php $i=1;
						if(isset($helps) and $helps->num_rows()>0)
						{  
							foreach($helps->result() as $help)
							{
					?>
					
			 <tr>
			 
			  <td><?php echo $i++; ?></td>
			  <td><?php echo $help->question; ?></td>
			  <td><?php echo word_limiter($help->description, 7); ?></td>
			  <td><li class="clsMailId">/<?php echo $help->page_refer; ?></li></td>
			  <td><?php echo get_user_times($help->created, get_user_timezone()); ?></td>
			  <td><?php echo get_user_times($help->modified_date, get_user_timezone()); ?></td>
			  <?php if($help->status == '0')
			        {
			        	$status = 'Active';
			        }
						else
							{
								$status = 'In Active';
							}
			 ?>
			  <td><?php echo $status; ?></td>
			  <td><a href="<?php echo admin_url('help/edithelp/'.$help->id)?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
   			  <a href="<?php echo admin_url('help/deletehelp/'.$help->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>

			      <!--<a href="<?php echo admin_url('help/hidehelp/'.$help->id)?>" onclick="return confirm('Are you sure want to Hide??');"><img src="<?php echo base_url()?>images/disable.png" alt="Hide" title="Hide" /></a>-->
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No helps Found').'</td></tr>'; 
			}
		?>
		</table>
		<br />
		<!--	<p style="text-align:left">
			<?php
				$data = array(
    'name' => 'show',
    'class' => 'Blck_Butt',
    'value' => translate_admin('Active Help'),
    'onclick' =>  'admin_url(administrator/help/viewhelp)',
    
    );
		echo form_submit($data);?></p>-->
		</form>	
    </div>



