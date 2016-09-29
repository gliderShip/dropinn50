<?php
/**
 * DROPinn Rooms Controller Class
 *
 * Its have the functionality of rooms display and edit section
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Rooms 
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rooms extends CI_Controller
{

public static $con="false";
public $places_API;  
 public function Rooms()
	{
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->helper('file');
		
		$this->load->library('Form_validation');
		$this->load->library('image_lib');

		$this->load->model('Users_model'); 
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->load->model('Trips_model');
		$this->load->model('Rooms_model');
		$this->facebook_lib->enable_debug(TRUE);
		$this->path = realpath(APPPATH . '../images');
		$this->logo = realpath(APPPATH . '../logo');
		$this->font = realpath(APPPATH . '../core');
		$this->places_API = $this->db->get_where('settings', array('code' => 'SITE_GOOGLE_API_ID'))->row()->string_value;
		}


	public function index($room_id = '',$preview = '')
	{
		 // if($this->dx_auth->get_user_id() != "")
		 // {
		 // 	$conditions             = array("id" => $room_id, "list.status" => 1, "list.is_enable"=>1, "list.list_pay"=>1);
		//  }
		//  else {
			  $conditions             = array("id" => $room_id, "list.status" => 1);
		//  }
	 
	 $result                 = $this->Common_model->getTableData('list', $conditions);
	 
	 $conditions1             = array("id" => $room_id);
	 
	 $lys_status                 = $this->Common_model->getTableData('lys_status', $conditions1)->row();
	 
	 $total_status = $lys_status->calendar+$lys_status->price+$lys_status->overview+$lys_status->address+$lys_status->photo+$lys_status->listing;
	 
	 if($result->row()->user_id != $this->dx_auth->get_user_id())
	 {
	 	if($result->row()->is_enable != 1 || $result->row()->list_pay != 1 || $total_status != 6)
	 	redirect('info');
	 }
	 
	 $data['preview'] = $preview;
	 				////////////////For viewing page statistics -Update///////////
	 	$today_month=date("F");
		$today_date=date("j");
		$today_year=date("Y");
		$conditions_statistics = array("list_id" => $room_id,"date"=>trim($today_date),"month"=>trim($today_month),"year"=>trim($today_year));
		$result_statistics = $this->Common_model->add_page_statistics($room_id,$conditions_statistics);
	 				//////////////////////////	
		if($result->num_rows() == 0)
		{
		  redirect('info/deny');
		}

  		$data['list']           = $list = $result->row();
		$title                  = $list->title;
		$page_viewed            = $list->page_viewed;
			 
		$data['page_viewed'] = $this->Trips_model->update_pageViewed($room_id, $page_viewed);
						
		$id                     = $room_id;
		$checkin                = $this->session->userdata('Vcheckin');
		$checkout               = $this->session->userdata('Vcheckout');
		$data['guests']         = $this->session->userdata('Vnumber_of_guests');
	
		$ckin                   = explode('/', $checkin);
		$ckout                  = explode('/', $checkout);
		
		
		
		$xprice                 = $this->Common_model->getTableData( 'price', array('id' => $id ) )->row();
		
		$guests                 = $xprice->guests;
		
		if(isset($xprice->cleaning))
		$data['cleaning']       = $xprice->cleaning;
		else
		$data['cleaning']       = 0;
		
		if(isset($xprice->security))
		$data['security']       = $xprice->security;
		else
		$data['security']       = 0;
		
		if(isset($xprice->night))
		$price                  = $xprice->night;
		else
		$price                  = 0;
		
		if(isset($xprice->week))
		$Wprice                 = $xprice->week;	
		else
		$Wprice                 = 0;
		
		if(isset($xprice->month))
		$Mprice                 = $xprice->month;	
		else
		$Mprice                 = 0;
		
		$data['guest_price'] = $xprice->addguests;
		
		$data['extra_guest'] = $xprice->guests;
		
		
		// Discount label 2 start 
				
							if($price < $xprice->previous_price)
		{
			$discount=((($xprice->previous_price)-$price)/$xprice->previous_price)*100;
			$data['discount']=$discount;
		}
        else{ $data['discount']=0; }

		
				
		// Discount label 2 end 
				
		
				
		//check admin premium condition and apply so for
		$query                  = $this->Common_model->getTableData( 'paymode', array('id' => 2));
		$row                    = $query->row();	
		 
		if(($ckin[0] == "mm" && $ckout[0] == "mm") or ($ckin[0] == "" && $ckout[0] == "") or ($checkin == 'Check In') or($checkin == 'Check Out') or($checkin == 'Check in') or ($checkin == '') or ($checkout == 'Check out') or ($checkout == '') )
		{
           $data['price']         = $price;
			
			if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix            = $row->fixed_amount; 
										$amt            = $price + $fix;
										$data['commission'] = $fix;
										$Fprice         = $amt;
							}
							else
							{  
										$per            = $row->percentage_amount; 
										$camt           = floatval(($price * $per) / 100);
										$amt            = $price + $camt;
										$data['commission'] = $camt;
										$Fprice         = $amt;
							}
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
		
		   }
			} 
		else
		{
		    $diff                  = strtotime($ckout[2].'-'.$ckout[0].'-'.$ckout[1]) - strtotime($ckin[2].'-'.$ckin[0].'-'.$ckin[1]);
			
			$days                  = ceil($diff/(3600*24));
			
			if($data['guests'] > $guests)
			{
			  $price               = ($price * $days) + ($days * $xprice->addguests);
			}
			else
			{
			  $price               = $price * $days;
			}
            			
			//Entering it into data variables
			$data['price']         = $price;
				
			if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
			
			$data['commission']    = 0;
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix             = $row->fixed_amount; 
										$amt             = $price + $fix;
										$data['commission'] = $fix;
										$Fprice          = $amt;
							}
							else
							{  
										$per             = $row->percentage_amount; 
										$camt            = floatval(($price * $per) / 100);
										$amt             = $price + $camt;
										$data['commission'] = $camt;
										$Fprice          = $amt;
							}
							
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}	
		   }
					}
			$conditions              = array('list_id' => $room_id);
			$data['images']          = $this->Gallery->get_imagesG(NULL, $conditions);
			
			$data['amnities']         = $this->Rooms_model->get_amnities();
			
			$conditions    			        = array('list_id' => $room_id, 'userto' => $list->user_id);
			$data['result']			     	  = $this->Trips_model->get_review($conditions);
			
			$conditions    			     	  = array('list_id' => $room_id, 'userto' => $list->user_id);
			$data['stars']			        	= $this->Trips_model->get_review_sum($conditions)->row();
	
			$data['room_id']          = $room_id;

			$price                    = $this->Common_model->getTableData('price', array('id' => $room_id));
  			$data['prices']           = $price->row();
			 
			$data['lat']              = $list->lat;
			$data['long']			  = $list->long;
			
			$data['policy']  = $this->Common_model->getTableData('cancellation_policy',array('id'=>$list->cancellation_policy))->row()->name;
			
			$data['city']    = $list->city;
			$data['state']    = $list->state;
            $data['country']		= $list->country;
			$data['street_address']		= $list->street_address;
			$data['optional_address']		= $list->optional_address;	
			$data['zip_code']		= $list->zip_code;
			$data['instance_book']		= $list->instance_book;
			
			// wishlist count 1 start
            // wishlist count 1 end
			// Advertisement popup 1 start
           
            // Advertisement popup 1 end
			$data['places_API'] =  $this->places_API ;						 
			$data['title']            = substr($title, 0, 70);
			$data["meta_keyword"]     = "";
			$data["meta_description"] = "";
			$data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
			
			$data['message_element']  = "rooms/view_edit_confirm";
			
			$this->load->view('template',$data);
	}
	
	
	public function newlist()
	{
		header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    
		if( (!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()) )
		{
		$this->session->set_userdata('redirect_to', 'rooms/new');
		redirect('users/signin','refresh');
		}
		$data['places_API'] =  $this->places_API ;
		$data["title"]            = get_meta_details('List_Your_property','title');
		$data["meta_keyword"]     = get_meta_details('List_Your_property','meta_keyword');
		$data["meta_description"] = get_meta_details('List_Your_property','meta_description');
		
		$data["message_element"]  = "list_your_space/view_add_list";
		$this->load->view('template',$data);
	}
	
	public function lys_new()
	{
		extract($this->input->post());
		$this->session->unset_userdata('popup_status');
		$home = trim($home);
		
		$property_id = $this->db->where('type',$home)->get('property_type');
		
		if($property_id->num_rows() != 0)
		{
			$property_id = $property_id->row()->id;
		}
		else {
			echo 'property_type_error';exit;
		}
		
		$data['property_id'] = $property_id;
		$data['room_type'] = $room;
		$data['capacity'] = trim($accom);
		$data['address'] = $addr;
		$data['user_id'] = $this->dx_auth->get_user_id();
		$data['created']     = local_to_gmt();
		$data['currency'] = $this->Common_model->getTableData('currency',array('default'=>"1",'status' =>"1"))->row()->currency_code;
		$data['is_enable'] = 0;
		$data['title'] = $room.' in '.$city;
		$data['price'] = '0';
		$data['lat'] = $lat;
		$data['long'] = $lng;
		$data['city'] = $city;
		$data['state'] = $state;
		$data['country'] = $country;
		
				$this->session->set_userdata('city',$city);
				
		$level = explode(',', $data['address']);
        $keys = array_keys($level);
        $country = $level[end($keys)];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        $data['country'] = trim($country);
		
		$default_policy = $this->Common_model->getTableData('cancellation_policy',array('is_standard'=>"1"))->row()->id;
		
		$data['cancellation_policy'] = $default_policy;
		
		
		$this->db->insert('list',$data);
		
		$insert_id = $this->db->insert_id();
		
		$this->session->set_userdata('room_id',$insert_id);
		
		$price['id'] = $insert_id;
		$price['night'] = 10;
		$price['currency'] = 'USD';
		$this->db->insert('price',$price);
		
		$this->db->insert('lys_status',array('id'=>$insert_id,'user_id'=>$this->dx_auth->get_user_id()));
		
		echo $insert_id;exit;
 	}
	
	public function lys_next()
	{
		
		$room_id = $this->session->set_userdata('room_id',$this->uri->segment(4));
		
		    if($this->session->userdata('room_id') == '' && $this->uri->segment(4) == '')
			{
				redirect('users/signin');
			}
		  if($this->uri->segment(4) != '')
		  {
               $user_check = $this->db->where('id',$this->uri->segment(4))->where('user_id',$this->dx_auth->get_user_id())->get('list');
				
				if($user_check->num_rows() == 0)
				{
					redirect('users/signin');
				}
				$room_id = $this->uri->segment(4);
				
				$this->session->set_userdata('room_id',$room_id);
				
				$new_check = $this->db->where('id',$room_id)->get('lys_status');
				if($new_check->num_rows() == 0)
				{
					redirect('rooms/edit_photo/'.$room_id);					
			    }
			}
			
	   
		 if($this->uri->segment(3) == 'edit' || $this->input->get('month') != '')
	     {
		   	if ($this->input->post("next")) 
			{
	
			    $db_name = $this->config->item('db');
	        	$db_table = "calendar";
	
	          
	            if ($this->input->post("ical_url") && ($this->input->post("ical_url") != '')) 
	            {
	                
				
				     $ical_url = trim($this->input->post("ical_url"));
								     $ical_content = trim($ical_url);
				
			/*     if (@file_get_contents($ical_url) == true) 
				     {
					       $ical_content = trim($ical_url);
				     } 
				     else 
				     {
					       $problems[] = "Ical resource specified by url is not available.";
		             }*/
			      } 
			      else 
			      {
				       $problems[] = "Resource file should be specified.";
				       $data['required_msg'] = 1;
				      //echo '<span style="color:red">Please Give Valid URL.</span>';
			        }
	
	            if(isset($ical_content))
	            {
	                   $check_ical = $this->db->where('url',trim($this->input->post('ical_url')))->where('list_id',$this->uri->segment(4))->get('ical_import');
	
	                   $log = Array();
	                   if($this->input->post('ical_url') != '')
	                   {
	                    if($check_ical->num_rows() == 0)
	                     {
				
	                          $query1= $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '{$db_name}' AND table_name = '{$db_table}' LIMIT 1");

	                           /*! exporting event from source into hash */
	                          require_once(realpath(APPPATH . '../app/views/templates/blue/rooms/codebase/class.php'));
	                          $exporter = new ICalExporter();
	                          $events = $exporter->toHash($ical_content);
	
	                          $success_num = 0;
	                          $error_num = 0;
	                         /*! inserting events in database */
	
	                          $check_tb = $this->db->select('group_id')->where('list_id',$this->uri->segment(4))->order_by('id','desc')->limit(1)->get('calendar');
	
	                          if($check_tb->num_rows() != 0)
	                          {
		                           $i1 = $check_tb->row()->group_id;
	                           }
	                           else 
	                           {
		                          $i1 = 1;
	                           }
	
	                           $value_start = substr(get_remote_data($ical_url),0,5);
	
	                          if($value_start == 'BEGIN')
	                          {
	                             $data = array(
										'id' =>NULL,
										'list_id' =>$this->uri->segment(4),
										'url' 		  => trim($this->input->post('ical_url')),
										'last_sync' => date('d M Y, g:i a',gmt_to_local(time(), get_user_timezone(), false))			
							                   );
				               $this->Common_model->insertData('ical_import', $data);
				               $ical_id = $this->db->insert_id();
				
	                          if(count($events))
	                           {
										for ($i = $i1; $i <= $i1+count($events); $i++) 
										{
												if($i == $i1)
												{
													$event = $events[1];
												}
												else
												{
													$event  = $events[$i-$i1];
												}
			
		
				
	                                   $days = (strtotime($event["end_date"]) - strtotime($event["start_date"])) / (60 * 60 * 24);
	                                  $created=$event["start_date"];
	
	                            for($j=0;$j<=$days;$j++)
	                            {	
									if($days == 1)
									{
										$direct = 'single';
									}
									else if($days > 1)
									{
		
										if($j == 1)
										{
										$direct = 'left';
										}
										else if($days == $j)
										{
										$direct = 'right';
										}
										else
										{
										$direct= 'both';
										}
									}	

							
		$startdate1=$event["start_date"];
		
		$id=$this->uri->segment(4);
		
		$check_dates = $this->db->where('list_id',$this->uri->segment(4))->where('booked_days',strtotime($startdate1))->get('calendar');
		
		if($check_dates->num_rows() != 0)
		{
			
		}
		else { 
			
			$data = array(
							'id' =>NULL,
							'list_id' => $this->uri->segment(4),
							'group_id' => $i,
							'availability' 		  => "Booked",
							'value' 		  => 0,
							'currency' 	=> "EUR",
							'notes' => "Not Available",                      //   $event["text"]
							'style' 	=> $direct,
							'booked_using' 	=> $ical_id,
							'booked_days' 	=>strtotime($startdate1),
							'created' 	=> strtotime($created)
				
							);
							
							$this->Common_model->insertData('calendar', $data);
							
		}
	
						$abc =  $event["start_date"];
						$newdate = strtotime ( '+1 day' , strtotime ( $abc ) ) ;
						$event["start_date"]=date("m/d/Y", $newdate);
	}		
	
			$success_num++;
		
	}//for loop end
	$data['success_num'] = count($events);
	$log = Array("text" => count($events)." Booking were inserted successfully.", "type" => "Success");
	}
else
	{
	$log = Array("text" =>"No data in given URL.", "type" => "Error");
	}
	}
else
	{
	$log = Array("text" =>"Please give valid URL.", "type" => "Error");
	}
	}           
	else {
		$ical_id = $check_ical->row()->id;
		/*! exporting event from source into hash */
	require_once(realpath(APPPATH . '../app/views/templates/blue/rooms/codebase/class.php'));
	$exporter = new ICalExporter();
	$events = $exporter->toHash($ical_content);
	
	$success_num = 0;
	$error_num = 0;
	/*! inserting events in database */
	
	$check_tb = $this->db->select('group_id')->where('list_id',$this->uri->segment(4))->order_by('id','desc')->limit(1)->get('calendar');
	
	if($check_tb->num_rows() != 0)
	{
		$i1 = $check_tb->row()->group_id;
	}
	else {
		$i1 = 1;
	}
	if(count($events) != 0)
	{
	for ($i = $i1; $i <= $i1+count($events); $i++) 
	{
		if($i == $i1)
	$event = $events[1];
		else 
	$event  = $events[$i-$i1];
				
	$days = (strtotime($event["end_date"]) - strtotime($event["start_date"])) / (60 * 60 * 24);
	$created=$event["start_date"];
	
	for($j=0;$j<=$days;$j++)
	{	
							if($days == 1)
							{
								$direct = 'single';
							}
							else if($days > 1)
							{

								if($j == 1)
								{
								$direct = 'left';
								}
								else if($days == $j)
								{
								$direct = 'right';
								}
								else
								{
								$direct= 'both';
								}
							}	

							
		$startdate1=$event["start_date"];
		
		$id=$this->uri->segment(4);
		
		$check_dates = $this->db->where('list_id',$this->uri->segment(4))->where('booked_days',strtotime($startdate1))->get('calendar');
		
		if($check_dates->num_rows() != 0)
		{
			
		}
		else { 
			
			$data = array(
							'id' =>NULL,
							'list_id' => $this->uri->segment(4),
							'group_id' => $i,
							'availability' 		  => "Booked",
							'value' 		  => 0,
							'currency' 	=> "EUR",
							'notes' => "Not Available",                      //   $event["text"]
							'style' 	=> $direct,
							'booked_using' 	=> $ical_id,
							'booked_days' 	=>strtotime($startdate1),
							'created' 	=> strtotime($created)
				
							);
							
							$this->Common_model->insertData('calendar', $data);
							
		}
	
						$abc =  $event["start_date"];
						$newdate = strtotime ( '+1 day' , strtotime ( $abc ) ) ;
						$event["start_date"]=date("m/d/Y", $newdate);
	}		
	
			$success_num++;
		
	}//for loop end
	
	$update_sync['last_sync'] = date('d M Y, g:i a',gmt_to_local(time(), get_user_timezone(), false));
		
	$this->db->where('id',$ical_id)->update('ical_import',$update_sync);
	}	
		$log = Array("text" => "This URL were already imported.", "type" => "Error");
	}	
	$data['log'] = $log;
	}
	}
   else
	{
	$log = Array("text" =>"No data in given URL.", "type" => "Error");
	$data['log'] = $log;
	}

}
		
		$check_calendar = $this->db->where('id',$this->uri->segment(4))->where('user_id',$this->dx_auth->get_user_id())->get('list');
			
			if($check_calendar->num_rows() == 0)
			{
				redirect('info');
			}
	 		$list_id = $this->uri->segment(4);
			$param = $list_id;
			$day     = date("d");
			$month   = $this->input->get('month', TRUE);
			$year    = $this->input->get('year', TRUE);
			
			if (!empty($month) && !empty($year))
			{
			$month   = $month;
			$year    = $year;
			}
			else
			{
			$month   = date("m");
			$year    = date("Y");
			}
			
			if($month > 12 || $month < 1)
			{
			  $month = date("m");
			}
			else
			{
			  $month = $month;
			}
			
			if(($year == ($year - 3)) || ($year == ($year + 3)))
			{
			  redirect('calendar/single/'.$list_id.'?month='.$month.'&year='.date("Y"));
			}

		 $row                 = $this->Common_model->getTableData('list', array( 'id' => $param))->row();
         $data['list_title']  = $row->title;
			$data['list_price']  = $row->price;
			
			$conditions          = array('list_id' => $list_id);
			$data['result_cal']      = $this->Trips_model->get_calendar($conditions)->result();
			
			$data['list_id']     = $list_id;
			$data['day']         = $day;
			$data['month']       = $month;
			$data['year']        = $year;
			//Remove incorrect list from seasonal price
			$query=$this->Common_model->getTableData('seasonalprice', array( 'list_id' => $list_id));
			$res=$query->result_array();
			foreach($res as $seasonal)
			{
				$starttime   	= $seasonal['start_date'];	
				$gmtTime   		= $seasonal['end_date'];
				if($gmtTime<$starttime)		
				{	
					$list_id    = $seasonal['list_id'];
					$remove_query="delete from seasonalprice where list_id='".$list_id."' and start_date='".$seasonal['start_date']."' and end_date='".$seasonal['end_date']."'";
					$remove_exe= $this->db->query($remove_query);
				}
			}	
		}
		
		
