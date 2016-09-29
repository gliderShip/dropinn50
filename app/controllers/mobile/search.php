<?php
/**
 * DROPinn Search Controller Class
 *
 * helps to achieve common tasks related to the site for mobile app like android and iphone.
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Search
 * @author		Cogzidel Product Team
 * @version		Version 1.0
 * @link		http://www.cogzidel.com
 
 */
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	public function Search()
	{
		parent::__construct();
		
		$this->load->helper('url');
		
		$this->load->library('DX_Auth');  

		$this->load->model('Users_model');
		$this->load->model('Gallery');
	}
	
	public function index()
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
		$search_view       = $this->input->get('search_view');
		
		$min               = $this->input->get('price_min');
		$max               = $this->input->get('price_max');
		
		$keywords          = $this->input->get('keywords');
		
	 $search_by_map     = $this->input->get('search_by_map');
		$sw_lat            = $this->input->get('sw_lat');
		$sw_lng            = $this->input->get('sw_lng');
		$ne_lat            = $this->input->get('ne_lat');
		$ne_lng            = $this->input->get('ne_lng');
		
		$min_bedrooms      = $this->input->get('min_bedrooms');
		$min_bathrooms     = $this->input->get('min_bathrooms');
		$min_beds          = $this->input->get('min_beds');
		
		$property_type_id  = $this->input->get('property_type_id');
		$hosting_amenities = $this->input->get('hosting_amenities');
		
		
		$array_items = array(
												'Vcheckin'                => '',
												'Vcheckout'               => '',
												'Vcheckout'					          => '',
								);
    $this->session->unset_userdata($array_items);
				
			if($this->input->post('checkin') != '' || $this->input->post('checkin') != 'mm/dd/yy')
		 {
		 	$freshdata = array(
									'Vcheckin'                => $this->input->get('checkin'),
									'Vcheckout'               => $this->input->get('checkout'),
									'Vnumber_of_guests'					  => $this->input->get('number_of_guests'),
					);
			 $this->session->set_userdata($freshdata);
				}
		
		 if($checkin!='--' && $checkout!='--' && $checkin!="yy-mm-dd" && $checkout!="yy-mm-dd" )
		 { 
						$ans = $this->db->query("SELECT id,list_id FROM `calendar` WHERE `booked_days` = '".$checkin."' OR `booked_days` = '".$checkout."' GROUP BY `list_id`");
						//echo $this->db->last_query();exit;
						$a   = $ans->result();
					//	$this->db->flush_cache();
						// Now after the checkin is completed
						if(!empty($a))
						{
							foreach($a as $a1)
							{ 
								array_push($stack, $a1->list_id);
							}
						}	
		 }
		  
		$query  = $this->input->get('location');
		$pieces = explode(",", $query);

		$print  = "";
		$len    = count($pieces);
		
	 	$condition = ''; 
	 	$condition .= "(`status` != '0')";
		
		if($search_by_map)
		{
		$condition .= "AND (`lat` BETWEEN $sw_lat AND $ne_lat) AND (`long` BETWEEN $sw_lng AND $ne_lng)";
		}
		else
		{
		if($query != '')
		{
			$i = 1;
			foreach($pieces as $address)
			{
				//$this->db->flush_cache();		
				$address = $this->db->escape_like_str($address);
				
				if($i == $len)
				$and = "";
				else
				$and = " OR ";

				if($i == 1)
				$condition .= " AND (";
				
				$condition .=  "`address`  LIKE '%".$address."%' OR `country` LIKE '%".$address."%'".$and;
				
				if($i == $len)
				$condition .= ")";
				
				$i++;
			}
		}
		}
		
		if(!empty($min_bedrooms))
		{
				$condition .= " AND (`bedrooms` = '".$min_bedrooms."')";
		}
		if(!empty($property_type))
		{
				$condition .= " AND (`property_id` = '".$property_type."')";
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
			$condition .= " AND (`capacity` >= '".$nof_guest."')";
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
									$or=" OR ";
					
									if($i == 1)
									$condition .= " AND (";
									
									$condition .=  "`room_type` LIKE '%".$room_type."%'".$or."`neighbor` = '".$room_type."'".$or."`neighbor` = '".$room_type."'".$or."`room_type` = '".$room_type."'".$or."`room_type` = '".$room_type."'".$and;									
									
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
						$this->db->where('price >=', $min);
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
						$this->db->where('price <=', $max);
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
		
   			//Exececute the query
   			
		$condition .= " AND (`status` != '0') AND (`user_id` != '0') AND (`address` != '0') AND (`is_enable` = '1')";
		
		$query_status = $this->db->where($condition)->get('list');
		
			  if($query_status->num_rows() != 0)
			{
				foreach($query_status->result() as $row_status)
				{
					$result_status = $this->db->where('id',$row_status->id)->get('lys_status');
					
					if($result_status->num_rows() != 0)
					{
						$result_status = $result_status->row();
											
				        $total = $result_status->calendar+$result_status->price+$result_status->overview+$result_status->photo+$result_status->address+$result_status->listing;
	
						if($total != 6)
						{
							$condition .= " AND (`id` != '".$row_status->id."')";
						}
					}
				}
			}	
			
			//$data['query'] = $this->db->get('list');
			$data['query'] = $this->db->query("SELECT * FROM (`list`) WHERE $condition");
			$tCount        = $data['query']->num_rows();
			//echo $this->db->last_query();exit;
			
			$properties = '';
			$sno   = 1; 
			if($data['query']->num_rows() > 0)
			{
					foreach($data['query']->result() as $row)
					{
						 $currency_symbol = $this->db->select('currency_symbol')->where('currency_code',$row->currency)->get('currency')->row()->currency_symbol;
					
						 $condition    = array("is_featured" => 1);
						$list_image   = $this->Gallery->get_imagesG($row->id, $condition)->row();

					if(isset($list_image->name))
					{
						$image_url_ex = explode('.',$list_image->name);

						$url = base_url().'images/'.$row->id.'/'.$image_url_ex[0].'_crop.jpg';
					}
					else
					{
						$url = base_url().'images/no_image.jpg';
					}
					
					$profile_pic = $this->Gallery->profilepic($row->user_id, 3);
					
					if($tCount == $sno) $comma = ''; else $comma = ',';
										
					$properties .= '{
					               "available":true,
																				"user_thumbnail_url":"'.$profile_pic.'",
																				"user_is_superhost":false,
																				"lat":'.$row->lat.',
																				"has_video":false,
																				"recommendation_count":0,
																				"lng":'.$row->long.',
																				"user_id":'.$row->user_id.',
																				"user_name":"'.get_user_by_id($row->user_id)->username.'",
																				"review_count":'.$row->review.',
																				"address":"'.$row->address.'",
																				"city":"'.$row->city.'",
																				"state":"'.$row->state.'",
																				"country":"'.$row->country.'",
																				"name":"'.$row->title.'",
																				"hosting_thumbnail_url":"'.$url.'",
																				"id":'.$row->id.',
																				"price":'.$row->price.',
																				"currency":"'.$row->currency.'",
																				"currency_symbol":"'.$currency_symbol.'"
																				}'.$comma;
								
		
					$sno++;
					}
			}
			else
			{
			  $properties = '{"available":false,"reason_message":"Your search was a little too specific, searching for a different city."}';
			}
	    
	
			$ajax_result  = '[';
																				
			$ajax_result .= 	$properties;		
			
			$ajax_result .=']';													
			
			echo $ajax_result;
	}
	
	
	public function dateconvert($date)
	{
		$ckout = explode('/', $date);
		$diff = $ckout[2].'-'.$ckout[0].'-'.$ckout[1];
		return $diff;
	}
	
	public function discover1(){
	$dis = $this->db->query('select * from list');
	//print_r($dis);
	foreach($dis->result() as $row){
		$id = $row->id;
		$user_id = $row->user_id;
		$email=$this->db->where('id',$user_id)->get('profiles')->row('email');
		if($id <= 3)
		{
		$data['id']  = $row->id;
		$data['user_id']  = $row->user_id;
		//$data['image'] = $this->db->where('list_id',$id)->get('list_photo')->row('image');
		$data['title'] = $row->title;
		$data['price']=$row->price;
		$data['country'] = $row->country;
		$data['city']  = $row->city;
		$data['address']  = $row->address;
		$data['room_type'] = $row->room_type;
		$currency  = $row->currency;
				$data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
				$country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
				$country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				if(!empty($country_code)){
					$data['currency_code']   = $country_code;
				}
                else{
                	$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                }
		$resize=$this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
		$src = $this->db->where('email',$email)->get('profile_picture')->row('src');
		$empty_image = base_url().'images/no_avatar.jpg';
		$empty_image1 = base_url().'images/no_image.jpg';
		if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                $data['src']  = $empty_image;
                }		
				if(!empty($resize)){
					$data['image']   = $resize;
				}
                else{
	                 $data['image']  = $empty_image1;
                }
				
		$disc[] = $data;
		}
		else if($id > 4 && $id <= 10)
		{
		$data['id']  = $row->id;
		$data['user_id'] = $row->user_id;
		$data['title']=$row->title;
		$data['price']=$row->price;
		$data['country'] = $row->country;
		$data['city']  = $row->city;
		$data['address']  = $row->address;
		$data['room_type'] = $row->room_type;
		$currency  = $row->currency;
				$data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
				$country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
				$country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				if(!empty($country_code)){
					$data['currency_code']   = $country_code;
				}
                else{
                	$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                }
		$resize=$this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
		$src = $this->db->where('email',$email)->get('profile_picture')->row('src');
		$empty_image = base_url().'images/no_avatar.jpg';
		$empty_image1 = base_url().'images/no_image.jpg';
		if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                $data['src']  = $empty_image;
                }		
				if(!empty($resize)){
					$data['image']   = $resize;
				}
                else{
	                 $data['image']  = $empty_image1;
                }
				
		$disc[] = $data;
		}
		
	}
	echo json_encode($disc, JSON_UNESCAPED_SLASHES);
}
	
	/*public function filter(){
		//$roomid            = $this->input->get('roomid');
		$checkin           = $this->input->get('checkin');   
		$checkout          = $this->input->get('checkout');
		$guest         = $this->input->get('guest');
		$room_type     = $this->input->get('room_type');
		$max         = $this->input->get('max_price');
		$min        = $this->input->get('min_price');
		$amenities         = $this->input->get('amenities');
		$beds         = $this->input->get('beds');
		$bedrooms         = $this->input->get('bedrooms');
		$bathrooms         = $this->input->get('bathrooms');
		
		$data1['checkin']    = get_gmt_time(strtotime($checkin));
		$data1['checkout']   = get_gmt_time(strtotime($checkout));
		$condition = "(`status` != 0) AND (`user_id` != 0) AND (`address` != 0) AND (`is_enable` = 1)";*/
		/*$condition = "(`status` != 0) AND (`user_id` != 0) AND (`address` != 0) AND (`is_enable` = 1)";
		$max_price =$this->db->where('price',$min)->get('list');
		$max_price =$this->db->where('price',$max)->or_where('price',$min)->get('list');
		//print_r($this->db->last_query());exit;
		
	if($max_price->num_rows()!=0)
	{
		 foreach($max_price->result() as $row2)
		 {
		 	$final =$row2->price; 
			//print_r($final);*/
			
