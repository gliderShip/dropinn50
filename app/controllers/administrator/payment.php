<?php
/**
 * DROPinn Admin Payment Controller Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package		DROPinn
 * @subpackage	Controllers
 * @category	Admin Payment
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com

 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller
{

	function Payment()
	{
			parent::__construct();
		
		// brain tree 1 start
               
               // brain tree 1 end                   
  
		
		$this->load->library('Table');
		$this->load->library('Pagination');
		$this->load->library('Twoco_Lib');
		
		$this->load->helper('form');
		$this->load->helper('url');
		
			//load validation library
		$this->load->library('form_validation');

		
		$this->load->model('Users_model');	
		$this->load->model('Trips_model');
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		
		// Protect entire controller so only admin, 
		// and users that have granted role in permissions table can access it.
		$this->dx_auth->check_uri_permissions();
		
		$api_user     = $this->Common_model->getTableData('payment_details', array('code' => 'CC_USER'))->row()->value;
		$api_pwd     = $this->Common_model->getTableData('payment_details', array('code' => 'CC_PASSWORD'))->row()->value;
		$api_key     = $this->Common_model->getTableData('payment_details', array('code' => 'CC_SIGNATURE'))->row()->value;
		
		$paymode     = $this->Common_model->getTableData('payments', array('payment_name' => 'Paypal'))->row()->is_live;
		
		if($paymode == 0)
		{
			$paymode = TRUE;
		}
		else
			{
				$paymode = FALSE;
			}
			$paypal_details = array(
// you can get this from your Paypal account, or from your
// test accounts in Sandbox
'API_username' => $api_user,
'API_signature' => $api_key,
'API_password' => $api_pwd,
// Paypal_ec defaults sandbox status to true
// Change to false if you want to go live and
// update the API credentials above
 'sandbox_status' => $paymode,
);
$this->load->library('paypal_ec', $paypal_details);
		
	}
	
	function index()
	{

		$this->form_validation->set_error_delimiters('<p>', '</p>');

		if($this->input->post())
		{	
			//Set rules
			$payId        = $this->input->post('name_gate');
			
			$this->form_validation->set_rules('name_gate','Pay Gateway','required|trim|xss_clean');
			if($payId == 1)
			{
			$this->form_validation->set_rules('pe_user','Payment Express Username','required|trim|xss_clean');
			$this->form_validation->set_rules('pe_password','Payment Express Password','required|trim|xss_clean');
			$this->form_validation->set_rules('pe_key','Payment Express Key','required|trim|xss_clean');
			}
			else if($payId == 2)
			{
			$this->form_validation->set_rules('paypal_id','Paypal Address Id','required|trim|xss_clean');
			}
			else {
			$this->form_validation->set_rules('ventor_id','Ventor ID','required|trim|xss_clean');
			$this->form_validation->set_rules('security','Security(2Checkout Username)','required|trim|xss_clean');
				}		
			if($this->form_validation->run())
			{	
			$payId        = $this->input->post('name_gate');
					
			if($payId == 1)
			{		
			$data1['value']    = $this->input->post('pe_user');
			$this->db->where('code', 'PE_USER');
			$this->db->update('payment_details',$data1);
			
			$data2['value']    = $this->input->post('pe_password');
			$this->db->where('code', 'PE_PASSWORD');
			$this->db->update('payment_details',$data2);
			
			$data3['value']    = $this->input->post('pe_key');
			$this->db->where('code', 'PE_KEY');
			$this->db->update('payment_details',$data3);
			}
			else if($payId == 2)
			{
			$data['value']    = $this->input->post('paypal_id');
			$this->db->where('code', 'PAYPAL_ID');
			$this->db->update('payment_details',$data);
			}
			else
			{
			$data1['value']    = $this->input->post('ventor_id');
			$this->db->where('code', '2C_VENTOR_ID');
			$this->db->update('payment_details',$data1);
			
			$data2['value']    = $this->input->post('security');
			$this->db->where('code', '2C_SECURITY');
			$this->db->update('payment_details',$data2);
			}
			
			$update['is_enabled'] = $this->input->post('is_active');
			$this->db->where('id', $payId);
			$this->db->update('payments',$update);
			
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Pay gateway added successfully.')));
			redirect_admin('payment/manage_gateway');
			}
	 }
		
	$query                  = $this->db->get_where('payments', array( 'is_enabled !=' => 1));
	$data['result']         = $query->result();
		
	$data['message_element'] = "administrator/payment/add_gateway";
	$this->load->view('administrator/admin_template', $data);
	}
	
	function manage_gateway($id = '')
	{
		$check = $this->input->post('check');
		if($this->input->post('delete'))
		{
				if(empty($check))
				{
					$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have select atleast one!')));
					redirect_admin('payment/manage_gateway');
				}
				
				foreach($check as $c)
				{
					$this->db->delete('payments', array('id' => $c));
				}
				
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Pay Gateway Deleted Successfully')));	
			redirect_admin('payment/manage_gateway');
		}
		else if($this->input->post('edit'))
		{
				if(empty($check))
				{
				 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have select atleast one!')));
					redirect_admin('payment/manage_gateway');
				}
				
				if(count($check) == 1)
				{
					$query                  = $this->db->get_where('payments', array( 'id' => $check[0]));
				 $data['result']         = $query->row();
					
					$query1                 = $this->db->get_where('payments');
					$data['payments']       = $query1->result();
					
					$data['pe_user']        = $this->db->get_where('payment_details', array('code' => 'CC_USER'))->row()->value;
					$data['pe_password']    = $this->db->get_where('payment_details', array('code' => 'CC_PASSWORD'))->row()->value;
					$data['pe_key']         = $this->db->get_where('payment_details', array('code' => 'CC_SIGNATURE'))->row()->value;
					
					$data['paypal_id']      = $this->db->get_where('payment_details', array('code' => 'PAYPAL_ID'))->row()->value;
					
					// brain tree 2 starts
                                       
			                // brain tree 2 end	    
                      
				$data['payId']           = $check[0];
				$data['message_element'] = "administrator/payment/edit_gateway";
				$this->load->view('administrator/admin_template', $data);
				}
				else
				{
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please select any one Pay Gateway to edit!')));
				redirect_admin('payment/manage_gateway');
				}
		}
		else if($this->input->post('update'))
		{
		
	      $this->form_validation->set_error_delimiters('<p>', '</p>');

		
			//Set rules
			$payId        = $this->input->post('payId');

			// brain tree 3 start
                       
                        //brain tree 3 end( changed below if condition as else if )
			if($payId == 2)
			{
			$this->form_validation->set_rules('paypal_id','Paypal Address Id','required|trim|xss_clean');
			$this->form_validation->set_rules('pe_user','Payment Express Username','required|trim|xss_clean');
			$this->form_validation->set_rules('pe_password','Payment Express Password','required|trim|xss_clean');
			$this->form_validation->set_rules('pe_key','Payment Express Key','required|trim|xss_clean');
			}
			else {
			$this->form_validation->set_rules('ventor_id','Ventor ID','required|trim|xss_clean');
			$this->form_validation->set_rules('security','Security(2Checkout Username)','required|trim|xss_clean');
				}		
			if($this->form_validation->run())
			{			
		 	$payId = $this->input->post('payId');
			$paypal_id = $this->input->post('paypal_id');
			// brain tree 4 start
                        if($payId == 1)
			{		
			
                       $data1['value']    = $this->input->post('merchantId');
			$this->db->where('code', 'BT_MERCHANT');
			$this->db->update('payment_details',$data1);
			
			$data2['value']    = $this->input->post('publicKey');
			$this->db->where('code', 'BT_PUBLICKEY');
			$this->db->update('payment_details',$data2);
			
			$data3['value']    = $this->input->post('privateKey');
			$this->db->where('code', 'BT_PRIVATEKEY');
			$this->db->update('payment_details',$data3);		
			
			$updateData['is_enabled'] = $this->input->post('is_active');
			$updateData['is_live']    = $this->input->post('type');
			$this->db->where('id', 1);
			$this->db->update('payments', $updateData);
			}
                        // brain tree 4 end( changed below if condition as else if ) 
			if($payId == 2)
			{
			$data['value']    = $this->input->post('paypal_id');
			$this->db->where('code', 'PAYPAL_ID');
			$this->db->update('payment_details',$data);
			
			$updateData['is_enabled'] = $this->input->post('is_active');
			$updateData['is_live']    = $this->input->post('paypal_url');
			$this->db->where('id', 2);
			$this->db->update('payments', $updateData);
			
			$data1['value']    = $this->input->post('pe_user');
			$this->db->where('code', 'CC_USER');
			$this->db->update('payment_details',$data1);
			
			$data2['value']    = $this->input->post('pe_password');
			$this->db->where('code', 'CC_PASSWORD');
			$this->db->update('payment_details',$data2);
			
			$data3['value']    = $this->input->post('pe_key');
			$this->db->where('code', 'CC_SIGNATURE');
			$this->db->update('payment_details',$data3);		
			
			$updateData['is_enabled'] = $this->input->post('is_active');
			$updateData['is_live']    = $this->input->post('paypal_url');
			$this->db->where('id', 2);
			$this->db->update('payments', $updateData);
			}
			else
			{
			$data1['value']    = $this->input->post('ventor_id');
			$this->db->where('code', '2C_VENTOR_ID');
			$this->db->update('payment_details',$data1);
			
			$data2['value']    = $this->input->post('security');
			$this->db->where('code', '2C_SECURITY');
			$this->db->update('payment_details',$data2);
			}
			
			$update['is_enabled'] = $this->input->post('is_active');
			$this->db->where('id', $payId);
			$this->db->update('payments',$update);
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Payment changes updated successfully')));
			redirect_admin('payment/manage_gateway');
			} else
			{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please enter the values to the required fields')));
			redirect_admin('payment/manage_gateway');
			}

		}
		else
		{
				if(isset($id) && $id != '')
				{
							$get = $this->db->get_where('payments', array( 'id' => $id))->row();
							//echo $get->is_enabled;exit;
							if($get->is_enabled == 1)
							{
									$change = 0;
							}
							else
							{
									$change = 1;
							}
							
							$data['is_enabled']   = $change;
							$this->db->where('id', $id);
							$this->db->update('payments',$data);
				}
		$data['payments']        = $this->db->get_where('payments');
		
		$data['message_element'] = "administrator/payment/manage_gateway";
		$this->load->view('administrator/admin_template', $data);
		}
	}
	
	function paymode($id = '')
	{
		$check = $this->input->post('check');
		if($this->input->post('edit'))
		{
				if(empty($check))
				{
				 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have select atleast one!')));
					redirect_admin('payment/paymode');
				}
			if(count($check) == 1)
			{
		 	$data['payId'] = $check[0];
			$data['currency'] = $this->Common_model->getTableData('currency',array('status'=>1));
					if($check[0] == 1)
					{
					$data['result'] = $this->db->get_where('paymode', array( 'id' => 1))->row();
					$data['message_element'] = "administrator/payment/list_pay";
					$this->load->view('administrator/admin_template', $data);
					}
					else if($check[0] == 2)
					{
					$data['result'] = $this->db->get_where('paymode', array( 'id' => 2))->row();
					$data['message_element'] = "administrator/payment/accommodation_pay";
					$this->load->view('administrator/admin_template', $data);	
					}
					else
					{
					$data['result'] = $this->db->get_where('paymode', array( 'id' => 3))->row();
					$data['message_element'] = "administrator/payment/reservation_request";
					$this->load->view('administrator/admin_template', $data);
					}
			}
			else
			{
			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please select any one pay mode to edit!')));
				redirect_admin('payment/paymode');
			}
		}
		else if($this->input->post('update'))
		{ 
	 	$payId = $this->input->post('payId');
			
			$data['is_premium']        = $this->input->post('is_premium');
			if($this->input->post('percentage_amount') > 99)
			{
				echo "<p class='percentage_commission'>Please give the below 100%.</p>";
			}
			elseif($this->input->post('percentage_amount') == 0) {
				echo "<p class='percentage_commission'>Please mention the percentage.</p>";
			}
           else{
				
if($this->input->post('fixed_amount') == 0)
{
echo "<p class='percentage_commission'>Please give the correct amount.</p>";exit;
}
else if(strlen($this->input->post('fixed_amount')) > 14)
{
echo "<p class='percentage_commission'>Please give the minimum amount.Maximum digit is 14.</p>";exit;
}
else {
            $data['is_fixed']          = $this->input->post('is_fixed');
			$data['fixed_amount']      = $this->input->post('fixed_amount');
			$data['percentage_amount'] = $this->input->post('percentage_amount');
			$data['currency'] = $this->input->post('currency');
			
			$this->db->where('id', $payId);
			$this->db->update('paymode',$data);
			
		   echo "<p>Updated Successfully.</p>";
}
			}
			
		}
		else
		{
				if(isset($id) && $id != '')
				{
							$get = $this->db->get_where('paymode', array( 'id' => $id))->row();
							if($get->is_premium == 1)
							{
									$change = 0;
							}
							else
							{
									$change = 1;
							}
							
							$data['is_premium']   = $change;
							$this->db->where('id', $id);
							$this->db->update('paymode',$data);
				}
		$data['payMode'] = $this->db->get('paymode');
	
	 $data['message_element'] = "administrator/payment/paymode";
	 $this->load->view('administrator/admin_template', $data);
		}
	}
	
	function finance()
	{
		$query          = $this->Trips_model->get_reservation();
	// Get offset and limit for page viewing
		$start          = (int) $this->uri->segment(4,0);
		
	 // Number of record showing per page
		$row_count      = 10;
		
		if($start > 0)
		   $offset			   = ($start-1) * $row_count;
		else
		   $offset			   =  $start * $row_count; 
		
		// Get all users
		$limits         =  array($row_count, $offset);                
		
		$data['result'] =  $this->Trips_model->get_reservation(NULL, $limits);
		
		//echo $data['result']->num_rows();exit;
		// Pagination config
		$p_config['base_url']    = site_url('administrator/payment/finance');
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $row_count;
			
		// Init pagination
		$this->pagination->initialize($p_config);		
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
		$data['message_element'] = "administrator/payment/reservation_list";
	 $this->load->view('administrator/admin_template', $data);
	}
function paid_payments(){
		$condition=array('is_payed'=>1);
		$query          = $this->Trips_model->get_reservation($condition);
		 $data=$query->result_array();
		
		//echo $this->db->last_query(); exit;
		// Get offset and limit for page viewing
		$start          = (int) $this->uri->segment(4,0);
		
	 // Number of record showing per page
		$row_count      = 10;
		
		if($start > 0)
		   $offset			   = ($start-1) * $row_count;
		else
		   $offset			   =  $start * $row_count; 
		
		// Get all users
		$limits         =  array($row_count, $offset);                
		
		$data['result'] =  $this->Trips_model->get_reservation($condition, $limits);
		//echo $data['result']->num_rows();exit;
		// Pagination config
		$p_config['base_url']    = site_url('administrator/payment/paid_payments');
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $row_count;
			
		// Init pagination
		$this->pagination->initialize($p_config);		
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
		$data['message_element'] = "administrator/payment/payed_payments";
	 $this->load->view('administrator/admin_template', $data);
	}
	function pending_payments(){
		$condition=array('is_payed'=>0, 'reservation.status'=>10);
		$query          = $this->Trips_model->get_reservation($condition);
		//echo $this->db->last_query(); exit;
		// Get offset and limit for page viewing
		$start          = (int) $this->uri->segment(4,0);
		
	 // Number of record showing per page
		$row_count      = 10;
		
		if($start > 0)
		   $offset			   = ($start-1) * $row_count;
		else
		   $offset			   =  $start * $row_count; 
		
		// Get all users
		$limits         =  array($row_count, $offset);                
		$condition_or=array('reservation.status'=>5);
		$condtion_orr=array('reservation.status'=>4);
					//$this->db->limit($limits[0],$limits[1]);
		//$data['result'] =  $this->Trips_model->get_reservation($condition, $limits, $condition_or,$condtion_orr);
		$this->db->select("reservation.coupon,reservation.transaction_id,reservation.id,reservation.list_id,reservation.userby,users.username,
					reservation.userto,reservation.checkin,reservation.checkout,reservation.host_penalty,reservation.no_quest,
					reservation.currency,reservation.price,reservation.topay ,reservation.admin_commission ,reservation.contacts_offer,
					reservation.credit_type,reservation.ref_amount,reservation.status,reservation.book_date,
					reservation.is_payed,reservation_status.name,reservation.cleaning,reservation.security,reservation.extra_guest_price,
					reservation.guest_count,reservation.host_topay,reservation.guest_topay,reservation.policy,reservation.cancel_date,reservation.is_payed_host,reservation.is_payed_guest");
		$this->db->from('reservation');
		$this->db->join('reservation_status', 'reservation.status = reservation_status.id','inner');
		$this->db->join('users','reservation.userby = users.id');
		$this->db->join('list','reservation.list_id = list.id');
		$this->db->where('reservation.is_payed',0)->where("reservation.status = 4");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 2");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 5");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 6");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 10");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 11");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 12");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 13");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 8");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 9");
		$data['result']=$this->db->get();
		//echo $data['result']->num_rows();exit;
		// Pagination config
		$p_config['base_url']    = site_url('administrator/payment/pending_payments');
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $row_count;
			
		// Init pagination
		$this->pagination->initialize($p_config);		
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
		
		$data['message_element'] = "administrator/payment/pending_payments";
	 $this->load->view('administrator/admin_template', $data);
	}
	function export_pay(){
	  	//include 'includes/config.php'; 
	  	$admin_currency=$this->db->query("select `currency_code` from `currency` where `default`= 1")->row()->currency_code;
 		$filename = "Paid_Payments.xls"; // File Name
		$condition=array('is_payed'=>0,'is_payed_host'=>0,'is_payed_guest'=>0);
		//$query          = $this->Trips_model->get_reservation($condition);
		$this->db->select("title, users.username,users.id,list.currency,list.price,reservation_status.name,");
		$this->db->from('reservation');
		$this->db->join('list','list.id=reservation.list_id');
		$this->db->join('users','users.id=reservation.userby' );
		$this->db->join('reservation_status','reservation_status.id=reservation.status');
		$this->db->where('reservation.is_payed',1);
		$query=$this->db->get();
		
		//echo $this->db->last_query(); exit;
		$data=$query->result_array();
		// Download file
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header("Content-Type: application/vnd.ms-excel");
		//header('Content-Transfer-Encoding: binary');
		$flag = false;
		echo ("List Name");
		echo ("\t Traveller Name ");
		echo ("\t Traveller ID ");
		echo ("\t Currency ");
		echo ("\t Total Price ");
		echo ("\t Status ");
		echo ("\t Is Payed \r\n");
		foreach($data as $row){
      	if(!$flag) {
      	// display field/column names as first row
      	
      		$flag = true;
    	}   	
    	echo implode("\t", array_values($row)) . "\t Yes \r\n";
		
	    }
		//redirect_admin('payment/payed_payments');
	
		}
	function export_pend(){
	  	//include 'includes/config.php'; 
	  	$admin_currency=$this->db->query("select `currency_code` from `currency` where `default`= 1")->row()->currency_code;
 		$filename = "Pending_Payments.xls"; // File Name
		$condition=array('is_payed'=>0,'is_payed_host'=>0,'is_payed_guest'=>0);
		//$query          = $this->Trips_model->get_reservation($condition);
		$this->db->select("title, users.username,users.id,list.currency,list.price,reservation_status.name,");
		$this->db->from('reservation');
		$this->db->join('list','list.id=reservation.list_id');
		$this->db->join('users','users.id=reservation.userby' );
		$this->db->join('reservation_status','reservation_status.id=reservation.status');
		$this->db->where('reservation.is_payed',0);
		$query=$this->db->get();
		
		//echo $this->db->last_query(); exit;
		$data=$query->result_array();
		// Download file
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header("Content-Type: application/vnd.ms-excel");
		//header('Content-Transfer-Encoding: binary');
		$flag = false;
		echo ("List Name");
		echo ("\t Traveller Name ");
		echo ("\t Traveller ID ");
		echo ("\t Currency ");
		echo ("\t Total Price ");
		echo ("\t Status ");
		echo ("\t Is Payed \r\n");
		foreach($data as $row){
      	if(!$flag) {
      	// display field/column names as first row
      	
      		$flag = true;
    	}   	
    	echo implode("\t", array_values($row)) . "\t No \r\n";
		
	    }
	//redirect_admin('payment/pending_payments');
		}
function export_payed(){
		$date=date("j-m-Y", now());
		$admin_currency=$this->db->query("select `currency_code` from `currency` where `default`= 1")->row()->currency_code;
 		$filename = "Paid_Payments-".$date; // File Name
		$condition=array('is_payed'=>0,'is_payed_host'=>0,'is_payed_guest'=>0);
		//$query          = $this->Trips_model->get_reservation($condition);
		$this->db->select("title, users.username,users.id,reservation.currency,reservation.price,reservation_status.name,reservation.book_date");
		$this->db->from('reservation');
		$this->db->join('list','list.id=reservation.list_id');
		$this->db->join('users','users.id=reservation.userby' );
		$this->db->join('reservation_status','reservation_status.id=reservation.status');
		$this->db->where('reservation.is_payed',1);
		$query=$this->db->get();
		
		//echo $this->db->last_query(); exit;
		$array=$query->result_array();
		
		header('Content-Disposition: attachment; filename=' . $filename . '.xls');
       header('Content-type: application/force-download');
       header('Content-Transfer-Encoding: binary');
       header('Pragma: public');
       //print "\xEF\xBB\xBF"; // UTF-8 BOM
       $h = array();
       foreach ($array as $row) {
           foreach ($row as $key => $val) {
               if (!in_array($key, $h)) {
                   $h[] = $key;
               }
           }
       }
       echo '<table border="1"><tr>';
      
           echo '<th>  List Name </th>';
           echo '<th>  Traveller Name </th>';
           echo '<th>  Traveller Id </th>';
		   echo '<th>  Currency </th>';
		   echo '<th>  Total Price </th>';
		   echo '<th>  Status </th>';
		   echo '<th>  Booked Date(YYYY-MM-DD)&</br>Time</th>';
     		
       echo '</tr>';
 
      for ($i=0; $i<$query->num_rows(); $i++){
           echo '<tr>';
           echo '<td>' . $array[$i]['title'] . '</td>';
           echo '<td>' . $array[$i]['username'] . '</td>';
		   echo '<td>' . $array[$i]['id'] . '</td>';
		   echo '<td>' . $array[$i]['currency'] . '</td>';
		   echo '<td>' . $array[$i]['price'] . '</td>';
		   echo '<td>' . $array[$i]['name'] . '</td>';
		   echo '<td>' .unix_to_human($array[$i]['book_date']) . '</td>';
           echo '</tr>';
     
     }
 
 
       echo '</table>';
   
	}
	
	function export_pending(){
		$date=date("j-m-Y", now());
		$admin_currency=$this->db->query("select `currency_code` from `currency` where `default`= 1")->row()->currency_code;
 		$filename = "Pending_Payments-".$date; // File Name
		$condition=array('is_payed'=>0,'is_payed_host'=>0,'is_payed_guest'=>0);
		//$query          = $this->Trips_model->get_reservation($condition);
		$this->db->select("title, users.username,users.id,reservation.currency,reservation.price,reservation_status.name,reservation.book_date");
		$this->db->from('reservation');
		$this->db->join('list','list.id=reservation.list_id');
		$this->db->join('users','users.id=reservation.userby' );
		$this->db->join('reservation_status','reservation_status.id=reservation.status');
		$this->db->where('reservation.is_payed',0)->where("reservation.status = 4");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 5");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 2");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 6");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 10");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 11");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 12");
		$this->db->or_where('reservation.is_payed',0)->where("reservation.status = 13");
		$query=$this->db->get();
		
		//echo $this->db->last_query(); exit;
		$array=$query->result_array();
		
		header('Content-Disposition: attachment; filename=' . $filename . '.xls');
       header('Content-type: application/force-download');
       header('Content-Transfer-Encoding: binary');
       header('Pragma: public');
       //print "\xEF\xBB\xBF"; // UTF-8 BOM
       $h = array();
       foreach ($array as $row) {
           foreach ($row as $key => $val) {
               if (!in_array($key, $h)) {
                   $h[] = $key;
               }
           }
       }
       echo '<table border="1"><tr>';
      
           echo '<th>  List Name </th>';
           echo '<th>  Traveller Name </th>';
           echo '<th>  Traveller Id </th>';
		   echo '<th>  Currency </th>';
		   echo '<th>  Total Price </th>';
		   echo '<th>  Status </th>';
		   echo '<th>  Booked Date(YYYY-MM-DD)&</br>Time</th>';
     		
       echo '</tr>';
 
       for ($i=0; $i<$query->num_rows(); $i++){
           echo '<tr>';
           echo '<td>' . $array[$i]['title'] . '</td>';
           echo '<td>' . $array[$i]['username'] . '</td>';
		   echo '<td>' . $array[$i]['id'] . '</td>';
		   echo '<td>' . $array[$i]['currency'] . '</td>';
		   echo '<td>' . $array[$i]['price'] . '</td>';
		   echo '<td>' . $array[$i]['name'] . '</td>';
		   echo '<td>' .unix_to_human($array[$i]['book_date']) . '</td>';
           echo '</tr>';
     
     }
 
 
       echo '</table>';
   
	}
	
	
	function details($param = '')
	{
		$result = $this->db->where('reservation.id',$param)->get('reservation');
		
		if($result->num_rows() !=0 )
		{
	  	$conditions              = array("reservation.id" => $param);
		$data['result']          = $row = $this->Trips_model->get_reservation($conditions)->row();
		
		$query                   = $this->Users_model->get_user_by_id($row->userby);
		$data['booker_name']     = $query->row()->username;
		
		$query1                  = $this->Users_model->get_user_by_id($row->userto);
	  	$data['hotelier_name']   = $query1->row()->username;
	  
	 	$data['currency_code'] = $data['result']->currency;
		        
        if($data['result']->host_topay == 0 && $data['result']->guest_topay == 0)
		{
        $data['topay'] = $data['result']->topay;
		}

		if($data['result']->host_topay != 0)
		{
		$data['host_topay'] = $data['result']->host_topay;
		}

		if($data['result']->guest_topay != 0 )
		{
		$data['guest_topay'] = $data['result']->guest_topay;
		}

		if($data['result']->host_penalty != 0 )
		{
		$data['host_penalty'] = $data['result']->host_penalty;
		}

        
	  if($data['result']->coupon != 0)
	  {	  
	  $data['coupon_price'] = $this->Common_model->getTableData('coupon',array('couponcode'=>$data['result']->coupon))->row()->coupon_price;
	  $data['coupon_currency'] = $this->Common_model->getTableData('coupon',array('couponcode'=>$data['result']->coupon))->row()->currency;
	  }
      	  
	  $data['currency_symbol'] = $this->Common_model->getTableData('currency',array('currency_code'=>$data['result']->currency))->row()->currency_symbol;
	
		$data['message_element'] = "administrator/payment/view_details";

	   $this->load->view('administrator/admin_template', $data);
		}
		else {
	        redirect_admin('payment/finance');
		}
	}
		
	function toPay()
	{
		
	
             	$result=$this->input->post();
		//print_r($result['to_pay']);
	  
	     if(isset($result['to_pay']))
	  {
	   $this->session->set_userdata('host_topay',$result['to_pay']); 
	  }

	$check_paypal = $this->db->where('is_enabled',1)->where('payment_name','Paypal')->get('payments')->num_rows();
			if($check_paypal == 0)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate("Payment gateway is not enabled. Please enable it.")));
			redirect_admin('payment/finance');
		}
	    	
                 
       
	  if($this->input->post('payviapaypal'))
			{
							
		$check_paypal = $this->db->where('is_enabled',1)->where('payment_name','Paypal')->get('payments')->num_rows();
		
			$list_id          = $this->input->post('list_id');
					$reservation_id   = $this->input->post('reservation_id');
					$amount           = $this->input->post('to_pay');
					$business         = $this->input->post('biz_id');
					$currency_code    = $this->input->post('currency');

					// Verify return
					$custom = $list_id.','.$reservation_id.','.$business.','.$amount;
					
/*if($currency_code == 'INR' || $currency_code == 'MYR' || $currency_code == 'ARS' || 
$currency_code == 'CNY' || $currency_code == 'IDR' || $currency_code == 'KRW' 
|| $currency_code == 'VND' || $currency_code == 'ZAR' || $currency_code == 'MXN')
{*/
	$currency_code1 = 'USD';
	$amount = get_currency_value_lys($currency_code,$currency_code1,$amount);
	$this->session->set_userdata('currency_code',$currency_code1);
