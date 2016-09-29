<?php
	if($msg = $this->session->flashdata('flash_message'))
				{
					echo $msg;
				}
?>
	<script src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
<script type="text/javascript"> 
      function user_type_fun(value)
      {
      	if(value == 'Host')
      	{
      		var post_id = $('#post_id :selected').text();
      		 $.ajax({
     	 type: 'GET',
     	 data: "post_id="+post_id,
         url : "<?php echo base_url().'administrator/neighbourhoods/get_room_id'?>",
         success : function($data){
         	if($data != 'Empty')
         	{
                 $('#room_id').replaceWith($data);
           }
           else
           {
           	 alert("Sorry! This User Don't have the List for this place.");
           	 $('#room_id_tr').hide();
           	 $('#rooms_title_tr').hide();
           	 $('#user_type').val('Guest');
           }
         },	
         error : function ($responseObj){
             alert("Something went wrong while processing your request.\n\nError => "
                 + $responseObj.responseText);
         }
        });
      		$('#room_id_tr').show();
      		$('#rooms_title_tr').show();

      	}
      	else
      	{
      		$('#room_id_tr').hide();
      		$('#rooms_title_tr').hide();
      	}
      }
    function get_title(id){
     $.ajax({
     	type: 'GET',
     	data: "id="+id,
         url : "<?php echo base_url().'administrator/neighbourhoods/rooms_title_drop'?>",
         success : function($data){
                 $('#rooms_title').html($data);

         },	
         error : function ($responseObj){
             alert("Something went wrong while processing your request.\n\nError => "
                 + $responseObj.responseText);
         }
     }); 
    }
    
    function get_room_id(post_id)
    {
    	if($('#user_type :selected').text() == 'Host')
    	{
    	  $.ajax({
     	 type: 'GET',
     	 data: "post_id="+post_id,
         url : "<?php echo base_url().'administrator/neighbourhoods/get_room_id'?>",
         success : function($data){
         	if($data != 'Empty')
         	{
                 $('#room_id').replaceWith($data);
           }
           else
           {
           	 alert("Sorry! This User Don't have the List for this place.");
           	 $('#room_id_tr').hide();
           	 $('#rooms_title_tr').hide();
           	 $('#user_type').val('Guest');
           }
         },	
         error : function ($responseObj){
             alert("Something went wrong while processing your request.\n\nError => "
                 + $responseObj.responseText);
         }
        }); 
       }
    }
	
    </script>	
    
     <script type="text/javascript">
	$(document).ready(function(){
		$("#knowledge").validate({
			debug: false,
			rules: {
				knowledge: {
          required: true,
          minlength: 6
        },
        city:
        {
        	customvalidation: true
        }
			},
			
			messages: {
		        knowledge:
                    { 
                    	required: "Knowledge Must Be Required",
                    	minlength: "Minimum 6 Characters Required"                       	
                  }
			},
			 
		});
		//alert($('#city').val());
		
		$.validator.addMethod("customvalidation", 
      function(value, element) {
         if(($('#city').val()) != 'none')
         return 'false';
            }, 
         "City Must Be Required"
      );
     
	});
	</script>
				<style>
	label.error { width: 250px; display: inline; color: red;}
	#city_error { width: 250px; display: inline; color: red;}
	</style>
<div id="Add_Email_Template">

			<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
				<h1 class="page-header3"><?php echo translate_admin("Add Neighbourhoods Local Knowledge"); ?></h1>
				<div class="but-set">
      	 <span3><input type="submit" onclick="window.location='<?php echo admin_url('neighbourhoods/viewknowledge'); ?>'" value="<?php echo translate_admin('Manage Local Knowledges'); ?>"></span3>
      </div>
	  <div>

<form method="post" id="knowledge" action="<?php echo admin_url('neighbourhoods/addknowledge')?>" enctype="multipart/form-data">					
  <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1" cellpadding="2" cellspacing="0">
<tr>
  <td><?php echo translate_admin('Knowledge'); ?><span style="color: red;">*</span></td>
		<td>
			<textarea class="clsTextBox" name="knowledge" id="knowledge" value="" style="height: 162px; width: 282px;" ></textarea>
				
		</td>
</tr>
<tr>
  <td><?php echo translate_admin('Post ID'); ?><span class="clsRed"></span></td>
		<td id="post_id">
				<select name='post_id' id="post_id" style="width:292px" onchange="get_room_id(this.value)">
				<?php
				if($posts->num_rows() != 0)
				{
					foreach($posts->result() as $row)
					{
						echo '<option>'.$row->id.'</option>';
					}
				}
				?>	
				</select>
		</td>
</tr>
<tr>
  <td><?php echo translate_admin('User Type'); ?><span class="clsRed"></span></td>
		<td>
				<select name='user_type' id="user_type" style="width:292px" onChange='user_type_fun(this.value)'>
				<option id="guest"><?php echo translate_admin('Guest'); ?></option>
				<option id="host"><?php echo translate_admin('Host'); ?></option>
				</select>
		</td>
</tr>
<tr id="room_id_tr" style="display: none">
  <td><?php echo translate_admin('Room ID'); ?><span class="clsRed"></span></td>
		<td>
				<select name='room_id' id="room_id" style="width:292px" onChange='get_title(this.value)'>
				<?php
				if($rooms->num_rows() != 0)
				{
					foreach($rooms->result() as $row)
					{
						?>
						<option> <?php echo $row->id; ?></option>
						<?php
					}
				}
				
				?>	
				</select>
		</td>
</tr>
<tr id="rooms_title_tr" style="display: none">
  <td><?php echo translate_admin('Room Title'); ?><span class="clsRed"></span></td>
		<td id="rooms_title">
				<input type="text" name='room_title' id="rooms_title" value="<?php echo $rooms->row()->title;?>" style="width:292px" readonly>
		</td>
</tr>
<tr>
		     <td><?php echo translate_admin('Is Shown').'?'; ?></td>
		     <td>
							<select name="is_shown" id="is_shown">
							<option value="0"> <?php echo translate_admin('No'); ?> </option>
							<option value="1"> <?php echo translate_admin('Yes'); ?> </option>
							</select>  
							</td>
		  </tr>
<tr>
	<td></td>
	<td>
	<input  name="submit" type="submit" id="submit" value="<?php echo translate_admin('Submit'); ?>">
	</td>
</tr>
		
</table>
</form>	
</div>
</div></div>




            