//edit function if condition close
   
	//data fetch to display	
		$result =  $this->db->where('id',$room_id)->get('list')->row();
	//	print_r($result);
		$lys_status = $this->db->where('id',$room_id)->get('lys_status')->row();
	    
		if($result->lat != '' && $result->long != '')
		{
			$data['MapURL'] = $this->circle_map($result->lat,$result->long);
		}
			$data['city']   = $result->city;
	
			$data['state']   = $result->state;
		$data['room_type']        = $result->title;
		$data['room_id']          = $result->id;
		$data['calendar_type']    = $result->calendar_type;
		$data['price']            = $result->price;
		$data['house_rule']       = $result->house_rule;
		$data['currency']         = $result->currency;
		$data['lys_status']       = $lys_status;
		
		$data['beds']			  =$result->beds;
		$data['bed_type']			  =$result->bed_type;
	   $data['instance_book']			  =$result->instance_book;
		
		$data['address']   = $result->address;
		$data['street_address']   = $result->street_address;
		$data['optional_address']   = $result->optional_address;
		if($result->country == 'UK')
		$data['country_name'] = 'United Kingdom';
		else
		$data['country_name']   = $result->country;
		$data['lat']   = $result->lat;
		$data['lng']   = $result->long;
		
		$check_policy = $this->Common_model->getTableData('cancellation_policy',array('id'=>$result->cancellation_policy));
		
		if($check_policy->num_rows() != 0)
		$data['cancellation_policy'] = $check_policy->row()->id;
		else
		$data['cancellation_policy'] = 1;	
		
		$data['cancellation_policy_data'] = $this->Common_model->getTableData('cancellation_policy',array('is_standard'=>"1"));
		
		$data['is_enable'] = $result->is_enable;
		$data['list_pay'] = $result->list_pay;
		
		//echo $data['country_name'];exit;
        $data['lys_status_count'] = $lys_status->calendar+$lys_status->price+$lys_status->overview+$lys_status->address+$lys_status->listing+$lys_status->photo;
		
        $data['total_status'] = $lys_status->overview+$lys_status->calendar+$lys_status->price+$lys_status->photo+$lys_status->listing+$lys_status->address;
		$data['zipcode']   = $result->zip_code;
		$data['room_type_org']    = $result->room_type.' in '.$this->session->userdata('city');
		$data['room_type_only']   = $result->room_type;
		$data['desc']             = $result->desc;
		$data['amenities']        = $this->db->get('amnities');
		$data['home_type']        = $this->db->where('id',$result->property_id)->get('property_type')->row()->type;
		$data['accommodates']     = $result->capacity;
		$data['bedrooms']         = $result->bedrooms;
		$data['bathrooms']        = $result->bathrooms;
		$data['result_amenites']           = $this->db->where('id',$room_id)->get('list')->row()->amenities;
		$data['country']          = $this->db->get('country');		
		
		$price_table = $this->db->where('id',$room_id)->get('price')->row();
		$currency_check = $this->db->where('currency_code',$result->currency)->where('status',1)->get('currency');
		$data['currency_symbol'] = $this->db->where('currency_code',$result->currency)->get('currency')->row()->currency_symbol;
		
		if($currency_check->num_rows() == 0)
		{
			$data['currency'] = get_currency_code();
			$data['currency_symbol'] = $this->db->where('currency_code',get_currency_code())->get('currency')->row()->currency_symbol;
			$data['price'] = get_currency_value1($result->id,$result->price);
			$data['week_price'] = get_currency_value1($result->id,$price_table->week);
			$data['month_price'] = get_currency_value1($result->id,$price_table->month);
        	$data['cleaning_fee'] = get_currency_value1($result->id,$price_table->cleaning);
			$data['security'] = get_currency_value1($result->id,$price_table->security);
			$data['guest_count'] = get_currency_value1($result->id,$price_table->guests);
			$data['extra_guest_price'] = get_currency_value1($result->id,$price_table->addguests);
		}
	else
	{	$data['week_price'] = $price_table->week;
		$data['month_price'] = $price_table->month;
        $data['cleaning_fee'] = $price_table->cleaning;
		$data['security'] = $price_table->security;
		$data['guest_count'] = $price_table->guests;
		$data['extra_guest_price'] = $price_table->addguests;
	}
	
		$data['currency_result'] = $this->db->where('status',1)->get('currency');
	   		
		$data['list_photo'] = $this->db->where('list_id',$room_id)->order_by('id','asc')->get('list_photo');
		$data['places_API'] =  $this->places_API ;
		
		$data["title"]            = get_meta_details('List_Your_property','title');
		$data["meta_keyword"]     = get_meta_details('List_Your_property','meta_keyword');
		$data["meta_description"] = get_meta_details('List_Your_property','meta_description');
		
		$data["message_element"]  = "list_your_space/view_list_your_space_next";
		$this->session->set_userdata("list_id",$room_id);
		$this->load->view('template',$data);
	}
	public function add_photo_user_login(){
		
 if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in() ))
					{
					echo "add photo user is not login";
					return true;
				}
	}
	
	function cleaning_price()
	{
		extract($this->input->post());
		$data['cleaning'] = $cleaning_price;
		$this->db->where('id',$room_id)->update('price',$data);
		echo 'Success';exit;
	}
		
	function get_cleaning_price()
	{
		extract($this->input->post());
		$result = $this->db->where('id',$room_id)->get('price');
		echo $result->row()->cleaning;exit;
	}
	
	function get_guest_count()
	{
		extract($this->input->post());
		$result = $this->db->where('id',$room_id)->get('price');
		echo $result->row()->guests;exit;
	}
	
	function get_extra_guest_price()
	{
		extract($this->input->post());
		$result = $this->db->where('id',$room_id)->get('price');
		echo $result->row()->addguests;exit;
	}
	
	function get_security_price()
	{
		extract($this->input->post());
		$result = $this->db->where('id',$room_id)->get('price');
		echo $result->row()->security;exit;
	}
	function get_instance_book()
	{
		extract($this->input->post());
		$result = $this->db->where('id',$room_id)->get('instance_book');
		echo $result->row()->cleaning;exit;
	}	
	
	public function circle_map($lat,$lng)
	{
		/* set some options */
$MapLat    = $lat; // latitude for map and circle center
$MapLng    = $lng; // longitude as above
$Rad = 100;         // the radius of our circle (in Kilometres)
$MapFill   = 'FF00A2';    // fill colour of our circle
$MapBorder = '91A93A';    // border colour of our circle
$MapWidth  = 210;         // map image width (max 640px)
$MapHeight = 210;         // map image height (max 640px)
 
 $Lat = $MapLat;
 $Lng = $MapLng;
$Detail=8;

  $R    = 1600000;
 
  $pi   = pi();
 
  $Lat  = ($Lat * $pi) / 180;
  $Lng  = ($Lng * $pi) / 180;
  $d    = $Rad / $R;
 
  $points = array();
  $i = 0;
 
  for($i = 0; $i <= 360; $i+=$Detail):
    $brng = $i * $pi / 180;
 
    $pLat = asin(sin($Lat)*cos($d) + cos($Lat)*sin($d)*cos($brng));
    $pLng = (($Lng + atan2(sin($brng)*sin($d)*cos($Lat), cos($d)-sin($Lat)*sin($pLat))) * 180) / $pi;
    $pLat = ($pLat * 180) /$pi;
 
    $points[] = array($pLat,$pLng);
  endfor;
 
  require_once('PolylineEncoder.php');
  $PolyEnc   = new PolylineEncoder($points);
  $EncString = $PolyEnc->dpEncode();
 
  $EncString = $EncString['Points'];
/* put together the static map URL */
$MapAPI = 'https://maps.google.com.au/maps/api/staticmap?';
$MapURL = $MapAPI.'center='.$MapLat.','.$MapLng.'&zoom=15&size='.$MapWidth.'x'.$MapHeight.'&maptype=roadmap&path=fillcolor:0x'.$MapFill.'33%7Ccolor:0x'.$MapBorder.'00%7Cenc:'.$EncString.'&sensor=false';
 return $MapURL;
/* output an image tag with our map as the source */
	}
	
	function ajax_circle_map()
	{
		extract($this->input->post());
		$mapurl = $this->circle_map($lat,$lng);
		echo $mapurl;exit;
	}
	
	public function calendar_type()
	{
		extract($this->input->post());
		$data['calendar_type'] = $type;
		$this->db->where('id',$room_id)->update('list',$data);
		$data1['calendar'] = 1;
		$this->db->where('id',$room_id)->update('lys_status',$data1);
		echo $room_id;exit;
	}
	
	public function get_calendar()
	{
		extract($this->input->post());
		echo $this->db->where('id',$room_id)->get('list')->row()->calendar_type;exit;
	}
	
	public function add_currency()
	{
	    extract($this->input->post());
		
		$data['currency'] = $currency;
		//$fr['from']          =$from;
	   
		$t['to']           =$to;
		$data3['price']              =$price;
	  
	  //  $from = $this->db->where('country_symbol',$from)->get('currency')->row()->currency_code;
	
		if($price == 'NaN')
		{
		
		  extract($this->input->post());
		  
		//	echo "no";
			$price=10;
		// $amount1= round(get_currency_value_lys($from,$to,$price));
		 $currency = $this->db->where('currency_code',$currency)->get('currency')->row()->currency_symbol;
		 echo $currency;
		 
		 
		 
		}
		else
	  {
	 
	   
		extract($this->input->post());
		
		$data['currency'] = $currency;
		//$fr['from']          =$from;
	   // $price1['price']              =$price; 
		$t['to']           =$to;
	//	$data3['price']              =$price;
	    //$from = $this->db->where('country_symbol',$from)->get('currency')->row()->currency_code;
		$from1=$this->db->where('id',$room_id)->get('list')->row()->currency;
		//$this->session->set_userdata('currency_value',$from1);
		
		
		$price=$this->db->where('id',$room_id)->get('list')->row()->price;
		//print_r($from1);
		//print_r($price);
		$amount1= round(get_currency_value_lys($from1,$to,$price));
		
	    //	print_r($amount1); 
	 	   $dataprice['price'] = $amount1;
		    $datecurrency['currency'] = $to;
			
		
		$this->db->where('id',$room_id)->update('list',$dataprice);
		
		$this->db->where('id',$room_id)->update('list',$datecurrency);
				
		$dataprice1['night'] = $amount1;
		
		$this->db->where('id',$room_id)->update('price',$datecurrency);
		
		$this->db->where('id',$room_id)->update('price',$dataprice1);
		
	   //$this->db->where('id',$room_id)->update('price',$amount);
		
		$priceamount=$this->db->where('id',$room_id)->get('list')->row()->price;
		
		$currency = $this->db->where('currency_code',$currency)->get('currency')->row()->currency_symbol;
		
		$amount2       =$this->db->where('id',$room_id)->get('list')->row()->price;
		
	    $e=array(
                  'currency'=>$currency,
                  'amount'=>$amount1 		
		
		       ); 
	  echo json_encode($e);exit;
		

       }
	 
	  
    }
    
	
	//change week function
	function week_add_currency()
	{
		extract($this->input->post());
		
		//$data['currency'] = $currency;
		
	
		 $weekprice['week'] = $price;
		 
		 
		 $t['to'] = $to;
		 
		 	//echo $data['currency']; 
			//echo $weekprice['week']; 
		    //echo $t['to'];
		   
		 
		// $from1=$this->db->where('id',$room_id)->get('list')->row()->currency;
		 
		 
		 $weekprice=$this->db->where('id',$room_id)->get('price')->row()->week;
		 
		
		 $amount1= round(get_currency_value_lys_week($currency,$to,$weekprice));
       
	 
		  $dataprice1['week'] = $amount1;
		 
		 $this->db->where('id',$room_id)->update('price',$dataprice1);
		
		
		$currency = $this->db->where('currency_code',$currency)->get('currency')->row()->currency_symbol;
		
		$amount2       =$this->db->where('id',$room_id)->get('price')->row()->week;
		
		echo $amount2;exit;
		
		 
		
		
	}




   //change week function
	
	
	   //change month function
	   
	  function month_add_currency()
	{
		extract($this->input->post());
		
		//$data['currency'] = $currency;
		
	
		 $monthprice['month'] = $price;
		 
		 
		 $t['to'] = $to;
		 
		 	//echo $data['currency']; 
			//echo $monthprice['month'];
		    //echo $t['to'];
		   
		 
		// $from1=$this->db->where('id',$room_id)->get('list')->row()->currency;
		 
		 
		 $monthprice=$this->db->where('id',$room_id)->get('price')->row()->month;
		 
		
		 $amount1= round(get_currency_value_lys_month($currency,$to,$monthprice));
       
	 
		  $dataprice1['month'] = $amount1;
		 
		 $this->db->where('id',$room_id)->update('price',$dataprice1);
		
		
		$currency = $this->db->where('currency_code',$currency)->get('currency')->row()->currency_symbol;
		
		$amount2       =$this->db->where('id',$room_id)->get('price')->row()->month;
		
		echo $amount2;exit;
		
		 
		
		
	}

	   
	   
	   
	   
	   //change month function
	
	
	
	
	
	
	public function add_price()
	{
		extract($this->input->post());
		
		if(isset($price))
		{
		$data['price'] = $price;
		$data1['night'] = $price;
		// Discount label 1 start 

		$fields2 = 'addguests,week,night,month,guests,cleaning';
		$previous_price           = $this->Common_model->getTableData('price', array('id' => $room_id),$fields2)->row()->night;	
		$data1['previous_price'] = $previous_price;
	
// Discount label 1 end 
		
		$this->db->where('id',$room_id)->update('list',$data);
		$this->db->where('id',$room_id)->update('price',$data1);
		$data2['price'] = 1;
		$this->db->where('id',$room_id)->update('lys_status',$data2);	
		}
		if(isset($week_price))
		{
		$data3['week'] = $week_price;
		$this->db->where('id',$room_id)->update('price',$data3);	
		}
		if(isset($month_price))
		{
		$data4['month'] = $month_price;
		$this->db->where('id',$room_id)->update('price',$data4);	
		}	
		echo 'Success';exit;
	}
	
	public function first_popup()
	{
		extract($this->input->post());
		echo $this->session->set_userdata('popup_status',1); 
	}
	
	public function get_lys_status()
	{
		extract($this->input->post());
		$result = $this->db->where('id',$room_id)->get('lys_status')->row();
		echo $result->calendar+$result->overview+$result->price+$result->photo+$result->address+$result->listing;exit; 
	}
	
        public function list_pay()
{
        $query               = $this->Common_model->getTableData( 'paymode', array( 'id' => 1) );
		$row                 = $query->row();
		extract($this->input->post());
		$query1               = $this->Common_model->getTableData( 'list', array( 'id' => $room_id) );
		$row1                 = $query1->row();
		if($row1->list_pay == 0 && $row->is_premium == 1)
		{
			echo $row->is_premium;exit;
		}
		else
			{
				echo 0;exit;
			}
		
}
function list_pay_status()
{
extract($this->input->post());
$result = $this->db->where('id',$room_id)->get('list')->row()->payment;
echo $result;exit;
}
function listpay($room_id)
{
$data['price'] = $this->db->select('price')->where('id',$room_id)->get('list')->row()->price;
$data2['id']=$room_id;
$query               = $this->Common_model->getTableData( 'paymode', array( 'id' => 1) );
		$row                 = $query->row();
if($row->is_premium == 1)
		{
					if($row->is_fixed == 1)
					{
								$amount           = $row->fixed_amount;
					}
					else
					{  
								$per              = $row->percentage_amount; 
								$amount           = floatval(($data['price'] * $per) / 100);
					}
					
		$condition           = array('id' => $data2['id']);
		$data4['status']     = 1;
		$this->Common_model->updateTableData('list', NULL, $condition, $data4);
		
	 	$this->session->set_userdata('amount', $amount);
		$this->session->set_userdata('Lid', $data2['id']);
		redirect('listpay?room_id='.$room_id);
		}
}
	function add_title()
    {
    	extract($this->input->post());
		$data['title'] = $title;
		$data1['title'] = $title_index;
	    $this->db->where('id',$room_id)->update('list',$data);
		$this->db->where('id',$room_id)->update('lys_status',$data1);
		
		$summary_status['summary'] = $this->db->where('id',$room_id)->get('lys_status')->row()->summary;
		if($summary_status['summary'] == 1)
		{
			$overview['overview'] = 1;
			$this->db->where('id',$room_id)->update('lys_status',$overview);
		}
		echo 'Success';exit;
    }	
	function add_title_zero()
	{
		extract($this->input->post());
		$data['title'] = $title;
		$data1['title'] = $title_index;
		$data1['overview'] = 0;
		$list_status['is_enable'] = 0;
		$list_status['list_pay'] = 0;
	    $this->db->where('id',$room_id)->update('list',$list_status);
	    $this->db->where('id',$room_id)->update('list',$data);
		$this->db->where('id',$room_id)->update('lys_status',$data1);

		echo 'Success';exit;
	}
	function add_desc()
	{
		extract($this->input->post());
		$data['desc'] = $desc;
		$data1['summary'] = $summary_index;
	    $this->db->where('id',$room_id)->update('list',$data);
		$this->db->where('id',$room_id)->update('lys_status',$data1);
		
		if($summary_index == 0)
		{
		$list_status['is_enable'] = 0;
		$list_status['list_pay'] = 0;
	    $this->db->where('id',$room_id)->update('list',$list_status);
		$overview['overview'] = 0;
			$this->db->where('id',$room_id)->update('lys_status',$overview);
		}
		$title_status['title'] = $this->db->where('id',$room_id)->get('lys_status')->row()->title;
		
		if($title_status['title'] == 1 && $summary_index!=0)
		{
			$overview['overview'] = 1;
			$this->db->where('id',$room_id)->update('lys_status',$overview);
		}
		echo $desc;exit;
	}
	
	function add_amenities()
	{
		extract($this->input->post());
		$amenities = $this->db->where('id',$room_id)->get('list')->row()->amenities;
		$amenities_ex = explode(',', $amenities);
		$i = 0;
		foreach($amenities_ex as $row)
		{
			//echo $amenities_ex[$i];
			if($amenities_ex[$i] == $amenity)
			{
				//echo $amenity;
			}
			else {
				$data['amenities'] = $amenity.','.$amenities;
		$data1['amenities'] = 1;
		$this->db->where('id',$room_id)->update('list',$data);
		$this->db->where('id',$room_id)->update('lys_status',$data1);
		echo 'Success';exit;
			}
			$i++;
		}		
	}
	
	function delete_amenities()
	{
		extract($this->input->post());
		$amenities = implode(',', $amenity);
		
		$data['amenities'] = $amenities;
        $this->db->where('id',$room_id)->update('list',$data);
echo $data['amenities'];exit;
	}
	
	function add_beds()
	{
		extract($this->input->post());
		$data_beds['beds'] = $beds;
		$data['bedscount'] = 1;
		$this->db->where('id',$room_id)->update('list',$data_beds);
		$this->db->where('id',$room_id)->update('lys_status',$data);
		
		$beds_status = $this->db->where('id',$room_id)->get('lys_status')->row()->bathrooms;
		if($beds_status == 1)
		{
			$listing['listing'] = 1;
			$this->db->where('id',$room_id)->update('lys_status',$listing);
		}
		
		echo 'Success';exit;
	}
	
	function get_beds()
	{
		extract($this->input->post());
		echo $this->db->where('id',$room_id)->get('list')->row()->beds;exit;
	}
	
	function add_bedrooms()
	{
		extract($this->input->post());
		$data_beds['bedrooms'] = $bedrooms;
		$data['beds'] = 1;
		$this->db->where('id',$room_id)->update('list',$data_beds);
		$this->db->where('id',$room_id)->update('lys_status',$data);
		
		$beds_status = $this->db->where('id',$room_id)->get('lys_status')->row()->bathrooms;
		if($beds_status == 1)
		{
			$listing['listing'] = 1;
			$this->db->where('id',$room_id)->update('lys_status',$listing);
		}
		
		echo 'Success';exit;
	}
	
	function get_bedrooms()
	{
		extract($this->input->post());
		echo $this->db->where('id',$room_id)->get('list')->row()->bedrooms;exit;
	}
	function get_bath()
	{
		extract($this->input->post());
		echo $this->db->where('id',$room_id)->get('list')->row()->bathrooms;exit;
	}
	function get_title()
	{
		extract($this->input->post());
		echo $this->db->where('id',$room_id)->get('list')->row()->title;exit;
	}
	function get_price()
	{
		extract($this->input->post());
		
		echo $this->db->where('id',$room_id)->get('list')->row()->price;exit;
	}
	function get_week_price()
	{
		extract($this->input->post());
		
			$listing['week'] = 0;
			$this->db->where('id',$room_id)->update('price',$listing);
			
		echo $this->db->where('id',$room_id)->get('price')->row()->week;exit;
	}
	function get_month_price()
	{
		extract($this->input->post());
			$listing['month'] = 0;
			$this->db->where('id',$room_id)->update('price',$listing);
			
		echo $this->db->where('id',$room_id)->get('price')->row()->month;exit;
	}
	/*function replace_price()
	{
		extract($this->input->post());
		$this->db->where('id',$room_id)->update('list',array('price'=>100));
		$this->db->where('id',$room_id)->update('price',array('night'=>100));
		exit;
	} */
	function get_summary()
	{
		extract($this->input->post());
		echo $this->db->where('id',$room_id)->get('list')->row()->desc;exit;
	}
	function add_bathrooms()
	{
		extract($this->input->post());
		$data['bathrooms'] = $bathrooms;
		$data1['bathrooms'] = 1;
		$this->db->where('id',$room_id)->update('list',$data);
		$this->db->where('id',$room_id)->update('lys_status',$data1);
		
		$bathrooms_status = $this->db->where('id',$room_id)->get('lys_status')->row()->beds;
		if($bathrooms_status == 1)
		{
			$listing['listing'] = 1;
			$this->db->where('id',$room_id)->update('lys_status',$listing);
		}
		echo 'Success';exit;
	}
	
	function add_hometype()
	{
		extract($this->input->post());
		$data['property_id'] = $this->db->where('type',$hometype)->get('property_type')->row()->id;
		$this->db->where('id',$room_id)->update('list',$data);
		echo 'Success';exit;
	}
	//
		function instance_book()
	{
		extract($this->input->post());
				$data['instance_book'] = $instance_book;
		
		$this->db->where('id',$room_id)->update('list',$data);
		echo 'Success';exit;
	}
	//
	
	function add_roomtype()
	{
		extract($this->input->post());
		$data['room_type'] = $roomtype;
		$this->db->where('id',$room_id)->update('list',$data);
		echo 'Success';exit;
	}
	
	function add_accommodates()
	{
		extract($this->input->post());
		$data['capacity'] = $accommodates;
		$this->db->where('id',$room_id)->update('list',$data);
		echo 'Success';exit;
	}
	
	function final_step()
	{
	   
		extract($this->input->post());
		
		echo 'Success';exit;
	}
	
	function min_price()
	{
		extract($this->input->post());
		$data2['price'] = 0;
		$this->db->where('id',$room_id)->update('lys_status',$data2);
		$list_status['is_enable'] = 0;
		$list_status['list_pay'] = 0;
	    $this->db->where('id',$room_id)->update('list',$list_status);	
	}
    
    
  /*  With Out CDN
	function add_photo1()
	{
		$room_id = $this->session->userdata('room_id');
		$room_id = $this->uri->segment(3);
		$filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id;
		
		if($this->dx_auth->get_user_id() == '')
		{
			echo 'users/signin';exit;
		}
		
		    if(!file_exists($filename)) 
		{
     	mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id, 0777, true);
		}
	
		$file_element_name = "upload_file1";	
		 
  if(isset($_FILES["upload_file1"]["name"]))
					{
						
 foreach ($_FILES["upload_file1"]["error"] as $key => $error) {
 	
				$tmp_name = $_FILES["upload_file1"]["tmp_name"][$key];
				$name = str_replace(' ','_',$_FILES["upload_file1"]["name"][$key]);
				
				 $ext = pathinfo($name, PATHINFO_EXTENSION);
				 
			$image_name    = random_string('alnum',8).'.'.$ext;
					if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')	
					{						 
					if( move_uploaded_file($tmp_name, "images/{$room_id}/{$image_name}")){
            	
				if($ext == 'png')
					{
						error_reporting(0);
						if(imagecreatefrompng("images/{$room_id}/{$image_name}"))
						{
						$image = imagecreatefrompng("images/{$room_id}/{$image_name}");
    					imagejpeg($image, "images/{$room_id}/{$image_name}", 100);
    					imagedestroy($image);
						}
					}
					
    /*  $this->load->library('upload', $config);
	  
      if (!$this->upload->do_upload($key))
      {
      	echo $this->upload->display_errors();
		 echo 'no';exit;
      }
      else
      {
      	 	    $upload_data = $this->upload->data(); 			
      	 	    	
			      		//$image_name    = $listid.random_string(alnum,8).'.'.$ext;
						$insertData['list_id']    = $this->uri->segment(3);
      	                $insertData['name']       = $image_name;
						$insertData['created']    = local_to_gmt();
						$check = $this->db->where('list_id',$room_id)->get('list_photo');
						
					$photo_status['photo'] = 1;
			            $this->db->where('id',$room_id)->update('lys_status',$photo_status);
												
						if($check->num_rows() == 0)
						{
							$insertData['is_featured'] = 1;
				     	}
                        else 
                        {
	                       $insertData['is_featured'] = 0;
                        }
						if($image_name != '')
						$this->Common_model->insertData('list_photo', $insertData);
						$this->watermark($room_id,$image_name);
      	   //echo 'yes';exit;
      	   
  $this->watermark($room_id,$image_name);
  $this->watermark1($room_id,$image_name);
	  }
	  }
	  }
$list_photo = $this->db->where('list_id',$room_id)->order_by('id','asc')->get('list_photo');
		   
		   echo ' <ul class="photo_img" id="photo_ul">';
   	if($list_photo->num_rows() != 0)
	{
   	foreach($list_photo->result() as $row)
     {    
      echo '<li class="photo_img_sub">
      <div id="pannel_photo_item_id" class="pannel_photo_item">
      <div class="first-photo-ribbon"></div>
      <div class="photo-drag-target"></div><a class="media-link"><img width="100%" src="'.base_url()."images/".$room_id."/".$row->name.'"></a>
      <button data-photo-id="29701026" class="delete-photo-btn js-delete-photo-btn" onclick="jQuery(this).delete_photo('.$row->id.')">
        <i ><img src="'.base_url().'css/templates/blue/images/delete-32.png"></i>
      </button>
      <div class="panel-body panel-condensed">
      <textarea rows="3" id="highlight_'.$row->id.'" placeholder="'.translate("What are the highlights of this photo?").'" class="input-large" onkeyup="$(this).highlight('.$row->id.')">'.trim($row->highlights).'</textarea>
      </div></li>';
     }
	}
   echo '</div>
   </ul>';
	  }
	}
	function add_photo()
	{
		$room_id = $this->session->userdata('room_id');
		$room_id = $this->uri->segment(3);
		
		$filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id;
		
		if($this->dx_auth->get_user_id() == '')
		{
			echo 'users/signin';exit;
		}
		
	    if(!file_exists($filename)) 
		{
     	mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id, 0777, true);
		}
		
		$file_element_name = "upload_file";	
			  
	  if(isset($_FILES["upload_file"]["name"]))
					{
       foreach ($_FILES["upload_file"]["error"] as $key => $error) {
 	
				$tmp_name = $_FILES["upload_file"]["tmp_name"][$key];
				$name = str_replace(' ','_',$_FILES["upload_file"]["name"][$key]);
				
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $image_name    = random_string('alnum',8).'.'.$ext;					   
					if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')	
					{				 
					if( move_uploaded_file($tmp_name, "images/{$room_id}/{$image_name}"))
					{
                    
					if($ext == 'png')
					{
						error_reporting(0);
						if(imagecreatefrompng("images/{$room_id}/{$image_name}"))
						{
						$image = imagecreatefrompng("images/{$room_id}/{$image_name}");
    					imagejpeg($image, "images/{$room_id}/{$image_name}", 100);
    					imagedestroy($image);
						}
					}
   /*   if (!$this->upload->do_upload($key))
      {
      	echo $this->upload->display_errors();exit;
		 echo 'no';exit;
      }
      else
      { 
      	 	    $upload_data = $this->upload->data(); 			
			      //$image_name    = $name;
				        $insertData['list_id']    = $this->uri->segment(3);
      	                $insertData['name']       = $image_name;
						$insertData['created']    = local_to_gmt();
						
						$check = $this->db->where('list_id',$room_id)->get('list_photo');
						
						$photo_status['photo'] = 1;
			            $this->db->where('id',$room_id)->update('lys_status',$photo_status);
						
						if($check->num_rows() == 0)
						{
							$insertData['is_featured'] = 1;
				     	}
                        else 
                        {
	                       $insertData['is_featured'] = 0;
                        }
						if($image_name != '')
						$this->Common_model->insertData('list_photo', $insertData);
						//$this->watermark($room_id,$image_name);
 $this->watermark($room_id,$image_name);
  $this->watermark1($room_id,$image_name);
   //exit;
   
	  }
	  }
	  }
 $list_photo = $this->db->where('list_id',$room_id)->order_by('id','asc')->get('list_photo');
		   
		  echo ' <ul class="photo_img" id="photo_ul">';
   	if($list_photo->num_rows() != 0)
	{
   	foreach($list_photo->result() as $row)
     {    
      echo '<li class="photo_img_sub">
      <div id="pannel_photo_item_id" class="pannel_photo_item">
      <div class="first-photo-ribbon"></div>
      <div class="photo-drag-target"></div><a class="media-link"><img width="100%" src="'.base_url()."images/".$room_id."/".$row->name.'"></a>
      <button data-photo-id="29701026" class="delete-photo-btn js-delete-photo-btn" onclick="jQuery(this).delete_photo('.$row->id.')">
        <i ><img src="'.base_url().'css/templates/blue/images/delete-32.png"></i>
      </button>
      <div class="panel-body panel-condensed">
      <textarea rows="3" id="highlight_'.$row->id.'" placeholder="'.translate("What are the highlights of this photo?").'" class="input-large" onkeyup="$(this).highlight('.$row->id.')">'.trim($row->highlights).'</textarea>
      </div></li>';

     }
	}
   echo '</div>
   </ul>';
	  }
	}
*/