/*if($min == '' && $max == '')
				{
					
				}
		else {
		$query_price = $this->db->where($condition)->get('list');
			
			
		   if($query_price->num_rows() != 0)
			{
				foreach($query_price->result() as $row_price)
				{
					$room_id = $row_price->id;
					
					$check_price = get_currency_value1($row_price->id,$row_price->price);
					//echo $max. ' - '.$min;exit;
					if($check_price <= $max || $check_price >= $min)
					{
						if($min == '' && $max == '')
				{
					
				}
				elseif($min == $max)
				{
					$condition .= " AND (`price` >= '".$min."')";
					$condition .= " AND (`price` <= '".$max."')";
				}
            else {
		
				if($min != '')
				{
					if($min > 0)
					{
							$condition .= " AND (`price` >= '".$min."')";
					}
				}
				else
				{
				  if($max != '')
						{
				  $min = 0;
						}
				}
				
				if($max != '')
				{
					
					 if($max > $min)
						{
							$condition .= " AND (`price` <= '".$max."')";
						}
						
				}			
} 
		
					}
else {
	$condition .= " AND (`id` != '".$row_price->id."')";
}
			
		$query   = $this->db->query('select * from `list` where `capacity` = "'.$guest.'" AND `room_type` = "'.$room_type.'" AND '.$condition.' AND `amenities` = "'.$amenities.'" AND `beds` = "'.$beds.'" AND `bedrooms` = "'.$bedrooms.'" AND `bathrooms` = "'.$bathrooms.'"');
		
			}
			}
		
		}
		if($query->num_rows()!=0)
	    {
	     foreach($query->result() as $row)
	     {
	     	$data['id']  = $row->id;
			$data['user_id']=$row->user_id;
		$data['country']=$row->country;
		$data['city']=$row->city;
		$data['state']=$row->state;
		$data['room_type']=$row->room_type;
		$data['email']=$this->db->where('id',$data['user_id'])->get('profiles')->row('email');
				$data['title']=$row->title;
				$data['price']=$row->price;
				$data['capacity']=$row->capacity;
				$data['currency']=$row->currency;
				$data['currency_symbol']=$this->db->where('currency_code',$data['currency'])->get('currency')->row('currency_symbol');
				$data['image']=$this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
				$src = $this->db->where('email',$data['email'])->get('profile_picture')->row('src');
				if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                 $data['src']  = 'null';
                }
				$fil[]=$data;
		
	     }
           echo json_encode($fil);
	    }
	   // }
	    //}
		else{
	echo '[{"status":"No List Found"}]';
    }
	}*/
	
