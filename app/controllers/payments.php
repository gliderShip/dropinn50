<?php
/**
 * DROPinn Payments Controller Class
 *
 * Helps to control payment functionality
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Profiles
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 */

class Payments extends CI_Controller {

	function Payments()
	{
		
                // brain tree 1 starts
               
            // brain tree 1 end
               parent::__construct();
	
		$this->load->helper('url');
				
		$this->load->library('Twoco_Lib');
		$this->load->library('email');
		$this->load->helper('form');
		$this->load->model('Users_model');
		$this->load->model('Referrals_model');
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->load->model('Contacts_model');
		
		$this->load->model('Trips_model');
		$trackingId='4568246565'; 
		$this->facebook_lib->enable_debug(TRUE);
		
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
	
	function index($param = '')
	{
	$this->session->set_userdata('cnumber_error','');
	$this->session->set_userdata('cname_error','');
	$this->session->set_userdata('ctype_error','');	
	$this->session->set_userdata('expire_error','');
	
	if($this->input->post('env'))
	{
		$this->session->set_userdata('call_back','mobile');
	}
	
	  if($param == '')
			{
			  redirect('info/deny');
			}
			
		$result                 = $this->Common_model->getTableData('list', array('id' => $param,'is_enable'=>1) );
		if($result->num_rows() == 0)
		{
		  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate("This List Hidden by Host.")));
			redirect('rooms/'.$param);
		}
		$check = $this->db->where('id',$param)->where('user_id',$this->dx_auth->get_user_id())->get('list');
		if($check->num_rows() != 0)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate("Host can't book their list.")));
			redirect('rooms/'.$param);
		}
	    	
			$result = $this->db->where('id',$param)->get('lys_status')->row();
			// check for instance book
			
			 $instance_book = $this->db->where('id',$param)->get('list')->row()->instance_book;
			 		  $photo= $this->Gallery->profilepic($this->dx_auth->get_user_id(),2);
			 $fade=base_url().'images/no_avatar-xlarge.jpg';
			 //exit;
			 if($instance_book==1 && $photo==$fade)
			 {
			 	
			 	$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate("Please Upload your profile picture.")));
			redirect('rooms/'.$param);
			 }

			
			//
			$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
			if($total != 6)
			{
			redirect('info');
			}
			
		 if( (!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()) )
			{
				if($this->input->get())
				{
						//contact me	
						$contact=$this->input->get('contact');	
						if($this->input->get('contact'))
						$redirect_to = 'payments/index/'.$param.'?contact='.$contact;
						else	
						$redirect_to = 'payments/index/'.$param;
						
						$newdata = array(
						        'list_id'                 => $param,
														'Lcheckin'                => $this->input->get('checkin'),
														'Lcheckout'               => $this->input->get('checkout'),
														'number_of_guests'		  => $this->input->get('guest'),
														'redirect_to'             => $redirect_to,
														'formCheckout'            => TRUE
										);
      $this->session->set_userdata($newdata);
							
					 redirect('users/signin','refresh');
			}
				else {
					$contact=$this->input->get('contact');	
						if($this->input->get('contact'))
						$redirect_to = 'payments/index/'.$param.'?contact='.$contact;
						else	
						$redirect_to = 'payments/index/'.$param;
						
						$newdata = array(
						        'list_id'                 => $param,
														'Lcheckin'                => $this->input->post('checkin'),
														'Lcheckout'               => $this->input->post('checkout'),
														'number_of_guests'		  => $this->input->post('number_of_guests'),
														'redirect_to'             => $redirect_to,
														'formCheckout'            => TRUE
										);
      $this->session->set_userdata($newdata);
							
					 redirect('users/signin','refresh');
				}
			} 
			
			/*Include Get option*/		
			
			 if($this->input->post('checkin') || $this->session->userdata('Lcheckin') || $this->input->get('checkin'))
				{
if($this->input->post('SignUp')!=NULL)
{
				//echo 'got it';
						//$this->guest_signup();
						
						
	if($this->input->post()||$this->input->get())
	{
	$this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
	$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
	$this->form_validation->set_rules('username','Username','required|trim|xss_clean|callback__check_user_name');
	$this->form_validation->set_rules('email','Email','required|trim|valid_email|xss_clean|callback__check_user_email');
	$this->form_validation->set_rules('password','Password','required|trim|min_length[5]|max_length[16]|xss_clean|matches[confirmpassword]');
	$this->form_validation->set_rules('confirmpassword','Confirm Password','required|trim|min_length[5]|max_length[16]|xss_clean');
	
		if($this->form_validation->run())
		{	
		//Get the post values
		$first_name        = $this->input->post('first_name');
		$last_name         = $this->input->post('last_name');
		$username          = $this->input->post('username');
		$email             = $this->input->post('email');
		$password          = $this->input->post('password');
		$confirmpassword   = $this->input->post('confirmpassword');
		$newsletter   	   = $this->input->post('news_letter');
		
		$data = $this->dx_auth->register($username, $password, $email);
		
		$this->dx_auth->login($username, $password, 'TRUE');
			
		//To check user come by reference
		if($this->session->userdata('ref_id'))
		$ref_id      = $this->session->userdata('ref_id');
		else
		$ref_id      = "";
		
		if(!empty($ref_id))
		{
		$details     = $this->Referrals_model->get_user_by_refId($ref_id);
		$invite_from = $details->row()->id;
		
						$insertData                    = array();
						$insertData['invite_from']     = $invite_from;
						$insertData['invite_to']       = $this->dx_auth->get_user_id();
						$insertData['join_date']       = local_to_gmt();
						
						$this->Referrals_model->insertReferrals($insertData);
						
   			$this->session->unset_userdata('ref_id');
			}
						
			$notification                     = array();
			$notification['user_id']										= $this->dx_auth->get_user_id();
			$notification['new_review ']						= 1;
			$notification['leave_review']				 = 1;
			$this->Common_model->insertData('user_notification', $notification);
			
			//Need to add this data to user profile too 
			$add['Fname']    = $first_name;
			$add['Lname']    = $last_name;
			$add['id']       = $this->dx_auth->get_user_id();
			$add['email']    = $email;
			$this->Common_model->insertData('profiles', $add);
			//End of adding it
			 $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Registered successfully.')));
			
		}
		}

			}
else if($this->input->post('SignIn')!=NULL)
{
	
	if($this->input->post()||$this->input->get())
		{
					if ( !$this->dx_auth->is_logged_in())
					{
						// Set form validation rules
						$this->form_validation->set_rules('username1', 'Username or Email', 'required|trim|xss_clean');
						$this->form_validation->set_rules('password1', 'password', 'required|trim|xss_clean');
					//	$this->form_validation->set_rules('remember', 'Remember me', 'integer');
						
						if($this->form_validation->run())
						{
								$username = $this->input->post("username1");
								$password = $this->input->post("password1");
								
								if ($this->dx_auth->login($username, $password, $this->form_validation->set_value('TRUE')))
								{
									// Redirect to homepage
									$newdata = array(
																					'user'      => $this->dx_auth->get_user_id(),
																					'username'  => $this->dx_auth->get_username(),
																					'logged_in' => TRUE
																				);
									$this->session->set_userdata($newdata);
									$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Logged in successfully.')));
								}
						}
									
					}							
		}
}	
		  $this->form($param);
	
				}

				else
				{
				  redirect('rooms/'.$param, "refresh");
				}
	}
	
	function contact() {
	
	if( (!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()) )
			{
			
			$data['status'] = "error";	
			//Store the values in session to redirect this page after login
			$newdata = array(
						        
						        						'Lid'                	  => $this->input->post('id'),
														'Lcheckin'                => $this->input->post('checkin'),
														'Lcheckout'               => $this->input->post('checkout'),
														'number_of_guests'		  => $this->input->post('guests'),
														'Lmessage'                => $this->input->post('message'),
														'redirect_to'             => 'rooms/'.$this->input->post('id'),
														'formCheckout'            => TRUE
										);
      		$this->session->set_userdata($newdata);
			
			}
			else
			{
				$check = $this->db->where('id',$this->input->post('id'))->where('user_id',$this->dx_auth->get_user_id())->get('list');
				
				if($check->num_rows() != 0)
				{
					$data['status'] = "your_list";
				}
				else {
					
			$status=1;
			if($this->session->userdata('formCheckout'))
			{
		 		$id				= $this->session->userdata('Lid');	
		 		$checkin        = $this->session->userdata('Lcheckin');
  		 		$checkout       = $this->session->userdata('Lcheckout');
	  	 		$data['guests'] = $this->session->userdata('number_of_guests');
				$message		= $this->session->userdata('Lmessage');
			}	
			else
			{	
		    $id 			= $this->input->post('id');
			$checkin        = $this->input->post('checkin');
			$checkout       = $this->input->post('checkout');
			$data['guests'] = $this->input->post('guests');
			$message=$this->input->post('message');
			}
			//Check the rooms availability
				$checkin_time=$checkin; 
				$checkin_time=get_gmt_time(strtotime($checkin_time));
				$checkout_time=$checkout; 
				$checkout_time=get_gmt_time(strtotime($checkout_time));
				$sql="select checkin,checkout from contacts where list_id='".$id."' and status!=1";
				$query=$this->db->query($sql);
				$res=$query->result_array();
				if($query->num_rows()>0)
				{
				foreach($res as $time)
				{
					$start_date=$time['checkin'];
					$end_date=$time['checkout'];	
					$start=get_gmt_time(strtotime($start_date));
					$end=get_gmt_time(strtotime($end_date));		
					if(($checkin_time >= $start && $checkin_time <= $end) || ($checkout_time >= $start && $checkout_time <= $end))
					{
						$status=0;
					}
				}
				}
			$daysexist = $this->db->query("SELECT id,list_id,booked_days FROM `calendar` WHERE `list_id` = '".$id."' AND (`booked_days` >= '".get_gmt_time(strtotime($checkin))."' AND `booked_days` <= '".get_gmt_time(strtotime($checkout))."') GROUP BY `id`");
			//echo $data['status'] = $this->db->last_query();exit;
			$rowsexist = $daysexist->num_rows();
             // echo $data['status'] = $daysexist->num_rows();exit;
			if($rowsexist > 0)
			{
				$status=0;
			}
            else
            {
	           $status = 1;
            } 	
         
		$contacts_already = $this->db->query("SELECT id FROM `contacts` WHERE `list_id` = '".$id."' AND `userby` = '".$this->dx_auth->get_user_id()."' AND `status` != 10 AND ((`checkin` >= '".$checkin."' AND `checkin` <= '".$checkout."') OR (`status` != 10 AND `checkout` >= '".$checkin."' AND `checkout` <= '".$checkout."'))"); 
		
		$conditions             = array("id" => $id, "list.status" => 1);
	 
	    $result                 = $this->Common_model->getTableData('list', $conditions);
		
		$conditions1             = array("id" => $id);
	 
	    $lys_status                 = $this->Common_model->getTableData('lys_status', $conditions1)->row();
	 			
			$capacity1 = $this->db->where('id',$id)->get('list')->row()->capacity;
				
			
			$capacity = $capacity1+1;
			
			$total_status = $lys_status->calendar+$lys_status->price+$lys_status->overview+$lys_status->address+$lys_status->photo+$lys_status->listing;
			
			 if($result->row()->is_enable != 1 || $result->row()->list_pay != 1 || $total_status != 6)
			 {
	 			$data['status'] = 'redirect';
			 }
			 else if($status==0)
			{
				$data['status'] = "not_available";
			}
			else if($capacity1 == 0)
			{
				$data['status'] = "not_available";
			}
			else if($data['guests'] > $capacity)
			{
				$data['status'] = "not_available";
			}
			else if($contacts_already->num_rows() != 0)
			{
				$data['status'] = "already";
			} 
			else 
			{
			$data['status'] = "success";	
			$list['list_id']=$id;
			$list['checkin']=$checkin;
			$list['checkout']=$checkout;
			$list['no_quest']=$data['guests'];
			$list['currency']=get_currency_code();
			
		//calculate price for the checkin and checkout dates
		$ckin           = explode('/', $checkin);
		$ckout          = explode('/', $checkout);
	
		$xprice         = $this->Common_model->getTableData( 'price', array('id' => $id ) )->row();
		//print_r($xprice);exit;
		$guests         = $xprice->guests;
		$per_night      = $xprice->night;
		
		if(isset($xprice->cleaning))
		$cleaning       = $xprice->cleaning;
		else
		$cleaning       = 0;
		
		if(isset($xprice->security))
		$security       = $xprice->security;
		else
		$security       = 0;
		
		if(isset($xprice->night))
		$price          = $xprice->night;
		else
		$price          = 0;
		
		if(isset($xprice->week))
		$Wprice         = $xprice->week;	
		else
		$Wprice         = 0;
		
		if(isset($xprice->month))
		$Mprice         = $xprice->month;	
		else
		$Mprice         = 0;
				
		//check admin premium condition and apply so for
		$query         = $this->Common_model->getTableData( 'paymode', array('id' => 2) );
		$row           = $query->row();	
		
		//Seasonal Price
		//1. Store all the dates between checkin and checkout in an array		
			$checkin_time		= get_gmt_time(strtotime($checkin));
		    $checkout_time		= get_gmt_time(strtotime($checkout));
			$travel_dates		= array();
			$seasonal_prices 	= array();		
			$total_nights		= 1;
			$total_price		= 0;
			$is_seasonal		= 0;
			$i					= $checkin_time;
			while($i<$checkout_time)
			{
				$checkin_date					= date('m/d/Y',$i);
				$checkin_date					= explode('/', $checkin_date);
				$travel_dates[$total_nights]	= $checkin_date[1].$checkin_date[0].$checkin_date[2];
				$i								= get_gmt_time(strtotime('+1 day',$i));
				$total_nights++; 
			}
			for($i=1;$i<$total_nights;$i++)
			{
				$seasonal_prices[$travel_dates[$i]]="";
			}
		//Store seasonal price of a list in an array
		$seasonal_query	= $this->Common_model->getTableData('seasonalprice',array('list_id' => $id));
		$seasonal_result= $seasonal_query->result_array();
		if($seasonal_query->num_rows()>0)
		{
			foreach($seasonal_result as $time)
			{
			
				//Get Seasonal price
				$seasonalprice_query	= $this->Common_model->getTableData('seasonalprice',array('list_id' => $id,'start_date' => $time['start_date'],'end_date' => $time['end_date']));
				$seasonalprice 			= $seasonalprice_query->row()->price;	
				//Days between start date and end date -> seasonal price	
				$start_time	= $time['start_date'];
				$end_time	= $time['end_date'];
				$i			= $start_time;
				while($i<=$end_time)
				{	
					$start_date					= date('m/d/Y',$i);
					$s_date						= explode('/',$start_date);	
					$s_date						= $s_date[1].$s_date[0].$s_date[2];
					$seasonal_prices[$s_date]	= $seasonalprice;
					$i							= get_gmt_time(strtotime('+1 day',$i));			
				}				
				
			}
			//Total Price
			for($i=1;$i<$total_nights;$i++)
			{
				if($seasonal_prices[$travel_dates[$i]] == "")	
				{	
					$total_price=$total_price+$xprice->night;
				}
				else 
				{
					$total_price= $total_price+$seasonal_prices[$travel_dates[$i]];
					$is_seasonal= 1;
				} 		
			}
			//Additional Guests
			if($data['guests'] > $guests)
			{
			  $days = $total_nights-1;		
			  $diff_guests = $data['guests'] - $guests;
			  $total_price = $total_price + ($days * $xprice->addguests * $diff_guests);
			}
			
			//Cleaning
			if($cleaning != 0)
			{
				$total_price = $total_price + $cleaning;
			}
			
			if($security != 0)
			{
				$total_price = $total_price + $security;
			}
						
			//Admin Commission
			$data['commission'] = 0;
			if($row->is_premium == 1)
			{
			   if($row->is_fixed == 1)
				{
					$fix                = $row->fixed_amount; 
				   $amt                = $total_price + get_currency_value_lys($row->currency,get_currency_code(),$fix);
				   $data['commission'] = get_currency_value_lys($row->currency,get_currency_code(),$fix);
				}
				else
				{  
					$per                = $row->percentage_amount; 
					$camt               = floatval(($total_price * $per) / 100);
					$amt                = $total_price + $camt;
					$data['commission'] = round($camt,2);	
				}
			}
			
		}
		if($is_seasonal==1)
		{	
			//Total days
			$days 			= $total_nights;
			//Final price	
			$data['price'] 	= $total_price;						
		}	
	else
		{		
		if(($ckin[0] == "mm" && $ckout[0] == "mm") or ($ckin[0] == "" && $ckout[0] == ""))
		{
		 	$days = 0;
			
   			$data['price']   = $price;

			if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
			
			$data['commission'] = 0;
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix                = $row->fixed_amount; 
				  						$amt                = $price + get_currency_value_lys($row->currency,get_currency_code(),$fix);
				  						$data['commission'] = get_currency_value_lys($row->currency,get_currency_code(),$fix);
										$Fprice             = $amt;
							}
							else
							{  
										$per                = $row->percentage_amount; 
										$camt               = floatval(($price * $per) / 100);
										$amt                = $price + $camt;
										$data['commission'] = round($camt,2);
										$Fprice             = $amt;
							}
							
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
		   }
			} 
		else
		{	
			$diff = strtotime($ckout[2].'-'.$ckout[0].'-'.$ckout[1]) - strtotime($ckin[2].'-'.$ckin[0].'-'.$ckin[1]);
			$days = ceil($diff/(3600*24));
			
			$price = $price * $days;
			
			//Additional guests
			if($data['guests'] > $guests)
			{
			  	$diff_days = $data['guests'] - $guests;
			  	$price     = $price + ($days * $xprice->addguests * $diff_days);
			}
				
					
			if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
			$data['commission'] = 0;
			
			
			if($days >= 7 && $days < 30)
			{
			 if(!empty($Wprice))
				{
				  $finalAmount     = $Wprice;
						$differNights    = $days - 7;
						$perDay          = $Wprice / 7;
						$per_night       = round($perDay, 2);
						if($differNights > 0)
						{
						  $addAmount     = $differNights * $per_night;
								$finalAmount   = $Wprice + $addAmount;
						}
						$price           = $finalAmount;
						//Additional guests
						if($data['guests'] > $guests)
						{
			  				$diff_days = $data['guests'] - $guests;
			  				$price     = $price + ($days * $xprice->addguests * $diff_days);
						}
				}
			}
			
			
			if($days >= 30)
			{
			 if(!empty($Mprice))
				{
				  $finalAmount     = $Mprice;
						$differNights    = $days - 30;
						$perDay          = $Mprice / 30;
						$per_night       = round($perDay, 2);
						if($differNights > 0)
						{
						  $addAmount     = $differNights * $per_night;
								$finalAmount   = $Mprice + $addAmount;
						}
						$price           = $finalAmount;
						//Additional guests
						if($data['guests'] > $guests)
						{
			  				$diff_days = $data['guests'] - $guests;
			  				$price     = $price + ($days * $xprice->addguests * $diff_days);
						}
				}
			}	
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix                = $row->fixed_amount; 
				  						$amt                = $price + get_currency_value_lys($row->currency,get_currency_code(),$fix);
				  						$data['commission'] = get_currency_value_lys($row->currency,get_currency_code(),$fix);
									    //print_r($data['commission']);
										$Fprice             = $amt;
							}
							else
							{  
										$per                = $row->percentage_amount; 
										$camt               = floatval(($price * $per) / 100);
										$amt                = $price + $camt;
										$data['commission'] = round($camt,2);
										$Fprice             = $amt;
							}
							
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
		   }

					$xprice         = $this->Common_model->getTableData( 'list', array('id' => $id ) )->row();
		
			
					if($cleaning != 0)
					{
					$price = $price + $cleaning;
					}	
					
					if($security != 0)
					{
					$price = $price + $security;
					}	
					
			  			$data['price']    = $price;
					}
		}				

            $data['price'] = get_currency_value1($id,$data['price']);
			
		   	$data['commission'] = get_currency_value1($id,$data['commission']);
			
			$list['price']=$data['price'];
			
			$list['admin_commission']=$data['commission'];
			$list['send_date']=local_to_gmt();
			$list['status']=1;
			$query_list		= $this->Common_model->getTableData( 'list',array('id' => $id) )->row();
			$list['userto'] = $query_list->user_id;
			$list['userby'] = $this->dx_auth->get_user_id();
			$key=substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789',5)),0,9);
			$list['contact_key']=$key;
			$query_user 	= $this->Common_model->getTableData('users',array('id' => $list['userby']))->row();
			$username 		= $query_user->username;
			$this->Common_model->insertData('contacts', $list);		
			$contact_id = $this->db->insert_id();	
			$query_name  = $this->Users_model->get_user_by_id($list['userby'])->row();
			$buyer_name  = $query_name->username;
			$link = base_url().'contacts/request/'.$contact_id;
			
			//Send Message Notification
			$insertData = array(
				'list_id'         => $list['list_id'],
				'contact_id'  	  => $contact_id,
				'userby'          => $list['userby'],
				'userto'          => $list['userto'],
				'message'         => '<b>You have a new contact request from '.ucfirst($username).'</b><br><br>'.$message,
				'created'         => local_to_gmt(),
				'message_type'    => 7
				);
			
			$this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $query_list->title, $contact_id);
			
			//Send mail to host
			$query        = $this->Common_model->getTableData( 'list',array('id' => $id) )->row();
			$host_id        = $query->user_id;
			$list_email		= $this->Common_model->getTableData('users',array('id' => $host_id))->row()->email; 
			$host_username		= $this->Common_model->getTableData('users',array('id' => $host_id))->row()->username; 
			$query2 = $this->Common_model->getTableData('users',array('id' => $this->dx_auth->get_user_id()))->row();
		  	$user_email   =	$query2->email;					
		    $admin_name  						= $this->dx_auth->get_site_title();
			$admin_email = $this->dx_auth->get_site_sadmin();
			
        $email_name = 'contact_request_to_host';
		$splVars    = array("{link}"=>$link,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{guest}"=>$data['guests'],"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($host_username), "{title}" => $query->title);
		$this->Email_model->sendMail($list_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
	    
		$email_name = 'contact_request_to_guest';
		$splVars    = array("{traveller_username}"=> $query2->username,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{guest}"=>$data['guests'],"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($host_username), "{title}" => $query->title);
		$this->Email_model->sendMail($user_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);	
		
		if($list_email != $admin_email && $user_email != $admin_email)
		{
		$email_name = 'contact_request_to_admin';
		$splVars    = array("{price}"=>'$'.$list['price'],"{traveller_username}"=> $query2->username,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{guest}"=>$data['guests'],"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($host_username), "{title}" => $query->title);
		$this->Email_model->sendMail($admin_email,$list_email,ucfirst($admin_name),$email_name,$splVars);
		}
			}
			}
			}		  
			echo json_encode($data);
	}
	
	function hide_email($email) { $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz'; $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999); for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])]; $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";'; $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));'; $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"'; $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>'; return '<span id="'.$id.'">[javascript protected email address]</span>'.$script; }
	
	function form($param = '')
	{
		
		$refer=$this->db->query("select * from `referral_management` where `id`=1 ")->row();
		//$data['fixed_status']=$refer->fixed_status;
		$refamt=$refer->fixed_amt;
		$refcur=$refer->currency;
		$type=$refer->type;
		$trip_amt=$refer->trip_amt;
		$trip_per=$refer->trip_per;
		$rent_amt=$refer->rent_amt;
		$rent_per=$refer->rent_per;
		$ref_total=get_currency_value2($refcur,'USD',$refamt);
		if($type==1){
			$trip_amt0=$trip_amt;
			$rent_amt0=$rent_amt;
		$trip=get_currency_value2($refcur,'USD',$trip_amt);
		$rent=get_currency_value2($refcur,'USD',$rent_amt);
		}
		if($type==0){
			$trip=(($trip_per)/100)*$ref_total;
			$rent=(($rent_per)/100)*$ref_total;
		$current = $this->session->userdata("locale_currency");
			
		}	
	
		     $check_paypal = $this->db->where('is_enabled',1)->get('payments')->num_rows();
	    
			  if($check_paypal == 0)
			  {
				   $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate("Payment gateway is not enabled. Please contact admin.")));
				   redirect('rooms/'.$param);
			  }
			 
		
		 if($this->input->get('contact'))
		 {		
				      $contact_key=$this->input->get('contact');
				    
					   $contact_result=$this->Common_model->getTableData('contacts',array('contact_key' => $contact_key))->row();
				 
						if($contact_result->status== 10)
						 {
						 	   $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Access denied.')));
							   redirect('rooms/'.$param, "refresh");	
						 }
				
						 if($contact_result->userby != $this->dx_auth->get_user_id())
						 {
						 	  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('You are not a valid user to use this link.')));
							  redirect('rooms/'.$param, "refresh");	
						 }
				 
				         $checkin        = $contact_result->checkin;
		  		          $checkout       = $contact_result->checkout;
					      $daysexist = $this->db->query("SELECT id,list_id,booked_days FROM `calendar` WHERE `list_id` = '".$param."' AND (`booked_days` >= '".get_gmt_time(strtotime($checkin))."' AND `booked_days` <= '".get_gmt_time(strtotime($checkout))."') GROUP BY `list_id`");
			  
			          if($daysexist->num_rows() == 1)
				      {
					        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Already Those dates are booked')));
				            redirect('rooms/'.$param, "refresh");
				      }
						 $data['guests'] = $contact_result->no_quest;
						 $data['contact_key'] = $contact_result->contact_key;
						 $data['offer']=$contact_result->offer;
		}
		else if($this->session->userdata('formCheckout'))
		{
		 			$checkin        = $this->session->userdata('Lcheckin');
  		            $checkout       = $this->session->userdata('Lcheckout');
	  				$data['guests'] = $this->session->userdata('number_of_guests');
		}
  		else if($this->input->get())
        {
					$checkin         = $this->input->get('checkin');
					$checkout        = $this->input->get('checkout');
				    $data['guests']  = $this->input->get('guest');
        }
        else
		{
					$checkin         = $this->input->post('checkin');
					$checkout        = $this->input->post('checkout');
					$data['guests']  = $this->input->post('number_of_guests');
		}
		
					$data['checkin']  = $checkin;
					$data['checkout'] = $checkout;
		
				  $date1 = new DateTime(date('Y-m-d H:i:s',strtotime($checkin)));
				  $date2 = new DateTime(date('Y-m-d H:i:s',strtotime($checkout)));
				  $interval = $date1->diff($date2);
		
		if($interval->days >= 28)
		{
			 $data['flash_message'] = "Your reservation is 28 or more days. So, the cacellation policy will be changed to Long Term.";
		}

		$ckin             = explode('/', $checkin);
		$ckout            = explode('/', $checkout);
		$id               = $param;
		
		if($ckin[0]  == "mm")
		{ 
			   //$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Sorry! Access denied.'));
			   redirect('rooms/'.$id, "refresh");
		} 
		if($ckout[0] == "mm") 
		{ 
		      //	$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Sorry! Access denied.'));
			  redirect('rooms/'.$id, "refresh");
		}
		

        $xprice         = $this->Common_model->getTableData( 'price', array('id' => $param) )->row();
		
		//  print_r($xprice);
		    /* if($this->input->get())
			{
				$price = $this->input->get('subtotal');	
			}
	       else {*/
		    $price          = $xprice->night;
		  // print_r($price);
			//}
		 $placeid        = $xprice->id;
			
			$guests         = $xprice->guests;
		
	 if(isset($xprice->cleaning))
		$cleaning       = $xprice->cleaning;
		
		else
		$cleaning       = 0;
		
		if(isset($xprice->security))
		$security       = $xprice->security;
		else
		$security       = 0;
		
		$data['cleaning'] = $cleaning;
		
		//print_r($data);
		$data['security'] = $security;
		//print_r($data);
		
		if(isset($xprice->week))
		$Wprice         = $xprice->week;	
		else
		$Wprice         = 0;
		
		if(isset($xprice->month))
		$Mprice         = $xprice->month;	
		else
		$Mprice         = 0;
		
		$query                = $this->Common_model->getTableData('list',array('id' => $id));
		$list                 =	$query->row();
		$data['address']      = $list->address;
		$data['room_type']    = $list->room_type;
		$data['total_guests'] = $list->capacity;
		$data['tit']          = $list->title;
		$data['manual']       = $list->house_rule;
		
		
		$diff                 = strtotime($ckout[2].'-'.$ckout[0].'-'.$ckout[1]) - strtotime($ckin[2].'-'.$ckin[0].'-'.$ckin[1]);
		$days                 = ceil($diff/(3600*24));
		
		/*$amt = $price * $days * $data['guests'];*/
		if($data['guests'] > $guests)
		{
				$diff_days          = $data['guests'] - $guests;
				$amt                = (get_currency_value1($id,$price) * $days) + ($days * get_currency_value1($id,$xprice->addguests) * $diff_days);
				 	
				$data['extra_guest_price'] = get_currency_value1($id,$xprice->addguests) * $diff_days;
		}  
		else
		{
				$amt                = get_currency_value1($id,$price) * $days;
		}

		//Entering it into data variables
		$data['id']           = $id;
		$data['price']        = $xprice->night;
		
		//exit;
		$data['days']         = $days;
		$data['full_cretids'] = 'off';
		
		$data['commission']   = 0;
		
			if($days >= 7 && $days < 30)
			{
			 if(!empty($Wprice))
				{
				  $finalAmount     = $Wprice;
						$differNights    = $days - 7;
						$perDay          = $Wprice / 7;
						$per_night       = $price = round($perDay, 2);
						if($differNights > 0)
						{
						  $addAmount     = $differNights * $per_night;
								$finalAmount   = $Wprice + $addAmount;
						}
						
						$amt             = $finalAmount;
				}
			}
			else 
			{
				   $finalAmount = $amt;
			}
			
			
			if($days >= 30)
			{
			 if(!empty($Mprice))
				{
				  $finalAmount     = $Mprice;
						$differNights    = $days - 30;
						$perDay          = $Mprice / 30;
						$per_night       = $price = round($perDay, 2);
						if($differNights > 0)
						{
						  $addAmount     = $differNights * $per_night;
								$finalAmount   = $Mprice + $addAmount;
						}
						$amt             = $finalAmount;
				}
			}
			else {
				$finalAmount = $amt;
			}	
		//Update the daily price
	 $data['price']        = $xprice->night;
			
	 //Cleaning fee
	 
		if($cleaning != 0)
		{
		
			$amt                = $amt + get_currency_value1($id,$cleaning);
		   // print_r($amt);
		}
		if($security != 0)
		{
			$amt                = $amt + get_currency_value1($id,$security);
		   //	print_r($amt);
		}
		else
		{
			$amt                = $amt;
		}
		
		$session_coupon			= $this->session->userdata("coupon"); 
		if($this->input->get('contact'))
		{
		$amt = get_currency_value_lys($contact_result->currency,get_currency_code(),$contact_result->price);
		$data['subtotal']    = $amt;
	   	$this->session->set_userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'",$amt);
		$query                = $this->Common_model->getTableData('paymode', array( 'id' => 2));
		$row                  = $query->row();
		
		/*if($row->is_premium == 1)
		{
		  if($row->is_fixed == 1)
				{
				   $fix                = $row->fixed_amount; 
				   $amt                = $amt + get_currency_value_lys($row->currency,get_currency_code(),$fix);
				   $data['commission'] = get_currency_value_lys($row->currency,get_currency_code(),$fix);
				}
				else
				{  
				   $per                = $row->percentage_amount; 
				   $camt               = floatval(($amt * $per) / 100);
				   $amt                = $amt + $camt;
				   $data['commission'] = round($camt,2);
				   $this->session->set_userdata('contact_commission',$camt);
				}
				
		}
		else
		{
		$amt  = $amt;
		}*/
		
		}
		else
		{
			//$amt=$this->session->userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'");
		}
		$this->session->set_userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'",$amt);
		$this->session->unset_userdata('coupon_code_used');
		
		//Coupon Starts
		if($this->input->post('apply_coupon'))
		{
			$is_coupon=0;
			//Get All coupons
			$query 			= $this->Common_model->get_coupon();
			$row   			=	$query->result_array();
			
			$list_id 		= $this->input->post('hosting_id');
			$coupon_code 	= $this->input->post('coupon_code');
			$user_id 		= $this->dx_auth->get_user_id();
					
			if($coupon_code != "")
			{
				$is_list_already	= $this->Common_model->getTableData('coupon_users', array( 'list_id' => $list_id,'user_id' => $user_id));
				$is_coupon_already	= $this->Common_model->getTableData('coupon_users', array( 'used_coupon_code' => $coupon_code,'user_id' => $user_id,'status'=>1));
				//Check the list is already access with the coupon by the host or not
				/*if($is_list_already->num_rows() != 0)
				{
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Sorry! You cannot use coupons for this list'));	
					redirect('rooms/'.$list_id, "refresh");
				}
				//Check the host already used the coupon or not
				else*/ if($is_coupon_already->num_rows() != 0)
				{
					$this->session->unset_userdata('coupon_code_used');
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your coupon is invalid')));	
					redirect('rooms/'.$list_id, "refresh");
				}
                 
				else 
				{
				//Coupon Discount calculation	
				foreach($row as $code)
				{
					if($coupon_code == $code['couponcode'])
					{
						//Currecy coversion
						$is_coupon			= 1;
						$current_currency	= get_currency_code();
						$coupon_currency	= $code['currency'];
						//if($current_currency == $coupon_currency) 
						$Coupon_amt = $code['coupon_price'];
						//else
						//$Coupon_amt = get_currency_value_coupon($code['coupon_price'],$coupon_currency); 
					}
				}
				if($is_coupon == 1)
				{
					//echo $Coupon_amt.'<br>';
					$list_currency     = $this->db->where('id',$list_id)->get('list')->row()->currency;
					//if($coupon_currency != $list_currency)
					$Coupon_amt  = get_currency_value_lys1($coupon_currency,get_currency_code(),$Coupon_amt);
					//echo $Coupon_amt.'<br>';exit;
					//echo $amt.'<br>';
					if($Coupon_amt >= $amt)
					{
						$this->session->unset_userdata('coupon_code_used');
						$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! There is equal money or more money in your coupon to book this list.')));	
					  	redirect('rooms/'.$list_id, "refresh");				
					}
					else
					{
						//Get the result amount & store the coupon informations	
						//echo $Coupon_amt;exit;
						$amt 				= $amt - $Coupon_amt;
						
						$insertData         = array(
						'list_id'			=> $list_id,
						'used_coupon_code'  => $coupon_code,
						'user_id'			=> $user_id,
						'status'			=> 0 
						);
						//echo $Coupon_amt.' - '.$amt;
						//echo get_currency_value1($list_id,$amt);exit;
						if($amt < get_currency_value_lys('USD',get_currency_code(),1))
						{
					    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your payment should be greater than 0.')));	
						redirect('rooms/'.$id, "refresh");
						}
						$this->Common_model->inserTableData('coupon_users',$insertData);
                     /*   $this->db->where('couponcode',$coupon_code)->update('coupon',array('status'=>1));*/
						$this->session->set_userdata("total_price_'".$list_id."'_'".$user_id."'",$amt);
						$this->session->set_userdata('coupon_code_used',1);
						$this->session->set_userdata('coupon_code',$coupon_code);
						$this->session->set_userdata('coupon_amt',$Coupon_amt);
						//echo	$this->session->userdata("coupon_amt");exit;
			         }
				}
				else 
				{
					   $this->session->unset_userdata('coupon_code_used');
					   $this->session->unset_userdata('coupon_code');
					   $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your coupon does not match.')));	
					   redirect('rooms/'.$list_id, "refresh");
				}
				
				}	
			}
			else 
			{
				    $this->session->unset_userdata('coupon_code_used');
					$this->session->unset_userdata('coupon_code');
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your coupon does not match.')));	
					redirect('rooms/'.$list_id, "refresh");
			}
		}
	else {
		$this->session->unset_userdata('coupon_code_used');
		$this->session->unset_userdata('coupon_code');
	}
		//Coupon Ends
		//echo $amt;exit;
		if($is_coupon != 1)
		{
		if(!$this->input->get('contact'))
		$data['subtotal']    = round($amt,2);		 
		}
		else
		{
		$data['subtotal']    = round($amt,2);	
		//echo "$data";
		}
		
		//if($this->session->userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'") == "")
		//{ echo 'total';exit;
			//redirect('rooms/'.$param, "refresh");
		//	$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Please! Try Again'));
		//}
		//check admin premium condition and apply so for
		$query                = $this->Common_model->getTableData('paymode', array( 'id' => 2));
		$row                  = $query->row();
		
		//if($is_coupon != 1)
		//{
		//if(!$this->input->get('contact'))
		//{
					
		if($row->is_premium == 1)
		{
		if($row->is_fixed == 1)
				{
				   $fix                = $row->fixed_amount; 
				   $amt                = $amt + get_currency_value_lys($row->currency,get_currency_code(),$fix);
				   $data['commission'] = get_currency_value_lys($row->currency,get_currency_code(),$fix);
			      
				}
				else
				{
				   
				      $per                = $row->percentage_amount; 
				     
				      $finalAmount = $finalAmount+$cleaning+$security;
				      $camt               = floatval(($finalAmount * $per) / 100);
					  $amt                = $amt + $camt;
				      $data['commission'] = round($camt,2);
				    			
				}
				
		}
		else
		{
		$amt  = $amt;
		}
	//	}
	//	}
		
		// Coupon Code Starts
			$ref_total1=$ref_total+10;
		if($amt > get_currency_value1($id,$ref_total1))
		{
		if($this->db->select('referral_amount')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount !=0 )
		{
		   $data['amt']    = $amt;
		   $data['referral_amount'] = $this->db->select('referral_amount')->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
		 }
        else
	    {
		 $data['amt'] = $amt;
	    }
		}
		else {
			$data['amt'] = $amt;
		}
		
    
		if($amt < 0)
		{
						$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your payment should be greater than 0.')));	
						redirect('rooms/'.$id, "refresh");
		}

		if($amt <= get_currency_value_lys('USD',get_currency_code(),10))
		{
			           $validation_amt = get_currency_value1($id,10);
						$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate("Sorry! Your payment should be greater than or equal to $validation_amt.")));	
						redirect('rooms/'.$id, "refresh");
		}
		
		$data['result']    = $this->Common_model->getTableData('payments')->result();
		
		$array_items = array(
							'list_id'           => '',
							'Lcheckin'          => '',
							'Lcheckout'         => '',
							'number_of_guests'	=> '',
							'formCheckout'      => ''
							);
  		$this->session->unset_userdata($array_items);
		
		    //$id = $list_id;
			$checkin_time		= get_gmt_time(strtotime($checkin));
		   
			$checkout_time		= get_gmt_time(strtotime($checkout));
			$travel_dates		= array();
			$seasonal_prices 	= array();		
			$total_nights		= 1;
			$total_price		= 0;
			$is_seasonal		= 0;
			$i					= $checkin_time;
			//print_r($i);
			//exit;
			while($i<$checkout_time)
			{
				$checkin_date					= date('m/d/Y',$i);
				$checkin_date					= explode('/', $checkin_date);
				$travel_dates[$total_nights]	= $checkin_date[1].$checkin_date[0].$checkin_date[2];
				$i								= get_gmt_time(strtotime('+1 day',$i));
				$total_nights++; 
			}
			for($i=1;$i<$total_nights;$i++)
			{
				$seasonal_prices[$travel_dates[$i]]="";
			}
		//Store seasonal price of a list in an array
  //		
		
		$seasonal_query	= $this->Common_model->getTableData('seasonalprice',array('list_id' => $id));
		$seasonal_result= $seasonal_query->result_array();
		//print_r($seasonal_result);
		if($seasonal_query->num_rows()>0)
		{
			foreach($seasonal_result as $time)
			{
			//Get Seasonal price
				$seasonalprice_query	= $this->Common_model->getTableData('seasonalprice',array('list_id' => $id,'start_date' => $time['start_date'],'end_date' => $time['end_date']));
				$seasonalprice 			= $seasonalprice_query->row()->price;	
				//Days between start date and end date -> seasonal price	
				$start_time	= $time['start_date'];
				$end_time	= $time['end_date'];
				$i			= $start_time;
				//print_r($i);
				//exit;
				while($i<=$end_time)
				{	
					$start_date					= date('m/d/Y',$i);
					$s_date						= explode('/',$start_date);	
					//print_r($s_data);
					$s_date						= $s_date[1].$s_date[0].$s_date[2];
					$seasonal_prices[$s_date]	= $seasonalprice;
					//print_r($seasonal_prices[$s_date]);
					$i							= get_gmt_time(strtotime('+1 day',$i));			
				}				
				
			}
			//Total Price
			//print_r($total_nights);
			for($i=1;$i<$total_nights;$i++)
			{
				if($seasonal_prices[$travel_dates[$i]] == "")	
				{	$xprice         = $this->Common_model->getTableData( 'price', array('id' => $id ) )->row();
					//print_r($xprice);
					$total_price=get_currency_value1($id,$total_price)+get_currency_value1($id,$xprice->night);
				 // echo "$total_price";
				}
				else 
				{
					 $total_price= get_currency_value1($id,$total_price)+get_currency_value1($id,$seasonal_prices[$travel_dates[$i]]);
					//echo "$total_price";
					$is_seasonal= 1;
				} 		
			} 
		//Additional Guests
			if($data['guests'] > $guests)
			{
			  $days = $total_nights-1;		
			  $diff_guests = $data['guests'] - $guests;
			  $total_price = get_currency_value1($id,$total_price) + ($days * get_currency_value1($id,$xprice->addguests) * $diff_guests);
			  
			  $data['extra_guest_price'] = get_currency_value1($id, $xprice->addguests) * $diff_guests;
			}
			
		//	print_r($data['subtotal']);
		
		//	$data['avg_price'] = $data['subtotal']/$days; 
 //price	
 		
			if($is_seasonal==1)
		    {
			 $data['avg_price'] = $data['subtotal']/($days+1);
		   }
			else {
				
				$data['price']        = $xprice->night;
				
			} 
				
//price		
			
			//print_r($data['avg_price']);
			//Cleaning
			
			if($cleaning != 0)
			{
				$cleaning_price=get_currency_value1($id,$cleaning);
				
				 $total_price = $total_price + get_currency_value1($id,$cleaning);
				
			}
			
			if($security != 0)
			{
				$total_price = $total_price + get_currency_value1($id,$security);
			}
			//Admin Commission
			//$data['commission'] = 0;		
		}
		
		
		if($is_seasonal==1)
		{
					
				//Total days
			  	$days 			= $total_nights;
			
				//Final price	
			     $data['avg_price']=($total_price-$data['security']- $data['cleaning'])/($days-1);
			     
			//    $data['avg_price'] = get_currency_value1($id,$total_price)/($days-1);
			   
			     if($contact_key != '')
			     {
				  	    $amt	=$data['subtotal'];
					     $total_price=$amt;
			      }
			     else
			      {
					    $amt = $data['avg_price'];	
			      }
				
	              $query                = $this->Common_model->getTableData('paymode', array( 'id' => 2));
			      $row                  = $query->row();
			      if($row->is_premium == 1)
			      {
						if($row->is_fixed == 1)
					    {
						     $fix                 = $row->fixed_amount; 
						     $amt                 = $amt + get_currency_value_lys($row->currency,get_currency_code(),$fix);
						     $data['commission']  = get_currency_value_lys($row->currency,get_currency_code(),$fix);
						   
						}
						else
						{
						      $per                = $row->percentage_amount; 
						      $percentage=$per/100;
					          $amount=($total_price)*$percentage;
					         $data['commission']= $amount;
															
							
								 /* $per                = $row->percentage_amount; 
								    $camt               = floatval(($data['avg_price'] * $per) / 100);
								   //print_r($camt);
								   $amt                = $amt + $camt;
								  // print_r($amt);
								   $data['commission'] = round($camt,2);
								  //print_r($data['commission']);*/
								  						  
						}
		   }
				  
		  else
		  {
		         $amt  = $amt;
							 
				 
		   }
						
							if(($this->session->userdata('coupon_code_used')==1)) 
							{
								if($contact_key != '')
								 {
								 				//echo "success";
									 
										$total_price =$amt+$this->session->userdata("coupon_amt");
								       
									  $data['subtotal'] 	= $total_price-$this->session->userdata("coupon_amt");		
								      $data['amt'] = $data['subtotal']+$data['commission'] ;
								      //	$amt=$data['amt']+$cleaning+$security+$data['commission'];
								      $amt=$data['amt'];
								    
									  $this->session->set_userdata('topay',$amt);
										
								 	
								 }
								 else
								 {
								     
								      $amt =$amt-$this->session->userdata("coupon_amt");
								      $data['subtotal'] 	= $total_price-$this->session->userdata("coupon_amt");		
								    	  $data['amt'] = $data['subtotal']+$data['commission'] ;
								      //	$amt=$data['amt']+$cleaning+$security+$data['commission'];
								      $amt=$data['amt'];
								    // print_r($amt);
									  $this->session->set_userdata('topay',$amt);
								  }		
							 	
							}
							else
							{
								 $data['subtotal'] 	= $total_price;	
							    							    //print_r($data['subtotal']);
							    //$data['amt'] = $data['avg_price'] * ($days-1)+$cleaning+$security+$data['commission'];
								 $data['amt'] = $total_price +$data['commission'];
								$amt=$data['amt'];
							    // $amt; exit;
							    $this->session->set_userdata('topay',$amt);
									
							}
							
							
				
			
	    }
