<?php
/**
 * DROPinn User Controller Class
 *
 * helps to achieve common tasks related to the site for mobile app like android and iphone.
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	User
 * @author		Cogzidel Product Team
 * @version		Version 1.0
 * @link		http://www.cogzidel.com
 
 */
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function User()
	{
		parent::__construct();
		
		$this->load->helper('url');
		
		$this->load->library('DX_Auth');  

		$this->load->model('Users_model');
		$this->load->model('Post_model');
		$this->load->model('Gallery');
		$this->load->model('Trips_model');
		$this->load->model('Message_model');
		$this->_table = 'users';
		
		
		
		
		
        }
public function index1()
	{
	}
	
	public function login()
	{
		$emailid          = $this->input->get('email_id');   
		$password          = $this->input->get('password');
		
			if ( ! empty($emailid) AND ! empty($password))
		 {
				if ($query = $this->get_login($emailid) AND $query->num_rows() == 1)
		 	{
					// Get user record
					$row = $query->row();
	
					// Check if user is banned or not
					if ($row->banned > 0)
					{
						echo '[{"status":"Sorry! The Emailid was banned."}]';exit;
					}
                    else
                    {
					$password = $this->_encode($password);
 					$stored_hash = $row->password;
				//print_r(crypt($password, $stored_hash));
                //print_r("<br>".$stored_hash);exit;
					// Is password matched with hash in database ?
					if (crypt($password, $stored_hash) === $stored_hash)
					{	
					  $profile_pic = $this->Gallery->profilepic($row->id, 2);
					  echo '[{"status":"Successfully logged in.","user_id":"'.$row->id.'","email":"'.$emailid.'","username":"'.$row->username.'","profile_pic":"'.$profile_pic.'"}]';
							exit;
					}
					else
					{
					  echo '[{"status":"Sorry! The password is invalid."}]';exit;
					}
					}				
		 	}
				else
				{
				 echo '[{"status":"Sorry! The Emailid is invalid."}]';exit;
				}
			}
	}
public function my_profile1()
	{
		$user_id								= $this->input->get("user_id");
		$email								= $this->input->get("email");
		
		$query   = $this->db->where('id',$user_id)->get('profiles');
		if($query->num_rows()!=0)
	{
		foreach($query->result() as $row)
		{
			$data['user_id']   = $row->id;
			$Fname   = $row->Fname;
			if(!empty($Fname))
			{
			$data['Fname']  = $Fname;
			}
else{
	$data['Fname'] = 'null';
}
			$Lname  = $row->Lname;
		if(!empty($Lname))
			{
			$data['Lname']  = $Lname;
			}
else{
	$data['Lname'] = 'null';
}
			$query1   = $this->db->where('email',$email)->get('profile_picture');
		if($query1->num_rows()!=0)
	{
		$data['profile_pic']  = $query1->row()->src;
		$data['email'] = $email;
	}
		else
			{
				$data['profile_pic'] = 'null';
				$data['email'] = $email;
			}
				$work  = $row->work;
		if(!empty($work))
			{
			$data['work']  = $work;
			}
else{
	$data['work'] = 'null';
}
			$data['school']  = $row->school;
			$live  = $row->live;
		if(!empty($live))
			{
			$data['live']  = $live;
			}
else{
	$data['live'] = 'null';
}
			$data['school']  = $row->school;
			
			/*$created   = $row->join_date;
			if(!empty($created))
			{
			$data['join_date']  = gmdate('F Y',$created);
			}
else{
	$data['join_date'] = '';
}*/
			$details[]   = $data;
		}
	echo json_encode($details);
	}
	else {
			echo '[{"status":"This User is not available"}]';
	}
	}
public function language()
	{
		$language								= $this->input->get("language");
		
		$query   = $this->db->get('language');
		//print_r($this->db->last_query());exit;
	foreach($query->result() as $row){
		$data['id']   = $row->id;
		$data['language']  = $row->name;
		$details[]   = $data;
	}
	echo json_encode($details);
	}

public function paypal()
{
	$user_id								= $this->input->get("user_id");
	$query   = $this->db->where('id',3)->get('payment_details');
	$query1   = $this->db->where('id',2)->get('payments');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row){
		$data['client_id']   = $row->value;
	}
	}
if($query1->num_rows()!=0)
	{
	foreach($query1->result() as $row){
		$data['is_live']   = $row->is_live;
	}
	}
	$details[] = $data;
	echo json_encode($details, JSON_UNESCAPED_SLASHES);
}
		public function view_profile1()
	{
		$user_id								= $this->input->get("user_id");
		$email								= $this->input->get("email");
		
		$query   = $this->db->where('id',$user_id)->get('profiles');
		if($query->num_rows()!=0)
	{
	foreach($query->result() as $row){
		$data['user_id']   = $row->id;
		$data['school']  = $row->school;
		//$email   = $row->email;
		$query1   = $this->db->where('email',$email)->get('profile_picture');
		if($query1->num_rows()!=0)
	{
		$data['profile_pic']  = $query1->row()->src;
		$data['email'] = $email;
	}
		else
			{
				$data['profile_pic'] = 'null';
				$data['email'] = $email;
			}
			
		$firstname = $row->Fname;//Firstname
		if(!empty($firstname)){
			$data['Fname'] = $firstname;
		} else {
			$data['Fname'] = 'null';
		}
		$lastname = $row->Lname;//Lastname	
		if(!empty($lastname)){
			$data['Lname'] = $lastname;
		} else {
			$data['Lname'] = $lastname;
		}
		
		$work  = $row->work;
		if(!empty($work))
			{
			$data['work']  = $work;
			}
			else{
				$data['work'] = 'null';
			}
		$phnum  = $row->phnum;
		if(!empty($phnum))
			{
			$data['phnum']  = $phnum;
			}
			else{
				$data['phnum'] = 'null';
			}
		$about_me  = $row->describe;
		if(!empty($about_me))
			{
			$data['about_me']  = $about_me;
			}
		else{
			$data['about_me'] = 'null';
		}
		
		$data['gender']  = $row->gender;
		$my_dob  = $row->dob;
		if(!empty($my_dob))
			{
			$data['dob']  = gmdate('m-d-Y',$my_dob);
			}
			else{
				$data['dob'] = '';
			}
		$my_lan = $row->language;
		if(!empty($my_lan))
			{
			$data['language']  = $my_lan;
			}
			else{
				$data['language'] = '';
			}
		
		
		
		$live  = $row->live;
		if(!empty($live))
			{
			$data['live']  = $live;
			}
else{
	$data['live'] = 'null';
}
	
		$details[]   = $data;
	}
	echo json_encode($details, JSON_UNESCAPED_SLASHES);
	}
	else {
			echo '[{"status":"This User is not available"}]';
	}
		
		
	}
	
public function view_profile()
	{
		$user_id	= $this->input->get("user_id");
		$email		= $this->input->get("email");
		
		$query   = $this->db->where('id',$user_id)->get('profiles');
		if($query->num_rows()!=0)
	{
	foreach($query->result() as $row){
		$data['user_id']   = $row->id;
        $data['firstname'] = $row->Fname;
        $data['lastname'] = $row->Lname;
		$data['school']  = $row->school;
		//$email   = $row->email;
		$query1   = $this->db->where('email',$email)->get('profile_picture');
		$data['email'] = $email;
        
        $data['profile_pic'] = $this->Gallery->profilepic($row->id, 2);
        
		if($query1->num_rows()!=0)
        {
		$data['src']  = $query1->row()->src;
		
        }
		else
        {
        $data['src'] = 'null';
        }
		$work  = $row->work;
		if(!empty($work))
			{
			$data['work']  = $work;
			}
        else{
            $data['work'] = 'null';
        }
		$phnum  = $row->phnum;
		if(!empty($phnum))
			{
			$data['phnum']  = $phnum;
			}
        else{
            $data['phnum'] = 'null';
        }
		$country_code  = $row->country_code;
		if(!empty($country_code))
			{
			$data['country_code']  = $country_code;
			}
		elseif ($country_code = '0') 
			{
			$data['country_code'] = 'null';
			}
			else{
			$data['country_code'] = 'null';
			}
		$about_me  = $row->describe;
		if(!empty($about_me))
			{
			$data['about_me']  = $about_me;
			}
        else{
            $data['about_me'] = 'null';
        }
		
		$data['gender']  = $row->gender;
		$my_dob  = $row->dob;
		if(!empty($my_dob))
			{
			//$data['dob']  = gmdate('d-m-Y',$my_dob);
                $data['dob'] = date('d-m-Y', strtotime($my_dob));
			}
        else{
            $data['dob'] = '';
        }
        $joindate = $row->join_date;
        if(!empty($joindate))
        {
            $data['join_date']  = gmdate('F Y',$joindate);
        }
        else{
            $data['join_date'] = '';
        }
		
		$data['language']  = $row->language;
		
		
		$live  = $row->live;
		if(!empty($live))
			{
			$data['live']  = $live;
			}
else{
	$data['live'] = 'null';
}
	
		$details[]   = $data;
	}
	echo json_encode($details);
	}
	else {
			echo '[{"status":"This User is not available"}]';
	}
		
		
	}
	
public function edit_profile1()
	{ //echo"<pre>"; print_r($this->input->get()); exit;
		extract($this->input->get());
		$data['id']   = $user_id;
		$data['Fname'] = $Fname;
		$data['Lname'] = $Lname;
		$data['school']  = $school;
		$data['email']     = $email;
		$data['work']  = $work;
		$data['phnum']  = $phnum;
		$data['describe']  = $describe;
		$data['gender']  = $gender;
		$my_dob  = $dob;
		$final_date = date($my_dob);
		$start_date =  strtotime($final_date);
		$data['dob'] = $start_date;
		$data['language']  = $language;
		$data['live']  = $live;
	$query   = $this->db->where('id',$user_id)->get('profiles');
	//print_r($query->result());exit;
	if($query->num_rows() == 0)
	{
	$this->db->insert('profiles',$data);
	echo '[{"reason_message":"Your profiles added Successfully"}]';
	}
	else {
	$this->db->where('id',$user_id)->update('profiles',$data);
	echo '[{"reason_message":"Your profiles updated Successfully"}]';
	}
	}
protected function get_login($login)
	{
	//$this->db->where('username', $login);
	$this->db->where('email', $login);
	return $this->db->get($this->_table);
	}
	protected function _encode($password)
	{
		$majorsalt = $this->config->item('DX_salt');
		
		// if PHP5
		if (function_exists('str_split'))
		{
			$_pass = str_split($password);
		}
		// if PHP4
		else
		{
			$_pass = array();
			if (is_string($password))
			{
				for ($i = 0; $i < strlen($password); $i++)
				{
					array_push($_pass, $password[$i]);
				}
			}
		}

		// encrypts every single letter of the password
		foreach ($_pass as $_hashpass)
		{
			$majorsalt .= md5($_hashpass);
		}

		// encrypts the string combinations of every single encrypted letter
		// and finally returns the encrypted password
		return md5($majorsalt);
	}
	

	
	
	
	
