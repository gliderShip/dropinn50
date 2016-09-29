<<?php 

$cdn =& get_instance();
$cdn_name = $cdn->db->get_where('settings', array('code' => 'cloud_name'))->row()->string_value;
$cdn_api = $cdn->db->get_where('settings', array('code' => 'cloud_api_key'))->row()->string_value;
$cloud_s_key = $cdn->db->get_where('settings', array('code' => 'cloud_api_secret'))->row()->string_value;
define('cdn_name', $cdn_name);
define('cdn_api', $cdn_api);
define('cloud_s_key', $cloud_s_key);

 ?>