<style type="text/css">
.paging-nav a:hover {
    background-color: #000;
    color: #FFF;
}
.paging-nav {
    float: right;
    margin: 10px 0px;
}
.paging-nav a {
    background-color: #CCC;
    border: 1px solid #808080;
    color: #000;
    font: bold 11px Arial,Helvetica,sans-serif !important;
    margin-left: 3px;
    padding: 3px;
}
.but-set{
margin: 0px; 	
}
</style>

<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "List Name" ; }
	.res_table td:nth-of-type(3):before { content: "Traveller Name (ID)"; }
	.res_table td:nth-of-type(4):before { content: "Total Prices"; }
	.res_table td:nth-of-type(5):before { content: "Status"; }
	.res_table td:nth-of-type(6):before { content: "Booked Date & Time?"; }
	.res_table td:nth-of-type(7):before { content: "Options"; }
}
</style>
<!-- Pagination Script-->
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/paging.js"></script> 
<!-- Pagination JS Script End -->

<div id="Reservation_List">

	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-10 col-md-10 col-sm-10">
	 <h1 class="page-header1"><?php echo translate_admin('Pending Payments'); ?></h1>
	 <div class="but-set">
		<span3><input type="submit" onclick="window.location='<?php echo admin_url('payment/export_pending')?>'" value="<?php echo translate_admin('Export Excel File'); ?>"></span3>
		</div>
	 </div>
<?php echo form_open('administrator/payment/finance'); ?>

<?php  				
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
		
		// Show error
		echo validation_errors();
		
				$tmpl = array (
                    'table_open'          => '<div class="col-xs-10 col-md-10 col-sm-10"><table id="tableData" class="table1 res_table" cellpadding="2" cellspacing="0">
',

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
		
		$this->table->set_heading(translate_admin('S.No'.'&#160;&#160;&#160;'), translate_admin('List Name'), translate_admin('Traveller Name(ID)'), translate_admin('Total Price'), translate_admin('Status'), translate_admin('Booked Date(yyyy-mm-dd) & Time'), translate_admin('Options'));
		
		if($result->num_rows() > 0)
		{
  		$i = 1;
		foreach ($result->result() as $row) 
		{
		$query          = $this->Users_model->get_user_by_id($row->userby);
		$booker_name    = $query->row()->username;
		
			$query1        = $this->Users_model->get_user_by_id($row->userto);
	 	
	 		//$hotelier_name = $query1->row()->username;
  	        //print_r($hotelier_name);
  	        //$hotelier_id   = $query1->row()->id;
			
			if($row->is_payed == 1)
			{
			 $is_payed =  translate_admin('Yes'); 
			}
			else
			{
			 $is_payed =  translate_admin('No');
			}
			$check_list_id = $this->db->where('id',$row->list_id)->get('list');
			if($check_list_id->num_rows()!=0)
			{
			$this->table->add_row(
				form_checkbox('check[]', $row->id).'&#160;'.
				$i,
				get_list_by_id($row->list_id)->title,
				$row->username.'('.$row->userby.')', 
				$row->currency .' '.$row->price,
				$row->name,
				unix_to_human($row->book_date),
				anchor(admin_url('payment/details/'.$row->id), translate_admin('View Details'))
				);
				$i++;
			}
		}
		}
		else
		{
		$this->table->add_row(
		'',
		translate_admin('There is no reservation yet !'),
		''
		);
		
		}
		
		
		echo $this->table->generate(); 
		
		echo form_close();
		
	
			
	?>
	<script type="text/javascript">
            $(document).ready(function() {
                $('#tableData').paging({limit:15});
            });
        </script>
        <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	</div>
</div></div>