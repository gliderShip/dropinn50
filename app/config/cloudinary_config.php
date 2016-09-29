<?php 


$active_group = "default";
$active_record = TRUE;
$CI =& get_instance();

$CI->load->database();
$cdn_name = $CI->db->get_where('settings', array('code' => 'cloud_name'))->row()->string_value;
$cdn_api = $CI->db->get_where('settings', array('code' => 'cloud_api_key'))->row()->string_value;
$cloud_s_key = $CI->db->get_where('settings', array('code' => 'cloud_api_secret'))->row()->string_value;
$cloud_version = $CI->db->get_where('settings', array('code' => 'cloud_name'))->row()->created + 1234;
define('cdn_name', $cdn_name);
define('cdn_api', $cdn_api);
define('cloud_s_key', $cloud_s_key);
define('cloud_version', 'v'.$cloud_version);

 $config = array(
  'cdn_name'    => $cdn_name,
  'cdn_api' => $cdn_api,
   'cloud_s_key'      => $cloud_s_key ,
   'cloud_version'      => 'v'.$cloud_version ) ;

?>