<?php
/**
 * DROPinn Search Controller Class
 *
 * Its the powerfull search functionality controller
 *
 * @package		DROPinn
 * @subpackage	Controllers
 * @category	Search
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com

 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	public function Search()
	{
		parent::__construct();
		
		$this->load->helper('url');
		
        $this->load->library('Form_validation');
		$this->load->library('Pagination');
		$this->load->library('email');		
		$this->load->helper('form');
		
		$this->load->model('Users_model');
		$this->load->model('Email_model');
		$this->load->model('Trips_model');
		$this->load->model('Rooms_model');
		
		$this->facebook_lib->enable_debug(TRUE);
	}
	
	
	public function index()
	{
  //Get the checkin and chekout dates
 
        $checkin           = '';
		$checkout          = ''; 
		$stack             = array();
		$room_types        = array();
	
		$checkin           = $this->input->post('checkin');   
		$checkout          = $this->input->post('checkout');
		$nof_guest         = $this->input->post('number_of_guests');
		
		if($this->input->post('room_types1'))
		{
		$room_types1        = $this->input->post('room_types1');
		$data['room_types1'] = $room_types1;
		}
	    if($this->input->post('room_types2'))
		{
		 $room_types2        = $this->input->post('room_types2');
		$data['room_types2'] = $room_types2;
		}
		if($this->input->post('room_types3'))
		{
		$room_types3        = $this->input->post('room_types3');
		$data['room_types3'] = $room_types3;
		}
		
      if($this->input->post('room_types4'))
		{
		$room_types1        = $this->input->post('room_types4');
		$data['room_types1'] = $room_types1;
		}
	    if($this->input->post('room_types5'))
		{
		 $room_types2        = $this->input->post('room_types5');
		$data['room_types2'] = $room_types2;
		}
		if($this->input->post('room_types6'))
		{
		$room_types3        = $this->input->post('room_types6');
		$data['room_types3'] = $room_types3;
		}
		
		if($this->input->post('room_types11'))
		{
		$room_types1        = $this->input->post('room_types11');
		$data['room_types1'] = $room_types1;
		}
	    if($this->input->post('room_types22'))
		{
		 $room_types2        = $this->input->post('room_types22');
		$data['room_types2'] = $room_types2;
		}
		if($this->input->post('room_types33'))
		{
		$room_types3        = $this->input->post('room_types33');
		$data['room_types3'] = $room_types3;
		}
		
		$min               = $this->input->post('min');
		$max               = $this->input->post('max');
		
		//get starred list status		
		$star=$this->input->get('starred'); 
		
		$page              = $this->input->get('page',1);
		$data['page']      = $page;
	
		$array_items = array(
												'Vcheckin'                => '',
												'Vcheckout'               => '',
												'Vcheckout'					          => '',
								    );
   $this->session->unset_userdata($array_items);
				
			if($this->input->post('checkin') != '' || $this->input->post('checkin') != 'mm/dd/yy')
		 {
		 	$freshdata = array(
									'Vcheckin'                => $this->input->post('checkin'),
									'Vcheckout'               => $this->input->post('checkout'),
									'Vnumber_of_guests'					  => $this->input->post('number_of_guests'),
					);
			 $this->session->set_userdata($freshdata);
				}
		
		if($this->input->post('location'))
		{				
		$location                 = $this->input->post('location');
		//$this->session->unset_userdata('location1');
		//$this->session->set_userdata('location',$location);
		}
		else if($this->input->get('location'))
		{
		$location                 = $this->input->get('location');
		}
		else if($this->session->userdata('ajax_search_location') != '')
		{
			//$location = $this->session->userdata('ajax_search_location');
		}

		$ser=$this->input->post('searchbox');
		if($this->input->post('searchbox') != '')
		{
		$location				  = $this->input->post('searchbox');
		//$this->session->unset_userdata('location');
		//$this->session->set_userdata('location1',$location);
	    } 
		
		if(!isset($location))
		{
			$location = '';
		}
		
		if(isset($location))
		{
		$pieces                   = explode(",", $location);
		$data['pieces']           = $pieces;
		$check=$this->input->post('checkin');
		if(isset($check))
		{
		$checkin                  = $this->input->post('checkin');
		//$this->session->set_userdata('checkin',$checkin);
		}
		else
		$checkin                  = 'mm/dd/yy';
		
		$check_out=$this->input->post('checkout');
	
		if(isset($check_out))
		{
		$checkout                 = $this->input->post('checkout');
		//$this->session->set_userdata('checkout',$checkout);
		}
		else
		$checkout                 = 'mm/dd/yy';
		
		
		
		if($this->input->post('number_of_guests'))
		{
		$number_of_guests         = $this->input->post('number_of_guests');
		//$this->session->set_userdata('number_of_guests',$number_of_guests);
		}
		else
		$number_of_guests         = '1';
		
	/*	if(!$this->input->post('location'))
		{
			$location = $this->session->userdata('location');			
		}
		if($location == '')
			{
				$location = $this->session->userdata('location1');
			}
			if($this->input->get('location'))
			{
				$location = $this->input->get('location');
			}*/
		if(!$this->input->post('checkin'))
		{
			//$checkin = $this->session->userdata('checkin');
		}
		if(!$this->input->post('checkout'))
		{
			//$checkout = $this->session->userdata('checkout');
		}
		if(!$this->input->post('number_of_guests'))
		{
			//$number_of_guests = $this->session->userdata('number_of_guests');
		}
				
        $data['property_type']    = $this->Common_model->getTableData('property_type')->result_array();
		$data['query']            = $location;
		$data['checkin']          = $checkin;
		$data['checkout']         = $checkout;
  		$data['number_of_guests'] = $number_of_guests;
		$data['room_types']       = $room_types;
		$data['min']              = $min;
		$data['max']              = $max;
		$data['amnities']         = $this->Rooms_model->get_amnities();
		$data['lat'] = $this->input->post('lat');
		$data['lng'] = $this->input->post('lng');
		
		if($this->input->post('lat') != '')
		{
		$this->session->set_userdata('lat',$data['lat']);
		$this->session->set_userdata('lng',$data['lng']);
		}
 else {
	$data['lat'] = $this->session->userdata('lat');
	$data['lng'] = $this->session->userdata('lng');
}
//echo $this->input->post('lat1');exit;
		}