//$data['subtotal']= $data['subtotal']+$cleaning_price; 		
       $data['amt'] = round($amt,2);
	    $data['policy']  = $this->Common_model->getTableData('cancellation_policy',array('id'=>$list->cancellation_policy))->row()->name;
       // Advertisement popup 1 start
       
	    // Advertisement popup 1 end
        
        $data['countries']			  = $this->Common_model->getCountries()->result();
		$data['title']                = get_meta_details('Confirm_your_booking','title');
		$data["meta_keyword"]         = get_meta_details('Confirm_your_booking','meta_keyword');
		$data["meta_description"]     = get_meta_details('Confirm_your_booking','meta_description');		
		$data['message_element']      = "payments/view_booking";
		$this->load->view('template',$data);
	}
	
	
	public function payment($param = "", $env="")
	{
		    if($this->input->post('agrees_to_terms') != 'on')
			{
					$newdata = array(
										'list_id'                 => $param,
										'Lcheckin'                => $this->input->post('checkin'),
										'Lcheckout'               => $this->input->post('checkout'),
										'number_of_guests'					   => $this->input->post('number_of_guests'),
										'formCheckout'            => TRUE
						);
					$this->session->set_userdata($newdata);
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('You must agree to the Cancellation Policy and House Rules!')));	
		  	        redirect('payments/index/'.$param,'refresh');
			}
			
			 $contact_key=$this->input->post('contact_key');
			 
			 if($contact_key != '')
			 {
			 	$this->session->set_userdata('contact_key',$contact_key);
			 }
			 else {
				$this->session->unset_userdata('contact_key'); 
			 }
			 
			/*$contact_key=$this->input->post('contact_key');
			$updateKey      		  = array('contact_key' => $contact_key);
			$updateData               = array();
			$updateData['status']    = 10;
			$this->Contacts_model->update_contact($updateKey,$updateData);*/
			
		/*	if($this->session->userdata("total_price_'".$param."'_'".$this->dx_auth->get_user_id()."'") == "")
			{
				redirect('rooms/'.$param, "refresh");
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Please! Try Again'));
		
             }*/

			 if($this->input->post('payment_method') == 'braintree')
			{
			   $this->submission_cc($param);
			}
                        // brain tree 2 end( change the below condition as else if)

		       if($this->input->post('payment_method') == 'paypal' || $env="mobile")
			{
			  
			   $this->submission($param,$contact_key);
			
			}
			else if($this->input->post('payment_method') == '2c')
			{
						//$this->submissionTwoc($param);	
			}
			else
			{
			   redirect('info');	
			}
	
	}
		
	function submission($param='',$contact_key)
	{
				$refer=$this->db->query("select * from `referral_management` where `id`=1 ")->row();
		//$data['fixed_status']=$refer->fixed_status;
		$refamt=$refer->fixed_amt;
		$refcur=$refer->currency;
		$type=$refer->type;
		$trip_amt=$refer->trip_amt;
		$trip_per=$refer->trip_per;
		$rent_amt=$refer->rent_amt;
		$rent_per=$refer->rent_per;
		$ref_total=get_currency_value2($refcur,'USD',$refamt);
		if($type==1){
			$trip_amt0=$trip_amt;
			$rent_amt0=$rent_amt;
		$trip=get_currency_value2($refcur,'USD',$trip_amt);
		$rent=get_currency_value2($refcur,'USD',$rent_amt);
		}
		if($type==0){
			$trip=(($trip_per)/100)*$ref_total;
			$rent=(($rent_per)/100)*$ref_total;
		$current = $this->session->userdata("locale_currency");
			
		}
				$checkin          = $this->input->post('checkin');
				$checkout         = $this->input->post('checkout');
				$number_of_guests = $this->input->post('number_of_guests');
				$ckin             = explode('/', $checkin);
				$ckout            = explode('/', $checkout);
				$id               = $this->uri->segment(3);
		
			if($this->session->userdata('mobile_user_id'))
			{
				$user_id = $this->session->userdata('mobile_user_id');
				$this->session->unset_userdata('mobile_user_id');
			}
			else
				{
					$user_id = $this->dx_auth->get_user_id();
				}
			
			if($ckin[0] == "mm")
			{
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Access denied.')));
				redirect('rooms/'.$id, "refresh");
			} 
			if($ckout[0] == "mm") 
			{
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Access denied.')));
				redirect('rooms/'.$id, "refresh");
			}
			
			$xprice      		= $this->Common_model->getTableData('price',array('id' => $this->uri->segment(3)))->row();
			
		
			$price          = $xprice->night;
		
			//$price      		 = $xprice->night;
			$placeid     		= $xprice->id;
			
			$guests      		= $xprice->guests;
			
			$extra_guest_price  = $xprice->addguests;
			
			if(isset($xprice->cleaning))
			$cleaning   		 = $xprice->cleaning;
			else
			$cleaning   		 = 0;
			
			if(isset($xprice->security))
			$security   		 = $xprice->security;
			else
			$security   		 = 0;
			
			if(isset($xprice->week))
			$Wprice         = $xprice->week;	
			else
			$Wprice         = 0;
			
			if(isset($xprice->month))
			$Mprice         = $xprice->month;	
			else
			$Mprice         = 0;
					
			$query         = $this->Common_model->getTableData('list',array('id' => $id));
			$q             =	$query->result();
	
			$diff          = strtotime($ckout[2].'-'.$ckout[0].'-'.$ckout[1]) - strtotime($ckin[2].'-'.$ckin[0].'-'.$ckin[1]);
			$days          = ceil($diff/(3600*24));
			
			$user_travel_cretids     = 0;
			if($this->session->userdata('travel_cretids'))
			{
			   $amt                  = $this->session->userdata('travel_cretids');
						$user_travel_cretids  = $this->session->userdata('user_travel_cretids');
						$is_travelCretids     = md5('Yes Travel Cretids');
			}
			else
			{
						if($number_of_guests  > $guests)
						{
							
								$diff_days = $number_of_guests  - $guests;
							 	$amt       = ($price * $days) + ($days * $xprice->addguests * $diff_days);
						}
						
						
						else
						{
								$amt       = $price * $days;
						}
						
							
					if($days >= 7 && $days < 30)
					{
						if(!empty($Wprice))
						{
								$finalAmount     = $Wprice;
								$differNights    = $days - 7;
								$perDay          = $Wprice / 7;
								$per_night       = round($perDay, 2);
								if($differNights > 0)
								{
										$addAmount     = $differNights * $per_night;
										$finalAmount   = $Wprice + $addAmount;
								}
								$amt             = $finalAmount;
						}
					}
					
					
					if($days >= 30)
					{
						if(!empty($Mprice))
						{
								$finalAmount     = $Mprice;
								$differNights    = $days - 30;
								$perDay          = $Mprice / 30;
								$per_night       = round($perDay, 2);
								if($differNights > 0)
								{
										$addAmount     = $differNights * $per_night;
										$finalAmount   = $Mprice + $addAmount;
								}
								$amt             = $finalAmount;
						}
					}	
				
				//Cleaning fee
				if($cleaning != 0)
				{
					$amt                = $amt + $cleaning;
				}
				if($security != 0)
				{
					$amt                = $amt + $cleaning;
				}
				else
				{
					$amt                = $amt;
				}		
						
						
						$to_pay            = 0;
						$admin_commission  = 0;
						//Amount from session 
					//	$amt=$this->session->userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'");
						//commission calculation
						$query       = $this->Common_model->getTableData('paymode', array( 'id' => 2));
						$row         = $query->row();
						if($row->is_premium == 1)
						{
								if($row->is_fixed == 1)
								{
								   $to_pay           = $amt;
											$fix              = $row->fixed_amount; 
											$amt              =get_currency_value_lys($row->currency,get_currency_code(), $amt) + get_currency_value_lys($row->currency,get_currency_code(),$fix);
											//$amt = $this->session->userdata('topay');
											 $admin_commission = get_currency_value_lys($row->currency,get_currency_code(),$fix);
								}
								else
								{  
								   $to_pay           = $amt;
								 			
											$per              = $row->percentage_amount; 
											$camt             = floatval(($amt * $per) / 100);
											$amt              = $amt + $camt;
										 	$amt   = $amt;
										 	$admin_commission = get_currency_value_lys($row->currency,get_currency_code(),$camt);
								}
						}
						else
						{
						$amt                   = $amt;
						$to_pay                = $amt;
					    //print_r($to_pay);
					
						}
						
						$is_travelCretids = md5('No Travel Cretids');
			}

	
		
		//seasonal
		
		
		
		//seasonal
		
		
		if($contact_key != '')
		{
			$data['contact_key'] = $contact_key;
			$contact_result = $this->db->where('contact_key',$contact_key)->get('contacts')->row();
			$this->session->set_userdata('contacts_offer',$contact_result->offer);
			$amt = $contact_result->price+$contact_result->admin_commission;
		}
		$ref_total1=$ref_total+10;
			
		if($amt > $ref_total1)
		{
		if($this->db->select('referral_amount')->where('id',$user_id)->get('users')->row()->referral_amount !=0 )
		{
          $referral_amount = $this->db->select('referral_amount')->where('id',$user_id)->get('users')->row()->referral_amount;
		  
		   if($referral_amount > $ref_total)
		{
			$final_amt = get_currency_value1($id,$amt)-get_currency_value($ref_total);
		}
		else
			{
				$final_amt = $amt-$referral_amount;
				
			}
	  	$amt = $final_amt; 
		}
        else
	    {
		 $amt = $amt;
	    }
		
		}
		else {
			$amt = $amt;
		}
			
		
		$amount = $amt;
		if($contact_key == "")
		$contact_key="None";
		//Entering it into data variables
		$row     = $this->Common_model->getTableData('payment_details', array('code' => 'PAYPAL_ID'))->row();
		$paymode = $this->db->where('payment_name','Paypal')->get('payments')->row()->is_live;
							
		if($this->session->userdata('final_amount') != '')
		{
		$amt = $this->session->userdata('final_amount');
		//print_r($amt);
		$this->session->unset_userdata('final_amount');
		}
		else {
			 $amt = $amt;
	      //  print_r($amt);
			
		}
		
		/*		if($this->session->userdata('per_night') != '')
		{
		$per_night = $this->session->userdata('per_night');
		$this->session->unset_userdata('per_night');
		}
else
	{
		$per_night = $amt;
	} 	*/
	$per_night=$amt/$days;
    
	
	
//echo $amt;exit;

/*if($this->session->userdata('booking_currency_symbol'))
{*/
//$custom = $id.'@'.$user_id.'@'.get_gmt_time(strtotime($checkin)).'@'.get_gmt_time(strtotime($checkout)).'@'.$number_of_guests.'@'.$is_travelCretids.'@'.$user_travel_cretids.'@'.$to_pay.'@'.$admin_commission.'@'.$contact_key.'@'.$cleaning.'@'.$security.'@'.$extra_guest_price.'@'.$guests.'@'.$amt.'@'.$this->session->userdata('booking_currency_symbol');	
/*}
else
{*/


  if($this->session->userdata('contact_commission'))
$admin_commission = $this->session->userdata('contact_commission');



//change Seasonal price 
            $checkin_time		= get_gmt_time(strtotime($checkin));
		    $checkout_time		= get_gmt_time(strtotime($checkout));
			$travel_dates		= array();
			$seasonal_prices 	= array();		
			$total_nights		= 1;
			$total_price		= 0;
			$is_seasonal		= 0;
			$i					= $checkin_time;
			while($i<$checkout_time)
			{
				$checkin_date					= date('m/d/Y',$i);
				$checkin_date					= explode('/', $checkin_date);
				$travel_dates[$total_nights]	= $checkin_date[1].$checkin_date[0].$checkin_date[2];
				$i								= get_gmt_time(strtotime('+1 day',$i));
				//echo "<pre>";
				//print_r($i);
				$total_nights++; 
			  
			}
			for($i=1;$i<$total_nights;$i++)
			{
				 $seasonal_prices[$travel_dates[$i]]="";
			}
			
          //  print_r($seasonal_prices);
            $seasonal_query	= $this->Common_model->getTableData('seasonalprice',array('list_id' => $id));
           $seasonal_result= $seasonal_query->result_array();
           //print_r($seasonal_result);
		   if($seasonal_query->num_rows()>0)
		  {
							   foreach($seasonal_result as $time)
							   {
							   			$seasonalprice_query	= $this->Common_model->getTableData('seasonalprice',array('list_id' => $id,'start_date' => $time['start_date'],'end_date' => $time['end_date']));
										$seasonalprice 			= $seasonalprice_query->row()->price;	
									 
										//Days between start date and end date -> seasonal price	
										$start_time	= $time['start_date'];
									 //	echo "<pre>";
									 //	print_r($start_time);
										$end_time	= $time['end_date'];
										$i			= $start_time;
									
										while($i<=$end_time)
									    {	
										$start_date					= date('m/d/Y',$i);
										$s_date						= explode('/',$start_date);	
										$s_date						= $s_date[1].$s_date[0].$s_date[2];
										$seasonal_prices[$s_date]	= $seasonalprice;
										$i							= get_gmt_time(strtotime('+1 day',$i));			
									  //  echo "<pre>";
									  //  print_r($i);
									    }
										
														
						    	}
                          //echo "<pre>";
					      //print_r($seasonal_prices);
						  //Total Price
						  //print_r($total_nights);
						 // print_r($travel_dates);
					      for($i=1;$i<$total_nights;$i++)
					      {
								if($seasonal_prices[$travel_dates[$i]] == "")	
								{	$xprice         = $this->Common_model->getTableData( 'price', array('id' => $id ) )->row();
									//print_r($xprice);
									$total_price=get_currency_value1($id,$total_price)+get_currency_value1($id,$xprice->night);
								   
								}
								else 
								{
									//print_r($seasonal_prices[$travel_dates[$i]]);	
									$total_price= get_currency_value1($id,$total_price)+$seasonal_prices[$travel_dates[$i]];
									
									$is_seasonal= 1;
								} 		
					      }
						 
		                 // echo "$total_price";
		                
			 //Additional Guests
			// echo $data['guests'] = $this->session->userdata('Vnumber_of_guests');
			   $data['guests']=$this->input->post('number_of_guests');
			 		
									 if($data['guests'] > $guests)
							 {
							 	
								  $days = $total_nights-1;		
								  $diff_guests = $data['guests'] - $guests;
								  $total_price = get_currency_value1($id,$total_price) + ($days * get_currency_value1($id,$xprice->addguests) * $diff_guests);
								
								    $data['extra_guest_price'] = get_currency_value1($id, $xprice->addguests) * $diff_guests;
								
							}
							
							//echo $data['extra_guest_price'];
							//echo "$total_price";
							//Cleaning
							if($cleaning != 0)
							{
								
								$total_price = $total_price + get_currency_value1($id,$cleaning);
							   // echo "$total_price";
							}
							 
							if($security != 0)
							{
								$total_price = $total_price + get_currency_value1($id,$security);
							  // echo "$total_price";
							}
							
			    //    $data['avg_price'] = $total_price/$days;
                    $per_night = $data['avg_price'];
                    $topay =($per_night*$days)-$this->session->userdata("coupon_amt");
								     $query                = $this->Common_model->getTableData('paymode', array( 'id' => 2));
		            $row                  = $query->row();
				   	$percentage=$per/100;
          		    $amount=$per_night*$days*$percentage; 
				    $admin_commission= $amount;
				    $amt=$amt;
					
					$total_price =$total_price-$this->session->userdata("coupon_amt");
					
				//coupon
				if(($this->session->userdata('coupon_code_used')==1)) 
							{
								
								if($contact_key != '')
								 {
								 				//echo "success";
									 $total_price =$amt+$this->session->userdata("coupon_amt");
								     
									  $data['subtotal'] 	= $total_price-$this->session->userdata("coupon_amt");		
								     
								       $data['amt'] = $data['subtotal']+$data['commission'] ;
								     // print_r($data['amt'] );
								      //	$amt=$data['amt']+$cleaning+$security+$data['commission'];
								     
									  $this->session->set_userdata('topay',$amt);
										
								 	
								 }
								 else
								 {
								     
								      $amt =$amt-$this->session->userdata("coupon_amt");
								      $data['subtotal'] 	= $total_price-$this->session->userdata("coupon_amt");		
								      $data['amt'] = $data['subtotal']+$data['commission'] ;
								      //	$amt=$data['amt']+$cleaning+$security+$data['commission'];
								      $amt=$data['amt'];
								      $this->session->set_userdata('topay',$amt);
								 }		
							 	
							}
							else
							{	
								$data['subtotal'] 	= $total_price;	
							    //print_r($data['subtotal']);
							    
							    //$data['amt'] = $data['avg_price'] * ($days-1)+$cleaning+$security+$data['commission'];
								$amt= $data['amt'] = $amt ;
							    $this->session->set_userdata('topay',$data['amt']);
								
									
							}
					
				//coupon
				
			    
}	  	
			    	
//change Seasonal price 		
  	
	
$custom = $id.'@'.$user_id.'@'.get_gmt_time(strtotime($checkin)).'@'.get_gmt_time(strtotime($checkout)).'@'.$number_of_guests.'@'.$is_travelCretids.'@'.$user_travel_cretids.'@'.$total_price.'@'.$admin_commission.'@'.$contact_key.'@'.$cleaning.'@'.$security.'@'.$extra_guest_price.'@'.$guests.'@'.$amt.'@'.$this->session->userdata('booking_currency_symbol').'@'.$per_night;		
//print_r($custom);
//exit;
//won



//$custom = $id.'@'.$user_id.'@'.get_gmt_time(strtotime($checkin)).'@'.get_gmt_time(strtotime($checkout)).'@'.$number_of_guests.'@'.$is_travelCretids.'@'.$user_travel_cretids.'@'.$to_pay.'@'.$admin_commission.'@'.$contact_key.'@'.$cleaning.'@'.$security.'@'.$extra_guest_price.'@'.$guests.'@'.$amt.'@'.$this->session->userdata('booking_currency_symbol').'@'.$per_night;		
//print_r($custom);
//exit;
//}
//$topay=$amt+$data['extra_guest_price'];
 	//echo $amt;exit;						 
$this->session->set_userdata('custom',$custom);
if(get_currency_code() == 'INR' || get_currency_code() == 'MYR' || get_currency_code() == 'ARS' || 
get_currency_code() == 'CNY' || get_currency_code() == 'IDR' || get_currency_code() == 'KRW' 
|| get_currency_code() == 'VND' || get_currency_code() == 'ZAR')
{
	$currency_code = 'USD';
	//$currency_code = $this->session->userdata('booking_currency_symbol');
	$amt = get_currency_value_lys(get_currency_code(),$currency_code,$amt);
	
}

else
	{
		//$currency_code = $this->session->userdata('booking_currency_symbol');
		$currency_code = get_currency_code();
		$amt = $amt;
	}
	
	$this->session->set_userdata('currency_code_payment',$currency_code);
		
			$to_buy = array(
'desc' => 'Purchase from ACME Store',
'currency' => $currency_code,
'type' => 'sale',
'return_URL' => site_url('payments/paypal_success'),
// see below have a function for this -- function back()
// whatever you use, make sure the URL is live and can process
// the next steps
'cancel_URL' => site_url('payments/paypal_cancel'), // this goes to this controllers index()
'shipping_amount' => 0,
'get_shipping' => false);
// I am just iterating through $this->product from defined
// above. In a live case, you could be iterating through
// the content of your shopping cart.
//foreach($this->product as $p) {
$temp_product = array(
'name' => $this->dx_auth->get_site_title().' Transaction',
'number' => $placeid,
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
		redirect('rooms/'.$id, "refresh");
	}
//$this->_error($set_ec_return);
}

	}
