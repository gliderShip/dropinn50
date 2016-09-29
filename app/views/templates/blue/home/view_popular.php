<style>
	.subnav{overflow: hidden;}
</style>
<?php 

	  echo '<div class="image-placeholder-popular container">
	  <ul class="popular_whole" style="margin-top:30px !important;"> <li> <ul class="popular_whole_img">';
	  
	  foreach($shortlist->result() as $row) 
	  {
	  		
    $step_completed=$this->db->where('id',$row->list_id)->where(array('calendar'=>1,'overview'=>1,'listing'=>1,'photo'=>1,'address'=>1,'price'=>1))->get('lys_status')->num_rows(); 
	if($step_completed == 1) 
	{	
			
			 
	  	$query['id'] = $row->id;		 //get list table data's
	  	$query['address'] = $row->address;
		$query['title'] = $row->title;
		$query['country'] = $row->country;
		$query['price'] = $row->price;
	  	
		$city=explode(',', $query['address']);
		$photo=$this->Users_model->get_list_by_id('list_photo','list_id',$row->list_id); //get photo name from list_photo table
	
				 if(count($photo) > 0) //condition for if empty photo
				 {
				 	 $url = base_url().'images/'.$query['id'].'/'.$photo['name']; ?>
				 	 	<li class="row wishlists-list-item">
				 	 		
				 	 	<?php	$this->path = realpath(APPPATH . '../images'); 
				 	 	$dir_url = $this->path.'/'.$query['id']; 
				 	 	if(is_dir($dir_url))
						{
							 if (file_exists($dir_url.'/'.$photo['name']))
							  { ?>
		   <?php echo '<li class="med_6 pe_12 mal_6" style="margin-bottom:20px;"><a href="'.site_url().'rooms/'.$query['id'].'">';?> <div style="position:relative;"><img src="<?php echo $url; ?>" height=183 width=275  alt=<?php echo $query["title"];?> >
		 <?php  }
					 else { 
					 echo '<li class="med_6 pe_12 mal_6" style="margin-bottom:20px;"><a href="'.site_url().'rooms/'.$query['id'].'">';?> <div style="position:relative;"><img src="<?php echo base_url().'images/no_image.jpg'; ?>" height=183 width=275 alt=<?php echo $query["title"];?> >
					 			<?php	 }
						}		
				 else { 
					 echo '<li class="med_6 pe_12 mal_6" style="margin-bottom:20px;"><a href="'.site_url().'rooms/'.$query['id'].'">';?> <div style="position:relative;"><img src="<?php echo base_url().'images/no_image.jpg'; ?>" height=183 width=275 alt=<?php echo $query["title"];?> >
					<?php 				 }
		 ?>
		   	<div style="position:absolute; bottom:0; left:0;" class="med_12 pe_12 mal_12 padd-zero">
		   	<ul>
  
    <li >
    	<?php 
    	echo '<div class="pop_img_h pe_7 padd-zero med_10 mal_7">'.$query['title'].'</div>';
    	echo '<div class="pop_img_h_place pe_8 med_10 mal_7" style="padding-left:0px !important;padding-right:5px !important;">'.$city['2'].','.$query['country'].'</div>';?>
    	</li>
    	 <li>
             <div class="pop_img_h_dollar" style="position:absolute: right:0; bottom:0;">
             	<div class="pop_doll">
                <?php echo '<p class="dollor_symbol">'.get_currency_symbol($query['id']).'</p><p class="dollor_price">'.get_currency_value1($query['id'],$query['price']); ?>
                <p class="per_night"><?php echo translate('per night');?></p>
                </div>
              </div>
          </li>
  
 </ul>
		   </div></div></a>
							</li>							 
					
	  
		   
		  <?php
		  } 
		     else 
		     {
		     	 		 	 	?><li class="row wishlists-list-item">
		   <?php echo '<li class="med_6 pe_12 mal_6" style="margin-bottom:20px;" ><a href="'.site_url().'rooms/'.$query['id'].'">';?> <div style="position:relative;"><img src="<?php echo base_url().'images/no_image.jpg'; ?> height=210    alt=<?php echo $query["title"];?> >
		   	<div style="position:absolute; bottom:0; left:0;" class="med_12 pe_12 mal_12 padd-zero">
		   	<ul>
  
    <li>
    	<?php 
    	echo '<div class="pop_img_h pe_9 padd-zero med_10 mal_10">'.$query['title'].'</div>';
    	echo '<div class="pop_img_h_place pe_12 med_10 mal_10">'.$city['2'].','.$query['country'].'</div>';?>
    	</li>
    	 <li>
         	<div class="pop_img_h_dollar" style="position:absolute: right:0; bottom:0;">
              <div class="pop_doll">
				<?php echo '<p class="dollor_symbol">'.get_currency_symbol($query['id']).'</p><p class="dollor_price">'.get_currency_value1($query['id'],$query['price']); ?>
                <p class="per_night"><?php echo translate('per night');?></p>
              </div>
            </div>
         </li>
 
 </ul>
		   </div></div></a>
							</li>							 
				<?php 
				 
			 }

	  }
	  }
            echo '</ul>
			</li> 
	  		 
			<li style="clear:both;"></li>
	  </ul>';    
				 
	  ?>