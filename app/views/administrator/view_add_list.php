<?php
$places_API = $this->db->get_where('settings', array('code' => 'SITE_GOOGLE_API_ID'))->row()->string_value;
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?php echo $places_API;?>&libraries=places"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script>
	$(document).ready(function()
{
	$("#form").validate({
     rules: {
                address: { 
                	required: true
                }
            },
     messages: {
                  address: {
                  	required: "This field is required"
                  	  }
                  }

});

});
</script>
<script>
	var places_API = '<?php echo $places_API;?>' ;
</script>
	   <script type="text/javascript">

$(document).ready(function () {
	
      var input = document.getElementById('address');
    autocomplete1 = new google.maps.places.Autocomplete(input);    
    google.maps.event.addListener(autocomplete1, 'place_changed', function() {
    	
    	   var place = autocomplete1.getPlace();
      
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();
      //alert(lat+','+lng);
         $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&key="+places_API+"&sensor=true&language=en", function( data ) {

         if(data.status == 'OK')
 	   	{
 	   		
      $('#hidden_lat').val(lat);
      $('#hidden_lng').val(lng);
 	   		
      address = data.results[0].formatted_address;
 	   	$('#hidden_address').val(address);
 	   	 var state_status = 0;
         	   	var addr = {};
 	   	 for (var ii = 0; ii < data.results[0].address_components.length; ii++)
 	   	 {
	   // var street_number = route = street = city = state = zipcode = country = formatted_address = '';
	    var types = data.results[0].address_components[ii].types.join(",");
	    if (types == "street_number"){
	      addr.street_number = data.results[0].address_components[ii].long_name;
	    }
	    if (types == "route" || types == "point_of_interest,establishment"){
	      addr.route = data.results[0].address_components[ii].long_name;
	      // $('#lys_street_address').val(addr.route);
	       if(addr.route == '[object HTMLInputElement]')
	    {
	      $('#lys_street_address').val('');
	    }
	    }
	    if (types == "sublocality,political" || types == "locality,political" || types == "neighborhood,political" || types == "administrative_area_level_3,political"){
	      addr.city = (city == '' || types == "locality,political") ? data.results[0].address_components[ii].long_name : city;
	    $('#city').val(addr.city);
	    if(addr.city == '[object HTMLInputElement]')
	    {
	      $('#city').val('');
	    }
	    }
	    if (types == "administrative_area_level_1,political"){
	    	state_status = 1;
	      addr.state = data.results[0].address_components[ii].long_name;
	      $('#state').val(addr.state);
	       if(addr.state == '[object HTMLInputElement]')
	    {
	      $('#state').val('');
	    }
	    }
	    if(state_status != 1)
	    {
	    	$('#state').val('');
	    }
	    if (types == "postal_code" || types == "postal_code_prefix,postal_code"){
	      addr.zipcode = data.results[0].address_components[ii].long_name;
	    //  $('#zipcode').val(addr.zipcode);
	       if(addr.zipcode == '[object HTMLInputElement]')
	    {
	      $('#zipcode').val('');
	    }
	    }
	    if (types == "country,political"){
	      addr.country = data.results[0].address_components[ii].long_name;

            $('#country').val(addr.country);            

	    }
	  }
	  }
    });
    
});
});

