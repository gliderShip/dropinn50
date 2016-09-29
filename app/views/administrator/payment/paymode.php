<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "Commission Type"; }
	.res_table td:nth-of-type(2):before { content: "Commission Fees" ; }
	.res_table td:nth-of-type(3):before { content: "Is Active?"; }
}
</style>


<div id="Paymode">
<?php 
  echo form_open('administrator/payment/paymode');
			
			//Show Flash Message
			
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
		
				$tmpl = array (
                    'table_open'          => '<div class="col-xs-9 col-md-9 col-sm-9"><table class="table1 res_table" cellpadding="2" cellspacing="0">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr>',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );

		$this->table->set_template($tmpl); 
		
		$this->table->set_heading(translate_admin('Commission Type'), translate_admin('Commission Fees'), translate_admin('Is Active?'));
		
				foreach ($payMode->result() as $row) 
				{
					if($row->is_premium == 1)
					{
							$is_premium = 'Yes';
							$change_to  = translate_admin('Change to free mode');
					}
					else
					{
							$is_premium = 'No';
							$change_to  = translate_admin('Change to premium mode');
					}
					
					if($row->is_fixed == 1)
					{
					  $commission = $row->currency.' '.$row->fixed_amount;
					}
					else
					{
					  $commission = $row->percentage_amount. '%';
					}
					
					$change = '<a href="'.admin_url('payment/paymode/'.$row->id).'"><img src="'.base_url().'images/change.jpg" title="'.$change_to.'" alt="'.$change_to.'" /></a>';
					
					$this->table->add_row(
						form_checkbox('check[]', $row->id).'&#160;&#160;'.
						$row->mod_name,
						$commission, 
						$is_premium.'&nbsp;&nbsp;&nbsp;'.$change
						);
					}

		
		//echo form_open($this->uri->uri_string());
		echo '<div class="container-fluid top-sp body-color"><div class="container"><div class="col-xs-9 col-md-9 col-sm-9"><h1>&#160;</h1><div class="but-set"><span3>';
		echo form_submit('edit', translate_admin('Edit Commission Settings'));
		echo '</spna3></div></div>';
		
		
		echo $this->table->generate(); 
		
		echo form_close();
	?>
</div>