public function default_discover1(){
	$query = $this->db->query('select * from discover');
	
	foreach($query->result() as $cover){
		$data['id']  = $cover->id;
		$data['country']  = $cover->country;
		$data['image']   = $cover->image;
		$val[]  = $data;
	}
	echo json_encode($val);
}
public function filter1(){
		//$roomid            = $this->input->get('roomid');
		$checkin           = $this->input->get('checkin');   
		$checkout          = $this->input->get('checkout');
		$guest         = $this->input->get('guest');
		$room_type     = $this->input->get('room_type');
		$price         = $this->input->get('price');
		$amenities         = $this->input->get('amenities');
		$beds         = $this->input->get('beds');
		$bedrooms         = $this->input->get('bedrooms');
		$bathrooms         = $this->input->get('bathrooms');
		
		$data1['checkin']    = get_gmt_time(strtotime($checkin));
		$data1['checkout']   = get_gmt_time(strtotime($checkout));
		
		$query   = $this->db->query('select * from `list` where `capacity` = "'.$guest.'" AND `room_type` = "'.$room_type.'" AND `price` = "'.$price.'" AND `amenities` = "'.$amenities.'" AND `beds` = "'.$beds.'" AND `bedrooms` = "'.$bedrooms.'" AND `bathrooms` = "'.$bathrooms.'"');
        //print_r($this->db->last_query());
		if($query->num_rows()!=0)
	    {
	     foreach($query->result() as $row)
	     {
	     	$data['id']  = $row->id;
			$data['user_id']=$row->user_id;
		$data['country']=$row->country;
		$data['city']=$row->city;
		$data['state']=$row->state;
		$data['room_type']=$row->room_type;
		$data['email']=$this->db->where('id',$data['user_id'])->get('profiles')->row('email');
				$data['title']=$row->title;
				$data['price']=$row->price;
				$data['capacity']=$row->capacity;
				$data['currency']=$row->currency;
				$data['currency_symbol']=$this->db->where('currency_code',$data['currency'])->get('currency')->row('currency_symbol');
				$data['image']=$this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
				$data['src'] = $this->db->where('email',$data['email'])->get('profile_picture')->row('src');
				
				$fil[]=$data;
		
	     }
           echo json_encode($fil);
	    }
		else{
	echo '[{"status":"No List Found"}]';
    }
		
	}