//========= With CDN ============//


function add_photo1()
    {
        $room_id = $this->session->userdata('room_id');
        $room_id = $this->uri->segment(3);
        require_once APPPATH.'libraries/cloudinary/autoload.php';
        \Cloudinary::config(array( 
                        "cloud_name" => cdn_name, 
                         "api_key" => cdn_api, 
                         "api_secret" => cloud_s_key
              ));
        
        if($this->dx_auth->get_user_id() == '')
        {
            echo 'users/signin';exit;
        }
         
  if(isset($_FILES["upload_file1"]["name"]))
                    {
                        
 foreach ($_FILES["upload_file1"]["error"] as $key => $error) {
    
                $tmp_name = $_FILES["upload_file1"]["tmp_name"][$key];
                $name = str_replace(' ','_',$_FILES["upload_file1"]["name"][$key]);
                $temp = explode('.', $name);
                $ext  = array_pop($temp);
                $name1 = implode('.', $temp);
                try{
                      $cloudimage=\Cloudinary\Uploader::upload($tmp_name,
                           array(
                                "public_id" => "images/{$room_id}/".$name1,));
                    }
                catch (Exception $e) {
                       $error = $e->getMessage();
                  }
                $secureimage = $cloudimage['secure_url'];
                 $ext = pathinfo($name, PATHINFO_EXTENSION);
                    if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')   
                    {                        
                    if( $secureimage!=''){
                        $image_name    = $name;
                        $insertData['list_id']    = $this->uri->segment(3);
                        $image = explode('.',$image_name);
                        $insertData['name'] = $image_name;
                        $userid = $this->db->where('id', $room_id)->get('list')->row()->user_id;
                        $insertData['user_id'] = $userid;
                                                $insertData['image']   = cdn_url_images().'images/'.$insertData['list_id'].'/'.$image_name;         
                                                $insertData['resize']  = cdn_url_images().'images/'.$insertData['list_id'].'/'.$image[0].'.'.$image[1];            
                                                $insertData['resize1'] = cdn_url_images().'images/'.$insertData['list_id'].'/'.$image[0].'.'.$image[1];
                        $insertData['name']       = $image_name;
                        $insertData['created']    = local_to_gmt();
                        $check = $this->db->where('list_id',$room_id)->get('list_photo');
                         $photo_status['photo'] = 1;
                        $this->db->where('id',$room_id)->update('lys_status',$photo_status);
                        if($check->num_rows() == 0)
                        {
                            $insertData['is_featured'] = 1;
                        }
                        else 
                        {
                           $insertData['is_featured'] = 0;
                        }
                        if($image_name != '')
                        $this->Common_model->insertData('list_photo', $insertData);
                            }
      }
      }
$list_photo = $this->db->where('list_id',$room_id)->order_by('id','asc')->get('list_photo');
           
           $echodata = ' <ul class="photo_img" id="photo_ul">';
    if($list_photo->num_rows() != 0)
    {
    foreach($list_photo->result() as $row)
     {    
      $echodata .= '<li class="photo_img_sub">
      <div id="pannel_photo_item_id" class="pannel_photo_item">
      <div class="first-photo-ribbon"></div>
      <div class="photo-drag-target"></div><a class="media-link"><img width="100%" src="'.base_url()."images/".$room_id."/".$row->name.'"></a>
      <button data-photo-id="29701026" class="delete-photo-btn js-delete-photo-btn" onclick="jQuery(this).delete_photo('.$row->id.')">
        <i ><img src="'.css_url().'/images/delete-32.png"></i>
      </button>
      <div class="panel-body panel-condensed">
      <textarea rows="3" id="highlight_'.$row->id.'" placeholder="'.translate("What are the highlights of this photo?").'" class="input-large" onkeyup="$(this).highlight('.$row->id.')">'.trim($row->highlights).'</textarea>
      </div></li>';
     }
    }
   $echodata .= '</div>
   </ul>';
      }
$data['disp_image']=$echodata;
$this->load->view('templates/blue/listphoto',$data);
    }
    function add_photo()
    {
        $room_id = $this->session->userdata('room_id');
        $room_id = $this->uri->segment(3);
        if($this->dx_auth->get_user_id() == '')
        {
            redirect('users/signin');exit;
        }
        require_once APPPATH.'libraries/cloudinary/autoload.php';
        \Cloudinary::config(array( 
                    "cloud_name" => cdn_name, 
                    "api_key" => cdn_api, 
                    "api_secret" => cloud_s_key
                    ));
      if(isset($_FILES["upload_file"]["name"]))
                    {
       foreach ($_FILES["upload_file"]["error"] as $key => $error) {
    
                $tmp_name = $_FILES["upload_file"]["tmp_name"][$key];
                $name = str_replace(' ','_',$_FILES["upload_file"]["name"][$key]);
                $temp = explode('.', $name);
                $ext  = array_pop($temp);
                $name1 = implode('.', $temp);
                  try{                                                    
                     $cloudimage1=\Cloudinary\Uploader::upload($tmp_name,
                         array(
                            "public_id" => "images/{$room_id}/".$name1,));
                      }
                  catch (Exception $e) {
                      $error = $e->getMessage();
                    } 
                $secureimage = $cloudimage1['secure_url'];
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPEG' || $ext == 'PNG' || $ext ==  'GIF' || $ext == 'JPG') 
                    {                
                    if($secureimage!='')
                    {
                $image_name    = $name;
                $insertData['list_id']    = $this->uri->segment(3);
                $image = explode('.',$image_name);
                $insertData['name'] = $image_name;
                                   
     $userid = $this->db->where('id', $room_id)->get('list')->row()->user_id;
     $insertData['user_id'] = $userid;
     $insertData['image']      = cdn_url_images().'images/'.$insertData['list_id'].'/'.$image_name;      
     $insertData['resize']   =cdn_url_images().'images/'.$insertData['list_id'].'/'.$image[0].'.'.$image[1];          
     $insertData['resize1']    = cdn_url_images().'images/'.$insertData['list_id'].'/'.$image[0].'.'.$image[1]; 
     $insertData['created']    = local_to_gmt();
     $check = $this->db->where('list_id',$room_id)->get('list_photo');
     $photo_status['photo'] = 1;
     $this->db->where('id',$room_id)->update('lys_status',$photo_status);
               if($check->num_rows() == 0)
                    {
                       $insertData['is_featured'] = 1;
                    }
                else 
                   {
                     $insertData['is_featured'] = 0;
                    }
                 if($image_name != '')
                    $this->Common_model->insertData('list_photo', $insertData);
          }
      }
      }
            $list_photo = $this->db->where('list_id',$room_id)->order_by('id','asc')->get('list_photo');
            $echodata = ' <ul class="photo_img" id="photo_ul">';
    if($list_photo->num_rows() != 0)
    {
    foreach($list_photo->result() as $row)
     {    
      $echodata .= '<li class="photo_img_sub">
      <div id="pannel_photo_item_id" class="pannel_photo_item">
      <div class="first-photo-ribbon"></div>
      <div class="photo-drag-target"></div><a class="media-link"><img width="100%" src="'.base_url()."images/".$room_id."/".$row->name.'"></a>
      <button data-photo-id="29701026" class="delete-photo-btn js-delete-photo-btn" onclick="jQuery(this).delete_photo('.$row->id.')">
        <i ><img src="'.css_url().'/images/delete-32.png"></i>
      </button>
      <div class="panel-body panel-condensed">
      <textarea rows="3" id="highlight_'.$row->id.'" placeholder="'.translate("What are the highlights of this photo?").'" class="input-large" onkeyup="$(this).highlight('.$row->id.')">'.trim($row->highlights).'</textarea>
      </div></li>';
        }
    }
   $echodata .= '</div>
   </ul>';
      }
$data['disp_image']=$echodata;
$this->load->view('templates/blue/listphoto',$data);
    }

