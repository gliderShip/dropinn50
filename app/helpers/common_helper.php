<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


function common()
 {
 	$ci =& get_instance();
	
 	$ip = getenv("REMOTE_ADDR");
	
	$result = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
	
	//if($result != false)
	//{
	$result1 = $ci->Common_model->getTableData('currency', array('currency_code' => $result['geoplugin_currencyCode'],'status'=>1));

	$ci->session->set_userdata('locale_country',$result['geoplugin_countryName']);
	
	if($result1->num_rows() != 0 && $ci->session->userdata("locale_currency") == '')
	{
	$ci->session->set_userdata("locale_currency",$result['geoplugin_currencyCode']);
	}
	//}
	/*else
    {
    	$ci->session->set_userdata("locale_currency",$ci->Common_model->getTableData('currency', array('status'=>1))->row()->currency_code);
    }*/
 }

function review_star_rating($listid)
{
 $CI     =& get_instance();
	$CI->load->model('Trips_model');
	$CI->load->model('Common_model');
	$userid=$CI->Common_model->getTableData('list',array('id'=>$listid))->row()->user_id;
	$conditions    	= array('list_id' => $listid, 'userto' => $userid);
	$CI 	=& get_instance();

	$result = $CI->Trips_model->get_review($conditions);

	$conditions  = array('list_id' => $listid, 'userto' => $userid);
	$stars		 = $CI->Trips_model->get_review_sum($conditions)->row();


	$overall=0;
	if($result->num_rows() > 0) {		 
							$accuracy      = $stars->accuracy;
							$cleanliness   = $stars->cleanliness;
							$communication = $stars->communication;
							$checkin       = $stars->checkin;
							$location      = $stars->location;
							$value         = $stars->value;
													
						$overall       = ($accuracy + $cleanliness + $communication + $checkin + $location + $value) / ($result->num_rows() * 6);

$overall=round($overall);
}
return $overall;
	
}
function get_meta_details($meta_name = '', $type = '')
{
		$CI     =& get_instance();

		if($type == 'title')
		{
		return $CI->Common_model->getTableData('metas', array('name' => $meta_name))->row()->title;
		}
		else if($type == 'meta_keyword')
		{
		return $CI->Common_model->getTableData('metas', array('name' => $meta_name))->row()->meta_keyword;
		}
		else if($type == 'meta_description')
		{
		return $CI->Common_model->getTableData('metas', array('name' => $meta_name))->row()->meta_description;
		}

}

function get_price1($val='')
{
	$percent=($val)*(55/100);
	$finalvalue=($percent*10)+$val;
	return $finalvalue;
}

function get_price2($val='')
{
	$percent=(($val)*(58/100));
	$finalvalue=($percent*10)+$val;
	return $finalvalue;
}	

function get_price3($val='')
{
	$percent=($val)*(130/100);
	$finalvalue=($percent*10)+$val;
	return $finalvalue;		 	
}

function get_price4($val='')
{
	$percent=($val)*(170/100);
	$finalvalue=($percent*10)+$val;
	return $finalvalue;	 	
}

// Advertisement popup 1 start

// Advertisement popup 1 end


?>
