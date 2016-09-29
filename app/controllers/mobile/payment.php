<?php
/**
 * DROPinn Payment Controller Class
 *
 * helps to achieve common tasks related to the site for mobile app like android and iphone.
 *
 * @package     Dropinn
 * @subpackage  Controllers
 * @category    Payment
 * @author      Cogzidel Product Team
 * @version     Version 1.0
 * @link        http://www.cogzidel.com
 
 */
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function Payment()
    {
        parent::__construct();
        
        $this->load->helper('url');
        //$this->load->library('Paypal_Lib');
        $this->load->library('Twoco_Lib');
        $this->load->library('email');
        $this->load->library('DX_Auth'); 
        $this->load->helper('form');
        //$this->load->library('Paypal_Lib');
         
        $this->load->model('Users_model');
        $this->load->model('Gallery');
        $this->load->model('Contacts_model');
        $this->load->model('Trips_model');
        $this->load->model('Referrals_model');
        $this->load->model('Email_model');
        $this->load->model('Message_model');
        
        
        
        require_once APPPATH.'libraries/braintree/lib/Braintree.php';
        
        $merchantId      = $this->db->get_where('payment_details', array('code' => 'BT_MERCHANT'))->row()->value;
        $publicKey       = $this->db->get_where('payment_details', array('code' => 'BT_PUBLICKEY'))->row()->value;
        $privateKey       = $this->db->get_where('payment_details', array('code' => 'BT_PRIVATEKEY'))->row()->value;
        $paymode     = $this->Common_model->getTableData('payments', array('payment_name' => 'CreditCard'))->row()->is_live;
        if($paymode == 0)
        {
            $paymode = 'sandbox';
        }
        else {
            $paymode = 'production';
        }
        
        Braintree_Configuration::environment($paymode);
        Braintree_Configuration::merchantId($merchantId);
        Braintree_Configuration::publicKey($publicKey);
        Braintree_Configuration::privateKey($privateKey); 
        
        
    }
            public function index()
    {
        
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
        
    /*public function guest_reservation()
    {
        $user_id=$this->input->get('user_id');
        
        $query=$this->db->where('userby',$user_id)->get('messages');
    if($query->num_rows()!=0)
    {
        foreach($query->result() as $row)
    {
        $data['room_id']=$row->list_id;
        $data['userby']=$row->userby;
        $data['userto']   = $row->userto;
        $query3=$this->db->where('id',$data['userto'])->get('users');
        foreach($query3->result() as $row3)
    {
        $data['username']  = $row3->username;
        $email =$row3->email;;
    }
    $query4=$this->db->where('email',$email)->get('profile_picture');
    //print_r($this->db->last_query());exit;
        foreach($query4->result() as $row4)
    {
        $data['profile_pic']  = $row4->src;
        
    }
        
        $data['message']   = $row->message;
        $query2=$this->db->where('id',$data['room_id'])->get('list');
        foreach($query2->result() as $row2)
    {
        $data['title']  = $row2->title;
        
    }
    
        $query1=$this->db->where('userto',$data['userto'])->get('reservation');
        foreach($query1->result() as $row1)
    {
        $start_date   = $row1->checkin;
        $data['checkin']  = gmdate('F d, Y',$start_date);
        $end_date   = $row1->checkout;
        $data['checkout']  = gmdate('F d, Y',$end_date);
        $data['guest']  = $row1->no_quest;
        $data['price']  =$row1->topay;
        $currency  = $row1->currency;
                $data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
                    $country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
                $country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
                if(!empty($country_symbol)){
                    $data['country_symbol']   = $country_symbol;
                }
                else{
                    $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
                    //print_r($this->db->last_query());exit;
                     $data['country_symbol']  = $check_default;
                }
                if(!empty($country_code)){
                    $data['currency_code']   = $country_code;
                }
                else{
                    $check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
                    //print_r($this->db->last_query());exit;
                     $data['currency_code']  = $check_default1;
                }
    }
    $created   = $row->created;

    $data['created']   = $this->facebook_style_date_time($created);
        $final[]=$data; 
        
    }
    echo json_encode($final, JSON_UNESCAPED_SLASHES);
    }
    else {
            echo '[{"Status":"No Reservation Request"}]';
    }   
    }*/ 
    public function contacts_request()
    {
        $num_guest = $this->input->get('num_guest');
        $checkin = $this->input->get('checkin');
        $userby = $this->input->get('userby');
        $userto = $this->input->get('userto');
        $list_id = $this->input->get('list_id');
        $checkout = $this->input->get('checkout');
        $comment = $this->input->get('comment');
        
        $query_list     = $this->db->get_where('list', array('id' => $list_id));
        $q        = $query_list->result();
        $row_list = $query_list->row();
        
        if(!empty($row_list->price))
        {
        $data['price']              = $row_list->price;
        }else
            {
            $data['price'] = '';    
            }
        
        $data['list_id']                = $list_id;
        
        $data['userby']                 = $userby;
        
        $data['userto']                 = $userto;
        
        
        $final_date = date($checkin);
        $start_date =  strtotime($final_date);
        
        $data['checkin']                = $start_date;
        
        $final_date1 = date($checkout);
        $end_date =  strtotime($final_date1);
        
        $data['checkout']               = $end_date;
        
        $data['no_quest']               = $num_guest;
        
        if(!empty($currency))
        {
        $data['currency']               = $currency;
        }else
            {
            $data['currency'] = ''; 
            }
        
        if(!empty($topay))
        {
        $data['topay']              = $topay;
        }else
            {
            $data['topay'] = '';    
            }
        
        $data['send_date']                  = time();
        $data['status']                 = '1';
        
        
        
        if(!empty($userby))
            {
                $id= '7';
        
                $messag   =  $this->db->where('id',$id)->get('message_type')->row()->name;  
                
                
                
                    
        $is_travelCretids    = NULL;
        $user_travel_cretids = NULL;
        
        
            $admin_email = $this->dx_auth->get_site_sadmin();
            $admin_name  = $this->dx_auth->get_site_title();
            
            $query3      = $this->Common_model->getTableData('users',array('id' => $userby));
            $rows        =  $query3->row();
                
            $username    = $rows->username;
            $user_id     = $rows->id;
            $email_id    = $rows->email;
            
            $query4      = $this->Common_model->getTableData('users',array('id' => $userto));
            $buyer_name  = $query4->row()->username;
            $buyer_email = $query4->row()->email;
            
            $query1      = $this->db->get_where('list', array('id' => $list_id));
            $q        = $query1->result();
            $row_list = $query1->row();
        //  $list['book_date']           = date('d-m-Y H:i:s');
                    
            //Actual insertion into the database
            $this->Common_model->insertData('contacts', $data);     
            $contact_id = $this->db->insert_id();
            
            //Send Message Notification
            
            $insertData = array(
                'list_id'         => $list_id,
                'reservation_id'  => $contact_id,
                'userby'          => $userby,
                'userto'          => $userto,
                'message'         => '<b>You have a new contact request from  '.ucfirst($username).'</b><br><br>'.$comment,
                
                'created'         => time(),
                'message_type'    => 7
                );
                // print_r($insertData);exit;
                //print_r($insertData);exit;
            $this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
            $message_id     = $this->db->insert_id();
            
            //$actionurl = 'Kindly accept reservation request in your inbox';
        
        $status_request = "Kindly see your inbox";  
                
   //Reservation Notification To Host
            $email_name = 'contact_request_to_guest';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($username), "{traveller_username}" => ucfirst($buyer_name), "{title}" => $row_list->title, "{traveler_email_id}" => $email_id, "{checkin}" => $checkin, "{checkout}" => $checkout, "{guest}" => $num_guest ,"{message}" => $comment);
            //Send Mail
            
            $this->Email_model->sendMail($buyer_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
            
         //Reservation Notification To Traveller
            $email_name = 'contact_request_to_host';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{host_username}" => ucfirst($username),"{link}" => $status_request, "{title}" => $row_list->title, "{traveler_email_id}" => $email_id, "{checkin}" => $checkin, "{checkout}" => $checkout, "{guest}" => $num_guest ,"{message}" => $comment);
            //Send Mail
            $this->Email_model->sendMail($email_id,$admin_email,ucfirst($admin_name),$email_name,$splVars);
            //print_r($splVars);exit;
            echo '[{"reason_message":"Your contact request sent successfully.","contact_id":'.$contact_id.'}]'; exit;
        
            }
else {
            echo '[{"reason_message":"Your contact request not sent successfully."}]'; exit;
        }
                
            
    }


function getclienttoken()
{
    $clientToken = Braintree_ClientToken::generate(array());
    echo '[{"token":"'.$clientToken.'"}]';
    ///have to send mail to admin  if 401 issue is encountered
}
    

//braintree payment

function braintree_payment()            //      1st url --> amount, userid,  driverid 
{
    
    $successcounter=0;                                    
    extract($this->input->get());
    
    
    if($nonce != "")
    {
        $braintreecustomer = Braintree_Customer::create();
        //$braintreecustomer->customer->id;
        $result = Braintree_Transaction::sale(array(
            'amount' => $price,
            'paymentMethodNonce' => $nonce,
                        
            'options' => array(
               'storeInVaultOnSuccess' => true,
               'submitForSettlement' => true
            )
        //"paymentMethodNonce" => "f492cba6-0c76-482a-83f8-0830bff60ba9",
        )); 
//'merchantAccountId' => $merchantAccountId,
        if($result->success)
        {
            $successcounter=1;  
            // echo '[{"reason_message":"Payment completed successfully.","reservation_id":'.$reservation_id.'}]'; exit;    
            // $rest_trans = $result->transaction;
//          
            // foreach($rest_trans as $trans)
            // {
                // $customer_id = $trans['customer']['id'];     
                // $payment_token  = $trans['creditCard']['token'];
            // }
            
            //echo '[{"status":"Success","customerid":'.$customer_id.',"paymenttoken":'.$payment_token.',"transactionid":'.$result->transaction->id.'}]';
            
            
            //$insert_brain_detail = $this->users_model->brain_update_details($user_id, $payment_token, $customer_id);
            //$this->mongo_db->db->users->update(array('_id' => new MongoId($userid)),array('$set'=>array("paymentnonce"=>"null1")));
            
            $data['transaction_id']         = $result->transaction->id;
            $data['list_id']                = $list_id;
            $data['userby']                 = $userby;
            $data['userto']                 = $userto;  
            $final_date = date($checkin);
            $start_date =  strtotime($final_date);
            $data['checkin']                = $start_date;
            $final_date1 = date($checkout);
            $end_date =  strtotime($final_date1);
            $data['checkout']               = $end_date;
            $data['no_quest']               = $no_quest;
            $data['currency']               = $currency;
            $data['price']                  = $price;
            $data['topay']                  = $topay;
            $data['admin_commission']       = $admin_commission;
            $data['credit_type']            = 1;
            $data['status']                 = 1;
            $data['payment_id']             = 2;
            $final_date2 = date($book_date);
            $bok_date =  strtotime($final_date2);
            $data['book_date']              = time();
            $data['guest_topay']            = $guest_topay;
            $data['policy']         = 1;
            $is_travelCretids    = NULL;
            $user_travel_cretids = NULL;
            
                $admin_email = $this->dx_auth->get_site_sadmin();
                $admin_name  = $this->dx_auth->get_site_title();
                
                $query3      = $this->Common_model->getTableData('users',array('id' => $userby));
                $rows        =  $query3->row();
                    
                $username    = $rows->username;
                $user_id     = $rows->id;
                $email_id    = $rows->email;
                
                $query4      = $this->Common_model->getTableData('users',array('id' => $userto));
                $buyer_name  = $query4->row()->username;
                $buyer_email = $query4->row()->email;
                
                $query1      = $this->db->get_where('list', array('id' => $list_id));
                $q        = $query1->result();
                $row_list = $query1->row();
        
                if(!empty($list_id))
                {       
                                    //sent mail to administrator
                                $email_name = 'tc_book_to_admin';
                                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $checkin, "{checkout}" => $checkout, "{market_price}" => $user_travel_cretids+$price, "{payed_amount}" => $price,"{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                                //Send Mail
                                $this->Email_model->sendMail($admin_email,$email_id,ucfirst($username),$email_name,$splVars);
                                
    
                                    //sent mail to buyer
                                $email_name = 'tc_book_to_host';
                                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $checkin, "{checkout}" => $checkout, "{market_price}" => $price);
                                //Send Mail
                                $this->Email_model->sendMail($buyer_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
    
            
                
            //  $list['book_date']           = date('d-m-Y H:i:s');
                        
                //Actual insertion into the database
                $this->Common_model->insertData('reservation', $data);      
                $reservation_id = $this->db->insert_id();
                
                //Send Message Notification
                
                $insertData = array(
                    'list_id'         => $list_id,
                    'reservation_id'  => $reservation_id,
                    'userby'          => $userby,
                    'userto'          => $userto,
                    'message'         => 'You have a new reservation request from '.ucfirst($username),
                    
                    'created'         => time(),
                    'message_type'    => 1
                    );
                      
                    //print_r($insertData);exit;
                $this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
                $message_id     = $this->db->insert_id();
                
                //$actionurl = 'Kindly accept reservation request in your inbox';
                    
       //Reservation Notification To Host
                $email_name = 'host_reservation_notification';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $checkin, "{checkout}" => $checkout, "{market_price}" => $price);
                //Send Mail
                $this->Email_model->sendMail($buyer_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
                
             //Reservation Notification To Traveller
                $email_name = 'traveller_reservation_notification';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username));
                //Send Mail
                $this->Email_model->sendMail($email_id,$admin_email,ucfirst($admin_name),$email_name,$splVars);
                
                    //Reservation Notification To Administrator
                    $email_name = 'admin_reservation_notification';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $user_travel_cretids+$list['price'], "{payed_amount}" => $list['price'],"{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                    //Send Mail
                    $this->Email_model->sendMail($admin_email,$email_id,ucfirst($username),$email_name,$splVars);
                
                echo '[{"status":"Success","reservation_id":'.$reservation_id.'}]'; exit;
            
                }
        
        }
        else
        {
            echo '[{"status":"Failure"}]'; exit;
        }
//  $userid = $user_id;
    //$nonceresult = $this->mongo_db->db->users->findOne(array('_id' => new MongoId($userid)));
    //$nonceresult=$this->db->get('reservation')->row()->paymentnonce;
    
    }
    else 
    {
        echo '[{"status":"Failure"}]'; exit;
    }
    // else if($nonceresult['paymentnonce'] == "null1") 
    // {
        // $result = Braintree_Transaction::sale(array(
            // 'paymentMethodToken' => $nonceresult['payment_token'],
            // 'amount' => $amount,
            // 'options' => array(
               // 'storeInVaultOnSuccess' => true,
               // 'submitForSettlement' => true
            // )
