<!--<script type="text/javascript">

//SuckerTree Vertical Menu 1.1 (Nov 8th, 06)
//By Dynamic Drive: http://www.dynamicdrive.com/style/

var menuids=["suckertree1"] //Enter id(s) of SuckerTree UL menus, separated by commas

function buildsubmenus(){
for (var i=0; i<menuids.length; i++){
  var ultags=document.getElementById(menuids[i]).getElementsByTagName("ul")
    for (var t=0; t<ultags.length; t++){
    ultags[t].parentNode.getElementsByTagName("a")[0].className="subfolderstyle"
		if (ultags[t].parentNode.parentNode.id==menuids[i]) //if this is a first level submenu
			ultags[t].style.left=ultags[t].parentNode.offsetWidth+"px" //dynamically position first level submenus to be width of main menu item
		else //else if this is a sub level submenu (ul)
		  ultags[t].style.left=ultags[t-1].getElementsByTagName("a")[0].offsetWidth+"px" //position menu to the right of menu item that activated it
    ultags[t].parentNode.onmouseover=function(){
    this.getElementsByTagName("ul")[0].style.display="block"
    }
    ultags[t].parentNode.onmouseout=function(){
    this.getElementsByTagName("ul")[0].style.display="none"
    }
    }
		for (var t=ultags.length-1; t>-1; t--){ //loop through all sub menus again, and use "display:none" to hide menus (to prevent possible page scrollbars
		ultags[t].style.visibility="visible"
		ultags[t].style.display="none"
		}
  }
}

if (window.addEventListener)
window.addEventListener("load", buildsubmenus, false)
else if (window.attachEvent)
window.attachEvent("onload", buildsubmenus)

</script> -->
<style>
	.fav_icon{
		    font-size: 20px;
    vertical-align: middle;
    padding: 0 10px;
	}
