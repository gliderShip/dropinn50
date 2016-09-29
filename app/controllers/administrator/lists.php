<?php
/**
 * DROPinn Admin List Controller Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package		DROPinn
 * @subpackage	Controllers
 * @category	Admin List
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 
 */
class Lists extends CI_Controller
{
	function Lists()
	{
		parent::__construct(); 
		
		$this->load->library('Table');
		$this->load->library('Pagination');
		$this->load->library('form_validation');
		
		$this->load->helper('form');
		$this->load->helper('url');
 	    $this->load->helper('file');
		$this->load->helper('text');
		$this->load->model('Users_model');
		$this->load->model('Rooms_model');
		$this->load->model('Common_model');

		$this->path = realpath(APPPATH . '../images');	
		
		// Protect entire controller so only admin, 
		// and users that have granted role in permissions table can access it.
		$this->dx_auth->check_uri_permissions();
	}
	
	function index()
	{
	    require_once APPPATH.'libraries/cloudinary/autoload.php';
            \Cloudinary::config(array( 
              "cloud_name" => cdn_name, 
              "api_key" => cdn_api, 
              "api_secret" => cloud_s_key
            ));
		$query = $this->db->get('list');
		// Get offset and limit for page viewing
		$start = (int) $this->uri->segment(4,0);
	 // Number of record showing per page
		$row_count = 10;
		if($start > 0)
		   $offset			 = ($start-1) * $row_count;
		else
		   $offset			 =  $start * $row_count; 
		
		
		// Get all users
		$data['users'] = $this->db->order_by('id','asc')->get('list', $row_count, $offset)->result();
		
		// Pagination config
		$p_config['base_url']    = admin_url('lists/index');
		$p_config['uri_segment'] = 4;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $query->num_rows();
		$p_config['per_page']    = $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
				
	$data['message_element'] = "administrator/view_lists";
	$this->load->view('administrator/admin_template', $data);
	}
	
