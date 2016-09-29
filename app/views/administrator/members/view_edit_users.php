<style type="text/css">
.img-popup {
  position: relative;
  /*left: 250px;*/
}
.img-popup .img-popup-hover {
  width:auto;
  display: none;
  position: absolute;
  padding: 10px;
  left: 7%;
}
.img-popup:hover .img-popup-hover {
  display: block !important;
}

@media only screen and (min-device-width : 980px) and (max-device-width : 1280px)
{
.img-popup {
  position: relative;
  /*left: 250px;*/
}
.img-popup .img-popup-hover {
  width:auto;
  display: none;
  position: absolute;
  padding: 10px;
  left: 12%;
}
.img-popup:hover .img-popup-hover {
  display: block !important;
}
}

@media only screen and (min-device-width : 800px) and (max-device-width : 979px)
{
.img-popup {
  position: relative;
  /*left: 250px;*/
}
.img-popup .img-popup-hover {
  width:auto;
  display: none;
  position: absolute;
  padding: 10px;
  left: 12%;
}
.img-popup:hover .img-popup-hover {
  display: block !important;
}
}

@media only screen and (min-device-width : 360px) and (max-device-width : 640px)
{
.img-popup {
  position: relative;
  /*left: 250px;*/
}
.img-popup .img-popup-hover {
  width:auto;
  display: none;
  position: absolute;
  padding: 10px;
  left: 25%;
}
.img-popup:hover .img-popup-hover {
  display: block !important;
}
}

@media only screen and (min-device-width : 320px) and (max-device-width : 358px)
{
.img-popup {
  position: relative;
  /*left: 250px;*/
}
.img-popup .img-popup-hover {
  width:auto;
  display: none;
  position: absolute;
  padding: 10px;
  left: 30%;
}
.img-popup:hover .img-popup-hover {
  display: block !important;
}
}

</style>
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
<h1 class="page-header1"><?php echo translate_admin('Edit Users'); ?></h1></div>
<?php 
$user_id = $this->uri->segment(4,0);
$query   = $this->db->get_where('profiles' , array('id' =>$user_id));
$q       = array();	
$q       = $query->result();


$email_id = $this->db->get_where('users' , array('id' => $user_id))->row()->email;
$username = $this->db->get_where('users' , array('id' => $user_id))->row()->username;
$timezone = $this->db->get_where('users' , array('id' => $user_id))->row()->timezone;

// flash message

	if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
			
// flash message


?>
<form action="<?php echo admin_url('members/edit/'.$user_id ); ?>" method="post" name="user_edit">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
<td><?php echo translate_admin("Name"); ?></td>
<td>
<input id="user_first_name" name="Fname" size="30" type="text" value="<?php if($q[0]->Fname) echo $q[0]->Fname; else echo '""'; ?>" />
<input style="margin:15px 0px 0 0;" id="user_last_name" name="Lname" size="30" type="text" value="<?php if($q[0]->Lname) echo $q[0]->Lname; else echo '""'; ?>" /></td>
</tr>

<tr>
<td>
<?php echo translate_admin("Username"); ?></td>
<td>
<input id="user_email" name="username" size="30" type="text" value="<?php echo $username ; ?>" readonly="readonly"/>
</td>
</tr>

<tr>
<td>
<?php echo translate_admin("Email"); ?></td>
<td>
<input id="user_email" name="email" size="30" type="text" value="<?php echo $email_id ; ?>" readonly="readonly"/>
</td>
</tr>
<tr>
<td><?php echo translate_admin("Where You Live"); ?></td>
<td><input id="user_profile_info_current_city" name="live" value="<?php if($q[0]->live) echo $q[0]->live; else echo ''; ?>" size="30" type="text" /><br /><span style="color:#9c9c9c; text-style:italic; font-size:11px;">e.g. Paris, FR / Brooklyn, NY / Chicago, IL</span><br /></td>
</tr>
																																														
<tr>
<td><?php echo translate_admin("Work"); ?></td>
<td>
<input id="user_profile_info_employer" name="work" size="30" type="text" value="<?php if($q[0]->live) echo $q[0]->work; else echo '""'; ?>" />
</td>
</tr>

<tr>
<td valign="top"><?php echo translate_admin("Phone Number"); ?></td>
<td>

<input autocomplete="off" class="private_lock" id="user_phone" name="phnum" size="30" type="text" value=<?php if($q[0]->phnum) echo $q[0]->phnum; else echo '""'; ?> />
<input id="user_phone_country" name="phcountry" type="hidden" />

