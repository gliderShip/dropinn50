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

class Cron extends CI_Controller {

	public function Cron()
	{
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->helper('payment');
		
		$this->load->library('Form_validation');
		
		$this->load->model('Users_model'); 
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->load->model('Trips_model');
		$this->load->model('Rooms_model');
		
		$this->facebook_lib->enable_debug(TRUE);
	}
	
	public function expire()
	{
			$sql="select *from reservation";
			$query=$this->db->query($sql);
			$res=$query->result_array();
			$date=date("F j, Y, g:i a");
			$date=get_gmt_time(strtotime($date));
			
			foreach($res as $reservation)
			{
	            $timestamp=$reservation['book_date'];
				$book_date=date("F j, Y, g:i a",$timestamp);
				$book_date=strtotime($book_date);
			    $gmtTime   = get_gmt_time(strtotime('+1 day',$timestamp));
				
				if($gmtTime<=$date && $reservation['status']==1)		
				{
					$reservation_id    = $reservation['id'];
					$admin_email 	   = $this->dx_auth->get_site_sadmin();
	 				$admin_name  						= $this->dx_auth->get_site_title();
					$conditions    				= array('reservation.id' => $reservation_id);
					$row           				= $this->Trips_model->get_reservation($conditions);
					if($row->num_rows() != 0)
					{
					$row = $row->row();
					$query1     				= $this->Users_model->get_user_by_id($row->userby);
					
					$traveler_name 				= $query1->row()->username;
					$traveler_email 			= $query1->row()->email;
		
					$query2     						 = $this->Users_model->get_user_by_id($row->userto);
					$host_name 								= $query2->row()->username;
					$host_email 							= $query2->row()->email;
		
					$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
				 
					$updateKey      		  = array('id' => $reservation_id);
					$updateData               = array();
					$updateData['status ']    = 2;
					$this->Trips_model->update_reservation($updateKey,$updateData);
					
					// payment_helper called for refund amount for host and guest as per host and guest cancellation policy
			
					refund($reservation_id);
					
					$currency = $this->Common_model->getTableData('currency',array('currency_code'=>$row->currency))->row()->currency_symbol;		
					$price = $row->price;
					//echo $currency;exit;
					$checkin  = date('m/d/Y', $row->checkin);
					$checkout = date('m/d/Y', $row->checkout);		
					
					//Send Mail To Traveller
					$email_name = 'traveler_reservation_expire';
					$splVars    = array("{checkout}"=>$checkout,"{checkin}"=>$checkin,"{currency}"=>$currency,"{price}"=>$price,"{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
					$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
					//Send Mail To Host
					$email_name = 'host_reservation_expire';
					$splVars    = array("{checkout}"=>$checkout,"{checkin}"=>$checkin,"{currency}"=>$currency,"{price}"=>$price,"{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
					$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
				
				//if($host_email != $admin_email && $traveler_email != $admin_email)
				//	{
					//Send Mail To Administrator
					$email_name = 'admin_reservation_expire';
					$splVars    = array("{traveler_email}"=>$traveler_email,"{host_email}"=>$host_email,"{checkout}"=>$checkout,"{checkin}"=>$checkin,"{currency}"=>$currency,"{price}"=>$price,"{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
					$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
				//	}
				}
				}
				
			}	
echo '<h2>Cron Successfully Runned.</h2>'; 	
			
	}

public function calendar_sync()
{
	require_once("app/views/templates/blue/rooms/codebase/class.php");
	
	$exporter = new ICalExporter();
	
	$ical_urls = $this->db->get('ical_import');
	
	if($ical_urls->num_rows() != 0)
	{
		foreach($ical_urls->result() as $row)
		{
			
		$ical_content = file_get_contents($row->url);
		
	$events = $exporter->toHash($ical_content);
	$success_num = 0;
	$error_num = 0;
	
	$id = $row->list_id;
	
	/*! inserting events in database */
	
	$check_tb = $this->db->select('group_id')->where('list_id',$id)->order_by('id','desc')->limit(1)->get('calendar');
	//$query = $this->db->last_query();
	//echo $query;exit;
	//print_r($check_tb->num_rows());exit;
	if($check_tb->num_rows() != 0)
	{
		$i1 = $check_tb->row()->group_id;
	}
	else {
		$i1 = 1;
	}
	
		
	for ($i = 1; $i <= count($events); $i++) 
	{
	$event = $events[$i];
	
	
	$days = (strtotime($event["end_date"]) - strtotime($event["start_date"])) / (60 * 60 * 24);
	$created=$event["start_date"];
	
	for($j=1;$j<=$days;$j++)
	{	
							if($days == 1)
							{
								$direct = 'single';
							}
							else if($days > 1)
							{

								if($j == 1)
								{
								$direct = 'left';
								}
								else if($days == $j)
								{
								$direct = 'right';
								}
								else
								{
								$direct= 'both';
								}
							}	

							
		$startdate1=$event["start_date"];
				
		$check_dates = $this->db->where('list_id',$id)->where('booked_days',strtotime($startdate1))->get('calendar');
		
		if($check_dates->num_rows() != 0)
		{
			$conflict = $i;
		}
		else { 
			
			$data = array(
							'id' =>NULL,
							'list_id' => $id,
							'group_id' => $i+$i1,
							'availability' 		  => "Booked",
							'value' 		  => 0,
							'currency' 	=> "EUR",
							'notes' => "Not Available",                      //   $event["text"]
							'style' 	=> $direct,
							'booked_using' 	=> $ical_id,
							'booked_days' 	=>strtotime($startdate1),
							'created' 	=> strtotime($created)
				
							);
							
							$this->Common_model->insertData('calendar', $data);
				}
		
	//	if(isset($conflict))
	//	{
	//		$this->db->where('list_id',$id)->where('group_id',$conflict)->delete('calendar');
	//	}
	
						$abc =  $event["start_date"];
						$newdate = strtotime ( '+1 day' , strtotime ( $abc ) ) ;
						$event["start_date"]=date("m/d/Y", $newdate);
	}		
	
			$success_num++;
		
	}//for loop end
	}
	}

	echo '<h2>Cron Successfully Runned.</h2>';
}

	public function mysql_backup()
	{
				// Load the DB utility class
$this->load->dbutil();

$date = new DateTime();
$time = $date->format('Y-m-d_H-i-s');

// Backup your entire database and assign it to a variable
$prefs = array(
                'tables'      => array(),  // Array of tables to backup.
                'ignore'      => array(),           // List of tables to omit from the backup
                'format'      => 'txt',             // gzip, zip, txt
                'filename'    => 'sql_backup_'.$time.'.sql',    // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );

$backup = $this->dbutil->backup($prefs); 

// Load the file helper and write the file to your server
$this->load->helper('file');
write_file('./backup/sql_backup_'.$time.'.sql', $backup);

	echo '<h2>Cron Successfully Runned.</h2>'; 

	}

function coupon_expire()
{
	$expired_date = time();
	$this->db->where('expirein <=',$expired_date)->update('coupon',array('status'=>1));
	echo '<h2>Cron Successfully Runned.</h2>'; 
}
	
function accept_status()
{

$date=date("F j, Y, g:i a");
$date=get_gmt_time(strtotime($date));

$result = $this->Common_model->getTableData('reservation',array('status'=>3));
	
	if($result->num_rows() != 0 )
	{
		foreach($result->result() as $row_status)
		{
			$timestamp = $row_status->checkin;
			
			$checkin = date("F j, Y, g:i a",$timestamp);
			
			$checkin = strtotime($checkin);
			
			$gmtTime = get_gmt_time(strtotime('+1 day',$timestamp));
				
			if($gmtTime <= $date)		
			{
				
				$reservation_id    = $row_status->id;
					$admin_email 	   = $this->dx_auth->get_site_sadmin();
	 				$admin_name  						= $this->dx_auth->get_site_title();
					$conditions    				= array('reservation.id' => $reservation_id);
					$row           				= $this->Trips_model->get_reservation($conditions)->row();
					$query1     				= $this->Users_model->get_user_by_id($row->userby);
					$traveler_name 				= $query1->row()->username;
					$traveler_email 			= $query1->row()->email;
		
					$query2     						 = $this->Users_model->get_user_by_id($row->userto);
					$host_name 								= $query2->row()->username;
					$host_email 							= $query2->row()->email;
		
					$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
				 
					$updateKey      		  = array('id' => $reservation_id);
					$updateData               = array();
					$updateData['status ']    = 2;
					$this->Trips_model->update_reservation($updateKey,$updateData);
					
					$conversation = $this->db->where('userto',$row->userto)->where('userby',$row->userby)->order_by('id','desc')->get('messages');
			
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
					$conversation1 = $this->db->where('userto',$row->userby)->where('userby',$row->userto)->order_by('id','desc')->get('messages');
				
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
				$conversation1 = $this->db->where('userto',$row->userby)->where('userby',$row->userto)->order_by('id','desc')->get('messages');
				
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
			
			if(!isset($conversation_id))
			{
				$insertData = array(
			'list_id'         => $row->list_id,
			'reservation_id'  => $reservation_id,
			'userby'          => 1,
			'userto'          => $row->userto,
			'message'         => "Your list reservation is expired.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			
				$insertData1 = array(
			'list_id'         => $row->list_id,
			'reservation_id'  => $reservation_id,
			'userby'          => 1,
			'userto'          => $row->userby,
			'message'         => "Your reservation is expired.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);
			
			}
			else {
				$insertData = array(
			'list_id'         => $row->list_id,
			'reservation_id'  => $reservation_id,
			'conversation_id'  => $conversation_id,
			'userby'          => 1,
			'userto'          => $row->userto,
			'message'         => "Your list reservation is expired.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			
				$insertData1 = array(
			'list_id'         => $row->list_id,
			'reservation_id'  => $reservation_id,
			'conversation_id' => $conversation_id,
			'userby'          => 1,
			'userto'          => $row->userby,
			'message'         => "Your reservation is expired.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 3
			);	
			
			}
					
		$this->Message_model->sentMessage($insertData);
		$this->Message_model->sentMessage($insertData1);
		
		$currency = $this->Common_model->getTableData('currency',array('currency_code'=>$row->currency))->row()->currency_symbol;		
		$price = $row->price;
		
		$checkin  = date('m/d/Y', $row->checkin);
		$checkout = date('m/d/Y', $row->checkout);		
					
					//Send Mail To Traveller
					$email_name = 'traveler_reservation_expire';
					$splVars    = array("{checkout}"=>$checkout,"{checkin}"=>$checkin,"{currency}"=>$currency,"{price}"=>$price,"{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
					$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
					//Send Mail To Host
					$email_name = 'host_reservation_expire';
					$splVars    = array("{checkout}"=>$checkout,"{checkin}"=>$checkin,"{currency}"=>$currency,"{price}"=>$price,"{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
					$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
				
				//if($host_email != $admin_email && $traveler_email != $admin_email)
				//	{
					//Send Mail To Administrator
					$email_name = 'admin_reservation_expire';
					$splVars    = array("{traveler_email}"=>$traveler_email,"{host_email}"=>$host_email,"{checkout}"=>$checkout,"{checkin}"=>$checkin,"{currency}"=>$currency,"{price}"=>$price,"{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
					$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
				//	}
				
			}
		}
	}
	echo '<h2>Cron Successfully Runned.</h2>'; 
} 

function after_checkin()
{
	$date=date("F j, Y, g:i a");
$date=get_gmt_time(strtotime($date));

$result = $this->Common_model->getTableData('reservation',array('status'=>7));
	
	if($result->num_rows() != 0 )
	{
		foreach($result->result() as $row_status)
		{
			$timestamp = $row_status->checkout;
			
			$checkout = date("F j, Y, g:i a",$timestamp);
			
			$checkout = strtotime($checkout);
			
			$gmtTime = get_gmt_time(strtotime('+1 day',$timestamp));
				
			if($gmtTime <= $date)		
			{
					
				$reservation_id           = $row_status->id;
				$admin_email 						= $this->dx_auth->get_site_sadmin();
			$admin_name  						= $this->dx_auth->get_site_title();
	
			$conditions    				= array('reservation.id' => $reservation_id);
			$row           				= $this->Trips_model->get_reservation($conditions)->row();
			
			
			$updateKey      										= array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 8;
			$this->Trips_model->update_reservation($updateKey,$updateData);
			
			$conditions    				= array('reservation.id' => $reservation_id);
	 		$row           				= $this->Trips_model->get_reservation($conditions)->row();
			$before_status = $row->status;
									
		    if($this->Users_model->get_user_by_id($row->userby))
			{
			$query1     				= $this->Users_model->get_user_by_id($row->userby);
			}
			else 
				{	
			//$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your List was deleted.')));
			//redirect('travelling/your_trips');
				}
			
			$traveler_name 				= $query1->row()->username;
			$traveler_email 			= $query1->row()->email;
			
			$query2     						 = $this->Users_model->get_user_by_id($row->userto);
			$host_name 								= $query2->row()->username;
			$host_email 							= $query2->row()->email;
			
			$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
						
			$username = $traveler_name;
			
			if($row->list_id)
			{

			$insertData = array(
			'list_id'         => $row->list_id,
			'reservation_id'  => $reservation_id,
			'userby'          => $row->userby,
			'userto'          => $row->userto,
			'message'         => "$username wants the review from you.",
			'created'         => date('m/d/Y g:i A'),
			'message_type '   => 4
			);			
			
		$this->Message_model->sentMessage($insertData);
				
		//Send Mail To Traveller
		$email_name = 'checkout_traveler';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
				
		//Send Mail To Host
		$email_name = 'checkout_host';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		//Send Mail To Administrator
		$email_name = 'checkout_admin';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
		
		//$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('You are successfully checked out.')));	
		//redirect('travelling/previous_trips'); 
			
          }
	
			}
		}	
	}
echo '<h2>Cron Successfully Runned.</h2>'; 
}
	function notify(){
		$date=date("F j, Y");
		//echo $date=get_gmt_time(strtotime($date));
		$result = $this->Common_model->getTableData('reservation',array('status'=>3));
	
	if($result->num_rows() != 0 )
	{
		foreach($result->result() as $row_status)
		{
			$timestamp = $row_status->checkin;
			$timestamp1=$row_status->checkout;
			$checkind = date("F j, Y",$timestamp);
			$checkoutd = date("F j, Y",$timestamp1);
			$checkin = strtotime($checkind);
			$checkout=strtotime($checkoutd);
			$gmtTime = strtotime('-1 day',$timestamp);
			$gmtTime = date("F j, Y",$gmtTime);	
			if($gmtTime == $date)		
			{
					
				$reservation_id           = $row_status->id;
				$admin_email 						= $this->dx_auth->get_site_sadmin();
				$admin_name  						= $this->dx_auth->get_site_title();
	
			$conditions    				= array('reservation.id' => $reservation_id);
			$row           				= $this->Trips_model->get_reservation($conditions)->row();
			$before_status = $row->status;
									
		    if($this->Users_model->get_user_by_id($row->userby))
			{
			$query1  = $this->Users_model->get_user_by_id($row->userby);
			$query2  = $this->Users_model->get_user_by_id($row->userto);
			}
			else 
				{	
			//$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your List was deleted.')));
			//redirect('travelling/your_trips');
				}
				$query=	$this->Common_model->getTableData('user_notification',array('user_id' => $row->userto));
   					$result=$query->row();
 					$notify=$result->upcoming_reservation;
			if($notify==1)
	{
				
			
			 $traveler_name 				= $query1->row()->username;
			 $traveler_email 			= $query1->row()->email;
			
			 $query2     						 = $this->Users_model->get_user_by_id($row->userto);
			 $host_name 								= $query2->row()->username;
			 $host_email 							= $query2->row()->email;
			
			$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
						
			$username = $traveler_name;
				
		//Send Mail To Host
		$email_name = 'host_notification';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{traveler_name}" => ucfirst($traveler_name), "{host_name}"=>ucfirst($host_name), "{traveler_email_id}"=>$traveler_email,"{list_title}" => $list_title, "{checkout}" => $checkoutd,"{checkin}"=>$checkind,);
		
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
				
		
          }
	
			}}
		}
echo '<h2>Cron Successfully Runned.</h2>';	
	}
function calendar_notify(){
		$date=date("F j, Y");
		//echo $date=get_gmt_time(strtotime($date));
		$result = $this->Common_model->getTableData('users');
	
	if($result->num_rows() != 0 )
	{
		foreach($result->result() as $row_status)
		{
			$id= $row_status->id;
			$query=	$this->Common_model->getTableData('user_notification',array('user_id' => $id));
   			$result1=$query->row();
			if($result1){
				$notify=$result1->rank_search;
			}
 			if($notify==1){
				$query1=$this->Common_model->getTableData('list',array('user_id' => $id));
				$rooms=$query1->row();
				if($rooms){
					foreach($query1->row() as $rooms){
					$rooms_id= $query1->row()->id;
					$conditions	= array('list_id' => $rooms_id);
					$orderby=array('book_date');
					//$result2= $this->Trips_model->get_reservation($conditions	= array('list_id' => $rooms_id));
					//$result2=$this->Common_model->getTableData($table='reservation',$conditions,$fields='', $like=array(), $limit=array(), $orderby = array(), $like1=array(), $orderby, $conditions1=array() );
					//$this->db->oreser_by
					$result2=$this->db->query("SELECT MAX( `book_date` ) 
						FROM  `reservation` 
						WHERE  `list_id` =".$rooms_id);
					//	$result2);
					
					 $res_data=$result2->result_array();
 					 foreach($res_data as $data)
						{
						$page_v= $data;
						}
						//print_r ($page_v);
						foreach($page_v as $data1){
							 $max_book=$data1;
						}
			 		
					
					
					}
					$now=time();
					
					
				  date("F j, Y",$now);
				    $last_booking=date("F j, Y",$max_book);
				  $gmtTime = strtotime('-1 month',$now);
				  	 $checking=date("F j, Y",$gmtTime);
				 //$checking=
				  $rooms_id;
				 if($gmtTime>$max_book){
				 	
				 	$reserv=$this->Common_model->getTableData('list',array('id'=>$rooms_id));
				 $list_name=$reserv->row()->title;
					 $user=$reserv->row()->user_id;
					$usert=$this->Common_model->getTableData('users',array('id'=>$user));
					$userf=$this->Common_model->getTableData('users',array('id'=>1));
					 $admin_email=$userf->row()->email;
					 $admin_name=$userf->row()->username;
				  $host_name=$usert->row()->username;
				 $host_email=$usert->row()->email;
					//Send Mail To Host
		$email_name = 'list_notification';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{host_name}"=>ucfirst($host_name), "{list_name}" => $list_name, );
		
		$this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);		
				
					 //echo $user
				 	
				 }
				 }
				
			}
				
				
			}}
echo '<h2>Cron Successfully Runned.</h2>';	
}


function cloudinary_cron()
{
	 set_time_limit(0);
        require_once APPPATH.'libraries/cloudinary/autoload.php';
          \Cloudinary::config(array( 
                          "cloud_name" => cdn_name, 
                           "api_key" => cdn_api, 
                           "api_secret" => cloud_s_key
                ));
           $error = 0 ; 
  	 try{
           $api = new \Cloudinary\Api();
  		 $msg = $api->ping() ;
		}catch (Exception $e) {
    $msg = $e->getMessage();
	$error = 1 ; 
    }
   if($error == 1){
print_r( $msg);
   	echo "<br>Cannot connect to Cloudinary!!<br> Check the clouidnary account and make necessry changes of API key in database and try again."; exit; 
   }

	/*
			  require_once APPPATH.'libraries/cloudinary/autoload.php';
			\Cloudinary::config(array( 
							"cloud_name" => "duidtvpap", 
							 "api_key" => "467224546749428", 
							 "api_secret" => "jaY19u-n9VXbP56JRPZ3EwurDl0"
				  ));  
		*/
			 /*
			   try{
				  $api = new \Cloudinary\Api();
				 $api->delete_all_resources();
				}catch (Exception $e) {
				 $error = $e->getMessage();
						 } */
			 
			  
	echo "<pre>";
	
$path   = array('./Cloud_data/logo','./Cloud_data/images','./Cloud_data/css','./Cloud_data/js','./Cloud_data/uploads');
//$path   = array('./css');
$count = 0 ;
foreach ($path as $incldePath) {
	

$result = array('files' => array());

$DirectoryIterator = new RecursiveDirectoryIterator($incldePath);
$IteratorIterator  = new RecursiveIteratorIterator($DirectoryIterator, RecursiveIteratorIterator::SELF_FIRST);

foreach ($IteratorIterator as $file) {

    $currPath = $file->getRealPath();
	
	if ($file->isDir()) {
        //$result['directories'][] = $file->getFilename();
    } elseif ($file->isFile()) {
        $result['files'][] = $currPath;
		/*
		$name = $file->getFilename() ;
				 $temp = explode('.', $name);
						$ext  = array_pop($temp);*/
		
         //      echo realpath(FCPATH) ; 
                   
				 $path= pathinfo($currPath); 
				 $public_id_tmp = $path['dirname']."/".$path['filename']; // Works from PHP 5.2
				 $public_id = str_replace(realpath(FCPATH)."/Cloud_data/","", $public_id_tmp); 
				 
		 		try{
  
                     $cloudimage=\Cloudinary\Uploader::upload($currPath,
                               array(
                                     "public_id" => $public_id , "resource_type" => "auto"));
									 
        			$getcssImg  = explode('/',$path['dirname']); 
					
					if($public_id == "loading16" || $public_id == "lys_hv" || $public_id == "down_arrow" || $public_id == "tick" || $public_id == "welcome" || $public_id == "calender_list-1" || $public_id == "calender_list-2" || $public_id == "calender_list-3" || $public_id == "preview-btn" || $public_id == "back_arrow" || $public_id == "list_your_space"  ) 
					{
					$cloudimage=\Cloudinary\Uploader::upload($currPath,
                               array(
                                     "public_id" => $public_id , "resource_type" => "raw"));		
					}// For Some images in CSS 
					
					if(end($getcssImg)=="images" && prev($getcssImg)=="blue"){
					 $cloudimage=\Cloudinary\Uploader::upload($currPath,
                               array(
                                     "public_id" => $public_id , "resource_type" => "raw"));
					} 
    				print_r($cloudimage);
					// echo $public_id; 
                    }
                catch (Exception $e) {
                       $error = $e->getMessage();
					   print_r($error);
                  }
				
    }
	
}
$count = $count + count($result['files']); 
//print_r($result);
echo "Uploaded ".$count." files to Cloudinary";
}

}



function cleanup_cloudinary(){
	
	   require_once APPPATH.'libraries/cloudinary/autoload.php';
        \Cloudinary::config(array( 
                        "cloud_name" => cdn_name, 
                         "api_key" => cdn_api, 
                         "api_secret" => cloud_s_key
              ));
		 try{
     $api = new \Cloudinary\Api();
    $data = $api->delete_all_resources();
   }catch (Exception $e) {
    $error = $e->getMessage();
            }
   print_r($data); exit;
	
}



}// End
?>
