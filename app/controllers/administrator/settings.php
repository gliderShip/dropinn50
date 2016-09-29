<?php
/**
 * DROPinn Admin Social Controller Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package		DROPinn
 * @subpackage	Controllers
 * @category	Admin Social
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
  
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller
{

	public function Settings()
	{
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('file');
		
		//load validation library
		$this->load->library('form_validation');
		$this->load->library('Table');
		$this->load->library('Pagination');

		$this->load->model('Users_model');	
		
		$this->load->library('image_lib');		
		
		// Protect entire controller so only admin, 
		// and users that have granted role in permissions table can access it.
		$this->dx_auth->check_uri_permissions();
		$this->path = realpath(APPPATH . '../');
	}
	
	public function index()
	{
			//--------- With CDN ------//
			require_once APPPATH.'libraries/cloudinary/autoload.php';
                \Cloudinary::config(array( 
                  "cloud_name" => cdn_name, 
                  "api_key" => cdn_api, 
                  "api_secret" => cloud_s_key
                ));
             //--------- END CDN ------//   			
			if($this->input->post('update'))
			{
				$data1['string_value']     = $this->input->post('site_title');
				$this->db->where('code', 'SITE_TITLE');
				$this->db->update('settings',$data1);
				
				$data2['string_value']    = $this->input->post('site_slogan');
				$this->db->where('code', 'SITE_SLOGAN');
				$this->db->update('settings',$data2);
				
				$data3['int_value']       = $this->input->post('offline');
				$this->db->where('code', 'SITE_STATUS');
				$this->db->update('settings',$data3);
				
				$data4['text_value']      = $this->input->post('offline_message');
				$this->db->where('code', 'OFFLINE_MESSAGE');
				$this->db->update('settings',$data4);
				
				$data5['text_value']      = $this->input->post('google_analytics',false);
				$this->db->where('code', 'GOOGLE_ANALYTICS_CODE');
				$this->db->update('settings',$data5);	
				
				$data6['string_value']    = $this->input->post('super_admin');
				$this->db->where('code', 'SITE_ADMIN_MAIL');
				$this->db->update('settings',$data6);
				
				
			// favicon image 1 start
				
				if($_FILES["favicon"]["name"])
				{
				$logo = $this->db->get_where('settings', array('code' => 'FAVICON_IMAGE'))->row()->string_value;
   // $real_logo = $this->path.'/logo/'.$logo;
	//------- With CDN ----------//
	    $temp = explode('.', $_FILES["favicon"]["name"]);
        $ext  = array_pop($temp);
        $name1 = implode('.', $temp);
            try{
                $cloudimage1=\Cloudinary\Uploader::upload($_FILES["favicon"]["tmp_name"],
                array("public_id" => "logo/".$name1));
                }catch (Exception $e) {
                    $error = $e->getMessage();
                }
        $secureimage1 = $cloudimage1['secure_url']; 
	//--------END CDN -----------//
    			
		/*		With out CDN
    	$config1 = array(
						'allowed_types' => 'jpg|jpeg|gif|png',
						'upload_path' => 'logo',
						'overwrite' => true,
						'remove_spaces' => TRUE,
						);
					$this->load->library('upload', $config1);
					if(!$this->upload->do_upload('favicon'))
					{
    						   $logo = '<img src="'.base_url().'logo/'.$logo.'" alt="logo">';
						echo $logo.'#<p>Please upload correct file.</p>';exit;
					 }    */ 
					 {
				// $upload_data = $this->upload->data(); 				
			     // $image_name    = $upload_data['file_name'];
						$data9['string_value']    = $_FILES["logo"]["name"];
				$this->db->where('code', 'FAVICON_IMAGE');
				$this->db->update('settings',$data9);

				$logo = $this->db->get_where('settings', array('code' => 'FAVICON_IMAGE'))->row()->string_value;
					}
/*	WITHOUT CDN			
                $real_logo = $this->path.'/logo/'.$logo;
				
$config['image_library'] = 'gd2';
$config['allowed_types'] = 'jpg|jpeg|gif|png';
$config['overwrite']=TRUE;
$config['source_image'] = $real_logo;
//$config['new_image'] = 'logo/logo.png';
$config['maintain_ratio'] = FALSE;
$config['width'] = 32;
$config['height'] = 32;

$this->image_lib->initialize($config);

if ( ! $this->image_lib->resize())
{
       $logo = '<img src="'.base_url().'logo/'.$logo.'" alt="logo">';
	echo  $logo.'#<p>'.translate_admin('Please upload correct file.').'</p>';exit;
}
*/
									
				$data9['string_value']    = $_FILES["favicon"]["name"];
				$this->db->where('code', 'FAVICON_IMAGE');
				$this->db->update('settings',$data9);
				
			}