//===========END ================//

function photo_check()
{
	extract($this->input->post());
	$check_photo = $this->db->where('list_id',$room_id)->get('list_photo');
	if($check_photo->num_rows()!=0)
	{
		echo '1';
	}
	else {
		echo '0';
	}
}

function delete_photo()
{
	extract($this->input->post());
	
	$name = $this->db->where('id',$photo_id)->get('list_photo')->row()->name;
	$list_id = $this->db->where('id',$photo_id)->get('list_photo')->row()->list_id;
	$pieces                   = explode(".", $name);
	//unlink(dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$name);
	$name1=$pieces[0];
	$this->db->delete('list_photo',array('id'=>$photo_id));
	$files = glob(APPPATH . '../images/'.$list_id.'/'.$name);
	$files = glob(APPPATH . '../images/'.$list_id.'/'.$name.'*'); // get all file names
	$files = glob(APPPATH . '../images/'.$list_id.'/'.$name1.'*'); // get all file names
	foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
	}
	
	$list_photo = $this->db->where('list_id',$room_id)->order_by('id','asc')->get('list_photo');
		   
		   if($list_photo->num_rows() == 0)
		   {
		   	   $photo_status['photo'] = 0;
			   $this->db->where('id',$room_id)->update('lys_status',$photo_status);
			   $list_status['is_enable'] = 0;
			   //$list_status['list_pay'] = 0;
			   $this->db->where('id',$room_id)->update('list',$list_status);
		   }
		   
		   $list_photo_isfeatured = $this->db->where('list_id',$room_id)->where('is_featured',1)->get('list_photo');
		   
		   if($list_photo_isfeatured->num_rows() == 0)
		   {
		   	$list_photo_isfeatured = $this->db->where('list_id',$room_id)->order_by('id','asc')->limit(1)->get('list_photo');
			if($list_photo_isfeatured->num_rows() == 1)
			{
				$this->db->where('list_id',$room_id)->where('id',$list_photo_isfeatured->row()->id)->set('is_featured',1)->update('list_photo');
			}
		   }
		  
		  
		  $echodata = ' <ul class="photo_img" id="photo_ul">';
   	if($list_photo->num_rows() != 0)
	{
		
   	foreach($list_photo->result() as $row)
     {    
      $echodata.= '<li class="photo_img_sub">
      <div id="pannel_photo_item_id" class="pannel_photo_item">
      <div class="first-photo-ribbon"></div>
      <div class="photo-drag-target"></div><a class="media-link"><img width="100%" src="'.base_url()."images/".$room_id."/".$row->name.'"></a>
      <button data-photo-id="29701026" class="delete-photo-btn js-delete-photo-btn" onclick="jQuery(this).delete_photo('.$row->id.')">
        <i ><img src="'.css_url().'/images/delete-32.png"></i>
      </button>
      <div class="panel-body panel-condensed">
      <textarea rows="3" id="highlight_'.$row->id.'" placeholder="'.translate("What are the highlights of this photo?").'" class="input-large" onkeyup="$(this).highlight('.$row->id.')">'.trim($row->highlights).'</textarea>
      </div></li>';

     }
	}
   $echodata.= '</div> </ul>';
   $data['disp_image']=$echodata;
$this->load->view('templates/blue/listphoto',$data);
   
}
function photo_highlight(){
	extract($this->input->post());
	$this->db->where('id',$photo_id)->set('highlights',$msg)->update('list_photo');
	echo 'Success';exit;
}
function get_photo_highlight()
{
	extract($this->input->post());
	echo $this->db->where('id',$photo_id)->get('list_photo')->row()->highlights;exit;
}
function final_photo()
{
 
	extract($this->input->post());
	
	$result = $this->db->where('id',$room_id)->get('lys_status')->row();
	
	$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
	if($total == 6)
	{
	  $data['is_enable'] = 1;
	  $data['list_pay'] = 1;
	  $this->db->where('id',$room_id)->update('list',$data);
	  
	  $query_user 	= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row();
		$username 		= $query_user->username;
		
		if($edit_list == 'edit')
		{
		  $edit_list="$username edited a list";	
		  $insertData = array(
			'list_id'         => $room_id,
			'conversation_id' => $room_id,
			'userby'          => $this->dx_auth->get_user_id(),
			'userto'          => 1,
			'message'         => $edit_list,
			'created'         => time(),
			'message_type '   => 11
			);	
		
		
		}
		else {
			$edit_list="$username created a new list";
		
		
				
		
			$insertData = array(
			'list_id'         => $room_id,
			'conversation_id' => $room_id,
			'userby'          => $this->dx_auth->get_user_id(),
			'userto'          => 1,
			'message'         => $edit_list,
			'created'         => time(),
			'message_type '   => 10
			);	
		}	
			
			$this->Message_model->sentMessage($insertData);
			
			$host_email = $query_user->email;
		
		$admin_email = $this->dx_auth->get_site_sadmin();
		$admin_name  = $this->dx_auth->get_site_title();
		
		$admin_to_email = $this->Common_model->getTableData('users',array('id' => 1))->row()->email;
			
			$query_list	= $this->Common_model->getTableData('list',array('id' => $room_id))->row();
			
			$currency = $query_list->currency;
			$price = $query_list->price;
			$title = $query_list->title;
			$link = base_url().'rooms/'.$room_id;
			
		$email_name = 'list_create_host';
		$splVars    = array("{host_name}"=>$username,"{price}"=> $currency.$price,"{link}"=>$link,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		$email_name = 'list_create_admin';
		$splVars    = array("{host_name}"=>$username,"{price}"=> $currency.$price,"{link}"=>$link,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($admin_to_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
	  
	}
	
	$image_name = $this->db->where('list_id',$room_id)->where('is_featured',1)->get('list_photo');
	if($image_name->num_rows()!=0)
	{
	$image_name = $image_name->row()->name;
	}
	else
	{
	$image_name = 'no_image.jpg';
	}
	echo $image_name;exit;
}

function add_address()
{
	extract($this->input->post());
	$data['country'] = $country;
	$data['city'] = $city;
	$data['state'] = $state;
	$data['optional_address'] = $optional_address;
	$data['street_address'] = $street_address;
	$data['zip_code'] = $zipcode;
	$data['address'] = $full_address." ".$zipcode;
	$data['lat'] = $lat;
	$data['long'] = $lng;
	$this->db->where('id',$room_id)->update('list',$data);
	$data1['address'] = 1;
	$this->db->where('id',$room_id)->update('lys_status',$data1);
	
		$result = $this->db->where('id',$room_id)->get('lys_status')->row();
			
			$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
			if($total == 6)
			{
			  $data['is_enable'] = 1;
			  $data['list_pay'] = 1;
			  $this->db->where('id',$room_id)->update('list',$data);
			}

	echo 'Success';exit;
}

function get_address()
{
	extract($this->input->post());
	$result = $this->db->where('id',$room_id)->get('list')->result();
	echo json_encode($result);exit;
}

function house_rules()
{
	extract($this->input->post());
	$data['house_rule'] = $house_rules;
	$this->db->where('id',$room_id)->update('list',$data);
	echo 'Success';exit;
}
	
	public function edit($param = '')
	{
	   	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
		{
			$room_id                  = $param;
			
			if($room_id == "")
			{
			  redirect('info/deny');
			}
			
			$result                   = $this->Common_model->getTableData('list', array('id' => $room_id));
			
			if($result->num_rows() == 0 or $result->row()->user_id != $this->dx_auth->get_user_id())
			{
					redirect('info/deny');
			}
		
			
			$conditions               = array('id' => $param);
			$data['row']              = $this->Rooms_model->get_room($conditions)->row();
			
			$data['amnities']         = $this->Rooms_model->get_amnities();
			
			$data['title']            = get_meta_details('Edit_your_Listing','title');
			$data["meta_keyword"]     = get_meta_details('Edit_your_Listing','meta_keyword');
			$data["meta_description"] = get_meta_details('Edit_your_Listing','meta_description');
		
			$data['message_element']  = "rooms/view_edit_listing";
			$this->load->view('template', $data);
		}
		else
		{
			redirect('users/signin');
		}	
	}
	
	
	public function update($param = '')
	{
			
		if($param != '')
		{
			// Form Validation
			
			$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
			
			$this->form_validation->set_rules('hosting_descriptions', 'Title','required|trim|xss_clean');
			$this->form_validation->set_rules('manual', 'manual','trim|xss_clean');
			$this->form_validation->set_rules('desc', 'Description','trim|xss_clean');
			$this->form_validation->set_rules('manual', 'manual','trim|xss_clean');
			$this->form_validation->set_rules('hosting_directions', 'Hosting Directions','trim|xss_clean');
			
			
			$amenity   = $this->input->post('amenities');
			$aCount    = count($amenity);
			
			$amenities = '';	
			if(is_array($amenity))
			{
				if(count($amenity) > 0)
				{
					$i = 1;
					foreach($amenity as $value)
					{
							if($i == $aCount) $comma = ''; else $comma = ',';
							
							$amenities .= $value.$comma;
							$i++;
					}
				}
			}
				//$descr = str_replace("\n",'^nl;^',$this->input->post("desc"));
				$descr = $this->input->post("desc");
			$updateData = array(
							'property_id'  	=> $this->input->post('property_id'),
							'room_type'   	=> $this->input->post('room_type'),
							'title'    		=> $this->input->post('hosting_descriptions'),
							'desc'         	=> $descr,
							'capacity'     	=> $this->input->post('capacity'),
							'cancellation_policy' => $this->input->post('cancellation_policy'),
							'bedrooms'    	=> $this->input->post('bedrooms'),
							'beds'     		=> $this->input->post('beds'),
							'bed_type'     	=> $this->input->post('hosting_bed_type'),
							'bathrooms'     => $this->input->post('hosting_bathrooms'),
							'manual'     	=> $this->input->post('manual'),
							'street_view'   => $this->input->post('street_view'),
							'directions'    => $this->input->post('hosting_directions'),
							'neighbor'		=> $this->input->post('area')
							);
																	
			if(isset($_POST['address']['formatted_address_native']))
			{												
				$address     = $_POST['address']['formatted_address_native'];
				if(!empty($address))
				{
				$address     = urlencode($address);
				$address     = str_replace('+','%20',$address); 
				$geocode     = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
				$output      = json_decode($geocode);
				
				$updateData['address'] = $_POST['address']['formatted_address_native'];
				
				$level = explode(',', $updateData['address']);
        $keys = array_keys($level);
        $country = $level[end($keys)];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        $updateData['country'] = trim($country);
				
				
				$updateData['lat'] 				= $output->results[0]->geometry->location->lat;
				$updateData['long'] 			= $output->results[0]->geometry->location->lng;
				}
			}
																
		if($amenities != '')
		{
		$updateData['amenities'] = $amenities;
		}
																
		$updateKey = array('id' => $param);									
		$this->Rooms_model->update_list($updateKey, $updateData);
															
		echo '{"redirect_to":"'.base_url().'rooms/'.$param.'","result":"success"}';
		}
	}
	
	
	public function edit_photo($param  = '')
	{
	
		
	//	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
		
			$data['room_id'] = $room_id  = $param;
			
			if($room_id == "")
			{
			  redirect('info/deny');
			}
			
			$result                   = $this->Common_model->getTableData('list', array('id' => $room_id));
			if($result->num_rows() == 0 or $result->row()->user_id != $this->dx_auth->get_user_id())
			{
					redirect('info/deny');
			}
		
	 if($this->input->post())
		{
				$listId           = $param;
				$images           = $this->input->post('image');
		  $is_main          = $this->input->post('is_main');
				
				$fimages = $this->Gallery->get_imagesG($listId);
				if($is_main != '')
				{
				foreach($fimages->result() as $row)
				{
				 if($row->id == $is_main)
				   $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 1));
					else
					  $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 0));
				}
				}
				
				if(!empty($images))
				{
					foreach($images as $key=>$value)
					{
					 $image_name = $this->Gallery->get_imagesG(NULL, array('id' => $value))->row()->name;
						unlink($this->path.'/'.$listId.'/'.$image_name);
							
						$conditions = array("id" => $value);
						$this->Common_model->deleteTableData('list_photo', $conditions);
					}
				}
		
					if(isset($_FILES["userfile"]["name"]))
					{
				
			$allowed_ext = array('jpg','jpeg','png','gif');

			$insertData['list_id'] = $listId;
			
			if(!is_dir($this->path.'/'.$listId))
			{
					mkdir($this->path.'/'.$listId, 0777, true);
					$insertData['is_featured'] = 1;
			}
			
		foreach ($_FILES["userfile"]["error"] as $key => $error) {
		  if ($error == UPLOAD_ERR_OK) {
				$tmp_name = $_FILES["userfile"]["tmp_name"][$key];
				$name = $_FILES["userfile"]["name"][$key];
					if( move_uploaded_file($tmp_name, "images/{$listId}/{$name}")){
					///////////////////////
                         	$config['image_library'] = 'gd2';
                          	$config['source_image'] = $this->path.'/'.$listId.'/'.$name;
							$config['encrypt_name'] = TRUE;
							
                          $this->image_lib->initialize($config);
                          $this->image_lib->clear();
						 $this->load->library('upload', $config);
						 ///////////////////////////////
						$insertData['name']       =$name;
						$insertData['created']    = local_to_gmt();
						if($name != '')
						$this->Common_model->insertData('list_photo', $insertData);
						$this->watermark($listId,$name);
						$this->watermark1($room_id,$image_name);
				 }
    		  }
		  }
			 		  
					}
					
					$rimages = $this->Gallery->get_imagesG($listId);
					$i = 1;
					$replace = '<ul class="clearfix">';
					foreach ($rimages->result() as $rimage)
					{		
					  if($rimage->is_featured == 1)
							 $checked = 'checked="checked"'; 
							else
							 $checked = ''; 
								
					 				$url = base_url().'images/'.$rimage->list_id.'/'.$rimage->name;
									
									$replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage->id.'" /></label>';
									$replace .= '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$rimage->id.'" /></p></li>';
								$i++;
					}
					$replace .= '</ul>';
					
					echo $replace;
		}
		else
		{
		$data['list_images']      = $this->Gallery->get_imagesG($param);
		$data['list']             = $this->Common_model->getTableData('list', array('id' => $param))->row();
		
		$data['title']            = get_meta_details('Add_photo_for_this_listing','title');
		$data["meta_keyword"]     = get_meta_details('Add_photo_for_this_listing','meta_keyword');
		$data["meta_description"] = get_meta_details('Add_photo_for_this_listing','meta_description');
		
		$data['message_element']     = "rooms/view_edit_photo";
		$this->load->view('template',$data);
		}
		}
		else
		{
		  redirect('users/signin');
		}
		
	}

