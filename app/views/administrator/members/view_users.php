<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "Username"; }
	.res_table td:nth-of-type(2):before { content: "Email" ; }
	.res_table td:nth-of-type(3):before { content: "Role"; }
	.res_table td:nth-of-type(4):before { content: "Banned"; }
	.res_table td:nth-of-type(5):before { content: "Last IP"; }
	.res_table td:nth-of-type(6):before { content: "Last Login"; }
	.res_table td:nth-of-type(7):before { content: "Created"; }
	.res_table td:nth-of-type(8):before { content: "Edit"; }
	.res_table td:nth-of-type(9):before { content: "Change Password"; }
}  
#usersearch{
	    border-radius: 5px;
    padding: 5px 10px 5px 10px;
    float: right;
    margin: -30px 96px 0px 0px;
    width: 25%;
}
.exe-all{
	    margin-left: 2px;
}
</style>


<!-- Export CSV-->
<div id="confirm" style="background-color: #000; opacity:0.5;" onclick="document.getElementById('confirm').style.display='none';
	document.getElementById('confirmbox').style.display='none';">
	</div>
<!-- Export CSV-->
	<?php  	
				//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
						
		// Show reset password message if exist
		if (isset($reset_message))
		echo $reset_message;
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
$this->table->set_heading(translate_admin('Username'), translate_admin('Email'), translate_admin('Role'), translate_admin('Banned'), 
							translate_admin('Last IP'),translate_admin('Last login'), translate_admin('Created'),translate_admin('Edit'), 
							translate_admin('Change Password')
						);
		foreach ($users as $user) 
		{
			if($this->db->where('id',$user->id)->get('profiles')->num_rows()!=0)
			{
					if($user->role_id==2)
					{
						$role = 'admin';
					}
					else {
						$role = 'user';
					}
				if($user->last_login == 0)
				{
					$last_login = 'Not yet login';
				}	
				else {
					$last_login = get_user_times($user->last_login, get_user_timezone());
				}
					
			$banned = ($user->banned == 1) ? 'Yes' : 'No';
			
			if($user->via_login == '')
			{
				$via_login = anchor('administrator/members/changepassword/'.$user->id, translate_admin('change passwords'));
			}
 else
	{
		$via_login = 'via '.ucfirst(strtolower($user->via_login)).' login';
	}
			
			$this->table->add_row(
				form_checkbox('checkbox_'.$user->id, $user->id).'&#160;'. 
				$user->username, 
				$user->email, 
				//$user->role_name, 
				$role,			
				$banned, 
				$user->last_ip,
				$last_login, 
				get_user_times($user->created, get_user_timezone()),
				anchor('administrator/members/edit/'.$user->id, translate_admin('Edit')),
				$via_login
				);
			}
		}
		echo '<div class="container-fluid top-sp body-color"><div class="container"><div class="col-xs-10 col-md-10 col-sm-10"><h1 class="page-header2">'.translate_admin('Member Management').'</h1></div>';
		echo '<div class="col-xs-10 col-md-10 col-sm-10">'; ?>
		
		<?php
		echo '';
		echo form_open('administrator/members');
		echo '<span2a>';
		echo form_submit('add', translate_admin('Add user'));
		echo '</span2a><span2>';
		echo form_submit('ban', translate_admin('Ban user'));
		echo '</span2><span2>';
		echo form_submit('unban', translate_admin('Unban user'),'class=unban-usr');
		echo '</span2><span2>';
		echo form_submit('reset_pass', translate_admin('Reset password'),'class=reset_pass');
		echo '</span2>';
		?>
		
		<!--<span2><input class="exe-all" type="submit" onclick="window.location='#popup-box'" value="Export All Users")"/>
		</span2>-->
		<span2>
		<input class="exe-all popup-box" type="button" onclick="document.getElementById('confirmbox').style.display='block';" value="<?php echo translate_admin('Export All Users'); ?>"/>
		</span2>
		<div id="confirmbox">
			<div id="popup-box" class="">
			<div class="">
			<a class="close" onclick="document.getElementById('confirm').style.display='none';
		document.getElementById('confirmbox').style.display='none';"href="#">&times;</a>
			<div class="content">
			<p>	<?php echo translate_admin('Click here to download as .Txt file'); ?></p>
			<p class=""><input type="submit" name="export" value="Export as Txt file"></p><br>
			<p>	<?php echo translate_admin('Click here to download as .Csv file'); ?></p>
			<p><input type="submit" name="export_csv" value="Export as Csv file"></p>
			</div>
			</div>
			</div>
		</div>
		
		<!---<input class="exe-all popup-box" type="submit" onclick="document.getElementById('confirm').style.display='block'; 
		document.getElementById('confirmbox').style.display='block';" value="<?php echo translate_admin('Export All Users'); ?>"/>
		<?php
		echo '</span2>';
		?>-->
				
		<?php 
		echo "</span2a>".form_close();
		echo '<form name="myform" action="'.site_url('administrator/members/getusers').'" method="post">'; ?>
		<!-- //echo '<b>Search Username / Email</b><input type="text" name="usersearch" id="usersearch" style="margin:0 10px; height:23px;">'; -->		
		<span2><input type="text" name="usersearch" id="usersearch" placeholder="Search UserName / Email"></span2>
	
		<!--  echo '<input type="submit" name="search" value="Search">'; -->
		<span2b><input class="span2bc" type="submit" name="search" value="<?php echo translate_admin('Search'); ?>"></span2b>
		</div>
		<div class="col-md-10 col-sm-10 col-xs-10">
		<?php echo "<b>".translate_admin('Note:')."</b>"."<span style=color:green;>".translate_admin('Select_atleast_user')."</span>";?>
		</div>
				
<!-- Export CSV-->
		
		<!-- Export CSV-->
		<?php


echo "</form>";
		
		echo $this->table->generate(); 
		
		
		/*if(!($this->uri->segment(3,0)))
		{*/
		 echo $pagination;
		//}
			
	?>
	

	<!--<div class="popup-box-click">
		<div id="confirmbox" >
	<a id="confirmclose" href="javascript:void();" onclick="document.getElementById('confirm').style.display='none';
		document.getElementById('confirmbox').style.display='none';">
		<img src="<?php echo site_url();?>images/fancy_close.png">
	</a>-->
		


		
		
		
		
		