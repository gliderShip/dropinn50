<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**

 * Admin URL

 *

 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the

 * first parameter either as a string or an array.

 *

 * @access	public

 * @param	string

 * @return	string

 */

if ( ! function_exists('admin_url'))
{
	function admin_url($uri = '')
	{
		$CI =& get_instance();

		$uri = 'administrator/'.$uri ;

		return $CI->config->site_url($uri);

	}

}



// ------------------------------------------------------------------------



/**

 * Images URL

 *

 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the

 * first parameter either as a string or an array.

 *

 * @access	public

 * @param	string

 * @return	string

 */

if ( ! function_exists('image_url'))

{

	function image_url($image_name = '')

	{
		$CI =& get_instance();
		$uri = str_replace($CI->config->item('index_page'),"",$CI->config->site_url()).'/css/images/'.$image_name;
		//echo $uri;exit;
		return $uri;

	}

}



// ------------------------------------------------------------------------



/**

 * Portfolio Images URL

 *

 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the

 * first parameter either as a string or an array.

 *

 * @access	public

 * @param	string

 * @return	string

 */

if ( ! function_exists('pimage_url'))

{

	function pimage_url($image_name = '')
	{

		$CI =& get_instance();

		$uri = str_replace($CI->config->item('index_page'),"",$CI->config->site_url()).'files/portfolios/'.$image_name;

		return $uri;

	}

}

function get_remote_data($url, $post_paramtrs=false)    {   $c = curl_init();curl_setopt($c, CURLOPT_URL, $url);curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); if($post_paramtrs){curl_setopt($c, CURLOPT_POST,TRUE);  curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&".$post_paramtrs );}  curl_setopt($c, CURLOPT_SSL_VERIFYHOST,false);curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0"); curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;'); curl_setopt($c, CURLOPT_MAXREDIRS, 10);  $follow_allowed= ( ini_get('open_basedir') || ini_get('safe_mode')) ? false:true;  if ($follow_allowed){curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);}curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);curl_setopt($c, CURLOPT_REFERER, $url);curl_setopt($c, CURLOPT_TIMEOUT, 60);curl_setopt($c, CURLOPT_AUTOREFERER, true);         curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');$data=curl_exec($c);$status=curl_getinfo($c);curl_close($c);preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si',  $status['url'],$link);$data=preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si','$1=$2'.$link[0].'$3$4$5', $data);$data=preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si','$1=$2'.$link[1].'://'.$link[3].'$3$4$5', $data);if($status['http_code']==200) {return $data;} elseif($status['http_code']==301 || $status['http_code']==302) { if (!$follow_allowed){if(empty($redirURL)){if(!empty($status['redirect_url'])){$redirURL=$status['redirect_url'];}}   if(empty($redirURL)){preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);if (!empty($m[2])){ $redirURL=$m[2]; } } if(empty($redirURL)){preg_match('/href\=\"(.*?)\"(.*?)here\<\/a\>/si',$data,$m); if (!empty($m[1])){ $redirURL=$m[1]; } }   if(!empty($redirURL)){$t=debug_backtrace(); return call_user_func( $t[0]["function"], trim($redirURL), $post_paramtrs);}}} return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:".json_encode($status)."<br/><br/>Last data got<br/>:$data";}

// ------------------------------------------------------------------------



/**

 * User Images URL

 *

 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the

 * first parameter either as a string or an array.

 *

 * @access	public

 * @param	string

 * @return	string

 */

if ( ! function_exists('uimage_url'))

{

	function uimage_url($image_name = '')

	{

		

		$CI =& get_instance();

		$uri = str_replace($CI->config->item('index_page'),"",$CI->config->site_url()).'files/logos/'.$image_name;

		return $uri;

	}

}


if ( ! function_exists('prfile_url'))

{

	function prfile_url($image_name = '')

	{

		

		$CI =& get_instance();

		$uri = str_replace($CI->config->item('index_page'),"",$CI->config->site_url()).'files/project_attachment/'.$image_name;

		return $uri;

	}

}