public function signup()
 {
		$username          = $this->input->get('firstname');
		$lastname	   =$this->input->get('lastname');
		$email_id          = $this->input->get('email_id');    
		$password          = $this->input->get('password');
 		$join  = $this->input->get('join_date');
		$final_date = date($join);
		$start_date =  strtotime($final_date);
			  // $date_in = get_user_times(get_gmt_time(strtotime($final_date)), get_user_timezone1());
		
		if( !$this->dx_auth->is_email_available($email_id) )
		{
				echo '[{"status":"Sorry! This email has already been registered.","success":"0"}]';exit;			
		}
		
		if( strlen($password) < 4)
		{
		  echo '[{"status":"Sorry! Password has too less characters.","success":"0"}]';exit;	
		}
		
	 
			$data = $this->dx_auth->register($username,$password, $email_id );
  			$add['Fname']=$username;
			$add['Lname']=$lastname;
			$add['join_date']  = $start_date;
			//$add1['join_date']  = $start_date;
			$add['id']    = $data['user_id'];
			$add['email'] = $email_id;
			$this->db->insert('profiles',$add);
			//$this->db->insert('users',$add1);		
			
		 	$profile_pic = $this->Gallery->profilepic($data['user_id'], 2);
		 
		 
		 
		      $this->sendemailtouser($add);
		 
 			echo '[{"status":"Welcome to DropInn.","user_id":"'.$data['user_id'].'","Email":"'.$email_id.'","FirstName":"'.$username.'","Lastname":"'.$lastname.'","profile_pic":"'.$profile_pic.'","join_date":"'.$start_date.'","success":"1"}]';
			//exit;
	
	}
	   public function forgot_password(){
    	
    	extract($this->input->get());
	 
	 $this->load->model('users_model');
	 
	 $this->load->model('email_model');
	 
	 $where['email'] = $email;
	 
	 $result = $this->users_model->get_data('users',$where);
	
	 if($result->num_rows() != 0)
	 {
	    $to = $result->row()->email;	
	 }
	 else {
		 echo '[{"status":"no data"}]';exit;
	 }
	 $config = Array(
        	'mailtype' => 'html',
        );
		$this->load->library('email',$config);
		$this->email->set_newline('\r\n');
		
		$this->email->from('dropinnmobile@gmail.com', 'DropInn');
		$this->email->to($to);
		$this->email->subject('Reset Your Password');
	 $data['username'] = $result->row()->username;
	 
	 $query1                  = $this->db->get_where('settings', array('code' => 'SITE_TITLE'));
	 $data['site_title']      = $query1->row()->string_value;
			
	$query3                  = $this->db->get_where('settings', array('code' => 'SITE_LOGO'));
	$data['logo']     		 = base_url().'images/'.$query3->row()->string_value;

$logo = $this->db->get_where('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
	 
	 $data['link'] = base_url().'mobile/user/link_confirm?email='.$where['email'];
	//print_r($data['link']);exit;
     $template = '<table cellspacing="0" cellpadding="0" width="678" style="border:1px solid #e6e6e6; background:#fff;  font-family:Arial, Helvetica, sans-serif; -moz-border-radius: 16px; -webkit-border-radius:16px; -khtml-border-radius: 16px; border-radius: 16px; -moz-box-shadow: 0 0 4px #888888; -webkit-box-shadow:0 0 4px #888888; box-shadow:0 0 4px #888888;">
	            <tr>
				<td>
				<table background="'.base_url().'images/email/head_bg.png" width="676" height="156" cellspacing="0" cellpadding="0">
				<tr>
				<td style="vertical-align:top;">
				</tr>
				</table>
				</td>
				</tr>
				<tr>
				<td style="padding:0 10px; font-size:14px;">
				<table style="width: 100%;" cellspacing="10" cellpadding="0">
				<tbody>
				<tr>
				<td style="color:#0271b8">Hi '.$data['username'].',</td>
				</tr>
				<tr>
				<td>
				<a target="_blank" href='.$data['link'].'>Reset Password</a>
				</td>
				</tr>
				<tr>
				<td>
				<p style="margin: 0 10px 0 0;">--</p>
				<p style="margin: 0 0 10px 0;">Thanks and Regards,</p>
				<p style="margin: 0 10px 0 0;"></p>
				<p style="margin: 0px;">DropInn Team</p>
				</td>
				</tr>
				</tbody>
				</table>				
				</td>
                </tr>
				<tr>
				<td>
				<table cellpadding="0" cellspacing="0" background="'.base_url().'images/email/footer.png" width="676" height="58" style="text-align:center;">
				<tr>
				<td style="font-size:13px; padding:6px 0 0 0; color:#333333;">Copyright 2014 - 2015 <span style="color:#0271b8;">'.$this->dx_auth->get_site_title().'.</span> All Rights Reserved.</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>';
	// print_r($template);exit;
	 $this->email->message($template);
	 
		 echo '[{"status":"Mail successfully sent."}]';
		return $this->email->send();
 }
 function link_confirm()
   {
   	extract($this->input->get());
	
	$this->load->model('users_model');
	
	$data['email'] = $email;
	
	$result = $this->users_model->get_data('users',$data);
    
	if($result->num_rows() != 0)
	{
		$data1['id'] = $result->row()->id;
		
		$data1['message_element']     = "reset_pwd";
        $message = $this->load->view('template',$data1,true);
        echo $message;
	}
	else {
		echo 'Sorry! this is not a valid link';
	}
	   
   }
public function reset_pwd()
  {
  	 $pwd = $this->_encode($this->input->post('confirm_pwd'));
	 $password = crypt($pwd);
	 
	 $this->load->model('users_model');
	 
	 $data['password'] = $password;
	 
	 $where['id'] = $this->uri->segment(4);
	 
	 $this->users_model->update_data('users',$data,$where);
	 
	 echo 'Your password has successfully reset.';	
         
           redirect('users/signin');

  }
	public function index()
	{
	}
	/*public function my_profile()
	{
		$user_id = $this->input->get("user_id");
		$email   = $this->input->get("email");
		
		//$query   = $this->db->where('id',$user_id)->get('profiles');
        
        //$query1 = $this->db->where('id', $user_id)->get('users');
        
        $query1 = $this->db->distinct()->select('users.id, users.email, profiles.*')->join('profiles', "profiles.email=users.email")->where("users.id", $user_id)->order_by('id', 'desc')->get('users');
        
        echo $query1;
        exit();
        
		if($query1->num_rows()!=0)
        {
		foreach($query1->result() as $row)
		{
			$data['user_id']   = $row->id;
            $email   = $row->email;
            
            $query2   = $this->db->where('email',$email)->get('profile_picture');
            if($query2->num_rows()!=0)
            {
                $data['profile_pic']  = $query1->row()->src;
                $data['email'] = $email;
            }
            else
            {
                $data['profile_pic'] = 'null';
                $data['email'] = $email;
            }
            
			$Fname   = $row->Fname;
			if(!empty($Fname))
			{
			$data['Fname']  = $Fname;
			}
            else
            {
                $data['Fname'] = 'null';
            }
			$Lname  = $row->Lname;
            if(!empty($Lname))
			{
			$data['Lname']  = $Lname;
			}
            else
            {
                $data['Lname'] = 'null';
            }
            
        $work  = $row->work;
			
		if(!empty($work))
			{
			$data['work']  = $work;
			}
        else
            {
            $data['work'] = 'null';
            }
            
            
        $data['school']  = $row->school;
			
        $live  = $row->live;
            
		if(!empty($live))
			{
			$data['live']  = $live;
			}
        else
            {
            $data['live'] = 'null';
            }
            
        $about_me   = $row->describe;
            if(!empty($about_me))
			{
			$data['about_me']  = $about_me;
			}
            else
            {
                $data['about_me'] = 'null';
            }
            
            
			$created   = $row->join_date;
			if(!empty($created))
			{
			$data['join_date']  = gmdate('F Y',$created);
			}
            else
            {
                $data['join_date'] = '';
            }
			$details[]   = $data;
		}
	echo json_encode($details);
	}
	else
    {
			echo '[{"status":"This User is not available"}]';
	}
	}*/
    
    public function my_profile()
    {
        $user_id = $this->input->get("user_id");
        $email   = $this->input->get("email");
        
        //$query   = $this->db->where('id',$user_id)->get('profiles');
        
        //$query1 = $this->db->where('id', $user_id)->get('users');
        
        
        
        $query1 = $this->db->distinct()->select('users.id as user_id,users.username as username, users.email, profiles.*')->join('profiles', "profiles.email=users.email")->where("users.id", $user_id)->where("profiles.id", $user_id)->order_by('id', 'desc')->get('users');
        
        
        if($query1->num_rows()!=0)
        {
            foreach($query1->result() as $row)
            {
                $data['user_id']   = $row->id;
                $email   = $row->email;
                
                $query2 = $this->db->where('id',$row->id)->get('users');
                $fbid = $query2->row()->fb_id;
                
                $query3 = $this->db->where('email',$email)->get('profile_picture');
                
                if($fbid!=0)
                {
                    $data['profile_pic']  = $query3->row()->src;
                }
                else
                {
                    $data['profile_pic']= $this->Gallery->profilepic($row->id, 2);
                }
                
                //$data['profile_pic'] = $this->Gallery->profilepic($row->id, 2);
                $data['email'] = $email;
                
                //$query2   = $this->db->where('email',$email)->get('profile_picture');
                
                /*if($query2->num_rows()!=0)
                {
                    $data['src']  = $query2->row()->src;
                    
                }
                else
                {
                    $data['src'] = '';
                }*/
                $username   = $row->username;
                if(!empty($username))
                {
                    $data['username']  = $username;
                }
                else
                {
                    $data['username'] = 'null';
                }
                
                $Fname   = $row->Fname;
                if(!empty($Fname))
                {
                    $data['Fname']  = $Fname;
                }
                else
                {
                    $data['Fname'] = 'null';
                }
                $Lname  = $row->Lname;
                if(!empty($Lname))
                {
                    $data['Lname']  = $Lname;
                }
                else
                {
                    $data['Lname'] = 'null';
                }
                
                $work  = $row->work;
                
                if(!empty($work))
                {
                    $data['work']  = $work;
                }
                else
                {
                    $data['work'] = 'null';
                }
                
                
                $data['school']  = $row->school;
                
                $live  = $row->live;
                
                if(!empty($live))
                {
                    $data['live']  = $live;
                }
                else
                {
                    $data['live'] = 'null';
                }
                
                $about_me   = $row->describe;
                if(!empty($about_me))
                {
                    $data['about_me']  = $about_me;
                }
                else
                {
                    $data['about_me'] = 'null';
                }
                
                
                $created   = $row->join_date;
                if(!empty($created))
                {
                    $data['join_date']  = gmdate('F Y',$created);
                }
                else
                {
                    $data['join_date'] = '';
                }
                $details[]   = $data;
            }
            echo json_encode($details);
        }
        else
        {
            echo '[{"status":"This User is not available"}]';
        }
    }

	
	
	
	
	public function edit_profile()
	{
		extract($this->input->get());
		$data['id']   = $user_id;
        $data['Fname'] = $fname;
        $data['Lname'] = $lname;
		$data['school']  = $school;
		$data['email']     = $email;
		$data['work']  = $work;
		$data['phnum']  = $phnum;
		$data['country_code']  = $country_code;
		$data['describe']  = $about_me;
		$data['gender']  = $gender;
		$my_dob  = $dob;
		//$final_date = date($my_dob);
		$start_date =  date('m/d/Y', strtotime($my_dob));
		$data['dob'] = $start_date;
		$data['language']  = $language;
		$data['live']  = $live;
		$query   = $this->db->where('id',$user_id)->get('profiles');
		$query1  = $this->db->where('id',$user_id)->get('users'); //Also update users table email
	//print_r($query->result());exit;
	if($query->num_rows() == 0)
	{
	if($query1->num_rows() == 0)
	{
	$this->db->insert('users',$data['email']);	//Insert users table email field also
	}	
	$this->db->insert('profiles',$data);
	echo '[{"reason_message":"Your profiles added Successfully"}]';
	}
	else {
	$this->db->where('id',$user_id)->update('profiles',$data);
	echo '[{"reason_message":"Your profiles updated Successfully"}]';
	}
	}
    public function updatedesc()
    {
        extract($this->input->get());
        $data['id']   = $user_id;
        $data['describe']  = $about_me;
    
        $query   = $this->db->where('id',$user_id)->get('profiles');
        //$query1  = $this->db->where('id',$user_id)->get('users'); //Also update users table email
        //print_r($query->result());exit;
        if($query->num_rows() == 0)
        {
            $this->db->insert('profiles',$data);
            echo '[{"reason_message":"Your data added Successfully"}]';
        }
        else {
            $this->db->where('id',$user_id)->update('profiles',$data);
            echo '[{"reason_message":"Your data updated Successfully"}]';
        }
    }
	

	public function sendemailtouser($info) {
		$config = Array(
        	'mailtype' => 'html',
        );
		$this->load->library('email',$config);
		$this->email->set_newline('\r\n');
		//$template = get_email_template('register');
		$this->email->from('dropinnmobile@gmail.com', 'DropInn');
		$this->email->to($info['email']);
		
		$this->email->subject('Welcome to DropInn');
		$query1                  = $this->db->get_where('settings', array('code' => 'SITE_TITLE'));
			$data['site_title']      = $query1->row()->string_value;
		$query3                  = $this->db->get_where('settings', array('code' => 'SITE_LOGO'));

			$data['logo']     		 = base_url().'images/'.$query3->row()->string_value;

			$logo = $this->db->get_where('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
			
		$str = '<p>Hi '.$info['Fname'].',</p>';
		//$this->load->library('encrypt');
		//$encrypted_email = $this->encrypt->encode($info['email']);
		$emailencode=base64_encode($info['email']);
		 $end_msg = "And thanks for joining our global community! Here's a quick guide to help you get started.<br>
		 <h2>Search</h2>
		 <h3> Find the perfect place to stay </h3>
		 Whether you're planning a weekend getaway or a trip around the world, we'll help you find the place that's right for you.<br>
		<h2> Contact </h2>
		 <h3>  Message a few hosts </h3>
		 <p>When you find a place (or two or three) you like, contact the host to learn more. Feel free to message as many hosts as you like.  </p><br>
		 <h2>Book</h2>
		 <h3>  Make your reservation  </h3>
		 <p>Once you're ready to make a reservation, book the place. You won't be charged until the host accepts. And then you're ready to pack your bags! </p><br> 
		 
		 ";
		 $template='<table cellspacing="0" cellpadding="0" width="678" style="border:1px solid #e6e6e6; background:#fff;  font-family:Arial, Helvetica, sans-serif; -moz-border-radius: 16px; -webkit-border-radius:16px; -khtml-border-radius: 16px; border-radius: 16px; -moz-box-shadow: 0 0 4px #888888; -webkit-box-shadow:0 0 4px #888888; box-shadow:0 0 4px #888888;">
	            <tr>
																	<td>
																					<table background="'.base_url().'images/email/head_bg.png" width="676" height="156" cellspacing="0" cellpadding="0">
																									<tr>
																													<td style="vertical-align:top;">
																																	<img src="'.base_url().'logo/'.$logo.'" alt="'.$this->dx_auth->get_site_title().'" style=" margin:10px 0 0 20px;" />
																																</td>
																																 
																												</tr>
																								</table>
																				</td>
																</tr>
																<tr>
																	<td style="padding:0 10px; font-size:14px;">
<table style="width: 100%;" cellspacing="10" cellpadding="0">
<tbody>
<tr>
<td>Hi '.$info['Fname'].',</td>
</tr>
<tr>
<td>
<p>'.$end_msg.'</p>
</td>
</tr>
<tr>
<td>
<p style="margin: 0 10px 0 0;">--</p>
<p style="margin: 0 0 10px 0;">Thanks and Regards,</p>
<p style="margin: 0 10px 0 0;"></p>
<p style="margin: 0px;">DropInn Team</p>
</td>
</tr>
</tbody>
</table>				
				</td>
                  </tr>
																			<tr>
																			<td>
																			<table cellpadding="0" cellspacing="0" background="'.base_url().'images/email/footer.png" width="676" height="58" style="text-align:center;">
																			<tr>
																			<td style="font-size:13px; padding:6px 0 0 0; color:#333333;">Copyright 2014 - 2015 <span style="color:#0271b8;">'.$this->dx_auth->get_site_title().'.</span> All Rights Reserved.</td>
																			</tr>
																			</table>
																			</td>
																			</tr>
																			</table>';

		 $this->email->message($template);
		 
		return $this->email->send();
	}




public function fb_signup()
{
	    $username           = 	$this->input->get('fname');
		$email_id           = 	$this->input->get('email_id');    
		$fb_id              = 	$this->input->get('fb_id');
		$fname				= 	$this->input->get('fname');
		$lname				= 	$this->input->get('lname');
		$live				= 	$this->input->get('live');
		$work				= 	$this->input->get('work');
		$phnum				= 	$this->input->get('phnum');
		$describe			= 	$this->input->get('describe');
		$src				= 	$this->input->get('src');
		$user_agent			= 	$this->input->get('user_agent');
		$last_ip			= 	$this->input->get('last_ip');
		
		$join  = $this->input->get('join_date');
		$final_date = date($join);
		$start_date =  strtotime($final_date);
			   //$date_in = get_user_times(get_gmt_time(strtotime($final_date)), get_user_timezone1());
		
		
		 $id_query1 = $this->db->where('email',$email_id)->get('profile_picture');
		 //$row_email= $id_query1->row()->email;
		  if($id_query1->num_rows() != 0)
          {
			$img['email'] = $email_id;
		    $img['src'] = $src;
			//$this->db->where('email',$email_id)->update('profile_picture',$img);
          }
		  else
		  	{
		  	$img['email'] = $email_id;
		    $img['src'] = $src;
			//$this->db->where('email',$email_id)->update('profile_picture',$img);
			$this->db->insert('profile_picture',$img);
		  	}
		
 		if( !$this->dx_auth->is_email_available($email_id) )
		{
				//echo '[{"status":"Sorry! This email has already been registered."}]';exit;
				$this->load->model('users_model');
				$users=$this->users_model->getUserIdByEmail($email_id);
				echo "[{\"status\":\"Sorry! This email has already been registered\",\"user_id\":\"".$users['id']."\"}]";exit;				
		}
        else
        {
           $id_query = $this->db->select('id')->limit(1)->order_by('id','desc')->from('users')->get();
		   
		   //$row_email= $id_query->row()-email;
 
		  if($id_query->num_rows() != 0)
          {
              foreach($id_query->result() as $row)
              {
                  $id = $row->id+1;
                  //$email = $row->email;
              }
          }
			$add['Fname']    = $fname;
			$add['Lname']    = $lname;
			$add['id']       = $id;
			$add['email']    = $email_id;
			$add['live']     = $live;
			$add['work']	 = $work;
			$add['phnum']	 = $phnum;
			$add['describe'] = $describe;
			$add['join_date'] = $start_date;
			//$add1['join_date'] = $start_date;
		    $this->Common_model->insertData('profiles', $add);
			
		
		   // $img['email'] = $email_id;
		   // $img['src'] = $src;
			//$this->Common_model->insertData('profile_picture', $img);
			//$this->db->insert('profile_picture',$img);
			$notification                     = array();
			$notification['user_id']		  = $id;
			$notification['new_review ']	  = 1;
			$notification['leave_review']	  = 1;
			$this->Common_model->insertData('user_notification', $notification);
			//$this->db->insert('users',$add1);
			$last_login=date('Y-m-d h:i:s', time());
			$auto['key_id']  =  md5($last_login);
			$auto['user_id']  = $id;
			$auto['user_agent'] = $user_agent;
			$auto['last_ip']  =  $last_ip;
			$auto['last_login']  =  $last_login;
			$this->Common_model->insertData('user_autologin', $auto);
			
            $data = $this->dx_auth->register($username, $fb_id, $email_id, $fb_id);		
			$update_data['photo_status'] = '2';
			$this->db->where('username',$username)->update('users',$update_data);

			echo "[{\"status\":\"Successfully registered\",\"user_id\":".$id.",\"email\":".$email_id."}]";
        }
}
    
    public function fb_signin()
    {
        $username           = 	$this->input->get('fname');
        $email_id           = 	$this->input->get('email_id');
        $fb_id              = 	$this->input->get('fb_id');
        $fname				= 	$this->input->get('fname');
        $lname				= 	$this->input->get('lname');
        $live				= 	$this->input->get('live');
        $work				= 	$this->input->get('work');
        $phnum				= 	$this->input->get('phnum');
        $describe			= 	$this->input->get('describe');
        $src				= 	$this->input->get('src');
        $user_agent			= 	$this->input->get('user_agent');
        $last_ip			= 	$this->input->get('last_ip');
        
        $join  = $this->input->get('join_date');
        $final_date = date($join);
        $start_date =  strtotime($final_date);
        //$date_in = get_user_times(get_gmt_time(strtotime($final_date)), get_user_timezone1());
        
        $query = $this->db->distinct()->where('email', $email_id)->where('fb_id', $fb_id)->get('users');
        
        if($query->num_rows() != 0)
        {
            $user_id = $query->row()->id;
            $emailid = $query->row()->email;
            
            $query2   = $this->db->where('email',$email_id)->get('profile_picture');
            
            if($query2->num_rows()!=0)
            {
                $src  = $query2->row()->src;
                
            }
            else
            {
                $src = '';
            }
            echo "[{\"status\":\"Successfully logged in\",\"user_id\":".$user_id.",\"email\":".$emailid.",\"imgurl\":".$src."}]";
        }
        else
        {
            echo "[{\"status\":\"Email ID doesn't exist\"}]";
        }
}

public function google_signup()
{
	    $username           = 	$this->input->get('fname');
		$email_id           = 	$this->input->get('email_id');    
		$gp_id              = 	$this->input->get('gp_id');
		$fname				= 	$this->input->get('fname');
		$lname				= 	$this->input->get('lname');
		$live				= 	$this->input->get('live');
		$work				= 	$this->input->get('work');
		$phnum				= 	$this->input->get('phnum');
		$describe			= 	$this->input->get('describe');
		$src				= 	$this->input->get('src');
		$user_agent			= 	$this->input->get('user_agent');
		$last_ip			= 	$this->input->get('last_ip');
		
		$join  = $this->input->get('join_date');
		$final_date = date($join);
		$start_date =  strtotime($final_date);
			   //$date_in = get_user_times(get_gmt_time(strtotime($final_date)), get_user_timezone1());
		
		 $id_query1 = $this->db->where('email',$email_id)->get('profile_picture');
		 //$row_email= $id_query1->row()->email;
		  if($id_query1->num_rows() != 0)
          {
			$img['email'] = $email_id;
		    $img['src'] = $src;
			//$this->db->where('email',$email_id)->update('profile_picture',$img);
          }
		  else
		  	{
		  	$img['email'] = $email_id;
		    $img['src'] = $src;
			//$this->db->where('email',$email_id)->update('profile_picture',$img);
			$this->db->insert('profile_picture',$img);
		  	}
		
 		if( !$this->dx_auth->is_email_available($email_id) )
		{
				//echo '[{"status":"Sorry! This email has already been registered."}]';exit;
				$this->load->model('users_model');
				$users=$this->users_model->getUserIdByEmail($email_id);
				echo "[{\"status\":\"Sorry! This email has already been registered\",\"user_id\":\"".$users['id']."\"}]";exit;				
		}
        else
        {
           $id_query = $this->db->select('id')->limit(1)->order_by('id','desc')->from('users')->get();
		   
		   //$row_email= $id_query->row()-email;
 
		  if($id_query->num_rows() != 0)
          {
              foreach($id_query->result() as $row)
              {
                  $id = $row->id+1;
                  //$email = $row->email;
              }
          }
			$add['Fname']    = $fname;
			$add['Lname']    = $lname;
			$add['id']       = $id;
			$add['email']    = $email_id;
			$add['live']     = $live;
			$add['work']	 = $work;
			$add['phnum']	 = $phnum;
			$add['describe'] = $describe;
			$add['join_date'] = $start_date;
			//$add1['join_date'] = $start_date;
		    $this->Common_model->insertData('profiles', $add);
			
		
		   // $img['email'] = $email_id;
		   // $img['src'] = $src;
			//$this->Common_model->insertData('profile_picture', $img);
			//$this->db->insert('profile_picture',$img);
			$notification                     = array();
			$notification['user_id']		  = $id;
			$notification['new_review ']	  = 1;
			$notification['leave_review']	  = 1;
			$this->Common_model->insertData('user_notification', $notification);
			//$this->db->insert('users',$add1);
			$last_login=date('Y-m-d h:i:s', time());
			$auto['key_id']  =  md5($last_login);
			$auto['user_id']  = $id;
			$auto['user_agent'] = $user_agent;
			$auto['last_ip']  =  $last_ip;
			$auto['last_login']  =  $last_login;
			$this->Common_model->insertData('user_autologin', $auto);
			
            $data = $this->dx_auth->register($username,$email_id, $email_id,'');		
			$update_data['photo_status'] = '2';
			$update_data['google_id'] = $gp_id;
			$this->db->where('username',$username)->update('users',$update_data);

			echo "[{\"status\":\"Successfully registered\",\"user_id\":".'"'.$id.'"'.",\"email\":".'"'.$email_id.'"'."}]";
        }
}


 public function google_signin()
    {
        $username           = 	$this->input->get('fname');
        $email_id           = 	$this->input->get('email_id');
        $gp_id              = 	$this->input->get('google_id');
        $fname				= 	$this->input->get('fname');
        $lname				= 	$this->input->get('lname');
        $live				= 	$this->input->get('live');
        $work				= 	$this->input->get('work');
        $phnum				= 	$this->input->get('phnum');
        $describe			= 	$this->input->get('describe');
        $src				= 	$this->input->get('src');
        $user_agent			= 	$this->input->get('user_agent');
        $last_ip			= 	$this->input->get('last_ip');
        
        $join  = $this->input->get('join_date');
        $final_date = date($join);
        $start_date =  strtotime($final_date);
        //$date_in = get_user_times(get_gmt_time(strtotime($final_date)), get_user_timezone1());
        
        $query = $this->db->distinct()->where('email', $email_id)->where('google_id', $gp_id)->get('users');
        
        if($query->num_rows() != 0)
        {
            $user_id = $query->row()->id;
            $emailid = $query->row()->email;
            
            $query2   = $this->db->where('email',$email_id)->get('profile_picture');
            
            if($query2->num_rows()!=0)
            {
                $src  = $query2->row()->src;
                
            }
            else
            {
                $src = '';
            }
            echo "[{\"status\":\"Successfully logged in\",\"user_id\":".'"'.$user_id.'"'.",\"email\":".'"'.$emailid.'"'.",\"imgurl\":".'"'.$src.'"'."}]";
        }
        else
        {
            echo "[{\"status\":\"Email ID doesn't exist\"}]";
        }
}



function user_data()
{
	$user_id = $this->input->get('user_id');
	
	$condition = array('id'=>$user_id);
	
	$email = $this->db->get_where('users', array('id' => $user_id))->row()->email;
	$username = $this->db->get_where('users', array('id' => $user_id))->row()->username;
    $photo_status = $this->db->get_where('users', array('id' => $user_id))->row()->photo_status;
	
	$condition1 = array('email'=>$email);
	$result_picture = $this->Common_model->getTableData('profile_picture',$condition1);
	$src = base_url().'images/no_avatar.jpg';
	foreach($result_picture->result() as $row)
	{
		$src = $row->src;
    }
	
	if($photo_status == 1)
	{
		$src = $this->Gallery->profilepic($user_id,2);
	}
	
	$result_user = $this->Common_model->getTableData('profiles',$condition);
	if($result_user->num_rows() != 0 )
	{
	foreach($result_user->result() as $row)
	{
		echo "[{ \"id\":".$row->id.",\"Fname\":\"".$row->Fname."\",\"Lname\":\"".$row->Lname."\",\"live\":\"".$row->live."\",
		\"work\":\"".$row->work."\",\"phnum\":\"".$row->phnum."\",\"describe\":\"".$row->describe."\",
		\"email\":\"".$row->email."\",\"username\":\"".$username."\",\"image_src\":\"".$src."\"}]";
	}
	}
	else {
		echo "[{\"status\":\"Incorrect User id\"}]";
		}
	
}

function edit_user_data()
{
	$user_id = $this->input->get('user_id');
	$gender   = $this->input->get('gender');
	$dob    = $this->input->get('dob');
	$school    = $this->input->get('school'); 
	$Fname    = $this->input->get('Fname');
	$Lname    = $this->input->get('Lname');
	$phnum    = $this->input->get('phnum'); 
	$live     = $this->input->get('live');
	$work     = $this->input->get('work');
	$describe = $this->input->get('desc'); 
	$data2['email']    = $this->input->get('email');
					$data2['timezone'] = $this->input->get('timezones'); 
					                            
	if(!$user_id)
	{
		echo "[{\"status\":\"Please enter user id\"}]";
	}
	else {
		
	$data = array(
									'Fname'    => $this->input->get('Fname'),
									'Lname'    => $this->input->get('Lname'),
									'phnum'    => $this->input->get('phnum'),
									'live'     => $this->input->get('live'),
									'work'     => $this->input->get('work'),
									'describe' => $this->input->get('desc'),
									'email'    => $this->input->get('email'),
 									'gender'   => $this->input->get('gender'),
									'dob'    => $this->input->get('dob'),
									'school'    => $this->input->get('school') 
	
	
								 );					
											
		$param     = $user_id;	
		//$data2['photo_status'] = 1;
		$this->db->where('id', $param);
		$this->db->update('users', $data2);
		$rows = $this->Common_model->getTableData('profiles', array('id' => $param))->num_rows();
		
		}	
					if($Fname != '' && $Lname != '' && $data2['email'] != '')
					{
					if($rows == 0)
					{
					$data['id']  = $param;
					if($Fname != '' && $Lname != '' && $phnum != '' && $live != '' && $work != '' && $describe != '' && $gender!='' && dob!='' && school!='' )
					{
					$this->Common_model->insertData('profiles', $data);
					}
					}
					else
					{
					$this->db->where('id', $param);
					if($Fname != '' && $Lname != '' && $data2['email'] != '')
					{
					$this->db->update('profiles', $data);
					}
					}
					if($data2['email'] != '' && $data2['timezone'] != '')
					{
						$this->db->where('id', $param);
					$this->db->update('users', $data2);
					}
					 echo "[{\"status\":\"Successfully updated\"}]";
					}
					
					else
						{
							echo "[{\"status\":\"Failed\"}]";
					}
				

}
 public function image_upload() 
	{
			$status = "";
			$msg = "";
			$file_element_name = 'uploadedfile';
			$user_id = $this->input->get('user_id');
			if ($status != "error")	
			{
				
	//	$config['upload_path'] = '/var/ftp/virtual_users/tastenote/tastenote.com/files/gastronote/';			
		 $config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$user_id.'/';
		  //Set the upload path
				//$config['upload_path'] = '/opt/lampp/htdocs/vignesh/dropinn-1.6.6/images/'.$user_id.'/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
				$config['encrypt_name']	= TRUE; // Change image name
				$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if(!$this->upload->do_upload($file_element_name)){
						$status = 'error';
						$msg = $this->upload->display_errors('','');
						$data = "";
					}
					else {
						$data = $this->upload->data(); // Get the uploaded file information

						$this->load->library('image_lib');
						$config['image_library'] = 'gd2';
						$config['source_image']	= $data['full_path'];
						$config['new_image']    = 'images/users/'.$user_id.'/'.$data['raw_name'].$data['file_ext'];
						$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] = TRUE;
						//$config['width'] = '260';
						$config['width'] = '205';
						$config['height'] = '1';
						$config['master_dim'] = 'width';
						
						 $this->image_lib->initialize($config);
						 if(!$this->image_lib->resize()) 
							 echo $this->image_lib->display_errors();	
						 
						$config['new_image']    = 'images/users/'.$user_id.'/'.$data['raw_name'].'_detail'.$data['file_ext'];
						$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] = TRUE;
						$config['width'] = '600';
						$config['height'] = '1';
						$config['master_dim'] = 'width';
						
						 $this->image_lib->initialize($config);
						 if(!$this->image_lib->resize()) 
							 echo $this->image_lib->display_errors();		
                         
                                             
                                                                       
                          $this->load->library('SimpleImage');
                         $img = new SimpleImage();
                         $img->load($data['full_path'])->resize(47, 48)->save('images/users/'.$user_id.'/'.$data['raw_name'].'_small_thumb.png');
                    
                         $map_path = getcwd();
                         $image_map = base_url().'images/users/'.$user_id.'/'.$data['raw_name'].'_small_thumb.png'; 
                         $layout_image = base_url().'/images/map_layout.png';
                         $merged = explode('_small_thumb.',$data['raw_name'].'_small_thumb.png');
                         $image_merged = $map_path.'images/'.$user_id.'/'.$merged[0].'_map.png';  
             
                         merge( $layout_image , $image_map , $image_merged);
                         
                              
					
						if($data) {
							$status = "success";
							$msg = "File successfully uploaded";
						}
						else {
							unlink($data['full_path']); // delete the file if not insert the details
							$status = "error";
							$msg = "Something went wrong when saving the file, please try again.";
						}
						echo $data['raw_name'].$data['file_ext'];
					}
					
				@unlink($_FILES[$file_element_name]);
			}
			// Response as json
			//echo json_encode(array('status' => $status, 'msg' => $msg, 'upload_data' => $data));
		
				
	} 

		/* public function image_upload()
    {
        $id = $this->input->get('user_id');
        $file_element_name = 'uploadedfile';
        $this->path     = realpath(APPPATH . '../images/users');
        
        //$filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users'.$id;
            $status = "";
            $msg = "";
            $file_element_name = 'uploadedfile';
            
            if ($status != "error")    
            {
                if(!is_dir($this->path.'/'.$id))
            {
                    mkdir($this->path.'/'.$id, 0777, true);
                    
            }
                //if(!file_exists($filename)) 
        //{
     //    mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/users'.$id, 0777, true);
        //}
                
            $config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id;  //Set the upload path
        
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
                //$config['encrypt_name']    = TRUE; // Change image name
                $config['file_name'] = 'userpic.jpg';
                $config['overwrite'] = TRUE;
                //$this->db->query('update users set photo_status = 1 where id = .$id');
                $this->load->library('upload', $config);
                
                    $this->upload->initialize($config);
            if(!$this->upload->do_upload($file_element_name)){
                        
                        $status = 'error';
                        $msg = $this->upload->display_errors('','');
                        $data = "";
                                        echo '[{"status":"'.$msg.'"}]'; 
                        
                        
                    }
                    else {
                        
                        
                        $data = $this->upload->data();
                        //base_url().'images/users/'.$user_id.'/userpic.jpg'
                //$thumb1 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_thumb.jpg';
                //GenerateThumbFile($target_path,$thumb1,107,78);
                //$thumb2 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_profile.jpg';
                //GenerateThumbFile($target_path,$thumb2,209,209);
                //$thumb3 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_home.jpg';
                //GenerateThumbFile($target_path,$thumb3,40,40);
                //$this->db->query('update users set photo_status = 1 where id = '.$id);
                $image = base_url().'/images/users/'.$id.'/userpic_thumb.jpg'; 
                $config1['image_library'] = 'gd2';
                $config1['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic.jpg';
                $config1['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic_thumb.jpg';
                //$config1['create_thumb'] = TRUE;
                $config1['maintain_ratio'] = TRUE;
                $config1['width'] = 107;
                $config1['height'] = 78;

                $this->load->library('image_lib');
                
                $this->image_lib->initialize($config1);

                if ( ! $this->image_lib->resize())
                {
                    $resize = $this->image_lib->display_errors();
                }
                $image = base_url().'/images/users/'.$id.'/userpic_profile.jpg'; 
                $config2['image_library'] = 'gd2';
                $config2['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic.jpg';
                $config2['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic_profile.jpg';
                //$config1['create_thumb'] = TRUE;
                $config2['maintain_ratio'] = TRUE;
                $config2['width'] = 209;
                $config2['height'] = 209;

                $this->load->library('image_lib');
                
                $this->image_lib->initialize($config2);

                if ( ! $this->image_lib->resize())
                {
                    $resize = $this->image_lib->display_errors();
                }
                $image = base_url().'/images/users/'.$id.'/userpic_home.jpg'; 
                $config3['image_library'] = 'gd2';
                $config3['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic.jpg';
                $config3['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic_home.jpg';
                //$config1['create_thumb'] = TRUE;
                $config3['maintain_ratio'] = TRUE;
                $config3['width'] = 40;
                $config3['height'] = 40;

                $this->load->library('image_lib');
                
                $this->image_lib->initialize($config3);

                if ( ! $this->image_lib->resize())
                {
                    $resize = $this->image_lib->display_errors();
                }
                        $data = $this->upload->data(); // Get the uploaded file information
                        //echo base_url().'images/users/'.$id.'/userpic.jpg';
                        
                        $resize = base_url().'images/users/'.$id.'/userpic.jpg';   
    
                echo '[{"image":"'.$image.'","resize":"'.$resize.'"}]';exit;
                        
                    }
                    
                @unlink($_FILES[$file_element_name]);
            }
            
    }*/
