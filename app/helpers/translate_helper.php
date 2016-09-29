<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Translate 
 *
 * $s - default source language ( English )
 * $d - Destination language ( French , Spanish ... )
 * Converted the string using google translate API.
 * @return	string
 */

		if ( ! function_exists('translate'))
		{
		function translate($text, $d = '')
		{
		$ci =& get_instance();
		$query  = $ci->db->get_where('settings', array('code' => 'FRONTEND_LANGUAGE'));
		
		$d =		$ci->session->userdata('locale');
		$s = 'en';
		
		
		if($query->row()->int_value == 2)
		{
			if($d=='') { $d = $query->row()->string_value; }
			
			$ci->lang->load('common', $d);
			$tranlate = $ci->lang->line($text);
			
			if($tranlate == '')
			return $text;
			else
			return $tranlate;
			
		}	
		else
		{ 
			if($d=='') { $d = $query->row()->string_value; }
			
			$ci->lang->load('common', $d);
			$tranlate = $ci->lang->line($text);
			
			if($tranlate == '')
			return $text;
			else
			return $tranlate;
		}
		}
		
		}	
		
		
		
		if ( ! function_exists('translate_admin'))
		{
		function translate_admin($text, $d = '')
		{
		$ci     =& get_instance();
		$query  = $ci->db->get_where('settings', array('code' => 'BACKEND_LANGUAGE'));
		if($query->row()->int_value == 2)
		{
			$ci->lang->load('common_admin', $query->row()->string_value);
			$tranlate = $ci->lang->line($text);
			
			if($tranlate == '')
			return $text;
			else
			return $tranlate;
			}	
		else
		{ 
			$ci->lang->load('common_admin', $query->row()->string_value);
			$tranlate = $ci->lang->line($text);
			
			if($tranlate == '')
			return $text;
			else
			return $tranlate;
		}
		}
		
		}	


		function get_currency_value($amount)
		{
			
			$rate=0;
			
			//echo $amount;
			
			$ci =& get_instance();
			
			$ci->load->helper("cookie");
			
			//var_dump($ci->session->userdata("default_currency_cookie"));
			
			$currency_code = $ci->session->userdata("locale_currency");
			
			$current      = $ci->Common_model->getTableData('currency', array('currency_code'=>$currency_code,'status'=>1));
			
			if($current->num_rows() != 0)
			{
				$currency_code = $current->row()->currency_code;
			}
			else
				{
					$current      = $ci->Common_model->getTableData('currency', array('default'=>1));
				
					if($current->num_rows() != 0)
					{
					$currency_code = $current->row()->currency_code;
					}
					else
					{
					$current = $ci->db->where('status',1)->get('currency');
					
					if($current->num_rows() !=0)
					{
						$currency_code = $current->row()->currency_code;
					}
					else {
						$currency_code = $ci->db->where('id',1)->get('currency')->row()->currency_code;
					}
					}
				}

			if($currency_code == '')
			{
				$current      = $ci->Common_model->getTableData('currency', array('currency_code'=>$currency_code,'default'=>1));
				
				if($current->num_rows() != 0)
				{
					$currency_code = $current->row()->currency_code;
				}
				else
				{
					$current = $ci->db->where('status',1)->get('currency');
					
					if($current->num_rows() !=0)
					{
						$currency_code = $current->row()->currency_code;
					}
					else {
						$currency_code = $ci->db->where('id',1)->get('currency')->row()->currency_code;
					}
				}
			}
			
			$params  = array('amount' => $amount, 'currFrom' => 'USD','currInto' => $currency_code);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
			
			//var_dump($rate);
			
			if($rate!=0)
				return $rate;
			else
				return $amount;
			
			//$googleCurrencyConvertor = new GoogleCurrencyConvertor("1","INR","USD");
		}
		
		function get_currency_value1($id,$amount)
		{
			
			//print_r($id);
			//print_r($amount);
			$rate=0;
			
			//echo $amount;
			
			$ci =& get_instance();
			
			$ci->load->helper("cookie");
			
			//var_dump($ci->session->userdata("default_currency_cookie"));
			
			//$current = $ci->session->userdata("locale_currency");
			//print_r($current);
			$list_currency     = $ci->db->where('id',$id)->get('list')->row()->currency;
			//print_r($list_currency);
			$current = $ci->session->userdata("locale_currency");
			
			if($current != '')
			{
				$result_current = $ci->db->where('currency_code',$current)->where('status',1)->get('currency');
			}
			
			if($current == '')
			{
				$list_currency1 = $ci->db->where('default',1)->where('status',1)->get('currency');
				//print_r($list_currency1);
				
				if($list_currency1->num_rows() != 0)
			{
				$list_currency1 = $list_currency1->row()->currency_code;
			}
			else
				{
					$list_currency1 = $ci->db->where('status',1)->get('currency');
					
					if($list_currency1->num_rows() !=0)
					{
						$list_currency1 = $list_currency1->row()->currency_code;
						
					}
					else {
						$list_currency1 = $ci->db->where('id',1)->get('currency')->row()->currency_code;
					}
				}
				
				$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $list_currency1);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
			
				
			//$current = 'USD';
			if($rate!=0)
				return $rate;
			else
				return 0;
			
			}
			elseif($result_current->num_rows() == 0) {
				
				$list_currency1 = $ci->db->where('default',1)->get('currency');
				
				if($list_currency1->num_rows() != 0)
			{
				$list_currency1 = $list_currency1->row()->currency_code;
			}
			else
				{
					$list_currency1 = $ci->db->where('status',1)->get('currency');
					
					if($list_currency1->num_rows() !=0)
					{
						$list_currency1 = $list_currency1->row()->currency_code;
					}
					else {
						$list_currency1 = $ci->db->where('id',1)->get('currency')->row()->currency_code;
					}
				}
				
				$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $list_currency1);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
				
			//$current = 'USD';
			if($rate!=0)
				return $rate;
			else
				return 0;
			}
			
			//echo "$amount";
			$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $current);
		    //echo $params;
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			//print_r($params);
			$rate=round(google_convert($params),2);
			
			//echo $rate;
			//var_dump($rate);
			
			if($rate!=0)
				return $rate;
			else
				return 0;
			  //return true;
			//$googleCurrencyConvertor = new GoogleCurrencyConvertor("1","INR","USD");
		}
		function get_currency_value_lys($from,$to,$amount)
		{
				
			$params  = array('amount' => $amount, 'currFrom' => $from, 'currInto' => $to);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
		   if($rate!=0)
				return $rate;
			else
				return 0;
			
		}
		function get_currency_value_cal($from,$to,$amount)
		{
			$params  = array('amount' => $amount, 'currFrom' => $from, 'currInto' => $to);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
			
			//var_dump($rate);
			
			if($rate!=0)
				return $rate;
			else
				return 0;
			
		}