/*}
else
	{
		$currency_code1 = $currency_code;
		$amount = $amount;
		$this->session->set_userdata('currency_code',$currency_code1);
	}*/
	
		$this->session->set_userdata('custom',$custom);
		
		$currency = $currency_code1;

$result  = $this->Common_model->getTableData( 'reservation',array('id' => $reservation_id) )->row();	

$host_payout_id = $business;	

		// echo $host_payout_id->email;exit;
// Set request-specific fields.
$vEmailSubject = 'PayPal payment';
$emailSubject = urlencode($vEmailSubject);
$receiverType = urlencode($host_payout_id);
$currency = urlencode($currency_code1); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

// Receivers
// Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
// Here you can modify to obtain array data from database.

$receivers = array(
  0 => array(
    'receiverEmail' => "$host_payout_id", 
    'amount' => "$amount",
    'uniqueID' => "$reservation_id", // 13 chars max
    'note' => " payment of commissions")
);
$receiversLenght = count($receivers);

// Add request-specific fields to the request string.
$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=USD";

$receiversArray = array();

for($i = 0; $i < $receiversLenght; $i++)
{
 $receiversArray[$i] = $receivers[$i];
}

foreach($receiversArray as $i => $receiverData)
{
 $receiverEmail = urlencode($receiverData['receiverEmail']);
 $amount = urlencode($receiverData['amount']);
 $uniqueID = urlencode($receiverData['uniqueID']);
 $note = urlencode($receiverData['note']);
 $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
}
//echo $receiverEmail;exit;
$this->session->set_userdata('payout_id',$receiverEmail);
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr);
//echo $nvpStr;exit;

