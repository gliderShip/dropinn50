<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "Payment Name"; }
	.res_table td:nth-of-type(2):before { content: "Is Active?" ; }
}
</style>

<div id="Manage_Gateway">
<?php 
  echo form_open('administrator/payment/manage_gateway');
			
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
		
		$this->table->set_heading(translate_admin('Payment Name'), translate_admin('Is Active?'));
		
		$notification = '';
		
		if($payments->num_rows() > 0)
		{
				foreach ($payments->result() as $row) 
				{
										
					if($row->is_enabled == 1)
					{ 
					  $change_to  = translate_admin('Click to deactive');
							$isActive   = 'Yes';
					}
					else
					{
					  $change_to  = translate_admin('Click to active');
							$isActive   = 'No';
					}
					
					$change = '<a href="'.admin_url('payment/manage_gateway/'.$row->id).'"><img src="'.base_url().'images/change.jpg" title="'.$change_to.'" alt="'.$change_to.'" /></a>';
					
					$this->table->add_row(
						form_checkbox('check[]', $row->id).'&#160;&#160;'.
						$row->payment_name, 
						$isActive.'&nbsp;&nbsp;&nbsp;'.$change
						);
				}
		}
		else
		{
		  $notification = '<p> '.translate_admin('There is no payment list.') .' '.anchor('administrator/payment',translate_admin('Click here')).' '.translate_admin('to add the first payment').'</p>';
		}
		
		//echo form_open($this->uri->uri_string());
		echo '<div class="container-fluid top-sp body-color"><div class="container"><div class="col-xs-9 col-md-9 col-sm-9"><h1>&#160;</h1>';			
		echo '<div class="but-set"><span3>';
		echo form_submit('edit', translate_admin('Edit Payment'));
		echo '</span3></div></div>';
		
		
		echo $this->table->generate(); 
		
		echo $notification;
		
		echo form_close();
	?>
	</div>