function watermark($list_id,$image_name)
	{
   $image_path =  dirname($_SERVER['SCRIPT_FILENAME']);
  
  $main_imgc		= $image_path."/images/$list_id/$image_name"; // main big photo / picture
  
// using the function to crop an image
$source_image = $main_imgc;
$main_img_ext = explode('.', $image_name);
$main_imgc = $image_path."/images/$list_id/$main_img_ext[0]";
$target_image = $main_imgc.'_crop.jpg';

$return = $this->resize_image($source_image, $target_image); 

if($return != 1)
{
	exit;
}
$main_img = $target_image;
$logo = $this->db->get_where('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
$watermark_img	= $image_path."/logo/$logo"; // use GIF or PNG, JPEG has no tranparency support
$padding 		= 3;     // distance to border in pixels for watermark image
$opacity		= 50;	// image opacity for transparent watermark

$watermark 	    = imagecreatefrompng($watermark_img); // create watermark
$image_water 	= imagecreatefromjpeg($main_img); // create main graphic

//if(!$image_water || !$watermark) die("Error: main image or watermark could not be loaded!");


$watermark_size 	= getimagesize($watermark_img);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$image_size 	= getimagesize($main_img);  
$dest_x 		= $image_size[0] - $watermark_width - $padding;  
$dest_y 		= $image_size[1] - $watermark_height - $padding;


// copy watermark on main image
imagecopy($image_water, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);


// print image to screen
//header("content-type: image/jpeg");   
imagejpeg($image_water,$main_imgc.'.'.$main_img_ext[1].'_watermark.jpg',100);  
//imagejpeg($image_water); 
imagedestroy($image_water);  
imagedestroy($watermark); 
return true;

	 } 

function watermark1($list_id,$image_name)
	{
   $image_path =  dirname($_SERVER['SCRIPT_FILENAME']);
  
  $main_imgc		= $image_path."/images/$list_id/$image_name"; // main big photo / picture
  
$watermark_size 	= getimagesize($main_imgc);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$config['image_library'] = 'gd2';
$config['source_image'] = $main_imgc;
$config['new_image'] = $image_path."/images/$list_id/$image_name"."_home.jpg";
$config['maintain_ratio'] = TRUE;
$config['width'] = 1355;
$config['height'] = 500;

$this->load->library('image_lib');
$this->image_lib->initialize($config);

if ( ! $this->image_lib->resize())
{
    echo $this->image_lib->display_errors();exit;
}
else {
	//echo 'resized';exit;
}
// using the function to crop an image
$file_ext = pathinfo($main_imgc, PATHINFO_EXTENSION);
$main_img = $config['new_image'];
$watermark_img	= $image_path."/images/banner_black_watermark_right.png"; // use GIF or PNG, JPEG has no tranparency support
$padding 		= 0;     // distance to border in pixels for watermark image
$opacity		= 50;	// image opacity for transparent watermark

$watermark 	    = imagecreatefrompng($watermark_img); // create watermark
if($file_ext == 'gif')
{
	$image_water 	= imagecreatefromgif($main_img); 
}else{
	$image_water 	= imagecreatefromjpeg($main_img);// create main graphic
}
 
//if(!$image_water || !$watermark) die("Error: main image or watermark could not be loaded!");


$watermark_size 	= getimagesize($watermark_img);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$image_size 	= getimagesize($main_img);  
$dest_x 		= $image_size[0] - $watermark_width - $padding;  
$dest_y 		= $image_size[1] - $watermark_height - $padding;

//echo $image_size[0].' - '.$watermark_width.'-'.$dest_x;
// copy watermark on main image
imagecopy($image_water, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);


// print image to screen
//header("content-type: image/jpeg");   
imagejpeg($image_water,$main_img.'_watermark.jpg',100);  
//imagejpeg($image_water); 
imagedestroy($image_water);  
imagedestroy($watermark); 

$main_img = $main_img.'_watermark.jpg';
$watermark_img	= $image_path."/images/banner_black_watermark_left.png"; // use GIF or PNG, JPEG has no tranparency support
$padding 		= 0;     // distance to border in pixels for watermark image
$opacity		= 50;	// image opacity for transparent watermark

$watermark 	    = imagecreatefrompng($watermark_img); // create watermark
$image_water 	= imagecreatefromjpeg($main_img); // create main graphic

//if(!$image_water || !$watermark) die("Error: main image or watermark could not be loaded!");


$watermark_size 	= getimagesize($watermark_img);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$image_size 	= getimagesize($main_img);  
$dest_x 		= $image_size[0] - $watermark_width - $padding;  
$dest_y 		= $image_size[1] - $watermark_height - $padding;

//echo $image_size[0].' - '.$watermark_width.'-'.$dest_x;
// copy watermark on main image
imagecopy($image_water, $watermark, 0, $dest_y, 0, 0, $watermark_width, $watermark_height);


// print image to screen
//header("content-type: image/jpeg");   
imagejpeg($image_water,$main_img,100);  
//imagejpeg($image_water); 
imagedestroy($image_water);  
imagedestroy($watermark); 

return true;

	 } 

	/**
 * Resize Image
 *
 * Takes the source image and resizes it to the specified width & height or proportionally if crop is off.
 * @access public
 * @author Jay Zawrotny <jayzawrotny@gmail.com>
 * @license Do whatever you want with it.
 * @param string $source_image The location to the original raw image.
 * @param string $destination_filename The location to save the new image.
 * @param int $width The desired width of the new image
 * @param int $height The desired height of the new image.
 * @param int $quality The quality of the JPG to produce 1 - 100
 * @param bool $crop Whether to crop the image or not. It always crops from the center.
 */
function resize_image($source_image, $destination_filename, $width = 400, $height = 350, $quality = 70, $crop = false)
{

        if( ! $image_data = getimagesize( $source_image ) )
        {
                return false;
        }

        switch( $image_data['mime'] )
        {
                case 'image/gif':
                        $get_func = 'imagecreatefromgif';
                        $suffix = ".gif";
                break;
                case 'image/jpeg';
                        $get_func = 'imagecreatefromjpeg';
                        $suffix = ".jpg";
                break;
                case 'image/png':
                        $get_func = 'imagecreatefrompng';
                        $suffix = ".png";
                break;
        }
 ini_set("memory_limit",-1);
        $img_original = call_user_func( $get_func, $source_image );
        $old_width = $image_data[0];
        $old_height = $image_data[1];
        $new_width = $width;
        $new_height = $height;
        $src_x = 0;
        $src_y = 0;
        $current_ratio = round( $old_width / $old_height, 2 );
        $desired_ratio_after = round( $width / $height, 2 );
        $desired_ratio_before = round( $height / $width, 2 );

      //  if( $old_width < $width || $old_height < $height )
      //  {
                /**
                 * The desired image size is bigger than the original image. 
                 * Best not to do anything at all really.
                 */
      //          return false;
     //   }


        /**
         * If the crop option is left on, it will take an image and best fit it
         * so it will always come out the exact specified size.
         */
        if( $crop )
        {
                /**
                 * create empty image of the specified size
                 */
                $new_image = imagecreatetruecolor( $width, $height );

                /**
                 * Landscape Image
                 */
                if( $current_ratio > $desired_ratio_after )
                {
                        $new_width = $old_width * $height / $old_height;
                }

                /**
                 * Nearly square ratio image.
                 */
                if( $current_ratio > $desired_ratio_before && $current_ratio < $desired_ratio_after )
                {
                        if( $old_width > $old_height )
                        {
                                $new_height = max( $width, $height );
                                $new_width = $old_width * $new_height / $old_height;
                        }
                        else
                        {
                                $new_height = $old_height * $width / $old_width;
                        }
                }

                /**
                 * Portrait sized image
                 */
                if( $current_ratio < $desired_ratio_before  )
                {
                        $new_height = $old_height * $width / $old_width;
                }

                /**
                 * Find out the ratio of the original photo to it's new, thumbnail-based size
                 * for both the width and the height. It's used to find out where to crop.
                 */
                $width_ratio = $old_width / $new_width;
                $height_ratio = $old_height / $new_height;

                /**
                 * Calculate where to crop based on the center of the image
                 */
                $src_x = floor( ( ( $new_width - $width ) / 2 ) * $width_ratio );
                $src_y = round( ( ( $new_height - $height ) / 2 ) * $height_ratio );
        }
        /**
         * Don't crop the image, just resize it proportionally
         */
        else
        {
                if( $old_width > $old_height )
                {
                        $ratio = max( $old_width, $old_height ) / max( $width, $height );
                }else{
                        $ratio = max( $old_width, $old_height ) / min( $width, $height );
                }

                $new_width = $old_width / $ratio;
                $new_height = $old_height / $ratio;

                $new_image = imagecreatetruecolor( $new_width, $new_height );
        }

        /**
         * Where all the real magic happens
         */
        imagecopyresampled( $new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height );

        /**
         * Save it as a JPG File with our $destination_filename param.
         */
          /*  $image_path =  dirname($_SERVER['SCRIPT_FILENAME']);
  
  $destination_filename		= $image_path."/images/85/crop";*/
        imagejpeg( $new_image, $destination_filename, $quality  );

        /**
         * Destroy the evidence!
         */
        imagedestroy( $new_image );
        imagedestroy( $img_original );

        /**
         * Return true because it worked and we're happy. Let the dancing commence!
         */
        return true;
} 
	public function edit_price($param = '')
	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
		{	
		if($param == "")
		{
		redirect('info/deny');
		}
		
		$conditions             = array("id" => $param, "user_id" => $this->dx_auth->get_user_id());
	 $result                 = $this->Common_model->getTableData('list', $conditions);
		
		if($result->num_rows() == 0)
		{
		  redirect('info/deny');
		}
		
		$data['room_id']  = $param;
		
			$this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;">', '</p>');
	       $default_curr = $this->db->where('default',1)->get('currency')->row()->currency_code;
		 if($this->input->post())
			{
				$this->form_validation->set_rules('nightly','Nightly','required');
					if($this->form_validation->run())
					{	
					if($this->session->userdata('locale_currency') != '')
					{
						if($this->session->userdata('locale_currency') != $result->row()->currency)
					{
					$neight_price_data = array('amount'=>$this->input->post('nightly'),'currFrom'=>$this->session->userdata("locale_currency"),'currInto'=>$result->row()->currency);
					$neigh_price = round(google_convert($neight_price_data));
					
					$weekly_price_data = array('amount'=>$this->input->post('weekly'),'currFrom'=>$this->session->userdata("locale_currency"),'currInto'=>$result->row()->currency);
					$week_price = round(google_convert($weekly_price_data));
					
					$monthly_price_data = array('amount'=>$this->input->post('monthly'),'currFrom'=>$this->session->userdata("locale_currency"),'currInto'=>$result->row()->currency);
					$month_price = round(google_convert($monthly_price_data));
					
					$extra_price_data = array('amount'=>$this->input->post('extra'),'currFrom'=>$this->session->userdata("locale_currency"),'currInto'=>$result->row()->currency);
					$extra_price = round(google_convert($extra_price_data));
					
					$guests_price_data = array('amount'=>$this->input->post('guests'),'currFrom'=>$this->session->userdata("locale_currency"),'currInto'=>$result->row()->currency);
					$guest_price = round(google_convert($guests_price_data));
					
					$security_price_data = array('amount'=>$this->input->post('security'),'currFrom'=>$this->session->userdata("locale_currency"),'currInto'=>$result->row()->currency);
					$security_price = round(google_convert($security_price_data));
					
					$cleaning_price_data = array('amount'=>$this->input->post('cleaning'),'currFrom'=>$this->session->userdata("locale_currency"),'currInto'=>$result->row()->currency);
					$cleaning_price = round(google_convert($cleaning_price_data));
					}
					else
					{
						$neigh_price = $this->input->post('nightly');
						$week_price = $this->input->post('weekly');
						$month_price = $this->input->post('monthly');
						$extra_price = $this->input->post('extra');
						$guest_price = $this->input->post('guests');
						$security_price = $this->input->post('security');
						$cleaning_price = $this->input->post('cleaning');
					}
					}
					elseif($default_curr != $result->row()->currency)
					{
						$neight_price_data = array('amount'=>$this->input->post('nightly'),'currFrom'=>$default_curr,'currInto'=>$result->row()->currency);
						$neigh_price = round(google_convert($neight_price_data));
						
						$weekly_price_data = array('amount'=>$this->input->post('weekly'),'currFrom'=>$default_curr,'currInto'=>$result->row()->currency);
						$week_price = round(google_convert($weekly_price_data));
						
						$monthly_price_data = array('amount'=>$this->input->post('monthly'),'currFrom'=>$default_curr,'currInto'=>$result->row()->currency);
						$month_price = round(google_convert($monthly_price_data));
						
						$extra_price_data = array('amount'=>$this->input->post('extra'),'currFrom'=>$default_curr,'currInto'=>$result->row()->currency);
						$extra_price = round(google_convert($extra_price_data));
						
						$guests_price_data = array('amount'=>$this->input->post('guests'),'currFrom'=>$default_curr,'currInto'=>$result->row()->currency);
						$guest_price = round(google_convert($guests_price_data));
						
						$security_price_data = array('amount'=>$this->input->post('security'),'currFrom'=>$default_curr,'currInto'=>$result->row()->currency);
						$security_price = round(google_convert($security_price_data));
						
						$cleaning_price_data = array('amount'=>$this->input->post('cleaning'),'currFrom'=>$default_curr,'currInto'=>$result->row()->currency);
						$cleaning_price = round(google_convert($cleaning_price_data));
					}
					else
					{
						$neigh_price = $this->input->post('nightly');
						$week_price = $this->input->post('weekly');
						$month_price = $this->input->post('monthly');
						$extra_price = $this->input->post('extra');
						$guest_price = $this->input->post('guests');
						$security_price = $this->input->post('security');
						$cleaning_price = $this->input->post('cleaning');
					}
							$data = array(
							'currency' 	=> $this->input->post('currency'),
							'night' 	=> $neigh_price,
							'week' 		=> $week_price,
							'month' 	=> $month_price,
							'addguests' => $extra_price,
							'guests'    => $guest_price,
							'security' 	=> $security_price,
							'cleaning' 	=> $cleaning_price	,
							'currency'  => $this->input->post('to')
							);
				
						$list_id        = $param;
						$this->Common_model->updateTableData('price', $list_id, NULL, $data);
						
						$data1          = array();
						$data1['price'] = $neigh_price;
						$this->Common_model->updateTableData('list', $list_id, NULL, $data1);
						
						$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Price updated successfully.')));
						redirect('rooms/edit_price/'.$param);
				 }
			}
			
			$data['list']                 = $this->Common_model->getTableData('list', array('id' => $param))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $param))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			
			
			$data['message_element']      = 'rooms/view_edit_price';
			$this->load->view('template', $data);
		}
		else
		{
			redirect('users/signin');
		}
	}
	
	
		public function edit_price1($param = '')
	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
		{	
		if($param == "")
		{		
		redirect('info/deny');	
		}
		
		$conditions             = array("id" => $param, "user_id" => $this->dx_auth->get_user_id());
	 $result                 = $this->Common_model->getTableData('list', $conditions);
		
		if($result->num_rows() == 0)
		{
		  redirect('info/deny');
		}
		
		$data['room_id']  = $param;
		
			$this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;">', '</p>');
	
		 if($this->input->post())
			{
				$this->form_validation->set_rules('nightly','Nightly','required|is_natural_no_zero');
					if($this->form_validation->run())
					{	
							$data = array(
							'currency' 	=> $this->input->post('currency'),
							'night' 				=> $this->input->post('nightly'),
							'week' 					=> $this->input->post('weekly'),
							'month' 				=> $this->input->post('monthly'),
							'addguests' => $this->input->post('extra'),
							'guests'    => $this->input->post('guests'),
							'security' 	=> $this->input->post('security'),
							'cleaning' 	=> $this->input->post('cleaning')
							);
				
						$list_id        = $param;
						$this->Common_model->updateTableData('price', $list_id, NULL, $data);
						
						$data1          = array();
						$data1['price'] = $this->input->post('nightly');
						$this->Common_model->updateTableData('list', $list_id, NULL, $data1);
						
						$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Price updated successfully.')));
						redirect('rooms/edit_price1/'.$param);
				 }
			}
			
			$data['list']                 = $this->Common_model->getTableData('list', array('id' => $param))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $param))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			
			
			$data['message_element']      = 'rooms/view_edit_price1';
			$this->load->view('template', $data);
		}
		else
		{
			redirect('users/signin');
		}
	}
	
		public function edit_price2($param = '')
	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
		{	
		if($param == "")
		{
		redirect('info/deny');
		}
		
		$conditions             = array("id" => $param, "user_id" => $this->dx_auth->get_user_id());
	 $result                 = $this->Common_model->getTableData('list', $conditions);
		
		if($result->num_rows() == 0)
		{
		  redirect('info/deny');
		}
		
		$data['room_id']  = $param;
		
			$this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;">', '</p>');
	
		 if($this->input->post())
			{
				$this->form_validation->set_rules('nightly','Nightly','required|is_natural_no_zero');
					if($this->form_validation->run())
					{	
							$data = array(
							'currency' 	=> $this->input->post('currency'),
							'night' 				=> $this->input->post('nightly'),
							'week' 					=> $this->input->post('weekly'),
							'month' 				=> $this->input->post('monthly'),
							'addguests' => $this->input->post('extra'),
							'guests'    => $this->input->post('guests'),
							'security' 	=> $this->input->post('security'),
							'cleaning' 	=> $this->input->post('cleaning')
							);
				
						$list_id        = $param;
						$this->Common_model->updateTableData('price', $list_id, NULL, $data);
						
						$data1          = array();
						$data1['price'] = $this->input->post('nightly');
						$this->Common_model->updateTableData('list', $list_id, NULL, $data1);
						
						$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Price updated successfully.')));
						redirect('rooms/edit_price2/'.$param);
				 }
			}
			
			$data['list']                 = $this->Common_model->getTableData('list', array('id' => $param))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $param))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			
			
			$data['message_element']      = 'rooms/view_edit_price2';
			$this->load->view('template', $data);
		}
		else
		{
			redirect('users/signin');
		}
	}
	
	
	
	public function edit_price3($param = '')
	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
		{	
		if($param == "")
		{
		redirect('info/deny');
		}
		
		$conditions             = array("id" => $param, "user_id" => $this->dx_auth->get_user_id());
	 $result                 = $this->Common_model->getTableData('list', $conditions);
		
		if($result->num_rows() == 0)
		{
		  redirect('info/deny');
		}
		
		$data['room_id']  = $param;
		
			$this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;">', '</p>');
	
		 if($this->input->post())
			{
				$this->form_validation->set_rules('nightly','Nightly','required|is_natural_no_zero');
					if($this->form_validation->run())
					{	
							$data = array(
							'currency' 	=> $this->input->post('currency'),
							'night' 				=> $this->input->post('nightly'),
							'week' 					=> $this->input->post('weekly'),
							'month' 				=> $this->input->post('monthly'),
							'addguests' => $this->input->post('extra'),
							'guests'    => $this->input->post('guests'),
							'security' 	=> $this->input->post('security'),
							'cleaning' 	=> $this->input->post('cleaning')
							);
				
						$list_id        = $param;
						$this->Common_model->updateTableData('price', $list_id, NULL, $data);
						
						$data1          = array();
						$data1['price'] = $this->input->post('nightly');
						$this->Common_model->updateTableData('list', $list_id, NULL, $data1);
						
						$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Price updated successfully.')));
						redirect('rooms/edit_price3/'.$param);
				 }
			}
			
			$data['list']                 = $this->Common_model->getTableData('list', array('id' => $param))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $param))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			
			
			$data['message_element']      = 'rooms/view_edit_price3';
			$this->load->view('template', $data);
		}
		else
		{
			redirect('users/signin');
		}
	}
	
	//delete the daily price table data
	
	public function delete($param)
	{
			$id = $this->uri->segment(4);
		
			$data['room_id']  = $param;
		

	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
{	
					
			if($param == "")
		{
		redirect('info/deny');
		}
			
	$condition = array("id" => $id);
	$this->Common_model->deleteTableData('daily_pricing', $condition);	
	
		$data['list']                 = $this->Common_model->getTableData('list', array('id' => $param))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $param))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			
			
			$data['message_element']      = 'rooms/view_edit_price1';
			$this->load->view('template', $data);
	
			
						
}
else
{
	redirect('users/signin');
}

						
}
	
	
	public function delete1($param)
	{
	
		$id = $this->uri->segment(4);
		
			$data['room_id']  = $param;
		
	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
{	
					
			if($param == "")
		{
		redirect('info/deny');
		}
			
	$condition = array("id" => $id);
	$this->Common_model->deleteTableData('weekly_pricing', $condition);	
	
		$data['list']                 = $this->Common_model->getTableData('list', array('id' => $param))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $param))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			
			
			$data['message_element']      = 'rooms/view_edit_price2';
			$this->load->view('template', $data);
	
			
						
}
else
{
	redirect('users/signin');
}

						
}
	
	//daily price
	
	public function daily_price($param = '')
	{
	
	
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
		{	
		if($param == "")
		{
		redirect('info/deny');
		}
		
		$conditions             = array("id" => $param, "user_id" => $this->dx_auth->get_user_id());
		 $result                 = $this->Common_model->getTableData('list', $conditions);
		 	$p=0;
		
		$data['room_id']  = $param;
		$room1=$param;
		$status="Available";
		
			$this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;">', '</p>');
	
	 
			
		 if($this->input->post())
			{

$train= $this->db->query("SELECT * FROM `daily_pricing` WHERE `room_id` = '".$room1."'");

$results=$train->result_array();


 $curr_symbol=$this->session->userdata('sess_currsymbol');
 if($curr_symbol=='')
 {
 $curr_symbol="$";
 }
 

if($train->num_rows()==0)
		{			
							$data = array(
							'id' =>NULL,
							'room_id' => $param,
							'from_date' 		  => $this->input->post('from_date_show'),
							'to_date' 		  => $this->input->post('through_date_show'),
							'cost' => $this->input->post('daily_price'),
							'currency' 	=> $curr_symbol,
							'status' => $status
							);
							
							$this->Common_model->insertData('daily_pricing', $data);
							
		}
		else
		{
		
		

		$this->form_validation->set_rules('daily_price','Daily Price','required|is_natural_no_zero');
					if($this->form_validation->run())
					{	
							$data = array(
							'id' =>NULL,
							'room_id' => $param,
							'from_date' 		  => $this->input->post('from_date_show'),
							'to_date' 		  => $this->input->post('through_date_show'),
							'cost' => $this->input->post('daily_price'),
							'currency' 	=> $curr_symbol,
							'status' => $status
							);
							
						
					$this->session->set_userdata($data);
					$value = 0;
											
					foreach ($results as $arrival)
					{
					$from_date = $arrival['from_date'];
					$from_date1=strtotime($from_date);
					$to_date = $arrival['to_date'];
					$to_date1=strtotime($to_date);
					$cost = $arrival['cost'];
					
					
					
					$from=$this->input->post('from_date_show');
					$through=$this->input->post('through_date_show');

                      



//       1)	                  //from date is before db date and to_date is inbetween db dates   --done
				if(strtotime($from) <= $from_date1 && strtotime($through) < $to_date1 && strtotime($through) >= $from_date1)             
				{
				$value = 1;
				$q=1;
				$this->load->view('dailyprice_confirmation');
			
				
				}
				else
				{
				$q=0;
				}
				//	2)					//from date is inbetween two db dates and to_date is after db date.-------done
				if(strtotime($through) >= $to_date1 && strtotime($from) > $from_date1 && strtotime($from) <= $to_date1)             
				{
				$i=1; $value = 1;
		$this->load->view('dailyprice_confirmation');		
				
				}
				else
				{
				$i=0;
				}
						
//     3)					//start for gven date check in between 2 db dates --done
						if(strtotime($from)>$from_date1 && strtotime($through)<$to_date1)
						{
						$j=1; $value = 1;
						$this->load->view('dailyprice_confirmation');
						
			}
							else
							{
							$j=0;
							}
						
//      4)          //start for db dates is inbetween of two given dates--done
						if(strtotime($from) <= $from_date1 && strtotime($through) >= $to_date1)
						{	
						$p=1; $value = 1;
					$this->load->view('dailyprice_confirmation');

}
						else
						{
						$p=0;
						}
					   
//     5)		   //start for given dates front of the db dates or end of the db dates  --done
					    if((strtotime($from)<$from_date1 && strtotime($through)<$from_date1) || ( strtotime($from)>$to_date1 && strtotime($through)>$to_date1))
						{
							                                 //$list_id        = $param;
															 $k=1;
						}
						else
						{
						$k=0;
						}
						
						
}
if($k==1)
{
if ($value != 1) {
$this->Common_model->insertData('daily_pricing', $data);
}
}
}
						
				 }
			}
			
			$data['list']                 = $this->Common_model->getTableData('list', array('id' => $param))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $param))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			
			
			$data['message_element']      = 'rooms/view_edit_price1';
			$this->load->view('template', $data);
		
		}
		else
		{
			redirect('users/signin');
		}	
	}