if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
{
 //exit('MassPay Completed Successfully: ' . print_r($httpParsedResponseAr, true));
 $this->session->set_userdata('paid_to','common');
 redirect_admin('payment/paypal_success/'.$reservation_id);
}
else
{
	$this->session->set_userdata('error_msg',str_replace('%20',' ',$httpParsedResponseAr['L_LONGMESSAGE0']));
 //exit('MassPay failed: ' . print_r($httpParsedResponseAr['L_LONGMESSAGE0'], true));exit;
 redirect_admin('payment/paypal_cancel');
}
			}
else if($this->input->post('payviapaypal_guest'))
			{
			//echo $this->input->post('host_to_pay');exit;				
		$check_paypal = $this->db->where('is_enabled',1)->where('payment_name','Paypal')->get('payments')->num_rows();
		
		if($check_paypal == 0)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate("Payment gateway is not enabled. Please enable it.")));
			redirect_admin('payment/finance');
		}
	    			$list_id          = $this->input->post('list_id');
					$reservation_id   = $this->input->post('reservation_id');
					$amount           = $this->input->post('guest_to_pay');
					$business         = $this->input->post('guest_biz_id');
					$currency_code    = $this->input->post('currency');

					// Verify return
					$custom = $list_id.','.$reservation_id.','.$business.','.$amount;
					
