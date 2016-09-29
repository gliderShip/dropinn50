<?php 
$this->config_data->db_config_fetch();
		
		//Manage site Status 
		if($this->config->item('site_status') == 1)
		{
			redirect('maintenance');
		}
if(isset($meta_keyword))
{
if($meta_keyword != 'mobile')
{
$this->load->view(THEME_FOLDER.'/includes/header'); 
}
}
else if($this->session->userdata('call_back') != 'mobile')
	{
		$this->load->view(THEME_FOLDER.'/includes/header'); 
	}
?>

<?php
	//Show Flash Message
	if($msg = $this->session->flashdata('flash_message'))
	{
		echo $msg;
	}
?>
<?php

	echo '<div id ="main_content">';
	$this->load->view(THEME_FOLDER.'/'.$message_element);
	echo '</div>';
?>

<script>
	$(document).ready(function(){
		$('.clsShow_Notification').fadeOut(5000);
	});
</script>
	
<?php
if(isset($meta_keyword))
{
if($message_element != 'list_your_space/view_list_your_space_next' && $message_element != 'view_search_result' && $meta_keyword != 'mobile')
{
 $this->load->view(THEME_FOLDER.'/includes/footer.php');
}
}
else if($this->session->userdata('call_back') != 'mobile') {
	if($message_element != 'list_your_space/view_list_your_space_next' && $message_element != 'view_search_result')
	{
		$this->load->view(THEME_FOLDER.'/includes/footer.php');
	}
}
?>
