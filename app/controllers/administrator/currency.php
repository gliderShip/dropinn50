<?php
/**
 * Dropinn help Class
 *
 * Permits admin to handle the static helps of the site
 *
 * @package		Dropinn
 * @subpackage	Controllers
 * @category	Manage Static help 
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @created		December 22 2008
 * @link		http://www.cogzidel.com
 
 */
	
class Currency extends CI_Controller {


	public function Currency()
	{
	 parent::__construct();

		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		$this->load->helper('text');
		
$this->dx_auth->check_uri_permissions();
$this->path=realpath(APPPATH);

	}
	

	public function addcurrency()
	{	
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
			//Set rules
				
		if($this->input->post('addcurrency'))
		{	
			$this->form_validation->set_rules('currency_name','Currency Name','required|trim|xss_clean');
			$this->form_validation->set_rules('currency_code','Currency Code','required|trim|xss_clean|callback_currency_code');//|callback_helpUrlCheck|callback_helpUrlValid');
			$this->form_validation->set_rules('currency_symbol','Currency Symbol','required|trim|callback_currency_symbol');
			$this->form_validation->set_rules('currency_value','Currency Value','required|numeric|greater_than[0]|trim');
			$this->form_validation->set_rules('default','Currency Default','required|trim|callback_currency_default');
			 
			if($this->form_validation->run())
			{
				
				  //prepare insert data
				  $insertData                  	  	= array();
				  $insertData['currency_name'] 		     = $this->input->post('currency_name');
				  $insertData['currency_code']  	   = strtoupper($this->input->post('currency_code'));
				  $insertData1['currency_code']  	   = strtoupper($this->input->post('currency_code'));
				  $insertData['currency_symbol']  		   = $this->input->post('currency_symbol');
				  $insertData1['currency_value'] = $this->input->post('currency_value');
				  $insertData['status']  		     = $this->input->post('status');
				   $insertData['default']		        	= $this->input->post('default');
				
		
				  //Add Group
				 
				  $this->db->insert('currency_converter',$insertData1);
				  
				  $this->db->insert('currency',$insertData);
									  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Currency added successfully')));
				  redirect_admin('currency');
				  }
				  }
		 //If - Form Submission End
			//Get Faq Categories
		$data['currency']=	$this->db->join('currency_converter','currency.id=currency_converter.id')->get('currency');
		
	 $data['message_element'] = "administrator/currency/addcurrency";
		$this->load->view('administrator/admin_template', $data);
	
	}
	

	function currency_code($str)
	{
		$str = strtoupper($str);
		$result = $this->db->where('currency_code',$str)->get('currency');
		if($result->num_rows() == 0)
		{
			$url = file_get_contents("http://openexchangerates.org/api/latest.json?app_id=8d43d9c41911416bb8d838e179812889");
        $json_a=json_decode($url,true);
		
		if(!array_key_exists($str,$json_a['rates']))
		{
		   $this->form_validation->set_message('currency_code', 'Please give valid Currency Code.');
			return FALSE;	
		}
		else {
			return TRUE;
		}
		}
		else
			{
				if($this->uri->segment(4) != 0)
				{
					$currency_code = $this->db->where('id',$this->uri->segment(4))->get('currency')->row()->currency_code;
					if($currency_code == $str)
					{
						return TRUE;
				    }
				}
			 $this->form_validation->set_message('currency_code', 'Already exist Currency Code.');
			return FALSE;		
			}
			
		$url = file_get_contents("http://openexchangerates.org/api/latest.json?app_id=8d43d9c41911416bb8d838e179812889");
        $json_a=json_decode($url,true);
		
		if(!array_key_exists($this->input->post('currency_code'),$json_a['rates']))
		{
		   $this->form_validation->set_message('currency_code', 'Please give valid Currency Code.');
			return FALSE;	
		}
		else {
			return TRUE;
		}
	}
	
