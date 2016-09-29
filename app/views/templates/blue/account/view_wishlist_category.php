 <div class="row wishlists-body">
  	<?php
  	if($wishlist_category->num_rows() != 0)
  	{
  		foreach($wishlist_category->result() as $row)
  		{
  				?>
  				<div class="col-4 row-space-4">
      <div class="panel">
  <a class="panel-image media-photo media-link media-photo-block wishlist-unit" href="<?php echo base_url().'account/wishlists/'.$row->id;?>" style="text-decoration:none !important;">
  				<?php
  			$user_wishlist = $this->Common_model->getTableData('user_wishlist',array('wishlist_id'=>$row->id,'user_id'=>$this->dx_auth->get_user_id()));	
  			
			if($user_wishlist->num_rows() != 0)
			{
				if($user_wishlist->num_rows() != 1)
				$count_status = 's';
				else
				$count_status = '';
					
				$conditions              = array('list_id' => $user_wishlist->row()->list_id,'is_featured'=>1);
			   $data['images']          = $this->Gallery->get_imagesG(NULL, $conditions);
			   
			   if($data['images']->num_rows() != 0)
			   {
			   	 $image = base_url().'images/'.$user_wishlist->row()->list_id.'/'.$data['images']->row()->name;
			   }
			   else
			   	{
			   		$image = base_url().'images';
			   	}
				?>
				
				<div style="background-image:url('<?php echo $image; ?>');" class="media-cover media-cover-dark wishlist-bg-img">
   				 </div>
				<?php
			}
			else
				{
					$count_status = '';
					?>
					<div style="background-image:url('<?php echo base_url().'images/'; ?>');" class="media-cover media-cover-dark wishlist-bg-img">
   				 </div>
					<?php
				}
  			?>
      
    <div class="row row-table row-full-height">
      <div class="col-12 col-middle text-center text-contrast">
        <div class="panel-body">
         <?php
        	if($row->privacy == 1)
			{
				echo '<i class="fa fa-lock fa-2x"></i>'; 
			}
        	?>
          <div class="h2"><strong><?php echo $row->name;?></strong></div>
        </div>
        
        <div class="btn btn-guest"><?php echo $user_wishlist->num_rows();?> Listing<?php echo $count_status;?></div>
      </div>
    </div>
  </a>
</div>
      </div>
       <?php
  		}
  	}
  	?>
  </div>