$data['wishlist_category'] = $this->Common_model->getTableData('wishlists',array('user_id'=>$this->dx_auth->get_user_id()));
$data['user_wishlist'] = $this->Common_model->getTableData('user_wishlist',array('user_id'=>$this->dx_auth->get_user_id()));
	   // $data['query']            = '';
		//$data['property_type']    = $this->Common_model->getTableData('property_type')->result_array();
		 // Advertisement popup 1 start
       
	
	    // Advertisement popup 1 end
		
		
		$data['title']            = get_meta_details('Search_Elements','title');
		$data["meta_keyword"]     = get_meta_details('Search_Elements','meta_keyword');
		$data["meta_description"] = get_meta_details('Search_Elements','meta_description');
		$data['message_element']  = 'view_search_result';
		$this->load->view('template',$data);
		
	}
	
	
	public function ajax_get_results()
	{
	 $this->load->library("Ajax_pagination");
		
	 //get starred list status		
		$star              = $this->input->get('starred'); 
		
  //Get the checkin and chekout dates
 		$checkin           = '';
		$checkout          = ''; 
		$stack             = array();
		$room_types        = array();
		$property_type_id  = array();
		
		$this->session->set_userdata('ajax_search_location', $this->input->get('location'));
		
		$checkin           = $this->input->get('checkin');   
		$checkout          = $this->input->get('checkout');
		$nof_guest         = $this->input->get('guests');
		$room_types        = $this->input->get('room_types');
		$this->session->set_userdata('Vcheckin',$checkin);
		$this->session->set_userdata('Vcheckout',$checkout);
		$this->session->set_userdata('Vnumber_of_guests',$nof_guest);
	
				
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
		$property_type	   = $this->input->get('property_type');
		$min_bathrooms     = $this->input->get('min_bathrooms');
		$min_beds          = $this->input->get('min_beds');
		$min_bed_type      = $this->input->get('min_bed_type');
		 $instance_book		=$this->input->get('instance_book');
		
		$property_type_id  = $this->input->get('property_type_id');
		$hosting_amenities = $this->input->get('hosting_amenities');
		
		$page              = $this->input->get('page');
		$sort              = $this->input->get('sort');
		$lat = $this->session->userdata('lat');
		$lng = $this->session->userdata('lng');
		
				
		/*if(empty($sort))
		{
		 $sort = 1;
		}*/
		
		$data['page']      = $page;
		
		 if($checkin!='--' && $checkout!='--' && $checkin!="yy-mm-dd" && $checkout!="yy-mm-dd" )
		 {
		 	    // Specify the start date. This date can be any English textual format  
    $date_from = $checkin;   
    $date_from = strtotime($date_from); // Convert date to a UNIX timestamp  
      
    // Specify the end date. This date can be any English textual format  
    $date_to = $checkout;  
    $date_to = strtotime($date_to); // Convert date to a UNIX timestamp  
      $arr = array();
    // Loop from the start date to end date and output all dates inbetween  
    for ($i=$date_from; $i<=$date_to; $i+=86400) {
    	 
		$arr[] = $i;
		
    }   
      $ans = $this->db->query("SELECT id,list_id FROM `calendar` WHERE `booked_days` = '".get_gmt_time(strtotime($checkin))."' OR `booked_days` = '".get_gmt_time(strtotime($checkout))."' GROUP BY `list_id`");
		
		if($ans->num_rows()==0)
		{
			$ans = $this->db->where_in('booked_days',$arr)->group_by('list_id')->get('calendar');
		}
				
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
		$FileName = str_replace("'", "", $location);
		$FileName4 = str_replace("-", "", $FileName);
		//$FileName5 = str_replace(" ", ",", $FileName4);
		$pieces = explode(",", $FileName4);
     
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
			
			//$condition .=  " AND (`address` LIKE '%".$pieces[0]."%')";
                      $condition .=  " AND ((`address` LIKE '%".$pieces[0]."%') OR (`state` LIKE '%".$pieces[0]."%') OR (`city` LIKE '%".$pieces[0]."%') OR (`country` LIKE '%".$pieces[0]."%') OR (`desc` LIKE '%".$pieces[0]."%') OR (`room_type` LIKE '%".$pieces[0]."%') OR (`title` LIKE '%".$pieces[0]."%') )";

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
		if(!empty($instance_book[0]))
		{
			
		  $condition .= " AND (`instance_book` = '1')";
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
			$condition .= " AND (`status` != '0') AND (`user_id` != '0') AND (`address` != '0') AND (`is_enable` = '1') AND (`banned` = '0')";
			
		// Get offset and limit for page viewing
		$start                = (int) $page;
		
	 // Number of record showing per page
		$per_page = 20;
		
		if($start > 0)
		   $offset			         = ($start-1) * $per_page;
		else
		   $offset			         =  $start * $per_page;
					
		if($sort == 2)
		{
		  $order              = "ORDER BY price ASC";
		}
		else if($sort == 3)
		{
		  $order              = "ORDER BY price DESC";
		}
		else if($sort == 4)
		{
		  $order              = "ORDER BY id DESC";
		}
		else
		{
		  $order              = "ORDER BY id ASC";
		} 
		
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
					$check_price = get_currency_value1($row_price->id,$row_price->price);
		
				$max = get_currency_value_lys(get_currency_code(),'USD',$max);
								
					if($max == 10000)
					{
						$max = $max*1000000000;
						$max = get_currency_value1($row_price->id,$max);
					}
					else {
						$max = get_currency_value_lys('USD',get_currency_code(),$max);
					}
									
					//echo $max. ' - '.$check_price;exit;
					if($this->input->get('new_search'))
					{
					if($check_price <= $max && $check_price >= $min)
					{
								
					}
else {
	$condition .= " AND (`id` != '".$row_price->id."')";
}
					}
					else
						{
							if($check_price <= $max || $check_price >= $min)
					{
							
					}
else {
	$condition .= " AND (`id` != '".$row_price->id."')";
}
	
						}
				}
			}
		}
			
		//My ShortLists	
		if($search_view == 2)
		{
			$constraint="";
			$shortlists=$this->db->where('id',$this->dx_auth->get_user_id())->get('users')->row()->shortlist;
			$my_lists=explode(',',$shortlists);
			$i=1;
			foreach($my_lists as $list)
			{
				if($i == count($my_lists))
				$OR = "";
				else
				$OR = " OR ";
				
				$data['query']        = $this->db->query("SELECT * FROM (`list`) WHERE $condition $order LIMIT $offset,$per_page");
				
				if($data['query']->num_rows()!=0)
				{
					foreach($data['query']->result() as $row)
					{
						if($row->id == $list)
						{
						 $constraint .=  "`id`= '".$list."'".$OR;
						}
						else
							{
								
							}
					}
				}		
			
				$i++;		
			}
			if($constraint == '')
			{
				$data['query']        = $this->db->query("SELECT * FROM (`list`) where $condition AND id=0 $order LIMIT $offset,$per_page");
  		        $total_rows           = 0;
  			
			}	
			else
				{
					$data['query']        = $this->db->query("SELECT * FROM (`list`) where $condition AND $constraint $order LIMIT $offset,$per_page");
  		        $total_rows           =  $this->db->query("SELECT * FROM (`list`) where $condition AND $constraint")->num_rows();
  			
				}
		
		}
		
		else
		{
  		$data['query']        = $this->db->query("SELECT * FROM (`list`) WHERE $condition $order LIMIT $offset,$per_page");
        $this->session->unset_userdata('query');
		$this->session->set_userdata('query',"SELECT * FROM (`list`) WHERE $condition ORDER BY id DESC");
  		$total_rows           =  $this->db->query("SELECT * FROM (`list`) WHERE $condition")->num_rows();	
		}	
	//echo $this->db->last_query();exit;
		$config['base_url']   = site_url('search').'?checkin='.urlencode($checkin).'&amp;checkout='.urlencode($checkout).'&amp;guests='.$nof_guest.'&amp;location='.urlencode($location).'&amp;min_bathrooms='.$min_bathrooms.'&amp;min_bedrooms='.$min_bedrooms.'&amp;min_beds='.$min_beds.'&amp;min_bed_type='.$min_bed_type.'&amp;per_page='.$per_page.'&amp;search_view=1&amp;sort='.$sort.'&amp;lat='.$lat.'&amp;lng='.$lng;
		
	 	$config['per_page']   = $per_page;
		
		$config['cur_page']   = $start;
		
		$config['total_rows'] = $total_rows;
		 
		$this->ajax_pagination->initialize($config);
		
		$pagination           = $this->ajax_pagination->create_links(false);
			
		$tCount               = $data['query']->num_rows();
			
			$properties          = '';
			$sno                 = 1; 
            foreach($data['query']->result() as $row)
			{ 
			//main photo
			$url                 = getListImage($row->id);
			
			//for map slider full list images
			$images              = $this->Gallery->get_imagesG($row->id);
			$picture_ids         = '';
			foreach($images->result() as $image)
			{
			  $picture_ids .= '"'.$image->list_id.'/'.$image->name.'",';
			}
						
			$profile_pic        = $this->Gallery->profilepic($row->user_id, 2);
			
			if($tCount == $sno) $comma = ''; else $comma = ',';
			
			$neighbor=$row->neighbor; 
			$final_price=get_currency_value1($row->id,$row->price);
			
			if($final_price <= $max && $final_price >= $min)
			{
				
			}
			
/*Offer price calculate*/	
		
if($checkin!='--' && $checkout!='--' && $checkin!="yy-mm-dd" && $checkout!="yy-mm-dd" )
{ 	


$daysdiff = (strtotime($checkout) - strtotime($checkin) ) / (60 * 60 * 24);
			

}

//My shortlist
$short_listed=0;
$cur_user_id=$this->dx_auth->get_user_id();
if($cur_user_id)
{
	
$wishlist_result = $this->Common_model->getTableData('user_wishlist',array('user_id' => $cur_user_id));

if($wishlist_result->num_rows() != 0)
{
	foreach($wishlist_result->result() as $wishlist)
	{
		if($wishlist->list_id == $row->id)
		$short_listed=1;
	}
}
	
}
///// review count	
$conditions_starrev = array('list_id' => $row->id, 'userto' =>$row->user_id);
$result_rev			= $this->Trips_model->get_review($conditions_starrev);
$overall_review_count=$result_rev->num_rows();
////// star rating display
$conditions_star    			    = array('list_id' => $row->id, 'userto' => $row->user_id);
$data['stars']			        	= $this->Trips_model->get_review_sum($conditions_star)->row();
if($overall_review_count > 0)
{

$accuracy      = (($data['stars']->accuracy *2) * 10) / $overall_review_count;
$cleanliness   = (($data['stars']->cleanliness *2) * 10) / $overall_review_count;
$communication = (($data['stars']->communication *2) * 10) / $overall_review_count;
$checkin       = (($data['stars']->checkin *2) * 10) / $overall_review_count;
$location      = (($data['stars']->location *2) * 10) / $overall_review_count;
$value         = (($data['stars']->value *2) * 10) / $overall_review_count;
$overall       = ($accuracy + $cleanliness + $communication + $checkin + $location + $value) / 6;
	
}else{
$overall = 0 ;	
}

/*$slider = '<ul class="rslides" id="slider'.$row->id.'">';
$conditions     = array("list_id" =>$row->id);
$image          = $this->Gallery->get_imagesG(NULL, $conditions); 
$j = 0;
if($image->num_rows() != 0)
{
	foreach($image->result() as $image_list)
	{
		$j++;
		$image = base_url()."images/".$image_list->list_id."/".$image_list->name;
$slider .='<li data="'.$j.'">
	<img width="216" height="144" data="'.$j.'" alt="" src="'.$image.'" style="position: absolute; top: 0px; left: 0px; z-index: 2; opacity: 1;height:130px !important">
	</li>';

	}
}
$slider .= '</ul>';*/

/*end of offer calculate	*/	
// Discount label 1 start 
//echo $instance_book; exit;
$dis_price=$this->Common_model->getTableData( 'price', array('id' => $row->id))->row(); 
$d_price=$dis_price->previous_price; 
		if($dis_price->night < $d_price)
		{
			$discount_amt=((($d_price)-$dis_price->night)/$d_price)*100;
			$discount=round($discount_amt).'%';
		}else { $discount=0; }	
// Discount label 1 end 
		
			$properties .= '{
							"user_thumbnail_url":"'.$profile_pic.'",
							"user_is_superhost":false,
							"lat":'.$row->lat.',
							"has_video":false,
							"room_type":"'.$row->room_type.'",
							
							"recommendation_count":0,
							"lng":'.$row->long.',
							"user_id":'.$row->user_id.',
							"user_name":"'.get_user_by_id($row->user_id)->username.'",
							"symbol":"'.get_currency_symbol($row->id).'",
							"currency_code":"'.get_currency_code().'",
							"review_count":'.$row->review.',
							"address":"'.$row->address.'",
	                        '/* Discount lable 2 start */.'	
							
                            
							  
                           '/* Discount lable 2 end */.'
	                       
	                         "state":"'.$row->state.'",
	                         "city":"'.$row->city.'",
						    "instant_book":"'.$row->instance_book.'",
							"name":"'.$row->title.'",
							"picture_ids":['.substr($picture_ids, 0, -1).'],
							"hosting_thumbnail_url":"'.$url.'",
							"id":'.$row->id.',
							"page_viewed":'.$row->page_viewed.',
							"price":'.$final_price.',
							
							 '/* wislist count 2 start */.'	
							
							 
							  '/*wislist count 2 end */.'	
							
							"short_listed":'.$short_listed.'
							}'.$comma;	







