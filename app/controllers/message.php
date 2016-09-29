<?php
/**
 * DROPinn Message Controller Class
 *
 * It helps to do the message system
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Message
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Controller {

	public function Message()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('Pagination');
		
		$this->load->model('Message_model');
		$this->facebook_lib->enable_debug(TRUE);
	}
	
	public	function inbox()
 {
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
			if($this->input->get())
			{
			extract($this->input->get());
			}
			
			if($this->input->get('type') == 'all')
			{				
				$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_archived"=>0); 
	  			$query = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
				
				$param = (int) $this->uri->segment(4,0);
				
				$param = rtrim($this->input->get('per_page'),'0')+1;
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
		
		$this->db->limit($data['row_count'],$data['offset']);
	   	$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_archived"=>0); 
	  	$data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
			
			// Pagination config
		$p_config['base_url']    = site_url('message/inbox?type='.$type);
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
		$p_config['page_query_string'] = TRUE;
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links();
		
			}
			else if($this->input->get('type') == 'starred')
			{
				$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_starred"=>1,"messages.is_archived"=>0); 
	  			$query = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
				
				$param = (int) $this->uri->segment(4,0);
				$param = rtrim($this->input->get('per_page'),'0')+1;
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
		
		$this->db->limit($data['row_count'],$data['offset']);
	   	$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_starred"=>1,"messages.is_archived"=>0);
	  	$data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
			
			// Pagination config
		$p_config['base_url']    = site_url('message/inbox?type='.$type);
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
		$p_config['page_query_string'] = TRUE;
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links();
		
			}
			else if($this->input->get('type') == 'unread')
			{
				$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_read"=>0,"messages.is_archived"=>0); 
	  			$query = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
				
				$param = (int) $this->uri->segment(4,0);
				$param = rtrim($this->input->get('per_page'),'0')+1;
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
		
		$this->db->limit($data['row_count'],$data['offset']);
	   	$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_read"=>0,"messages.is_archived"=>0);
	  	$data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
			
			// Pagination config
		$p_config['base_url']    = site_url('message/inbox?type='.$type);
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
		$p_config['page_query_string'] = TRUE;
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links();
		
			}
            else if($this->input->get('type') == 'reservations')
			{
				$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.message_type"=>1,"messages.is_archived"=>0); 
	  			$query = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
				
				$param = (int) $this->uri->segment(4,0);
				$param = rtrim($this->input->get('per_page'),'0')+1;
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
		
		$this->db->limit($data['row_count'],$data['offset']);
	   	$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.message_type"=>1,"messages.is_archived"=>0); 
	  	$data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
			
			// Pagination config
		$p_config['base_url']    = site_url('message/inbox?type='.$type);
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
		$p_config['page_query_string'] = TRUE;
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links();
			}
			else if($this->input->get('type') == 'never_responded')
			{
				$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_respond"=>0,"messages.is_archived"=>0); 
	  			$query  = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
				
				$param = (int) $this->uri->segment(4,0);
				$param = rtrim($this->input->get('per_page'),'0')+1;
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
		
		$this->db->limit($data['row_count'],$data['offset']);
	   	$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_respond"=>0,"messages.is_archived"=>0);
	  	$data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
			
			// Pagination config
		$p_config['base_url']    = site_url('message/inbox?type='.$type);
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
		$p_config['page_query_string'] = TRUE;
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links();
			}
			else if($this->input->get('type') == 'hidden')
			{
				$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_archived"=>1); 
	  			$query  = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
				
				$param = (int) $this->uri->segment(4,0);
				$param = rtrim($this->input->get('per_page'),'0')+1;
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
		
		$this->db->limit($data['row_count'],$data['offset']);
	   	$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_archived"=>1);
	  	$data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
			
			// Pagination config
		$p_config['base_url']    = site_url('message/inbox?type='.$type);
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
		$p_config['page_query_string'] = TRUE;
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links();
			}
			else {
				$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_archived"=>0); 
	  			$query = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
				
				$param = (int) $this->uri->segment(4,0);
				//$param = rtrim($this->input->get('per_page'),'0')+1;
		
		 // Number of record showing per page
		$data['row_count'] = 10;
		
		if($param > 0)
		   $data['offset']			 = ($param-1) * $data['row_count'];
		else
		   $data['offset']			 =  $param * $data['row_count']; 
		
		$this->db->limit($data['row_count'],$data['offset']);
	   $conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_archived"=>0); 
	  	$data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
			
			// Pagination config
		$p_config['base_url']    = site_url('message/inbox/index');
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $data['row_count'];
		//$p_config['page_query_string'] = TRUE;
				
		// Init pagination
		$this->pagination->initialize($p_config);
				
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
			}
			
			$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_archived"=>0); 
	  		$data['all_count'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'))->num_rows();
			
			$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_starred"=>1); 
	  		$data['starred_count'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'))->num_rows();
			
			$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_read"=>0); 
	  		$data['unread_count'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'))->num_rows();
			
			$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.message_type"=>1); 
	  		$data['reservations_count'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'))->num_rows();
					
			$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_respond"=>0); 
	  		$rc=$data['respond_count']  = $this->Message_model->get_messages($conditions, NULL, array('messages.userby','desc'))->num_rows();
			//echo $rc;
			 //$data['respond_count'] =$this->db->query('select * from `messages` where `messages`.`userto`='.$this->dx_auth->get_user_id().' and `is_respond`=0')->num_rows();
			//echo $this->db->last_query();
			//exit;
			$conditions       = array("messages.userto " => $this->dx_auth->get_user_id(),"messages.is_archived"=>1); 
	  		$data['hidden_count'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'))->num_rows();
					
				//var_dump($data['messages']); exit;
				$data['type']             = $this->input->get('type');
				$data['title']            = get_meta_details('Inbox','title');
				$data["meta_keyword"]     = get_meta_details('Inbox','meta_keyword');
				$data["meta_description"] = get_meta_details('Inbox','meta_description');
			
				$data['message_element']  = 'message/inbox';
				$this->load->view('template',$data);	
}
else
{
redirect('users/signin');
}	
	}
	
	public function starred()
	{
	 $message_id   	              = $this->input->post('message_id');
		$to_change   	               = $this->input->post('to_change');
		$updateKey      										   = array('id' => $message_id);
		
		$updateData                  = array();
		$updateData['is_starred']    = $to_change;
		$this->Message_model->updateMessage($updateKey,$updateData);
		
		if($to_change == 0)
		{
		  echo translate("Message unstarred successfully.");
		}
		else
		{
		  echo translate("Message starred successfully.");
		}
	}
	
	//Ajax page
	public function delete()
	{
	  $message_id   	= $this->input->post('message_id');
			
			$this->Message_model->deleteMessage($message_id);
			
	  $conditions       = array("messages.userto " => $this->dx_auth->get_user_id());
		 $data['messages'] = $this->Message_model->get_messages($conditions);
				
		//echo $this->load->view(THEME_FOLDER.'/message/ajax_inbox',$data);					
	
	echo "success";
	}
	
	public function archive()
	{
	 $message_id   	= $this->input->post('message_id');
	
		$updateKey      										= array('id' => $message_id);
		$updateData               = array();
		$updateData['is_archived ']   = 1;
		$this->Message_model->updateMessage($updateKey,$updateData);
	}
	
	public function unarchive()
	{
	 $message_id   	= $this->input->post('message_id');
	
		$updateKey      										= array('id' => $message_id);
		$updateData               = array();
		$updateData['is_archived ']   = 0;
		$this->Message_model->updateMessage($updateKey,$updateData);
	}
	
	public function is_read()
	{
	 $message_id   	= $this->input->post('message_id');
	
		$updateKey      										= array('id' => $message_id);
		$updateData               = array();
		$updateData['is_read ']   = 1;
		$this->Message_model->updateMessage($updateKey,$updateData);
	}
	
}

/* End of file message.php */
/* Location: ./app/controllers/message.php */
?>
