    <div class="Edit_Page">
      <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}		
	  ?>
	  <?php
	  	//Content of a group
		if(isset($cancellations) and $cancellations->num_rows()>0)
		{
			$cancellation = $cancellations->row();
	  ?>
	 	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 		<h1 class="page-header1"><?php echo translate_admin('Edit Host Cancellation Policy'); ?></h1></div>
			<form method="post" id="editCancellation"  action="<?php echo admin_url('cancellation/edit_host_Cancellation')?>/<?php echo $cancellation->id;  ?>">
<div class="col-xs-9 col-md-9 col-sm-9">
   <table class="table" cellpadding="2" cellspacing="0">
		
		<tr class="">
				<td class="clsNoborder">
				Months Prior
				</td>
				<td>
					<select name="months">
						<?php
						for($i=1; $i<=12;$i++)
						{
							?>
						<option value="<?php echo $i;?>" <?php if($cancellation->months == $i) echo 'selected'; ?>><?php echo $i;?></option>	
							<?php
						} 
						?>
					</select>
									
				</td>
			</tr>
			
			<tr class="">
				<td class="clsNoborder">
				Free Cancellation Limit<span style="color: red;">*</span>
				</td>
				<td>
				
					<input type="text" name="free_cancellation" value="<?php echo $cancellation->free_cancellation;?>"/>
				    <p><?php echo form_error('free_cancellation');?></p>				
				</td>
			</tr>
		
			<tr class="">
				<td class="clsNoborder">
				Days
				</td>
				<td>
					<select name="days">
						<?php
						for($i=1; $i<=31;$i++)
						{
							?>
						<option value="<?php echo $i;?>" <?php if($cancellation->days == $i) echo 'selected'; ?>><?php echo $i;?></option>	
							<?php
						} 
						?>
					</select>
									
				</td>
			</tr>
				
			<tr class="">
				<td class="clsNoborder">
				Before Days Amount<span style="color: red;">*</span>
				</td>
				<td>
				
					<input type="text" name="before_amount" value="<?php echo $cancellation->before_amount;?>"/>
				   <p><?php echo form_error('before_amount');?></p>				
				</td>
			</tr>
			
			<tr class="">
				<td class="clsNoborder">
				Within Days Amount<span style="color: red;">*</span>
				</td>
				<td>
				
					<input type="text" name="after_amount" value="<?php echo $cancellation->after_amount;?>"/>
					<p><?php echo form_error('after_amount');?></p>				
				</td>
			</tr>
			
			<tr class="">
				<td class="clsNoborder">
				Currency
				</td>
				<td>
				
				<select name="currency">
						<?php
						foreach($currency->result() as $row)
						{
							?>
						<option value="<?php echo $row->currency_code;?>" <?php if($cancellation->currency == $row->currency_code) echo 'selected'; ?>><?php echo $row->currency_code;?></option>	
							<?php
						} 
						?>
					</select>
									
				</td>
			</tr>
				  
    <tr>
				<td></td>
				<td>
		  <input type="hidden" name="cancellation_operation" value="edit" />
		  <input type="hidden" name="id"  value="<?php echo $cancellation->id; ?>"/>
    <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" id="submit_edit"  name="editCancellation"/>
     <input class="can-but" type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Cancel'); ?>"  name="cancel"/></td>
	  	</tr>  
        
	  </table>
	</form>
	  <?php
	  }
	  ?>
    </div>
