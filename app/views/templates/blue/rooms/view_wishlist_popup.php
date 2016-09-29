<script>
$(document).ready(function() {
	
var cancel = false;
  $('.selectContainer').click(function()
  {
  	$('.selectWidget').show();
  	if(i == 1 || i == 2)
  	{
  		$('#new_wishlist').show();
  		$('.doneContainer').hide();
  	}
  	else if(done == 1)
  	{
  		done = 0;
  		$('.selectWidget').hide();
  	}
  	else if(i == 3)
  	{
  		$('.doneContainer').show();
  		$('#new_wishlist').hide();
  	}
  	else
  	{
  	$('.doneContainer').show();
  	$('#new_wishlist').hide();
  	}
  	
  	if($('#new_wishlist').css('display') == 'none' && i != 3)
  	{
  		i = 0;
  	}
  	else if(i == 3)
  	{
  		i = 3;
  	}
  	else {
  		i = 1;
  	}
  	cancel = true;
  	 	
  })
 var i = 0;
 $('#create_new').click(function()
 {
 	i++;
 	$('.doneContainer').hide();
 	//$('#wishlist_category_name').val('');
 	$('#new_wishlist').show();
 })
 
 $('#wishlist_close').click(function()
			{
				$('body').css({'overflow':'scroll'});
				$('.modal_save_to_wishlist').hide();
			})
			
$(".panel-header").hover(function(){
   // $("div.description").show();
   $('#new_wishlist').hide();
   i = 0;
   $(".selectWidget").hide();
});

$(".panel-footer").hover(function(){
   // $("div.description").show();
   $('#new_wishlist').hide();
   i = 0;
   $(".selectWidget").hide();
});

$('.wishlist-note').focus(function()
{
	$('#new_wishlist').hide();
	i = 0;
	 $(".selectWidget").hide();
})

$('#add_note').click(function()
{
	$('#new_wishlist').hide();
	i = 0;
	$(".selectWidget").hide();
})

$('#wishlist_category').click(function()
{
	if($('#wishlist_category_name').val())
	{
	var name = $('#wishlist_category_name').val();
	var dataString = 'name='+$('#wishlist_category_name').val()+'&privacy='+$('#privacy').val()+'&list_id='+$('#hidden_room_id').val();
	 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/wishlist_category";?>',
		   data: dataString,
		   success: function(data){
		   	var category_id = data;
		   		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/get_wishlist_category";?>',
		   data: 'list_id='+$('#hidden_room_id').val()+'&category_id='+category_id,
		   success: function(data){
		   	    $('.selectWidget').hide();
		   		$('.selectList').replaceWith(data);
		   	 var count = 0;
		   		$("input[type=checkbox]:checked").each ( function() 
				{
				count++;				
				});
				if(count == 1)
				{
		   		$('#selected span').text(name);
		   	    }
		   	    else
				{
				$('#selected span').text(count+' Wish Lists');
				}
				
				   }
		 });
		   		
				   }
		 });
	}
	$('#wishlist_category_name').val('');
	$('#new_wishlist').hide();
	$('.doneContainer').show();
})

var done = 0;

$('#wishlist_done').click(function()
{
	var wishlist_count = 0;
	var name = '';
	done = 1;
	$("input[type=checkbox]:checked").each ( function() 
	{
   
if($(this).val() != 'true')
		{
wishlist_count++;
}

   if(wishlist_count == 1)
{
   name = $('#'+$(this).val()).text();
   $('#selected span').text(name);
}
});

if(wishlist_count == 1)
{

}
else
{
setTimeout(function() { $('#selected span').text(wishlist_count+' Wish Lists') }, 100);
}
$('.selectWidget').css({'display':'none'});

})

