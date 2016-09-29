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
	
class Cancellation extends CI_Controller {

	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/
	public function Cancellation()
	{
	 parent::__construct();

		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//load model
		$this->load->model('cancellation_model');		
		$this->dx_auth->check_uri_permissions();

	}//Controller End 
	

	
	/**
	 * Loads Faqs settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function addCancellation()
	{	
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('addCancellation'))
		{	
			extract($this->input->post());	
			
           	//Set rules
			$this->form_validation->set_rules('name','Policy Name','required|trim|xss_clean');
			$this->form_validation->set_rules('cancellation_title','Cancellation Title','required|trim|xss_clean');
			$this->form_validation->set_rules('cancellation_content','Cancellation Content','required|trim|xss_clean');
			$this->form_validation->set_rules('cleaning_refund','','required|trim|xss_clean');
			$this->form_validation->set_rules('security_refund','','required|trim|xss_clean');
			$this->form_validation->set_rules('additional_refund','','required|trim|xss_clean');
			$this->form_validation->set_rules('list_refund_before','','required|trim|xss_clean');
			$this->form_validation->set_rules('list_refund_after','','required|trim|xss_clean');
			$this->form_validation->set_rules('list_days_prior_status','','required|trim|xss_clean');
			
			if($list_refund_before == 1)
			{
				$this->form_validation->set_rules('list_before_percentage','Before checkin percentage','required|trim|xss_clean|numeric|greater_than[0]|less_than[101]|integer');
			}
			
			if($list_refund_after == 1)
			{
				$this->form_validation->set_rules('list_after_percentage','After checkin percentage','required|trim|xss_clean|numeric|greater_than[0]|less_than[101]|integer');
			}

			if($list_days_prior_status == 1)
			{
				$this->form_validation->set_rules('list_days_prior_percentage','Days prior percentage','required|trim|xss_clean|numeric|greater_than[0]|less_than[101]|integer');
			}
			
			if($list_refund_before == 1 && $list_days_prior_status == 1)
			{
			$this->form_validation->set_rules('list_before_days','Before checkin days','required|trim|xss_clean|callback_check_days');
			}

			if($this->form_validation->run())
			{	
				  //prepare insert data
				  $insertData                  	  	= array();
				   
				  $insertData['name']  	      					   = $name;	
			   	  $insertData['cancellation_title'] 	           = $cancellation_title;
				  $insertData['cancellation_content']  	           = $cancellation_content;
				  
				  $insertData['cleaning_status']  	               = $cleaning_refund;
				  
				  $insertData['security_status']  	               = $security_refund;
				  
				  $insertData['additional_status']  	           = $additional_refund;
				  
				  $insertData['list_days_prior_status']  	       = $list_days_prior_status;
				  $insertData['list_days_prior_days']  	           = $list_days_prior_days;
				  $insertData['list_days_prior_percentage']        = $list_days_prior_percentage;
				  
				  $insertData['list_before_status']  	           = $list_refund_before;
				  $insertData['list_before_days']  	               = $list_before_days;
				  $insertData['list_before_percentage']            = $list_before_percentage;
				  $insertData['list_after_status']  	           = $list_refund_after;
				  $insertData['list_non_refundable_nights']  	   = $list_non_refund_days;
				  $insertData['list_after_days']  	               = $list_after_days;
				  $insertData['list_after_percentage']     	       = $list_after_percentage;
				  
				  $insertData['is_standard']     	       		   = $is_standard;
				  
				  //Add Groups
				  $this->cancellation_model->addCancellation($insertData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Cancellation Policy added successfully')));
				  redirect_admin('cancellation/viewCancellation');
		 	} 
		} //If - Form Submission End
	elseif($this->input->post('cancel')) {
	redirect_admin('cancellation/viewCancellation');
}
		
	 $data['message_element'] = "administrator/cancellation_policy/addCancellation";
		$this->load->view('administrator/admin_template', $data);
	
	}//Function addPage End 
	

	
	/**
	 * Loads Manage Static Pages View.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function viewcancellation()
	{
		
		if((!$this->dx_auth->is_logged_in()) || $this->dx_auth->get_user_id() != 1)
			{
				redirect('info/deny');
			}
		//Get Groups
		$data['cancellation']	=	$this->cancellation_model->getcancellation();
		
		//Load View	
	 $data['message_element'] = "administrator/cancellation_policy/viewCancellation";
		$this->load->view('administrator/admin_template', $data);

	}//End of 	
	

	
	/**
	 * Delete Faq.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function deleteCancellation()
	{	
	$id = $this->uri->segment(4,0);
	
	if($this->input->post())
	extract($this->input->post());
		
	if($id != 0)
	{
	$condition = array('cancellation_policy.id'=>$id);
	
	if($id == 4 || $id == 5)
	{
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry you're not able to delete this cancellation policy.")));
	redirect_admin('cancellation/viewCancellation');
	}
	
	$data['cancellations']	=	$this->cancellation_model->getCancellation($condition);
	if($data['cancellations']->num_rows() == 0)
	{
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This cancellation policy already deleted.')));
	redirect_admin('cancellation/viewCancellation');
	}
		
	$check = $this->cancellation_model->check_deleteCancellation(NULL,$condition);
	
	if($check->num_rows() == 0)
	{
	$this->cancellation_model->deleteCancellation(NULL,$condition);
	}
	else
	{
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Your chosen cancellation policy is used by some lists.')));
	redirect_admin('cancellation/viewCancellation');
	}
	}
	else if($checkbox)
	{
		foreach($checkbox as $value)
		{
		 $condition = array('cancellation_policy.id'=>$value);
		
		 $check = $this->cancellation_model->check_deleteCancellation(NULL,$condition);
		
		if($value == 4 || $value == 5)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry you're not able to delete this cancellation policy.")));
			redirect_admin('cancellation/viewCancellation');
		}
		
		if($check->num_rows() != 0)
		{
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Your chosen cancellation policy is used by some lists.')));
		redirect_admin('cancellation/viewCancellation');
		}
		}
		
		foreach($checkbox as $value)
		{
		 $condition = array('cancellation_policy.id'=>$value);
		
		 $check = $this->cancellation_model->check_deleteCancellation(NULL,$condition);
	
		if($check->num_rows() == 0)
		{
		$this->cancellation_model->deleteCancellation(NULL,$condition);
		}
		else
		{
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Your chosen cancellation policy is used by some lists.')));
		redirect_admin('cancellation/viewCancellation');
		}
		}
	}
			
		//Notification message
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Cancellation Policy deleted successfully')));
		redirect_admin('cancellation/viewCancellation');
	}
	//Function end
	
	
	
	/**
	 * Loads Manage Static Pages View.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function editCancellation()
	{		
		//Get id of the category	
	 $id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('editCancellation'))
		{
			
			if($id == 5)
			{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry you're not able to update this cancellation policy.")));
			redirect_admin('cancellation/viewCancellation');
			}
	
			extract($this->input->post());	
			
           	//Set rules
			$this->form_validation->set_rules('name','Policy Name','required|trim|xss_clean');
			$this->form_validation->set_rules('cancellation_title','Cancellation Title','required|trim|xss_clean');
			$this->form_validation->set_rules('cancellation_content','Cancellation Content','required|trim|xss_clean');
						
			if($list_refund_before == 1)
			{
				$this->form_validation->set_rules('list_before_percentage','Before checkin percentage','required|trim|xss_clean|numeric|greater_than[0]|less_than[101]|integer');
			}
			
			if($list_refund_after == 1)
			{
				$this->form_validation->set_rules('list_after_percentage','After checkin percentage','required|trim|xss_clean|numeric|greater_than[0]|less_than[101]|integer');
			}

			if($list_days_prior_status == 1)
			{
				$this->form_validation->set_rules('list_days_prior_percentage','Days prior percentage','required|trim|xss_clean|numeric|greater_than[0]|less_than[101]|integer');
			}
			
			if($list_refund_before == 1 && $list_days_prior_status == 1)
			{
			$this->form_validation->set_rules('list_before_days','Before checkin days','required|trim|xss_clean|callback_check_days');
			}
			
			if($this->form_validation->run())
			{					
				  //prepare update data
				  $updateData                  	  	= array();
				   
				  $updateData['name']  	      					   = $name;	
			   	  $updateData['cancellation_title'] 	           = $cancellation_title;
				  $updateData['cancellation_content']  	           = $cancellation_content;
				  
				  $updateData['cleaning_status']  	               = $cleaning_refund;
				  
				  $updateData['security_status']  	               = $security_refund;
				  
				  $updateData['additional_status']  	           = $additional_refund;
				  
				  $updateData['list_days_prior_status']  	       = $list_days_prior_status;
				  $updateData['list_days_prior_days']  	           = $list_days_prior_days;
				  $updateData['list_days_prior_percentage']        = $list_days_prior_percentage;
				  
				  $updateData['list_before_status']  	           = $list_refund_before;
				  $updateData['list_before_days']  	               = $list_before_days;
				  $updateData['list_before_percentage']            = $list_before_percentage;
				  $updateData['list_after_status']  	           = $list_refund_after;
				  $updateData['list_after_days']  	               = $list_after_days;
				  $updateData['list_non_refundable_nights']  	   = $list_non_refund_days;
				  $updateData['list_after_percentage']     	       = $list_after_percentage;
				  
				  $updateData['is_standard']     	       		   = $is_standard;
				  
				  $check_is_standard = $this->Common_model->getTableData('cancellation_policy',array('is_standard'=>"1"));
				  
				  if($check_is_standard->num_rows() == 1)
				  {
				  	if($check_is_standard->row()->id == $this->uri->segment(4) && $is_standard == "0")
					{
				  	 //Notification message
				 	 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Atleast one cancellation policy should exist.')));
					 redirect_admin('cancellation/viewCancellation');
					}
				  }
				  
				  $updateKey = array('cancellation_policy.id'=>$this->uri->segment(4));
				  
				  //Add Groups
				  $this->cancellation_model->updateCancellation($updateKey,$updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Cancellation Policy updated successfully')));
				 redirect_admin('cancellation/viewCancellation');
		 		} 
			} //If - Form Submission End
			elseif($this->input->post('cancel')) {
				redirect_admin('cancellation/viewCancellation');
			}
		
		$condition = array('cancellation_policy.id'=>$id);
			
	 //Get Groups
		$data['cancellations']	=	$this->cancellation_model->getCancellation($condition);
		
		$data['check_cancellation'] = $this->Common_model->getTableData('list',array('cancellation_policy'=>$this->uri->segment(4)))->num_rows();
		
		if($data['cancellations']->num_rows() == 0)
		{
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This cancellation policy already deleted.')));
	        redirect_admin('cancellation/viewCancellation');
		}

			//Load View	
	$data['message_element'] = "administrator/cancellation_policy/editCancellation";
		$this->load->view('administrator/admin_template', $data);
   
	}//End of editPage
	
	function check_policy()
	{
		extract($this->input->post());
		
		$result = $this->Common_model->getTableData('list',array('cancellation_policy'=>$id));
		
		if($result->num_rows() != 0)
		{
			echo 'yes';
		}
		else
		{
			echo 'no';	
		}
	}

	public function edit_host_Cancellation()
	{		
		//Get id of the category	
	 $id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('editCancellation'))
		{
			extract($this->input->post());	
			
           	//Set rules
			$this->form_validation->set_rules('before_amount','Before Days Prior Amount','required|trim|xss_clean|numeric|greater_than[0]|integer|callback_price_check');
			$this->form_validation->set_rules('after_amount','After Days Prior Amount','required|trim|xss_clean|numeric|greater_than[0]|integer|callback_price_check');
			$this->form_validation->set_rules('free_cancellation','Free Cancellation Limit','required|trim|xss_clean|numeric|less_than[1001]|integer');
									
			if($this->form_validation->run())
			{					
				  //prepare update data
				  $updateData                  	    = array();
				 				  
				  $updateData['days']  	          	= $days;
				  $updateData['months']  	      	= $months;
				  $updateData['before_amount']    	= $before_amount;
				  $updateData['after_amount']  	  	= $after_amount;
				  $updateData['currency']  	     	= $currency;
				  $updateData['free_cancellation']  = $free_cancellation;
				   
				  $updateKey = array('id'=>1);
				  
				  //Add Groups
				  $this->Common_model->updateTableData('host_cancellation_policy',0,$updateKey,$updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Cancellation Policy updated successfully')));
				 redirect_admin('cancellation/edit_host_Cancellation');
		 	} 
		} //If - Form Submission End
		elseif($this->input->post('cancel')) {
			redirect_admin('cancellation/edit_host_Cancellation');
			}
		
		$condition = array('id'=>1);
			
	 //Get Groups
		$data['cancellations']	=	$this->Common_model->getTableData('host_cancellation_policy',$condition);
		
		$data['currency'] = $this->Common_model->getTableData('currency',array('status'=>1));
		
		//Load View	
	$data['message_element'] = "administrator/cancellation_policy/edit_host_Cancellation";
		$this->load->view('administrator/admin_template', $data);
   
	}//End of editPage
	
	function price_check($value)
	{
		$convert_value = round(get_currency_value_lys('USD',$this->input->post('currency'),300));
		
		if($value <= $convert_value)
		{
		return TRUE;	
		}
		else
		{
		$this->form_validation->set_message('price_check', 'Amount should be less than or equal to '.$convert_value.$this->input->post('currency'));
		return FALSE;
		}
	}

	function check_days($value)
	{
		if($value <= $this->input->post('list_days_prior_days'))
		{
		return TRUE;	
		}
		else
		{
		$this->form_validation->set_message('check_days', 'Before checkin days are less than or equal to Days prior days.');
		return FALSE;
		}
	}
	
}
//End  Page Class

/* End of file Page.php */ 
/* Location: ./app/controllers/admin/Page.php */