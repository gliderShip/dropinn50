<?php
$places_API = $this->db->get_where('settings', array('code' => 'SITE_GOOGLE_API_ID'))->row()->string_value;
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?php echo $places_API;?>&libraries=places"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<style>
.endcap > b {
    margin-left: -60%;
   }
   #lwlb_availability{
   	    width: 100px;
   }
</style>
<script>
	var places_API = '<?php echo $places_API;?>' ;
</script>

<script src="<?php echo base_url(); ?>js/jquery.validation.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
   jQuery(document).ready(function()
   { 
     jQuery("#editdetails").validate({
    	
         rules: {
                title: 
                {
                	required: true
                }
                
            },
     messages: {
                           title: 
                           {
                           	required: "Please enter the title"
                           },
                           
              },

     });
});

</script>
<script>
	jQuery(document).ready(function()
{
	/* Mouse tracking */
var g_mouse_x = 0;
var g_mouse_y = 0;

  jQuery(document).mousemove(function(e){
    g_mouse_x = e.pageX;
    g_mouse_y = e.pageY;
    jQuery('#mouse_x').val(g_mouse_x);
    jQuery('#mouse_y').val(g_mouse_y);
  });

	var validator = jQuery("#overview_form").submit(function() {
			// update underlying textarea before submit validation
			tinyMCE.triggerSave();
		}).validate({
     rules: {
                title: { 
                	required: true
                },
                 desc: { 
                	required: true
                }
            },
    errorPlacement: function(label, element) {
				// position error label after generated textarea
				if (element.is("textarea")) {
					label.insertAfter(element.next());
				} else {
					label.insertAfter(element);
				}
			}

});

jQuery('#new_photo_image').change(function()
{
	jQuery.ajax({
		url: '<?php echo base_url()."rooms/add_photo_user_login"; ?>',
   	type : 'POST',
   	success : function(data)
   	{
      if(data == '')
      {
      	window.location.href = '<?php echo base_url().'administrator'; ?>';
      }
   	}
	})
})

});
</script>
	   <script type="text/javascript">

jQuery(document).ready(function () {
	
	var input = document.getElementById('address');
    autocomplete = new google.maps.places.Autocomplete(input);    
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
    	
    })
	var input1 = document.getElementById('lys_street_address_edit');
    autocomplete2 = new google.maps.places.Autocomplete(input1);    
    google.maps.event.addListener(autocomplete2, 'place_changed', function() {
    	var place = autocomplete2.getPlace();
      
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();
      //alert(lat+','+lng);
         jQuery.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&sensor=false&key="+places_API+"&language=en", function(data) {
         if(data.status == 'OK')
 	   	{
 	   		
      jQuery('#hidden_lat_edit').val(lat);
      jQuery('#hidden_lng_edit').val(lng);
 	   		
      address = data.results[0].formatted_address;
 	   	jQuery('#hidden_address_edit').val(address);
 	   	 var state_status = 0;
         	   	var addr = {};
 	   	 for (var ii = 0; ii < data.results[0].address_components.length; ii++)
 	   	 {
	    var street_number = route = street = city = state = zipcode = country = formatted_address = '';
	    var types = data.results[0].address_components[ii].types.join(",");
	    if (types == "street_number"){
	      addr.street_number = data.results[0].address_components[ii].long_name;
	    }
	    if (types == "route" || types == "point_of_interest,establishment"){
	      addr.route = data.results[0].address_components[ii].long_name;
	    //   jQuery('#lys_street_address_edit').val(addr.route);
	      /* if(addr.route == '[object HTMLInputElement]')
	    {
	      jQuery('#lys_street_address_edit').val('');
	    }*/
	    }
	    if (types == "sublocality,political" || types == "locality,political" || types == "neighborhood,political" || types == "administrative_area_level_3,political"){
	      addr.city = (city == '' || types == "locality,political") ? data.results[0].address_components[ii].long_name : city;
	    jQuery('#city_edit').val(addr.city);
	    /*if(addr.city == '[object HTMLInputElement]')
	    {
	      jQuery('#city_edit').val('');
	    }*/
	    }
	    if (types == "administrative_area_level_1,political"){
	    	state_status = 1;
	      addr.state = data.results[0].address_components[ii].long_name;
	      jQuery('#state_edit').val(addr.state);
	       /*if(addr.state == '[object HTMLInputElement]')
	    {
	      jQuery('#state_edit').val('');
	    }*/
	    }
	    if(state_status != 1)
	    {
	    	jQuery('#state_edit').val('');
	    }
	    if (types == "postal_code" || types == "postal_code_prefix,postal_code"){
	      addr.zipcode = data.results[0].address_components[ii].long_name;
	      jQuery('#zipcode_edit').val(addr.zipcode);
	       /*if(addr.zipcode == '[object HTMLInputElement]')
	    {
	      jQuery('#zipcode_edit').val('');
	    }*/
	    }
	    if (types == "country,political"){
	      addr.country = data.results[0].address_components[ii].long_name;
	      jQuery("#country_edit option").each(function() {
          if(jQuery(this).text() == jQuery.trim(addr.country)) { 
            jQuery('#country_edit').val(addr.country);            
           }                        
               });
	    }
	  }
	  }
	  else
	  {
	  	jQuery.ajax({
		url: '<?php echo base_url()."rooms/get_address"; ?>',
   	type : 'POST',
    dataType: 'json',
   	data : { room_id: <?php echo $result->id; ?>},
   	success : function(data)
   	{
       jQuery.each(data, function(key, value)
			  {
			  	city = value['city'];
			  	jQuery('#city_edit').val(city);
			  	state = value['state'];
			  	 jQuery('#state_edit').val(state);
                country = value['country'];
                jQuery('#country_edit').val(country);
                jQuery('#hidden_lat_edit').val(value['lat']);
                jQuery('#hidden_lng_edit').val(value['long']); 
                
                jQuery('#zipcode_edit').val(value['zip_code']);
			  })
   	}
	})
	  }
 	  }); 
 	  jQuery('.next_active').css('opacity',1);
 	  jQuery('.disable-btn').hide();
      jQuery('.enable-btn').show();

    })
      var input = document.getElementById('address');
    autocomplete1 = new google.maps.places.Autocomplete(input);    
    google.maps.event.addListener(autocomplete1, 'place_changed', function() {
    	
    	   var place = autocomplete1.getPlace();
      
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();
      //alert(lat+','+lng);
         jQuery.getJSON("http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&key="+places_API+"&sensor=false", function( data ) {

         if(data.status == 'OK')
 	   	{
 	   		
      jQuery('#hidden_lat').val(lat);
      jQuery('#hidden_lng').val(lng);
 	   		
      address = data.results[0].formatted_address;
 	   	jQuery('#hidden_address').val(address);
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
	       jQuery('#lys_street_address').val(addr.route);
	       if(addr.route == '[object HTMLInputElement]')
	    {
	      jQuery('#lys_street_address').val('');
	    }
	    }
	    if (types == "sublocality,political" || types == "locality,political" || types == "neighborhood,political" || types == "administrative_area_level_3,political"){
	      addr.city = (city == '' || types == "locality,political") ? data.results[0].address_components[ii].long_name : city;
	    jQuery('#city').val(addr.city);
	    if(addr.city == '[object HTMLInputElement]')
	    {
	      jQuery('#city').val('');
	    }
	    }
	    if (types == "administrative_area_level_1,political"){
	    	state_status = 1;
	      addr.state = data.results[0].address_components[ii].long_name;
	      jQuery('#state').val(addr.state);
	       if(addr.state == '[object HTMLInputElement]')
	    {
	      jQuery('#state').val('');
	    }
	    }
	    if(state_status != 1)
	    {
	    	jQuery('#state').val('');
	    }
	    if (types == "postal_code" || types == "postal_code_prefix,postal_code"){
	      addr.zipcode = data.results[0].address_components[ii].long_name;
	      jQuery('#zipcode').val(addr.zipcode);
	       if(addr.zipcode == '[object HTMLInputElement]')
	    {
	      jQuery('#zipcode').val('');
	    }
	    }
	    if (types == "country,political"){
	      addr.country = data.results[0].address_components[ii].long_name;

            jQuery('#country').val(addr.country);            

	    }
	  }
	  }
    });
    
});
});

</script>

<script>

function initialize() {
var latlong = new google.maps.LatLng(jQuery('#hidden_lat_edit').val(),jQuery('#hidden_lng_edit').val());
var marker1;
var map1;
var lat;
var lng;
var styles = [ {
    "featureType": "water",
    "elementType": "geometry.fill",
    "stylers": [
      { "weight": 3.3 },
      { "hue": "#00aaff" },
      { "lightness": 100 },
      { "saturation": 93 },
      { "gamma": 0.01 },
      { "color": "#5cb8e4" }
    ]
  }
  ];
  var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});
  
  var mapOptions = {
    zoom: 13,
    center: latlong,
    scrollwheel: false
  };
  map1 = new google.maps.Map(document.getElementById('map-canvas1'), mapOptions);

 	map1.setCenter(latlong);

   marker1 = new google.maps.Marker({
     map:map1,
     draggable:true,
     //animation: google.maps.Animation.DROP,
     position: latlong
   });
map1.mapTypes.set('map-canvas1', styledMap);
  map1.setMapTypeId('map-canvas1');
  google.maps.event.addListener(marker1, 'dragend', function(event)
  {
 	 map1.setCenter(marker1.getPosition());

     lat = marker1.getPosition().lat();
 	 lng = marker1.getPosition().lng();

 	   jQuery.getJSON("http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&key="+places_API+"&sensor=true", function( data ) {

 	   	if(data.status == 'OK')
 	   	{
 	   		jQuery('.disable_finish').hide();
     		jQuery('.enable_finish').show();
     		
 	 jQuery('#hidden_lat_edit').val(lat);
     jQuery('#hidden_lng_edit').val(lng);
     
 	   	address = data.results[0].formatted_address;
 	   	jQuery('#hidden_address_edit').val(address);
 	   	var addr = {};
 	   	 for (var ii = 0; ii < data.results[0].address_components.length; ii++)
 	   	 {
	    var street_number = route = street = city = state = zipcode = country = formatted_address = '';
	    var types = data.results[0].address_components[ii].types.join(",");
	    if (types == "street_number"){
	      addr.street_number = data.results[0].address_components[ii].long_name;
	    }
	    if (types == "route" || types == "point_of_interest,establishment"){
	      addr.route = data.results[0].address_components[ii].long_name;
	        jQuery('#lys_street_address_edit').val(addr.route);
	        if(addr.route == '[object HTMLInputElement]')
	    {
	      jQuery('#lys_street_address_edit').val('');
	    }
	    }
	    if (types == "sublocality,political" || types == "locality,political" || types == "neighborhood,political" || types == "administrative_area_level_3,political"){
	      addr.city = (city == '' || types == "locality,political") ? data.results[0].address_components[ii].long_name : city;
	    jQuery('#city_edit').val(addr.city);
	    if(addr.city == '[object HTMLInputElement]')
	    {
	      jQuery('#city_edit').val('');
	    }
	    }
	    if (types == "administrative_area_level_1,political"){
	      addr.state = data.results[0].address_components[ii].long_name;
	      jQuery('#state_edit').val(addr.state); 
	      if(addr.state == '[object HTMLInputElement]')
	    {
	      jQuery('#state_edit').val('');
	    }
	    }
	    if (types == "postal_code" || types == "postal_code_prefix,postal_code"){
	      addr.zipcode = data.results[0].address_components[ii].long_name;
	      jQuery('#zipcode_edit').val(addr.zipcode);
	      if(addr.zipcode == '[object HTMLInputElement]')
	    {
	      jQuery('#zipcode_edit').val('');
	    }
	    }
	    if (types == "country,political"){
	      addr.country = data.results[0].address_components[ii].long_name;
	      jQuery("#country_edit option").each(function() {
          if(jQuery(this).text() == jQuery.trim(addr.country)) {
              jQuery('#country_edit').val(addr.country);            
           }                        
               });
	    }
	  }
}
else
{
	jQuery.ajax({
		url: '<?php echo base_url()."rooms/get_address"; ?>',
   	type : 'POST',
    dataType: 'json',
   	data : { room_id: <?php echo $result->id; ?>},
   	success : function(data)
   	{
       jQuery.each(data, function(key, value)
			  {
			  	city = value['city'];
			  	jQuery('#city_edit').val(city);
			  	state = value['state'];
			  	 jQuery('#state_edit').val(state);
                country = value['country'];
                jQuery('#country_edit').val(country);
                jQuery('#hidden_lat_edit').val(value['lat']);
                jQuery('#hidden_lng_edit').val(value['long']); 
                jQuery('#zipcode_edit').val(value['zip_code']);
                
			  })
   	}
	})
	
}
 	  }); 
 	
 	 
  });
  }