//          
        // ));
//      
        // if($result->success)
        // {
            // $successcounter=1;       
            // echo '[{"status":"Success"}]';
//          
        // }
//      
    // }
    
// 
// if ($successcounter == 1) 
// {
    // // $resultuser=$this->users_model->display_details($userid);
// //   
    // // if($resultuser->hasNext())
        // // {
            // // foreach($resultuser as $document)
                 // // {
                 // // $rider_name=$document['firstname'].$document['lastname'];
                        // // $tripid = $document['last_tripid'];
                        // // $rider_num=$document['mobile_no'];
                 // // }
        // // }
//      
//      
        // if(!empty($reservation_id))
        // {
//          
            // //$resulttrip=$this->users_model->update_paymentid($tripid,$result->transaction->id);
            // $resu1=$this->users_model->get_paymentid($tripid);
            // //echo '[{"status":"Success"}]';
//          
            // //send pay notification to driver
                // $resultdriver=$this->drivers_model->display_details($driverid);
                    // if($resultdriver->hasNext())
                    // {    
                        // foreach($resultdriver as $document)
                        // {
                        // $registatoin_ids = $document['regid'];
                        // }
                    // }
//              
                // $message = "paid";
                // if (isset($registatoin_ids)&&isset($message)) 
                // {
                    // if($registatoin_ids == "null")
                    // {
                        // $this->pushnotification($driverid,$message);
                    // }
                    // else 
                    // {
                        // $registatoin_ids = array($registatoin_ids);
                        // $message = array("request" => $message);
                        // $this->drivers_model->send_push_notification($registatoin_ids, $message);
                    // }
                // }
            // //send msg
        // }
        // else 
        // {
                        // echo '[{"status":"Fail to send push notification"}]';
//                  
        // }
    // //print_r("success!: " . $result->transaction->id);
//     
//  
// } 

}
    