public function listing(){
	$location = $this->input->get('location');
	
	//$query_data = $this->db->where('address',$location)->get('list');
	
		//$status_details = $query_data->row()->status;
		
		//$this->db->like('address',$location);
		//$this->db->or_like('city',$location);
		//$this->db->or_like('state',$location);
		//$this->db->or_like('country',$location);
		$query = $this->db->query('SELECT * FROM `list` WHERE (`check_status` != 0 ) AND ( `address` LIKE "%'.$location.'%" OR `city` LIKE "%'.$location.'%" OR `state` LIKE "%'.$location.'%" OR `country` LIKE "%'.$location.'%" )');
	//echo $query->num_rows();
	//print_r($this->db->last_query());exit;
		
		
		
		//$query = $this->db->get('list');
		//print_r($this->db->last_query());exit;
		//$status_details = $query->row()->status;
		//print_r($status_details);exit;
		//print_r($this->db->last_query());exit;
	//$query = $this->db->query('SELECT * FROM `list` LIKE `address` = "'.$location.'" OR `city` = "'.$location.'" OR `state` = "'.$location.'" OR `country` = "'.$location.'"');
	//print_r($this->db->last_query());exit;
	
	if($query->num_rows() !=0)
	{
	foreach($query->result() as $row)
	{
		$data['id']=$row->id;
		$data['user_id']=$row->user_id;
		$data['country']=$row->country;
		$data['check_status']=$row->status;
		$data['address']=$row->address;
		$data['city']=$row->city;
		$data['state']=$row->state;
		$data['room_type']=$row->room_type;
		$data['email']=$this->db->where('id',$data['user_id'])->get('profiles')->row('email');
		
				$data['title']=$row->title;
				$data['price']=$row->price;
				$data['capacity']=$row->capacity;
				//$data['currency']=$row->currency;
				$currency  = $row->currency;
				$country_symbol=$this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
				$country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				if(!empty($country_code)){
					$data['currency_code']   = $country_code;
				}
                else{
                	$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                }
				
				//print_r($data['email']);exit;
				$empty_image1 = base_url().'images/no_image.jpg';
				$nmg = $this->db->where('list_id',$data['id'])->get('list_photo')->row('resize1');
				if(!empty($nmg))
				{
				$data['resize1'] = $nmg;
				}
				else
					{
						$data['resize1'] =$empty_image1;
					}
				
				
				$empty_image = base_url().'images/no_avatar.jpg';
				$src = $this->db->where('email',$data['email'])->get('profile_picture')->row('src');
				if(!empty($src))
				{
				$data['src'] = $src;
				}
				else
					{
						$data['src'] =$empty_image;
					}
				$data['status']  = 'List Found';
				
				$loc[]=$data;		
		//print_r($data['src']);
	}
	echo json_encode($loc, JSON_UNESCAPED_SLASHES);
	}
else{
	echo '[{"status":"No List Found"}]';
}
}


