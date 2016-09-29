<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


function refund($param)
 {
 	$ci =& get_instance();
	
 	    $conditions              = array("reservation.id" => $param);
		$data['result']          = $row = $ci->Trips_model->get_reservation($conditions)->row();
		
		$query                   = $ci->Users_model->get_user_by_id($row->userby);
		$data['booker_name']     = $query->row()->username;
		
		$query1                  = $ci->Users_model->get_user_by_id($row->userto);
	  	$data['hotelier_name']   = $query1->row()->username;
	  
	 	$data['currency_code'] = $data['result']->currency;
		
		$cancellation_policy = $ci->Common_model->getTableData('cancellation_policy',array('id'=>$row->policy))->row();
		
		if($row->status == 6)
		{
		if(round($row->cleaning+$row->security+$row->extra_guest_price+before_checkin_guest($param),2) != 0)
		$refund_data['guest_topay'] = round($row->cleaning+$row->security+$row->extra_guest_price+before_checkin_guest($param),2);
				
		if(round(before_checkin_host($param),2) != 0)
		{
		if($row->host_penalty == 0)
		{
		$host_penalty = $ci->Common_model->getTableData('host_cancellation_penalty',array('user_id'=>$row->userto));
		
		if($host_penalty->num_rows() != 0)
		{ 
		if(round(before_checkin_host($param),2) > get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount))
		{
		$refund_data['host_penalty'] = get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount);
		$refund_data['host_topay'] = round(before_checkin_host($param),2) - get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount);	
		$penalty_data['update_penalty'] = 0; 
		}
		else
		{
		$refund_data['host_penalty'] = get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],round(before_checkin_host($param),2));
		$refund_data['host_topay'] = 0;
		$penalty_data['update_penalty'] = $host_penalty->row()->amount - get_currency_value_lys($data['currency_code'],$host_penalty->row()->currency,round(before_checkin_host($param),2)); 		
		$ci->Common_model->updateTableData('reservation',0,array('id'=>$data['result']->id),array('host_penalty'=>$refund_data['host_penalty']));
		//$ci->Common_model->updateTableData('host_cancellation_penalty',0,array('id'=>$host_penalty->row()->id),array('amount'=>$penalty_data['update_penalty']));
		}
		$ci->Common_model->updateTableData('host_cancellation_penalty',0,array('id'=>$host_penalty->row()->id),array('amount'=>$penalty_data['update_penalty']));
		}
		else
		{
		$refund_data['host_topay'] = round(before_checkin_host($param),2);
		}
		}
		else
		{
		$refund_data['host_topay'] = round(before_checkin_host($param),2) - $data['result']->host_penalty;
		}
		}
		else
		{
		//$data['host_topay'] = round($ci->before_checkin_host($param),2);
		}
		}
		
		if($row->status == 12)
		{
			$additional_fees_guest = 0;
			$additional_fees_host = 0;
			
			if($cancellation_policy->cleaning_status == 1)
			{
			$additional_fees_guest += $row->cleaning;
			}
			else
			{
			$additional_fees_host += $row->cleaning;
			}
			
			if($cancellation_policy->security_status == 1)
			{
			$additional_fees_guest += $row->security;
			}
			else
			{
			$additional_fees_host += $row->cleaning;
			}
			
			if($cancellation_policy->additional_status == 1)
			{
			$additional_fees_guest += $row->extra_guest_price;
			}
			else
			{
			$additional_fees_host += $row->cleaning;
			}

		if(round($additional_fees_guest+after_checkin_guest($param),2) != 0)
		$refund_data['guest_topay'] = round($additional_fees_guest+after_checkin_guest($param),2);
		
		if(round($additional_fees_host+after_checkin_host($param),2) != 0)
		{
		if($row->host_penalty == 0)
		{
		$host_penalty = $ci->Common_model->getTableData('host_cancellation_penalty',array('user_id'=>$row->userto));
		if($host_penalty->num_rows() != 0)
		{
		if(round($additional_fees_host+after_checkin_host($param),2) > get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount))
		{
		$refund_data['host_penalty'] = get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount);
		$refund_data['host_topay'] = round($additional_fees_host+after_checkin_host($param),2) - get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount);	
		$penalty_data['update_penalty'] = 0; 
		}
		else
		{
		$refund_data['host_penalty'] = round($additional_fees_host+after_checkin_host($param),2);
		$refund_data['host_topay'] = 0;
		$refund_data['update_penalty'] = $host_penalty->row()->amount - get_currency_value_lys($data['currency_code'],$host_penalty->row()->currency,round($additional_fees_host+after_checkin_host($param),2)); 		
		$ci->Common_model->updateTableData('reservation',0,array('id'=>$data['result']->id),array('host_penalty'=>$refund_data['host_penalty']));
		//$ci->Common_model->updateTableData('host_cancellation_penalty',0,array('id'=>$host_penalty->row()->id),array('amount'=>$penalty_data['update_penalty']));
		}
		$ci->Common_model->updateTableData('host_cancellation_penalty',0,array('id'=>$host_penalty->row()->id),array('amount'=>$penalty_data['update_penalty']));
		}
		else
		{
		$refund_data['host_topay'] = round($additional_fees_host+after_checkin_host($param),2);
		}
		}
		else
		{
		$refund_data['host_topay'] = round($additional_fees_host+after_checkin_host($param),2) - $data['result']->host_penalty;
		}
		}
		}  

		if($row->status == 7 || $row->status == 10 || $row->status == 8 || $row->status == 9)
		{
		if($data['result']->host_penalty == 0)
		{
		$host_penalty = $ci->Common_model->getTableData('host_cancellation_penalty',array('user_id'=>$row->userto));
		if($host_penalty->num_rows() != 0)
		{
		if($data['result']->topay > get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount))
		{
		$refund_data['host_penalty'] = get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount);
		$host_to_pay_penalty_data['host_to_pay_penalty'] = get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount);
		$refund_data['topay'] = $data['result']->topay - get_currency_value_lys($host_penalty->row()->currency,$data['currency_code'],$host_penalty->row()->amount);	
		$penalty_data['update_penalty'] = 0; 
		}
		else
		{
		$host_to_pay_penalty_data['host_to_pay_penalty'] = $data['result']->topay;
		$refund_data['topay'] = 0;
		$penalty_data['update_penalty'] = $host_penalty->row()->amount - get_currency_value_lys($data['currency_code'],$host_penalty->row()->currency,$data['result']->topay); 	
		$refund_data['host_penalty'] = $penalty_data['update_penalty'];
		$ci->Common_model->updateTableData('reservation',0,array('id'=>$data['result']->id),array('host_penalty'=>$host_to_pay_penalty_data['host_to_pay_penalty']));
		//$ci->Common_model->updateTableData('host_cancellation_penalty',0,array('id'=>$host_penalty->row()->id),array('amount'=>$penalty_data['update_penalty']));
		}
		$ci->Common_model->updateTableData('host_cancellation_penalty',0,array('id'=>$host_penalty->row()->id),array('amount'=>$penalty_data['update_penalty']));
		}
		else
		{
		$refund_data['topay'] = $data['result']->topay;
		}
		}
		else
		{
		$refund_data['topay'] = $data['result']->topay - $data['result']->host_penalty;
		}
		}	
		else if($row->status == 13 || $row->status == 4 || $row->status==2)
		{
		//$refund_data['commission_status'] = 0;
		$refund_data['topay'] = $data['result']->price;	
		}
		else
		{
		$refund_data['topay'] = $data['result']->topay;
		}
		
		if($data['result']->coupon != 0)
	 	{	  
	  	$data['coupon_price'] = $ci->Common_model->getTableData('coupon',array('couponcode'=>$data['result']->coupon))->row()->coupon_price;
	  	$data['coupon_currency'] = $ci->Common_model->getTableData('coupon',array('couponcode'=>$data['result']->coupon))->row()->currency;
	 	
	 	if($row->status == 5 || $row->status == 7 || $row->status == 8 || $row->status == 9 || $row->status == 10)
 		{
 		$refund_data['topay'] = $refund_data['topay']+$data['coupon_price'];
 		}
 
		if(isset($refund_data['host_topay']))
 		{
 		$refund_data['host_topay'] = $refund_data['host_topay']+$data['coupon_price'];
 		}
		
		}
				 
		$ci->Common_model->updateTableData('reservation',0,array('id'=>$param),$refund_data);
		
		return json_encode($refund_data);
 }