$('#wishlist_save').click(function()
{
	var wishlist_count = 0;
	
	var total_count = 0;
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
		total_count++;
		}
	});
	
	if($('#selected span').text() != '0 Wish Lists')
	{
		var first_wishlist = $('.selectList li').first().attr('data-wishlist-id');
		$('input[value='+first_wishlist+']').prop('checked',true);
	}
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
			
		wishlist_count++;
		
   		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/add_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val()+'&i='+wishlist_count+'&total_count='+total_count,
		   success: function(data){
		   	 
    			$('.'+$('#hidden_room_id').val()).attr('value', '<?php echo translate("Saved to Wish List"); ?>'); 
    		    $('.'+$('#hidden_room_id').val()).attr('src', '<?php echo base_url().'images/heart_rose.png'; ?>'); 
    		    
    		    $('#my_shortlist').removeClass("savelist");
                $('#my_shortlist').addClass("savedlist");
    			$('#my_shortlist').attr('value', '<?php echo translate("Saved To Wish List"); ?>'); 
    			    		
    			 var image_src = '<a style="background-image:url(<?php echo base_url();?>images/heart_rose.png);background-repeat: no-repeat;left: 193px !important;padding: 16px;top:5px;" class="search_heart_normal search_heart" id="'+$('#hidden_room_id').val()+'" style="position: absolute;cursor:pointer;cursor: hand;" id="my_shortlist" onclick="add_shortlist('+$('#hidden_room_id').val()+',this);" href="#"> </a>';
		    
		   		$('#'+$('#hidden_room_id').val()).replaceWith(image_src);
    			    		
    		    $('.modal_save_to_wishlist').hide();
    		    $('body').css({'overflow':'scroll'});
    		    
    		    $.ajax({
  				url: "<?php echo site_url('rooms/wishlist_count'); ?>",
  				async: true,
  				type: "GET",
  				data: "list_id="+$('#hidden_room_id').val(),
  				success: function(data) {
  					$('#counter').text(data);
  				}
  				});
    		    
    		    }
  				
		   	});
				  
		}
});
if(wishlist_count == 0)
{
	//$('.modal_save_to_wishlist').show();
	
	 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/remove_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val(),
		   success: function(data){
		   	
    			$('.'+$('#hidden_room_id').val()).attr('value', '<?php echo translate("Save To Wish List"); ?>'); 
    			$('.'+$('#hidden_room_id').val()).attr('src', '<?php echo base_url().'images/search_heart_hover.png'; ?>'); 
    			
    			$('#my_shortlist').removeClass("savedlist");
                $('#my_shortlist').addClass("savelist");
    			$('#my_shortlist').attr('value', '<?php echo translate("Save To Wish List"); ?>'); 
    			
		    var image_src = '<a style="background-image:url(<?php echo base_url();?>images/search_heart_hover.png);background-repeat: no-repeat;left: 193px;padding: 16px;top:5px;" class="search_heart_normal search_heart" id="'+$('#hidden_room_id').val()+'" style="position: absolute;cursor:pointer;cursor: hand;" id="my_shortlist" onclick="add_shortlist('+$('#hidden_room_id').val()+',this);" href="#"> </a>';
		    
		   		$('#'+$('#hidden_room_id').val()).replaceWith(image_src);
                
    			$('.modal_save_to_wishlist').hide();
    			$('body').css({'overflow':'scroll'});
    			
    			$.ajax({
  				url: "<?php echo site_url('rooms/wishlist_count'); ?>",
  				async: true,
  				type: "GET",
  				data: "list_id="+$('#hidden_room_id').val(),
  				success: function(data) {
  					$('#counter').text(data);
  				}
  				});
  				
    		    }

		 });		 
}

})

$('#wishlist_save_account').click(function()
{
	var wishlist_count = 0;
	var wishlist_count1 = 0;
	var total_count = 0;
	
	if($('#selected span').text() != '0 Wish Lists')
	{
		var first_wishlist = $('.selectList li').first().attr('data-wishlist-id');
		$('input[value='+first_wishlist+']').prop('checked',true);
	}
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
		total_count++;
		}
	});
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
		wishlist_count++;
				
   		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/add_user_wishlist_account";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val()+'&i='+wishlist_count+'&total_count='+total_count,
		   success: function(data){
		   	wishlist_count1++;
		   	if(wishlist_count1 == 1)
		   	{
		   	  $('#list_div').replaceWith(data);
		   	}
		   	
		   	$('.modal_save_to_wishlist').hide();
    		$('body').css({'overflow':'scroll'});
         	   }
		 });
		}
});