	/*
	function managelist()
	{
		$check = $this->input->post('check');
		
		if($this->input->get('id') != '')
		{
			$check = $this->input->get('id');
		}
		
		if($check == '' && $this->input->post() != '' && $this->input->post('add_desc') == '' && $this->input->post('update_overview') == '' && $this->input->post('update_desc') == '' && $this->input->post('update_price') == '' && $this->input->post('import') == '' && $this->input->post('update_aminities') == '' && $this->input->post('update_photo') == '')
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
			redirect_admin('lists');
		}
		
		if($check == '' && $this->input->post('list_id') == '' && $this->input->get('month') == '' )
		{
			$check = $this->uri->segment(4);
				/* if(empty($check))
					{
						$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
						redirect_admin('lists');
					}
				
				$query                   = $this->db->get_where('list', array( 'id' => $check));
				$data['result']          = $query->row();
				
				$data['country_list'] = $this->db->get('country');
				
				$list = $this->db->where('id',$check)->get('list')->row();
				
				$data['lat']              = $list->lat;
				$data['long']			  = $list->long;
			$location1  = $list->street_address;
		$FileName1 = str_replace("'", "", $location1);
		$FileName4 = str_replace("-", "", $FileName1);
		
				$data['city']    = $list->city;
				$data['state']    = $list->state;
            	$data['country']		= $list->country;
				$data['street_address']		= $FileName4;
				$data['optional_address']		= $list->optional_address;	
				$data['zip_code']		= $list->zip_code;
				
				$data['currency_result'] = $this->db->where('status',1)->get('currency');
				
				$data['currency_value'] = $this->db->where('id',$check)->get('list')->row()->currency;
				
				if($query->num_rows() == 0)
				{
					//$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This list already deleted.')));
					redirect_admin('lists');
				}
				
			$check_calendar = $this->db->where('id',$check)->get('list');
			$param = $check;
			if($check_calendar->num_rows() == 0)
			{
				redirect('info');
			}
	 		$list_id = $param;
			$day     = date("d");
			$month   = $this->input->get('month', TRUE);
			$year    = $this->input->get('year', TRUE);
			
			if (!empty($month) && !empty($year))
			{
			  $month   = $month;
			  $year    = $year;
			}
			else
			{
			  $month   = date("m");
			  $year    = date("Y");
			}
			
			if($month > 12 || $month < 1)
			{
			  $month = date("m");
			}
			else
			{
			  $month = $month;
			}
			
			if(($year == ($year - 3)) || ($year == ($year + 3)))
			{
			  redirect('calendar/single/'.$list_id.'?month='.$month.'&year='.date("Y"));
			}
			if($this->Common_model->getTableData('list', array( 'id' => $param))->num_rows()==0)
			{
				redirect('info/deny');
			}
		 $row                 = $this->Common_model->getTableData('list', array( 'id' => $param))->row();
   $data['list_title']  = $row->title;
			$data['list_price']  = $row->price;
			
			$conditions          = array('list_id' => $list_id);
			$this->load->model('Trips_model');
			$data['result_cal']      = $this->Trips_model->get_calendar($conditions)->result();
			
			$data['list_id']     = $list_id;
			$data['day']         = $day;
			$data['month']       = $month;
			$data['year']        = $year;
			//Remove incorrect list from seasonal price
			$query=$this->Common_model->getTableData('seasonalprice', array( 'list_id' => $list_id));
			$res=$query->result_array();
			foreach($res as $seasonal)
			{
				$starttime   	= $seasonal['start_date'];	
				$gmtTime   		= $seasonal['end_date'];
				if($gmtTime<$starttime)		
				{	
					$list_id    = $seasonal['list_id'];
					$remove_query="delete from seasonalprice where list_id='".$list_id."' and start_date='".$seasonal['start_date']."' and end_date='".$seasonal['end_date']."'";
					$remove_exe= $this->db->query($remove_query);
				}
			}	
				
				
				$data['amnities']        = $this->Rooms_model->get_amnities();
				
				$data['property_types']  = $this->db->order_by('id','asc')->get('property_type');
				$data['lys_status'] = $this->db->where('id',$check)->get('lys_status')->row();
				$query3                  = $this->db->get_where('price',array('id' => $check));
	   			$data['price']           = $query3->row(); 
				
				$data['list_images']     = $this->Gallery->get_imagesG($check);
				
				$data['cancellation_policy_data'] = $this->Common_model->getTableData('cancellation_policy',array('is_standard'=>"1"));
				
				$data['message_element'] = "administrator/view_edit_list";
				$this->load->view('administrator/admin_template', $data);
				
		}
else{
        if($this->input->post('update_overview'))
		{
			
			extract($this->input->post());
			$data['title'] = $title;
		//	print_r($data['title']);exit;
			$data['desc'] = $desc;
			$this->db->where('id',$id)->update('list',$data);
			
			$data_lys['title'] = 1;
			$data_lys['overview'] = 1;
			$data_lys['summary'] = 1;
			$this->db->where('id',$id)->update('lys_status',$data_lys);
			
			$result = $this->db->where('id',$id)->get('lys_status')->row();
			
			$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
			if($total == 6)
			{
			  $data['is_enable'] = 1;
			  $data['list_pay'] = 1;
			  $this->db->where('id',$id)->update('list',$data);
			}
			
			echo '<p>Updated successfully</p>';
		}
		else if($this->input->post('delete') || $this->uri->segment(4) == 'delete')
		{
			
			if($this->input->get('id') != '')
			{
				$check = array();
				$check[0] = $this->input->get('id');
			}
			
			if(empty($check))
			{
			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
				redirect_admin('lists');
			}
		foreach($check as $id)
		{
		$reservation_status=$this->Common_model->getTableData( 'reservation', array('list_id' => $id, 'status !=' => '10' ));
		if($reservation_status->num_rows() > 0)
		{
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, The selected listing is in process or resevered by someone')));
		redirect_admin('lists');
		}
		else
		{
		$this->db->delete('list', array('id' => $id));
		$this->db->delete('list_photo', array('id' => $id)); 
		$this->db->delete('price', array('id' => $id)); 
		$this->db->delete('calendar', array('list_id' => $id)); 
		$this->db->delete('messages', array('list_id' => $id));
		$this->db->delete('referrals_booking', array('list_id' => $id));
		$this->db->delete('reviews', array('list_id' => $id));
		$this->db->delete('statistics', array('list_id' => $id));
		$this->db->delete('contacts', array('list_id' => $id));
		$this->db->delete('reservation', array('list_id' => $id));
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate_admin('Rooms deleted successfully.')));
		}
		}
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('List Deleted Successfully')));
		redirect_admin('lists');
		}
		else if($this->input->post('featured'))
		{
			
			if(empty($check))
			{
			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
				redirect_admin('lists');
			}
		
			foreach($check as $c)
			{
				$lys_status = $this->db->where('id',$c)->get('lys_status');
				
				foreach($lys_status->result() as $row)
				{
				$total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
				$total_status = 6 - $total_status;
				}
				if($total_status == 0)
				$this->Common_model->updateTableData('list',$c,NULL,array("is_featured" => '1'));
				else
				{
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry! you're choosed incompleted list.")));
			 	redirect_admin('lists');
				}
			}
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Updated Successfully')));
			 redirect_admin('lists');
		}
		else if($this->input->post('unfeatured'))
		{
			if(empty($check))
			{
			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
				redirect_admin('lists');
			}
				foreach($check as $c)
				{
					$sql="update list set is_featured=0 where id='".$c."'";
					$this->db->query($sql); 
				}
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('List unfeatured Successfully')));
			 redirect_admin('lists');
		}
		else if($this->input->post('edit') || $this->input->get('id'))
		{
			$check = array();
			if($this->input->get('id') != '')
		{
			$check[0] = $this->input->get('id');
		}
					if(empty($check))
					{
						$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
						redirect_admin('lists');
					}
				if(count($check) == 1)
				{
				$query                   = $this->db->get_where('list', array( 'id' => $check[0]));
				$data['result']          = $query->row();
				
				$data['currency_result'] = $this->db->where('status',1)->get('currency');
				
				$data['currency_value'] = $this->db->where('id',$check[0])->get('list')->row()->currency;
				
				if($query->num_rows() == 0)
				{
					$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This list already deleted.')));
					redirect_admin('lists');
				}
				
				$check_calendar = $query;
				
			$param = $check[0];
			if($check_calendar->num_rows() == 0)
			{
				redirect('info');
			}
	 		$list_id = $param;
			$day     = date("d");
			$month   = $this->input->get('month', TRUE);
			$year    = $this->input->get('year', TRUE);
			
			if (!empty($month) && !empty($year))
			{
			$month   = $month;
			$year    = $year;
			}
			else
			{
			$month   = date("m");
			$year    = date("Y");
			}
			
			if($month > 12 || $month < 1)
			{
			  $month = date("m");
			}
			else
			{
			  $month = $month;
			}
			
			if(($year == ($year - 3)) || ($year == ($year + 3)))
			{
			  redirect('calendar/single/'.$list_id.'?month='.$month.'&year='.date("Y"));
			}
			if($this->Common_model->getTableData('list', array( 'id' => $param))->num_rows()==0)
			{
				redirect('info/deny');
			}
		 $row                 = $this->Common_model->getTableData('list', array( 'id' => $param))->row();
   $data['list_title']  = $row->title;
			$data['list_price']  = $row->price;
			
			$conditions          = array('list_id' => $list_id);
			$this->load->model('Trips_model');
			$data['result_cal']      = $this->Trips_model->get_calendar($conditions)->result();
			
			$data['list_id']     = $list_id;
			$data['day']         = $day;
			$data['month']       = $month;
			$data['year']        = $year;
			//Remove incorrect list from seasonal price
			$query=$this->Common_model->getTableData('seasonalprice', array( 'list_id' => $list_id));
			$res=$query->result_array();
			foreach($res as $seasonal)
			{
				$starttime   	= $seasonal['start_date'];	
				$gmtTime   		= $seasonal['end_date'];
				if($gmtTime<$starttime)		
				{	
					$list_id    = $seasonal['list_id'];
					$remove_query="delete from seasonalprice where list_id='".$list_id."' and start_date='".$seasonal['start_date']."' and end_date='".$seasonal['end_date']."'";
					$remove_exe= $this->db->query($remove_query);
				}
			}	
				
				
				
				$data['amnities']        = $this->Rooms_model->get_amnities();
				
				$data['property_types']  = $this->db->order_by('id','asc')->get('property_type');
				
				$query3                  = $this->db->get_where('price',array('id' => $check[0]));
	   			$data['price']           = $query3->row(); 
				
				$data['list_images']     = $this->Gallery->get_imagesG($check[0]);
				
				$data['lys_status'] = $this->db->where('id',$check[0])->get('lys_status')->row();
				
				$data['cancellation_policy_data'] = $this->Common_model->getTableData('cancellation_policy',array('is_standard'=>"1"));
				
				$data['message_element'] = "administrator/view_edit_list";
				$this->load->view('administrator/admin_template', $data);
				}
				else
				{
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please select any one list to edit!')));
				redirect_admin('lists');
				}
		}
else if($this->input->get('month'))
{
	if ($this->input->post("next")) {
	
			 $db_name = $this->config->item('db');
	 $db_table = "calendar";
	
	if ($this->input->post("ical_url") && ($this->input->post("ical_url") != '')) {
		
				$ical_url = trim($this->input->post("ical_url"));
				
				if (@file_get_contents(trim($this->input->post("ical_url"))) == true) {
					$ical_content = trim($this->input->post("ical_url"));
				} else {
					$problems[] = "Ical resource specified by url is not available.";
				}
			} else {
				$problems[] = "Resource file should be specified.";
				$data['required_msg'] = 1;
				//echo '<span style="color:red">Please Give Valid URL.</span>';
			}
	
	$check_ical = $this->db->where('url',trim($this->input->post('ical_url')))->where('list_id',$this->uri->segment(4))->get('ical_import');
	
	$log = Array();
	if($this->input->post('ical_url') != '')
	{
	if($check_ical->num_rows() == 0)
	{
				$data = array(
							'id' =>NULL,
							'list_id' =>$this->uri->segment(4),
							'url' 		  => trim($this->input->post('ical_url')),
							'last_sync' => date('d M Y, g:i a',gmt_to_local(time(), get_user_timezone(), false))			
							);
				$this->Common_model->insertData('ical_import', $data);
				$ical_id = $this->db->insert_id();
				$query1= $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '{$db_name}' AND table_name = '{$db_table}' LIMIT 1");

	/*! exporting event from source into hash 
	require_once(realpath(APPPATH . '../app/views/templates/blue/rooms/codebase/class.php'));
	$exporter = new ICalExporter();
	$events = $exporter->toHash($ical_content);
	
	$success_num = 0;
	$error_num = 0;
	/*! inserting events in database 
	
	$check_tb = $this->db->select('group_id')->where('list_id',$this->uri->segment(4))->order_by('id','desc')->limit(1)->get('calendar');
	
	if($check_tb->num_rows() != 0)
	{
		$i1 = $check_tb->row()->group_id;
	}
	else {
		$i1 = 1;
	}
	if(count($events))
	{
	for ($i = $i1; $i <= $i1+count($events); $i++) 
	{
		if($i == $i1)
	$event = $events[1];
		else 
	$event  = $events[$i-$i1];
				
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
		
		$id=$this->uri->segment(4);
		
		$check_dates = $this->db->where('list_id',$this->uri->segment(4))->where('booked_days',strtotime($startdate1))->get('calendar');
		
		if($check_dates->num_rows() != 0)
		{
			
		}
		else { 
			
			$data = array(
							'id' =>NULL,
							'list_id' => $this->uri->segment(4),
							'group_id' => $i,
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
	
						$abc =  $event["start_date"];
						$newdate = strtotime ( '+1 day' , strtotime ( $abc ) ) ;
						$event["start_date"]=date("m/d/Y", $newdate);
	}		
	
			$success_num++;
		
	}//for loop end
	$data['success_num'] = $success_num;
	$log = Array("text" => "{$success_num} Booking were inserted successfully.", "type" => "Success");
	}
	else
	{
	$log = Array("text" =>"No data in given URL.", "type" => "Error");
	}
	}           
	else {
		$ical_id = $check_ical->row()->id;
		/*! exporting event from source into hash 
	require_once(realpath(APPPATH . '../app/views/templates/blue/rooms/codebase/class.php'));
	$exporter = new ICalExporter();
	$events = $exporter->toHash($ical_content);
	
	$success_num = 0;
	$error_num = 0;
	/*! inserting events in database 
	
	$check_tb = $this->db->select('group_id')->where('list_id',$this->uri->segment(4))->order_by('id','desc')->limit(1)->get('calendar');
	
	if($check_tb->num_rows() != 0)
	{
		$i1 = $check_tb->row()->group_id;
	}
	else {
		$i1 = 1;
	}
	
	for ($i = $i1; $i <= $i1+count($events); $i++) 
	{
		if($i == $i1)
	$event = $events[1];
		else 
	$event  = $events[$i-$i1];
				
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
		
		$id=$this->uri->segment(4);
		
		$check_dates = $this->db->where('list_id',$this->uri->segment(4))->where('booked_days',strtotime($startdate1))->get('calendar');
		
		if($check_dates->num_rows() != 0)
		{
			
		}
		else { 
			
			$data = array(
							'id' =>NULL,
							'list_id' => $this->uri->segment(4),
							'group_id' => $i,
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
	
						$abc =  $event["start_date"];
						$newdate = strtotime ( '+1 day' , strtotime ( $abc ) ) ;
						$event["start_date"]=date("m/d/Y", $newdate);
	}		
	
			$success_num++;
		
	}//for loop end
	
	$update_sync['last_sync'] = date('d M Y, g:i a',gmt_to_local(time(), get_user_timezone(), false));
		
	$this->db->where('id',$ical_id)->update('ical_import',$update_sync);
		
		$log = Array("text" => "This URL were already imported.", "type" => "Error");
	}	
	$data['log'] = $log;
	}
}
				$query                   = $this->db->get_where('list', array( 'id' => $this->uri->segment(4)));
				$data['result']          = $query->row();
				
				$data['currency_result'] = $this->db->where('status',1)->get('currency');
				
				$data['currency_value'] = $this->db->where('id',$this->uri->segment(4))->get('list')->row()->currency;
				
				if($query->num_rows() == 0)
				{
					$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This list already deleted.')));
					redirect_admin('lists');
				}
				
				$check_calendar = $query;
				
			$param = $this->uri->segment(4);
			if($check_calendar->num_rows() == 0)
			{
				redirect('info');
			}
	 		$list_id = $param;
			$day     = date("d");
			$month   = $this->input->get('month', TRUE);
			$year    = $this->input->get('year', TRUE);
			
			if (!empty($month) && !empty($year))
			{
			$month   = $month;
			$year    = $year;
			}
			else
			{
			$month   = date("m");
			$year    = date("Y");
			}
			
			if($month > 12 || $month < 1)
			{
			  $month = date("m");
			}
			else
			{
			  $month = $month;
			}
			
			if(($year == ($year - 3)) || ($year == ($year + 3)))
			{
			  redirect('calendar/single/'.$list_id.'?month='.$month.'&year='.date("Y"));
			}
			if($this->Common_model->getTableData('list', array( 'id' => $param))->num_rows()==0)
			{
				redirect('info/deny');
			}
		 $row                 = $this->Common_model->getTableData('list', array( 'id' => $param))->row();
   $data['list_title']  = $row->title;
			$data['list_price']  = $row->price;
			
			$conditions          = array('list_id' => $list_id);
			$this->load->model('Trips_model');
			$data['result_cal']      = $this->Trips_model->get_calendar($conditions)->result();
			
			$data['list_id']     = $list_id;
			$data['day']         = $day;
			$data['month']       = $month;
			$data['year']        = $year;
			//Remove incorrect list from seasonal price
			$query=$this->Common_model->getTableData('seasonalprice', array( 'list_id' => $list_id));
			$res=$query->result_array();
			foreach($res as $seasonal)
			{
				$starttime   	= $seasonal['start_date'];	
				$gmtTime   		= $seasonal['end_date'];
				if($gmtTime<$starttime)		
				{	
					$list_id    = $seasonal['list_id'];
					$remove_query="delete from seasonalprice where list_id='".$list_id."' and start_date='".$seasonal['start_date']."' and end_date='".$seasonal['end_date']."'";
					$remove_exe= $this->db->query($remove_query);
				}
			}	
				
				$data['amnities']        = $this->Rooms_model->get_amnities();
				
				$data['property_types']  = $this->db->order_by('id','asc')->get('property_type');
				
				$query3                  = $this->db->get_where('price',array('id' => $list_id));
	   			$data['price']           = $query3->row(); 
				
				$data['list_images']     = $this->Gallery->get_imagesG($list_id);
				
				$data['lys_status'] = $this->db->where('id',$list_id)->get('lys_status')->row();
				
				$data['cancellation_policy_data'] = $this->Common_model->getTableData('cancellation_policy',array('is_standard'=>"1"));
				
				$data['message_element'] = "administrator/view_edit_list";
				$this->load->view('administrator/admin_template', $data);
				
}
		else if($this->input->post('add_desc'))
		{
		 $listId           = $this->input->post('id');
	
				$query                   = $this->db->get_where('list', array( 'id' => $listId));
		     if($query->num_rows() == 0)
				{
					echo '0';exit;
				}
			
			$check_policy = $this->Common_model->getTableData('cancellation_policy',array('id'=>$this->input->post('cancellation_policy')));
			
			if($check_policy->num_rows() == 0)
			{
				echo 'policy';exit;
			}
			
			$data = array(
							'property_id'  			=> $this->input->post('property_id'),
							'room_type'   			=> $this->input->post('room_type'),
							'capacity'     			=> $this->input->post('capacity'),
							'bedrooms'    			=> $this->input->post('bedrooms'),
							'beds'     				=> $this->input->post('beds'),
							'bed_type'     			=> $this->input->post('hosting_bed_type'),
							'bathrooms'     		=> $this->input->post('hosting_bathrooms'),
							'house_rule'     		=> $this->input->post('manual'),
							'cancellation_policy' 	=> $this->input->post('cancellation_policy'),
							
							);
							
							extract($this->input->post());
							
							if($this->input->post('beds') != '')
							{
								$data_lys['bedscount'] = 1;
							}
							
							if($this->input->post('hosting_bathrooms') != '')
							{
								$data_lys['bathrooms'] = 1;
							}
                            if($this->input->post('bedrooms') != '')
							{
								$data_lys['beds'] = 1;
							}
							
							if($this->input->post('hosting_bed_type') != '')
							{
								$data_lys['bedtype'] = 1;
							}
							
							if($this->input->post('bedrooms') != '' && $this->input->post('beds') != '' && $this->input->post('hosting_bed_type') != '' && $this->input->post('hosting_bathrooms') != '')
							{
								$data_lys['listing'] = 1;
							}
							else
							{
								$data_lys['listing'] = 0;
							}
							$this->db->where('id',$listId)->update('lys_status',$data_lys);

			$this->db->where('id', $listId);
			$this->db->update('list',$data);
			
			$result = $this->db->where('id',$listId)->get('lys_status')->row();
			
			$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
			if($total == 6)
			{
			  $data['is_enable'] = 1;
			  $data['list_pay'] = 1;
			  $this->db->where('id',$listId)->update('list',$data);
			}
					
		echo "<p>List Description Updated Successfully</p>";	
      }
else if($this->input->post('update_desc'))
		{
		 $listId           = $this->input->post('id');
						
				$query                   = $this->db->get_where('list', array( 'id' => $listId));
		     if($query->num_rows() == 0)
				{
					echo '0';exit;
				}
				
			$check_policy = $this->Common_model->getTableData('cancellation_policy',array('id'=>$this->input->post('cancellation_policy')));
			
			if($check_policy->num_rows() == 0)
			{
				echo 'policy';exit;
			}

			if($this->input->post('title') == '')
			{
				$this->db->where('id',$listId)->update('lys_status',array('title'=>0,'overview'=>0));
			}
			else
				{
					if($this->input->post('desc') != '')
					{
						 $this->db->where('id',$listId)->update('lys_status',array('title'=>1,'overview'=>1,'summary'=>1));
						$lys_status = $this->db->where('id',$listId)->get('lys_status');
				
				foreach($lys_status->result() as $row)
				{
				$total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
				$total_status = 6 - $total_status;
				}
					if($total_status == 0)
					{
					$this->db->where('id',$listId)->update('list',array('is_enable'=>1,'list_pay'=>1));	
					}	
					}
					else
				  $this->db->where('id',$listId)->update('lys_status',array('overview'=>0,'summary'=>0));			
				}
			$data = array(
							'property_id'  			=> $this->input->post('property_id'),
							'room_type'   			=> $this->input->post('room_type'),
							'title'    				=> $this->input->post('title'),
							'desc'         			=> $this->input->post('desc'),
							'capacity'     			=> $this->input->post('capacity'),
							'bedrooms'    			=> $this->input->post('bedrooms'),
							'beds'     				=> $this->input->post('beds'),
							'bed_type'     			=> $this->input->post('hosting_bed_type'),
							'bathrooms'     		=> $this->input->post('hosting_bathrooms'),
							'house_rule'     		=> $this->input->post('manual'),
							'cancellation_policy' 	=> $this->input->post('cancellation_policy'),
							'address'     			=> $this->input->post('address'),
							'lat'                   => $this->input->post('hidden_lat'),
							'long'                   => $this->input->post('hidden_lng'),
							'street_address'                   => $this->input->post('lys_street_address'),
							'city'                   => $this->input->post('city'),
							'state'                   => $this->input->post('state'),
							'zip_code'                   => $this->input->post('zipcode'),
							'country'                   => $this->input->post('country')
							);
							extract($this->input->post());
							if($this->input->post('bedrooms') != '' && $this->input->post('beds') != '' && $this->input->post('hosting_bed_type') != '' && $this->input->post('hosting_bathrooms') != '123')
							{
								$listing = 1;
							}
							else
							{
								$listing = 0;
							}
							$this->db->where('id',$listId)->update('lys_status',array('listing'=>$listing));
$address_explode = explode(',',$this->input->post('address'));	
if(count($address_explode) < 3)
{
echo "1";
}
else
{
			$this->db->where('id', $listId);
			$this->db->update('list',$data);
			
				$result = $this->db->where('id',$listId)->get('lys_status')->row();
			
			$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
			if($total == 6)
			{
			  $data['is_enable'] = 1;
			  $data['list_pay'] = 1;
			  $this->db->where('id',$listId)->update('list',$data);
			}
					
		echo "<p>List Description Updated Successfully</p>";
	}	}
		else if($this->input->post('update_aminities'))
		{
		 $listId           = $this->input->post('list_id');
						
						$query                   = $this->db->get_where('list', array( 'id' => $listId));
		     if($query->num_rows() == 0)
				{
					echo '0';exit;
				}
			
   $amenity   = $this->input->post('amenities');
			$aCount    = count($amenity);
			
			$amenities = '';	
			if(is_array($amenity))
			{
				if(count($amenity) > 0)
				{
					$i = 1;
					foreach($amenity as $value)
					{
							if($i == $aCount) $comma = ''; else $comma = ',';
							
							$amenities .= $value.$comma;
							$i++;
					}
				}
			}
			
		if($amenities != '')
		{
		$updateData['amenities'] = $amenities;
		}
if($amenities == '')
{
echo "<p>Sorry, You have to select atleast one!</p>";exit;
}
												
		$updateKey = array('id' => $listId);									
		$this->Rooms_model->update_list($updateKey, $updateData);

			echo "<p>List Amenities Updated Successfully</p>";
		}
	 else if($this->input->post('update_photo'))
		{
			//echo 'test';exit;
				$listId           = $this->input->post('list_id');
				
				$images           = $this->input->post('image');
		        $is_main          = $this->input->post('is_main');
				
				$fimages          = $this->Gallery->get_imagesG($listId);
				if($is_main != '')
				{
				foreach($fimages->result() as $row)
				{
				 if($row->id == $is_main)
				   $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 1));
					else
					  $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 0));
				}
				}
				
				if(!empty($images))
				{
					foreach($images as $key=>$value)
					{
					 $name = $this->Gallery->get_imagesG(NULL, array('id' => $value))->row()->name;
					 $pieces                   = explode(".", $name);
	//unlink(dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$name);
	$name1=$pieces[0];
				     unlink($this->path.'/'.$listId.'/'.$name);
					 unlink($this->path.'/'.$listId.'/'.$name1.'_crop');
				     unlink($this->path.'/'.$listId.'/'.$name.'_home.jpg');
					 unlink($this->path.'/'.$listId.'/'.$name.'_home.jpg_watermark.jpg');
					 unlink($this->path.'/'.$listId.'/'.$name.'_watermark.jpg');
						$delete_photo = 1;	
						$conditions = array("id" => $value);
						$this->Common_model->deleteTableData('list_photo', $conditions);
					}
					//echo 'Deleted Successfully';exit;
				}
				
				$room_id = $listId;
				
		$filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id;
		
		if($this->dx_auth->get_user_id() == '')
		{
			echo 'logout';exit;
		}
		
	    if(!file_exists($filename)) 
		{
     	mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id, 0777, true);
		}
			  
	  if(isset($_FILES["userfile"]["name"]))
					{
       foreach ($_FILES["userfile"]["error"] as $key => $error) {
 	
	if($this->dx_auth->get_user_id() == '')
		{
			echo 'logout';exit;
		}
				$tmp_name = $_FILES["userfile"]["tmp_name"][$key];
				$name = str_replace(' ','_',$_FILES["userfile"]["name"][$key]);
					
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
						$image_name    = random_string('alnum',8).'.'.$ext;
					if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')	
					{				 
					if( move_uploaded_file($tmp_name, "images/{$room_id}/{$image_name}"))
					{
						if($this->dx_auth->get_user_id() == '')
						{
						echo 'logout';exit;
						}
						if($ext == 'png')
					{
						$image = imagecreatefrompng("images/{$room_id}/{$image_name}");
    					imagejpeg($image, "images/{$room_id}/{$image_name}", 100);
    					imagedestroy($image);
					}
							
			      //$image_name    = $name;
				        $insertData['list_id']    = $room_id;
      	                $insertData['name']       = $image_name;
						/*$insertData['image']      = base_url().'images/'.$insertData['list_id'].$image_name.'/375*375';     
						$insertData['resize']     = base_url().'images/'.$insertData['list_id'].$image_name.'/375*375';            
						$insertData['resize1']    = base_url().'images/'.$insertData['list_id'].$image_name.'/375*375'; 
						$insertData['created']    = local_to_gmt();
												
						$check = $this->db->where('list_id',$room_id)->get('list_photo');
						
						$photo_status['photo'] = 1;
			            $this->db->where('id',$room_id)->update('lys_status',$photo_status);
						
						if($check->num_rows() == 0)
						{
							$insertData['is_featured'] = 1;
				     	}
                        else 
                        {
	                       $insertData['is_featured'] = 0;
                        }
						if($image_name != '')
						$this->Common_model->insertData('list_photo', $insertData);
                        $this->watermark($room_id,$image_name);
						$this->watermark1($room_id,$image_name);
					}
					}
   else if(count($_FILES["userfile"]["error"]) == 1) {
   	if($_FILES["userfile"]["name"][0] != '')
	{
	$rimages = $this->Gallery->get_imagesG($listId);
					$i = 1;
					$replace = '<ul class="clearfix">';
					foreach ($rimages->result() as $rimage)
					{	
					  if($rimage->is_featured == 1)
							 $checked = 'checked="checked"'; 
							else
							 $checked = ''; 
								
					    $url = base_url().'images/'.$rimage->list_id.'/'.$rimage->name;
									$replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage->id.'" /></label>';
									$replace .= '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$rimage->id.'" /></p><div class="panel-body panel-condensed"><textarea rows="3"  class="highlight" name="highlight" id="highlight_'.$rimage->id.'" placeholder="'.translate_admin("What are the highlights of this photo?").'" class="input-large" onkeyup="highlight1('.$rimage->id.')">'.trim($rimage->highlights).'</textarea></div></li>';
								$i++;
					}
					$replace .= '</ul>';
					if($this->dx_auth->get_user_id() == '')
		{
			echo 'logout';exit;
		}
			$result = $this->db->where('id',$listId)->get('lys_status')->row();
			
			$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
			if($total == 6)
			{
			  $data['is_enable'] = 1;
			  $data['list_pay'] = 1;
			  $this->db->where('id',$listId)->update('list',$data);
			}
					
				 echo $replace."#"."<p>Please upload correct file.</p>";exit;
}
   }
					}
					}
					$rimages = $this->Gallery->get_imagesG($listId);
					$i = 1;
					$replace = '<ul class="clearfix">';
					if($rimages->num_rows() == 0)
					{
						$this->db->where('id',$listId)->update('lys_status',array('photo'=>0));
					}
					else
						{
							$this->db->where('id',$listId)->update('lys_status',array('photo'=>1));
							$lys_status = $this->db->where('id',$listId)->get('lys_status');
				
				foreach($lys_status->result() as $row)
				{
				$total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
				$total_status = 6 - $total_status;
				}
					if($total_status == 0)
					{
					$this->db->where('id',$listId)->update('list',array('is_enable'=>1,'list_pay'=>1));	
					}	
						}
					foreach ($rimages->result() as $rimage)
					{		
					  if($rimage->is_featured == 1)
							 $checked = 'checked="checked"'; 
							else
							 $checked = ''; 
								
					    			$url = base_url().'images/'.$rimage->list_id.'/'.$rimage->name;
									$replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage->id.'" /></label>';
									$replace .= '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$rimage->id.'" /></p><div class="panel-body panel-condensed"><textarea rows="3" cols="18"  class="highlight" name="highlight" id="highlight_'.$rimage->id.'" placeholder="'.translate_admin("What are the highlights of this photo?").'" class="input-large" onkeyup="highlight1('.$rimage->id.')">'.trim($rimage->highlights).'</textarea></div></li>';
								$i++;
					}
					$replace .= '</ul>';
					if($this->dx_auth->get_user_id() == '')
		{
			echo 'logout';exit;
		}

			$result = $this->db->where('id',$listId)->get('lys_status')->row();
			
			$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
			if($total == 6)
			{
			  $data['is_enable'] = 1;
			  $data['list_pay'] = 1;
			  $this->db->where('id',$listId)->update('list',$data);
			}
					if(isset($delete_photo))
					{
					 echo $replace."#"."<p>List Photo's Deleted successfully</p>";exit;
					}
					else
					{
					 echo $replace."#"."<p>List Photo's Updated successfully</p>";
					}
		}
		else if($this->input->post('update_price'))
		{
		$listId           = $this->input->post('list_id');
		
		$query                   = $this->db->get_where('list', array( 'id' => $listId));
		     if($query->num_rows() == 0)
				{
					echo '0';exit;
				}
			
			$data = array(
											'currency'   => $this->input->post('currency'),
											'night'      => $this->input->post('nightly'),
											'week'       => $this->input->post('weekly'),
											'month'      => $this->input->post('monthly'),
											'addguests'  => $this->input->post('extra'),
											'guests'     => $this->input->post('guests'),
											'security'   => $this->input->post('security'),
											'cleaning'   => $this->input->post('cleaning'),
											'currency'   => $this->input->post('currency')
											);
											
			$dataP = array();
			$dataP['price'] =  $this->input->post('nightly');
			$dataP['currency'] = $this->input->post('currency');

			$this->db->where('id', $listId);
			$this->db->update('price', $data);
			
			$this->db->where('id',$listId)->update('lys_status',array('price'=>1));
			
			$this->db->where('id', $listId);
			$this->db->update('list', $dataP);
			
				$result = $this->db->where('id',$listId)->get('lys_status')->row();
			
			$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
			if($total == 6)
			{
			  $data1['is_enable'] = 1;
			  $data1['list_pay'] = 1;
			  $this->db->where('id',$listId)->update('list',$data1);
			}
			
			echo "<p>List Price Updated successfully</p>";
		}
		else
		{
		redirect_admin('lists');
		}
		}
	}*/
	
