<?php
/**
 * DROPinn Payment List Controller Class
 *
 * helps to achieve payment functionality while adding the list.
 *
 * @package		DROPinn
 * @subpackage	Controllers
 * @category	Pay List
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listpay extends CI_Controller {

	public function Listpay()
	{
		parent::__construct();
		
               	
	// brain tree 1 start
		

        // brain tree 1 end 
		
		$this->load->helper('url');
		$this->load->helper('form');
	  
		 
        $this->load->library('Form_validation');
		$this->load->library('email');		
		$this->load->library('form_validation');
		$this->load->library('Twoco_Lib');
		
		$this->load->model('Users_model');
		
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
	
	public function index()
 {
extract($this->input->get());
		$this->form_validation->set_error_delimiters('<p>', '</p>');
		
		$check_paypal = $this->db->where('is_enabled',1)->where('payment_name','Paypal')->get('payments')->num_rows();
		$check_credit_card = $this->db->where('is_enabled',1)->where('payment_name','CreditCard')->get('payments')->num_rows();
		//print_r($check_paypal);exit;
		//if($check_paypal == 0 && $check_credit_card ==0 )
			if($check_paypal == 0 && $check_credit_card ==0)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate("Payment gateway is not enabled. Please contact admin.")));
			redirect('rooms/'.$this->session->userdata('Lid'));
		}
		
		$check_room_user = $this->Common_model->getTableData('list',array('id'=>$room_id,'user_id'=>$this->dx_auth->get_user_id()));
		
		if($check_room_user->num_rows() == 0)
		{
			redirect('info');
		}
		
		$check_room_id = $this->db->where('id',$room_id)->where('is_enable',0)->where('list_pay',0)->get('list');
		
		if($check_room_id->num_rows() == 0)
		{
			redirect('info');
		}
		
		if($this->input->post('book_it_button'))
		{
				
                                // brain tree 2 start     
                                
                                // brain tree 2 ( add else if condition in below if)
				if($this->input->post('payment_method') == 'paypal')
				{
				   $this->submission($room_id);	
				}
				else if($this->input->post('payment_method') == '2c')
				{
				   $this->submissionTwoc();	
				}
				else
				{
					 	redirect('info');		
				}
		}
		
		$data['id']               = $this->session->userdata('Lid');
		
		$row1 = $this->Common_model->getTableData('paymode', array('id' => '1'))->row();
	
	if($row1->is_premium == 1)
			{
			   if($row1->is_fixed == 1)
				{
					$fix                = $row1->fixed_amount; 
					$amt = get_currency_value_lys($row1->currency,get_currency_code(),$fix);
				}
				else
				{  
					$per                = $row1->percentage_amount; 
					$list_price =  $check_room_user->row()->price;
					$list_price = get_currency_value_lys($row1->currency,get_currency_code(),$list_price);
					$camt               = floatval(($list_price * $per) / 100);
					$amt = $camt;	
				}
			}
			
			if(!isset($amt))
		{
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',"Administrator has disabled this commission setup. Please try again."));
		//redirect('rooms/'.$data['id'], "refresh");
		}
		
		$data['amt']              = round($amt,2);
		$data['full_cretids'] = 'off';
		
		$data['result']           = $this->Common_model->getTableData('payments')->result();
		
		$data['title']            = get_meta_details('Payment_Option','title');
		$data["meta_keyword"]     = get_meta_details('Payment_Option','meta_keyword');
		$data["meta_description"] = get_meta_details('Payment_Option','meta_description');
		
		$data['message_element']  = "payments/view_listPay";
		
		$this->load->view('template',$data);
	}
	
	public function submission($param)
	{
	    $list_id = $param;	
					$row     = $this->Common_model->getTableData('payment_details', array('code' => 'PAYPAL_ID'))->row();
				 	$paymode = $this->db->where('payment_name','Paypal')->get('payments')->row()->is_live;
	$row1 = $this->Common_model->getTableData('paymode', array('id' => '1'))->row();
	$list_data = $this->Common_model->getTableData('list', array('id' => $list_id))->row();
	
	if($row1->is_premium == 1)
			{
			   if($row1->is_fixed == 1)
				{
					$fix                = $row1->fixed_amount; 
					$amt = get_currency_value_lys($row1->currency,get_currency_code(),$fix);
				}
				else
				{  
					$per                = $row1->percentage_amount; 
					$list_price =  $list_data->price;
					$list_price = get_currency_value_lys($row1->currency,get_currency_code(),$list_price);
					$camt               = floatval(($list_price * $per) / 100);
					$amt = $camt;	
				}
			}
		
		if(!isset($amt))
		{
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',"Administrator has disabled this commission setup. Please try again."));
		redirect('rooms/'.$list_id, "refresh");
		}

		//$amt = get_currency_value_lys('USD',get_currency_code(),$amt);
		
if(get_currency_value($this->session->userdata('amount')) == 0)
{
$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('You are not able to pay a amount for this list. Please contact the Admin.')));	
redirect('rooms/lys_next/edit/'.$list_id);
}
	if(get_currency_code() == 'INR' || get_currency_code() == 'MYR' || get_currency_code() == 'ARS' || 
get_currency_code() == 'CNY' || get_currency_code() == 'IDR' || get_currency_code() == 'KRW' 
|| get_currency_code() == 'VND' || get_currency_code() == 'ZAR')
{
	$currency_code = 'USD';
	$amt = get_currency_value_lys(get_currency_code(),$currency_code,$amt);
	
}
else
	{
		$currency_code = get_currency_code();
		$amt = $amt;
	}

			$to_buy = array(
'desc' => 'Purchase from ACME Store',
'currency' => $currency_code,
'type' => 'sale',
'return_URL' => site_url('listpay/list_success/'.$list_id),
// see below have a function for this -- function back()
// whatever you use, make sure the URL is live and can process
// the next steps
'cancel_URL' => site_url('listpay/list_cancel'), // this goes to this controllers index()
'shipping_amount' => 0,
'get_shipping' => false);
// I am just iterating through $this->product from defined
// above. In a live case, you could be iterating through
// the content of your shopping cart.
//foreach($this->product as $p) {
$temp_product = array(
'name' => $this->dx_auth->get_site_title().' Transaction',
'number' => $list_id,
'quantity' => 1, // simple example -- fixed to 1
'amount' => $amt);

// add product to main $to_buy array
$to_buy['products'][] = $temp_product;
//}
// enquire Paypal API for token
$set_ec_return = $this->paypal_ec->set_ec($to_buy);
if (isset($set_ec_return['ec_status']) && ($set_ec_return['ec_status'] === true)) {
// redirect to Paypal
$this->paypal_ec->redirect_to_paypal($set_ec_return['TOKEN']);
// You could detect your visitor's browser and redirect to Paypal's mobile checkout
// if they are on a mobile device. Just add a true as the last parameter. It defaults
// to false
// $this->paypal_ec->redirect_to_paypal( $set_ec_return['TOKEN'], true);
} else {
	
	$id = $list_id;
	
	if($set_ec_return['L_LONGMESSAGE0'] == 'Security header is not valid')
	{
		$username = $this->dx_auth->get_username();
		$list_title = $this->Common_model->getTableData('list',array('id'=>$id))->row()->title;
		$email = $this->Common_model->getTableData('users',array('id'=>$this->dx_auth->get_user_id()))->row()->email;
		$admin_email = $this->Common_model->getTableData('users',array('id'=>1))->row()->email;
		
		$admin_email_from = $this->dx_auth->get_site_sadmin();
		$admin_name  = $this->dx_auth->get_site_title();
		
		$email_name = 'payment_issue_to_admin';
		$splVars    = array("{payment_type}"=>'PayPal',"{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($username), "{list_title}" => $list_title, '{email_id}' => $email);
				
		$this->Email_model->sendMail($admin_email,$admin_email_from,ucfirst($admin_name),$email_name,$splVars);	
		
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate("PayPal business account is misconfigured. Please contact your Administrator.")));
		redirect('rooms/'.$list_id, "refresh");
	}
	
	if($set_ec_return['L_ERRORCODE0'] == 10525)
	{
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',$set_ec_return['L_LONGMESSAGE0']));
		redirect('rooms/'.$list_id, "refresh");
	}
$this->_error($set_ec_return);
}			
			
	}
	
	public function list_cancel()
	{
	 // redirect('home/addlist','refresh');
	   redirect('rooms/new','refresh');
	}
	
	public function list_ipn()
	{
		if($_REQUEST['payment_status'] == 'Completed')
		{
			
			$data   = explode('@',$custom); 
			$listId         = $data[0];
			$data['status'] = 1;
			$this->db->where('id', $listId);
			$this->db->update('list', $data);
		$query        = $this->Common_model->getTableData( 'list',array('id' => $listId) )->row();
			$list_email        = $query->email; 
			$data['status'] = $list_email;
			$query2 = $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row();
		  	$user_email   =	$query2->email;
			$data['status'] = $user_email;					
			$emailsubject = "Host Listing Confirmation";
			$headers = "";
						$headers .= "From: Dropinn Host Listing <gokulnath@cogzidel.com>\r\n";
						$headers .= "MIME-Version: 1.0\n";
						$headers .= "Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"----=MIME_BOUNDRY_main_message\"\n"; 
						$headers .= "X-Sender: from_name<" . $user_email . ">\n";
						$headers .= "X-Mailer: PHP4\n";
						$headers .= "X-Priority: 3\n"; //1 = Urgent, 3 = Normal
						$headers .= "Return-Path: <" .$user_email . ">\n"; 
						$emsg = 'You have finished the payment for your listing ';
						mail($list_email, $emailsubject, $emsg,$headers); 		
		
		
		}
	}
	public function payment($param)
	{
		
                   // brain tree 3 start	
	
                    if($this->input->post('payment_method') == 'braintree')
			{
			   $this->submissionCC($param);
			}
                   // brain tree 3 end( changed as else if condition in below if)     
		   if($this->input->post('payment_method') == 'paypal')
			{
			   
			   $this->submission($param);
			
			}
			else if($this->input->post('payment_method') == '2c')
			{
			   $this->submissionTwoc($param);	
			}
			else
			{
			   redirect('listpay?room_id='.$param);	
			}
	
	}

       // brain tree 4 start
	
	// brain tree 4 start
	public function list_success()
	{
	//echo $payment_status 	= $this->input->post('payment_status',true);
		
		//exit;
			
		$token = $_GET['token'];
$payer_id = $_GET['PayerID'];
// GetExpressCheckoutDetails
$get_ec_return = $this->paypal_ec->get_ec($token);

if (isset($get_ec_return['ec_status']) && ($get_ec_return['ec_status'] === true)) {
	
// at this point, you have all of the data for the transaction.
// you may want to save the data for future action. what's left to
// do is to collect the money -- you do that by call DoExpressCheckoutPayment
// via $this->paypal_ec->do_ec();
//
// I suggest to save all of the details of the transaction. You get all that
// in $get_ec_return array
	if(get_currency_code() == 'INR' || get_currency_code() == 'MYR' || get_currency_code() == 'ARS' || 
get_currency_code() == 'CNY' || get_currency_code() == 'IDR' || get_currency_code() == 'KRW' 
|| get_currency_code() == 'VND' || get_currency_code() == 'ZAR')
{
	$currency_code = 'USD';
	$amt = get_currency_value_lys(get_currency_code(),$currency_code,$this->session->userdata('amount'));
	
}
else
	{
		$currency_code = get_currency_code();
		$amt = $this->session->userdata('amount');
	}
$ec_details = array(
'token' => $token,
'payer_id' => $payer_id,
'currency' => $currency_code,
'amount' => $get_ec_return['PAYMENTREQUEST_0_AMT'],
'IPN_URL' => site_url('payments/ipn'),
// in case you want to log the IPN, and you
// may have to in case of Pending transaction
'type' => 'sale');

// DoExpressCheckoutPayment
$do_ec_return = $this->paypal_ec->do_ec($ec_details);

if (isset($do_ec_return['ec_status']) && ($do_ec_return['ec_status'] === true)) {
// at this point, you have collected payment from your customer
// you may want to process the order now.

/* echo "<h1>Thank you. We will process your order now.</h1>";
echo "<pre>";
echo "\nGetExpressCheckoutDetails Data\n" . print_r($get_ec_return, true);
echo "\n\nDoExpressCheckoutPayment Data\n" . print_r($do_ec_return, true);
echo "</pre>";exit; */
		
		$data_listpay['list_id'] = $get_ec_return['L_NUMBER0'];
		$data_listpay['amount'] = $this->session->userdata('list_commission');
		$data_listpay['currency'] = get_currency_code();
		$data_listpay['created'] = time();
		
		$this->db->insert('list_pay',$data_listpay);
		
		if($this->input->post('payment_status',true) == 'Completed')
		{
		 $listId         = $this->input->post('custom',true);
		
		$condition           = array('id' => $listId);
		$data['status']     = 1;
                $data['list_pay'] = 1;
				$data['is_enable']= 1;
				$data['payment'] = 1;
		$this->Common_model->updateTableData('list', NULL, $condition,$data);
		
		if( (!$this->dx_auth->is_logged_in()) || (!$this->facebook_lib->logged_in()))
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Session expired, please try again.')));
			redirect('users/signin');
		}
		
		$query_user 	= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row();
		$username 		= $query_user->username;
		
			$insertData = array(
			'list_id'         => $listId,
			'conversation_id' => $listId,
			'userby'          => $this->dx_auth->get_user_id(),
			'userto'          => 1,
			'message'         => "$username created a new list.",
			'created'         => time(),
			'message_type '   => 10
			);	
			
			$this->Message_model->sentMessage($insertData);
			
			$host_email = $query_user->email;
		
		$admin_email = $this->dx_auth->get_site_sadmin();
		$admin_name  = $this->dx_auth->get_site_title();
		
		$admin_to_email = $this->Common_model->getTableData('users',array('id' => 1))->row()->email;
			
			$query_list	= $this->Common_model->getTableData('list',array('id' => $listId))->row();
			
			$currency = $query_list->currency;
			$price = $query_list->price;
			$title = $query_list->title;
			$link = base_url().'rooms/'.$listId;
			
		$email_name = 'list_create_host';
		$splVars    = array("{host_name}"=>$username,"{price}"=> $currency.$price,"{link}"=>$link,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		$email_name = 'list_create_admin';
		$splVars    = array("{host_name}"=>$username,"{price}"=> $currency.$price,"{link}"=>$link,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($admin_to_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
				
		//redirect('rooms/edit/'.$listId, 'refresh');
		$listId         = $this->uri->segment('3');
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Rooms added successfully.')));
		redirect('rooms/'.$listId, 'refresh');
		}
		else if($this->input->post('payment_status',true) == '')
		{
		 $listId         = $this->uri->segment('3');
	
		$condition           = array('id' => $listId);
		$data['status']     = 1;
                $data['list_pay'] = 1;
					$data['is_enable']= 1;
		$this->Common_model->updateTableData('list', NULL, $condition,$data);
		
		if( (!$this->dx_auth->is_logged_in()) || (!$this->facebook_lib->logged_in()))
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Session expired, please try again.')));
			redirect('users/signin');
		}
		
		$query_user 	= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row();
		$username 		= $query_user->username;
		
			$insertData = array(
			'list_id'         => $listId,
			'conversation_id' => $listId,
			'userby'          => $this->dx_auth->get_user_id(),
			'userto'          => 1,
			'message'         => "$username created a new list.",
			'created'         => time(),
			'message_type '   => 10
			);	
			
			$this->Message_model->sentMessage($insertData);
			
			$host_email = $query_user->email;
		
		$admin_email = $this->dx_auth->get_site_sadmin();
		$admin_name  = $this->dx_auth->get_site_title();
		
		$admin_to_email = $this->Common_model->getTableData('users',array('id' => 1))->row()->email;
			
			$query_list	= $this->Common_model->getTableData('list',array('id' => $listId))->row();
			
			$currency = $query_list->currency;
			$price = $query_list->price;
			$title = $query_list->title;
			$link = base_url().'rooms/'.$listId;
			
		$email_name = 'list_create_host';
		$splVars    = array("{host_name}"=>$username,"{price}"=> $currency.$price,"{link}"=>$link,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		$email_name = 'list_create_admin';
		$splVars    = array("{host_name}"=>$username,"{price}"=> $currency.$price,"{link}"=>$link,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($admin_to_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		//redirect('rooms/edit/'.$listId, 'refresh');
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Rooms added successfully.')));
		redirect('rooms/'.$listId, 'refresh');	
		}
		else
		{
			//echo $this->input->post('payment_status',true);
			//exit;
		 //redirect('home/addlist','refresh');
		 redirect('rooms/new','refresh');
		}
	}
else {
$this->_error($do_ec_return);
}
	}
else {
$this->_error($do_ec_return);
}
	} 

public function braintree_success()
	{

		$result = Braintree_Transaction::sale(array(
  'amount' => get_currency_value_lys(get_currency_code(),'USD',$this->session->userdata('list_commission')),
  "paymentMethodNonce" => $_POST['payment_method_nonce'],
  'options' => array(
    'submitForSettlement' => true
  )
));

$transaction = $result->transaction ;
/*
 if($result->success == 1)
{ 
if($result->message == "Amount must be greater than zero.")
	{
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',$result->message));
		redirect('rooms/'.$this->input->post('custom',true), "refresh");
	}
 */
 
 if( (!$this->dx_auth->is_logged_in()) || (!$this->facebook_lib->logged_in()))
		{
			//$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Session expired, please try again.')));
			//redirect('users/signin');
		}
		
if ($transaction->status == "authorized" || $transaction->status == "submitted_for_settlement")
		{
		 $listId         = $this->input->post('custom',true);
		
		$condition           = array('id' => $listId);
		$data['status']      = 1;
        $data['list_pay']    = 1;
		$data['is_enable']   = 1;
		$data['payment']     = 1;
		$this->Common_model->updateTableData('list', NULL, $condition,$data);
		
		if( (!$this->dx_auth->is_logged_in()) || (!$this->facebook_lib->logged_in()))
		{
			//$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Session expired, please try again.')));
			//redirect('users/signin');
		}
		
		$query_user 	= $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row();
		$username 		= $query_user->username;
		
			$insertData = array(
			'list_id'         => $listId,
			'conversation_id' => $listId,
			'userby'          => $this->dx_auth->get_user_id(),
			'userto'          => 1,
			'message'         => "$username created a new list.",
			'created'         => time(),
			'message_type '   => 10
			);	
			
			$this->Message_model->sentMessage($insertData);
			
				$host_email = $query_user->email;
		
		$admin_email = $this->dx_auth->get_site_sadmin();
		$admin_name  = $this->dx_auth->get_site_title();
		
		$admin_to_email = $this->Common_model->getTableData('users',array('id' => 1))->row()->email;
			
			$query_list	= $this->Common_model->getTableData('list',array('id' => $listId))->row();
			
			$currency = $query_list->currency;
			$price = $query_list->price;
			$title = $query_list->title;
			$link = base_url().'rooms/'.$listId;
			
		$email_name = 'list_create_host';
		$splVars    = array("{host_name}"=>$username,"{price}"=> $currency.$price,"{link}"=>$link,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		$email_name = 'list_create_admin';
		$splVars    = array("{host_name}"=>$username,"{price}"=> $currency.$price,"{link}"=>$link,"{list_title}"=>$title,"{site_name}" => $this->dx_auth->get_site_title());
		$this->Email_model->sendMail($admin_to_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		$data_listpay['list_id'] = $listId;
		$data_listpay['amount'] = $this->session->userdata('list_commission');
		$data_listpay['currency'] = get_currency_code();
		$data_listpay['created'] = time();
		
		$this->db->insert('list_pay',$data_listpay);
		
		
		$data['title']="Payment Success !";
		$data['message_element']      = "payments/paypal_success";
		$this->load->view('template',$data);
		  
		 
		//redirect('rooms/edit/'.$listId, 'refresh');
		//$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Rooms added successfully.')));
		//redirect('rooms/'.$listId, 'refresh');
		
		}
else {
			 	$data['title']="Payment Cancelled !";
			$data['message_element']      = "payments/paypal_cancel";
			$this->load->view('template',$data);
          }
		/* }
		else {
			 	$data['title']="Payment Cancelled !";
			$data['message_element']      = "payments/paypal_cancel";
			$this->load->view('template',$data);
          }
	*/
	} 

function _error($ecd) {
echo "<br>error at Express Checkout<br>";
echo "<pre>" . print_r($ecd, true) . "</pre>";
echo "<br>CURL error message<br>";
echo 'Message:' . $this->session->userdata('curl_error_msg') . '<br>';
echo 'Number:' . $this->session->userdata('curl_error_no') . '<br>';
}

}

/* End of file listpay.php */
/* Location: ./app/controllers/listpay.php */
?>
