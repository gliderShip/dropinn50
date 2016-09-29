<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Page Name" ; }
	.res_table td:nth-of-type(3):before { content: "Content"; }
	.res_table td:nth-of-type(4):before { content: "Status"; }
	.res_table td:nth-of-type(5):before { content: "Edit"; }
	.res_table td:nth-of-type(6):before { content: "Delete"; }
}
</style>



 <script type="text/javascript">

function delete_record(id)
{
	var change_status=$("#table_name").val();
	
	var status_image='enable';
	
	var ok=confirm("Are you sure to Delete this record?");
		if(!ok)
			return false;
			
	$.ajax({ type: "POST",url: "<?php echo admin_url('popup/delete_record')?>",async: true,data: "id="+id+"&delete_record="+change_status, success: function(data)
			{	
				if(data!=0)
				{
					$("#row_id_"+id).html('<td colspan="5" style="text-align:center;color:red">Record Deleted</td>');
					
					$("#row_id_"+id).delay(800).fadeOut('slow');
					//$("#row_id_"+id).remove();
				}
				else
					alert("Error");
			}
		  });
}

</script>
    <div class="View_Managemetas">
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
  <h1 class="page-header3"><?php echo translate_admin("Manage Popups"); ?></h1>
  <div class="but-set">
  		<span3><input type="submit" onclick="window.location='<?php echo admin_url('popup/addpopup')?>'" value="<?php echo translate_admin('Add Popup'); ?>"></span3>
  </div>
										</div>

	<form action="" name="managepage" method="post">
  <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Page name'); ?></th>
											<th><?php echo translate_admin('Content'); ?></th>
											<th><?php echo translate_admin('Status'); ?></th>
											<th><?php echo translate_admin('Edit'); ?></th>	
											<th><?php echo translate_admin('Delete'); ?></th>	
									</thead>											
											
											
				<?php 
				if(isset($popup) and $popup->num_rows()>0)
						{  
							foreach($popup->result() as $popup)
							{ 
						 ?>
										 <tr id="row_id_<?php echo $popup->id?>">
			  
			  <td><?php echo $popup->id; ?></td>
			  <td><?php if($popup->name=='rooms'){ echo "List Detail Page"; } elseif($popup->name=='search') { echo "Search Page"; }elseif($popup->name=='home'){ echo "Home Page"; }elseif($popup->name=='step2'){ echo "Step2 Booking Confirmation Page"; }elseif($popup->name=='step4'){ echo "Step4 Booking Confirmation Page"; } ?></td>
			  <td><?php echo $popup->content; ?></td>
			  <td><?php if($popup->status==1){ echo "Enable"; } else { echo "Disable"; } ?></td>
			 <td><li><a href="<?php echo admin_url('popup/editpopup/'.$popup->id)?>"><img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a></li></td>
        	               <td>
                  <a href="javascript:void(0)" onclick="delete_record('<?php echo $popup->id?>')">
              		<img src="<?php echo base_url()?>images/Delete.png"/>
                  </a>
			  </td>
        	</tr>
				
           <?php
				}//Foreach End
			}//If End

		
		?>
		
		
		
		</table>
		<input type="hidden" name="table_name" id="table_name" value="page_popup" />
		</form>	

</div>