</style>
<div id="left">
<ul id="menu" class="unstyled accordion colapse in">
  <li class="accordion-group">
  	<a href="<?php echo admin_url('backend');?>" data-target="#dashboard-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-dashboard fav_icon" aria-hidden="true"></i><?php echo translate_admin('Dashboard'); ?></a></li>
   <li class="accordion-group">
     <a href="javascript:void(0);" data-target="#site-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cogs fav_icon" aria-hidden="true"></i><?php echo translate_admin('Site Settings'); ?></a>
    <ul id="site-nav" class="collapse" style="height:0px;">
        <li><a href="<?php echo admin_url('settings'); ?>"><i class="icon-"></i><?php echo translate_admin('Global Settings'); ?></a></li>
        <li><a href="<?php echo admin_url('theme_select'); ?>"><i class="icon-"></i><?php echo translate_admin('Theme settings'); ?></a></li>
            <li><a href="javascript:void(0);" data-target="#lang-nav" data-toggle="collapse" data-parent="#menu"><i class="icon-"></i><?php echo translate_admin('Language Settings'); ?></a>
			<ul id="lang-nav" class="collapse" style="height: 0px;">
			<li><a href="<?php echo admin_url('settings/lang_front'); ?>"><i class="icon-"></i><?php echo translate_admin('Front-end Settings'); ?></a></li>
			<li><a href="<?php echo admin_url('settings/lang_back'); ?>"><i class="icon-"></i><?php echo translate_admin('Back-end Settings'); ?></a></li>
			</ul>
            </li>
            <li><a href="<?php echo admin_url('settings/home_meta_settings'); ?>"><i class="icon-"></i><?php echo translate_admin('Home page Meta Settings'); ?></a></li>
            <li><a href="<?php echo admin_url('settings/change_password'); ?>"><i class="icon-"></i><?php echo translate_admin('Change Password'); ?></a></li>
            <li><a href="<?php echo admin_url('settings/how_it_works'); ?>"><i class="icon-"></i><?php echo translate_admin('How It Works'); ?></a></li>
      </ul>
      </li>
        <li class="accordion-group">
        <a href="javascript:void(0);" data-target="#email-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-envelope fav_icon" aria-hidden="true"></i><?php echo translate_admin('E-Mail Settings'); ?></a>
	<ul id="email-nav" class="collapse" style="height: 0px;">
        <li><a href="<?php echo admin_url('email/index/1'); ?>"><i class="icon-"></i><?php echo translate_admin('E-Mail Template'); ?></a></li>
        <li><a href="<?php echo admin_url('email/settings'); ?>"><i class="icon-"></i><?php echo translate_admin('E-Mail Settings'); ?></a></li>
        <li><a href="<?php echo admin_url('email/mass_email'); ?>"><i class="icon-"></i><?php echo translate_admin('Mass E-Mail Campaigns'); ?></a></li>
     </ul>
     </li>
  		<li class="accordion-group">
        <a href="javascript:void(0);" data-target="#member-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-users fav_icon" aria-hidden="true"></i><?php echo translate_admin('Member Management'); ?></a>
	<ul id="member-nav" class="collapse" style="height: 0px;">
        <li><a href="<?php echo admin_url('members'); ?>"><i class="icon-"></i><?php echo translate_admin('User Management'); ?></a></li>
        <li><a href="<?php echo admin_url('superhost'); ?>"><i class="icon-"></i><?php echo translate_admin('Super Host Management'); ?></a></li>
     </ul>
     </li><li class="accordion-group"><a href="<?php echo admin_url('lists'); ?>" data-target="#user-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-list fav_icon" aria-hidden="true"></i><?php echo translate_admin('User Listing Management'); ?></a></li>
  		<li class="accordion-group">
  		<a href="javascript:void(0);" data-target="#man-ame-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cog fav_icon" aria-hidden="true"></i><?php echo translate_admin('Manage Amenities'); ?></a>
     <ul id="man-ame-nav" class="collapse" style="height:0px;">
		<li><a href="<?php echo admin_url('lists/addamenities'); ?>"><i class="icon-"></i><?php echo translate_admin('Add Amenity'); ?></a></li>
		<li><a href="<?php echo admin_url('lists/view_all'); ?>"><i class="icon-"></i><?php echo translate_admin('View Amenities'); ?></a></li>
     </ul>
        </li>
         <li class="accordion-group <?php if($this->uri->segment(2) == 'property_type') echo "selected"; ?>">
         <a href="javascript:void(0);" data-target="#man-pro-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cog fav_icon" aria-hidden="true"></i><?php echo translate_admin('Manage Property types'); ?></a>
         <ul id="man-pro-nav" class="collapse" style="height: 0px;">
		<li><a href="<?php echo admin_url('property_type/view_property'); ?>"><i class="icon-i"></i><?php echo translate_admin('Add Property type'); ?></a></li>
		<li><a href="<?php echo admin_url('property_type/view_all_property'); ?>"><i class="icon-i"></i><?php echo translate_admin('View Property types'); ?></a></li>
      </ul>
      </li>  
       <li class="accordion-group <?php if($this->uri->segment(2) == 'language') echo "selected"; ?>">
       <a href="javascript:void(0);" data-target="#man-lan-nav" data-toggle="collapse" data-parent="#menu"> <i class="fa fa-language fav_icon" aria-hidden="true"></i><?php echo translate_admin('Manage Languages'); ?></a>
       <ul id="man-lan-nav" class="collapse" style="height:0px;">
	   <li><a href="<?php echo admin_url('language/add_language'); ?>"><i class="icon-i"></i><?php echo translate_admin('Add Language'); ?></a></li>
	   <li><a href="<?php echo admin_url('language/view_languages'); ?>"><i class="icon-i"></i><?php echo translate_admin('View Languages'); ?></a></li>
       </ul>
       </li>  
       <li class="accordion-group">
       <a href="javascript:void(0);" data-target="#neigh-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-flag-o fav_icon" aria-hidden="true"></i> <?php echo translate_admin('Neighbourhoods'); ?></a>
       <ul id="neigh-nav" class="collapse" style="height: 0px;">
	   <li><a href="<?php echo admin_url('neighbourhoods/addcity'); ?>"><i class="icon"-></i><?php echo translate_admin('Add Cities'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/viewcity'); ?>"><i class="icon"-></i><?php echo translate_admin('View Cities'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/addcity_place'); ?>"><i class="icon"-></i><?php echo translate_admin('Add Places'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/viewcity_place'); ?>"><i class="icon"-></i><?php echo translate_admin('View Places'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/addcategory'); ?>"><i class="icon"-></i><?php echo translate_admin('Add Categories'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/viewcategory'); ?>"><i class="icon"-></i><?php echo translate_admin('View Categories'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/addpost'); ?>"><i class="icon"-></i><?php echo translate_admin('Add Posts'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/viewpost'); ?>"><i class="icon"-></i><?php echo translate_admin('View Posts'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/addphotographer'); ?>"><i class="icon"-></i><?php echo translate_admin('Add Photographers'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/viewphotographer'); ?>"><i class="icon"-></i><?php echo translate_admin('View Photographers'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/addtag'); ?>"><i class="icon"-></i><?php echo translate_admin('Add Tags'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/viewtag'); ?>"><i class="icon"-></i><?php echo translate_admin('View Tags'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/addknowledge'); ?>"><i class="icon"-></i><?php echo translate_admin('Add Local Knowledges'); ?></a></li>
       <li><a href="<?php echo admin_url('neighbourhoods/viewknowledge'); ?>"><i class="icon"-></i><?php echo translate_admin('View Local Knowledges'); ?></a></li>
       </ul>
        </li>  
        <li class="accordion-group">
        <a href="javascript:void(0);" data-target="#finan-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-money fav_icon" aria-hidden="true"></i><?php echo translate_admin('Finance'); ?></a>
        <ul id="finan-nav" class="collapse" style="height: 0px;">
        <li><a href="<?php echo admin_url('payment/finance'); ?>"><i class="icon-"></i><?php echo translate_admin('Guest Booking'); ?></a></li>
        <li><a href="<?php echo admin_url('payment/paid_payments'); ?>"><i class="icon-"></i><?php echo translate_admin('Paid Payments'); ?></a></li>
        <li><a href="<?php echo admin_url('payment/pending_payments'); ?>"><i class="icon-"></i><?php echo translate_admin('Pending Payments'); ?></a></li>
        <li><a href="<?php echo admin_url('payment/list_pay'); ?>"><i class="icon-"></i><?php echo translate_admin('Host Listing'); ?></a></li>
        <li><a href="<?php echo admin_url('payment/accept_pay'); ?>"><i class="icon-"></i><?php echo translate_admin('Reservation Accept'); ?></a></li>
        </ul>
        </li>
         <li class="accordion-group">
        <a href="<?php echo admin_url('referrals/index'); ?>" data-target="#fbc-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cog fav_icon" aria-hidden="true"></i><?php echo translate_admin('Referral Management'); ?></a></li>
       
        
        <li class="accordion-group">
        <a href="javascript:void(0);" data-target="#payment-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-money fav_icon" aria-hidden="true"></i><?php echo translate_admin('Payment Settings'); ?></a>
         <ul id="payment-nav" class="collapse" style="height: 0px;">
         <li class="accordion-group">
         <a href="javascript:void(0);" data-target="#payment-gat-nav" data-toggle="collapse" data-parent="#menu"><i class="icon-"></i><?php echo translate_admin('Payment Gateway'); ?></a>
         <ul id="payment-gat-nav" class="collapse" style="height: 0px;">
                 <!--<li><a href="<?php echo admin_url('payment'); ?>"><?php echo translate_admin('Add Pay Gateway'); ?></a></li>-->
          <li><a href="<?php echo admin_url('payment/manage_gateway'); ?>"><i class="icon-"></i><?php echo translate_admin('Manage Pay Gateway'); ?></a></li>
          </ul>
          </li>
          <li><a href="<?php echo admin_url('payment/paymode'); ?>"><i class="icon-"></i><?php echo translate_admin('Commission Setup'); ?></a></li>
          </ul>
        </li>
        <!--<li class="accordion-group">
        <a href="<?php echo admin_url('social/footer_link'); ?>" data-target="#foot-soc-nav" data-toggle="collapse" data-parent="#menu"><i class="icon-"></i><?php echo translate_admin('Footer Social Link'); ?></a></li>-->
        <li class="accordion-group">
        <a href="<?php echo admin_url('social/fb_settings'); ?>" data-target="#fbc-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-facebook-square fav_icon" aria-hidden="true"></i><?php echo translate_admin('Facebook Connect'); ?></a></li>
        <li class="accordion-group">
        <a href="<?php echo admin_url('social/twitter_settings'); ?>" data-target="#twit-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-twitter-square fav_icon" aria-hidden="true"></i><?php echo translate_admin('Twitter Connect'); ?></a></li> 
        <li class="accordion-group">
        <a href="<?php echo admin_url('social/google_settings'); ?>" data-target="#google-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-google-plus-square fav_icon" aria-hidden="true"></i><?php echo translate_admin('Google Connect'); ?></a></li>
        <li class="accordion-group">
        <a href="<?php echo admin_url('social/cloudinary_settings'); ?>" data-target="#google-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cloud-upload fav_icon" aria-hidden="true"></i><?php echo translate_admin('Cloudinary Connect'); ?></a></li>
        <li class="accordion-group"> 
        <a href="<?php echo admin_url('social/mobile_verification_settings'); ?>" data-target="#ph-no-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-phone-square fav_icon" aria-hidden="true"></i><?php echo translate_admin('Phone Number Verification'); ?></a></li>
        <li class="accordion-group">
        <a href="<?php echo admin_url('managemetas'); ?>" data-target="#man-meta-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cog fav_icon" aria-hidden="true"></i><?php echo translate_admin('Manage Meta'); ?></a></li>
        <li class="accordion-group">
        <a href="<?php echo admin_url('currency'); ?>" data-target="#man-curr-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-money fav_icon" aria-hidden="true"></i><?php echo translate_admin('Manage Currency'); ?></a></li>
        <li class="accordion-group">
        <a href="<?php echo admin_url('page/viewPages'); ?>" data-target="#man-sta-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cog fav_icon" aria-hidden="true"></i><?php echo translate_admin('Manage Static Pages'); ?></a></li>
        <li class="accordion-group <?php if($this->uri->segment(2) == 'coupon' && $this->uri->segment(3) != 'plans') echo "selected"; ?>">
        <a href="javascript:void(0);" data-target="#cou-cnt-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-tags fav_icon" aria-hidden="true"></i><?php echo translate_admin('Coupon Control System'); ?></a> 
		<ul id="cou-cnt-nav" class="collapse" style="height: 0px;">
		<li><a href="<?php echo admin_url('coupon/add_coupon'); ?>"><i class="icon-i"></i><?php echo translate_admin('Add Coupon Codes'); ?></a></li>
		<li><a href="<?php echo admin_url('coupon/view_all_coupon'); ?>"><i class="icon-i"></i><?php echo translate_admin('View Coupon Codes'); ?></a></li>
        </ul>
        </li>
        <!--<li><a href="javascript:void(0);"><?php echo translate_admin('Manage_Neighborhoods'); ?></a>
                        <ul style="z-index: 999;">
						 <li><a href="<?php echo admin_url('email/addplace'); ?>"><?php echo translate_admin('Add_Places'); ?></a></li>
                         <li><a href="<?php echo admin_url('email/viewplace'); ?>"><?php echo translate_admin('View_places'); ?></a></li>
                         
                        </ul>
     </li>-->
        <li class="accordion-group"><a href="<?php echo admin_url('help/viewhelp'); ?>" data-target="#help-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-question-circle fav_icon" aria-hidden="true"></i><?php echo translate_admin('Help'); ?></a></li>
    	<?php /*?><li><a href="<?php echo admin_url('social/news_letter'); ?>"><?php echo translate_admin('News Letter'); ?></a></li> <?php */?>
       <li class="accordion-group">
       <a href="<?php echo admin_url('admin_key/viewAdmin_key'); ?>" data-target="#admin-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-key fav_icon" aria-hidden="true"></i><?php echo translate_admin('Admin Key'); ?></a></li>
        <li class="accordion-group">
        <a href="<?php echo admin_url('faq/viewFaqs'); ?>" data-target="#faq-sys-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-question-circle fav_icon" aria-hidden="true"></i><?php echo translate_admin('FAQ System'); ?></a></li>  
        <li class="accordion-group">
        <a href="<?php echo admin_url('contact'); ?>" data-target="#man-con-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cog fav_icon" aria-hidden="true"></i><?php echo translate_admin('Manage Contact'); ?></a></li>
        <li class="accordion-group">
        <a href="<?php echo admin_url('joinus/viewJoinus'); ?>" data-target="#join-us-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-hand-o-right fav_icon" aria-hidden="true"></i><?php echo translate_admin('Join us on'); ?></a></li>
        <li class="accordion-group">
        <a href="javascript:void(0);" data-target="#man-can-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cog fav_icon" aria-hidden="true"></i><?php echo translate_admin('Manage Cancellation Policy'); ?></a>
		<ul id="man-can-nav" class="collapse" style="height: 0px;">
		<li><a href="<?php echo admin_url('cancellation/viewcancellation'); ?>"><i class="icon-i"></i><?php echo translate_admin('Guest Cancellation Policy'); ?></a></li>
		<li><a href="<?php echo admin_url('cancellation/edit_host_Cancellation'); ?>"><i class="icon-i"></i><?php echo translate_admin('Host Cancellation Policy'); ?></a></li>
        </ul>
        </li>
         <!-- Advertisement popup 1 start -->
		<li class="accordion-group">
		<a href="<?php echo admin_url('popup'); ?>" data-target="#man-pop-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-cog fav_icon" aria-hidden="true"></i><?php echo translate_admin('Manage Popups'); ?></a></li>

               <!-- Advertisement popup 1 end -->
        <li class="accordion-group">
        <a href="<?php echo admin_url('backup'); ?>" data-target="#backup-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-folder-o fav_icon" aria-hidden="true"></i><?php echo translate_admin('Backup'); ?></a></li>
          <!-- Google Analytics 1 start -->
         <li class="accordion-group">
         <a href="<?php echo admin_url('Google_Analytics'); ?>" data-target="#google-ana-nav" data-toggle="collapse" data-parent="#menu"><i class="fa fa-line-chart fav_icon" aria-hidden="true"></i><?php echo translate_admin('Google Analytics'); ?></a></li>
             <!-- Googe Analytics1 end -->
</ul>
</div> 