</script>

	<script type="text/javascript">
				function startCallback() {
				if($('#hosting_property_type_id').val() == '')
				{
					$('#hosting_property_type_id_error').show();
		       if($('#hosting_room_type').val() == '')
				{
					$('#hosting_room_type_error').show();
				}
				else
				{
					$('#hosting_room_type_error').hide();
				}
				if($('#hosting_person_capacity').val() == '')
				{
					$('#hosting_person_capacity_error').show();
				}
				else
				{
					$('#hosting_person_capacity_error').hide();
				}
		$('html,body').animate({scrollTop : 0},800);
	return false;
				}
				else 
				if($('#hosting_room_type').val() == '')
				{
					$('#hosting_property_type_id_error').hide();
					$('#hosting_room_type_error').show();
	         if($('#hosting_person_capacity').val() == '')
				{
					$('#hosting_person_capacity_error').show();
				}
				else
				{
					$('#hosting_person_capacity_error').hide();
				}
		$('html,body').animate({scrollTop : 0},800);
	return false;
				}
				else			
				if($('#hosting_person_capacity').val() == '')
				{
					$('#hosting_property_type_id_error').hide();
					$('#hosting_person_capacity_error').show();
					$('#hosting_room_type_error').hide();
		$('html,body').animate({scrollTop : 0},800);
	return false;
				}
				else
				{
				$('#hosting_person_capacity_error').hide();
				$('#hosting_property_type_id_error').hide();
				$('#hosting_room_type_error').hide();	
				}
				
				//document.getElementById('message').innerHTML = '<img src="<?php echo base_url().'images/loading.gif' ?>">';
				// make something useful before submit (onStart)
				return true;
			}

			function completeCallback(response) {
//if(response.length > 50 )
//{
//window.location.href = "<?php echo base_url().'administrator';?>";
//}
//else
if(response == 1)
{
document.getElementById('message').innerHTML = '';
document.getElementById('message_error').innerHTML = "<p>Please give valid address.</p>";	
}
else
{
window.location.href = '<?php echo base_url();?>'+response;
}
			}
			
				function startCallback2() {
				document.getElementById('message2').innerHTML = '<img src="<?php echo base_url().'images/loading.gif' ?>">';
				// make something useful before submit (onStart)
				return true;
			}

			function completeCallback2(response) {

if(response.length > 50 )
{
window.location.href = "<?php echo base_url().'administrator';?>";
}
else{
document.getElementById('message2').innerHTML = response;
}
			}
			
			function startCallback3() {
				
				 $.ajax({
            url: "<?php echo base_url()?>users/check_user",            
            type: "POST",                       
            success: function (result) { 
            	if(result != 1)
            	{
              window.location.href = '<?php echo base_url().'administrator';?>';  
              }                
            }          
            });
         			
				document.getElementById('message3').innerHTML = '<img src="<?php echo base_url().'images/loading.gif' ?>">';
				// make something useful before submit (onStart)
				$('#submit_dis').show();
				$('#image_upload').hide();
				return true;
			}

			function completeCallback3(response) {
			 var res = response;
if(response == 'logout')
{
window.location.href = "<?php echo base_url().'administrator';?>";	
}
			var getSplit = res.split('#'); 
				document.getElementById('galleria_container').innerHTML = getSplit[0];
	
				if(getSplit[1].length > 100 || getSplit[1].substring(0, 3) == 'con')
				{
					window.location.href = "<?php echo base_url().'administrator';?>";
				}
				else if(getSplit[1] == '<p>Please upload correct file.</p>')
				{
					document.getElementById('message3').innerHTML = '';
					document.getElementById('message3_error').innerHTML  = getSplit[1];
				}
				else
				{
					document.getElementById('message3_error').innerHTML  = '';
					document.getElementById('message3').innerHTML = getSplit[1];
				}
				window.photos_form.reset();
				$('#submit_dis').hide();
				$('#image_upload').show();
			}
			
			function startCallback4() {
				document.getElementById('message4').innerHTML = '<img src="<?php echo base_url().'images/loading.gif' ?>">';
				// make something useful before submit (onStart)
				return true;
			}

			function completeCallback4(response) {
				if(response.length > 100 )
{
window.location.href = "<?php echo base_url().'administrator';?>";
}
else
{
				document.getElementById('message4').innerHTML = response;
			}
			}
	
	</script>
 

<script type="text/javascript" src="<?php echo base_url() ?>js/webtoolkit.aim.js"></script>
<div id="View_Edit_List">
    <div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Add Listing'); ?></h1>
	</div>