public function upload(){
	$status = "";
			$msg = "";
			$file_element_name = 'uploadedfile';
			$room_id = $this->input->get('room_id');
			 $this->path     = realpath(APPPATH . '../images');
			
			if ($status != "error")	
			{
				if(!is_dir($this->path.'/'.$room_id))
            {
                    mkdir($this->path.'/'.$room_id, 0777, true);
                    
            }
				
			$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'; //Set the upload path
			
			$config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
			
			$config['encrypt_name']	= TRUE; // Change image name
			
			 //$config['file_name']    = base64_encode("" . mt_rand());
			
			//$url=urlencode(base64_encode($file_element_name));
			
			$this->load->library('upload', $config);
			
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('','');
				$data = "";
				
				echo '[{"status":"'.$msg.'"}]'; 
			}
			else 
			{
				$data = $this->upload->data(); // Get the uploaded file information
				
				//$data = file_put_contents($file_element_name, base64_encode($_POST[$data1]));
				
                $image = base_url().'images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];   
				
				$config1['image_library'] = 'gd2';
				$config1['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config1['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].'_100_100'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config1['maintain_ratio'] = TRUE;
				$config1['width'] = 100;
				$config1['height'] = 100;

				$this->load->library('image_lib');
				
				$this->image_lib->initialize($config1);

				if ( ! $this->image_lib->resize())
				{
   				 $resize = $this->image_lib->display_errors();
				}
				
				$resize = base_url().'images/'.$room_id.'/'.$data['raw_name'].'_100_100'.$data['file_ext'];
							
				$config2['image_library'] = 'gd2';
				$config2['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config2['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].'_320_320'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config2['maintain_ratio'] = TRUE;
				$config2['width'] = 320;
				$config2['height'] = 320;
				
				//$this->load->library('image_lib');

				$this->image_lib->initialize($config2);

				if ( ! $this->image_lib->resize())
				{
   				 $resize1 = $this->image_lib->display_errors();
				}
				
				$resize1 = base_url().'images/'.$room_id.'/'.$data['raw_name'].'_320_320'.$data['file_ext'];
		        
				$data1['list_id'] = $room_id;
				$data1['image'] = $resize1; 
				$this->db->insert('list_photo', $data1);
				echo $resize1;exit;				
			}
				
			@unlink($_FILES[$file_element_name]);
	        }
			//$data['message_element']     = "test";
           //$this->load->view('template',$data);
}