	function managelist()
    {
        
    require_once APPPATH.'libraries/cloudinary/autoload.php';

\Cloudinary::config(array( 
  "cloud_name" => cdn_name, 
  "api_key" => cdn_api, 
  "api_secret" => cloud_s_key
));

        $check = $this->input->post('check');
        
        if($this->input->get('id') != '')
        {
            $check = $this->input->get('id');
        }
        
        if($check == '' && $this->input->post() != '' && $this->input->post('add_desc') == '' && $this->input->post('update_overview') == '' && $this->input->post('update_desc') == '' && $this->input->post('update_price') == '' && $this->input->post('import') == '' && $this->input->post('update_aminities') == '' && $this->input->post('update_photo') == '')
        {
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
            redirect_admin('lists');
        }
        
        if($check == '' && $this->input->post('list_id') == '' && $this->input->get('month') == '' )
        {
            $check = $this->uri->segment(4);
                /* if(empty($check))
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                        redirect_admin('lists');
                    }
                */
                $query                   = $this->db->get_where('list', array( 'id' => $check));
                $data['result']          = $query->row();
                
                $data['country_list'] = $this->db->get('country');
                
                $list = $this->db->where('id',$check)->get('list')->row();
                
                $data['lat']              = $list->lat;
                $data['long']             = $list->long;
            
                $data['city']    = $list->city;
                $data['state']    = $list->state;
                $data['country']        = $list->country;
                $data['street_address']     = $list->street_address;
                $data['optional_address']       = $list->optional_address;  
                $data['zip_code']       = $list->zip_code;
                
                $data['currency_result'] = $this->db->where('status',1)->get('currency');
                
                $data['currency_value'] = $this->db->where('id',$check)->get('list')->row()->currency;
                
                if($query->num_rows() == 0)
                {
                    //$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This list already deleted.')));
                    redirect_admin('lists');
                }
                
            $check_calendar = $this->db->where('id',$check)->get('list');
            $param = $check;
            if($check_calendar->num_rows() == 0)
            {
                redirect('info');
            }
            $list_id = $param;
            $day     = date("d");
            $month   = $this->input->get('month', TRUE);
            $year    = $this->input->get('year', TRUE);
            
            if (!empty($month) && !empty($year))
            {
              $month   = $month;
              $year    = $year;
            }
            else
            {
              $month   = date("m");
              $year    = date("Y");
            }
            
            if($month > 12 || $month < 1)
            {
              $month = date("m");
            }
            else
            {
              $month = $month;
            }
            
            if(($year == ($year - 3)) || ($year == ($year + 3)))
            {
              redirect('calendar/single/'.$list_id.'?month='.$month.'&year='.date("Y"));
            }
            if($this->Common_model->getTableData('list', array( 'id' => $param))->num_rows()==0)
            {
                redirect('info/deny');
            }
         $row                 = $this->Common_model->getTableData('list', array( 'id' => $param))->row();
   $data['list_title']  = $row->title;
            $data['list_price']  = $row->price;
            
            $conditions          = array('list_id' => $list_id);
            $this->load->model('Trips_model');
            $data['result_cal']      = $this->Trips_model->get_calendar($conditions)->result();
            
            $data['list_id']     = $list_id;
            $data['day']         = $day;
            $data['month']       = $month;
            $data['year']        = $year;
            //Remove incorrect list from seasonal price
            $query=$this->Common_model->getTableData('seasonalprice', array( 'list_id' => $list_id));
            $res=$query->result_array();
            foreach($res as $seasonal)
            {
                $starttime      = $seasonal['start_date'];  
                $gmtTime        = $seasonal['end_date'];
                if($gmtTime<$starttime)     
                {   
                    $list_id    = $seasonal['list_id'];
                    $remove_query="delete from seasonalprice where list_id='".$list_id."' and start_date='".$seasonal['start_date']."' and end_date='".$seasonal['end_date']."'";
                    $remove_exe= $this->db->query($remove_query);
                }
            }   
                
                
                $data['amnities']        = $this->Rooms_model->get_amnities();
                
                $data['property_types']  = $this->db->order_by('id','asc')->get('property_type');
                $data['lys_status'] = $this->db->where('id',$check)->get('lys_status')->row();
                $query3                  = $this->db->get_where('price',array('id' => $check));
                $data['price']           = $query3->row(); 
                
                $data['list_images']     = $this->Gallery->get_imagesG($check);
                
                $data['cancellation_policy_data'] = $this->Common_model->getTableData('cancellation_policy',array('is_standard'=>"1"));
                
                $data['message_element'] = "administrator/view_edit_list";
                $this->load->view('administrator/admin_template', $data);
                
        }
else{
        if($this->input->post('update_overview'))
        {
            
            extract($this->input->post());
            $data['title'] = $title;
        //  print_r($data['title']);exit;
            $data['desc'] = $desc;
            $this->db->where('id',$id)->update('list',$data);
            
            $data_lys['title'] = 1;
            $data_lys['overview'] = 1;
            $data_lys['summary'] = 1;
            $this->db->where('id',$id)->update('lys_status',$data_lys);
            
            $result = $this->db->where('id',$id)->get('lys_status')->row();
            
            $total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
    
            if($total == 6)
            {
              $data['is_enable'] = 1;
              $data['list_pay'] = 1;
              $this->db->where('id',$id)->update('list',$data);
            }
            
            echo '<p>Updated successfully</p>';
        }
        else if($this->input->post('delete') || $this->uri->segment(4) == 'delete')
        {
                    
                
            //india
         try{

             

             $image=\Cloudinary\Uploader::destroy("images/".$id."/".$id, array(

                                    "invalidate" => TRUE,));
                              // print_r($image);                                                                    

                            }

                   catch (Exception $e) {

                   $error = $e->getMessage();
                   // print_r($error);

                   } 
                     
            if($this->input->get('id') != '')
            {
                $check = array();
                $check[0] = $this->input->get('id');
            }
            
            if(empty($check))
            {
             $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                redirect_admin('lists');
            }
        foreach($check as $id)
        {
        $reservation_status=$this->Common_model->getTableData( 'reservation', array('list_id' => $id, 'status !=' => '10' ));
        if($reservation_status->num_rows() > 0)
        {
        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, The selected listing is in process or resevered by someone')));
        redirect_admin('lists');
        }
        else
        {
         
            
        $this->db->delete('list', array('id' => $id));
        $this->db->delete('list_photo', array('id' => $id)); 
        $this->db->delete('price', array('id' => $id)); 
        $this->db->delete('calendar', array('list_id' => $id)); 
        $this->db->delete('messages', array('list_id' => $id));
        $this->db->delete('referrals_booking', array('list_id' => $id));
        $this->db->delete('reviews', array('list_id' => $id));
        $this->db->delete('statistics', array('list_id' => $id));
        $this->db->delete('contacts', array('list_id' => $id));
        $this->db->delete('reservation', array('list_id' => $id));
        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate_admin('Rooms deleted successfully.')));
        }
        }
        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('List Deleted Successfully')));
        redirect_admin('lists');
        }
        else if($this->input->post('featured'))
        {
            
            if(empty($check))
            {
             $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                redirect_admin('lists');
            }
        
            foreach($check as $c)
            {
                $lys_status = $this->db->where('id',$c)->get('lys_status');
                
                foreach($lys_status->result() as $row)
                {
                $total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
                $total_status = 6 - $total_status;
                }
                if($total_status == 0)
                $this->Common_model->updateTableData('list',$c,NULL,array("is_featured" => '1'));
                else
                {
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry! you're choosed incompleted list.")));
                redirect_admin('lists');
                }
            }
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Updated Successfully')));
             redirect_admin('lists');
        }
        else if($this->input->post('unfeatured'))
        {
            if(empty($check))
            {
             $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                redirect_admin('lists');
            }
                foreach($check as $c)
                {
                    $sql="update list set is_featured=0 where id='".$c."'";
                    $this->db->query($sql); 
                }
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('List unfeatured Successfully')));
             redirect_admin('lists');
        }
        else if($this->input->post('edit') || $this->input->get('id'))
        {
            $check = array();
            if($this->input->get('id') != '')
        {
            $check[0] = $this->input->get('id');
        }
                    if(empty($check))
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                        redirect_admin('lists');
                    }
                if(count($check) == 1)
                {
                $query                   = $this->db->get_where('list', array( 'id' => $check[0]));
                $data['result']          = $query->row();
                
                $data['currency_result'] = $this->db->where('status',1)->get('currency');
                
                $data['currency_value'] = $this->db->where('id',$check[0])->get('list')->row()->currency;
                
                if($query->num_rows() == 0)
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This list already deleted.')));
                    redirect_admin('lists');
                }
                
                $check_calendar = $query;
                
            $param = $check[0];
            if($check_calendar->num_rows() == 0)
            {
                redirect('info');
            }
            $list_id = $param;
            $day     = date("d");
            $month   = $this->input->get('month', TRUE);
            $year    = $this->input->get('year', TRUE);
            
            if (!empty($month) && !empty($year))
            {
            $month   = $month;
            $year    = $year;
            }
            else
            {
            $month   = date("m");
            $year    = date("Y");
            }
            
            if($month > 12 || $month < 1)
            {
              $month = date("m");
            }
            else
            {
              $month = $month;
            }
            
            if(($year == ($year - 3)) || ($year == ($year + 3)))
            {
              redirect('calendar/single/'.$list_id.'?month='.$month.'&year='.date("Y"));
            }
            if($this->Common_model->getTableData('list', array( 'id' => $param))->num_rows()==0)
            {
                redirect('info/deny');
            }
         $row                 = $this->Common_model->getTableData('list', array( 'id' => $param))->row();
   $data['list_title']  = $row->title;
            $data['list_price']  = $row->price;
            
            $conditions          = array('list_id' => $list_id);
            $this->load->model('Trips_model');
            $data['result_cal']      = $this->Trips_model->get_calendar($conditions)->result();
            
            $data['list_id']     = $list_id;
            $data['day']         = $day;
            $data['month']       = $month;
            $data['year']        = $year;
            //Remove incorrect list from seasonal price
            $query=$this->Common_model->getTableData('seasonalprice', array( 'list_id' => $list_id));
            $res=$query->result_array();
            foreach($res as $seasonal)
            {
                $starttime      = $seasonal['start_date'];  
                $gmtTime        = $seasonal['end_date'];
                if($gmtTime<$starttime)     
                {   
                    $list_id    = $seasonal['list_id'];
                    $remove_query="delete from seasonalprice where list_id='".$list_id."' and start_date='".$seasonal['start_date']."' and end_date='".$seasonal['end_date']."'";
                    $remove_exe= $this->db->query($remove_query);
                }
            }   
                
                
                
                $data['amnities']        = $this->Rooms_model->get_amnities();
                
                $data['property_types']  = $this->db->order_by('id','asc')->get('property_type');
                
                $query3                  = $this->db->get_where('price',array('id' => $check[0]));
                $data['price']           = $query3->row(); 
                
                $data['list_images']     = $this->Gallery->get_imagesG($check[0]);
                
                $data['lys_status'] = $this->db->where('id',$check[0])->get('lys_status')->row();
                
                $data['cancellation_policy_data'] = $this->Common_model->getTableData('cancellation_policy',array('is_standard'=>"1"));
                
                $data['message_element'] = "administrator/view_edit_list";
                $this->load->view('administrator/admin_template', $data);
                }
                else
                {
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please select any one list to edit!')));
                redirect_admin('lists');
                }
        }