</td>
</tr>
<tr>
<td valign="top"><?php echo translate_admin("Time Zone"); ?></td>
<td> <?php echo timezone_menu($timezone); ?>  </td>
</tr>
										
<tr>
<td valign:top;"><?php echo translate_admin("Describe Yourself"); ?></td>
<td><textarea cols="40" id="user_profile_info_about" name="desc" rows="20" style="width:250px;height:200px;"><?php if($q[0]->describe) echo $q[0]->describe; ?></textarea></td>
</tr>   
<!--ID verification 1 start -->
<tr>
<td valign:top;"><?php echo translate_admin("ID status"); ?></td>
<td><input type="radio" name="check" id="check0" value="0" <?php  if($status == 0)  echo "checked='checked'";?> /> No,not verified</br>
<input type="radio" name="check" id="check1" value="1" <?php if($status == 1)  echo "checked='checked'";?> /> Yes,Verified&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;
</td>
<tr>
<td></td>
<td>	
<div class="img-popup">
<img src="<?php echo base_url().'images/'.$view; ?>" style="width:40px !important; height:40px !important;"/>
<div class="img-popup-hover">
<img src="<?php echo base_url().'images/'.$view; ?>" style="width:100% !important; height:100% !important;"/>	
</div>
</td>
<tr>
<td></td>
<td>
<div class="img-popup">
<img src="<?php echo base_url().'images/'.$viewf; ?>" style="width:40px !important; height:40px !important;" />
<div class="img-popup-hover">
<img src="<?php echo base_url().'images/'.$viewf; ?>" style="width:100% !important; height:100% !important;"/>	
</div>
</div>
</td>
</tr>

<!--ID verification 1 end -->
<tr>
<td>
Name
</td>
<td>
<input type="text" name="emergency_name" size="30" value="<?php if(isset($q[0]->emergency_name)) echo $q[0]->emergency_name; else echo '""'; ?>"/>
</td>
</tr> 
<tr>
<td>
Phone
</td>
<td>
<input type="text" name="emergency_phone" value="<?php if(isset($q[0]->emergency_phone)) echo $q[0]->emergency_phone; else echo '""'; ?>" size="30"/>
</td>
</tr> 
<tr>
<td>
Email
</td>
<td>
<input type="text" name="emergency_email" value="<?php if(isset($q[0]->emergency_email)) echo $q[0]->emergency_email; else echo '""'; ?>" size="30"/>
</td>
</tr> 
<tr>
<td>
Relationship
</td>
<td>
<input type="text" name="emergency_relation" value="<?php if(isset($q[0]->emergency_relation)) echo $q[0]->emergency_relation; else echo '""'; ?>" size="30"/>
</td>
</tr> 
<tr>
<td></td>
<td><input type="submit" name="commit" value="<?php echo translate_admin("Update"); ?>"></td>
</tr>
</table>

</form>		
</div>	
<!--ID verification 2 start -

<div class="col-xs-9 col-md-9 col-sm-9">
<h1 class="page-header1"><?php echo translate_admin('ID Verification'); ?></h1></div>
	<form action="<?php echo admin_url('members/passport_verification'); ?>" name="passport" id="passport" method="post" enctype="multipart/form-data">                  		
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
	<td style="color: #E5EBF2">Nagara</td>									
	<td><input id="file1" name="file1" type="file" /> <?php echo form_error('file1'); ?>	
		<?php 
		if($view !=''){ ?>
		<a href="<?php echo base_url().'images/'.$view; ?>" target="_blank"><?php echo translate("View file"); ?></a> <a href="<?php echo admin_url('members/delete/'); ?>"><?php echo translate("Delete file"); ?></a> 
		<?php }else{}?>
	</td>
	</tr>
<tr>
	<td></td>
	<td><?php echo form_error('file1'); ?>
		<input id="file2" name="file2" type="file" /> <?php echo form_error('file2'); ?>
		<?php if($viewf!=''){ ?>
		<a href="<?php echo base_url().'images/'.$viewf; ?>" target="_blank"><?php echo translate("View file"); ?></a> <a href="<?php echo admin_url('members/deletef/'); ?>"><?php echo translate("Delete file"); ?></a> 
		<?php }?>
	</td>
</tr>
<tr>
	<td></td>
	<td><?php echo form_error('file2'); ?>
		<input type="submit" id="upload" name="upload" value="<?php echo translate("Upload photo"); ?>">
	</td>
</tr>
</table>
</form>
</div>	

<!--ID verification 1 end -->
				