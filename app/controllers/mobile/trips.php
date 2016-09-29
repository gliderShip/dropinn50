<?php
/**
 * DROPinn User Controller Class
 *
 * helps to achieve common tasks related to the site for mobile app like android and iphone.
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	User
 * @author		Cogzidel Product Team
 * @version		Version 1.0
 * @link		http://www.cogzidel.com
 
 */
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trips extends CI_Controller {

	public function Trips()
	{
		parent::__construct();
		
		$this->load->helper('url');
		
		$this->load->library('DX_Auth');  

		$this->load->model('Users_model');
		$this->load->model('Gallery');
		$this->load->model('Trips_model');
		$this->load->model('Message_model');
		$this->_table = 'users';
	}
	
	public function index()
	{
	}
	
	public	function request()
 {
     extract($this->input->get());
				
			 $conditions= array('reservation.id' => $reservation_id, 'reservation.userto' => $user_id);
 			 
 			 $result        				 = $this->Trips_model->get_reservation($conditions);
				
				if($result->num_rows() == 0)
				{
				  echo '[{"status":"Access denied"}]';exit;
				}
				
				$data1['result'] 			= $result->row();
				
				$list_id       			 = $data1['result']->list_id;
				$no_quest          = $data['no_quest'] = $data1['result']->no_quest; 
				
				$x  = $this->Common_model->getTableData('price',array('id' => $list_id));
	  			
				$data['per_night'] = $x->row()->night;
				
				$diff              = $data1['result']->checkout - $data1['result']->checkin;
	  			$data['nights']    = $days = ceil($diff/(3600*24));
		  		$amt=$data['subtotal']  = $result->row()->topay;
				
				$data['cleaning'] = $result->row()->cleaning;	
				$data['security'] = $result->row()->security;
				
				$guests = $result->row()->no_quest;
				
				$data['commission'] = 0;
				
				//check admin premium condition and apply so for
				$query              = $this->Common_model->getTableData('paymode', array( 'id' => 2));
				$row                = $query->row();
					
				$data['total_payout']     = $amt;
				$data['subtotal']     = $amt;
				
				$data['policy'] = $this->db->where('id',$list_id)->get('list')->row()->cancellation_policy;
				//$data['policy'] = $this->Common_model->getTableData('cancellation_policy',array('id'=>$result->row()->policy))->row()->name;
				if($data['policy'] == '')
				{
					$data['policy'] == 'No policy';
				}
				
				$data['list_id'] = $data1['result']->list_id;
				
				$data['list_title'] = $this->db->where('id',$data1['result']->list_id)->get('list')->row()->title;
				
				$data['checkin'] = $data1['result']->checkin;
				
				$data['checkout'] = $data1['result']->checkout;
				
				$data['reservation_status'] = $this->db->where('id',$data1['result']->status)->get('reservation_status')->row()->name;
				
				echo '['.json_encode($data).']';			
	} 
	
	function accept()
	{
		extract($this->input->get());
				
	 $admin_email 						= $this->dx_auth->get_site_sadmin();
	 $admin_name  						= $this->dx_auth->get_site_title();
	
		$conditions    				= array('reservation.id' => $reservation_id);
		$row           				= $this->Trips_model->get_reservation($conditions)->row();
		
		if($this->Trips_model->get_reservation($conditions)->num_rows() == 0)
		{
			echo '[{"status":"Please give valid reservation id"}]';exit;
		}
		
		if($row->status != 1)
		{
			echo '[{"status":"Sorry! this reservation request not in Pending status"}]';exit;
		}
				
		$query1 = $this->db->where('id',$row->userby)->get('users');
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;
		
		$query2 = $this->db->where('id',$row->userto)->get('users');
		$host_name 								= $query2->row()->username;
		$host_email 							= $query2->row()->email;
		
		$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
		
		$traveler          = $this->Common_model->getTableData('profiles', array('id' => $row->userby))->row();
		$host             	= $this->Common_model->getTableData('profiles', array('id' => $row->userto))->row();
		
		//Traveller Info
		if(!empty($traveler ))
		{
		$FnameT												=	$traveler->Fname;
		$LnameT												= $traveler->Lname;
		$liveT													= $traveler->live;
		$phnumT 											= $traveler->phnum;
		}
		else
		{
		$FnameT												=	'';
		$LnameT												= '';
		$liveT													= '';
		$phnumT 											= '';
		}
		
		//Host Info
		if(!empty($host ))
		{
		$FnameH												=	$host->Fname;
		$LnameH												= $host->Lname;
		$liveH													= $host->live;
		$phnumH 											= $host->phnum;
		}
		else
		{
		$FnameH												=	'';
		$LnameH												= '';
		$liveH													= '';
		$phnumH 											= '';
		}
	
		//for calendar
		if($is_block == 'on')
		{
				$this->db->select_max('group_id');
				$group_id                   = $this->db->get('calendar')->row()->group_id;
				
				if(empty($group_id)) echo $countJ = 0; else $countJ = $group_id;
				
				$insertData['list_id']      = $row->list_id;
				$insertData['group_id']     = $countJ + 1;
				$insertData['availability'] = 'Booked';
				$insertData['booked_using'] = 'Other';
				
					$checkin  = date('m/d/Y', $checkin);
					$checkout = date('m/d/Y', $checkout);
					$days     = getDaysInBetween($checkin, $checkout);
		
					$count = count($days);
					$i = 1;
					foreach ($days as $val)
					{
						if($count == 1)
						{
							$insertData['style'] = 'single';
						}
						else if($count > 1)
						{
							if($i == 1)
							{
							$insertData['style'] = 'left';
							}
							else if($count == $i)
							{
							$insertData['notes'] = '';
							$insertData['style'] = 'right';
							}
							else
							{
							$insertData['notes'] = '';
							$insertData['style'] = 'both';
							}
						}	
					$insertData['booked_days'] = $val;
					$this->Trips_model->insert_calendar($insertData);				
					$i++;
					}
			}
	           $list_data = $this->db->where('id',$row->list_id)->get('list')->row();
			 //Send Message Notification To Traveler
			$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userto,
				'userto'          => $row->userby,
				'message'         => "Congratulation, Your reservation request is granted by $host_name for $list_title.",
				'created'         => local_to_gmt(),
				'message_type'    => 3
				);
			$this->Message_model->sentMessage($insertData, 1);

           $referral_code_check1 = $this->db->where('id',$row->userto)->get('users');
		   
           if($referral_code_check1->row()->list_referral_code)
		   {
		   	 $own_referral_code = $referral_code_check1->row()->list_referral_code;
			 $referral_code_check2 = $this->db->where('referral_code',$own_referral_code)->get('users');
			 if($referral_code_check2->num_rows()!=0)
			 {
			 	$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userto,
				'userto'          => $referral_code_check2->row()->id,
				'message'         => "Congratulation, You have earned $75 by $host_name.",
				'created'         => local_to_gmt(),
				'message_type'    => 9
				);
			$this->Message_model->sentMessage($insertData, 1);
			 
			 $this->db->set('list_referral_code','')->where('id',$referral_code_check1->row()->id)->update('users');
			// $this->db->set('cancel_list_referral_code',$own_referral_code)->where('id',$referral_code_check1->row()->id)->update('users');
			 
			$amt_check = $this->db->where('id',$referral_code_check2->row()->id)->get('users');
			if($amt_check->row()->referral_amount)
			{
				$amt = 75+$amt_check->row()->referral_amount;
			}
			else {
				$amt = 75;
			}
			
			$this->db->set('referral_amount',$amt)->where('id',$referral_code_check2->row()->id)->update('users');
			
			 
				$email_name = 'referral_credit';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{friend_name}" => $host_name, "{user_name}" => $referral_code_check2->row()->username, '{amount}' => '$75');
		$this->Email_model->sendMail($referral_code_check2->row()->email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
			 }
		   }
		   
		    $referral_code_check3 = $this->db->where('id',$row->userby)->get('users');
		   
           if($referral_code_check3->row()->trips_referral_code)
		   {
		   	 $own_referral_code = $referral_code_check3->row()->trips_referral_code;
			
			
			 $referral_code_check4 = $this->db->where('referral_code',$own_referral_code)->get('users');
			 if($referral_code_check4->num_rows()!=0)
			 {
			 	$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userby,
				'userto'          => $referral_code_check4->row()->id,
				'message'         => "Congratulation, You have earned $25 by ".$referral_code_check3->row()->username,
				'created'         => local_to_gmt(),
				'message_type'    => 9
				);
			$this->Message_model->sentMessage($insertData, 1);
			
			 $this->db->set('trips_referral_code','')->where('id',$referral_code_check3->row()->id)->update('users');
			 
			$amt_check1 = $this->db->where('id',$referral_code_check4->row()->id)->get('users');
			if($amt_check1->row()->referral_amount)
			{
				$amt1 = 25+$amt_check1->row()->referral_amount;
			}
			else {
				$amt1 = 25;
			}

			$this->db->set('referral_amount',$amt1)->where('id',$referral_code_check4->row()->id)->update('users');
			
				$email_name = 'referral_credit';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{friend_name}" => $referral_code_check3->row()->username, "{username}" => $referral_code_check4->row()->username, "{amount}" => '$25');
		$this->Email_model->sendMail($referral_code_check4->row()->email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
			 }
		   }
			
			$updateKey      		  = array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 3;
			$updateData['is_payed']   = 0;
			$this->Trips_model->update_reservation($updateKey,$updateData);
			
			if($list_data->optional_address == '')
			{
				$optional_address = ' -';
			}
			else {
				$optional_address = $list_data->optional_address;
		    }
			if($list_data->state == '')
			{
				$state = ' -';
			}
			else {
				$state = $list_data->state;
		    }
			
			//Send Mail To Traveller
		$email_name = 'traveler_reservation_granted';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_name}" => $list_title,  "{host_name}" => ucfirst($host_name), "{Fname}" => $FnameH, "{Lname}" => $LnameH, "{livein}" => $liveH, "{phnum}" => $phnumH, "{comment}" => $comment,
		"{street_address}" => $list_data->street_address,"{optional_address}"=>$optional_address,"{city}"=>$list_data->city,"{state}"=>$state,"{country}"=>$list_data->country, "{zipcode}"=>$list_data->zip_code);
		$this->Email_model->sendMail($traveler_email,$host_email,ucfirst($admin_name),$email_name,$splVars);
		
		//Send Mail To Host
		$email_name = 'host_reservation_granted';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name), "{Fname}" => $FnameT, "{Lname}" => $LnameT, "{livein}" => $liveT, "{phnum}" => $phnumT, "{comment}" => $comment);
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
				
		//Send Mail To Administrator
		$email_name = 'admin_reservation_granted';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		echo '[{"status":"Successfully accepted"}]';
	}
	
	public	function decline()
 {	
	extract($this->input->get());
	
	 $admin_email 						= $this->dx_auth->get_site_sadmin();
	 $admin_name  						= $this->dx_auth->get_site_title();
	
		$conditions    				= array('reservation.id' => $reservation_id);
		$row           				= $this->Trips_model->get_reservation($conditions)->row();
		
		if($this->Trips_model->get_reservation($conditions)->num_rows() == 0)
		{
			echo '[{"status":"Please give valid reservation id"}]';exit;
		}
		
		if($row->status != 1)
		{
			echo '[{"status":"Sorry! this reservation request not in Pending status"}]';exit;
		}
		
		$query1     						 = $this->Users_model->get_user_by_id($row->userby);
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;
		
		$query2     						 = $this->Users_model->get_user_by_id($row->userto);
		$host_name 								= $query2->row()->username;
		$host_email 							= $query2->row()->email;
		
		$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
	
		//for calendar
		//if($is_block == 'on')
		//{
				$this->db->select_max('group_id');
				$group_id               = $this->db->get('calendar')->row()->group_id;
				
				if(empty($group_id)) echo $countJ = 0; else $countJ = $group_id;
				
				$insertData['list_id']      = $row->list_id;
				$insertData['group_id']     = $countJ + 1;
				$insertData['availability'] = 'Available';
				$insertData['booked_using'] = 'Other';
				
					$checkin  = date('m/d/Y', $checkin);
					$checkout = date('m/d/Y', $checkout);
					$days     = getDaysInBetween($checkin, $checkout);
		
					$count = count($days);
					$i = 1;
					foreach ($days as $val)
					{
						if($count == 1)
						{
							$insertData['style'] = 'single';
						}
						else if($count > 1)
						{
							if($i == 1)
							{
							$insertData['style'] = 'left';
							}
							else if($count == $i)
							{
							$insertData['notes'] = '';
							$insertData['style'] = 'right';
							}
							else
							{
							$insertData['notes'] = '';
							$insertData['style'] = 'both';
							}
						}	
						
					$insertData['booked_days'] = $val;
					$this->Trips_model->insert_calendar($insertData);				
					$i++;
					}
					$this->db->where('list_id',$row->list_id)->where('availability','Available')->delete('calendar');
					$query = $this->db->get('calendar');
					$row1 = $query->last_row();
					if($row1->availability == 'Not Available')
					{
					$this->db->where('group_id',$row1->group_id)->delete('calendar');
					}
			//}
	
			//Send Message Notification To Traveller
			$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userto,
				'userto'          => $row->userby,
				'message'         => "Sorry, Your reservation request is declined by $host_name for $list_title.",
				'created'         => local_to_gmt(),
				'message_type'    => 3
				);
			$this->Message_model->sentMessage($insertData, 1);
			$message_id     = $this->db->insert_id();
			
			
			$updateKey      		  = array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 4;
			$this->Trips_model->update_reservation($updateKey,$updateData);
	
			//Send Mail To Traveller
		$email_name = 'traveler_reservation_declined';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name), "{comment}" => $comment);
		$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		//Send Mail To Host
		$email_name = 'host_reservation_declined';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name), "{comment}" => $comment);
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
				
		//Send Mail To Administrator
		$email_name = 'admin_reservation_declined';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
	
	    echo '[{"status":"Successfully declined"}]';
	}
	
	public	function conversation()
	{

      extract($this->input->get());

      $param = $conversation_id;
	  
	  if($param == 0)
	  {
	  	echo '[{"status":"Access denied"}]';exit;
	  }
	  if($param == '')
			{
			  echo '[{"status":"Access denied"}]';exit;
			}
			$check = $this->db->where('conversation_id',$param)->get('messages');
			if($check->num_rows() == 0)
			{
				echo '[{"status":"Access denied"}]';exit;
			}	
			
   $data['conversation_id'] = $param;
	  $conditions              = array("messages.conversation_id" => $param, "messages.userby" => $user_id);
			$or_where                = array("messages.userto" => $user_id);
			$orderby                 = array('id', "DESC");
		    
	            $this->db->where($conditions);
                 
	            $this->db->or_where($or_where);

        		$this->db->order_by($orderby[0], $orderby[1]);
         
                $this->db->from('messages');
 
                $this->db->join('message_type', 'messages.message_type = message_type.id','inner');
                $this->db->join('reservation', 'messages.reservation_id = reservation.id','left');
                $this->db->join('contacts', 'messages.contact_id = contacts.id','left');
 				$this->db->join('users', 'messages.userby = users.id','right');
                $this->db->select('messages.id,messages.list_id,messages.reservation_id,messages.contact_id,messages.conversation_id,messages.userby,messages.userto,messages.subject,messages.message,messages.created,messages.is_read,messages.message_type,messages.is_starred,message_type.name,message_type.url,reservation.checkin,reservation.checkout,reservation.price,reservation.no_quest,contacts.checkin,contacts.checkout,users.username,users.email,users.photo_status');
                     
                $row = $this->db->get();
				
				if($row->num_rows() != 0)
				{
					foreach($row->result() as $row_data)
					{
						$data['id'] = $row_data->id;
						$data['list_id'] = $row_data->list_id;
						$data['reservation_id'] = $row_data->reservation_id;
						$data['contact_id'] = $row_data->contact_id;
						$data['conversation_id'] = $row_data->conversation_id;
						$data['userby'] = $row_data->userby;
						$data['userto'] = $row_data->userto;
						$data['subject'] = $row_data->subject;
						$data['message'] = $row_data->message;
						$data['created'] = $row_data->created;
						$data['is_read'] = $row_data->is_read;
						$data['message_type'] = $row_data->message_type;
						$data['is_starred'] = $row_data->is_starred;
						$data['name'] = $row_data->name;
						$data['url'] = $row_data->url;
						$data['checkin'] = $row_data->checkin;
						$data['checkout'] = $row_data->checkout;
						$data['price'] = $row_data->price;
						$data['no_quest'] = $row_data->no_quest;
						$data['username'] = $row_data->username;
						$data['email'] = $row_data->email;
						$data['photo_status'] = $row_data->photo_status;
						$data['userby_image'] = $this->Gallery->profilepic($row_data->userby,2);
						$data1[] = $data;
					}
				}
		        echo strip_tags(json_encode($data1));
	}
	
	public function sendmessage()
	{	
	 extract($this->input->get());
	 
						if($this->input->get('reservation_id') != 0)
						{	
						$insertData = array(
							'list_id'         => $list_id,
			   	            'reservation_id'  => $reservation_id,
							'userby'          => $userby,
							'userto'          => $userto,
							'message'         => $comment,
							'created'         => local_to_gmt(),
							'message_type '   => 3
							);			
						}
						elseif($this->input->get('contact_id') != 0)
							{
									$insertData = array(
							'list_id'         => $list_id,
			   	            'contact_id'      => $contact_id,
							'userby'          => $userby,
							'userto'          => $userto,
							'message'         => $comment,
							'created'         => local_to_gmt(),
							'message_type '   => 3
							);	
							}
		   	$this->Message_model->sentMessage($insertData,1);	
			
			echo '[{"status":"Successfully Message sent."}]';
	
	}
	}
?>
