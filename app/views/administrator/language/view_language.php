<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Language Name" ; }
	.res_table td:nth-of-type(3):before { content: "Language Code"; }
	.res_table td:nth-of-type(4):before { content: "Status"; }
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
          <h1 class="page-header3"><?php echo translate_admin("Manage Languages"); ?></h1>
			<div class="but-set">
			<span3><input type="submit" onclick="window.location='<?php echo admin_url('language/add_language')?>'" value="<?php echo translate_admin('Add Language'); ?>"></span3>
													</div>
      </div>

	
	<form action="<?php echo admin_url('language/delete_language') ?>" name="managepage" method="post">
  <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Language Name'); ?></th>
											<th><?php echo translate_admin('Language Code'); ?></th>
											<th><?php echo translate_admin('Status'); ?></th>
											<th><?php echo translate_admin('Action'); ?></th>
        </thead>
					<?php $i=1;
						if(isset($languages) and $languages->num_rows()>0)
						{  
							foreach($languages->result() as $language)
							{
					?>
					
			 <tr>
			  <td><?php echo form_checkbox('checkbox[]', $language->id);?>&#160;&#160;<?php echo $i++; ?></td>
			  <td><?php echo $language->name; ?></td>
			  <td><?php	echo $language->code; ?></td>
			  <td><?php	echo $language->status == 1 ? 'Yes' : 'No'; ?></td>
              <td><a href="<?php echo admin_url('language/edit_language/'.$language->id)?>">
			    <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
			  <a href="<?php echo admin_url('language/delete_language/'.$language->id)?>">
			    <img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No Language Found').'</td></tr>'; 
			}
		?>
		</table>
		<br />
		<p style="text-align:left">
			<?php
				$data = array(
    'name' => 'delete',
    'class' => 'Blck_Butt',
    'value' => translate_admin('Delete Language'),
    );
		echo form_submit($data);?></p>
		</form>	
    </div>