if(wishlist_count == 0)
{
	
	 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/remove_user_wishlist_account";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val(),
		   success: function(data){
		   	$('#list_div').replaceWith(data);
		   	$('.modal_save_to_wishlist').hide();
    	    $('body').css({'overflow':'scroll'});
				   }
		 });	
		 
	 
}

});

$('#wishlist_save_home').click(function()
{
	var wishlist_count = 0;
	var wishlist_count1 = 0;
	var total_count = 0;
	
	if($('#selected span').text() != '0 Wish Lists')
	{
		var first_wishlist = $('.selectList li').first().attr('data-wishlist-id');
		$('input[value='+first_wishlist+']').prop('checked',true);
	}
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
		total_count++;
		}
	});
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
		wishlist_count++;
				
   		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/add_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val()+'&i='+wishlist_count+'&total_count='+total_count,
		   success: function(data){
		   	wishlist_count1++;
		   	if(wishlist_count1 == 1)
		   	{
		   	  
		   	}
		   	var image_src = '<img width="40" height="40" src="<?php echo base_url();?>images/heart_but_pink.png" alt="no heart image" class="wishlist_pink" onclick="add_shortlist('+$('#hidden_room_id').val()+',this);">';
		   	$('.wishlist_normal_'+$('#hidden_room_id').val()).replaceWith(image_src);
		   	$('.modal_save_to_wishlist').hide();
    		$('body').css({'overflow':'scroll'});
         	   }
		 });
		}
});

if(wishlist_count == 0)
{
	
	 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/remove_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val(),
		   success: function(data){
		   
		   	$('.modal_save_to_wishlist').hide();
		   	var image_src = '<img width="40" height="40" src="<?php echo base_url();?>images/heart_but.png" alt="no heart image" class="wishlist_pink" onclick="add_shortlist('+$('#hidden_room_id').val()+',this);">';
		   	$('.wishlist_pink_'+$('#hidden_room_id').val()).replaceWith(image_src);
    	    $('body').css({'overflow':'scroll'});
				   }
		 });	
		 
	 
}

});

$('#wishlist_save_rooms').click(function()
{
	var wishlist_count = 0;
	var wishlist_count1 = 0;
	var total_count = 0;
	
	if($('#selected span').text() != '0 Wish Lists')
	{
		var first_wishlist = $('.selectList li').first().attr('data-wishlist-id');
		$('input[value='+first_wishlist+']').prop('checked',true);
	}
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
		total_count++;
		}
	});
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
		wishlist_count++;

   		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/add_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val()+'&i='+wishlist_count+'&total_count='+total_count,
		   success: function(data){
		   	wishlist_count1++;
		   	if(wishlist_count1 == 1)
		   	{
		   	  
		   	}
		    $("#my_shortlist").removeClass("savelist");
            $("#my_shortlist").addClass("savedlist");
            $("#my_shortlist").addClass("accept_button_save_wish");
    		$("#my_shortlist").attr('value', '<?php echo translate("Saved To Wish List"); ?>'); 
    		document.getElementById("counter").innerHTML=parseInt($('#counter').text())+1;
		   	$('.modal_save_to_wishlist').hide();
    		$('body').css({'overflow':'scroll'});
         	   }
		 });
		 }
});

