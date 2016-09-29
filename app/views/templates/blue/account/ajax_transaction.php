    	<div class="Box_Content">
			<?php if($result->num_rows() > 0) { ?>
            <table class="clsTable_View" cellspacing="0" cellpadding="4" width="100%">
            <thead>
            <tr>
            <th><?php echo translate("Date"); ?></th>
            <th><?php echo translate("Type"); ?></th>
            <th><?php echo translate("Details"); ?></th>
            <th><?php echo translate("Amount"); ?></th>
            <?php
            if($status == 'future')
			{
				?>
				<th><?php echo translate("Pay To"); ?></th>
				<?php
			}
			else {
				?>
				<th><?php echo translate("Paid Out"); ?></th>
				<?php
				}
            ?>
            
            </tr>
            </thead>
            <tbody>
            
            <?php
												foreach($result->result() as $row) {
																								
												$type = $row->accept_pay;
													
												if($status == 'future')
												{	
												$payto = $row->accept_pay_transaction_id; 
												}
												else
													{
													 $payto = urldecode($row->payout_id);
													}
													
													if(isset($row->created))
													{
														$created = get_user_times($row ->created, get_user_timezone());
													}
													else
														{
															$created = get_user_times($row ->checkout, get_user_timezone());
														}
														
														/*if(isset($row->payout_id))
														{
															$payout_id = urldecode($row->payout_id);
														}
														else
															{
																$payout_id = 'PayPal';
															}
															
													if($row->accept_pay == 0)
													{
														
													}*/
													
												?>
            <tr>
            <td><?php echo $created; ?> </td>
            <td><?php echo $type; ?> </td>
            <td><?php echo get_user_times($row->checkin, get_user_timezone()).' - '.get_user_times($row->checkout, get_user_timezone()).'<br>'.$row->title; ?> </td>
            <td><?php
            if(is_numeric($row->topay))
			{ 
			echo get_currency_symbol($row->list_id).get_currency_value_lys($row->currency,get_currency_code(),$row->topay);
			}
			else
			{ 
			echo get_currency_symbol($row->list_id).$row->topay;
			}
			
            ?> </td>
            
			<td><?php echo $payto; ?>
            </tr>
            <?php } ?>
            
            </tbody>
            </table>
            <div id="results_pagination" class="clsFloatRight">
            	<?php
            	echo $pagination;
            	?>
            </div>
            <?php //echo $pagination;
				} else {
 ?>
         <center><p><h2><?php echo translate("No Transactions."); ?></h2></p></center>
            <?php } ?>
        </div>