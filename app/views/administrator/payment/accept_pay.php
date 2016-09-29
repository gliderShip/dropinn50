
<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "List Name" ; }
	.res_table td:nth-of-type(3):before { content: "Host Name (ID)"; }
	.res_table td:nth-of-type(4):before { content: "Commission"; }
}
</style>

<div id="Reservation_List">

	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Reservation Accept Commission'); ?></h1>
	 </div>

<?php  				
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
		
		// Show error
		echo validation_errors();
		
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
		
		$this->table->set_heading(translate_admin('S.No'), translate_admin('List Name'), translate_admin('Host Name(ID)'), translate_admin('Commission'));
		if($result->num_rows() > 0)
		{
  $i = 1;
		foreach ($result->result() as $row) 
		{
			foreach($result_currency->result() as $row_currency)
			{
				$currency[] = $row_currency->currency;
			}
			$this->table->add_row(
				$i,
				$row->title,
				$row->username.'('.$row->id.')', 
				$currency[$i-1] .' '.round($row->amount)
				);
				$i++;
			}
		}
		else
		{
		$this->table->add_row(
		'',
		translate_admin('There is no host listing commission yet !'),
		''
		);
		
		}
		
		
		echo $this->table->generate(); 
				
		echo $pagination;
			
	?>
	</div>