else if($this->input->get('month'))
{
    if ($this->input->post("next")) {
    
             $db_name = $this->config->item('db');
     $db_table = "calendar";
    
    if ($this->input->post("ical_url") && ($this->input->post("ical_url") != '')) {
        
                $ical_url = trim($this->input->post("ical_url"));
                
                if (@file_get_contents(trim($this->input->post("ical_url"))) == true) {
                    $ical_content = trim($this->input->post("ical_url"));
                } else {
                    $problems[] = "Ical resource specified by url is not available.";
                }
            } else {
                $problems[] = "Resource file should be specified.";
                $data['required_msg'] = 1;
                //echo '<span style="color:red">Please Give Valid URL.</span>';
            }
    
    $check_ical = $this->db->where('url',trim($this->input->post('ical_url')))->where('list_id',$this->uri->segment(4))->get('ical_import');
    
    $log = Array();
    if($this->input->post('ical_url') != '')
    {
    if($check_ical->num_rows() == 0)
    {
                $data = array(
                            'id' =>NULL,
                            'list_id' =>$this->uri->segment(4),
                            'url'         => trim($this->input->post('ical_url')),
                            'last_sync' => date('d M Y, g:i a',gmt_to_local(time(), get_user_timezone(), false))            
                            );
                $this->Common_model->insertData('ical_import', $data);
                $ical_id = $this->db->insert_id();
                $query1= $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '{$db_name}' AND table_name = '{$db_table}' LIMIT 1");

    /*! exporting event from source into hash */
    require_once(realpath(APPPATH . '../app/views/templates/blue/rooms/codebase/class.php'));
    $exporter = new ICalExporter();
    $events = $exporter->toHash($ical_content);
    
    $success_num = 0;
    $error_num = 0;
    /*! inserting events in database */
    
    $check_tb = $this->db->select('group_id')->where('list_id',$this->uri->segment(4))->order_by('id','desc')->limit(1)->get('calendar');
    
    if($check_tb->num_rows() != 0)
    {
        $i1 = $check_tb->row()->group_id;
    }
    else {
        $i1 = 1;
    }
    if(count($events))
    {
    for ($i = $i1; $i <= $i1+count($events); $i++) 
    {
        if($i == $i1)
    $event = $events[1];
        else 
    $event  = $events[$i-$i1];
                
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
        
        $id=$this->uri->segment(4);
        
        $check_dates = $this->db->where('list_id',$this->uri->segment(4))->where('booked_days',strtotime($startdate1))->get('calendar');
        
        if($check_dates->num_rows() != 0)
        {
            
        }
        else { 
            
            $data = array(
                            'id' =>NULL,
                            'list_id' => $this->uri->segment(4),
                            'group_id' => $i,
                            'availability'        => "Booked",
                            'value'           => 0,
                            'currency'  => "EUR",
                            'notes' => "Not Available",                      //   $event["text"]
                            'style'     => $direct,
                            'booked_using'  => $ical_id,
                            'booked_days'   =>strtotime($startdate1),
                            'created'   => strtotime($created)
                
                            );
                            
                            $this->Common_model->insertData('calendar', $data);
                            
        }
    
                        $abc =  $event["start_date"];
                        $newdate = strtotime ( '+1 day' , strtotime ( $abc ) ) ;
                        $event["start_date"]=date("m/d/Y", $newdate);
    }       
    
            $success_num++;
        
    }//for loop end
    $data['success_num'] = $success_num;
    $log = Array("text" => "{$success_num} Booking were inserted successfully.", "type" => "Success");
    }
    else
    {
    $log = Array("text" =>"No data in given URL.", "type" => "Error");
    }
    }           
    else {
        $ical_id = $check_ical->row()->id;
        /*! exporting event from source into hash */
    require_once(realpath(APPPATH . '../app/views/templates/blue/rooms/codebase/class.php'));
    $exporter = new ICalExporter();
    $events = $exporter->toHash($ical_content);
    
    $success_num = 0;
    $error_num = 0;
    /*! inserting events in database */
    
    $check_tb = $this->db->select('group_id')->where('list_id',$this->uri->segment(4))->order_by('id','desc')->limit(1)->get('calendar');
    
    if($check_tb->num_rows() != 0)
    {
        $i1 = $check_tb->row()->group_id;
    }
    else {
        $i1 = 1;
    }
    
    for ($i = $i1; $i <= $i1+count($events); $i++) 
    {
        if($i == $i1)
    $event = $events[1];
        else 
    $event  = $events[$i-$i1];
                
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
        
        $id=$this->uri->segment(4);
        
        $check_dates = $this->db->where('list_id',$this->uri->segment(4))->where('booked_days',strtotime($startdate1))->get('calendar');
        
        if($check_dates->num_rows() != 0)
        {
            
        }
        else { 
            
            $data = array(
                            'id' =>NULL,
                            'list_id' => $this->uri->segment(4),
                            'group_id' => $i,
                            'availability'        => "Booked",
                            'value'           => 0,
                            'currency'  => "EUR",
                            'notes' => "Not Available",                      //   $event["text"]
                            'style'     => $direct,
                            'booked_using'  => $ical_id,
                            'booked_days'   =>strtotime($startdate1),
                            'created'   => strtotime($created)
                
                            );
                            
                            $this->Common_model->insertData('calendar', $data);
                            
        }
    
                        $abc =  $event["start_date"];
                        $newdate = strtotime ( '+1 day' , strtotime ( $abc ) ) ;
                        $event["start_date"]=date("m/d/Y", $newdate);
    }       
    
            $success_num++;
        
    }//for loop end
    
    $update_sync['last_sync'] = date('d M Y, g:i a',gmt_to_local(time(), get_user_timezone(), false));
        
    $this->db->where('id',$ical_id)->update('ical_import',$update_sync);
        
        $log = Array("text" => "This URL were already imported.", "type" => "Error");
    }   
    $data['log'] = $log;
    }
}
                $query                   = $this->db->get_where('list', array( 'id' => $this->uri->segment(4)));
                $data['result']          = $query->row();
                
                $data['currency_result'] = $this->db->where('status',1)->get('currency');
                
                $data['currency_value'] = $this->db->where('id',$this->uri->segment(4))->get('list')->row()->currency;
                
                if($query->num_rows() == 0)
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This list already deleted.')));
                    redirect_admin('lists');
                }
                
                $check_calendar = $query;
                
            $param = $this->uri->segment(4);
            if($check_calendar->num_rows() == 0)
            {
                redirect('info');
            }
            $list_id = $param;
            $day     = date("d");
            $month   = $this->input->get('month', TRUE);
            $year    = $this->input->get('year', TRUE);
            
            if (!empty($month) && !empty($year))
            {
            $month   = $month;
            $year    = $year;
            }
            else
            {
            $month   = date("m");
            $year    = date("Y");
            }
            
            if($month > 12 || $month < 1)
            {
              $month = date("m");
            }
            else
            {
              $month = $month;
            }
            
            if(($year == ($year - 3)) || ($year == ($year + 3)))
            {
              redirect('calendar/single/'.$list_id.'?month='.$month.'&year='.date("Y"));
            }
            if($this->Common_model->getTableData('list', array( 'id' => $param))->num_rows()==0)
            {
                redirect('info/deny');
            }
         $row                 = $this->Common_model->getTableData('list', array( 'id' => $param))->row();
   $data['list_title']  = $row->title;
            $data['list_price']  = $row->price;
            
            $conditions          = array('list_id' => $list_id);
            $this->load->model('Trips_model');
            $data['result_cal']      = $this->Trips_model->get_calendar($conditions)->result();
            
            $data['list_id']     = $list_id;
            $data['day']         = $day;
            $data['month']       = $month;
            $data['year']        = $year;
            //Remove incorrect list from seasonal price
            $query=$this->Common_model->getTableData('seasonalprice', array( 'list_id' => $list_id));
            $res=$query->result_array();
            foreach($res as $seasonal)
            {
                $starttime      = $seasonal['start_date'];  
                $gmtTime        = $seasonal['end_date'];
                if($gmtTime<$starttime)     
                {   
                    $list_id    = $seasonal['list_id'];
                    $remove_query="delete from seasonalprice where list_id='".$list_id."' and start_date='".$seasonal['start_date']."' and end_date='".$seasonal['end_date']."'";
                    $remove_exe= $this->db->query($remove_query);
                }
            }   
                
                $data['amnities']        = $this->Rooms_model->get_amnities();
                
                $data['property_types']  = $this->db->order_by('id','asc')->get('property_type');
                
                $query3                  = $this->db->get_where('price',array('id' => $list_id));
                $data['price']           = $query3->row(); 
                
                $data['list_images']     = $this->Gallery->get_imagesG($list_id);
                
                $data['lys_status'] = $this->db->where('id',$list_id)->get('lys_status')->row();
                
                $data['cancellation_policy_data'] = $this->Common_model->getTableData('cancellation_policy',array('is_standard'=>"1"));
                
                $data['message_element'] = "administrator/view_edit_list";
                $this->load->view('administrator/admin_template', $data);
                
}
        else if($this->input->post('add_desc'))
        {
         $listId           = $this->input->post('id');
    
                $query                   = $this->db->get_where('list', array( 'id' => $listId));
             if($query->num_rows() == 0)
                {
                    echo '0';exit;
                }
            
            $check_policy = $this->Common_model->getTableData('cancellation_policy',array('id'=>$this->input->post('cancellation_policy')));
            
            if($check_policy->num_rows() == 0)
            {
                echo 'policy';exit;
            }
            
            $data = array(
                            'property_id'           => $this->input->post('property_id'),
                            'room_type'             => $this->input->post('room_type'),
                            'capacity'              => $this->input->post('capacity'),
                            'bedrooms'              => $this->input->post('bedrooms'),
                            'beds'                  => $this->input->post('beds'),
                            'bed_type'              => $this->input->post('hosting_bed_type'),
                            'bathrooms'             => $this->input->post('hosting_bathrooms'),
                            'house_rule'            => $this->input->post('manual'),
                            'cancellation_policy'   => $this->input->post('cancellation_policy'),
                            
                            );
                            
                            extract($this->input->post());
                            
                            if($this->input->post('beds') != '')
                            {
                                $data_lys['beds'] = 1;
                            }
                            
                            if($this->input->post('hosting_bathrooms') != '123')
                            {
                                $data_lys['bathrooms'] = 1;
                            }
                            
                            if($this->input->post('bedrooms') != '' && $this->input->post('beds') != '' && $this->input->post('hosting_bed_type') != '' && $this->input->post('hosting_bathrooms') != '123')
                            {
                                $data_lys['listing'] = 1;
                            }
                            else
                            {
                                $data_lys['listing'] = 0;
                            }
                            $this->db->where('id',$listId)->update('lys_status',$data_lys);

            $this->db->where('id', $listId);
            $this->db->update('list',$data);
            
            $result = $this->db->where('id',$listId)->get('lys_status')->row();
            
            $total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
    
            if($total == 6)
            {
              $data['is_enable'] = 1;
              $data['list_pay'] = 1;
              $this->db->where('id',$listId)->update('list',$data);
            }
                    
        echo "<p>List Description Updated Successfully</p>";    
      }
