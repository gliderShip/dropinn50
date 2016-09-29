<?php
/**
 * DROPinn Admin Contact Controller Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package        DROPinn
 * @subpackage    Controllers
 * @category    Admin Contact
 * @author        Cogzidel Product Team
 * @version        Version 1.4
 * @link        http://www.cogzidel.com
 
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupon extends CI_Controller
{

    public function Coupon()
    {
            parent::__construct();
        
        $this->load->library('Table');
        $this->load->library('Pagination');
        $this->load->library('DX_Auth');
        $this->load->library('form_validation');
        
        $this->load->helper('form');
        $this->load->helper('url');
        
        $this->load->model('Common_model');
        $this->load->model('Coupon_model');
        $this->load->model('Users_model');
        $this->dx_auth->check_uri_permissions();
    }
    
public function add_coupon() // Add coupon
{

 	//print_r($_POST);exit;
	$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
	if($this->input->post('submit'))
		{
			//Set rules
			$this->form_validation->set_rules('expirein','Expire In','required|trim|xss_clean');
			$this->form_validation->set_rules('coupon_price','Coupon Price','required|numeric|trim|xss_clean|callback_price_check');
			$this->form_validation->set_rules('gencode','Coupon Code','required|trim|xss_clean|callback_code_check');
			if($this->form_validation->run())
			{

				$coupon_expire = strtotime(str_replace('/', '-', $this->input->post('expirein'))); 
				
				$coupon_price = $this->input->post('coupon_price');
	  			$currency = $this->input->post('currency');
	  		    $code 		= $this->input->post('gencode');
				$coupon_price = $this->input->post('coupon_price');
	  			$currency = $this->input->post('currency');
				
	  			$data = array(
		  					 'expirein'   => $coupon_expire,
		  					 'coupon_price' => $coupon_price,
							 'couponcode' => $code,
							 'status' => 0, // 0 -> Active Status & 1-> Expired
							 'currency' => $currency 
							);
if($coupon_price > 0)
{
$this->Common_model->insertData('coupon',$data);
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Code Generated successfully!')));
}
else{
	    		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please give the valid amount.')));
}				
				redirect_admin('coupon/view_all_coupon');
			}
		} 
		$data['currencies'] = $this->Common_model->getTableData('currency',array('status'=>1));
	    $data['message_element'] = "administrator/coupon/viewcoupon";
		$this->load->view('administrator/admin_template', $data);	  
    } // Add coupon

   public function price_check($str)
	{
		if ($str < round(get_currency_value_lys('USD',$this->input->post('currency'),10)))
		{
			$this->form_validation->set_message('price_check', 'Coupon Price should be above or equal to '.$this->input->post('currency').' '.round(get_currency_value_lys('USD',$this->input->post('currency'),10)).'.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	} 
    
public function view_all_coupon()     
{
    $this->load->model('coupon_model');
    $data['coupon']    =    $this->coupon_model->get_coupon();
    $datare  =    $this->coupon_model->get_coupon();
	$no=$this->db->affected_rows();
	if(0<$no)
	{
		foreach($datare->result() as $coup)
		{
			
	         $date=date("F j, Y");
			 $timestamp1=$coup->expirein;
			  $checkoutd = date("F j, Y",$timestamp1);
			   if($checkoutd < $date )
			  {
			  	 $cid= $coup->id;
				$data1['status']= 1;
 		     	$this->db->where('id',$cid);
				$this->db->update('coupon',$data1);
			  }
		}
}
	
    //$data['row']    = $this->db->get_where('coupon', array('id' => '1'))->row();  
    $data['message_element'] = "administrator/coupon/view_coupon_table";
    $this->load->view('administrator/admin_template', $data);
}  // View All coupon
	   public function code_check($strd)
	{
		    $this->load->model('coupon_model');
		
		    $data=$strd;
      $data=  $this->coupon_model->same_coupon($data);
	

		if ($data==1)
		{
				$this->form_validation->set_message('code_check', 'Coupon code allready Exit ');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	} 


public function edit_coupon()
{
 $this->load->model('coupon_model');
 //Get id of the category    
 //Intialize values for library and helpers    
 $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
 if($this->input->post('submit'))
 {
 	$check_data = $this->db->where('id',$this->uri->segment(4))->get('coupon');
			if($check_data->num_rows() == 0)
			{
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This coupon already deleted.')));
				redirect_admin('coupon/view_all_coupon');
			}
		
      //Set rules
    $this->form_validation->set_rules('expirein','Expire In','required|trim|xss_clean');
    $this->form_validation->set_rules('coupon_price','Coupon Price','required|numeric|trim|xss_clean|callback_price_check');
    if($this->form_validation->run())
    {    
      //prepare update data
        $updateData = array();
        $updateData['expirein']     = strtotime(str_replace('/', '-', $this->input->post('expirein')));
        $updateData['coupon_price'] = round($this->input->post('coupon_price'));
        $updateData['status'] = 0; // 0 -> Active Status & 1-> Expired
        $updateData['currency'] = $this->input->post('currency');
        
       //Edit Faq Category
      $updateKey = array('coupon.id'=>$this->uri->segment(4));
$id=$this->uri->segment(4);
if($updateData['coupon_price'] > 0 && $updateData['coupon_price'] < 60001)
{
      $this->coupon_model->updatecoupon($updateKey,$updateData);
	       
	  $this->db->where('used_coupon_code',$this->input->post('coupon_code'))->update('coupon_users',array('status'=>1));
	  	  
      //Notification message
      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Coupon updated successfully')));
}
	else if($updateData['coupon_price'] > 60000){
		                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Your price is too long. The maximum is $60000.')));
		 redirect_admin('coupon/edit_coupon/'.$id);
	}
else
{
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please give the valid amount.')));
 redirect_admin('coupon/edit_coupon/'.$id);
}
      redirect_admin('coupon/view_all_coupon');
     }
 } 
		$check_status = $this->db->where('id',$this->uri->segment(4))->get('coupon')->row()->status;
			    if($check_status == 1)
				{
					$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin("Sorry! you're not able to edit expired coupon.")));
				    redirect_admin('coupon/view_all_coupon');
				}
				
 //If - Form Submission End
    //Set Condition To Fetch The Faq Category
    $condition = array('coupon.id'=>$this->uri->segment(4));
   //Get Groups
    $data['coupon']    =    $this->coupon_model->get_coupon($condition);
    
    if($data['coupon']->num_rows()==0)
            {
				$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('This coupon already deleted.')));
				redirect_admin('coupon/view_all_coupon');
            }
    $data['currencies'] = $this->Common_model->getTableData('currency',array('status'=>1));
    //Load View    
    $data['message_element'] = "administrator/coupon/edit_coupon";
    $this->load->view('administrator/admin_template', $data);
} // edit_coupon

public function delete_coupon()
{
    $this->load->model('coupon_model');
    $id = $this->uri->segment(4,0);
    if($id == 0)    
    {
        $couponlist  =   $this->input->post('couponlist');
        if(!empty($couponlist))
        {    
                foreach($couponlist as $res)
                 {
                    $condition = array('coupon.id'=>$res);
                    $this->coupon_model->deletecoupon(NULL,$condition);
                 }
            }
        else
        {
        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',translate_admin('Please select coupon list')));
     redirect_admin('coupon/view_all_coupon');
        }
    }
    else
    {
    $condition = array('coupon.id'=>$id);
    $this->coupon_model->deletecoupon(NULL,$condition);
    }        
        //Notification message
        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',translate_admin('Coupon deleted successfully')));
        redirect_admin('coupon/view_all_coupon');
} // delete_coupon



 }    // Class           
?>
