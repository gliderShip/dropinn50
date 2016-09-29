<?php
class Referrals extends CI_Controller {

	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/
	public function Referrals()
	{
	 parent::__construct();

		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
			$this->load->library('Table');
		$this->load->library('Pagination');
		$this->load->library('Twoco_Lib');
		
		$this->load->helper('form');
		$this->load->helper('url');
		
			//load validation library
		$this->load->library('form_validation');

		
		$this->load->model('Users_model');	
		$this->load->model('Trips_model');
		$this->load->model('Email_model');
		$this->load->model('Message_model');
	
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->helper('file');
		
		$this->load->library('Form_validation');
		$this->load->library('image_lib');

		$this->load->model('Users_model'); 
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->load->model('Trips_model');
		$this->load->model('Rooms_model');
		$this->facebook_lib->enable_debug(TRUE);
		$this->path = realpath(APPPATH . '../images');
		$this->logo = realpath(APPPATH . '../logo');
		$this->font = realpath(APPPATH . '../core');
		//load model
		$this->load->model('page_model');		
$this->dx_auth->check_uri_permissions();
$this->path=realpath(APPPATH);

	}//Controller End 
	public function index(){
		$refer1=$this->db->query("select * from `referral_management` where `id`=1 ");
		$refer=$refer1->row();//('referral_management')->where('id',1);
		$data['result']=$refer1->row();		
		$data['currency'] = $this->Common_model->getTableData('currency',array('status'=>1));
		$data['famount'] = $refer->fixed_amt;
		$data['first'] = $refer->type;
		$data['fixed_status'] = $refer->fixed_status;
		$data['message_element'] = "administrator/referrals";
		$this->load->view('administrator/admin_template', $data);
		
	}
public function update(){
	 $data['fixed_status']=$this->input->post('is_fixed');
	 $data['fixed_amt']=$this->input->post('total');
	 $data['currency']=$this->input->post('currency');
	 $data['type']=$this->input->post('type');
	 $data['trip_amt']=$this->input->post('tripf');
	 $data['trip_per']=$this->input->post('tripp');
	 $data['rent_amt']=$this->input->post('rentf');
	 $data['rent_per']=$this->input->post('rentp');
	 $this->db->where('id', 1);
	 $this->db->update('referral_management',$data);
	 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Updated successfully!')));
	 redirect('administrator/referrals/index');
	 
	}
	
}
?>