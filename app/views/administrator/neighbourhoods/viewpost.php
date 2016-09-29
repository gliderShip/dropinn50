<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Post ID" ; }
	.res_table td:nth-of-type(3):before { content: "City"; }
	.res_table td:nth-of-type(4):before { content: "Place"; }
	.res_table td:nth-of-type(5):before { content: "Title"; }
	.res_table td:nth-of-type(6):before { content: "Description"; }
	.res_table td:nth-of-type(7):before { content: "Is Featured?"; }
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
          <h1 class="page-header3"><?php echo translate_admin("Manage Neighborhood Place Posts"); ?></h1>
          <div class="but-set">
			<span3><input type="submit" onclick="window.location='<?php echo admin_url('neighbourhoods/addpost')?>'" value="<?php echo translate_admin('Add Post'); ?>"></span3>
										</div>
      </div>

	
	<form action="<?php echo admin_url('neighbourhoods/deletepost') ?>" name="managepage" method="post">
<div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
											<th><?php echo translate_admin('S.No'); ?></th>
											<th><?php echo translate_admin('Post ID'); ?></th>
											<th><?php echo translate_admin('City'); ?></th>
											<th><?php echo translate_admin('Place'); ?></th>
											<th><?php echo translate_admin('Title'); ?></th>
											<th><?php echo translate_admin('Description'); ?></th>
											<th><?php echo translate_admin('Is Featured ?'); ?></th>
											<!--<th><?php echo translate_admin('Created'); ?></th>-->
											<th><?php echo translate_admin('Action'); ?></th>
										</thead>
        
					<?php $i=1;
						if(isset($posts) and $posts->num_rows()>0)
						{  
							foreach($posts->result() as $post)
							{
					?>
					
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $post->id; ?>"  />&#160;&#160;<?php echo $i++; ?></td>
			  <td><?php echo $post->id; ?></td>
			  <td><?php echo $post->city; ?></td>
			  <td><?php echo $post->place; ?></td>
			  <td><?php echo word_limiter($post->image_title, 4); ?></td>
			  <td><?php echo word_limiter($post->image_desc, 4); ?></td>
			  <td><?php if($post->is_featured==0) echo translate_admin('No'); else echo translate_admin('Yes'); ?></td>
			 <!-- <td><?php echo get_user_times($post->created, get_user_timezone()); ?></td>-->
			  <td><a href="<?php echo admin_url('neighbourhoods/editpost/'.$post->id)?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
			      <a href="<?php echo admin_url('neighbourhoods/deletepost/'.$post->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.translate_admin('No Posts Found').'</td></tr>'; 
			}
		?>
		</table>
		<br />
			<p style="text-align:left">
			<?php
				$data = array(
    'name' => 'delete',
    'class' => 'Blck_Butt',
    'value' => translate_admin('Delete Post'),
    );
		echo form_submit($data);?></p>
		</form>	
    </div>