// favicon image 1 end
				$temp1 = explode('.', $_FILES["logo"]["name"]);
                $ext1  = array_pop($temp1);
                $name2 = implode('.', $temp1);
        
                try{
                    $cloudimage=\Cloudinary\Uploader::upload($_FILES["logo"]["tmp_name"],
                    array("public_id" => "logo/".$name2));
                    }
                catch (Exception $e) {
                    $error = $e->getMessage();
                    }
                $secureimage = $cloudimage['secure_url'];   
	
    	if($_FILES["logo"]["name"])
				{
				$logo = $this->db->get_where('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
              /*
                $real_logo = $this->path.'/logo/'.$logo;
				
				
    	$config1 = array(
						'allowed_types' => 'jpg|jpeg|gif|png',
						'upload_path' => 'logo',
						'overwrite' => true,
						'remove_spaces' => TRUE,
						);
					$this->load->library('upload', $config1);
					if(!$this->upload->do_upload('logo'))
					{
    						   $logo = '<img src="'.base_url().'logo/'.$logo.'" alt="logo">';
						echo $logo.'#<p>Please upload correct file.</p>';exit;
					}
					else */
					{
				   // $upload_data = $this->upload->data(); 				
			     // $image_name    = $upload_data['file_name'];
						$data7['string_value']    = $_FILES["logo"]["name"];
				$this->db->where('code', 'SITE_LOGO');
				$this->db->update('settings',$data7);

				$logo = $this->db->get_where('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
					}
				
                // $real_logo = $this->path.'/logo/'.$logo;
				
// $config['image_library'] = 'gd2';
// $config['allowed_types'] = 'jpg|jpeg|gif|png';
// $config['overwrite']=TRUE;
// $config['source_image'] = $real_logo;
// //$config['new_image'] = 'logo/logo.png';
// $config['maintain_ratio'] = FALSE;
// $config['width'] = 137;
// $config['height'] = 45;
// 
// $this->image_lib->initialize($config);

// if ( ! $this->image_lib->resize())
// {
       // $logo = '<img src="'.base_url().'logo/'.$logo.'" alt="logo">';
	// echo  $logo.'#<p>'.translate_admin('Please upload correct file.').'</p>';exit;
// }

									
				$data7['string_value']    = $_FILES["logo"]["name"];
				$this->db->where('code', 'SITE_LOGO');
				$this->db->update('settings',$data7);
				
			}
		 	$query6                  = $this->db->get_where('settings', array('code' => 'SITE_LOGO'));

		 	$logo = '<img src="'.base_url().'logo/'.$query6->row()->string_value.'" alt="logo">';
				echo  $logo.'#<p>'.translate_admin('Settings updated successfully').'</p>';
			
				$data8['default']    = 1;
				$this->db->update('currency', array('default' => '0'));
				$this->db->where('currency_code', $this->input->post('currency'));
				$this->db->update('currency',$data8);	
				
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Settings updated successfully')));
			    redirect_admin('settings');
			}
			else
			{
			$query1                  = $this->db->get_where('settings', array('code' => 'SITE_TITLE'));
			$data['site_title']      = $query1->row()->string_value;
			
			$query2                  = $this->db->get_where('settings', array('code' => 'SITE_SLOGAN'));
			$data['site_slogan']     = $query2->row()->string_value;
			
			$query3                  = $this->db->get_where('settings', array('code' => 'SITE_STATUS'));
			$data['site_status']     = $query3->row()->int_value;
			
			$query4                  = $this->db->get_where('settings', array('code' => 'OFFLINE_MESSAGE'));
			$data['offline_message'] = $query4->row()->text_value;
			
			$query5                  = $this->db->get_where('settings', array('code' => 'GOOGLE_ANALYTICS_CODE'));
			$data['google_analytics']= $query5->row()->text_value;
			
			$query6                  = $this->db->get_where('settings', array('code' => 'SITE_ADMIN_MAIL'));
			$data['super_admin']     = $query6->row()->string_value;
			
			$query7                  = $this->db->get_where('settings', array('code' => 'SITE_LOGO'));
			$data['logo']     		 = base_url().'logo/'.$query7->row()->string_value;
			
			// favicon image 2 start
			
			$query9                  = $this->db->get_where('settings', array('code' => 'FAVICON_IMAGE'));
			$data['favicon']     		 = base_url().'logo/'.$query9->row()->string_value;
			
			// favicon image 2 end
			
			
			
			$query8					 = $this->Common_model->getTableData('currency',array('status'=>1));
			$data['currencies']		 = $query8; 
			
			$data['message_element'] = "administrator/settings/site_settings";
			$this->load->view('administrator/admin_template', $data);
			}	
	}
	
	
	public function lang_front()
	{
	 if($this->input->post('update'))
		{
				$data['int_value']       = $this->input->post('language_translator');
				
				if($data['int_value'] == 1)
				$data['string_value']    = $this->input->post('core_lang');
				
				$this->db->where('code', 'FRONTEND_LANGUAGE');
				$this->db->update('settings',$data);
				
				echo '<p>'.translate_admin('Settings updated successfully').'</p>';
		}
		else
		{
		$query                       = $this->db->get_where('settings', array('code' => 'FRONTEND_LANGUAGE'));
		$data['language_translator'] = $query->row()->int_value;
		$data['core_lang']           = $query->row()->text_value;
		$data['google_lang']         = $query->row()->string_value;
		
		$data['languages']           = $this->Common_model->getTableData('language')->result();
		
 	$data['message_element'] = "administrator/settings/lang_front";
		$this->load->view('administrator/admin_template', $data);
		}
	}
	
	
	public function lang_back()
	{
	 if($this->input->post('update'))
		{
				$data['int_value']       = $this->input->post('language_translator');
				
				if($data['int_value'] == 1)
				$data['string_value']    = $this->input->post('core_lang');
				
				$this->db->where('code', 'BACKEND_LANGUAGE');
				$this->db->update('settings',$data);
				
				echo '<p>'.translate_admin('Settings updated successfully').'</p>';
		}
		else
		{
		$query                       = $this->db->get_where('settings', array('code' => 'BACKEND_LANGUAGE'));
		$data['language_translator'] = $query->row()->int_value;
		$data['core_lang']           = $query->row()->text_value;
		$data['google_lang']         = $query->row()->string_value;
		
  $data['languages']           = $this->Common_model->getTableData('language')->result();		
		
 	$data['message_element'] = "administrator/settings/lang_back";
		$this->load->view('administrator/admin_template', $data);
		}
		}
	
	
	public function home_meta_settings()
	{
		if($this->input->post('update'))
		{
		 	$data1['string_value']     = $this->input->post('meta_keyword');
				$this->db->where('code', 'META_KEYWORD');
				$this->db->update('settings',$data1);
				
				$data2['string_value']    = $this->input->post('meta_description');
				$this->db->where('code', 'META_DESCRIPTION');
				$this->db->update('settings',$data2);
				
			echo '<p>'.translate_admin('Settings updated successfully').'</p>';
		}
		else
		{
		$query1                  = $this->db->get_where('settings', array('code' => 'META_KEYWORD'));
		$data['meta_keyword']    = $query1->row()->string_value;
		
		$query2                  = $this->db->get_where('settings', array('code' => 'META_DESCRIPTION'));
		$data['meta_description'] = $query2->row()->string_value;
		
		$data['message_element'] = "administrator/settings/manage_meta";
		$this->load->view('administrator/admin_template', $data);
		}
	}
	
	public function change_password()
	{
		if($this->input->post('update'))
		{
		  $majorsalt = '';
		  
				$newpassword = $this->input->post('new_password');
				
				$password     = $this->input->post('old_password');
		$user_id      = $this->dx_auth->get_user_id();
		$stored_hash  = get_user_by_id($user_id)->password;
		
	 $password     = $this->dx_auth->_encode($password);
		
		if (crypt($password, $stored_hash) === $stored_hash)
		{
			
			// if PHP5
			if (function_exists('str_split'))
			{
				$_pass = str_split($newpassword);
			}
			// if PHP4
			else
			{
				$_pass = array();
				if (is_string($newpassword))
				{
					for ($i = 0; $i < strlen($newpassword); $i++)
					{
						array_push($_pass, $newpassword[$i]);
					}
				}
			}
			foreach ($_pass as $_hashpass)
			{
				$majorsalt .= md5($_hashpass);
			}
			$final_pass = crypt(md5($majorsalt));
	
		 $data['password']     = $final_pass;
			$this->db->where('id', 1);
			$this->db->update('users',$data);
			
			echo '<p>'.translate_admin('Settings updated successfully').'</p>';
		}
		else
		{   echo '<span style="color:red;">'.translate_admin('Your Old Password is wrong').'</span>';
			}
		}
		else
		{
		$data['message_element'] = "administrator/settings/change_password";
		$this->load->view('administrator/admin_template', $data);
		}
	}
	
	public function how_it_works()
	{
	 if($this->input->post('update'))
		{
		   if($this->input->post('display_type') == 0)
					{
								if($_FILES["media"]["name"])
								{
								$media = $this->db->get_where('settings', array('code' => 'HOW_IT_WORKS'))->row()->string_value;
								$real_logo = $this->path.'/uploads/howit/'.$media;
								//unlink($real_logo);
								
									$config = array(
										'allowed_types' => 'mp4|flv|FLV',
										'upload_path'   => 'uploads/howit',
										'remove_spaces' => TRUE
										);
									$this->load->library('upload', $config);
									if(!$this->upload->do_upload('media'))
									{
										$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please Upload Correct File or MP4 Video')));
										redirect_admin('settings/how_it_works');
									}
								//$this->upload->display_errors('<p>','</p>');
								$upload_data = $this->upload->data(); 				
			      				$file_name    = $upload_data['file_name'];
								$data1['string_value']    = $file_name;
								$this->db->where('code', 'HOW_IT_WORKS');
								$this->db->update('settings',$data1);
								
								$data2['int_value']    = 0;
								$this->db->where('code', 'HOW_IT_WORKS');
								$this->db->update('settings',$data2);
								$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Successfully Uploaded')));
								redirect_admin('settings/how_it_works');
							}
else
	{
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please Upload Correct File or MP4 Video')));
										redirect_admin('settings/how_it_works');
	}
					}
					else if($this->input->post('display_type') == 1)
					{
						$this->form_validation->set_rules('embed_code', 'Embeded code field', 'required');
						if($this->form_validation->run())
						{	 
	//$pattern = '%(?:https?://)?(?:www\.)?(?:youtu\.be/| view.vzaar\.com(?:/embed/|/v/|/watch\?v=))([\w-]{10,12})[a-zA-Z0-9\< \>\"]%x';
//	if(!preg_match($pattern, $this->input->post('embed_code'), $matches))
//	{
//$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Enter the Valid Embed Code.'));
//		redirect_admin('settings/how_it_works');
//	}
	$code = $this->input->post('embed_code');
		$last = substr($this->input->post('embed_code'), -1); 	
		/* if($last != '>')
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Enter the Valid Embed Code.'));
		redirect_admin('settings/how_it_works');
		}		
		if($code[0] != '<')
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Enter the Valid Embed Code.'));
		redirect_admin('settings/how_it_works');
		}	*/
		                $data1['text_value']    = $this->input->post('embed_code') ;
						$this->db->where('code', 'HOW_IT_WORKS');
						$this->db->update('settings',$data1);
						
						$data2['int_value']    = 1;
						$this->db->where('code', 'HOW_IT_WORKS');
						$this->db->update('settings',$data2);
						$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Successfully Uploaded')));
						redirect_admin('settings/how_it_works');
						}
						}
			
		}

  $data['display_type']    = $this->db->get_where('settings', array('code' => 'HOW_IT_WORKS'))->row()->int_value;
		$data['media']           = $this->db->get_where('settings', array('code' => 'HOW_IT_WORKS'))->row()->string_value;
		$data['embed_code']      = $this->db->get_where('settings', array('code' => 'HOW_IT_WORKS'))->row()->text_value;
		
		$data['message_element'] = "administrator/settings/how_it_works";
		$this->load->view('administrator/admin_template', $data);
	
	}	

 function banner()
 {
 	
	if($this->input->post())
	{
		if($_FILES["media"]["name"])
								{
								$media = $this->db->get_where('settings', array('code' => 'BANNER_VIDEO'))->row()->string_value;
								$real_logo = $this->path.'/uploads/banner/'.$media;
								unlink($real_logo);
								
									$config = array(
										'allowed_types' => 'mp4',
										'upload_path'   => 'uploads/banner',
										'overwrite' => true,
										'remove_spaces' => TRUE,
										);
									$this->load->library('upload', $config);
									if(!$this->upload->do_upload('media'))
									{
										$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please Upload Correct MP4 File.')));
										redirect_admin('settings/banner');
									}
									else {
										$upload_data = $this->upload->data(); 				
			                            $video_name    = $upload_data['file_name'];
									}
								//$this->upload->display_errors('<p>','</p>');
								$data1['string_value']    = $video_name;
								$this->db->where('code', 'BANNER_VIDEO');
								$this->db->update('settings',$data1);
								
								$data2['int_value']    = 0;
								$this->db->where('code', 'BANNER_VIDEO');
								$this->db->update('settings',$data2);
								$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Successfully Uploaded')));
								redirect_admin('settings/banner');
							}	
	}
	else {
		$data["video_url"]   = $this->Common_model->getTableData('settings', array('code' => 'BANNER_VIDEO'))->row()->string_value;
		$data['message_element'] = "administrator/settings/banner";
		$this->load->view('administrator/admin_template',$data);
	}
	
 }
	
}
?>