</script>	
 <style>
      html, body, #map-canvas1 {
        height: 100%;
        margin: 0px;
        padding: 0px;
      }
      #map-canvas1{
      	width:479px;
      	height: 300px;
      }
    </style>

	<script type="text/javascript">
				function startCallback() {
				
				if(jQuery('#hosting_property_type_id').val() == '')
				{
					jQuery('#hosting_property_type_id_error').show();
		       if(jQuery('#hosting_room_type').val() == '')
				{
					jQuery('#hosting_room_type_error').show();
				}
				else
				{
					jQuery('#hosting_room_type_error').hide();
				}
				if(jQuery('#hosting_person_capacity').val() == '')
				{
					jQuery('#hosting_person_capacity_error').show();
				}
				else
				{
					jQuery('#hosting_person_capacity_error').hide();
				}
			jQuery('#update_desc').click(function(){
		jQuery('html,body').animate({scrollTop : 0},800);
	});
	return false;
				}
				else 
				if(jQuery('#hosting_room_type').val() == '')
				{
					jQuery('#hosting_property_type_id_error').hide();
					jQuery('#hosting_room_type_error').show();
	         if(jQuery('#hosting_person_capacity').val() == '')
				{
					jQuery('#hosting_person_capacity_error').show();
				}
				else
				{
					jQuery('#hosting_person_capacity_error').hide();
				}
					jQuery('#update_desc').click(function(){
		jQuery('html,body').animate({scrollTop : 0},800);
	});
	return false;
				}
				else			
				if(jQuery('#hosting_person_capacity').val() == '')
				{
					jQuery('#hosting_property_type_id_error').hide();
					jQuery('#hosting_person_capacity_error').show();
					jQuery('#hosting_room_type_error').hide();
					jQuery('#update_desc').click(function(){
		jQuery('html,body').animate({scrollTop : 0},800);
	});
	return false;
				}
				else
				{
				jQuery('#hosting_person_capacity_error').hide();
				jQuery('#hosting_property_type_id_error').hide();
				jQuery('#hosting_room_type_error').hide();	
				}
				
				document.getElementById('message').innerHTML = '<img src="<?php echo base_url().'images/loading.gif' ?>">';
				// make something useful before submit (onStart)
				return true;
			}

			function completeCallback(response) {
if(response.length > 50 )
{
window.location.href = "<?php echo base_url().'administrator';?>";
}
else if(response == 0)
{
window.location.href = "<?php echo base_url().'administrator/lists/check_valid_id/'.$result->id;?>";
}
else if(response == 'policy')
{
document.getElementById('message_overview').innerHTML = '';
document.getElementById('message_error').innerHTML = "<p>Your chosen Cancellation Policy already deleted.</p>";	
}
else if(response == 1)
{
document.getElementById('message_overview').innerHTML = '';
document.getElementById('message_error').innerHTML = "<p>Please give valid address.</p>";	
}
else
{
document.getElementById('message_overview').innerHTML = response;
jQuery('#message_overview').fadeIn();
jQuery('#message_overview').fadeOut(1500);
}
			}
			
				function completeCallback_edit(response) {
if(response.length > 50 )
{
window.location.href = "<?php echo base_url().'administrator';?>";
}
else if(response == 0)
{
window.location.href = "<?php echo base_url().'administrator/lists/check_valid_id/'.$result->id;?>";
}
else if(response == 'policy')
{
document.getElementById('message_edit').innerHTML = '';
document.getElementById('message_error').innerHTML = "<p>Your chosen Cancellation Policy already deleted.</p>";	
}
else if(response == 1)
{
document.getElementById('message_edit').innerHTML = '';
document.getElementById('message_error').innerHTML = "<p>Please give valid address.</p>";	
}
else
{
document.getElementById('message_edit').innerHTML = response;
}
			}
			
			function completeCallback_listing(response) {
if(response.length > 50 )
{
window.location.href = "<?php echo base_url().'administrator';?>";
}
else if(response == 0)
{
window.location.href = "<?php echo base_url().'administrator/lists/check_valid_id/'.$result->id;?>";
}
else if(response == 'policy')
{
document.getElementById('message').innerHTML = '';
document.getElementById('message_error').innerHTML = "<p>Your chosen Cancellation Policy already deleted.</p>";	
}
else if(response == 1)
{
document.getElementById('message').innerHTML = '';
document.getElementById('message_error').innerHTML = "<p>Please give valid address.</p>";	
}
else
{
document.getElementById('message').innerHTML = response;
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
else if(response == 0)
{
window.location.href = "<?php echo base_url().'administrator/lists/check_valid_id/'.$result->id;?>";
}
else{
document.getElementById('message2').innerHTML = response;
}
			}
			
			function startCallback3() {
				
				 jQuery.ajax({
            url: "<?php echo base_url()?>users/check_user",            
            type: "POST",                       
            success: function (result) { 
            	if(result != 1)
            	{
            //  window.location.href = '<?php echo base_url().'administrator';?>';  
              }                
            }          
            });
         			
				document.getElementById('message3').innerHTML = '<img src="<?php echo base_url().'images/loading.gif' ?>">';
				// make something useful before submit (onStart)
				jQuery('#submit_dis').show();
				jQuery('#image_upload').hide();
				return true;
			}

			function completeCallback3(response) {
				
					 jQuery.ajax({
            url: "<?php echo base_url()?>users/check_user",            
            type: "POST",                       
            success: function (result) { 
            	if(result != 1)
            	{
             // window.location.href = '<?php echo base_url().'administrator';?>';  
              }                
            }          
            });
				
			 var res = response;
if(response == 0)
{
//window.location.href = "<?php echo base_url().'administrator/lists/check_valid_id/'.$result->id;?>";
}
if(response == 'logout')
{
//window.location.href = "<?php echo base_url().'administrator';?>";	
}
			var getSplit = res.split('#'); 
				document.getElementById('galleria_container').innerHTML = getSplit[0];
	
				if(getSplit[1].length > 100 || getSplit[1].substring(0, 3) == 'con')
				{
					//window.location.href = "<?php echo base_url().'administrator';?>";
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
				jQuery('#submit_dis').hide();
				jQuery('#image_upload').show();
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
else if(response == 0)
{
window.location.href = "<?php echo base_url().'administrator/lists/check_valid_id/'.$result->id;?>";
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
	 <h1 class="page-header3"><?php 
	 
	 if($this->uri->segment(4) == '' || $this->input->get('month') != '')
	 echo translate_admin('Edit Listing');
	 else
	 echo translate_admin('Add Listing');
	 ?></h1>
		
		<div class="">   
    	<span3><input type="submit" id="aminitiesA" onclick="javascript:showhide('2');" value="<?php echo translate_admin('Amenities'); ?>"></span3>
             	<?php 
	 if($this->uri->segment(3) == 'addlist')
	 {?>	
	 		<div class="">
	 		<span4><input type="submit" id="" onclick="javascript:showhide('1');" value="<?php echo translate_admin('Listing'); ?>"></span4>
          	<span4><input type="submit" id="" onclick="javascript:showhide('7');" value="<?php echo translate_admin('Address'); ?>"></span4>
          	<span4><input type="submit" id="" onclick="javascript:showhide('3');" value="<?php echo translate_admin('Photos'); ?>"></span4>
          	<span4><input type="submit" id="" onclick="javascript:showhide('6');" value="<?php echo translate_admin('Overview'); ?>"></span4>
          	<span4><input type="submit" id="" onclick="javascript:showhide('4');" value="<?php echo translate_admin('Pricing'); ?>"></span4>
          	<span4><input type="submit" id="" onclick="javascript:showhide('5');" value="<?php echo translate_admin('Calendar'); ?>"></span4>          	
          	</div>

	<?php }
	 else
	 	{
	 		?>
	 		<span3><input type="submit" id="descriptionA" class="" onclick="javascript:showhide('8');" value="<?php echo translate_admin('Calendar'); ?>"></span3>
	 		<span3><input type="submit" id="photoA" onclick="javascript:showhide('3');" value="<?php echo translate_admin('Photos'); ?>"></span3>
	 		<span3><input type="submit" id="priceA" onclick="javascript:showhide('4');" value="<?php echo translate_admin('Pricing'); ?>"></span3>
	 		<span3><input type="submit" id="descriptionA" class="clsNav_Act" onclick="javascript:showhide('1');" value="<?php echo translate_admin('Description'); ?>"></span3>
	 		<?php
	 	} ?>
	</div>
	</div>

<?php if($this->uri->segment(3) == 'addlist')
	{
		if($this->input->get('month') != '')
{
		 ?>
		 <div id="description">
		 <?php
}
else {
	?>
	<div id="description" style="display:none">
	<?php
}
?>
<form id="edit" action="<?php echo admin_url('lists/managelist'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback_listing})">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
<td style="padding:20px 0px 0px 8px;"><?php echo translate_admin("Property type"); ?></td>
<td style="padding:20px 0px 0px 8px;">
<select style="width:200px;" class="fixed-width" id="hosting_property_type_id" name="property_id">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
	<?php 
	
	if($property_types->num_rows() != 0)
	{
		foreach($property_types->result() as $row)
		{
			?>
			<option value="<?php echo $row->id;?>" <?php if($result->property_id == $row->id) echo 'selected=selected'; ?>> <?php echo $row->type; ?></option>
	<?php	}
	}
	?>
			
</select>
<lable class='error' id="hosting_property_type_id_error" style="display: none;">Please choose the Property Type.</label>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Room type"); ?></td>
<td>
<select style="width:200px;" class="fixed-width" id="hosting_room_type" name="room_type">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<option value="Private room" <?php if($result->room_type == 'Private room') echo 'selected=selected'; ?>><?php echo translate_admin("Private room"); ?></option>
<option value="Shared room" <?php if($result->room_type == 'Shared room') echo 'selected=selected'; ?>><?php echo translate_admin("Shared room"); ?></option>
<option value="Entire Home/Apt" <?php if($result->room_type == 'Entire Home/Apt') echo 'selected=selected'; ?>><?php echo translate_admin("Entire home/apt"); ?></option>
</select>
<lable class='error' id="hosting_room_type_error" style="display: none;">Please choose the Room Type.</label>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Accommodates");?></td>
<td>
<select style="width:200px;" class="fixed-width" id="hosting_person_capacity" name="capacity">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<?php for($i = 1; $i <= 16; $i++) { ?>
<option value="<?php echo $i; ?>" <?php if($result->capacity == $i) echo 'selected'; ?>><?php echo $i;  if($i == 16) echo '+'; ?>
</option>
<?php } ?>
</select>
<lable class='error' id="hosting_person_capacity_error" style="display: none;">Please choose the Accommodates.</label>

</td>
</tr>

<tr>
<td><?php echo translate_admin("Bedrooms"); ?></td>
<td>
<select style="width:200px;" class="fixed-width" id="hosting_bedrooms" name="bedrooms">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<?php for($i = 1; $i <= 16; $i++) { ?>
<option value="<?php echo $i; ?>"<?php if($result->bedrooms == $i) echo 'selected'; ?>><?php echo $i; if($i == 16) echo '+'; ?> </option>
<?php } ?>
</select>
</td>
</tr>


<tr>
<td><?php echo translate_admin("Beds"); ?></td>
<td>
<select class="fixed-width" id="hosting_beds" name="beds">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<?php for($i = 1; $i <= 16; $i++) { ?>
<option value="<?php echo $i; ?>"<?php if($result->beds == $i) echo 'selected'; ?>><?php echo $i; if($i == 16) echo '+'; ?> </option>
<?php } ?>
</select>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Bed type"); ?></td>
<td>
	<select class="fixed-width" id="hosting_bed_type" name="hosting_bed_type">
<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
	<option value="Airbed"<?php if($result->bed_type == 'Airbed') echo 'selected'; ?>><?php echo translate_admin("Airbed");?></option>
	<option value="Futon"<?php if($result->bed_type == 'Futon') echo 'selected'; ?>><?php echo translate_admin("Futon");?></option>
	<option value="Pull-out Sofa"<?php if($result->bed_type == 'Pull-out Sofa') echo 'selected'; ?>><?php echo translate_admin("Pull-out Sofa");?></option>
	<option value="Couch"<?php if($result->bed_type == 'Couch') echo 'selected'; ?>><?php echo translate_admin("Couch");?></option>
	<option value="Real Bed"<?php if($result->bed_type == 'Real Bed') echo 'selected'; ?>><?php echo translate_admin("Real Bed");?></option>
	</select>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Bathrooms"); ?></td>
<td>
<select name="hosting_bathrooms" id="hosting_bathrooms" class="fixed-width">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<option value="0"<?php if($result->bathrooms == '0') echo 'selected'; ?>>0</option> 
<option value="0.5"<?php if($result->bathrooms == '0.5') echo 'selected'; ?>>0.5 </option>
<option value="1"<?php if($result->bathrooms == '1') echo 'selected'; ?>>1 </option>
<option value="1.5"<?php if($result->bathrooms == '1.5') echo 'selected'; ?>>1.5 </option>
<option value="2"<?php if($result->bathrooms == '2') echo 'selected'; ?>>2 </option>
<option value="2.5"<?php if($result->bathrooms == '2.5') echo 'selected'; ?>>2.5 </option>
<option value="3"<?php if($result->bathrooms == '3') echo 'selected'; ?>>3 </option>
<option value="3.5"<?php if($result->bathrooms == '3.5') echo 'selected'; ?>>3.5 </option>
<option value="4"<?php if($result->bathrooms == '4') echo 'selected'; ?>>4 </option>
<option value="4.5"<?php if($result->bathrooms == '4.5') echo 'selected'; ?>>4.5 </option>
<option value="5"<?php if($result->bathrooms == '5') echo 'selected'; ?>>5 </option>
<option value="5.5"<?php if($result->bathrooms == '5.5') echo 'selected'; ?>>5.5 </option>
<option value="6"<?php if($result->bathrooms == '6') echo 'selected'; ?>>6 </option>
<option value="6.5"<?php if($result->bathrooms == '6.5') echo 'selected'; ?>>6.5 </option>
<option value="7"<?php if($result->bathrooms == '7') echo 'selected'; ?>>7 </option>
<option value="7.5"<?php if($result->bathrooms == '7.5') echo 'selected'; ?>>7.5 </option>
<option value="8"<?php if($result->bathrooms == '8') echo 'selected'; ?>>8+ </option>
</select>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Cancellation Policy"); ?></td>
<?php //print_r($result); exit;?>
<td>
	<select name="cancellation_policy" id="cancellation_policy" class="fixed-width">
     <?php
  foreach($cancellation_policy_data->result() as $policy)
  {
  	?>
  	<option value="<?php echo $policy->id;?>" <?php if($policy->id == $result->cancellation_policy) echo 'selected'; ?>><?php echo $policy->name;?></option>
  	<?php
  }
  ?> 
    </select>
</td>
</tr>

<tr>
<td><?php echo translate_admin("House Manual"); ?></td>
<td><textarea id="hosting_house_manual" name="manual" class="manual" size="115"><?php echo $result->house_rule; ?></textarea></td>
</tr>
<input type="hidden" value="<?php echo $result->id;?>" name="id">
<tr>
<td></td>
<td>
<div class="clearfix">
<input class="clsSubmitBt1" type="submit" id="add_desc" name="add_desc" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" />
<p><div id="message"></div></p>
<span style="float:left; padding:20px 0 0 0;color:red"><div id="message_error"></div></span>
</div>
</td>
</tr>
<input type="hidden" name="list_id" value="<?php echo $result->id;  ?>">
<input type="hidden" name="hidden_lat" id="hidden_lat" value="<?php echo $result->lat;  ?>">
<input type="hidden" name="hidden_lng" id="hidden_lng" value="<?php echo $result->long;  ?>">
<input type="hidden" name="lys_street_address" id="lys_street_address" value="<?php echo $result->street_address; ?>">
<input type="hidden" name="city" id="city" value="<?php echo $result->city;  ?>">
<input type="hidden" name="state" id="state" value="<?php echo $result->state;  ?>">
<input type="hidden" name="zipcode" id="zipcode" value="<?php echo $result->zip_code;  ?>">
<input type="hidden" name="country" id="country" value="<?php echo $result->country;  ?>">
</table> 
</form>
</div></div>

		<?php
	}
	 else {
	 	if($this->input->get('month') != '')
{
		 ?>
		 
		 <div id="description" style="display: none">
		 <?php
}
else {
	?>
	<div id="description">
	<?php
}
?>
<form id="editdetails" action="<?php echo admin_url('lists/managelist'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback_edit})">
<div class="col-md-9 col-xs-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
<td style="padding:20px 0px 0px 8px;"><?php echo translate_admin("Property type"); ?></td>
<td style="padding:20px 0px 0px 8px;">
<select style="width:200px;" class="fixed-width" id="hosting_property_type_id" name="property_id">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
	<?php 
	
	if($property_types->num_rows() != 0)
	{
		foreach($property_types->result() as $row)
		{
			?>
			<option value="<?php echo $row->id;?>" <?php if($result->property_id == $row->id) echo 'selected=selected'; ?>> <?php echo $row->type; ?></option>
	<?php	}
	}
	?>
			
</select>
<lable class='error' id="hosting_property_type_id_error" style="display: none;">Please choose the Property Type.</label>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Room type"); ?></td>
<td>
<select style="width:200px;" class="fixed-width" id="hosting_room_type" name="room_type">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<option value="Private room" <?php if($result->room_type == 'Private room') echo 'selected=selected'; ?>><?php echo translate_admin("Private room"); ?></option>
<option value="Shared room" <?php if($result->room_type == 'Shared room') echo 'selected=selected'; ?>><?php echo translate_admin("Shared room"); ?></option>
<option value="Entire Home/Apt" <?php if($result->room_type == 'Entire Home/Apt') echo 'selected=selected'; ?>><?php echo translate_admin("Entire home/apt"); ?></option>
</select>
<lable class='error' id="hosting_room_type_error" style="display: none;">Please choose the Room Type.</label>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Accommodates");?></td>
<td>
<select style="width:200px;" class="fixed-width" id="hosting_person_capacity" name="capacity">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<?php for($i = 1; $i <= 16; $i++) { ?>
<option value="<?php echo $i; ?>" <?php if($result->capacity == $i) echo 'selected'; ?>><?php echo $i;  if($i == 16) echo '+'; ?>
</option>
<?php } ?>
</select>
<lable class='error' id="hosting_person_capacity_error" style="display: none;">Please choose the Accommodates.</label>

</td>
</tr>

<tr>
<td><?php echo translate_admin("Bedrooms"); ?></td>
<td>
<select style="width:200px;" class="fixed-width" id="hosting_bedrooms" name="bedrooms">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<?php for($i = 1; $i <= 16; $i++) { ?>
<option value="<?php echo $i; ?>"<?php if($result->bedrooms == $i) echo 'selected'; ?>><?php echo $i; if($i == 16) echo '+'; ?> </option>
<?php } ?>
</select>
</td>
</tr>


<tr>
<td><?php echo translate_admin("Beds"); ?></td>
<td>
<select class="fixed-width" id="hosting_beds" name="beds">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<?php for($i = 1; $i <= 16; $i++) { ?>
<option value="<?php echo $i; ?>"<?php if($result->beds == $i) echo 'selected'; ?>><?php echo $i; if($i == 16) echo '+'; ?> </option>
<?php } ?>
</select>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Bed type"); ?></td>
<td>
	<select class="fixed-width" id="hosting_bed_type" name="hosting_bed_type">
<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
	<option value="Airbed"<?php if($result->bed_type == 'Airbed') echo 'selected'; ?>><?php echo translate_admin("Airbed");?></option>
	<option value="Futon"<?php if($result->bed_type == 'Futon') echo 'selected'; ?>><?php echo translate_admin("Futon");?></option>
	<option value="Pull-out Sofa"<?php if($result->bed_type == 'Pull-out Sofa') echo 'selected'; ?>><?php echo translate_admin("Pull-out Sofa");?></option>
	<option value="Couch"<?php if($result->bed_type == 'Couch') echo 'selected'; ?>><?php echo translate_admin("Couch");?></option>
	<option value="Real Bed"<?php if($result->bed_type == 'Real Bed') echo 'selected'; ?>><?php echo translate_admin("Real Bed");?></option>
	</select>
</td>
</tr>

<tr>
<td><?php echo translate_admin("Bathrooms"); ?></td>
<td>
<select name="hosting_bathrooms" id="hosting_bathrooms" class="fixed-width">
	<?php echo translate("Select...");?>
	<option selected disabled value=""><?php echo translate("Select...");?>
<option value="0"<?php if($result->bathrooms == '0') echo 'selected'; ?>>0</option> 
<option value="0.5"<?php if($result->bathrooms == '0.5') echo 'selected'; ?>>0.5 </option>
<option value="1"<?php if($result->bathrooms == '1') echo 'selected'; ?>>1 </option>
<option value="1.5"<?php if($result->bathrooms == '1.5') echo 'selected'; ?>>1.5 </option>
<option value="2"<?php if($result->bathrooms == '2') echo 'selected'; ?>>2 </option>
<option value="2.5"<?php if($result->bathrooms == '2.5') echo 'selected'; ?>>2.5 </option>
<option value="3"<?php if($result->bathrooms == '3') echo 'selected'; ?>>3 </option>
<option value="3.5"<?php if($result->bathrooms == '3.5') echo 'selected'; ?>>3.5 </option>
<option value="4"<?php if($result->bathrooms == '4') echo 'selected'; ?>>4 </option>
<option value="4.5"<?php if($result->bathrooms == '4.5') echo 'selected'; ?>>4.5 </option>
<option value="5"<?php if($result->bathrooms == '5') echo 'selected'; ?>>5 </option>
<option value="5.5"<?php if($result->bathrooms == '5.5') echo 'selected'; ?>>5.5 </option>
<option value="6"<?php if($result->bathrooms == '6') echo 'selected'; ?>>6 </option>
<option value="6.5"<?php if($result->bathrooms == '6.5') echo 'selected'; ?>>6.5 </option>
<option value="7"<?php if($result->bathrooms == '7') echo 'selected'; ?>>7 </option>
<option value="7.5"<?php if($result->bathrooms == '7.5') echo 'selected'; ?>>7.5 </option>
<option value="8"<?php if($result->bathrooms == '8') echo 'selected'; ?>>8+ </option>
</select>
</td>
</tr>


<tr>
<td><?php echo translate_admin("Title"); ?></td>

<td><input type="text" size="28" name="title" id="title" value="<?php echo $result->title;  ?>">

</td>
</tr>

<tr>
<td><?php echo translate_admin("Address"); ?></td>
<td><input type="text" size="28" name="address" id='address' value="<?php echo $result->address; ?>"></td>
</tr>

<tr>
<td><?php echo translate_admin("Cancellation Policy"); ?></td>
<?php //print_r($result); exit;?>
<td>
	<select name="cancellation_policy" id="cancellation_policy" class="fixed-width">
    <?php
  foreach($cancellation_policy_data->result() as $policy)
  {
  	?>
  	<option value="<?php echo $policy->id;?>" <?php if($policy->id == $result->cancellation_policy) echo 'selected'; ?>><?php echo $policy->name;?></option>
  	<?php
  }
  ?> 
   </select>
</td>
</tr>

<tr>
<td><?php echo translate_admin("House Manual"); ?></td>
<td><textarea id="hosting_house_manual" name="manual" class="manual" size="115"><?php echo $result->house_rule; ?></textarea></td>
</tr>

<tr>
<td><?php echo translate_admin("Description"); ?></td>
<td><textarea type="text" name="desc" maxlength="250" class="description"><?php echo $result->desc;  ?></textarea></td>
</tr>
<input type="hidden" name="id" value="<?php echo $result->id;?>">
<tr>
<td></td>
<td>
<div class="clearfix">
<input class="clsSubmitBt1" type="submit" id="update_desc" name="update_desc" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" />
<p><div id="message_edit"></div></p>
<span style="float:left; padding:20px 0 0 0;color:red"><div id="message_error"></div></span>
</div>
</td>
</tr>
<input type="hidden" name="list_id" value="<?php echo $result->id;  ?>">
<input type="hidden" name="hidden_lat" id="hidden_lat" value="<?php echo $result->lat;  ?>">
<input type="hidden" name="hidden_lng" id="hidden_lng" value="<?php echo $result->long;  ?>">
<input type="hidden" name="lys_street_address" id="lys_street_address" value="<?php echo $result->street_address; ?>">
<input type="hidden" name="city" id="city" value="<?php echo $result->city;  ?>">
<input type="hidden" name="state" id="state" value="<?php echo $result->state;  ?>">
<input type="hidden" name="zipcode" id="zipcode" value="<?php echo $result->zip_code;  ?>">
<input type="hidden" name="country" id="country" value="<?php echo $result->country;  ?>">
</table> 
</div>
</form>
</div>
		 <?php
	 }
	?>
<div id="aminities" style="display:none;">
<div class="col-xs-9 col-md-9 col-sm-9">
<form action="<?php echo admin_url('lists/managelist'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback2, 'onComplete' : completeCallback2})">
<p style="text-align:left; margin: 10px 0px 0px 0px;">&nbsp;</p>
<div class="clearfix">
					<?php 
				$in_arr = explode(',', $result->amenities);
				$tCount = $amnities->num_rows();
				$i = 1; $j = 1; 
				foreach($amnities->result() as $rows) { if($i == 1) echo '<ul class="amenity_column">'; ?>
							<li>
									<input type="checkbox" <?php if(in_array($rows->id, $in_arr)) echo 'checked="checked"'; ?> name="amenities[]" id="amenity_<?php echo $j; ?>" value="<?php echo $rows->id; ?>">
									<label for="amenity_<?php echo $j; ?>"><?php echo $rows->name; ?> <a title="<?php echo $rows->description; ?>" class="tooltip"><img style="width:16px; height:16px;" src="<?php echo base_url(); ?>images/questionmark_hover.png" alt="Questionmark_hover"></a> </label>
							</li>
							<?php if($i == 8) { $i = 0; echo '</ul>'; } else if($j == $tCount) { echo '</ul>'; } $i++; $j++; } ?>


</div>

<input type="hidden" name="list_id" value="<?php echo $result->id;  ?>">

<div style="clear:both"></div>


<div class="clearfix">
<input type="submit" name="update_aminities" value="<?php echo translate_admin("Update"); ?>"/>
<span style="float:left; padding:20px 0 0 0;"><div id="message2"></div></span>
</div>
</form>
</div>
<div style="clear:both"></div>
</div>

<div id="photo" style="display:none; text-align:left;">
<form enctype="multipart/form-data" action="<?php echo admin_url('lists/managelist'); ?>" method="post" id="photos_form" onsubmit="return AIM.submit(this, {'onStart' : startCallback3, 'onComplete' : completeCallback3})">
<div class="col-xs-9 col-md-9 col-sm-9">
<p style="text-align:left; margin: 10px 0px 0px 0px;">
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
						echo '<div class="panel-body panel-condensed">
      							<textarea rows="3" cols="18" class="highlight" name="highlight" id="highlight_'.$image->id.'" placeholder="'.translate_admin("What are the highlights of this photo?").'" class="input-large" onkeyup="highlight1('.$image->id.')">'.trim($image->highlights).'</textarea>
      								</div></li>';
						$i++;
			}
			echo '</ul>';
			echo '</div>';
			
		} 
?>

</p>
<table class="table" cellpadding="2" cellspacing="0">
<tr>
<input type="hidden" name="list_id" value="<?php echo $result->id;  ?>">
<td><span style="margin:0 10px 0 0;"> <?php echo translate_admin("Upload new photo"); ?> </span></td>
<td><input id="new_photo_image" name="userfile[]"  size="24" type="file" multiple="true"/></td>
</tr>
<tr>
	<td>&#160;</td>
<td>
<div class="clearfix">
	<input type="submit" name="update_photo" id="image_upload" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" />
	<!-- <input class="can-but" type="submit" name="update_photo" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" disabled/> -->

<p><div id="message3"></div></p>
<span style="float:left; padding:20px 0 0 0;color:red"><div id="message3_error"></div></span>
</div>
</td></tr></table>
</form>
</div>
<div style="clear:both"></div>
</div>


<script type="text/javascript" src="<?php echo base_url().'js/jquery.validate.js'; ?>"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
<script>
  // When the browser is ready...
  jQuery(function() {
  jQuery.validator.addMethod('minStrict', function (value, el, param) {
    return value > param;
});
    // Setup form validation on the #register-form element
    jQuery("#edit_price").validate({
    
        // Specify the validation rules
        rules: {
	            nightly: { 
	            	required:true,
	            	number: true,
	            	//minStrict: 9 
	            	    },
            	    
				    /*      weekly: { required:true,number: true,minStrict: 0 },
				monthly: { required:true,number: true,minStrict: 0 },
				extra: { required:true,number: true,minStrict: 0 },
				cleaning: { required:true,number: true,minStrict: 0 },
				security: { required:true,number: true,minStrict: 0 },*/
        },
        
        // Specify the validation error messages
        messages: 
        {
            nightly: 
               { 
               	  required: "Please enter the nightly price",
                  number : "Please enter the number.",
                  //minStrict : "Please give the more than 9." 
              },
          			/*	  weekly: { required: "Please enter the weekly price",
						number : "Please enter the number.",
						minStrict : "Please give the more than 0."},
						            monthly: { required: "Please enter the monthly price",
						number : "Please enter the number.",
						minStrict : "Please give the more than 0." },
						extra: { required: "Please enter the extra price",
						number : "Please enter the number.",
						minStrict : "Please give the more than 0."  },
						cleaning: { required: "Please enter the cleaning price",
						number : "Please enter the number.",
						minStrict : "Please give the more than 0." },
						security: { required: "Please enter the security price",
						number : "Please enter the number.",
						minStrict : "Please give the more than 0." }*/
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>
 
<script>
			
	jQuery('document').ready(function()
	{
		 var cur=(jQuery('#currency_drop :selected').val());
                 
                 jQuery.ajax({
            url: "<?php echo base_url()?>administrator/lists/curr_diff",            
            type: "POST",    
             data: "currency="+cur,                   
            success: function (result) { 
            	     jQuery('#nightly').rules("add", { 
			           				         min:result,
			           				        
			           				         	});         
            }          
            });
             jQuery('#nightly').change(function()
  	     {
              //  alert('success');
         
               // alert(jQuery('#currency_drop :selected').val());
                 var cur=(jQuery('#currency_drop :selected').val());
                 
                 jQuery.ajax({
            url: "<?php echo base_url()?>administrator/lists/curr_diff",            
            type: "POST",    
             data: "currency="+cur,                   
            success: function (result) { 
            	     jQuery('#nightly').rules("add", { 
			           				         min:result,
			           				        
			           				         	});         
            }          
            });
           
         
          });
         jQuery('#currency_drop').change(function()
  	     {
              //  alert('success');
         
               // alert(jQuery('#currency_drop :selected').val());
                 var cur=(jQuery('#currency_drop :selected').val());
                 
                 jQuery.ajax({
            url: "<?php echo base_url()?>administrator/lists/curr_diff",            
            type: "POST",    
             data: "currency="+cur,                   
            success: function (result) { 
            	     jQuery('#nightly').rules("add", { 
			           				         min:result,
			           				        
			           				         	});         
            }          
            });
           
         
          });  
     });

</script>	
<div id="price" style="display:none;">
<form action="<?php echo admin_url('lists/managelist'); ?>" method="post" id="edit_price" onsubmit="return AIM.submit(this, {'onComplete' : completeCallback4})">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
<td style="padding: 20px 0px 0px 8px;"><?php echo translate_admin("Nightly"); ?><span class="ClsRed">*</span></td>
<td style="padding: 20px 0px 0px 8px;"><input type="text" name="nightly" id="nightly" value="<?php echo $price->night;  ?>"></td>
</tr>

<tr>
<td><?php echo translate_admin("Weekly"); ?></td>
<td><input type="text" name="weekly" value="<?php echo $price->week;  ?>"></td>
</tr>


<tr>
<td><?php echo translate_admin("Monthly"); ?></td>
<td><input type="text" name="monthly" value="<?php echo $price->month;  ?>"></td>
</tr>


<tr>
<td><?php echo translate_admin("Additional Guests"); ?></td>
<td>
<input id="hosting_price_for_extra_person_native" name="extra" size="30" type="text" value=<?php echo $price->addguests; ?> />
&nbsp;<?php echo translate_admin("Per night for each guest after"); ?>                 
<select style="margin:10px 0px 0px 0px;" id="hosting_guests_included" name="guests">
<?php for($i = 1; $i <= 16; $i++) { ?>
<option value="<?php echo $i; ?>"><?php echo $i; if($i == 16) echo '+'; ?> </option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td><?php echo translate_admin("Cleaning Fees"); ?></td>
<td><input id="hosting_extras_price_native" name="cleaning" size="30" type="text" value="<?php echo $price->cleaning;  ?>"></td>
</tr>

<tr>
<td><?php echo translate_admin("Security Fees"); ?></td>
<td><input id="hosting_security_price_native" name="security" size="30" type="text" value="<?php echo $price->security;  ?>"></td>
</tr>
<tr>
<td><?php echo translate_admin("Currency"); ?></td>
<td>
<select class="currency_price" name="currency" id="currency_drop" style="height: 37px;">
			<?php foreach($currency_result->result() as $row)
			{
				print_r($row->currency_code);
				//exit;
				?>
				<option value="<?php echo $row->currency_code;?>" <?php if($currency_value == $row->currency_code) echo 'selected';  ?>><?php echo $row->currency_code;?></option>
				<?php
			}
			?>
		</select>
</td></tr>
<tr>
<td></td>
<td>
<div class="clearfix">
<input type="submit" name="update_price" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" /></span>
<p><div id="message4"></div></p>
</div>
</td>
</tr>
<input type="hidden" name="list_id" value="<?php echo $result->id;  ?>">
</table> 
</form>
</div>
</div>


<?php
if($this->uri->segment('3') == 'addlist')
{
?>
<div id="calendar">
<div class="col-md-9 col-xs-9 col-sm-9">
<?php
}
else {
	?>
	<div id="calendar" style="display: none">
	<div class="col-md-9 col-xs-9 col-sm-9">
	<?php
}
?>
<img src="<?php echo base_url();?>images/calender_list-2.png" id="calendar_icon">
<img src="<?php echo base_url();?>images/cal-hover.png" id="calendar_icon1" style="display: none">	
</div>
</div>

<div id="overview" style="display: none">
<form action="<?php echo admin_url('lists/managelist'); ?>" id="overview_form" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
<td style="padding: 20px 0px 0px 8px;"><?php echo translate_admin("Title"); ?><span class="ClsRed">*</span></td>
<td style="padding: 20px 0px 0px 8px;"><input type="text" size="28" name="title" value="<?php echo $result->title;  ?>">
</td>
</tr>  

<tr>
<td><?php echo translate_admin("Description"); ?><span class="ClsRed">*</span></td>
<td><textarea name="desc" class="description"><?php echo $result->desc;  ?></textarea></td>
</tr>
<input type="hidden" name="id" value="<?php echo $result->id; ?>">
<tr>
<td></td>
<td>
<div class="clearfix">
<input class="clsSubmitBt1" type="submit" id="update_overview" name="update_overview" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" />
<p><div id="message_overview"></div></p>
<span style="float:left; padding:20px 0 0 0;color:red"><div id="message_error_overview"></div></span>
</div>
</td>
</tr>
<input type="hidden" name="list_id" value="<?php echo $result->id;  ?>">
<input type="hidden" name="hidden_lat" id="hidden_lat" value="<?php echo $result->lat;  ?>">
<input type="hidden" name="hidden_lng" id="hidden_lng" value="<?php echo $result->long;  ?>">
<input type="hidden" name="lys_street_address" id="lys_street_address" value="<?php echo $result->street_address; ?>">
<input type="hidden" name="city" id="city" value="<?php echo $result->city;  ?>">
<input type="hidden" name="state" id="state" value="<?php echo $result->state;  ?>">
<input type="hidden" name="zipcode" id="zipcode" value="<?php echo $result->zip_code;  ?>">
<input type="hidden" name="country" id="country" value="<?php echo $result->country;  ?>">
</table> 
</form>
</div>
</div>

<div id="address" style="display: none">
	<div class="col-md-9 col-xs-9 col-sm-9">
	<div class="modal-list modal-with-background-image1" id="address_popup1" style="display: none">
 <div id="my_form">
  
  <div class="col-md-9 col-xs-9 col-sm-9"
  <div class="modal-content">
  
  <div class="">
  <div class="panel-header">
    <div class="media-body">
        <h1 class="page-header">
          <p><span style="font-size:18px;"><?php echo translate_admin("Enter Address");?></span></p>
          <p><small class="addrs_small"><?php echo translate_admin("What is your listing's address?");?></small></p>
        </h3>
     </div>
    </div>
  <div class="panel-body">
<!--<form id="js-address-form">-->
  <table>
  	<tr>
  		<td style="padding: 20px 0px 0px 0px;">
    <label for="country" class="label-large"><?php echo translate_admin("Country");?></label>
    </td>
    <td style="padding: 20px 0px 0px 0px;">
    
  <select id="country_edit" name="country_code">
  	<?php 
  	if(isset($country_list))
	{
  	foreach($country_list->result() as $country_list1)
  	{
  		if($country == $country_list1->country_name)
		{
			$selected = 'selected';
		}
		else
	    {
		$selected = '';
        }
  		echo '<option value="'.$country_list1->country_name.'"'.$selected.'>'.$country_list1->country_name.'</option>';
  	}
  	}?>
       
  </select>

 </td>
 </tr>
  <tr>
  	<td style="padding: 20px 0px 0px 0px;">
    <label for="street" class="label-large"><?php echo translate_admin("Street Address");?></label>
    </td>
    <td style="padding: 20px 0px 0px 0px;">
    <input type="text" size=34 placeholder="e.g. 123 Main St." class="input-large" value="<?php echo $street_address; ?>" id="lys_street_address_edit">
 </td>
 </tr>
<input type="hidden" name="hidden_address" id="hidden_address_edit"/>
<input type="hidden" name="hidden_lat" id="hidden_lat_edit"/>
<input type="hidden" name="hidden_lng" id="hidden_lng_edit"/>
  <tr>
  	<td style="padding: 20px 0px 0px 0px;">
    <label for="apt" class="label-large"><?php echo translate_admin("Apt, Suite, Bldg. (optional)");?></label>
    </td>
    <td style="padding: 20px 0px 0px 0px;">
    <input type="text" size=34 placeholder="e.g. Apt #7" class="input-large" value="<?php echo $optional_address; ?>" id="apt_edit" name="apt">
    </td>
  </tr>
<tr>
  	<td style="padding: 20px 0px 0px 0px;">
    <label for="city" class="label-large"><?php echo translate_admin("City");?></label></td><td style="padding: 20px 0px 0px 0px;">
    <input type="text" size=34 placeholder="e.g. San Francisco" class="input-large" value="<?php echo $city; ?>" id="city_edit" name="city">
</td>
  </tr>
<tr>
  	<td style="padding: 20px 0px 0px 0px;">
    <label for="state" class="label-large"><?php echo translate_admin("State");?></label></td><td style="padding: 20px 0px 0px 0px;">
    <input type="text" size=34 placeholder="e.g. CA" class="input-large" value="<?php echo $state; ?>" id="state_edit" name="state">
    </td>
     </tr>
<tr>
  	<td style="padding: 20px 0px 0px 0px;">
    <label for="zipcode" class="label-large"><?php echo translate_admin("ZIP Code");?></label></td><td style="padding: 20px 0px 0px 0px;">
    <input type="text" size=34 placeholder="e.g. 94103" class="input-large" value="<?php echo $zip_code; ?>" id="zipcode_edit" name="zipcode">
  </td>
    </tr>
    <tr>
    	<td></td>
    	<td style="padding: 20px 0px 0px 0px;">
    <input type="submit" id="next-btn1" onclick="disable()" class="disable-btn btn-primarybtn next_active" value="<?php echo translate("Next");?>">
    
    <input type="submit" id="next-btn" class="enable-btn btn-primarybtn next_active" style="display: none" value="<?php echo translate("Next");?>">
    
    </td>
</table>
<!--</form>-->
</div>
</div>
</div>
</div>
</div></div>

<div class="modal-list modal-with-background-image1" id="address_popup2" style="display: none">
<div id="my_form" >
  <div class="modal-content">
  <div class="col-md-9 col-xs-9 col-sm-9">
  <div class="panel">
  <div class="panel-header">
    <div class="media-body">
        <h1 class="page=header1">
          <span style="font-size:18px;"><?php echo translate("Location Not Found");?></span><br>
          <small class="addrs_small"><?php echo translate("Manually pin this listing's location on a map.");?></small>
        </h1>
     </div>
  </div><br>
  <div class="panel-align panel-body">
 <p class="panel-para"><?php echo translate("We couldn't automatically find this listing's location, but if the address below is correct you can manually pin it's location on the map instead.");?></p>
<br>
<strong id="str_street_address_edit"></strong><br>
<strong id="str_city_state_address_edit"></strong><br>
<strong id="str_zipcode_edit"></strong><br>
<strong id="str_country_edit"></strong><br>
<br>
  </div>
<div class="panel-footer">
    <input type="submit" class="cancel-btn" id="edit_address" value="<?php echo translate("Edit Address");?>">
    <input type="submit"  class="pin-on-map" id="pin-on-map" value="<?php echo translate("Pin on Map");?>">

</div>


</div>


</div>
</div>
</div>
</div>

<div class="modal-list modal-with-background-image1" id="address_popup3" style="display: none">
<div id="my_form">
 <div class="modal-content">
  <div class="col-md-9 col-xs-9 col-sm-9">
  <div class="panel-borderhead panel">
  <div class="panel-header">
    <div class="media-body">
        <h1 class="page=header1">
          <span style="font-size:18px;"><?php echo translate("Pin Location");?></span><br>
          <small class="addrs_small"><?php echo translate("Move the map to pin your listing's exact location.");?></small>
        </h1>
        <br>
     </div>
    </div>
  <div class="panel-body">
    

<div class="panel">
<div id="map-canvas1"></div>
  </div>
<div style="border-top:none;" class="panel-border panel-align panel-body">
<strong id="str_street_address2"></strong><br>
<strong id="str_city_state_address2"></strong><br>
<strong id="str_zipcode2"></strong><br>
<strong id="str_country2"></strong><br>
<a data-event-name="edit_address_click" class="edit-address-link" id="edit_popup3"><?php echo translate("Edit address");?></a>
</div>

</div><br>
<div style="border:none;" class="panel-footer">
   <!-- <button class="cancel-btn" id="cancel_popup3">
      Cancel
   </button>-->
    <input type="submit"  class="pin-on-map enable_finish" id="finish_popup3" style="display: none" va;ue="<?php echo translate("Finish");?>">
    <input type="submit" id="finish_popup3" class="pin-on-map disable_finish"  onClick="alert('Pin your listing exact location on map to continue.');" style="opacity: 0.65;" value="<?php echo translate("Finish");?>" > 
    
</div>


</div>
</div>
</div>
</div>
</div>
	<div class="center_entire" id="address_entire">
	<div class="col-md-9 col-xs-9 col-sm-9">
	<div class="title-address center_entire_left" style="margin-top:30px;">
        <h2><?php echo translate("Address");?></h2><br>
        <p class="text_address"><?php echo translate("Your exact address is private and only shared with guests after a reservation is confirmed.");?></p>
  </div><br>
	<div class="lys_address">
		<?php
		if($street_address != '')
		{
			?>
			<img id="static_map" height="250" width="275" class="lst_map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $lat.','.$lng; ?>&key=<?php echo $places_API; ?>&size=571x275&zoom=15&format=png&markers=color:red|label:|<?php echo $lat.','.$lng; ?>&sensor=false&maptype=roadmap&style=feature:water|element:geometry.fill|weight:3.3|hue:0x00aaff|lightness:100|saturation:93|gamma:0.01|color:0x5cb8e4">
			<?php
		}
		else {
			?>
			<img id="static_map" src="<?php echo base_url(); ?>images/map_lys.png" />
			<?php
		}
		?><br>
        <p class="add_content" id="add_content"><?php echo translate("This Listing has no address.");?></p><br>
        <!--<img id="add_address" src="<?php echo base_url(); ?>images/add_address.png" />-->
        <input type="submit" id="add_address" class="pin-on-map" value="<?php echo translate("Add address");?>">
        <div id="after_address" style="display: none;padding-top: 20px;">
        <strong id="str_street_address1_edit"><?php echo $street_address;?></strong><br>
        <strong id="str_city_state_address1_edit"><?php echo $city.' '.$state;?></strong><br>
        <strong id="str_zipcode1_edit"><?php echo $zip_code;?></strong><br>
        <strong id="str_country1_edit"><?php echo $country;?></strong><br><br>
        <a id="edit_address1"><?php echo translate("Edit address");?></a>
        </div>
    </div>
</div>

</div>
</div>

<?php if($this->input->get('month') != '')
{
	?>
	<div id="calendar_container" style="border-top:3px solid #e3e3e3;">
	<div class="col-md-9 col-xs-9 col-sm-9">
	<?php
}
else {
?>
<div id="calendar_container" style="display: none;border-top:3px solid #e3e3e3;">
<div class="col-md-9 col-xs-9 col-sm-9">
<?php } ?>

 <!-- Required Stylesheets -->
<link href="<?php echo css_url(); ?>/edit_listing.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url(); ?>/calendar_single.css" media="screen" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function showmydiv()
{
	var availability=document.getElementById('lwlb_availability').value;
	if(availability=="Available")
	{
	document.getElementById('lwlb_price').style.display="block";
	document.getElementById('lwlb_notes').style.display="none";
	}
	else
	{
	document.getElementById('lwlb_price').style.display="none";
	document.getElementById('lwlb_notes').style.display="block";
	}
}
</script>
<script type="text/javascript">
jQuery.noConflict();
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/prototype.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/scriptaculous.js"></script>

<script type="text/javascript">
function showmydiv()
{
	var availability=document.getElementById('lwlb_availability').value;
	if(availability=="Available")
	{
	document.getElementById('lwlb_price').style.display="block";
	document.getElementById('lwlb_notes').style.display="none";
	}
	else
	{
	document.getElementById('lwlb_price').style.display="none";
	document.getElementById('lwlb_notes').style.display="block";
	}
}

function get_square(rowIndex,colIndex) {
	return schedules[rowIndex][colIndex];
}
function gridColToDataCol(gridCol) {
				return (gridCol);
}

function gridRowToDataRow(gridRow) {
				return (gridRow);
}

function update_calendar_data(visible_row,hosting_id,json) {

				schedules[0] = json[0];
				render_grid(0, 34);
}

function is_address_line(row) {
				return false;
}

function lwlb_hide_special() {
				lwlb_hide('lwlb_calendar2');
				range_remove_all();
}

function range_add(i){

if(select_range_click_count <2)
{

				if (select_range_stop == null) return;
				if (i < select_range_start) return;
				if (i < select_range_stop) range_remove(i+1);

				for (var tmp=select_range_stop; tmp <= i; tmp++) {
								if (!is_selectable(0,tmp)) return;
								
								rollover('tile_' + tmp, 'tile', 'tile_selected');
				}

				select_range_stop = i;
			}
}

function range_remove(i) {
				if (select_range_stop == null) return;
				if (i <= select_range_start) return;
				if (select_range_click_count>=2) return;
				if (i>select_range_stop) return;
				var cged = select_range_stop;
				for (; select_range_stop >= i; select_range_stop--) {
								rollover('tile_' + select_range_stop, 'tile_selected', 'tile');
				}

				select_range_stop = i;
				
				for( j=cged; j <= 50; j++)
				{
				
					rollover('tile_' + j, 'tile_selected', 'tile');
				}
}


function range_remove_all() {
				for (var i=select_range_start; i <= select_range_stop; i++) {
								rollover('tile_' + i, 'tile_selected', 'tile');
				}

				select_range_click_count = 0;
				select_range_start = null;
				select_range_stop = null;
}

function click_down(i) {
				select_range_click_count++;

				if (select_range_click_count > 2) {
								range_remove_all();
								return;
				}

				if (select_range_stop != null) return; // abort on second click

				if (!is_selectable(0, i)) {
								var matches = getAtomicBounds(0,i);
								select_range_start = matches[0];
								select_range_stop = matches[1];
								show_calendar();
								return;
				}

				select_range_start = i;
				select_range_stop = i;
				rollover('tile_'+i,'tile','tile_selected');
}

function click_up(i) {
	
	
				if (select_range_click_count == 2) {
					
								show_calendar();
				}
				else
				{
					lwlb_hide('lwlb_calendar2');
				}
					
				
}

function show_calendar() {

				prepareLightbox(<?php echo $result->id; ?>,"<?php echo $list_title; ?>",0,select_range_start,select_range_stop);

}

</script>
<!-- End of style sheets inclusion -->
<?php 
	$id = $result->id;
	$query = $this->db->get_where('list', array( 'id' => $id));
	$q = $query->result();
	$query2 = $this->db->get_where('amnities', array( 'id' => $id));
	$r = $query2->result();
?>
<div class="container_bg" id="View_Edit_List">
 
    <div class="View_Edit_Main_Content">
      <div id="notification-area"></div>
      <div id="dashboard-content">
        <div id="dashboard_v2" class="edit_room">
          
          <div class="row">
            <div class="col three-fourths content">
              <div id="notification-area"></div>
              <div id="dashboard-content">

  <?php
		$first_day     = mktime(0,0,0,$month,1,$year);
		$days_in_month = cal_days_in_month(0,$month,$year);
		$day_of_week   = date('N',$first_day);
		
		$months        = array('January','February','March','April','May','June','July','August','September','October','November','December');
		$title         = $months[$month-1]; 
		
		if ($day_of_week == 7) { $blank = 0; } else { $blank = $day_of_week; }
		
		if (($month-1) == 0) 
		{
		$prevmonth = 1;
		$prevyear  = ($year-1);
		}
		else 
		{
		$prevmonth = ($month-1);
		$prevyear  = $year;
		}
		$day_prevmonth=cal_days_in_month(0,$prevmonth,$prevyear)-($blank-1);
		
		if($month == 01)
		{
		$prev_month = 12; $prev_year = $year - 1;
		}
		else
		{
		$prev_month = $month - 1; $prev_year = $year;
		}
		
		if($month == 12)
		{
		$next_month = 01; $next_year = $year + 1;
		}
		else
		{
		$next_month = $month+1; $next_year = $year;
		}

		$day_num    = 1;
		$day_count  = 1;
		$datenow    = time();
		$monthnow   = date('n',$datenow);
		$yearnow    = date('Y',$datenow);
		$daynow     = date('j',$datenow);
	?>
 <script type="text/javascript">

var global_grid = null;
<?php
$columnInfo = '';
$schedules  = '';
$firstDay =  $prev_year.'-'.$prev_month.'-'.$day_prevmonth;
//Previous Months Days
while ($blank > 0) { 
$dayJ        = date('D',strtotime($prev_year.'-'.$prev_month.'-'.$day_prevmonth));
$month_day   = date('M d',strtotime($prev_year.'-'.$prev_month.'-'.$day_prevmonth));
$full_date   = $prev_month.'/'.$day_prevmonth.'/'.$prev_year;

$pre_schedules  = '{cl: "av", sty: "both", isa: 1},';

foreach($result_cal as $row)
{
	if(get_gmt_time(strtotime($full_date)) == $row->booked_days)
	{
	  if($row->value != '') { $value = ', reservation_value: '.$row->value; } else { $value = ''; }
			if($row->availability == 'Not Available') {$c1 = 'bs'; $st = 2; } else {$c1 = 'tp'; $st = 4; }
			$pre_schedules  = '{cl: "'.$c1.'", sty: "'.$row->style.'", isa: 0, id: '.$row->id.', notes: "'.$row->notes.'", st: '.$st.', sst: "'.$row->booked_using.'", gid: '.$row->group_id.$value.'},';
	}
}
$schedules  .= $pre_schedules;

$columnInfo .= '"'.$dayJ.'\u003Cbr /\u003E'.$month_day.'",';

$blank = $blank-1; $day_count++; $day_prevmonth++;
}

//Current Months Days
while ($day_num <= $days_in_month) { 
$dayJ        = date('D',strtotime($year.'-'.$month.'-'.$day_num));
$month_day   = date('M d',strtotime($year.'-'.$month.'-'.$day_num));
$full_date   = $month.'/'.$day_num.'/'.$year;

$pre_schedules  = '{cl: "av", sty: "both", isa: 1},';
foreach($result_cal as $row)
{
	if(get_gmt_time(strtotime($full_date)) == $row->booked_days)
	{
	  if($row->value != '') { $value = ', reservation_value: '.$row->value; } else { $value = ''; }
			if($row->availability == 'Not Available') {$c1 = 'bs'; $st = 2; } else {$c1 = 'tp'; $st = 4; }
			$pre_schedules  = '{cl: "'.$c1.'", sty: "'.$row->style.'", isa: 0, id: '.$row->id.', notes: "'.$row->notes.'", st: '.$st.', sst: "'.$row->booked_using.'", gid: '.$row->group_id.$value.'},';
	}
}
$schedules  .= $pre_schedules;

$columnInfo .= '"'.$dayJ.'\u003Cbr /\u003E'.$month_day.'",';

$day_num++; $day_count++;

if ($day_count > 7) { $day_count = 1; }
}

//Next Months Days
$day_nextmonth = 1;

while ($day_count > 1 && $day_count <= 7 ) {
$dayJ           = date('D',strtotime($next_year.'-'.$next_month.'-'.$day_nextmonth));
$month_day      = date('M d',strtotime($next_year.'-'.$next_month.'-'.$day_nextmonth));
$full_date      = $next_month.'/'.$day_nextmonth.'/'.$next_year;

$pre_schedules  = '{cl: "av", sty: "both", isa: 1},';
foreach($result_cal as $row)
{
	if(get_gmt_time(strtotime($full_date)) == $row->booked_days)
	{
			if($row->value != '') { $value = ', reservation_value: '.$row->value; } else { $value = ''; }
			if($row->availability == 'Not Available') {$c1 = 'bs'; $st = 2; } else {$c1 = 'tp'; $st = 4; }
			$pre_schedules  = '{cl: "'.$c1.'", sty: "'.$row->style.'", isa: 0, id: '.$row->id.', notes: "'.$row->notes.'", st: '.$st.', sst: "'.$row->booked_using.'", gid: '.$row->group_id.$value.'},';
	}
}
$schedules  .= $pre_schedules;

$columnInfo .= '"'.$dayJ.'\u003Cbr /\u003E'.$month_day.'",';

$day_count++; $day_nextmonth++;
}

$lastDay = $next_year.'-'.$next_month.'-'.($day_nextmonth-1);
?>

var columnInfo = [<?php echo substr($columnInfo, 0, -1); ?>];

var hostings = [{"price":<?php echo get_currency_value1($list_id,$list_price); ?>,"name":"<?php echo $list_title; ?>","available":1,"row":0,"id":<?php echo $list_id; ?>,"currency_symbol":"<?php  echo get_currency_symbol($list_id); ?>","currency":"<?php  echo get_currency_code(); ?>","lc_name":"<?php echo strtolower($list_title); ?>"}];

var schedules = [[<?php echo substr($schedules, 0, -1); ?>]];


var reservationHash = new Hash({});

var g_start_date = date_parse_datestamp('<?php echo $firstDay; ?>');
var g_stop_date = date_parse_datestamp('<?php echo $lastDay; ?>');
var g_today_index = 19;

var g_enable_change_dates = true;



function centerLightbox() {
    var boxWidth = jQuery('#lwlb_calendar2').width();
    var boxHeight= jQuery('#lwlb_calendar2').height();
   var g_mouse_x = jQuery('#mouse_x').val();
   var g_mouse_y = jQuery('#mouse_y').val();
    $('lwlb_calendar2').setStyle({ position:"absolute", left: (g_mouse_x - (boxWidth / 0.57) )+"px", top: (g_mouse_y - boxHeight - 253)+"px"});
}

/***** SELECTION *****/

/* A span is selectable if it contains no atomic groups */
function is_selectable(gridRow,gridCol) {
    if (is_address_line(gridRow)) return false;

    var schedule = get_square(gridRow,gridCol);
    if (schedule.gid || schedule.confirmation) {
        return false;
    } else {
        return true;
    }
}

/* If atomic span / grouping, what are the bounds of the span? */
function getAtomicBounds(gridRow,gridCol) {
    var minCol = gridCol;
    var maxCol = gridCol;

    var grouping_uid = get_square(gridRow,gridCol).gid;
    while (get_square(gridRow,maxCol+1) && (get_square(gridRow,maxCol+1).gid==grouping_uid)) {
        maxCol+=1;
    }
    while (get_square(gridRow,minCol-1) && (get_square(gridRow,minCol-1).gid==grouping_uid)) {
        minCol-=1;
    }
    return [minCol,maxCol];
}

function getSpanBounds(gridRow,gridCol) {
    var minCol = gridCol;
    var maxCol = gridCol;

    if (get_square(gridRow,gridCol).sty=='single') return [minCol,maxCol];
    while (get_square(gridRow,maxCol+1) && (get_square(gridRow,maxCol+1).sty!='right')) {
        maxCol+=1;
    }
    return [minCol,maxCol+1];
}


function getSquareText(square,gridRow,gridCol) {
    var dataRow = gridRowToDataRow(gridRow);
    var dataCol = gridColToDataCol(gridCol);

    var hosting = hostings[dataRow];
    
    if (("left"==square.sty) || ("single"==square.sty)) {
        switch(square.st) {
        case 0:
            if (square.daily_price) {
                return hosting.currency_symbol + square.daily_price;
            } else {
                return hosting.currency_symbol + hosting.price;
            }
        case 2:
            if (square.notes) {
                return square.notes
            } else {
                return 'Not available';
            }
        case 5:
            if (square.notes) {
                return square.notes
            } else {
                return 'Not available';
            }
        case 3:
            if (square.notes) {
                return square.sst + ": " + square.notes
            } else {
                return square.sst + ': Not available';
            }
        case 4:
            return "Booked" + (square.notes ? ": "+square.notes : '');
        case 1:
            var r = reservationHash.get(square.confirmation);
            if (!r) return '(error)';
            if (r.is_accepted) {
                return r.base_price + " Cogzidel, " + r.guest_name +  (square.notes ? ": "+square.notes : '');
            } else {
                return r.base_price + " Cogzidel, " + r.guest_name + " (PENDING)" + (square.notes ? ": "+square.notes : '');
            }
        default: return hosting.currency_symbol + hostings[dataRow].price;
        }
    } else if ((dataCol==0) && square.isa) {
        if (square.daily_price) {
            return hosting.currency_symbol + square.daily_price;
        } else {
            return hosting.currency_symbol + hosting.price;
        }
    } else {
        return "&nbsp;";
    }
}


function before_submit() {
		var lwprice = document.getElementById('seasonal_price').value;
		if($('lwlb_availability').value == 'Available')
		{
		if(lwprice.length >= 10)
		{
			alert('Give the below 10 digits price');return false;
		}
		if(lwprice < 10)
		{
		//window.name = lwprice;
		alert('Must give the above 10 price');return false;
		}
		}
		
		if($('lwlb_availability').value != 'Available' && $('lwlb_notes').value == 'Notes...')
		{
			alert('Give some notes.');
		}
		//window.name = '';
	$('lightbox_submit').disabled = true;
	$('lightbox_spinner').show();
}

function after_submit() {
	$('lightbox_submit').disabled = false;
	$('lightbox_spinner').hide();
    lwlb_hide_special();
}


function prepareLightbox(hosting_id,hosting_name,gridRow,gridMinCol,gridMaxCol) {
    var firstSquare = get_square(gridRow,gridMinCol);
    var lastSquare = get_square(gridRow,gridMaxCol);
    var startDate = new Date(g_start_date.getTime()); //date_parse_datestamp(firstSquare.dt);
    var stopDate = new Date(g_start_date.getTime()); //date_parse_datestamp(lastSquare.dt);

    var dataRow = gridRowToDataRow(gridRow);
    var hosting = hostings[dataRow];

    $$("span.currency_symbol").each(function(x){ x.innerHTML=hosting.currency_symbol; });
    $('lwlb_currency').value = hosting.currency;

    startDate.setDate(startDate.getDate() + gridColToDataCol(gridMinCol));
    stopDate.setDate(stopDate.getDate() + gridColToDataCol(gridMaxCol));

    /* Applies to all lightbox styles */

    $('lightbox_submit').disabled = false;
	$('lightbox_spinner').hide();

    $('lwlb_hosting_id').value = hosting_id;
    $('lwlb_visible_row').value = gridRow;

    if (firstSquare.gid) {
        $('lwlb_grouping_uid').value = firstSquare.gid;
    } else {
        $('lwlb_grouping_uid').value = "";
    }

    $('lwlb_starting_date').value = $('lwlb_starting_date_original').value = date_print_usa_date(startDate);
    $('lwlb_stopping_date').value = $('lwlb_stopping_date_original').value = date_print_usa_date(stopDate);
    $('lwlb_data_start_date').value = date_print_usa_date(g_start_date);
    $('lwlb_confirmation').value = firstSquare.confirmation ? firstSquare.confirmation : '';

    if (firstSquare.notes) {
        if (firstSquare.notes) $('lwlb_notes').value = firstSquare.notes;
    } else {
        $('lwlb_notes').value = "Notes...";
    }

    $('lwlb_reservation_section').hide();
    $('lwlb_normal_section').hide();

    // Reservation Case
    if (firstSquare.st==1) {
        g_enable_change_dates = false;
        var r = reservationHash.get(firstSquare.confirmation);

        $('lwlb_availability').value = "Booked";
        $('lwlb_booking_service_other').value = "Cogzidel";

        // Setup the date span
							/*	alert(r.start_date);*/
        $('lwlb_date_span_start').innerHTML = date_print_simplified(date_parse_datestamp(r.start_date));
        $('lwlb_date_span_stop').innerHTML = date_print_simplified(date_parse_datestamp(r.end_date));
        $('lwlb_date_single').hide();
        $('lwlb_date_span').show();

        $('lwlb_reservation_section').show();
        $('lwlb_reservation_guest_pic').src = r.guest_pic;
        $('lwlb_reservation_hosting_name').innerHTML = hosting_name;

        if (r.is_accepted) {
            $('lwlb_reservation_code').innerHTML = r.confirmation;
        } else {
            $('lwlb_reservation_code').innerHTML = "pending";
        }
        $('lwlb_reservation_guest_name').innerHTML = r.guest_name;
        $('lwlb_reservation_dates').innerHTML = date_print_simplified(date_parse_datestamp(r.start_date)) + " - " + date_print_simplified(date_parse_datestamp(r.end_date));
        $('lwlb_reservation_base_price').innerHTML = r.base_price;
        $('lwlb_reservation_guest_email').innerHTML = r.guest_email;
        $('lwlb_reservation_guest_email').href = "mailto:"+r.guest_email;
        $('lwlb_reservation_guest_phone').innerHTML = (""==r.guest_phone) ? "(unknown)" : r.guest_phone;

        if (r.is_accepted) {
            $('lwlb_reservation_contact').show();
            $('lwlb_reservation_itinerary').show();
            $('lwlb_reservation_accept').hide();
        } else {
            $('lwlb_reservation_contact').hide();
            $('lwlb_reservation_itinerary').hide();
            $('lwlb_reservation_accept').show();
        }

    } else {
        g_enable_change_dates = true;
        //if (minColIndex<g_today_index) return false; // disable click behavior if non-reservation

        $('lwlb_hosting_name').innerHTML = hosting_name;

        // Setup the date span
        if (gridMinCol==gridMaxCol) {
            $('lwlb_date_single').innerHTML = date_print_simplified(startDate);
            $('lwlb_date_single').show();
            $('lwlb_date_span').hide();
        } else {
								/*alert(startDate);*/
            $('lwlb_date_span_start').innerHTML = date_print_simplified(startDate);
            $('lwlb_date_span_stop').innerHTML = date_print_simplified(stopDate);
            $('lwlb_date_single').hide();
            $('lwlb_date_span').show();
        }

        $('lwlb_normal_section').show();

        //$('price').value = ""; //schedules[i].daily_price;
        if ((0==firstSquare.st) || !firstSquare.st) {
            $('lwlb_availability').value = 'Available';
        } else if ((2==firstSquare.st) || (5==firstSquare.st)) {
            $('lwlb_availability').value = 'Not Available';
        } else {
            $('lwlb_availability').value = 'Booked';
        }
    }

    lwlb_show('lwlb_calendar2',{no_scroll: true});

    centerLightbox(); // specific to multi-calendar vs. singular calendar
}


/**** DATE HELPERS ****/

function get_month_abbrev(month) {
    return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][month];
}

function date_parse_datestamp(datestamp) {
    // yyyy-mm-dd
    var parts = datestamp.split("-");
    return new Date(parts[0],parts[1]-1,parts[2]);
    //return new Date(get_month_abbrev(parts[1]-1)+" "+parts[2]+", "+parts[0]+" 00:00:00 "); // parts[0],parts[1]-1,parts[2]
}
function date_parse_usa_date(datestamp) {
    // mm/dd/yyyy
    var parts = datestamp.split("/");
    return new Date(parts[2],parts[0]-1,parts[1]);
}

function date_print_usa_date(dt) {
    // 2010-03-11: KEEP IT IN THE LOCAL TIMEZONE; OTHERWISE DATE OFFSET PROBLEMS!
    return (dt.getMonth()+1) + "/" + dt.getDate() + "/" + dt.getFullYear();
}

function date_print_simplified(dt) {
    //return get_month_abbrev(dt.getUTCMonth()) + " " + dt.getUTCDate();
    // 2010-03-11: KEEP IT IN THE LOCAL TIMEZONE; OTHERWISE DATE OFFSET PROBLEMS!
    return get_month_abbrev(dt.getMonth()) + " " + dt.getDate();
}

</script>
                <div id="lwlb_overlay" style="opacity:0.2;"></div>
                <script>
	
    function lwlb_force_stop_date_after_start() {
        var startDate = date_parse_usa_date($('lwlb_starting_date').value);
        var stopDate = date_parse_usa_date($('lwlb_stopping_date').value);

        if (startDate >= stopDate) {
            var newStopDate = new Date(startDate.getTime()+Date.one_day);
            $('lwlb_stopping_date').value = date_print_usa_date(newStopDate);
            $('lwlb_date_span_stop').innerHTML = date_print_simplified(newStopDate);
        }
    }

    function lwlb_start_date_calendar_popup() {
        if (!g_enable_change_dates) return false;

        new CalendarDateSelect($('lwlb_starting_date'), {
            popup_by:$('lwlb_date_span_start'),
            buttons:false,
            default_date_offset:0,
            year_range:[new Date().getFullYear(),new Date().getFullYear()+1],
            valid_date_check:function (dt){ return(dt >= calendar_helper_simple_today()); }
        } );
    }

    function lwlb_stop_date_calendar_popup() {
        if (!g_enable_change_dates) return false;
        
        var startDate = date_parse_usa_date($('lwlb_starting_date').value);
        var default_offset = Math.ceil((startDate - new Date())/Date.one_day) + 1;

        new CalendarDateSelect($('lwlb_stopping_date'), {
            popup_by:$('lwlb_date_span_stop'),
            buttons:false,
            default_date_offset:default_offset,
            year_range:[new Date().getFullYear(),new Date().getFullYear()+1],
            valid_date_check:function (dt){ return(dt > startDate); }
        } );
    }

    function lwlb_booking_service_changed() {
        if ($('lwlb_booking_service').value=="Other") {
            $('lwlb_booking_service_other').show();
        } else {
            $('lwlb_booking_service_other').hide();
        }

    }

    function lwlb_availability_changed() {
        switch ($('lwlb_availability').value) {
        case 'Available':
            $('lwlb_row_per_night').show();
            $('lwlb_row_service').hide();
            $('lwlb_row_value').hide();
            $('lwlb_notes').hide();
            break;
        case 'Not Available':
            $('lwlb_row_per_night').hide();
            $('lwlb_row_service').hide();
            $('lwlb_row_value').hide();
            $('lwlb_notes').show();
            break;
        case 'Booked':
            $('lwlb_row_per_night').hide();
            $('lwlb_row_service').show();
            $('lwlb_row_value').show();
            $('lwlb_notes').show();
            break;
        }
    }
    function lwlbOpenProfile() {
        var confirmation = $('lwlb_confirmation').value;
        var record = reservationHash.get(confirmation);
        window.open('<?php echo base_url(); ?>users/show/'+record.guest_id);
    }

    function lwlbOpenMessage() {
        var confirmation = $('lwlb_confirmation').value;
        var record = reservationHash.get(confirmation);
        window.open('<?php echo base_url(); ?>messaging/qt_with/'+record.guest_id);
    }

    function lwlbOpenItinerary() {
        var confirmation = $('lwlb_confirmation').value;
        var record = reservationHash.get(confirmation);
        window.open('<?php echo base_url(); ?>reservation/print_confirmation/?code='+confirmation);
    }

    function lwlbOpenAcceptDecline() {
        var confirmation = $('lwlb_confirmation').value;
        var record = reservationHash.get(confirmation);
        window.open('<?php echo base_url(); ?>reservation/approve?code='+confirmation);
    }
</script>
<input type="hidden" id="mouse_x">
<input type="hidden" id="mouse_y">

                <div id="lwlb_calendar2" class="lwlb_lightbox_calendar">
                  <div class="container">
                    <div class="inner">
                      <form action="<?php echo site_url('calendar/modify_calendar').'?month='.$month.'&year='.$year; ?>" method="post" onSubmit="before_submit();; new Ajax.Request('<?php echo site_url('calendar/modify_calendar').'?month='.$month.'&year='.$year; ?>', {asynchronous:true, evalScripts:true, parameters:Form.serialize(this), onSuccess: function(transport){ window.location.href = '<?php echo base_url();?>administrator/lists/managelist/<?php echo $result->id;?>?month=1'; }}); return false;">
                        <input type="hidden" name="data_start_date" id="lwlb_data_start_date" value="" />
                        <input type="hidden" name="confirmation" id="lwlb_confirmation" value="" />
                        <input type="hidden" name="starting_date_original" id="lwlb_starting_date_original" value="" />
                        <input type="hidden" name="stopping_date_original" id="lwlb_stopping_date_original" value="" />
                        <input type="hidden" name="grouping_uid" id="lwlb_grouping_uid" value="" />
                        <input type="hidden" name="currency" id="lwlb_currency" value="" />
                        <input type="hidden" name="starting_date" id="lwlb_starting_date" value="" onChange="$('lwlb_date_span_start').innerHTML = date_print_simplified(date_parse_usa_date(this.value)); lwlb_force_stop_date_after_start();" />
                        <input type="hidden" name="stopping_date" id="lwlb_stopping_date" value="" onChange="$('lwlb_date_span_stop').innerHTML = date_print_simplified(date_parse_usa_date(this.value)); lwlb_force_stop_date_after_start();" />
                        <input type="hidden" name="hosting_id" id="lwlb_hosting_id" value="">
                        <input type="hidden" name="visible_row" id="lwlb_visible_row" value="">
                        <div id="lwlb_reservation_section" style="margin-bottom:10px;">
                          <div class="header bottom_line">
                            <div class="header_text" style="float:left;"> <span id="lwlb_reservation_guest_name">guest name</span> (<span id="lwlb_reservation_code">code</span>) </div>
                            <div class="close"><a href="#" onClick="lwlb_hide_special();return false;"><img src="<?php echo css_url(); ?>/images/sin_cal_close.gif" /></a></div>
                            <div class="clear"></div>
                          </div>
                          <div class="bottom_line">
                            <div style="float:left;width:60px;"> <img id="lwlb_reservation_guest_pic" src="" style="width:50px;height:50px;" /> </div>
                            <div style="float:left;width:200px;">
                              <div><span class="label">Dates:</span> <span id="lwlb_reservation_dates">dates</span></div>
                              <div><span class="label">Place:</span> <span id="lwlb_reservation_hosting_name">dates</span></div>
                              <div><span class="label">Price:</span> <span id="lwlb_reservation_base_price">price</span></div>
                            </div>
                            <div class="clear"></div>
                          </div>
                          <div style=""> <span id="lwlb_reservation_contact"><a id="lwlb_reservation_contact_details_show_link" href="#" onClick="Element.hide('lwlb_reservation_contact_details_show_link');Element.show('lwlb_reservation_contact_details');Element.show('lwlb_reservation_contact_details_hide_link');return(false);" >Contact</a><a id="lwlb_reservation_contact_details_hide_link" href="#" onClick="Element.hide('lwlb_reservation_contact_details_hide_link');Element.show('lwlb_reservation_contact_details_show_link');Element.hide('lwlb_reservation_contact_details');return(false);" style="display:none;">Hide Contact</a> |</span> <a href="#" onClick="lwlbOpenProfile();return false;">Profile</a> | <a href="#" onClick="lwlbOpenMessage();return false;">Message History</a> <span id="lwlb_reservation_itinerary">| <a href="#" onClick="lwlbOpenItinerary();return false;">Itinerary</a></span> <span id="lwlb_reservation_accept">| <a href="#" onClick="lwlbOpenAcceptDecline();return false;">Accept/Decline</a></span> </div>
                          <div id="lwlb_reservation_contact_details" style="display:none;border-top: 1px solid #CBCACA; padding-top:5px; margin-top:5px;"> <span class="label">Email:</span> <a id="lwlb_reservation_guest_email" href="#">guest email</a> <span class="label">Phone:</span> <span id="lwlb_reservation_guest_phone">guest phone</span> </div>
                        </div>
                        <div id="lwlb_normal_section">
                          <div class="header bottom_line">
                            <div id="lwlb_hosting_name" class="header_text" style="float:left; width:280px;border-bottom: 1px solid #c3c3c3;">hosting name</div>
                            <div class="close"><a href="#" onClick="lwlb_hide_special();return false;"><img src="<?php echo css_url(); ?>/images/sin_cal_close.gif" /></a></div>
                            <div class="clear"></div>
                          </div>
                          <!--<div id="lwlb_date_single">single date</div>
                          <div id="lwlb_date_span"> <span id="lwlb_date_span_start" class="calendar_link" onClick="lwlb_start_date_calendar_popup();">checkin</span> - <span id="lwlb_date_span_stop" class="calendar_link" onClick="lwlb_stop_date_calendar_popup();">checkout</span> </div>
                          
                          <div style="padding-bottom:5px;padding-top:5px;">
                            <table>
                              <tr>
                                <td style="width:80px;">Availability</td>
                                <td><select id="lwlb_availability" name="availability" value="" onChange="showmydiv()" >
                                    <option value="Available">Available</option>
                                    <option value="Booked">Booked</option>
                                    <option value="Not Available">Not Available</option>
                                  </select></td>
                              </tr>
                            </table>
                          </div>-->
                        </div>
                        <div id="lwlb_date_single" style="padding-top:36px;">single date</div>
                          <div id="lwlb_date_span"> <span id="lwlb_date_span_start" class="calendar_link" onClick="lwlb_start_date_calendar_popup();">checkin</span> - <span id="lwlb_date_span_stop" class="calendar_link" onClick="lwlb_stop_date_calendar_popup();">checkout</span> </div>
                          
                          <div style="padding-bottom:5px;padding-top:5px;">
                            <table>
                              <tr>
                                <td style="width:80px;">Availability</td>
                                <td><select id="lwlb_availability" name="availability" value="" onChange="showmydiv()" >
                                    <option value="Available">Available</option>
                                    <option value="Booked">Booked</option>
                                    <option value="Not Available">Not Available</option>
                                  </select></td>
                              </tr>
                            </table>
                          </div>
                        <div id="lwlb_price">
                        <p><label style="padding-right: 43px;">Price</label><input type="text" name="seasonal_price" id="seasonal_price" value=""></p>
                        </div>
                        <textarea id="lwlb_notes" name="notes" style="width:100%;height:30px;display:none;" onClick="if (this.value=='Notes...') { this.value=''; };">Notes...</textarea>
                        <div style="margin-top:5px;text-align:left;">
                         
                          <button id="lightbox_submit" type="submit" class="btn blue gotomsg" name="commit"><span><span><?php echo translate("Submit"); ?></span></span></button>
                          
                          <img src="<?php echo base_url(); ?>images/spinner.gif" id="lightbox_spinner" style="display:none;"/> or <a href="#" onClick="lwlb_hide_special();return false;">Cancel</a> </div>
                      </form>
                    </div>
                  </div>
                </div>
                
                <div>
                  
                  <div class="Box editlist_Box1">
                  <div class="Box_Head1">
                  	 <h1 class="page-header1">
                    	<span class="edit_room_icon calendar">
                    	</span><?php echo $list_title;?> - Calendar</h2></div>
                    <br>
                    <div id="calendar2" class="Box_Content">
                      <div id="backdrop">
                        <div class="clear"></div>
                        <div>
                          <script>
    var select_range_start = null;
    var select_range_stop = null;
    var select_range_click_count = 0;
</script>
                          <div class="full_bubble">
                            <div class="inner">
                              <?php
		$first_day     = mktime(0,0,0,$month,1,$year);
		$days_in_month = cal_days_in_month(0,$month,$year);
		$day_of_week   = date('N',$first_day);
		
		$months        = array('January','February','March','April','May','June','July','August','September','October','November','December');
		$title         = $months[$month-1]; 
		
		if ($day_of_week == 7) { $blank = 0; } else { $blank = $day_of_week; }
		
		if (($month-1) == 0) 
		{
		$prevmonth = 1;
		$prevyear  = ($year-1);
		}
		else 
		{
		$prevmonth = ($month-1);
		$prevyear  = $year;
		}
		$day_prevmonth=cal_days_in_month(0,$prevmonth,$prevyear)-($blank-1);
		
		if($month == 01)
		{
		$prev_month = 12; $prev_year = $year - 1;
		}
		else
		{
		$prev_month = $month - 1; $prev_year = $year;
		}
		
		if($month == 12)
		{
		$next_month = 01; $next_year = $year + 1;
		}
		else
		{
		$next_month = $month+1; $next_year = $year;
		}

		$day_num    = 1;
		$day_count  = 1;
		$datenow    = time();
		$monthnow   = date('n',$datenow);
		$yearnow    = date('Y',$datenow);
		$daynow     = date('j',$datenow);
		?>
                              <div>
                                <!-- Table -->
                                <div class="calendar-header">
                                  <div class="prev-month"> <a href="<?php echo site_url('administrator/lists/managelist/'.$result->id.'?month='.$prev_month.'&year='.$prev_year); ?>"> <img alt="Previous" height="34" src="<?php echo base_url(); ?>images/bttn_month_prev.png" width="35" /> </a> </div>
                                  <div class="next-month"> <a href="<?php echo site_url('administrator/lists/managelist/'.$result->id.'?month='.$next_month.'&year='.$next_year); ?>"> <img alt="Next" height="34" src="<?php echo base_url(); ?>images/bttn_month_next.png" width="35" /> </a> </div>
                                  
                                  <div class="display-month"><?php echo "$title $year"; ?></div>
								  
                                  <div class="clear"></div>
                                </div>
                                <div>
                                  <div>
                                    <div class="day_header">Sun</div>
                                    <div class="day_header">Mon</div>
                                    <div class="day_header">Tue</div>
                                    <div class="day_header">Wed</div>
                                    <div class="day_header">Thu</div>
                                    <div class="day_header">Fri</div>
                                    <div class="day_header">Sat</div>
                                    <div class="clear"></div>
                                  </div>
                                  <?php $k = 1; $i = 0; $j = 48;	while ($blank > 0) { if($k == 1) echo '<div>'; ?>
                                  <?php if(strtotime($prev_year.'-'.$prev_month.'-'.$day_prevmonth) < time()) { ?>
                                  <div class="tile disabled" id="tile_<?php echo $i; ?>">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_prevmonth; ?></div>
                                      <div class="line_reg" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"></span> </div>
                                    </div>
                                  </div>
                                  <?php }  else { 
                                  		//seasonal rate
                                  		$date=$prev_month.'/'.$day_prevmonth.'/'.$prev_year;
                                  		$price=getDailyPrice($list_id,get_gmt_time(strtotime($date)),$list_price);
                                  	?>
                                  <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_prevmonth; ?></div>
                                      <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id).get_currency_value1($list_id,$price); ?></b></span> </div>
                                    </div>
                                  </div>
                                  <?php } ?>
                                  <?php $blank = $blank-1; $day_count++; $day_prevmonth++; $i++; $j--; if($k == 7) { $k = 0; echo '<div class="clear"></div></div>'; } $k++; } ?>
                                  <?php while ($day_num <= $days_in_month) { if($k == 1) echo '<div>';  ?>
                                  <?php if(strtotime($year.'-'.$month.'-'.$day_num) < time()-(1 * 24 * 60 * 60)) {
                                 // 	if($i < date('d',time()) || $day_num != date('d',time()))
                                 // { ?>
                                  <div class="tile disabled" id="tile_<?php echo $i; ?>">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_num; ?></div>
                                      <div class="line_reg" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"></span> </div>
                                    </div>
                                  </div>
                                 <!--<?php// } 
								  //else {
                                  		//seasonal rate
                                  //		$date=$month.'/'.$day_num.'/'.$year;
                                 // 		$price=getDailyPrice($list_id,get_gmt_time(strtotime($date)),$list_price);
									 ?>-->
                                 <!-- <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_num; ?></div>
                                      <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id).get_currency_value1($list_id,$price); ?></b></span> </div>
                                    </div>
                                  </div>-->
                                  <?php //} 
                                  } else {
                                  		//seasonal rate
                                  		$date=$month.'/'.$day_num.'/'.$year;
                                  		$price=getDailyPrice($list_id,get_gmt_time(strtotime($date)),$list_price);
									 ?>
                                  <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_num; ?></div>
                                      <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id).get_currency_value1($list_id,$price); ?></b></span> </div>
                                    </div>
                                  </div>
                                  <?php } ?>
                                  <?php $day_num++; $day_count++; if ($day_count > 7) { echo "</tr><tr>"; $day_count = 1; } $i++; $j--; if($k == 7) { $k = 0; echo '<div class="clear"></div></div>'; } $k++; } ?>
                                  <?php $day_nextmonth = 1; while ($day_count > 1 && $day_count <= 7 ) { if($k == 1) echo '<div>'; ?>
                                  <?php if(strtotime($next_year.'-'.$next_month.'-'.$day_nextmonth) < time()) { ?>
                                  <div class="tile disabled" id="tile_<?php echo $i; ?>">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_nextmonth; ?></div>
                                      <div class="line_reg" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"></span> </div>
                                    </div>
                                  </div>
                                  <?php } else { 
                                  		//seasonal rate
                                  		$date=$next_month.'/'.$day_nextmonth.'/'.$next_year;
                                  		$price=getDailyPrice($list_id,get_gmt_time(strtotime($date)),$list_price);
                                  	?>
                                  <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                    <div class="tile_container">
                                      <div class="day"><?php echo $day_nextmonth; ?></div>
                                      <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id).get_currency_value1($list_id,$price); ?></b></span> </div>
                                    </div>
                                  </div>
                                  <?php } ?>
                                  <?php $day_count++; $day_nextmonth++; $i++; $j--; if($k == 7) { $k = 0; echo '<div class="clear"></div></div>'; } $k++; } ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <script>
						  
						  
						  
function render_grid(start_idx, stop_idx) {
    var prev_square;

    // ignore the first and last week of data (since it is padding)
    for(var i = start_idx; i <= stop_idx; i++){
      var square = schedules[0][i];
      var e = jQuery('#square_' + i);

      if(e.length == 0 || square === undefined)
        continue;

      var text = getSquareText(square, 0);

      // append to the square text if we detect that it's blocked out because the host denied a reservation
      if(prev_square != null && ((prev_square.st != square.st && square.st == 2 && square.gid != null) ||
        (prev_square.st == square.st && square.st == 2 && prev_square.gid == null && square.gid != null)))
        text += " (denied booking)";

      var span = getSpanBounds(0, i);

      var width = span[1] - span[0] + 1;

      if(text.length > (width * 8)){
        e.attr('title', text);
        text = text.substr(0, (width * 16) - 4) + "...";
      }

      e.find('span.content').html(text);
      var baseClass = e.attr('class').split(' ')[0];
      e.attr('class', baseClass + " " + square.cl + " " + square.sty);

      prev_square = square;

    }
}
render_grid(0, <?php if($i == 35) echo '34'; else echo '41'; ?>);
</script>
                        </div>
                        <div class="clear"></div>
                      </div>
                      <!-- backdrop -->
                    </div>
                    <!-- calendar2 -->
                  </div>
                </div>
			<div class="sync_cal" style="margin-left:200px;width: 62.5%;">
					<div class="Box editlist_Box-admin">
							<div class="Box_Head1">
							<h1 class="page-header1">
							<span class="edit_room_icon calendar"></span>
							Synchronize Calendars
							</h1>
							
							</div><br>
							<div id="synccal" class="Box_Content">

<h3>Import and synchronize all your calendar</h3>

<?php 
//date_default_timezone_set('Asia/Kolkata');
if (isset($_POST["icals"]))
{
$icals= $_POST["icals"];
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Calender</title>
	<link rel="stylesheet" type="text/css" href="codebase/style.css" />
</head>
<body>
	<!--<center>-->
		<div id="install_main">
			<div id="install_content">
<?php

	$problems = Array();

if (isset($log)) {

if (isset($_POST["icals"]))
{
$icals= $_POST["icals"];
}
	/*! output export result */
	?>
	<?php
	if(isset($success_num))
	{
	//for ($i = 0; $i < count($log); $i++) {
		?>
                <div class="log_row">
                    <br> <div class="log_msg <?php echo $log["type"]; ?>"><?php if($success_num != 0){ echo $log["type"].': '.$log["text"]; ?>
					<div class="log_row">
                   <!-- <div class="log_msg"><div class="num"></div>Import is completed. 
                    <?php echo anchor('administrator/lists/managelist/'.$this->uri->segment(4).'?month=1' ,translate('Return to show the booked calender'));?>
               </div><br>-->
					<?php } else
						{
							$nodata_msg = 1;
							//echo '<span style="color:red">There is no data in given URL</span>';
						} 
                    ?>
                    </div>														 
                </div>
		<?php
	//}

	$urls='';
	}
else {
	echo '<br><span style="color:red;">'.$log['type'].': '.$log['text'].'</span><br>';
	//echo anchor('administrator/lists/managelist/'.$result->id.'?month=1' ,translate('Return to Import'));
}
}
// else
 // {

$ical_import= $this->db->query("SELECT * FROM `ical_import` WHERE `list_id` = '".$result->id."' order by id ASC");

		$results=$ical_import->result_array();

	/*! outputing configuration form */
?><br>
				<div id="content_step_1">
					<form action="<?php echo base_url().'administrator/lists/managelist/'.$result->id.'?month=1';?>" method="post" enctype="multipart/form-data">
							<tr><td></td></tr>					

							<tr  style="height:10px; " >
								<td style="float: left; margin-top: -43px; margin-left: 60px; line-height:1">
							
								<td class="first_td" valign="top" style="float: left; font-size:14px; font-weight: bold; margin:6px 0 0 25px; font-family:Arial; color:#808080;"></td>
								<td style="float: left; margin-top: -43px; margin-left: 60px; line-height:1">
									<p>URL:&nbsp;<input class="input" name="ical_url" onBlur="if(this.value=='') this.value=this.defaultValue" onFocus="if(this.value==this.defaultValue) this.value=''" value="" type="text" style="width:400px; border-radius:2px; -o-border-radius:2px; -webkit-border-radius:2px; -moz-border-radius:2px; text-shadow:none;">
									
									<input class="continue-button btn" style="padding:4px;margin:4px 0 9px 5px;" name="import" type="submit" name="next" value="Import" id="bluebutt"> 
									
									<?php 
									if(isset($required_msg))
									{
										?>
										<span style="color: red;" id="required_msg">Please give valid URL.</span>
									<?php }
									if(isset($nodata_msg))
									{
										?>
										<span style="color:red;">There is no data in given URL.</span>
										<?php
									}
									?><br><br>
									<p style="color:#993300">Paste valid calendar link here, e.g. <?php echo site_url()?>calendar/ical/...</p>
									</p><br>
								</td>
							</tr>

						</table>
					</form>
				
				</div>
<?php


?>
			</div>
		</div>
	<!--</center>-->
</body>
</html>
<?php
if(count($results) != 0)
{
	?>
<p style="width: 682px;">
<span style="float:left; font-weight: bold; font-size:14px; margin-top:0px; font-family:Arial; color:#808080;">Imported URL's:</span>
	<br><br>
	<?php
	foreach ($results as $ical_url)
{

 $urls = $ical_url['url'];
 $last_sync=$ical_url['last_sync'];

?>
<div id="list_url" style="padding-bottom:15px; width: 682px;">
<span style="font-family:Arial; font-size:14px; font-weight:600;">
<?php if($urls!='')
{

if(strlen($urls)>41)
{
  echo anchor($urls ,substr($urls, 0, 41)."...");
}
else
{
  echo anchor($urls,$urls);
}
?></span>
  <span style="font-size:12px; float:right; ">
  <?php echo "Last sync : $last_sync";?></span>
  <br/>
  <span style="font-size: 11.5px;
    margin-left: 573px;
    text-align: right;"><?php
 echo anchor('administrator/lists/sync_cal/'.$ical_url['id'] ,translate('Sync Now')).'&nbsp&nbsp&nbsp';
 echo anchor('administrator/lists/delete_cal/'.$ical_url['id'] ,translate('Remove'));

}
else
{
echo "None";
}
 ?></span>
</div>
<?php
}
  }
  ?>
</p>
<br/>
<?php //} ?>
<p  style="padding-bottom:15px; margin-top:-18px; width: 100px;float:left;"><span style="float:left; font-weight: bold; font-size:14px; margin-top:0px; font-family:Arial; color:#808080; ">Export URL:</span> &nbsp;&nbsp;
	<div class="calender" style="margin-left: 90px;margin-top:-18px;position: absolute;"><?php echo anchor('calendar/ical/'.$result->id , site_url().'calendar/ical/'.$result->id)?></div></p>

							</div>
					
					</div>
				</div>	
                <!-- export instructions -->
              </div>
            </div>
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
<!-- edit_room -->
</div>

</div>


<!-- TinyMCE inclusion -->
<script type="text/javascript" src="<?php echo base_url()?>css/tiny_mce/tiny_mce.js" ></script>

<script language="Javascript">
tinyMCE.init({
	 mode : "textareas",
       editor_selector : "manual",
       editor_deselector : "highlight",
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
})

tinyMCE.init({
        mode : "textareas",
       editor_selector : "description",
       editor_deselector : "highlight",
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

var address_status = "<?php echo $lys_status->address;?>";

if(address_status == 1)
{
	   jQuery('#add_address').hide();
     	jQuery('#add_content').hide();
     	jQuery('#address_entire').show(); 
     	jQuery('#after_address').show();
}

jQuery('#add_address').click(function()
     {
     	jQuery('#address_popup1').delay(5000).show();
     	jQuery('#address_entire').hide();     	
     })

 jQuery('#edit_address1').click(function()
     {
     	jQuery('#address_entire').hide();
     	jQuery('#address_popup2').hide();
     	jQuery('#address_popup1').show();
     	
     	if(jQuery.trim(jQuery('#lys_street_address_edit').val()) == '' && jQuery.trim(jQuery('#city_edit').val()) == '' && jQuery.trim(jQuery('#zipcode_edit').val()) == '')
     	{
     		jQuery('.next_active').css('opacity',0.65);
     		jQuery('.disable-btn').show();
     		jQuery('.enable-btn').hide();
     	}
     	else
     	{
     		jQuery('.next_active').css('opacity',1);
     		jQuery('.disable-btn').hide();
     		jQuery('.enable-btn').show();
     	}
     	
     })
  jQuery('#edit_popup3').click(function()
     {
     	jQuery('#address_popup3').hide();
     	jQuery('#address_popup1').show();
     	
     	if(jQuery.trim(jQuery('#lys_street_address_edit').val())!='' && jQuery.trim(jQuery('#city_edit').val()) != '' && jQuery.trim(jQuery('#zipcode_edit').val())!= '')
     	{
     		jQuery('.next_active').css('opacity',1);
     		jQuery('.disable-btn').hide();
     		jQuery('.enable-btn').show();
     	}
     	else
     	{
     		jQuery('.next_active').css('opacity',0.65);
     		jQuery('.disable-btn').show();
     		jQuery('.enable-btn').hide();
     	}
     	
     })
      jQuery('#edit_address').click(function()
     {
     	jQuery('#address_popup2').hide();
     	jQuery('#address_popup1').show();
     	
     	if(jQuery.trim(jQuery('#lys_street_address_edit').val())!='' && jQuery.trim(jQuery('#city_edit').val()) != '' && jQuery.trim(jQuery('#zipcode_edit').val())!= '')
     	{
     		jQuery('.next_active').css('opacity',1);
     		jQuery('.disable-btn').hide();
     		jQuery('.enable-btn').show();
     	}
     	else
     	{
     		jQuery('.next_active').css('opacity',0.65);
     		jQuery('.disable-btn').show();
     		jQuery('.enable-btn').hide();
     	}
     	
     })
     
     jQuery('#finish_popup3').click(function()
     {
     	jQuery.ajax({
  type: "POST",
  url: '<?php echo base_url()."rooms/add_address";?>',
  data: { room_id: <?php echo $result->id;?>, country: jQuery('#country_edit').val(), city: jQuery('#city_edit').val(), state: jQuery('#state_edit').val(), street_address: jQuery('#lys_street_address_edit').val(), optional_address: jQuery('#apt_edit').val(), zipcode: jQuery('#zipcode_edit').val(), lat: jQuery('#hidden_lat_edit').val(), lng: jQuery('#hidden_lng_edit').val(), full_address: jQuery('#hidden_address_edit').val() },
   success: function(data)
        {
       jQuery('#str_street_address_edit').replaceWith('<strong id="str_street_address_edit">'+jQuery('#lys_street_address_edit').val()+'</strong>');
       jQuery('#str_city_state_address_edit').replaceWith('<strong id="str_city_state_address_edit">'+jQuery('#city_edit').val()+'  '+jQuery('#state_edit').val()+'</strong>');
       jQuery('#str_country_edit').replaceWith('<strong id="str_country_edit">'+jQuery('#country_edit').val()+'</strong>');
       jQuery('#str_zipcode_edit').replaceWith('<strong id="str_zipcode_edit">'+jQuery('#zipcode_edit').val()+'</strong>');
       jQuery('#str_street_address1_edit').replaceWith('<strong id="str_street_address1_edit">'+jQuery('#lys_street_address_edit').val()+'</strong>');
       jQuery('#str_city_state_address1_edit').replaceWith('<strong id="str_city_state_address1_edit">'+jQuery('#city_edit').val()+'  '+jQuery('#state_edit').val()+'</strong>');
       jQuery('#str_country1_edit').replaceWith('<strong id="str_country1_edit">'+jQuery('#country_edit').val()+'</strong>');
       jQuery('#str_zipcode1_edit').replaceWith('<strong id="str_zipcode1_edit">'+jQuery('#zipcode_edit').val()+'</strong>');
       jQuery('#str_street_address2_edit').replaceWith('<strong id="str_street_address2_edit">'+jQuery('#lys_street_address_edit').val()+'</strong>');
       jQuery('#str_city_state_address2_edit').replaceWith('<strong id="str_city_state_address2_edit">'+jQuery('#city_edit').val()+'  '+jQuery('#state_edit').val()+'</strong>');
       jQuery('#str_country2_edit').replaceWith('<strong id="str_country2_edit">'+jQuery('#country_edit').val()+'</strong>');
       jQuery('#str_zipcode2_edit').replaceWith('<strong id="str_zipcode2_edit">'+jQuery('#zipcode_edit').val()+'</strong>');	
      // jQuery('#hidden_lat_edit').val(jQuery('#hidden_lat').val());
       //jQuery('#hidden_lng_edit').val(jQuery('#hidden_lng').val());
        }
});
     	jQuery('#address_popup3').hide();
     	address_status = 1;
     	jQuery('#add_address').hide();
     	jQuery('#add_content').hide();
     	jQuery('#address_entire').show(); 
     	jQuery('#after_address').show();
     	lat= jQuery('#hidden_lat_edit').val();
     	lng = jQuery('#hidden_lng_edit').val();
     	
    	jQuery('#static_map').replaceWith('<img id="static_map" src="https://maps.googleapis.com/maps/api/staticmap?center='+lat+','+lng+'&size=370x277&zoom=15&format=png&markers=color:red|label:|'+lat+','+lng+'&key='+places_API+'&sensor=false&maptype=roadmap&style=feature:water|element:geometry.fill|weight:3.3|hue:0x00aaff|lightness:100|saturation:93|gamma:0.01|color:0x5cb8e4">');
     	
     /*	jQuery.ajax({
     		url: '<?php //echo base_url()."rooms/ajax_circle_map"; ?>',
   /*	type : 'POST',
   	data : { lat: lat, lng: lng},
   	success : function(data)
   	{
   		jQuery('#static_circle_map').show();
   		jQuery('#static_circle_map_img').replaceWith('<img width="210" height="210" id="static_circle_map_img" src="'+data+'">');
   	}
     	})*/
     })
     
  jQuery('.disable-btn').click(function()
     {
     	alert('Please enter the data');
     })
     jQuery('#next-btn').click(function()
     {
     	jQuery('#address_popup1').hide();
     	jQuery('#address_popup2').show();
     	 jQuery('#str_street_address_edit').replaceWith('<strong id="str_street_address_edit">'+jQuery('#lys_street_address_edit').val()+'</strong>');
       jQuery('#str_city_state_address_edit').replaceWith('<strong id="str_city_state_address_edit">'+jQuery('#city_edit').val()+'  '+jQuery('#state_edit').val()+'</strong>');
       jQuery('#str_country_edit').replaceWith('<strong id="str_country">'+jQuery('#country_edit').val()+'</strong>');
       jQuery('#str_zipcode_edit').replaceWith('<strong id="str_zipcode">'+jQuery('#zipcode_edit').val()+'</strong>');
     /*  jQuery('#str_street_address1').replaceWith('<strong id="str_street_address1">'+jQuery('#lys_street_address').val()+'</strong>');
       jQuery('#str_city_state_address1').replaceWith('<strong id="str_city_state_address1">'+jQuery('#city').val()+'  '+jQuery('#state').val()+'</strong>');
       jQuery('#str_country1').replaceWith('<strong id="str_country1">'+jQuery('#country').val()+'</strong>');
       jQuery('#str_zipcode1').replaceWith('<strong id="str_zipcode1">'+jQuery('#zipcode').val()+'</strong>');*/
       jQuery('#str_street_address2_edit').replaceWith('<strong id="str_street_address2_edit">'+jQuery('#lys_street_address_edit').val()+'</strong>');
       jQuery('#str_city_state_address2_edit').replaceWith('<strong id="str_city_state_address2_edit">'+jQuery('#city_edit').val()+'  '+jQuery('#state_edit').val()+'</strong>');
       jQuery('#str_country2_edit').replaceWith('<strong id="str_country2_edit">'+jQuery('#country_edit').val()+'</strong>');
       jQuery('#str_zipcode2_edit').replaceWith('<strong id="str_zipcode2_edit">'+jQuery('#zipcode_edit').val()+'</strong>');	
     })
       jQuery('#pin-on-map').click(function()
     {
     	jQuery('#address_popup1').hide();
     	jQuery('#address_popup2').hide();
     	jQuery('#address_popup3').show();
     	jQuery('.disable_finish').show();
     	jQuery('.enable_finish').hide();
     	
     	if(jQuery('#hidden_lat_edit').val() == '')
     	{
     		jQuery.ajax({
		url: '<?php echo base_url()."rooms/get_address"; ?>',
   	type : 'POST',
    dataType: 'json',
   	data : { room_id: <?php echo $result->id; ?>},
   	success : function(data)
   	{
       jQuery.each(data, function(key, value)
			  {
			  	city = value['city'];
			  	jQuery('#city_edit').val(city);
			  	state = value['state'];
			  	jQuery('#state_edit').val(state);
                country = value['country'];
                jQuery('#country_edit').val(country);
                jQuery('#hidden_lat_edit').val(value['lat']);
                jQuery('#hidden_lng_edit').val(value['long']); 
                jQuery('#zipcode_edit').val(value['zip_code']);
                jQuery('#lys_street_address_edit').val(value['street_address']);
                initialize();
			  })
   	}
	})
     	} else {
     	initialize();
     	}

     	
     })
function showhide(id)
{
		if(id == 1)
		{
		 document.getElementById("description").style.display = "block";
			document.getElementById("aminities").style.display = "none";
			document.getElementById("photo").style.display = "none";
			document.getElementById("price").style.display = "none";
			document.getElementById("calendar").style.display = "none";
			document.getElementById("overview").style.display = "none";
			document.getElementById("address").style.display = "none";
			document.getElementById("calendar_container").style.display = "none";
			
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
			document.getElementById("calendar").style.display = "none";
			document.getElementById("overview").style.display = "none";
			document.getElementById("address").style.display = "none";
			document.getElementById("calendar_container").style.display = "none";
			
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
			document.getElementById("calendar").style.display = "none";
			document.getElementById("overview").style.display = "none";
			document.getElementById("address").style.display = "none";
			document.getElementById("calendar_container").style.display = "none";
			
		document.getElementById('descriptionA').className = '';
		document.getElementById('aminitiesA').className = '';
		document.getElementById('photoA').className = 'clsNav_Act';
		document.getElementById('priceA').className = '';
		}
		else if(id == 5)
		{
			document.getElementById("photo").style.display = "none";
			document.getElementById("description").style.display = "none";
			document.getElementById("aminities").style.display = "none";
			document.getElementById("price").style.display = "none";
			document.getElementById("calendar").style.display = "block";
			document.getElementById("overview").style.display = "none";
			document.getElementById("address").style.display = "none";
			document.getElementById("calendar_container").style.display = "none";
			
		document.getElementById('descriptionA').className = '';
		document.getElementById('aminitiesA').className = '';
		document.getElementById('photoA').className = '';
		document.getElementById('priceA').className = '';
		}
		else if(id == 6)
		{
			document.getElementById("photo").style.display = "none";
			document.getElementById("description").style.display = "none";
			document.getElementById("aminities").style.display = "none";
			document.getElementById("price").style.display = "none";
			document.getElementById("calendar").style.display = "none";
			document.getElementById("overview").style.display = "block";
			document.getElementById("address").style.display = "none";
			document.getElementById("calendar_container").style.display = "none";
			
		document.getElementById('descriptionA').className = '';
		document.getElementById('aminitiesA').className = '';
		document.getElementById('photoA').className = '';
		document.getElementById('priceA').className = '';
		}
		else if(id == 7)
		{
			document.getElementById("photo").style.display = "none";
			document.getElementById("description").style.display = "none";
			document.getElementById("aminities").style.display = "none";
			document.getElementById("price").style.display = "none";
			document.getElementById("calendar").style.display = "none";
			document.getElementById("overview").style.display = "none";
			document.getElementById("address").style.display = "block";
			document.getElementById("calendar_container").style.display = "none";
			
		document.getElementById('descriptionA').className = '';
		document.getElementById('aminitiesA').className = '';
		document.getElementById('photoA').className = '';
		document.getElementById('priceA').className = '';
		}
		else if(id == 8)
		{
			document.getElementById("photo").style.display = "none";
			document.getElementById("description").style.display = "none";
			document.getElementById("aminities").style.display = "none";
			document.getElementById("price").style.display = "none";
			document.getElementById("calendar").style.display = "none";
			document.getElementById("overview").style.display = "none";
			document.getElementById("address").style.display = "none";
			document.getElementById("calendar_container").style.display = "block";
			
		document.getElementById('descriptionA').className = '';
		document.getElementById('aminitiesA').className = '';
		document.getElementById('photoA').className = '';
		document.getElementById('priceA').className = '';
		}

		else
		{
		 document.getElementById("price").style.display = "block";
			document.getElementById("description").style.display = "none";
			document.getElementById("aminities").style.display = "none";
			document.getElementById("photo").style.display = "none";
			document.getElementById("calendar").style.display = "none";
			document.getElementById("overview").style.display = "none";
			document.getElementById("address").style.display = "none";
			document.getElementById("calendar_container").style.display = "none";
			
		document.getElementById('descriptionA').className = '';
		document.getElementById('aminitiesA').className = '';
		document.getElementById('photoA').className = '';
		document.getElementById('priceA').className = 'clsNav_Act';
		}	
}
function highlight1(id)
{
	jQuery.ajax({
		url: '<?php echo base_url().'rooms/photo_highlight'?>',
		type: 'POST',
		data: { photo_id: id, msg: jQuery('#highlight_'+id).val() },
        success: function(data)
        {
        	
        }
	})
}
		
jQuery(document).ready(function()
{
		
	jQuery('#calendar_icon').click(function()
{
	jQuery.ajax({
		url: '<?php echo base_url().'administrator/lists/calendar_type'?>',
		type: 'POST',
		data: { type: 1, room_id: <?php echo $result->id?> },
        success: function(data)
        {
        	calendar = 1;
        	jQuery('#calendar_icon1').show();
        	jQuery('#calendar_icon').hide();
        }
	})
})

var calendar_status = '<?php echo $lys_status->calendar;?>';

if(calendar_status == 1)
{
	jQuery('#calendar_icon1').show();
    jQuery('#calendar_icon').hide();
}
else
{
	jQuery('#calendar_icon1').hide();
    jQuery('#calendar_icon').show();
}

})
</script>
<script>
	
</script>