if(wishlist_count == 0)
{
	
	 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/remove_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val(),
		   success: function(data){
		   
		   	$('.modal_save_to_wishlist').hide();
		   	$("#my_shortlist").removeClass("savedlist");
		   	$("#my_shortlist").removeClass("accept_button_save_wish");
            $("#my_shortlist").addClass("savelist");
    		$("#my_shortlist").attr('value', '<?php echo translate("Save To Wish List"); ?>'); 
    		document.getElementById("counter").innerHTML=parseInt($('#counter').text())-1;
    	    $('body').css({'overflow':'scroll'});
				   }
		 });	
		 
	 
}

});

$('#wishlist_save_account_other').click(function()
{
	var wishlist_count = 0;
	var wishlist_count1 = 0;
	var total_count = 0;
	
	if($('#selected span').text() != '0 Wish Lists')
	{
		var first_wishlist = $('.selectList li').first().attr('data-wishlist-id');
		$('input[value='+first_wishlist+']').prop('checked',true);
	}
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
		total_count++;
		}
	});
	
	$("input[type=checkbox]:checked").each ( function() 
	{
		if($(this).val() != 'true')
		{
		wishlist_count++;

   		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/add_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val()+'&i='+wishlist_count+'&total_count='+total_count,
		   success: function(data){
		   	wishlist_count1++;
		   	if(wishlist_count1 == 1)
		   	{
		   	  
		   	}
		    
		    var image_src = '<img style="position: absolute;cursor:pointer;cursor: hand;" src="<?php echo base_url();?>images/heart_rose.png" alt="no heart image" class="search_heart_normal search_heart" id="'+$('#hidden_room_id').val()+'" onclick="add_shortlist_other('+$('#hidden_room_id').val()+',this);">';
		    
		   	$('#'+$('#hidden_room_id').val()).replaceWith(image_src);
		   	$('.modal_save_to_wishlist').hide();
		   
		   //	$('.infoBox').hide();
    		$('body').css({'overflow':'scroll'});
         	   }
		 });
		 }
});

if(wishlist_count == 0)
{
	
	 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/remove_user_wishlist";?>',
		   data: 'wishlist_id='+$(this).val()+'&list_id='+$('#hidden_room_id').val()+'&note='+$('.wishlist-note').val(),
		   success: function(data){
		   
		   	$('.modal_save_to_wishlist').hide();
		   	
			// if(<?php //echo $this->session->userdata('map_info');?> //== 1)
		   	//{
		   //	location.reload();
		   //	}
		   
		    var image_src = '<img style="position: absolute;cursor:pointer;cursor: hand;" src="<?php echo base_url();?>images/search_heart_hover.png" alt="no heart image" class="search_heart_normal search_heart" id="'+$('#hidden_room_id').val()+'" onclick="add_shortlist_other('+$('#hidden_room_id').val()+',this);">';
		    
		   	$('#'+$('#hidden_room_id').val()).replaceWith(image_src);
		   	
    	    $('body').css({'overflow':'scroll'});
				   }
		 });	
		 
	 
}

});
 
 });