public function discover()
{
	$this->load->model('Trips_model');
    
    $common_currency = $this->input->get('common_currency');
	//$dis = $this->db->query('select * from list');
	//print_r($dis);
	$this->db->query('select * from list');
	if($this->input->get('start'))
	$this->db->limit(5,$this->input->get('start')-1);
	else
	$this->db->limit(5,0);
	//if($dis->num_rows() !=0)
	if($result = $this->db->where(array('is_enable'=>1,'status'=>1))->from('list')->get()->result())
	{
	    $i = 1 ; 
	foreach($result as $row)
	{
		$id = $row->id;
		$user_id = $row->user_id;
		$email=$this->db->where('id',$user_id)->get('profiles')->row('email');
		
		if($i <= 15)
		{
		$data['id']  = $row->id;
		
		$data['user_id'] = $row->user_id;
		$data['title']=$row->title;
		$conditions_starrev = array('list_id' => $row->id);
$result_rev			= $this->Trips_model->get_review($conditions_starrev);
$overall_review_count=$result_rev->num_rows();
$conditions_star    			    = array('list_id' => $row->id);
$data1['stars']			        	= $this->Trips_model->get_review_sum($conditions_star)->row();
if($overall_review_count > 0)
{

$accuracy      = (($data1['stars']->accuracy *2) * 10) / $overall_review_count;
$cleanliness   = (($data1['stars']->cleanliness *2) * 10) / $overall_review_count;
$communication = (($data1['stars']->communication *2) * 10) / $overall_review_count;
$checkin       = (($data1['stars']->checkin *2) * 10) / $overall_review_count;
$location      = (($data1['stars']->location *2) * 10) / $overall_review_count;
$value         = (($data1['stars']->value *2) * 10) / $overall_review_count;
$overall       = ($accuracy + $cleanliness + $communication + $checkin + $location + $value) / 6;
	
}else{
$overall = 0 ;	
}
        $data['overallreview']="$overall";
		$data['price']=$row->price;
		$data['country'] = $row->country;
		$data['city']  = $row->city;
		$data['address']  = $row->address;
		$data['room_type'] = $row->room_type;
		$data['status'] = "success";
		$currency  = $row->currency;
            
        
            
				$data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
				//$country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
				$country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				if(!empty($country_code)){
					$data['currency_code']   = $country_code;
                    
                    $currencycode = $country_code;
				}
                else{
                	$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                    
                    $currencycode = $check_default1;
                }
            
            // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
//             
            // $obj = json_decode($json);
//             
            // foreach($obj->results as $results)
            // {
                // $value = $results->val;
                // //echo $value;
                // $data['common_currency_code'] = $common_currency;
                // $data['common_currency_value'] = $value * $row->price;
            // }
            
            
            
            $data['common_currency_code'] = $common_currency;
            $data['common_currency_value'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $row->price;
            
            
            
		$image =$this->db->where('id',$data['id'])->get('list_photo')->row('image');
		$empty_image = base_url().'images/no_avatar.jpg';
		$empty_image1 = base_url().'images/no_image.jpg';
		if(!empty($image)){
					$data['image']   = $image;
				}
                else{
	                 $data['image']  = $empty_image1;
                }
		/*$src = $this->db->where('email',$email)->get('profile_picture')->row('src');
		//print_r($this->db->last_query());exit;
		if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                $data['src']  = $empty_image;
                }
            $data['profile_pic'] = $this->Gallery->profilepic($row->user_id, 2);*/
            $query2 = $this->db->where('id',$row->user_id)->get('users');
            $fbid = $query2->row()->fb_id;
            $emailid = $query2->row()->email;
            $query3 = $this->db->where('email',$emailid)->get('profile_picture');
            
            if($fbid!=0)
            {
                $data['profile_pic']  = $query3->row()->src;
            }
            else
            {
                //srikrishnan
                $data['profile_pic']= $this->Gallery->profilepic($row->user_id, 2);
                //Ragul    $data['profile_pic']  = $query3->row()->src;
            }
		$resize = $this->db->where('list_id',$data['id'])->get('list_photo')->row('resize');
		if(!empty($resize)){
					$data['resize']   = $resize;
				}
                else{
	                 $data['resize']  = $empty_image1;
                }
		$resize1=$this->db->where('list_id',$data['id'])->get('list_photo')->row('resize1');
		if(!empty($resize1)){
					$data['resize1']   = $resize1;
				}
                else{
	                 $data['resize1']  = $empty_image1;
                }		
		$disc[] = $data;
		}
		else 
		{
		$data['status'] = "over";
	    $disc[] = $data;
		break;
		}
        $i++;
	}

	
	echo json_encode($disc, JSON_UNESCAPED_SLASHES);
	}
else {
	echo '[{"status":"No Data Fund"}]';
}
}

public function default_discover(){
	$query = $this->db->query('select * from discover');
	
	foreach($query->result() as $cover){
		$data['id']  = $cover->id;
		$data['country']  = $cover->country;
		$image   = $cover->image;
		if(!empty($image)){
					$data['image']   = $image;
				}
                else{
	                 $data['image']  = 'null';
                }	
		$val[]  = $data;
	}
	echo json_encode($val);
}

public function discover_place(){
	$id = $this->input->get('id');
	$query  = $this->db->where('city_id',$id)->get('neigh_city_place');
	if($query->num_rows() !=0)
	{
		foreach($query->result() as $row)
		{
			$data['id']   = $row->id;
			$data['city_id']  = $row->city_id;
			$data['city_name']  = $row->city_name;
			$data['place_name'] = $row->place_name;
			$data['quote']   = $row->quote;
			$data['short_desc']  = $row->short_desc;
			$data['long_desc']  = $row->long_desc;
			$data['image_name'] = $row->image_name;
			$data['is_featured'] = $row->is_featured;
			$inner[]  = $data;
		}
		echo json_encode($inner);
	}
}

public function discover_post(){
	$name = $this->input->get('name');
	$query = $this->db->where('place',$name)->get('neigh_post');
	if($query->num_rows() !=0)
	{
		foreach($query->result() as $row)
		{
			$data['id']   = $row->id;
			$data['city']  = $row->city;
			$data['place']  = $row->place;
			$data['image_title'] = $row->image_title;
			$data['image_desc']   = $row->image_desc;
			$data['big_image1']  = $row->big_image1;
			$data['small_image1']  = $row->small_image1;
			$data['small_image2'] = $row->small_image2;
			$data['small_image3'] = $row->small_image3;
			$data['small_image4'] = $row->small_image4;
			$data['small_image5'] = $row->small_image5;
			$data['big_image2']   = $row->big_image2;
			$data['big_image3']   = $row->big_image3;
			$data['is_featured']  = $row->is_featured;
			$post[]  = $data;
		}
		echo json_encode($post);
	}
}

public function wishlist(){
	$roomid =$this->input->get('roomid');
		$user_id=$this->input->get('user_id');
		
		$result="";
		$my=explode(',',$roomid);
		foreach($my as $list)
		{
			if($list != $roomid)
			{
			$result  .= $list.",";
			}
		}
			//Remove Comma from last character
			if((substr($result, -1)) == ',')
			$my_shortlist=substr_replace($result ,"",-1);
			else
			$my_shortlist= $result;

			$data=array('shortlist' => $my_shortlist);
			$this->db->where('id',$user_id);		
			$this->db->update('users',$data);
			
			echo '[{"reason_message":"Updated Successfully"}]';
}

public function history(){
	$add['address']   = $this->input->get('address');
	
	$this->db->insert('history', $add);
	echo '[{"status":"Inserted Successfully"}]';
}

public function display_history(){
	$query  =  $this->db->query('select * from history order by id DESC');
	if($query->num_rows() !=0)
	{
		foreach($query->result() as $row)
		{
			$data['id']  = $row->id;
			$data['address']  = $row->address;
			$history[]  = $data;
		}
		echo json_encode($history);
	}
}
public function listing1(){
	$location = $this->input->get('location');
	
	//$query_data = $this->db->where('address',$location)->get('list');
	
		//$status_details = $query_data->row()->status;
		
		//$this->db->like('address',$location);
		//$this->db->or_like('city',$location);
		//$this->db->or_like('state',$location);
		//$this->db->or_like('country',$location);
		$query = $this->db->query('SELECT * FROM `list` WHERE (`status` != 0 AND `is_enable` != 0 ) AND ( `address` LIKE "%'.$location.'%" OR `city` LIKE "%'.$location.'%" OR `state` LIKE "%'.$location.'%" OR `country` LIKE "%'.$location.'%" )');
		
		//print_r($this->db->last_query());exit;
		
		
		
		//$query = $this->db->get('list');
		//print_r($this->db->last_query());exit;
		//$status_details = $query->row()->status;
		//print_r($status_details);exit;
		//print_r($this->db->last_query());exit;
	//$query = $this->db->query('SELECT * FROM `list` LIKE `address` = "'.$location.'" OR `city` = "'.$location.'" OR `state` = "'.$location.'" OR `country` = "'.$location.'"');
	//print_r($this->db->last_query());exit;
	
	if($query->num_rows() !=0)
	{
	foreach($query->result() as $row)
	{
		$data['id']=$row->id;
		$data['user_id']=$row->user_id;
		$data['country']=$row->country;
		$data['list_status']=$row->status;
		$data['address']=$row->address;
		$data['city']=$row->city;
		$data['state']=$row->state;
		$data['room_type']=$row->room_type;
		$data['email']=$this->db->where('id',$data['user_id'])->get('profiles')->row('email');
		
				$data['title']=$row->title;
				$data['price']=$row->price;
				$data['capacity']=$row->capacity;
				//$data['currency']=$row->currency;
				$currency  = $row->currency;print_r($currency);exit;
				$country_symbol=$this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
				$country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				if(!empty($country_code)){
					$data['currency_code']   = $country_code;
				}
                else{
                	$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                }
				
				
				$nmg=$this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
				
				$empty_image1 = base_url().'images/no_image.jpg';
				$nmg = $this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
				if(!empty($nmg))
				{
				$data['image'] = $nmg;
				}
				else
					{
						$data['image'] =$empty_image1;
					}
				
				$empty_image = base_url().'images/no_avatar.jpg';
				if(!empty($data['email']))
				{
				$data['src'] = $this->db->where('email',$data['email'])->get('profile_picture')->row('src');
				}
				else
					{
						$data['src'] =$empty_image;
					}
				$data['status']  = 'List Found';
				
				$loc[]=$data;		
		//print_r($data['src']);
	}
	echo json_encode($loc, JSON_UNESCAPED_SLASHES);
	}
else{
	echo '[{"status":"No List Found"}]';
}
}


public function search_results()


	{
		$this->load->model('Trips_model');
        $common_currency = $this->input->get('common_currency');
 		$checkin           = '';
		$checkout          = ''; 
		$stack             = array();
		$room_types        = array();
		$property_type_id  = array();
		
		$this->session->set_userdata('search_result', $this->input->get('location'));
		
		$checkin           = $this->input->get('checkin');   
		$checkout          = $this->input->get('checkout');
		$nof_guest         = $this->input->get('guests');
		$room_types        = $this->input->get('room_types');
		
		$min               = $this->input->get('price_min');
		$max               = $this->input->get('price_max');
		
		$keywords          = $this->input->get('keywords');
		
	 	$search_by_map     = $this->input->get('search_by_map');
		$sw_lat            = $this->input->get('sw_lat');
		$sw_lng            = $this->input->get('sw_lng');
		$ne_lat            = $this->input->get('ne_lat');
		$ne_lng            = $this->input->get('ne_lng');
		
		$min_bedrooms      = $this->input->get('min_bedrooms');
		$property_type	   = $this->input->get('property_type');
		$min_bathrooms     = $this->input->get('min_bathrooms');
		$min_beds          = $this->input->get('min_beds');
		$min_bed_type      = $this->input->get('min_bed_type');
		
		$property_type_id  = $this->input->get('property_type_id');
		$hosting_amenities = $this->input->get('hosting_amenities');
		
		$page              = $this->input->get('page');
		$sort              = $this->input->get('sort');
		$lat = $this->session->userdata('lat');
		$lng = $this->session->userdata('lng');
		

		
		$data['page']      = $page;
		
		 if($checkin!='--' && $checkout!='--' && $checkin!="yy-mm-dd" && $checkout!="yy-mm-dd" )
		 {
		 	   
    		$date_from = $checkin;   
    		$date_from = strtotime($date_from); 
      
  
   			$date_to = $checkout;  
    		$date_to = strtotime($date_to); 
      		$arr = array();
   
    		for ($i=$date_from; $i<=$date_to; $i+=86400) {
    	 
			$arr[] = $i;
		
    	}   
			
      	$ans = $this->db->query("SELECT id,list_id FROM `reservation` WHERE `checkin` = '".get_gmt_time(strtotime($checkin))."' OR `checkout` = '".get_gmt_time(strtotime($checkout))."' GROUP BY `list_id`");
		
	/*	if($ans->num_rows()==0)
		{
			$ans = $this->db->where_in('booked_days',$arr)->group_by('list_id')->get('calendar');
		}*/
				
		$a   = $ans->result();
	//	$this->db->flush_cache();
						
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
		
		$condition .= "(`status` != '0')";
	 
		if($search_by_map)
		{
		$condition .= "AND (`lat` BETWEEN $sw_lat AND $ne_lat) AND (`long` BETWEEN $sw_lng AND $ne_lng)";
		}
		else
		{
		if($location != '')
		{
		 $i = 1;
			
		 $condition .=  " AND ((`address` LIKE '%".$pieces[0]."%') OR (`state` LIKE '%".$pieces[0]."%') OR (`city` LIKE '%".$pieces[0]."%') OR (`country` LIKE '%".$pieces[0]."%'))";
		}
		}
		
		if(!empty($min_bedrooms))
		{
				$condition .= " AND (`bedrooms` = '".$min_bedrooms."')";
		}
		if($property_type != 0)
		{
				$condition .= " AND (`property_id` = '".$property_type."')";
		}
		if(!empty($min_bathrooms))
		{
				$condition .= " AND (`bathrooms` = '".$min_bathrooms."')";
		}
		
		if(!empty($min_beds))
		{
		  $condition .= " AND (`beds` = '".$min_beds."')";
		}
		
		if(!empty($min_bed_type))
		{
		  $condition .= " AND (`bed_type` = '".$min_bed_type."')";
		}

		if(!empty($stack))
		{ 
			$condition .= " AND (`id` NOT IN(".implode(',',$stack)."))";
		}
		
		if($nof_guest > 1)
		{
			$condition .= " AND (`capacity` >= '".$nof_guest."')";
		}
		
		
		if(is_array($room_types))
		{
			if(count($room_types) > 0)
			{
			    $i = 1;
				$separated = explode(",", $room_types);
				
				$level = explode(',', $room_types);
				$keys = array_keys($level);
				
        		$last1 = $level[reset($keys)];
        		$last2 = $level[next($keys)];
        		$last3 = $level[end($keys)];
      			
							foreach($separated as $room_type)
							{								
									if($i == count($room_types))
									$and = "";
									else
									$and = " AND ";
									$or=" OR ";
					
									$condition .= " AND (";
									
									$condition .=  "`room_type`  LIKE '%".$last1."%'".$or."`room_type` = '".$last2."'".$or."`room_type` = '".$last3."'";									
									
									$condition .= ")";
									
							}
			
			}
		}	
				if(is_array($hosting_amenities))
				{
				
				
					if(count($hosting_amenities) > 0)
					{
					    $i = 1;
					    $space_separated = explode(" ", $hosting_amenities);
						
					    foreach($space_separated as $amenity)
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
			$condition .= " AND (`status` != '0') AND (`user_id` != '0') AND (`address` != '0') AND (`is_enable` = '1')";
			
		
		$query_status = $this->db->where($condition)->get('list');
		
			  if($query_status->num_rows() != 0)
			{
				foreach($query_status->result() as $row_status)
				{
					$result_status = $this->db->where('id',$row_status->id)->get('lys_status');
					
					if($result_status->num_rows() != 0)
					{
						$result_status = $result_status->row();
											
				        $total = $result_status->calendar+$result_status->price+$result_status->overview+$result_status->photo+$result_status->address+$result_status->listing;
	
						if($total != 6)
						{
							$condition .= " AND (`id` != '".$row_status->id."')";
						}
					}
				}
			}	
	if($min == '' && $max == '')
				{
					
				}
		else {
		$query_price = $this->db->where($condition)->get('list');
			
			
		   if($query_price->num_rows() != 0)
			{
				foreach($query_price->result() as $row_price)
				{
					$room_id = $row_price->id;
					
					$check_price = $row_price->price;
					//echo $max. ' - '.$min;exit;
					if($check_price <= $max || $check_price >= $min)
					{
						if($min == '' && $max == '')
				{
					
				}
				elseif($min == $max)
				{
					$condition .= " AND (`price` >= '".$min."')";
					$condition .= " AND (`price` <= '".$max."')";
				}
            else {
		
				if($min != '')
				{
					if($min > 0)
					{
							$condition .= " AND (`price` >= '".$min."')";
					}
				}
				else
				{
				  if($max != '')
						{
				  $min = 0;
						}
				}
				
				if($max != '')
				{
					
					 if($max > $min)
						{
							$condition .= " AND (`price` <= '".$max."')";
						}
						
				}			
} 
		
					}
		else {
		$condition .= " AND (`id` != '".$row_price->id."')";
		}
}
			}
		
		}
  		//$data1['query']        = $this->db->query("SELECT * FROM (`list`) WHERE $condition $order LIMIT $offset,$per_page");
		/*$data1['query']        = $this->db->query("SELECT * FROM (`list`) WHERE $condition ORDER BY id DESC");
		$this->session->unset_userdata('query');
		$this->session->set_userdata('query',"SELECT * FROM (`list`) WHERE $condition ORDER BY id DESC");
  		$total_rows           =  $this->db->query("SELECT * FROM (`list`) WHERE $condition")->num_rows();*/	
		
	//echo $this->db->last_query();exit;
	$this->db->query("SELECT * FROM (`list`) WHERE $condition ORDER BY id DESC");
	$this->session->unset_userdata('query');
	$this->session->set_userdata('query',"SELECT * FROM (`list`) WHERE $condition ORDER BY id DESC");
	
	if($this->input->get('start'))
	$this->db->limit(5,$this->input->get('start')-1);
	else
	$this->db->limit(5,0);
	
			//$tCount               = $data1['query']->num_rows();
		
			$properties          = '';
			$sno                 = 1; 
          // if($data1['query']->num_rows()!=0)
          if($result = $this->db->from('list')->where($condition)->order_by("id", "desc")->get()->result())
	    	{
	    	$fil =array();
			//$data = array();
	     //foreach($data1['query']->result() as $row)
	     foreach($result as $row)
	     {
	     	 
	     	$data['id']  = $row->id;
			$data['user_id']=$row->user_id;
			$data['address']=$row->address;
			$data['summary']=$row->desc;
			$data['bedrooms']=$row->bedrooms;
			$data['beds']=$row->beds;
			$data['bathrooms']=$row->bathrooms;
			$data['amenities']=$row->amenities;
			
			$conditions_starrev = array('list_id' => $row->id);
$result_rev			= $this->Trips_model->get_review($conditions_starrev);
$overall_review_count=$result_rev->num_rows();
$conditions_star    			    = array('list_id' => $row->id);
$data1['stars']			        	= $this->Trips_model->get_review_sum($conditions_star)->row();
if($overall_review_count > 0)
{

$accuracy      = (($data1['stars']->accuracy *2) * 10) / $overall_review_count;
$cleanliness   = (($data1['stars']->cleanliness *2) * 10) / $overall_review_count;
$communication = (($data1['stars']->communication *2) * 10) / $overall_review_count;
$checkin       = (($data1['stars']->checkin *2) * 10) / $overall_review_count;
$location      = (($data1['stars']->location *2) * 10) / $overall_review_count;
$value         = (($data1['stars']->value *2) * 10) / $overall_review_count;
$overall       = ($accuracy + $cleanliness + $communication + $checkin + $location + $value) / 6;
	
}else{
$overall = 0 ;	
}
        $data['overallreview']="$overall";
		/*$data['country']=$row->country;
		$data['city']=$row->city;
		$data['state']=$row->state;
		$data['room_type']=$row->room_type;*/
		$email_value=$this->db->where('id',$data['user_id'])->get('profiles');
		/*$data['email']=$email_value->row()->email;
				$data['title']=$row->title;
				$data['price']=$row->price;
				$data['capacity']=$row->capacity;
				$data['currency']=$row->currency;*/
				$currency_symbol=$this->db->where('currency_code',$row->currency)->get('currency');
				
				
				$image =$this->db->where('list_id',$data['id'])->get('list_photo');
				
				if(!empty($image->row()->image))
				{
					$data['image']   = $image->row()->image;
					$data['resize']   = $image->row()->resize;
					$data['resize1']   = $image->row()->resize1;
				}
                else{
	                 $data['image']  = "null";
					 $data['resize']   = "null";
					$data['resize1']   = "null";
                }
				
				$profilepic_query = $this->db->where('id',$row->user_id)->get('users');
				if($profilepic_query->num_rows() != 0)
				{
                $fbid = $profilepic_query->row()->fb_id;
				$email = $profilepic_query->row()->email;
                
              	$profilepic_query2 = $this->db->where('email',$email)->get('profile_picture')->row('src');
                
                if($fbid!=0)
                {
                    $data['src']  = $profilepic_query2;
                }
                else
                {
                    //$data['src']= $this->Gallery->profilepic($row->user_id, 2);
					$data['src']  = $profilepic_query2;
                }
                }

				
				$currency  = $row->currency;
				$country_symbol=$this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
				$country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				if(!empty($country_code)){
					$data['currency_code']   = $country_code;
                    
                    $currencycode = $country_code;
				}
                else{
                	$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                    
                     $currencycode = $check_default1;
                }
				if(!empty($row->price)){
					$data['price']   = $row->price;
				}
                else{
	                 $data['price']  = '';
                }
				/*if(!empty($row->capacity)){
					$data['guest']   = $row->capacity;
				}
                else{
	                 $data['guest']  = '';
                }
				if(!empty($row->currency)){
					$data['currency']   = $row->currency;
				}
                else{
	                 $data['currency']  = '';
                }*/
				if(!empty($row->title)){
					$data['title']   = $row->title;
				}
                else{
	                 $data['title']  = '';
                }
				if(!empty($email_value->row()->email)){
					$data['email']   = $email_value->row()->email;
				}
                else{
	                 $data['email']  = '';
                }
				if(!empty($row->room_type)){
					$data['room_type']   = $row->room_type;
				}
                else{
	                 $data['room_type']  = '';
                }
				if(!empty($row->state)){
					$data['state']   = $row->state;
				}
                else{
	                 $data['state']  = '';
                }
				if(!empty($row->city)){
					$data['city']   = $row->city;
				}
                else{
	                 $data['city']  = '';
                }
				if(!empty($row->country)){
					$data['country']   = $row->country;
				}
                else{
	                 $data['country']  = '';
                }
				/*if(!empty($src->row()->src)){
					$data['src']   = $src->row()->src;
				}
                else{
	                 $data['src']  = $empty_image;
                }*/
             
        //     $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
             //echo $json;
          //   $obj = json_decode($json);
             
            // foreach($obj->results as $results)
             //{
               //  $value = $results->val;
                 //echo $value;
                 //$data['common_currency_code'] = $common_currency;
                 //$data['common_currency_value'] = $value * $row->price;
            // }
           // $data['common_currency_code'] = $common_currency;
               
            $data['common_currency_code'] = $common_currency;
            $data['common_currency_value'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $row->price;
            $data['status']  = 'List Found';
				$fil[]=$data;
		
		
	     }
		 if(!empty($fil))
		 {
           echo json_encode($fil);
		 }
		 else 
		 {
			 echo '';
		 }
	 }

		else
		{
	      echo '[{"status":"No List Found"}]';
        }
	 
	 
		
	}

	}
	?>