// Discount label 1 end (Replace)			
   $sno++;
			}
	 
		 $startlist    = 1 + $offset;  
	  $endlist      = $offset + $per_page;
	  
	  if($total_rows == 0)
	  {
	  	$startlist = 0;
	  }
			
			if($endlist > $total_rows)
			{
			  $endlist    = $total_rows;
			}
			
			$ajax_result  = '{
																				"results_count_html":"\n<b>'.$startlist.' &ndash; '.$endlist.'</b> of <b>'.$total_rows.' Rentals</b>",
																				"results_count_top_html":"  '.$total_rows.' '.translate('Rentals').' - '.$pieces[0].'\n",
																				"view_type":'.$search_view.',
																				"results_pagination_html":"'.$pagination.'\n",
																				"present_standby_option":false,
																				"lat":"'.$lat.'",
																				"lng":"'.$lng.'",
																				"properties":[';
																				
			$ajax_result .= 	$properties;		
			
			$ajax_result .='],
																			"banner_info":{}
																			}';													
			
			echo $ajax_result;
	}
	
	public function dateconvert($date)
	{
		$ckout = explode('/', $date);
		$diff  = $ckout[2].'-'.$ckout[0].'-'.$ckout[1];
		return $diff;
	}
	
	function ajax_get_symbol()
	{
		extract($this->input->get());
		echo '{"symbol":"'.get_currency_symbol($id).'","min_price":"'.get_currency_value1($id,10).'","max_price":"'.get_currency_value1($id,10000).'"}';
	}
	
	public function get_maps()
	{
  $this->load->view('template',$data);		
	}
	
	public function add_my_shortlist()
	{
	if( (!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()) )
	{
		echo "error";
	}
	else 
	{	
		$list_id=$this->input->post('list_id');
		$user_id=$this->dx_auth->get_user_id();
		$shortlist=$this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->shortlist;
		if($shortlist=="")
		{
			$data=array('shortlist' => $list_id);
			$this->db->where('id',$user_id);		
			$this->db->update('users',$data);
		}
		else
		{
			$my_shortlist=$shortlist.','.$list_id;
			$data=array('shortlist' => $my_shortlist);
			$this->db->where('id',$user_id);		
			$this->db->update('users',$data);
		}

	
	/* $count_list=array();
	  $count_wishlist=0;
	  $lists=$this->db->select('shortlist')->get('users');

	  foreach($lists->result() as $rows_count)
	  {
	  	if($rows_count->shortlist)
		{
	  	$count_list[]=$rows_count->shortlist;
		}
	 	  }
	  foreach($count_list as $list_room)
	  {
	 $view_list=explode(",",$list_room);
	 $count = count($view_list);
	 for($i=0;$i<$count;$i++)
	 {
	 	if($room_id == $view_list[$i])
		{
			$count_wishlist++;
	    }
		else
			{
				$count_wishlist;
			}
	 }
	  } */
	  }
	}
	
	public function footer()
	{
		$this->load->view('templates/blue/includes/footer');
	}
	public function login_check()
	{
	if( (!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()) )
		echo "error";
	else 
		echo "success";
	}
	
		public function sample()
	{
	$neighbor='';
	
		$location  = $this->input->get('location');
        $query = $this->session->userdata('query');

		$query       = $this->db->query($query);
		$results=$query->result_array();
		
		if($query->num_rows() != 0)
		{
			foreach($results as $row)
			{ 
				$zz=$row['neighbor'];
				$neighbor = $row['title']." : ".$zz.'<br>'.$neighbor;
			}			
		}
		else{
		$empty='';
		}	
		echo $neighbor;	


	}
public function lat_lng()
{
	extract($this->input->post());
	$this->session->set_userdata('lat',$lat);
	$this->session->set_userdata('lng',$lng);
}

}
?>