public  function contact_accept()
    {
        $contact_id                   = $this->input->get('contact_id');
        $message                      = $this->input->get('comment');   
        //Update the status,price
            
            
            if(!empty($contact_id))
            {
            $updateKey                = array('id' => $contact_id);
            $updateData               = array();
            $updateData['status']    = 3;
            $updateData['price']     = $this->input->get('price');
            $this->Contacts_model->update_contact($updateKey,$updateData);
        
            $currency_symbol = $this->Common_model->getTableData('currency',array('currency_code'=>get_currency_code()))->row()->currency_symbol;
            
            $price = $currency_symbol.$this->input->get('price');
            
        //Email the confirmation link to the traveller  
        $result         = $this->Common_model->getTableData('contacts',array('id' => $contact_id))->row();
        $traveller_id   = $result->userby;
        
        $guest  = $result->no_quest;
        $start_date   = $result->checkin;
        $checkin  = gmdate('d-m-y',$start_date);
        $end_date =  $result->checkout;
        $checkout  = gmdate('d-m-y',$end_date);
        
        
        
        
        $host_id    = $result->userto; 
        $key            = $result->contact_key; 
        $list_id        = $result->list_id;
        $title          = $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
        $host_email     = $this->Common_model->getTableData('users',array('id' => $host_id))->row()->email;
        $traveller_email= $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->email;
        
        //send message to traveller
        $host_id        = $result->userto;
        $travellername  = $this->Common_model->getTableData('users',array('id' => $traveller_id))->row()->username;
        $hostname       = $this->Common_model->getTableData('users',array('id' => $host_id))->row()->username;
        $list_title     = $this->Common_model->getTableData('list',array('id' => $list_id))->row()->title;
    
            $insertData = array(
                'list_id'         => $list_id,
                'contact_id'      => $contact_id,
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
        
        $link = 'I accepted your contact request';
            
        $email_name = 'request_granted_to_host';
        $splVars    = array("{traveller_username}"=> $travellername,"{price}"=>$price,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout,"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
        $this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);       
        
        $email_name = 'request_granted_to_guest';
        $splVars    = array($link,"{host_email}"=>$host_email,"{price}"=>$price,"{guest_email}"=>$traveller_email,"{traveller_username}"=> $travellername,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
        $this->Email_model->sendMail($traveller_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
        
        if($host_email != $admin_email && $traveller_email != $admin_email)
        {
        $email_name = 'request_granted_to_admin';
        $splVars    = array("{traveller_username}"=> $travellername,"{price}"=>$price,"{host_email}"=>$host_email,"{guest_email}"=>$traveller_email,"{checkin}"=>$checkin,"{checkout}" => $checkout, "{guest}"=>$guest,"{message}"=>$message, "{site_name}" => $this->dx_auth->get_site_title(), "{host_username}" => ucfirst($hostname), "{title}" => $list_title);
        $this->Email_model->sendMail($admin_email,$host_email,ucfirst($admin_name),$email_name,$splVars);
        }
        echo '[{"reason_message":"Your contact response to successfully.","contact_id":'.$contact_id.'}]'; exit;
        
            }
else {
            echo '[{"reason_message":"Your contact response not sent successfully."}]'; exit;
        }
            

                
    }
        public function host_reservation_inbox()
    {
        $user_id=$this->input->get('userto');
        $common_currency = $this->input->get('common_currency');
        
        if($this->input->get('start'))
        $this->db->limit(10,$this->input->get('start')-1);
        else
        $this->db->limit(10,0);
        
        //$query=$this->db->where('userto',$user_id)->where('message_type', "1")->order_by("reservation_id", "desc")->get('messages');
        
    //if($query->num_rows()!=0)
    if($result = $this->db->from('messages')->where('userto',$user_id)->where('message_type', "1")->order_by("reservation_id", "desc")->get()->result())
    {
        //foreach($query->result() as $row)
        foreach($result as $row)
    {
        $data['id'] = $row->id;
        $data['room_id']=$row->list_id;
        $data['reservation_id']   = $row->reservation_id;
        $data['userby']=$row->userby;
        $data['userbyname'] = $this->db->where('id', $data['userby'])->get('users')->row()->username;
        $data['userto']   = $row->userto;
        $data['usertoname'] = $this->db->where('id', $data['userto'])->get('users')->row()->username;
        $data['message']   = $row->message;
        $data['isread'] = $row->is_read;
        $created   = $row->created;
        $data['created']   = $this->facebook_style_date_time($created);
        
        $query1=$this->db->where('id',$data['reservation_id'])->get('reservation');
        $start_date   = $query1->row()->checkin;
        
        $data['checkin']  = date('M d, Y',$start_date);
        $end_date   = $query1->row()->checkout;
        $data['checkout']  = date('M d, Y',$end_date);
        $data['guest']  = $query1->row()->no_quest;
        $data['price']  =$query1->row()->topay;
        $currency  = $query1->row()->currency;
        
                $data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
                $country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
                
                
                if(!empty($country_code))
                {
                    $data['currency_code']   = $country_code;
                    
                    $currencycode = $country_code;
                }
                else{
                    $check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
                    //print_r($this->db->last_query());exit;
                     $data['currency_code']  = $check_default1;
                    
                    $currencycode = $check_default1;
                }
                
                // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
//             
            // $obj = json_decode($json);
//             
            // foreach($obj->results as $results)
            // {
                // $value = $results->val;
                // //echo $value;
                // $data['common_currency_code'] = $common_currency;
                // $data['common_currency_value'] = $value * $data['price'];
            // }
            
                         
            $data['common_currency_code'] = $common_currency;
            $data['common_currency_value'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $data['price'];
            
            
            
            

                $status  =$query1->row()->status;
                $query_status =$this->db->where('id',$status)->get('reservation_status')->row()->name;
                $data['status']  =$query_status;
                $data['Status']  = 'Your Reservation Request';
        $query2=$this->db->where('id',$data['room_id'])->get('list');
    
        $data['title']  = $query2->row()->title;
        
        $query3=$this->db->where('id',$data['userby'])->get('users');
        $data['username']  = $query3->row()->username;
        $email =$query3->row()->email;
        $fbid = $query3->row()->fb_id;
        
        $query5 = $this->db->where('email',$email)->get('profile_picture');
        
        if(!empty($fb_id) && $fb_id != 0)
        {
            $data['profile_pic']  = $query5->row()->src;
        }
        else
        {
            $data['profile_pic']= $this->Gallery->profilepic($row->userby, 2);
            // Ragul $data['profile_pic']  = $query5->row()->src;
        }
        
        $final[]=$data;
    }
        echo json_encode($final, JSON_UNESCAPED_SLASHES);
        
    }
    else
    {
            echo '[{"Status":"No Reservation Request"}]';
    }   
        
    }
    
    public function guest_inbox()
    {
        $user_id=$this->input->get('userto');
        
        //$query=$this->db->where('userto',$user_id)->where('message_type', "3")->order_by("reservation_id", "desc")->get('messages');
        
        //$this->db->where('userto',$user_id)->where('message_type', "3")->order_by("reservation_id", "desc")->get('messages');
        
        if($this->input->get('start'))
        $this->db->limit(10,$this->input->get('start')-1);
        else
        $this->db->limit(10,0);
		 $where = "(message_type='1' or message_type='3'or message_type='4' or message_type='5') "; 
      
        if($result = $this->db->from('messages')->where('userto',$user_id)->where($where)->order_by("created", "desc")->get()->result())
		{
              
      
        //if($query->num_rows()!=0)
        /*
        if($result = $this->db->from('messages')->where('userto',$user_id)->where('message_type', "3")->order_by("reservation_id", "desc")->get()->result())
                {*/
        
            //foreach($query->result() as $row)
            foreach($result as $row)
            {
                $data['id'] = $row->id;
                $data['room_id']=$row->list_id;
                $data['reservation_id']   = $row->reservation_id;
                $data['userby']=$row->userby;
                $data['userbyname'] = $this->db->where('id', $data['userby'])->get('users')->row()->username;
                $data['userto']   = $row->userto;
                $data['usertoname'] = $this->db->where('id', $data['userto'])->get('users')->row()->username;
                $data['message']   = $row->message;
                $data['isread'] = $row->is_read;
                $created   = $row->created;
                $data['created']   = $this->facebook_style_date_time($created);
                
                $query1=$this->db->where('id',$data['reservation_id'])->get('reservation');
                
                $start_date   = $query1->row()->checkin;
                
                $data['checkin']  = date('F d, Y',$start_date);
                $end_date   = $query1->row()->checkout;
                $data['checkout']  = date('F d, Y',$end_date);
                $data['guest']  = $query1->row()->no_quest;
                $data['price']  =$query1->row()->topay;
                $currency  = $query1->row()->currency;
                $data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
                //$country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
                $country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
                //$data['country_symbol']   = $country_symbol;
                $data['currency_code']   = $country_code;
                $status  =$query1->row()->status;
                $query_status =$this->db->where('id',$status)->get('reservation_status')->row()->name;
                $data['status']  =$query_status;
                $data['Status']  = 'Your Reservation Request';
                
                $query2=$this->db->where('id',$data['room_id'])->get('list');
                
                $data['title']  = $query2->row()->title;
                
                $query3=$this->db->where('id',$data['userby'])->get('users');
                $data['username']  = $query3->row()->username;
                $email =$query3->row()->email;
                $fbid = $query3->row()->fb_id;
                
                $query5 = $this->db->where('email',$email)->get('profile_picture');
                
                if(!empty($fb_id) && $fb_id != 0)
                {
                    $data['profile_pic']  = $query5->row()->src;
                }
                else
                {
                    //srikrishnan
                //$data['profile_pic']= $this->Gallery->profilepic($row->user_id, 2);
                $profile=$query5->row()->src;
                    $data['profile_pic']  = "$profile";
                }
                
                //print_r($this->db->last_query());
                //exit();
                
                $final[]=$data;
            }
            echo json_encode($final, JSON_UNESCAPED_SLASHES);
            
        }
        else
        {
            echo '[{"Status":"No Reservation Request"}]';
        }
        
    }
    
    public function check_is_read()
    {
        $id = $this->input->get('id');
        $roomid = $this->input->get('room_id');
        $userby = $this->input->get('user_by');
        $userto = $this->input->get('user_to');
        $data['is_read'] = $this->input->get('is_read');
        
        $this->db->where('id', $id)->where('list_id', $roomid)->where('userby', $userby)->where('userto', $userto)->update('messages', $data);
        
        echo '[{"Status":"Message read successfully"}]';
        
    }
public function host_reservation_response()
{
        
        $reservation_id = $this->input->get('reservation_id');
        $comment = $this->input->get('comment');
        $status=$this->input->get('status');
        
        if ($status == "completed") {
            
     $admin_email                       = $this->dx_auth->get_site_sadmin();
     $admin_name                        = $this->dx_auth->get_site_title();
    
        $conditions                 = array('reservation.id' => $reservation_id);
        $row                        = $this->Trips_model->get_reservation($conditions)->row();
        $transaction_id = $row->transaction_id;
        $checkin = $row->checkin;
        $checkout = $row->checkout;
        $price =  $row->price;
        $currency =  $row->currency;
        
        //print_r($currency);exit;
        /*$query1                            = $this->Users_model->get_user_by_id($row->userby);
        $traveler_name              = $query1->row()->username;
        $traveler_email             = $query1->row()->email;*/
        
        $query1 = $this->db->where('id',$row->userby)->get('users');
        $traveler_name              = $query1->row()->username;
        $traveler_email             = $query1->row()->email;
        
        //$query2                            = $this->Users_model->get_user_by_id($row->userto);
        $query2 = $this->db->where('id',$row->userto)->get('users');
        $host_name                              = $query2->row()->username;
        $host_email                             = $query2->row()->email;
        
        $list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
        
        $traveler          = $this->Common_model->getTableData('profiles', array('id' => $row->userby))->row();
        $host               = $this->Common_model->getTableData('profiles', array('id' => $row->userto))->row();
        
        $data_acceptpay['reservation_id'] = $reservation_id;
        $data_acceptpay['amount'] = $price;
        $data_acceptpay['currency'] = $currency;
        $data_acceptpay['created'] = time();
        $data_acceptpay['transaction_id'] = $transaction_id;
        
            //print_r($data_acceptpay['transaction_id']);exit;  
        $this->db->insert('accept_pay',$data_acceptpay);
        
        //Traveller Info
        if(!empty($traveler ))
        {
        $FnameT                                             =   $traveler->Fname;
        $LnameT                                             = $traveler->Lname;
        $liveT                                                  = $traveler->live;
        $phnumT                                             = $traveler->phnum;
        }
        else
        {
        $FnameT                                             =   '';
        $LnameT                                             = '';
        $liveT                                                  = '';
        $phnumT                                             = '';
        }
        
        //Host Info
        if(!empty($host ))
        {
        $FnameH                                             =   $host->Fname;
        $LnameH                                             = $host->Lname;
        $liveH                                                  = $host->live;
        $phnumH                                             = $host->phnum;
        }
        else
        {
        $FnameH                                             =   '';
        $LnameH                                             = '';
        $liveH                                                  = '';
        $phnumH                                             = '';
        }
    
               $list_data = $this->db->where('id',$row->list_id)->get('list')->row();
            
             //Send Message Notification To Traveler
            $insertData = array(
                'list_id'         => $row->list_id,
                'reservation_id'  => $reservation_id,
                'userby'          => $row->userto,
                'userto'          => $row->userby,
                'message'         => "Congratulation, Your reservation request is accepted by $host_name for $list_title",
                'created'         => local_to_gmt(),
                'message_type'    => 3
                );
                
            $this->Message_model->sentMessage($insertData, 1);
            
            $updateData['is_respond'] = 1;
            $updateKey['reservation_id'] = $reservation_id;
            $updateKey['userto'] = $row->userto;
            
            $this->Message_model->updateMessage($updateKey,$updateData);

   
            $updateKey                = array('id' => $reservation_id);
            $updateData               = array();
            $updateData['status ']    = 3;
            $updateData['is_payed']   = 0;    
            $this->Trips_model->update_reservation($updateKey,$updateData);
            
            if($list_data->optional_address == '')
            {
                $optional_address = ' -';
            }
            else {
                $optional_address = $list_data->address;
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
        
        echo '[{"Status":"Your reservation request accept process successfully completed."}]';
        }
else 
{
    $admin_email                        = $this->dx_auth->get_site_sadmin();
     $admin_name                        = $this->dx_auth->get_site_title();
    
        $conditions                 = array('reservation.id' => $reservation_id);
        $row                        = $this->Trips_model->get_reservation($conditions)->row();
        
        $query1                              = $this->Users_model->get_user_by_id($row->userby);
        $traveler_name              = $query1->row()->username;
        $traveler_email             = $query1->row()->email;
        
        $query2                              = $this->Users_model->get_user_by_id($row->userto);
        $host_name                              = $query2->row()->username;
        $host_email                             = $query2->row()->email;
        
        $list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
    
            //Send Message Notification To Traveller
            $insertData = array(
                'list_id'         => $row->list_id,
                'reservation_id'  => $reservation_id,
                'userby'          => $row->userto,
                'userto'          => $row->userby,
                'message'         => "Sorry, Your reservation request is rejected by $host_name for $list_title.",
                'created'         => local_to_gmt(),
                'message_type'    => 3
                );
                
            $this->Message_model->sentMessage($insertData, 1);
            $message_id     = $this->db->insert_id();
            
            $updateData['is_respond'] = 1;
            $updateKey['reservation_id'] = $reservation_id;
            $updateKey['userto'] = $row->userto;
            
            $this->Message_model->updateMessage($updateKey,$updateData);
            
            $updateKey                = array('id' => $reservation_id);
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
    
    echo '[{"Status":"Your reservation request accept process not completed."}]';
}
}
        public function bookit_page1()   
    {
        $user_id=$this->input->get('user_id');
        $room_id=$this->input->get('room_id');
        
        $query=$this->db->where('id',$room_id)->get('list');
    if($query->num_rows()!=0)
    {
    foreach($query->result() as $row)
    {
        $data['id']=$row->id;
        $data['user_id']=$row->user_id;
        $data['title']   = $row->title;
        $data['room_type']   = $row->room_type;
        $data['bedrooms']   = $row->bedrooms;
        $data['bathrooms']   = $row->bathrooms;
        $data['address']   = $row->address;
        $data['city']   = $row->city;
        $data['country']   = $row->country;
        $data['guest']   = $row->capacity;
        $data['price']   = $row->price;
        $ID = 2;
        $admin_fees = $this->db->where('id',$ID)->get('paymode');
        $fix_amount= $admin_fees->row()->fixed_amount;
        $data['admin_fees']   = $fix_amount;
        
                //$data['currency']=$row->currency;
                //$currencyvalue =$this->db->where('currency_code',$data['currency'])->get('currency')->row('currency_symbol');
                //$country_symbol =$this->db->where('currency_code',$data['currency'])->get('currency')->row('country_symbol');
                $image =$this->db->where('list_id',$data['id'])->get('list_photo');
                if(!empty($image->row()->image)){
                    $data['image']   = $image->row()->image;
                }
                else{
                     $data['image']  = '';
                }
                $currency  = $row->currency;
                $country_symbol=$this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
                $country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
                if(!empty($country_symbol)){
                    $data['country_symbol']   = $country_symbol;
                }
                else{
                    $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
                    //print_r($this->db->last_query());exit;
                     $data['country_symbol']  = $check_default;
                }
                if(!empty($country_code)){
                    $data['currency_code']   = $country_code;
                }
                else{
                    $check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
                    //print_r($this->db->last_query());exit;
                     $data['currency_code']  = $check_default1;
                }
                $final[]=$data; 
                
        
    }
    
    echo json_encode($final);
    }
    else {
            echo '[{"Status":"No Listings Found"}]';
    }
    
}
    
    
    public function bookit_page()
    {
        $user_id=$this->input->get('user_id');
        $room_id=$this->input->get('room_id');
        $common_currency = $this->input->get('common_currency');
        
        $query=$this->db->where('id',$room_id)->get('list');
    if($query->num_rows()!=0)
    {
    foreach($query->result() as $row)
    {
        $data['id']=$row->id;
        $data['user_id']=$row->user_id;
        $data['title']   = $row->title;
        $data['room_type']   = $row->room_type;
        $data['bedrooms']   = $row->bedrooms;
        $data['bathrooms']   = $row->bathrooms;
        $data['address']   = $row->address;
        $data['city']   = $row->city;
        $data['country']   = $row->country;
        $data['guest']   = $row->capacity;
        $data['price']   = $row->price;
        
        $other_fees = $this->db->where('id', $row->id)->get('price');
        $cleaning_fees = $other_fees->row()->cleaning;
        
        $ID = 2;
        $admin_fees = $this->db->where('id',$ID)->get('paymode');
        
        $fix_amount= $admin_fees->row()->fixed_amount;
        $admin_currency = $admin_fees->row()->currency;
        $data['admin_fees']   = $fix_amount;
                $currency  = $row->currency;
                $data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
                $currency_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
                $country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
                
                if(!empty($currency_code)){
                    $data['currency'] = $currency_code;
                    
                    $currencycode = $currency_code;
                }
                else {
                    $check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
                    //print_r($this->db->last_query());exit;
                     $data['currency']  = $check_default1;
                     $currencycode = $check_default1;
                }
                
                
                /*if(!empty($country_symbol)){
                    $data['country_symbol']   = $country_symbol;
                }
                else{
                    $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
                    //print_r($this->db->last_query());exit;
                     $data['country_symbol']  = $check_default;
                }*/
        
        // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
//             
            // $obj = json_decode($json);
//             
            // foreach($obj->results as $results)
            // {
                // $value = $results->val;
                // //echo $value;
                // $data['common_currency_code'] = $common_currency;
                // $data['common_price'] = $value * $row->price;
                // $data['cleaning_fees'] = $value * $cleaning_fees;
                // //$data['common_admin_fees'] = $value * $fix_amount;
            // }
            
            
               $data['common_currency_code'] = $common_currency;
            $data['common_price'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $row->price;
            $data['cleaning_fees'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $cleaning_fees;
            
            // $json1 = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$admin_currency."_".$common_currency);
//             
            // $obj1 = json_decode($json1);
//             
            // foreach($obj1->results as $results)
            // {
                // $value1 = $results->val;
                // $data['common_admin_fees'] = $value1 * $fix_amount;
            // }
            
            $data['common_admin_fees'] = (get_currency_value_lys($admin_currency,$common_currency,1)) * $fix_amount;
            
        
        $image =$this->db->where('list_id',$data['id'])->get('list_photo');
                if(!empty($image->row()->image)){
                    $data['resize']   = $image->row()->resize;
                }
                else{
                     $data['resize']  = '';
                }
                
                $final[]=$data; 
                
        
    }
    
    echo json_encode($final);
    }
    else {
            echo '[{"Status":"No Listings Found"}]';
    }
    
}
    
    public function bookit_date()
{
    $room_id = $this->input->get('room_id');
    $query   = $this->db->where('list_id',$room_id)->get('reservation');
//  print_r($query);exit;
    if($query->num_rows() != 0)
    {
        foreach($query->result() as $row)
        {
          
        $data['room_id']   = $row->list_id;
        $start_date   = $row->checkin;
        $data['checkin']  = gmdate('d-m-Y',$start_date);
        $end_date =  $row->checkout;
        $data['checkout']  = gmdate('d-m-Y',$end_date);
        $data['status']  = $row->status;
        $list_date[]   = $data;
    
        }
        echo json_encode($list_date);
    }
        else {
            echo '[{"status":"This room not available"}]';
    }
}




    public function guest_reservation_details()
    {
        $common_currency = $this->input->get('common_currency');
        $user_id=$this->input->get('userby');
        $user_to=$this->input->get('userto');
        $reservation_id =$this->input->get('reservation_id');
        //print_r($reservation_id);exit;
    //$query11=$this->db->where('id',$reservation_id)->get('reservation');
    //$query3 =$this->db->where('userby',$user_id)->get('reservation');
    $query3 = $this->db->query('SELECT * FROM `messages` WHERE (`userby` = '.$user_id.'  AND `reservation_id` = '.$reservation_id.' AND `userto` = '.$user_to.' )');
    //print_r($this->db->last_query());exit;    
    
    if($query3->num_rows()!=0)
    {
        //$user_by = $query3->row()->userby;
        $query5 =$this->db->where('id',$reservation_id)->get('reservation');
        $query1=$this->db->where('id',$user_id)->get('users');
        $data['username']  = $query1->row()->username;
        $email =$query1->row()->email;;
        
        /*$query4=$this->db->where('email',$email)->get('profile_picture');
        $empty_image = base_url().'images/no_avatar.jpg';
        if($query4->num_rows()!=0)
    {

        $data['src']  = $query4->row()->src;
    }
        else {
            $data['src']  = $empty_image;
        }
        
        $data['profile_pic'] = $this->Gallery->profilepic($user_id, 2);*/
        
            $profileimagequery1 = $this->db->where('id',$row->user_id)->get('users');
            $fbid = $profileimagequery1->row()->fb_id;
            
            $profileimagequery2 = $this->db->where('email',$email)->get('profile_picture');
            
            if($fbid!=0)
            {
                $data['profile_pic']  = $profileimagequery2->row()->src;
            }
            else
            {
                $data['profile_pic']= $this->Gallery->profilepic($user_id, 2);
            }
        
        $data['room_id'] = $query3->row()->list_id;
        
        $query2=$this->db->where('id',$data['room_id'])->get('list');
        $data['user_id'] = $query2->row()->user_id;
        $data['title']  = $query2->row()->title;
        $data['address']  = $query2->row()->address;
        $data['room_type']  = $query2->row()->room_type;
        $data['country']  = $query2->row()->country;
        $data['city']  = $query2->row()->city;
        $start_date   = $query5->row()->checkin;
        $data['checkin']  = gmdate('F d, Y',$start_date);
        $end_date   = $query5->row()->checkout;
        $data['checkout']  = gmdate('F d, Y',$end_date);
        $data['guest']  = $query5->row()->no_quest;
        $data['price']  =$query5->row()->topay;
        $currency  = $query5->row()->currency;
        
        $status  =$query5->row()->status;
        $query_status =$this->db->where('id',$status)->get('reservation_status')->row()->name;
        $data['status']  =$query_status;
        $data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
        $country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
        
        $country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
        $data['country_symbol']   = $country_symbol;

        $data['currency_code']   = $country_code;
        $currencycode = $country_code;
        
        // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
//             
            // $obj = json_decode($json);
//             
            // foreach($obj->results as $results)
            // {
                // $value = $results->val;
                // //echo $value;
                // $data['common_currency_code'] = $common_currency;
                // $data['common_currency_value'] = $value * $query5->row()->topay;
            // }
            
                 $data['common_currency_code'] = $common_currency;
            $data['common_currency_value'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $query5->row()->topay;
            
            
            

        $final[]=$data; 
        
    echo json_encode($final, JSON_UNESCAPED_SLASHES);
    
    }
    else {
            echo '[{"Status":"No Reservation Request"}]';
    }   
        
    }
    public function host_reservation_inbox1()
    {
        $user_id=$this->input->get('userto');
        
        $query=$this->db->where('userto',$user_id)->order_by("reservation_id", "desc")->get('messages');
        
    if($query->num_rows()!=0)
    {
        foreach($query->result() as $row)
    {
        $data['room_id']=$row->list_id;
        $data['reservation_id']   = $row->reservation_id;
        $data['userby']=$row->userby;
        $data['userto']   = $row->userto;
        $data['message']   = $row->message;
        $created   = $row->created;
        $data['created']   = $this->facebook_style_date_time($created);
        
        $query1=$this->db->where('id',$data['reservation_id'])->get('reservation');
        $start_date   = $query1->row()->checkin;
        
        $data['checkin']  = date('F d, Y',$start_date);
        $end_date   = $query1->row()->checkout;
        $data['checkout']  = date('F d, Y',$end_date);
        $data['guest']  = $query1->row()->no_quest;
        $data['price']  =$query1->row()->topay;
        $currency  = $query1->row()->currency;
                $data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
                $country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
                $country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
                $data['country_symbol']   = $country_symbol;
                $data['currency_code']   = $country_code;
                $status  =$query1->row()->status;
                $query_status =$this->db->where('id',$status)->get('reservation_status')->row()->name;
                $data['status']  =$query_status;
                $data['Status']  = 'Your Reservation Request';
        $query2=$this->db->where('id',$data['room_id'])->get('list');
    
        $data['title']  = $query2->row()->title;
        
        $query3=$this->db->where('id',$data['userby'])->get('users');
        $data['username']  = $query3->row()->username;
        $email =$query3->row()->email;
        $query4=$this->db->where('email',$email)->get('profile_picture');
        $empty_image = base_url().'images/no_avatar.jpg';
        if($query4->num_rows()!=0)
        {   
        $data['profile_pic']  = $query4->row()->src;
        }
        else
        {
        $data['profile_pic']= $empty_image;
        }
        $final[]=$data; 
    }
        echo json_encode($final, JSON_UNESCAPED_SLASHES);
        
    }
    else 
    {
            echo '[{"Status":"No Reservation Request"}]';
    }   

    }
function cancellation_policy1()
    {
        $cancel = $this->input->get('policy');
        $id='1';
        
        $cancellation_policy = $this->db->where('id',$id)->from('cancellation_policy')->get();
        echo json_encode($cancellation_policy->result());
        
    }
    public function guest_reservation_request()
    {
        
        extract($this->input->get());
            //date_default_timezone_set("Asia/Kolkata"); 
        
        $data['transaction_id']         = $transaction_id;
        
        $data['list_id']                = $list_id;
        
        $data['userby']                 = $userby;
        
        $data['userto']                 = $userto;
        
        
        $final_date = date($checkin);
        $start_date =  strtotime($final_date);
        
        $data['checkin']                = $start_date;
        
        $final_date1 = date($checkout);
        $end_date =  strtotime($final_date1);
        
        $data['checkout']               = $end_date;
        
        $data['no_quest']               = $no_quest;
        
        $data['currency']               = $currency;
        
        $data['price']                  = $price;
        
        $data['topay']                  = $topay;
        
        $data['admin_commission']       = $admin_commission;
        
        $data['credit_type']            = 1;
        
        $data['status']                 = 1;
        
        $data['payment_id']             = 2;
        
        $final_date2 = date($book_date);
        $bok_date =  strtotime($final_date2);
        
        $data['book_date']              = time();
        
        //$data['guest_topay']              = $guest_topay;

        $data['policy']         = 1;
        
        
        $is_travelCretids    = NULL;
        $user_travel_cretids = NULL;
        
        
            $admin_email = $this->dx_auth->get_site_sadmin();
            $admin_name  = $this->dx_auth->get_site_title();
            
            $query3      = $this->Common_model->getTableData('users',array('id' => $userby));
            $rows        =  $query3->row();
                
            $username    = $rows->username;
            $user_id     = $rows->id;
            $email_id    = $rows->email;
            
            $query4      = $this->Common_model->getTableData('users',array('id' => $userto));
            $buyer_name  = $query4->row()->username;
            $buyer_email = $query4->row()->email;
            
            $query1      = $this->db->get_where('list', array('id' => $list_id));
            $q        = $query1->result();
            $row_list = $query1->row();
    
        if(!empty($list_id))
            {       
                                //sent mail to administrator
                            $email_name = 'tc_book_to_admin';
                            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $checkin, "{checkout}" => $checkout, "{market_price}" => $user_travel_cretids+$price, "{payed_amount}" => $price,"{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                            //Send Mail
                            $this->Email_model->sendMail($admin_email,$email_id,ucfirst($username),$email_name,$splVars);
                            

                                //sent mail to buyer
                            $email_name = 'tc_book_to_host';
                            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $checkin, "{checkout}" => $checkout, "{market_price}" => $price);
                            //Send Mail
                            $this->Email_model->sendMail($buyer_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);

        
            
        //  $list['book_date']           = date('d-m-Y H:i:s');
                    
            //Actual insertion into the database
            $this->Common_model->insertData('reservation', $data);      
            $reservation_id = $this->db->insert_id();
            
            //Send Message Notification
            
            $insertData = array(
                'list_id'         => $list_id,
                'reservation_id'  => $reservation_id,
                'userby'          => $userby,
                'userto'          => $userto,
                'message'         => 'You have a new reservation request from '.ucfirst($username),
                
                'created'         => time(),
                'message_type'    => 1
                );
                  
                //print_r($insertData);exit;
            $this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
            $message_id     = $this->db->insert_id();
            
            //$actionurl = 'Kindly accept reservation request in your inbox';
                
   //Reservation Notification To Host
            $email_name = 'host_reservation_notification';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $checkin, "{checkout}" => $checkout, "{market_price}" => $price);
            //Send Mail
            $this->Email_model->sendMail($buyer_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
            
         //Reservation Notification To Traveller
            $email_name = 'traveller_reservation_notification';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username));
            //Send Mail
            $this->Email_model->sendMail($email_id,$admin_email,ucfirst($admin_name),$email_name,$splVars);
            
                //Reservation Notification To Administrator
                $email_name = 'admin_reservation_notification';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $user_travel_cretids+$list['price'], "{payed_amount}" => $list['price'],"{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                //Send Mail
                $this->Email_model->sendMail($admin_email,$email_id,ucfirst($username),$email_name,$splVars);
                
                
                 if($this->session->userdata('contact_key') != '')
            {
            $updateKey                = array('contact_key' => $this->session->userdata('contact_key'));
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
                
                if(empty($group_id)) echo $countJ = 0; else $countJ = $group_id;
                
                $insertData_cal['list_id']      = $list_id;
                $insertData_cal['group_id']     = $countJ + 1;
                $insertData_cal['availability'] = 'Booked';
                $insertData_cal['booked_using'] = 'Other';
                
                    $checkin  = date('m/d/Y', $list['checkin']);
                    $checkout = date('m/d/Y', $list['checkout']);
                    
                    $days     = getDaysInBetween($checkin, $checkout);
        
                    $count = count($days);
                    $i = 1;
                    foreach ($days as $val)
                    {
                        if($count == 1)
                        {
                            $insertData_cal['style'] = 'single';
                        }
                        else if($count > 1)
                        {
                            if($i == 1)
                            {
                            $insertData_cal['style'] = 'left';
                            }
                            else if($count == $i)
                            {
                            $insertData_cal['notes'] = '';
                            $insertData_cal['style'] = 'right';
                            }
                            else
                            {
                            $insertData_cal['notes'] = '';
                            $insertData_cal['style'] = 'both';
                            }
                        }    
                    $insertData_cal['booked_days'] = $val;
                    $this->Trips_model->insert_calendar($insertData_cal);                
                    $i++;
                    }
            }
            else    
               $list['status'] = 1;
            
            echo '[{"reason_message":"Payment completed successfully.","reservation_id":'.$reservation_id.'}]'; exit;
        
            }
        else {
            echo '[{"reason_message":"Payment not completed successfully."}]'; exit;
        }
        
    }
function facebook_style_date_time($timestamp){
                $difference = time() - $timestamp;
               $periods = array(" seconds ago", " minutes ago", " hours ago", " days ago", " weeks ago", " months ago", " years ago");
                $lengths = array("60","60","24","7","4.35","12","10");
                
                if ($difference > 0) { // this was in the past time
                } else { // this was in the future time
                    $difference = -$difference;
                }
                for($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
                $difference = round($difference);
                if($difference != 1) $periods[$j].= "";
                $text = "$difference$periods[$j]";
                  if(($text == '0 seconds ago') || ($text == '1 seconds ago'))
                {
                    $text = 'Now';
                }
                
                if(($text == '1 minutes ago')){
                    $text = 'A minute ago';
                }
                
                if(($text == '1 hours ago')){
                    $text = 'An hour ago';
                }
                
                if(($text == '1 days ago')){
                    $text = 'Yesterday';
                }
                
                if(($text == '1 weeks ago')){
                    $text = '1 week ago';
                }
                
                if(($text == '1 months ago')){
                    $text = '1 month ago';
                }
                
                if(($text == '1 years ago')){
                    $text = '1 year ago';
                }

                return $text;
            }
function cancellation_policy()
    {
        $cancel = $this->input->get('policy');
        $id='1';
        
        $cancellation_policy = $this->db->where('id',$id)->from('cancellation_policy')->get();
        echo json_encode($cancellation_policy->result());
        
    }
    
    
    function pay()
    {
        if ((!$this->input->get('list_id')) ||  (!$this->input->get('status')))
 {
    echo '[{"status":"Required All Fields"}]';
 }
else
{
    
        $list_id = $this->input->get('list_id');
        $list['list_id'] = $list_id;
        $token = $_GET["token"];
        $playerid = $_GET["PayerID"];
        $status = $this->input->get('status');
        //$httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata, $api_user, $api_pwd, $api_key, $PayPalMode);
// print_r($httpParsedResponseAr['ACK']);exit;
        if($status == 'success')
        //if($_REQUEST['payment_status'] == 'Completed')
        {
            
            //print_r($_REQUEST['ItemName']);exit;
            //echo "<script> alert(''success');</script>";
        $custom = $this->session->userdata('custom');
        //print_r($custom);
        //exit;
        $data   = array();
        $list   = array();
        $data   = explode('@',$custom); 
        
        $contact_key    = $data[9];

        $list['list_id']       = $data[0];
        $list['userby']        = $data[1];
        
        $query1      = $this->Common_model->getTableData('list', array('id' => $list['list_id']));
        $buyer_id    = $query1->row()->user_id;
        
        $list['userto']            = $buyer_id;
        $list['checkin']           = $data[2];
        $list['checkout']          = $data[3];
        $list['no_quest']          = $data[4];
        
        $amt = explode('%',$httpParsedResponseAr['AMT']);
        
        $list['price']             = $amt[0];
        $currency                  = $httpParsedResponseAr['CURRENCYCODE'];
        
        $list['payment_id']        = 2;
        $list['credit_type']       = 1;
        $list['transaction_id']    = 0;
  
        $is_travelCretids          = $data[5];
        $user_travel_cretids       = $data[6];
        
        $list['topay']             = get_currency_value2($currency,$query1->row()->currency,$data[7]);
        $list['currency']          = $query1->row()->currency;
        $list['admin_commission']  = $data[8];
        
        //Entering into it
        
           
                
            $list['status'] = 3;
            $this->db->select_max('group_id');
                $group_id                   = $this->db->get('calendar')->row()->group_id;
                
                if(empty($group_id)) echo $countJ = 0; else $countJ = $group_id;
                
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
            
  if($list['price'] > 75)
            {
            $user_id = $list['userby'];
            $details = $this->Referrals_model->get_details_by_Iid($user_id);
            $row     = $details->row();
            $count   = $details->num_rows();
            if($count > 0)
            {
                                    $details1 = $this->Referrals_model->get_details_refamount($row->invite_from);
                                    if($details1->num_rows() == 0)
                                    {                       
                                    $insertData                  = array();
                                    $insertData['user_id']       = $row->invite_from;
                                    $insertData['count_trip']    = 1;
                                    $insertData['amount']        = 25;
                                    $this->Referrals_model->insertReferralsAmount($insertData);
                                    }
                                    else
                                    {
                                    $count_trip                  = $details1->row()->count_trip;
                                    $amount                      = $details1->row()->amount;
                                    $updateKey                   = array('id' => $row->id);
                                    $updateData                  = array();
                                    $updateData['count_trip']    = $count_trip + 1;
                                    $updateData['amount']        = $amount + 25;
                                    $this->Referrals_model->updateReferralsAmount($updateKey,$updateData);
                                    }
                }
            }
            
            $q        = $query1->result();
            $row_list = $query1->row();
         $iUser_id = $q[0]->user_id;
            $details2 = $this->Referrals_model->get_details_by_Iid($iUser_id);
            $row      = $details2->row();
            $count    = $details2->num_rows();
                if($count > 0)
                {
                 $details3 = $this->Referrals_model->get_details_refamount($row->invite_from);
                                    if($details3->num_rows() == 0)
                                    {                           
                                    $insertData                  = array();
                                    $insertData['user_id']       = $row->invite_from;
                                    $insertData['count_book']    = 1;
                                    $insertData['amount']        = 75;
                                    $this->Referrals_model->insertReferralsAmount($insertData);
                                    }
                                    else
                                    {
                                    $count_book   = $details3->row()->count_book;
                                    $amount       = $details3->row()->amount;
                                    $updateKey                   = array('id' => $row->id);
                                    $updateData                  = array();
                                    $updateData['count_trip']    = $count_book + 1;
                                    $updateData['amount']        = $amount + 75;
                                    $this->Referrals_model->updateReferralsAmount($updateKey,$updateData);
                                    }
                }
                
            $admin_email = $this->dx_auth->get_site_sadmin();
            $admin_name  = $this->dx_auth->get_site_title();
            
            $query3      = $this->Common_model->getTableData('users',array('id' => $list['userby']));
            $rows        =  $query3->row();
                
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
                                $updateData['amount']        = $amount -    $user_travel_cretids;
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
                    
            //Actual insertion into the database
            $this->Common_model->insertData('reservation', $list);      
            $reservation_id = $this->db->insert_id();
            
            //Send Message Notification
            $insertData = array(
                'list_id'         => $list['list_id'],
                'reservation_id'  => $reservation_id,
                'userby'          => $list['userby'],
                'userto'          => $list['userto'],
                'message'         => 'You have a new reservation request from '.ucfirst($username),
                'created'         => local_to_gmt(),
                'message_type'    => 1
                );
            $this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
            $message_id     = $this->db->insert_id();
            
            $actionurl = site_url('trips/request/'.$reservation_id);
                
   //Reservation Notification To Host
            $email_name = 'host_reservation_notification';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" =>  date('d-m-Y',$list['checkin']), "{checkout}" => date('d-m-Y',$list['checkout']), "{market_price}" => $list['price'], "{action_url}" => $actionurl);
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
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" =>  date('d-m-Y',$list['checkin']), "{checkout}" => date('d-m-Y',$list['checkout']), "{market_price}" => $user_travel_cretids+$list['price'], "{payed_amount}" => $list['price'],"{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                //Send Mail
                $this->Email_model->sendMail($admin_email,$email_id,ucfirst($username),$email_name,$splVars);
                
            //  if($is_block == 'on')
    //  {
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
            }
            
        
            $referral_amount = $this->db->where('id',$this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
            if($referral_amount > 100)
            {
                $this->db->set('referral_amount',$referral_amount-100)->where('id',$this->dx_auth->get_user_id())->update('users');
            }
            else
            {
         $this->db->set('referral_amount',0)->where('id',$this->dx_auth->get_user_id())->update('users');
            }
           
           echo '[{"status":"SUCCESS updated"}]';
    }}
    
    function form()
    {
        
        $id             = $this->input->get('id');
        $checkin         = $this->input->get('checkin');
        $checkout        = $this->input->get('checkout');
        $data['guests']  = $this->input->get('guest');
        $user_id = $this->input->get('user_id');
        
        $currency = $this->input->get('currency');
        
        $this->session->set_userdata('locale_currency',$currency);
  
        $param = $id;
        $data['checkin']  = $checkin;
        $data['checkout'] = $checkout;

        $ckin             = explode('/', $checkin);
        $ckout            = explode('/', $checkout);
        $pay              = $this->Common_model->getTableData('paywhom',array('id' => 1));
        $paywhom          = $pay->result();
        $paywhom          = $paywhom[0]->whom;
        $id               = $param;
                
        if($ckin[0]  == "mm")
        { 
            //$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Sorry! Access denied.'));
            redirect('rooms/'.$id, "refresh");
        } 
        if($ckout[0] == "mm") 
        { 
        //  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Sorry! Access denied.'));
            redirect('rooms/'.$id, "refresh");
        }
        

        $xprice         = $this->Common_model->getTableData( 'price', array('id' => $param) )->row();
        
        /* if($this->input->get())
        {
            $price = $this->input->get('subtotal'); 
        }
       else {*/
        $price          = $xprice->night;
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
        
        $data['security'] = $security;
        
        if(isset($xprice->week))
        $Wprice         = $xprice->week;    
        else
        $Wprice         = 0;
        
        if(isset($xprice->month))
        $Mprice         = $xprice->month;   
        else
        $Mprice         = 0;
        
        
        if($paywhom)
        {
            $query        = $this->Common_model->getTableData( 'list',array('id' => $id) )->row();
            $email        = $query->email;  
        } 
        else
        {
            $query        = $this->Common_model->getTableData( 'users',array('role_id' => 2) )->row();
            $email        = $query->email;
        }
        
        $query                = $this->Common_model->getTableData('list',array('id' => $id));
        $list                 = $query->row();
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
            else {
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
        }
        if($security != 0)
        {
            $amt                = $amt + get_currency_value1($id,$security);
        }
        else
        {
            $amt                = $amt;
        }
        
        $session_coupon         = $this->session->userdata("coupon"); 
        if($this->input->get('contact'))
        {
        $amt = get_currency_value_lys($contact_result->currency,get_currency_code(),$contact_result->price);
        $this->session->set_userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'",$amt);
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
            $query          = $this->Common_model->get_coupon();
            $row            =   $query->result_array();
            
            $list_id        = $this->input->post('hosting_id');
            $coupon_code    = $this->input->post('coupon_code');
            $user_id        = $this->dx_auth->get_user_id();
                    
            if($coupon_code != "")
            {
                $is_list_already    = $this->Common_model->getTableData('coupon_users', array( 'list_id' => $list_id,'user_id' => $user_id));
                $is_coupon_already  = $this->Common_model->getTableData('coupon_users', array( 'used_coupon_code' => $coupon_code,'user_id' => $user_id,'status'=>0));
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
                        $is_coupon          = 1;
                        $current_currency   = get_currency_code();
                        $coupon_currency    = $code['currency'];
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
                        $amt                = $amt - $Coupon_amt;
                        //echo $amt;exit;
                        $insertData         = array(
                        'list_id'           => $list_id,
                        'used_coupon_code'  => $coupon_code,
                        'user_id'           => $user_id,
                        'status'            => 0 
                        );
                        //echo $Coupon_amt.' - '.$amt;exit;
                        //echo get_currency_value1($list_id,$amt);exit;
                        if($amt < 1)
                        {
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your payment should be greater than 0.'))); 
                        redirect('rooms/'.$id, "refresh");
                        }
                        $this->Common_model->inserTableData('coupon_users',$insertData);
                        $this->db->where('couponcode',$coupon_code)->update('coupon',array('status'=>1));
                        $this->session->set_userdata("total_price_'".$list_id."'_'".$user_id."'",$amt);
                        $this->session->set_userdata('coupon_code_used',1);
                        $this->session->set_userdata('coupon_code',$coupon_code);
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
        
        
        
        $data['subtotal']    = $amt;
        
        //if($this->session->userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'") == "")
        //{ echo 'total';exit;
            //redirect('rooms/'.$param, "refresh");
        //  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Please! Try Again'));
        //}
        //check admin premium condition and apply so for
        $query                = $this->Common_model->getTableData('paymode', array( 'id' => 2));
        $row                  = $query->row();
        
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
                   $camt               = floatval(($finalAmount * $per) / 100);
                    $amt                = $amt + $camt;
                    $data['commission'] = $camt;
                }
                
        }
        else
        {
        $amt  = $amt;
        }
        
        // Coupon Code Starts
        
        if($amt > 110)
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

        if($amt < 10)
        {
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your payment should be greater than or equal to 10.')));    
                        redirect('rooms/'.$id, "refresh");
        }
        
        $data['result']    = $this->Common_model->getTableData('payments')->result();
        
        $array_items = array(
                            'list_id'           => '',
                            'Lcheckin'          => '',
                            'Lcheckout'         => '',
                            'number_of_guests'  => '',
                            'formCheckout'      => ''
                            );
        $this->session->unset_userdata($array_items);
        
            //$id = $list_id;
            $checkin_time       = get_gmt_time(strtotime($checkin));
            $checkout_time      = get_gmt_time(strtotime($checkout));
            $travel_dates       = array();
            $seasonal_prices    = array();      
            $total_nights       = 1;
            $total_price        = 0;
            $is_seasonal        = 0;
            $i                  = $checkin_time;
            while($i<$checkout_time)
            {
                $checkin_date                   = date('m/d/Y',$i);
                $checkin_date                   = explode('/', $checkin_date);
                $travel_dates[$total_nights]    = $checkin_date[1].$checkin_date[0].$checkin_date[2];
                $i                              = get_gmt_time(strtotime('+1 day',$i));
                $total_nights++; 
            }
            for($i=1;$i<$total_nights;$i++)
            {
                $seasonal_prices[$travel_dates[$i]]="";
            }
        //Store seasonal price of a list in an array
        $seasonal_query = $this->Common_model->getTableData('seasonalprice',array('list_id' => $id));
        $seasonal_result= $seasonal_query->result_array();
        if($seasonal_query->num_rows()>0)
        {
            foreach($seasonal_result as $time)
            {
            
                //Get Seasonal price
                $seasonalprice_query    = $this->Common_model->getTableData('seasonalprice',array('list_id' => $id,'start_date' => $time['start_date'],'end_date' => $time['end_date']));
                $seasonalprice          = $seasonalprice_query->row()->price;   
                //Days between start date and end date -> seasonal price    
                $start_time = $time['start_date'];
                $end_time   = $time['end_date'];
                $i          = $start_time;
                while($i<=$end_time)
                {   
                    $start_date                 = date('m/d/Y',$i);
                    $s_date                     = explode('/',$start_date); 
                    $s_date                     = $s_date[1].$s_date[0].$s_date[2];
                    $seasonal_prices[$s_date]   = $seasonalprice;
                    $i                          = get_gmt_time(strtotime('+1 day',$i));         
                }               
                
            }
            //Total Price
            for($i=1;$i<$total_nights;$i++)
            {
                if($seasonal_prices[$travel_dates[$i]] == "")   
                {   $xprice         = $this->Common_model->getTableData( 'price', array('id' => $id ) )->row();
                    $total_price=get_currency_value1($id,$total_price)+get_currency_value1($id,$xprice->night);
                }
                else 
                {
                    $total_price= get_currency_value1($id,$total_price)+$seasonal_prices[$travel_dates[$i]];
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
            //Cleaning
            if($cleaning != 0)
            {
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
            $days           = $total_nights;
            
            //Final price   
            $data['subtotal']   = $total_price; 
            $data['avg_price'] = $total_price/($days-1);
            //echo $data['avg_price'];exit;
            $amt = $data['subtotal'];
            
            $query                = $this->Common_model->getTableData('paymode', array( 'id' => 2));
        $row                  = $query->row();
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
                   $camt               = floatval(($finalAmount * $per) / 100);
                   $amt                = $amt + $camt;
                   $data['commission'] = $camt;
                   
                }
        }
        else
        {
        $amt  = $amt;
        }
                $data['amt'] = $amt;
                $this->session->set_userdata('topay',$amt);
        }
         
            $data['env'] = 'mobile';
            $this->session->set_userdata('mobile_user_id',$user_id);
        $data['result']    = $this->Common_model->getTableData('payments')->result();   

        $data['countries']            = $this->Common_model->getCountries()->result();
        $data['title']                = get_meta_details('Confirm_your_booking','title');
        $data["meta_keyword"]         = 'mobile';
        $data["meta_description"]     = get_meta_details('Confirm_your_booking','meta_description');
        
        $data['message_element']      = "payments/view_booking";
        $this->load->view('template',$data);
    }
    
    function form_android()
    {
        $id              = $this->input->get('id');
        $checkin         = $this->input->get('checkin');
        $checkout        = $this->input->get('checkout');
        $data['guests']  = $this->input->get('guest');
        $user_id = $this->input->get('user_id');
        
        $currency = $this->input->get('currency');
        
        $this->session->set_userdata('locale_currency',$currency);
  
        $param = $id;
        $data['checkin']  = $checkin;
        $data['checkout'] = $checkout;

        $ckin             = explode('/', $checkin);
        $ckout            = explode('/', $checkout);
        $pay              = $this->Common_model->getTableData('paywhom',array('id' => 1));
        $paywhom          = $pay->result();
        $paywhom          = $paywhom[0]->whom;
        $id               = $param;
        
        $xprice         = $this->Common_model->getTableData( 'price', array('id' => $param) )->row();
        
        /* if($this->input->get())
        {
            $price = $this->input->get('subtotal'); 
        }
       else {*/
        $price          = $xprice->night;
        //}
        $placeid        = $xprice->id;
        
        $guests         = $xprice->guests;
        
     if(isset($xprice->cleaning))
        $cleaning       = $xprice->cleaning;
        else
        $cleaning       = 0;
        
        if(isset($xprice->week))
        $Wprice         = $xprice->week;    
        else
        $Wprice         = 0;
        
        if(isset($xprice->month))
        $Mprice         = $xprice->month;   
        else
        $Mprice         = 0;
        
        
        if($paywhom)
        {
            $query        = $this->Common_model->getTableData( 'list',array('id' => $id) )->row();
            $email        = $query->email;  
        } 
        else
        {
            $query        = $this->Common_model->getTableData( 'users',array('role_id' => 2) )->row();
            $email        = $query->email;
        }
        
        $query                = $this->Common_model->getTableData('list',array('id' => $id));
        $list                 = $query->row();
        $data['address']      = $list->address;
        $data['room_type']    = $list->room_type;
        $data['total_guests'] = $list->capacity;
        $data['tit']          = $list->title;
        $data['manual']       = $list->manual;
        $data['cancellation_policy'] = $list->cancellation_policy;
        $data['house_rule'] = $list->house_rule;
        
        $diff                 = strtotime($ckout[2].'-'.$ckout[0].'-'.$ckout[1]) - strtotime($ckin[2].'-'.$ckin[0].'-'.$ckin[1]);
        $days                 = ceil($diff/(3600*24));
        
        /*$amt = $price * $days * $data['guests'];*/
        if($data['guests'] > $guests)
        {
                $diff_days          = $data['guests'] - $guests;
                $amt                = ($price * $days) + ($days * $xprice->addguests * $diff_days);
        }  
        else
        {
                $amt                = $price * $days;
        }

        
        //Entering it into data variables
        $data['id']           = $id;
        $data['price']        = $xprice->night;
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
            
        //Update the daily price
     $data['price']        = $xprice->night;
            
     //Cleaning fee
        if($cleaning != 0)
        {
            $amt                = $amt + $cleaning;
        }
        else
        {
            $amt                = $amt;
        }
        $session_coupon         = $this->session->userdata("coupon"); 
        if($this->input->get('contact'))
        {
        $amt=$contact_result->price;
        $this->session->set_userdata("total_price_'".$id."'_'".$user_id."'",$amt);
        }
        else
        {
            //$amt=$this->session->userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'");
        }
        
        //Coupon Starts
        if($this->input->post('apply_coupon'))
        {
            $is_coupon=0;
            //Get All coupons
            $query          = $this->Common_model->get_coupon();
            $row            =   $query->result_array();
            
            $list_id        = $this->input->post('hosting_id');
            $coupon_code    = $this->input->post('coupon_code');
            $user_id        = $user_id;
                    
            if($coupon_code != "")
            {
                $is_list_already    = $this->Common_model->getTableData('coupon_users', array( 'list_id' => $list_id,'user_id' => $user_id));
                $is_coupon_already  = $this->Common_model->getTableData('coupon_users', array( 'used_coupon_code' => $coupon_code,'user_id' => $user_id));
                //Check the list is already access with the coupon by the host or not
                /*if($is_list_already->num_rows() != 0)
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Sorry! You cannot use coupons for this list'));  
                    redirect('rooms/'.$list_id, "refresh");
                }
                //Check the host already used the coupon or not
                else*/ if($is_coupon_already->num_rows() != 0)
                {
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
                        $is_coupon          = 1;
                        $current_currency   = get_currency_code();
                        $coupon_currency    = $code['currency'];
                        if($current_currency == $coupon_currency) 
                        $Coupon_amt = $code['coupon_price'];
                        else
                        $Coupon_amt = get_currency_value_coupon($code['coupon_price'],$coupon_currency); 
                    }
                }
                if($is_coupon == 1)
                {
                    if($Coupon_amt >= get_currency_value1($list_id,$amt))
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! There is equal money or more money in your coupon to book this list.')));   
                        redirect('rooms/'.$list_id, "refresh");             
                    }
                    else
                    {
                        //Get the result amount & store the coupon informations     
                        $amt                = $amt - $Coupon_amt;
                        $insertData         = array(
                        'list_id'           => $list_id,
                        'used_coupon_code'  => $coupon_code,
                        'user_id'           => $user_id,
                        'status'            => 0 
                        );
                        $this->Common_model->inserTableData('coupon_users',$insertData);
$this->db->where('couponcode',$coupon_code)->update('coupon',array('status'=>1));
                        $this->session->set_userdata("total_price_'".$list_id."'_'".$user_id."'",$amt);
                    }
                }
                else 
                {
                      $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your coupon does not match.')));  
                      //redirect('rooms/'.$list_id, "refresh");
                }
                
                }   
            }
            else 
            {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your coupon does not match.')));    
                //  redirect('rooms/'.$list_id, "refresh");
            }
        }
        //Coupon Ends
        
        
        
        $data['subtotal']    = $amt;
        
        //if($this->session->userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'") == "")
        //{ echo 'total';exit;
            //redirect('rooms/'.$param, "refresh");
        //  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Please! Try Again'));
        //}
        //check admin premium condition and apply so for
        $query                = $this->Common_model->getTableData('paymode', array( 'id' => 2));
        $row                  = $query->row();
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
                   $camt               = floatval(($amt * $per) / 100);
                            $amt                = $amt + $camt;
                            $data['commission'] = $camt;
                }
                
        }
        else
        {
        $amt  = $amt;
        }
                
        // Coupon Code Starts
        //print_r($amt);exit;
        if($amt > 110)
        {
            $da = array();
            $this->db->select('*');
            $this->db->where('id',$user_id);
            $this->db->from('users');
            $value = $this->db->get()->result();
            foreach ($value as $val) 
                {
                    $da = $val->referral_amount;
                    
                }
            
        if($da !=0 )
        {
           $data['amt']    = $amt;
           $data['referral_amount'] = $da;
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
                        //$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Your payment should be greater than 0.')));   
                        //redirect('rooms/'.$id, "refresh");
                        echo '[{"status":"Sorry! Your payment should be greater than 0."}]';exit;
        }
        
        $data['result']    = $this->Common_model->getTableData('payments')->result();
        
        $array_items = array(
                            'list_id'           => '',
                            'Lcheckin'          => '',
                            'Lcheckout'         => '',
                            'number_of_guests'  => '',
                            'formCheckout'      => ''
                            );
        $this->session->unset_userdata($array_items);
        
            //$id = $list_id;
            $checkin_time       = get_gmt_time(strtotime($checkin));
            $checkout_time      = get_gmt_time(strtotime($checkout));
            $travel_dates       = array();
            $seasonal_prices    = array();      
            $total_nights       = 1;
            $total_price        = 0;
            $is_seasonal        = 0;
            $i                  = $checkin_time;
            while($i<$checkout_time)
            {
                $checkin_date                   = date('m/d/Y',$i);
                $checkin_date                   = explode('/', $checkin_date);
                $travel_dates[$total_nights]    = $checkin_date[1].$checkin_date[0].$checkin_date[2];
                $i                              = get_gmt_time(strtotime('+1 day',$i));
                $total_nights++; 
            }
            for($i=1;$i<$total_nights;$i++)
            {
                $seasonal_prices[$travel_dates[$i]]="";
            }
        //Store seasonal price of a list in an array
        $seasonal_query = $this->Common_model->getTableData('seasonalprice',array('list_id' => $id));
        $seasonal_result= $seasonal_query->result_array();
        if($seasonal_query->num_rows()>0)
        {
            foreach($seasonal_result as $time)
            {
            
                //Get Seasonal price
                $seasonalprice_query    = $this->Common_model->getTableData('seasonalprice',array('list_id' => $id,'start_date' => $time['start_date'],'end_date' => $time['end_date']));
                $seasonalprice          = $seasonalprice_query->row()->price;   
                //Days between start date and end date -> seasonal price    
                $start_time = $time['start_date'];
                $end_time   = $time['end_date'];
                $i          = $start_time;
                while($i<=$end_time)
                {   
                    $start_date                 = date('m/d/Y',$i);
                    $s_date                     = explode('/',$start_date); 
                    $s_date                     = $s_date[1].$s_date[0].$s_date[2];
                    $seasonal_prices[$s_date]   = $seasonalprice;
                    $i                          = get_gmt_time(strtotime('+1 day',$i));         
                }               
                
            }
            //Total Price
            for($i=1;$i<$total_nights;$i++)
            {
                if($seasonal_prices[$travel_dates[$i]] == "")   
                {   $xprice         = $this->Common_model->getTableData( 'price', array('id' => $id ) )->row();
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
            //Admin Commission
            //$data['commission'] = 0;          
        }
        if($is_seasonal==1)
        {   
            //Total days
            $days           = $total_nights;
            //Final price   
            $data['subtotal']   = $total_price; 
            $data['avg_price'] = $total_price/($days-1);
            //echo $data['avg_price'];exit;
            $amt = $data['subtotal'];
            
            $query                = $this->Common_model->getTableData('paymode', array( 'id' => 2));
        $row                  = $query->row();
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
                   $camt               = floatval(($amt * $per) / 100);
                            $amt                = $amt + $camt;
                            $data['commission'] = $camt;
                }
        }
        else
        {
        $amt  = $amt;
        }
                $data['amt'] = $amt;
                $this->session->set_userdata('topay',$amt);
        }
         $data['img']=getListImage($id);
         
        echo '['.json_encode($data).']';exit;
    }

public function paypal_details()
{
     $result = $this->db->get('payment_details');
     echo json_encode($result->result());exit;
}
    
    public function paypal()
    {
     $id             = $this->input->get('list_id');
        $checkin        = $this->input->get('checkin');
        $checkout       = $this->input->get('checkout');
        $data['guests'] = $this->input->get('guests');
        
        //check the list_id is in db
        $this->db->where('status !=', 0);
        $this->db->where('user_id !=', 0);
        $this->db->where('address !=', '0');
        $this->db->where('id', $id );
        $query = $this->db->get('list');
        if($query->num_rows() == 0)
        {
          echo '[{"available":false,"reason_message":"The host id is not available"}]'; exit;
        }
        
        $ckin  = explode('/', $checkin);
        $ckout = explode('/', $checkout);
    
        $x  = $this->db->get_where('price',array('id' => $id ));
        $x1 = $x->result();
        
        $per_night = $x1[0]->night;
        
        $guests    = $x1[0]->guests;
        
        if(isset($x1[0]->cleaning))
        $cleaning  = $x1[0]->cleaning;
        else
        $cleaning  = 0;
        
        if(isset($x1[0]->night))
        $price  = $x1[0]->night;
        else
        $price  = 0;
        
        if(isset($x1[0]->week))
        $Wprice = $x1[0]->week; 
        else
        $Wprice = 0;
        
        if(isset($x1[0]->month))
        $Mprice = $x1[0]->month;    
        else
        $Mprice = 0;
        
        //check admin premium condition and apply so for
        $query       = $this->db->get_where('paymode', array('id' => 2));
        $row         = $query->row();   


    if(($ckin[0] == "mm" && $ckout[0] == "mm") or ($ckin[0] == "" && $ckout[0] == ""))
        {
         $days = 0;
            
   $data['price']    = $price;
            
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
                                        $fix  = $row->fixed_amount; 
                                        $amt  = $price + $fix;
                                        $data['commission'] = $fix;
                                        $Fprice             = $amt;
                            }
                            else
                            {  
                                        $per  = $row->percentage_amount; 
                                        $camt = floatval(($price * $per) / 100);
                                        $amt  = $price + $camt;
                                        $data['commission'] = $camt;
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
            
            if($data['guests'] > $guests)
            {
              $diff_days = $data['guests'] - $guests;
              $price     = ($price * $days) + ($days * $x1[0]->addguests * $diff_days);
            }
            else
            {
              $price = $price * $days;
            }
            
            if($cleaning != 0)
            {
             $price = $price + $cleaning;
            }   
            
            //Entering it into data variables
            $data['price']    = $price;
                    
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
                                        $fix  = $row->fixed_amount; 
                                        $amt  = $price + $fix;
                                        $data['commission'] = $fix;
                                        $Fprice             = $amt;
                            }
                            else
                            {  
                                        $per  = $row->percentage_amount; 
                                        $camt = floatval(($price * $per) / 100);
                                        $amt  = $price + $camt;
                                        $data['commission'] = $camt;
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
                    
                    
            $query = $this->db->query("SELECT id,list_id FROM `calendar` WHERE `list_id` = '".$id."' AND (`booked_days` = '".$checkin."' OR `booked_days` = '".$checkout."') GROUP BY `list_id`");
            $rows  = $query->num_rows();
            //echo $this->db->last_query();exit;
            
            if($rows > 0)
            {
              echo '[{"available":false,"total_price":'.$data['price'].',"reason_message":"Those dates are not available"}]';
            }
            else
            {
              $is_live    = $this->db->get_where('payments', array( 'id' => 2))->row()->is_live;
                    
                    if($is_live == 1)
                    $paypal_url    = '1';
                    else
                    $paypal_url    = '2';
                    
                    $paypal_id     = $this->Common_model->getTableData('payment_details', array('code' => 'PAYPAL_ID'))->row()->value;
                    
              echo '[{"available":true,"is_live":"'.$paypal_url.'","paypal_id":"'.$paypal_id.'","service_fee":"$'.$data['commission'].'","cleaning_fee":"$'.$cleaning.'","reason_message":"","price_per_night":"$'.$per_night.'","nights":'.$days.',"total_price":"$'.($data['price']+$data['commission']).'"}]';
            }

    }
    
    public function paypalipn()
    {
    
     //mail('rameshr@cogzidel.com','Checkiny by me',$_REQUEST['payment_status'].'Coming'.$_REQUEST['mc_gross'].'Coming'.$_REQUEST['custom']);
        if($this->input->get('status') == 'Completed')
        {
        $list   = array();
        
        $list['list_id']       = $this->input->get('list_id');
        $list['userby']        = $this->input->get('userby');
        
        $query1      = $this->db->get_where('list', array('id' => $list['list_id']));
        $buyer_id    = $query1->row()->user_id;
        
        $list['userto']        = $buyer_id;
        $list['checkin']       = $this->input->get('checkin');
        $list['checkout']      = $this->input->get('checkout');
        $list['no_quest']      = $this->input->get('guest');
        $list['price']         = $this->input->get('amount');
        $list['credit_type']   = 1;
  
        $is_travelCretids    = NULL;
        $user_travel_cretids = NULL;
        //mail('rameshr@cogzidel.com','Test-done',$list['from'].'Coming3'.$list['to'].'vvv'.$data[2].'bbb'.$data[3]);
        
         $list['status'] = 1;

            if($list['price'] > 75)
            {
            $user_id = $list['userby'];
            $details = $this->Referrals_model->get_details_by_Iid($user_id);
            $row     = $details->row();
            $count   = $details->num_rows();
            if($count > 0)
            {
                                    $details1 = $this->Referrals_model->get_details_refamount($row->invite_from);
                                    if($details1->num_rows() == 0)
                                    {                       
                                    $insertData                  = array();
                                    $insertData['user_id']       = $row->invite_from;
                                    $insertData['count_trip']    = 1;
                                    $insertData['amount']        = 25;
                                    $this->Referrals_model->insertReferralsAmount($insertData);
                                    }
                                    else
                                    {
                                    $count_trip   = $details1->row()->count_trip;
                                    $amount       = $details1->row()->amount;
                                    $updateKey                   = array('id' => $row->id);
                                    $updateData                  = array();
                                    $updateData['count_trip']    = $count_trip + 1;
                                    $updateData['amount']        = $amount + 25;
                                    $this->Referrals_model->updateReferralsAmount($updateKey,$updateData);
                                    }
                }
            }
            
            $q        = $query1->result();
            $row_list = $query1->row();
         $iUser_id = $q[0]->user_id;
            $details2 = $this->Referrals_model->get_details_by_Iid($iUser_id);
            $row      = $details2->row();
            $count    = $details2->num_rows();
                if($count > 0)
                {
                 $details3 = $this->Referrals_model->get_details_refamount($row->invite_from);
                                    if($details3->num_rows() == 0)
                                    {                           
                                    $insertData                  = array();
                                    $insertData['user_id']       = $row->invite_from;
                                    $insertData['count_book']    = 1;
                                    $insertData['amount']        = 75;
                                    $this->Referrals_model->insertReferralsAmount($insertData);
                                    }
                                    else
                                    {
                                    $count_book   = $details3->row()->count_book;
                                    $amount       = $details3->row()->amount;
                                    $updateKey                   = array('id' => $row->id);
                                    $updateData                  = array();
                                    $updateData['count_trip']    = $count_book + 1;
                                    $updateData['amount']        = $amount + 75;
                                    $this->Referrals_model->updateReferralsAmount($updateKey,$updateData);
                                    }
                }
                
            $admin_email = $this->dx_auth->get_site_sadmin();
            $admin_name  = $this->dx_auth->get_site_title();
            
            $query3      = $this->db->get_where('users',array('id' => $list['userby']));
            $rows        =  $query3->row();
                
            $username    = $rows->username;
            $user_id     = $rows->id;
            $email_id    = $rows->email;
            
            $query4      = $this->users->get_user_by_id($buyer_id);
            $buyer_name  = $query4->row()->username;
            $buyer_email = $query4->row()->email;
            
            //Check md5('No Travel Cretids') || md5('Yes Travel Cretids')
            if($is_travelCretids == '7c4f08a53f4454ea2a9fdd94ad0c2eeb')
            {           
                        $query5      = $this->Referrals_model->get_details_refamount($user_id);
                $amount      = $query5->row()->amount;          
                                                                
                                $updateKey                   = array('user_id ' => $user_id);
                                $updateData                  = array();
                                $updateData['amount']        = $amount -    $user_travel_cretids;
                                $this->Referrals_model->updateReferralsAmount($updateKey,$updateData);
                                
                                $list['credit_type']   = 2;
                                $list['ref_amount']    = $user_travel_cretids;

                            
                            $row = $query4->row();
                            
                                //sent mail to administrator
                            $email_name = 'tc_book_to_admin';
                            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $user_travel_cretids+$list['price'], "{payed_amount}" => $list['price'],"{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                            //Send Mail
                            $this->Email_model->sendMail($admin_email,$email_id,ucfirst($username),$email_name,$splVars);
                            

                                //sent mail to buyer
                            $email_name = 'tc_book_to_host';
                            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $list['price']);
                            //Send Mail
                            $this->Email_model->sendMail($buyer_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);

            }
            
        //  $list['book_date']           = date('d-m-Y H:i:s');
                    
            //Actual insertion into the database
            $this->db->insert('reservation',$list);     
            $reservation_id = $this->db->insert_id();
            
            //Send Message Notification
            $insertData = array(
                'list_id'         => $list['list_id'],
                'reservation_id'  => $reservation_id,
                'userby'          => $list['userby'],
                'userto'          => $list['userto'],
                'message'         => 'You have a new reservation request from '.ucfirst($username),
                'created'         => date('m/d/Y g:i A'),
                'message_type'    => 1
                );
            $this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
            $message_id     = $this->db->insert_id();
            
            $actionurl = site_url('trips/request/'.$reservation_id);
                
   //Reservation Notification To Host
            $email_name = 'host_reservation_notification';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $list['price'], "{action_url}" => $actionurl);
            //Send Mail
            $this->Email_model->sendMail($buyer_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
            
         //Reservation Notification To Traveller
            $email_name = 'traveller_reservation_notification';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username));
            //Send Mail
            $this->Email_model->sendMail($email_id,$admin_email,ucfirst($admin_name),$email_name,$splVars);
            
                //Reservation Notification To Administrator
                $email_name = 'admin_reservation_notification';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $user_travel_cretids+$list['price'], "{payed_amount}" => $list['price'],"{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                //Send Mail
                $this->Email_model->sendMail($admin_email,$email_id,ucfirst($username),$email_name,$splVars);
                
                echo '[{"reason_message":"Payment completed successfully."}]'; exit;
                
        }
    
    
    }
    
}

?>
