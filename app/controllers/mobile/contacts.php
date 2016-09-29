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
		
		$this->load->model('Users_model'); 
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->load->model('Contacts_model');
	}
	
	public function request()
	{
			extract($this->input->get());
						
			$conditions    				 = array('contacts.id' => $contact_id, 'contacts.userto' => $user_id);
 			$result        				 = $this->Contacts_model->get_contacts($conditions);
				
				if($result->num_rows() == 0)
				{
				  echo '[{"status":"Access denied"}]';exit;
				}
				
				$data1['result'] 			= $result->row();
				$list_id       			 = $data1['result']->list_id;
				$data['list']=$this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
				$no_quest    = $data1['result']->no_quest;
				$data['no_quest']=$no_quest;
				
				$x    	   = $this->Common_model->getTableData('price',array('id' => $list_id));
	  			$data['per_night'] = $price = $x->row()->night;
				
				
				$checkin=$data1['result']->checkin;
				$data['checkin']=$checkin;
				$checkout=$data1['result']->checkout;
				$data['checkout']=$checkout;
				
				$diff              = abs(strtotime($checkout) - strtotime($checkin));
	  			$data['nights']    = $days = floor($diff/(60*60*24));		
		  		$amt=$data['subtotal']  = $result->row()->price;
				$data['message']   = $this->Common_model->getTableData('messages',array('contact_id' => $data1['result']->id,'message_type' => '7'))->row()->message;	
				
				$data['commission'] = $result->admin_commission;
								
				$data['totalprice']     = $result->row()->price;
				$data['subtotal']     = $result->row()->price;
				$data['per_night'] = $price = $result->row()->price/$days;
				$data['status']    = $result->row()->name;
								
			echo '['.strip_tags(json_encode($data)).']';
			
	}

	public function response()
	{
          extract($this->input->get());
		  						
			$conditions    				 = array('contacts.id' => $contact_id, 'contacts.userby' => $user_id);
 			$result        				 = $this->Contacts_model->get_contacts($conditions);
				
				if($result->num_rows() == 0)
				{
				  echo '[{"status":"Access denied"}]';exit;
				}
				
				$data1['result'] 			= $result->row();
				$list_id       			 = $data1['result']->list_id;
				$key					 = $data1['result']->contact_key;
				$data['list']=$this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
				$no_quest    = $data1['result']->no_quest;
				$data['no_quest']=$no_quest;
				
				$x    	   = $this->Common_model->getTableData('price',array('id' => $list_id));
	  			$data['per_night'] = $price = $x->row()->night;
				
				$data['cleaning'] = $result->row()->cleaning;	
				$data['security'] = $result->row()->security;
				
				$checkin=$data1['result']->checkin;
				$data['checkin']=$checkin;
				$checkout=$data1['result']->checkout;
				$data['checkout']=$checkout;
				
				$diff              = abs(strtotime($checkout) - strtotime($checkin));
	  			$data['nights']    = $days = floor($diff/(60*60*24));		
		  		$data['subtotal']  = $result->row()->price;
				$data['status']	  = $this->Common_model->getTableData('contacts',array('id' => $data1['result']->id))->row()->status;
				
				if($data['status']==4)
				$data['message']   = $this->Common_model->getTableData('messages',array('contact_id' => $data1['result']->id,'message_type' => '3'))->row()->message;
				else
				$data['message']   = $this->Common_model->getTableData('messages',array('contact_id' => $data1['result']->id,'message_type' => '8'))->row()->message;	
				
				$data['url']   = base_url()."payments/form/".$list_id."?contact=".$key;	
				$data['status']	  = $this->Common_model->getTableData('contacts',array('id' => $data1['result']->id))->row()->status;
				$data['commission'] = $result->row()->admin_commission;	
				$data['total_payout']     = $amt;
				$data['totalprice']		  = round($result->row()->price + $data['commission']);
			
			echo '['.strip_tags(json_encode($data)).']';
	}
	public	function accept()
 	{
 		extract($this->input->get());
	
		$message					  = $comment;
			
	 	//Update the status,price
	 		$updateKey      		  = array('id' => $contact_id);
			$updateData               = array();
			$updateData['status']    = 3;
			$updateData['price']     = $price;
			$this->Contacts_model->update_contact($updateKey,$updateData);
			
			$price = '$'.$price;
			
	 	//Email the confirmation link to the traveller	
		$result			= $this->Common_model->getTableData('contacts',array('id' => $contact_id))->row();
		$traveller_id	= $result->userby; 
		$key			= $result->contact_key;	
		$list_id		= $result->list_id;
		$title			= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
		$host_email		= $this->Common_model->getTableData('users',array('id' => $user_id))->row()->email;
		$traveller_email= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
		
		//send message to traveller
		$host_id		= $result->userto;
		$travellername 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->username;
		$hostname		= $this->Common_model->getTableData('users',array('id' => $user_id))->row()->username;
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
		
		echo '[{"status":"Accepted successfully"}]';		
	}

   public function special()
 	{
 		extract($this->input->get());
 		
		$message					  = $comment;	
	 	//Update the status,price
	 		$updateKey      		  = array('id' => $contact_id);
			$updateData               = array();
			$updateData['status']    = 3;
			$updateData['price']     = $price;
			$this->Contacts_model->update_contact($updateKey,$updateData);
	 	
		    extract($this->input->post());
		
	 		$price = '$'.$price;
			
	 	//Email the confirmation link to the traveller	
		$result			= $this->Common_model->getTableData('contacts',array('id' => $contact_id))->row();
		$traveller_id	= $result->userby; 
		$key			= $result->contact_key;	
		$list_id		= $result->list_id;
		$title			= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
		$host_email		= $this->Common_model->getTableData('users',array('id' => $user_id))->row()->email;
		$traveller_email= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
		
		//send message to traveller
		$host_id		= $result->userto;
		$travellername 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->username;
		$hostname		= $this->Common_model->getTableData('users',array('id' => $user_id))->row()->username;
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
		
		echo '[{"status":"Accepted successfully"}]';
				
	}


	public function discuss()
	{
		extract($this->input->get());
		
		$contact_id   				  = $contact_id;
		$message					  = $comment;	
					
	 	//Email the confirmation link to the traveller	
		$result			= $this->Common_model->getTableData('contacts',array('id' => $contact_id))->row();
		$traveller_id	= $result->userby; 
		$list_id		= $result->list_id;
		$title			= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
		$host_email		= $this->Common_model->getTableData('users',array('id' => $user_id))->row()->email;
		$traveller_email= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
		//send message to traveller
		$host_id		= $result->userto;
		$travellername 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->username;
		$hostname		= $this->Common_model->getTableData('users',array('id' => $user_id))->row()->username;
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
		
		$traveller_email= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
	    $host_email		= $this->Common_model->getTableData('users',array('id' => $user_id))->row()->email;	
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
			echo '[{"status":"Message successfully sent."}]';							
	}
	public	function decline()
 	{
 		extract($this->input->get());
		
	 	$contact_id   				  = $contact_id;	
	 	//Update the status,price
	 		$updateKey      		  = array('id' => $contact_id);
			$updateData               = array();
			$updateData['status']    = 4;
			$this->Contacts_model->update_contact($updateKey,$updateData);
			
		$message					  = $comment;		
	 	//Email the confirmation link to the traveller	
		$result			= $this->Common_model->getTableData('contacts',array('id' => $contact_id))->row();
		$traveller_id	= $result->userby; 
		$list_id		= $result->list_id;
		//send message to traveller
		$host_id		= $result->userto;
		$travellername 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->username;
		$hostname		= $this->Common_model->getTableData('users',array('id' => $user_id))->row()->username;
			
		$traveller_email 	= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
	    $host_email		= $this->Common_model->getTableData('users',array('id' => $user_id))->row()->email;	
		$list_title		= $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
						
			$insertData = array(
				'list_id'         => $list_id,
				'contact_id'  	  => $contact_id,
				'userby'          => $host_id,
				'userto'          => $traveller_id,
				'message'         => '<b>Contact Request Declined by '.$hostname.'</b><br><br>'.$message,
				'message_type'    => 3
				);
			
		$this->Message_model->sentMessage($insertData,1);	
		
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
		echo '[{"status":"Declined successfully"}]';
	}
}
?>