public function map_upload_old(){
	$status = "";
			$msg = "";
			$file_element_name = 'uploadedfile';
			$room_id = $this->input->get('room_id');
			 $this->path     = realpath(APPPATH . '../images/users');
			
			if ($status != "error")	
			{
				if(!is_dir($this->path.'/'.$room_id))
            {
                    mkdir($this->path.'/'.$room_id, 0777, true);
                    
            }
				
			$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$room_id.'/'; //Set the upload path
			
			$config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
			
			$config['encrypt_name']	= TRUE; // Change image name
			
			 //$config['file_name']    = base64_encode("" . mt_rand());
			
			//$url=urlencode(base64_encode($file_element_name));
			
			$this->load->library('upload', $config);
			
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('','');
				$data = "";
				
				echo '[{"status":"'.$msg.'"}]'; 
			}
			else 
			{
				$data = $this->upload->data(); // Get the uploaded file information
				
				//$data = file_put_contents($file_element_name, base64_encode($_POST[$data1]));
				
                 $image = base_url().'images/users'.$room_id.'/'.$data['raw_name'].$data['file_ext'];     
							
				$config2['image_library'] = 'gd2';
				$config2['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config2['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users'.$room_id.'/'.$data['raw_name'].'_320_320'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config2['maintain_ratio'] = TRUE;
				$config2['width'] = 700;
				$config2['height'] = 320;
				
				$this->load->library('image_lib');

				$this->image_lib->initialize($config2);

				if ( ! $this->image_lib->resize())
				{
   				 $resize1 = $this->image_lib->display_errors();
				}
				
				$resize1 = base_url().'images/users/'.$room_id.'/'.$data['raw_name'].'_320_320'.$data['file_ext'];
		        
				$data1['list_id'] = $room_id;
				$data1['map'] = $resize1; 
				$this->db->insert('list_photo', $data1);
				echo $resize1;exit;				
			}
				
			@unlink($_FILES[$file_element_name]);
	        }
			//$data['message_element']     = "test";
           //$this->load->view('template',$data);
}
public function post_upload()
	{
		$status = "";
			$msg = "";
			$alert = "Upload correct file";
			$file_element_name = 'uploadedfile';
			$room_id = $this->input->get('room_id');
			$user_id = $this->input->get('user_id');
			 $this->path     = realpath(APPPATH . '../images');
			
			if ($status != "error")	
			{
				if(!is_dir($this->path.'/'.$room_id))
            {
                    mkdir($this->path.'/'.$room_id, 0777, true);
                    
            }
				
			$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'; //Set the upload path
			
			$config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
			
			$config['encrypt_name']	= TRUE; // Change image name
			
			 //$config['file_name']    = base64_encode("" . mt_rand());
			
			//$url=urlencode(base64_encode($file_element_name));
			
			$this->load->library('upload', $config);
			
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('','');
				$data = "";
				
				echo '[{"status":"'.$msg.'"}]'; 
			}
			else 
			{
				$data = $this->upload->data(); // Get the uploaded file information
				
				//$data = file_put_contents($file_element_name, base64_encode($_POST[$data1]));
				
                		$image = base_url().'images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];

				//$this->db->where('list_id', $room_id)->update('list_photo', $image);
				
				$config1['image_library'] = 'gd2';
				$config1['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config1['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].'_100_100'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config1['maintain_ratio'] = TRUE;
				$config1['width'] = 100;
				$config1['height'] = 100;

				$this->load->library('image_lib');
				
				$this->image_lib->initialize($config1);

				if ( ! $this->image_lib->resize())
				{
   				 $resize = $this->image_lib->display_errors();
				}
				
				$resize = base_url().'images/'.$room_id.'/'.$data['raw_name'].'_100_100'.$data['file_ext'];
							
				$config2['image_library'] = 'gd2';
				$config2['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config2['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].'_375_375'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config2['maintain_ratio'] = TRUE;
				$config2['width'] = 375;
				$config2['height'] = 750;

				$this->image_lib->initialize($config2);

				if ( ! $this->image_lib->resize())
				{
   				 $resize1 = $this->image_lib->display_errors();
				}
				
				$resize1 = base_url().'images/'.$room_id.'/'.$data['raw_name'].'_375_375'.$data['file_ext'];
		      
				//echo '[{"resize1":"'.$resize1.'"}]';exit;	
				 echo '[{"image":"'.$image.'","resize":"'.$resize.'","resize1":"'.$resize1.'"}]';exit;			
			}
				
			@unlink($_FILES[$file_element_name]);
	        }
       //$data['message_element']     = "test";
       //$this->load->view('template',$data);
		
    }

    public function return_image1(){
	$room_id = $this->input->get('room_id');
	$user_id = $this->input->get('user_id');
	$image = $this->input->get('image');
	$resize = $this->input->get('resize');
	$resize1 = $this->input->get('resize1');
	$level = explode('/', $image);
	$keys = array_keys($level);
	$last3 = $level[end($keys)];
		
	$alert = 'Upload valid image';
	$alert_success = 'Successfully updated your photo';
	if((!empty($image)) && (!empty($resize)) && (!empty($resize1)))
			{
			
			$data5['photo'] = 1 ;
			}
			else {
				$data5['photo'] = 0 ;
			}
	$this->db->where('id',$room_id)->update('lys_status',$data5);
	  if((!empty($image)) && (!empty($resize)) && (!empty($resize1)) && (!empty($room_id)) && (!empty($user_id)))
				{
				$data1['user_id'] = $user_id;
				$data1['list_id'] = $room_id;
				$data1['image'] = $image;
				$data1['resize'] = $resize;
				$data1['resize1'] = $resize1;
				$data1['created'] = time();
				$data1['is_featured'] = '1';
				$data1['name'] = $last3;
				$data_value = $this->db->insert('list_photo', $data1);
				
				//$this->db->where('list_id',$room_id)->update('list_photo',$data1);
				
				$data_final = $this->db->where('image', $image)->get('list_photo');
				
				
				$row_id = $data_final->row()->id; 
				
				echo '[{"status":"'.$alert_success.'","image_id":"'.$row_id.'"}]';
				}else
					{
						echo '[{"status":"'.$alert.'"}]';
					}
	
}