function get_currency_value_lys1($from,$to,$amount)
		{
			$params  = array('amount' => $amount, 'currFrom' => $from, 'currInto' => $to);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
			
			//var_dump($rate);
			
			if($rate!=0)
				return $rate;
			else
				return $amount;
			
		}
		function get_currency_value2($fromcurrency,$tocurrency,$amount)
		{
			$rate=0;
			
			//echo $amount;
			
			$ci =& get_instance();
			
			$ci->load->helper("cookie");
			
			//var_dump($ci->session->userdata("default_currency_cookie"));
			
			//$current = $ci->session->userdata("locale_currency");
			
			//$list_currency     = $currency;
			
			/*if($current == '')
			{
				$list_currency1 = $ci->db->where('default',1)->get('currency')->row()->currency_code;
				
				$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $list_currency1);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params));
				
			//$current = 'USD';
			if($rate!=0)
				return $rate;
			else
				return $amount;
			
			}*/
			
			$params  = array('amount' => $amount, 'currFrom' => $fromcurrency, 'currInto' => $tocurrency);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
			
			//var_dump($rate);
			
			if($rate!=0)
				return $rate;
			else
				return $amount;
			
			
			//$googleCurrencyConvertor = new GoogleCurrencyConvertor("1","INR","USD");
		}

		function get_currency_symbol1()
		{
	 	$ci =& get_instance();
			$currency_code     = $ci->session->userdata('locale_currency');
			
			$new_currency      = $ci->Common_model->getTableData('currency', array('default' => 1,'status'=>1));
			
			if($new_currency->num_rows() == 0)
			{
				$new_currency = $ci->Common_model->getTableData('currency', array('status'=>1));
				if($new_currency->num_rows() == 0)
				{
					$new_currency = $ci->Common_model->getTableData('currency', array('id'=>1))->row();
				}
				else
					{
						$new_currency = $ci->Common_model->getTableData('currency', array('status'=>1))->row();
					}
			}
			else {
				$new_currency      = $ci->Common_model->getTableData('currency', array('default' => 1,'status'=>1))->row();
			}
			if($currency_code == '')
			{
					$currency_symbol = $new_currency->currency_symbol;
					return			$currency_symbol;
			}
			else
			{
                $currency_symbol   = $ci->Common_model->getTableData('currency', array('currency_code' => $currency_code,'status'=>1));
			if($currency_symbol->num_rows()!=0)
			{
				$currency_symbol = $currency_symbol->row()->currency_symbol;
			}
			else {
				$currency_symbol = $ci->Common_model->getTableData('currency', array('default'=>1)); 
				if($currency_symbol->num_rows() == 0)
				{
					$new_currency = $ci->Common_model->getTableData('currency', array('status'=>1));
					if($new_currency->num_rows() != 0)
					{
						$currency_symbol = $new_currency->row()->currency_symbol;
					}
					else
					$currency_symbol = $ci->Common_model->getTableData('currency', array('id'=>1))->row()->currency_symbol;
				}
				else
					{
						$currency_symbol = $currency_symbol->row()->currency_symbol;
					}
			}
				return		$currency_symbol;
			}		
		}
		
		function get_currency_symbol($id)
		{
			
	 	$ci =& get_instance();
			$currency_code     = $ci->session->userdata('locale_currency');
			
			$list_currency     = $ci->db->where("id",$id)->get("list")->row()->currency;
		
			$new_currency      = $ci->Common_model->getTableData('currency', array('currency_code' => $list_currency,'default' => 1,'status'=>1));
			
			if($new_currency->num_rows() == 0)
			{
				$new_currency = $ci->Common_model->getTableData('currency', array('currency_code' => 'USD'))->row();
			}
			else {
				$new_currency      = $ci->Common_model->getTableData('currency', array('default' => 1,'status'=>1))->row();
			}
			
			if($currency_code == '')
			{
				$list_currency = $ci->db->where('default',1)->where('status',1)->get('currency');
				
				if($list_currency->num_rows() == 0)
				{
					$list_currency = $ci->db->where('status',1)->get('currency');
					
					if($list_currency->num_rows() == 0)
					{
						$list_currency = $ci->db->where('id',1)->get('currency')->row()->currency_code;
					}
					else {
						$list_currency = $list_currency->row()->currency_code;
					}
				}
				else
					{
						$list_currency = $list_currency->row()->currency_code;
					}
								
				$currency_symbol   = $ci->Common_model->getTableData('currency', array('currency_code' => $list_currency,'status'=>1));
			if($currency_symbol->num_rows()!=0)
			{
				$currency_symbol = $currency_symbol->row()->currency_symbol;
			}
			else {
				$currency_symbol = $ci->Common_model->getTableData('currency', array('default'=>1)); 
				if($currency_symbol->num_rows() == 0)
				{
					$currency_symbol = $ci->Common_model->getTableData('currency', array('id'=>1))->row()->currency_symbol;
				}
				else
					{
						$currency_symbol = $ci->Common_model->getTableData('currency', array('status'=>1))->row()->currency_symbol;
					}
			}

					return			$currency_symbol;
			}
			else
			{			
			$currency_symbol   = $ci->Common_model->getTableData('currency', array('currency_code' => $currency_code,'status'=>1));
			if($currency_symbol->num_rows()!=0)
			{
				$currency_symbol = $currency_symbol->row()->currency_symbol;
			}
			else {
				$currency_symbol = $ci->Common_model->getTableData('currency', array('default'=>1)); 
				if($currency_symbol->num_rows() == 0)
				{
					$currency_symbol = $ci->Common_model->getTableData('currency', array('status'=>1));
						if($currency_symbol->num_rows() == 0)
						{
						$currency_symbol = $ci->Common_model->getTableData('currency', array('id'=>1))->row()->currency_symbol;
						}
						else
							{
								$currency_symbol = $currency_symbol->row()->currency_symbol;
							}
				}
				else
					{
						$currency_symbol = $currency_symbol->row()->currency_symbol;
					}
			}
			
				return	$currency_symbol;
			}		
		}
		
		
		function get_currency_code()
		{
	 	$ci =& get_instance();
			
			$currency_code     = $ci->session->userdata('locale_currency');
			$new_currency      = $ci->Common_model->getTableData('currency', array('default' => 1,'status'=>1));
			
			if($new_currency->num_rows() == 0)
			{
				$new_currency = $ci->Common_model->getTableData('currency', array('status' => 1))->row();
			}
			else {
				$new_currency      = $ci->Common_model->getTableData('currency', array('default' => 1,'status'=>1))->row();
			}
			
			if($currency_code == '')
			{
			  $currency_code   = $new_currency->currency_code;
			}
			else
			{
			
			$current      = $ci->Common_model->getTableData('currency', array('currency_code'=>$currency_code,'status'=>1));
			if($current->num_rows() != 0)
			{
				$currency_code = $current->row()->currency_code;
			}
			else
				{
					$currency_code = $ci->Common_model->getTableData('currency', array('default'=>1)); 
				if($currency_code->num_rows() == 0)
				{
					$currency_code = $ci->Common_model->getTableData('currency', array('id'=>1))->row()->$currency_code;
				}
				else
					{
						$currency_code = $currency_code->row()->currency_code;
					}
				}
				
			}
				return	$currency_code;		
		}
		
		function get_currency_value_coupon($amount,$currency)
		{
			$rate	= 0;
			$currency_from	= $currency;
			
			//echo $amount;
			
			$ci =& get_instance();
			
			$ci->load->helper("cookie");
			
			//var_dump($ci->session->userdata("default_currency_cookie"));
			$currency_to 	 	= $ci->session->userdata("locale_currency");
			if($currency_to == '')
			{
				$ci->load->model('Common_model');	
				$currency_to	= $ci->Common_model->getTableData('currency',array('default' => '1'))->row()->currency_code; 	
			}
			
			$params  = array('amount' => $amount, 'currFrom' => $currency_from, 'currInto' => $currency_to);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
			
			
			//var_dump($rate);
			
			if($rate!=0)
				return $rate;
			else
				return $amount;
			
			//$googleCurrencyConvertor = new GoogleCurrencyConvertor("1","INR","USD");
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
		
		
	/*	$gurl="http://www.google.com/finance/converter?a=$amount&from=$currFrom&to=$currInto";
		
		$html=getHtmlCodeViaFopen($gurl);
		
		//var_dump($gurl);
		
		$pattern='/<span class=b(.*)\<\/span\>/Uis';
		
		preg_match_all($pattern,$html,$return,PREG_PATTERN_ORDER);
				
				//var_dump($return[0][0]);
				
		if (isset($return[0][0])) {
			$rate=$return[0][0];
			
			//var_dump($rate);
			
			$tmp=str_replace(" ".$currInto,"",$rate);
			
			$tmp=str_replace("<span class=bld>","",$tmp);
			
			$rate=$tmp;
							
			//var_dump(round($rate));
							
			//Patch Ends
			
			//$reverseRate=1/$rate;
		}
		else {
			$rate="Google could not convert your request. Please try again.";
		}
				*/	
		//echo $rate;
		
		$ci =& get_instance();
		
		if($currFrom == 'USD')
		{
			$currInto_result = $ci->db->where('currency_code',$currInto)->get('currency_converter')->row();
			$rate = $amount * $currInto_result->currency_value;
		}
		else 	
		{
			
		$currFrom_result = $ci->db->where('currency_code',$currFrom)->get('currency_converter')->row();
	 
		// print_r($currFrom_result);exit;
		if(empty($currFrom_result))
        {
		// print_r('fsg');
        $rate=0;
        }
        else {
        $from_usd = 1/$currFrom_result->currency_value; 
        $from_usd_amt = $amount * $from_usd;
        $currInto_result = $ci->db->where('currency_code',$currInto)->get('currency_converter')->row();
        $rate = $currInto_result->currency_value * $from_usd_amt;
        
        }
       
		
		}
		
		return $rate;
	}

