<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "City" ; }
	.res_table td:nth-of-type(3):before { content: "Description"; }
	.res_table td:nth-of-type(4):before { content: "get Around With"; }
	.res_table td:nth-of-type(5):before { content: "Known For"; }
	.res_table td:nth-of-type(6):before { content: "Is Home?"; }
	.res_table td:nth-of-type(7):before { content: "Created"; }
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
          <h1 class="page-header3"><?php echo translate_admin("Manage Neighbourhood Cities"); ?></h1>
			<div class="but-set">
			<span3><input type="submit" onclick="window.location='<?php echo admin_url('neighbourhoods/addcity')?>'" value="<?php echo translate_admin('Add City'); ?>"></span3>
      </div>
	</div>
	
	<form action="<?php echo admin_url('neighbourhoods/deletecity') ?>" name="managepage" method="post">
<div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('City'); ?></th>
											<th><?php echo translate_admin('Description'); ?></th>
											<th><?php echo translate_admin('Get Around With'); ?></th>
											<th><?php echo translate_admin('Known For'); ?></th>
											<th><?php echo translate_admin('Is Home').'?'; ?></th>
											<th><?php echo translate_admin('Created'); ?></th>
										    <th><?php echo translate_admin('Action'); ?></th>
        									</thead>
					<?php $i=1;
						if(isset($cities) and $cities->num_rows()>0)
						{  
							foreach($cities->result() as $city)
							{
					?>
					
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $city->id; ?>"  />&#160;&#160;<?php echo $i++; ?></td>
			  <td><?php echo $city->city_name; ?></td>
			  <td><?php echo word_limiter($city->city_desc, 4); ?></td>
			  <td><?php echo word_limiter($city->around, 4); ?></td>
			  <td><?php echo word_limiter($city->known, 4); ?></td>
			  <td><?php if($city->is_home==0) echo translate_admin('No'); else echo translate_admin('Yes'); ?></td>
			  <td><?php echo get_user_times($city->created, get_user_timezone()); ?></td>
			  <td><a href="<?php echo admin_url('neighbourhoods/editcity/'.$city->id)?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
			      <a href="<?php echo admin_url('neighbourhoods/deletecity/'.$city->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No Neighbourhoods Found').'</td></tr>'; 
			}
		?>
		</table>
		<br />
			<p style="text-align:left">
			<?php
				$data = array(
    'name' => 'delete',
    'class' => 'Blck_Butt',
    'value' => translate_admin('Delete City'),
    );
		echo form_submit($data);?></p>
		</form>	
    </div>


