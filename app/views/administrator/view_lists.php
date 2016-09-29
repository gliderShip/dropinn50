<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "List Id"; }
	.res_table td:nth-of-type(2):before { content: "User Name" ; }
	.res_table td:nth-of-type(3):before { content: "Address"; }
	.res_table td:nth-of-type(4):before { content: "Title"; }
	.res_table td:nth-of-type(5):before { content: "Status"; }
	.res_table td:nth-of-type(6):before { content: "Capacity"; }
	.res_table td:nth-of-type(7):before { content: "Price"; }
	.res_table td:nth-of-type(8):before { content: "Featured"; }
	.res_table td:nth-of-type(9):before { content: "Action"; }
}  
</style>



<?php //echo form_open('administrator/lists/managelist'); ?>

<?php  				
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
		
		// Show error
		echo validation_errors();
		
				$tmpl = array (
                    'table_open'          => '<div class="col-md-10 col-sm-10 col-xs-10"><table class="table1 res_table" border="0" cellpadding="4" cellspacing="0">',

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

		$this->table->set_heading(translate_admin('LIST ID'), translate_admin('USER NAME'), translate_admin('ADDRESS'), translate_admin('TITLE'), translate_admin('STATUS'), translate_admin('CAPACITY'), translate_admin('PRICE'),translate_admin('Featured'),translate_admin('ACTION'));
		$num_rows1 = 0;
		foreach ($users as $user) 
		{
		if(isset($user->user_id))
		{
				$query = $this->Users_model->get_user_by_id($user->user_id);
				$username = $this->db->where('id',$user->user_id)->get('users');
				if($username->num_rows()!=0)
				{
					$num_rows1 = 1;
					$username = $username->row()->username;
				}
				else {
					$username = 'No Data';
				}
				$lys_status = $this->db->where('id',$user->id)->get('lys_status');
				
				foreach($lys_status->result() as $row)
				{
				$total_status = $row->address+$row->overview+$row->price+$row->photo+$row->calendar+$row->listing;
				$total_status = 6 - $total_status;
				}
				
				if($total_status == 0)
				{
					$total_status = 'Listed';
				}
				else {
					if($total_status != 1) $s = 's'; else $s='';
					$total_status = 'Pending ('.$total_status.' Step'.$s.')';
				}
									// Get user record
					if($query)
					{
				      
					    $user_name = $query->row()->username;
					    $this->table->add_row(
						form_checkbox('check[]', $user->id).'&#160;'.
						$user->id, 
						$username, 
						character_limiter($user->address, 20), 			
						character_limiter($user->title, 20),
						$total_status,
						$user->capacity, 
						$user->price,
						$user->is_featured==1?"Yes":"No",
						'<a href="'.admin_url("lists/managelist/?id=".$user->id).'"><img src="'.base_url().'images/edit-new.png" alt="Edit" title="Edit" /></a>
						<a href="'.admin_url("lists/managelist/delete/?id=".$user->id).'" onClick="return confirm(Are you sure want to delete??);"><img src="'.base_url().'images/Delete.png" alt="Delete" title="Delete" /></a>');
			       }
			}
		}
		
		//echo form_open($this->uri->uri_string());
			echo '<div class="container-fluid top-sp body-color"><div class="container"><div class="col-xs-10 col-md-10 col-sm-10"><h1 class="page-header2">'.translate_admin('User Listing Management').'</h1></div>';
		echo '<div class="col-xs-10 col-md-10 col-sm-10">';
		
		echo '<span2a>';
		echo form_open('administrator/lists/addlist');
		echo form_submit('add', translate_admin('Add List'));
		echo form_close();
		echo '</span2a><span2>';
		echo form_open('administrator/lists/managelist');
		echo form_submit('delete', translate_admin('Delete List'));
		echo '</span2><span2>';
		echo form_submit('featured', translate_admin('Featured List'),'class=feat_list');
		echo '</span2><span2>';
		echo form_submit('unfeatured', translate_admin('Unfeatured List'),'class=reset_pass');
		?>
	
	
	
	
		
		<?php echo '<form name="myform" action="'.base_url().'administrator/lists/keyword_search" method="post">'; ?>
		<!-- //echo '<b>Search Username / Email</b><input type="text" name="usersearch" id="usersearch" style="margin:0 10px; height:23px;">'; -->		
		<span2d><input type="text" name="keywordsearch" id="keywordsearch" placeholder="Search UserName / Email"></span2d>
	
		<!--  echo '<input type="submit" name="search" value="Search">'; -->
		<span2b><input class="span2bc" type="submit" name="search" value="<?php echo translate_admin('Search'); ?>"></span2b>
		</div>
		
		
		<?php echo $this->table->generate(); 
		
		//if(isset($num_rows))
	//	{
		if($num_rows1 == 0)
		{
			echo '<h1>No results found.</h1>';
		//}
		}
		echo form_close();
				
		echo $pagination;
			
	?>