public function dailyprice_confirm()
{

$room_id = $this->session->userdata('room_id');  
$from_date_form = $this->session->userdata('from_date');  
$to_date_form = $this->session->userdata('to_date');  
$cost = $this->session->userdata('cost');  
$currency = $this->session->userdata('currency');  
$status = $this->session->userdata('status');  

$data = array(
							'id' =>NULL,
							'room_id' => $room_id,
							'from_date' 		  => $from_date_form,
							'to_date' 		  => $to_date_form,
							'cost' => $cost,
							'currency' 	=> $currency,
							'status' => $status
							);

$train= $this->db->query("SELECT * FROM `daily_pricing` WHERE `room_id` = '".$room_id."'");

$results=$train->result_array();
foreach ($results as $arrival)
{
 $from_date = $arrival['from_date'];
 $from_date1=strtotime($from_date);
 $to_date = $arrival['to_date'];
 $to_date1=strtotime($to_date);
 $cost = $arrival['cost'];



$from=$from_date_form;
$through=$to_date_form;
			
			if(strtotime($from) <= $from_date1 && strtotime($through) < $to_date1 && strtotime($through) >= $from_date1)             
			{
			//which is big date is checking									
			
			$ghi =  $through;
			$adddate = strtotime ( '+1 day' , strtotime ( $ghi ) ) ;
			$adddate=date("m/d/Y", $adddate);
			
			$data4 = array(
			'from_date' => $adddate
			);
			
			$condition = array("from_date" => $from_date);
			
			$this->Common_model->updateTableData('daily_pricing', NULL, $condition, $data4);
			
			$q=1;
	
			}
			else
			{
			$q=0;
			}
			if(strtotime($through) >= $to_date1 && strtotime($from) > $from_date1 && strtotime($from) <= $to_date1)             
			{
			
			$ghi =  $from;
			$adddate = strtotime ( '-1 day' , strtotime ( $ghi ) ) ;
			$adddate=date("m/d/Y", $adddate);
			
			$data5 = array(
			'to_date' => $adddate
			);
			
			$condition = array("to_date" => $to_date);
			
			$this->Common_model->updateTableData('daily_pricing', NULL, $condition, $data5);
			
			$i=1;
			
			}
			else
			{
			$i=0;
			}
			
			if(strtotime($from)>$from_date1 && strtotime($through)<$to_date1)
			{
			$j=1;
			
			}
			else
			{
			$j=0;
			}
			
			if(strtotime($from) <= $from_date1 && strtotime($through) >= $to_date1)
			{	
			$condition = array("from_date" => $from_date, "to_date" => $to_date);
			$this->Common_model->deleteTableData('daily_pricing', $condition);	
			$p=1;
			
			}
			
			else
			{
			$p=0;
			}
			
			if((strtotime($from)<$from_date1 && strtotime($through)<$from_date1) || ( strtotime($from)>$to_date1 && strtotime($through)>$to_date1))
			{
			
			$k=1;
			
			}
			else
			{
			$k=0;
			}

			}
			
			if($p==1)
			{
			$this->Common_model->insertData('daily_pricing', $data);
			}
			
			if($q==1)
			{
			$this->Common_model->insertData('daily_pricing', $data);
			}
			if($k==1)
			{
			$this->Common_model->insertData('daily_pricing', $data);
			}
			if($i==1)
			{
			$this->Common_model->insertData('daily_pricing', $data);
			}
			if($j==1)
			{
			$abc =  $from_date_form;
			$newdate = strtotime ( '-1 day' , strtotime ( $abc ) ) ;
			$newdate=date("m/d/Y", $newdate);
			
			$data2 = array(
			'to_date' => $newdate
			);
			
			$condition = array("to_date" => $to_date);
			
			$this->Common_model->updateTableData('daily_pricing', NULL, $condition, $data2);
	
			$this->Common_model->insertData('daily_pricing', $data);
			
			$def=$to_date_form;
			$newdate1=strtotime ( '+1 day' , strtotime ( $def ) ) ;
			$newdate1=date("m/d/Y", $newdate1);
			
			$data3 = array(
			'id' =>NULL,
			'room_id' => $room_id ,
			'from_date' 		  => $newdate1,
			'to_date' 		  => $to_date,
			'cost' => $cost,
			'currency' 	=> $currency,
			'status' => $status
			);
			
			$this->Common_model->insertData('daily_pricing', $data3);
			
			}
			$data['list']                 = $this->Common_model->getTableData('list', array('id' => $room_id ))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $room_id ))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			
			
			redirect('rooms/edit_price1/'.$room_id);
			}

	public function weekly_price($param = '')
	{
	
	
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
		{	
		if($param == "")
		{
		redirect('info/deny');
		}
		
		$conditions             = array("id" => $param, "user_id" => $this->dx_auth->get_user_id());
		 $result                 = $this->Common_model->getTableData('list', $conditions);
		 	$p=0;

		$data['room_id']  = $param;
		$room1=$param;
		$status="Available";
		
			$this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;">', '</p>');

		 if($this->input->post())
			{

$train= $this->db->query("SELECT * FROM `weekly_pricing` WHERE `room_id` = '".$room1."'");

$results=$train->result_array();

 $curr_symbol=$this->session->userdata('sess_currsymbol');
 if($curr_symbol=='')
 {
 $curr_symbol="$";
 }
 
 
if($train->num_rows()==0)
		{			
							$data = array(
							'id' =>NULL,
							'room_id' => $param,
							'from_date' 		  => $this->input->post('from_date_show'),
							'to_date' 		  => $this->input->post('through_date_show'),
							'cost' => $this->input->post('daily_price'),
							'currency' 	=> $curr_symbol,
							'status' => $status
							);
							
							$this->Common_model->insertData('weekly_pricing', $data);
							
		}
		else
		{
		
		

		$this->form_validation->set_rules('daily_price','Daily Price','required|is_natural_no_zero');
					if($this->form_validation->run())
					{	
							$data = array(
							'id' =>NULL,
							'room_id' => $param,
							'from_date' 		  => $this->input->post('from_date_show'),
							'to_date' 		  => $this->input->post('through_date_show'),
							'cost' => $this->input->post('daily_price'),
							'currency' 	=> $curr_symbol,
							'status' => $status
							);
							
						
					$this->session->set_userdata($data);
					$value = 0;
											
					foreach ($results as $arrival)
					{
					$from_date = $arrival['from_date'];
					$from_date1=strtotime($from_date);
					$to_date = $arrival['to_date'];
					$to_date1=strtotime($to_date);
					$cost = $arrival['cost'];
					
					
					
					$from=$this->input->post('from_date_show');
					$through=$this->input->post('through_date_show');

//       1)	                  //from date is before db date and to_date is inbetween db dates   --done
				if(strtotime($from) <= $from_date1 && strtotime($through) < $to_date1 && strtotime($through) >= $from_date1)             
				{
				$q=1;
				$value =1;
				$this->load->view('weeklyprice_confirmation');
			
				
				}
				else
				{
				$q=0;
				}
				//	2)					//from date is inbetween two db dates and to_date is after db date.-------done
				if(strtotime($through) >= $to_date1 && strtotime($from) > $from_date1 && strtotime($from) <= $to_date1)             
				{
				$i=1;
				$value =1;
		$this->load->view('weeklyprice_confirmation');		
				
				}
				else
				{
				$i=0;
				}
						
//     3)					//start for gven date check in between 2 db dates --done
						if(strtotime($from)>$from_date1 && strtotime($through)<$to_date1)
						{
						$j=1; $value =1;
						$this->load->view('weeklyprice_confirmation');
						
			}
							else
							{
							$j=0;
							}
						
//      4)          //start for db dates is inbetween of two given dates--done
						if(strtotime($from) <= $from_date1 && strtotime($through) >= $to_date1)
						{	
						$p=1;
						$value =1;
					$this->load->view('weeklyprice_confirmation');

}
						else
						{
						$p=0;
						}
					   
//     5)		   //start for given dates front of the db dates or end of the db dates  --done
					    if((strtotime($from)<$from_date1 && strtotime($through)<$from_date1) || ( strtotime($from)>$to_date1 && strtotime($through)>$to_date1))
						{
							                                 //$list_id        = $param;
															 $k=1;
						}
						else
						{
						$k=0;
						}
						
						
}
if($k==1)
{

if ($value != 1) {
$this->Common_model->insertData('weekly_pricing', $data);

}
}
}
						
				 }
			}
			
			$data['list']                 = $this->Common_model->getTableData('list', array('id' => $param))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $param))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			
			
			$data['message_element']      = 'rooms/view_edit_price2';
			$this->load->view('template', $data);
		
		}
		else
		{
			redirect('users/signin');
		}	
	}
	
	public function weeklyprice_confirm ()
	{
$room_id = $this->session->userdata('room_id');  
$from_date_form = $this->session->userdata('from_date');  
$to_date_form = $this->session->userdata('to_date');  
$cost = $this->session->userdata('cost');  
$currency = $this->session->userdata('currency');  
$status = $this->session->userdata('status');  

$data = array(
							'id' =>NULL,
							'room_id' => $room_id,
							'from_date' 		  => $from_date_form,
							'to_date' 		  => $to_date_form,
							'cost' => $cost,
							'currency' 	=> $currency,
							'status' => $status
							);

$train= $this->db->query("SELECT * FROM `weekly_pricing` WHERE `room_id` = '".$room_id."'");

$results=$train->result_array();
foreach ($results as $arrival)
{
 $from_date = $arrival['from_date'];
 $from_date1=strtotime($from_date);
 $to_date = $arrival['to_date'];
 $to_date1=strtotime($to_date);
 $cost = $arrival['cost'];



$from=$from_date_form;
$through=$to_date_form;
			
			if(strtotime($from) <= $from_date1 && strtotime($through) < $to_date1 && strtotime($through) >= $from_date1)             
			{
			//which is big date is checking									
			
			$ghi =  $through;
			$adddate = strtotime ( '+1 day' , strtotime ( $ghi ) ) ;
			$adddate=date("m/d/Y", $adddate);
			
			$data4 = array(
			'from_date' => $adddate
			);
			
			$condition = array("from_date" => $from_date);
			
			$this->Common_model->updateTableData('weekly_pricing', NULL, $condition, $data4);
			
			$q=1;
			
			
			}
			else
			{
			$q=0;
			}
			if(strtotime($through) >= $to_date1 && strtotime($from) > $from_date1 && strtotime($from) <= $to_date1)             
			{
			
			$ghi =  $from;
			$adddate = strtotime ( '-1 day' , strtotime ( $ghi ) ) ;
			$adddate=date("m/d/Y", $adddate);
			
			$data5 = array(
			'to_date' => $adddate
			);
			
			$condition = array("to_date" => $to_date);
			
			$this->Common_model->updateTableData('weekly_pricing', NULL, $condition, $data5);
			
			$i=1;
			
			}
			else
			{
			$i=0;
			}
			
			if(strtotime($from)>$from_date1 && strtotime($through)<$to_date1)
			{
			$j=1;
			
			}
			else
			{
			$j=0;
			}
			
			if(strtotime($from) <= $from_date1 && strtotime($through) >= $to_date1)
			{	
			$condition = array("from_date" => $from_date, "to_date" => $to_date);
			$this->Common_model->deleteTableData('weekly_pricing', $condition);	
			$p=1;
			
			}
			
			else
			{
			$p=0;
			}
			
			if((strtotime($from)<$from_date1 && strtotime($through)<$from_date1) || ( strtotime($from)>$to_date1 && strtotime($through)>$to_date1))
			{
			
			$k=1;
			
			}
			else
			{
			$k=0;
			}
			
			}
			
			if($p==1)
			{
			$this->Common_model->insertData('weekly_pricing', $data);
			}
			
			if($q==1)
			{
			$this->Common_model->insertData('weekly_pricing', $data);
			}
			if($k==1)
			{
			$this->Common_model->insertData('weekly_pricing', $data);
			}
			if($i==1)
			{
			$this->Common_model->insertData('weekly_pricing', $data);
			}
			if($j==1)
			{
			$abc =  $from_date_form;
			$newdate = strtotime ( '-1 day' , strtotime ( $abc ) ) ;
			$newdate=date("m/d/Y", $newdate);
			
			$data2 = array(
			'to_date' => $newdate
			);
			
			$condition = array("to_date" => $to_date);
			
			$this->Common_model->updateTableData('weekly_pricing', NULL, $condition, $data2);

			$this->Common_model->insertData('weekly_pricing', $data);
			
			$def=$to_date_form;
			$newdate1=strtotime ( '+1 day' , strtotime ( $def ) ) ;
			$newdate1=date("m/d/Y", $newdate1);
			
			$data3 = array(
			'id' =>NULL,
			'room_id' => $room_id ,
			'from_date' 		  => $newdate1,
			'to_date' 		  => $to_date,
			'cost' => $cost,
			'currency' 	=> $currency,
			'status' => $status
			);
			
			$this->Common_model->insertData('weekly_pricing', $data3);
			
			}

			
			$data['list']                 = $this->Common_model->getTableData('list', array('id' => $room_id ))->row();
			$data['list_price']           = $this->Common_model->getTableData('price', array('id' => $room_id ))->row();
			
			$data['title']					           = get_meta_details('Edit_the_price_information_for_your_site','title');		
			$data["meta_keyword"]			      = get_meta_details('Edit_the_price_information_for_your_site','meta_keyword');
			$data["meta_description"]    	= get_meta_details('Edit_the_price_information_for_your_site','meta_description');			

			redirect('rooms/edit_price2/'.$room_id);
			
	
	
	}

	//Ajax Page
	public function calendar_tab_inner($param = '')
	{
	  if($param == '')
			{
			 exit('Access denied');
			}
			
			$day     = 1;
			$month   = $this->input->post('cal_month', TRUE);
			$year    = $this->input->post('cal_year', TRUE);
			
			$data['list_id']  = $param;
			$data['day']      = $day;
			$data['month']    = $month;
			$data['year']     = $year;
			
			$data['offset_time']     =  $this->input->post('offset', TRUE);;
			
			$conditions       = array('list_id' => $param);
			$data['result']   = $this->Trips_model->get_calendar($conditions)->result();
			
	  $this->load->view(THEME_FOLDER.'/rooms/view_calendar_tab',$data);
	}
	
		
	public function change_status()
	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
		{
			$sow_hide = $this->input->get('stat'); 
			$row_id   = $this->input->get('rid');
			
			if($sow_hide == 1)
			{
			 $condition      = array("id" => $row_id);
				$data['status'] = 0;
			 $this->Common_model->updateTableData('list', NULL, $condition, $data); 
    
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Status change successfully.')));
				redirect('listings');
			}
			else
			{
			 $condition            = array("id" => $row_id);
				$data['show_or_hide'] = 1;
				$this->Common_model->updateTableData('list', NULL, $condition, $data); 
				
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Status change successfully.')));
				redirect('listings');
			}
			
			$data['title']               = get_meta_details('Manage_Listings','title');
			$data["meta_keyword"]        = get_meta_details('Manage_Listings','meta_keyword');
			$data["meta_description"]    = get_meta_details('Manage_Listings','meta_description');
			
			$data['message_element']     = "hosting/view_hosting";
			$this->load->view('template',$data);
			
		}
		else
		{
		redirect('users/signin');
		}
 }

	public function change_availability($param = '')
	{
	if($param != '')
	{ 
	 $is_available = $this->input->post('is_available');
	 if($is_available == 0)
		{
  	echo 	'{"result":"unavailable","message":"Your listing will be hidden from public search results.","available":false,"prompt":"Your listing can now be activated!"}';
		}
		else
		{	
   echo	'{"result":"available","message":"Your listing will now appear in public search results.","available":true,"prompt":"Your listing is active."}';
		}
	}
		
	}
	
	public function deletelisting()
	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
		{
		$id = $this->uri->segment(3);
		//$id = $this->uri->segment(2);
		$where = "(`status` = '1' OR `status` = '3' OR `status` = '7' OR `status` = '8' OR `status` = '9')";
		$check = $this->db->where('list_id',$id)->where($where)->get('reservation');
		
		$check_contact = $this->db->where('list_id',$id)->where($where)->get('contacts');
		
		if($check->num_rows()!=0)
		{
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry, The selected listing is in process or resevered by someone')));
		redirect('listings/','refresh');
		}
		else if($check_contact->num_rows()!=0)
		{
	    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry, The selected listing is in process or resevered by someone')));
		redirect('listings/','refresh');
		}
		
		$list_data = $this->Common_model->getTableData('list',array('id'=>$id));
		
		if($list_data->num_rows() == 0)
		{
		 $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('The selected listing is already deleted.')));
		 redirect('listings/','refresh');
		}
				
		$title = $list_data->row()->title;
		
		$this->db->delete('list', array('id' => $id)); 
		$this->db->delete('price', array('id' => $id)); 
		$this->db->delete('amnities', array('id' => $id)); 
		$this->db->delete('messages',array('list_id'=>$id));
		$this->db->where('list_id',$id)->where($where)->delete('contacts');
		$this->db->where('list_id',$id)->where($where)->delete('reservation');
        // delete image folder directory
		$check_folder = $this->db->where('list_id',$id)->get('list_photo');
		if($check_folder->num_rows()!=0)
		{
		$this->db->delete('list_photo',array('list_id'=>$id));
		delete_files(APPPATH . '../images/'.$id.'',TRUE);
        rmdir(APPPATH . '../images/'.$id.'');
		}
        // delete image folder directory
				
		$query_user 	= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row();
		$username 		= $query_user->username;
		
		if($list_data->row()->is_enable == 1 && $list_data->row()->list_pay == 1)
		{
		
			$insertData = array(
			'list_id'         => $id,
			'conversation_id' => $id,
			'userby'          => $this->dx_auth->get_user_id(),
			'userto'          => 1,
			'message'         => "$username deleted a list, please check the mail for more details.",
			'created'         => time(),
			'message_type '   => 10
			);	
			
			$this->Message_model->sentMessage($insertData);
			
			$host_email = $query_user->email;
		
		$admin_email = $this->dx_auth->get_site_sadmin();
		$admin_name  = $this->dx_auth->get_site_title();
		
		$admin_to_email = $this->Common_model->getTableData('users',array('id' => 1))->row()->email;
					
		$email_name = 'list_delete_host';
		$splVars    = array("{host_name}"=>$username,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		$email_name = 'list_delete_admin';
		$splVars    = array("{host_name}"=>$username,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($admin_to_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		}
	
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Rooms deleted successfully.')));
		redirect('listings/','refresh');
		}
		else
		{
			redirect('users/signin');
		}
	}

	public function ajax_refresh_subtotal()
	{	
	  $id             = $this->input->get('hosting_id');
	  $this->session->unset_userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'");
	  $checkin        = $this->input->get('checkin');
	  $checkout       = $this->input->get('checkout');
	  $data['guests'] = $this->input->get('number_of_guests');
	  $capacity		= $this->Common_model->getTableData( 'list', array('id' => $id ) )->row()->capacity;	
		
		$ckin           = explode('/', $checkin);
		$ckout          = explode('/', $checkout);
	
		$xprice         = $this->Common_model->getTableData( 'price', array('id' => $id ) )->row();
		
		
		$guests         = $xprice->guests;
		$per_night      = $xprice->night;
		
		if(isset($xprice->cleaning))
		$cleaning       = $xprice->cleaning;
		else
		$cleaning       = 0;
		
		if(isset($xprice->security))
		$security       = $xprice->security;
		else
		$security       = 0;
		
		if(isset($xprice->night))
		$price          = $xprice->night;
		else
		$price          = 0;
		
		if(isset($xprice->week))
		$Wprice         = $xprice->week;	
		else
		$Wprice         = 0;
		
		if(isset($xprice->month))
		$Mprice         = $xprice->month;	
		else
		$Mprice         = 0;
		
		$guest_count = $xprice->guests;
				
		//check admin premium condition and apply so for
		$query         = $this->Common_model->getTableData( 'paymode', array('id' => 2) );
		$row           = $query->row();	
		
		//Seasonal Price
		//1. Store all the dates between checkin and checkout in an array		
			$checkin_time		= get_gmt_time(strtotime($checkin));
			$checkout_time		= get_gmt_time(strtotime($checkout));
			$travel_dates		= array();
			$seasonal_prices 	= array();		
			$total_nights		= 1;
			$total_price		= 0;
			$is_seasonal		= 0;
			$i					= $checkin_time;
			while($i<$checkout_time)
			{
				$checkin_date					= date('m/d/Y',$i);
				$checkin_date					= explode('/', $checkin_date);
				$travel_dates[$total_nights]	= $checkin_date[1].$checkin_date[0].$checkin_date[2];
				$i								= get_gmt_time(strtotime('+1 day',$i));
				$total_nights++; 
			}
			for($i=1;$i<$total_nights;$i++)
			{
				$seasonal_prices[$travel_dates[$i]]="";
			}
		//Store seasonal price of a list in an array
		$seasonal_query	= $this->Common_model->getTableData('seasonalprice',array('list_id' => $id));
		$seasonal_result= $seasonal_query->result_array();
		if($seasonal_query->num_rows()>0)
		{
			foreach($seasonal_result as $time)
			{
			
				//Get Seasonal price
				$seasonalprice_query	= $this->Common_model->getTableData('seasonalprice',array('list_id' => $id,'start_date' => $time['start_date'],'end_date' => $time['end_date']));
				$seasonalprice 			= $seasonalprice_query->row()->price;	
				//Days between start date and end date -> seasonal price	
				$start_time	= $time['start_date'];
				$end_time	= $time['end_date'];
				$i			= $start_time;
				while($i<=$end_time)
				{	
					$start_date					= date('m/d/Y',$i);
					$s_date						= explode('/',$start_date);	
					$s_date						= $s_date[1].$s_date[0].$s_date[2];
					$seasonal_prices[$s_date]	= $seasonalprice;
					$i							= get_gmt_time(strtotime('+1 day',$i));			
				}				
				
			}
			//Total Price
			for($i=1;$i<$total_nights;$i++)
			{
				if($seasonal_prices[$travel_dates[$i]] == "")	
				{	
					$total_price=$total_price+$xprice->night;
				}
				else 
				{
					$total_price= $total_price+$seasonal_prices[$travel_dates[$i]];
					$is_seasonal= 1;
				} 		
			}
			//Additional Guests
			if($data['guests'] > $guests)
			{
			  
			  $days = $total_nights-1;		
			  $diff_guests = $data['guests'] - $guests;
			  $total_price = $total_price + ($days * $xprice->addguests * $diff_guests);
			  $extra_guest = 1;
			  $extra_guest_price = $xprice->addguests*$diff_guests;
			}
			//Cleaning
			if($cleaning != 0)
			{
				$total_price = $total_price + $cleaning;
			}
			
			if($security != 0)
			{
				$total_price = $total_price + $security;
			}
			
			//Admin Commission
			$data['commission'] = 0;
			if($row->is_premium == 1)
			{
			   if($row->is_fixed == 1)
				{
					$fix                = $row->fixed_amount; 
					$amt                = $total_price + $fix;
					$data['commission'] = $fix;
				}
				else
				{  
					$per                = $row->percentage_amount; 
					$camt               = floatval(($total_price * $per) / 100);
					$amt                = $total_price + $camt;
					$data['commission'] = $camt;	
				}
			}
			
		}
		if($is_seasonal==1)
		{	
			//Total days
			$days 			= $total_nights;
			//Final price	
			$data['price'] 	= $total_price;						
		}	
	else
		{
		if(($ckin[0] == "mm" && $ckout[0] == "mm") or ($ckin[0] == "" && $ckout[0] == "") or ($checkin == 'Check in') or ($checkout == 'Check out'))
		{
		 	$days = 0;
			
   			$data['price']   = $price;
			
			if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
			
			$data['commission'] = 0;
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix                = $row->fixed_amount; 
										$amt                = $price + $fix;
										$data['commission'] = $fix;
										$Fprice             = $amt;
							}
							else
							{  
										$per                = $row->percentage_amount; 
										$camt               = floatval(($price * $per) / 100);
										$amt                = $price + $camt;
										$data['commission'] = $camt;
										$Fprice             = $amt;
							}
							
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}	
		   }
			} 
		else
		{	
			$diff = strtotime($ckout[2].'-'.$ckout[0].'-'.$ckout[1]) - strtotime($ckin[2].'-'.$ckin[0].'-'.$ckin[1]);
			$days = ceil($diff/(3600*24));

			$price = $price * $days;
			//Additional guests
			
			if($data['guests'] > $guests)
			{
			  	$diff_days = $data['guests'] - $guests;
			  	$price     = $price + ($days * $xprice->addguests * $diff_days);
				$extra_guest = 1;
				$extra_guest_price = $xprice->addguests*$diff_days;
			}
					
			if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
			$data['commission'] = 0;
			
			
			if($days >= 7 && $days < 30)
			{
			 if(!empty($Wprice))
				{
				  $finalAmount     = $Wprice;
						$differNights    = $days - 7;
						$perDay          = $Wprice / 7;
						$per_night       = round($perDay, 2);
						if($differNights > 0)
						{
						  $addAmount     = $differNights * $per_night;
								$finalAmount   = $Wprice + $addAmount;
						}
						$price           = $finalAmount;
						//Additional guests
						if($data['guests'] > $guests)
						{
			  				$diff_days = $data['guests'] - $guests;
			  				$price     = $price + ($days * $xprice->addguests * $diff_days);
							$extra_guest = 1;
							$extra_guest_price = $xprice->addguests*$diff_days;
						}
				}
			}
			
			
			if($days >= 30)
			{
			 if(!empty($Mprice))
				{
				  $finalAmount     = $Mprice;
						$differNights    = $days - 30;
						$perDay          = $Mprice / 30;
						$per_night       = round($perDay, 2);
						if($differNights > 0)
						{
						  $addAmount     = $differNights * $per_night;
								$finalAmount   = $Mprice + $addAmount;
						}
						$price           = $finalAmount;
						//Additional guests
						if($data['guests'] > $guests)
						{
			  				$diff_days = $data['guests'] - $guests;
			  				$price     = $price + ($days * $xprice->addguests * $diff_days);
							$extra_guest = 1;
							$extra_guest_price = $xprice->addguests*$diff_days;
						}
				}
			}	
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix                = $row->fixed_amount; 
										$amt                = $price + $fix;
										$data['commission'] = $fix;
										$Fprice             = $amt;
							}
							else
							{  
										$per                = $row->percentage_amount; 
										$camt               = floatval(($price * $per) / 100);
										$amt                = $price + $camt;
										$data['commission'] = $camt;
										$Fprice             = $amt;
							}
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
		   }
			
			
					
					$xprice         = $this->Common_model->getTableData( 'list', array('id' => $id ) )->row();
		
			
					if($cleaning != 0)
					{
					$price = $price + $cleaning;
					}	
					if($security != 0)
					{
					$price = $price + $security;
					}
			  			$data['price']    = $price;
					}
		}
			$query = $this->db->query("SELECT id,list_id FROM `calendar` WHERE `list_id` = '".$id."' AND (`booked_days` = '".get_gmt_time(strtotime($checkin))."' OR `booked_days` = '".get_gmt_time(strtotime($checkout))."') GROUP BY `list_id`");
			$rows  = $query->num_rows();
			$daysexist = $this->db->query("SELECT id,list_id,booked_days FROM `calendar` WHERE `list_id` = '".$id."' AND (`booked_days` >= '".get_gmt_time(strtotime($checkin))."' AND `booked_days` <= '".get_gmt_time(strtotime($checkout))."') GROUP BY `list_id`");
			
			$rowsexist = $daysexist->num_rows();

			if($rowsexist > 0)
			{
			  if(isset($extra_guest))
			  {
			  if($extra_guest == 1)
			  {
			  	echo '{"available":false,"extra_guest":1,"extra_guest_price":"'.get_currency_symbol($id).get_currency_value1($id,$extra_guest_price).'","total_price":'.$data['price'].',"reason_message":"Those dates are not available"}';
			  }
			  }
			  else {
				 echo '{"available":false,"total_price":'.$data['price'].',"reason_message":"Those dates are not available"}'; 
			  }
			}
			else if ($data['guests'] > $capacity)
			{
			  echo '{"available":false,"total_price":'.$data['price'].',"reason_message":"'.$capacity.' guest(s) only allowed"}';	
			}
			else
			{
			  $this->session->set_userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'",$data['price']);
			  $staggered_price = "";
					if($days >= 30)
					$staggered_price = ',"staggered_price":"'.get_currency_symbol($id).get_currency_value1($id,$data['price']).'","staggered":false';
				if(isset($extra_guest))
			  {	
			if($extra_guest == 1)
			  {
			  	 echo '{"service_fee":"'.get_currency_symbol($id).get_currency_value_lys($row->currency,get_currency_code(),$data['commission']).'","extra_guest_price":"'.get_currency_symbol($id).get_currency_value1($id,$extra_guest_price).'","extra_guest":1,"reason_message":"","price_per_night":"'.get_currency_symbol($id).get_currency_value1($id,$per_night).'","nights":'.$days.',"available":true,"can_instant_book":false,"total_price":"'.get_currency_symbol($id).get_currency_value1($id,$data['price']).'"'.$staggered_price.'}';
			  }	
			  }
			else
				{
				echo '{"service_fee":"'.get_currency_symbol($id).get_currency_value_lys($row->currency,get_currency_code(),$data['commission']).'","reason_message":"","price_per_night":"'.get_currency_symbol($id).get_currency_value1($id,$per_night).'","nights":'.$days.',"available":true,"can_instant_book":false,"total_price":"'.get_currency_symbol($id).get_currency_value1($id,$data['price']).'"'.$staggered_price.'}';
				}		
			}
	}
	
	public function sublet_available()
	{
	
	}
	
	public function ajax_contact()
	{
		$room_id       = $this->input->post('room_id');
		$message       = $this->input->post('message');
		
			//Send Message Notification To Host
			$insertData = array(
				'list_id'         => $room_id,
				'userby'          => $this->dx_auth->get_user_id(),
				'userto'          => get_list_by_id($room_id)->user_id,
				'message'         => $message,
				'created'         => local_to_gmt(),
				'message_type'    => 6
				);
				
			$this->Message_model->sentMessage($insertData, 1);
			
			echo 'Message send successfully';
	}
	
	public function upgrade_photo()
	{
			$this->path             = realpath(APPPATH . '../images');
			$this->gallery_path_url = base_url().'images/';
			$this->logopath         = realpath(APPPATH . '../');
			
			$result                 = $this->Common_model->getTableData('list');
			
			foreach($result->result() as $row)
			{
			 $id     = $row->id;
				if(is_dir($this->path.'/'.$id))
				{
					$files = scandir($this->path.'/'.$id);
					$files = array_diff($files, array('.','..'));
					
							$flag = 'true';
							foreach($files as $file)
							{
									if($file != 'Thumbs.db')
									{
										if($flag == 'true')
										{
										$insertData['is_featured'] = 1;
										}
										else
										{
										$insertData['is_featured'] = 0;
										}
										
										$insertData['list_id']    = $id;
										$insertData['name']       = $file;
										$insertData['created']    = local_to_gmt();
										$this->Common_model->insertData('list_photo', $insertData);
										$flag = 'false';
									}
							}
				}
			}
			
			echo "<p style='text-decoration:blink; font-size:18px; color:#339966;'> List photo's upgraded successfully. </p>";
			exit;			
	}
	



	
	public function convert()
	{
		
		 $amount = $this->input->post('amount');
		 $to = $this->input->post('to');
		 
		$id=$this->input->post('list_id');
		
		$price_table= $this->db->query("SELECT `currency` FROM `price` WHERE `id` = '".$id."'");
		$results=$price_table->result_array();
		foreach ($results as $curr)
		{
		 $currency = $curr['currency'];
		}
		$from=$currency;
		
		$ci =& get_instance();
		
		$string = "1".$from."=?".$to;
		
		$google_url = "http://www.google.com/ig/calculator?hl=en&q=".$string;
		
		$result = file_get_contents($google_url);
		
		$result = explode('"', $result);
		
		$converted_amount = explode(' ', $result[3]);
		$conversion = $converted_amount[0];
		$conversion = $conversion * $amount;
		$conversion = round($conversion, 2);
		
		$rhs_text = ucwords(str_replace($converted_amount[0],"",$result[3]));
		
		$rhs1=$conversion;
		
		
		$rhs = $conversion.$rhs_text;
		
		$google_lhs = explode(' ', $result[1]);
		$from_amount = $google_lhs[0];
		
		$from_text = ucwords(str_replace($from_amount,"",$result[1]));
		$lhs = $amount." ".$from_text;
		echo round($rhs1);
	}


	public function add_neighbor()
	{
			$id=$this->input->post('list_id');
			
			$price_table= $this->db->query("SELECT `neighbor` FROM `list` WHERE `id` = '".$id."'");
			$results=$price_table->result_array();
			foreach ($results as $neighbors)
			{
			 	$neighbor = $neighbors['neighbor'];
			}
			if($neighbor=="nothing select" || $neighbor=="No neighbor")
			{
			
				echo "please select neighbor place";
			
			}
			else
			{
				echo $neighbor;
			}
	}

	function entity_decode($string, $quote_style = ENT_COMPAT, $charset = "UTF-8") 
	{    
			 $string = html_entity_decode($string, $quote_style, $charset);
		
			 $string = preg_replace_callback('~&#x([0-9a-fA-F]+);~i', "chr_utf8_callback", $string);
			 $string = preg_replace('~&#([0-9]+);~e', 'chr_utf8("\\1")', $string);

			 return $string; 
	}

	function get_currency()
	{
	
		 $ci =& get_instance();
		 $currency_code = trim($this->input->post('currency'));
		 
		 $currency_symbol= $ci->Common_model->getTableData('currency', array('currency_code' => $currency_code))->row()->currency_symbol;
		 $currency_symbol1=	 html_entity_decode($currency_symbol, ENT_COMPAT, 'UTF-8');
		 echo $currency_symbol1;exit;
		 
	}
	
	public function change_currency()
	{
	 	$string_value  = $this->input->post('currency_code');
		$this->session->set_userdata('locale_currency',$string_value);
	}
	
