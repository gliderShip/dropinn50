<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Url" ; }
	.res_table td:nth-of-type(3):before { content: "Name"; }
	.res_table td:nth-of-type(4):before { content: "Title"; }
	.res_table td:nth-of-type(5):before { content: "Description"; }
	.res_table td:nth-of-type(6):before { content: "Keyword"; }
	.res_table td:nth-of-type(7):before { content: "Action"; }
}
</style>


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
          <h1 class="page-header1"><?php echo translate_admin("Manage Metas"); ?></h1> 
										</div>

	<form action="<?php echo admin_url('page/deletePage') ?>" name="managepage" method="post">
  <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Url'); ?></th>
											<th><?php echo translate_admin('Name'); ?></th>
											<th><?php echo translate_admin('Title'); ?></th>
											<th><?php echo translate_admin('Description'); ?></th> 	
											<th><?php echo translate_admin('Keyword'); ?></th>
											<th><?php echo translate_admin('Action'); ?></th>	
										</thead>											
											
											
				<?php 
				if(isset($meta) and $meta->num_rows()>0)
						{  
							foreach($meta->result() as $meta)
							{ 
						 ?>
										 <tr>
			  
			  <td><?php echo $meta->id; ?></td>
			  <td><?php echo $meta->url; ?></td>
			  <td><?php echo word_wrap($meta->name,10); ?></td>
			  <td><?php echo word_wrap($meta->title,10); ?></td>
			  <td><?php echo word_wrap($meta->meta_description,10) ; ?></td>
			  <td><?php echo word_wrap($meta->meta_keyword,10); ?></td>
			 <td><li><a href="<?php echo admin_url('managemetas/editmetas/'.$meta->id)?>"><img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a></li></td>
        	</tr>
				
           <?php
				}//Foreach End
			}//If End
		
		?>
		
		
		
		</table>
		<?php echo $pagination; ?>
		</form>	

</div>