else if($this->input->post('update_desc'))
        {
         $listId           = $this->input->post('id');
                        
                $query                   = $this->db->get_where('list', array( 'id' => $listId));
             if($query->num_rows() == 0)
                {
                    echo '0';exit;
                }
                
            $check_policy = $this->Common_model->getTableData('cancellation_policy',array('id'=>$this->input->post('cancellation_policy')));
            
            if($check_policy->num_rows() == 0)
            {
                echo 'policy';exit;
            }

            if($this->input->post('title') == '')
            {
                $this->db->where('id',$listId)->update('lys_status',array('title'=>0,'overview'=>0));
            }
            else
                {
                    if($this->input->post('desc') != '')
                    {
                         $this->db->where('id',$listId)->update('lys_status',array('title'=>1,'overview'=>1,'summary'=>1));
                        $lys_status = $this->db->where('id',$listId)->get('lys_status');
                
                foreach($lys_status->result() as $row)
                {
                $total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
                $total_status = 6 - $total_status;
                }
                    if($total_status == 0)
                    {
                    $this->db->where('id',$listId)->update('list',array('is_enable'=>1,'list_pay'=>1)); 
                    }   
                    }
                    else
                  $this->db->where('id',$listId)->update('lys_status',array('overview'=>0,'summary'=>0));           
                }
            $data = array(
                            'property_id'           => $this->input->post('property_id'),
                            'room_type'             => $this->input->post('room_type'),
                            'title'                 => $this->input->post('title'),
                            'desc'                  => $this->input->post('desc'),
                            'capacity'              => $this->input->post('capacity'),
                            'bedrooms'              => $this->input->post('bedrooms'),
                            'beds'                  => $this->input->post('beds'),
                            'bed_type'              => $this->input->post('hosting_bed_type'),
                            'bathrooms'             => $this->input->post('hosting_bathrooms'),
                            'house_rule'            => $this->input->post('manual'),
                            'cancellation_policy'   => $this->input->post('cancellation_policy'),
                            'address'               => $this->input->post('address'),
                            'lat'                   => $this->input->post('hidden_lat'),
                            'long'                   => $this->input->post('hidden_lng'),
                            'street_address'                   => $this->input->post('lys_street_address'),
                            'city'                   => $this->input->post('city'),
                            'state'                   => $this->input->post('state'),
                            'zip_code'                   => $this->input->post('zipcode'),
                            'country'                   => $this->input->post('country')
                            );
                            extract($this->input->post());
                            if($this->input->post('bedrooms') != '' && $this->input->post('beds') != '' && $this->input->post('hosting_bed_type') != '' && $this->input->post('hosting_bathrooms') != '123')
                            {
                                $listing = 1;
                            }
                            else
                            {
                                $listing = 0;
                            }
                            $this->db->where('id',$listId)->update('lys_status',array('listing'=>$listing));
$address_explode = explode(',',$this->input->post('address'));  
if(count($address_explode) < 3)
{
echo "1";
}
else
{
            $this->db->where('id', $listId);
            $this->db->update('list',$data);
            
                $result = $this->db->where('id',$listId)->get('lys_status')->row();
            
            $total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
    
            if($total == 6)
            {
              $data['is_enable'] = 1;
              $data['list_pay'] = 1;
              $this->db->where('id',$listId)->update('list',$data);
            }
                    
        echo "<p>List Description Updated Successfully</p>";
    }   }
        else if($this->input->post('update_aminities'))
        {
         $listId           = $this->input->post('list_id');
                        
                        $query                   = $this->db->get_where('list', array( 'id' => $listId));
             if($query->num_rows() == 0)
                {
                    echo '0';exit;
                }
            
   $amenity   = $this->input->post('amenities');
            $aCount    = count($amenity);
            
            $amenities = '';    
            if(is_array($amenity))
            {
                if(count($amenity) > 0)
                {
                    $i = 1;
                    foreach($amenity as $value)
                    {
                            if($i == $aCount) $comma = ''; else $comma = ',';
                            
                            $amenities .= $value.$comma;
                            $i++;
                    }
                }
            }
            
        if($amenities != '')
        {
        $updateData['amenities'] = $amenities;
        }
if($amenities == '')
{
echo "<p>Sorry, You have to select atleast one!</p>";exit;
}
                                                
        $updateKey = array('id' => $listId);                                    
        $this->Rooms_model->update_list($updateKey, $updateData);

            echo "<p>List Amenities Updated Successfully</p>";
        }
     else if($this->input->post('update_photo'))
        {
            //echo 'test';exit;
                $listId           = $this->input->post('list_id');
                
                $images           = $this->input->post('image');
                $is_main          = $this->input->post('is_main');
                
                $fimages          = $this->Gallery->get_imagesG($listId);
                if($is_main != '')
                {
                foreach($fimages->result() as $row)
                {
                 if($row->id == $is_main)
                   $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 1));
                    else
                      $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 0));
                }
                }
                
                if(!empty($images))
                {
                    foreach($images as $key=>$value)
                    {
                     $image_name = $this->Gallery->get_imagesG(NULL, array('id' => $value))->row()->name;
                     
                     try{

             

             $image=\Cloudinary\Uploader::destroy("images/".$value."/".$image_name, array(

                          //"public_id" => "images/".$room_id));
                          //"/".$row->name
                          "invalidate" => TRUE,));
                             // print_r($image);                                                                    

                            }

                   catch (Exception $e) {

                   $error = $e->getMessage();
                 //  print_r($error);

                   } 
                     //unlink($this->path.'/'.$listId.'/'.$image_name);
                        $delete_photo = 1;  
                        $conditions = array("id" => $value);
                        $this->Common_model->deleteTableData('list_photo', $conditions);
                    }
                    //echo 'Deleted Successfully';exit;
                }
                
                $room_id = $listId;
                
        $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id;
        
        if($this->dx_auth->get_user_id() == '')
        {
            echo 'logout';exit;
        }
        
        if(!file_exists($filename)) 
        {
        mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id, 0777, true);
        }
              
      if(isset($_FILES["userfile"]["name"]))
                    {
       foreach ($_FILES["userfile"]["error"] as $key => $error) {
    
    if($this->dx_auth->get_user_id() == '')
        {
            echo 'logout';exit;
        }
                $tmp_name = $_FILES["userfile"]["tmp_name"][$key];
                $name = str_replace(' ','_',$_FILES["userfile"]["name"][$key]);
                
                $temp = explode('.', $name);
$ext  = array_pop($temp);
$name1 = implode('.', $temp);
                try{
                $cloudimage=\Cloudinary\Uploader::upload($tmp_name,
array("public_id" => "images/".$room_id."/".$name1
));
// print_r($image);
}catch (Exception $e) {
$error = $e->getMessage();
// print_r($error); 
} 
$secureimage = @$cloudimage['secure_url'];
                    
                    $ext = pathinfo($name, PATHINFO_EXTENSION);

                                   
                    if( $secureimage!='')
                    {
                        if($this->dx_auth->get_user_id() == '')
                        {
                        echo 'logout';exit;
                        }
                        /*
                        if($ext == 'png' || $ext == 'PNG')
                                            {
                                                $image = imagecreatefrompng("images/{$room_id}/{$name}");
                                                imagejpeg($image, "images/{$room_id}/{$name}", 100);
                                                imagedestroy($image);
                                            }*/
                        
                            
                  $image_name    = $name;
                        $userid = $this->db->where('id', $room_id)->get('list')->row()->user_id;
                        $insertData['user_id'] = $userid;
                        $insertData['list_id']    = $room_id;
                        $insertData['name']       = $image_name;
                        $image = explode('.',$image_name);

                        $insertData['image']      = cdn_url_images().'images/'.$insertData['list_id'].'/'.$image_name;         
                        $insertData['resize']     = cdn_url_images().'images/'.$insertData['list_id'].'/'.$image[0].'.'.$image[1];            
                        $insertData['resize1']    = cdn_url_images().'images/'.$insertData['list_id'].'/'.$image[0].'.'.$image[1];
                        $insertData['created']    = local_to_gmt();
                                                
                        $check = $this->db->where('list_id',$room_id)->get('list_photo');
                        
                        $photo_status['photo'] = 1;
                        $this->db->where('id',$room_id)->update('lys_status',$photo_status);
                        
                        if($check->num_rows() == 0)
                        {
                            $insertData['is_featured'] = 1;
                        }
                        else 
                        {
                           $insertData['is_featured'] = 0;
                        }
                        if($image_name != '')
                        $this->Common_model->insertData('list_photo', $insertData);
                       /*
                        $this->watermark($room_id,$image_name);
                                               $this->watermark1($room_id,$image_name);
                                               $this->resize($room_id,$image_name);
                                                $this->resize1($room_id,$image_name);*/
                       
                    }
                    
  /*
   else if(count($_FILES["userfile"]["error"]) == 1) {
      if($_FILES["userfile"]["name"][0] != '')
      {
      $rimages = $this->Gallery->get_imagesG($listId);
                      $i = 1;
                      $replace = '<ul class="clearfix">';
                      foreach ($rimages->result() as $rimage)
                      {   
                        if($rimage->is_featured == 1)
                               $checked = 'checked="checked"'; 
                              else
                               $checked = ''; 
                                  
                          $url = cdn_url_images().'images/'.$rimage->list_id.'/'.$rimage->name;
                                      $replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage->id.'" /></label>';
                                      $replace .= '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$rimage->id.'" /></p><div class="panel-body panel-condensed"><textarea rows="3"  class="highlight" name="highlight" id="highlight_'.$rimage->id.'" placeholder="'.translate_admin("What are the highlights of this photo?").'" class="input-large" onkeyup="highlight1('.$rimage->id.')">'.trim($rimage->highlights).'</textarea></div></li>';
                                  $i++;
                      }
                      $replace .= '</ul>';
                      if($this->dx_auth->get_user_id() == '')
          {
              echo 'logout';exit;
          }
              $result = $this->db->where('id',$listId)->get('lys_status')->row();
              
              $total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
      
              if($total == 6)
              {
                $data['is_enable'] = 1;
                $data['list_pay'] = 1;
                $this->db->where('id',$listId)->update('list',$data);
              }
                      
                   echo $replace."#"."<p>Please upload correct file.</p>";exit;
  }
     }*/
  
                    }
                    }
                    $rimages = $this->Gallery->get_imagesG($listId);
                    $i = 1;
                    $replace = '<ul class="clearfix">';
                    if($rimages->num_rows() == 0)
                    {
                        $this->db->where('id',$listId)->update('lys_status',array('photo'=>0));
                    }
                    else
                        {
                            $this->db->where('id',$listId)->update('lys_status',array('photo'=>1));
                            $lys_status = $this->db->where('id',$listId)->get('lys_status');
                
                foreach($lys_status->result() as $row)
                {
                $total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
                $total_status = 6 - $total_status;
                }
                    if($total_status == 0)
                    {
                    $this->db->where('id',$listId)->update('list',array('is_enable'=>1,'list_pay'=>1)); 
                    }   
                        }
                    foreach ($rimages->result() as $rimage)
                    {       
                      if($rimage->is_featured == 1)
                             $checked = 'checked="checked"'; 
                            else
                             $checked = ''; 
                                
                        $url = cdn_url_images().'images/'.$rimage->list_id.'/'.$rimage->name;
                                    $replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage->id.'" /></label>';
                                    $replace .= '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$rimage->id.'" /></p><div class="panel-body panel-condensed"><textarea rows="3" cols="18"  class="highlight" name="highlight" id="highlight_'.$rimage->id.'" placeholder="'.translate_admin("What are the highlights of this photo?").'" class="input-large" onkeyup="highlight1('.$rimage->id.')">'.trim($rimage->highlights).'</textarea></div></li>';
                                $i++;
                    }
                    $replace .= '</ul>';
                    if($this->dx_auth->get_user_id() == '')
        {
            echo 'logout';exit;
        }

            $result = $this->db->where('id',$listId)->get('lys_status')->row();
            
            $total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
    
            if($total == 6)
            {
              $data['is_enable'] = 1;
              $data['list_pay'] = 1;
              $this->db->where('id',$listId)->update('list',$data);
            }
                    if(isset($delete_photo))
                    {
                         try{
                                                              $image=\Cloudinary\Uploader::destroy("images/".$room_id."/".$image_name, array(
                                                                                      //"public_id" => "images/".$room_id));
                                                       //"/".$row->name
                                                       "invalidate" => TRUE,));
                                                          // print_r($image);                                                                    
                                                                                        }
                                                                               catch (Exception $e) {
                                                                               $error = $e->getMessage();
                                               // print_r($error);
                                                                               }
                     echo $replace."#"."<p>List Photo's Deleted successfully</p>";exit;
                    }
                    else
                    {
                     echo $replace."#"."<p>List Photo's Updated successfully</p>";
                    }
        }
        else if($this->input->post('update_price'))
        {
        $listId           = $this->input->post('list_id');
        
        $query                   = $this->db->get_where('list', array( 'id' => $listId));
             if($query->num_rows() == 0)
                {
                    echo '0';exit;
                }
            
            $data = array(
                                            'currency'   => $this->input->post('currency'),
                                            'night'      => $this->input->post('nightly'),
                                            'week'       => $this->input->post('weekly'),
                                            'month'      => $this->input->post('monthly'),
                                            'addguests'  => $this->input->post('extra'),
                                            'guests'     => $this->input->post('guests'),
                                            'security'   => $this->input->post('security'),
                                            'cleaning'   => $this->input->post('cleaning'),
                                            'currency'   => $this->input->post('currency')
                                            );
                                            
            $dataP = array();
            $dataP['price'] =  $this->input->post('nightly');
            $dataP['currency'] = $this->input->post('currency');

            $this->db->where('id', $listId);
            $this->db->update('price', $data);
            
            $this->db->where('id',$listId)->update('lys_status',array('price'=>1));
            
            $this->db->where('id', $listId);
            $this->db->update('list', $dataP);
            
                $result = $this->db->where('id',$listId)->get('lys_status')->row();
            
            $total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
    
            if($total == 6)
            {
              $data1['is_enable'] = 1;
              $data1['list_pay'] = 1;
              $this->db->where('id',$listId)->update('list',$data1);
            }
            
            echo "<p>List Price Updated successfully</p>";
        }
        else
        {
        redirect_admin('lists');
        }
        }
    }
	
	
