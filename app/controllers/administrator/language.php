<?php
/**
 * Dropinn page Class
 *
 * Permits admin to handle the static pages of the site
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Manage Static Page 
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @created		December 22 2008
 * @link		http://www.cogzidel.com
 
 */
	
class Language extends CI_Controller {

	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/
	public function Language()
	{
	 parent::__construct();

		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
	
		$this->dx_auth->check_uri_permissions();

	}//Controller End 
	

	
	/**
	 * Loads Faqs settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function add_language()
	{	
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('add_language'))
		{	
			extract($this->input->post());	
			
           	//Set rules
			$this->form_validation->set_rules('name','Language Name','required|trim|xss_clean|alpha|callback_name_check');
			$this->form_validation->set_rules('language_code','Language Code','required|trim|xss_clean|alpha|callback_code_check');
			$this->form_validation->set_rules('status','Status','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{
				extract($this->input->post());
				
				$data['name'] = $name;
				$data['code'] = $language_code;
				$data['status'] = $status;
				
				$this->Common_model->inserTableData('language',$data);
				
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Language Added Successfully.')));
				redirect_admin('language/view_languages');
			}
			
			
		} //If - Form Submission End
	elseif($this->input->post('cancel')) {
	redirect_admin('language/view_languages');
}
		
	 $data['message_element'] = "administrator/language/add_language";
		$this->load->view('administrator/admin_template', $data);
	
	}//Function addPage End 
	

	
	/**
	 * Loads Manage Static Pages View.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function view_languages()
	{
		
		if((!$this->dx_auth->is_logged_in()) || $this->dx_auth->get_user_id() != 1)
			{
				redirect('info/deny');
			}
		//Get Groups
		$data['languages']	=	$this->Common_model->getTableData('language');
		
		//Load View	
	 $data['message_element'] = "administrator/language/view_language";
		$this->load->view('administrator/admin_template', $data);

	}//End of 	
	

	
	/**
	 * Delete Faq.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function delete_language()
	{	
	$id = $this->uri->segment(4,0);
	
	if($this->input->post())
	extract($this->input->post());
		
	if($id != 0)
	{
	$condition = array('language.id'=>$id);
		
	if($id <= 6)
	{
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry you're not able to delete this language.")));
	redirect_admin('language/view_languages');
	}
	
	$check_lang = $this->db->query('select * from profiles where find_in_set('.$id.',language)');
	
	if($check_lang->num_rows() != 0)
	{
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry this language is used by some one, so you're not able to delete it.")));
	redirect_admin('language/view_languages');	
	}
	
	$data['languages']	=	$this->Common_model->getTableData('language',$condition);
	if($data['languages']->num_rows() == 0)
	{
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This language already deleted.')));
	redirect_admin('language/view_languages');
	}
	
	$this->Common_model->deleteTableData('language',$condition);
	
	//Notification message
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Language deleted successfully')));
		redirect_admin('language/view_languages');
	
	}
	else if($checkbox)
	{
		
		foreach($checkbox as $value)
		{
		 $condition = array('language.id'=>$value);
		
		 $check = $this->Common_model->getTableData('language',$condition);
		
		if($value <= 6)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry you're not able to delete this language.")));
			redirect_admin('language/view_languages');
		}

		$check_lang = $this->db->query('select * from profiles where find_in_set('.$value.',language)');
	
		if($check_lang->num_rows() != 0)
		{
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry this language is used by some one, so you're not able to delete it.")));
		redirect_admin('language/view_languages');	
		}
	
		}
				
		foreach($checkbox as $value)
		{
		 $condition = array('language.id'=>$value);
		
		$this->Common_model->deleteTableData('language',$condition);
		}
		
		//Notification message
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Language deleted successfully')));
		redirect_admin('language/view_languages');
	}
	else
	{
	//Notification message
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please choose atleast one language.')));
		redirect_admin('language/view_languages');
	}

	}
	//Function end
	
	
	
	/**
	 * Loads Manage Static Pages View.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function edit_language()
	{		
		//Get id of the category	
	 $id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('edit_language'))
		{	
			extract($this->input->post());	
			
           	//Set rules
			$this->form_validation->set_rules('name','Language Name','required|trim|xss_clean|alpha|callback_edit_name_check');
			$this->form_validation->set_rules('language_code','Language Code','required|trim|xss_clean|alpha|callback_edit_code_check');
			$this->form_validation->set_rules('status','Status','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{
				extract($this->input->post());
				
				$data['name'] = $name;
				$data['code'] = $language_code;
				$data['status'] = $status;
				
				$this->Common_model->updateTableData('language',0,array('id'=>$id),$data);
				
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Language Updated Successfully.')));
				redirect_admin('language/view_languages');
			}
			
			
		} //If - Form Submission End
			elseif($this->input->post('cancel')) {
				redirect_admin('cancellation/viewCancellation');
			}
		
		$condition = array('id'=>$id);
			
	 //Get Groups
		$data['languages']	=	$this->Common_model->getTableData('language',$condition);
		
		$data['check_language'] = $this->Common_model->getTableData('language',array('id'=>$this->uri->segment(4)))->num_rows();
		
		if($data['languages']->num_rows() == 0)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This cancellation policy already deleted.')));
	        redirect_admin('cancellation/viewCancellation');
		}

			//Load View	
	$data['message_element'] = "administrator/language/edit_language";
		$this->load->view('administrator/admin_template', $data);
   
	}//End of editPage
	
	function name_check($value)
	{
		$name_result = $this->Common_model->getTableData('language',array('name'=>$value));
		
		if($name_result->num_rows() == 0)
		{
		return TRUE;	
		}
		else
		{
		$this->form_validation->set_message('name_check', 'This Language name is already registered. Please enter new one.');
		return FALSE;
		}
	}

	function edit_name_check($value)
	{
		$name_result = $this->Common_model->getTableData('language',array('name'=>$value,'id !='=>$this->uri->segment(4)));
		
		if($name_result->num_rows() == 0)
		{
		return TRUE;	
		}
		else
		{
		$this->form_validation->set_message('edit_name_check', 'This Language name is already registered. Please enter new one.');
		return FALSE;
		}
	}

	function code_check($value)
	{
		$name_result = $this->Common_model->getTableData('language',array('code'=>$value));
		if($name_result->num_rows() == 0)
		{
		return TRUE;	
		}
		else
		{
		$this->form_validation->set_message('code_check', 'This Language code is already registered. Please enter new one.');
		return FALSE;
		}
	}
	
	function edit_code_check($value)
	{
		$name_result = $this->Common_model->getTableData('language',array('code'=>$value,'id !='=>$this->uri->segment(4)));
		
		if($name_result->num_rows() == 0)
		{
		return TRUE;	
		}
		else
		{
		$this->form_validation->set_message('edit_code_check', 'This Language code is already registered. Please enter new one.');
		return FALSE;
		}
	}
	
}
//End  Page Class

/* End of file Page.php */ 
/* Location: ./app/controllers/admin/Page.php */