function before_checkin_guest($param)
	{
		$ci =& get_instance();
		$conditions   = array("reservation.id" => $param);
		$row          = $ci->Trips_model->get_reservation($conditions)->row();
		
		$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
		$date2 = new DateTime(date('Y-m-d H:i:s',$row->cancel_date));
		$interval = $date1->diff($date2);
		
		$cancellation_policy = $ci->Common_model->getTableData('cancellation_policy',array('id'=>$row->policy))->row();
		
		$topay = $row->topay-$row->cleaning-$row->security-$row->extra_guest_price;
		
		if($cancellation_policy->list_days_prior_status == 1)
		{
			
		 if($cancellation_policy->list_days_prior_days < $interval->days)
		{
			return ($cancellation_policy->list_days_prior_percentage/100) * $topay;
		}
		else
			{				
				if($cancellation_policy->list_before_status == 1)
		{
		 if($cancellation_policy->list_before_days < $interval->days)
		{
			return ($cancellation_policy->list_before_percentage/100) * $topay;
		}
		else
			{
				$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
				$date2 = new DateTime(date('Y-m-d H:i:s',$row->checkout));
				$interval = $date1->diff($date2);
				
				$per_day_amount = $topay/$interval->days;
				if($cancellation_policy->list_non_refundable_nights > $interval->days)
				{
					$list_non_refundable_nights = $interval->days;
				}
				else
				{
					$list_non_refundable_nights = $cancellation_policy->list_non_refundable_nights;
				}
				$non_refund_days = $interval->days-$list_non_refundable_nights;
				return ($cancellation_policy->list_before_percentage/100)*($non_refund_days*$per_day_amount);
			}
		}		
		else {
			return 0;
		}
			}
		}

		if($cancellation_policy->list_before_status == 1)
		{
		 if($cancellation_policy->list_before_days < $interval->days)
		{
			return ($cancellation_policy->list_before_percentage/100) * $topay;
		}
		else
			{
				$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
				$date2 = new DateTime(date('Y-m-d H:i:s',$row->checkout));
				$interval = $date1->diff($date2);
				
				$per_day_amount = $topay/$interval->days;
				if($cancellation_policy->list_non_refundable_nights > $interval->days)
				{
					$list_non_refundable_nights = $interval->days;
				}
				else
				{
					$list_non_refundable_nights = $cancellation_policy->list_non_refundable_nights;
				}
				$non_refund_days = $interval->days-$list_non_refundable_nights;
				return ($cancellation_policy->list_before_percentage/100)*($non_refund_days*$per_day_amount);
			}
		}		
		else {
			return 0;
		}		
		
		
	}