</script>
<div class="modal_save_to_wishlist med_12 mal_12 pe_12" style="display: none;">
	<div class="modal-table med_6 med_offset-3">
	<div class="modal-cell"><div class="new-modal wishlist-modal modal-content show_share_fb_checkbox med_6 med_offset-3 padding-zero" style="width:100%;">
  <div class="panel-header med_12 mal_12 pe_12 ">
   <span class="no_fb popup_title">Save to Wish List</span>
    <span class="fb" style="display: none;">Save to Wish List and Facebook Timeline</span>
    <a class="panel-close" data-behavior="modal-close" id="wishlist_close" href="javascript:void(0);">×</a>
  </div>
 <input type="hidden" value="<?php echo $list_id; ?>" id="hidden_room_id">
  <div class="panel-body" style="overflow: hidden;">
    <div class="row med_12 mal_12 pe_12 padding-zero" style="padding-top:10px" id="panel-body">
      <div class="med_2 mal_2 pe_3 padding-zero">
        <div class="media-photo media-photo-block dynamic-listing-photo-container">
          <div class="media-cover text-center">
            <img class="dynamic-listing-photo img-responsive-height" src="<?php echo $images;?>">
          </div>
        </div>
      </div>

      <div class="wishlist med_10 mal_10 pe_9 " style="min-height: 398px;">
        <div class="hosting_description text-lead"><?php echo $title;?></div>

        <p class="hosting_address"><?php echo $address;?></p>

        <div class="med_12 mal_12 pe_12 padding-zero">
        	
          <div class="">
          	
            <div class="selectContainer select select-block ">
            	<img src="<?php echo base_url().'images/dropdown.jpg';?>" onclick="myFunction()" style="float: right;margin-right:10px;margin-top:14px !important;" class="down_arrow_pop">
              <div class="selectWidget select_wishlistdrop" style="display: none">
         <?php
         $checked = '';
               echo ' <ul class="selectList list-unstyled wishdrop_height">';
	if($wishlist_category->num_rows() != 0)
					{
						$j = 0;
						foreach($wishlist_category->result() as $category_wishlist)
						{
							$user_wishlist_inner = $this->Common_model->getTableData('user_wishlist',array('user_id'=>$this->dx_auth->get_user_id(),'list_id'=>$list_id,'wishlist_id'=>$category_wishlist->id));
							
							if($user_wishlist_inner->num_rows() != 0)
							{								
								$checked = 'checked';
							}
							else
							{
								$checked = '';
 							}

							echo '<li class="checked" data-wishlist-id="'.$category_wishlist->id.'">
  							  <label class="checkbox text-truncate">
   								<input type="checkbox" value="'.$category_wishlist->id.'" '.$checked.'>
    							<span id="'.$category_wishlist->id.'">'.$category_wishlist->name.'</span>
 							  </label>
							</li>';
						$j++;
						}
					}
					if($user_wishlist1->num_rows() != 0)
					{
						$checked = 'checked';
					}
					else
						{
							$checked = '';
						}
				echo '</ul>';