// ------------------------------------------------------------------------



/**

 * Header Redirect Admin

 *

 * Header redirect in two flavors

 * For very fine grained control over headers, you could use the Output

 * Library's set_header() function.

 *

 * @access	public

 * @param	string	the URL

 * @param	string	the method: location or redirect

 * @return	string

 */

if ( ! function_exists('redirect_admin'))

{

	function redirect_admin($uri = '', $method = 'location', $http_response_code = 302)

	{

		switch($method)

		{

			

			case 'refresh'	: header("Refresh:0;url=".admin_url($uri));

				break;

			default			: header("Location: ".admin_url($uri), TRUE, $http_response_code);

				break;

		}

		exit;

	}

}



// ------------------------------------------------------------------------



/**

 * Header Redirect Admin

 *

 * Header redirect in two flavors

 * For very fine grained control over headers, you could use the Output

 * Library's set_header() function.

 *

 * @access	public

 * @param	string	the URL

 * @param	string	the method: location or redirect

 * @return	string

 */

if ( ! function_exists('replaceSpaceWithUnderscore'))

{

	function replaceSpaceWithUnderscore($text='')

	{

		$text = str_replace(' ','_',$text);

		return $text;

		

	} //Function replaceSpaceWithUnderscore End

}



// ------------------------------------------------------------------------



/**

 * Header Redirect Admin

 *

 * Header redirect in two flavors

 * For very fine grained control over headers, you could use the Output

 * Library's set_header() function.

 *

 * @access	public

 * @param	string	the URL

 * @param	string	the method: location or redirect

 * @return	string

 */

if ( ! function_exists('replaceUnderscoreWithSpace'))

{

	function replaceUnderscoreWithSpace($text = '')

	{

		$text = str_replace('_',' ',$text);

		return $text;

	}//Function replaceUnderscoreWithSpace End

}


// ------------------------------------------------------------------------


if ( ! function_exists('css_url'))

{

	function css_url($resource = "raw")
	{
 
		$CI  =& get_instance();
		@$query = $CI->db->get_where("theme_select",array("status"=>1))->row()->color;
		if($query){
		$uri = $CI->config->item('cdn_url_raw').$CI->config->item('cdn_name').'/'.$resource.'/upload/'.$CI->config->item('cloud_version').'/'.'css_'.$query.'/'.THEME_FOLDER;	
		}else{
		$uri = $CI->config->item('cdn_url_raw').$CI->config->item('cdn_name').'/'.$resource.'/upload/'.$CI->config->item('cloud_version').'/'.'css/'.THEME_FOLDER;	
		}
		

		return $uri;

	}//Function css_url End

}

if ( ! function_exists('url_with_query'))

{
function url_with_query()
{
    $CI =& get_instance();

    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}
}


/// ==============

if ( ! function_exists('cdn_url_raw'))

{

    function cdn_url_raw()
    {

        $CI  =& get_instance();
        $uri = $CI->config->item('cdn_url_raw').$CI->config->item('cdn_name').'/raw/upload/'.$CI->config->item('cloud_version').'/';

        return $uri;

    }

}

if ( ! function_exists('cdn_url_images'))

{

    function cdn_url_images()
    {

        $CI  =& get_instance();
        $uri = $CI->config->item('cdn_url_image').$CI->config->item('cdn_name').'/image/upload/';

        return $uri;

    }

}

if ( ! function_exists('cdn_url_video'))

{

    function cdn_url_video()
    {

        $CI  =& get_instance();
        $uri = $CI->config->item('cdn_url_raw').$CI->config->item('cdn_name').'/video/upload/';

        return $uri;

    }

}


if ( ! function_exists('cdn_url_logo'))

{

    function cdn_url_logo()
    {

        $CI  =& get_instance();
        $uri = $CI->config->item('cdn_url_raw').$CI->config->item('cdn_name').'/image/upload/';

        return $uri;

    }

}


///////---------





/* End of file MY_url_helper.php */

/* Location: ./app/helpers/MY_url_helper.php */

?>