<!-- Required css stylesheets -->
<style>
#results_pagination {
    float: right;
    line-height: 20px;
    margin-left: 12px;
    overflow: hidden;
    text-align: center;
}
.pagination {
    overflow: hidden;
}
#results_pagination {
    line-height: 20px;
    text-align: center;
}
#results_pagination span {
    background: url("<?php echo css_url();?>/images/pagination_bg.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
    color: #fff;
    display: inline-block;
    font-weight: bold;
    height: 20px;
    width: 20px;
}
#results_pagination a {
    background: url("<?php echo css_url();?>/images/pagination_bg.png") no-repeat scroll 100% 0 rgba(0, 0, 0, 0);
    color: #7e7d7d;
    display: inline-block;
    font-weight: bold;
    height: 20px;
    width: 20px;
}
#results_pagination a:hover {
    background: url("<?php echo css_url();?>/images/pagination_bg.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
    color: #fff;
    text-decoration: none;
}
.payout-method, .payout-year, .payout-month, .payout-listing
{
	margin-left: 8px;
    margin-top: 6px;
    background-color: #ffffff;
}
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "Date"; }
	.res_table td:nth-of-type(2):before { content: "Type" ; }
	.res_table td:nth-of-type(3):before { content: "Details "; }
	.res_table td:nth-of-type(4):before { content: "Amount "; }
	.res_table td:nth-of-type(5):before { content: "Paid Out "; }
}

</style>
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />

<!-- End of stylesheet inclusion -->
  <?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>

			<?php $this->load->view(THEME_FOLDER.'/includes/account_header'); ?>
				
			<?php $this->load->view(THEME_FOLDER.'/includes/transaction_header'); ?>
			
			<script>
			$(document).ready(function()
			{
				<?php if($result->num_rows() == 0) { ?>
					$('#export').hide();
					<?php } ?>
				$('.payout-method').change(function()
				{
					var list = $('.payout-listing').val();
					var year = $('.payout-year').val();
					var month = $('.payout-month').val();

					var data = 'payout_methods='+$(this).val()+'&listing='+list;
					
					$.ajax({
            url: "<?php echo base_url()?>account/ajax_future_transaction",            
            type: "GET",                       
            data:data,
            success: function (result) { 
            	//alert(result);  
            	if(result.length < 200)
            	{
            		$('#export').hide();
            	}
            	else
            	{
            		$('#export').show();
            	} 
            	$('.Box_Content').replaceWith(result);            
            }
           
            });
				})
				
				$('.payout-listing').change(function()
				{
					var method = $('.payout-method').val();
					var year = $('.payout-year').val();
					var month = $('.payout-month').val();

					var data = 'payout_methods='+method+'&listing='+$(this).val();
					
					$.ajax({
            url: "<?php echo base_url()?>account/ajax_future_transaction",            
            type: "GET",                       
            data:data,
            success: function (result) { 
            	//alert(result);  
            	if(result.length < 200)
            	{
            		$('#export').hide();
            	}
            	else
            	{
            		$('#export').show();
            	} 
            	$('.Box_Content').replaceWith(result);            
            }
           
            });
				})
				
				 $(".pagination a").live('click',function () {
				 
            var s = $(this);
            var r = s.html();

            if (s.attr("rel") == "next") {
                r = parseInt($("div.pagination span.current").html(), 10) + 1
            } else {
                if (s.attr("rel") == "prev") {
                    r = parseInt($("div.pagination span.current").html(), 10) - 1
                } else {
                    r = parseInt(r, 10)
                }
            }
            
            if(s.attr("class") == "last")
            {
            	r = s.attr('rel');
            }

            if (isNaN(r) || r < 1) {
                r = 1
            }
            $("#page").val(r);

            var list = $('.payout-listing').val();
					var methods = $('.payout-method').val();

					var data = 'payout_methods='+methods+'&listing='+list+'&page='+$('#page').val();
					
					$.ajax({
            url: "<?php echo base_url()?>account/ajax_future_transaction",            
            type: "GET",                       
            data:data,
            success: function (result) { 
            	//alert(result);  
            	if(result.length < 200)
            	{
            		$('#export').hide();
            	}
            	else
            	{
            		$('#export').show();
            	} 
            	$('.Box_Content').replaceWith(result);            
            }
           
            });
            
            return false
        });
				
        $('#export').click(function()
        {
        	var list = $('.payout-listing').val();
					var methods = $('.payout-method').val();
				var page = '<?php echo $this->uri->segment(4);?>';
				
				if(page == '')
				{
					page = $('#page').val();
				}
					var data = 'payout_methods='+methods+'&listing='+list+'&export=1&page='+page;
					
					window.location.href="<?php echo base_url();?>account/ajax_future_transaction?"+data;
					
					$.ajax({
            url: "<?php echo base_url()?>account/ajax_future_transaction",            
            type: "GET",                       
            data:data,
            success: function (result) {
            
            	//alert(result);  
            	//$('.Box_Content').replaceWith(result);            
            }
           
            });
        })
			})
			</script>
			<input type="hidden" name="page" id="page" value=""/>
<div id="dashboard_container">
    <div class="Box" id="View_Transaction">
    	<div class="Box_Head msgbg tran-list">
    		<!--<h2><?php echo translate("Transaction History"); ?></h2>-->
    		<select class="payout-method select_pay med_2 pe_8 colsm-4">
    			
          <option value="0">All payout methods</option>
          
          <?php
    			foreach($payout_methods->result() as $payout_method)
				{
					echo '<option value="'.$payout_method->id.'">'.$payout_method->payment_name.'</option>';
				}
    			?>
          
        </select>
        <select class="payout-listing select_pay med_2 pe_8 colsm-4">
          <option value="0">All listings</option>
          
          <?php
    			foreach($listings->result() as $list)
				{
					echo '<option value="'.$list->id.'">'.$list->title.'</option>';
				}
    			?>
           
        </select>
        <button id="export" class="btn_dash tran_but_list_img">export</button>
    		</div>
    	<div class="Box_Content">
			<?php if($result->num_rows() > 0) { ?>
            <table class="clsTable_View res_table" cellspacing="0" cellpadding="4" width="100%">
            <thead>
            <tr>
            <th><?php echo translate("Date"); ?></th>
            <th><?php echo translate("Type"); ?></th>
            <th><?php echo translate("Details"); ?></th>
            <th><?php echo translate("Amount"); ?></th>
            <th><?php echo translate("Pay To"); ?></th>
            </tr>
            </thead>
            <tbody>
            
            <?php
												foreach($result->result() as $row) {
												
												$query           = $this->Users_model->get_user_by_id($row->userby);
												$booker_name     = $query->row()->username;
												
												$query1          = $this->Users_model->get_user_by_id($row->userto);
												$hotelier_name   = $query1->row()->username;
												
												$type = $row->accept_pay;
																								
												$payto = $row->accept_pay_transaction_id; 													
												?>
            <tr>
            <td><?php echo get_user_times($row->checkout, get_user_timezone()); ?> </td>
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
            <?php } else { ?>
         <center><p><h2><?php echo translate("No Transactions."); ?></h2></p></center>
            <?php } ?>
        </div>
  	</div>
</div>