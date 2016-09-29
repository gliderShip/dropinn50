<?php
/**
 * DROPinn Hosting Controller Class
 *
 * helps to achieve common tasks related to the hosting.
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Hosting
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 
 */ 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listings extends CI_Controller {

	public function Listings()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->helper('form');

		$this->load->library('form_validation');
		$this->load->library('Pagination');
		
		$this->load->model('Users_model');
		$this->load->model('Trips_model');
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->facebook_lib->enable_debug(TRUE);
	}
	
	public	function index()
 	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{		
			$data['title']            = get_meta_details('Your_Hosting_data','title');
			$data["meta_keyword"]     = get_meta_details('Your_Hosting_data','meta_keyword');
			$data["meta_description"] = get_meta_details('Your_Hosting_data','meta_description');
			
			//Remove the list which status is 0
			//$conditions = array("status" => '0');
			//$this->Common_model->deleteTableData('list', $conditions);
			
			$id = $this->dx_auth->get_user_id();
			
		$query = $this->db->distinct()->select('list.*,lys_status.calendar as calendar_status,lys_status.overview as overview_status,lys_status.price as price_status,lys_status.photo as photo_status,lys_status.address as address_status,lys_status.listing as listing_status')->join('lys_status',"lys_status.id=list.id")->where("list.user_id",$id)->get('list');
		
		$param = (int) $this->uri->segment(3,0);
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
			
			// Pagination config
		$p_config['base_url']    = site_url('listings/index');
		$p_config['uri_segment'] = 3;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
  											
		$data['message_element']  = "hosting/view_hosting";
		$this->load->view('template',$data);
		
		}
		else
		{
			redirect('users/signin');
		}
	}
	
	
	public function change_status()
	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
			$sow_hide = $this->input->get('stat'); 
			$row_id   = $this->input->get('rid');
			if($sow_hide == 1)
			{
				$data['is_enable']      = 1;
				$condition              = array("id" => $row_id);
				$this->Common_model->updateTableData('list', NULL, $condition , $data); 
				redirect('listings');
			}
			else
			{
				$data['is_enable']       = 0;
				$condition               = array("id" => $row_id);
				$this->Common_model->updateTableData('list', NULL, $condition , $data);
				redirect('listings');
			}
			
			$data['title']            = get_meta_details('Manage_Listings','title');
			$data["meta_keyword"]     = get_meta_details('Manage_Listings','meta_keyword');
			$data["meta_description"] = get_meta_details('Manage_Listings','meta_description');
			
			$data['message_element']  = "hosting/view_hosting";
			$this->load->view('template',$data);
			
		}
		else
		{
		redirect('users/signin');
		}
 }
	
		
	public function sort_by_status()
	{
	 if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
		$sort           = $this->input->get('f'); 
		
		
		$data['sort']             = $sort;
		
		$id = $this->dx_auth->get_user_id();
		
		 if($sort=="active")
                        {
                        	//echo 'Result >> Active Listings';		    
                            $this->db->where('is_enable', 1);
                        }	
                        
                        if($sort=="hide")
                        {
                        	//echo 'Result >> Hidden Listings';				    
                            $this->db->where('is_enable', 0);
                        }
			
		$query = $this->db->select('list.*,lys_status.calendar as calendar_status,lys_status.overview as overview_status,lys_status.price as price_status,lys_status.photo as photo_status,lys_status.address as address_status,lys_status.listing as listing_status')->join('lys_status',"lys_status.id=list.id")->where("list.user_id",$id)->get('list');
		
		$param = rtrim($this->input->get('per_page'),'0')+1;
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
			
			// Pagination config
		$p_config['base_url']    = site_url('listings/sort_by_status?f='.$sort);
		$p_config['uri_segment'] = 3;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
		$p_config['page_query_string'] = TRUE;
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links();
		
		$data['title']            = get_meta_details('Manage Listings','title');
		$data["meta_keyword"]     = get_meta_details('Manage Listings','meta_keyword');
		$data["meta_description"] = get_meta_details('Manage Listings','meta_description');
		
		$data['message_element']  = "hosting/view_hosting";
		$this->load->view('template',$data);
		}
		else
		{
			redirect('users/signin');
		}
	}
	
	
	public function my_reservation()
	{
			if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
			{
			$conditions    				       = array('userto' => $this->dx_auth->get_user_id());
 		$data['result'] 			       = $this->Trips_model->get_reservation($conditions);
			
			$data['title']            = get_meta_details('My_Reservations','title');
			$data["meta_keyword"]     = get_meta_details('Edit_your_Profile','meta_keyword');
			$data["meta_description"] = get_meta_details('Edit_your_Profile','meta_description');
		 
			$data['message_element']  = "hosting/view_myreservation";
			$this->load->view('template',$data);
			}
			else
			{
				redirect('users/signin');
			}
	}
	
	
	//Ajax page
	public function cancel_host($params1 = '', $params2 = '')
	{
		if($this->input->post('reservation_id'))
		{
		 $reservation_id    = $this->input->post('reservation_id');
			
			$admin_email 						= $this->dx_auth->get_site_sadmin();
			$admin_name  						= $this->dx_auth->get_site_title();
	
			$conditions    				= array('reservation.id' => $reservation_id);
			$row           				= $this->Trips_model->get_reservation($conditions)->row();
			
			$query1     						 = $this->Users_model->get_user_by_id($row->userby);
			$traveler_name 				= $query1->row()->username;
			$traveler_email 			= $query1->row()->email;
			
			$query2     						 = $this->Users_model->get_user_by_id($row->userto);
			$host_name 								= $query2->row()->username;
			$host_email 							= $query2->row()->email;
			
			$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
			
			$updateKey      										= array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 5;
			$this->Trips_model->update_reservation($updateKey,$updateData);
		
		$comment = $this->input->post('comment');
		
		//Send Mail To Traveller
		$email_name = 'traveler_reservation_declined';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{comment}" => $comment, "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		//Send Mail To Host
		$email_name = 'host_reservation_declined';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
				
		//Send Mail To Administrator
		$email_name = 'admin_reservation_declined';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('You are successfully cancelled the trip.')));
		}
		else
		{
		$data['reservation_id'] = $params1;
		$data['list_id']        = $params2;
		$this->load->view(THEME_FOLDER.'/hosting/view_cancel_host',$data);
		}
	}
	
	public function policies()
	{
		 if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
			$data['title']            = get_meta_details('Stand_Bys','title');
			$data["meta_keyword"]     = get_meta_details('Stand_Bys','meta_keyword');
			$data["meta_description"] = get_meta_details('Stand_Bys','meta_description');
		 
			$data['message_element']  = "hosting/view_policies";
			$this->load->view('template',$data);
		}
		else
		{
			redirect('users/signin');

		}
	}
	
}

/* End of file listings.php */
/* Location: ./app/controllers/listings.php */
?>