?>
                <div class="newWLContainer med_12 mal_12 pe_12" style="min-height: 90%;">
                  <div class="doneContainer">
                    <a href="javascript:void(0);" class="create btn_dash finish" id="create_new" style="text-decoration: none !important;margin-bottom: 5px;">Create new</a>
                    <a href="javascript:void(0);" class="btn_dash finish" id="wishlist_done" style="text-decoration: none !important;margin-bottom: 5px;">Done</a>
                  </div>
                  <div style="display:none" id="new_wishlist">
                    
                    <div class="row" style="margin-left:0 !important;">
                      <div class="med_6 mal_6 pe_12 padding-zero" style="margin-bottom: 5px">
                        <input type="text" placeholder="Make a new wish list..." size="26" id="wishlist_category_name" style="width: 100%;">
                      </div>
                      <div class="med_6 mal_6 pe_12 padding-zero wishlist-create" style="">
                        <div class="select save_pop" style="vertical-align: top !important;">
                          <select class="wishlist-create-privacy save_every" name="private" id="privacy">
                            <option selected="" value="0">
                              Everyone
                            </option>
                            <option value="1">
                              Only Me
                            </option>
                          </select>
                        </div>
                        <i style="line-height: 2;margin-left: 5px;margin-right: 5px;" class="fa fa-question-circle fa-1g" id="privacy-tooltip-trigger"></i>
                        <div data-trigger="#privacy-tooltip-trigger" role="tooltip" id="privacy-tooltip" class="tooltip tooltip-bottom-left" style="margin:90px 0px 0px 244px;">
                          <div class="panel-body">
                            <h5>Everyone</h5>
                            <p>Visible to everyone and included on your public <?php echo $this->dx_auth->get_site_title(); ?> profile.</p>
                          </div>
                          <div class="panel-body">
                            <h5>Only Me</h5>
                            <p>Visible only to you and not shared anywhere.</p>
                          </div>
                        </div>
                        <button class="btn_dash finish pull-right createWishlist save_pop1" style="padding: 6px 12px;" id="wishlist_category">Create</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <span id="selected">
              	<span><?php
              	if($user_wishlist->num_rows() != 0)
				{
					$note = $user_wishlist->row()->note;
					if($user_wishlist->num_rows() == 1)
					{
					$wishlist_name = $this->Common_model->getTableData('wishlists',array('id'=>$user_wishlist->row()->wishlist_id));
					echo $wishlist_name->row()->name;
					}
					else
					{
					echo $user_wishlist->num_rows().' Wish Lists';		
					}
					
				}
				else
					{
					$this->db->order_by('id','desc');
					$user_first_wishlist = $this->Common_model->getTableData('wishlists',array('user_id'=>$this->dx_auth->get_user_id()));
					if($user_first_wishlist->num_rows() != 0)
					{
					echo $user_first_wishlist->row()->name;	
					}
					else
					{
					echo '0 Wish List';	
					}
					$note = '';						
					}
              	?>
              	</span>
              <i></i></span>
            </div>
          </div>
        </div>

        <div class="" id="add_note">
          <div class="noteContainer med_12 mal_12 pe_12 padding-zero">
            <label class="addnote">Add a note</label>
            <textarea class="wishlist-note" name="note"><?php echo $note; ?></textarea>
          </div>
          
           <!-- <div class="col-6 share_fb_checkbox">
              <label>Add to Timeline:</label>
              <i title="You can always change your sharing options in Account Settings." data-behavior="tooltip" class="icon icon-facebook fb_icon"></i>
              <input type="checkbox" value="true" name="fb_share" id="fb_share">
            </div>-->
          
        </div>
      </div>
    </div>
  </div>

  <div class="panel-footer">
  	<?php
  	if(isset($status))
	{
		if($status == 'account')
		{
		?>
    <button class="blue gotomsg btn" id="wishlist_save_account" type="submit">Save</button>
    <?php 
		}
		else if($status == 'home')
		{
		?>
	<button class="blue gotomsg btn" id="wishlist_save_home" type="submit">Save</button>	
	<?php
		}
		else if($status == 'rooms')
    	{	
    	?>
    <button class="blue gotomsg btn" id="wishlist_save_rooms" type="submit">Save</button>
    <?php } 
		else if($status == 'account_other')
    	{	
    	?>
    <button class="blue gotomsg btn" id="wishlist_save_account_other" type="submit">Save</button>
    <?php }
		} else
    {
    	?>
    <button class="btn_dash finish" id="wishlist_save" type="submit">Save</button>
    <?php } ?>
  </div>
  
</div>
</div>
</div>
</div>
<style>
.wishlist-note{
 border-radius: 2px;
 padding:14px !important;
}
.wishlist-create
{
	width: 48%!important;
}
.panel-close:hover, .alert-close:hover, .modal-close:hover, .panel-close:focus, .alert-close:focus, .modal-close:focus {
    color: #b0b3b5;
    text-decoration: none;
}
.noteContainer label {
    display: block;
    padding-bottom: 8px;
    padding-top: 9px;
    padding-left:1px;
}
.wishlist-note {
    line-height: inherit;
    padding-bottom: 10px;
    padding-top: 10px;
    resize: vertical;
      display: block;
    padding: 8px 10px;
    width: 100%;
    margin-left:1px;
}
#privacy-tooltip-trigger:hover + .tooltip{
    display:block !important;
    z-index:3000;
    float:left;
    margin:90px 0px 0px 244px;
}
.wishlist {
    overflow: hidden;
    padding-top: 0px;
}
.save_every
{
	padding: 5px 10px 5px 5px;
}
.save_pop{
	margin-left:5px;
}
.save_pop1{
	border-radius: 2px;
}
.callbacks_nav{
	height: 128px !important;
}
@media screen and (min-width:320px) and (max-width:640px){
	.save_pop{
	margin-left:0px;
}
}
@media screen and (min-width:320px) and (max-width:479px){
.save_pop1{
	width:100%;
}

}
</style>