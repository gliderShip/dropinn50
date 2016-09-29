<?php
            $logo         = $this->Common_model->getTableData('settings',array('code' => 'SITE_LOGO'))->row()->string_value;
			$query        = $this->Common_model->getTableData('settings', array('code' => 'FRONTEND_LANGUAGE'));
			$trans_lang   = $query->row()->int_value;
			$default_lang = $query->row()->string_value;
			$user_lang    = $this->session->userdata('locale');
			
			if($user_lang == '')
			{
			  $locale = $default_lang;
			}
			else
			{
			  $locale = $user_lang;
			}

			$currency_code = get_currency_code();

			$currency_symbol   = $this->Common_model->getTableData('currency', array('currency_code' => $currency_code))->row()->currency_symbol;
									
			if($this->dx_auth->is_logged_in())
			{
				if($this->dx_auth->get_username() == "")
				{
				$query          = $this->Common_model->getTableData( 'profiles',array('id' => $this->dx_auth->get_user_id()) )->row();
				$name           = $query->Fname.' '.$query->Lname;
				}
				else
				{
				$name           = $this->dx_auth->get_username();
				}
			}
			else
			{
			$name = '';
			}
  ?>
  <style>
  #currency_drop {
background-color: #FFFFFF;
}
#currency > select {
margin-left: 10px;
margin-top: 10px;
height:31px;
}
#language > select
{
height:31px;
	margin-top: 0px;
}
#lang_drop
{
	top:2px;
}
  </style>
  <script>
  
  jQuery('document').ready(function(){
  	
  //	$("#language_drop").datepicker($.datepicker.regional["fr"]);
  	
  	jQuery('#currency_drop').change(function()
  	{
  		jQuery.ajax(
  		{
  			url: '<?php echo base_url().'users/change_currency'; ?>',
  			type: "post",
  			data: 'currency_code='+jQuery(this).val(),
  			success: function()
  			{
  				window.location.reload();
  			}
  		})
  	})
  	
  	jQuery('#language_drop').change(function()
  	{
  		jQuery.ajax(
  		{
  			url: '<?php echo base_url().'users/change_language'; ?>',
  			type: "post",
  			data: 'lang_code='+jQuery(this).val(),
  			success: function()
  			{
  				
  				window.location.reload();
  			}
  		})
  	})
  	
  })
 </script>
<div id="Footer">

		<div id="footer" class="container">
<div class="med_4 mal_4 pe_12">
<!--<div id="footer" class="row">
<div class="span3 clearfix">-->
<h5><?php echo translate("Language Settings"); ?> </h5>
<div class="clsFloatLeft" style="margin-top: 10px;">
<div id="language">
<div class="football_img"> 
	<img class="img_lang" src="<?php echo css_url(); ?>/images/football.png" /> 
	<!--<i class="fa fa-soccer-ball-o img_lang" style="color: #616161; margin-left: -4.9px;"></i>-->
	</div>
<!--<div class="football_img" style="top:-9px; "> 
  <!--	<img class="img_lang" src="<?php echo css_url(); ?>/images/football.png" /> 
	<i class="fa fa-soccer-ball-o img_lang" style="color: #616161; margin-left: -4.9px;"></i>
</div>-->

<?php

