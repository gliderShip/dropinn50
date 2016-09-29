<style>

@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S.No"; }
	.res_table td:nth-of-type(2):before { content: "Username" ; }
	.res_table td:nth-of-type(3):before { content: "Email"; }
	.res_table td:nth-of-type(4):before { content: "Is Sper Host?"; }
}  
</style>
<script>
	$(document).ready(function(){
					$("#fade").delay(1000).fadeOut('slow');
		});
</script>
<?php
	

	if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
?>
<div class="container-fluid top-sp body-color"><div class="container">
<div class="col-xs-10 col-md-10 col-sm-10"><h1 class="page-header2">Super Host Management
</h1>
</div>

<style type="text/css">
.su-class
{
	margin:10px 0px 0px 45px;
}
.text-class
{
	margin:10px 0px 0px 25px;
	width:115% !important;
}
.search-sp
{
	margin-top:10px;
	margin-left:30px;
}
.up-class
{
	margin-top:10px;
}
@media only screen and (min-device-width : 290px) and (max-device-width : 640px)
{
.su-class
{
	margin:10px 0px 0px 0px;
}
.text-class
{
	margin:10px 0px 0px 0px;
	width:115% !important;
}
.search-sp
{
	margin-top:10px;
	margin-left:0px;
}
.up-class
{
	margin-top:10px;
}
	
}
</style>
<div class="col-xs-10 col-md-10 col-sm-10">
	<?php
		echo '';
		echo form_open('administrator/superhost');
		echo '<div class="row"><div style="float:right;" class="col-md-2 col-sm-4 col-xs-6">';
		echo '<input class="search-sp" type="submit" name="Search" value="Search">';
		echo '</div><div style="float:right;" class="col-md-2 col-sm-4 col-xs-6">';
		echo '<input class="text-class" type="text" name="search">';
		echo '</div><div style="float:left;" class="col-md-2 col-sm-4 col-xs-12">';
		echo '<input class="up-class" type="submit" name="Accept" value="Update As SuperHost" style="" >';
		echo '</div><div style="float:left;" class="col-md-2 col-sm-4 col-xs-12">';
		echo '<input class="su-class" type="submit" name="Decline" value="Remove from Super Host" style="" >';
		
		//echo form_submit('Search', translate_admin('Search Super Host'));
		echo '</div></div>';	
		echo validation_errors();
		echo '</div>';
		$tmpl = array (
                    'table_open'          => '<div class="col-md-10 col-sm-10 col-xs-10"><table class="table1 res_table"   border="0" cellpadding="4" cellspacing="0">',

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
$this->table->set_heading(translate_admin('S.No'),translate_admin('Username'), translate_admin('Email'),translate_admin('Is Super Host?')
						);
						if($searched){
								$superid=$searched;
							}
						if($superid){
							
						foreach($superid as $id){
		$this->db->select('username,email,superhost')->where('id',$id);
		$users=$this->db->get('users')->row();
		
		if($users->superhost==1){
				$super='yes';
				}
		else{
				$super='No';
		}
						
							$this->table->add_row(
				form_checkbox('checkbox_',$id).$id.'&#160;', 
				$users->username, 
				$users->email, 
				$super				
				);
				}
				}
else{
	$this->table->add_row(
				'No Super Host Found'		
				);
				
				
	
}	
						
echo $this->table->generate(); 
	echo form_close();
		?>
		
</div>

</div>