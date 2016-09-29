<script>
    $(function () {
      // Slideshow 4
      $("#slider4").responsiveSlides({
        auto: true,
        pager: false,
       // nav: true,
        speed: 500,
        namespace: "callbacks",
        before: function () {
          $('.events').append("<li>before event fired.</li>");
        },
        after: function () {
          $('.events').append("<li>after event fired.</li>");
        }
      });

    });
  </script>
  <style>
  	.rslides.callbacks.callbacks1
  	{
  		min-height:400px;
  	}
  </style>
  <?php
  if($cities->num_rows() != 0)
{
		echo '
 <div class="callbacks_container neighborhood_home" style="min-height:400px;">
<div style="width:100%;">
		<div class="neighbourhoods">
        		<ul>
  
    <li><h1 class="neighborhoods">';?><?php echo translate("Neighborhoods");?><?php echo '</h1></li>
</ul>
    <li><div class="center_nei">	
<ul class="nei_country">';
}
else {
		echo '
<div class="callbacks_container neighborhood_home" style="min-height:400px;">
<div style="width:100%;">
		<div class="neighbourhoods">
        		<ul>
    <li class="med_12 mal_12 pe_12 fullwidth370 Text-ellipsis1"><h1 class="neighborhoods">';?><?php echo translate("No Neighborhoods");?><?php echo '</h1></li>
  </ul>
 
  
  
  <tr>
    <td><div class="center_nei">	
<ul class="nei_country">';
	
	
	
	
}	
	?>


	
<?php
if($cities->num_rows() != 0)
{
foreach($cities->result() as $city)
{
?>
<li class="med_3 mal_4 pe_12 fullwidth370 Text-ellipsis1"><ul class="nei_country_line">

<!--<a href="<?php echo site_url()."rooms/".$row->id; ?>"></a>-->
<li class="med_12 mal_12 pe_12 nopadding Text-ellipsis1">
<a href='<?php echo site_url()."neighbourhoods/city/".$city->id; ?>'>
<?php echo $city->city_name; ?></a>
<?php
$city_created = $this->db->where('city_name',$city->city_name)->get('neigh_city')->row()->created;
 $month = 60 * 60 * 24 * 30; // month in seconds
if (time() - $city_created < $month) {
  // within the last 30 days ...
  echo '<span>'.translate("New").'</span>';
}
 ?></li>
</ul></li>

<?php } 
 }
else
	{
		
	} ?>
	</ul>
</div>
</li>
  </tr>
</ul></div></div>
<ul class="rslides" id="slider4">
<?php 

foreach($cities->result() as $row) 
{

$url = base_url().'images/neighbourhoods/'.$row->id.'/'.$row->image_name; 


?>
<li>
<img class="neigh_img" src="<?php echo $url; ?>" alt="">
<div class="caption">
<div class="room_head">
<strong>
<span> <a href="<?php echo base_url().'search?location='.$row->city_name; ?>"><?php echo $row->city_name; ?></a> </span>
</strong>
</div>
</div>
</li>
<?php } ?>
</ul>
</div>
<style>

</style>