public function return_image(){
	$room_id = $this->input->get('room_id');
	$user_id = $this->input->get('user_id');
	$image = $this->input->get('image');
	$org_image = basename($image);
	$resize = $this->input->get('resize');
	$resize1 = $this->input->get('resize1');
	$alert = 'Upload valid image';
	$alert_success = 'Successfully updated your photo';
	
	  if((!empty($image)) && (!empty($resize)) && (!empty($resize1)) && (!empty($room_id)) && (!empty($user_id)))
				{
				$data1['user_id'] = $user_id;
				$data1['list_id'] = $room_id;
				$data1['name'] = $org_image;
				$data1['image'] = $image;
				$data1['resize'] = $resize;
				$data1['resize1'] = $resize1;
				$data1['created'] = time();
				$data1['is_featured'] = '1';
				$this->db->insert('list_photo', $data1);
				//$this->db->where('list_id',$room_id)->update('list_photo',$data1);
				echo '[{"status":"'.$alert_success.'"}]';
				}else
					{
						echo '[{"status":"'.$alert.'"}]';
					}
	
	
}

public function map_upload(){
$status = "";
			$msg = "";
			$alert = "Upload correct file";
			$file_element_name = 'uploadedfile';
			$room_id = $this->input->get('room_id');
			$user_id = $this->input->get('user_id');
			 $this->path     = realpath(APPPATH . '../images');
			
			if ($status != "error")	
			{
				if(!is_dir($this->path.'/'.$room_id))
            {
                    mkdir($this->path.'/'.$room_id, 0777, true);
                    
            }
				
			$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'; //Set the upload path
			
			$config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
			
			$config['encrypt_name']	= TRUE; // Change image name
			
			 //$config['file_name']    = base64_encode("" . mt_rand());
			
			//$url=urlencode(base64_encode($file_element_name));
			
			$this->load->library('upload', $config);
			
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('','');
				$data = "";
				
				echo '[{"status":"'.$msg.'"}]';
			}
			else 
			{
				$data = $this->upload->data(); // Get the uploaded file information
				
				//$data = file_put_contents($file_element_name, base64_encode($_POST[$data1]));
				
                $image = base_url().'images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];   
				
				$config1['image_library'] = 'gd2';
				$config1['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config1['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].'_100_100'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config1['maintain_ratio'] = TRUE;
				$config1['width'] = 100;
				$config1['height'] = 100;

				$this->load->library('image_lib');
				
				$this->image_lib->initialize($config1);

				if ( ! $this->image_lib->resize())
				{
   				 $resize = $this->image_lib->display_errors();
				}
				
				$resize = base_url().'images/'.$room_id.'/'.$data['raw_name'].'_100_100'.$data['file_ext'];
							
				$config2['image_library'] = 'gd2';
				$config2['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config2['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].'_525_525'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config2['maintain_ratio'] = TRUE;
				$config2['width'] = 525;
				$config2['height'] = 525;

				$this->image_lib->initialize($config2);

				if ( ! $this->image_lib->resize())
				{
   				 $resize1 = $this->image_lib->display_errors();
				}
				
				$resize1 = base_url().'images/'.$room_id.'/'.$data['raw_name'].'_525_525'.$data['file_ext'];
		      
				//echo '[{"resize1":"'.$resize1.'"}]';exit;	
				 echo '[{"image":"'.$image.'","resize":"'.$resize.'","resize1":"'.$resize1.'"}]';exit;			
			}
				
			@unlink($_FILES[$file_element_name]);
	        }
       //$data['message_element']     = "test";
       //$this->load->view('template',$data);
}

public function return_map(){
	$user_id = $this->input->get('user_id');
	$room_id = $this->input->get('room_id');
	$image_image = $this->input->get('map');
	$alert = 'Upload valid image';
	$alert_success = 'Successfully Added your map';
	
	  if((!empty($image_image)) && (!empty($user_id)) && (!empty($room_id)))
				{
				$data1['user_id'] = $user_id;
				$data1['list_id'] = $room_id;
				$data1['map'] = $image_image;
				$data1['created'] = time();
				//$data1['is_featured'] = '1';
				 $data_value = $this->db->insert('map_photo', $data1);
				 //print_r($this->db->last_query());
				$data_final = $this->db->where('map', $image_image)->get('map_photo');
				 
				$row_id = $data_final->row()->id;
				echo '[{"status":"'.$alert_success.'","map_id":"'.$row_id.'"}]';
				}else
					{
						echo '[{"status":"'.$alert.'"}]';
					}
	
}

public function edit_map(){
	$status = "";
			$msg = "";
			$file_element_name = 'uploadedfile';
			$room_id = $this->input->get('room_id');
			 $this->path     = realpath(APPPATH . '../images');
			
			if ($status != "error")	
			{
				if(!is_dir($this->path.'/'.$room_id))
            {
                    mkdir($this->path.'/'.$room_id, 0777, true);
                    
            }
				
			$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'; //Set the upload path
			
			$config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
			
			$config['encrypt_name']	= TRUE; // Change image name
			
			 //$config['file_name']    = base64_encode("" . mt_rand());
			
			//$url=urlencode(base64_encode($file_element_name));
			
			$this->load->library('upload', $config);
			
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('','');
				$data = "";
				
				echo '[{"status":"'.$msg.'"}]'; 
			}
			else 
			{
				$data = $this->upload->data(); // Get the uploaded file information
				
				//$data = file_put_contents($file_element_name, base64_encode($_POST[$data1]));
				
                $image = base_url().'images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];   
							
				$config2['image_library'] = 'gd2';
				$config2['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config2['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].'_320_320'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config2['maintain_ratio'] = TRUE;
				$config2['width'] = 320;
				$config2['height'] = 320;
				
				$this->load->library('image_lib');

				$this->image_lib->initialize($config2);

				if ( ! $this->image_lib->resize())
				{
   				 $resize1 = $this->image_lib->display_errors();
				}
				
				$resize1 = base_url().'images/'.$room_id.'/'.$data['raw_name'].'_320_320'.$data['file_ext'];
		        
				$data1['map'] = $resize1; 
				$this->db->update('list_photo',$data1);
				echo $resize1;exit;				
			}
				
			@unlink($_FILES[$file_element_name]);
	        }
			//$data['message_element']     = "test";
           //$this->load->view('template',$data);
}

