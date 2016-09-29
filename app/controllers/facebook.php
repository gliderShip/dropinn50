<?php
/**
 * DROPinn Facebook Controller Class
 *
 * It helps to control the facebook users
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Facebook
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Facebook extends CI_Controller {
		
		function __construct()
		{
			parent::__construct();
			
			// $this->load->add_package_path('/Users/elliot/github/codeigniter-facebook/application/');
			$this->load->library('Facebook_Lib');
			
			$this->load->model('Users_model');
			$this->load->model('Referrals_model');
			$this->facebook_lib->enable_debug(TRUE);
		}
		
		function index()
		{
			// We can use the open graph place meta data in the head.
			// This meta data will be used to create a facebook page automatically
			// when we 'like' the page.
			// 
			// For more details see: http://developers.facebook.com/docs/opengraph
			
			$opengraph = 	array(
								'type'				     => 'website',
								'title'				    => 'My Awesome Site',
								'url'				      => site_url(),
								'image'				    => '',
								'description'		=> 'The best site in the whole world'
							);

			$this->load->vars('opengraph', $opengraph);
			$this->load->view(THEME_FOLDER.'/view_facebook');
		}
		
		function login()
		{
			// This is the easiest way to keep your code up-to-date. Use git to checkout the 
			// codeigniter-facebook repo to a location outside of your site directory.
			// 
			// Add the 'application' directory from the repo as a package path:
			// $this->load->add_package_path('/var/www/haughin.com/codeigniter-facebook/application/');
			// 
			// Then when you want to grab a fresh copy of the code, you can just run a git pull 
			// on your codeigniter-facebook directory.
		//	echo "Redirecting You Shortly.........";
			
			//if($this->facebook_lib->logged_in())
			//{
					/*
				$result = $this->facebook_lib->call('get', 'me', array('metadata' => 1));
				
				//var_dump($result); exit;
				//$data['data']=$result->__resp->data;
				$id       =  $result->__resp->data->id;
				$email    = $result->__resp->data->email;
				
				if(isset($result->__resp->data->username) && $result->__resp->data->username!="")
				$username = $result->__resp->data->username;
				else
				$username = $result->__resp->data->first_name.$result->__resp->data->last_name;
					
				$password = $id;
				
				if(isset($result->__resp->data->first_name))
				$Fname    =  $result->__resp->data->first_name;
				else
				$Fname    = '';
				
				if(isset($result->__resp->data->last_name))
                $Lname    =  $result->__resp->data->last_name;
				else
				$Lname    = '';
				
				if(isset($result->__resp->data->hometown->name))
				$live     = $result->__resp->data->hometown->name;
				else
				$live     = '';
				*/
				
				extract($this->input->post());
				
				if(!isset($username))
				{
					$username = $name.$Lname;
				}
				if(!isset($email))
				{
					$email =$id;
				}
				
				if(!isset($live))
				{
					$live ='';
				}
				$password = $id;
				
				//First time register More stuff to do here
				if(!$this->email_exists($email) && !$this->fb_exists($username))
				{
					$this->dx_auth->register($username, $password, $email, $id);
					
					$this->dx_auth->login($username, $password, 'TRUE');
					$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
					$add['id']     = $this->dx_auth->get_user_id();
					$add['Fname']  = $Fname;
					$add['Lname']  = $Lname;
					$add['live']   = $live;
					$add['email']  = $email;
					$this->Common_model->insertData('profiles',$add);
					//echo $email;exit;
					$notification                     = array();
					$notification['user_id']	      = $this->dx_auth->get_user_id();
					$notification['new_review ']						= 1;
					$notification['leave_review']				 = 1;
					$this->Common_model->insertData('user_notification', $notification);
					
				//	$src           = facebook_picture('me');
					$add2['email'] = $email;
					$add2['src']   = $src;
					$this->Common_model->insertData('profile_picture',$add2);
					//$reference  =substr(number_format(time() * rand(),0,'',''),0,6);
					//$reference  = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789',5)),0,8);
		$data2=array("photo_status" => 2,"via_login"=>"facebook") ;
	    
		$this->db->where('id', $this->dx_auth->get_user_id());
		
		$this->db->update('users', $data2);
		
			
					$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Logged in successfully.')));
				//	redirect('home/dashboard/'.$this->dx_auth->get_user_id(), 'refresh');
					echo 'home/dashboard/'.$this->dx_auth->get_user_id();
					//echo json_encode($array);
				}
				else 
				{	
					$condition   = array("email" => $email);
				//	$src         = facebook_picture('me');
					$add2['src'] = $src;
					$this->Common_model->updateTableData('profile_picture', NULL, $condition, $add2);
					$this->dx_auth->login($username, $password, 'TRUE');
					
					//check to facebook login valid
					if ($this->dx_auth->login($username, $password,'TRUE'))
					{
					$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
					 // check facebook id is there
					 $query = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row();
						if($query->fb_id == "")
						{
						$condition     = array("id" => $this->dx_auth->get_user_id(),"via_login"=>"facebook");
						$data['fb_id'] = $id; 
						$this->Common_model->updateTableData('users', NULL, $condition, $data);
						}
						
						// Redirect to homepage
						$newdata = array(
																		'user'      => $this->dx_auth->get_user_id(),
																		'username'  => $this->dx_auth->get_username(),
																		'logged_in' => TRUE
																	);
						$this->session->set_userdata($newdata);
						
						if($this->session->userdata('redirect_to'))
						{
								$redirect_to = $this->session->userdata('redirect_to');
								$this->session->unset_userdata('redirect_to');
								//redirect($redirect_to, 'refresh');
								echo $redirect_to;
						}
						else
						{
								$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Logged in successfully.')));
							//	redirect('home/dashboard/'.$this->dx_auth->get_user_id(), 'refresh');
							echo 'home/dashboard/'.$this->dx_auth->get_user_id();
							//echo json_encode($array);
						}
					}
					else
					{
					 //if facebook info not matching to our db then delete the old one and replace by new one with exist date
						$fb_details = $this->Users_model->delete_user_fb($email);
						
						$this->dx_auth->register($username, $password, $email, $id, $fb_details[0], $fb_details[1], $fb_details[2], $fb_details[3]);
						$this->dx_auth->login($username, $password, 'TRUE');
						$this->Common_model->updateTableData('login_history',0,array('session_id'=>$this->session->userdata('session_id')),array('user_id'=>$this->dx_auth->get_user_id()));
						$condition   = array("email" => $email);
					//	$src         = facebook_picture('me');
						$add2['src'] = $src;
						$this->Common_model->updateTableData('profile_picture', NULL, $condition, $add2);
						
						$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success',translate('Logged in successfully.')));
						//redirect('home/dashboard/'.$this->dx_auth->get_user_id(), 'refresh');
						echo 'home/dashboard/'.$this->dx_auth->get_user_id();
							//echo json_encode($array);
					}
					
				}
				
			//}
		}
		
		function logout()
		{
			$this->facebook_lib->logout();
			redirect('facebook');
		}

		public function email_exists($email)
		{
			$query = $this->Common_model->getTableData('users', array('email' => $email));
			$q     = $query->num_rows();
			if($q == 1)
				return TRUE;
			else return FALSE;
		}
		
		public function fb_exists($id)
		{
			$query = $this->Common_model->getTableData('users', array('username' => $id));
			$q     = $query->num_rows();
			if($q != 0)
				return TRUE;
			else return FALSE;
		}
		public function fb_exists_email($id)
		{
			$query = $this->Common_model->getTableData('users', array('email' => $id));
			$q     = $query->num_rows();
			if($q != 0)
				return TRUE;
			else return FALSE;
		}
	
	
	public function success()
	{
		extract($this->input->post());
				
				if(!isset($username))
				{
					$username = $name.$Lname;
				}
				if(!isset($email))
				{
					$email ='';
				}
				
				if(!isset($live))
				{
					$live ='';
				}
				$password = $id;
				
	//	$checknew_user = $this->fb_exists($id);
				 
		// $profile_url_large = str_replace("_normal","",$src);
			
			$profile = array('src' => $src,'fb_id' => $id, 'username'=>$name,'email'=>$email );
			$this->session->set_userdata($profile); 
			
		if(!$email)
		{
			
		//	echo 'users';exit;
			//$user_id = $this->Users_model->createTwitterUser($twitter_id,$username);
			
			
			//$data['title']               = 'Twitter SignUp';
			//$data['message_element']     = "users/view_fb_popup";
			
			//$this->load->view('template',$data);
			$fb_popup = array('fb_id'=>$id, 'username'=>$name);
			$this->session->set_userdata($fb_popup); 
			
			echo 'facebook/view_fb_popup';
			//echo "signup";
		}
		else
		{
		if(!$this->fb_exists_email($email))
		{
				$this->Facebook_MailId();
			
		}
		else
		{
		
			$user_info = $this->Users_model->getUserInfobyemail_id($email);
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
		
		}	}
		
		 
	}
	
	function view_fb_popup()
	{
		if ($this->dx_auth->is_logged_in())
		{
			redirect('users/signin');
		}
		else 
		{ 
		$this->load->helper('form');
		$data['title']               = 'Facebook SignUp';
		$data['message_element']     = "users/view_fb_popup";
			
		$this->load->view('template',$data);
		}
	}
	
	public function Facebook_MailId_Popup()
	{
		
	 $fb_id = $this->session->userdata('fb_id');
	 $src = $this->session->userdata('src');
	 
	  $mailId  = $this->input->post('email');
	  	 
	  $username  = $this->input->post('username');
	
	  $user_id = $this->Users_model->createFacebookUser($fb_id,$username);
	  
	  $query_users = $this->db->query('UPDATE users SET email="'.$mailId.'" WHERE fb_id="'.$fb_id.'"');
	$query_profiles = $this->db->query('UPDATE profiles SET email="'.$mailId.'" WHERE id="'.$user_id.'"');

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
$this->db->set('trips_referral_code',$referral_code['referral_code'])->where('fb_id', $fb_id)->update('users');
$this->db->set('list_referral_code',$referral_code['referral_code'])->where('fb_id', $fb_id)->update('users');
$this->db->set('ref_total',$ref_total)->where('fb_id', $fb_id)->update('users');
$this->db->set('ref_trip',$trip)->where('fb_id', $fb_id)->update('users');
$this->db->set('ref_rent',$rent)->where('fb_id', $fb_id)->update('users');

                    $add2['email'] = $mailId;
					$add2['src']   = $src;
					$this->Common_model->insertData('profile_picture',$add2);
					//$reference  =substr(number_format(time() * rand(),0,'',''),0,6);
					$reference  = md5($username);
		$data2=array("photo_status" => 2) ;
	
		$this->db->where('fb_id', $fb_id);
		
		$this->db->update('users', $data2);

       $user = array(					
			'DX_user_id'			 => $user_id,
			'DX_username'			 => $username,
			'DX_emailId'			 => $mailId,
		//	'DX_refId'				 => $reference,
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
						$data['via_login']= "facebook";
						$data['ref_id'] = $reference;
						$this->Common_model->updateTableData('users', NULL, $condition, $data);
						$admin_email = $this->dx_auth->get_site_sadmin();

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
public function Facebook_MailId()
	{
		
	 $fb_id = $this->session->userdata('fb_id');
	 $src = $this->session->userdata('src');
	 
	  $mailId  = $this->session->userdata('email');
	  	 
	  $username  = $this->session->userdata('username');
	  
	
	  $user_id = $this->Users_model->createFacebookUserEmail($fb_id,$username,$mailId,$src);
	  
	 // $query_users = $this->db->query('UPDATE users SET email="'.$mailId.'" WHERE fb_id="'.$fb_id.'"');
	//$query_profiles = $this->db->query('UPDATE profiles SET email="'.$mailId.'" WHERE id="'.$user_id.'"');

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
$this->db->set('trips_referral_code',$referral_code['referral_code'])->where('fb_id', $fb_id)->update('users');
$this->db->set('list_referral_code',$referral_code['referral_code'])->where('fb_id', $fb_id)->update('users');
$this->db->set('ref_total',$ref_total)->where('fb_id', $fb_id)->update('users');
$this->db->set('ref_trip',$trip)->where('fb_id', $fb_id)->update('users');
$this->db->set('ref_rent',$rent)->where('fb_id', $fb_id)->update('users');

                    $add2['email'] = $mailId;
					$add2['src']   = $src;
					$this->Common_model->insertData('profile_picture',$add2);
					//$reference  =substr(number_format(time() * rand(),0,'',''),0,6);
					$reference  = md5($username);
		$data2=array("photo_status" => 2) ;
	
		$this->db->where('fb_id', $fb_id);
		
		$this->db->update('users', $data2);

       $user = array(					
			'DX_user_id'			 => $user_id,
			'DX_username'			 => $username,
			'DX_emailId'			 => $mailId,
		//	'DX_refId'				 => $reference,
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
						$data['via_login']= "facebook";
						$data['ref_id'] = $reference;
						$this->Common_model->updateTableData('users', NULL, $condition, $data);
						$admin_email = $this->dx_auth->get_site_sadmin();

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
	 echo 'home/dashboard';
	 //redirect('users/signup');
	 

	} 
 function check_username_fb(){
	   $username=$this->input->get('username');
	   $data['checked'] = $this->Users_model->check_username_twitter($username);
	   echo json_encode($data['checked']);   
   }
 function check_email_fb(){
	   $email=$this->input->get('email');
	   $data['checked'] = $this->Users_model->check_email_twitter($email);
	   echo json_encode($data['checked']);
   
   }	
	}
	
/* End of file facebook.php */
/* Location: ./app/controllers/facebook.php */
?>