<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Coupon Code" ; }
	.res_table td:nth-of-type(3):before { content: "Coupon Amount"; }
	.res_table td:nth-of-type(4):before { content: "Currency"; }
	.res_table td:nth-of-type(5):before { content: "Expired Date"; }
	.res_table td:nth-of-type(6):before { content: "Status"; }
}
</style>


<div id="View_Coupon_Pages">
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
          <h1 class="page-header3"><?php echo translate_admin("Manage Coupon"); ?></h1>
		<div class="but-set">
			<span3><input type="submit" onclick="window.location='<?php echo admin_url('coupon/add_coupon')?>'" value="<?php echo translate_admin('Add Coupon Code'); ?>"></span3>
										</div>
			</div>					
	  
	<form action="<?php echo admin_url('coupon/delete_coupon') ?>" name="managepage" method="post">
    <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Coupon Code'); ?></th>
											<th><?php echo translate_admin('Coupon Amount'); ?></th>
											<th><?php echo translate_admin('Currency'); ?></th>
											<th><?php echo translate_admin('Expired Date'); ?></th> 
											<th><?php echo translate_admin('Status'); ?></th> 
											<th><?php echo translate_admin('Action'); ?></th>
										</thead>        
					<?php $i=1;
						if(isset($coupon) and $coupon->num_rows()>0)
						{  
							foreach($coupon->result() as $coupon)
							{
					?>	
			  <tr>
			  <td><input type="checkbox" class="clsNoborder" name="couponlist[]" id="couponlist[]" value="<?php echo $coupon->id; ?>"  />&#160;&#160;<?php echo $i++; ?></td>
			  <td><?php echo $coupon->couponcode; ?></td>		 
			  <td><?php echo $coupon->coupon_price; ?></td>
			  <td><?php echo $coupon->currency; ?></td>
			  <td><?php  echo date('d/m/y',$coupon->expirein);  ?></td>
			  <td><?php 
			 
 if($coupon->status == 0)
 {
 	echo "Active";
 }else{
 	echo "Expired";
 } ?></td>
			   <!-- <td><?php echo (date("d-m-Y", strtotime($row_date['coupon']))); ?></td>   -->
			  <td><a href="<?php echo admin_url('coupon/edit_coupon/'.$coupon->id)?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
		        <a href="<?php echo admin_url('coupon/delete_coupon/'.$coupon->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
   <?php
			}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No Coupon Found').'</td></tr>'; 
			}
		?>
		</table>
		<br />
			<p style="text-align:left">
			<?php
				$data = array(
    'name' => 'delete',
    'class' => 'Blck_Butt',
    'value' => translate_admin('Delete'),
    );
    echo form_submit($data);?>
		</form>	
		</div>									