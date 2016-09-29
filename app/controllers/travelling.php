<?php
/**
 * DROPinn Travelling Controller Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Travelling
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Travelling extends CI_Controller {

	public function Travelling()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->helper('form');
		$this->load->helper('payment');
		
		$this->load->library('form_validation');
		$this->load->library('Pagination');
		
		$this->load->model('Users_model');
		$this->load->model('Trips_model');
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->facebook_lib->enable_debug(TRUE);
	}
	
	
 //Current Trips
	/* public function current_trip()
	{ 
		//if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
	   $cur_user_id              = $this->dx_auth->get_user_id();
    $conditions               = array("reservation.userby" => $cur_user_id, "reservation.status" => 7);
				$data['result']           = $this->Trips_model->get_reservation_trips($conditions);
				
				$data['title']            = get_meta_details('Your_Current_Trips','title');
				$data["meta_keyword"]     = get_meta_details('Your_Current_Trips','meta_keyword');
				$data["meta_description"] = get_meta_details('Your_Current_Trips','meta_description');
				
				$data['message_element']  = "travelling/view_current_trips";
				$this->load->view('template',$data);
		}
		else
		{
			redirect('users/signin');
		}
	}
	*/
	
	//Upcomming Trips
	public function your_trips()
	{ 
		//if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))	
		{
	   $cur_user_id              = $this->dx_auth->get_user_id();
       $conditions               = array("reservation.userby" => $cur_user_id);
				$conditions_in            = array(1,2,3,5,6,7,11,12,13);
				$query           = $this->Trips_model->get_reservation_trips($conditions, $conditions_in);
				
					$param = (int) $this->uri->segment(4,0);
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
		
		$this->db->limit($data['row_count'],$data['offset']);
		 $conditions               = array("reservation.userby" => $cur_user_id);
		 $conditions_in            = array(1,2,3,5,6,7,11,12,13);
		$data['result']           = $this->Trips_model->get_reservation_trips($conditions, $conditions_in);
				
			// Pagination config
		$p_config['base_url']    = site_url('travelling/your_trips/index');
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
				
				$data['title']            = get_meta_details('Your_trips','title');
				$data["meta_keyword"]     = get_meta_details('Your_trips','meta_keyword');
				$data["meta_description"] = get_meta_details('Your_trips','meta_description');
				
				$data['message_element']  = "travelling/view_upcomming_trips";
				$this->load->view('template',$data);
		}
		else
		{
			redirect('users/signin');
		}
		
	}
	
	
	//Previous Trips
	public function previous_trips()
	{ 
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
	   $cur_user_id              = $this->dx_auth->get_user_id();
    $conditions               = array("reservation.userby" => $cur_user_id, "reservation.status >=" => 8);
				$query          = $this->Trips_model->get_reservation_trips($conditions);
				
				
						$param = (int) $this->uri->segment(4,0);
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
		
		$this->db->limit($data['row_count'],$data['offset']);
		  $conditions               = array("reservation.userby" => $cur_user_id, "reservation.status >=" => 8);
		$data['result']           = $this->Trips_model->get_reservation_trips($conditions);
				
			// Pagination config
		$p_config['base_url']    = site_url('travelling/previous_trips/index');
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
				
				
				$data['title']            = get_meta_details('Your_Previous_Trips_Trips','title');
				$data["meta_keyword"]     = get_meta_details('Your_Previous_Trips_Trips','meta_keyword');
				$data["meta_description"] = get_meta_details('Your_Previous_Trips_Trips','meta_description');
				
				$data['message_element']  = "travelling/view_previous_trips";
				$this->load->view('template',$data);
		}
		else
		{
			redirect('users/signin');
		}
	}
	
	public	function host_details($param = '')
	{
			$data['title']            = get_meta_details('Host_Details','title');
			$data["meta_keyword"]     = get_meta_details('Host_Details','meta_keyword');
			$data["meta_description"] = get_meta_details('Host_Details','meta_description');
			
			$data['message_element']  = "travelling/view_host_details";
			$this->load->view('template',$data);
	}
	
	public	function billing($param = '')
 {
if(isset($param))

			{
			 $reservation_id     = $param;
				
				$conditions    				 = array('reservation.id' => $reservation_id, 'reservation.userby' => $this->dx_auth->get_user_id());
 			$result        				 = $this->Trips_model->get_reservation($conditions);
				
				if($result->num_rows() == 0)
				{
				  redirect('info');
				}
				
				$data['result'] 			= $result->row();
				$list_id       			 = $data['result']->list_id;
				$no_quest          = $data['no_quest'] = $data['result']->no_quest; 
				
				$x    												 = $this->Common_model->getTableData('price',array('id' => $list_id));
	  			$data['per_night'] = $price = $x->row()->night;
				
				$diff              = $data['result']->checkout - $data['result']->checkin;
				
				$data['nights']    = $days = ceil($diff/(3600*24));

		  		$amt=$data['subtotal']  = $result->row()->topay;
				$data['commission'] = 0;
								
				$data['policy'] = $this->Common_model->getTableData('cancellation_policy',array('id'=>$result->row()->policy))->row()->name;
				
				//check admin premium condition and apply so for
				$query              = $this->Common_model->getTableData('paymode', array( 'id' => 3));
				$row                = $query->row();
				
				$data['cleaning'] = $result->row()->cleaning;	
				$data['security'] = $result->row()->security;
				
				$guests = $result->row()->no_quest;
				
			/*	if($guests > $result->row()->guest_count)
		        {
		        	
				$diff_days          = $guests - $result->row()->guest_count;
				$data['extra_guest_price'] = $result->row()->extra_guest_price * $diff_days;
		        $data['per_night'] = $result->row()->topay-$result->row()->cleaning-$result->row()->security-$data['extra_guest_price'];
		        
				}  
				else 
				{
				$data['per_night'] = $result->row()->topay-$result->row()->cleaning-$result->row()->security;	
				} */
				 /* 
				 //*if($row->is_premium == 1)
				{
						if($row->is_fixed == 1)
						{
									$fix           = $row->fixed_amount; 
									$amt           = $amt - $fix;
									$data['commission'] = $amt ;
						}
						else
						{  
									$per           = $row->percentage_amount; 
									$camt          = floatval(($amt * $per) / 100);
									$amt           = $amt - $camt;
									$data['commission']  = $camt ;
						}
				}
				else
				{*/
				$amt                      = $amt;
			//	}
					
				$data['total_payout']     = $amt;
				
				$data['title']            = get_meta_details('Reservation_Request','title');
				$data["meta_keyword"]     = get_meta_details('Reservation_Request','meta_keyword');
				$data["meta_description"] = get_meta_details('Reservation_Request','meta_description'); 
			
				
				$data['message_element']  = 'trips/request_traveller';
				$this->load->view('template',$data);	
			}
			else
			{
			 redirect('info');
			}	
	}
	
	// Ajax page
	public function cancel_travel($params1 = '', $params2 = '', $params3 = '')
	{
		
		if($params1 != '')
		{
				
			$conditions    				= array('reservation.id' => $params1);
			$row           				= $this->Trips_model->get_reservation($conditions)->row();
		if($row->status == 12 || $row->status == 6)
		{		
			echo translate('This reservation is already cancelled by Guest.');exit;
		}
		else if($row->status == 5 || $row->status == 11)
		{
			echo translate('This reservation is already cancelled by Host.');exit;
		
		}
	
		}
		if($this->input->post('reservation_id'))
		{

		 $reservation_id    = $this->input->post('reservation_id');
			
			$admin_email 						= $this->dx_auth->get_site_sadmin();
			$admin_name  						= $this->dx_auth->get_site_title();
	
			$conditions    				= array('reservation.id' => $reservation_id);
			$row           				= $this->Trips_model->get_reservation($conditions)->row();
			
			$before_status = $row->status;
							
		    if($this->Users_model->get_user_by_id($row->userby))
			{
			$query1     				= $this->Users_model->get_user_by_id($row->userby);
				
			}
			else 
				{
					
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your List was deleted.')));
			redirect('travelling/your_trips');
						
				}
			
			$traveler_name 				= $query1->row()->username;
			$traveler_email 			= $query1->row()->email;
			
			$query2     						 = $this->Users_model->get_user_by_id($row->userto);
			$host_name 								= $query2->row()->username;
			$host_email 							= $query2->row()->email;
			
			$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
			
			$updateKey      										= array('id' => $reservation_id);
			$updateData               = array();
			
			$check_user = $this->db->where('user_id',$this->dx_auth->get_user_id())->where('id',$row->list_id)->get('list');
							
						
			if($check_user->num_rows() == 1)
			{
			if($row->status == 7)
				$updateData['status']  = 11;
				else
				$updateData['status']  = 5;
				
				if($row->status == 1)
				$updateData['status']  = 13;
								
				$host_cancellation = $this->Common_model->getTableData('host_cancellation_policy',array('id'=>1))->row();
				$check_free_cancellation_limit = $this->db->query("SELECT * FROM (`reservation`) WHERE `userto` = ".$this->dx_auth->get_user_id()." AND from_unixtime(cancel_date) >= DATE_SUB(CURRENT_TIMESTAMP(),INTERVAL ".$host_cancellation->months." MONTH) AND (`status` = 5  OR `status` = 11)");
							
				if($check_free_cancellation_limit->num_rows() >= $host_cancellation->free_cancellation)
				{
					$free_cancellation_limit = 0;
					$date1 = new DateTime(date('Y-m-d H:i:s'));
					$date2 = new DateTime(date('Y-m-d H:i:s',$row->checkin));
					$interval = $date1->diff($date2);
					
					if($interval->days < $host_cancellation->days)
					{
						$penalty_amount = $host_cancellation->before_amount;
					
					}
					else {
						
						$penalty_amount = $host_cancellation->after_amount;
							
					}
					
					$check_already_penalty = $this->Common_model->getTableData('host_cancellation_penalty',array('user_id'=>$this->dx_auth->get_user_id()));
					
					if($check_already_penalty->num_rows() != 0 )
					{
						$update_penalty['amount'] = $check_already_penalty->row()->amount+get_currency_value_lys($host_cancellation->currency,$check_already_penalty->row()->currency,$penalty_amount);
						$update_penalty_key['id'] = $check_already_penalty->row()->id;
						$this->Common_model->updateTableData('host_cancellation_penalty',0,$update_penalty_key,$update_penalty);
										}
					else
					{
						$insert_penalty['amount']  = $penalty_amount;
						$insert_penalty['user_id'] = $this->dx_auth->get_user_id();
						$insert_penalty['currency']  = $host_cancellation->currency;
						$this->Common_model->inserTableData('host_cancellation_penalty',$insert_penalty);	
						
					}
			
					$insertData = array(
					'list_id'         => $row->list_id,
					'reservation_id'  => $reservation_id,
					'userby'          => 1,
					'userto'          => $row->userto,
					'message'         => "You're cross the free cancellation limit. So, Admin will take $penalty_amount".$host_cancellation->currency." penalty amount from your next payout.",
					'created'         => local_to_gmt(),
					'message_type'    => 3
					);
						
						
					$this->Message_model->sentMessage($insertData, 1);
					
					$insertData = array(
					'list_id'         => $row->list_id,
					'reservation_id'  => $reservation_id,
					'userby'          => 1,
					'userto'          => 1,
					'message'         => "$host_name cross the free cancellation limit. So, You will earn $penalty_amount".$host_cancellation->currency." penalty amount from $host_name next payout.",
					'created'         => local_to_gmt(),
					'message_type'    => 3
					);
					$this->Message_model->sentMessage($insertData, 1);
			
				}
				else {
					
					$free_cancellation_limit = $host_cancellation->free_cancellation-$check_free_cancellation_limit->num_rows();
				}
				
				$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userto,
				'userto'          => $row->userby,
				'message'         => "Sorry, Your reservation is cancelled by $host_name for $list_title.",
				'created'         => local_to_gmt(),
				'message_type'    => 3
				);
			$this->Message_model->sentMessage($insertData, 1);
			
			}
			else
			{
					
						
			if($row->status == 7)
			$updateData['status']  = 12;
			else
			$updateData['status']  = 6;
			
			if($row->status == 1)
			$updateData['status']  = 13;
						
			$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userby,
				'userto'          => $row->userto,
				'message'         => "Sorry, Your list reservation is cancelled by $traveler_name for $list_title.",
				'created'         => local_to_gmt(),
				'message_type'    => 3
				);
			$this->Message_model->sentMessage($insertData, 1);
			
			}
					
			$updateData['cancel_date'] = time();
			refund($reservation_id); 
			
			$this->Trips_model->update_reservation($updateKey,$updateData);
			
			
			// payment_helper called for refund amount for host and guest as per host and guest cancellation policy
			
			
			 
		/*	$cancel_trip_referral_query = $this->db->select('cancel_trips_referral_code')->where('id',$row->userby)->get('users');
			if($cancel_trip_referral_query->num_rows()!=0)
			{
				if($cancel_trip_referral_query->row()->cancel_trips_referral_code != '')
				{
			 $this->db->set('trips_referral_code',$cancel_trip_referral_query->row()->cancel_trips_referral_code)->where('id',$row->userby)->update('users');
			 $this->db->set('cancel_trips_referral_code','')->where('id',$row->userby)->update('users');
			 $referral_by = $this->db->where('referral_code',$cancel_trip_referral_query->row()->cancel_trips_referral_code)->get('users');
				if($referral_by->num_rows()!=0)
				{
					$referral_amount = $referral_by->row()->referral_amount-25;
					$this->db->set('referral_amount',$referral_amount)->where('referral_code',$cancel_trip_referral_query->row()->cancel_trips_referral_code)->update('users');
				}
				}
			}
			
			$cancel_list_referral_query = $this->db->select('cancel_list_referral_code')->where('id',$row->userto)->get('users');
			if($cancel_list_referral_query->num_rows()!=0)
			{
				if($cancel_list_referral_query->row()->cancel_list_referral_code != '')
				{
			 $this->db->set('list_referral_code',$cancel_list_referral_query->row()->cancel_list_referral_code)->where('id',$row->userto)->update('users');
			 $this->db->set('cancel_list_referral_code','')->where('id',$row->userto)->update('users');
				 $referral_by = $this->db->where('referral_code',$cancel_list_referral_query->row()->cancel_list_referral_code)->get('users');
				if($referral_by->num_rows()!=0)
				{
					$referral_amount = $referral_by->row()->referral_amount-75;
					$this->db->set('referral_amount',$referral_amount)->where('referral_code',$cancel_list_referral_query->row()->cancel_list_referral_code)->update('users');
				}
                }
			}
		*/
		
		$comment = $this->input->post('comment');
		
		$this->db->where('list_id',$row->list_id)->where('booked_days >=',$row->checkin)->where('booked_days <=',$row->checkout)->delete('calendar');
		
		if($row->status == 3)
		{
		$status = 'Confirmed';
		}
		else if($row->status == 7)
		{
			$status = 'After Checkin';
		}
		else {
			$status = 'Pending';
		} 
		
		if($updateData['status ']  == 5 || $updateData['status ']  == 11)
		{
			
		if(isset($penalty_amount))
		{
			$penalty = 'From your next payout we will take '.$penalty_amount.$host_cancellation->currency.' penalty amount.';
			$admin_penalty = 'From '.$host_name.' next payout You will earn '.$penalty_amount.$host_cancellation->currency.' penalty amount.';
		}
		else
		{
			$penalty = '';
			$admin_penalty = '';
		}
		
		//if(isset($free_cancellation_limit))
		//{
			$free_cancellation = 'Remaining Free cancellation: '.$free_cancellation_limit;
		//}
				
		//Send Mail To Host
		$email_name = 'host_reservation_cancel';
		$splVars    = array("{penalty}"=>$penalty,"{cancellation_limit}"=>$free_cancellation,"{site_name}" => $this->dx_auth->get_site_title(),"{status}" => $status, "{comment}" => $comment, "{traveler_name}" => ucfirst($host_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($traveler_name));
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		

	//Send Mail To Traveller
		$email_name = 'traveler_reservation_cancel';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{user_type}" => 'Host',"{status}" => $status, "{comment}" => $comment, "{traveler_name}" => ucfirst($host_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($traveler_name));
		$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
	
	//Send Mail To Administrator
		$email_name = 'admin_reservation_cancel';
		$splVars    = array("{penalty}"=>$admin_penalty,"{site_name}" => $this->dx_auth->get_site_title(),"{status}" => $status, "{traveler_name}" => ucfirst($host_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($traveler_name));
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);	
		
        }
else {
		$admin_penalty = '';
		$free_cancellation = '';
        //Send Mail To Host
		$email_name = 'host_reservation_cancel';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{status}" => $status, "{comment}" => $comment, "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
				
		//Send Mail To Traveller
		$email_name = 'traveler_reservation_cancel';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{user_type}" => 'Guest', "{status}" => $status, "{comment}" => $comment, "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		//Send Mail To Administrator
		$email_name = 'admin_reservation_cancel';
		$splVars    = array("{penalty}"=>$admin_penalty,"{site_name}" => $this->dx_auth->get_site_title(),"{status}" => $status, "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);	
}

		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('You are successfully cancelled the trip.')));
		
		if($params3 == '')
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Reservation Cancelled Successfully.')));
			redirect('travelling/your_trips');
		}
