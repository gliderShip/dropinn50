<?php
$js = array(
    array('jquery.easing.1.3.min.js'),
    array('jquery.sliderkit.1.8.min.js'),
    array('sliderkit.delaycaptions.min.js'),
    array('jquery.leanModal.min.js'),
    array('responsiveslides.min.js'),
    array('home_new.js')
);

$this->carabiner->group('slider',array('js'=>$js));
$this->carabiner->display('slider');
?>
<ul class="results-list list-unstyled" id="list_div">
  	<?php
  	if($wishlists->num_rows() != 0)
	{
		$i = 0;
		foreach($wishlists->result() as $row)
		{
			$i++;
			?>
			<script>
    // You can also use "$(window).load(function() {"
    $(function () {
      // Slideshow 4
      $("#slider<?php echo $i;?>").responsiveSlides({
        auto: false,
        pager: false,
        nav: true,
        speed: 500,
        namespace: "callbacks",
        prevText: "<i class='fa fa-angle-left slider-arrow-left' style='display:none;'></i>",
        nextText: "<i class='fa fa-angle-right slider-arrow-right' style='display:none;'></i>",
        before: function () {
          $('.events').append("<li>before event fired.</li>");
        },
        after: function () {
        	
          //$('.events').append("<li>after event fired.</li>");
        }
      });
   $('.rslides').css("cssText","overflow:visible !important");
$('.next').click(function()
{
	var value = parseInt($('.callbacks<?php echo $i;?>_on').attr('data'));
    var value1 = value-1;
    if(value1 == 0)
    {
       var value2 = $('#total_count_<?php echo $i;?>').val();
       $('#image_count_<?php echo $i;?>'+value2).removeClass('dot-dark-gray');
    }
       $('#image_count_<?php echo $i;?>'+value1).removeClass('dot-dark-gray');
       $('#image_count_<?php echo $i;?>'+value).addClass('dot-dark-gray');
})
$('.prev').click(function()
{
	var value = parseInt($('.callbacks<?php echo $i;?>_on').attr('data'));
    var value1 = value+1;
    var value2 = $('#total_count_<?php echo $i;?>').val();
    if(value1 > value)
    {
       $('#image_count_<?php echo $i;?>1').removeClass('dot-dark-gray');
    }
       $('#image_count_<?php echo $i;?>'+value1).removeClass('dot-dark-gray');
       $('#image_count_<?php echo $i;?>'+value).addClass('dot-dark-gray');
})
    });
   </script>
  	<li class="row listing row-space-2 row-space-top-2" id="li_<?php echo $row->list_id;?>" style="overflow: inherit;">
  		<label for="<?php echo $row->list_id;?>" class="col-12">
  <div class="row">
    <div class="col-3">
      <div class="slideshow-container">
      	<div class="listing_slideshow_thumb_view">
 <div class="listing-slideshow">
<ul class="rslides" id="slider<?php echo $i;?>">
<?php
$conditions     = array('list_id' => $row->list_id);
$image          = $this->Gallery->get_imagesG(NULL, $conditions); 
$j = 0;
if($image->num_rows() != 0)
{
	foreach($image->result() as $image_list)
	{
		$j++;
		$image = base_url().'images/'.$image_list->list_id.'/'.$image_list->name;
		?>
	<li data="<?php echo $j;?>">
	<img width="216" height="144" data="<?php echo $j;?>" alt="" src="<?php echo $image;?>" style="position: absolute; top: 0px; left: 0px; z-index: 2; opacity: 1;height:130px !important">
	</li>
		<?php
	}
	
}
/*else
{
	$image = base_url().'images/no_image.jpg';
}*/
		?>

  <img width="216" height="144" alt="" src="<?php echo $image;?>" class="view_wish">



</ul>
<input type="hidden" id="total_count_<?php echo $i;?>" value="<?php echo $j;?>">
<ul class="photo-dots-list list-layout" id="bullets_<?php echo $i;?>">
	<?php
	for($k=1; $k<=$j; $k++)
	{
		if($k == 1)
		$class= "dot-dark-gray";
		else
        $class = "";
		?>
<li class="photo-dots-list-item dot <?php echo $class;?>" id="image_count_<?php echo $i.$k;?>"></li>

<?php
// dot-dark-gray
	}
?>
</ul>
</div>


<a class="photo-paginate prev" href="javascript:void(0);"><i></i></a>
<a class="photo-paginate next" href="javascript:void(0);"><i></i></a>
</div></div>
    </div>
    <div class="col-9">
      <div class="room-info row row-space-2">
        <div class="col-9">
          <h2 class="h3 row-space-1"><a href="<?php echo base_url().'rooms/'.$row->list_id;?>"><?php echo $row->title;?></a></h2>

          <p class="text-muted row-space-1">
           <?php echo $row->address; ?>
          </p>
          <ul class="list-unstyled inline spaced text-muted">
            <li><?php echo $row->type;?></li>
            <li><?php echo $row->bed_type;?> Bed</li>
            <li></li>
          </ul>
        </div>

        <div class="col-3">
          <div class="text-center pull-right">
            <sup class="h5"></sup>
            <span class="h2 price-amount"><?php echo get_currency_value1($row->list_id,$row->price);?></span>
            <sup class="h5"><?php echo get_currency_code(); ?></sup>
            <br>
            <span class="text-muted">per night</span>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="media col-8">
          
            <img width="28" height="28" class="pull-left img-round" alt="<?php echo get_user_by_id($this->dx_auth->get_user_id())->username;?>" src="<?php echo $this->Gallery->profilepic($this->dx_auth->get_user_id(), 1); ?>">
          
          <!--<form class="note-container media-body text-right" id="add-note-form" method="post" action="">-->
          	<div class="note-container media-body text-right" id="add-note-form_<?php echo $row->list_id;?>">
            <input type="hidden" value="<?php echo $row->list_id;?>" name="hosting_id">
            <input type="hidden" value="<?php echo $row->wishlist_id;?>" name="id">
            <textarea placeholder="Add Note" class="row-space-2" name="note"><?php echo $row->note; ?></textarea>
            <button class="btn gray add-note addnote" id="add_note_<?php echo $row->list_id;?>" data="<?php echo $row->list_id;?>" type="submit" >
              
                Add Note
              
            </button>
          </div>
        </div>

     <!--   <div class="col-4 pullright" style="display: none;" id="<?php echo $row->list_id;?>" >-->

        <div class="col-4 pullright" style="display: none;width:43.333% !important;" id="<?php echo $row->list_id;?>">
          <div class="btn-group pull-right wish_list_button_container">
            <a title="Save this listing to review later." data-img="https://a1.muscache.com/pictures/21578093/large.jpg" data-address="P D'Mello road Sewri, Mumbai, Maharashtra 400015, India" data-name="Entire 3bed and 3bath  South Mumbai" data-hosting_id="1268882" class="wish_list_button saved btn gray" onclick="add_shortlist(<?php echo $row->list_id;?>,this);">
               <span class="icon fa-heart"></span>
               Change
            </a>
            <a data-hosting_id="<?php echo $row->list_id;?>" data-category_id="<?php echo $row->wishlist_id;?>" class="btn gray remove_listing_button listbutton">
              <span class="icon fa-remove"></span>
              Remove
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</label>


</li>
<?php
		}
	}
  	?>
