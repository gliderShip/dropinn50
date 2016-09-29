<?php
class Backend extends CI_Controller
{
	function Backend()
	{
		parent::__construct();
		
		$this->load->library('Table');
		$this->load->library('Pagination');
		$this->load->library('DX_Auth');
		
		$this->load->helper('form');
		$this->load->helper('url');
 	$this->load->helper('file');

		$this->path = realpath(APPPATH . '../images');
		
		$this->load->model('Users_model');			
		
		// Protect entire controller so only admin, 
		// and users that have granted role in permissions table can access it.
		$this->dx_auth->check_uri_permissions();
	}
	
	function index()
	{
	$get_users_table=$this->db->get("users");
	
	//$user_date=$get_users_table->result();
	
	 $cur_date=date("F j, Y");
	
	$today_user=array();
	
	$registered_user_today='';
	$created_date="";
	
	foreach($get_users_table->result() as  $user)
	{
		$timestamp=$user->created;
			     $book_date=date("F j, Y",$timestamp);
		
	
	  if($book_date==$cur_date)
	  {
		  $today_user[]=$user->id;
		
	  }
		
	}

	$get_list_table=$this->db->get("list");
	$created_datelist="";
	
	$today_userlist  = array();
	
		foreach($get_list_table->result() as $list)
		{
		 	 $created_datelist=date("F j, Y",$list->created);
				
   if($created_datelist==$cur_date)
			$today_userlist[] = $list->user_id;
		}
		
 //$data['user_id']    = $this->session->userdata['DX_user_id'];

	$data['today_userlist']= count($today_userlist);
	$data['todayuser'] = count($today_user);
	

	//$data['list_id'] = $list_id;

		
	$get_reservation  = $this->db->get('reservation');
//print_r($get_reservation);
	$today_reservation=array();
	$created_datelist1="";
//exit;
		foreach($get_reservation->result() as $reservation)
		{
		
		  $reservation_list=date("F j, Y",$reservation->book_date);
		
   if($reservation_list==$cur_date)
			{
		    	$today_reservation[] = $reservation->list_id;
			}
		}
		
		
  $data['today_reservation'] = count($today_reservation);
		
		$data['message_element'] = "administrator/view_home";
	 $this->load->view('administrator/admin_template', $data);
	}

}
?>