/*if($currency_code == 'INR' || $currency_code == 'MYR' || $currency_code == 'ARS' || 
$currency_code == 'CNY' || $currency_code == 'IDR' || $currency_code == 'KRW' 
|| $currency_code == 'VND' || $currency_code == 'ZAR' || $currency_code == 'MXN')
{*/
	$currency_code1 = 'USD';
	$amount = get_currency_value_lys($currency_code,$currency_code1,$amount);
	$this->session->set_userdata('currency_code',$currency_code1);
/*}
else
	{
		$currency_code1 = $currency_code;
		$amount = $amount;
		$this->session->set_userdata('currency_code',$currency_code1);
	}*/
	
		$this->session->set_userdata('custom',$custom);
		
		$currency = $currency_code1;

$result  = $this->Common_model->getTableData( 'reservation',array('id' => $reservation_id) )->row();	

$host_payout_id = $business;	

		// echo $host_payout_id->email;exit;
// Set request-specific fields.
$vEmailSubject = 'PayPal payment';
$emailSubject = urlencode($vEmailSubject);
$receiverType = urlencode($host_payout_id);
$currency = urlencode($currency_code1); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

// Receivers
// Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
// Here you can modify to obtain array data from database.

$receivers = array(
  0 => array(
    'receiverEmail' => "$host_payout_id", 
    'amount' => "$amount",
    'uniqueID' => "$reservation_id", // 13 chars max
    'note' => " payment of commissions")
);
$receiversLenght = count($receivers);