function check_ban_user(){
	
	 if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in() ))
	{
		 echo "Banned user";exit;
	  }
}
	function getDistanceBetweenPointsNew($latitude1, $longitude1,
$latitude2, $longitude2, $unit = 'Mi')
{
   $theta = $longitude1 - $longitude2;
   $distance = (sin(deg2rad($latitude1)) *
   sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) *
   cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
   $distance = acos($distance);
   $distance = rad2deg($distance);
   $distance = $distance * 60 * 1.1515;
   switch($unit)
   {
      case 'Mi': break;
      case 'Km' : $distance = $distance *1.609344;
   }
   return (round($distance,2));
}
public function fb_friends_id($room_id)
{
	$fb_app_id = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
	$fb_app_secret = $this->db->get_where('settings', array('code' => 'SITE_FB_API_SECRET'))->row()->string_value;
	   $facebook = array(
            'appId'  => $fb_app_id,
            'secret' => $fb_app_secret,
            'cookie' => true
        );
        $this->load->library('facebook',$facebook);
		$user_id = $this->db->where('id',$room_id)->from('list')->get()->row()->user_id;
		$user = $this->db->where('id',$user_id)->from('users')->get()->row()->fb_id;
 // $user = '100006468578281';
 //return $user;exit;
if($user){
	try{
		//get the facebook friends list
	  $user_friends = $this->facebook->api('/'.$user.'/friends');
	  if($user_friends)
	  {
	  foreach($user_friends['data'] as $user_friend)
	{
	//echo $user_friend['id'];
	$result = $this->db->where('fb_id',$user_friend['id'])->from('users')->get();
	if($result->num_rows() != 0)
	{
		$fb_friends_id[] = $result->row()->id.',';
		//$friends = $this->db->where('fb_id',$user)->from('users')->get()->row()->friends;
		//$this->db->where('fb_id',$user)->set('friends',$friends.$result->row()->id)->update('users');
	}
    }
	if(isset($fb_friends_id))
	return $fb_friends_id;	
	else
	return false;
	}
	}
   catch(FacebookApiException $e){
		error_log($e);
		$user = NULL;
	}	
}
else {
	return false;
}

}

function currency_converter()
{
	$from = $this->input->post('from');
    $to = $this->input->post('to');
	$amount = $this->input->post('amount');
	//print_r($amount);
	
	echo round(get_currency_value_lys($from,$to,$amount));exit;
}

function security_price()
{
	extract($this->input->post());
	$data['security'] = $security_price;
	$this->db->where('id',$room_id)->update('price',$data);
	echo 'Success';exit;
}