function before_checkin_host($param)
	{
		$ci =& get_instance();
		$conditions   = array("reservation.id" => $param);
		$row          = $ci->Trips_model->get_reservation($conditions)->row();
		
		$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
		$date2 = new DateTime(date('Y-m-d H:i:s',$row->cancel_date));
		$interval = $date1->diff($date2);
		
		$cancellation_policy = $ci->Common_model->getTableData('cancellation_policy',array('id'=>$row->policy))->row();
		
		$topay = $row->topay-$row->cleaning-$row->security-$row->extra_guest_price;
		
		if($cancellation_policy->list_days_prior_status == 1)
		{
		 if($cancellation_policy->list_days_prior_days < $interval->days)
		{
			$percentage = 100 - $cancellation_policy->list_days_prior_percentage;
			return ($percentage/100) * $topay;
		}
		else
			{
				/*$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
				$date2 = new DateTime(date('Y-m-d H:i:s',$row->checkout));
				$interval = $date1->diff($date2);
				
				$per_day_amount = $topay/$interval->days;
				
				$percentage = 100 - $cancellation_policy->list_before_percentage;
				return ($percentage/100)*$per_day_amount;*/
				
				if($cancellation_policy->list_before_status == 1)
		{
		 if($cancellation_policy->list_before_days < $interval->days)
		{
			$percentage = 100 - $cancellation_policy->list_before_percentage;
			return ($percentage/100) * $topay;
		}
		else
			{
				$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
				$date2 = new DateTime(date('Y-m-d H:i:s',$row->checkout));
				$interval = $date1->diff($date2);
				
				$per_day_amount = $topay/$interval->days;
				if($cancellation_policy->list_non_refundable_nights > $interval->days)
				{
					$list_non_refundable_nights = $interval->days;
				}
				else
				{
					$list_non_refundable_nights = $cancellation_policy->list_non_refundable_nights;
				}
				$non_refund_days = $interval->days-$list_non_refundable_nights;
				if($non_refund_days <= 0)
				{
				$non_refund_days = $interval->days;
				return (100/100)*($non_refund_days*$per_day_amount);
				}
				$percentage = 100 - $cancellation_policy->list_before_percentage;
				return ($percentage/100)*($non_refund_days*$per_day_amount);
			}	
		}
		else
		{
			$percentage = 100;
			return ($percentage/100) * $topay;	
		}
			}
		}		
				
		if($cancellation_policy->list_before_status == 1)
		{
		 if($cancellation_policy->list_before_days < $interval->days)
		{
			$percentage = 100 - $cancellation_policy->list_before_percentage;
			return ($percentage/100) * $topay;
		}
		else
			{
				$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
				$date2 = new DateTime(date('Y-m-d H:i:s',$row->checkout));
				$interval = $date1->diff($date2);
				
				$per_day_amount = $topay/$interval->days;
				if($cancellation_policy->list_non_refundable_nights > $interval->days)
				{
					$list_non_refundable_nights = $interval->days;
				}
				else
				{
					$list_non_refundable_nights = $cancellation_policy->list_non_refundable_nights;
				}
				$non_refund_days = $interval->days-$list_non_refundable_nights;
				if($non_refund_days <= 0)
				{
				$non_refund_days = $interval->days;
				return (100/100)*($non_refund_days*$per_day_amount);
				}
				$percentage = 100 - $cancellation_policy->list_before_percentage;
				return ($percentage/100)*($non_refund_days*$per_day_amount);
			}	
		}
		else
		{
			$percentage = 100;
			return ($percentage/100) * $topay;	
		}	
	}

	function after_checkin_guest($param)
	{
		$ci =& get_instance();
		$conditions   = array("reservation.id" => $param);
		$row          = $ci->Trips_model->get_reservation($conditions)->row();
		
		$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
		$date2 = new DateTime(date('Y-m-d H:i:s',$row->cancel_date));
		$interval = $date1->diff($date2);
		
		$cancellation_policy = $ci->Common_model->getTableData('cancellation_policy',array('id'=>$row->policy))->row();
		
		$topay = $row->topay-$row->cleaning-$row->security-$row->extra_guest_price;
		
		if($cancellation_policy->list_after_status == 1)
		{
		 if($cancellation_policy->list_after_days >= $interval->days)
		{
			return ($cancellation_policy->list_after_percentage/100) * $topay;
		}
		else
			{
				return 0;
			}	
		}
		else {
			return 0;
		}	
	}
	
	function after_checkin_host($param)
	{
		$ci =& get_instance();
		$conditions   = array("reservation.id" => $param);
		$row          = $ci->Trips_model->get_reservation($conditions)->row();
		
		$date1 = new DateTime(date('Y-m-d H:i:s', $row->checkin));
		$date2 = new DateTime(date('Y-m-d H:i:s',$row->cancel_date));
		$interval = $date1->diff($date2);
		
		$cancellation_policy = $ci->Common_model->getTableData('cancellation_policy',array('id'=>$row->policy))->row();
		
		$topay = $row->topay-$row->cleaning-$row->security-$row->extra_guest_price;
		
		if($cancellation_policy->list_after_status == 1)
		{
		 if($cancellation_policy->list_after_days > $interval->days)
		{
			return 0;
		}
		else
			{
				$percentage = $cancellation_policy->list_after_percentage;
			    return ($percentage/100) * $topay;
			}
		}
		else
			{
			$percentage = 100;
			return ($percentage/100) * $topay;	
		}		
	}

?>