function photo_highlight()
{
	extract($this->input->post());
	$this->db->where('id',$photo_id)->set('highlights',$msg)->update('list_photo');
	echo 'Success';exit;
}
	function watermark($list_id,$image_name)
	{
   $image_path =  dirname($_SERVER['SCRIPT_FILENAME']);
  
  $main_imgc		= $image_path."/images/$list_id/$image_name"; // main big photo / picture
  
// using the function to crop an image
$source_image = $main_imgc;
$main_img_ext = explode('.', $image_name);
$main_imgc = $image_path."/images/$list_id/$main_img_ext[0]";
$target_image = $main_imgc.'_crop.jpg';

$return = $this->resize_image($source_image, $target_image); 

if($return != 1)
{
	exit;
}
$main_img = $target_image;
$logo = $this->db->get_where('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
$watermark_img	= $image_path."/images/$logo"; // use GIF or PNG, JPEG has no tranparency support
$padding 		= 3;     // distance to border in pixels for watermark image
$opacity		= 50;	// image opacity for transparent watermark

$watermark 	    = imagecreatefrompng($watermark_img); // create watermark
$image_water 	= imagecreatefromjpeg($main_img); // create main graphic

if(!$image_water || !$watermark) die("Error: main image or watermark could not be loaded!");


$watermark_size 	= getimagesize($watermark_img);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$image_size 	= getimagesize($main_img);  
$dest_x 		= $image_size[0] - $watermark_width - $padding;  
$dest_y 		= $image_size[1] - $watermark_height - $padding;


// copy watermark on main image
imagecopy($image_water, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);


// print image to screen
//header("content-type: image/jpeg");   
imagejpeg($image_water,$main_imgc.'.'.$main_img_ext[1].'_watermark.jpg',100);  
//imagejpeg($image_water); 
imagedestroy($image_water);  
imagedestroy($watermark); 
return true;

	 } 

function watermark1($list_id,$image_name)
	{
   $image_path =  dirname($_SERVER['SCRIPT_FILENAME']);
  
  $main_imgc		= $image_path."/images/$list_id/$image_name"; // main big photo / picture
  
$watermark_size 	= getimagesize($main_imgc);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$config['image_library'] = 'gd2';
$config['source_image'] = $main_imgc;
$config['new_image'] = $image_path."/images/$list_id/$image_name"."_home.jpg";
$config['maintain_ratio'] = TRUE;
$config['width'] = 1355;
$config['height'] = 500;

$this->load->library('image_lib');
$this->image_lib->initialize($config);

if ( ! $this->image_lib->resize())
{
    echo $this->image_lib->display_errors();exit;
}
else {
	//echo 'resized';exit;
}
// using the function to crop an image
$main_img = $config['new_image'];
$watermark_img	= $image_path."/images/banner_black_watermark_right.png"; // use GIF or PNG, JPEG has no tranparency support
$padding 		= 0;     // distance to border in pixels for watermark image
$opacity		= 50;	// image opacity for transparent watermark

$watermark 	    = imagecreatefrompng($watermark_img); // create watermark
$image_water 	= imagecreatefromjpeg($main_img); // create main graphic

if(!$image_water || !$watermark) die("Error: main image or watermark could not be loaded!");


$watermark_size 	= getimagesize($watermark_img);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$image_size 	= getimagesize($main_img);  
$dest_x 		= $image_size[0] - $watermark_width - $padding;  
$dest_y 		= $image_size[1] - $watermark_height - $padding;

//echo $image_size[0].' - '.$watermark_width.'-'.$dest_x;
// copy watermark on main image
imagecopy($image_water, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);


// print image to screen
//header("content-type: image/jpeg");   
imagejpeg($image_water,$main_img.'_watermark.jpg',100);  
//imagejpeg($image_water); 
imagedestroy($image_water);  
imagedestroy($watermark); 

$main_img = $main_img.'_watermark.jpg';
$watermark_img	= $image_path."/images/banner_black_watermark_left.png"; // use GIF or PNG, JPEG has no tranparency support
$padding 		= 0;     // distance to border in pixels for watermark image
$opacity		= 50;	// image opacity for transparent watermark

$watermark 	    = imagecreatefrompng($watermark_img); // create watermark
$image_water 	= imagecreatefromjpeg($main_img); // create main graphic

if(!$image_water || !$watermark) die("Error: main image or watermark could not be loaded!");


$watermark_size 	= getimagesize($watermark_img);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$image_size 	= getimagesize($main_img);  
$dest_x 		= $image_size[0] - $watermark_width - $padding;  
$dest_y 		= $image_size[1] - $watermark_height - $padding;

//echo $image_size[0].' - '.$watermark_width.'-'.$dest_x;
// copy watermark on main image
imagecopy($image_water, $watermark, 0, $dest_y, 0, 0, $watermark_width, $watermark_height);


// print image to screen
//header("content-type: image/jpeg");   
imagejpeg($image_water,$main_img,100);  
//imagejpeg($image_water); 
imagedestroy($image_water);  
imagedestroy($watermark); 

return true;

	 } 

	/**
 * Resize Image
 *
 * Takes the source image and resizes it to the specified width & height or proportionally if crop is off.
 * @access public
 * @author Jay Zawrotny <jayzawrotny@gmail.com>
 * @license Do whatever you want with it.
 * @param string $source_image The location to the original raw image.
 * @param string $destination_filename The location to save the new image.
 * @param int $width The desired width of the new image
 * @param int $height The desired height of the new image.
 * @param int $quality The quality of the JPG to produce 1 - 100
 * @param bool $crop Whether to crop the image or not. It always crops from the center.
 */
function resize_image($source_image, $destination_filename, $width = 375, $height = 375, $quality = 70, $crop = false)
{

        if( ! $image_data = getimagesize( $source_image ) )
        {
                return false;
        }

        switch( $image_data['mime'] )
        {
                case 'image/gif':
                        $get_func = 'imagecreatefromgif';
                        $suffix = ".gif";
                break;
                case 'image/jpeg';
                        $get_func = 'imagecreatefromjpeg';
                        $suffix = ".jpg";
                break;
                case 'image/png':
                        $get_func = 'imagecreatefrompng';
                        $suffix = ".png";
                break;
        }

        $img_original = call_user_func( $get_func, $source_image );
        $old_width = $image_data[0];
        $old_height = $image_data[1];
        $new_width = $width;
        $new_height = $height;
        $src_x = 0;
        $src_y = 0;
        $current_ratio = round( $old_width / $old_height, 2 );
        $desired_ratio_after = round( $width / $height, 2 );
        $desired_ratio_before = round( $height / $width, 2 );

      //  if( $old_width < $width || $old_height < $height )
      //  {
                /**
                 * The desired image size is bigger than the original image. 
                 * Best not to do anything at all really.
                 */
      //          return false;
     //   }


        /**
         * If the crop option is left on, it will take an image and best fit it
         * so it will always come out the exact specified size.
         */
        if( $crop )
        {
                /**
                 * create empty image of the specified size
                 */
                $new_image = imagecreatetruecolor( $width, $height );

                /**
                 * Landscape Image
                 */
                if( $current_ratio > $desired_ratio_after )
                {
                        $new_width = $old_width * $height / $old_height;
                }

                /**
                 * Nearly square ratio image.
                 */
                if( $current_ratio > $desired_ratio_before && $current_ratio < $desired_ratio_after )
                {
                        if( $old_width > $old_height )
                        {
                                $new_height = max( $width, $height );
                                $new_width = $old_width * $new_height / $old_height;
                        }
                        else
                        {
                                $new_height = $old_height * $width / $old_width;
                        }
                }

                /**
                 * Portrait sized image
                 */
                if( $current_ratio < $desired_ratio_before  )
                {
                        $new_height = $old_height * $width / $old_width;
                }

                /**
                 * Find out the ratio of the original photo to it's new, thumbnail-based size
                 * for both the width and the height. It's used to find out where to crop.
                 */
                $width_ratio = $old_width / $new_width;
                $height_ratio = $old_height / $new_height;

                /**
                 * Calculate where to crop based on the center of the image
                 */
                $src_x = floor( ( ( $new_width - $width ) / 2 ) * $width_ratio );
                $src_y = round( ( ( $new_height - $height ) / 2 ) * $height_ratio );
        }
        /**
         * Don't crop the image, just resize it proportionally
         */
        else
        {
                if( $old_width > $old_height )
                {
                        $ratio = max( $old_width, $old_height ) / max( $width, $height );
                }else{
                        $ratio = max( $old_width, $old_height ) / min( $width, $height );
                }

                $new_width = $old_width / $ratio;
                $new_height = $old_height / $ratio;

                $new_image = imagecreatetruecolor( $new_width, $new_height );
        }

        /**
         * Where all the real magic happens
         */
        imagecopyresampled( $new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height );

        /**
         * Save it as a JPG File with our $destination_filename param.
         */
          /*  $image_path =  dirname($_SERVER['SCRIPT_FILENAME']);
  
  $destination_filename		= $image_path."/images/85/crop";*/
        imagejpeg( $new_image, $destination_filename, $quality  );

        /**
         * Destroy the evidence!
         */
        imagedestroy( $new_image );
        imagedestroy( $img_original );

        /**
         * Return true because it worked and we're happy. Let the dancing commence!
         */
        return true;
} 
	public function view_all()
	{	
		//Get Groups
		 $this->load->model('aminity_model');
			$data['aminity']	=	$this->aminity_model->getaminity();
		
		//$data['area']   =   $this->place_model->getplace1();
		
		//Load View	
	 $data['message_element'] = "administrator/aminity/view_aminity";
		$this->load->view('administrator/admin_template', $data);
	   
	}

	
function view_amenity()
{


$data['message_element'] = "administrator/view_add_aminity";
	$this->load->view('administrator/admin_template', $data);

}

public function editamenity()
	{		
	
	$this->load->model('aminity_model');
	
		//Get id of the category	
	 $id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('submit'))
		{	
           	//Set rules
			$this->form_validation->set_rules('name','Amenity Name','required|trim|xss_clean');
			$this->form_validation->set_rules('desc','Amenity Description','required|trim|xss_clean');
						
			if($this->form_validation->run())
			{	
				  //prepare update data
				  $updateData                  	  	= array();	
			   $updateData['name']  		    = $this->input->post('name');
				  $updateData['description']  		     = $this->input->post('desc');
						
				  
				  $check_data = $this->db->where('id',$this->uri->segment(4))->get('amnities');
				  
				  if($check_data->num_rows() == 0)
				  {
				  	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This amenity is already deleted.')));
				  	redirect_admin('lists/view_all');
				  }
				  
				  //Edit Faq Category
				  $updateKey 							= array('amnities.id'=>$this->uri->segment(4));
				  
				  $this->aminity_model->updateaminity($updateKey,$updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Amenity updated successfully')));
				  redirect_admin('lists/view_all');
		 	} 
		} //If - Form Submission End
		
		//Set Condition To Fetch The Faq Category
		$condition = array('amnities.id'=>$id);
			
	 //Get Groups
		$data['aminity']	=	$this->aminity_model->getaminity($condition);
				  
				  if($data['aminity']->num_rows() == 0)
				  {
				  	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This amenity is already deleted.')));
				  	redirect_admin('lists/view_all');
				  }

			//Load View	
	 $data['message_element'] = "administrator/aminity/edit_aminity";
		$this->load->view('administrator/admin_template', $data);
   
	}
	public function delete_aminity()
	{	
	$this->load->model('aminity_model');
	$id = $this->uri->segment(4,0);
		
	if($id == 0)	
	{
		$getaminity	 =	$this->aminity_model->getaminity();
		$aminitylist  =   $this->input->post('aminitylist');
		if(!empty($aminitylist))
		{	
				foreach($aminitylist as $res)
				 {
					$condition = array('amnities.id'=>$res);
					$this->aminity_model->deleteaminity(NULL,$condition);
				 }
			} 
		else
		{
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please select Amenity')));
	 redirect_admin('lists/view_all');
		}
	}
	else
	{
	$condition = array('amnities.id'=>$id);
	$this->aminity_model->deleteaminity(NULL,$condition);
	}		
		//Notification message
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Amenity deleted successfully')));
		redirect_admin('lists/view_all');
	}
	
	
  function addaminity()
  {
  $aminity = $this->input->post('addaminitie'); 
  $desc = $this->input->post('desc_aminitie'); 
  if(empty($aminity) && empty($desc))
			{
			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
				redirect_admin('lists');
			}else
			{
			$nul ="NULL";
			$data = array(
											'id'         => NULL,
											'name'       => $this->input->post('addaminitie'),
											'description'=> $this->input->post('desc_aminitie')
											
											);
			$this->Common_model->insertData('amnities',$data);
		
			echo "<p>Additional Amenity added successfully</p>";
			
			}
			
  }
  
  function addamenities()
  {
  $aminity1 = $this->input->post('addaminitie'); 
  $desc1 = $this->input->post('desc_aminitie'); 
  
  //Set rules
			$this->form_validation->set_rules('addaminitie','Amenity Name','required|trim|xss_clean');
			$this->form_validation->set_rules('desc_aminitie','Amenity Description','required|trim|xss_clean');
						
			if($this->form_validation->run())
			{	
			$nul ="NULL";
			$data = array(
											'id'         => NULL,
											'name'       => $this->input->post('addaminitie'),
											'description'=> $this->input->post('desc_aminitie')
											
											);
			$this->Common_model->insertData('amnities',$data);
			
			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Amenity added successfully!')));
			redirect_admin('lists/view_all');
			
			}
			
    $data['message_element'] = "administrator/view_add_aminity";
	$this->load->view('administrator/admin_template', $data);
			
  }
function keyword_search()
{
	$keywords=$this->input->post('keywordsearch');
		
		if($keywords == '')
		{
			$keywords = $this->session->userdata('keywordsearch');
		}
		else
        {
        	$this->session->set_userdata('keywordsearch',$keywords);
        }
		
		$condition_user = "username LIKE '%".$keywords."%'"; 
				$username = $this->db->where($condition_user)->get('users');

				$userid="";
				if($username->num_rows()!=0)
				{
					foreach($username->result() as $row)
					{
						$userid .= " OR user_id=".$row->id." ";
					}
				}
				else {
					$userid=" OR user_id=0 ";
				}
				
				// Get offset and limit for page viewing
		$start = (int) $this->uri->segment(5,0);
		
		
	 // Number of record showing per page
		$row_count = 10;
		
		if($start > 0)
		   $offset			 = ($start-1) * $row_count;
		else
		   $offset			 =  $start * $row_count; 
										
		// Pagination config
		$row_count = 10;
				
		if(is_numeric($keywords))
		{
			$value=intval($keywords);			
			
			$lys_status = $this->db->get('lys_status');
				$condition = '';
				foreach($lys_status->result() as $row)
				{
				$total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
				$total_status = 6 - $total_status;
				
				if($total_status != 0)
				{
					if($keywords == $total_status)
				{
					$condition .= "id = ".$row->id." or ";
				}
				
				}
								
				}
				
				$condition .= "id = ".$keywords." or capacity = $keywords or price = $keywords";
			
			$this->db->where($condition);
		$result = $this->db->get('list', $row_count, $offset);
		$this->db->where($condition);
		$result1 = $this->db->get('list')->num_rows();
		}
		else
			{
				$condition = '';
				
				if(strtolower($keywords) == 'yes')
				{
					$condition .= "is_featured = 1 or ";
				}
								
				if(strtolower($keywords) == 'listed')
				{
					$condition .= 'is_enable = 1 and list_pay = 1 or ';
				}
				
				if(strtolower($keywords) == 'pending')
				{
					$lys_status = $this->db->get('lys_status');
				
				foreach($lys_status->result() as $row)
				{
				$total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
				$total_status = 6 - $total_status;
				
				if(strtolower($keywords) == 'pending' && $total_status > 0)
				{
					$condition .= "id = ".$row->id." or ";
				}
								
				}
				
					//$condition .= 'is_enable = 0 and list_pay = 0 or ';
				}
				
				$condition .= "address  LIKE '%".$keywords."%' or  title  LIKE '%".$keywords."%' ".$userid;
					$this->db->where($condition);
		      $result = $this->db->get('list', $row_count, $offset);
			//  echo $this->db->last_query();exit;
			  	$this->db->where($condition);
		      $result1 = $this->db->get('list')->num_rows();
			}
	
	$data['num_rows'] = $result->num_rows();
	//print_r($result->result());exit;
	//echo $data['num_rows'];exit;
		$data['users'] = $result->result();
		
		//echo $this->db->where($condition)->get('list')->num_rows();exit;
		
		$p_config['base_url']    = admin_url('lists/keyword_search/index');
		$p_config['uri_segment'] = 5;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $result1;
		$p_config['per_page']    = $row_count;
				//echo $result1;exit;
		// Init pagination
		$this->pagination->initialize($p_config);		
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links2();
		
    	 $data['message_element'] = "administrator/view_lists";
		 $this->load->view('administrator/admin_template', $data);
		 $keywords="";
		 $userid="";
			
	//}
}
	
	function check_valid_id($listId)
	{
		 $query                   = $this->db->get_where('list', array( 'id' => $listId));
		     if($query->num_rows() == 0)
				{
					$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This list already deleted.')));
					redirect_admin('lists');
				}
	}
	
	function calendar_type()
	{
		extract($this->input->post());
		$data['calendar_type'] = $type;
		$this->db->where('id',$room_id)->update('list',$data);
		$data1['calendar'] = 1;
		$this->db->where('id',$room_id)->update('lys_status',$data1);	
		
		$result = $this->db->where('id',$room_id)->get('lys_status')->row();
			
			$total = $result->calendar+$result->price+$result->overview+$result->photo+$result->address+$result->listing;
	
			if($total == 6)
			{
			  $data['is_enable'] = 1;
			  $data['list_pay'] = 1;
			  $this->db->where('id',$room_id)->update('list',$data);
			}
			
		echo $room_id;exit;
	}
	//With out CDN
	
	
	function addlist()
    {
        
    require_once APPPATH.'libraries/cloudinary/autoload.php';

\Cloudinary::config(array( 
  "cloud_name" => cdn_name, 
  "api_key" => cdn_api, 
  "api_secret" => cloud_s_key
));
 
       $check = $this->input->post('check');
        
    if($this->input->post('delete'))
        {
            if(empty($check))
            {
             $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                
                redirect_admin('lists');
            }
        foreach($check as $id)
        {
        $reservation_status=$this->Common_model->getTableData( 'reservation', array('list_id' => $id, 'status !=' => '10' ));
        if($reservation_status->num_rows() > 0)
        {
            
        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, The selected listing is in process or resevered by someone')));
        redirect_admin('lists');
        }
        else
        {
        $this->db->delete('list', array('id' => $id));
        $this->db->delete('list_photo', array('id' => $id)); 
        $this->db->delete('price', array('id' => $id)); 
        $this->db->delete('calendar', array('list_id' => $id)); 
        $this->db->delete('messages', array('list_id' => $id));
        $this->db->delete('referrals_booking', array('list_id' => $id));
        $this->db->delete('reviews', array('list_id' => $id));
        $this->db->delete('statistics', array('list_id' => $id));
        $this->db->delete('contacts', array('list_id' => $id));
        $this->db->delete('reservation', array('list_id' => $id));
        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate_admin('Rooms deleted successfully.')));
        }
        }
        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('List Deleted Successfully')));
        redirect_admin('lists');
        }
        else if($this->input->post('featured'))
        {
            
            if(empty($check))
            {
             $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                redirect_admin('lists');
            }
        
            foreach($check as $c)
            {
                $lys_status = $this->db->where('id',$c)->get('lys_status');
                
                foreach($lys_status->result() as $row)
                {
                $total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
                $total_status = 6 - $total_status;
                }
                if($total_status == 0)
                $this->Common_model->updateTableData('list',$c,NULL,array("is_featured" => '1'));
                else
                {
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry! you're choosed incompleted list.")));
                redirect_admin('lists');
                }
            }
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Updated Successfully')));
             redirect_admin('lists');
        }
        else if($this->input->post('unfeatured'))
        {
            if(empty($check))
            {
             $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                redirect_admin('lists');
            }
                foreach($check as $c)
                {
                    $sql="update list set is_featured=0 where id='".$c."'";
                    $this->db->query($sql); 
                }
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('List unfeatured Successfully')));
             redirect_admin('lists');
        }
        else if($this->input->post('add'))
        {                               
                $data['amnities']        = $this->Rooms_model->get_amnities();
                
                $data['property_types']  = $this->db->order_by('id','asc')->get('property_type');
                
                $query3                  = $this->db->get_where('price',array('id' => $check[0]));
                $data['price']           = $query3->row(); 
                
                $data['list_images']     = $this->Gallery->get_imagesG($check[0]);
                
                $data['message_element'] = "administrator/view_add_list";
                $this->load->view('administrator/admin_template', $data);
                
        }
        else if($this->input->post('update_desc'))
        {
            
            $data = array(
                            'user_id'               => $this->dx_auth->get_user_id(),
                            'property_id'           => $this->input->post('property_id'),
                            'room_type'             => $this->input->post('room_type'),
                            'capacity'              => $this->input->post('capacity'),
                            'cancellation_policy'   => '1',
                            'address'               => $this->input->post('address'),
                            'lat'                   => $this->input->post('hidden_lat'),
                            'long'                  => $this->input->post('hidden_lng'),
                            'street_address'        => $this->input->post('lys_street_address'),
                            'city'                  => $this->input->post('city'),
                            'state'                 => $this->input->post('state'),
                            'zip_code'              => $this->input->post('zipcode'),
                            'country'               => $this->input->post('country'),
                            'created'               => time(),
                            'price'                 => 10,
                            'currency'              => 'USD',
                            'calendar_type'         => 2,
                            'is_enable'             => 0
                            );
                            extract($this->input->post());
                        
$address_explode = explode(',',$this->input->post('address'));  
if(count($address_explode) < 3)
{
echo "1";exit;
}
        $this->db->insert('list',$data);
         $data_lys = array(
                                'id'                => $this->db->insert_id(),
                                'user_id'           => $this->dx_auth->get_user_id(),
                                'calendar'          => 0,
                                'price'             => 0,
                                'overview'          => 0,
                                'title'             => 0,
                                'summary'           => 0,
                                'photo'             => 0,
                                'amenities'         => 0,
                                'address'           => 0,
                                'listing'           => 0,
                                'beds'              => 0,
                                'bathrooms'         => 0                                
                                );      
        $last_insert_id =   $this->db->insert_id();                 
        $this->db->insert('lys_status',$data_lys);
        
        $data_price = array(
                        'id' => $last_insert_id,
                        'night' => 10,
                        'week' => 0,
                        'month' => 0,
                        'guests' => 0,
                        'addguests' => 0,
                        'cleaning' => 0,
                        'security' => 0,
                        'currency' => 'USD'
        );
        
        $this->db->insert('price',$data_price);
        echo 'administrator/lists/addlist/'.$last_insert_id;            
        //echo "<p>List Description Updated Successfully</p>";
    }
        else if($this->input->post('update_aminities'))
        {
         $listId           = $this->input->post('list_id');
                        
                        $query                   = $this->db->get_where('list', array( 'id' => $listId));
             if($query->num_rows() == 0)
                {
                    echo '0';exit;
                }
                        
   $amenity   = $this->input->post('amenities');
            $aCount    = count($amenity);
            
            $amenities = '';    
            if(is_array($amenity))
            {
                if(count($amenity) > 0)
                {
                    $i = 1;
                    foreach($amenity as $value)
                    {
                            if($i == $aCount) $comma = ''; else $comma = ',';
                            
                            $amenities .= $value.$comma;
                            $i++;
                    }
                }
            }
            
        if($amenities != '')
        {
        $updateData['amenities'] = $amenities;
        }
if($amenities == '')
{
echo "<p>Sorry, You have to select atleast one!</p>";exit;
}
                                                
        $updateKey = array('id' => $listId);                                    
        $this->Rooms_model->update_list($updateKey, $updateData);

            echo "<p>List Amenities Updated Successfully</p>";
        }
     else if($this->input->post('update_photo'))
        {
                $listId           = $this->input->post('list_id');
                
                        
                $images           = $this->input->post('image');
                $is_main          = $this->input->post('is_main');
                
                $fimages          = $this->Gallery->get_imagesG($listId);
                if($is_main != '')
                {
                foreach($fimages->result() as $row)
                {
                 if($row->id == $is_main)
                   $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 1));
                    else
                      $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 0));
                }
                }
                
                if(!empty($images))
                {
                    foreach($images as $key=>$value)
                    {
                     $image_name = $this->Gallery->get_imagesG(NULL, array('id' => $value))->row()->name;
                     //unlink($this->path.'/'.$listId.'/'.$image_name);
                     
                     try{
                                                              $image=\Cloudinary\Uploader::destroy("images/".$listId."/".$image_name, array(
                                                                                      //"public_id" => "images/".$room_id));
                                                       //"/".$row->name
                                                       "invalidate" => TRUE,));
                                                           print_r($image);                                                                    
                                                                                        }
                                                                               catch (Exception $e) {
                                                                               $error = $e->getMessage();
                                                print_r($error);
                                                                               }
                            
                        $conditions = array("id" => $value);
                        $this->Common_model->deleteTableData('list_photo', $conditions);
                    }
                }
        
                    $room_id = $listId;
        
        $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id;
        
        if($this->dx_auth->get_user_id() == '')
        {
            echo 'logout';exit;
        }
        
        if(!file_exists($filename)) 
        {
        mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id, 0777, true);
        }
              
      if(isset($_FILES["userfile"]["name"]))
                    {
       foreach ($_FILES["userfile"]["error"] as $key => $error) {
    
    if($this->dx_auth->get_user_id() == '')
        {
            echo 'logout';exit;
        }
                $tmp_name = $_FILES["userfile"]["tmp_name"][$key];
                $name = str_replace(' ','_',$_FILES["userfile"]["name"][$key]);
                
$temp1 = explode('.', $name);
$ext1  = array_pop($temp1);
$name2 = implode('.', $temp1);
                
                 try{
$cloudimage=\Cloudinary\Uploader::upload($tmp_name,
array("public_id" => "images/".$room_id."/".$name2
));
// print_r($image);
}catch (Exception $e) {
$error = $e->getMessage();
// print_r($error); 
} 
$secureurl=$cloudimage['secure_url'];
                    
                    $ext = pathinfo($name, PATHINFO_EXTENSION);

                    if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPEG' || $ext == 'PNG' || $ext ==  'GIF' || $ext == 'JPG') 
                    {                
                    if( $secureurl!='')
                    {
                        if($this->dx_auth->get_user_id() == '')
                        {
                        echo 'logout';exit;
                        }
                        if($ext == 'png' || $ext == 'PNG' )
                    {
                        $image = imagecreatefrompng("images/{$room_id}/{$name}");
                        imagejpeg($image, "images/{$room_id}/{$name}", 100);
                        imagedestroy($image);
                    }
                            
                        $image_name    = $name;
                        $insertData['list_id']    = $room_id;
                        $insertData['name']       = $image_name; 
                         echo "hi";exit;
                        
                        $insertData['created']    = local_to_gmt();
                        
                        $check = $this->db->where('list_id',$room_id)->get('list_photo');
                        
                        $photo_status['photo'] = 1;
                        $this->db->where('id',$room_id)->update('lys_status',$photo_status);
                        
                        if($check->num_rows() == 0)
                        {
                            $insertData['is_featured'] = 1;
                        }
                        else 
                        {
                           $insertData['is_featured'] = 0;
                        }
                        if($image_name != '')
                        $this->Common_model->insertData('list_photo', $insertData);
                        $this->watermark($room_id,$image_name);
                        $this->watermark1($room_id,$image_name);
                    }
                    }
   else if(count($_FILES["userfile"]["error"]) == 1) {
    if($_FILES["userfile"]["name"][0] != '')
    {
    $rimages = $this->Gallery->get_imagesG($listId);
                    $i = 1;
                    $replace = '<ul class="clearfix">';
                    foreach ($rimages->result() as $rimage)
                    {       
                      if($rimage->is_featured == 1)
                             $checked = 'checked="checked"'; 
                            else
                             $checked = ''; 
                                
                        $url = base_url().'images/'.$rimage->list_id.'/'.$rimage->name;
                                    $replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage->id.'" /></label>';
                                    $replace .= '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$rimage->id.'" /></p><div class="panel-body panel-condensed"><textarea rows="3" id="highlight_'.$rimage->id.'" placeholder="'.translate_admin("What are the highlights of this photo?").'" class="input-large" onkeyup="highlight1('.$rimage->id.')">'.trim($rimage->highlights).'</textarea></div></li>';
                                $i++;
                    }
                    $replace .= '</ul>';
                    if($this->dx_auth->get_user_id() == '')
        {
            echo 'logout';exit;
        }
                    
                 echo $replace."#"."<p>Please upload correct file.</p>";exit;
}
   }
                    }
                    }
                    $rimages = $this->Gallery->get_imagesG($listId);
                    $i = 1;
                    $replace = '<ul class="clearfix">';
                    if($rimages->num_rows() == 0)
                    {
                        $this->db->where('id',$listId)->update('lys_status',array('photo'=>0));
                    }
                    else
                        {
                            $this->db->where('id',$listId)->update('lys_status',array('photo'=>1));
                            $lys_status = $this->db->where('id',$c)->get('lys_status');
                
                foreach($lys_status->result() as $row)
                {
                $total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
                $total_status = 6 - $total_status;
                }
                    if($total_status == 0)
                    {
                    $this->db->where('id',$listId)->update('list',array('is_enable'=>1,'list_pay'=>1)); 
                    }   
                        }
                    foreach ($rimages->result() as $rimage)
                    {       
                      if($rimage->is_featured == 1)
                             $checked = 'checked="checked"'; 
                            else
                             $checked = ''; 
                                
                        $url = base_url().'images/'.$rimage->list_id.'/'.$rimage->name;
                                    $replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage->id.'" /></label>';
                                    $replace .= '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$rimage->id.'" /></p></li>';
                                $i++;
                    }
                    $replace .= '</ul>';
                    if($this->dx_auth->get_user_id() == '')
        {
            echo 'logout';exit;
        }
                    
                 echo $replace."#"."<p>List Photo's Updated successfully</p>";

        }
        else if($this->input->post('update_price'))
        {
        $listId           = $this->input->post('list_id');
        
        $query                   = $this->db->get_where('list', array( 'id' => $listId));
             if($query->num_rows() == 0)
                {
                    echo '0';exit;
                }
            
            $data = array(
                                            'currency'   => $this->input->post('currency'),
                                            'night'      => $this->input->post('nightly'),
                                            'week'       => $this->input->post('weekly'),
                                            'month'      => $this->input->post('monthly'),
                                            'addguests'  => $this->input->post('extra'),
                                            'guests'     => $this->input->post('guests'),
                                            'security'   => $this->input->post('security'),
                                            'cleaning'   => $this->input->post('cleaning')
                                            );
                                            
            $dataP = array();
            $dataP['price'] =  $this->input->post('nightly');

            $this->db->where('id', $listId);
            $this->db->update('price', $data);
            
            $this->db->where('id', $listId);
            $this->db->update('list', $dataP);
            
                echo "<p>List Price Updated successfully</p>";
        }
        else
        {
        redirect_admin('lists');
        }
    }





