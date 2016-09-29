<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Currency Name" ; }
	.res_table td:nth-of-type(3):before { content: "Currency Code"; }
	.res_table td:nth-of-type(4):before { content: "Currency Symbol"; }
	.res_table td:nth-of-type(5):before { content: "Currncy Value"; }
	.res_table td:nth-of-type(6):before { content: "Status"; }
	.res_table td:nth-of-type(7):before { content: "Default"; }
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
          <h1 class="page-header3"><?php echo translate_admin("Manage Currency"); ?></h1>
		<div class="but-set">
		<span3><input type="submit" onclick="window.location='<?php echo admin_url('currency/addcurrency')?>'" value="<?php echo translate_admin('Add Currency'); ?>"></span3>
      </div>
	</div>
	
	<form action="<?php echo admin_url('help/hidehelp') ?>" name="managehelp" method="post">
    <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
										<thead>		 						
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Currency Name'); ?></th>
											<th><?php echo translate_admin('Currency Code'); ?></th>
											<th><?php echo translate_admin('Currency Symbol'); ?></th>
											<th><?php echo translate_admin('Currency Value'); ?></th>
											<th><?php echo translate_admin('Status'); ?></th>
											<th><?php echo translate_admin('Default'); ?></th>
											<th><?php echo translate_admin('Action'); ?></th>
        </thead>
					<?php $i=1;
						if(isset($currency) and $currency->num_rows()>0)
						{  
							foreach($currency->result() as $currency_data)
							{
					?>
					
			 <tr>
			 
			  <td><?php echo $i++; ?></td>
			  <td><?php echo $currency_data->currency_name; ?></td>
			  <td><?php echo $currency_data->currency_code; ?></td>
			  <td><?php echo $currency_data->currency_symbol; ?></td>
			  <td><?php echo $currency_data->currency_value; ?></td>
			  <?php if($currency_data->status == '1')
			        {
			        	$status = 'Active';
			        }
						else
							{
								$status = 'In Active';
							}
			 ?>
			  <td><?php echo $status; ?></td>
			  <?php if($currency_data->default == '0')
			        {
			        	$default = 'No';
			        }
						else
							{
								$default = 'Yes';
							}
			 ?>
			  <td><?php echo $default; ?></td>
			  <td><a href="<?php echo admin_url('currency/editcurrency/'.$currency_data->id)?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
   			  <a href="<?php echo admin_url('currency/deletecurrency/'.$currency_data->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>

			      <!--<a href="<?php echo admin_url('help/hidehelp/'.$help->id)?>" onclick="return confirm('Are you sure want to Hide??');"><img src="<?php echo base_url()?>images/disable.png" alt="Hide" title="Hide" /></a>-->
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No Currency Found').'</td></tr>'; 
			}
		?>
		</table>
		<br />
		</form>	
    </div>
<?php echo $pagination; ?>