	public function index()
	{	
        $this->load->library('Pagination');
		
		// Get offset and limit for page viewing
		$start = (int) $this->uri->segment(4,0);
		
	 // Number of record showing per page
		$row_count = 10;
		
		if($start > 0)
		   $offset			  = ($start-1) * $row_count;
		else
		   $offset			  =  $start * $row_count; 
				
		//Get Groups
		$data['currency']	=	$this->db->join('currency','currency.currency_code=currency_converter.currency_code')->get('currency_converter',$row_count,$offset);
		$currency	=	$this->db->join('currency','currency.currency_code=currency_converter.currency_code')->get('currency_converter');
		
		// Pagination config
		$p_config['base_url'] 			= admin_url('currency/index');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $currency->num_rows();
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		
		// Create pagination links
		$data['pagination']     = $this->pagination->create_links2();
		//print_r($data['pagination']);exit;
		//Load View	
	 $data['message_element'] = "administrator/currency/viewcurrency";
		$this->load->view('administrator/admin_template', $data);
	   
	}//End of 	
	

	
	/**
	 * Delete Faq.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function deletecurrency()
	{	
	$id = $this->uri->segment(4,0);
		
	$condition = array('currency.id'=>$id);
	
	$result_check = $this->Common_model->getTableData('currency',$condition);
	
	if($result_check->num_rows() != 0)
	{
		$currency_check = $this->Common_model->getTableData('list',array('currency'=>$result_check->row()->currency_code));
		
		if($currency_check->num_rows() != 0)
		{
			//Notification message
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("This currency used by some list's. So, you're not able to delete this currency.")));
			redirect_admin('currency');
		}
	}
	
	$condition1 = array('currency_converter.id'=>$id);
	$this->db->where($condition)->delete('currency');
	$this->db->where($condition1)->delete('currency_converter');
			
		//Notification message
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Currency deleted successfully')));
		redirect_admin('currency');
	}
	//Function end
	
	
	
	/**
	 * Loads Manage Static helps View.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	public function editcurrency()
	{	
		//Get id of the category	
	 $id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('editcurrency'))
		{
			
			$check_data = $this->db->where('id',$id)->get('currency');
			
			if($check_data->num_rows() == 0)
			{
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This Currency already deleted.')));
				redirect_admin('currency');
			}
				
          	$this->form_validation->set_rules('currency_name','Currency Name','required|trim|xss_clean');
			$this->form_validation->set_rules('currency_code','Currency Code','required|trim|xss_clean|callback_currency_code');//|callback_helpUrlCheck|callback_helpUrlValid');
			$this->form_validation->set_rules('currency_symbol','Currency Symbol','required|trim|callback_currency_symbol');
			$this->form_validation->set_rules('currency_value','Currency Value','required|numeric|greater_than[0]|trim');
			$this->form_validation->set_rules('default','Currency Default','required|trim|callback_currency_default');
			
			if($this->form_validation->run())
			{
				
				  //prepare insert data
				  $insertData                  	  	= array();
				  $insertData['currency_name'] 		     = $this->input->post('currency_name');
				  $insertData['currency_code']  	   = strtoupper($this->input->post('currency_code'));
				  $insertData1['currency_code']  	   = strtoupper($this->input->post('currency_code'));
				  $insertData['currency_symbol']  		   = $this->input->post('currency_symbol');
				  $insertData1['currency_value'] = $this->input->post('currency_value');
				  $insertData['status']  		     = $this->input->post('status');
				   $insertData['default']		        	= $this->input->post('default');
				
		
				  //Add Group
				  $currency_code = $check_data->row()->currency_code;
				  
				  $this->db->where('currency_code',$currency_code)->update('currency_converter',$insertData1);
				  
				  $this->db->where('currency_code',$currency_code)->update('currency',$insertData);
									  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Currency updated successfully')));
				  redirect_admin('currency');
				  }
		} //If - Form Submission End
		
		$check_data = $this->db->where('id',$id)->get('currency')->row()->currency_code;
		
		$data['currency']=	$this->db->where('currency.currency_code',$check_data)->join('currency_converter','currency.currency_code=currency_converter.currency_code')->get('currency');
		
			if($data['currency']->num_rows() == 0)
			{
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This Currency already deleted.')));
				redirect_admin('currency');
			}
		
			//Load View	
	 $data['message_element'] = "administrator/currency/editcurrency";
		$this->load->view('administrator/admin_template', $data);
   
	}//End of edithelp
	
	public function currency_symbol($input)
	{
		$string = $input[0];
	if(is_numeric($string)) 
	{
	$this->form_validation->set_message('currency_symbol', 'Please give valid Currency Symbol.');
			return FALSE;
    }
	else {
		 return TRUE;
	}	
	}
	
	public function currency_default($input)
	{
	if($input == 1) 
	{
		$check = $this->db->where('default',1)->where('id !=',$this->uri->segment(4))->where('status',1)->get('currency');
		
		if($check->num_rows() != 0)
		{
			$this->form_validation->set_message('currency_default', "Default currency's already set. Please change that default currency.");
			return FALSE;
		}
		else
		{
		 	return TRUE;
		}
    }
	else {
		 return TRUE;
	}	
	}
	
	
}
//End  help Class

/* End of file help.php */ 
/* Location: ./app/controllers/admin/help.php */