$default_language = $this->db->where('code','FRONTEND_LANGUAGE')->get('settings')->row()->int_value;
if($default_language == 2)
{
?>
<!-- Begin TranslateThis Button -->

<!--<div id="translate-this"><a style="width:180px;height:18px;display:block;" class="translate-this-button" href="//www.translatecompany.com/translate-this/">Translate This</a></div>

<script type="text/javascript" src="//x.translateth.is/translate-this.js"></script><script type="text/javascript">TranslateThis();</script>

<!-- End TranslateThis Button -->
<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'de,en,es,fr,it,pt', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        
<?php
}
else 
{
?>

<!--<div class="football_img"> 
<i class="fa fa-soccer-ball-o img_lang" style="color: #616161; margin-left: -3.9px; top: 9px"></i>
</div>-->
	

<?php
$default_language = $this->db->where('code','FRONTEND_LANGUAGE')->get('settings')->row()->string_value;
$default_lang_name = $this->db->where('code',$default_language)->get('language')->row()->name;
?>
<?php if($this->session->userdata('language')=="") $default_lang_name = $default_lang_name; else $default_lang_name = $this->session->userdata('language'); ?>
<div class="arrow_sym">  </div>

<select id="language_drop" onchange="this.className = this.options[this.selectedIndex].className" style="background-color: #FFFFFF;">
<?php 
$languages_core = $this->Common_model->getTableData( 'language',array('id <='=>6))->result();
foreach($languages_core as $language) { 
	if($language->name == $default_lang_name)
	{
		echo $s = 'selected';
	}
	else {
		echo $s = '';
	}
	?>

<option class="language option"   value="<?php echo $language->code; ?>" id="language_selector_<?php echo $language->code; ?>" name="<?php echo $language->code; ?>" <?php echo $s;?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $language->name; ?></option> 

  	<?php } ?>						
</select>
<?php
}
?>																				
</div>
</div>

<div class="clsFloatLeft">
<div id="currency" class="notranslate">
<select id="currency_drop" onchange="this.className = this.options[this.selectedIndex].className">
<?php 
$currencies = $this->Common_model->getTableData('currency',array('status'=>1))->result();
foreach($currencies as $currency) {
	if($currency->currency_code == $currency_code) echo $s = 'selected';
	else echo $s = '';
	  ?>		
<option value="<?php echo $currency->currency_code; ?>" name="<?php echo $currency->currency_code; ?>" id="currency_selector_<?php echo $currency->currency_code; ?>" class="currency option" <?php echo $s;?>><?php echo $currency->currency_symbol.' '.$currency->currency_code; ?> </option>
<?php } ?>					
</select>																							
</div>		
</div>

</div>
<div class="med_4 mal_4 pe_12">
<h5><?php echo translate("Discover"); ?> </h5>
<ul class="unstyled js-footer-links">

<li>
<a href="<?php echo site_url('info/how_it_works'); ?>"><?php echo translate("How it works"); ?></a>
</li>

<?php 
$result = $this->db->where('is_footer',1)->where('is_under','discover')->from('page')->get();
if($result->num_rows()!=0)
{
	foreach($result->result() as $row)
	{
	echo '<li>
<a href="'.site_url("pages/view/".$row->page_url).'">'.$row->page_name.'</a>
</li>';
}
}
?>
</ul>
</div>
<div class="med_4 mal_4 pe_12">
<h5><?php echo translate("Company"); ?></h5>
<ul class="unstyled js-footer-links">
<li>
<a href="<?php echo site_url('pages/contact'); ?>"><?php echo translate("Contact us");?></a>
</li>
<li>
<a href="<?php echo site_url('pages/faq'); ?>"><?php echo translate("FAQ"); ?></a>
</li>

<?php 
$result = $this->db->where('is_footer',1)->where('is_under','company')->from('page')->get();
if($result->num_rows()!=0)
{
	foreach($result->result() as $row)
	{
	echo '<li>
<a href="'.site_url("pages/view/".$row->page_url).'">'.$row->page_name.'</a>
</li>';
}
}
?>
</ul>
</div>
<?php
$sql="select url from joinus";$query=$this->db->query($sql);$result=$query->result_array();
$site=array();$i=1;
foreach($result as $res) { $site[$i]=$res['url']; $i=$i+1; }
	 $twitter  = $this->db->get_where('joinus', array('id' => '1'))->row()->url;
	 $facebook = $this->db->get_where('joinus', array('id' => '2'))->row()->url;
	 $google   = $this->db->get_where('joinus', array('id' => '3'))->row()->url;
	 $youtube  = $this->db->get_where('joinus', array('id' => '4'))->row()->url;