<div id="description">
<form action="<?php echo admin_url('lists/addlist'); ?>" method="post" id="form" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table add-list-tab" cellpadding="2" cellspacing="0">
<tr>
<td><?php echo translate_admin("Property type"); ?><span class="ClsRed">*</span></td>
<td>
<select style="width:200px;" class="fixed-width" id="hosting_property_type_id" name="property_id">
	<?php echo translate("Select...");?>
			<option selected disabled value=""><?php echo translate("Select...");?>
	<?php 
	
	if($property_types->num_rows() != 0)
	{
		foreach($property_types->result() as $row)
		{
			?>
			<option value="<?php echo $row->id;?>"> <?php echo $row->type; ?></option>
	<?php	}
	}
	?>
			
</select>
<lable class='error' id="hosting_property_type_id_error" style="display: none;">Please choose the Property Type.</label>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Room type"); ?><span class="ClsRed">*</span></td>
<td>
<select style="width:200px;" class="fixed-width" id="hosting_room_type" name="room_type">
	<?php echo translate("Select...");?>
			<option selected disabled value=""><?php echo translate("Select...");?>
<option value="Private room"><?php echo translate_admin("Private room"); ?></option>
<option value="Shared room"><?php echo translate_admin("Shared room"); ?></option>
<option value="Entire Home/Apt"><?php echo translate_admin("Entire home/apt"); ?></option>
</select>
<lable class='error' id="hosting_room_type_error" style="display: none;">Please choose the Room Type.</label>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Accommodates");?><span class="ClsRed">*</span></td>
<td>
<select style="width:200px;" class="fixed-width" id="hosting_person_capacity" name="capacity">
	<?php echo translate("Select...");?>
			<option selected disabled value=""><?php echo translate("Select...");?>
<?php for($i = 1; $i <= 16; $i++) { ?>
<option value="<?php echo $i; ?>"><?php echo $i;  if($i == 16) echo '+'; ?>
</option>
<?php } ?>
</select>
<lable class='error' id="hosting_person_capacity_error" style="display: none;">Please choose the Accommodates.</label>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Address"); ?><span class="ClsRed">*</span></td>
<td><input type="text" size="28" name="address" id='address' value=""></td>
</tr>

<tr>
<td></td>
<td>
<div class="clearfix">
<input type="submit" id="update_desc" name="update_desc" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" />
<p><div id="message"></div></p>
<p><div id="message_error"></div></p>
</div>
</td>
</tr>
<input type="hidden" name="list_id" value="">
<input type="hidden" name="hidden_lat" id="hidden_lat" value="">
<input type="hidden" name="hidden_lng" id="hidden_lng" value="">
<input type="hidden" name="lys_street_address" id="lys_street_address" value="">
<input type="hidden" name="city" id="city" value="">
<input type="hidden" name="state" id="state" value="">
<input type="hidden" name="country" id="country" value="">
<input type="hidden" name="zipcode" id="zipcode" value="">
</table> 
</form>
</div>


<div id="aminities" style="display:none;">
<div class="clsFloatLeft" style="width:98%">
<form action="<?php echo admin_url('lists/managelist'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback2, 'onComplete' : completeCallback2})">
<p style="text-align:left; border-top:4px solid #E3E3E3;">&nbsp;</p>
<div class="clearfix">
					<?php 
				$in_arr = explode(',', $result->amenities);
				$tCount = $amnities->num_rows();
				$i = 1; $j = 1; 
				foreach($amnities->result() as $rows) { if($i == 1) echo '<ul class="amenity_column">'; ?>
							<li>
									<input type="checkbox" <?php if(in_array($j, $in_arr)) echo 'checked="checked"'; ?> name="amenities[]" id="amenity_<?php echo $j; ?>" value="<?php echo $j; ?>">
									<label for="amenity_<?php echo $j; ?>"><?php echo $rows->name; ?> <a title="<?php echo $rows->description; ?>" class="tooltip"><img style="width:16px; height:16px;" src="<?php echo base_url(); ?>images/questionmark_hover.png" alt="Questionmark_hover"></a> </label>
							</li>
							<?php if($i == 8) { $i = 0; echo '</ul>'; } else if($j == $tCount) { echo '</ul>'; } $i++; $j++; } ?>


</div>

<input type="hidden" name="list_id" value="<?php echo $result->id;  ?>">

<div style="clear:both"></div>