// Add request-specific fields to the request string.
$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=USD";

$receiversArray = array();

for($i = 0; $i < $receiversLenght; $i++)
{
 $receiversArray[$i] = $receivers[$i];
}

foreach($receiversArray as $i => $receiverData)
{
 $receiverEmail = urlencode($receiverData['receiverEmail']);
 $amount = urlencode($receiverData['amount']);
 $uniqueID = urlencode($receiverData['uniqueID']);
 $note = urlencode($receiverData['note']);
 $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
}
//echo $receiverEmail;exit;
$this->session->set_userdata('payout_id',$receiverEmail);
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr);
//echo $nvpStr;exit;

if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
{
 //exit('MassPay Completed Successfully: ' . print_r($httpParsedResponseAr, true));
 $this->session->set_userdata('paid_to','guest');
 redirect_admin('payment/paypal_success/'.$reservation_id);
}
else
{
	$this->session->set_userdata('error_msg',str_replace('%20',' ',$httpParsedResponseAr['L_LONGMESSAGE0']));
 //exit('MassPay failed: ' . print_r($httpParsedResponseAr['L_LONGMESSAGE0'], true));exit;
 redirect_admin('payment/paypal_cancel');
}
			}
if($this->input->post('payviapaypal_host'))
			{
							
		$check_paypal = $this->db->where('is_enabled',1)->where('payment_name','Paypal')->get('payments')->num_rows();
		
		if($check_paypal == 0)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate("Payment gateway is not enabled. Please enable it.")));
			redirect_admin('payment/finance');
		}
	    			$list_id          = $this->input->post('list_id');
					$reservation_id   = $this->input->post('reservation_id');
					$amount           = $this->input->post('host_to_pay');
					$business         = $this->input->post('host_biz_id');
					$currency_code    = $this->input->post('currency');

					// Verify return
					$custom = $list_id.','.$reservation_id.','.$business.','.$amount;
					
/*if($currency_code == 'INR' || $currency_code == 'MYR' || $currency_code == 'ARS' || 
$currency_code == 'CNY' || $currency_code == 'IDR' || $currency_code == 'KRW' 
|| $currency_code == 'VND' || $currency_code == 'ZAR' || $currency_code == 'MXN')
{*/
	$currency_code1 = 'USD';
	$amount = get_currency_value_lys($currency_code,$currency_code1,$amount);
	$this->session->set_userdata('currency_code',$currency_code1);
/*}
else
	{
		$currency_code1 = $currency_code;
		$amount = $amount;
		$this->session->set_userdata('currency_code',$currency_code1);
	}*/
	
		$this->session->set_userdata('custom',$custom);
		
		$currency = $currency_code1;

$result  = $this->Common_model->getTableData( 'reservation',array('id' => $reservation_id) )->row();	

$host_payout_id = $business;	

		// echo $host_payout_id->email;exit;
// Set request-specific fields.
$vEmailSubject = 'PayPal payment';
$emailSubject = urlencode($vEmailSubject);
$receiverType = urlencode($host_payout_id);
$currency = urlencode($currency_code1); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

// Receivers
// Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
// Here you can modify to obtain array data from database.

$receivers = array(
  0 => array(
    'receiverEmail' => "$host_payout_id", 
    'amount' => "$amount",
    'uniqueID' => "$reservation_id", // 13 chars max
    'note' => " payment of commissions")
);
$receiversLenght = count($receivers);

// Add request-specific fields to the request string.
$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=USD";

$receiversArray = array();

for($i = 0; $i < $receiversLenght; $i++)
{
 $receiversArray[$i] = $receivers[$i];
}

foreach($receiversArray as $i => $receiverData)
{
 $receiverEmail = urlencode($receiverData['receiverEmail']);
 $amount = urlencode($receiverData['amount']);
 $uniqueID = urlencode($receiverData['uniqueID']);
 $note = urlencode($receiverData['note']);
 $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
}
//echo $receiverEmail;exit;
$this->session->set_userdata('payout_id',$receiverEmail);
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr);
//echo $nvpStr;exit;

if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
{
 $this->session->set_userdata('paid_to','host');
 //exit('MassPay Completed Successfully: ' . print_r($httpParsedResponseAr, true));
 redirect_admin('payment/paypal_success/'.$reservation_id);
}
else
{
	$this->session->set_userdata('error_msg',str_replace('%20',' ',$httpParsedResponseAr['L_LONGMESSAGE0']));
 //exit('MassPay failed: ' . print_r($httpParsedResponseAr['L_LONGMESSAGE0'], true));exit;
 redirect_admin('payment/paypal_cancel');
}
			}
else if($this->input->post('payviapaypal_accept_pay'))
			{
				
				$accept_pay_result = $this->Common_model->getTableData('accept_pay',array('id'=>$this->input->post('accept_pay')))->row();
	
	$check_paypal = $this->db->where('is_enabled',1)->where('payment_name','Paypal')->get('payments')->num_rows();
		
		if($check_paypal == 0)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate("Payment gateway is not enabled. Please enable it.")));
			redirect_admin('payment/finance');
		}
		
		
	    $list_id          = $this->input->post('list_id');
					$reservation_id   = $this->input->post('reservation_id');
					$amount           = $accept_pay_result->amount;
					$business         = $this->input->post('biz_host_id');
					$currency_code    = $accept_pay_result->currency;

					// Verify return
					$custom = $list_id.','.$reservation_id.','.$business.','.$amount;
						
		$this->session->set_userdata('custom',$custom);
		
		$currency = $currency_code;

$result  = $this->Common_model->getTableData( 'reservation',array('id' => $reservation_id) )->row();	

$host_payout_id = $business;	

		// echo $host_payout_id->email;exit;
// Set request-specific fields.
$vEmailSubject = 'PayPal payment';
$emailSubject = urlencode($vEmailSubject);
$receiverType = urlencode($host_payout_id);
$currency = urlencode($currency_code); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

// Receivers
// Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
// Here you can modify to obtain array data from database.

$receivers = array(
  0 => array(
    'receiverEmail' => "$host_payout_id", 
    'amount' => "$amount",
    'uniqueID' => "$reservation_id", // 13 chars max
    'note' => " payment of commissions")
);
$receiversLenght = count($receivers);

// Add request-specific fields to the request string.
$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=USD";

$receiversArray = array();

for($i = 0; $i < $receiversLenght; $i++)
{
 $receiversArray[$i] = $receivers[$i];
}