// brain tree 3 start

// brain tree 3 end
	
	
	
	function paypal_cancel()
	{
		$data['title']           = "Payment Failed";
		
			if($this->session->userdata('call_back') == 'mobile')
		   {
			$message_element = 'payments/paypal_cancel_mobile';
			}
		else 
		{
			$message_element = 'payments/paypal_cancel';
		}
		$data['message_element']      = $message_element;
		$this->load->view('template',$data);
	}
	
		function paypal_success()
		{
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
$currency_code = $this->session->userdata('currency_code_payment');
if($currency_code == 'INR' || $currency_code == 'MYR' || $currency_code == 'ARS' || 
$currency_code == 'CNY' || $currency_code == 'IDR' || $currency_code == 'KRW' 
|| $currency_code == 'VND' || $currency_code == 'ZAR')
{
	$currency_code = 'USD';
	//$currency_code = $this->session->userdata('booking_currency_symbol');
	$amt = get_currency_value_lys($currency_code,$currency_code,$amt);
	
}
else
	{
		//$currency_code = $this->session->userdata('booking_currency_symbol');
		$currency_code = $currency_code;
		$amt = $amt;
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

if(isset($do_ec_return['L_SHORTMESSAGE0']) && ($do_ec_return['L_SHORTMESSAGE0'] === 'Duplicate Request'))
{
	redirect('home');
}
$refer=$this->db->query("select * from `referral_management` where `id`=1 ")->row();
		//$data['fixed_status']=$refer->fixed_status;
		$refamt=$refer->fixed_amt;
		$refcur=$refer->currency;
		$type=$refer->type;
		$trip_amt=$refer->trip_amt;
		$trip_per=$refer->trip_per;
		$rent_amt=$refer->rent_amt;
		$rent_per=$refer->rent_per;
		$ref_total=get_currency_value2($refcur,'USD',$refamt);
		if($type==1){
			$trip_amt0=$trip_amt;
			$rent_amt0=$rent_amt;
		$trip=get_currency_value2($refcur,'USD',$trip_amt);
		$rent=get_currency_value2($refcur,'USD',$rent_amt);
		}
		if($type==0){
			$trip=(($trip_per)/100)*$ref_total;
			$rent=(($rent_per)/100)*$ref_total;
		$current = $this->session->userdata("locale_currency");
			
		}

$custom = $this->session->userdata('custom');
		$data   = array();
		$list   = array();
		$data   = explode('@',$custom); 
		
		$contact_key	= $data[9];

		$list['list_id']       = $data[0];
		$list['userby']        = $data[1];
		
		$query1      = $this->Common_model->getTableData('list', array('id' => $list['list_id']));
		$buyer_id    = $query1->row()->user_id;
		
		$list['userto']            = $buyer_id;
		$list['checkin']           = $data[2];
		$list['checkout']          = $data[3];
		$list['no_quest']          = $data[4];
			
		$date1 = new DateTime(date('Y-m-d H:i:s',$list['checkin']));
		$date2 = new DateTime(date('Y-m-d H:i:s',$list['checkout']));
		$interval = $date1->diff($date2);
		
		if($interval->days >= 28)
		{
			$list['policy'] 		   = 5;
		}	
		else {
			$list['policy'] 		   = $query1->row()->cancellation_policy;
		}	
				
		$amt = $data[14];
		
		$list['price']             = $data[14];
		$currency                  = $data[15];
			
		$list['payment_id']        = 2;
		$list['credit_type']       = 1;
		$list['transaction_id']    = 0;
  
		$is_travelCretids          = $data[5];
		$user_travel_cretids       = $data[6];
		
		$list['currency']          = get_currency_code();
		if($currency != 'USD')
		{
		  $list['admin_commission']  = $data[8];
		  $list['cleaning']  = $data[10];
		  $list['security']  = $data[11];	
		  $list['topay']     = $this->session->userdata('subtotal');
		}
		else
			{
				$list['admin_commission']  = $data[8];
				$list['cleaning']  = $data[10];
		  $list['security']  = $data[11];	
		  $list['topay']     = $this->session->userdata('subtotal');
			}
		
		$list['guest_count'] = $data[13];

            if($list['no_quest'] > $list['guest_count'])
		        {
		        	if($currency != 'USD')
					{
		        	$list['extra_guest_price']  = $data[12];
					}
					else {
						$list['extra_guest_price']  = $data[12];
					}
				}
				
			if($this->session->userdata('contact_key') != '')
			{
			$updateKey      		  = array('contact_key' => $this->session->userdata('contact_key'));
			$updateData               = array();
			$updateData['status']    = 10;
			$list['contacts_offer'] = $this->session->userdata('contacts_offer');
			$this->Contacts_model->update_contact($updateKey,$updateData);
			}
				
		   	if($contact_key != "None")
			{
			$list['status'] = 1;
			$this->db->select_max('group_id');
				$group_id                   = $this->db->get('calendar')->row()->group_id;
				
				if(empty($group_id))  $countJ = 0; else $countJ = $group_id;
				
				$insertData['list_id']      = $list['list_id'];
				$insertData['group_id']     = $countJ + 1;
				$insertData['availability'] = 'Booked';
				$insertData['booked_using'] = 'Other';
				
					$checkin  = date('m/d/Y', $list['checkin']);
					$checkout = date('m/d/Y', $list['checkout']);
					
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
			else	{
				$listid1=$list['list_id'];
			$instance_book = $this->db->where('id',$listid1)->get('list')->row()->instance_book;
			//echo $this->db->last_query();
			 if($instance_book==1){
			  	$list['status'] = 3; 	
			 }
			 else{
			 $list['status'] = 1; 	
			 }
		   	
		   	}
			if($list['price'] > $rent)
			{
			$user_id = $list['userby'];
			$details = $this->Referrals_model->get_details_by_Iid($user_id);
			$row     = $details->row();
			$count   = $details->num_rows();
			if($count > 0)
			{
									$details1 = $this->Referrals_model->get_details_refamount($row->invite_from);
									$amt_check = $this->db->where('id',$row->invite_from)->get('users');
									 $user=$this->Users_model->get_user_by_id($this->dx_auth->get_user_id())->row();
		$trip1=$user->ref_trip;
		$rent1=$user->ref_rent;	
									if($amt_check->num_rows() == 0)
									{ 						
									$insertData                  = array();
									$insertData['user_id']       = $row->invite_from;
									$insertData['count_trip']    = 1;
									$insertData['amount']        = $trip1;
									$this->Referrals_model->insertReferralsAmount($insertData);
									}
									else
									{
									$count_trip                  = $details1->row()->count_trip;
									$amount                      = $amt_check->row()->amount;
									$updateKey                   = array('id' => $row->id);
									$updateData                  = array();
									$updateData['count_trip']    = $count_trip + 1;
									$updateData['amount']        = $amount + $trip1;
									$this->Referrals_model->updateReferralsAmount($updateKey,$updateData);
									}
				}
			}
			
			$q        =	$query1->result();
			$row_list = $query1->row();
		 $iUser_id = $q[0]->user_id;
			$details2 = $this->Referrals_model->get_details_by_Iid($iUser_id);
			$amt_check1 = $this->db->where('id',$row->invite_from)->get('users');
			$row      = $details2->row();
			$count    = $details2->num_rows();
				if($count > 0)
				{
					 $user=$this->Users_model->get_user_by_id($this->dx_auth->get_user_id())->row();
		$trip1=$user->ref_trip;
		$rent1=$user->ref_rent;	
				 $details3 = $this->Referrals_model->get_details_refamount($row->invite_from);
				 	 $amt_check1 = $this->db->where('id',$row->invite_from)->get('users');
									if($amt_check1->num_rows() == 0)
									{ 							
									$insertData                  = array();
									$insertData['user_id']       = $row->invite_from;
									$insertData['count_book']    = 1;
									$insertData['amount']        = $rent1;
									$this->Referrals_model->insertReferralsAmount($insertData);
									}
									else
									{
									$count_book   = $details3->row()->count_book;
									$amount       = $amt_check1->row()->amount;
									$updateKey                   = array('id' => $row->id);
									$updateData                  = array();
									$updateData['count_trip']    = $count_book + 1;
									$updateData['amount']        = $amount + $rent1;
									$this->Referrals_model->updateReferralsAmount($updateKey,$updateData);
									}
				}
				
			$admin_email = $this->dx_auth->get_site_sadmin();
			$admin_name  = $this->dx_auth->get_site_title();
			
			$query3      = $this->Common_model->getTableData('users',array('id' => $list['userby']));
			$rows        =	$query3->row();
				
			$username    = $rows->username;
			$user_id     = $rows->id;
			$email_id    = $rows->email;
			
			$query4      = $this->Users_model->get_user_by_id($buyer_id);
			$buyer_name  = $query4->row()->username;
			$buyer_email = $query4->row()->email;
			
			//Check md5('No Travel Cretids') || md5('Yes Travel Cretids')
			if($is_travelCretids == '7c4f08a53f4454ea2a9fdd94ad0c2eeb')
			{			
					  	$query5      = $this->Referrals_model->get_details_refamount($user_id);
		     	$amount      = $query5->row()->amount;			
																
								$updateKey                   = array('user_id ' => $user_id);
								$updateData                  = array();
								$updateData['amount']        = $amount -	$user_travel_cretids;
								$this->Referrals_model->updateReferralsAmount($updateKey,$updateData);
								
								$list['credit_type']         = 2;
								$list['ref_amount']          = $user_travel_cretids;

							
							$row = $query4->row();
							
								//sent mail to administrator
							$email_name = 'tc_book_to_admin';
							$splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => date('d-m-Y',$list['checkin']), "{checkout}" => date('d-m-Y',$list['checkout']), "{market_price}" => $user_travel_cretids+$list['price'], "{payed_amount}" => $list['price'],"{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
							//Send Mail
							$this->Email_model->sendMail($admin_email,$email_id,ucfirst($username),$email_name,$splVars);
							

								//sent mail to buyer
							$email_name = 'tc_book_to_host';
							$splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => date('d-m-Y',$list['checkin']), "{checkout}" => date('d-m-Y',$list['checkout']), "{market_price}" => $list['price']);
							//Send Mail
							if($buyer_email!='0')
			{
							$this->Email_model->sendMail($buyer_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
			}
			}
			
		 $list['book_date']           = local_to_gmt();
		 
		 if($this->session->userdata('coupon_code_used') == 1)
		 {
		 $list['coupon'] = $this->session->userdata('coupon_code');
		 
		  $this->db->where('couponcode',$list['coupon'])->update('coupon',array('status'=>1));
		  $this->db->where('used_coupon_code',$list['coupon'])->update('coupon_users',array('status'=>1));
		  
		 $this->session->unset_userdata('coupon_code');
		 $this->session->unset_userdata('coupon_code_used');
		 }
		 else
		 	{
		 		$list['coupon'] = 0;
		 	}
			//Actual insertion into the database
			$this->Common_model->insertData('reservation', $list);		
			$reservation_id = $this->db->insert_id();
			
			
			$reservation_result = $this->Common_model->getTableData('reservation',array('id'=>$reservation_id))->row();
			
			$currency_symbol = $this->Common_model->getTableData('currency',array('currency_code'=>$reservation_result->currency))->row()->currency_symbol;
			
			$price = $reservation_result->currency.' '.$currency_symbol.$reservation_result->price;
			
			$host_price = $reservation_result->currency.' '.$currency_symbol.$reservation_result->topay; 
			
			if($interval->days >= 28)
			{
				$conversation = $this->db->where('userto',$list['userto'])->where('userby',$list['userby'])->order_by('id','desc')->get('messages');
				$conversation1 = $this->db->where('userto',$list['userby'])->where('userby',$list['userto'])->order_by('id','desc')->get('messages');
				
				if($conversation->num_rows() != 0)
				{
				$conversation_id = $conversation->row()->id;	
				}
				elseif($conversation1->num_rows() != 0) 
				{
				$conversation_id = $conversation1->row()->id;
				}
				else
				{
				$conversation_id = $reservation_id.'1';		
				}
				
				//Send Message Notification
			$insertData = array(
				'list_id'         => $list['list_id'],
				'reservation_id'  => $reservation_id,
				'conversation_id' => $conversation_id,
				'userby'          => 1,
				'userto'          => $list['userby'],
				'message'         => "Your reservation is 28 days or more. So, Long Term cancellation policy applied for ".$row_list->title,
				'created'         => local_to_gmt(),
				'message_type'    => 3
				);
			$this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
			
				//Send Message Notification
			$insertData = array(
				'list_id'         => $list['list_id'],
				'reservation_id'  => $reservation_id,
				'conversation_id' => $conversation_id,
				'userby'          => 1,
				'userto'          => $list['userto'],
				'message'         => ucfirst($username)." reservation is 28 days or more. So, Long Term cancellation policy applied for ".$row_list->title,
				'created'         => local_to_gmt(),
				'message_type'    => 3
				);
			$this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
			
			}
//Send Message Notification
			if($instance_book==1){
				$insertData = array(
				'list_id'         => $list['list_id'],
				'reservation_id'  => $reservation_id,
				'conversation_id'  => $reservation_id,
				'userby'          => $list['userto'],
				'userto'          => $list['userby'],
				'message'         => 'Your reservation is successfully done ',
				'created'         => local_to_gmt(),
				'message_type'    => 3
				);
			$this->Message_model->sentMessage($insertData,ucfirst($username), ucfirst($buyer_name), $row_list->title, $reservation_id);
			$message_id     = $this->db->insert_id();
				 $insertData = array(
				'list_id'         => $list['list_id'],
				'reservation_id'  => $reservation_id,
				'userby'          => $list['userby'],
				'userto'          => $list['userto'],
				'message'         => 'You have a new reservation from '.ucfirst($username),
				'created'         => local_to_gmt(),
				'message_type'    => 1
				);
			}
			else{
				 $insertData = array(
				'list_id'         => $list['list_id'],
				'reservation_id'  => $reservation_id,
				'userby'          => $list['userby'],
				'userto'          => $list['userto'],
				'message'         => 'You have a new reservation request from '.ucfirst($username),
				'created'         => local_to_gmt(),
				'message_type'    => 1
				);
			}
	       
			$this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
			$message_id     = $this->db->insert_id();
			
			$actionurl = site_url('trips/request/'.$reservation_id);
			
			//date_default_timezone_set('Asia/Kolkata');
			
   //Reservation Notification To Host
			$email_name = 'host_reservation_notification';
			$splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A',time()), "{traveler_email_id}" => $email_id, "{checkin}" =>  date('m/d/Y',$list['checkin']), "{checkout}" => date('m/d/Y',$list['checkout']), "{market_price}" => $host_price, "{action_url}" => $actionurl);
			//Send Mail
			//
			if($buyer_email!='0')
			{
			$this->Email_model->sendMail($buyer_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
			}
		 //Reservation Notification To Traveller
			$email_name = 'traveller_reservation_notification';
			$splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username));
			//Send Mail
			$this->Email_model->sendMail($email_id,$admin_email,ucfirst($admin_name),$email_name,$splVars);
			
				//Reservation Notification To Administrator
				$email_name = 'admin_reservation_notification';
				$splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A',time()), "{traveler_email_id}" => $email_id, "{checkin}" =>  date('m/d/Y',$list['checkin']), "{checkout}" => date('m/d/Y',$list['checkout']), "{market_price}" => $price, "{payed_amount}" => $list['price'],"{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
				//Send Mail
				$this->Email_model->sendMail($admin_email,$email_id,ucfirst($username),$email_name,$splVars);
				
			//	if($is_block == 'on')
	//	{
				$this->db->select_max('group_id');
				$group_id                   = $this->db->get('calendar')->row()->group_id;
				
				if(empty($group_id)) echo $countJ = 0; else $countJ = $group_id;
				
				$insertData1['list_id']      = $list['list_id'];
				//$insertData['reservation_id'] = $reservation_id;
				$insertData1['group_id']     = $countJ + 1;
				$insertData1['availability'] = 'Not Available';
				$insertData1['booked_using'] = 'Other';
				
					$checkin  = date('m/d/Y', $list['checkin']);
					$checkout = date('m/d/Y', $list['checkout']);
					$days     = getDaysInBetween($checkin, $checkout);
		    // print_r($days);exit;
					$count = count($days);
					$i = 1;
					foreach ($days as $val)
					{
						if($count == 1)
						{
							$insertData1['style'] = 'single';
						}
						else if($count > 1)
						{
							if($i == 1)
							{
							$insertData1['style'] = 'left';
							}
							else if($count == $i)
							{
							$insertData1['notes'] = '';
							$insertData1['style'] = 'right';
							}
							else
							{
							$insertData1['notes'] = '';
							$insertData1['style'] = 'both';
							}
						}	
					$insertData1['booked_days'] = $val;
					$this->Trips_model->insert_calendar($insertData1);				
					$i++;
					}
					
					 $referral_amount = $this->db->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
            if($referral_amount > $ref_total)
			{
				$this->db->set('referral_amount',$referral_amount-$ref_total)->where('id',$this->dx_auth->get_user_id())->update('users');
			}
          //  else
	       // {
		 //$this->db->set('referral_amount',0)->where('id',$this->dx_auth->get_user_id())->update('users');
	       // }
           
		   if($this->session->userdata('call_back') == 'mobile')
		   {
			$message_element = 'payments/paypal_success_mobile';
			}
		else 
		{
			
			 // Advertisement popup 2 start
            // $data['PagePopupContent'] 		= GetPagePopupContent('step4');
	         // Advertisement popup 2 end
			
			$message_element = 'payments/paypal_success';
		}

            $data['title']="Payment Success !";
			$data['message_element']      = $message_element;
			$this->load->view('template',$data);
					
			}
			 else {
$this->_error($do_ec_return);
}
} else {
$this->_error($get_ec_return);
}
		}