public function edit_map_upload(){
	$status = "";
			$msg = "";
			$file_element_name = 'uploadedfile';
			$room_id = $this->input->get('room_id');
			 $this->path     = realpath(APPPATH . '../images/users');
			
			if ($status != "error")	
			{
				if(!is_dir($this->path.'/'.$room_id))
            {
                    mkdir($this->path.'/'.$room_id, 0777, true);
                    
            }
				
			$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$room_id.'/'; //Set the upload path
			
			$config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
			
			$config['encrypt_name']	= TRUE; // Change image name
			
			 //$config['file_name']    = base64_encode("" . mt_rand());
			
			//$url=urlencode(base64_encode($file_element_name));
			
			$this->load->library('upload', $config);
			
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('','');
				$data = "";
				
				echo '[{"status":"'.$msg.'"}]'; 
			}
			else 
			{
				$data = $this->upload->data(); // Get the uploaded file information
				
				//$data = file_put_contents($file_element_name, base64_encode($_POST[$data1]));
				
                $image = base_url().'images/users/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];   
				
				$config1['image_library'] = 'gd2';
				$config1['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config1['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$room_id.'/'.$data['raw_name'].'_100_100'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config1['maintain_ratio'] = TRUE;
				$config1['width'] = 100;
				$config1['height'] = 100;

				$this->load->library('image_lib');
				
				$this->image_lib->initialize($config1);

				if ( ! $this->image_lib->resize())
				{
   				 $resize = $this->image_lib->display_errors();
				}
				
				$resize = base_url().'images/users/'.$room_id.'/'.$data['raw_name'].'_100_100'.$data['file_ext'];
								
				$config2['image_library'] = 'gd2';
				$config2['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
				$config2['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$room_id.'/'.$data['raw_name'].'_320_320'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config2['maintain_ratio'] = TRUE;
				$config2['width'] = 320;
				$config2['height'] = 320;
				
				$this->load->library('image_lib');

				$this->image_lib->initialize($config2);

				if ( ! $this->image_lib->resize())
				{
   				 $resize1 = $this->image_lib->display_errors();
				}
				
				$resize1 = base_url().'images/users/'.$room_id.'/'.$data['raw_name'].'_320_320'.$data['file_ext'];
		        
				$data1['map'] = $resize1; 
				$this->db->update('map_photo',$data1);
				echo '[{"map":"'.$resize1.'"}]';exit;				
			}
				
			@unlink($_FILES[$file_element_name]);
	        }
//$data['message_element']     = "test";
        // $this->load->view('template',$data);
}
	
function change_password()
{
	$user_id = $this->input->get('user_id');
	$this->session->set_userdata('DX_user_id',$user_id);
	$result = $this->db->where('id',$user_id)->from('users')->get();
	  $opwd  =  $this->input->get('old_password');
	  //echo $opwd;
	 $old_pass = $this->_check_password($this->input->get('old_password'));
	if($result->num_rows() != 0)
	{
		if($old_pass == 1)
		{
			$new_pass = crypt($this->dx_auth->_encode($this->input->get('new_password')));
			//echo $new_pass;
			$this->db->where('id',$user_id)->update('users',array('password'=>$new_pass));
	//if($this->dx_auth->change_password($this->input->get('old_password'), $this->input->get('new_password')))
	//{
		echo "[{\"status\":\"Successfully Changed\"}]";
	//}
	//else {
	//	echo "[{\"status\":\"Please enter correct old password\"}]";
	//}
	}
else {
	echo "[{\"status\":\"Please enter correct old password\"}]";
}
	}
	else {
		echo "[{\"status\":\"Please use valid user id\"}]";
	}
	}
function _check_password($old_pass)
	{
	 $password     = $old_pass;
		
		$user_id      = $this->session->userdata('DX_user_id');
	
		$stored_hash  = $this->db->where('id',$user_id)->get('users')->row()->password;
		
     //echo $password;
	 $password     = $this->dx_auth->_encode($password);
	// echo $password;
	// echo crypt($password, $stored_hash);
	// echo $stored_hash;
	// exit;
		if (crypt($password, $stored_hash) === $stored_hash)
		{
			
			return true;			
		} 
		else 
		{

			//$this->form_validation->set_message('_check_password', 'Your Old Password Is Wrong');
			return false;
		}//If end
	}
function view_listing()
{
	//$room_id = $this->input->get('room_id');
	$user_id = $this->input->get('user_id');
	$currency = $this->input->get('currency');
	$this->session->set_userdata('DX_user_id',$user_id);
	$result = $this->db->where('id',$user_id)->from('list')->get();
	if($result->num_rows() != 0)
	{
		//$lists = $this->db->where('user_id',$user_id)->where('id',$room_id)->from('list')->get();
		$lists = $this->db->where('id',$user_id)->from('list')->get();
		if($lists->num_rows() != 0)
		{
			echo "[";
		foreach($lists->result() as $row)
		{
			//$imageurl = $this->db->select('imageurl')->where('imageurl',$row->imageurl)->get('list')->row()->imageurl;
			$currency_symbol=0;
			if($row->currency!=null)
			{
			// $currency_symbol = $this->db->select('currency_symbol')->where('currency_code',$row->currency)->get('currency')->row()->currency_symbol;
			}
			//print_r($currency_symbol);exit;
			$search=array('\'','"','(',')','!','{','[','}',']','<','>');
			$replace=array('&sq','&dq','&obr','&cbr','&ex','&obs','&oabr','&cbs','&cabr');
		    $desc_replace = str_replace($search, $replace, $row->desc);
			$desc_tags = stripslashes($desc_replace);
			//$price = $this->get_currency_value1($row->id, $row->price, $currency);
			$price=$row->price;
			
			 $condition    = array("is_featured" => 1);
						$list_image   = $this->Gallery->get_imagesG($row->id, $condition)->row();

					if(isset($list_image->name))
					{
						$image_url_ex = explode('.',$list_image->name);

						$url = base_url().'images/'.$row->id.'/'.$image_url_ex[0].'_crop.jpg';
					}
					else
					{
						$url = base_url().'images/no_image.jpg';
					}
					
			$json[] ="{ \"room_id\":".$row->id.",\"title\":\"".$row->title."\",\"desc\":\"".$desc_tags."\",\"address\":\"".$row->address."\",
		\"country\":\"".$row->country."\",\"price\":\"".$price."\",\"image_src\":\"".$url."\",\"currency\":\"".$row->currency."\",\"currency_symbol\":\"".$currency_symbol."\"},";
		}
		$count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row;
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json;
		echo "]";
		}
		else
			{
				echo "[{\"status\":\"No List\"}]";
			}
	}
	else {
		echo "[{\"status\":\"Please logged in\"}]";
	}
	
}

function listing_details()
{
	$room_id = $this->input->get('room_id');
	$user_id = $this->input->get('user_id');
	$currency = $this->input->get('currency');
	$user_check = $this->db->where('user_id',$user_id)->where('id',$room_id)->from('list')->get();
	$user_valid = $this->db->where('id',$user_id)->from('users')->get();
	if($user_valid->num_rows() != 0)
	{
	if($user_check->num_rows() != 0)
	{
	 $conditions             = array("id" => $room_id, "list.is_enable" => 1, "list.status" => 1);
     $result                 = $this->Common_model->getTableData('list', $conditions);
		
		if($result->num_rows() != 0)
	{
	foreach($result->result() as $row)
	{
		 
		$id = $row->id;
		$user_id = $row->user_id;
		$address=$row->address;
		$country='';
		$city='';
		$state='';
		$cancellation_policy = $row->cancellation_policy; 	
		$room_type=$row->room_type;
		$bedrooms=$row->bedrooms;
		$beds=$row->beds;
		$bed_type=$row->bed_type;
		if($row->bathrooms == NULL)
		{
			$bathrooms=0;
		}
		else {
			$bathrooms=$row->bathrooms;
		}
		$title=$row->title;
		$amenities=$row->amenities;
		$desc=$row->desc;
		$capacity=$row->capacity;  
		$price=$row->price; 
		$email=$row->email;
		$phone=$row->phone;
		$review=$row->review;
		$lat=$row->lat;
		$long=$row->long;
		$property_id=$row->property_id;
		$street_view=$row->street_view;
		$sublet_price=$row->sublet_price;
		$sublet_status=$row->sublet_status;
		$sublet_startdate=$row->sublet_startdate;
		$sublet_enddate=$row->sublet_enddate;
		$currency=$row->currency;
		$manual=$row->house_rule;
		$page_viewed=$row->page_viewed;
		$neighbor=$row->neighbor;
		$directions=$row->directions;
		
		$price_query=$this->db->where('id',$room_id)->from('price')->get();
		
		if($price_query->num_rows() != 0)
	   { 
		foreach($price_query->result() as $row)
	   {
	   	$currency_symbol = $this->db->select('currency_symbol')->where('currency_code',$row->currency)->get('currency')->row()->currency_symbol;
				$price = $price;
				$cleaning_fee = $row->cleaning;
	            $extra_guest_fee = $row->addguests.'/guest after'.$row->guests;
				$additional_guests_price = $row->addguests;
				$additional_guests_after = $row->guests;
		        $Wprice = $row->week;
		        $Mprice = $row->month;
	   }
	   }
else
	{
		$Wprice='';
		$Mprice='';
		$cleaning_fee='';
		$price='';
		$extra_guest_fee='';
	}
			
	
     $conditions             = array("id" => $room_id, "list.is_enable" => 1, "list.status" => 1);
	 $result                 = $this->Common_model->getTableData('list', $conditions);
	 
	 	$today_month=date("F");
		$today_date=date("j");
		$today_year=date("Y");
		$conditions_statistics = array("list_id" => $room_id,"date"=>trim($today_date),"month"=>trim($today_month),"year"=>trim($today_year));
		$result_statistics = $this->Common_model->add_page_statistics($room_id,$conditions_statistics);
		
		$list                   = $list = $result->row();
		$title                  = $list->title;
		$page_viewed            = $list->page_viewed;
		
		$page_viewed = $this->Trips_model->update_pageViewed($room_id, $page_viewed);
		
			
		$id                     = $room_id;
		$checkin                = $this->session->userdata('Vcheckin');
		$checkout               = $this->session->userdata('Vcheckout');
		$guests                 = $this->session->userdata('Vnumber_of_guests');
	
		$ckin                   = explode('/', $checkin);
		$ckout                  = explode('/', $checkout);
		
		//check admin premium condition and apply so for
		$query                  = $this->Common_model->getTableData( 'paymode', array('id' => 2));
		$row                    = $query->row();	

		
		if(($ckin[0] == "mm" && $ckout[0] == "mm") or ($ckin[0] == "" && $ckout[0] == ""))
		{
      			
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
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix            = $row->fixed_amount; 
										$amt            = $price + $fix;
										$commission = $fix;
										$Fprice         = $amt;
							}
							else
							{  
										$per            = $row->percentage_amount; 
										$camt           = floatval(($price * $per) / 100);
										$amt            = $price + $camt;
										$commission = $camt;
										$Fprice         = $amt;
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
			$diff                  = strtotime($ckout[2].'-'.$ckout[0].'-'.$ckout[1]) - strtotime($ckin[2].'-'.$ckin[0].'-'.$ckin[1]);
			$days                  = ceil($diff/(3600*24));
			
			if($guests > $guests)
			{
			  $price               = ($price * $days) + ($days * $xprice->addguests);
			}
			else
			{
			  $price               = $price * $days;
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
			
			$commission    = 0;
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix             = $row->fixed_amount; 
										$amt             = $price + $fix;
										$commission = $fix;
										$Fprice          = $amt;
							}
							else
							{  
										$per             = $row->percentage_amount; 
										$camt            = floatval(($price * $per) / 100);
										$amt             = $price + $camt;
										$commission = $camt;
										$Fprice          = $amt;
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
		
			$conditions              = array('list_id' => $room_id);
			 
			 $condition    = array("is_featured" => 1);
						$list_image   = $this->Gallery->get_imagesG($room_id, $condition)->row();

					if(isset($list_image->name))
					{
						$image_url_ex = explode('.',$list_image->name);

						$image = base_url().'images/'.$room_id.'/'.$image_url_ex[0].'_crop.jpg';
					}
					else
					{
						$image = base_url().'images/no_image.jpg';
					}
			
			$conditions    			        = array('list_id' => $room_id, 'userto' => $list->user_id);
			$result			     	  = $this->Trips_model->get_review($conditions);
			
			$conditions    			     	  = array('list_id' => $room_id, 'userto' => $list->user_id);
			$stars			        	= $this->Trips_model->get_review_sum($conditions)->row();	
			 
			$title            = substr($title, 0, 70);
			
			$level = explode(',', $address);
		$keys = array_keys($level);
		$country = $level[end($keys)];
		if(is_numeric($country) || ctype_alnum($country))
		$country = $level[$keys[count($keys)-2]];
		if(is_numeric($country) || ctype_alnum($country))
		$country = $level[$keys[count($keys)-3]];
		   
		   
		   $search=array('\'','"','(',')','!','{','[','}',']');
			$replace=array('&sq','&dq','&obr','&cbr','&ex','&obs','&oabr','&cbs','&cabr');
		    $desc_replace = str_replace($search, $replace, $desc);
			$desc_tags = stripslashes($desc_replace);
				 if($street_view == 0)
				 {
				 	$street_view_str = 'Hide Street View';
				 }
				 elseif($street_view == 1)
				 {
				 	$street_view_str = 'Nearby (within 2 blocks)';
				 }
				 else {
					 $street_view_str = 'Closest to My Address';
				 }
				 $amenities = $this->db->get_where('list', array('id' => $room_id))->row()->amenities;
				 $property_type = $this->db->get_where('property_type', array('id' => $property_id))->row()->type;
    $in_arr = explode(',', $amenities);
	$result = $this->db->get('amnities');
	
	//s$currency = $this->input->get('currency');
	$price = $price;
	$Wprice = $Wprice;
	$Mprice = $Mprice;
	$cleaning_fee = $cleaning_fee;	
	$additional_guests_price = $additional_guests_price;
	$sublet_price = $sublet_price;
	/*$price = $this->get_currency_value1($room_id, $price, $currency);
	$Wprice = $this->get_currency_value1($room_id, $Wprice, $currency);
	$Mprice = $this->get_currency_value1($room_id, $Mprice, $currency);
	$cleaning_fee = $this->get_currency_value1($room_id, $cleaning_fee, $currency);
	$additional_guests_price = $this->get_currency_value1($room_id, $additional_guests_price, $currency);
	$sublet_price = $this->get_currency_value1($room_id, $sublet_price, $currency);*/
				 
            echo "[ { \"id\":".$room_id.",\"user_id\":".$user_id.",\"title\":\"".$title."\",\"country\":\"".$country.
			    "\",\"city\":\"".$city."\",\"state\":\"".$state."\",\"cancellation_policy\":\"".$cancellation_policy.
	           "\",\"address\":\"".$address."\",\"image_url\":\"".$image."\",\"room_type\":\"".$room_type."\",\"bedrooms\":".$bedrooms.
	           ",\"beds\":".$beds.",\"bathrooms\":".$bathrooms.",\"bed_type\":\"".$bed_type."\",\"desc\":\"".$desc_tags."\",\"capacity\":".$capacity.",\"price\":\"".$price.
	           "\",\"cleaning_fee\":\"".$cleaning_fee."\",\"additional_guest_fee\":\"".$additional_guests_price."\",
	           \"additional_guest_after\":\"".$additional_guests_after."\",\"weekly_price\":\"".$Wprice.
	           "\",\"monthly_price\":\"".$Mprice."\",\"email\":\"".$email."\",\"phone\":\"".$phone."\",\"review\":\"".$review.
	           "\",\"lat\":".$lat.",\"long\":".$long.",\"property_type\":\"".$property_type."\",\"street_view\":\"".$street_view_str.
	           "\",\"sublet_price\":".$sublet_price.",\"sublet_status\":".$sublet_status.",\"sublet_startdate\":\"".$sublet_startdate.
	           "\",\"sublet_enddate\":\"".$sublet_enddate."\",\"currency\":\"".$currency."\",\"currency_symbol\":\"".$currency_symbol."\",\"manual\":\"".$manual."\",\"page_viewed\":".$page_viewed
	           .",\"neighbor\":\"".$neighbor."\",\"directions\":\"".$directions."\",\"amenities\":\"";if($result->num_rows() != 0) {
	          if($amenities)
			   {
			    foreach($result->result() as $row)
	{
	    if(in_array($row->id, $in_arr))
		{
			$json[] = $row->name.",";
		}
	}
	$count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row; 
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json."\""; echo "} ]";exit;
			   }
			   }
else {
	$json[] ='';
	
}
		echo "\"} ]";			
	
			  
	}
	}
	
	else {
	echo "[ { \"status\":\"Access Denied\" } ]";
}
}
else
	{
		echo "[ { \"status\":\"Check your room id\" } ]";
	}
	}
else {
	echo "[ { \"status\":\"Check your user id\" } ]"; 
}
}
function edit_listing()
{
	$room_id = $this->input->get('room_id');
	$user_id = $this->input->get('user_id');
	$property_type = $this->input->get('property_type');
	$room_type = $this->input->get('room_type');
	$title = $this->input->get('title');
	$desc = $this->input->get("desc");
	$amenities = $this->input->get('amenities');
	$accommodates = $this->input->get('accommodates');
	$bedrooms = $this->input->get('bedrooms');
	$beds = $this->input->get('beds');
	$bed_type = $this->input->get('bed_type');
	//$imageurl = $this->input->get('imageurl');
	$bathrooms = $this->input->get('bathrooms');
	$manual = $this->input->get('manual');
	$cancellation_policy = $this->input->get('cancellation_policy');
	$address = $this->input->get('address');
	$neighborhoods = $this->input->get('neighborhoods');
	$street_view = $this->input->get('street_view');
	$directions = $this->input->get('directions');
	$nightly_price = $this->input->get('nightly_price');
	$weekly_price = $this->input->get('weekly_price');
	$monthly_price = $this->input->get('monthly_price');
	$additional_guests_after = $this->input->get('additional_guests_after');
	$additional_guests_fee = $this->input->get('additional_guests_fee');
	$cleaning_fees = $this->input->get('cleaning_fees');
	$lat = $this->input->get('lat');
	$long = $this->input->get('long');
	$phone = $this->input->get('phone');
	$currency = $this->input->get('currency');
	
	 $property_id = $this->db->get_where('property_type', array('type' => $property_type))->row()->id;
	 if($street_view == 'Hide Street View')
				 {
				 	$street_view_str = 0 ;
				 }
				 elseif($street_view == 'Nearby (within 2 blocks)')
				 {
				 	$street_view_str = 1 ;
				 }
				 else {
					 $street_view_str = 2 ;
				 }
	   $in_arr = explode(',', $amenities);
	   $result = $this->db->get('amnities');
	$amenities_id  = '';	
	if($result->num_rows() != 0) {
	           if($amenities)
			   {
			    foreach($result->result() as $row)
	   {
	    if(in_array($row->name, $in_arr))
		{
			$json[] = $row->id.",";
		}
	}
	  // print_r($json);exit;
	$count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					$amenities_id = '';
					foreach($slice as $row)
					{
						$amenities_id .= $row; 
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					$amenities_id .= $json;
			   }
			   }
else {
	$amenities_id ='';
}

	$level = explode(',', $address);
		$keys = array_keys($level);
		$country = $level[end($keys)];
		if(is_numeric($country) || ctype_alnum($country))
		$country = $level[$keys[count($keys)-2]];
		if(is_numeric($country) || ctype_alnum($country))
		$country = $level[$keys[count($keys)-3]];
		
			$updateData = array(
							'property_id'  	=> $property_id,
							'room_type'   	=> $room_type,
							'title'    		=> $title,
							'desc'         	=> $desc,
							'capacity'     	=> $accommodates,
							'cancellation_policy' => $cancellation_policy,
							'bedrooms'    	=> $bedrooms,
							'beds'     		=> $beds,
							'bed_type'     	=> $bed_type,

							//'imageurl' 		=> $imageurl,

							'bathrooms'     => $bathrooms,
							'house_rule'     	=> $manual,
							'street_view'   => 0,
							'directions'    => $directions,
							'neighbor'		=> $neighborhoods,
							'address'       => $address,
							'lat'			=> $lat,
							'long'			=> $long,
							'amenities'		=> $amenities_id,
							'price'			=> $nightly_price,
							'country'		=> $country,
							'phone' 		=> $phone,
							'currency'      => $currency
						 );
	
	$data = array(
							'night' 	=> $nightly_price,
							'week' 		=> $weekly_price,
							'month' 	=> $monthly_price,
							'addguests' => $additional_guests_fee ,
							'guests'    => $additional_guests_after,
							'cleaning' 	=> $cleaning_fees,
							'currency'  => $currency
							);
	
	$user_check = $this->db->where('user_id',$user_id)->where('id',$room_id)->from('list')->get();
	$user_valid = $this->db->where('id',$user_id)->from('users')->get();
	
	if($user_valid->num_rows() != 0)
	{
	if($user_check->num_rows() != 0)
	{
		$updateKey = array('id' => $room_id);	
		/* if($property_id && $property_type && $title && $desc && $amenities && $accommodates && $bed_rooms 
		 && $beds && $bed_type && $bath_rooms && $manual && $cancellation_policy && $address && $neighborhoods 
		 && $street_view && $directions && $lat && $long && $nightly_price && $weekly_price && $monthly_price 
		 && $additional_guests_after && $additional_guests_fee && $cleaning_fees)
		 {*/
		 	$this->load->model('Rooms_model');
		$this->Rooms_model->update_list($updateKey, $updateData);
		$this->Common_model->updateTableData('price', $room_id, NULL, $data);
		echo "[ { \"status\":\"Updated Successfully.\" } ]"; exit;
	/*	 }
		 else {
			 echo "[ { \"status\":\"Please Enter All Details.\" } ]"; exit;
		 }*/
	}
	else {
		echo "[ { \"status\":\"Check your room id\" } ]";exit;
	}
	}
	else {
		echo "[ { \"status\":\"Check your user id\" } ]";exit;
	}
}

function twitter_signup()
	{
		
	if($this->input->get("email") && $this->input->get("firstname") && $this->input->get("lastname") && $this->input->get("username") 
	&& $this->input->get("twitter_id") && $this->input->get("image_url") && $this->input->get('user_agent') && $this->input->get('last_ip') )
			{
				
			extract($this->input->get());
			
			$user_agent			= 	$this->input->get('user_agent');
		    $last_ip			= 	$this->input->get('last_ip');
			
			$emailCheck = $this->db->query("select users.email from users where email = '$email' ")->result(); 
			if (count($emailCheck) == 0)
			{
				
				
			$usernameCheck = $this->db->query("select users.username from users where username = '$username' ")->result(); 
				if (count($usernameCheck) == 0)
				{
					
		$twitterCheck = $this->db->query("select users.twitter_id from users where twitter_id = '$twitter_id' ")->result(); 
				if (count($twitterCheck) == 0)
				{
						
				  $twitter_image_url = $image_url;
				
				$data['email'] = $this->input->get("email");
				$data['username'] = $this->input->get("username");
				$data['twitter_id'] = $this->input->get("twitter_id");
				$last_login=date('Y-m-d h:i:s', time());
			    $data['last_ip']  =  $last_ip;
			    $data['last_login']  =  $last_login;
				
					$this->db->insert('users',$data);
					
							  $user_id = $this->db->where('email',$data['email'])->select('*')->from('users')->get()->row()->id;
					$add['Fname']    = $this->input->get("firstname");
			$add['Lname']    = $this->input->get("lastname");
			$add['id']       = $user_id;
			$add['email']    = $data['email'];
			
		$join  = $this->input->get('join_date');
		$final_date = date($join);
		$start_date =  strtotime($final_date);
			   //$date_in = get_user_times(get_gmt_time(strtotime($final_date)), get_user_timezone1());
			$add['join_date']       = $start_date;
			$add5['join_date']       = $start_date;
		    $this->Common_model->insertData('profiles', $add);
			$this->db->insert('users',$add5);
		
		    $img['email'] = $data['email'];
		    $img['src'] = $image_url;
			$this->Common_model->insertData('profile_picture', $img);
			
			$notification                     = array();
			$notification['user_id']		  = $user_id;
			$notification['new_review ']	  = 1;
			$notification['leave_review']	  = 1;
			$this->Common_model->insertData('user_notification', $notification);
			
			$last_login=date('Y-m-d h:i:s', time());
			$auto['key_id']  =  md5($last_login);
			$auto['user_id']  = $user_id;
			$auto['user_agent'] = $user_agent;
			$auto['last_ip']  =  $last_ip;
			$auto['last_login']  =  $last_login;
			
			$this->Common_model->insertData('user_autologin', $auto);
	  	
					
echo '[{"user_id":"'.$user_id.'","status":"success","twitter_uid":"'.$this->input->get("twitter_id").'","profile_image":"'.$image_url.'"}]';	
					
						
				}
else {
	echo '[{"status":"Twitter Id Already Taken"}]';	
}	
					
				}
				else
				{
					echo '[{"status":"Username Already Taken"}]';	
						
				}		
				
				
			}
			else 
			{
				 echo '[{"status":"Email is Already Registered"}]';	
			}		 
							
			}
			else
			{
			echo '[{"status":"Failed"}]';
			}
		
	}
function inbox()
{
	       $user_id = $this->input->get('user_id');
	       $conditions       = array("messages.userto " => $user_id); 
		 	$data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id','desc'));
			if($data['messages']->num_rows()!=0)
			{
$value = $data['messages']->result();
echo "[";
foreach($value as $row)
{
	if($row->contact_id != 0)
			 {
			 $checkin=$this->Common_model->getTableData('contacts',array('id' => $row->contact_id))->row()->checkin; 
             $checkout=$this->Common_model->getTableData('contacts',array('id' => $row->contact_id))->row()->checkout; 
			 }
else {
	 $checkin=$this->Common_model->getTableData('reservation',array('id' => $row->reservation_id))->row()->checkin; 
     $checkout=$this->Common_model->getTableData('reservation',array('id' => $row->reservation_id))->row()->checkout; 
}

$src = $this->Gallery->profilepic($row->userby,2);

 $search=array('<br>');
 $replace=array('&br&');
 $message = str_replace($search, $replace, $row->message);
 
 $currency = $this->input->get('currency');
 if($row->price != 0)	
 $price = $row->price;
 //$price = $this->get_currency_value1($row->list_id, $row->price, $currency);
			
$json[] = "{ \"id\":".$row->id.",\"list_id\":\"".$row->list_id."\",\"reservation_id\":\"".$row->reservation_id."\",\"contact_id\":\"".$row->contact_id."\",
		\"conversation_id\":\"".$row->conversation_id."\",\"userby\":\"".$row->userby."\",\"userto\":\"".$row->userto."\",
		\"email\":\"".$row->email."\",\"username\":\"".$row->username."\",\"src\":\"".$src."\",\"subject\":\"".$row->subject."\",\"message\":\"".$message."\",\"created\":\"".$row->created."\",\"is_read\":\"".$row->is_read."\",\"message_type\":\"".$row->message_type."\",\"is_starred\":\"".$row->is_starred."\",\"name\":\"".$row->name."\",\"url\":\"".$row->url."\",\"checkin\":\"".$checkin."\",\"checkout\":\"".$checkout."\",\"price\":\"".$price."\",\"no_quest\":\"".$row->no_quest."\"},";

}

$count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row;
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json;
		echo "]";

			}
else
	{
		echo '[{"status":"Empty"}]';
	}
}

function get_currency_value1($id,$amount,$currency)
		{
			$rate=0;
						
			$this->load->helper("cookie");
						
			$current = $currency;
			
			$list_currency     = $this->db->where('id',$id)->get('list')->row()->currency;
			
			if($current == '')
			{
				$list_currency1 = $this->db->where('default',1)->get('currency')->row()->currency_code;
				
				$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $list_currency1);
						
			$rate=round(google_convert($params));
				
			if($rate!=0)
				return $rate;
			else
				return 0;
			
			}
			
			$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $current);
			
			$rate=round(google_convert($params));
						
			if($rate!=0)
				return $rate;
			else
				return 0;
	}

		function google_convert($params)
	{
		$amount    = $params["amount"];
		
		$currFrom  = $params["currFrom"];
		
		$currInto  = $params["currInto"];
		
		if (trim($amount) == "" ||!is_numeric($amount)) {
			trigger_error("Please enter a valid amount",E_USER_ERROR);         	
		}
		$return=array();
			
		if($currFrom == 'USD')
		{
			$currInto_result = $this->db->where('currency_code','currency_symbol',$currInto)->get('currency_converter')->row();
			$rate = $amount * $currInto_result->currency_value;
		}
		else 	
		{
			
		$currFrom_result = $this->db->where('currency_code','currency_symbol',$currFrom)->get('currency_converter')->row();
		
		$from_usd = 1/$currFrom_result->currency_value; 
		
		$from_usd_amt = $amount * $from_usd;
		
		$currInto_result = $this->db->where('currency_code','currency_symbol',$currInto)->get('currency_converter')->row();
		
		$rate = $currInto_result->currency_value * $from_usd_amt;
		
		}
		
		return $rate;
	}
		 public function discover()
{
$result=$this->db->get('neigh_city');

if($result->num_rows!=0)
{
echo "[";
foreach($result->result() as $rows) {
 	$city=$rows->city_name;
	$image=$rows->image_name;
		$json[]= "{ \"cityname\":\"".$city."\",\"image\":\"".base_url()."/images/neighbourhoods/".$rows->id."/".$image."\"},";
	
	
}
$count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row1)
					{
						echo $row1;
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json;
		echo "]";
	
  
	}
}
function view_discoverlisting()
{
	//$room_id = $this->input->get('room_id');
	$city= $this->input->get('place');
	//$currency = $this->input->get('currency');
//	$this->session->set_userdata('DX_user_id',$user_id);
	$result = $this->db->where('city',$city)->from('list')->get();
	if($result->num_rows() != 0)
	{
		//$lists = $this->db->where('user_id',$user_id)->where('id',$room_id)->from('list')->get();
		$lists = $this->db->where('city',$city)->from('list')->get();
		if($lists->num_rows() != 0)
		{
			echo "[";
		foreach($lists->result() as $row)
		{
			//$imageurl = $this->db->select('imageurl')->where('imageurl',$row->imageurl)->get('list')->row()->imageurl;
			$currency_symbol = $this->db->select('currency_symbol')->where('currency_code',$row->currency)->get('currency')->row()->currency_symbol;
			$search=array('\'','"','(',')','!','{','[','}',']','<','>');
			$replace=array('&sq','&dq','&obr','&cbr','&ex','&obs','&oabr','&cbs','&cabr');
		    $desc_replace = str_replace($search, $replace, $row->desc);
			$desc_tags = stripslashes($desc_replace);
			//$price = $this->get_currency_value1($row->id, $row->price, $currency);
			$price=$row->price;
			
			 $condition    = array("is_featured" => 1);
						$list_image   = $this->Gallery->get_imagesG($row->id, $condition)->row();

					if(isset($list_image->name))
					{
						$image_url_ex = explode('.',$list_image->name);

						$url = base_url().'images/'.$row->id.'/'.$image_url_ex[0].'_crop.jpg';
					}
					else
					{
						$url = base_url().'images/no_image.jpg';
					}
					
			$json[] ="{ \"room_id\":".$row->id.",\"title\":\"".$row->title."\",\"desc\":\"".$desc_tags."\",\"address\":\"".$row->address."\",
		\"country\":\"".$row->country."\",\"price\":\"".$price."\",\"image_src\":\"".$url."\",\"currency\":\"".$row->currency."\",\"currency_symbol\":\"".$currency_symbol."\"},";
		}
		$count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row;
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json;
		echo "]";
		}
		else
			{
				echo "[{\"status\":\"No List\"}]";
			}
	}
	else {
		echo "[{\"status\":\"No Listing exist\"}]";
	}
	
}

public function current_location(){
		$user_id = $this->input->get('user_id');
		$location = $this->input->get('location');
	$query = $this->db->where('city',$location)->get('list');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row)
	{
		$data['id']=$row->id;
		$data['user_id']=$row->user_id;
		$data['country']=$row->country;
		$data['city']=$row->city;
		$data['state']=$row->state;
		$data['room_type']=$row->room_type;
		$data['email']=$this->db->where('id',$data['user_id'])->get('profiles')->row('email');
				$data['title']=$row->title;
				$data['price']=$row->price;
				$data['capacity']=$row->capacity;
				$data['currency']=$row->currency;
				$data['currency_symbol']=$this->db->where('currency_code',$data['currency'])->get('currency')->row('currency_symbol');
				$country_symbol =$this->db->where('currency_code',$data['currency'])->get('currency')->row('country_symbol');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                 $data['country_symbol']  = 'null';
                }
				$resize1 =$this->db->where('id',$data['id'])->get('list_photo')->row('resize1');
				if(!empty($resize1)){
					$data['resize1']   = $resize1;
				}
                else{
	                 $data['resize1']  = 'null';
                }
				$src = $this->db->where('email',$data['email'])->get('profile_picture')->row('src');
				if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                 $data['src']  = 'null';
                }
				$curr[]=$data;	
				//print_r($data['email']);	
	}
	echo json_encode($curr);
	}
	}