function get_currency_value_coupon1($id,$amount)
		{
			$rate=0;
			
			//echo $amount;
			
			$ci =& get_instance();
			
			$ci->load->helper("cookie");
			
			//var_dump($ci->session->userdata("default_currency_cookie"));
			
			$current = $ci->session->userdata("locale_currency");
			
			$list_currency     = $ci->db->where('id',$id)->get('list')->row()->currency;
			
			if($current == '')
			{
				$list_currency1 = $ci->db->where('default',1)->get('currency')->row()->currency_code;
				
				$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $list_currency1);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
			
			//$current = 'USD';
			if($rate!=0)
				return $rate;
			else
				return 0;
			
			}
			
			$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $current);
			
			//$ci->load->library('GoogleCurrencyConvertor',$params);
			
			$rate=round(google_convert($params),2);
			
			//var_dump($rate);
			
			if($rate!=0)
				return $rate;
			else
				return 0;
			
			//$googleCurrencyConvertor = new GoogleCurrencyConvertor("1","INR","USD");
		}

function getHtmlCodeViaFopen($url){
		$ci =& get_instance();
		$returnStr="";
		
		$default_currency_code=$ci->config->item('default_currency_code');
		
		if($default_currency_code!="USD")
		{
			$fp=fopen($url, "r") or die("ERROR: Failed to open $url for reading via this script.");
			while (!feof($fp)) {
				$returnStr.=fgetc($fp);
			}
			fclose($fp);
			return $returnStr;
		}
}

// ------------------------------------------------------------------------
/* End of file translate_helper.php */
/* Location: ./system/helpers/translate_helper.php */
