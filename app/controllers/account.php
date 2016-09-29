<?php
/**
 * DROPinn Account Controller Class
 *
 * It helps to show the user account details
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Account
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	//Constructor
	public function Account()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->helper('form');
		
		$this->load->library('form_validation');
		$this->load->library('Pagination');
		
		// Export CSV
		$this->load->helper('download');
		
		
		$this->load->model('Users_model');
		$this->load->model('Trips_model');
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->facebook_lib->enable_debug(TRUE);
	}
	
	public function index()
	{
 	//if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
 	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
	 	$user_id = $this->dx_auth->get_user_id();
		
	 	$query   = $this->Common_model->getTableData( 'user_notification', array("user_id" => $user_id));
		
				if($this->input->post())
				{
					if($this->input->post('periodic_offers') == 1)
						$data['periodic_offers']      = 1;
					else
						$data['periodic_offers']      = 0;
						
					if($this->input->post('company_news') == 1)
						$data['company_news']         = 1;
					else
						$data['company_news']         = 0;
						
					if($this->input->post('upcoming_reservation') == 1)
						$data['upcoming_reservation'] = 1;
					else
						$data['upcoming_reservation'] = 0;
						 
					if($this->input->post('new_review') == 1)
						$data['new_review']           = 1;
					else
						$data['new_review']           = 0;
						
					if($this->input->post('leave_review') == 1)
						$data['leave_review']        = 1;
					else
						$data['leave_review']        = 0;
						
					if($this->input->post('standby_guests') == 1)
						$data['standby_guests']      = 1;
					else
						$data['standby_guests']      = 0;
						
					if($this->input->post('rank_search') == 1)
						$data['rank_search']         = 1;
					else
						$data['rank_search']         = 0;
						
					//insert the data	
					$data['user_id']             = $user_id;
					
					if($query->num_rows() > 0)
					{ 
					$condition = array("user_id" => $user_id);
					$this->Common_model->updateTableData('user_notification', NULL, $condition, $data);
					//echo $this->db->last_query();exit;
					}
					else
					{
						$this->Common_model->insertData('user_notification', $data);
					}
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Settings updated successfully')));
				redirect('account');
				}

		
			$row = $query->row();
			
			if($query->num_rows() > 0)
			{
			$data['periodic_offers']      = $row->periodic_offers;
			$data['company_news']         = $row->company_news;
			$data['upcoming_reservation'] = $row->upcoming_reservation;
			$data['standby_guests']       = $row->standby_guests;
			$data['new_review']           = $row->new_review;
			$data['leave_review']         = $row->leave_review;
			$data['rank_search']          = $row->rank_search;
			}
			
			$data['title']                = get_meta_details('Edit_account_details','title');
			$data["meta_keyword"]         = get_meta_details('Edit_account_details','meta_keyword');
			$data["meta_description"]     = get_meta_details('Edit_account_details','meta_description');
			$data['message_element']      = "account/view_nodification";
			$this->load->view('template',$data);
		}
		else
		{
			redirect('users/signin');
		}
	}
	
	
	//Payout Preferences
	public function payout()
	{
	  //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
	  if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in())) 
	  {
				if($this->input->post())
				{
				$data['user_id']         = $this->dx_auth->get_user_id();
				$data['country']         = $this->input->post('country');
				$data['payout_type']     = $this->input->post('payout_type');
				$data['email'] 			 = $this->input->post('email');
				$data['currency']		 = $this->input->post('currency');
				$check = $this->db->where('user_id',$data['user_id'])->where('email',$data['email'])->where('currency',$data['currency'])->get('payout_preferences');
				
				if($check->num_rows() == 0)
				{
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your payout preference set successfully.')));
				$this->Common_model->insertData('payout_preferences', $data);
				}
				else {
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your payout preference this data already inserted.')));
				}
				}
				$data['result']						   	 =	$this->Common_model->getTableData( 'payout_preferences', array("user_id" => $this->dx_auth->get_user_id()) );
				$data['defaults']						   =	$this->Common_model->getTableData( 'payout_preferences', array("user_id" => $this->dx_auth->get_user_id(), "is_default !=" => 1) );
				$data['countries']						 	= $this->Common_model->getCountries()->result();
				
				$data['title'] 			        = get_meta_details('Your_Payment_Method_details','title');
				$data["meta_keyword"]     = get_meta_details('Your_Payment_Method_details','meta_keyword');
		 	    $data["meta_description"] = get_meta_details('Your_Payment_Method_details','meta_description');
				$data['message_element']  = "account/view_payout";
				$this->load->view('template',$data);
       }
	 else
	 {
				redirect('users/signin');
	 }
	}


	public function setDefault()
	{
	  //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
	  if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
	  {
			  $user_id                              = $this->dx_auth->get_user_id();
					
	    if($this->input->post())
	    { 
		   //unset the previous default email
					$condition                            = array("user_id" => $user_id);
		   $unset_default_email['is_default']    = 0;
		   $this->Common_model->updateTableData('payout_preferences', NULL, $condition, $unset_default_email);
		   
		   //set new default email	 
					$default_email                        = $this->input->post('default_email'); 
				 $data['is_default']                   = 1;
					$condition                            = array("id" => $default_email);
				 $this->Common_model->updateTableData('payout_preferences', NULL, $condition , $data); 
					
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your default payout updated successfully.')));
				}
		 
				redirect('account/payout');
	  }
	  else
	  {
     redirect('users/signin');
	  }
	}
	
	
	//Ajax page
	public function payoutMethod()
	{
	 // if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
	  if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
	  {
	    $country                  = $this->input->post('country');
					
					$conditions               = array("country_symbol" => $country);
					$query                    = $this->Common_model->getCountries($conditions);
					
					$data['result']           = $this->Common_model->getTableData('payments', array("is_payout" => 1));
	    $data['country']	         =	$query->row()->country_name;
					$data['country_symbol']	  =	$query->row()->country_symbol;
					
	    $this->load->view(THEME_FOLDER.'/account/view_payout_method', $data);
   }
	  else
	  {
     return false;
   }
	}
	
	
	//Ajax page
	public function paymentInfo()
	{    
    //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
	   { 
     $country             = '';
	    $payout_type         = '';

	    $country					        =	$this->input->post('country');
	    $payout_type	        =	$this->input->post('payout_type'); 
																				
	    $data['user_id']     = $this->dx_auth->get_user_id();
					$data['payout_type']	=	$payout_type;
					$data['payout_name']	=	$this->Common_model->getTableData('payments',array("id" => $payout_type))->row()->payment_name;
	    $data['country']     = $country;
					
	    $this->load->view(THEME_FOLDER.'/account/view_payment_info', $data);
	  }
	  else
	  {
     redirect('users/signin');
	  }	
	}
	
	
	public function transaction()
	{
			//if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
			if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
			{
				$data['payout_methods'] = $this->Common_model->getTableData('payments');
				$data['listings'] = $this->Common_model->getTableData('list',array('user_id'=>$this->dx_auth->get_user_id(),'is_enable'=>1,'list_pay'=>1),NULL,NULL,NULL,array('id'=>'asc'));
				
				$user_signup = $this->Common_model->getTableData('users',array('id'=>$this->dx_auth->get_user_id()))->row()->created;
								
				$start_date = date('Y',$user_signup);
				
				$end_date = date('Y',time());
				
				for($end_date = date('Y',time());$end_date >= $start_date; $end_date--)
				{
					$years[] = $end_date;
				}
				
				$data['years'] = $years;
				
				if($this->uri->segment(3) == 'future')
				{
					
					$user_id                  = $this->dx_auth->get_user_id();
				$conditions               = array("reservation.userto" => $user_id,"reservation.is_payed"=>0);
		 
		 $query = $this->db->query("SELECT 'Reservation' as accept_pay, 'PayPal' as accept_pay_transaction_id, list.title,`reservation`.`coupon`, `reservation`.`transaction_id`, `reservation`.`id`, `reservation`.`list_id`, `reservation`.`userby`, `users`.`username`, `reservation`.`userto`, `reservation`.`checkin`, `reservation`.`checkout`, `reservation`.`no_quest`, `reservation`.`currency`, `reservation`.`price`, `reservation`.`topay`, `reservation`.`admin_commission`, `reservation`.`credit_type`, `reservation`.`ref_amount`, `reservation`.`status`, `reservation`.`book_date`, `reservation`.`is_payed`, `reservation_status`.`name`, `reservation`.`cleaning`, `reservation`.`security`, `reservation`.`extra_guest_price`, `reservation`.`guest_count` FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1 UNION ALL SELECT 'Commission' as accept_pay, IF(accept_pay.transaction_id = '','PayPal','Creditcard') as accept_pay_transaction_id, list.title,`reservation`.`coupon`, `reservation`.`transaction_id`, `accept_pay`.`reservation_id` as id, `reservation`.`list_id`, `reservation`.`userby`, `users`.`username`, `reservation`.`userto`, `reservation`.`checkin`, `reservation`.`checkout`, `reservation`.`no_quest`, `reservation`.`currency`, `reservation`.`price`, `reservation`.`topay`, `reservation`.`admin_commission`, `reservation`.`credit_type`, `reservation`.`ref_amount`, `reservation`.`status`, `reservation`.`book_date`, `reservation`.`is_payed`, `reservation_status`.`name`, `reservation`.`cleaning`, `reservation`.`security`, `reservation`.`extra_guest_price`, `reservation`.`guest_count` FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN accept_pay on accept_pay.reservation_id = reservation.id WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1");

			//echo $this->db->last_query();exit;
				// Get offset and limit for page viewing
				$start = (int) $this->uri->segment(4,0);
				
				// Number of record showing per page
				$row_count = 10;
				
				if($start > 0)
							$offset			 = ($start-1) * $row_count;
				else
							$offset			 =  $start * $row_count; 
				
				$limit = $offset.", ".$row_count;
				// Get all transaction
				
		 		$data['result'] = $this->db->query("SELECT 'Reservation' as accept_pay, IF(reservation.transaction_id = 0,'PayPal','PayPal') as accept_pay_transaction_id, list.title,`reservation`.`coupon`, `reservation`.`transaction_id`, `reservation`.`id`, `reservation`.`list_id`, `reservation`.`userby`, `users`.`username`, `reservation`.`userto`, `reservation`.`checkin`, `reservation`.`checkout`, `reservation`.`no_quest`, `reservation`.`currency` as currency, `reservation`.`price`, IF(reservation.host_topay = 0,IF(reservation.host_penalty = 0,reservation.topay,CONCAT(reservation.host_topay,'(Amount has been taken by admin due to penalty)')),reservation.host_topay) as topay, `reservation`.`admin_commission`, `reservation`.`credit_type`, `reservation`.`ref_amount`, `reservation`.`status`, `reservation`.`book_date`, `reservation`.`is_payed`, `reservation_status`.`name`, `reservation`.`cleaning`, `reservation`.`security`, `reservation`.`extra_guest_price`, `reservation`.`guest_count` FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1 UNION ALL SELECT 'Commission' as accept_pay, IF(accept_pay.transaction_id = '','PayPal','Creditcard') as accept_pay_transaction_id, list.title,`reservation`.`coupon`, `reservation`.`transaction_id`, `accept_pay`.`reservation_id` as id, `reservation`.`list_id`, `reservation`.`userby`, `users`.`username`, `reservation`.`userto`, `reservation`.`checkin`, `reservation`.`checkout`, `reservation`.`no_quest`, `accept_pay`.`currency` as currency, `reservation`.`price`, `accept_pay`.`amount` as topay, `reservation`.`admin_commission`, `reservation`.`credit_type`, `reservation`.`ref_amount`, `reservation`.`status`, `reservation`.`book_date`, `reservation`.`is_payed`, `reservation_status`.`name`, `reservation`.`cleaning`, `reservation`.`security`, `reservation`.`extra_guest_price`, `reservation`.`guest_count` FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN accept_pay on accept_pay.reservation_id = reservation.id WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1 ORDER BY book_date DESC LIMIT $limit");	
			
			//UNIX_TIMESTAMP(STR_TO_DATE(FROM_UNIXTIME(checkout, '%b %d, %Y'), '%b %d, %Y %h %i %s'))	
				//echo $this->db->last_query();exit;
				// Pagination config
				$config['base_url']       = site_url('account/transaction/future');
				$config['uri_segment']    = 4;
				$config['num_links']      = 5;
				$config['total_rows']     = $query->num_rows();
				$config['per_page']       = $row_count;
						
				// Init pagination
				$this->pagination->initialize($config);		
				// Create pagination links
				$data['pagination']       = $this->pagination->create_links2();
				
				$data['title']            = get_meta_details('Your_Transaction_Details','title');
				$data["meta_keyword"]     = get_meta_details('Your_Transaction_Details','meta_keyword');
		 		$data["meta_description"] = get_meta_details('Your_Transaction_Details','meta_description');
				$data['message_element']  = "account/view_future_transaction";
				$this->load->view('template', $data);
					
				}
				else if($this->uri->segment(3) != 'future')
				{
				
			    $user_id                  = $this->dx_auth->get_user_id();
						 
		$query = $this->db->query("SELECT 'Reservation' as accept_pay, IF(reservation.transaction_id = 0,'PayPal','Creditcard') as accept_pay_transaction_id, refund.created, refund.payout_id, list.title, reservation.list_id, reservation.checkin, reservation.checkout, reservation.id as id, `reservation`.`currency` as currency, IF(reservation.host_topay = 0,reservation.topay,reservation.host_topay) as topay FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN `refund` ON `reservation`.`id` = `refund`.`reservation_id` where reservation.is_payed = 1 AND reservation.userto = $user_id UNION ALL SELECT 'Commission' as accept_pay, IF(accept_pay.transaction_id = '','PayPal','Creditcard') as accept_pay_transaction_id, refund.created, refund.payout_id, list.title, reservation.list_id, reservation.checkin, reservation.checkout, accept_pay.reservation_id as id, `accept_pay`.`currency` as currency, accept_pay.amount as topay FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN `refund` ON `reservation`.`id` = `refund`.`reservation_id` LEFT JOIN `accept_pay` ON `reservation`.`id` = `accept_pay`.`reservation_id` where accept_pay.status = 1 AND reservation.userto = $user_id");

		
				// Get offset and limit for page viewing
				$start = (int) $this->uri->segment(3,0);
				
				// Number of record showing per page
				$row_count = 10;
				
				if($start > 0)
							$offset			 = ($start-1) * $row_count;
				else
							$offset			 =  $start * $row_count; 
				
				$limit = $offset.', '.$row_count;
				
				$data['result'] = $this->db->query("SELECT 'Reservation' as accept_pay, IF(reservation.transaction_id = 0,'PayPal','Creditcard') as accept_pay_transaction_id, refund.created, refund.payout_id, list.title, reservation.list_id, reservation.checkin, reservation.checkout, reservation.id as id, `reservation`.`currency` as currency, IF(reservation.host_topay = 0,reservation.topay,reservation.host_topay) as topay FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN `refund` ON `reservation`.`id` = `refund`.`reservation_id` where refund.userto = $user_id AND reservation.is_payed = 1 AND reservation.userto = $user_id AND refund.accept_status = 0 UNION ALL SELECT 'Commission' as accept_pay, IF(accept_pay.transaction_id = '','PayPal','Creditcard') as accept_pay_transaction_id, refund.created, refund.payout_id, list.title, reservation.list_id, reservation.checkin, reservation.checkout, accept_pay.reservation_id as id, `accept_pay`.`currency` as currency, accept_pay.amount as topay FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN `refund` ON `reservation`.`id` = `refund`.`reservation_id` LEFT JOIN `accept_pay` ON `reservation`.`id` = `accept_pay`.`reservation_id` where refund.userto = $user_id AND accept_pay.status = 1 AND reservation.userto = $user_id ORDER BY  created DESC LIMIT $limit");
//UNIX_TIMESTAMP(STR_TO_DATE(FROM_UNIXTIME(created, '%b %d, %Y'), '%b %d, %Y %h %i %s'))				
				// Pagination config
				$config['base_url']       = site_url('account/transaction');
				$config['uri_segment']    = 3;
				$config['num_links']      = 5;
				$config['total_rows']     = $query->num_rows();
				$config['per_page']       = $row_count;
						
				// Init pagination
				$this->pagination->initialize($config);		
				// Create pagination links
				$data['pagination']       = $this->pagination->create_links2();
				
				$data['title']            = get_meta_details('Your_Transaction_Details','title');
				$data["meta_keyword"]     = get_meta_details('Your_Transaction_Details','meta_keyword');
		 		$data["meta_description"] = get_meta_details('Your_Transaction_Details','meta_description');
				$data['message_element']  = "account/view_transaction";
				$this->load->view('template', $data);
				}
			}
			else
			{
				redirect('users/signin');
			}
	}

public function ajax_completed_transaction()
{
	$this->load->library("Ajax_pagination");
	 
	extract($this->input->get());
		
	if(isset($payout_methods))
    {
    	if($payout_methods != 0)
		{
    	if($payout_methods == 2)
		{
			$condition = 'reservation.transaction_id = 0';
			$accept_condition = 'accept_pay.transaction_id = ""';
		}
		else if($payout_methods == 1) {
			$condition = 'reservation.transaction_id != 0';
			$accept_condition = 'accept_pay.transaction_id != ""';
		}
		}
		else
			{
			 $condition = '(reservation.transaction_id != 0 OR reservation.transaction_id = 0)';
			 $accept_condition = '(accept_pay.transaction_id != "" OR accept_pay.transaction_id = "")';
			}
    }	

	if(isset($listing))
	{
		if($listing != 0)
		{
		$condition .= ' AND reservation.list_id ='.$listing;
		$accept_condition .= ' AND reservation.list_id ='.$listing;
		}
	}
	
	if(isset($year))
	{
		if($year != 0)
		{
		$condition .= ' AND FROM_UNIXTIME(refund.created, "%Y") ='.$year;
		$accept_condition .= ' AND FROM_UNIXTIME(refund.created, "%Y") ='.$year;
		}
	}
	
	if(isset($month))
	{
		if($month != 0)
		{
		$condition .= ' AND FROM_UNIXTIME(refund.created, "%m") ='.$month;
		$accept_condition .= ' AND FROM_UNIXTIME(refund.created, "%m") ='.$month;
		}
	}
	
	$user_id = $this->dx_auth->get_user_id();
	
	if(isset($page))
	{
		$param = $page;
	}
	else
	{
		$param = 1;
	}
	
	$start = $param;
                
                $per_page = 10;
				
				// Number of record showing per page
				$row_count = 10;
				
				if($start > 0)
							$offset			 = ($start-1) * $row_count;
				else
							$offset			 =  $start * $row_count; 
				
				$limit = $offset.', '.$row_count;
	    			
    $query = $this->db->query("SELECT refund.created, FROM_UNIXTIME(refund.created, '%b %d, %Y') as Date, 'Reservation' as Type, list.title as List_Title, FROM_UNIXTIME(reservation.checkin, '%b %d, %Y') as CheckIn,FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Checkout, `reservation`.`currency` as Currency, IF(reservation.host_topay = 0,reservation.topay,reservation.host_topay) as Amount, refund.payout_id as Paid_Out FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN `refund` ON `reservation`.`id` = `refund`.`reservation_id` where refund.userto = $user_id AND reservation.is_payed = 1 AND refund.accept_status = 0 AND reservation.userto = $user_id AND $condition UNION ALL SELECT refund.created, FROM_UNIXTIME(refund.created, '%b %d, %Y') as Date, 'Commission' as Type, list.title as List_Title, FROM_UNIXTIME(reservation.checkin, '%b %d, %Y') as CheckIn,FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Checkout, `accept_pay`.`currency` as Currency, `accept_pay`.`amount` as Amount, refund.payout_id as Paid_Out FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN `refund` ON `reservation`.`id` = `refund`.`reservation_id` LEFT JOIN `accept_pay` ON `reservation`.`id` = `accept_pay`.`reservation_id` where refund.userto = $user_id AND accept_pay.status = 1 AND reservation.userto = $user_id AND $accept_condition ORDER BY created DESC LIMIT $limit");
	
 //UNIX_TIMESTAMP(STR_TO_DATE(Date, '%b %d, %Y %h %i %s')) and  
   if(isset($export))
   {
   	$data = $query;
   	$this->load->dbutil();
		$delimiter = ",";
		$newline = "\r\n";
		$query=$this->dbutil->csv_from_result1($data, $delimiter, $newline); 
		$site_name  = $this->dx_auth->get_site_title();
		$name='Export-'.$site_name.'completed_transaction.csv';
		force_download($name, $query);
   }
                
	    
    $data['result'] = $this->db->query("SELECT 'Reservation' as accept_pay, IF(reservation.transaction_id = 0,'PayPal','PayPal') as accept_pay_transaction_id, refund.created, refund.payout_id, list.title, reservation.list_id, reservation.checkin, reservation.checkout, reservation.id as id, `reservation`.`currency` as currency, IF(reservation.host_topay = 0,reservation.topay,reservation.host_topay) as topay FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN `refund` ON `reservation`.`id` = `refund`.`reservation_id` where refund.userto = $user_id AND reservation.is_payed = 1 AND reservation.userto = $user_id AND refund.accept_status = 0 AND $condition UNION ALL SELECT 'Commission' as accept_pay, IF(accept_pay.transaction_id = '','PayPal','Creditcard') as accept_pay_transaction_id, refund.created, refund.payout_id, list.title, reservation.list_id, reservation.checkin, reservation.checkout, accept_pay.reservation_id as id, `accept_pay`.`currency` as currency, accept_pay.amount as topay FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN `refund` ON `reservation`.`id` = `refund`.`reservation_id` LEFT JOIN `accept_pay` ON `reservation`.`id` = `accept_pay`.`reservation_id` where refund.userto = $user_id AND accept_pay.status = 1 AND reservation.userto = $user_id AND $accept_condition ORDER BY created DESC LIMIT $limit");
		//UNIX_TIMESTAMP(STR_TO_DATE(FROM_UNIXTIME(created, '%b %d, %Y'), '%b %d, %Y %h %i %s'))
		 		// Pagination config
				$config['base_url']       = site_url('account/transaction?payout_methods='.$payout_methods.'&listing='.$listing.'&month='.$month.'&year='.$year.'&per_page='.$row_count);
				
				$config['cur_page']   = $start;
				$config['total_rows']     = $query->num_rows();
				$config['per_page']       = $per_page;
				//$config['page_query_string'] = TRUE;
						
				// Init pagination
				$this->ajax_pagination->initialize($config);		
				// Create pagination links
				$data['pagination']       = $this->ajax_pagination->create_links(false);
				
				$data['status'] = 'completed';
		 							
	$this->load->view('templates/blue/account/ajax_transaction', $data);
				 			
}

public function ajax_future_transaction()
{
	$this->load->library("Ajax_pagination");
	 
	extract($this->input->get());
		
	if(isset($payout_methods))
    {
    	if($payout_methods != 0)
		{
    	if($payout_methods == 2)
		{
			$condition = '(reservation.transaction_id != 0 OR reservation.transaction_id = 0)';
			
			$accept_condition = 'accept_pay.transaction_id = ""';
		}
		else if($payout_methods == 1)
		{
			$condition = 'reservation.transaction_id = 23';
			
			$accept_condition = 'accept_pay.transaction_id != ""';
		}
		}
		else
			{
			 $condition = '(reservation.transaction_id != 0 OR reservation.transaction_id = 0)';
				
			 $accept_condition = '(accept_pay.transaction_id != "" OR accept_pay.transaction_id = "")';
			}
    }	

	if(isset($listing))
	{
		if($listing != 0)
		{
		$condition .= ' AND reservation.list_id ='.$listing;
		$accept_condition .= ' AND reservation.list_id ='.$listing;
		}
	}
	
	$user_id = $this->dx_auth->get_user_id();
		
	$query = $this->db->query("SELECT FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Date, 'Reservation' as Type,  list.title as List_Title, FROM_UNIXTIME(reservation.checkin, '%b %d, %Y') as CheckIn,FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Checkout, `reservation`.`currency` as Currency, IF(reservation.host_topay = 0,reservation.topay,reservation.host_topay) as Amount, IF(reservation.transaction_id = 0,'PayPal','Creditcard') as Payto FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1 AND $condition UNION ALL SELECT FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Date, 'Commission' as Type,  list.title as List_Title, FROM_UNIXTIME(reservation.checkin, '%b %d, %Y') as CheckIn,FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Checkout, `accept_pay`.`currency` as Currency, `accept_pay`.`amount` as Amount, IF(accept_pay.transaction_id = '','PayPal','Creditcard') as Payto FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN accept_pay on accept_pay.reservation_id = reservation.id WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1 AND $accept_condition");

	if(isset($page))
	{
		$param = $page;
	}
	else
	{
		$param = 1;
	}
	
	$start = $param;
                
                $per_page = 10;
				
				// Number of record showing per page
				$row_count = 10;
				
				if($start > 0)
							$offset			 = ($start-1) * $row_count;
				else
							$offset			 =  $start * $row_count; 
				
				$limit = $offset.', '.$row_count;
	
	$query_export = $this->db->query("SELECT reservation.book_date as short, FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Date, 'Reservation' as Type,  list.title as List_Title, FROM_UNIXTIME(reservation.checkin, '%b %d, %Y') as CheckIn,FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Checkout, `reservation`.`currency` as Currency, IF(reservation.host_topay = 0,IF(reservation.host_penalty = 0,reservation.topay,CONCAT(reservation.host_topay,'(Amount has been taken by admin due to penalty)')),reservation.host_topay) as Amount, IF(reservation.transaction_id = 0,'PayPal','PayPal') as Payto FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1 AND $condition UNION ALL SELECT reservation.book_date as short, FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Date, 'Commission' as Type,  list.title as List_Title, FROM_UNIXTIME(reservation.checkin, '%b %d, %Y') as CheckIn,FROM_UNIXTIME(reservation.checkout, '%b %d, %Y') as Checkout, `accept_pay`.`currency` as Currency, `accept_pay`.`amount` as Amount, IF(accept_pay.transaction_id = '','PayPal','Creditcard') as Payto FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN accept_pay on accept_pay.reservation_id = reservation.id WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1 AND $accept_condition ORDER BY short DESC LIMIT $limit");

	//UNIX_TIMESTAMP(STR_TO_DATE(Date, '%b %d, %Y %h %i %s'))
	 if(isset($export))
   {
   	$data = $query_export;
   	$this->load->dbutil();
		$delimiter = ",";
		$newline = "\r\n";
		$query=$this->dbutil->csv_from_result1($data, $delimiter, $newline); 
		$site_name  = $this->dx_auth->get_site_title();
		$name='Export-'.$site_name.'future_transaction.csv';
		force_download($name, $query);
   }

	if(isset($page))
	{
		$param = $page;
	}
	else
	{
		$param = 1;
	}
	//echo $param;exit;
    			//$param = $this->input->get('page');
   
                $start = $param;
                
                $per_page = 10;
				
				// Number of record showing per page
				$row_count = 10;
				
				if($start > 0)
							$offset			 = ($start-1) * $row_count;
				else
							$offset			 =  $start * $row_count; 
				
				$limit = $offset.', '.$row_count;
				
				  $data['result'] = $this->db->query("SELECT 'Reservation' as accept_pay, IF(reservation.transaction_id = 0,'PayPal','PayPal') as accept_pay_transaction_id, list.title,`reservation`.`coupon`, `reservation`.`transaction_id`, `reservation`.`id`, `reservation`.`list_id`, `reservation`.`userby`, `users`.`username`, `reservation`.`userto`, `reservation`.`checkin`, `reservation`.`checkout`, `reservation`.`no_quest`, `reservation`.`currency`, `reservation`.`price`, IF(reservation.host_topay = 0,IF(reservation.host_penalty = 0,reservation.topay,CONCAT(reservation.host_topay,'(Amount has been taken by admin due to penalty)')),reservation.host_topay) as topay, `reservation`.`admin_commission`, `reservation`.`credit_type`, `reservation`.`ref_amount`, `reservation`.`status`, `reservation`.`book_date`, `reservation`.`is_payed`, `reservation_status`.`name`, `reservation`.`cleaning`, `reservation`.`security`, `reservation`.`extra_guest_price`, `reservation`.`guest_count` FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1 AND $condition UNION ALL SELECT 'Commission' as accept_pay, IF(accept_pay.transaction_id = '','PayPal','Creditcard') as accept_pay_transaction_id, list.title,`reservation`.`coupon`, `reservation`.`transaction_id`, `accept_pay`.`reservation_id` as id, `reservation`.`list_id`, `reservation`.`userby`, `users`.`username`, `reservation`.`userto`, `reservation`.`checkin`, `reservation`.`checkout`, `reservation`.`no_quest`, `accept_pay`.`currency` as currency, `reservation`.`price`, `accept_pay`.`amount` as topay, `reservation`.`admin_commission`, `reservation`.`credit_type`, `reservation`.`ref_amount`, `reservation`.`status`, `reservation`.`book_date`, `reservation`.`is_payed`, `reservation_status`.`name`, `reservation`.`cleaning`, `reservation`.`security`, `reservation`.`extra_guest_price`, `reservation`.`guest_count` FROM `reservation` INNER JOIN `reservation_status` ON `reservation`.`status` = `reservation_status`.`id` JOIN `users` ON `reservation`.`userby` = `users`.`id` JOIN `list` ON `reservation`.`list_id` = `list`.`id` JOIN accept_pay on accept_pay.reservation_id = reservation.id WHERE `reservation`.`is_payed` = 0 AND `reservation`.`userto` = $user_id AND reservation.status != 1 AND $accept_condition ORDER BY book_date DESC LIMIT $limit");

//UNIX_TIMESTAMP(STR_TO_DATE(FROM_UNIXTIME(checkout, '%b %d, %Y'), '%b %d, %Y %h %i %s')) 
				// Get all transaction
				//$data['result']           = $this->Trips_model->get_transaction($conditions, $limit);
				//echo $this->db->last_query();exit;
				
				if($param == 2)
				{
					//echo $this->db->last_query();exit;
				}
				
				// Pagination config
				$config['base_url']       = site_url('account/transaction/future?payout_methods='.$payout_methods.'&listing='.$listing.'&per_page='.$row_count);
				
				$config['cur_page']   = $start;
				$config['total_rows']     = $query->num_rows();
				$config['per_page']       = $per_page;
				//$config['page_query_string'] = TRUE;
						
				// Init pagination
				$this->ajax_pagination->initialize($config);		
				// Create pagination links
				$data['pagination']       = $this->ajax_pagination->create_links(false);
				
				$data['status'] = 'future';
		 				
	$this->load->view('templates/blue/account/ajax_transaction', $data);
}

 public function mywishlist()
 {
	if(($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
	{	
	  $this->db->order_by('id','desc');		
	  $data['wishlist_category'] = $this->Common_model->getTableData('wishlists',array('user_id'=>$this->dx_auth->get_user_id()));
	  
	  $data['user_id'] = $this->dx_auth->get_user_id();	  
	  
	  $data['title']            = get_meta_details('My Wishlist','title');
	  $data["meta_keyword"]     = get_meta_details('My Wishlist','meta_keyword');
 	  $data["meta_description"] = get_meta_details('My Wishlist','meta_description');
	  $data['message_element']  = "account/view_wishlist";
	  $this->load->view('template', $data);
	}
	else
	{
		redirect('users/signin');
	}	
 }

public function wishlists($param)
{
	$this->session->set_userdata('wishlist_id',$param);
	
	$wishlists_data = $this->Common_model->getTableData('wishlists',array('id'=>$param));
	
	if($this->dx_auth->get_user_id() == $wishlists_data->row()->user_id)
	{
	$data['check_user'] = 1;
	$query = $this->db->where('user_wishlist.wishlist_id',$param)->join('wishlists','wishlists.id = user_wishlist.wishlist_id')->join('list','list.id = user_wishlist.list_id')->join('property_type','property_type.id = list.property_id')->get('user_wishlist');
	}
	else
	{
	$data['check_user'] = 0;	
	$query = $this->db->where('user_wishlist.wishlist_id',$param)->where('wishlists.privacy',0)->join('wishlists','wishlists.id = user_wishlist.wishlist_id')->join('list','list.id = user_wishlist.list_id')->join('property_type','property_type.id = list.property_id')->get('user_wishlist');
	}
	
	$data['wishlists'] = $query;
	
	$location_og = '';
	
	if($query->num_rows() != 0)
	{
		$i = 1;
		foreach($query->result() as $data_list)
		{
			$i++;
			if($i == $query->num_rows()+1 && $query->num_rows() != 1)
			{
			  $location_og .= ' and ';
			}
			$location_og .= $data_list->state.', '.$data_list->country;
			
			if($i != $query->num_rows()+1 && $i != $query->num_rows())
			{
			$location_og .= ' , ';
			}
		}
	}

	$data['wishlist_name'] = $wishlists_data->row()->name;
	$data['username'] = get_user_by_id($wishlists_data->row()->user_id)->username;
	
	$data['og_desc'] = $data['wishlist_name'].": ".$query->num_rows()." unique accommodations in ".$location_og.". An ".$this->dx_auth->get_site_title()." Wish List by ".$data['username'].".";
	
	$locations = '';
	
	if($query->num_rows() != 0)
	{
		foreach($query->result() as $row)
		{
			$conditions              = array('list_id' => $row->list_id,"is_featured"=>1);
	        $images          = $this->Gallery->get_imagesG(NULL, $conditions);
	        
	        if($images->num_rows() != 0)
	        $data['images'] = base_url().'images/'.$row->list_id.'/'.$images->row()->name;
			else
			$data['images'] = base_url().'images/'.$row->list_id.'/no_image.jpg';
			
			$address = $row->state.', '.$row->country;
			    		
    		$wishlist_user_check = $this->Common_model->getTableData('user_wishlist',array('list_id'=>$row->list_id,'user_id'=>$this->dx_auth->get_user_id()));
			
    		if(!$this->dx_auth->is_logged_in())
			{
    		
    		$wishlist_img = base_url().'images/search_heart_hover.png';
		   
			} 
			else if($wishlist_user_check->num_rows() != 0)
			{
		
			$wishlist_img = base_url().'images/heart_rose.png';	
			
			}
			else {
		
			$wishlist_img = base_url().'images/search_heart_hover.png';
			}
		
			$locations .= '["'.$row->title.'",'.$row->lat.','.$row->long.',"'.$address.'","'.$data['images'].'","'.$row->list_id.'","'.get_currency_value1($row->list_id,$row->price).'","'.get_currency_code().'","'.$wishlist_img.'"],';
		}
	}
	else
		{
			//redirect('info');
		}
		//echo $locations;exit;
	$check = $this->Common_model->getTableData('wishlists',array('id'=>$param));
	
	if($check->num_rows() == 0)
	{
		redirect('info');
	}

    $locations = rtrim($locations, ",");
	
	$data['locations'] = $locations;
			
	$data['user_id'] = $wishlists_data->row()->user_id;
		
	$data['privacy'] = $wishlists_data->row()->privacy;
	
	if($this->dx_auth->get_user_id() == 0 && $data['privacy'] == 1)
	{
		redirect('info');
	}
	
	$data['wishlist_details'] = $this->Common_model->getTableData('wishlists',array('id'=>$param))->row();
			
	$data['title']            = get_meta_details('My Wishlist','title');
	$data["meta_keyword"]     = get_meta_details('My Wishlist','meta_keyword');
 	$data["meta_description"] = get_meta_details('My Wishlist','meta_description');
    $data['message_element']  = "account/view_wishlist_inner";
	$this->load->view('template', $data);
}

public function users_wishlist($user_id)
{
	$this->db->order_by('id','desc');		
	$data['wishlist_category'] = $this->Common_model->getTableData('wishlists',array('user_id'=>$user_id,'privacy'=>0));
	
	if($data['wishlist_category']->num_rows() ==0)
	{
		redirect('info');
	}
	
	$data['user_id'] = $user_id;
		  	  
	$data['title']            = get_meta_details('My Wishlist','title');
	$data["meta_keyword"]     = get_meta_details('My Wishlist','meta_keyword');
 	$data["meta_description"] = get_meta_details('My Wishlist','meta_description');
	$data['message_element']  = "account/view_wishlist";
	$this->load->view('template', $data);
}

	function email_share()
	{
		extract($this->input->post());
		
		$username = get_user_by_id($this->dx_auth->get_user_id())->username;
		
		$toemail = $email_id;
		$message = $message;
		
		$admin_name  = $this->dx_auth->get_site_title();
		$admin_email = $this->dx_auth->get_site_sadmin();
		
		$user_wishlist = $this->Common_model->getTableData('user_wishlist',array('wishlist_id'=>$wishlist_id));	
  			
			if($user_wishlist->num_rows() != 0)
			{					
			   $conditions              = array('list_id' => $user_wishlist->row()->list_id,'is_featured'=>1);
			   $data['images']          = $this->Gallery->get_imagesG(NULL, $conditions);
			   
			   if($data['images']->num_rows() != 0)
			   {
			   	 $image = base_url().'images/'.$user_wishlist->row()->list_id.'/'.$data['images']->row()->name;
			   }
			}
		
		if(isset($image))
		{
		$src = $image;
		$img_div = '<div style="margin: 0; padding: 0; font-family: \'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif; font-weight: bold; font-size: 24px; line-height: 28px; padding-bottom: 10px;"><img class="CToWUd" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif; max-width: 100%; border: 0;width:650px;height:400px;" src="'.$src.'" alt="" /></div><br /><br />';
		}
		else
		{
		$img_div = '';
		}
		
		$link = base_url().'account/wishlists/'.$wishlist_id;
		
		$wishlist_name = $this->Common_model->getTableData('wishlists',array('id'=>$wishlist_id))->row()->name;
		
		$email_name = 'wish_list_email_share';
		$splVars    = array("{wishlist_name}"=>$wishlist_name,"{img_div}"=>$img_div,"{link}"=>$link,"{message}"=>$message, "{site_name}" => $admin_name, "{username}" => ucfirst($username));
		
		$email_explode = explode(',',$email_id);
		
		foreach($email_explode as $row_email)
		{
		$this->Email_model->sendMail($row_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
		}
	}		

	function security()
	{
	   if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
	   {
		$this->load->library('user_agent');  //load library
		
		$query = $this->db->query('SELECT if(user_agent REGEXP "[[:<:]]Chrome[[:>:]]" = 1,"Chrome",if(user_agent REGEXP "[[:<:]]Firefox[[:>:]]" = 1,"Firefox","")) as user_agent1, user_id, session_id, last_activity, logout, ip_address, user_agent, id, location FROM `login_history` where user_id = '.$this->dx_auth->get_user_id().' group by user_agent1,ip_address order by last_activity DESC');
				
		$data['login_historys'] = $query;

		$data['title']            = get_meta_details('Security','title');
		$data["meta_keyword"]     = get_meta_details('Security','meta_keyword');
 		$data["meta_description"] = get_meta_details('Security','meta_description');
		$data['message_element']  = "account/security";
		$this->load->view('template', $data);
	   }
	   else 
	   {
		redirect('users/signin');
	   }
	}
	
	
	
	
  	function settings()
	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) )
	   {
		
	    $data['title']            = get_meta_details('Setting','title');
		$data["meta_keyword"]     = get_meta_details('Setting','meta_keyword');
 		$data["meta_description"] = get_meta_details('Setting','meta_description');
	    $data['message_element']  = "account/setting";
		$this->load->view('template', $data);	
		}
   else 
	   {
		redirect(base_url());
	   }
		
	}
	
	function logout()
	{
		$login_history_id = $this->input->get('login_history_id');
		$session_id = $this->input->get('session_id');
		
		$this->Common_model->deleteTableData('ci_sessions',array('session_id'=>$session_id));
		$this->Common_model->deleteTableData('user_autologin',array('session_id'=>$session_id));
		$this->Common_model->updateTableData('login_history',0,array('id'=>$login_history_id),array('logout'=>1));
				
		if($session_id == $this->session->userdata('session_id'))
		{
		echo 'error';	
		}
		else
		{
		echo 'success';
		}
	}
   
    

	
}

/* End of file account.php */
/* Location: ./app/controllers/account.php */
?>