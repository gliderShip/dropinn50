<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']    = "home";
$route['search']                = "search/index";
$route['rooms/(:num)']          = "rooms/index/$1";
$route['rooms/(:num)/preview']  = "rooms/index/$1/preview";
$route['rooms/new']             = "rooms/newlist";
$route['tell_a_friend']         = "referrals/tell_a_friend";
$route['users/profile/(:num)']  = "users/vouch";
$route['404_override']          = '';
$route['hosting']          = 'listings';

$route['administrator']  	      = 'administrator/backend';
$route['administrator/login']  	= 'administrator/auth/login';

$route['administrator/admin_key/viewAdmin_key/(:any)'] = 'info';
$route['administrator/backend/(:any)'] = 'info';
$route['administrator/settings/lang_front/(:any)'] = 'info';
$route['administrator/settings/lang_back/(:any)'] = 'info';
$route['administrator/settings/home_meta_settings/(:any)'] = 'info';
$route['administrator/settings/change_password/(:any)'] = 'info';
$route['administrator/settings/how_it_works/(:any)'] = 'info';
$route['administrator/email/settings/(:any)'] = 'info';
$route['administrator/email/mass_email/(:any)'] = 'info';
$route['administrator/members/add/(:any)'] = 'info';
$route['administrator/members/changepassword/(:num)/(:any)'] = 'info';
$route['administrator/members/edit/(:num)/(:any)'] = 'info';
$route['administrator/lists/addlist/(:num)'] = 'administrator/lists/managelist/(:num)';
$route['administrator/lists/managelist/(:num)/(:any)'] = 'info';
$route['administrator/lists/view_aminity/(:any)'] = 'info';
$route['administrator/lists/view_all/(:any)'] = 'info';
$route['administrator/property_type/view_property/(:any)'] = 'info';
$route['administrator/property_type/view_all_property/(:any)'] = 'info';
$route['administrator/neighbourhoods/addcity/(:any)'] = 'info';
$route['administrator/neighbourhoods/viewcity/(:any)'] = 'info';
$route['administrator/neighbourhoods/addcity_place/(:any)'] = 'info';
$route['administrator/neighbourhoods/viewcity_place/(:any)'] = 'info';
$route['administrator/neighbourhoods/editcity_place/(:num)/(:any)'] = 'info';
$route['administrator/neighbourhoods/editcity/(:num)/(:any)'] = 'info';
$route['administrator/neighbourhoods/addcategory/(:any)'] = 'info';
$route['administrator/neighbourhoods/viewcategory/(:any)'] = 'info';
$route['administrator/neighbourhoods/editcategory/(:num)/(:any)'] = 'info';
$route['administrator/neighbourhoods/addpost/(:any)'] = 'info';
$route['administrator/neighbourhoods/viewpost/(:any)'] = 'info';
$route['administrator/neighbourhoods/editpost/(:num)/(:any)'] = 'info';
$route['administrator/neighbourhoods/addphotographer/(:any)'] = 'info';
$route['administrator/neighbourhoods/viewphotographer/(:any)'] = 'info';
$route['administrator/neighbourhoods/editphotographer/(:num)/(:any)'] = 'info';
$route['administrator/neighbourhoods/addtag/(:any)'] = 'info';
$route['administrator/neighbourhoods/viewtag/(:any)'] = 'info';
$route['administrator/neighbourhoods/edittag/(:num)/(:any)'] = 'info';
$route['administrator/neighbourhoods/addknowledge/(:any)'] = 'info';
$route['administrator/neighbourhoods/viewknowledge/(:any)'] = 'info';
$route['administrator/neighbourhoods/editknowledge/(:num)/(:any)'] = 'info';
$route['administrator/payment/finance/(:num)/(:any)'] = 'info';
$route['administrator/payment/details/(:num)/(:any)'] = 'info';
$route['administrator/payment/manage_gateway/(:num)/(:any)'] = 'info';
$route['administrator/payment/paymode/(:num)/(:any)'] = 'info';
$route['administrator/social/fb_settings/(:any)'] = 'info';
$route['administrator/social/twitter_settings/(:any)'] = 'info';
$route['administrator/social/google_settings/(:any)'] = 'info';
$route['administrator/managemetas/editmetas/(:num)/(:any)'] = 'info';
$route['administrator/page/viewPages/(:any)'] = 'info';
$route['administrator/page/addPage/(:any)'] = 'info';
$route['administrator/page/editPage/(:num)/(:any)'] = 'info';
$route['administrator/coupon/add_coupon/(:any)'] = 'info';
$route['administrator/coupon/view_all_coupon/(:any)'] = 'info';
$route['administrator/coupon/edit_coupon/(:num)/(:any)'] = 'info';
$route['administrator/help/viewhelp/(:any)'] = 'info';
$route['administrator/help/addhelp/(:any)'] = 'info';
$route['administrator/help/edithelp/(:num)/(:any)'] = 'info';
$route['administrator/faq/viewFaqs/(:any)'] = 'info';
$route['administrator/faq/editfaq/(:num)/(:any)'] = 'info';
$route['administrator/faq/addFaq/(:any)'] = 'info';
$route['administrator/contact/(:any)'] = 'info';
$route['administrator/joinus/viewJoinus/(:any)'] = 'info';
$route['administrator/cancellation/viewcancellation/(:any)'] = 'info';
$route['administrator/cancellation/editCancellation/(:num)/(:any)'] = 'info';

/* End of file routes.php */
/* Location: ./application/config/routes.php */