// 
// function addlist()
    // {
//         
    // require_once APPPATH.'libraries/cloudinary/autoload.php';
// 
// \Cloudinary::config(array( 
  // "cloud_name" => cdn_name, 
  // "api_key" => cdn_api, 
  // "api_secret" => cloud_s_key
// ));
//  
       // $check = $this->input->post('check');
//         
    // if($this->input->post('delete'))
        // {
            // if(empty($check))
            // {
             // $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
//                 
                // redirect_admin('lists');
            // }
        // foreach($check as $id)
        // {
        // $reservation_status=$this->Common_model->getTableData( 'reservation', array('list_id' => $id, 'status !=' => '10' ));
        // if($reservation_status->num_rows() > 0)
        // {
//             
        // $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, The selected listing is in process or resevered by someone')));
        // redirect_admin('lists');
        // }
        // else
        // {
        // $this->db->delete('list', array('id' => $id));
        // $this->db->delete('list_photo', array('id' => $id)); 
        // $this->db->delete('price', array('id' => $id)); 
        // $this->db->delete('calendar', array('list_id' => $id)); 
        // $this->db->delete('messages', array('list_id' => $id));
        // $this->db->delete('referrals_booking', array('list_id' => $id));
        // $this->db->delete('reviews', array('list_id' => $id));
        // $this->db->delete('statistics', array('list_id' => $id));
        // $this->db->delete('contacts', array('list_id' => $id));
        // $this->db->delete('reservation', array('list_id' => $id));
        // $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate_admin('Rooms deleted successfully.')));
        // }
        // }
        // $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('List Deleted Successfully')));
        // redirect_admin('lists');
        // }
        // else if($this->input->post('featured'))
        // {
