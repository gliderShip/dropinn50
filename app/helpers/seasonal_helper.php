<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getDailyPrice($list_id,$date,$list_price)
{
		$CI     =& get_instance();
		$special=0;
		$CI->load->model('Common_model');	
		//edit calender
			$list_currency=$CI->db->where('id',$list_id)->get('list')->row()->currency;
		    //print_r($list_currency);
		//edit calender
		
		$conditions=array('list_id' => $list_id);
		$seasonal_query=$CI->Common_model->getTableData('seasonalprice',$conditions);
		//echo "<pre>";
		//print_r($seasonal_query);
	    if($seasonal_query -> num_rows() > 0)
		 {
				    
					$seasonal_result=$seasonal_query->result_array();
					
						foreach($seasonal_result as $res)
						{
								
							if($date == $res['start_date'] || $date == $res['end_date'] || ($date > $res['start_date'] && $date < $res['end_date']))
							{
									$price=$res['price'];
								  //edit calender
								  //	$list_currency = $CI->Common_model->getTableData('list',array('id'=>$list_id))->row()->currency;
									
									//print_r($res['currency']);
							        $price = $price;
								   //print_r($price);n getDailyPrice
									
									
								//	$price = get_currency_value_cal(get_currency_code(),$list_currency,$price);
								//end edit calender
									$special=1;
							}
					   }
		
                		
			   if($special == 0)
				
				//start edit calender
				
			   //$price=get_currency_value_lys($list_currency,get_currency_code(),$list_price);
				//print_r($date);
				  //print_r($list_price);
				  $price=$list_price;
			    //	$price=get_currency_value1($list_id,$list_price);
				//end edit calender

	         }
	        else 
	        {
					//start edit calender
					$price=$list_price;	
				
				    //$price=get_currency_value_lys($list_currency,get_currency_code(),$list_price);
				    //end edit calender
	          }
	          
	 
		return $price;
}

?>