public function currency(){
	$currency1 = $this->db->query('select * from currency');
	foreach ($currency1->result() as $row) {
		$data['id'] = $row->id;
		$data['currency_code'] = $row->currency_code;
        $data['currency_name'] = $row->currency_name;
		$data['currency_symbol'] = $row->currency_symbol;
		/*$country_symbol = $row->country_symbol;
		if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                 $data['country_symbol']  = 'null';
                }*/
		$data['default']       = $row->default;
		$status       = $row->status;
		if($status == 1){
		$currency[] = $data;
		}
	}
	echo json_encode($currency);
}

public function view_currency(){
	$roomid = $this->input->get('roomid');
	$query = $this->db->where('id',$roomid)->get('list');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row)
	{
		$data['id']=$row->id;
		$data['currency']=$row->currency;
		$vcurrency[] = $data;
	}
	echo json_encode($vcurrency);
	}
	else {
			echo '[{"status":"This roomid is not available"}]';
	    }
}

public function edit_currency(){
		$roomid=$this->input->get('roomid');
	
		$data['currency']			 = $this->input->get("currency");
		if($roomid == ''){
		  echo '[{"status":"You should provide roomid."}]';exit;
		}
		else{
		$this->db->where('id',$roomid)->update('list',$data);
		
		echo '[{"reason_message":"Updated Successfully"}]';
		}
}