function test()
{
	$checkin  = date('m/d/Y', '1413763200');
					$checkout = date('m/d/Y', '1413849600');
					$days     = getDaysInBetween($checkin, $checkout);
		
					$count = count($days);
					$i = 1;
					foreach ($days as $val)
					{
						if($count == 1)
						{
							$insertData1['style'] = 'single';
						}
						else if($count > 1)
						{
							if($i == 1)
							{
							$insertData1['style'] = 'left';
							}
							else if($count == $i)
							{
							$insertData1['notes'] = '';
							$insertData1['style'] = 'right';
							}
							else
							{
							$insertData1['notes'] = '';
							$insertData1['style'] = 'both';
							}
						}	
					$insertData1['booked_days'] = $val;
					echo $val;
					}
}

// brain tree 4 start
// brain tree 4 end

	
	function paypal_ipn()
	{
			$logfile = 'ipnlog/' . uniqid() . '.html';
$logdata = "<pre>\r\n" . print_r($_POST, true) . '</pre>';
file_put_contents($logfile, $logdata);
	}

	function _error($ecd) {
echo "<br>error at Express Checkout<br>";
echo "<pre>" . print_r($ecd, true) . "</pre>";
echo "<br>CURL error message<br>";
echo 'Message:' . $this->session->userdata('curl_error_msg') . '<br>';
echo 'Number:' . $this->session->userdata('curl_error_no') . '<br>';
}

	//Date convert module
	public function dateconvert($date)
	{
		$ckout = explode('/', $date);
		$diff = $ckout[2].'-'.$ckout[0].'-'.$ckout[1];
		return $diff;
	}
	
}

/* End of file payments.php */
/* Location: ./app/controllers/payments.php */
?>