function guest_count()
{
	extract($this->input->post());

	$data1['guests'] = $guest_count;
	$this->db->where('id',$room_id)->update('price',$data1);

	echo 'Success';exit;
}

function extra_guest_price()
{
	extract($this->input->post());

	$data1['addguests'] = $guest_price;
	$this->db->where('id',$room_id)->update('price',$data1);

	echo 'Success';exit;
}

function cancellation_policy()
{
	extract($this->input->post());

	$data1['cancellation_policy'] = $policy;
	
	$check_policy = $this->Common_model->getTableData('cancellation_policy',array('id'=>$policy));
	
	if($check_policy->num_rows() == 0)
	{
		echo 'failed';exit;
	}
	else
	{
	$this->db->where('id',$room_id)->update('list',$data1);
	echo 'Success';exit;
	}
}

function add_bed_type()
{
	extract($this->input->post());

	$data1['bed_type'] = $bed_type;
	$this->db->where('id',$room_id)->update('list',$data1);
	
	$data['bedtype'] = 1;
	$this->db->where('id',$room_id)->update('lys_status',$data);

	echo 'Success';exit;
}

function get_summary1()
{
	extract($this->input->post());

	$description = $this->db->where('id',$room_id)->get('list')->row()->desc;

	echo $description;exit;
}

function change_title_status()
{
	extract($this->input->post());
	  $list_status['is_enable'] = 0;
			   $list_status['list_pay'] = 0;
			   $this->db->where('id',$room_id)->update('list',$list_status);
	$title_status = $this->db->where('id',$room_id)->update('lys_status',array('title'=>0,'overview'=>0));

	echo 'success';exit;
}

function change_summary_status()
{
	extract($this->input->post());
	  $list_status['is_enable'] = 0;
			   $list_status['list_pay'] = 0;
			   $this->db->where('id',$room_id)->update('list',$list_status);
	$title_status = $this->db->where('id',$room_id)->update('lys_status',array('summary'=>0,'overview'=>0));

	echo 'success';exit;
}

public function add_my_shortlist()
	{
	if( (!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()) )
	{
		$this->session->set_userdata('redirect_to',base_url().'rooms/add_my_shortlist/'.$this->uri->segment(3));
		redirect('users/signin');
	}
	else 
	{	
		$list_id=$this->uri->segment(3);
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
redirect('account/mywishlist');
		$shortlist=$this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->shortlist;
		//Remove the selected list from the All short lists
		$result="";
		$my=explode(',',$shortlist);
		foreach($my as $list)
		{
			if($list != $list_id)
			{
			$result  .= $list.",";
			}
		}
			//Remove Comma from last character
			if((substr($result, -1)) == ',')
			$my_shortlist=substr_replace($result ,"",-1);
			else
			$my_shortlist= $result;
						
	  $data['title']            = get_meta_details('My Wishlist','title');
	  $data["meta_keyword"]     = get_meta_details('My Wishlist','meta_keyword');
 	  $data["meta_description"] = get_meta_details('My Wishlist','meta_description');
	  $data['message_element']  = "account/view_wishlist";
	  $this->load->view('template', $data);
	  }
	}
	
	function photo_count()
	{
		extract($this->input->post());
		
		$result = $this->db->where('list_id',$room_id)->get('list_photo');
		
		echo $result->num_rows();
	}
	
	public function delete_cal($param='')
	{	
	$condition = array("id" => $param);
	
	$list_id = $this->Common_model->getTableData('ical_import', $condition)->row()->list_id;
	
	$this->Common_model->deleteTableData('ical_import', $condition);
	
	$condition1 = array("booked_using" => $param);
	
	$this->Common_model->deleteTableData('calendar', $condition1);	
	
	 redirect('rooms/lys_next/edit/'.$list_id);
	}
	
	public function sync_cal($ical_id)
	{
	require_once("app/views/templates/blue/rooms/codebase/class.php");
	
	$exporter = new ICalExporter();
	
	$ical_urls = $this->db->where('id',$ical_id)->get('ical_import');
	
	if($ical_urls->num_rows() != 0)
	{
		foreach($ical_urls->result() as $row)
		{
			
		$ical_content = file_get_contents($row->url);
		
	$events = $exporter->toHash($ical_content);
	$success_num = 0;
	$error_num = 0;
	
	$id = $row->list_id;
	
	/*! inserting events in database */
	
	$check_tb = $this->db->select('group_id')->where('list_id',$id)->order_by('id','desc')->limit(1)->get('calendar');
	//$query = $this->db->last_query();
	//echo $query;exit;
	//print_r($check_tb->num_rows());exit;
	if($check_tb->num_rows() != 0)
	{
		$i1 = $check_tb->row()->group_id;
	}
	else {
		$i1 = 1;
	}
	
		
	for ($i = 1; $i <= count($events); $i++) 
	{
	$event = $events[$i];
	
	
	$days = (strtotime($event["end_date"]) - strtotime($event["start_date"])) / (60 * 60 * 24);
	$created=$event["start_date"];
	
	for($j=0;$j<=$days;$j++)
	{	
							if($days == 1)
							{
								$direct = 'single';
							}
							else if($days > 1)
							{

								if($j == 1)
								{
								$direct = 'left';
								}
								else if($days == $j)
								{
								$direct = 'right';
								}
								else
								{
								$direct= 'both';
								}
							}	

							
		$startdate1=$event["start_date"];
					
		$check_dates = $this->db->where('list_id',$id)->where('booked_days',strtotime($startdate1))->get('calendar');
		
		if($check_dates->num_rows() != 0)
		{
			$conflict = $i;
		}
		else { 
			
			$data = array(
							'id' =>NULL,
							'list_id' => $id,
							'group_id' => $i+$i1,
							'availability' 		  => "Booked",
							'value' 		  => 0,
							'currency' 	=> "EUR",
							'notes' => "Not Available",                      //   $event["text"]
							'style' 	=> $direct,
							'booked_using' 	=> 0,
							'booked_days' 	=>strtotime($startdate1),
							'created' 	=> strtotime($created)
				
							);
							
							$this->Common_model->insertData('calendar', $data);
				}
		
	//	if(isset($conflict))
	//	{
	//		$this->db->where('list_id',$id)->where('group_id',$conflict)->delete('calendar');
	//	}
	
						$abc =  $event["start_date"];
						$newdate = strtotime ( '+1 day' , strtotime ( $abc ) ) ;
						$event["start_date"]=date("m/d/Y", $newdate);
	}		
	
			$success_num++;
		
	}//for loop end
		$update_sync['last_sync'] = date('d M Y, g:i a',gmt_to_local(time(), get_user_timezone(), false));
		
		$this->db->where('id',$row->id)->update('ical_import',$update_sync);
	
	}
	}
   redirect('rooms/lys_next/edit/'.$ical_urls->row()->list_id);
	}

function get_data()
{
	extract($this->input->post());
	
	$result = $this->Common_model->getTableData('list',array('id'=>$list_id));
	
	foreach($result->result() as $row)
	{
		$conditions              = array('list_id' => $list_id,"is_featured"=>1);
	    $data['images']          = base_url().'images/'.$list_id.'/'.$this->Gallery->get_imagesG(NULL, $conditions)->row()->name; 
		$data['title'] = $row->title;
		$data['address'] = $row->address;
		$data['instance_book'] = $row->instance_book;
	}
	
	echo json_encode($data);
}

function wishlist_category()
{
	extract($this->input->get());
	
	$data['user_id'] = $this->dx_auth->get_user_id();
	$data['name'] = $name;
	$data['privacy'] = $privacy;
	$data['created'] = time();
	
	$data = $this->Common_model->inserTableData('wishlists',$data);
	
	echo $data;exit;
}

function wishlist_category_inner()
{
	extract($this->input->get());
	
	$data['user_id'] = $this->dx_auth->get_user_id();
	$data['name'] = $name;
	$data['privacy'] = $privacy;
	$data['created'] = time();
	
	$this->Common_model->inserTableData('wishlists',$data);
	
	$this->db->order_by('id','desc');
	$data1['wishlist_category'] = $this->Common_model->getTableData('wishlists',array('user_id'=>$this->dx_auth->get_user_id()));

	$this->load->view('templates/blue/account/view_wishlist_category',$data1);
}

function get_wishlist_category()
{
	extract($this->input->get());
	
	$this->db->order_by('id','desc');	
	$wishlist_category = $this->Common_model->getTableData('wishlists',array('user_id'=>$this->dx_auth->get_user_id()));
	
	$user_wishlist1 = $this->Common_model->getTableData('user_wishlist',array('user_id'=>$this->dx_auth->get_user_id(),'list_id'=>$list_id));
	
	$checked_wishlist = $this->db->where('user_wishlist.list_id',$list_id)->where('user_wishlist.user_id',$this->dx_auth->get_user_id())->join('user_wishlist','user_wishlist.wishlist_id = wishlists.id')->get('wishlists');
	$checked = '';
	echo ' <ul class="selectList list-unstyled">';
	if($wishlist_category->num_rows() != 0)
					{
						$j = 0;
						foreach($wishlist_category->result() as $category_wishlist)
						{
							$user_wishlist = $this->Common_model->getTableData('user_wishlist',array('user_id'=>$this->dx_auth->get_user_id(),'list_id'=>$list_id,'wishlist_id'=>$category_wishlist->id));
							
							if($user_wishlist->num_rows() != 0)
							{								
								$checked = 'checked';
							}
							else
							{
								$checked = '';
 							}
							
							if(isset($category_id))
							{
								if($category_wishlist->id == $category_id)
								{
									$checked = 'checked';
								}
							}

							echo '<li class="checked" data-wishlist-id="'.$category_wishlist->id.'">
  							  <label class="checkbox text-truncate">
   								<input type="checkbox" value="'.$category_wishlist->id.'" '.$checked.'>
    							<span id="'.$category_wishlist->id.'">'.$category_wishlist->name.'</span>
 							  </label>
							</li>';
						$j++;
						}
					}
					if($user_wishlist1->num_rows() != 0)
					{
					if($user_wishlist1->row()->wishlist_id == 0)
					{
						$checked = 'checked';
					}
					}
				echo '</ul>';
	
}

function add_user_wishlist()
{
	extract($this->input->get());
	
	$data['user_id'] = $this->dx_auth->get_user_id();
	$data['list_id'] = $list_id;
		
	$check_delete = $this->Common_model->getTableData('user_wishlist',$data);
		
    if($check_delete->num_rows() > $total_count)
	{
	$this->Common_model->deleteTableData('user_wishlist',$data);
	}
	
	$data['wishlist_id'] = $wishlist_id;
	
	$data['note'] = $note;
		
	$check = $this->Common_model->getTableData('user_wishlist',$data);
	
	if($check->num_rows() == 0)
	{
		
	$data1['user_id'] = $this->dx_auth->get_user_id();
	$data1['list_id'] = $list_id;
		
	$data1['wishlist_id'] = $wishlist_id;
		
	$check1 = $this->Common_model->getTableData('user_wishlist',$data1);
	
	if($check1->num_rows() == 0)
	{
	
	$data['note'] = $note;
	$data['created'] = time();
	
	$data_result = $this->Common_model->inserTableData('user_wishlist',$data);
	
	}
	else
	{
		$data_result1 = $this->Common_model->updateTableData('user_wishlist',NULL,$data1,array('note'=>$note));
	}

	}
	
	echo 'success';exit;
}

function add_user_wishlist_account()
{
	extract($this->input->get());
	
	$data['user_id'] = $this->dx_auth->get_user_id();
	$data['list_id'] = $list_id;
		
	$check_delete = $this->Common_model->getTableData('user_wishlist',$data);
		
    if($check_delete->num_rows() > $total_count)
	{
	$this->Common_model->deleteTableData('user_wishlist',$data);
	}
	
	$data['wishlist_id'] = $wishlist_id;
	
	$data['note'] = $note;
		
	$check = $this->Common_model->getTableData('user_wishlist',$data);
	
	if($check->num_rows() == 0)
	{
		
	$data1['user_id'] = $this->dx_auth->get_user_id();
	$data1['list_id'] = $list_id;
		
	$data1['wishlist_id'] = $wishlist_id;
		
	$check1 = $this->Common_model->getTableData('user_wishlist',$data1);
	
	if($check1->num_rows() == 0)
	{
	
	$data['note'] = $note;
	$data['created'] = time();
	
	$data_result = $this->Common_model->inserTableData('user_wishlist',$data);
	
	}
	else
	{
		$data_result1 = $this->Common_model->updateTableData('user_wishlist',NULL,$data1,array('note'=>$note));
	}

	}
	
	$param = $this->session->userdata('wishlist_id');
	
	$query = $this->db->where('user_wishlist.user_id',$this->dx_auth->get_user_id())->where('user_wishlist.wishlist_id',$param)->join('wishlists','wishlists.id = user_wishlist.wishlist_id')->join('list','list.id = user_wishlist.list_id')->join('property_type','property_type.id = list.property_id')->get('user_wishlist');
	
	$data['wishlists'] = $query;
		
	$locations = '';
	
	if($query->num_rows() != 0)
	{
		foreach($query->result() as $row)
		{
			$conditions              = array('list_id' => $row->list_id,"is_featured"=>1);
	        $data['images']          = base_url().'images/'.$row->list_id.'/'.$this->Gallery->get_imagesG(NULL, $conditions)->row()->name; 
		
			$locations .= '["'.$row->title.'",'.$row->lat.','.$row->long.',"'.$row->address.'","'.$data['images'].'"],';
		}
	}

    $locations = rtrim($locations, ",");
	
	$data['locations'] = $locations;
	
	$data['wishlist_name'] = $this->Common_model->getTableData('wishlists',array('id'=>$param))->row()->name;
		
	$data['wishlist_details'] = $this->Common_model->getTableData('wishlists',array('id'=>$param))->row();
	
	$this->load->view('templates/blue/account/view_wishlist_inner_ajax',$data);
}

function remove_user_wishlist()
{
	extract($this->input->get());
	
	$data['user_id'] = $this->dx_auth->get_user_id();
    $data['list_id'] = $list_id;
	
	$this->Common_model->deleteTableData('user_wishlist',$data);
	
	echo 'success';exit;
}

function remove_user_wishlist_account()
{
	extract($this->input->get());
	
	$data['user_id'] = $this->dx_auth->get_user_id();
    $data['list_id'] = $list_id;
	
	$this->Common_model->deleteTableData('user_wishlist',$data);
	
	$param = $this->session->userdata('wishlist_id');
	
	$query = $this->db->where('user_wishlist.user_id',$this->dx_auth->get_user_id())->where('user_wishlist.wishlist_id',$param)->join('wishlists','wishlists.id = user_wishlist.wishlist_id')->join('list','list.id = user_wishlist.list_id')->join('property_type','property_type.id = list.property_id')->get('user_wishlist');
	
	$data['wishlists'] = $query;
		
	$locations = '';
	
	if($query->num_rows() != 0)
	{
		foreach($query->result() as $row)
		{
			$conditions              = array('list_id' => $row->list_id,"is_featured"=>1);
	        $data['images']          = base_url().'images/'.$row->list_id.'/'.$this->Gallery->get_imagesG(NULL, $conditions)->row()->name; 
		
			$locations .= '["'.$row->title.'",'.$row->lat.','.$row->long.',"'.$row->address.'","'.$data['images'].'"],';
		}
	}

    $locations = rtrim($locations, ",");
	
	$data['locations'] = $locations;
	
	$data['wishlist_name'] = $this->Common_model->getTableData('wishlists',array('id'=>$param))->row()->name;
	
	$data['wishlist_details'] = $this->Common_model->getTableData('wishlists',array('id'=>$param))->row();
	
	$this->load->view('templates/blue/account/view_wishlist_inner_ajax',$data);
}

function remove_wishlist_inner()
{
	extract($this->input->get());
	
	$data['user_id'] = $this->dx_auth->get_user_id();
    $data['list_id'] = $list_id;
	$data['wishlist_id'] = $category_id;
	
	$this->Common_model->deleteTableData('user_wishlist',$data);
	
	echo 'success';exit;
}

function wishlist_title()
{
	extract($this->input->get());
	
	$data['user_id'] = $this->dx_auth->get_user_id();
	$data['list_id'] = $list_id; 
	
	$result = $this->Common_model->getTableData('user_wishlist',$data);
	
	if($result->num_rows() != 0)
	{
		if($result->num_rows() == 1)
		{
			
			$wishlist_name = $this->Common_model->getTableData('wishlists',array('id'=>$result->row()->wishlist_id))->row()->name;
			
			echo $wishlist_name;
		}
		else
		{
		   echo $result->num_rows().' Wish Lists';
		}
	}
	else
		{
			echo '0 Wish List';
		}
}

function wishlist_popup()
{
	extract($this->input->get());
	
	$this->db->order_by('id','desc');
	$data['wishlist_category'] = $this->Common_model->getTableData('wishlists',array('user_id'=>$this->dx_auth->get_user_id()));
	
	$data['user_wishlist'] = $this->Common_model->getTableData('user_wishlist',array('user_id'=>$this->dx_auth->get_user_id(),'list_id'=>$list_id));
	
	$data['user_wishlist1'] = $this->Common_model->getTableData('user_wishlist',array('user_id'=>$this->dx_auth->get_user_id(),'list_id'=>$list_id,"wishlist_id"=>0));
	
	$result = $this->Common_model->getTableData('list',array('id'=>$list_id));
	
	foreach($result->result() as $row)
	{
		$conditions              = array('list_id' => $list_id,"is_featured"=>1);
	    $images          = $this->Gallery->get_imagesG(NULL, $conditions);
		
		if($images->num_rows() != 0)
		{
			$data['images'] = base_url().'images/'.$list_id.'/'.$images->row()->name;
		}
		else
			{
				$data['images'] = base_url().'images/no_image.jpg';
			}
		 
		$data['title'] = $row->title;
		$data['address'] = $row->address;
	}
	
	$data['list_id'] = $list_id;
	
	if(isset($status))
	{
		$data['status'] = $status;
	}
	
	if(isset($status1))
	{
	$this->session->set_userdata('map_info',1);
	}	
	
	$this->load->view('templates/blue/rooms/view_wishlist_popup',$data);
}

function wishlist_note()
{
	extract($this->input->get());
	
	$data['list_id'] = $list_id;
	$data['wishlist_id'] = $wishlist_id;
	$data['user_id'] = $this->dx_auth->get_user_id();
	
	$update_data['note'] = $note;
	
	$this->Common_model->updateTableData('user_wishlist',NULL,$data,$update_data);

	echo $note;exit;
}

function delete_wishlist()
{
	extract($this->input->get());

	$data['id'] = $wishlist_id;
	
	$data1['wishlist_id'] = $wishlist_id;
	
	$this->Common_model->deleteTableData('wishlists',$data);
	
	$this->Common_model->deleteTableData('user_wishlist',$data1);
	
	echo 'success';exit;
}

function edit_wishlist()
{
	extract($this->input->get());

	$data1['id'] = $id;
	$data['name'] = $name;
	$data['privacy'] = $privacy;
	
	if($name != '')		
	$this->Common_model->updateTableData('wishlists',NULL,$data1,$data);
}
// Drag and Drop image upload 1 start


// Drag and Drop image upload 1 end



// Video grabber 1 start


// Video grabber 1 end

// spam listing 1 start	

	// spam listing 1 end	











}	

/* End of file rooms.php */
/* Location: ./app/controllers/rooms.php */ 
?>
