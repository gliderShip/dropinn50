<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "City" ; }
	.res_table td:nth-of-type(3):before { content: "Place"; }
	.res_table td:nth-of-type(4):before { content: "Quote"; }
	.res_table td:nth-of-type(5):before { content: "Short Description"; }
	.res_table td:nth-of-type(6):before { content: "Long Description"; }
	.res_table td:nth-of-type(7):before { content: "Category"; }
	.res_table td:nth-of-type(8):before { content: "Is Featured?"; }
	.res_table td:nth-of-type(9):before { content: "Created"; }
	.res_table td:nth-of-type(10):before { content: "Action"; }
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
          <h1 class="page-header3"><?php echo translate_admin("Manage Neighbourhoods"); ?></h1>
          <div class="but-set">
			<span3><input type="submit" onclick="window.location='<?php echo admin_url('neighbourhoods/addcity_place')?>'" value="<?php echo translate_admin('Add City Place'); ?>"></span3>
		</div>
      </div>

	
	<form action="<?php echo admin_url('neighbourhoods/deletecity_place') ?>" name="managepage" method="post">
<div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('City'); ?></th>
											<th><?php echo translate_admin('Place'); ?></th>
											<th><?php echo translate_admin('Quote'); ?></th>
											<th><?php echo translate_admin('Short Description'); ?></th>
											<th><?php echo translate_admin('Long Description'); ?></th>
											<th><?php echo translate_admin('Category'); ?></th>
											<th><?php echo translate_admin('Is Featured').'?'; ?></th>
											<th><?php echo translate_admin('Created'); ?></th>
										    <th><?php echo translate_admin('Action'); ?></th>
										</thead>        
					<?php $i=1;
						if(isset($places) and $places->num_rows()>0)
						{  
							foreach($places->result() as $place)
							{
					?>
					
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $place->id; ?>"  />&#160;&#160;<?php echo $i++; ?></td>
			  <td><?php echo $place->city_name; ?></td>
			  <td><?php echo $place->place_name; ?></td>
			  <td><?php echo word_limiter($place->quote, 4); ?></td>
			  <td><?php echo word_limiter($place->short_desc, 4); ?></td>
			  <td><?php echo word_limiter($place->long_desc, 4); ?></td>
			  <td>
			  	<?php 
			  	$result = $this->db->where('place',$place->place_name)->get('neigh_place_category');
					
				if($result->num_rows()!=0)
				{
					foreach($result->result() as $row)
					{
						echo $this->db->where('id',$row->category_id)->get('neigh_category')->row()->category.',';
					}
				}
				
					?>
			  </td>
			  <td><?php if($place->is_featured==0) echo translate_admin('No'); else echo translate_admin('Yes'); ?></td>
			  <td><?php echo get_user_times($place->created, get_user_timezone()); ?></td>
			  <td><a href="<?php echo admin_url('neighbourhoods/editcity_place/'.$place->id)?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
			      <a href="<?php echo admin_url('neighbourhoods/deletecity_place/'.$place->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No Neighbourhood Places Found').'</td></tr>'; 
			}
		?>
		</table>
		<br />
			<p style="text-align:left">
			<?php
				$data = array(
    'name' => 'delete',
    'class' => 'Blck_Butt',
    'value' => translate_admin('Delete Place'),
    );
		echo form_submit($data);?></p>
		</form>	
    </div>