</ul>
<script>
$(document).ready(function()
{
	$('#map_view').click(function()
	{
		$('#list_view').css({'opacity':'1'});	
		$('#map_view').css({'opacity':'0.44'});
		$('#list_div').hide();
		$('#map_div').show();
		initialize();
	})
	
	$('#list_view').click(function()
	{
		$('#map_view').css({'opacity':'1'});
		$('#list_view').css({'opacity':'0.44'});
		$('#list_div').show();
		$('#map_div').hide();
	})
	
	$('textarea').focus(function()
	{
		 var id = $(this).next().attr('data');
		  
		$('#add_note_'+id).css({'opacity':'1'});
	});
	
	$('textarea').focusout(function()
	{
		$('.add-note').css({'opacity':'0'});
	});
	
	$('.row-space-top-2 label').hover(function()
	{
		$('#'+$(this).attr('for')).show();
		
	},function()
	{
		$('#'+$(this).attr('for')).hide();
	});
	
	$('.remove_listing_button').click(function()
	{
		var list_id = $(this).attr('data-hosting_id');
		
		var category_id = $(this).attr('data-category_id');
		
		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/remove_wishlist_inner";?>',
		   data: 'list_id='+list_id+'&category_id='+category_id,
		   success: function(data){
		   	
  				var value = $('#wishlist_count').text();
  				$('#wishlist_count').text(value-1);
    			  $( "#li_"+list_id ).slideUp();
    		    
				   }
		 });
	});
	
	add_shortlist = function(item_id,that) {
							 
		 $('body').css({'overflow':'hidden'});
		// disable_scroll();
		var value = $(that).val();
		/*$.ajax({
  				url: "<?php //echo site_url('rooms/get_data'); ?>",
  				/*type: "POST",
  				dataType: 'json',
  				data: "list_id="+item_id,
  				success: function(data) {
  				//alert(data.images)
  				$('.dynamic-listing-photo').attr('src',data.images);
  				$('.hosting_description').text(data.title);
  				$('.hosting_address').text(data.address);  				
  				}
   				});
		$('#hidden_room_id').val(item_id);
		//$('.modal_save_to_wishlist').show();*/
		//alert(item_id);
		$.ajax({
  				url: "<?php echo site_url('rooms/wishlist_popup'); ?>",
  				type: "get",
  				data: "list_id="+item_id+"&status=account",
  				success: function(data) {
  					//alert(data);
  					$('.modal_save_to_wishlist').replaceWith(data);	
  					$('.modal_save_to_wishlist').show();
  				}
   				});		
    	};
    	
    	$( ".add-note" ).click(function( event ) {
    		    		
    		var list_id = $(this).attr('data');
    		
    		$('#add-note-form_'+list_id).addClass('loading');
    		
    		var note = $('#add-note-form_'+list_id+' textarea').val();
    		    		
    		$.ajax({
  				url: "<?php echo site_url('rooms/wishlist_note'); ?>",
  				type: "get",
  				data: "list_id="+list_id+'&wishlist_id='+<?php echo $wishlist_details->id;?>+'&note='+note,
  				success: function(data) {
  					
  					setTimeout(function()
  					{
  						$('#add-note-form_'+list_id).removeClass('loading');
  					},1000);
  				}
   				});
    		
    	});
    	
    	$('#edit_wishlist').click(function()
    	{
    		$('#box_content').hide();
    		$('#box_content2').show();
    	})
    	
    	$('#save_wishlist').click(function()
    	{
    		$.ajax({
  				url: "<?php echo site_url('rooms/edit_wishlist'); ?>",
  				type: "get",
  				data: "name="+$('#wish-list-name').val()+'&id='+<?php echo $wishlist_details->id;?>+'&privacy='+$('#private').val(),
  				success: function(data) {
  					
  					window.location.href="<?php echo base_url().'account/mywishlist';?>";
  				}
   				});
    	})
    	
		$('#cancel_wishlist').click(function(){
			window.location.href="<?php echo base_url().'account/mywishlist';?>";
		})    
})

function show_confirm()
    	{
    		if(confirm("Are you sure?"))
    		{
    			    		
    		$.ajax({
  				url: "<?php echo site_url('rooms/delete_wishlist'); ?>",
  				type: "get",
  				data: 'wishlist_id='+<?php echo $wishlist_details->id;?>,
  				success: function(data) {
  					
  					window.location.href="<?php echo base_url().'account/mywishlist';?>";
  				}
   				});

    		}
    		else
    		{
    			
    		}
    	}
    	            
</script>