<div class="clearfix">
<span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update_aminities" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" /></span>
<span style="float:left; padding:20px 0 0 0;"><div id="message2"></div></span>
</div>
</form>
</div>
<div style="clear:both"></div>
</div>

<div id="photo" style="display:none; text-align:left;">
<div class="clsFloatLeft" style="width:98%">
<form enctype="multipart/form-data" action="<?php echo admin_url('lists/managelist'); ?>" method="post" id="photos_form" onsubmit="return AIM.submit(this, {'onStart' : startCallback3, 'onComplete' : completeCallback3})">
<p style="text-align:left; border-top:4px solid #E3E3E3; padding:10px 0 10px;">
	<?php
	if($list_images->num_rows() > 0)
		{
	?>
	<span> <?php echo translate_admin("Choose checkbox to delete photo and radio button for feature image"); ?> </span>
   <?php
		}
		?>
 <?php 
  echo '<div id="galleria_container">'; 
		if(count($list_images) > 0)
		{
			echo '<ul class="clearfix">';
			$i = 1;
			foreach ($list_images->result() as $image)
			{		
				if($image->is_featured == 1)
					$checked = 'checked="checked"'; 
				else
					$checked = ''; 
								
			  $url = base_url().'images/'.$image->list_id.'/'.$image->name;
						echo '<li>';
			   echo '<p><label><input type="checkbox" name="image[]" value="'.$image->id.'" /></label>';
  				echo '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$image->id.'" /></p>';
						echo '</li>';
						$i++;
			}
			echo '</ul>';
			echo '</div>';
			
		} 
?>

</p>
<input type="hidden" name="list_id" value="<?php echo $result->id;  ?>">
<p> <span style="margin:0 10px 0 0;"> <?php echo translate_admin("Upload new photo"); ?> </span>
<input id="new_photo_image" name="userfile[]"  size="24" type="file" multiple="true"/>
</p>
<div class="clearfix">
<span style="float:left; margin:0 10px 0 0;">
	<input class="clsSubmitBt1" type="submit" name="update_photo" id="image_upload" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" />
