<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Title" ; }
	.res_table td:nth-of-type(3):before { content: "Code"; }
	.res_table td:nth-of-type(4):before { content: "Action"; }
}  
</style>

<div id="Email_Template">

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
					<h1 class="page-header3"><?php echo translate_admin('Manage Email Template'); ?></h1>
					<div class="but-set">
					<span3><input type="submit" onclick="window.location='<?php echo admin_url('email/addemailTemplate')?>'" value="<?php echo translate_admin('Add Email Template'); ?>"></span3> 	
					</div>
					</div>
    <div class="col-xs-9 col-md-9 col-sm-9">                            
   <table class="table1 res_table" cellpadding="2" cellspacing="0" align="left">
		  <thead>
		  <tr>
				<th><?php echo translate_admin("S.No"); ?></th>
    <th><?php echo translate_admin("Title"); ?></th>
				<th><?php echo translate_admin("Code"); ?></th>
		  <th><?php echo translate_admin("Action"); ?></th>
				</tr>
		        </thead>
		<?php
		 if(isset($email_settings) && $email_settings->num_rows() > 0)
			{
			$i = 1;
			$c=$this->uri->segment(4);
				foreach($email_settings->result() as $email_setting)
				{ 
		?>
			 <tr>
				 <td><?php echo $i+(($c-1)*15); ?></td>
			  <td><?php echo ucfirst($email_setting->title); ?></td>
					<td><?php echo $email_setting->type; ?></td>
		
			  <td><a href="<?php echo admin_url('email/edit/'.$email_setting->id)?>">	<img src="<?php echo base_url()?>images/edit_img.jpg"/></a>
			  <a href="<?php echo admin_url('email/delete/'.$email_setting->id)?>"; onclick="return confirm('Are you sure want to delete??');"> 		<img src="<?php echo base_url()?>images/Delete.png"/>
			  </a></td>
        	</tr>
        <?php
								$i++;
								
				}//Foreach End
			} 
			else
			{
			echo '<tr><td colspan="6">'.translate_admin('No Template Found').'</td></tr>'; 
			}
		?>
		
		</table>
		
<?php echo $pagination;?>
</div>