//             
            // if(empty($check))
            // {
             // $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                // redirect_admin('lists');
            // }
//         
            // foreach($check as $c)
            // {
                // $lys_status = $this->db->where('id',$c)->get('lys_status');
//                 
                // foreach($lys_status->result() as $row)
                // {
                // $total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
                // $total_status = 6 - $total_status;
                // }
                // if($total_status == 0)
                // $this->Common_model->updateTableData('list',$c,NULL,array("is_featured" => '1'));
                // else
                // {
                // $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry! you're choosed incompleted list.")));
                // redirect_admin('lists');
                // }
            // }
                // $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Updated Successfully')));
             // redirect_admin('lists');
        // }
        // else if($this->input->post('unfeatured'))
        // {
            // if(empty($check))
            // {
             // $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
                // redirect_admin('lists');
            // }
                // foreach($check as $c)
                // {
                    // $sql="update list set is_featured=0 where id='".$c."'";
                    // $this->db->query($sql); 
                // }
                // $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('List unfeatured Successfully')));
             // redirect_admin('lists');
        // }
        // else if($this->input->post('add'))
        // {                               
                // $data['amnities']        = $this->Rooms_model->get_amnities();
//                 
                // $data['property_types']  = $this->db->order_by('id','asc')->get('property_type');
//                 
                // $query3                  = $this->db->get_where('price',array('id' => $check[0]));
                // $data['price']           = $query3->row(); 
//                 
                // $data['list_images']     = $this->Gallery->get_imagesG($check[0]);
//                 
                // $data['message_element'] = "administrator/view_add_list";
                // $this->load->view('administrator/admin_template', $data);
//                 
        // }
        // else if($this->input->post('update_desc'))
        // {
//             
            // $data = array(
                            // 'user_id'               => $this->dx_auth->get_user_id(),
                            // 'property_id'           => $this->input->post('property_id'),
                            // 'room_type'             => $this->input->post('room_type'),
                            // 'capacity'              => $this->input->post('capacity'),
                            // 'cancellation_policy'   => '1',
                            // 'address'               => $this->input->post('address'),
                            // 'lat'                   => $this->input->post('hidden_lat'),
                            // 'long'                  => $this->input->post('hidden_lng'),
                            // 'street_address'        => $this->input->post('lys_street_address'),
                            // 'city'                  => $this->input->post('city'),
                            // 'state'                 => $this->input->post('state'),
                            // 'zip_code'              => $this->input->post('zipcode'),
                            // 'country'               => $this->input->post('country'),
                            // 'created'               => time(),
                            // 'price'                 => 10,
                            // 'currency'              => 'USD',
                            // 'calendar_type'         => 2,
                            // 'is_enable'             => 0
                            // );
                            // extract($this->input->post());
//                         
// $address_explode = explode(',',$this->input->post('address'));  
// if(count($address_explode) < 3)
// {
// echo "1";exit;
// }
        // $this->db->insert('list',$data);
         // $data_lys = array(
                                // 'id'                => $this->db->insert_id(),
                                // 'user_id'           => $this->dx_auth->get_user_id(),
                                // 'calendar'          => 0,
                                // 'price'             => 0,
                                // 'overview'          => 0,
                                // 'title'             => 0,
                                // 'summary'           => 0,
                                // 'photo'             => 0,
                                // 'amenities'         => 0,
                                // 'address'           => 0,
                                // 'listing'           => 0,
                                // 'beds'              => 0,
                                // 'bathrooms'         => 0                                
                                // );      
        // $last_insert_id =   $this->db->insert_id();                 
        // $this->db->insert('lys_status',$data_lys);
//         
        // $data_price = array(
                        // 'id' => $last_insert_id,
                        // 'night' => 10,
                        // 'week' => 0,
                        // 'month' => 0,
                        // 'guests' => 0,
                        // 'addguests' => 0,
                        // 'cleaning' => 0,
                        // 'security' => 0,
                        // 'currency' => 'USD'
        // );
//         
        // $this->db->insert('price',$data_price);
        // echo 'administrator/lists/addlist/'.$last_insert_id;            
        // //echo "<p>List Description Updated Successfully</p>";
    // }
        // else if($this->input->post('update_aminities'))
        // {
         // $listId           = $this->input->post('list_id');
//                         
                        // $query                   = $this->db->get_where('list', array( 'id' => $listId));
             // if($query->num_rows() == 0)
                // {
                    // echo '0';exit;
                // }
//                         
   // $amenity   = $this->input->post('amenities');
            // $aCount    = count($amenity);
//             
            // $amenities = '';    
            // if(is_array($amenity))
            // {
                // if(count($amenity) > 0)
                // {
                    // $i = 1;
                    // foreach($amenity as $value)
                    // {
                            // if($i == $aCount) $comma = ''; else $comma = ',';
//                             
                            // $amenities .= $value.$comma;
                            // $i++;
                    // }
                // }
            // }
//             
        // if($amenities != '')
        // {
        // $updateData['amenities'] = $amenities;
        // }
// if($amenities == '')
// {
// echo "<p>Sorry, You have to select atleast one!</p>";exit;
// }
//                                                 
        // $updateKey = array('id' => $listId);                                    
        // $this->Rooms_model->update_list($updateKey, $updateData);
// 
            // echo "<p>List Amenities Updated Successfully</p>";
        // }
     // else if($this->input->post('update_photo'))
        // {
                // $listId           = $this->input->post('list_id');
//                 
//                         
                // $images           = $this->input->post('image');
                // $is_main          = $this->input->post('is_main');
//                 
                // $fimages          = $this->Gallery->get_imagesG($listId);
                // if($is_main != '')
                // {
                // foreach($fimages->result() as $row)
                // {
                 // if($row->id == $is_main)
                   // $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 1));
                    // else
                      // $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 0));
                // }
                // }
//                 
                // if(!empty($images))
                // {
                    // foreach($images as $key=>$value)
                    // {
                     // $image_name = $this->Gallery->get_imagesG(NULL, array('id' => $value))->row()->name;
                     // //unlink($this->path.'/'.$listId.'/'.$image_name);
//                      
                     // try{
                                                              // $image=\Cloudinary\Uploader::destroy("images/".$listId."/".$image_name, array(
                                                                                      // //"public_id" => "images/".$room_id));
                                                       // //"/".$row->name
                                                       // "invalidate" => TRUE,));
                                                           // print_r($image);                                                                    
                                                                                        // }
                                                                               // catch (Exception $e) {
                                                                               // $error = $e->getMessage();
                                                // print_r($error);
                                                                               // }
//                             
                        // $conditions = array("id" => $value);
                        // $this->Common_model->deleteTableData('list_photo', $conditions);
                    // }
                // }
//         
                    // $room_id = $listId;
//         
        // $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id;
//         
        // if($this->dx_auth->get_user_id() == '')
        // {
            // echo 'logout';exit;
        // }
//         
        // if(!file_exists($filename)) 
        // {
        // mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id, 0777, true);
        // }
//               
      // if(isset($_FILES["userfile"]["name"]))
                    // {
       // foreach ($_FILES["userfile"]["error"] as $key => $error) {
//     
    // if($this->dx_auth->get_user_id() == '')
        // {
            // echo 'logout';exit;
        // }
                // $tmp_name = $_FILES["userfile"]["tmp_name"][$key];
                // $name = str_replace(' ','_',$_FILES["userfile"]["name"][$key]);
//                 
// $temp1 = explode('.', $name);
// $ext1  = array_pop($temp1);
// $name2 = implode('.', $temp1);
//                 
                 // try{
// $cloudimage=\Cloudinary\Uploader::upload($tmp_name,
// array("public_id" => "images/".$room_id."/".$name2
// ));
 // print_r($image);
// }catch (Exception $e) {
// $error = $e->getMessage();
 // print_r($error); 
// } 
// $secureurl=$cloudimage['secure_url'];
//                     
                    // $ext = pathinfo($name, PATHINFO_EXTENSION);
// 
                    // if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPEG' || $ext == 'PNG' || $ext ==  'GIF' || $ext == 'JPG') 
                    // {                
                    // if( $secureurl!='')
                    // {
                        // if($this->dx_auth->get_user_id() == '')
                        // {
                        // echo 'logout';exit;
                        // }
                        // if($ext == 'png' || $ext == 'PNG' )
                    // {
                        // $image = imagecreatefrompng("images/{$room_id}/{$name}");
                        // imagejpeg($image, "images/{$room_id}/{$name}", 100);
                        // imagedestroy($image);
                    // }
//                             
                        // $image_name    = $name;
                        // $insertData['list_id']    = $room_id;
                        // $insertData['name']       = $image_name; 
                         // echo "hi";exit;
//                         
                        // $insertData['created']    = local_to_gmt();
//                         
                        // $check = $this->db->where('list_id',$room_id)->get('list_photo');
//                         
                        // $photo_status['photo'] = 1;
                        // $this->db->where('id',$room_id)->update('lys_status',$photo_status);
//                         
                        // if($check->num_rows() == 0)
                        // {
                            // $insertData['is_featured'] = 1;
                        // }
                        // else 
                        // {
                           // $insertData['is_featured'] = 0;
                        // }
                        // if($image_name != '')
                        // $this->Common_model->insertData('list_photo', $insertData);
                        // $this->watermark($room_id,$image_name);
                        // $this->watermark1($room_id,$image_name);
                    // }
                    // }
   // else if(count($_FILES["userfile"]["error"]) == 1) {
    // if($_FILES["userfile"]["name"][0] != '')
    // {
    // $rimages = $this->Gallery->get_imagesG($listId);
                    // $i = 1;
                    // $replace = '<ul class="clearfix">';
                    // foreach ($rimages->result() as $rimage)
                    // {       
                      // if($rimage->is_featured == 1)
                             // $checked = 'checked="checked"'; 
                            // else
                             // $checked = ''; 
//                                 
                        // $url = base_url().'images/'.$rimage->list_id.'/'.$rimage->name;
                                    // $replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage->id.'" /></label>';
                                    // $replace .= '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$rimage->id.'" /></p><div class="panel-body panel-condensed"><textarea rows="3" id="highlight_'.$rimage->id.'" placeholder="'.translate_admin("What are the highlights of this photo?").'" class="input-large" onkeyup="highlight1('.$rimage->id.')">'.trim($rimage->highlights).'</textarea></div></li>';
                                // $i++;
                    // }
                    // $replace .= '</ul>';
                    // if($this->dx_auth->get_user_id() == '')
        // {
            // echo 'logout';exit;
        // }
//                     
                 // echo $replace."#"."<p>Please upload correct file.</p>";exit;
// }
   // }
                    // }
                    // }
                    // $rimages = $this->Gallery->get_imagesG($listId);
                    // $i = 1;
                    // $replace = '<ul class="clearfix">';
                    // if($rimages->num_rows() == 0)
                    // {
                        // $this->db->where('id',$listId)->update('lys_status',array('photo'=>0));
                    // }
                    // else
                        // {
                            // $this->db->where('id',$listId)->update('lys_status',array('photo'=>1));
                            // $lys_status = $this->db->where('id',$c)->get('lys_status');
//                 
                // foreach($lys_status->result() as $row)
                // {
                // $total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
                // $total_status = 6 - $total_status;
                // }
                    // if($total_status == 0)
                    // {
                    // $this->db->where('id',$listId)->update('list',array('is_enable'=>1,'list_pay'=>1)); 
                    // }   
                        // }
                    // foreach ($rimages->result() as $rimage)
                    // {       
                      // if($rimage->is_featured == 1)
                             // $checked = 'checked="checked"'; 
                            // else
                             // $checked = ''; 
//                                 
                        // $url = base_url().'images/'.$rimage->list_id.'/'.$rimage->name;
                                    // $replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage->id.'" /></label>';
                                    // $replace .= '<img src="'.$url.'" width="150" height="150" /><input type="radio" '.$checked.' name="is_main" value="'.$rimage->id.'" /></p></li>';
                                // $i++;
                    // }
                    // $replace .= '</ul>';
                    // if($this->dx_auth->get_user_id() == '')
        // {
            // echo 'logout';exit;
        // }
//                     
                 // echo $replace."#"."<p>List Photo's Updated successfully</p>";
// 
        // }
        // else if($this->input->post('update_price'))
        // {
        // $listId           = $this->input->post('list_id');
//         
        // $query                   = $this->db->get_where('list', array( 'id' => $listId));
             // if($query->num_rows() == 0)
                // {
                    // echo '0';exit;
                // }
//             
            // $data = array(
                                            // 'currency'   => $this->input->post('currency'),
                                            // 'night'      => $this->input->post('nightly'),
                                            // 'week'       => $this->input->post('weekly'),
                                            // 'month'      => $this->input->post('monthly'),
                                            // 'addguests'  => $this->input->post('extra'),
                                            // 'guests'     => $this->input->post('guests'),
                                            // 'security'   => $this->input->post('security'),
                                            // 'cleaning'   => $this->input->post('cleaning')
                                            // );
//                                             
            // $dataP = array();
            // $dataP['price'] =  $this->input->post('nightly');
// 
            // $this->db->where('id', $listId);
            // $this->db->update('price', $data);
//             
            // $this->db->where('id', $listId);
            // $this->db->update('list', $dataP);
//             
                // echo "<p>List Price Updated successfully</p>";
        // }
        // else
        // {
        // redirect_admin('lists');
        // }
    // }



public function delete_cal($param='')
	{	
	$condition = array("id" => $param);
	
	$list_id = $this->Common_model->getTableData('ical_import', $condition)->row()->list_id;
	
	$this->Common_model->deleteTableData('ical_import', $condition);	
	
	$condition1 = array("booked_using" => $param);
	
	$this->Common_model->deleteTableData('calendar', $condition1);
	
	redirect('administrator/lists/managelist/'.$list_id.'?month=1');
	}
	
	public function sync_cal($ical_id)
	{
	require_once("app/views/templates/blue/rooms/codebase/class.php");
	
	$exporter = new ICalExporter();
	
	$ical_urls = $this->db->where('id',$ical_id)->get('ical_import');
	
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
	
	$update_sync['last_sync'] = date('d M Y, g:i a',gmt_to_local(time(), get_user_timezone(), false));
		
	$this->db->where('id',$row->id)->update('ical_import',$update_sync);
	}
	}

   redirect('administrator/lists/managelist/'.$ical_urls->row()->list_id.'?month=1');
	}

    function curr_diff()
	{
		 // echo "success";
			$currency=$this->input->post('currency');
		   //echo $currency;exit;
		     if($currency)
		   {
		     	$currencyvalue=$this->db->where('currency_code',$currency)->get('currency_converter')->row()->currency_value;
		        $currency_value=round($currencyvalue*10);
		   }
		   else
		   {
			  $currency_value=10;
		   }
		
		     echo json_encode($currency_value);exit;
			 
	 }

}
?>