foreach($receiversArray as $i => $receiverData)
{
 $receiverEmail = urlencode($receiverData['receiverEmail']);
 $amount = urlencode($receiverData['amount']);
 $uniqueID = urlencode($receiverData['uniqueID']);
 $note = urlencode($receiverData['note']);
 $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
}
$this->session->set_userdata('payout_id',$receiverEmail);
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr);
//echo $nvpStr;exit;

if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
{
 //exit('MassPay Completed Successfully: ' . print_r($httpParsedResponseAr, true));
 redirect_admin('payment/paypal_commission_success/'.$accept_pay_result->id);
}
else
{
	$this->session->set_userdata('error_msg',str_replace('%20',' ',$httpParsedResponseAr['L_LONGMESSAGE0']));
 //exit('MassPay failed: ' . print_r($httpParsedResponseAr['L_LONGMESSAGE0'], true));exit;
 redirect_admin('payment/paypal_cancel');
}
				
			}
// brain tree 1 refund start


// brain tree 1 refund end below change for elseif into else
			else
			{
	  $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;">', '</p>');
   if($this->input->post('send'))
			{
			$list_id        = $this->input->post('list_id');
			$reservation_id = $this->input->post('reservation_id');
			
			// $this->form_validation->set_rules('comment','Message','required|trim|xss_clean');
				
			//if($this->form_validation->run())
			//{
				if($this->input->post('payout_userto1') != 0)
				{	
				$payout_id = $this->input->post('payout_userto1');
				}
				else
				{
				  if($this->input->post('payout_userto2') != 0)
				  $payout_id = $this->input->post('payout_userto2');
				}
				
				if($this->input->post('payout_userby1') != 0)
				{	
				$payout_id = $this->input->post('payout_userby1');
				}
				else
				{
				  if($this->input->post('payout_userby2') != 0)
				  $payout_id = $this->input->post('payout_userby2');
				}
								
				$insertData = array(
					'list_id'         => $list_id,
					'reservation_id'  => $reservation_id,
					'userby'          => $this->dx_auth->get_user_id(),
					'userto'          => $payout_id,
					'message'         => $this->input->post('comment'),
					'message_type '   => 3
					);			
		
				$this->Message_model->sentMessage($insertData,1);
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate("Message successfully sent.")));
				redirect_admin('payment/details/'.$reservation_id);
			//}
			$result = $this->db->where('reservation.id',$reservation_id)->get('reservation');
		
		if($result->num_rows() !=0 )
		{
			$conditions              = array("reservation.id" => $reservation_id);
			$data['result']          = $row = $this->Trips_model->get_reservation($conditions)->row();
			
			$query                   = $this->Users_model->get_user_by_id($row->userby);
			$data['booker_name']     = $query->row()->username;
			
			$query1                  = $this->Users_model->get_user_by_id($row->userto);
			$data['hotelier_name']   = $query1->row()->username;
			
			$data['currency_code'] = $data['result']->currency;
	  
	  if($data['result']->coupon != 0)
	  {	  
	  $data['coupon_price'] = $this->Common_model->getTableData('coupon',array('couponcode'=>$data['result']->coupon))->row()->coupon_price;
	  $data['coupon_currency'] = $this->Common_model->getTableData('coupon',array('couponcode'=>$data['result']->coupon))->row()->currency;
	  }
	  $data['currency_symbol'] = $this->Common_model->getTableData('currency',array('currency_code'=>$data['result']->currency))->row()->currency_symbol;
		
			
			$data['message_element'] = "administrator/payment/view_details";
			$this->load->view('administrator/admin_template', $data);
			}
			
        else {
	        redirect_admin('payment/finance');
		}
		}
		}
	}
		
	function paypal_ipn()
	{
		if($_REQUEST['payment_status'] == 'Completed')
		{
		$custom              = $_REQUEST['custom'];
		$data                = array();
		$list                = array();
		$data                = explode('@', $custom); 
		
		$list_id             = $data[0];
		$reservation_id      = $data[1];
		$email_id            = $data[2];
		
		$price               = $_REQUEST['mc_gross'];
		
		$result              = $this->Common_model->getTableData( 'reservation',array('id' => $list_id) )->row();
		$checkin             = $result->checkin;
		$checkout            = $result->checkout;
		
		$admin_email         = $this->dx_auth->get_site_sadmin();
		$admin_name          = $this->dx_auth->get_site_title();
		
		$query               = $this->Users_model->get_user_by_id($result->userto);
	 $hotelier_name       = $query->row()->username;
		$hotelier_email      = $query->row()->email;
		
		$list['payed_date']  = date('d-m-Y H:i:s');
		$list['is_payed']    = 0;
		
		 //Reservation Notification To Host
			$email_name = 'admin_payment';
			$splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($hotelier_name), "{list_title}" => get_list_by_id($list_id)->title, "{payed_date}}" => $list['payed_date'], "{pay_id}" => $email_id, "{checkin}" => $checkin, "{checkout}" => $checkout, "{payed_price}" => $price);
			//Send Mail
			$this->Email_model->sendMail($hotelier_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);

		$condition = array("id" => $reservation_id);
		$this->Common_model->updateTableData('reservation', NULL, $condition, $list);
		}
	}
	
	
	function paypal_cancel()
	{
		if($this->session->userdata('error_msg') != '')
		{
			$data['message'] = $this->session->userdata('error_msg');
		}
		
		$data['message_element'] = "administrator/payment/paypal_cancel";
	 $this->load->view('administrator/admin_template', $data);
	}
	
	function paypal_success($id)
	{			
		$is_payed = $this->db->where('id',$id)->get('reservation')->row()->is_payed;
		
		if($is_payed == 0)
		{
		//	echo 'test';exit;
		$custom              = $this->session->userdata('custom');
		$data                = array();
		$list                = array();
		$data                = explode(',', $custom); 

		$list_id             = $data[0];
		$reservation_id      = $data[1];
		$email_id            = $data[2];
		
		$price               = $data[3];

		$result              = $this->Common_model->getTableData( 'reservation',array('id' => $id) )->row();
		
		$checkin             = $result->checkin;
		$checkout            = $result->checkout;
		
		$admin_email         = $this->dx_auth->get_site_sadmin();
		$admin_name          = $this->dx_auth->get_site_title();
		
		$query               = $this->Users_model->get_user_by_id($result->userby);
	    $traveler_name       = $query->row()->username;
		$traveler_email      = $query->row()->email;
		
		$query1               = $this->Users_model->get_user_by_id($result->userto);
	    $hotelier_name       = $query1->row()->username;
		$hotelier_email      = $query1->row()->email;
		
		/* if($result->status != 1 && $result->status != 2 && $result->status != 4 && $result->status != 5 && $result->status != 6 )
		{
			$query               = $this->Users_model->get_user_by_id($result->userto);
	        $hotelier_name       = $query->row()->username;
		    $hotelier_email      = $query->row()->email;
		}
		else if($result->status == 2 || $result->status == 4 || $result->status == 5 || $result->status == 6)
		{
			$query               = $this->Users_model->get_user_by_id($result->userby);
	        $traveler_name       = $query->row()->username;
		    $traveler_email      = $query->row()->email;
		}
		else
		{
			$query               = $this->Users_model->get_user_by_id($result->userto);
	        $hotelier_name       = $query->row()->username;
		    $hotelier_email      = $query->row()->email;
		}
		*/
		
		   $currency_symbol = $this->Common_model->getTableData('currency',array('currency_code'=>$result->currency))->row()->currency_symbol;
		
           		   		   		   
		  $conversation = $this->db->where('userto',$result->userto)->where('userby',1)->order_by('id','desc')->get('messages');
			
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
					$conversation1 = $this->db->where('userto',1)->where('userby',$result->userto)->order_by('id','desc')->get('messages');
				
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
				$conversation1 = $this->db->where('userto',1)->where('userby',$result->userto)->order_by('id','desc')->get('messages');
				
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
		  
		  $name = '';
		  
		//if($result->status != 1 && $result->status != 2 && $result->status != 4 && $result->status != 5 && $result->status != 6 )
		if($this->session->userdata('paid_to') == 'host')// || $this->session->userdata('paid_to') == 'common')
		{
		    //Reservation Notification To Host
		    $host_payout_id = get_userPayout($result->userto)->email;
		    $name = $hotelier_name;
			$refund_amt = $currency_symbol.$this->session->userdata('host_topay');
			$email_name = 'refund_host';
			$splVars = array("{site_name}" => $this->dx_auth->get_site_title(),"{currency}"=>$currency_symbol,"{price}"=>$result->price,"{account}" => "PayPal $host_payout_id","{traveler_name}" => ucfirst($traveler_name), "{host_name}" => ucfirst($hotelier_name), "{list_title}" => get_list_by_id($list_id)->title, "{payed_date}" => date("F j, Y",time()), "{checkin}" => date("F j, Y",$checkin), "{checkout}" => date("F j, Y",$checkout), "{refund_amt}" => $refund_amt);
			//Send Mail
			$this->Email_model->sendMail($hotelier_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
			
			if(!isset($conversation_id))
			{
				$insertData = array(
			'list_id'         => $result->list_id,
			'reservation_id'  => $result->id,
			'userby'          => 1,
			'userto'          => $result->userto,
			'message'         => "Admin refund $refund_amt to you.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			
			}
			else {
				$insertData = array(
			'list_id'         => $result->list_id,
			'reservation_id'  => $result->id,
			'conversation_id'  => $conversation_id,
			'userby'          => 1,
			'userto'          => $result->userto,
			'message'         => "Admin refund $refund_amt to you.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			}
			
			$refund_data['userto'] = $result->userto;
			
			$this->Message_model->sentMessage($insertData);
			
		}
		else if($this->session->userdata('paid_to') == 'guest' || $this->session->userdata('paid_to') == 'common') {
			 //Reservation Notification To Guest
			 
			 $name = $traveler_name;
			 
			$host_payout_id = get_userPayout($result->userby)->email;
			
		    $name = $traveler_name;
			if($this->session->userdata('guest_topay') == '')
			{
				$refund_amount = $this->session->userdata('topay');
			}
			else
			{
				$refund_amount = $this->session->userdata('guest_topay');
			}
			$refund_amt = $currency_symbol.$refund_amount;
			$email_name = 'refund_guest';
			$splVars = array("{site_name}" => $this->dx_auth->get_site_title(),"{currency}"=>$currency_symbol,"{price}"=>$result->price,"{account}" => "PayPal $host_payout_id","{traveler_name}" => ucfirst($traveler_name), "{host_name}" => ucfirst($hotelier_name), "{list_title}" => get_list_by_id($list_id)->title, "{payed_date}" => date("F j, Y",time()), "{checkin}" => date("F j, Y",$checkin), "{checkout}" => date("F j, Y",$checkout), "{refund_amt}" => $refund_amt);
			//Send Mail
			$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
			
			if(!isset($conversation_id))
			{
				$insertData = array(
			'list_id'         => $result->list_id,
			'reservation_id'  => $result->id,
			'userby'          => 1,
			'userto'          => $result->userby,
			'message'         => "Admin refund $refund_amt to you.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			
			}
			else {
				$insertData = array(
			'list_id'         => $result->list_id,
			'reservation_id'  => $result->id,
			'conversation_id'  => $conversation_id,
			'userby'          => 1,
			'userto'          => $result->userby,
			'message'         => "Admin refund $refund_amt to you.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			}
			
			$refund_data['userto'] = $result->userby;
			
			$this->Message_model->sentMessage($insertData);

		}
		
		$host_cancellation = $this->Common_model->getTableData('host_cancellation_penalty',array('user_id'=>$result->userto));

	    $refund_data['reservation_id'] = $result->id;
		$refund_data['payout_id'] = $this->session->userdata('payout_id');
		$refund_data['created'] = time();
		
		$this->Common_model->inserTableData('refund',$refund_data);
		
		 $admin_to_email = $this->db->where('id',1)->get('users')->row()->email;
		 
		 //Reservation Notification To Admin
			$email_name = 'refund_admin';
			$splVars = array("{name}"=>$name,"{traveler_email}"=>$traveler_email,"{host_email}"=>$hotelier_email,"{site_name}" => $this->dx_auth->get_site_title(),"{currency}"=>$currency_symbol,"{price}"=>$result->price,"{account}" => "PayPal $host_payout_id","{traveler_name}" => ucfirst($traveler_name), "{host_name}" => ucfirst($hotelier_name), "{list_title}" => get_list_by_id($list_id)->title, "{payed_date}" => date("F j, Y",time()), "{checkin}" => date("F j, Y",$checkin), "{checkout}" => date("F j, Y",$checkout), "{refund_amt}" => $refund_amt);
			//Send Mail
			$this->Email_model->sendMail($admin_to_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		if($this->session->userdata('paid_to') == 'common')
		{
		$this->db->where('id',$id)->update('reservation',array('is_payed'=>1));
		}
		else if($this->session->userdata('paid_to') == 'host')
		{
			$this->db->where('id',$id)->update('reservation',array('is_payed_host'=>1));
		}
		else {
			$this->db->where('id',$id)->update('reservation',array('is_payed_guest'=>1));
		}
		
		$result  = $this->Common_model->getTableData( 'reservation',array('id' => $id) )->row();
		
		if($result->is_payed_host == 1 && $result->is_payed_guest == 1)
		{
			$this->db->where('id',$id)->update('reservation',array('is_payed'=>1));
		}
		
		if($this->session->userdata('host_topay') == 0 || $this->session->userdata('guest_topay') == 0)
		{
			$this->db->where('id',$id)->update('reservation',array('is_payed'=>1));
		}
		
		if($result->transaction_id == 0)
		{
			$data['status'] = 'PayPal';
		}
		
		$data['message_element'] = "administrator/payment/paypal_success";
	 $this->load->view('administrator/admin_template', $data);
	
	}
else
{
	$data['message_element'] = "administrator/payment/paypal_success";
	 $this->load->view('administrator/admin_template', $data);
}
}
	function paypal_commission_success($id)
	{
		
		$accept_pay_result = $this->db->where('id',$id)->get('accept_pay')->row();
		
		$is_payed = $accept_pay_result->status;
		
		if($is_payed == 0)
		{
		//	echo 'test';exit;
		$custom              = $this->session->userdata('custom');
		$data                = array();
		$list                = array();
		$data                = explode(',', $custom); 

		$list_id             = $data[0];
		$reservation_id      = $data[1];
		$email_id            = $data[2];
		
		$price               = $data[3];

		$result              = $this->Common_model->getTableData( 'reservation',array('id' => $reservation_id) )->row();
		
		$checkin             = $result->checkin;
		$checkout            = $result->checkout;
		
		$admin_email         = $this->dx_auth->get_site_sadmin();
		$admin_name          = $this->dx_auth->get_site_title();
		
		$query               = $this->Users_model->get_user_by_id($result->userby);
	    $traveler_name       = $query->row()->username;
		$traveler_email      = $query->row()->email;
		
		$query1               = $this->Users_model->get_user_by_id($result->userto);
	    $hotelier_name       = $query1->row()->username;
		$hotelier_email      = $query1->row()->email;
			
		   $currency_symbol = $this->Common_model->getTableData('currency',array('currency_code'=>$accept_pay_result->currency))->row()->currency_symbol;
		
           $refund_amt = $currency_symbol.$accept_pay_result->amount;
		   		   		   
		  $conversation = $this->db->where('userto',$result->userto)->where('userby',1)->order_by('id','desc')->get('messages');
			
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
					$conversation1 = $this->db->where('userto',1)->where('userby',$result->userto)->order_by('id','desc')->get('messages');
				
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
				$conversation1 = $this->db->where('userto',1)->where('userby',$result->userto)->order_by('id','desc')->get('messages');
				
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
		  
		  $name = '';
		
		    //Reservation Notification To Host
		    $host_payout_id = get_userPayout($result->userto)->email;
		    $name = $hotelier_name;
			$email_name = 'refund_host_commission';
			$splVars = array("{site_name}" => $this->dx_auth->get_site_title(),"{currency}"=>$currency_symbol, "{host_name}" => ucfirst($hotelier_name),"{account}" => "PayPal $host_payout_id", "{list_title}" => get_list_by_id($list_id)->title, "{payed_date}" => date("F j, Y",time()), "{refund_amt}" => $refund_amt);
			//Send Mail
			$this->Email_model->sendMail($hotelier_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
			
			if(!isset($conversation_id))
			{
				$insertData = array(
			'list_id'         => $result->list_id,
			'reservation_id'  => $result->id,
			'userby'          => 1,
			'userto'          => $result->userto,
			'message'         => "Admin refund $refund_amt to you for Host reservation accept commission.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			
			}
			else {
				$insertData = array(
			'list_id'         => $result->list_id,
			'reservation_id'  => $result->id,
			'conversation_id'  => $conversation_id,
			'userby'          => 1,
			'userto'          => $result->userto,
			'message'         => "Admin refund $refund_amt to you for Host reservation accept commission.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			}
			
			$refund_data['userto'] = $result->userto;
			
			$this->Message_model->sentMessage($insertData);
		
	    $refund_data['reservation_id'] = $result->id;
		$refund_data['payout_id'] = $this->session->userdata('payout_id');
		$refund_data['created'] = time();
		$refund_data['accept_status'] = 1;
		
		$this->Common_model->inserTableData('refund',$refund_data);
		
		 $admin_to_email = $this->db->where('id',1)->get('users')->row()->email;
		 
		 //Reservation Notification To Admin
			$email_name = 'refund_host_commission_admin';
			$splVars = array("{name}"=>$name,"{traveler_email}"=>$traveler_email,"{host_email}"=>$hotelier_email,"{site_name}" => $this->dx_auth->get_site_title(),"{currency}"=>$currency_symbol,"{account}" => "PayPal $host_payout_id", "{host_name}" => ucfirst($hotelier_name), "{list_title}" => get_list_by_id($list_id)->title, "{payed_date}" => date("F j, Y",time()), "{refund_amt}" => $refund_amt);
			//Send Mail
			$this->Email_model->sendMail($admin_to_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		$this->db->where('id',$id)->update('accept_pay',array('status'=>1));
		$data['message_element'] = "administrator/payment/paypal_success";
	 $this->load->view('administrator/admin_template', $data);
	
	}
else
{
	$data['message_element'] = "administrator/payment/paypal_success";
	 $this->load->view('administrator/admin_template', $data);
}
		
	}
	
	
	   function list_pay()
   {
	 //print_r($result->result());exit;
	 
	 // Get offset and limit for page viewing
		$start          = (int) $this->uri->segment(4,0);
		
	 // Number of record showing per page
		$row_count      = 10;
		
		if($start > 0)
		   $offset			   = ($start-1) * $row_count;
		else
		   $offset			   =  $start * $row_count; 
		
		// Get all users
		$limits         =  array($row_count, $offset);
		
		$result = $this->db->limit($row_count,$offset)->join('list','list.id=list_pay.list_id')->join('users','users.id=list.user_id')->get('list_pay');
		
		$result1 = $this->db->join('list','list.id=list_pay.list_id')->join('users','users.id=list.user_id')->get('list_pay');
		
		$data['result_currency'] = $this->db->select('list_pay.currency')->join('list','list.id=list_pay.list_id')->join('users','users.id=list.user_id')->get('list_pay');
		//print_r($data['result_currency']->result());exit;
	    $data['result'] = $result;                
		//$data['result'] =  $this->Trips_model->get_reservation(NULL, $limits);
		
		// Pagination config
		$p_config['base_url']    = site_url('administrator/payment/list_pay');
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $result1->num_rows();
		$p_config['per_page']    = $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
		
	 $data['title']="Payment Host Listing";
	 $data['message_element']      = "administrator/payment/host_listing";
	 $this->load->view('administrator/admin_template',$data);
   }

   function accept_pay()
   {
	 //print_r($result->result());exit;
	 
	 // Get offset and limit for page viewing
		$start          = (int) $this->uri->segment(4,0);
		
	 // Number of record showing per page
		$row_count      = 10;
		
		if($start > 0)
		   $offset			   = ($start-1) * $row_count;
		else
		   $offset			   =  $start * $row_count; 
		
		// Get all users
		$limits         =  array($row_count, $offset);
		
		$result = $this->db->limit($row_count,$offset)->join('reservation','reservation.id=accept_pay.reservation_id')->join('list','list.id=reservation.list_id')->join('users','users.id=list.user_id')->get('accept_pay');
		
		$result1 = $this->db->join('reservation','reservation.id=accept_pay.reservation_id')->join('list','list.id=reservation.list_id')->join('users','users.id=list.user_id')->get('accept_pay');
		
		$data['result_currency'] = $this->db->select('accept_pay.currency')->limit($row_count,$offset)->join('reservation','reservation.id=accept_pay.reservation_id')->join('list','list.id=reservation.list_id')->join('users','users.id=list.user_id')->get('accept_pay');
		
	    $data['result'] = $result;                
		//$data['result'] =  $this->Trips_model->get_reservation(NULL, $limits);
		
		// Pagination config
		$p_config['base_url']    = site_url('administrator/payment/accept_pay');
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $result1->num_rows();
		$p_config['per_page']    = $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
		
	 $data['title']="Payment Reservation Accept";
	 $data['message_element']      = "administrator/payment/accept_pay";
	 $this->load->view('administrator/admin_template',$data);
   }

   function braintree_refund()
	{
		 $result = Braintree_Transaction::refund('fbysy6');
$result->success;
# true
print_r($result);exit;
$refund = $result->transaction;
$refund->type;
# 'credit'

$refund->refundedTransactionId;
# original transaction ID

Braintree_Transaction::find($transaction->id)->refundId;
# ID of refund associated to a transaction, if any
		 
		 /* $result = Braintree_Customer::create(array(
  'creditCard' => array(
    'number' => '4111111111111111'
  )
));
foreach($result->errors->deepAll() AS $error) {
  print_r($error->code . ": " . $error->message . "\n");
} */
	}
	
	function PPHttpPost($methodName_, $nvpStr_)
{
 global $environment;
 
 $api_user     = $this->Common_model->getTableData('payment_details', array('code' => 'CC_USER'))->row()->value;
 $api_pwd     = $this->Common_model->getTableData('payment_details', array('code' => 'CC_PASSWORD'))->row()->value;
 $api_key     = $this->Common_model->getTableData('payment_details', array('code' => 'CC_SIGNATURE'))->row()->value;
 $paymode     = $this->Common_model->getTableData('payments', array('payment_name' => 'Paypal'))->row()->is_live;

	if($paymode == 0)
     $environment = 'sandbox';
	else
	 $environment = '';
	  
 // Set up your API credentials, PayPal end point, and API version.
 // How to obtain API credentials:
 // https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO
 $API_UserName = urlencode($api_user);
 $API_Password = urlencode($api_pwd);
 $API_Signature = urlencode($api_key);
 $API_Endpoint = "https://api-3t.paypal.com/nvp";
 if("sandbox" === $environment || "beta-sandbox" === $environment)
 {
  $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
 }
 $version = urlencode('51.0');

 // Set the curl parameters.
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
 curl_setopt($ch, CURLOPT_VERBOSE, 1);

 // Turn off the server and peer verification (TrustManager Concept).
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POST, 1);

 // Set the API operation, version, and API signature in the request.
 $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

 // Set the request as a POST FIELD for curl.
 curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

 // Get response from the server.
 $httpResponse = curl_exec($ch);

 if( !$httpResponse)
 {
  exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) .')');
 }

 // Extract the response details.
 $httpResponseAr = explode("&", $httpResponse);

 $httpParsedResponseAr = array();
 foreach ($httpResponseAr as $i => $value)
 {
  $tmpAr = explode("=", $value);
  if(sizeof($tmpAr) > 1)
  {
   $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
  }
 }

 if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
 {
  exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
 }

 return $httpParsedResponseAr;
}

}
?>