?>
<div class="med_12">
	<div class="joinus footer2">
<h5 style="text-align: center; border-top: 1px solid rgb(238, 238, 238); padding-top: 21px; border-bottom: medium none ! important;"><?php echo translate("Join us on"); ?></h5>
<ul class="unstyled js-external-links">
<li>
<a target="_blank" href="<?php echo $twitter; ?>"><i class="fa fa-twitter"></i></a>
</li>
<li>
<a target="_blank" href="<?php echo $facebook; ?>"><i class="fa fa-facebook"></i></a>
</li>
<li> 
<a target="_blank" href="<?php echo $google; ?>"><i class="fa fa-google-plus"></i></a>
</li>
<li>
<a target="_blank" href="<?php echo $youtube; ?>"><i class="fa fa-youtube-play"></i></a>
</li>
</ul>
<div id="copyright footer_copy"> Developed by <a style="color:white;" href="http://www.cogzidel.com/airbnb-clone/" target="_blank">Cogzidel Technologies</a> </div>

</div>

</div>
<!-- Advertisement popup 1 start -->
	

<!-- Advertisement popup 1 end -->


<script>
$(document).ready(function()
{
	 $('#close_search_footer').click(function()
  {
  	$('#clientsCTA').hide();
  })

window.setInterval(function(){
     var lang = $(".goog-te-menu-value span:first").text();     
    $.ajax({
                'type':'POST',
                'data':{lang:lang},
                'success':function(data) {
                    },
                'error':function(){ },
                'url':"<?php echo base_url();?>users/change_languages"
            });
    
    
},5000);

})
</script>
<?php
if($this->uri->segment(1) == 'search')
{
	echo '<button id="close_search_footer"><img src="'.base_url().'images/close.png" height="15" width="15">&nbsp;Close</button>';
}
?>
<style>
#close_search_footer
{
		bottom: 0;
position: fixed;
left: 10px;
padding-bottom: 0%;
background: #FFFFFF;
border: 1px solid #DCE0E0;
color: #565A5C;
float: left;
height: 39px;
width: 95px;
font-weight: bold;
}
.goog-te-banner-frame.skiptranslate {
    display: none !important;
    } 
body {
    top: 0px !important; 
    }
</style>
</body>
</html>
<?php
if($this->uri->segment(1) != 'search')
{
?>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.validate.min.js'; ?>"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
<?php
if($this->uri->segment(2) == 'editConfirm' || $this->uri->segment(1) == 'rooms') 
{
?>
<script>
	var places_API = "<?php echo $places_API;?>";
</script>
<script src="<?php echo base_url().'js/page3.js'; ?>"></script>
<?php
} 
else if($this->uri->segment(1) != 'users') 
{ 
if($this->uri->segment(1) == 'home' || $this->uri->segment(1) == '' || $this->uri->segment(2) == 'wishlists') 
{  
$js = array(
     array(cdn_url_raw().'/js/jquery.easing.1.3.min.js'),
    array(cdn_url_raw().'/js/jquery.sliderkit.1.8.min.js'),
    array(cdn_url_raw().'/js/sliderkit.delaycaptions.min.js'),
    array(cdn_url_raw().'/js/jquery.leanModal.min.js'),
    array(cdn_url_raw().'/js/responsiveslides.min.js'),
    array(cdn_url_raw().'/js/home_new.js')
);

$this->carabiner->group('slider',array('js'=>$js));
$this->carabiner->display('slider');
}
  } 
  }
if($this->uri->segment(2) == 'help')
{
	$help_js = array(
    array('jquery_help.min.js'),
    array('jquery-ui_help.min.js')
);

$this->carabiner->group('help',array('js'=>$help_js));
$this->carabiner->display('help');

}
if($this->uri->segment(2) == 'signin' || $this->uri->segment(2) == 'signup')
{
	?>
<script src="<?php echo base_url().'js/jquery.fancybox-1.3.4.pack.js'; ?>"></script>
	<?php
}
?>