public function view_image1(){
	$roomid  = $this->input->get('roomid');
	$query  = $this->db->where('list_id',$roomid)->get('list_photo');
	foreach($query->result() as $val){
		$id   = $val->id;
		$image  = $val->image;
		echo '[{"id":"'.$id.'","image":"'.$image.'"}]';
	}
}
public function longterm(){
	$roomid   = $this->input->get('roomid');
    //$common_currency = $this->input->get('common_currency');
	
	$query    = $this->db->where('id',$roomid)->get('price');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row)
	{
		$data['roomid']=$row->id;
		$data['week']=$row->week;
		$data['month'] = $row->month;
                $data['clean'] = $row->cleaning;
		$data['status'] = "There is a data for this roomid";
		$list_query = $this->db->where('id',$roomid)->get('list');
		$data['price'] = $list_query->row()->price;
		$currency  = $list_query->row()->currency;
		
		$data['currency_symbol']  = $row->currency;
				
                $country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
                    $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				
				
        /*$json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currency."_".$common_currency);
        
        $obj = json_decode($json);
        
        foreach($obj->results as $results)
        {
            $value = $results->val;
            //echo $value;
            $data['common_currency_code'] = $common_currency;
            $data['common_currency_value_week'] = $value * $row->week;
            $data['common_currency_value_month'] = $value * $row->month;
        }*/
		$long[] = $data;
	}
	echo json_encode($long);
	}
else{
	echo '[{"status":"There is no data for this roomid"}]';
	
}
}
public function longtermconvert(){
	$roomid   = $this->input->get('roomid');
    $common_currency = $this->input->get('common_currency');
	
	$query    = $this->db->where('id',$roomid)->get('price');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row)
	{
		$data['roomid']=$row->id;
		$data['week']=$row->week;
		$data['month'] = $row->month;
		$data['status'] = "There is a data for this roomid";
		$list_query = $this->db->where('id',$roomid)->get('list');
		$data['price'] = $list_query->row()->price;
		$currency  = $list_query->row()->currency;
		
		$data['currency_symbol']  = $row->currency;
				
                $country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
                    $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				
				
        // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currency."_".$common_currency);
//         
        // $obj = json_decode($json);
//         
        // foreach($obj->results as $results)
        // {
            // $value = $results->val;
            // //echo $value;
            // $data['common_currency_code'] = $common_currency;
            // $data['common_currency_value_week'] = $value * $row->week;
            // $data['common_currency_value_month'] = $value * $row->month;
        // }
        
             $data['common_currency_code'] = $common_currency;
            $data['common_currency_value_week'] = (get_currency_value_lys($currency,$common_currency,1)) *$row->week;
            
            $data['common_currency_value_month'] = (get_currency_value_lys($currency,$common_currency,1)) * $row->month;
        
		$long[] = $data;
	}
	echo json_encode($long);
	}
else{
	echo '[{"status":"There is no data for this roomid"}]';
	
}
}
public function edit_longterm1(){
	$roomid=$this->input->get('roomid');
	
		$data['week']			 = $this->input->get("week");
		$data['month']           = $this->input->get("month");
		$data['currency']   = $this->input->get('currency');
		$check_id = $this->db->where('id',$roomid)->get('price');
		
		//if($roomid == '')
		if($check_id->num_rows() == 0)
		{
		  echo '[{"status":"You should provide roomid."}]';exit;
		}
		else{
		$this->db->where('id',$roomid)->update('price',$data);
		
		echo '[{"reason_message":"Updated Successfully"}]';
		}
}

public function edit_longterm(){
	$roomid=$this->input->get('roomid');
	
		$week			 = $this->input->get('week');
		$month           = $this->input->get('month');
		$currency        = $this->input->get('currency');
                $clean        = $this->input->get('clean');  
		$check_id = $this->db->where('id',$roomid)->get('price');
		if($check_id->num_rows() == 0){
		
		  echo '[{"reason_message":"You should provide roomid."}]';exit;
		}
		
	else {
		

	$data['id']	= $roomid;
	$data['week']  = $week;
	$data['month'] = $month;
	$data['currency'] = $currency;
        $data['cleaning'] = $clean;  
	$this->db->where('id',$roomid)->update('price',$data);
	echo '[{"reason_message":"Updated Successfully."}]';exit;
	}
	
}

public function view_image(){
	$roomid  = $this->input->get('roomid');
	$query  = $this->db->where('list_id',$roomid)->get('list_photo');
	if($query->num_rows() != 0)
	{
	foreach($query->result() as $val){
		$data['id']   = $val->id;
		$image  = $val->image;
		if(!empty($image)){
					$data['image']   = $image;
				}
                else{
	                 $data['image']  = 'null';
                }
		$resize = $val->resize;
		if(!empty($resize)){
					$data['resize']   = $resize;
				}
                else{
	                 $data['resize']  = 'null';
                }
		$resize1 = $val->resize1;
		if(!empty($resize1)){
					$data['resize1']   = $resize1;
				}
                else{
	                 $data['resize1']  = 'null';
                }
		
		$img[]  = $data;
	}
	echo json_encode($img);
	}
	else {
			echo '[{"status":"This roomid is not available"}]';
	    }
}

public function user_detail(){
	$user_id  = $this->input->get('user_id');
	
	$query  = $this->db->where('id',$user_id)->get('profiles');
	foreach($query->result() as $row){
		$data['Fname']  = $row->Fname;
		$data['Lname']  = $row->Lname;
		$data['gender'] = $row->gender;
		$data['dob']    = $row->dob;
		$data['email']  = $row->email;
		$data['live']   = $row->live;
		$data['school'] = $row->school;
		$data['work']   = $row->work;
	    $data['phnum']  = $row->phnum;
		$data['describe'] = $row->describe;
		$eid   = $row->email;
		$src   = $this->db->where('email',$eid)->get('profile_picture')->row('src');
		if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                 $data['src']  = 'null';
                }
		
		$user[]  = $data;
	}
	echo json_encode($user);
}

public function set_address(){
	
	$data['user_id']  = $this->input->get('user_id');
	$data['address']  = $this->input->get('address');
	$data['street_address']  = $this->input->get('street_address');
	$data['city']     = $this->input->get('city');
	$data['state']    = $this->input->get('state');
	$data['country']  = $this->input->get('country');
	
	$this->db->insert('list', $data);
	$insert_id = $this->db->insert_id();
		$this->db->where('id',$insert_id);
	echo '[{"reason_message":"Set Address added successfully.", "room_id":"'.$insert_id.'"}]';exit;
}

public function edit_address(){
	$roomid=$this->input->get('roomid');
	
		$data['address']			 = $this->input->get("address");
		$data['street_address']  = $this->input->get('street_address');
		$data['country']           = $this->input->get("country");
		$data['city']             = $this->input->get("city");
		$data['state']            = $this->input->get('state');
		$data['zip_code']         = $this->input->get('zip_code');
		
		if($roomid == ''){
		  echo '[{"status":"You should provide roomid."}]';exit;
		}
		else{
		$this->db->where('id',$roomid)->update('list',$data);
		
		echo '[{"reason_message":"Updated Successfully"}]';
		}
}

public function view_address(){
	$roomid   = $this->input->get('roomid');
	$query   = $this->db->where('id',$roomid)->get('list');
	if($query->num_rows() != 0)
	{
	foreach($query->result() as $row){
		$data['state']  = $row->state;
		$data['city']   = $row->city;
		$data['country'] = $row->country;
		$addr[]  = $data;
	}
	echo json_encode($addr);
	}
	else {
			echo '[{"status":"This roomid is not available"}]';
	    }
	
}

public function deactive()
    {
	$roomid   = $this->input->get('roomid');
	$query  = $this->db->where('id',$roomid)->get('list');
    $query1 = $this->db->where('list_id', $roomid)->get('reservation');
    $query2  = $this->db->where('id',$roomid)->get('list_photo');
    $query3  = $this->db->where('id',$roomid)->get('lys_status');
    $query4  = $this->db->where('id',$roomid)->get('map_photo');
    $query5  = $this->db->where('id',$roomid)->get('contacts');
    $query6  = $this->db->where('id',$roomid)->get('messages');

    
    if($query1->num_rows() != 0)
    {
        foreach($query1->result() as $row)
        {
            $res_status_id = $row->status;
            if($res_status_id != 2 || $res_status_id != 4 || $res_status_id != 10)
            {
            	
                $reason = "cannot delete";
            }
            else
            {
                if($query->num_rows()!=0)
                {
                    $this->db->where('id',$roomid)->delete('list');
                    $this->db->where('list_id', $roomid)->delete('reservation');
                    
                    if($query2->num_rows()!=0)
                    {
                    $this->db->where('list_id', $roomid)->delete('list_photo');
                    }
                    if($query3->num_rows()!=0)
                    {
                    $this->db->where('id' , $roomid)->delete('lys_status');
                    }
                    if($query4->num_rows()!=0)
                    {
                    $this->db->where('list_id', $roomid)->delete('map_photo');
                    }
                    if($query5->num_rows()!=0)
                    {
                    $this->db->where('list_id', $roomid)->delete('contacts');
                    }
                    if($query6->num_rows()!=0)
                    {
                    $this->db->where('list_id', $roomid)->delete('messages');
                    }
					
                    $reason = "delete";
                    
                }
                else
                {
                	$reason = "no list";
                    
                }
            }
        }

		if($reason == "cannot delete")
		{
			echo '[{"reason_message":"Can not delete the list. Because some reservations might be in pending state"}]';
		}
		else if($reason == "delete")
		{
			echo '[{"reason_message":"List Deleted Successfully"}]';
		}
		else if($reason == "no list")
		{
			echo '[{"reason_message":"There is no list on this roomid"}]';
		}
   }
    else
    {
	if($query->num_rows()!=0)
	{
        $this->db->where('id',$roomid)->delete('list');
        if($query2->num_rows()!=0)
        {
            $this->db->where('list_id', $roomid)->delete('list_photo');
        }
        if($query3->num_rows()!=0)
        {
            $this->db->where('id' , $roomid)->delete('lys_status');
        }
        if($query4->num_rows()!=0)
        {
            $this->db->where('list_id', $roomid)->delete('map_photo');
        }
        if($query5->num_rows()!=0)
        {
            $this->db->where('list_id', $roomid)->delete('contacts');
        }
        if($query6->num_rows()!=0)
        {
            $this->db->where('list_id', $roomid)->delete('messages');
        }
        
        echo '[{"reason_message":"List Deleted Successfully"}]';

	}
	else
    {
		echo '[{"reason_message":"There is no list on this roomid"}]';
	}
    
    }
    }
public function facebook()
		{
			$user_id								= $this->input->get("user_id");
	$query   = $this->db->where('id',6)->get('settings');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row){
		
		
		$data['api_key']   = $row->string_value;
	}
		}
    
     $data['colud_name']    = $this->db->get_where('settings', array('code' => 'cloud_name'))->row()->string_value;
     $data['colud_api_key']    = $this->db->get_where('settings', array('code' => 'cloud_api_key'))->row()->string_value;
     $data['colud_api_secret']    = $this->db->get_where('settings', array('code' => 'cloud_api_secret'))->row()->string_value;
    
    
    
$details[] = $data;
	echo json_encode($details, JSON_UNESCAPED_SLASHES);
		}
        
  public function Checklogin()
  {
      $user_id = $this->input->get("user_id");
     $this->load->helper();
     $check = get_user_by_id($user_id);
     if (get_user_by_id($user_id))
     {
         echo '[{"Status":"Successfully"}]';
     }
    else {
	  echo '[{"Status":"Fail"}]';
    }
     
  }    
        
}
?>