</span>
<span style="float:left; margin:0 10px 0 0;display: none;" id="submit_dis">
<!--
-->
</span>	
<span style="float:left; padding:20px 0 0 0;"><div id="message3"></div></span>
<span style="float:left; padding:20px 0 0 0;color:red"><div id="message3_error"></div></span>
</div>
</form>
</div>
<div style="clear:both"></div>
</div>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.validate.js'; ?>"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
<script>
  
  // When the browser is ready...
  $(function() {
  $.validator.addMethod('minStrict', function (value, el, param) {
    return value > param;
});
    // Setup form validation on the #register-form element
    $("#edit_price").validate({
    
        // Specify the validation rules
        rules: {
            nightly: { required:true,number: true,minStrict: 0 },
            weekly: { required:true,number: true,minStrict: 0 },
monthly: { required:true,number: true,minStrict: 0 },
extra: { required:true,number: true,minStrict: 0 },
cleaning: { required:true,number: true,minStrict: 0 },
security: { required:true,number: true,minStrict: 0 },
        },
        
        // Specify the validation error messages
        messages: {
            nightly: { required: "Please enter the nightly price",
number : "Please enter the number.",
minStrict : "Please give the more than 0." },
            weekly: { required: "Please enter the weekly price",
number : "Please enter the number.",
minStrict : "Please give the more than 0." },
            monthly: { required: "Please enter the monthly price",
number : "Please enter the number.",
minStrict : "Please give the more than 0." },
extra: { required: "Please enter the extra price",
number : "Please enter the number.",
minStrict : "Please give the more than 0." },
cleaning: { required: "Please enter the cleaning price",
number : "Please enter the number.",
minStrict : "Please give the more than 0." },
security: { required: "Please enter the security price",
number : "Please enter the number.",
minStrict : "Please give the more than 0." }
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>
<div id="price" style="display:none;">
<form action="<?php echo admin_url('lists/managelist'); ?>" method="post" id="edit_price" onsubmit="return AIM.submit(this, {'onComplete' : completeCallback4})">
<table class="table">

<tr>
<td><?php echo translate_admin("Nightly"); ?>*</td>
<td><input type="text" name="nightly" value="<?php echo $price->night;  ?>"></td>
</tr>

<tr>
<td><?php echo translate_admin("Weekly"); ?>*</td>
<td><input type="text" name="weekly" value="<?php echo $price->week;  ?>"></td>
</tr>


<tr>
<td><?php echo translate_admin("Monthly"); ?>*</td>
<td><input type="text" name="monthly" value="<?php echo $price->month;  ?>"></td>
</tr>


<tr>
<td><?php echo translate_admin("Additional Guests"); ?>*</td>
<td>
<input id="hosting_price_for_extra_person_native" name="extra" size="30" type="text" value=<?php echo $price->addguests; ?> />
&nbsp;<?php echo translate_admin("Per night for each guest after"); ?>                 
<select id="hosting_guests_included" name="guests">
<?php for($i = 1; $i <= 16; $i++) { ?>
<option value="<?php echo $i; ?>"><?php echo $i; if($i == 16) echo '+'; ?> </option>
<?php } ?>
</select>
</td>
</tr>


<tr>
<td><?php echo translate_admin("Cleaning Fees"); ?>*</td>
<td><input id="hosting_extras_price_native" name="cleaning" size="30" type="text" value="<?php echo $price->cleaning;  ?>"></td>
</tr>

<tr>
<td><?php echo translate_admin("Security Fees"); ?>*</td>
<td><input id="hosting_security_price_native" name="security" size="30" type="text" value="<?php echo $price->security;  ?>"></td>
</tr>
<tr>
<td></td>
<td>
<div class="clearfix">
<span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update_price" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" /></span>
<span style="float:left; padding:0 0 0 0;"><div id="message4"></div></span>
</div>
</td>
</tr>
<input type="hidden" name="list_id" value="<?php echo $result->id;  ?>">
</table> 
</form>
</div>

</div>
</div>
</div>

<!-- TinyMCE inclusion -->
<script type="text/javascript" src="<?php echo base_url()?>css/tiny_mce/tiny_mce.js" ></script>

<script language="Javascript">

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true
});

</script>  
<!-- End of inclusion of files -->
<script type="text/javascript">
function showhide(id)
{
		if(id == 1)
		{
		 document.getElementById("description").style.display = "block";
			document.getElementById("aminities").style.display = "none";
			document.getElementById("photo").style.display = "none";
			document.getElementById("price").style.display = "none";
			
		document.getElementById('descriptionA').className = 'clsNav_Act';
		document.getElementById('aminitiesA').className = '';
		document.getElementById('photoA').className = '';
		document.getElementById('priceA').className = '';
		}
		else if(id == 2)
		{
			document.getElementById("aminities").style.display = "block";
			document.getElementById("description").style.display = "none";
			document.getElementById("photo").style.display = "none";
			document.getElementById("price").style.display = "none";
			
		document.getElementById('descriptionA').className = '';
		document.getElementById('aminitiesA').className = 'clsNav_Act';
		document.getElementById('photoA').className = '';
		document.getElementById('priceA').className = '';
		}
		else if(id == 3)
		{
			document.getElementById("photo").style.display = "block";
			document.getElementById("description").style.display = "none";
			document.getElementById("aminities").style.display = "none";
			document.getElementById("price").style.display = "none";
			
		document.getElementById('descriptionA').className = '';
		document.getElementById('aminitiesA').className = '';
		document.getElementById('photoA').className = 'clsNav_Act';
		document.getElementById('priceA').className = '';
		}
		else
		{
		 document.getElementById("price").style.display = "block";
			document.getElementById("description").style.display = "none";
			document.getElementById("aminities").style.display = "none";
			document.getElementById("photo").style.display = "none";
			
		document.getElementById('descriptionA').className = '';
		document.getElementById('aminitiesA').className = '';
		document.getElementById('photoA').className = '';
		document.getElementById('priceA').className = 'clsNav_Act';
		}
}
</script>
