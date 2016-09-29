<div class="need_top_part"> <b><?php echo translate('Help Center');?></b>
	<?php
	if(isset($home_help_value))
	{
	?>
	 <a href="<?php echo base_url().'home/help/'.$home_help_value ;?>"><?php echo translate('Home');?></a>
	 <?php } ?>
	 <?php
	if(isset($guide_help_value))
	{
	?>
	  <a href="<?php echo base_url().'home/help/'.$guide_help_value ;?>"><?php echo translate('Guide');?></a>
	  <?php } ?>
	   <?php
	if(isset($account_help_value))
	{
	?>
	  <a href="<?php echo base_url().'home/help/'.$account_help_value ;?>"><?php echo translate('Account');?></a> 
	  <?php } ?>
	   <?php
	if(isset($dashboard_help_value))
	{
	?>
	  <a href="<?php echo base_url().'home/help/'.$dashboard_help_value ;?>"><?php echo translate('Dashboard');?></a> 	
	  <?php } ?>  
      <div style="clear:both;"></div>
      
   <div class="container">
		<div class="search_help"><input type="text" class="help_searchbox" placeholder="<?php echo translate('Search the help center');?>" /></div>
     </div>
  <div class="need_top_part_b_whole">
    <div class="need_top_part_breadcrumb container"><?php echo translate('Help Center');?> > <span><?php echo $page_refer;?></span> </div>
  </div>
</div>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript">
	$(document).ready(function() {
	    $(function() {
	    	
	        $( ".help_searchbox" ).autocomplete({
	      
	        	    source: function(request, response) {
	                $.ajax({ url: "<?php echo base_url().'home/help_autocomplete';?>",
	                data: 'val='+$(".help_searchbox").val(),
	                dataType: "json",
	                type: "GET",
	                success: function(data){
	                	response(data);
	               //alert(data);
	                }
	            });
	        },
	        select: function( event, ui ) 
	        { 
	        	//alert(ui.item.value); 
	        	$.ajax({ url: "<?php echo base_url().'home/help_id';?>",
	                data: 'val='+ui.item.value,
	                //dataType: "json",
	                type: "GET",
	                success: function(data){
	                	//response(data);
	               //alert(data);
	               window.location.href = '<?php echo base_url().'home/help/';?>'+data;
	                }
	            });
	        	},
	        minLength: 2
	        });
	    });
	});
	</script>
	<style>
	ul.ui-autocomplete li.ui-menu-item
	{
		text-align:left;
		
		}
	</style>
<div class="middle_part_whole">
		<div class="middle_part_mid container">
        		<div class="mid_left med_3 mal_3 pe_12">
                	<ul class="need_back">
                    	<li><?php echo translate('Questions');?></li>
                    	
                     
                   <?php  
                
					  if($result->num_rows()!=0)
					  {
                       foreach($result->result() as $row)
						{
						 $stat = $row->status;
						 
						 $help_question=$row->question;
						 $help_id= $row->id;
						//if($row->page_refer != 'guide')
							//{
						 ?>
						<li><a href="<?php echo base_url().'home/help/'.$row->id; ?>" class="unselect"> <?php echo "$help_question";?> <?php //} ?></a></li>
                       
                   <?php  }                    
  						}
                  else
                   { ?>
                <li><a href="<?php echo base_url().'home/help/'.$row->id; ?>" class="unselect"> <?php echo "$question";?> </a></li>
                
                   	 <?php
                   	
				   }?>
                      
                    </ul>
                </div>
        		<div class="mid_right med_9 mal_9 pe_12">
                		<h2><?php 
                		if($question)
						{
                		echo $question; 
						}
                		?></h2>
                       <div class="steps"> <div class="steps_l">
               
                            <p> <?php echo $description;?> </p>

                          </div>
                       
                        <div class="clear"> <?php 
                       if(!$description)
                		{
                        echo translate('Sorry! No Results Found.');}?>
                        </div>  
                </div>
                <div class="clear"></div>
        </div>
</div>