else {
	$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Reservation Cancelled Successfully.')));
	redirect('listings/my_reservation');
}
        }
		else
		{
				
			$conditions    				     = array('reservation.id' => $params1);
			$row           				     = $this->Trips_model->get_reservation($conditions)->row();
			
			if($this->Trips_model->get_reservation($conditions)->num_rows() == 0)
			{
				redirect('info');
			}
			
			$check_user = $this->db->where('user_id',$this->dx_auth->get_user_id())->where('id',$row->list_id)->get('list');
							
			if($check_user->num_rows() == 1)
			{
				$data['user_type'] = 'host';
				
			}
			else
				{
				$data['user_type'] = 'guest';	
				}	
			
			$checkin  									     = $row->checkin;
		    $checkout 									     = $row->checkout;
			
			$diff1 												     = $checkout - $checkin;
			$days1 												     = ceil($diff1/(3600*24));
			
			$diff2 												     = local_to_gmt() - $checkin;
			$days2 												     = ceil($diff2/(3600*24));
			
			$policy = $this->Common_model->getTableData('cancellation_policy',array('id'=>$row->policy))->row();
	  		$data['policy_name'] = $policy->name;
			
			$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
			$date2 = new DateTime(date('Y-m-d H:i:s'));
			$interval = $date1->diff($date2);
			
			if($row->status != 7)
			{
				
			if($policy->list_before_status == 0)
			{
				$data['nights'] = 0;
				$data['non_nights'] = ceil($diff1/(3600*24));
			}
			else
			{
				if($policy->list_before_days <= $interval->days)
				{
				  $data['nights']    = $days = $interval->days;
				  $data['non_nights'] = 0;
				}
				else
				{
				  $data['nights']    = $days = ceil($diff1/(3600*24))-$policy->list_non_refundable_nights;
				  if($policy->list_non_refundable_nights > ceil($diff1/(3600*24)))
				  {
				  	$data['non_nights'] = ceil($diff1/(3600*24));
				  }
				  else
				  {
				  	$data['non_nights'] = $policy->list_non_refundable_nights;	
				  }
				}
				
			}
			}
			else if($row->status == 7)
			{
				
			}
					
			$data['reservation_id'] = $params1;
			$data['list_id']        = $params2;
			$this->load->view(THEME_FOLDER.'/travelling/view_cancel_travel',$data);
		}
	}
	
	//Ajax Page
	public function checkin($param = '')
	{
		if($this->input->post())
		{
	 	$reservation_id               = $this->input->post('reservation_id');
			$updateKey      		  = array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 7;
			$this->Trips_model->update_reservation($updateKey,$updateData);
			/*if(!$this->Trips_model->update_reservation($updateKey,$updateData))
			{
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('You are successfully checked in. Enjoy the trip.')));
			redirect('travelling/your_trips');
			}
			else 
			{
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your List was deleted.')));	
			redirect('travelling/your_trips');
			}*/
			
			// payment_helper called for refund amount for host and guest as per host and guest cancellation policy
			
			refund($reservation_id); 
			
			$conditions    				= array('reservation.id' => $reservation_id);
			$row           				= $this->Trips_model->get_reservation($conditions)->row();
			
			 if($this->Users_model->get_user_by_id($row->userby))
			{
			$query1     				= $this->Users_model->get_user_by_id($row->userby);
			}
			else 
		    {	
			    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your List was deleted.')));
			    redirect('travelling/your_trips');
		    }
			
			$traveler_name 				= $query1->row()->username;
			$traveler_email 			= $query1->row()->email;
			
			$query2     						 = $this->Users_model->get_user_by_id($row->userto);
			$host_name 								= $query2->row()->username;
			$host_email 							= $query2->row()->email;
			
			$admin_email 						= $this->dx_auth->get_site_sadmin();
			$admin_name  						= $this->dx_auth->get_site_title();
			
			$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
						
			$username = ucfirst($this->dx_auth->get_username());
			
			$checkin  = date('m/d/Y', $row->checkin);
			$checkout = date('m/d/Y', $row->checkout);	
			$price    = $row->price;
			
			$currency = $this->db->where('currency_code',$row->currency)->get('currency')->row()->currency_symbol;
			
			$conversation = $this->db->where('userto',$row->userto)->where('userby',$row->userby)->order_by('id','desc')->get('messages');
			
			if($conversation->num_rows() != 0)
			{
				foreach($conversation->result() as $row3)
				{
					if($row3->conversation_id != 0)
					{
						$conversation_id = $row3->conversation_id;
				    }
					else 
						{
					$conversation1 = $this->db->where('userto',$row->userby)->where('userby',$row->userto)->order_by('id','desc')->get('messages');
				
				if($conversation1->num_rows() != 0)
			{
				foreach($conversation1->result() as $row2)
				{
					if($row2->conversation_id != 0)
					{
						$conversation_id = $row2->conversation_id;
					}
				}
			}
				else
					{
						$conversation_id = 0;
					}
				}
			}
			}
			else {
				$conversation1 = $this->db->where('userto',$row->userby)->where('userby',$row->userto)->order_by('id','desc')->get('messages');
				
				if($conversation1->num_rows() != 0)
			{
				foreach($conversation1->result() as $row1)
				{
					if($row1->conversation_id != 0)
					{
						$conversation_id = $row1->conversation_id;
					}
				}
			}
				else
					{
						$conversation_id = 0;
					}
			}
			
			if(!isset($conversation_id))
			{
				$insertData = array(
			'list_id'         => $row->list_id,
			'reservation_id'  => $reservation_id,
			'userby'          => $row->userby,
			'userto'          => $row->userto,
			'message'         => "$username checkin to $list_title.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			
			}
			else {
				$insertData = array(
			'list_id'         => $row->list_id,
			'reservation_id'  => $reservation_id,
			'conversation_id'  => $conversation_id,
			'userby'          => $row->userby,
			'userto'          => $row->userto,
			'message'         => "$username checkin to $list_title.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			}
			
			if($row->list_id)
			{			
		$this->Message_model->sentMessage($insertData);
		
		//Send Mail To Traveller
		$email_name = 'checkin_traveller';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{currency}"=>$currency,"{traveler_email}"=>$traveler_email,"{host_email}"=>$host_email,"{traveler_name}" => ucfirst($traveler_name),"{checkin}"=>$checkin,"{checkout}"=>$checkout,"{price}"=>$price, "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
		
	
		//Send Mail To Host
		$email_name = 'checkin_host';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{currency}"=>$currency,"{traveler_email}"=>$traveler_email,"{host_email}"=>$host_email,"{traveler_name}" => ucfirst($traveler_name),"{checkin}"=>$checkin,"{checkout}"=>$checkout,"{price}"=>$price, "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		
		//Send Mail To Administrator
		$admin_to_email = $this->db->where('id',1)->get('users')->row()->email;
		$email_name = 'checkin_admin';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{currency}"=>$currency,"{traveler_email}"=>$traveler_email,"{host_email}"=>$host_email,"{traveler_name}" => ucfirst($traveler_name),"{checkin}"=>$checkin,"{checkout}"=>$checkout,"{price}"=>$price, "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($admin_to_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);

		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('You are successfully checkin.')));	
		redirect('travelling/your_trips'); 
			}
			else {
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your List was deleted.')));	
		redirect('travelling/your_trips');
			}
		}
		
	 $data['reservation_id'] = $param;
	 $this->load->view(THEME_FOLDER.'/travelling/view_checkin',$data);
	}
	
	// Ajax Page
	public function checkout($param = '')
	{
		if($this->input->post())
		{
	 	$reservation_id           = $this->input->post('reservation_id');
		$admin_email 						= $this->dx_auth->get_site_sadmin();
			$admin_name  						= $this->dx_auth->get_site_title();
	
			$conditions    				= array('reservation.id' => $reservation_id);
			$row           				= $this->Trips_model->get_reservation($conditions)->row();
			
			
			$updateKey      										= array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 8;
			$this->Trips_model->update_reservation($updateKey,$updateData);
			
			$conditions    				= array('reservation.id' => $reservation_id);
	 		$row           				= $this->Trips_model->get_reservation($conditions)->row();
			$before_status = $row->status;
									
		    if($this->Users_model->get_user_by_id($row->userby))
			{
			$query1     				= $this->Users_model->get_user_by_id($row->userby);
			}
			else 
				{	
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your List was deleted.')));
			redirect('travelling/your_trips');
				}
			
			$traveler_name 				= $query1->row()->username;
			$traveler_email 			= $query1->row()->email;
			
			$query2     						 = $this->Users_model->get_user_by_id($row->userto);
			$host_name 								= $query2->row()->username;
			$host_email 							= $query2->row()->email;
			
			$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
						
			$username = ucfirst($this->dx_auth->get_username());
			
			if($row->list_id)
			{

			$insertData = array(
			'list_id'         => $row->list_id,
			'reservation_id'  => $reservation_id,
			'userby'          => $row->userby,
			'userto'          => $row->userto,
			'message'         => "$username wants the review from you.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 4
			);			
			
		$this->Message_model->sentMessage($insertData);
				
		//Send Mail To Traveller
		$email_name = 'checkout_traveler';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
				
		//Send Mail To Host
		$email_name = 'checkout_host';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		//Send mail to host about review
					$host_id =$this->Common_model->getTableData('reservation',array('id'=>$reservation_id))->row()->userto;
					$query=	$this->Common_model->getTableData('user_notification',array('user_id' => $host_id));
   					$result=$query->row();
 					echo $notify=$result->leave_review;
					
					
	if($notify==1)
	{
		 $username =$this->dx_auth->get_username();
		$list_id =$this->Common_model->getTableData('reservation',array('id'=>$reservation_id))->row()->list_id;
		 $host_id =$this->Common_model->getTableData('reservation',array('id'=>$reservation_id))->row()->userto;
		 $list_name =$this->Common_model->getTableData('list',array('id'=>$list_id))->row()->title;
		$host_name = $this->Common_model->getTableData('users',array('id'=>$host_id))->row()->username;
		 $host_email = $this->Common_model->getTableData('users',array('id'=>$host_id))->row()->email;
		 $guest_name =$this->Common_model->getTableData('users',array('id'=>$row->userby))->row()->username;
		 $admin_email = $this->Common_model->getTableData('users',array('id'=>1))->row()->email;
		 $admin_name  = $this->dx_auth->get_site_title();
		
		$email_name = 'guest_notify_review';
		$splVars    = array("{host_name}"=>$host_name,"{site_name}" => $this->dx_auth->get_site_title(), "{guest_name}" => $guest_name, "{list_name}" => $list_name, '{email_id}' => $host_email);
				
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);	
		
		
	}
		 
		
		//Send Mail To Administrator
		$email_name = 'checkout_admin';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('You are successfully checked out.')));	
		redirect('travelling/previous_trips'); 
			}
			else {
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your List was deleted.')));	
		redirect('travelling/previous_trips');
			}
		
		}
		
	 $data['reservation_id'] = $param;
	 $this->load->view(THEME_FOLDER.'/travelling/view_checkout',$data);
	}

}

/* End of file travelling.php */
/* Location: ./app/controllers/travelling.php */
?>
